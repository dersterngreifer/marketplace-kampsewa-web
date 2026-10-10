<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckUserLogin;
use App\Models\Feedback;
use App\Models\PemasukanPerusahaan;
use App\Models\PembayaranPenyewaan;
use App\Models\PengeluaranPerusahaan;
use App\Models\StatusNotifikasiUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('dev');
    }
    public function index()
    {
        // -- variable list consume
        $currentDate = Carbon::now();
        $startOfCurrentMonth = $currentDate->copy()->startOfMonth();
        $endOfCurrentMonth = $currentDate->copy()->endOfMonth();
        $startOfPreviousMonth = $startOfCurrentMonth->copy()->subMonth()->startOfMonth();
        $endOfPreviousMonth = $startOfCurrentMonth->copy()->subMonth()->endOfMonth();
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // -- ambil user berdasarkan yang baru saja terdaftar
        $user_baru_terdaftar = User::select('users.*')
            ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
            ->where('users.type', 0)
            ->whereDate('users.created_at', Carbon::today())
            ->where('status_notifikasi_user.status', 'unread')
            ->orderByDesc('users.created_at')->limit(10)
            ->get();

        // -- Ambil total pengguna (customer)
        $total_pengguna = User::where('type', 0)->count();

        // -- total perbandingan jumlah customer minggu lalu dan sekarang
        $totalUsersPreviousMonth = User::where('type', 0)
            ->whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])
            ->count();
        $totalUsersCurrentMonth = User::where('type', 0)
            ->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])
            ->count();
        if ($totalUsersPreviousMonth == 0) {
            $percentageChange = $totalUsersCurrentMonth > 0 ? 100 : 0;
        } else {
            $percentageChange = (($totalUsersCurrentMonth - $totalUsersPreviousMonth) / $totalUsersPreviousMonth) * 100;
        }
        $percentageChange = round($percentageChange, 2);

        // -- total feedback
        $total_feedback = Feedback::count();

        // -- total feedback perbandingan bulan lalu dan sekarang
        $totalFeedbackUsersPreviousMonth = Feedback::whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])->count();
        $totalFeedbackUsersCurrentMonth = Feedback::whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->count();
        if ($totalFeedbackUsersPreviousMonth == 0) {
            $percentageFeedbackChange = $totalFeedbackUsersCurrentMonth > 0 ? 100 : 0;
        } else {
            $percentageFeedbackChange = (($totalFeedbackUsersCurrentMonth - $totalFeedbackUsersPreviousMonth) / $totalFeedbackUsersPreviousMonth) * 100;
        }
        $percentageFeedbackChange = round($percentageFeedbackChange, 2);

        $total_mitra = DB::table('produk')
            ->whereIn('id_user', function ($query) {
                $query->select('id')
                    ->from('users')
                    ->where('type', 0);
            })
            ->distinct()
            ->count('id_user');

        $totalMitraUsersPreviousMonth = User::where('type', 0)
            ->whereExists(function ($query) use ($startOfPreviousMonth, $endOfPreviousMonth) {
                $query->select(DB::raw(1))
                    ->from('produk')
                    ->whereColumn('users.id', 'produk.id_user')
                    ->whereBetween('produk.created_at', [$startOfPreviousMonth, $endOfPreviousMonth]);
            })
            ->count();

        $totalMitraUsersCurrentMonth = User::where('type', 0)
            ->whereExists(function ($query) use ($startOfCurrentMonth, $endOfCurrentMonth) {
                $query->select(DB::raw(1))
                    ->from('produk')
                    ->whereColumn('users.id', 'produk.id_user')
                    ->whereBetween('produk.created_at', [$startOfCurrentMonth, $endOfCurrentMonth]);
            })
            ->count();

        if ($totalMitraUsersPreviousMonth == 0) {
            $percentageMitraChange = $totalMitraUsersCurrentMonth > 0 ? 100 : 0;
        } else {
            $percentageMitraChange = (($totalMitraUsersCurrentMonth - $totalMitraUsersPreviousMonth) / $totalMitraUsersPreviousMonth) * 100;
        }
        $percentageMitraChange = round($percentageMitraChange, 2);

        // -- total pemasukan bulan ini
        $pemasukan_bulan_ini = PemasukanPerusahaan::whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->sum('nominal');
        $pemasukan_bulan_ini_ldr = number_format($pemasukan_bulan_ini, 0, ',', '.');

        // -- total pemasukan bulan lalu
        $pemasukan_bulan_lalu = PemasukanPerusahaan::whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])->sum('nominal');
        $pemasukan_bulan_lalu_ldr = number_format($pemasukan_bulan_lalu, 0, ',', '.');

        // -- perbandingan pemasukan bulan lalu dan sekarang
        if ($pemasukan_bulan_lalu == 0) {
            $percentagePemasukanChange = $pemasukan_bulan_ini > 0 ? 100 : 0;
        } else {
            $percentagePemasukanChange = (($pemasukan_bulan_ini - $pemasukan_bulan_lalu) / $pemasukan_bulan_lalu) * 100;
        }
        $percentagePemasukanChange = round($percentagePemasukanChange, 2);

        // -- total pengeluaran bulan ini
        $pengeluaran_bulan_ini = PengeluaranPerusahaan::whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->sum('nominal');
        $pengeluaran_bulan_ini_ldr = number_format($pengeluaran_bulan_ini, 0, ',', '.');

        // -- total pengeluaran bulan lalu
        $pengeluaran_bulan_lalu = PengeluaranPerusahaan::whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])->sum('nominal');
        $pengeluaran_bulan_lalu_ldr = number_format($pengeluaran_bulan_lalu, 0, ',', '.');

        // -- perbandingan pengeluaran bulan lalu dan sekarang
        if ($pengeluaran_bulan_lalu == 0) {
            $percentagePengeluaranChange = $pengeluaran_bulan_ini > 0 ? 100 : 0;
        } else {
            $percentagePengeluaranChange = (($pengeluaran_bulan_ini - $pengeluaran_bulan_lalu) / $pengeluaran_bulan_lalu) * 100;
        }
        $percentagePengeluaranChange = round($percentagePengeluaranChange, 2);

        // -- menghitung total keseluruhan nominal pemasukan dan nominal pengeluaran tahun saat ini
        $total_pemasukan_tahun_ini = PemasukanPerusahaan::whereYear('created_at', date('Y'))->sum('nominal');
        $total_pengeluaran_tahun_ini = PengeluaranPerusahaan::whereYear('created_at', date('Y'))->sum('nominal');

        // -- menghitung total keuntungan
        $total_keuntungan = $total_pemasukan_tahun_ini - $total_pengeluaran_tahun_ini;

        // Format total keuntungan
        if ($total_keuntungan >= 1000000) { // Jika total keuntungan >= 1 juta
            $formatted_keuntungan = number_format($total_keuntungan / 1000000, 0) . 'M';
        } else { // Jika total keuntungan < 1 juta
            $formatted_keuntungan = number_format($total_keuntungan, 0);
        }

        // Hitung total kerugian
        $total_kerugian = abs($total_pengeluaran_tahun_ini - $total_pemasukan_tahun_ini);

        // Format total kerugian
        if ($total_kerugian >= 1000000) {
            $formatted_kerugian = number_format($total_kerugian / 1000000, 0) . 'M';
        } else if ($total_kerugian >= 100000) {
            $formatted_kerugian = number_format($total_kerugian / 100000, 0) . 'K';
        } else {
            $formatted_kerugian = number_format($total_kerugian, 0);
        }

        /*
        |--------------------------------------------------------------------------
        |-- get customer baru bulan ini
        |--------------------------------------------------------------------------
        */

        $_get_customer_baru_bulan_ini = User::where('type', 0)
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)->limit(5)->orderBy('created_at', 'desc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        |-- get customer online
        |--------------------------------------------------------------------------
        */

        $_get_customer_online = User::where('type', 0)->where('status', 'online')->paginate(20);
        $total_transaksi = PembayaranPenyewaan::count();

        return view('developers.dashboard', [
            'title' => 'Dashboard | Developer Kamp Sewa',
            'user_baru_terdaftar' => $user_baru_terdaftar,
            'total_pengguna' => $total_pengguna,
            'percentageChange' => $percentageChange,
            'total_feedback' => $total_feedback,
            'percentageFeedbackChange' => $percentageFeedbackChange,
            'total_mitra' => $total_mitra,
            'percentageMitraChange' => $percentageMitraChange,
            'pemasukan_bulan_ini' => $pemasukan_bulan_ini_ldr,
            'pemasukan_bulan_lalu' => $pemasukan_bulan_lalu_ldr,
            'percentagePemasukanChange' => $percentagePemasukanChange,
            'pengeluaran_bulan_ini' => $pengeluaran_bulan_ini_ldr,
            'pengeluaran_bulan_lalu' => $pengeluaran_bulan_lalu_ldr,
            'percentagePengeluaranChange' => $percentagePengeluaranChange,
            'total_keuntungan_tahun_ini' => $formatted_keuntungan,
            'total_kerugian_tahun_ini' => $formatted_kerugian,
            'customer_baru_bulan_ini' => $_get_customer_baru_bulan_ini,
            'customer_online' => $_get_customer_online,
            'total_transaksi' => $total_transaksi,
        ]);
    }


    public function markNotificationAsRead()
    {
        $statusNotifikasi = StatusNotifikasiUser::where('status', 'unread')->get();
        foreach ($statusNotifikasi as $status) {
            $status->update(['status' => 'read']);
        }
        return back();
    }
}
