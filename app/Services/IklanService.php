<?php

namespace App\Services;

use App\Models\DetailIklan;
use App\Models\Iklan;
use App\Models\IklanSuspend;
use App\Models\PemasukanPerusahaan;
use App\Models\PembayaranIklan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class IklanService
{
    /**
     * Kapasitas kuota maksimal iklan yang boleh aktif tayang bersamaan di platform.
     */
    const MAX_IKLAN_AKTIF = 15;

    /**
     * Sinkronisasi otomatis status iklan:
     * 1. Menyelesaikan iklan yang masa tayangnya telah berakhir (tanggal_akhir < today).
     * 2. Membatasi jumlah iklan aktif agar maksimal 15 (surplus dialihkan ke status Pending / menunggu giliran).
     * 3. Mengaktifkan iklan Pending yang sudah mencapai tanggal mulai jika slot tersedia (< 15).
     * 4. Menghapus otomatis transaksi iklan draft yang belum dibayar lebih dari 1 hari (24 jam).
     *
     * @return array Ringkasan hasil sinkronisasi
     */
    public static function autoSyncStatusIklan(): array
    {
        $today = Carbon::today();
        $expiredCount = 0;
        $demotedCount = 0;
        $promotedCount = 0;
        $deletedUnpaidCount = 0;

        try {
            // -------------------------------------------------------------
            // STEP 1: Selesaikan iklan yang tanggal_akhir sudah lewat
            // -------------------------------------------------------------
            $expiredAds = DetailIklan::whereIn('status_iklan', ['Aktif', 'Pending', 'Nonaktif'])
                ->whereDate('tanggal_akhir', '<', $today)
                ->get(['id', 'id_iklan']);

            if ($expiredAds->isNotEmpty()) {
                $expiredCount = $expiredAds->count();
                $expiredDetailIds = $expiredAds->pluck('id')->filter()->unique()->values()->all();
                $expiredIklanIds = $expiredAds->pluck('id_iklan')->filter()->unique()->values()->all();

                DetailIklan::whereIn('id', $expiredDetailIds)->update(['status_iklan' => 'Selesai']);
                if (!empty($expiredIklanIds)) {
                    Iklan::whereIn('id', $expiredIklanIds)->update(['status' => 'expired']);
                }
            }

            // -------------------------------------------------------------
            // STEP 2: Batasi iklan aktif maksimal 15 iklan saja
            //         Jika ada lebih dari 15, sisanya dialihkan ke Pending
            //         Prioritas: tanggal_mulai ASC, created_at ASC
            // -------------------------------------------------------------
            $activeAds = DetailIklan::where('status_iklan', 'Aktif')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_akhir', '>=', $today)
                ->orderBy('tanggal_mulai', 'asc')
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get(['id', 'id_iklan']);

            if ($activeAds->count() > self::MAX_IKLAN_AKTIF) {
                $surplusAds = $activeAds->slice(self::MAX_IKLAN_AKTIF);
                $demotedCount = $surplusAds->count();
                $surplusDetailIds = $surplusAds->pluck('id')->filter()->unique()->values()->all();
                $surplusIklanIds = $surplusAds->pluck('id_iklan')->filter()->unique()->values()->all();

                DetailIklan::whereIn('id', $surplusDetailIds)->update(['status_iklan' => 'Pending']);
                if (!empty($surplusIklanIds)) {
                    Iklan::whereIn('id', $surplusIklanIds)->update(['status' => 'inactive']);
                }
            }

            // -------------------------------------------------------------
            // STEP 3: Isi slot aktif yang tersisa jika jumlah aktif < 15
            //         Ambil dari antrian Pending (FIFO) yang jadwalnya sudah tiba
            // -------------------------------------------------------------
            $currentActiveCount = DetailIklan::where('status_iklan', 'Aktif')->count();
            if ($currentActiveCount < self::MAX_IKLAN_AKTIF) {
                $slotAvailable = self::MAX_IKLAN_AKTIF - $currentActiveCount;

                $promotedAds = DetailIklan::join('pembayaran_iklan', 'detail_iklan.id_iklan', '=', 'pembayaran_iklan.id_iklan')
                    ->where('pembayaran_iklan.status_bayar', 'lunas')
                    ->where('detail_iklan.status_iklan', 'Pending')
                    ->whereDate('detail_iklan.tanggal_mulai', '<=', $today)
                    ->whereDate('detail_iklan.tanggal_akhir', '>=', $today)
                    ->orderBy('detail_iklan.tanggal_mulai', 'asc')
                    ->orderBy('detail_iklan.created_at', 'asc')
                    ->orderBy('detail_iklan.id', 'asc')
                    ->limit($slotAvailable)
                    ->get(['detail_iklan.id', 'detail_iklan.id_iklan']);

                if ($promotedAds->isNotEmpty()) {
                    $promotedCount = $promotedAds->count();
                    $promotedDetailIds = $promotedAds->pluck('id')->filter()->unique()->values()->all();
                    $promotedIklanIds = $promotedAds->pluck('id_iklan')->filter()->unique()->values()->all();

                    DetailIklan::whereIn('id', $promotedDetailIds)->update(['status_iklan' => 'Aktif']);
                    if (!empty($promotedIklanIds)) {
                        Iklan::whereIn('id', $promotedIklanIds)->update(['status' => 'active']);
                    }
                }
            }

            // -------------------------------------------------------------
            // STEP 4: Bersihkan transaksi draft iklan belum bayar > 1 hari (24 jam)
            // -------------------------------------------------------------
            $deletedUnpaidCount = self::autoCleanupUnpaidAds(24);
        } catch (\Exception $e) {
            Log::error('Error pada IklanService::autoSyncStatusIklan: ' . $e->getMessage());
        }

        return [
            'expired_count'        => $expiredCount,
            'demoted_count'        => $demotedCount,
            'promoted_count'       => $promotedCount,
            'deleted_unpaid_count' => $deletedUnpaidCount,
            'total_active'         => DetailIklan::where('status_iklan', 'Aktif')->count(),
            'total_pending'        => DetailIklan::where('status_iklan', 'Pending')->count(),
            'max_slot'             => self::MAX_IKLAN_AKTIF,
        ];
    }

    /**
     * Hapus otomatis data transaksi draft iklan yang belum dibayar lebih dari 1 hari (24 jam).
     *
     * Kriteria pembersihan:
     * - Usia draft iklan lebih dari $hoursOld jam (default 24 jam / 1 hari)
     * - Status bayar BUKAN 'lunas' (tidak memiliki riwayat bayar yang lunas)
     * - Status iklan di detail_iklan bukan 'Aktif', 'Pending', atau 'Selesai'
     * - Status iklan di tabel iklan bukan 'active'
     *
     * @param int $hoursOld Jam batas toleransi belum bayar (default 24 jam)
     * @return int Jumlah iklan belum bayar yang dihapus
     */
    public static function autoCleanupUnpaidAds(int $hoursOld = 24): int
    {
        $deletedCount = 0;
        $threshold = Carbon::now()->subHours($hoursOld);

        try {
            // Ambil iklan yang dibuat sebelum threshold dan belum pernah lunas
            $unpaidAds = Iklan::where('created_at', '<', $threshold)
                ->where('status', '!=', 'active')
                ->whereDoesntHave('pembayaran', function ($q) {
                    $q->where('status_bayar', 'lunas');
                })
                ->whereDoesntHave('detailIklan', function ($q) {
                    $q->whereIn('status_iklan', ['Aktif', 'Pending', 'Selesai']);
                })
                ->get();

            if ($unpaidAds->isEmpty()) {
                return 0;
            }

            // Inisialisasi Midtrans config jika tersedia (untuk verifikasi settlement sebelum hapus)
            $serverKey = config('midtrans.serverKey');
            $hasMidtrans = !empty($serverKey);
            if ($hasMidtrans) {
                \Midtrans\Config::$serverKey = $serverKey;
                \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);
            }

            foreach ($unpaidAds as $iklan) {
                $pembayaran = PembayaranIklan::where('id_iklan', $iklan->id)->first();
                $detail = DetailIklan::where('id_iklan', $iklan->id)->first();

                // Double check ke Midtrans jika ada order_id
                if ($hasMidtrans && $pembayaran && !empty($pembayaran->midtrans_order_id)) {
                    try {
                        $midtransStatus = \Midtrans\Transaction::status($pembayaran->midtrans_order_id);
                        $trxStatus = strtolower($midtransStatus->transaction_status ?? '');
                        $fraudStatus = strtolower($midtransStatus->fraud_status ?? '');

                        if (in_array($trxStatus, ['settlement', 'capture', 'success']) && (empty($fraudStatus) || $fraudStatus === 'accept')) {
                            // User ternyata sudah membayar, perbarui status menjadi lunas
                            $pembayaran->update([
                                'status_bayar' => 'lunas',
                                'midtrans_transaction_status' => $trxStatus,
                                'payment_type' => $midtransStatus->payment_type ?? $pembayaran->payment_type,
                            ]);
                            $statusIklan = ($detail && Carbon::today() >= Carbon::parse($detail->tanggal_mulai)) ? 'Aktif' : 'Pending';
                            if ($detail) {
                                $detail->update(['status_iklan' => $statusIklan]);
                            }
                            $iklan->update(['status' => 'active']);

                            PemasukanPerusahaan::firstOrCreate(
                                ['deskripsi' => 'Sumber pemasukan dari layanan iklan customer (Order ID: ' . $pembayaran->midtrans_order_id . ')'],
                                [
                                    'sumber' => 'Layanan Iklan',
                                    'nominal' => $pembayaran->total_bayar ?? ($detail ? $detail->harga_iklan : 0),
                                ]
                            );

                            Log::info("IklanService: Iklan #{$iklan->id} terverifikasi lunas di Midtrans, dibatalkan dari pembersihan.");
                            continue;
                        }
                    } catch (\Exception $e) {
                        // Trx tidak ditemukan di Midtrans atau error koneksi, aman lanjut pembersihan
                    }
                }

                // Hapus berkas poster jika ada, bukan URL, dan tidak digunakan oleh iklan lain
                if (!empty($iklan->poster) && !filter_var($iklan->poster, FILTER_VALIDATE_URL)) {
                    $isShared = Iklan::where('poster', $iklan->poster)->where('id', '!=', $iklan->id)->exists();
                    if (!$isShared) {
                        $possiblePaths = [
                            public_path('assets/image/customers/advert/' . basename($iklan->poster)),
                            public_path($iklan->poster),
                        ];
                        foreach ($possiblePaths as $path) {
                            if (file_exists($path) && is_file($path)) {
                                @unlink($path);
                            }
                        }
                    }
                }

                // Hapus data relasi dan master iklan
                PembayaranIklan::where('id_iklan', $iklan->id)->delete();
                DetailIklan::where('id_iklan', $iklan->id)->delete();
                IklanSuspend::where('id_iklan', $iklan->id)->delete();
                $iklan->delete();

                $deletedCount++;
                Log::info("IklanService: Transaksi iklan draft #{$iklan->id} otomatis dihapus karena belum dibayar lebih dari {$hoursOld} jam.");
            }
        } catch (\Exception $e) {
            Log::error('Error pada IklanService::autoCleanupUnpaidAds: ' . $e->getMessage());
        }

        return $deletedCount;
    }

    /**
     * Periksa ketersediaan kuota slot aktif.
     */
    public static function isSlotAvailable(): bool
    {
        return DetailIklan::where('status_iklan', 'Aktif')->count() < self::MAX_IKLAN_AKTIF;
    }

    /**
     * Hitung sisa slot aktif yang tersedia saat ini.
     */
    public static function getRemainingSlotCount(): int
    {
        $current = DetailIklan::where('status_iklan', 'Aktif')->count();
        return max(0, self::MAX_IKLAN_AKTIF - $current);
    }
}
