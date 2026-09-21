<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartWebController extends Controller
{
    public function ApiTotalKeuntungan()
    {
        $total_pemasukan_tahun_sekarang = Pemasukan::whereYear('created_at', date('Y'))->sum('nominal');
        $total_pengeluaran_tahun_sekarang = Pengeluaran::whereYear('created_at', date('Y'))->sum('nominal');

        $total_pemasukan_tahun_lalu = Pemasukan::whereYear('created_at', date('Y') - 1)->sum('nominal');
        $total_pengeluaran_tahun_lalu = Pengeluaran::whereYear('created_at', date('Y') - 1)->sum('nominal');

        // Menghitung total keuntungan tahun sekarang
        $total_keuntungan = abs($total_pemasukan_tahun_sekarang - $total_pengeluaran_tahun_sekarang);
        if ($total_keuntungan >= 1000000) {
            $formatted_keuntungan = number_format($total_keuntungan / 1000000, 0) . 'M';
        } else {
            $formatted_keuntungan = number_format($total_keuntungan, 0);
        }

        // Menghitung total keuntungan tahun lalu
        $total_keuntungan_lalu = abs($total_pemasukan_tahun_lalu - $total_pengeluaran_tahun_lalu);
        if ($total_keuntungan_lalu >= 1000000) {
            $formatted_keuntungan_lalu = number_format($total_keuntungan_lalu / 1000000, 0) . 'M';
        } else {
            $formatted_keuntungan_lalu = number_format($total_keuntungan_lalu, 0);
        }

        // Menghitung total kerugian tahun sekarang
        $total_kerugian = abs($total_pengeluaran_tahun_sekarang - $total_pemasukan_tahun_sekarang);
        if ($total_kerugian >= 1000000) {
            $formatted_kerugian = number_format($total_kerugian / 1000000, 0) . 'M';
        } else {
            $formatted_kerugian = number_format($total_kerugian, 0);
        }

        // Menghitung total kerugian tahun lalu
        $total_kerugian_lalu = abs($total_pengeluaran_tahun_lalu - $total_pemasukan_tahun_lalu);
        if ($total_kerugian_lalu >= 1000000) {
            $formatted_kerugian_lalu = number_format($total_kerugian_lalu / 1000000, 0) . 'M';
        } else {
            $formatted_kerugian_lalu = number_format($total_kerugian_lalu, 0);
        }

        return response()->json([
            'total' => [
                'total_keuntungan' => [
                    'keuntungan_tahun_saat_ini' => $formatted_keuntungan,
                    'keuntungan_tahun_lalu' => $formatted_keuntungan_lalu,
                ],
                'total_kerugian' => [
                    'kerugian_tahun_saat_ini' => $formatted_kerugian,
                    'kerugian_tahun_lalu' => $formatted_kerugian_lalu
                ],
            ],
        ]);
    }

    public function apiChartMenuPenghasilan()
    {
        $year = Carbon::now()->year;
        $totalPemasukanPerBulan = [];

        for ($month = 1; $month <= 12; $month++) {
            $total = DB::table('pemasukan')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('nominal');

            $totalPemasukanPerBulan[] = [
                'month' => Carbon::create()->month($month)->format('F'),
                'total' => $total
            ];
        }
        return response()->json(['total_pemasukan_per_bulan' => $totalPemasukanPerBulan], 200);
    }

    public function apiChartTotalPenghasilanPerbulanSaatIniMenuPenghasilan()
    {
        $time = Carbon::now()->month;

        for ($month = 1; $month <= $time; $month++) {
            $total = DB::table('pemasukan')
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $month)
                ->sum('nominal');
            $totalPemasukanPerBulan[] = [
                'month' => Carbon::create()->month($month)->format('F'),
                'total' => $total
            ];
        }

        return response()->json(['total_pemasukan_per_bulan' => $totalPemasukanPerBulan], 200);
    }

    public function apiPerbandinganPemasukanPertahunWebCust($id_user) {
        $total_perbulan_tahun_kemarin = [];
        for($bulan = 1; $bulan <= 12; $bulan++) {
            $total_pertahun = Pemasukan::where('id_user', $id_user)
            ->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', $bulan)
            ->where('sumber', 'Penyewaan')
            ->sum('nominal');
            $total_perbulan_tahun_kemarin[$bulan] = $total_pertahun;
        }
        if(!empty($total_perbulan_tahun_kemarin)){
            return response()->json([
                'message' => 'success',
                'data_pertahun' => $total_perbulan_tahun_kemarin,
            ]);
        }
    }

    public function apiStatistikPesanan($id_user)
    {
        // Total semua pesanan
        $total_semua_pesanan = DB::table('penyewaan')
            ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->where('produk.id_user', $id_user)
            ->distinct('penyewaan.id')
            ->count('penyewaan.id');

        // Total belum dikonfirmasi (Pending)
        $total_belum_dikonfirmasi = DB::table('penyewaan')
            ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->where('produk.id_user', $id_user)
            ->where('penyewaan.status_penyewaan', 'Pending')
            ->distinct('penyewaan.id')
            ->count('penyewaan.id');

        // Total sedang disewa (Aktif)
        $total_sedang_disewa = DB::table('penyewaan')
            ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->where('produk.id_user', $id_user)
            ->where('penyewaan.status_penyewaan', 'Aktif')
            ->distinct('penyewaan.id')
            ->count('penyewaan.id');

        // Total QTY produk belum dikonfirmasi
        $total_produk_belum_dikonfirmasi = DB::table('penyewaan')
            ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->where('produk.id_user', $id_user)
            ->where('penyewaan.status_penyewaan', 'Pending')
            ->sum('detail_penyewaan.qty');

        return response()->json([
            'message' => 'success',
            'data' => [
                'total_semua_pesanan' => $total_semua_pesanan,
                'total_belum_dikonfirmasi' => $total_belum_dikonfirmasi,
                'total_sedang_disewa' => $total_sedang_disewa,
                'total_produk_belum_dikonfirmasi' => (int) $total_produk_belum_dikonfirmasi,
            ]
        ], 200);
    }
}
