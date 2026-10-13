<?php
// cspell:disable

namespace App\Http\Controllers\Api;

use App\Helpers\PhotoHelper;
use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Produk;
use App\Models\User;
use App\Services\PromoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * GET /api/store/{id_user}
     * Menampilkan profil publik sebuah toko berdasarkan id_user pemiliknya.
     * Dilengkapi dengan statistik: rating, jumlah ulasan, total produk aktif,
     * total transaksi yang sudah selesai, serta active_promo jika ada.
     */
    public function showStoreProfile($id_user)
    {
        try {
            // Ambil data user (pemilik toko atau user biasa)
            $user = User::select(
                'id',
                'name',
                'foto',
                'name_store',
                'deskripsi_toko',
                'foto_toko',
                'banner_toko',
                'created_at'
            )
                ->where('id', $id_user)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'status'  => 'error',
                    'message' => 'Pengguna tidak ditemukan.',
                ], 404);
            }

            $is_toko = !empty($user->name_store);

            $ratingInfo = null;
            $totalProdukAktif = 0;
            $totalTransaksiSelesai = 0;

            if ($is_toko) {
                // Hitung rating rata-rata & total ulasan dari seluruh produk toko ini
                $ratingInfo = DB::table('produk')
                    ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
                    ->where('produk.id_user', $id_user)
                    ->select(
                        DB::raw('IFNULL(ROUND(AVG(rating_produk.rating), 1), 0) as rating_toko'),
                        DB::raw('COUNT(rating_produk.id) as total_ulasan')
                    )
                    ->first();

                // Hitung jumlah produk yang masih aktif (memiliki stok & harga)
                $totalProdukAktif = DB::table('produk')
                    ->join('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
                    ->join('detail_variant_produk', 'variant_produk.id', '=', 'detail_variant_produk.id_variant_produk')
                    ->where('produk.id_user', $id_user)
                    ->whereNotNull('detail_variant_produk.harga_sewa')
                    ->where('detail_variant_produk.stok', '>', 0)
                    ->distinct('produk.id')
                    ->count('produk.id');

                // Hitung total transaksi yang sudah selesai untuk toko ini
                $totalTransaksiSelesai = DB::table('penyewaan')
                    ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
                    ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                    ->where('produk.id_user', $id_user)
                    ->where('penyewaan.status_penyewaan', 'Selesai')
                    ->distinct('penyewaan.id')
                    ->count('penyewaan.id');
            }

            // Ambil alamat utama (type = 1 untuk toko, atau bisa alamat utama user)
            $alamatToko = Alamat::select('provinsi', 'kota_kabupaten', 'detail_lainnya')
                ->where('id_user', $id_user)
                ->where('type', 1)
                ->first();

            // Build URL foto
            $fotoTokoUrl = null;
            if ($is_toko && $user->foto_toko && $user->foto_toko !== 'Belum Di isi') {
                $fotoTokoUrl = asset('assets/image/customers/logo_toko/' . $user->foto_toko);
            } elseif (!$is_toko && $user->foto && $user->foto !== 'Belum Di isi') {
                $fotoTokoUrl = asset('assets/image/customers/profile/' . $user->foto);
            } else {
                $fotoTokoUrl = asset('images/placeholder-image.png');
            }

            $bannerTokoUrl = ($is_toko && $user->banner_toko && $user->banner_toko !== 'Belum Di isi')
                ? asset('assets/image/customers/banner/' . $user->banner_toko)
                : null;

            // Ambil promo aktif toko (jika ada)
            $activePromo = PromoService::getActiveStorePromo($id_user);
            $activePromoData = $activePromo ? [
                'id_promo'        => (int) $activePromo->id_promo,
                'judul_promo'     => $activePromo->judul_promo,
                'deskripsi'       => $activePromo->deskripsi_promo,
                'tipe_diskon'     => $activePromo->tipe_diskon,
                'nilai_diskon'    => (float) $activePromo->nilai_diskon,
                'cakupan'         => $activePromo->cakupan,
                'tanggal_selesai' => $activePromo->tanggal_selesai ? $activePromo->tanggal_selesai->toDateTimeString() : null,
            ] : null;

            $data = [
                'id_user'                 => (int) $id_user,
                'is_toko'                 => $is_toko,
                'nama_store'              => $is_toko ? $user->name_store : $user->name,
                'store_name'              => $is_toko ? $user->name_store : $user->name,
                'deskripsi_toko'          => $is_toko ? $user->deskripsi_toko : 'Pengguna ini adalah pengguna biasa dan tidak menyewakan produk.',
                'store_desc'              => $is_toko ? $user->deskripsi_toko : 'Pengguna ini adalah pengguna biasa dan tidak menyewakan produk.',
                'foto_toko'               => $fotoTokoUrl,
                'store_logo'              => $fotoTokoUrl,
                'banner_toko'             => $bannerTokoUrl,
                'rating_toko'             => $ratingInfo ? (float) $ratingInfo->rating_toko : 0.0,
                'total_ulasan'            => $ratingInfo ? (int) $ratingInfo->total_ulasan : 0,
                'total_produk_aktif'      => (int) $totalProdukAktif,
                'total_transaksi_selesai' => (int) $totalTransaksiSelesai,
                'total_followers'         => DB::table('followers')->where('followed_id', $id_user)->count(),
                'total_following'         => DB::table('followers')->where('follower_id', $id_user)->count(),
                'is_followed_by_me'       => auth('sanctum')->id() ? DB::table('followers')->where('follower_id', auth('sanctum')->id())->where('followed_id', $id_user)->exists() : false,
                'active_promo'            => $activePromoData,
                'alamat'                  => $alamatToko ? [
                    'provinsi'      => $alamatToko->provinsi,
                    'kota'          => $alamatToko->kota_kabupaten,
                    'detail_alamat' => $alamatToko->detail_lainnya,
                ] : null,
                'tanggal_bergabung'       => $user->created_at,
            ];

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'data'    => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data profil toko.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/store/{id_user}/products
     * Menampilkan daftar produk milik toko tertentu dengan filter, sorting, dan kalkulasi promo diskon.
     *
     * Query Params (optional):
     *   - sort        : discount_desc | discount_asc | price_asc | price_desc | latest
     *   - is_promo    : 1 | 0 (atau is_discount: true/false)
     *   - category    : nama kategori produk (alias: kategori)
     *   - search      : pencarian berdasarkan nama/deskripsi produk
     */
    public function showStoreProducts(Request $request, $id_user)
    {
        try {
            // Validasi user / toko
            $user = User::select('id', 'name_store')
                ->where('id', $id_user)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'status'  => 'error',
                    'message' => 'Toko tidak ditemukan.',
                ], 404);
            }

            if (empty($user->name_store)) {
                return response()->json([
                    'success' => false,
                    'status'  => 'error',
                    'message' => 'User ini belum membuka toko.',
                ], 404);
            }

            $kategori = $request->query('kategori') ?? $request->query('category');
            $search   = $request->query('search');
            $sort     = $request->query('sort');
            $isPromo  = $request->query('is_promo') ?? $request->query('is_discount');

            // Build query produk milik toko ini
            $query = Produk::with(['foto', 'likes'])
                ->leftJoin('users', 'users.id', '=', 'produk.id_user')
                ->leftJoin('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
                ->leftJoin('detail_variant_produk', 'variant_produk.id', '=', 'detail_variant_produk.id_variant_produk')
                ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
                ->select(
                    'produk.id',
                    'produk.id as id_produk',
                    'produk.id_user as id_user',
                    'produk.nama as nama_produk',
                    'produk.kategori',
                    'produk.deskripsi',
                    'produk.status',
                    DB::raw('AVG(rating_produk.rating) as rating'),
                    DB::raw('COUNT(DISTINCT rating_produk.id) as total_disewa'),
                    DB::raw('MIN(detail_variant_produk.harga_sewa) as harga_sewa'),
                    DB::raw('(SELECT SUM(dvp.stok) FROM detail_variant_produk dvp JOIN variant_produk vp ON vp.id = dvp.id_variant_produk WHERE vp.id_produk = produk.id) as stok'),
                    'users.name_store as nama_toko',
                    'users.foto_toko as foto_toko_raw'
                )
                ->where('produk.id_user', $id_user)
                ->whereNotNull('detail_variant_produk.harga_sewa');

            // Filter berdasarkan kategori
            if ($kategori && strtolower($kategori) !== 'semua') {
                $query->where('produk.kategori', 'like', '%' . $kategori . '%');
            }

            // Filter berdasarkan pencarian nama produk
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('produk.nama', 'like', '%' . $search . '%')
                        ->orWhere('produk.deskripsi', 'like', '%' . $search . '%');
                });
            }

            $produk = $query
                ->groupBy(
                    'produk.id',
                    'produk.id_user',
                    'produk.nama',
                    'produk.kategori',
                    'produk.deskripsi',
                    'produk.status',
                    'users.id',
                    'users.name_store',
                    'users.foto_toko'
                )
                ->orderByDesc('produk.created_at')
                ->get()
                ->map(function ($item) {
                    $userId = auth('sanctum')->id();

                    // Likes
                    $item->total_likes = $item->likes ? $item->likes->count() : 0;
                    $item->is_liked    = $userId && $item->likes
                        ? $item->likes->contains('id_user', $userId)
                        : false;
                    unset($item->likes);

                    // Foto produk
                    $images = [];
                    if ($item->foto && $item->foto->count() > 0) {
                        $images = $item->foto->map(function ($f) {
                            return PhotoHelper::getPhotoUrl($f->url_foto, $f->tipe_sumber);
                        })->toArray();

                        $item->foto_array = $item->foto->map(function ($f) {
                            return [
                                'id'          => $f->id,
                                'url'         => PhotoHelper::getPhotoUrl($f->url_foto, $f->tipe_sumber),
                                'urutan'      => $f->urutan,
                                'tipe_sumber' => $f->tipe_sumber,
                            ];
                        })->toArray();
                    } else {
                        $item->foto_array = [];
                    }

                    $item->images     = $images;
                    $item->foto_depan = PhotoHelper::getThumbnailUrl($item);
                    $item->image      = $item->foto_depan; // alias untuk mobile app
                    unset($item->foto);

                    // Foto toko
                    $fotoTokoRaw      = $item->foto_toko_raw ?? null;
                    $item->foto_toko  = ($fotoTokoRaw && $fotoTokoRaw !== 'Belum Di isi')
                        ? asset('assets/image/customers/logo_toko/' . $fotoTokoRaw)
                        : asset('images/placeholder-image.png');
                    unset($item->foto_toko_raw);

                    // Normalisasi tipe data
                    $item->rating       = $item->rating !== null ? round((float) $item->rating, 1) : 0.0;
                    $item->rata_rating  = $item->rating; // alias
                    $item->harga_sewa   = $item->harga_sewa !== null ? (int) $item->harga_sewa : 0;
                    $item->total_disewa = (int) $item->total_disewa;
                    $item->total_ulasan = (int) $item->total_disewa;
                    $item->stok         = (int) $item->stok;

                    return $item;
                });

            // Aplikasikan kalkulasi diskon aktif toko secara menyeluruh
            PromoService::applyDiscountsToCollection($produk);

            // Filter produk sedang promo jika diminta
            if ($isPromo === '1' || $isPromo === 1 || $isPromo === 'true' || $isPromo === true) {
                $produk = $produk->filter(function ($p) {
                    return !empty($p->is_diskon);
                })->values();
            }

            // Pengurutan produk (Sorting)
            switch ($sort) {
                case 'discount_desc':
                    $produk = $produk->sortByDesc(function ($p) {
                        return $p->persentase_diskon ?? 0;
                    })->values();
                    break;
                case 'discount_asc':
                    $produk = $produk->filter(function ($p) {
                        return !empty($p->is_diskon);
                    })->sortBy(function ($p) {
                        return $p->persentase_diskon ?? 0;
                    })->values();
                    break;
                case 'price_asc':
                    $produk = $produk->sortBy(function ($p) {
                        return $p->harga ?? $p->harga_sewa ?? 0;
                    })->values();
                    break;
                case 'price_desc':
                    $produk = $produk->sortByDesc(function ($p) {
                        return $p->harga ?? $p->harga_sewa ?? 0;
                    })->values();
                    break;
                case 'latest':
                default:
                    // Sudah diurutkan berdasarkan produk.created_at desc dari database query
                    break;
            }

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'data'    => $produk,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengambil produk toko.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
