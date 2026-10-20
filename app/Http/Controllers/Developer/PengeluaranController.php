<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\PengeluaranPerusahaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class PengeluaranController extends Controller
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

        // 2. Total Pengeluaran Tahunan (Tahun ini vs Tahun lalu)
        $pengeluaran_tahun_ini = (int) PengeluaranPerusahaan::whereYear('created_at', $currentYear)->sum('nominal');
        $pengeluaran_tahun_lalu = (int) PengeluaranPerusahaan::whereYear('created_at', $currentYear - 1)->sum('nominal');
        $pengeluaran_dua_tahun_lalu = (int) PengeluaranPerusahaan::whereYear('created_at', $currentYear - 2)->sum('nominal');

        if ($pengeluaran_tahun_lalu > 0) {
            $persentase_perubahan = (($pengeluaran_tahun_ini - $pengeluaran_tahun_lalu) / $pengeluaran_tahun_lalu) * 100;
        } else {
            $persentase_perubahan = $pengeluaran_tahun_ini > 0 ? 100 : 0;
        }

        // 3. Total Pengeluaran Bulanan (Bulan ini vs Bulan lalu)
        $monthCurrentTotal = (int) PengeluaranPerusahaan::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('nominal');

        $monthPreviousTotal = (int) PengeluaranPerusahaan::whereYear('created_at', $lastMonthDate->year)
            ->whereMonth('created_at', $lastMonthDate->month)
            ->sum('nominal');

        if ($monthPreviousTotal > 0) {
            $persentase_perubahan_bulan = (($monthCurrentTotal - $monthPreviousTotal) / $monthPreviousTotal) * 100;
        } else {
            $persentase_perubahan_bulan = $monthCurrentTotal > 0 ? 100 : 0;
        }

        // 4. Total Pengeluaran Mingguan (Minggu ini vs Minggu lalu)
        $weekCurrentTotal = (int) PengeluaranPerusahaan::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->sum('nominal');

        $weekPreviousTotal = (int) PengeluaranPerusahaan::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->sum('nominal');

        if ($weekPreviousTotal > 0) {
            $persentase_perubahan_minggu = (($weekCurrentTotal - $weekPreviousTotal) / $weekPreviousTotal) * 100;
        } else {
            $persentase_perubahan_minggu = $weekCurrentTotal > 0 ? 100 : 0;
        }

        // 5. Total Pengeluaran Harian (Hari ini vs Kemarin)
        $dayCurrentTotal = (int) PengeluaranPerusahaan::whereDate('created_at', Carbon::today())->sum('nominal');
        $dayPreviousTotal = (int) PengeluaranPerusahaan::whereDate('created_at', Carbon::yesterday())->sum('nominal');

        // Rata-rata pengeluaran harian
        $currentDayCount = max(1, Carbon::now()->day);
        $avgDailyExpenseMonth = (int) ($monthCurrentTotal / $currentDayCount);

        // 6. Data Grafik 12 Bulan Tahun Berjalan (#chart-pengeluaran-pertahun)
        $monthlyExpenseData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyExpenseData[] = (int) PengeluaranPerusahaan::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $m)
                ->sum('nominal');
        }

        // 7. Data Grafik 12 Bulan untuk Line Chart (#chart-pengeluaran-perbulan)
        $recentMonthsLabels = [];
        $recentMonthsData = [];
        for ($i = 11; $i >= 0; $i--) {
            $dt = Carbon::now()->subMonths($i);
            $recentMonthsLabels[] = $bulanMap[$dt->month] ?? $dt->format('M');
            $recentMonthsData[] = (int) PengeluaranPerusahaan::whereYear('created_at', $dt->year)
                ->whereMonth('created_at', $dt->month)
                ->sum('nominal');
        }

        // 8. Breakdown Kategori Pengeluaran
        $kategoriList = PengeluaranPerusahaan::select(
            'kategori_pengeluaran',
            DB::raw('SUM(nominal) as total'),
            DB::raw('COUNT(*) as total_transaksi')
        )
            ->groupBy('kategori_pengeluaran')
            ->orderByDesc('total')
            ->get();

        // 9. List Pengeluaran Baru-Baru Ini (5 data terakhir)
        $pengeluaranBaruIni = PengeluaranPerusahaan::orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        // 10. List Data Pengeluaran dengan Relasi User, Search dan Pagination
        $search = $request->query('search');
        $kategoriFilter = $request->query('kategori');

        $query = PengeluaranPerusahaan::with('user')->orderByDesc('created_at')->orderByDesc('id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sumber', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('kategori_pengeluaran', 'like', "%{$search}%")
                    ->orWhere('nominal', 'like', "%{$search}%")
                    ->orWhereDate('created_at', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($kategoriFilter && $kategoriFilter !== 'all') {
            if ($kategoriFilter === 'manual') {
                $query->whereNotNull('id_user');
            } elseif ($kategoriFilter === 'otomatis') {
                $query->whereNull('id_user');
            } else {
                $query->where('kategori_pengeluaran', $kategoriFilter);
            }
        }

        $list_pengeluaran = $query->paginate(10)->withQueryString();

        // 11. Statistik & Informasi Admin yang Menginputkan Pengeluaran
        $adminPengeluaranStats = User::whereHas('pengeluaranPerusahaan')
            ->withCount('pengeluaranPerusahaan')
            ->withSum('pengeluaranPerusahaan', 'nominal')
            ->orderByDesc('pengeluaran_perusahaan_count')
            ->get();

        $latestPengeluaran = PengeluaranPerusahaan::with('user')
            ->whereNotNull('id_user')
            ->latest('created_at')
            ->first();

        $oldestPengeluaran = PengeluaranPerusahaan::with('user')
            ->whereNotNull('id_user')
            ->oldest('created_at')
            ->first();

        $totalPengeluaranAll = PengeluaranPerusahaan::count();
        $totalAdminInputCount = PengeluaranPerusahaan::whereNotNull('id_user')->count();
        $totalAdminInputNominal = PengeluaranPerusahaan::whereNotNull('id_user')->sum('nominal');
        $totalOtomatisInputCount = PengeluaranPerusahaan::whereNull('id_user')->count();

        return view('developers.pengeluaran', [
            'title' => 'Pengeluaran',
            'user_baru_terdaftar' => $user_baru_terdaftar,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,
            'todayNameIndo' => $todayNameIndo,
            'currentMonthNameIndo' => $currentMonthNameIndo,
            'lastMonthNameIndo' => $lastMonthNameIndo,
            'pengeluaran_tahun_ini' => $pengeluaran_tahun_ini,
            'pengeluaran_tahun_lalu' => $pengeluaran_tahun_lalu,
            'pengeluaran_dua_tahun_lalu' => $pengeluaran_dua_tahun_lalu,
            'persentase_perubahan' => $persentase_perubahan,
            'monthCurrentTotal' => $monthCurrentTotal,
            'monthPreviousTotal' => $monthPreviousTotal,
            'persentase_perubahan_bulan' => $persentase_perubahan_bulan,
            'weekCurrentTotal' => $weekCurrentTotal,
            'weekPreviousTotal' => $weekPreviousTotal,
            'persentase_perubahan_minggu' => $persentase_perubahan_minggu,
            'dayCurrentTotal' => $dayCurrentTotal,
            'dayPreviousTotal' => $dayPreviousTotal,
            'avgDailyExpenseMonth' => $avgDailyExpenseMonth,
            'monthlyExpenseData' => $monthlyExpenseData,
            'recentMonthsLabels' => $recentMonthsLabels,
            'recentMonthsData' => $recentMonthsData,
            'kategoriList' => $kategoriList,
            'pengeluaranBaruIni' => $pengeluaranBaruIni,
            'list_pengeluaran' => $list_pengeluaran,
            'search' => $search,
            'kategoriFilter' => $kategoriFilter,
            'adminPengeluaranStats' => $adminPengeluaranStats,
            'latestPengeluaran' => $latestPengeluaran,
            'oldestPengeluaran' => $oldestPengeluaran,
            'totalAdminInputCount' => $totalAdminInputCount,
            'totalAdminInputNominal' => $totalAdminInputNominal,
            'totalPengeluaranAll' => $totalPengeluaranAll,
            'totalOtomatisInputCount' => $totalOtomatisInputCount,
        ]);
    }

    public function tambahPengeluaran(Request $request)
    {
        try {
            $request->validate([
                'sumber' => 'required|string|max:100|min:2',
                'deskripsi' => 'required|string|max:255|min:2',
                'nominal' => 'required|numeric|min:0',
                'kategori_pengeluaran' => 'required|string|max:100',
            ]);

            $pengeluaran = new PengeluaranPerusahaan();
            $pengeluaran->id_user = Auth::id();
            $pengeluaran->sumber = ucwords(trim($request->sumber));
            $pengeluaran->deskripsi = trim($request->deskripsi);
            $pengeluaran->nominal = (int) $request->nominal;
            $pengeluaran->kategori_pengeluaran = trim($request->kategori_pengeluaran);
            $pengeluaran->save();

            Alert::toast('Data pengeluaran berhasil disimpan', 'success');
            return back();
        } catch (\Exception $error) {
            Log::error('Error tambah pengeluaran: ' . $error->getMessage());
            Alert::toast('Gagal menyimpan data: ' . $error->getMessage(), 'error');
            return back();
        }
    }

    public function updatePengeluaran(Request $request, $id)
    {
        try {
            $request->validate([
                'sumber' => 'required|string|max:100|min:2',
                'deskripsi' => 'required|string|max:255|min:2',
                'nominal' => 'required|numeric|min:0',
                'kategori_pengeluaran' => 'required|string|max:100',
            ]);

            $pengeluaran = PengeluaranPerusahaan::findOrFail($id);
            $pengeluaran->sumber = ucwords(trim($request->sumber));
            $pengeluaran->deskripsi = trim($request->deskripsi);
            $pengeluaran->nominal = (int) $request->nominal;
            $pengeluaran->kategori_pengeluaran = trim($request->kategori_pengeluaran);
            $pengeluaran->save();

            Alert::toast('Data pengeluaran berhasil diperbarui', 'success');
            return back();
        } catch (\Exception $error) {
            Log::error('Error update pengeluaran: ' . $error->getMessage());
            Alert::toast('Gagal memperbarui data: ' . $error->getMessage(), 'error');
            return back();
        }
    }

    public function hapusPengeluaran($id)
    {
        try {
            $pengeluaran = PengeluaranPerusahaan::findOrFail($id);
            $pengeluaran->delete();

            Alert::toast('Data pengeluaran berhasil dihapus', 'success');
            return back();
        } catch (\Exception $error) {
            Log::error('Error hapus pengeluaran: ' . $error->getMessage());
            Alert::toast('Gagal menghapus data', 'error');
            return back();
        }
    }

    public function exportPengeluaran()
    {
        $pengeluaran = PengeluaranPerusahaan::orderByDesc('created_at')->orderByDesc('id')->get();
        $filename = 'laporan-pengeluaran-kampsewa-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($pengeluaran) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM
            fputcsv($file, ['No', 'ID Transaksi', 'Kategori Pengeluaran', 'Sumber', 'Deskripsi', 'Nominal (Rp)', 'Tanggal Transaksi']);

            $no = 1;
            foreach ($pengeluaran as $row) {
                fputcsv($file, [
                    $no++,
                    $row->id,
                    $row->kategori_pengeluaran ?? 'Umum',
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
        $filename = 'template-import-pengeluaran.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Kategori', 'Sumber', 'Deskripsi', 'Nominal']);
            fputcsv($file, ['Server & Cloud', 'AWS & Cloudflare', 'Langganan server cloud dan database cluster', 6500000]);
            fputcsv($file, ['Operasional', 'Operasional Kantor', 'Biaya utilitas listrik dan internet kantor pusat', 1200000]);
            fputcsv($file, ['Maintenance', 'Keamanan & Tool', 'Lisensi audit keamanan dan software monitoring', 3000000]);
            fputcsv($file, ['Gaji & SDM', 'Gaji Tim Developer', 'Gaji dan honorarium tim teknis KampSewa', 15000000]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importPengeluaran(Request $request)
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

                // Support both formats:
                // Format A (4 cols): Kategori, Sumber, Deskripsi, Nominal
                // Format B (3 cols): Sumber, Deskripsi, Nominal (default kategori 'Operasional')
                if (count($data) >= 4) {
                    $kategori = trim($data[0] ?? 'Operasional');
                    $sumber = trim($data[1] ?? '');
                    $deskripsi = trim($data[2] ?? '');
                    $nominalStr = preg_replace('/[^0-9]/', '', $data[3] ?? '0');
                } else {
                    $kategori = 'Operasional';
                    $sumber = trim($data[0] ?? '');
                    $deskripsi = trim($data[1] ?? '');
                    $nominalStr = preg_replace('/[^0-9]/', '', $data[2] ?? '0');
                }

                $nominal = (int) $nominalStr;

                if ($sumber !== '' && $nominal > 0) {
                    PengeluaranPerusahaan::create([
                        'id_user' => Auth::id(),
                        'kategori_pengeluaran' => ucwords($kategori ?: 'Operasional'),
                        'sumber' => ucwords($sumber),
                        'deskripsi' => $deskripsi ?: 'Pengeluaran dari import data',
                        'nominal' => $nominal,
                    ]);
                    $importedCount++;
                }
            }
            DB::commit();
            fclose($file);

            Alert::toast("Berhasil mengimpor $importedCount data pengeluaran.", 'success');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import pengeluaran error: ' . $e->getMessage());
            Alert::toast('Gagal mengimpor file: ' . $e->getMessage(), 'error');
            return back();
        }
    }
}
