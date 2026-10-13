<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PembayaranPenyewaan;
use App\Models\PengaturanPengirimanToko;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

class PengirimanController extends Controller
{
    public function __construct()
    {
        $this->middleware('cust');
    }

    private function resolveUserId($id_user)
    {
        if (is_numeric($id_user)) {
            return (int) $id_user;
        }

        try {
            return Crypt::decrypt($id_user);
        } catch (\Exception $e) {
            try {
                return Crypt::decryptString($id_user);
            } catch (\Exception $e2) {
                return session('id_user') ?: auth()->id();
            }
        }
    }

    /**
     * Halaman Pengaturan Biaya Antar & Opsi Pengiriman Toko
     */
    public function index($id_user)
    {
        $userId = $this->resolveUserId($id_user);
        $user = User::find($userId);

        $pengaturan = PengaturanPengirimanToko::firstOrCreate(
            ['id_user' => $userId],
            [
                'layanan_antar' => true,
                'biaya_antar' => 10000,
                'keterangan' => 'Pengantaran mencakup wilayah kota dan area kampus sekitar toko.',
                'minimal_transaksi_gratis_antar' => 0,
            ]
        );

        // Statistik ringkas pesanan diantar vs ambil langsung
        $totalPesananDiantar = PembayaranPenyewaan::join('penyewaan', 'pembayaran_penyewaan.id_penyewaan', '=', 'penyewaan.id')
            ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->where('produk.id_user', $userId)
            ->where(function ($q) {
                $q->where('pembayaran_penyewaan.jenis_transaksi', 'diantar')
                  ->orWhere('pembayaran_penyewaan.jenis_transaksi', 'Antar Ke Alamatmu')
                  ->orWhere('penyewaan.opsi_pengiriman', 'diantarkan')
                  ->orWhere('penyewaan.opsi_pengiriman', 'Antar Ke Alamatmu');
            })
            ->distinct('penyewaan.id')
            ->count('penyewaan.id');

        $totalPesananAmbil = PembayaranPenyewaan::join('penyewaan', 'pembayaran_penyewaan.id_penyewaan', '=', 'penyewaan.id')
            ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->where('produk.id_user', $userId)
            ->where(function ($q) {
                $q->where('pembayaran_penyewaan.jenis_transaksi', 'ambil ditempat')
                  ->orWhere('pembayaran_penyewaan.jenis_transaksi', 'Ambil Ditempat')
                  ->orWhere('penyewaan.opsi_pengiriman', 'ambil_sendiri')
                  ->orWhere('penyewaan.opsi_pengiriman', 'Ambil Ditempat');
            })
            ->distinct('penyewaan.id')
            ->count('penyewaan.id');

        // Riwayat pesanan terbaru untuk referensi toko mitra
        $pesananTerbaru = Penyewaan::join('users as penyewa', 'penyewaan.id_user', '=', 'penyewa.id')
            ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->leftJoin('pembayaran_penyewaan', 'penyewaan.id', '=', 'pembayaran_penyewaan.id_penyewaan')
            ->where('produk.id_user', $userId)
            ->select(
                'penyewaan.id as id_penyewaan',
                'penyewaan.tanggal_mulai',
                'penyewaan.tanggal_selesai',
                'penyewaan.status_penyewaan',
                'penyewaan.opsi_pengiriman',
                'penyewaan.alamat_pengiriman',
                'penyewa.name as nama_penyewa',
                'penyewa.nomor_telephone as telp_penyewa',
                'pembayaran_penyewaan.biaya_antar',
                'pembayaran_penyewaan.total_pembayaran',
                'pembayaran_penyewaan.status_pembayaran'
            )
            ->distinct('penyewaan.id')
            ->orderByDesc('penyewaan.id')
            ->limit(10)
            ->get();

        return view('customers.menu-pengiriman.index', [
            'title' => 'Pengaturan Biaya Antar | KampSewa',
            'id_user' => $id_user,
            'user' => $user,
            'pengaturan' => $pengaturan,
            'totalPesananDiantar' => $totalPesananDiantar,
            'totalPesananAmbil' => $totalPesananAmbil,
            'pesananTerbaru' => $pesananTerbaru,
        ]);
    }

    /**
     * Simpan pembaruan pengaturan biaya antar toko
     */
    public function update(Request $request, $id_user)
    {
        $userId = $this->resolveUserId($id_user);

        $request->validate([
            'layanan_antar' => 'required|in:0,1',
            'biaya_antar' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
            'minimal_transaksi_gratis_antar' => 'nullable|numeric|min:0',
        ], [
            'layanan_antar.required' => 'Status ketersediaan layanan pengantaran wajib ditentukan.',
            'biaya_antar.required' => 'Nominal biaya tarif antar toko wajib diisi.',
            'biaya_antar.numeric' => 'Nominal biaya tarif antar harus berupa angka.',
            'biaya_antar.min' => 'Nominal tarif antar minimal Rp 0.',
        ]);

        $pengaturan = PengaturanPengirimanToko::firstOrCreate(['id_user' => $userId]);

        $pengaturan->layanan_antar = (bool) $request->layanan_antar;
        $pengaturan->biaya_antar = (int) $request->biaya_antar;
        $pengaturan->keterangan = $request->keterangan;
        $pengaturan->minimal_transaksi_gratis_antar = (int) ($request->minimal_transaksi_gratis_antar ?? 0);
        $pengaturan->save();

        Alert::success('Berhasil Diperbarui', 'Tarif biaya antar toko Rp ' . number_format($pengaturan->biaya_antar, 0, ',', '.') . ' berhasil disimpan dan disinkronkan ke aplikasi mobile.');

        return redirect()->route('menu-pengiriman.index', ['id_user' => $id_user]);
    }
}
