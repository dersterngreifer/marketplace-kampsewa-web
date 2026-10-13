<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengaturanPengirimanToko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PengirimanApiController extends Controller
{
    /**
     * GET /api/pengiriman/biaya-antar/{id_toko}
     * Atau GET /api/pengiriman/biaya-antar?id_toko=...
     * 
     * Mengambil tarif dan status layanan pengantaran toko mitra untuk digunakan pada checkout mobile.
     */
    public function getBiayaAntar($id_toko = null)
    {
        try {
            $idToko = $id_toko ?? request()->query('id_toko') ?? request()->query('id_user');

            if (!$idToko) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Parameter id_toko atau id_user pemilik toko wajib disertakan.',
                ], 400);
            }

            $user = User::find($idToko);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Toko mitra tidak ditemukan.',
                ], 404);
            }

            $pengaturan = PengaturanPengirimanToko::where('id_user', $idToko)->first();

            $layananAntar = $pengaturan ? (bool) $pengaturan->layanan_antar : true;
            $biayaAntar = $pengaturan ? (int) $pengaturan->biaya_antar : 0;
            $keterangan = $pengaturan && !empty($pengaturan->keterangan) 
                ? $pengaturan->keterangan 
                : 'Pengantaran perlengkapan oleh kurir toko ke alamat pemesan.';
            $minGratis = $pengaturan ? (int) $pengaturan->minimal_transaksi_gratis_antar : 0;

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan tarif pengiriman toko',
                'data' => [
                    'id_toko' => (int) $idToko,
                    'nama_toko' => $user->name_store ?? $user->name,
                    'layanan_antar' => $layananAntar,
                    'biaya_antar' => $biayaAntar,
                    'biaya_antar_format' => 'Rp ' . number_format($biayaAntar, 0, ',', '.'),
                    'keterangan' => $keterangan,
                    'minimal_transaksi_gratis_antar' => $minGratis,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error getBiayaAntar: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem saat mengambil tarif pengiriman.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/pengiriman/biaya-antar
     * 
     * Menyimpan/memperbarui tarif biaya antar toko via API.
     */
    public function setBiayaAntar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_user' => 'nullable|integer',
                'id_toko' => 'nullable|integer',
                'layanan_antar' => 'required|boolean',
                'biaya_antar' => 'required|numeric|min:0',
                'keterangan' => 'nullable|string|max:500',
                'minimal_transaksi_gratis_antar' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $idUser = $request->input('id_user') 
                ?? $request->input('id_toko') 
                ?? auth('sanctum')->id();

            if (!$idUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID mitra toko tidak ditemukan.',
                ], 400);
            }

            $pengaturan = PengaturanPengirimanToko::firstOrCreate(['id_user' => $idUser]);
            $pengaturan->layanan_antar = (bool) $request->input('layanan_antar');
            $pengaturan->biaya_antar = (int) $request->input('biaya_antar');
            $pengaturan->keterangan = $request->input('keterangan');
            $pengaturan->minimal_transaksi_gratis_antar = (int) ($request->input('minimal_transaksi_gratis_antar', 0));
            $pengaturan->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Pengaturan biaya antar toko berhasil disimpan.',
                'data' => [
                    'id_toko' => (int) $idUser,
                    'layanan_antar' => (bool) $pengaturan->layanan_antar,
                    'biaya_antar' => (int) $pengaturan->biaya_antar,
                    'biaya_antar_format' => 'Rp ' . number_format($pengaturan->biaya_antar, 0, ',', '.'),
                    'keterangan' => $pengaturan->keterangan,
                    'minimal_transaksi_gratis_antar' => (int) $pengaturan->minimal_transaksi_gratis_antar,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error setBiayaAntar: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem saat menyimpan tarif pengiriman.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
