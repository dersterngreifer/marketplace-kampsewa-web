<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Penyewaan;
use App\Models\DetailPenyewaan;
use App\Models\DetailVariantProduk;
use App\Models\PembayaranPenyewaan;
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

            // Fetch products owned by this user/store
            $produk = Produk::with(['variants.detailVariants'])->where('id_user', $id_user_dec)->get();

            return view('customers.kasir-offline.index')->with([
                'title' => 'Kasir / Order Offline',
                'produk' => $produk,
                'id_user' => $id_user_dec,
            ]);
        } catch (\Exception $error) {
            Log::error('KasirOffline index error: ' . $error->getMessage());
            Alert::toast('Terjadi kesalahan memuat kasir!', 'error');
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
                'no_hp' => 'required|string|max:20',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'items' => 'required|array',
                'items.*.id_produk' => 'required|exists:produk,id',
                'items.*.id_detail_variant' => 'required|exists:detail_variant_produk,id',
                'items.*.qty' => 'required|integer|min:1',
                'denda' => 'nullable|numeric|min:0',
            ]);

            $denda = $request->input('denda', 0);
            $total_bayar = 0;

            // Buat record penyewaan
            $penyewaan = new Penyewaan();
            $penyewaan->id_user = null; // offline user
            $penyewaan->tipe_pesanan = 'offline';
            $penyewaan->nama_pelanggan_offline = $request->nama_pelanggan;
            $penyewaan->no_hp_offline = $request->no_hp;
            $penyewaan->tanggal_mulai = $request->tanggal_mulai;
            $penyewaan->tanggal_selesai = $request->tanggal_selesai;
            $penyewaan->pesan = $request->pesan ?? 'Pesanan Kasir Offline';
            $penyewaan->status_penyewaan = 'Aktif'; // Langsung sewa berlangsung
            $penyewaan->save();

            // Loop items
            foreach ($request->items as $item) {
                $detailVarian = DetailVariantProduk::with('variant')->find($item['id_detail_variant']);
                
                if ($detailVarian->stok < $item['qty']) {
                    throw new \Exception("Stok tidak mencukupi untuk varian terpilih.");
                }

                // Hitung durasi
                $start = Carbon::parse($request->tanggal_mulai);
                $end = Carbon::parse($request->tanggal_selesai);
                $durasi = $start->diffInDays($end) + 1;
                
                $subtotal = $detailVarian->harga_sewa * $item['qty'] * $durasi;
                $total_bayar += $subtotal;

                // Create detail penyewaan
                $detailPenyewaan = new DetailPenyewaan();
                $detailPenyewaan->id_penyewaan = $penyewaan->id;
                $detailPenyewaan->id_produk = $item['id_produk'];
                $detailPenyewaan->qty = $item['qty'];
                $detailPenyewaan->warna_produk = $detailVarian->variant->warna ?? '-';
                $detailPenyewaan->ukuran = $detailVarian->ukuran;
                $detailPenyewaan->variant_ref = $detailVarian->id;
                $detailPenyewaan->save();

                // Kurangi stok
                $detailVarian->stok -= $item['qty'];
                $detailVarian->save();
            }

            $total_bayar += $denda;

            // Create pembayaran (otomatis lunas cash)
            $pembayaran = new PembayaranPenyewaan();
            $pembayaran->id_penyewaan = $penyewaan->id;
            $pembayaran->nominal = $total_bayar;
            $pembayaran->metode = 'Cash';
            $pembayaran->status_pembayaran = 'Lunas';
            $pembayaran->save();

            if ($denda > 0) {
                \App\Models\Pemasukan::create([
                    'id_user' => $id_user_dec,
                    'sumber' => 'Denda Penyewaan',
                    'deskripsi' => 'Denda/Tambahan Kasir Offline',
                    'nominal' => $denda,
                    'id_pembayaran_penyewaan' => null,
                ]);
            }

            DB::commit();

            Alert::toast('Pesanan offline berhasil dibuat!', 'success');
            return redirect()->route('menu-transaksi.sewa-berlangsung', ['id_user' => Crypt::encrypt($id_user_dec)]);

        } catch (\Exception $error) {
            DB::rollBack();
            Log::error('KasirOffline proses error: ' . $error->getMessage());
            Alert::toast('Gagal: ' . $error->getMessage(), 'error');
            return back();
        }
    }
}
