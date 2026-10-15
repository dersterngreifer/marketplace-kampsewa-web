<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\DetailIklan;
use App\Models\Iklan;
use App\Models\PemasukanPerusahaan;
use App\Models\PembayaranIklan;
use App\Models\PembayaranPenyewaan;
use App\Models\User;
use App\Services\IklanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class IklanController extends Controller
{
    public function __construct()
    {
        $this->middleware('dev');
    }

    public function index(Request $request)
    {
        // 1. Sinkronisasi otomatis status iklan:
        //    - Selesaikan otomatis iklan yang tanggal_akhir < today
        //    - Batasi iklan aktif maksimal 15 (sisanya dialihkan ke antrian Pending)
        //    - Aktifkan iklan Pending (FIFO) jika slot aktif < 15 dan tanggal_mulai sudah tiba
        $syncSummary = IklanService::autoSyncStatusIklan();

        // 2. Ambil user berdasarkan yang baru saja terdaftar
        $user_baru_terdaftar = User::select('users.*')
            ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
            ->where('users.type', 0)
            ->whereDate('users.created_at', Carbon::today())
            ->where('status_notifikasi_user.status', 'unread')
            ->orderByDesc('users.created_at')->limit(10)
            ->get();

        // 3. Count total transaksi iklan
        $get_count_total_transaksi_iklan = PembayaranIklan::all()->count();

        // 4. Count total iklan pending (antrian menunggu giliran) & suspended
        $get_count_total_iklan_pending = DetailIklan::where('status_iklan', 'Pending')->count();
        $get_count_total_iklan_suspend = DetailIklan::where('status_iklan', 'Nonaktif')->count();

        // 5. Count total iklan aktif (maksimal 15 iklan yang sedang tayang di mobile)
        $get_count_total_iklan_aktif = DetailIklan::where('status_iklan', 'Aktif')->count();

        // 6. Count total iklan selesai
        $get_count_total_iklan_selesai = DetailIklan::where('status_iklan', 'Selesai')->count();

        // 7. Total pendapatan iklan bulan ini
        $pendapatan_iklan_bulan_ini = PembayaranIklan::where('status_bayar', 'lunas')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_bayar');

        // 8. Total pajak platform bulan ini (dari transaksi penyewaan mitra-pelanggan)
        $pajak_bulan_ini = PembayaranPenyewaan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('pajak_platform');

        // Kuota slot
        $max_iklan_aktif = IklanService::MAX_IKLAN_AKTIF;
        $sisa_slot_aktif = IklanService::getRemainingSlotCount();

        // Ambil request input cari
        $cari = $request->query('cari');

        // 9. Data user iklan pending (antrian murni menunggu giliran)
        $user_pending = User::join('iklan', 'users.id', '=', 'iklan.id_user')
            ->join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->leftJoin('produk', 'iklan.id_produk', '=', 'produk.id')
            ->where('detail_iklan.status_iklan', 'Pending')
            ->select(
                'users.id as id_user',
                'users.name',
                'users.email',
                'users.foto',
                'users.name_store',
                'users.nomor_telephone',
                'iklan.judul',
                'iklan.sub_judul',
                'iklan.deskripsi',
                'iklan.poster',
                'iklan.target_type',
                'iklan.badge_promo',
                'iklan.id_produk',
                'iklan.id as id_iklan_main',
                'produk.nama as nama_produk',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.status_iklan',
                'detail_iklan.created_at',
                DB::raw('DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai) as durasi_hari'),
                'pembayaran_iklan.status_bayar',
                'pembayaran_iklan.midtrans_transaction_status',
                'pembayaran_iklan.payment_type',
                'pembayaran_iklan.metode_bayar',
                'pembayaran_iklan.total_bayar as nominal_bayar',
                'pembayaran_iklan.id as id_pembayaran_iklan'
            )
            ->orderBy('detail_iklan.tanggal_mulai', 'asc')
            ->orderBy('detail_iklan.created_at', 'asc')
            ->distinct();

        if (!empty($cari)) {
            $user_pending->where(function ($query) use ($cari) {
                $query->where('users.name', 'like', '%' . $cari . '%')
                    ->orWhere('users.name_store', 'like', '%' . $cari . '%')
                    ->orWhere('users.nomor_telephone', 'like', '%' . $cari . '%')
                    ->orWhere('users.email', 'like', '%' . $cari . '%')
                    ->orWhere('iklan.judul', 'like', '%' . $cari . '%')
                    ->orWhere('iklan.sub_judul', 'like', '%' . $cari . '%');
            });
        }

        $user_pending = $user_pending->paginate(10, ['*'], 'pending_page');

        // 9b. Data iklan yang ditangguhkan (suspend / nonaktif)
        $data_iklan_suspend = User::join('iklan', 'users.id', '=', 'iklan.id_user')
            ->join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->leftJoin('produk', 'iklan.id_produk', '=', 'produk.id')
            ->leftJoin('iklan_suspends', function ($join) {
                $join->on('iklan.id', '=', 'iklan_suspends.id_iklan')
                     ->whereRaw('iklan_suspends.id = (SELECT MAX(id) FROM iklan_suspends WHERE iklan_suspends.id_iklan = iklan.id)');
            })
            ->where('detail_iklan.status_iklan', 'Nonaktif')
            ->select(
                'users.id as id_user',
                'users.name',
                'users.email',
                'users.foto',
                'users.name_store',
                'users.nomor_telephone',
                'iklan.judul',
                'iklan.sub_judul',
                'iklan.deskripsi',
                'iklan.poster',
                'iklan.target_type',
                'iklan.badge_promo',
                'iklan.id_produk',
                'iklan.id as id_iklan_main',
                'produk.nama as nama_produk',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.status_iklan',
                'detail_iklan.created_at',
                'detail_iklan.updated_at',
                DB::raw('DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai) as durasi_hari'),
                'pembayaran_iklan.status_bayar',
                'pembayaran_iklan.midtrans_transaction_status',
                'pembayaran_iklan.payment_type',
                'pembayaran_iklan.metode_bayar',
                'pembayaran_iklan.total_bayar as nominal_bayar',
                'pembayaran_iklan.id as id_pembayaran_iklan',
                'iklan_suspends.alasan as suspend_alasan',
                'iklan_suspends.catatan as suspend_catatan',
                'iklan_suspends.bagian_revisi as suspend_bagian_revisi',
                'iklan_suspends.status_revisi as suspend_status_revisi',
                'iklan_suspends.created_at as suspend_created_at'
            )
            ->orderByDesc('detail_iklan.updated_at')
            ->distinct();

        if (!empty($cari)) {
            $data_iklan_suspend->where(function ($query) use ($cari) {
                $query->where('users.name', 'like', '%' . $cari . '%')
                    ->orWhere('users.name_store', 'like', '%' . $cari . '%')
                    ->orWhere('users.nomor_telephone', 'like', '%' . $cari . '%')
                    ->orWhere('users.email', 'like', '%' . $cari . '%')
                    ->orWhere('iklan.judul', 'like', '%' . $cari . '%')
                    ->orWhere('iklan.sub_judul', 'like', '%' . $cari . '%');
            });
        }

        $data_iklan_suspend = $data_iklan_suspend->paginate(10, ['*'], 'suspend_page');

        // 10. Data iklan selesai (arsip tayang)
        $data_iklan_selesai = User::join('iklan', 'users.id', '=', 'iklan.id_user')
            ->join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->leftJoin('produk', 'iklan.id_produk', '=', 'produk.id')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->where('detail_iklan.status_iklan', 'like', '%selesai%')
            ->select(
                'users.id as id_user',
                'users.name',
                'users.foto',
                'users.name_store',
                'iklan.judul',
                'iklan.sub_judul',
                'iklan.deskripsi',
                'iklan.poster',
                'iklan.target_type',
                'iklan.badge_promo',
                'iklan.id_produk',
                'iklan.id as id_iklan_main',
                'produk.nama as nama_produk',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.status_iklan',
                DB::raw('DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai) as durasi_hari'),
                'pembayaran_iklan.total_bayar as nominal_bayar'
            )
            ->orderByDesc('detail_iklan.tanggal_akhir')
            ->distinct()->paginate(10);
        $get_count_iklan_selesai = $data_iklan_selesai->total();

        // 11. Data iklan aktif (maksimal 15 iklan tayang)
        $data_iklan_aktif = User::join('iklan', 'users.id', '=', 'iklan.id_user')
            ->join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->leftJoin('produk', 'iklan.id_produk', '=', 'produk.id')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->where('detail_iklan.status_iklan', 'Aktif')
            ->select(
                'users.id as id_user',
                'users.name',
                'users.foto',
                'users.name_store',
                'users.email',
                'users.nomor_telephone',
                'iklan.judul',
                'iklan.sub_judul',
                'iklan.deskripsi',
                'iklan.poster',
                'iklan.target_type',
                'iklan.badge_promo',
                'iklan.id_produk',
                'iklan.id as id_iklan_main',
                'produk.nama as nama_produk',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.status_iklan',
                DB::raw('DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai) as durasi_hari'),
                'pembayaran_iklan.status_bayar',
                'pembayaran_iklan.midtrans_transaction_status',
                'pembayaran_iklan.metode_bayar',
                'pembayaran_iklan.payment_type',
                'pembayaran_iklan.total_bayar as nominal_bayar'
            )
            ->orderBy('detail_iklan.tanggal_akhir', 'asc')
            ->distinct()->limit($max_iklan_aktif)->get();

        // 12. Iklan yang akan expired dalam 3 hari ke depan
        $iklan_hampir_expired = DetailIklan::join('iklan', 'detail_iklan.id_iklan', '=', 'iklan.id')
            ->join('users', 'iklan.id_user', '=', 'users.id')
            ->leftJoin('produk', 'iklan.id_produk', '=', 'produk.id')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->where('detail_iklan.status_iklan', 'Aktif')
            ->whereBetween('detail_iklan.tanggal_akhir', [
                Carbon::today(),
                Carbon::today()->addDays(3),
            ])
            ->select(
                'users.name',
                'users.foto',
                'users.name_store',
                'users.email',
                'users.nomor_telephone',
                'iklan.judul',
                'iklan.sub_judul',
                'iklan.deskripsi',
                'iklan.poster',
                'iklan.target_type',
                'iklan.badge_promo',
                'iklan.id_produk',
                'iklan.id as id_iklan_main',
                'produk.nama as nama_produk',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.status_iklan',
                DB::raw('DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai) as durasi_hari'),
                'pembayaran_iklan.total_bayar as nominal_bayar'
            )
            ->orderBy('detail_iklan.tanggal_akhir', 'asc')
            ->get();

        return view('developers.iklan')->with([
            'title'                          => 'Iklan Customer',
            'user_baru_terdaftar'            => $user_baru_terdaftar,
            'get_count_total_transaksi_iklan' => $get_count_total_transaksi_iklan,
            'get_count_total_iklan_pending'  => $get_count_total_iklan_pending,
            'get_count_total_iklan_suspend'  => $get_count_total_iklan_suspend,
            'get_count_total_iklan_aktif'    => $get_count_total_iklan_aktif,
            'get_count_total_iklan_selesai'  => $get_count_total_iklan_selesai,
            'pendapatan_iklan_bulan_ini'     => $pendapatan_iklan_bulan_ini,
            'pajak_bulan_ini'               => $pajak_bulan_ini,
            'max_iklan_aktif'                => $max_iklan_aktif,
            'sisa_slot_aktif'                => $sisa_slot_aktif,
            'user_pending'                  => $user_pending,
            'data_iklan_suspend'            => $data_iklan_suspend,
            'cari'                          => $cari,
            'data_iklan_selesai'            => $data_iklan_selesai,
            'data_iklan_aktif'              => $data_iklan_aktif,
            'get_count_iklan_selesai'       => $get_count_iklan_selesai,
            'iklan_hampir_expired'          => $iklan_hampir_expired,
        ]);
    }

    /**
     * Admin mengaktifkan iklan secara manual.
     * Mengikuti batas kuota maksimal 15 slot iklan aktif.
     */
    public function aktivasiIklan($id_detail_iklan)
    {
        // Jalankan auto-sync terlebih dahulu
        IklanService::autoSyncStatusIklan();

        // Periksa kuota slot aktif
        if (!IklanService::isSlotAvailable()) {
            Alert::warning(
                'Slot Iklan Penuh (15/15)',
                'Saat ini kapasitas iklan aktif sudah mencapai batas maksimal ' . IklanService::MAX_IKLAN_AKTIF . ' iklan. Silakan selesaikan atau nonaktifkan salah satu iklan aktif terlebih dahulu untuk membuka slot.'
            )->persistent(true);
            return back();
        }

        $detail = DetailIklan::findOrFail($id_detail_iklan);
        $detail->update(['status_iklan' => 'Aktif']);

        // Sinkronkan status tabel iklan menjadi active (agar tampil di mobile)
        Iklan::where('id', $detail->id_iklan)->update(['status' => 'active']);

        // Selesaikan status penangguhan (suspend) jika ada
        \App\Models\IklanSuspend::where('id_iklan', $detail->id_iklan)
            ->where('status_revisi', '!=', 'selesai')
            ->update(['status_revisi' => 'selesai']);

        // Jika ada pembayaran yang belum lunas, tandai lunas
        PembayaranIklan::where('id_iklan', $detail->id_iklan)
            ->where('status_bayar', '!=', 'lunas')
            ->update([
                'status_bayar' => 'lunas',
                'midtrans_transaction_status' => 'settlement',
            ]);

        // Catat ke pemasukan perusahaan jika belum tercatat
        $pembayaran = PembayaranIklan::where('id_iklan', $detail->id_iklan)->first();
        $nominal = ($pembayaran && $pembayaran->total_bayar > 0) ? $pembayaran->total_bayar : ($detail->harga_iklan ?? 0);

        if ($nominal > 0) {
            $sudahDicatat = PemasukanPerusahaan::where('deskripsi', 'like', '%Aktivasi manual iklan #' . $detail->id_iklan . '%')
                ->where('sumber', 'Layanan Iklan')
                ->exists();

            if (!$sudahDicatat) {
                PemasukanPerusahaan::create([
                    'sumber'    => 'Layanan Iklan',
                    'deskripsi' => 'Aktivasi manual iklan #' . $detail->id_iklan . ' oleh admin - ' . now()->format('d M Y'),
                    'nominal'   => $nominal,
                ]);
            }
        }

        Alert::toast('Iklan berhasil diaktifkan / dipulihkan.', 'success');
        return back();
    }

    /**
     * Admin menonaktifkan iklan (suspend) dengan mencatat alasan dan bagian yang wajib direvisi oleh mitra.
     * Otomatis memicu promosi iklan pending jika slot terbuka.
     */
    public function nonaktifkanIklan(Request $request, $id_detail_iklan)
    {
        $request->validate([
            'alasan'         => 'required|string|max:255',
            'catatan'        => 'nullable|string|max:1000',
            'bagian_revisi'  => 'nullable|array',
        ]);

        $detail = DetailIklan::findOrFail($id_detail_iklan);
        $iklan = Iklan::findOrFail($detail->id_iklan);

        $detail->update(['status_iklan' => 'Nonaktif']);

        // Sinkronkan status tabel iklan
        $iklan->update(['status' => 'inactive']);

        // Catat alasan penangguhan ke tabel iklan_suspends
        \App\Models\IklanSuspend::create([
            'id_iklan'        => $iklan->id,
            'id_detail_iklan' => $detail->id,
            'id_user'         => $iklan->id_user,
            'alasan'          => $request->input('alasan'),
            'bagian_revisi'   => $request->input('bagian_revisi', []),
            'catatan'         => $request->input('catatan'),
            'status_revisi'   => 'menunggu_perbaikan',
        ]);

        // Jalankan sinkronisasi untuk mengisi slot dari antrian pending jika ada yang eligible
        IklanService::autoSyncStatusIklan();

        Alert::success('Iklan Ditangguhkan', 'Iklan berhasil di-suspend dan rincian perbaikan telah dikirimkan ke dashboard mitra.')->persistent(true);
        return back();
    }

    /**
     * Admin menyelesaikan iklan (mark as selesai).
     * Otomatis membuka slot untuk antrian pending berikutnya.
     */
    public function selesaikanIklan($id_detail_iklan)
    {
        $detail = DetailIklan::findOrFail($id_detail_iklan);
        $detail->update(['status_iklan' => 'Selesai']);

        // Sinkronkan status tabel iklan
        Iklan::where('id', $detail->id_iklan)->update(['status' => 'expired']);

        // Jalankan sinkronisasi untuk mengisi slot dari antrian pending jika ada yang eligible
        IklanService::autoSyncStatusIklan();

        Alert::toast('Iklan telah diselesaikan.', 'success');
        return back();
    }

    public function deleteIklanPending($id_iklan)
    {
        DetailIklan::where('id_iklan', $id_iklan)->delete();
        PembayaranIklan::where('id_iklan', $id_iklan)->delete();
        Iklan::where('id', $id_iklan)->delete();

        // Jalankan sinkronisasi ulang
        IklanService::autoSyncStatusIklan();

        Alert::toast('Iklan berhasil dihapus.', 'success');
        return back();
    }
}
