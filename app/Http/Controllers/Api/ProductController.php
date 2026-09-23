<?php
// cspell:disable

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\PhotoHelper;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // fungsi untuk menampilkan 2 produk rating tertinggi
    // di halaman pertama dashboard mobile
    public function produkRatingTertinggiLimit6()
    {
        // Ambil data produk dengan rata-rata rating
        $produk = Produk::with('foto')->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
            ->leftJoin('users', 'users.id', '=', 'produk.id_user')
            ->leftJoin('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
            ->leftJoin('detail_variant_produk', 'variant_produk.id', '=', 'detail_variant_produk.id_variant_produk')
            ->select(
                'produk.id',
                'produk.id as id_produk',
                'produk.id_user as id_user',
                'users.name as nama_user',
                'users.name_store as nama_toko',
                'users.foto as foto_toko',
                DB::raw('(SELECT IFNULL(ROUND(AVG(rp.rating), 1), 0) FROM rating_produk rp JOIN produk p ON p.id = rp.id_produk WHERE p.id_user = users.id) as rating_toko'),
                'produk.nama as nama_produk',
                'produk.kategori',
                'produk.foto_depan',
                'produk.foto_belakang',
                'produk.foto_kiri',
                'produk.foto_kanan',
                DB::raw('AVG(rating_produk.rating) as rata_rating'),
                DB::raw('MIN(detail_variant_produk.harga_sewa) as harga_sewa'),
                DB::raw('(SELECT SUM(stok) FROM detail_variant_produk JOIN variant_produk ON variant_produk.id = detail_variant_produk.id_variant_produk WHERE variant_produk.id_produk = produk.id) as stok')
            )
            ->whereNotNull('rating_produk.rating')
            ->whereNotNull('detail_variant_produk.harga_sewa')
            ->whereNotNull('produk.id_user')
            ->groupBy('produk.id', 'produk.id_user', 'users.id', 'users.name', 'users.name_store', 'users.foto', 'produk.nama', 'produk.kategori', 'produk.foto_depan', 'produk.foto_belakang', 'produk.foto_kiri', 'produk.foto_kanan')
            ->orderByDesc(DB::raw('AVG(rating_produk.rating)'))
            ->orderBy(DB::raw('MIN(detail_variant_produk.harga_sewa)'))
            ->limit(6)
            ->get()
            ->map(function ($item) {
                $item->foto_depan = str_starts_with($item->foto_depan ?? '', 'http') ? $item->foto_depan : PhotoHelper::getPhotoUrl($item->foto_depan, 'internal');
                $item->foto_belakang = $item->foto_belakang !== 'Belum di isi' && $item->foto_belakang !== null 
                    ? (str_starts_with($item->foto_belakang ?? '', 'http') ? $item->foto_belakang : PhotoHelper::getPhotoUrl($item->foto_belakang, 'internal'))
                    : null;
                $item->foto_kiri = $item->foto_kiri !== 'Belum di isi' && $item->foto_kiri !== null
                    ? (str_starts_with($item->foto_kiri ?? '', 'http') ? $item->foto_kiri : PhotoHelper::getPhotoUrl($item->foto_kiri, 'internal'))
                    : null;
                $item->foto_kanan = $item->foto_kanan !== 'Belum di isi' && $item->foto_kanan !== null
                    ? (str_starts_with($item->foto_kanan ?? '', 'http') ? $item->foto_kanan : PhotoHelper::getPhotoUrl($item->foto_kanan, 'internal'))
                    : null;

                $images = [];
                if ($item->foto_depan) $images[] = $item->foto_depan;
                if ($item->foto_belakang) $images[] = $item->foto_belakang;
                if ($item->foto_kiri) $images[] = $item->foto_kiri;
                if ($item->foto_kanan) $images[] = $item->foto_kanan;

                if ($item->foto && $item->foto->count() > 0) {
                    $item->foto_array = $item->foto->map(function ($f) use (&$images) {
                        $url = PhotoHelper::getPhotoUrl($f->url_foto, $f->tipe_sumber);
                        $images[] = $url;
                        return [
                            'id' => $f->id,
                            'url' => $url,
                            'urutan' => $f->urutan,
                            'tipe_sumber' => $f->tipe_sumber
                        ];
                    })->toArray();
                } else {
                    $item->foto_array = [];
                }
                
                $item->images = $images;
                unset($item->foto);

                return $item;
            });

        // Check apakah data ada
        if ($produk->isEmpty()) {
            // Jika tidak ada maka response 404
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        // Jika data ada maka response 200
        return response()->json([
            'message' => 'success',
            'data_produk' => $produk
        ], 200);
    }

    // fungsi untuk menampilkan product berdasarkan
    // kategori: tenda, pakaian, tas & sepatu, perlengkapan, semua
    // berdasarkan: rating, termurah, termahal, terdekat
    public function getProdukByFilter($kategori = 'semua')
    {
        $filter = request()->query('filter', 'semua');
        $search = request()->query('search', null);
        $hargaMin = request()->query('hargaMin', null);
        $hargaMax = request()->query('hargaMax', null);

        // Ambil data dengan join table produk, users, variant_produk, detail_variant_produk, rating_produk
        $produk = Produk::with('foto')->leftJoin('users', 'users.id', '=', 'produk.id_user')
            ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
            ->leftJoin('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
            ->leftJoin('detail_variant_produk', 'variant_produk.id', '=', 'detail_variant_produk.id_variant_produk')
            ->select(
                'produk.id',
                'produk.id as id_produk',
                'produk.id_user as id_user',
                'users.name as nama_user',
                'users.name_store as nama_toko',
                'users.foto as foto_toko',
                DB::raw('(SELECT IFNULL(ROUND(AVG(rp.rating), 1), 0) FROM rating_produk rp JOIN produk p ON p.id = rp.id_produk WHERE p.id_user = users.id) as rating_toko'),
                DB::raw('MAX(rating_produk.id) as id_rating_produk'),
                DB::raw('MAX(variant_produk.id) as id_variant_produk'),
                DB::raw('MAX(detail_variant_produk.id) as id_detail_variant_produk'),
                'produk.nama as nama_produk',
                'produk.kategori',
                'produk.foto_depan',
                'produk.foto_belakang',
                'produk.foto_kiri',
                'produk.foto_kanan',
                DB::raw('AVG(rating_produk.rating) as rata_rating'),
                DB::raw('MIN(detail_variant_produk.harga_sewa) as harga_sewa'),
                DB::raw('(SELECT SUM(stok) FROM detail_variant_produk JOIN variant_produk ON variant_produk.id = detail_variant_produk.id_variant_produk WHERE variant_produk.id_produk = produk.id) as stok')
            )
            // ->whereNotNull('rating_produk.rating') // Dikomentari agar produk tanpa rating tetap tampil
            ->whereNotNull('detail_variant_produk.harga_sewa')
            ->whereNotNull('produk.id_user');

        // Filter kategori jika bukan 'semua'
        if ($kategori !== 'semua') {
            $produk->where('produk.kategori', 'like', '%' . $kategori . '%');
        }

        // Pencarian berdasarkan nama atau deskripsi produk
        if ($search) {
            $produk->where(function ($query) use ($search) {
                $query->where('produk.nama', 'like', '%' . $search . '%')
                    ->orWhere('produk.deskripsi', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan harga minimal dan maksimal
        if ($hargaMin !== null) {
            $produk->having('harga_sewa', '>=', $hargaMin);
        }

        if ($hargaMax !== null) {
            $produk->having('harga_sewa', '<=', $hargaMax);
        }

        // Filter berdasarkan metode urutan
        switch ($filter) {
            case 'Rekomendasi':
                $produk->orderBy('rata_rating', 'desc');
                break;
            case 'Termurah':
                $produk->orderBy('harga_sewa', 'asc');
                break;
            case 'Termahal':
                $produk->orderBy('harga_sewa', 'desc');
                break;
            case 'Terbaru':
                $produk->orderBy('produk.created_at', 'desc');
                break;
            case 'Semua':
            default:
                break;
        }

        // Group by produk untuk menghindari duplikasi
        $produk->groupBy('produk.id', 'produk.id_user', 'users.id', 'users.name', 'users.name_store', 'users.foto', 'produk.nama', 'produk.kategori', 'produk.foto_depan', 'produk.foto_belakang', 'produk.foto_kiri', 'produk.foto_kanan');

        // Eksekusi query dan ambil hasil
        $data = $produk->get()
            ->map(function ($item) {
                $item->foto_depan = str_starts_with($item->foto_depan ?? '', 'http') ? $item->foto_depan : PhotoHelper::getPhotoUrl($item->foto_depan, 'internal');
                $item->foto_belakang = $item->foto_belakang !== 'Belum di isi' && $item->foto_belakang !== null 
                    ? (str_starts_with($item->foto_belakang ?? '', 'http') ? $item->foto_belakang : PhotoHelper::getPhotoUrl($item->foto_belakang, 'internal'))
                    : null;
                $item->foto_kiri = $item->foto_kiri !== 'Belum di isi' && $item->foto_kiri !== null
                    ? (str_starts_with($item->foto_kiri ?? '', 'http') ? $item->foto_kiri : PhotoHelper::getPhotoUrl($item->foto_kiri, 'internal'))
                    : null;
                $item->foto_kanan = $item->foto_kanan !== 'Belum di isi' && $item->foto_kanan !== null
                    ? (str_starts_with($item->foto_kanan ?? '', 'http') ? $item->foto_kanan : PhotoHelper::getPhotoUrl($item->foto_kanan, 'internal'))
                    : null;

                $images = [];
                if ($item->foto_depan) $images[] = $item->foto_depan;
                if ($item->foto_belakang) $images[] = $item->foto_belakang;
                if ($item->foto_kiri) $images[] = $item->foto_kiri;
                if ($item->foto_kanan) $images[] = $item->foto_kanan;

                if ($item->foto && $item->foto->count() > 0) {
                    $item->foto_array = $item->foto->map(function ($f) use (&$images) {
                        $url = PhotoHelper::getPhotoUrl($f->url_foto, $f->tipe_sumber);
                        $images[] = $url;
                        return [
                            'id' => $f->id,
                            'url' => $url,
                            'urutan' => $f->urutan,
                            'tipe_sumber' => $f->tipe_sumber
                        ];
                    })->toArray();
                } else {
                    $item->foto_array = [];
                }
                
                $item->images = $images;
                unset($item->foto);

                return $item;
            });

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ], 200);
    }

    // fungsi untuk menampilkan detial : nama, stok, harga, warna, ukuran
    // saat user clik icon keranjang produk, $parameter berdasarkan id/nama produk
    public function getDetailProdukKeranjang($parameter)
    {
        $warna = request()->query('warna');
        $ukuran = request()->query('ukuran');

        try {
            // Query untuk mendapatkan semua varian produk tanpa filter warna dan ukuran
            $all_variants = Produk::leftJoin('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
                ->leftJoin('detail_variant_produk', 'variant_produk.id', '=', 'detail_variant_produk.id_variant_produk')
                ->select(
                    'produk.id',
                'produk.id as id_produk',
                    'produk.nama as nama_produk',
                    'produk.foto_depan',
                    'variant_produk.id as id_variant_produk',
                    'variant_produk.warna',
                    'detail_variant_produk.id as id_detail_variant_produk',
                    'detail_variant_produk.ukuran',
                    'detail_variant_produk.stok',
                    'detail_variant_produk.harga_sewa'
                )
                ->where(function ($query) use ($parameter) {
                    $query->where('produk.nama', $parameter)
                        ->orWhere('produk.id', $parameter);
                })
                ->whereNotNull('produk.id_user')
                ->get()
                ->map(function ($item) {
                    $item->foto_depan = str_starts_with($item->foto_depan ?? '', 'http') ? $item->foto_depan : PhotoHelper::getPhotoUrl($item->foto_depan, 'internal');
                    return $item;
                });

            // Query untuk mendapatkan produk dan variannya dengan filter
            $filtered_query = Produk::leftJoin('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
                ->leftJoin('detail_variant_produk', function ($join) {
                    $join->on('variant_produk.id', '=', 'detail_variant_produk.id_variant_produk');
                    $join->whereNotNull('detail_variant_produk.ukuran');
                })
                ->select(
                    'produk.id',
                'produk.id as id_produk',
                    'produk.nama as nama_produk',
                    'produk.foto_depan',
                    'variant_produk.id as id_variant_produk',
                    'variant_produk.warna',
                    'detail_variant_produk.id as id_detail_variant_produk',
                    'detail_variant_produk.ukuran',
                    'detail_variant_produk.stok',
                    'detail_variant_produk.harga_sewa'
                )
                ->where('produk.id', $parameter)
                ->whereNotNull('produk.id_user');

            // Filter berdasarkan warna
            if ($warna) {
                $filtered_query->where('variant_produk.warna', 'like', '%' . $warna . '%');
            }

            // Filter berdasarkan ukuran
            if ($ukuran) {
                $filtered_query->where('detail_variant_produk.ukuran', $ukuran);
            }

            $filtered_results = $filtered_query->get()
                ->map(function ($item) {
                    $item->foto_depan = str_starts_with($item->foto_depan ?? '', 'http') ? $item->foto_depan : PhotoHelper::getPhotoUrl($item->foto_depan, 'internal');
                    return $item;
                });

            if ($all_variants->isEmpty()) {
                return response()->json([
                    'message' => 'Data tidak ditemukan!',
                ], 404);
            }

            return response()->json([
                'message' => 'success',
                'all_variants' => $all_variants,
                'filtered_results' => $filtered_results,
            ], 200);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil detail produk'], 500);
        }
    }


    // fungsi untuk get detail produk
    public function getDetailProduct($parameter)
    {
        try {
            $warna = request()->query('warna');
            $ukuran = request()->query('ukuran');

            $tb_produk = Produk::with('foto')
                ->leftJoin('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
                ->leftJoin('detail_variant_produk', 'variant_produk.id', '=', 'detail_variant_produk.id_variant_produk')
                ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
                ->leftJoin('users', 'users.id', '=', 'produk.id_user')
                ->select(
                    'produk.id',
                'produk.id as id_produk',
                    'produk.nama as nama_produk',
                    'produk.deskripsi as deskripsi_produk',
                    'produk.foto_depan',
                    'produk.foto_belakang',
                    'produk.foto_kiri',
                    'produk.foto_kanan',
                    DB::raw('MIN(detail_variant_produk.harga_sewa) as harga_sewa'),
                DB::raw('(SELECT SUM(stok) FROM detail_variant_produk JOIN variant_produk ON variant_produk.id = detail_variant_produk.id_variant_produk WHERE variant_produk.id_produk = produk.id) as stok'),
                    DB::raw('AVG(rating_produk.rating) as rating'),
                    DB::raw('COUNT(rating_produk.ulasan) as total_ulasan'),
                    'users.id as id_user',
                    'users.foto as foto_user',
                    'users.name as nama_user',
                    'users.name_store as nama_toko',
                    'users.foto as foto_toko',
                    DB::raw('(SELECT IFNULL(ROUND(AVG(rp.rating), 1), 0) FROM rating_produk rp JOIN produk p ON p.id = rp.id_produk WHERE p.id_user = users.id) as rating_toko')
                )
                ->where('produk.id', $parameter)
                ->whereNotNull('produk.id_user')
                ->groupBy(
                    'produk.id',
                    'produk.nama',
                    'produk.deskripsi',
                    'produk.foto_depan',
                    'produk.foto_belakang',
                    'produk.foto_kiri',
                    'produk.foto_kanan',
                    'users.id',
                    'users.foto',
                    'users.name',
                    'users.name_store'
                );

            if ($warna) {
                $tb_produk->where('variant_produk.warna', 'like', '%' . $warna . '%');
            }

            if ($ukuran) {
                $tb_produk->where('detail_variant_produk.ukuran', 'like', '%' . $ukuran . '%');
            }

            $all_variants = $tb_produk->get()
                ->map(function ($item) {
                    // Transform foto URLs
                    $item->foto_depan = str_starts_with($item->foto_depan ?? '', 'http') ? $item->foto_depan : PhotoHelper::getPhotoUrl($item->foto_depan, 'internal');
                    $item->foto_belakang = $item->foto_belakang !== 'Belum di isi' 
                        ? (str_starts_with($item->foto_belakang ?? '', 'http') ? $item->foto_belakang : PhotoHelper::getPhotoUrl($item->foto_belakang, 'internal'))
                        : null;
                    $item->foto_kiri = $item->foto_kiri !== 'Belum di isi' 
                        ? (str_starts_with($item->foto_kiri ?? '', 'http') ? $item->foto_kiri : PhotoHelper::getPhotoUrl($item->foto_kiri, 'internal'))
                        : null;
                    $item->foto_kanan = $item->foto_kanan !== 'Belum di isi' 
                        ? (str_starts_with($item->foto_kanan ?? '', 'http') ? $item->foto_kanan : PhotoHelper::getPhotoUrl($item->foto_kanan, 'internal'))
                        : null;

                    $images = [];
                    if ($item->foto_depan) $images[] = $item->foto_depan;
                    if ($item->foto_belakang) $images[] = $item->foto_belakang;
                    if ($item->foto_kiri) $images[] = $item->foto_kiri;
                    if ($item->foto_kanan) $images[] = $item->foto_kanan;

                    // Tambahkan array foto dari relasi
                    if ($item->foto && $item->foto->count() > 0) {
                        $item->foto_array = $item->foto->map(function ($f) use (&$images) {
                            $url = PhotoHelper::getPhotoUrl($f->url_foto, $f->tipe_sumber);
                            $images[] = $url;
                            return [
                                'id' => $f->id,
                                'url' => $url,
                                'urutan' => $f->urutan,
                                'tipe_sumber' => $f->tipe_sumber
                            ];
                        })->toArray();
                    } else {
                        $item->foto_array = [];
                    }

                    $item->images = $images;
                    unset($item->foto);

                    return $item;
                });

            if ($all_variants->isEmpty()) {
                return response()->json([
                    'message' => 'Data tidak ditemukan!',
                ], 404);
            }

            return response()->json([
                'message' => 'success',
                'detail_produk' => $all_variants,
            ], 200);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil detail produk'], 500);
        }
    }

    // Fungsi untuk mendapatkan rekomendasi pencarian (berdasarkan rating tertinggi atau random)
    public function getRekomendasiPencarian()
    {
        $produk = Produk::with('foto')->leftJoin('users', 'users.id', '=', 'produk.id_user')
            ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
            ->leftJoin('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
            ->leftJoin('detail_variant_produk', 'variant_produk.id', '=', 'detail_variant_produk.id_variant_produk')
            ->select(
                'produk.id',
                'produk.id as id_produk',
                'produk.id_user as id_user',
                'users.name as nama_user',
                'users.name_store as nama_toko',
                'users.foto as foto_toko',
                DB::raw('(SELECT IFNULL(ROUND(AVG(rp.rating), 1), 0) FROM rating_produk rp JOIN produk p ON p.id = rp.id_produk WHERE p.id_user = users.id) as rating_toko'),
                'produk.nama as nama_produk',
                'produk.kategori',
                'produk.foto_depan',
                'produk.foto_belakang',
                'produk.foto_kiri',
                'produk.foto_kanan',
                DB::raw('AVG(rating_produk.rating) as rata_rating'),
                DB::raw('MIN(detail_variant_produk.harga_sewa) as harga_sewa'),
                DB::raw('(SELECT SUM(stok) FROM detail_variant_produk JOIN variant_produk ON variant_produk.id = detail_variant_produk.id_variant_produk WHERE variant_produk.id_produk = produk.id) as stok')
            )
            ->whereNotNull('detail_variant_produk.harga_sewa')
            ->whereNotNull('produk.id_user')
            ->groupBy('produk.id', 'produk.id_user', 'users.id', 'users.name', 'users.name_store', 'users.foto', 'produk.nama', 'produk.kategori', 'produk.foto_depan', 'produk.foto_belakang', 'produk.foto_kiri', 'produk.foto_kanan')
            ->orderByDesc(DB::raw('AVG(rating_produk.rating)'))
            ->inRandomOrder()
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->foto_depan = str_starts_with($item->foto_depan ?? '', 'http') ? $item->foto_depan : PhotoHelper::getPhotoUrl($item->foto_depan, 'internal');
                $item->foto_belakang = $item->foto_belakang !== 'Belum di isi' && $item->foto_belakang !== null 
                    ? (str_starts_with($item->foto_belakang ?? '', 'http') ? $item->foto_belakang : PhotoHelper::getPhotoUrl($item->foto_belakang, 'internal'))
                    : null;
                $item->foto_kiri = $item->foto_kiri !== 'Belum di isi' && $item->foto_kiri !== null
                    ? (str_starts_with($item->foto_kiri ?? '', 'http') ? $item->foto_kiri : PhotoHelper::getPhotoUrl($item->foto_kiri, 'internal'))
                    : null;
                $item->foto_kanan = $item->foto_kanan !== 'Belum di isi' && $item->foto_kanan !== null
                    ? (str_starts_with($item->foto_kanan ?? '', 'http') ? $item->foto_kanan : PhotoHelper::getPhotoUrl($item->foto_kanan, 'internal'))
                    : null;

                $images = [];
                if ($item->foto_depan) $images[] = $item->foto_depan;
                if ($item->foto_belakang) $images[] = $item->foto_belakang;
                if ($item->foto_kiri) $images[] = $item->foto_kiri;
                if ($item->foto_kanan) $images[] = $item->foto_kanan;

                if ($item->foto && $item->foto->count() > 0) {
                    $item->foto_array = $item->foto->map(function ($f) use (&$images) {
                        $url = PhotoHelper::getPhotoUrl($f->url_foto, $f->tipe_sumber);
                        $images[] = $url;
                        return [
                            'id' => $f->id,
                            'url' => $url,
                            'urutan' => $f->urutan,
                            'tipe_sumber' => $f->tipe_sumber
                        ];
                    })->toArray();
                } else {
                    $item->foto_array = [];
                }
                
                $item->images = $images;
                unset($item->foto);

                return $item;
            });

        if ($produk->isEmpty()) {
            return response()->json(['message' => 'Data rekomendasi tidak ditemukan!'], 404);
        }

        return response()->json([
            'message' => 'success',
            'data_rekomendasi' => $produk
        ], 200);
    }

    // Fungsi khusus untuk menampilkan produk milik user itu sendiri
    public function getUserProducts()
    {
        $filterKategori = request()->query('filter');

        $query = Produk::with('foto')->leftJoin('users', 'users.id', '=', 'produk.id_user')
            ->leftJoin('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
            ->leftJoin('detail_variant_produk', 'variant_produk.id', '=', 'detail_variant_produk.id_variant_produk')
            ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
            ->select(
                'produk.id',
                'produk.id as id_produk',
                'produk.id_user as id_user',
                'users.name as nama_user',
                'users.name_store as nama_toko',
                'users.foto as foto_toko',
                DB::raw('(SELECT IFNULL(ROUND(AVG(rp.rating), 1), 0) FROM rating_produk rp JOIN produk p ON p.id = rp.id_produk WHERE p.id_user = users.id) as rating_toko'),
                'produk.nama as nama_produk',
                'produk.kategori',
                'produk.foto_depan',
                'produk.foto_belakang',
                'produk.foto_kiri',
                'produk.foto_kanan',
                DB::raw('AVG(rating_produk.rating) as rata_rating'),
                DB::raw('MIN(detail_variant_produk.harga_sewa) as harga_sewa'),
                DB::raw('(SELECT SUM(stok) FROM detail_variant_produk JOIN variant_produk ON variant_produk.id = detail_variant_produk.id_variant_produk WHERE variant_produk.id_produk = produk.id) as stok')
            )
            ->where('produk.id_user', '=', auth()->id());

        if ($filterKategori && strtolower($filterKategori) !== 'semua') {
            $query->where('produk.kategori', 'like', '%' . $filterKategori . '%');
        }

        $produk = $query->groupBy('produk.id', 'produk.id_user', 'users.id', 'users.name', 'users.name_store', 'users.foto', 'produk.nama', 'produk.kategori', 'produk.foto_depan', 'produk.foto_belakang', 'produk.foto_kiri', 'produk.foto_kanan')
            ->orderByDesc('produk.created_at')
            ->get()
            ->map(function ($item) {
                $item->foto_depan = str_starts_with($item->foto_depan ?? '', 'http') ? $item->foto_depan : PhotoHelper::getPhotoUrl($item->foto_depan, 'internal');
                $item->foto_belakang = $item->foto_belakang !== 'Belum di isi' && $item->foto_belakang !== null 
                    ? (str_starts_with($item->foto_belakang ?? '', 'http') ? $item->foto_belakang : PhotoHelper::getPhotoUrl($item->foto_belakang, 'internal'))
                    : null;
                $item->foto_kiri = $item->foto_kiri !== 'Belum di isi' && $item->foto_kiri !== null
                    ? (str_starts_with($item->foto_kiri ?? '', 'http') ? $item->foto_kiri : PhotoHelper::getPhotoUrl($item->foto_kiri, 'internal'))
                    : null;
                $item->foto_kanan = $item->foto_kanan !== 'Belum di isi' && $item->foto_kanan !== null
                    ? (str_starts_with($item->foto_kanan ?? '', 'http') ? $item->foto_kanan : PhotoHelper::getPhotoUrl($item->foto_kanan, 'internal'))
                    : null;

                $images = [];
                if ($item->foto_depan) $images[] = $item->foto_depan;
                if ($item->foto_belakang) $images[] = $item->foto_belakang;
                if ($item->foto_kiri) $images[] = $item->foto_kiri;
                if ($item->foto_kanan) $images[] = $item->foto_kanan;

                if ($item->foto && $item->foto->count() > 0) {
                    $item->foto_array = $item->foto->map(function ($f) use (&$images) {
                        $url = PhotoHelper::getPhotoUrl($f->url_foto, $f->tipe_sumber);
                        $images[] = $url;
                        return [
                            'id' => $f->id,
                            'url' => $url,
                            'urutan' => $f->urutan,
                            'tipe_sumber' => $f->tipe_sumber
                        ];
                    })->toArray();
                } else {
                    $item->foto_array = [];
                }
                
                $item->images = $images;
                unset($item->foto);

                return $item;
            });

        if ($produk->isEmpty()) {
            return response()->json(['message' => 'Anda belum memiliki produk.'], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $produk
        ], 200);
    }

    // Fungsi untuk mendapatkan semua kategori unik dari seluruh produk
    public function getAllKategori()
    {
        try {
            $kategori = Produk::select('kategori')
                ->whereNotNull('kategori')
                ->where('kategori', '!=', '')
                ->distinct()
                ->pluck('kategori');

            return response()->json([
                'message' => 'success',
                'data' => $kategori
            ], 200);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil kategori'], 500);
        }
    }
}
