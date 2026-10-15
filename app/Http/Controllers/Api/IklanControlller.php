<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Iklan;
use App\Services\PromoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IklanControlller extends Controller
{
    /**
     * GET /api/iklan
     * Menampilkan daftar banner iklan aktif di dashboard.
     * Dilengkapi dengan relasi ke toko mitra, produk terkait, dan badge promo diskon.
     */
    public function getAllIklan()
    {
        // Sinkronisasi otomatis: selesaikan yang kadaluarsa dan batasi slot aktif maksimal 15
        \App\Services\IklanService::autoSyncStatusIklan();

        $today = Carbon::today();
        $now = Carbon::now();

        // Ambil iklan yang aktif baik dari detail_iklan maupun tabel iklan langsung
        $iklanList = DB::table('iklan')
            ->leftJoin('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->leftJoin('users', 'iklan.id_user', '=', 'users.id')
            ->where('iklan.status', 'active')
            ->where(function ($query) use ($today, $now) {
                // Skenario 1: Lewat detail_iklan sewa mitra (wajib pembayaran lunas & status iklan Aktif)
                $query->where(function ($q) use ($today) {
                    $q->whereNotNull('detail_iklan.id')
                      ->where('pembayaran_iklan.status_bayar', 'lunas')
                      ->where('detail_iklan.status_iklan', 'Aktif')
                      ->whereDate('detail_iklan.tanggal_mulai', '<=', $today)
                      ->whereDate('detail_iklan.tanggal_akhir', '>=', $today);
                })
                // Skenario 2: Lewat tabel iklan langsung (iklan campaign / promo mandiri developer tanpa detail_iklan)
                ->orWhere(function ($q) use ($now) {
                    $q->whereNull('detail_iklan.id')
                      ->where(function ($sub) use ($now) {
                          $sub->whereNull('iklan.tanggal_mulai')
                              ->orWhere('iklan.tanggal_mulai', '<=', $now);
                      })
                      ->where(function ($sub) use ($now) {
                          $sub->whereNull('iklan.tanggal_selesai')
                              ->orWhere('iklan.tanggal_selesai', '>=', $now);
                      });
                });
            })
            ->select(
                'iklan.id',
                'iklan.id_user',
                'iklan.id_produk',
                'iklan.target_type',
                'iklan.badge_promo',
                'iklan.poster',
                'iklan.judul',
                'iklan.sub_judul',
                'iklan.deskripsi',
                'users.name as user_name',
                'users.name_store'
            )
            ->distinct()
            ->limit(\App\Services\IklanService::MAX_IKLAN_AKTIF)
            ->get();

        if ($iklanList->isEmpty()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Tidak ada iklan aktif saat ini',
                'data'    => [],
            ], 200);
        }

        $data = $iklanList->map(function ($item) {
            // Resolusi URL / path poster
            $poster = $item->poster;
            if ($poster && !Str::startsWith($poster, ['http://', 'https://'])) {
                if (Str::startsWith($poster, 'assets/')) {
                    $poster = asset($poster);
                } else {
                    $poster = asset('assets/image/customers/advert/' . $poster);
                }
            }

            // Cek apakah ada diskon aktif untuk produk / toko ini
            $hasActiveDiscount = false;
            $badgePromo = $item->badge_promo;

            if ($item->id_produk) {
                $promo = PromoService::getActivePromoForProduct($item->id_produk, $item->id_user);
                if ($promo) {
                    $hasActiveDiscount = true;
                    if (empty($badgePromo)) {
                        $badgePromo = $promo->tipe_diskon === 'persen'
                            ? 'DISKON ' . (int) $promo->nilai_diskon . '%'
                            : 'HEMAT Rp ' . number_format($promo->nilai_diskon, 0, ',', '.');
                    }
                }
            } elseif ($item->id_user) {
                $promo = PromoService::getActiveStorePromo($item->id_user);
                if ($promo) {
                    $hasActiveDiscount = true;
                    if (empty($badgePromo)) {
                        $badgePromo = $promo->tipe_diskon === 'persen'
                            ? 'DISKON ' . (int) $promo->nilai_diskon . '%'
                            : 'PROMO TOKO';
                    }
                }
            }

            return [
                'id'                  => (int) $item->id,
                'judul'               => $item->judul ?? '',
                'sub_judul'           => $item->sub_judul ?? '',
                'deskripsi'           => $item->deskripsi ?? $item->sub_judul ?? '',
                'poster'              => $poster ?? '',
                'target_type'         => $item->target_type ?? ($item->id_produk ? 'product' : 'store'),
                'id_toko'             => $item->id_user ? (int) $item->id_user : null,
                'nama_toko'           => !empty($item->name_store) ? $item->name_store : ($item->user_name ?? 'KampSewa Mitra'),
                'id_produk'           => $item->id_produk ? (int) $item->id_produk : null,
                'badge_promo'         => $badgePromo ?? 'PROMO TOKO',
                'has_active_discount' => $hasActiveDiscount,
            ];
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Data iklan berhasil diambil',
            'data'    => $data,
        ], 200);
    }

    /**
     * GET /api/iklan/{identifier}
     * Menampilkan detail iklan berdasarkan ID atau judul.
     */
    public function getDetailIklan($identifier)
    {
        $iklan = DB::table('iklan')
            ->leftJoin('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->leftJoin('users', 'iklan.id_user', '=', 'users.id')
            ->select('users.id as user_id', 'users.name as user_name', 'users.name_store', 'iklan.*')
            ->where(function ($q) use ($identifier) {
                $q->where('iklan.id', $identifier)
                  ->orWhere('iklan.judul', 'like', '%' . $identifier . '%');
            })
            ->where('iklan.status', 'active')
            ->where(function ($q) {
                $q->whereNull('detail_iklan.id')
                  ->orWhere('pembayaran_iklan.status_bayar', 'lunas');
            })
            ->first();

        if (!$iklan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Iklan not found'
            ], 404);
        }

        // Resolusi URL / path poster
        $poster = $iklan->poster;
        if ($poster && !Str::startsWith($poster, ['http://', 'https://'])) {
            if (Str::startsWith($poster, 'assets/')) {
                $poster = asset($poster);
            } else {
                $poster = asset('assets/image/customers/advert/' . $poster);
            }
        }
        $iklan->poster = $poster;

        // Cek promo aktif
        $hasActiveDiscount = false;
        $badgePromo = $iklan->badge_promo;
        if ($iklan->id_produk) {
            $promo = PromoService::getActivePromoForProduct($iklan->id_produk, $iklan->id_user);
            if ($promo) {
                $hasActiveDiscount = true;
                if (empty($badgePromo)) {
                    $badgePromo = $promo->tipe_diskon === 'persen' ? 'DISKON ' . (int) $promo->nilai_diskon . '%' : 'PROMO';
                }
            }
        } elseif ($iklan->id_user) {
            $promo = PromoService::getActiveStorePromo($iklan->id_user);
            if ($promo) {
                $hasActiveDiscount = true;
                if (empty($badgePromo)) {
                    $badgePromo = $promo->tipe_diskon === 'persen' ? 'DISKON ' . (int) $promo->nilai_diskon . '%' : 'PROMO TOKO';
                }
            }
        }

        $iklan->badge_promo = $badgePromo ?? 'PROMO';
        $iklan->has_active_discount = $hasActiveDiscount;
        $iklan->id_toko = $iklan->id_user ? (int) $iklan->id_user : null;
        $iklan->nama_toko = !empty($iklan->name_store) ? $iklan->name_store : ($iklan->user_name ?? 'KampSewa Mitra');

        return response()->json([
            'status'  => 'success',
            'message' => 'Success',
            'iklan'   => $iklan,
            'data'    => $iklan,
        ], 200);
    }
}
