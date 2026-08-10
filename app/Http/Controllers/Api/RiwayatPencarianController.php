<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPencarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RiwayatPencarianController extends Controller
{
    // fungsi untuk ketika user mengetikkan pencarian
    // maka kata kunci pencarian tersebut akan disimpan
    public function insert($id_user, Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'kata_kunci' => 'nullable|string|max:30',
        ]);

        if ($validasi->fails()) {
            return response()->json(['error' => $validasi->errors()], 400);
        }

        $kata_kunci = $request->kata_kunci;

        if ($kata_kunci) {
            // Cek apakah pencarian dengan keyword yang sama (mengabaikan huruf besar/kecil) sudah ada
            $existingRiwayat = RiwayatPencarian::where('id_user', $id_user)
                ->whereRaw('LOWER(kata_kunci) = ?', [strtolower($kata_kunci)])
                ->first();

            if ($existingRiwayat) {
                // Jika sudah ada, update dengan kata kunci terbaru dan perbarui waktunya
                $existingRiwayat->kata_kunci = $kata_kunci;
                $existingRiwayat->touch(); // Untuk memperbarui updated_at
                $existingRiwayat->save();
            } else {
                // Jika belum ada, insert data baru
                $table_riwayat_pencarian = new RiwayatPencarian();
                $table_riwayat_pencarian->id_user = $id_user;
                $table_riwayat_pencarian->kata_kunci = $kata_kunci;
                $table_riwayat_pencarian->save();
            }
        }

        return response()->json([
            'message' => 'success',
        ], 200);
    }

    // fungsi untuk menampilkan riwayat pencarian
    public function show($id_user)
    {
        $table_riwayat_pencarian = RiwayatPencarian::where('id_user', $id_user)
            ->select('kata_kunci')
            ->orderBy('updated_at', 'desc')
            ->get();

        if ($table_riwayat_pencarian->isEmpty()) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => "success",
            'data' => $table_riwayat_pencarian,
        ]);
    }

    public function delete($id_user)
    {
        try {
            $query_keyword = request()->query('keyword');
            $keyword = $query_keyword;

            if ($keyword) {
                RiwayatPencarian::leftJoin('users', 'users.id', '=', 'riwayat_pencarian.id_user')
                    ->where('riwayat_pencarian.id_user', $id_user)
                    ->where('riwayat_pencarian.kata_kunci', $keyword)
                    ->delete();

                return response()->json([
                    'message' => 'Data terhapus',
                ], 200);
            } else {
                RiwayatPencarian::leftJoin('users', 'users.id', '=', 'riwayat_pencarian.id_user')
                    ->where('riwayat_pencarian.id_user', $id_user)
                    ->delete();
                return response()->json([
                    'message' => 'Semua data terhapus',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
