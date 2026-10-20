<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\PemasukanPerusahaan;
use App\Models\PembayaranIklan;
use App\Models\PembayaranPenyewaan;
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

        // Statistik Tambahan Sumber Penghasilan & Rata-rata Bulan Ini
        $currentDayCount = max(1, Carbon::now()->day);
        $avgDailyIncomeMonth = (int) ($monthCurrentTotal / $currentDayCount);
        $totalAdRevenueMonth = (int) PembayaranIklan::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where(function ($q) {
                $q->where('status_bayar', 'lunas')
                  ->orWhere('midtrans_transaction_status', 'settlement');
            })
            ->sum('total_bayar');
        $totalCommissionMonth = (int) PembayaranPenyewaan::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('pajak_platform');
        $highestTransactionMonth = PemasukanPerusahaan::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->orderByDesc('nominal')
            ->first();

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

        // 10. List Pemasukan dengan Relasi User, Filter Sumber, Pencarian dan Paginasi
        $search = $request->query('search');
        $sumberFilter = $request->query('sumber');
        $query = PemasukanPerusahaan::with('user')->orderByDesc('created_at')->orderByDesc('id');

        if ($sumberFilter && $sumberFilter !== 'all') {
            if ($sumberFilter === 'manual') {
                $query->whereNotNull('id_user');
            } elseif ($sumberFilter === 'otomatis') {
                $query->whereNull('id_user');
            } else {
                $query->where('sumber', 'like', "%{$sumberFilter}%");
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sumber', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('nominal', 'like', "%{$search}%")
                    ->orWhereDate('created_at', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $list_pemasukan = $query->paginate(10)->withQueryString();

        // 11. Distribusi Sumber Pemasukan untuk Filter Chips
        $distinctSumbers = PemasukanPerusahaan::select('sumber', DB::raw('count(*) as count'), DB::raw('sum(nominal) as total'))
            ->groupBy('sumber')
            ->orderByDesc('total')
            ->get();

        $manualPemasukanCount = PemasukanPerusahaan::whereNotNull('id_user')->count();
        $manualPemasukanTotal = PemasukanPerusahaan::whereNotNull('id_user')->sum('nominal');

        $otomatisPemasukanCount = PemasukanPerusahaan::whereNull('id_user')->count();
        $otomatisPemasukanTotal = PemasukanPerusahaan::whereNull('id_user')->sum('nominal');

        // 12. Statistik & Informasi Admin yang Menginputkan Pemasukan Manual
        $adminPemasukanStats = User::whereHas('pemasukanPerusahaan')
            ->withCount('pemasukanPerusahaan')
            ->withSum('pemasukanPerusahaan', 'nominal')
            ->orderByDesc('pemasukan_perusahaan_count')
            ->get();

        $latestManualPemasukan = PemasukanPerusahaan::with('user')
            ->whereNotNull('id_user')
            ->latest('created_at')
            ->first();

        $oldestManualPemasukan = PemasukanPerusahaan::with('user')
            ->whereNotNull('id_user')
            ->oldest('created_at')
            ->first();

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
            'sumberFilter' => $sumberFilter,
            'distinctSumbers' => $distinctSumbers,
            'manualPemasukanCount' => $manualPemasukanCount,
            'manualPemasukanTotal' => $manualPemasukanTotal,
            'otomatisPemasukanCount' => $otomatisPemasukanCount,
            'otomatisPemasukanTotal' => $otomatisPemasukanTotal,
            'adminPemasukanStats' => $adminPemasukanStats,
            'latestManualPemasukan' => $latestManualPemasukan,
            'oldestManualPemasukan' => $oldestManualPemasukan,
            'avgDailyIncomeMonth' => $avgDailyIncomeMonth,
            'totalAdRevenueMonth' => $totalAdRevenueMonth,
            'totalCommissionMonth' => $totalCommissionMonth,
            'highestTransactionMonth' => $highestTransactionMonth,
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
            $pemasukan->id_user = Auth::id();
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

    public function updatePenghasilan(Request $request, $id)
    {
        try {
            $request->validate([
                'sumber' => 'required|string|max:100|min:2',
                'deskripsi' => 'required|string|max:255|min:2',
                'nominal' => 'required|numeric|min:0',
            ]);

            $pemasukan = PemasukanPerusahaan::findOrFail($id);
            $pemasukan->sumber = ucwords(trim($request->sumber));
            $pemasukan->deskripsi = trim($request->deskripsi);
            $pemasukan->nominal = (int) $request->nominal;
            $pemasukan->save();

            Alert::toast('Data pemasukan berhasil diperbarui', 'success');
            return back();
        } catch (\Exception $error) {
            Log::error('Error update penghasilan: ' . $error->getMessage());
            Alert::toast('Gagal memperbarui data: ' . $error->getMessage(), 'error');
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

    public function exportPenghasilan()
    {
        $pemasukan = PemasukanPerusahaan::orderByDesc('created_at')->orderByDesc('id')->get();
        $filename = 'laporan-pemasukan-kampsewa-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($pemasukan) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM
            fputcsv($file, ['No', 'ID Transaksi', 'Sumber Pemasukan', 'Deskripsi', 'Nominal (Rp)', 'Tanggal Transaksi']);

            $no = 1;
            foreach ($pemasukan as $row) {
                fputcsv($file, [
                    $no++,
                    $row->id,
                    $row->sumber,
                    $row->deskripsi,
                    $row->nominal,
                    $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function templateCsv()
    {
        $filename = 'template-import-pemasukan.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Sumber', 'Deskripsi', 'Nominal']);
            fputcsv($file, ['Layanan Iklan', 'Pendapatan promosi banner mitra outdoor', 500000]);
            fputcsv($file, ['Pajak Platform', 'Bagi hasil sewa alat camping dan tenda', 150000]);
            fputcsv($file, ['Kemitraan Mitra', 'Biaya verifikasi badge mitra resmi', 250000]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importPenghasilan(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        try {
            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');

            $bom = fread($file, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($file);
            }

            $header = fgetcsv($file, 1000, ',');
            if (!$header || count($header) < 2) {
                rewind($file);
                $header = fgetcsv($file, 1000, ';');
                $delimiter = ';';
            } else {
                $delimiter = ',';
            }

            $importedCount = 0;
            DB::beginTransaction();
            while (($data = fgetcsv($file, 1000, $delimiter)) !== false) {
                if (empty($data) || !isset($data[0])) continue;

                $sumber = trim($data[0] ?? '');
                $deskripsi = trim($data[1] ?? '');
                $nominalStr = preg_replace('/[^0-9]/', '', $data[2] ?? '0');
                $nominal = (int) $nominalStr;

                if ($sumber !== '' && $nominal > 0) {
                    PemasukanPerusahaan::create([
                        'id_user' => Auth::id(),
                        'sumber' => ucwords($sumber),
                        'deskripsi' => $deskripsi ?: 'Pemasukan dari import data',
                        'nominal' => $nominal,
                    ]);
                    $importedCount++;
                }
            }
            DB::commit();
            fclose($file);

            Alert::toast("Berhasil mengimpor $importedCount data pemasukan.", 'success');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import penghasilan error: ' . $e->getMessage());
            Alert::toast('Gagal mengimpor file: ' . $e->getMessage(), 'error');
            return back();
        }
    }
}
