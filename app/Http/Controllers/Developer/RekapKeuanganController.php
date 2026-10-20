<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\PemasukanPerusahaan;
use App\Models\PengeluaranPerusahaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class RekapKeuanganController extends Controller
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

        $bulanMap = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

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

        // 2. Perhitungan Keuntungan Tahunan (Pemasukan - Pengeluaran)
        $pemasukanTahunIni = (int) PemasukanPerusahaan::whereYear('created_at', $currentYear)->sum('nominal');
        $pengeluaranTahunIni = (int) PengeluaranPerusahaan::whereYear('created_at', $currentYear)->sum('nominal');
        $keuntunganTahunIni = $pemasukanTahunIni - $pengeluaranTahunIni;

        $pemasukanTahunLalu = (int) PemasukanPerusahaan::whereYear('created_at', $currentYear - 1)->sum('nominal');
        $pengeluaranTahunLalu = (int) PengeluaranPerusahaan::whereYear('created_at', $currentYear - 1)->sum('nominal');
        $keuntunganTahunLalu = $pemasukanTahunLalu - $pengeluaranTahunLalu;

        if ($keuntunganTahunLalu != 0) {
            $persenKeuntunganTahun = (($keuntunganTahunIni - $keuntunganTahunLalu) / abs($keuntunganTahunLalu)) * 100;
        } else {
            $persenKeuntunganTahun = $keuntunganTahunIni > 0 ? 100 : 0;
        }

        // 3. Perhitungan Keuntungan Bulanan
        $pemasukanBulanIni = (int) PemasukanPerusahaan::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('nominal');
        $pengeluaranBulanIni = (int) PengeluaranPerusahaan::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('nominal');
        $keuntunganBulanIni = $pemasukanBulanIni - $pengeluaranBulanIni;

        $pemasukanBulanLalu = (int) PemasukanPerusahaan::whereYear('created_at', $lastMonthDate->year)
            ->whereMonth('created_at', $lastMonthDate->month)
            ->sum('nominal');
        $pengeluaranBulanLalu = (int) PengeluaranPerusahaan::whereYear('created_at', $lastMonthDate->year)
            ->whereMonth('created_at', $lastMonthDate->month)
            ->sum('nominal');
        $keuntunganBulanLalu = $pemasukanBulanLalu - $pengeluaranBulanLalu;

        if ($keuntunganBulanLalu != 0) {
            $persenKeuntunganBulan = (($keuntunganBulanIni - $keuntunganBulanLalu) / abs($keuntunganBulanLalu)) * 100;
        } else {
            $persenKeuntunganBulan = $keuntunganBulanIni > 0 ? 100 : 0;
        }

        // 4. Perhitungan Kerugian / Pengeluaran Tahunan
        if ($pengeluaranTahunLalu != 0) {
            $persenPengeluaranTahun = (($pengeluaranTahunIni - $pengeluaranTahunLalu) / $pengeluaranTahunLalu) * 100;
        } else {
            $persenPengeluaranTahun = $pengeluaranTahunIni > 0 ? 100 : 0;
        }

        // 5. Perhitungan Kerugian / Pengeluaran Bulanan
        if ($pengeluaranBulanLalu != 0) {
            $persenPengeluaranBulan = (($pengeluaranBulanIni - $pengeluaranBulanLalu) / $pengeluaranBulanLalu) * 100;
        } else {
            $persenPengeluaranBulan = $pengeluaranBulanIni > 0 ? 100 : 0;
        }

        // 6. Dataset Perbandingan Keuntungan 4 Tahun Terakhir (2026, 2025, 2024, 2023)
        $yearsComparison = [
            2026 => [
                'color' => 'rgb(40, 127, 113)',
                'bg' => 'rgba(40, 127, 113, 0.05)',
            ],
            2025 => [
                'color' => 'rgb(235, 134, 43)',
                'bg' => 'rgba(235, 134, 43, 0.05)',
            ],
            2024 => [
                'color' => 'rgb(179, 129, 244)',
                'bg' => 'rgba(179, 129, 244, 0.05)',
            ],
            2023 => [
                'color' => 'rgb(180, 210, 0)',
                'bg' => 'rgba(180, 210, 0, 0.05)',
            ],
        ];

        $chartDatasets = [];
        $totalKeuntunganPerTahun = [];

        foreach ($yearsComparison as $year => $styling) {
            $monthlyProfitsInMillions = [];
            $totalYearIn = 0;
            $totalYearOut = 0;

            for ($m = 1; $m <= 12; $m++) {
                $mIn = (int) PemasukanPerusahaan::whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->sum('nominal');
                $mOut = (int) PengeluaranPerusahaan::whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->sum('nominal');

                $totalYearIn += $mIn;
                $totalYearOut += $mOut;

                // Profit in Millions (Juta Rupiah, 1 desimal)
                $netProfitMillion = round(($mIn - $mOut) / 1000000, 1);
                $monthlyProfitsInMillions[] = $netProfitMillion;
            }

            $totalKeuntunganPerTahun[$year] = $totalYearIn - $totalYearOut;

            $chartDatasets[] = [
                'label' => "Tahun {$year}",
                'data' => $monthlyProfitsInMillions,
                'borderWidth' => 3,
                'borderColor' => $styling['color'],
                'backgroundColor' => $styling['bg'],
                'tension' => 0.35,
                'fill' => false,
            ];
        }

        // 7. Tabel Rekap Harian (Union Transaksi Pemasukan & Pengeluaran)
        $search = $request->query('search');

        $query = DB::table(DB::raw('(
            SELECT DATE(created_at) AS tgl, nominal AS pemasukan, 0 AS pengeluaran FROM pemasukan_perusahaan
            UNION ALL
            SELECT DATE(created_at) AS tgl, 0 AS pemasukan, nominal AS pengeluaran FROM pengeluaran_perusahaan
        ) AS combined'))
            ->selectRaw('tgl AS tanggal, SUM(pemasukan) AS total_pemasukan, SUM(pengeluaran) AS total_pengeluaran, (SUM(pemasukan) - SUM(pengeluaran)) AS keuntungan_bersih')
            ->groupBy('tgl')
            ->orderByDesc('tgl');

        if ($search) {
            $query->where('tgl', 'like', "%{$search}%");
        }

        $rekapData = $query->paginate(10)->withQueryString();

        return view('developers.rekap-keuangan', [
            'title' => 'Rekap Keuangan | Developer',
            'user_baru_terdaftar' => $user_baru_terdaftar,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,
            'currentMonthNameIndo' => $currentMonthNameIndo,
            'lastMonthNameIndo' => $lastMonthNameIndo,
            'keuntunganTahunIni' => $keuntunganTahunIni,
            'keuntunganTahunLalu' => $keuntunganTahunLalu,
            'persenKeuntunganTahun' => $persenKeuntunganTahun,
            'keuntunganBulanIni' => $keuntunganBulanIni,
            'keuntunganBulanLalu' => $keuntunganBulanLalu,
            'persenKeuntunganBulan' => $persenKeuntunganBulan,
            'pengeluaranTahunIni' => $pengeluaranTahunIni,
            'pengeluaranTahunLalu' => $pengeluaranTahunLalu,
            'persenPengeluaranTahun' => $persenPengeluaranTahun,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
            'pengeluaranBulanLalu' => $pengeluaranBulanLalu,
            'persenPengeluaranBulan' => $persenPengeluaranBulan,
            'totalKeuntunganPerTahun' => $totalKeuntunganPerTahun,
            'chartDatasets' => $chartDatasets,
            'rekapData' => $rekapData,
            'search' => $search,
        ]);
    }

    public function exportRekap()
    {
        $query = DB::table(DB::raw('(
            SELECT DATE(created_at) AS tgl, nominal AS pemasukan, 0 AS pengeluaran FROM pemasukan_perusahaan
            UNION ALL
            SELECT DATE(created_at) AS tgl, 0 AS pemasukan, nominal AS pengeluaran FROM pengeluaran_perusahaan
        ) AS combined'))
            ->selectRaw('tgl AS tanggal, SUM(pemasukan) AS total_pemasukan, SUM(pengeluaran) AS total_pengeluaran, (SUM(pemasukan) - SUM(pengeluaran)) AS keuntungan_bersih')
            ->groupBy('tgl')
            ->orderByDesc('tgl')
            ->get();

        $filename = 'laporan-rekap-keuangan-kampsewa-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM
            fputcsv($file, ['No', 'Tanggal', 'Total Pemasukan (Rp)', 'Total Pengeluaran (Rp)', 'Keuntungan Bersih (Rp)', 'Status Margin']);

            $no = 1;
            foreach ($query as $row) {
                fputcsv($file, [
                    $no++,
                    $row->tanggal,
                    $row->total_pemasukan,
                    $row->total_pengeluaran,
                    $row->keuntungan_bersih,
                    $row->keuntungan_bersih >= 0 ? 'Surplus' : 'Defisit',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function templateCsv()
    {
        $filename = 'template-import-rekap-keuangan.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Tipe', 'Sumber_Kategori', 'Deskripsi', 'Nominal', 'Tanggal']);
            fputcsv($file, ['Pemasukan', 'Layanan Iklan', 'Promosi iklan banner mitra', 500000, date('Y-m-d')]);
            fputcsv($file, ['Pemasukan', 'Pajak Platform', 'Bagi hasil sewa perlengkapan outdoor', 150000, date('Y-m-d')]);
            fputcsv($file, ['Pengeluaran', 'Server & Cloud', 'Biaya langganan server AWS dan database', 2000000, date('Y-m-d')]);
            fputcsv($file, ['Pengeluaran', 'Operasional', 'Biaya operasional kantor dan utilitas', 750000, date('Y-m-d')]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importRekap(Request $request)
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

            $importedPemasukan = 0;
            $importedPengeluaran = 0;

            DB::beginTransaction();
            while (($data = fgetcsv($file, 1000, $delimiter)) !== false) {
                if (empty($data) || !isset($data[0])) continue;

                $tipe = strtolower(trim($data[0] ?? ''));
                $sumberKategori = trim($data[1] ?? 'Umum');
                $deskripsi = trim($data[2] ?? '');
                $nominalStr = preg_replace('/[^0-9]/', '', $data[3] ?? '0');
                $nominal = (int) $nominalStr;
                $tanggalRaw = trim($data[4] ?? '');

                $createdAt = now();
                if (!empty($tanggalRaw)) {
                    try {
                        $createdAt = Carbon::parse($tanggalRaw);
                    } catch (\Exception $e) {
                        $createdAt = now();
                    }
                }

                if ($nominal > 0) {
                    if (str_contains($tipe, 'keluar') || str_contains($tipe, 'pengeluaran') || str_contains($tipe, 'expense')) {
                        PengeluaranPerusahaan::create([
                            'kategori_pengeluaran' => ucwords($sumberKategori ?: 'Operasional'),
                            'sumber' => ucwords($sumberKategori ?: 'Operasional'),
                            'deskripsi' => $deskripsi ?: 'Pengeluaran dari import rekap keuangan',
                            'nominal' => $nominal,
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt,
                        ]);
                        $importedPengeluaran++;
                    } else {
                        PemasukanPerusahaan::create([
                            'sumber' => ucwords($sumberKategori ?: 'Lainnya'),
                            'deskripsi' => $deskripsi ?: 'Pemasukan dari import rekap keuangan',
                            'nominal' => $nominal,
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt,
                        ]);
                        $importedPemasukan++;
                    }
                }
            }
            DB::commit();
            fclose($file);

            $total = $importedPemasukan + $importedPengeluaran;
            Alert::toast("Berhasil mengimpor $total data rekap ($importedPemasukan pemasukan, $importedPengeluaran pengeluaran).", 'success');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import rekap keuangan error: ' . $e->getMessage());
            Alert::toast('Gagal mengimpor file: ' . $e->getMessage(), 'error');
            return back();
        }
    }
}
