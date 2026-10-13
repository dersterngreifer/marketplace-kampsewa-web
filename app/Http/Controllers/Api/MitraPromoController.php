<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\PromosiProduk;
use App\Models\PromosiToko;
use App\Services\PromoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MitraPromoController extends Controller
{
    /**
     * GET /api/mitra/promo atau GET /api/mitra/diskon
     * Mengambil daftar diskon produk yang dibuat oleh mitra yang sedang login.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $activeStoreWide = PromoService::getActiveStoreWidePromo($userId);

        $promos = PromosiToko::with(['produk' => function ($q) {
            $q->select('produk.id', 'produk.nama', 'produk.kategori');
        }])
            ->where('id_user', $userId)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($promo) {
                $now = Carbon::now();
                $isCurrentlyActive = $promo->is_active && $promo->status === 'active' && $promo->tanggal_mulai <= $now && $promo->tanggal_selesai >= $now;

                return [
                    'id_promo'            => $promo->id_promo,
                    'id_diskon'           => $promo->id_promo,
                    'nama_diskon'         => $promo->nama_diskon,
                    'tipe_diskon'         => $promo->tipe_diskon,
                    'nilai_diskon'        => (float) $promo->nilai_diskon,
                    'cakupan'             => $promo->cakupan,
                    'konflik_diskon'      => $promo->konflik_diskon ?? 'replace',
                    'tanggal_mulai'       => $promo->tanggal_mulai ? $promo->tanggal_mulai->toDateTimeString() : null,
                    'tanggal_selesai'     => $promo->tanggal_selesai ? $promo->tanggal_selesai->toDateTimeString() : null,
                    'is_active'           => (bool) $promo->is_active,
                    'status'              => $promo->status,
                    'is_currently_active' => $isCurrentlyActive,
                    'total_produk'        => $promo->cakupan === 'all_products' ? Produk::where('id_user', $promo->id_user)->count() : $promo->produk->count(),
                    'produk'              => $promo->produk,
                    'created_at'          => $promo->created_at ? $promo->created_at->toDateTimeString() : null,
                ];
            });

        return response()->json([
            'status'                      => 'success',
            'message'                     => 'Daftar diskon produk toko berhasil diambil',
            'has_active_store_wide_promo' => $activeStoreWide ? true : false,
            'active_store_wide_promo'     => $activeStoreWide ? [
                'id_promo'        => $activeStoreWide->id_promo,
                'nama_diskon'     => $activeStoreWide->nama_diskon,
                'nilai_diskon'    => (float) $activeStoreWide->nilai_diskon,
                'tipe_diskon'     => $activeStoreWide->tipe_diskon,
                'konflik_diskon'  => $activeStoreWide->konflik_diskon ?? 'replace',
                'tanggal_selesai' => $activeStoreWide->tanggal_selesai ? $activeStoreWide->tanggal_selesai->toDateTimeString() : null,
            ] : null,
            'data'                        => $promos,
        ], 200);
    }

    /**
     * GET /api/mitra/diskon/available-products
     * Mengambil daftar seluruh produk milik mitra beserta status kelayakan diskon:
     * - Apakah sedang terkunci dalam diskon aktif lain (is_locked)
     * - Rincian diskon aktif saat ini (current_discount)
     * - Apakah toko sedang memiliki diskon keseluruhan (has_active_store_wide_promo)
     */
    public function availableProducts(Request $request)
    {
        $userId = auth()->id();
        $excludePromoId = $request->query('exclude_promo_id');

        $activeStoreWide = PromoService::getActiveStoreWidePromo($userId, $excludePromoId);
        $activeDiscountMap = PromoService::getActiveProductsDiscountMap($userId, $excludePromoId);

        $rawProducts = Produk::with(['photoThumbnail', 'detailVariant'])
            ->where('id_user', $userId)
            ->orderBy('nama', 'asc')
            ->get();

        // Ambil daftar kategori unik produk milik mitra dari database
        $categories = $rawProducts->pluck('kategori')->filter()->unique()->values();

        // Opsi filtering kategori produk via query parameter ?kategori=...
        $kategoriFilter = $request->query('kategori');
        if (!empty($kategoriFilter)) {
            $rawProducts = $rawProducts->where('kategori', $kategoriFilter)->values();
        }

        $products = $rawProducts->map(function ($prod) use ($activeDiscountMap) {
            $hargaNormal = $prod->detailVariant ? $prod->detailVariant->min('harga_sewa') : 0;
            $activeDisc = $activeDiscountMap[$prod->id] ?? null;

            return [
                'id_produk'        => $prod->id,
                'nama_produk'      => $prod->nama,
                'kategori'         => $prod->kategori,
                'harga_normal'     => (int) $hargaNormal,
                'foto'             => $prod->photoThumbnail ? asset('assets/image/customers/produk/' . $prod->photoThumbnail->url_foto) : null,
                'is_locked'        => !empty($activeDisc),
                'can_be_discounted'=> empty($activeDisc),
                'current_discount' => $activeDisc,
            ];
        });

        return response()->json([
            'status'                      => 'success',
            'message'                     => 'Daftar produk dan status diskon berhasil diambil',
            'has_active_store_wide_promo' => $activeStoreWide ? true : false,
            'active_store_wide_promo'     => $activeStoreWide ? [
                'id_promo'        => $activeStoreWide->id_promo,
                'nama_diskon'     => $activeStoreWide->nama_diskon,
                'nilai_diskon'    => (float) $activeStoreWide->nilai_diskon,
                'tipe_diskon'     => $activeStoreWide->tipe_diskon,
                'konflik_diskon'  => $activeStoreWide->konflik_diskon ?? 'replace',
                'tanggal_selesai' => $activeStoreWide->tanggal_selesai ? $activeStoreWide->tanggal_selesai->toDateTimeString() : null,
            ] : null,
            'categories'                  => $categories,
            'total_products'              => $products->count(),
            'data'                        => $products,
        ], 200);
    }

    /**
     * GET /api/mitra/promo/{id} atau GET /api/mitra/diskon/{id}
     * Mengambil detail diskon produk beserta daftar id_produk yang terpilih.
     */
    public function show($id)
    {
        $userId = auth()->id();

        $promo = PromosiToko::with(['produk' => function ($q) {
            $q->select('produk.id', 'produk.nama', 'produk.kategori');
        }])
            ->where('id_promo', $id)
            ->where('id_user', $userId)
            ->first();

        if (!$promo) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data diskon tidak ditemukan atau bukan milik Anda',
            ], 404);
        }

        $selectedProductIds = PromosiProduk::where('id_promo', $promo->id_promo)
            ->pluck('id_produk')
            ->toArray();

        $data = [
            'id_promo'             => $promo->id_promo,
            'id_diskon'            => $promo->id_promo,
            'nama_diskon'          => $promo->nama_diskon,
            'tipe_diskon'          => $promo->tipe_diskon,
            'nilai_diskon'         => (float) $promo->nilai_diskon,
            'cakupan'              => $promo->cakupan,
            'konflik_diskon'       => $promo->konflik_diskon ?? 'replace',
            'tanggal_mulai'        => $promo->tanggal_mulai ? $promo->tanggal_mulai->toDateTimeString() : null,
            'tanggal_selesai'      => $promo->tanggal_selesai ? $promo->tanggal_selesai->toDateTimeString() : null,
            'is_active'            => (bool) $promo->is_active,
            'status'               => $promo->status,
            'selected_product_ids' => $selectedProductIds,
            'produk'               => $promo->produk,
            'created_at'           => $promo->created_at ? $promo->created_at->toDateTimeString() : null,
        ];

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail diskon produk berhasil diambil',
            'data'    => $data,
        ], 200);
    }

    /**
     * POST /api/mitra/promo atau POST /api/mitra/diskon
     * Membuat program diskon produk baru oleh mitra.
     */
    public function store(Request $request)
    {
        $userId = auth()->id();

        $validator = Validator::make($request->all(), [
            'nama_diskon'       => 'nullable|string|max:150',
            'judul_promo'       => 'nullable|string|max:150',
            'tipe_diskon'       => 'required|in:persen,nominal',
            'nilai_diskon'      => 'required|numeric|min:1',
            'cakupan'           => 'required|in:all_products,selected_products',
            'konflik_diskon'    => 'nullable|in:replace,skip',
            'selected_products' => 'nullable',
            'tanggal_mulai'     => 'required|date',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
            'status'            => 'nullable|in:active,draft,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi data diskon gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $isActive = $request->input('status', 'active') !== 'inactive';

        // 1. ATURAN DISKON KESELURUHAN (all_products):
        if ($request->cakupan === 'all_products' && $isActive) {
            $existingStoreWide = PromoService::getActiveStoreWidePromo($userId);
            if ($existingStoreWide) {
                return response()->json([
                    'status'             => 'error',
                    'code'               => 'ACTIVE_STORE_WIDE_EXISTS',
                    'message'            => "Toko Anda saat ini sudah memiliki program diskon keseluruhan aktif ('{$existingStoreWide->nama_diskon}'). Anda tidak dapat membuat diskon keseluruhan baru sebelum program tersebut berakhir atau dinonaktifkan/dihapus.",
                    'active_store_promo' => [
                        'id_promo'        => $existingStoreWide->id_promo,
                        'nama_diskon'     => $existingStoreWide->nama_diskon,
                        'nilai_diskon'    => (float) $existingStoreWide->nilai_diskon,
                        'tipe_diskon'     => $existingStoreWide->tipe_diskon,
                        'tanggal_selesai' => $existingStoreWide->tanggal_selesai ? $existingStoreWide->tanggal_selesai->toDateTimeString() : null,
                    ],
                ], 422);
            }
        }

        // 2. ATURAN PRODUK PILIHAN TERTENTU (selected_products):
        $productIds = [];
        if ($request->cakupan === 'selected_products') {
            $productIds = is_array($request->selected_products)
                ? $request->selected_products
                : (is_string($request->selected_products) ? explode(',', $request->selected_products) : []);
            $productIds = array_filter(array_map('intval', $productIds));

            if (empty($productIds)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Pilih minimal satu produk jika memilih cakupan Produk Tertentu (selected_products).',
                ], 422);
            }

            if ($isActive) {
                $activeDiscountMap = PromoService::getActiveProductsDiscountMap($userId);
                $conflicts = [];
                foreach ($productIds as $pId) {
                    if (isset($activeDiscountMap[$pId])) {
                        $prodName = Produk::where('id', $pId)->value('nama') ?? "ID $pId";
                        $conflicts[] = [
                            'id_produk'        => $pId,
                            'nama_produk'      => $prodName,
                            'current_discount' => $activeDiscountMap[$pId],
                        ];
                    }
                }

                if (!empty($conflicts)) {
                    return response()->json([
                        'status'               => 'error',
                        'code'                 => 'PRODUCT_DISCOUNT_CONFLICT',
                        'message'              => 'Beberapa produk yang dipilih sedang memiliki program diskon aktif. Produk tidak dapat didiskon ganda. Silakan edit program diskon terkait.',
                        'conflicting_products' => $conflicts,
                    ], 422);
                }
            }
        }

        $namaDiskon = $request->input('nama_diskon') ?: $request->input('judul_promo') ?: 'Diskon Produk';
        $konflikDiskon = $request->cakupan === 'all_products' ? ($request->input('konflik_diskon', 'replace')) : 'replace';

        DB::beginTransaction();
        try {
            $promo = PromosiToko::create([
                'id_user'         => $userId,
                'nama_diskon'     => $namaDiskon,
                'judul_promo'     => $namaDiskon,
                'tipe_diskon'     => $request->tipe_diskon,
                'nilai_diskon'    => $request->nilai_diskon,
                'cakupan'         => $request->cakupan,
                'konflik_diskon'  => $konflikDiskon,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'is_banner'       => false,
                'is_active'       => $isActive,
                'status'          => $request->input('status', 'active'),
            ]);

            // Jika cakupan selected_products, simpan relasi ke promosi_produk
            if ($request->cakupan === 'selected_products') {
                foreach ($productIds as $pId) {
                    PromosiProduk::create([
                        'id_promo'  => $promo->id_promo,
                        'id_produk' => $pId,
                    ]);
                }
            }

            // Jika all_products dengan opsi replace:
            if ($request->cakupan === 'all_products' && $isActive && $konflikDiskon === 'replace') {
                PromoService::handleStoreWideReplaceConflict($userId, $promo->id_promo);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Program diskon produk berhasil dibuat',
                'data'    => [
                    'id_promo'       => $promo->id_promo,
                    'nama_diskon'    => $promo->nama_diskon,
                    'tipe_diskon'    => $promo->tipe_diskon,
                    'nilai_diskon'   => (float) $promo->nilai_diskon,
                    'cakupan'        => $promo->cakupan,
                    'konflik_diskon' => $promo->konflik_diskon,
                    'is_active'      => (bool) $promo->is_active,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal membuat program diskon: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PUT/POST /api/mitra/promo/{id} atau /api/mitra/diskon/{id}
     * Memperbarui diskon produk.
     */
    public function update(Request $request, $id)
    {
        $userId = auth()->id();

        $promo = PromosiToko::where('id_promo', $id)
            ->where('id_user', $userId)
            ->first();

        if (!$promo) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Diskon tidak ditemukan atau bukan milik Anda',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_diskon'       => 'nullable|string|max:150',
            'judul_promo'       => 'nullable|string|max:150',
            'tipe_diskon'       => 'required|in:persen,nominal',
            'nilai_diskon'      => 'required|numeric|min:1',
            'cakupan'           => 'required|in:all_products,selected_products',
            'konflik_diskon'    => 'nullable|in:replace,skip',
            'selected_products' => 'nullable',
            'tanggal_mulai'     => 'required|date',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
            'status'            => 'nullable|in:active,draft,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi pembaruan diskon gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $isActive = $request->has('status')
            ? ($request->input('status') !== 'inactive')
            : $promo->is_active;

        // 1. Cek diskon keseluruhan aktif lain
        if ($request->cakupan === 'all_products' && $isActive) {
            $existingStoreWide = PromoService::getActiveStoreWidePromo($userId, $promo->id_promo);
            if ($existingStoreWide) {
                return response()->json([
                    'status'             => 'error',
                    'code'               => 'ACTIVE_STORE_WIDE_EXISTS',
                    'message'            => "Toko Anda saat ini sudah memiliki diskon keseluruhan aktif lainnya ('{$existingStoreWide->nama_diskon}').",
                    'active_store_promo' => [
                        'id_promo'        => $existingStoreWide->id_promo,
                        'nama_diskon'     => $existingStoreWide->nama_diskon,
                        'nilai_diskon'    => (float) $existingStoreWide->nilai_diskon,
                        'tipe_diskon'     => $existingStoreWide->tipe_diskon,
                        'tanggal_selesai' => $existingStoreWide->tanggal_selesai ? $existingStoreWide->tanggal_selesai->toDateTimeString() : null,
                    ],
                ], 422);
            }
        }

        // 2. Cek konflik produk pada selected_products
        $productIds = [];
        if ($request->cakupan === 'selected_products') {
            $productIds = is_array($request->selected_products)
                ? $request->selected_products
                : (is_string($request->selected_products) ? explode(',', $request->selected_products) : []);
            $productIds = array_filter(array_map('intval', $productIds));

            if (empty($productIds)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Pilih minimal satu produk jika memilih cakupan Produk Tertentu (selected_products).',
                ], 422);
            }

            if ($isActive) {
                $activeDiscountMap = PromoService::getActiveProductsDiscountMap($userId, $promo->id_promo);
                $conflicts = [];
                foreach ($productIds as $pId) {
                    if (isset($activeDiscountMap[$pId])) {
                        $prodName = Produk::where('id', $pId)->value('nama') ?? "ID $pId";
                        $conflicts[] = [
                            'id_produk'        => $pId,
                            'nama_produk'      => $prodName,
                            'current_discount' => $activeDiscountMap[$pId],
                        ];
                    }
                }

                if (!empty($conflicts)) {
                    return response()->json([
                        'status'               => 'error',
                        'code'                 => 'PRODUCT_DISCOUNT_CONFLICT',
                        'message'              => 'Beberapa produk yang dipilih sedang memiliki program diskon aktif di promo lain.',
                        'conflicting_products' => $conflicts,
                    ], 422);
                }
            }
        }

        $namaDiskon = $request->input('nama_diskon') ?: $request->input('judul_promo') ?: $promo->nama_diskon;
        $konflikDiskon = $request->cakupan === 'all_products' ? ($request->input('konflik_diskon', 'replace')) : 'replace';

        DB::beginTransaction();
        try {
            $promo->update([
                'nama_diskon'     => $namaDiskon,
                'judul_promo'     => $namaDiskon,
                'tipe_diskon'     => $request->tipe_diskon,
                'nilai_diskon'    => $request->nilai_diskon,
                'cakupan'         => $request->cakupan,
                'konflik_diskon'  => $konflikDiskon,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'is_active'       => $isActive,
                'status'          => $request->input('status', $promo->status),
            ]);

            // Sinkronisasi relasi produk
            PromosiProduk::where('id_promo', $promo->id_promo)->delete();
            if ($request->cakupan === 'selected_products') {
                foreach ($productIds as $pId) {
                    PromosiProduk::create([
                        'id_promo'  => $promo->id_promo,
                        'id_produk' => $pId,
                    ]);
                }
            }

            // Jika all_products dengan replace
            if ($request->cakupan === 'all_products' && $isActive && $konflikDiskon === 'replace') {
                PromoService::handleStoreWideReplaceConflict($userId, $promo->id_promo);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Diskon produk berhasil diperbarui',
                'data'    => [
                    'id_promo'       => $promo->id_promo,
                    'nama_diskon'    => $promo->nama_diskon,
                    'tipe_diskon'    => $promo->tipe_diskon,
                    'nilai_diskon'   => (float) $promo->nilai_diskon,
                    'cakupan'        => $promo->cakupan,
                    'konflik_diskon' => $promo->konflik_diskon,
                    'is_active'      => (bool) $promo->is_active,
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui diskon: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /api/mitra/promo/{id} atau /api/mitra/diskon/{id}
     * Menghapus diskon produk.
     */
    public function destroy($id)
    {
        $userId = auth()->id();

        $promo = PromosiToko::where('id_promo', $id)
            ->where('id_user', $userId)
            ->first();

        if (!$promo) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Diskon tidak ditemukan atau bukan milik Anda',
            ], 404);
        }

        PromosiProduk::where('id_promo', $promo->id_promo)->delete();
        $promo->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Program diskon produk berhasil dihapus',
        ], 200);
    }

    /**
     * GET /api/diskon/toko/{id_toko} (PUBLIC)
     * Mengambil daftar diskon produk aktif milik toko tertentu untuk ditampilkan di profil toko publik.
     */
    public function getDiskonTokoPublic($id_toko)
    {
        $now = Carbon::now();

        $diskonList = PromosiToko::with(['produk' => function ($q) {
            $q->select('produk.id', 'produk.nama', 'produk.kategori');
        }])
            ->where('id_user', $id_toko)
            ->where('is_active', true)
            ->where('status', 'active')
            ->where('tanggal_mulai', '<=', $now)
            ->where('tanggal_selesai', '>=', $now)
            ->orderByDesc('nilai_diskon')
            ->get()
            ->map(function ($d) {
                return [
                    'id_diskon'       => $d->id_promo,
                    'nama_diskon'     => $d->nama_diskon,
                    'tipe_diskon'     => $d->tipe_diskon,
                    'nilai_diskon'    => (float) $d->nilai_diskon,
                    'label_diskon'    => $d->tipe_diskon === 'persen' ? (int) $d->nilai_diskon . '%' : 'Rp ' . number_format($d->nilai_diskon, 0, ',', '.'),
                    'cakupan'         => $d->cakupan,
                    'tanggal_mulai'   => $d->tanggal_mulai ? $d->tanggal_mulai->toDateTimeString() : null,
                    'tanggal_selesai' => $d->tanggal_selesai ? $d->tanggal_selesai->toDateTimeString() : null,
                    'produk'          => $d->produk,
                ];
            });

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar diskon aktif toko berhasil diambil',
            'data'    => $diskonList,
        ], 200);
    }
}
