<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\PemasukanPerusahaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class PenghasilanController extends Controller
{
    public function __construct()
    {
        $this->middleware('dev');
    }

    public function index(Request $request)
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $lastMonthDate = Carbon::now()->subMonth();

        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $bulanMap = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $todayNameIndo = $hariMap[Carbon::now()->format('l')] ?? Carbon::now()->format('l');
        $currentMonthNameIndo = $bulanMap[$currentMonth] ?? Carbon::now()->format('F');
        $lastMonthNameIndo = $bulanMap[$lastMonthDate->month] ?? $lastMonthDate->format('F');

        // 1. Ambil user baru terdaftar hari ini untuk notifikasi navbar
        $user_baru_terdaftar = User::select('users.*')
            ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
            ->where('users.type', 0)
            ->whereDate('users.created_at', Carbon::today())
            ->where('status_notifikasi_user.status', 'unread')
            ->orderByDesc('users.created_at')
            ->limit(10)
            ->get();

        // 2. Total Customer
        $totalCustomer = User::where('type', 0)->count();

        // 3. Total Penghasilan Tahunan (Tahun ini vs Tahun lalu)
        $penghasilan_tahun_ini = (int) PemasukanPerusahaan::whereYear('created_at', $currentYear)->sum('nominal');
        $penghasilan_tahun_lalu = (int) PemasukanPerusahaan::whereYear('created_at', $currentYear - 1)->sum('nominal');

        if ($penghasilan_tahun_lalu > 0) {
            $persentase_perubahan = (($penghasilan_tahun_ini - $penghasilan_tahun_lalu) / $penghasilan_tahun_lalu) * 100;
        } else {
            $persentase_perubahan = $penghasilan_tahun_ini > 0 ? 100 : 0;
        }

        // 4. Total Penghasilan Bulanan (Bulan ini vs Bulan lalu)
        $monthCurrentTotal = (int) PemasukanPerusahaan::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('nominal');

        $monthPreviousTotal = (int) PemasukanPerusahaan::whereYear('created_at', $lastMonthDate->year)
            ->whereMonth('created_at', $lastMonthDate->month)
            ->sum('nominal');

        if ($monthPreviousTotal > 0) {
            $persentase_perubahan_bulan = (($monthCurrentTotal - $monthPreviousTotal) / $monthPreviousTotal) * 100;
        } else {
            $persentase_perubahan_bulan = $monthCurrentTotal > 0 ? 100 : 0;
        }

        // 5. Total Penghasilan Mingguan (Minggu ini vs Minggu lalu)
        $weekCurrentTotal = (int) PemasukanPerusahaan::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->sum('nominal');

        $weekPreviousTotal = (int) PemasukanPerusahaan::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->sum('nominal');

        if ($weekPreviousTotal > 0) {
            $persentase_perubahan_minggu = (($weekCurrentTotal - $weekPreviousTotal) / $weekPreviousTotal) * 100;
        } else {
            $persentase_perubahan_minggu = $weekCurrentTotal > 0 ? 100 : 0;
        }

        $currentWeekNumber = min(4, (int) ceil(Carbon::now()->day / 7));
        $previousWeekNumber = max(1, $currentWeekNumber - 1);

        // 6. Total Penghasilan Harian (Hari ini vs Kemarin)
        $dayCurrentTotal = (int) PemasukanPerusahaan::whereDate('created_at', Carbon::today())->sum('nominal');
        $dayPreviousTotal = (int) PemasukanPerusahaan::whereDate('created_at', Carbon::yesterday())->sum('nominal');

        // 7. Data Grafik 12 Bulan Tahun Berjalan (#penghasilan)
        $monthlyIncomeData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyIncomeData[] = (int) PemasukanPerusahaan::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $m)
                ->sum('nominal');
        }

        // 8. Data Grafik 4 Bulan Terakhir (#penghasilan-perbulan)
        $recentMonthsLabels = [];
        $recentMonthsData = [];
        for ($i = 3; $i >= 0; $i--) {
            $dt = Carbon::now()->subMonths($i);
            $recentMonthsLabels[] = $bulanMap[$dt->month] ?? $dt->format('M');
            $recentMonthsData[] = (int) PemasukanPerusahaan::whereYear('created_at', $dt->year)
                ->whereMonth('created_at', $dt->month)
                ->sum('nominal');
        }

        // 9. Data Grafik 4 Minggu Bulan Ini (#penghasilan-perminggu)
        $weeklyData = [
            (int) PemasukanPerusahaan::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->whereBetween(DB::raw('DAY(created_at)'), [1, 7])
                ->sum('nominal'),
            (int) PemasukanPerusahaan::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->whereBetween(DB::raw('DAY(created_at)'), [8, 14])
                ->sum('nominal'),
            (int) PemasukanPerusahaan::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->whereBetween(DB::raw('DAY(created_at)'), [15, 21])
                ->sum('nominal'),
            (int) PemasukanPerusahaan::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->whereBetween(DB::raw('DAY(created_at)'), [22, 31])
                ->sum('nominal'),
        ];

        // 10. List Pemasukan dengan fitur Pencarian dan Paginasi
        $search = $request->query('search');
        $query = PemasukanPerusahaan::orderByDesc('created_at')->orderByDesc('id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sumber', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('nominal', 'like', "%{$search}%")
                    ->orWhereDate('created_at', 'like', "%{$search}%");
            });
        }

        $list_pemasukan = $query->paginate(10)->withQueryString();

        return view('developers.penghasilan', [
            'title' => 'Penghasilan',
            'user_baru_terdaftar' => $user_baru_terdaftar,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,
            'todayNameIndo' => $todayNameIndo,
            'currentMonthNameIndo' => $currentMonthNameIndo,
            'lastMonthNameIndo' => $lastMonthNameIndo,
            'totalCustomer' => $totalCustomer,
            'penghasilan_tahun_ini' => $penghasilan_tahun_ini,
            'penghasilan_tahun_lalu' => $penghasilan_tahun_lalu,
            'persentase_perubahan' => $persentase_perubahan,
            'monthCurrentTotal' => $monthCurrentTotal,
            'monthPreviousTotal' => $monthPreviousTotal,
            'persentase_perubahan_bulan' => $persentase_perubahan_bulan,
            'weekCurrentTotal' => $weekCurrentTotal,
            'weekPreviousTotal' => $weekPreviousTotal,
            'persentase_perubahan_minggu' => $persentase_perubahan_minggu,
            'currentWeekNumber' => $currentWeekNumber,
            'previousWeekNumber' => $previousWeekNumber,
            'dayCurrentTotal' => $dayCurrentTotal,
            'dayPreviousTotal' => $dayPreviousTotal,
            'monthlyIncomeData' => $monthlyIncomeData,
            'recentMonthsLabels' => $recentMonthsLabels,
            'recentMonthsData' => $recentMonthsData,
            'weeklyData' => $weeklyData,
            'list_pemasukan' => $list_pemasukan,
            'search' => $search,
        ]);
    }

    public function tambahPenghasilan(Request $request)
    {
        try {
            $request->validate([
                'sumber' => 'required|string|max:100|min:3',
                'deskripsi' => 'required|string|max:255|min:3',
                'nominal' => 'required|numeric|min:0',
            ]);

            $pemasukan = new PemasukanPerusahaan();
            $pemasukan->sumber = ucwords(trim($request->sumber));
            $pemasukan->deskripsi = trim($request->deskripsi);
            $pemasukan->nominal = (int) $request->nominal;
            $pemasukan->save();

            Alert::toast('Data pemasukan berhasil disimpan', 'success');
            return back();
        } catch (\Exception $error) {
            Log::error('Error tambah penghasilan: ' . $error->getMessage());
            Alert::toast('Gagal menyimpan data: ' . $error->getMessage(), 'error');
            return back();
        }
    }

    public function hapusPenghasilan($id)
    {
        try {
            $pemasukan = PemasukanPerusahaan::findOrFail($id);
            $pemasukan->delete();

            Alert::toast('Data pemasukan berhasil dihapus', 'success');
            return back();
        } catch (\Exception $error) {
            Log::error('Error hapus pemasukan: ' . $error->getMessage());
            Alert::toast('Gagal menghapus data', 'error');
            return back();
        }
    }
}
