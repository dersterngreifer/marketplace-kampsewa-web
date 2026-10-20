<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\DetailIklan;
use App\Models\Feedback;
use App\Models\FeedbackMessage;
use App\Models\PembayaranIklan;
use App\Models\Penyewaan;
use App\Models\RatingProduk;
use App\Models\StatusNotifikasiUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ActivityNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('dev');
    }

    /**
     * Tampilkan halaman pusat notifikasi aktivitas umum platform.
     */
    public function index(Request $request)
    {
        $currentTab = $request->get('tab', 'all');
        $statusFilter = $request->get('status', 'all'); // 'all', 'unread', 'read'
        $timeFilter = $request->get('time', 'all');     // 'all', 'today', 'this_week', 'this_month'
        $search = trim($request->get('search', ''));

        // ==========================================
        // 1. STATISTIK RINGKASAN AKTIVITAS
        // ==========================================
        $unreadUsersCount = StatusNotifikasiUser::where('status', 'unread')->count();
        $unreadFeedbackCount = Feedback::whereNull('read_at')->count();
        $unreadMessageCount = FeedbackMessage::where('sender_type', 'customer')->whereNull('read_at')->count();
        $unreadAdPaymentsCount = PembayaranIklan::whereNull('read_at')->count();
        $unreadRevisedAdsCount = \App\Models\IklanSuspend::join('detail_iklan', 'iklan_suspends.id_detail_iklan', '=', 'detail_iklan.id')
            ->where('detail_iklan.status_iklan', 'Nonaktif')
            ->where('iklan_suspends.status_revisi', 'sudah_diperbaiki')
            ->whereNull('iklan_suspends.read_at')
            ->count();
        $unreadRevisedProductsCount = \App\Models\ProdukSuspend::where('status_revisi', 'sudah_diperbaiki')
            ->whereNull('read_at')
            ->count();

        $totalUnreadCount = $unreadUsersCount + $unreadFeedbackCount + $unreadMessageCount + $unreadAdPaymentsCount + $unreadRevisedAdsCount + $unreadRevisedProductsCount;

        $totalUsersToday = User::where('type', 0)->whereDate('created_at', Carbon::today())->count();
        $totalFeedbackToday = Feedback::whereDate('created_at', Carbon::today())->count();
        $totalRentalsToday = DB::table('penyewaan')->whereDate('created_at', Carbon::today())->count();
        $totalAdTransactionsToday = PembayaranIklan::whereDate('created_at', Carbon::today())->count();
        $totalAdRevenueToday = PembayaranIklan::whereDate('created_at', Carbon::today())
            ->where(function ($q) {
                $q->where('status_bayar', 'lunas')
                  ->orWhere('midtrans_transaction_status', 'settlement');
            })
            ->sum('total_bayar');
        $totalAdsExpiring = DetailIklan::where('status_iklan', 'Aktif')
            ->whereBetween('tanggal_akhir', [Carbon::today(), Carbon::today()->addDays(3)])
            ->count();
        $totalBadRatings = RatingProduk::where('rating', '<=', 2)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // ==========================================
        // 2. QUERY DATA SESUAI TAB YANG AKTIF
        // ==========================================

        // Data untuk Tab: Registrasi Pengguna
        $usersQuery = User::select('users.*', 'status_notifikasi_user.status as notif_status')
            ->leftJoin('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
            ->where('users.type', 0)
            ->orderByDesc('users.created_at');

        if ($search !== '') {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.nomor_telephone', 'like', "%{$search}%");
            });
        }
        $this->applyTimeFilter($usersQuery, 'users.created_at', $timeFilter);
        if ($statusFilter === 'unread') {
            $usersQuery->where(function($q) {
                $q->where('status_notifikasi_user.status', 'unread')
                  ->orWhereNull('status_notifikasi_user.id');
            });
        } elseif ($statusFilter === 'read') {
            $usersQuery->where('status_notifikasi_user.status', 'read');
        }
        $usersList = $usersQuery->paginate(12, ['*'], 'users_page')->withQueryString();

        // Data untuk Tab: Feedback & Saran
        $feedbackQuery = Feedback::with(['user', 'latestMessage'])
            ->orderByDesc('created_at');

        if ($search !== '') {
            $feedbackQuery->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('kriteria') && $request->kriteria !== 'all') {
            $feedbackQuery->where('kriteria', $request->kriteria);
        }
        if ($request->filled('feedback_status') && $request->feedback_status !== 'all') {
            $feedbackQuery->where('status', $request->feedback_status);
        }
        $this->applyTimeFilter($feedbackQuery, 'feedback.created_at', $timeFilter);
        if ($statusFilter === 'unread') {
            $feedbackQuery->whereNull('read_at');
        } elseif ($statusFilter === 'read') {
            $feedbackQuery->whereNotNull('read_at');
        }
        $feedbackList = $feedbackQuery->paginate(10, ['*'], 'feedback_page')->withQueryString();

        // Data untuk Tab: Transaksi Penyewaan
        $rentalsQuery = DB::table('penyewaan')
            ->join('users as penyewa', 'penyewaan.id_user', '=', 'penyewa.id')
            ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->join('users as mitra', 'produk.id_user', '=', 'mitra.id')
            ->join('pembayaran_penyewaan', 'penyewaan.id', '=', 'pembayaran_penyewaan.id_penyewaan')
            ->select(
                'penyewaan.id as penyewaan_id',
                'penyewaan.status_penyewaan',
                'penyewaan.created_at',
                'penyewa.name as nama_penyewa',
                'penyewa.email as email_penyewa',
                'penyewa.foto as foto_penyewa',
                'mitra.name as nama_mitra',
                'produk.nama as nama_produk',
                'pembayaran_penyewaan.total_pembayaran',
                'pembayaran_penyewaan.pajak_platform',
                'pembayaran_penyewaan.status_pembayaran'
            )
            ->distinct()
            ->orderByDesc('penyewaan.created_at');

        if ($search !== '') {
            $rentalsQuery->where(function ($q) use ($search) {
                $q->where('penyewa.name', 'like', "%{$search}%")
                  ->orWhere('mitra.name', 'like', "%{$search}%")
                  ->orWhere('produk.nama', 'like', "%{$search}%");
            });
        }
        if ($request->filled('rental_status') && $request->rental_status !== 'all') {
            $rentalsQuery->where('penyewaan.status_penyewaan', $request->rental_status);
        }
        $this->applyTimeFilter($rentalsQuery, 'penyewaan.created_at', $timeFilter);
        $rentalsList = $rentalsQuery->paginate(10, ['*'], 'rentals_page')->withQueryString();

        // Data untuk Tab: Transaksi Sewa Jasa Iklan Mitra
        $adPaymentsQuery = PembayaranIklan::with(['user', 'iklan.latestDetail'])
            ->orderByDesc('created_at');

        if ($search !== '') {
            $adPaymentsQuery->where(function ($q) use ($search) {
                $q->where('midtrans_order_id', 'like', "%{$search}%")
                  ->orWhere('metode_bayar', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('name_store', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('iklan', function ($iq) use ($search) {
                      $iq->where('judul', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('ad_pay_status') && $request->ad_pay_status !== 'all') {
            $adPaymentsQuery->where('status_bayar', $request->ad_pay_status);
        }
        $this->applyTimeFilter($adPaymentsQuery, 'pembayaran_iklan.created_at', $timeFilter);
        if ($statusFilter === 'unread') {
            $adPaymentsQuery->whereNull('pembayaran_iklan.read_at');
        } elseif ($statusFilter === 'read') {
            $adPaymentsQuery->whereNotNull('pembayaran_iklan.read_at');
        }
        $adPaymentsList = $adPaymentsQuery->paginate(10, ['*'], 'ad_pay_page')->withQueryString();

        // Data iklan aktif & status tayang (backward compatibility)
        $adsQuery = DetailIklan::join('iklan', 'detail_iklan.id_iklan', '=', 'iklan.id')
            ->join('users', 'iklan.id_user', '=', 'users.id')
            ->select(
                'detail_iklan.id as detail_iklan_id',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.status_iklan',
                'detail_iklan.harga_iklan',
                'detail_iklan.created_at',
                'iklan.id as iklan_id',
                'iklan.judul as judul_iklan',
                'users.name as nama_mitra',
                'users.email as email_mitra',
                'users.foto as foto_mitra'
            )
            ->orderByDesc('detail_iklan.created_at');

        if ($search !== '') {
            $adsQuery->where(function ($q) use ($search) {
                $q->where('iklan.judul', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }
        if ($request->filled('ad_status') && $request->ad_status !== 'all') {
            $adsQuery->where('detail_iklan.status_iklan', $request->ad_status);
        }
        $this->applyTimeFilter($adsQuery, 'detail_iklan.created_at', $timeFilter);
        $adsList = $adsQuery->paginate(10, ['*'], 'ads_page')->withQueryString();

        // Data untuk Tab: Ulasan & Rating
        $ratingsQuery = RatingProduk::with(['user', 'produk.user'])
            ->orderByDesc('created_at');

        if ($search !== '') {
            $ratingsQuery->where(function ($q) use ($search) {
                $q->where('ulasan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('produk', function ($pq) use ($search) {
                      $pq->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('rating_star') && $request->rating_star !== 'all') {
            $ratingsQuery->where('rating', (int)$request->rating_star);
        }
        $this->applyTimeFilter($ratingsQuery, 'rating_produk.created_at', $timeFilter);
        $ratingsList = $ratingsQuery->paginate(10, ['*'], 'ratings_page')->withQueryString();

        // Feed Ringkasan Aktivitas Terbaru untuk Tab 'all'
        $recentUsers = User::where('type', 0)->orderByDesc('created_at')->take(5)->get();
        $recentFeedbacks = Feedback::with('user')->orderByDesc('created_at')->take(5)->get();
        $recentRentals = DB::table('penyewaan')
            ->join('users as penyewa', 'penyewaan.id_user', '=', 'penyewa.id')
            ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->select('penyewaan.*', 'penyewa.name as nama_penyewa', 'produk.nama as nama_produk')
            ->orderByDesc('penyewaan.created_at')
            ->distinct()
            ->take(5)
            ->get();
        $recentAdPayments = PembayaranIklan::with(['user', 'iklan'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Data untuk Iklan Suspend yang Sudah Direvisi Mitra
        $revisedAdsQuery = \App\Models\IklanSuspend::with(['user', 'iklan', 'detailIklan'])
            ->join('detail_iklan', 'iklan_suspends.id_detail_iklan', '=', 'detail_iklan.id')
            ->where('detail_iklan.status_iklan', 'Nonaktif')
            ->where('iklan_suspends.status_revisi', 'sudah_diperbaiki')
            ->select('iklan_suspends.*')
            ->orderByDesc('iklan_suspends.updated_at');

        if ($search !== '') {
            $revisedAdsQuery->where(function ($q) use ($search) {
                $q->where('iklan_suspends.alasan', 'like', "%{$search}%")
                  ->orWhere('iklan_suspends.catatan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('name_store', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('iklan', function ($iq) use ($search) {
                      $iq->where('judul', 'like', "%{$search}%");
                  });
            });
        }
        $this->applyTimeFilter($revisedAdsQuery, 'iklan_suspends.updated_at', $timeFilter);
        if ($statusFilter === 'unread') {
            $revisedAdsQuery->whereNull('iklan_suspends.read_at');
        } elseif ($statusFilter === 'read') {
            $revisedAdsQuery->whereNotNull('iklan_suspends.read_at');
        }
        $revisedAdsList = $revisedAdsQuery->paginate(10, ['*'], 'rev_ads_page')->withQueryString();
        $recentRevisedAds = \App\Models\IklanSuspend::with(['user', 'iklan'])
            ->join('detail_iklan', 'iklan_suspends.id_detail_iklan', '=', 'detail_iklan.id')
            ->where('detail_iklan.status_iklan', 'Nonaktif')
            ->where('iklan_suspends.status_revisi', 'sudah_diperbaiki')
            ->select('iklan_suspends.*')
            ->orderByDesc('iklan_suspends.updated_at')
            ->take(5)
            ->get();

        // Data untuk Produk Suspend yang Sudah Direvisi Mitra
        $revisedProductsQuery = \App\Models\ProdukSuspend::with(['user', 'produk.foto', 'admin'])
            ->where('status_revisi', 'sudah_diperbaiki')
            ->orderByDesc('updated_at');

        if ($search !== '') {
            $revisedProductsQuery->where(function ($q) use ($search) {
                $q->where('alasan', 'like', "%{$search}%")
                  ->orWhere('catatan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('name_store', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('produk', function ($pq) use ($search) {
                      $pq->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        $this->applyTimeFilter($revisedProductsQuery, 'produk_suspends.updated_at', $timeFilter);
        if ($statusFilter === 'unread') {
            $revisedProductsQuery->whereNull('read_at');
        } elseif ($statusFilter === 'read') {
            $revisedProductsQuery->whereNotNull('read_at');
        }
        $revisedProductsList = $revisedProductsQuery->paginate(10, ['*'], 'rev_prod_page')->withQueryString();
        $recentRevisedProducts = \App\Models\ProdukSuspend::with(['user', 'produk'])
            ->where('status_revisi', 'sudah_diperbaiki')
            ->orderByDesc('updated_at')
            ->take(5)
            ->get();

        return view('developers.aktivitas-notifikasi', [
            'title'               => 'Notifikasi Aktivitas | Developer KampSewa',
            'currentTab'          => $currentTab,
            'statusFilter'        => $statusFilter,
            'timeFilter'          => $timeFilter,
            'search'              => $search,
            'totalUnreadCount'    => $totalUnreadCount,
            'unreadUsersCount'      => $unreadUsersCount,
            'unreadFeedbackCount'   => $unreadFeedbackCount,
            'unreadAdPaymentsCount' => $unreadAdPaymentsCount,
            'unreadRevisedAdsCount' => $unreadRevisedAdsCount,
            'unreadRevisedProductsCount' => $unreadRevisedProductsCount,
            'totalUsersToday'          => $totalUsersToday,
            'totalFeedbackToday'       => $totalFeedbackToday,
            'totalRentalsToday'        => $totalRentalsToday,
            'totalAdTransactionsToday' => $totalAdTransactionsToday,
            'totalAdRevenueToday'      => $totalAdRevenueToday,
            'totalAdsExpiring'         => $totalAdsExpiring,
            'totalBadRatings'          => $totalBadRatings,
            'usersList'                => $usersList,
            'feedbackList'             => $feedbackList,
            'rentalsList'              => $rentalsList,
            'adsList'                  => $adsList,
            'adPaymentsList'           => $adPaymentsList,
            'ratingsList'              => $ratingsList,
            'revisedAdsList'           => $revisedAdsList,
            'revisedProductsList'      => $revisedProductsList,
            'recentUsers'              => $recentUsers,
            'recentFeedbacks'          => $recentFeedbacks,
            'recentRentals'            => $recentRentals,
            'recentAdPayments'         => $recentAdPayments,
            'recentRevisedAds'         => $recentRevisedAds,
            'recentRevisedProducts'    => $recentRevisedProducts,
        ]);
    }

    /**
     * Tandai SEMUA notifikasi sebagai sudah dibaca.
     */
    public function markAllAsRead(Request $request)
    {
        try {
            DB::transaction(function () {
                // 1. Update status_notifikasi_user yang berstatus unread
                StatusNotifikasiUser::where('status', 'unread')->update(['status' => 'read']);

                // Pastikan jika ada user type 0 yang belum memiliki status_notifikasi_user tercover
                $usersWithoutStatus = User::where('type', 0)
                    ->whereNotIn('id', function ($query) {
                        $query->select('id_user')->from('status_notifikasi_user');
                    })
                    ->pluck('id');

                foreach ($usersWithoutStatus as $userId) {
                    StatusNotifikasiUser::create([
                        'id_user' => $userId,
                        'status'  => 'read',
                    ]);
                }

                // 2. Update feedback unread
                Feedback::whereNull('read_at')->update(['read_at' => now()]);

                // 3. Update feedback message dari customer yang belum dibaca
                FeedbackMessage::where('sender_type', 'customer')
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);

                // 4. Update transaksi sewa iklan mitra yang belum dibaca
                PembayaranIklan::whereNull('read_at')->update(['read_at' => now()]);

                // 5. Update notifikasi iklan suspend yang sudah direvisi mitra
                \App\Models\IklanSuspend::where('status_revisi', 'sudah_diperbaiki')
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);

                // 6. Update notifikasi produk suspend yang sudah direvisi mitra
                \App\Models\ProdukSuspend::where('status_revisi', 'sudah_diperbaiki')
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            });

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Semua notifikasi aktivitas berhasil ditandai sebagai dibaca.',
                ]);
            }

            Alert::toast('Semua notifikasi berhasil ditandai sebagai dibaca.', 'success');
            return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menandai notifikasi: ' . $e->getMessage(),
                ], 500);
            }

            Alert::toast('Gagal menandai notifikasi.', 'error');
            return back()->with('error', 'Gagal menandai notifikasi.');
        }
    }

    /**
     * Tandai satu item notifikasi sebagai dibaca.
     */
    public function markItemAsRead(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');

        try {
            if ($type === 'user') {
                StatusNotifikasiUser::updateOrCreate(
                    ['id_user' => $id],
                    ['status' => 'read']
                );
            } elseif ($type === 'feedback') {
                Feedback::where('id', $id)->update(['read_at' => now()]);
                FeedbackMessage::where('feedback_id', $id)
                    ->where('sender_type', 'customer')
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            } elseif ($type === 'ad_payment') {
                PembayaranIklan::where('id', $id)->update(['read_at' => now()]);
            } elseif ($type === 'iklan_revisi' || $type === 'ad_revision') {
                \App\Models\IklanSuspend::where('id', $id)->update(['read_at' => now()]);
            } elseif ($type === 'produk_revisi' || $type === 'product_revision') {
                \App\Models\ProdukSuspend::where('id', $id)->update(['read_at' => now()]);
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi berhasil ditandai sebagai dibaca.',
                ]);
            }

            return back()->with('success', 'Notifikasi berhasil ditandai sebagai dibaca.');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal memproses permintaan.');
        }
    }

    /**
     * Helper filter waktu
     */
    private function applyTimeFilter($query, $column, $timeFilter)
    {
        return match ($timeFilter) {
            'today'      => $query->whereDate($column, Carbon::today()),
            'this_week'  => $query->whereBetween($column, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
            'this_month' => $query->whereMonth($column, Carbon::now()->month)->whereYear($column, Carbon::now()->year),
            default      => $query,
        };
    }
}
