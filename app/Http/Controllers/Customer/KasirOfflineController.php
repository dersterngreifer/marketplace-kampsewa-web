<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\PhotoHelper;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Penyewaan;
use App\Models\DetailPenyewaan;
use App\Models\DetailVariantProduk;
use App\Models\PembayaranPenyewaan;
use App\Models\Pemasukan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class KasirOfflineController extends Controller
{
    public function __construct()
    {
        $this->middleware('cust');
    }

    public function index($id_user)
    {
        try {
            $id_user_dec = Crypt::decrypt($id_user);

            // Fetch products owned by this user/store along with photos and variants
            $produk = Produk::with(['foto', 'variants.detailVariants'])
                ->where('id_user', $id_user_dec)
                ->orderBy('nama', 'asc')
                ->get()
                ->map(function ($p) {
                    $p->thumbnail_url = PhotoHelper::getThumbnailUrl($p);
                    $totalStok = 0;
                    $minHarga = null;
                    foreach ($p->variants as $v) {
                        foreach ($v->detailVariants as $dv) {
                            $totalStok += (int) $dv->stok;
                            if ($minHarga === null || $dv->harga_sewa < $minHarga) {
                                $minHarga = (int) $dv->harga_sewa;
                            }
                        }
                    }
                    $p->total_stok = $totalStok;
                    $p->harga_mulai = $minHarga ?? 0;
                    return $p;
                });

            // Fetch recent offline orders for this store
            $offlineOrderIds = DB::table('penyewaan')
                ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
                ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                ->where('produk.id_user', $id_user_dec)
                ->where('penyewaan.tipe_pesanan', 'offline')
                ->distinct()
                ->pluck('penyewaan.id');

            $riwayatOffline = collect();
            $statsOffline = [
                'total_transaksi' => 0,
                'aktif_sekarang' => 0,
                'total_pendapatan' => 0,
            ];

            if ($offlineOrderIds->isNotEmpty()) {
                $statsOffline['total_transaksi'] = $offlineOrderIds->count();
                $statsOffline['aktif_sekarang'] = Penyewaan::whereIn('id', $offlineOrderIds)
                    ->where('status_penyewaan', 'Aktif')
                    ->count();
                $statsOffline['total_pendapatan'] = (int) PembayaranPenyewaan::whereIn('id_penyewaan', $offlineOrderIds)
                    ->where('status_pembayaran', 'Lunas')
                    ->sum('total_pembayaran');

                $orders = Penyewaan::with(['pembayaran', 'user'])
                    ->whereIn('id', $offlineOrderIds)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();

                foreach ($orders as $order) {
                    $items = DB::table('detail_penyewaan')
                        ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                        ->where('detail_penyewaan.id_penyewaan', $order->id)
                        ->select(
                            'produk.nama',
                            'detail_penyewaan.warna_produk',
                            'detail_penyewaan.ukuran',
                            'detail_penyewaan.qty',
                            'detail_penyewaan.subtotal'
                        )
                        ->get();

                    $order->items_summary = $items;
                    $order->nama_pelanggan_display = $order->nama_pelanggan_offline ?: ($order->user->name ?? 'Pelanggan Offline');
                    $order->no_hp_display = $order->no_hp_offline ?: ($order->user->nomor_telephone ?? '-');
                    $riwayatOffline->push($order);
                }
            }

            return view('customers.kasir-offline.index')->with([
                'title' => 'Kasir / Order Offline',
                'produk' => $produk,
                'id_user' => $id_user_dec,
                'riwayatOffline' => $riwayatOffline,
                'statsOffline' => $statsOffline,
            ]);
        } catch (\Exception $error) {
            Log::error('KasirOffline index error: ' . $error->getMessage());
            Alert::toast('Terjadi kesalahan memuat kasir: ' . $error->getMessage(), 'error');
            return back();
        }
    }

    public function prosesPesanan(Request $request, $id_user)
    {
        DB::beginTransaction();
        try {
            $id_user_dec = Crypt::decrypt($id_user);

            $request->validate([
                'nama_pelanggan' => 'required|string|max:255',
                'no_hp' => 'required|string|max:25',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'metode_pembayaran' => 'nullable|string|in:Cash,Transfer,QRIS',
                'jaminan_sewa' => 'nullable|string|max:255',
                'foto_jaminan' => 'nullable|image|max:3072',
                'jumlah_dibayar' => 'nullable|numeric|min:0',
                'pesan' => 'nullable|string|max:500',
                'denda' => 'nullable|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.id_produk' => 'required|exists:produk,id',
                'items.*.id_detail_variant' => 'required|exists:detail_variant_produk,id',
                'items.*.qty' => 'required|integer|min:1',
            ]);

            $denda = (int) $request->input('denda', 0);
            $metode = $request->input('metode_pembayaran', 'Cash');

            // Hitung durasi sewa (konsisten dengan TransaksiMenuController & Api\TransaksiController)
            $start = Carbon::parse($request->tanggal_mulai)->startOfDay();
            $end = Carbon::parse($request->tanggal_selesai)->startOfDay();
            $durasi = max(1, $start->diffInDays($end));

            // Cek apakah nomor HP pelanggan sudah terdaftar di aplikasi
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->no_hp);
            $existingUser = User::where('nomor_telephone', $request->no_hp)
                ->orWhere('nomor_telephone', $cleanPhone)
                ->first();

            // Buat record penyewaan
            $penyewaan = new Penyewaan();
            $penyewaan->id_user = $existingUser ? $existingUser->id : null;
            $penyewaan->tipe_pesanan = 'offline';
            $penyewaan->nama_pelanggan_offline = $request->nama_pelanggan;
            $penyewaan->no_hp_offline = $request->no_hp;
            $penyewaan->tanggal_mulai = $request->tanggal_mulai;
            $penyewaan->tanggal_selesai = $request->tanggal_selesai;
            $penyewaan->pesan = $request->filled('pesan') ? $request->pesan : 'Pesanan Kasir Offline (Walk-in)';
            $penyewaan->opsi_pengiriman = 'Ambil Ditempat';
            $penyewaan->status_penyewaan = 'Aktif'; // Langsung sewa berlangsung
            $penyewaan->save();

            // Gabungkan item dengan varian yang sama jika dipilih lebih dari 1 baris
            $groupedItems = [];
            foreach ($request->items as $item) {
                $variantId = (int) $item['id_detail_variant'];
                if (!isset($groupedItems[$variantId])) {
                    $groupedItems[$variantId] = [
                        'id_produk' => (int) $item['id_produk'],
                        'id_detail_variant' => $variantId,
                        'qty' => 0,
                    ];
                }
                $groupedItems[$variantId]['qty'] += (int) $item['qty'];
            }

            $subtotalPerHari = 0;

            // Loop items & potong stok
            foreach ($groupedItems as $item) {
                $detailVarian = DetailVariantProduk::with('variant')
                    ->lockForUpdate()
                    ->find($item['id_detail_variant']);

                if (!$detailVarian) {
                    throw new \Exception('Varian produk tidak ditemukan.');
                }

                // Pastikan produk milik toko ini
                $produkModel = Produk::where('id', $item['id_produk'])
                    ->where('id_user', $id_user_dec)
                    ->first();

                if (!$produkModel) {
                    throw new \Exception('Produk tidak valid untuk toko ini.');
                }

                if ($detailVarian->stok < $item['qty']) {
                    throw new \Exception("Stok tidak mencukupi untuk {$produkModel->nama} ({$detailVarian->variant->warna} - {$detailVarian->ukuran}). Sisa stok: {$detailVarian->stok}.");
                }

                $hargaSatuan = (int) $detailVarian->harga_sewa;
                $rowSubtotalHarian = $hargaSatuan * $item['qty'];
                $subtotalPerHari += $rowSubtotalHarian;

                // Create detail penyewaan (subtotal adalah harga per hari x qty sesuai standar sistem)
                $detailPenyewaan = new DetailPenyewaan();
                $detailPenyewaan->id_penyewaan = $penyewaan->id;
                $detailPenyewaan->id_produk = $produkModel->id;
                $detailPenyewaan->id_detail_variant_produk = $detailVarian->id;
                $detailPenyewaan->warna_produk = $detailVarian->variant->warna ?? '-';
                $detailPenyewaan->ukuran = $detailVarian->ukuran;
                $detailPenyewaan->qty = $item['qty'];
                $detailPenyewaan->harga_sewa_satuan = $hargaSatuan;
                $detailPenyewaan->subtotal = $rowSubtotalHarian;
                $detailPenyewaan->save();

                // Kurangi stok
                $detailVarian->stok -= $item['qty'];
                $detailVarian->save();
            }

            $totalSewa = $subtotalPerHari * $durasi;
            $totalBayar = $totalSewa + $denda;

            $jumlahDibayar = (int) $request->input('jumlah_dibayar', $totalBayar);
            if ($jumlahDibayar < $totalBayar) {
                $jumlahDibayar = $totalBayar;
            }
            $kembalian = max(0, $jumlahDibayar - $totalBayar);

            // Handle jaminan sewa (foto atau teks keterangan KTP/Identitas)
            $jaminanValue = $request->filled('jaminan_sewa')
                ? $request->input('jaminan_sewa')
                : 'KTP / Identitas Fisik (Ditahan di Toko)';

            if ($request->hasFile('foto_jaminan')) {
                $file = $request->file('foto_jaminan');
                $filename = time() . '_jaminan_pos.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/image/customers/jaminan/'), $filename);
                $jaminanValue = $filename;
            }

            // Create pembayaran (otomatis Lunas di Kasir)
            $pembayaran = new PembayaranPenyewaan();
            $pembayaran->id_penyewaan = $penyewaan->id;
            $pembayaran->bukti_pembayaran = 'Belum di isi';
            $pembayaran->jaminan_sewa = $jaminanValue;
            $pembayaran->jumlah_pembayaran = $jumlahDibayar;
            $pembayaran->kembalian_pembayaran = $kembalian;
            $pembayaran->biaya_admin = 0;
            $pembayaran->biaya_antar = 0;
            $pembayaran->kurang_pembayaran = 0;
            $pembayaran->total_pembayaran = $totalBayar;
            $pembayaran->metode = $metode;
            $pembayaran->jenis_transaksi = 'ambil ditempat';
            $pembayaran->status_pembayaran = 'Lunas';
            $pembayaran->save();

            // Catat Pemasukan Penyewaan untuk Toko
            if ($totalSewa > 0) {
                Pemasukan::create([
                    'id_user' => $id_user_dec,
                    'sumber' => 'Penyewaan',
                    'deskripsi' => 'Transaksi Kasir Offline - ' . $request->nama_pelanggan . ' (ID Order: ' . $penyewaan->id . ')',
                    'nominal' => $totalSewa,
                    'id_pembayaran_penyewaan' => $pembayaran->id,
                ]);
            }

            // Catat Pemasukan Denda / Tambahan jika ada
            if ($denda > 0) {
                Pemasukan::create([
                    'id_user' => $id_user_dec,
                    'sumber' => 'Denda Penyewaan',
                    'deskripsi' => 'Denda/Tambahan Kasir Offline - ' . $request->nama_pelanggan . ' (ID Order: ' . $penyewaan->id . ')',
                    'nominal' => $denda,
                    'id_pembayaran_penyewaan' => null,
                ]);
            }

            DB::commit();

            Alert::toast('Transaksi Kasir Offline berhasil diproses! Status sewa langsung Aktif & Lunas.', 'success');
            return redirect()->route('menu-transaksi.sewa-berlangsung', ['id_user' => Crypt::encrypt($id_user_dec)]);

        } catch (\Exception $error) {
            DB::rollBack();
            Log::error('KasirOffline proses error: ' . $error->getMessage());
            Alert::toast('Gagal: ' . $error->getMessage(), 'error');
            return back()->withInput();
        }
    }
}
