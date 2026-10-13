<?php

namespace App\Services;

use App\Models\PromosiToko;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PromoService
{
    /**
     * Mengambil promo aktif diskon keseluruhan toko (all_products).
     * Digunakan untuk memastikan toko hanya memiliki MAKSIMAL 1 diskon keseluruhan aktif.
     */
    public static function getActiveStoreWidePromo($idUser, $excludePromoId = null)
    {
        $now = Carbon::now();

        return PromosiToko::where('id_user', $idUser)
            ->where('cakupan', 'all_products')
            ->where('is_active', true)
            ->where('status', 'active')
            ->where('tanggal_mulai', '<=', $now)
            ->where('tanggal_selesai', '>=', $now)
            ->when($excludePromoId, function ($q) use ($excludePromoId) {
                $q->where('id_promo', '!=', $excludePromoId);
            })
            ->first();
    }

    /**
     * Mengambil peta (map) produk toko yang saat ini sedang aktif dalam program diskon.
     * Mengembalikan [id_produk => ['id_promo', 'nama_diskon', 'nilai_diskon', 'tipe_diskon', 'cakupan']]
     * Digunakan untuk memvalidasi agar produk tidak didiskon ganda (hanya bisa edit diskon produk tersebut).
     */
    public static function getActiveProductsDiscountMap($idUser, $excludePromoId = null)
    {
        $now = Carbon::now();
        $map = [];

        // 1. Cek diskon produk spesifik (selected_products)
        $selectedRows = DB::table('promosi_toko')
            ->join('promosi_produk', 'promosi_toko.id_promo', '=', 'promosi_produk.id_promo')
            ->where('promosi_toko.id_user', $idUser)
            ->where('promosi_toko.is_active', true)
            ->where('promosi_toko.status', 'active')
            ->where('promosi_toko.tanggal_mulai', '<=', $now)
            ->where('promosi_toko.tanggal_selesai', '>=', $now)
            ->when($excludePromoId, function ($q) use ($excludePromoId) {
                $q->where('promosi_toko.id_promo', '!=', $excludePromoId);
            })
            ->select(
                'promosi_produk.id_produk',
                'promosi_toko.id_promo',
                'promosi_toko.nama_diskon',
                'promosi_toko.judul_promo',
                'promosi_toko.nilai_diskon',
                'promosi_toko.tipe_diskon',
                'promosi_toko.cakupan',
                'promosi_toko.tanggal_mulai',
                'promosi_toko.tanggal_selesai'
            )
            ->get();

        foreach ($selectedRows as $r) {
            $map[$r->id_produk] = [
                'id_promo'        => $r->id_promo,
                'nama_diskon'     => $r->nama_diskon ?: $r->judul_promo,
                'nilai_diskon'    => (float) $r->nilai_diskon,
                'tipe_diskon'     => $r->tipe_diskon,
                'cakupan'         => $r->cakupan,
                'label_diskon'    => $r->tipe_diskon === 'persen' ? round($r->nilai_diskon) . '%' : 'Rp ' . number_format($r->nilai_diskon, 0, ',', '.'),
                'tanggal_selesai' => $r->tanggal_selesai,
            ];
        }

        // 2. Cek apakah ada diskon keseluruhan aktif (all_products)
        $storeWidePromo = self::getActiveStoreWidePromo($idUser, $excludePromoId);
        if ($storeWidePromo) {
            // Ambil semua produk milik toko
            $allProdIds = DB::table('produk')->where('id_user', $idUser)->pluck('id');
            foreach ($allProdIds as $pId) {
                // Jika belum tercakup di selected_products spesifik, tandai tercakup di store-wide promo
                if (!isset($map[$pId])) {
                    $map[$pId] = [
                        'id_promo'        => $storeWidePromo->id_promo,
                        'nama_diskon'     => $storeWidePromo->nama_diskon ?: $storeWidePromo->judul_promo,
                        'nilai_diskon'    => (float) $storeWidePromo->nilai_diskon,
                        'tipe_diskon'     => $storeWidePromo->tipe_diskon,
                        'cakupan'         => 'all_products',
                        'label_diskon'    => $storeWidePromo->tipe_diskon === 'persen' ? round($storeWidePromo->nilai_diskon) . '%' : 'Rp ' . number_format($storeWidePromo->nilai_diskon, 0, ',', '.'),
                        'tanggal_selesai' => $storeWidePromo->tanggal_selesai,
                    ];
                }
            }
        }

        return $map;
    }

    /**
     * Menangani strategi replace konflik saat diskon keseluruhan (all_products) dibuat atau diaktifkan.
     * Opsi 1 (Replace): Seluruh produk disamaratakan. Diskon lama pada produk-produk toko di-replace / dinonaktifkan.
     */
    public static function handleStoreWideReplaceConflict($idUser, $newPromoId)
    {
        // Cari promo-promo lama milik toko (selain promo baru ini) yang bertipe selected_products
        $oldPromos = PromosiToko::where('id_user', $idUser)
            ->where('id_promo', '!=', $newPromoId)
            ->where('cakupan', 'selected_products')
            ->get();

        foreach ($oldPromos as $old) {
            // Hapus relasi produk pada promo lama agar tidak override promo store-wide baru
            DB::table('promosi_produk')->where('id_promo', $old->id_promo)->delete();
            // Nonaktifkan promo lama yang sudah ter-replace
            $old->update([
                'is_active' => false,
                'status'    => 'inactive',
            ]);
        }
    }

    /**
     * Mengambil promo aktif toko (store-wide atau promo aktif utama).
     */
    public static function getActiveStorePromo($idUser)
    {
        $now = Carbon::now();

        // Cari promo aktif toko (prioritaskan cakupan all_products)
        $storePromo = PromosiToko::where('id_user', $idUser)
            ->where('is_active', true)
            ->where('status', 'active')
            ->where('tanggal_mulai', '<=', $now)
            ->where('tanggal_selesai', '>=', $now)
            ->orderByRaw("CASE WHEN cakupan = 'all_products' THEN 1 ELSE 2 END")
            ->orderByDesc('nilai_diskon')
            ->first();

        return $storePromo;
    }

    /**
     * Hitung diskon untuk satu harga dan promo.
     */
    public static function calculateDiscount($originalPrice, $promo = null)
    {
        $originalPrice = (int) $originalPrice;

        if (!$promo) {
            return [
                'harga_asli'        => $originalPrice,
                'harga'             => $originalPrice,
                'is_diskon'         => false,
                'persentase_diskon' => 0,
                'potongan_harga'    => 0,
                'nama_promo'        => null,
            ];
        }

        $tipe = $promo->tipe_diskon ?? 'persen';
        $nilai = (float) ($promo->nilai_diskon ?? 0);

        if ($tipe === 'persen') {
            $persentase = (int) round(min(100, max(0, $nilai)));
            $potongan = (int) round(($originalPrice * $persentase) / 100);
            $harga = max(0, $originalPrice - $potongan);
        } else { // nominal
            $potongan = (int) round(min($originalPrice, max(0, $nilai)));
            $persentase = $originalPrice > 0 ? (int) round(($potongan / $originalPrice) * 100) : 0;
            $harga = max(0, $originalPrice - $potongan);
        }

        return [
            'harga_asli'        => $originalPrice,
            'harga'             => $harga,
            'is_diskon'         => $potongan > 0,
            'persentase_diskon' => $persentase,
            'potongan_harga'    => $potongan,
            'nama_promo'        => $promo->nama_diskon ?? $promo->judul_promo ?? null,
            'nama_diskon'       => $promo->nama_diskon ?? $promo->judul_promo ?? null,
        ];
    }

    /**
     * Pasang atribut kalkulasi diskon ke satu produk / item.
     */
    public static function applyDiscountToSingle($item)
    {
        if (!$item) return $item;

        $produkId = $item->id_produk ?? $item->id ?? null;
        $idUser   = $item->id_user ?? null;
        $harga    = $item->harga_sewa ?? $item->harga ?? 0;

        $promo = self::getActivePromoForProduct($produkId, $idUser);
        $calc  = self::calculateDiscount($harga, $promo);

        $item->harga_asli        = $calc['harga_asli'];
        $item->harga             = $calc['harga'];
        $item->harga_sewa        = $calc['harga']; // harga sewa efektif
        $item->is_diskon         = $calc['is_diskon'];
        $item->persentase_diskon = $calc['persentase_diskon'];
        $item->potongan_harga    = $calc['potongan_harga'];
        $item->nama_promo        = $calc['nama_promo'];
        $item->nama_diskon       = $calc['nama_diskon'];
        $item->tanggal_mulai     = $promo ? ($promo->tanggal_mulai ?? null) : null;
        $item->tanggal_selesai   = $promo ? ($promo->tanggal_selesai ?? null) : null;

        return $item;
    }

    /**
     * Cari promo aktif untuk suatu produk tertentu.
     */
    public static function getActivePromoForProduct($produkId, $idUser)
    {
        $now = Carbon::now();

        // 1. Cek promo selected_products untuk produk ini
        if ($produkId) {
            $selectedPromo = PromosiToko::join('promosi_produk', 'promosi_toko.id_promo', '=', 'promosi_produk.id_promo')
                ->where('promosi_produk.id_produk', $produkId)
                ->where('promosi_toko.is_active', true)
                ->where('promosi_toko.status', 'active')
                ->where('promosi_toko.tanggal_mulai', '<=', $now)
                ->where('promosi_toko.tanggal_selesai', '>=', $now)
                ->select('promosi_toko.*')
                ->orderByDesc('promosi_toko.nilai_diskon')
                ->first();

            if ($selectedPromo) {
                return $selectedPromo;
            }
        }

        // 2. Cek promo store-wide (all_products) milik toko
        if ($idUser) {
            $storePromo = PromosiToko::where('id_user', $idUser)
                ->where('cakupan', 'all_products')
                ->where('is_active', true)
                ->where('status', 'active')
                ->where('tanggal_mulai', '<=', $now)
                ->where('tanggal_selesai', '>=', $now)
                ->orderByDesc('nilai_diskon')
                ->first();

            if ($storePromo) {
                return $storePromo;
            }
        }

        return null;
    }

    /**
     * Mengaplikasikan diskon ke Collection produk secara batch (anti N+1 query).
     */
    public static function applyDiscountsToCollection($items)
    {
        if (empty($items) || (is_object($items) && method_exists($items, 'isEmpty') && $items->isEmpty())) {
            return $items;
        }

        $now = Carbon::now();

        // Kumpulkan product IDs dan user IDs
        $productIds = [];
        $userIds    = [];

        foreach ($items as $item) {
            $pId = $item->id_produk ?? $item->id ?? null;
            $uId = $item->id_user ?? null;
            if ($pId) $productIds[] = $pId;
            if ($uId) $userIds[] = $uId;
        }

        $productIds = array_unique(array_filter($productIds));
        $userIds    = array_unique(array_filter($userIds));

        // 1. Query promo selected_products
        $selectedPromos = [];
        if (!empty($productIds)) {
            $rows = DB::table('promosi_toko')
                ->join('promosi_produk', 'promosi_toko.id_promo', '=', 'promosi_produk.id_promo')
                ->whereIn('promosi_produk.id_produk', $productIds)
                ->where('promosi_toko.is_active', true)
                ->where('promosi_toko.status', 'active')
                ->where('promosi_toko.tanggal_mulai', '<=', $now)
                ->where('promosi_toko.tanggal_selesai', '>=', $now)
                ->select(
                    'promosi_toko.*',
                    'promosi_produk.id_produk'
                )
                ->orderByDesc('promosi_toko.nilai_diskon')
                ->get();

            foreach ($rows as $row) {
                if (!isset($selectedPromos[$row->id_produk])) {
                    $selectedPromos[$row->id_produk] = $row;
                }
            }
        }

        // 2. Query promo store-wide (all_products)
        $storePromos = [];
        if (!empty($userIds)) {
            $rows = DB::table('promosi_toko')
                ->whereIn('id_user', $userIds)
                ->where('cakupan', 'all_products')
                ->where('is_active', true)
                ->where('status', 'active')
                ->where('tanggal_mulai', '<=', $now)
                ->where('tanggal_selesai', '>=', $now)
                ->orderByDesc('nilai_diskon')
                ->get();

            foreach ($rows as $row) {
                if (!isset($storePromos[$row->id_user])) {
                    $storePromos[$row->id_user] = $row;
                }
            }
        }

        // 3. Pasangkan promo ke setiap item
        foreach ($items as $item) {
            $pId = $item->id_produk ?? $item->id ?? null;
            $uId = $item->id_user ?? null;
            $harga = $item->harga_sewa ?? $item->harga ?? 0;

            // Prioritaskan selected product promo, lalu store-wide promo
            $matchedPromo = $selectedPromos[$pId] ?? ($storePromos[$uId] ?? null);

            $calc = self::calculateDiscount($harga, $matchedPromo);

            $item->harga_asli        = $calc['harga_asli'];
            $item->harga             = $calc['harga'];
            $item->harga_sewa        = $calc['harga']; // harga sewa efektif
            $item->is_diskon         = $calc['is_diskon'];
            $item->persentase_diskon = $calc['persentase_diskon'];
            $item->potongan_harga    = $calc['potongan_harga'];
            $item->nama_promo        = $calc['nama_promo'];
            $item->nama_diskon       = $calc['nama_diskon'] ?? $calc['nama_promo'];
            $item->tanggal_mulai     = $matchedPromo ? ($matchedPromo->tanggal_mulai ?? null) : null;
            $item->tanggal_selesai   = $matchedPromo ? ($matchedPromo->tanggal_selesai ?? null) : null;
        }

        return $items;
    }
}
