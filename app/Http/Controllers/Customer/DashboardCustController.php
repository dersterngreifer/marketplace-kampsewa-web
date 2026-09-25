<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use App\Models\Pengembalian;
use App\Models\Penyewaan;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardCustController extends Controller
{
    public function __construct()
    {
        $this->middleware('cust');
    }

    public function index($id_user = null)
    {
        try {
            $id_user_dec = $id_user ? Crypt::decrypt($id_user) : session('id_user');
            if (!$id_user_dec && auth()->check()) {
                $id_user_dec = auth()->id();
            }

            $user_name = User::where('id', $id_user_dec)->value('name') ?? 'Agung';

            $tahun_ini = Carbon::now()->year;
            $tahun_lalu = Carbon::now()->subYear()->year;
            $dua_tahun_lalu = Carbon::now()->subYears(2)->year;

            $pemasukan_tahun_ini = Pemasukan::where('id_user', $id_user_dec)->whereYear('created_at', $tahun_ini)->sum('nominal');
            $pemasukan_tahun_lalu = Pemasukan::where('id_user', $id_user_dec)->whereYear('created_at', $tahun_lalu)->sum('nominal');
            $pemasukan_dua_tahun_lalu = Pemasukan::where('id_user', $id_user_dec)->whereYear('created_at', $dua_tahun_lalu)->sum('nominal');

            $kenaikan_persentase = $pemasukan_tahun_lalu != 0 ? min((($pemasukan_tahun_ini - $pemasukan_tahun_lalu) / abs($pemasukan_tahun_lalu)) * 100, 100) : ($pemasukan_tahun_ini > 0 ? 100 : 0);
            $kenaikan_persentase_lalu = $pemasukan_dua_tahun_lalu != 0 ? min((($pemasukan_tahun_lalu - $pemasukan_dua_tahun_lalu) / abs($pemasukan_dua_tahun_lalu)) * 100, 100) : ($pemasukan_tahun_lalu > 0 ? 100 : 0);

            // Per bulan
            $bulan_ini = Carbon::now()->month;
            $bulan_lalu = Carbon::now()->subMonth()->month;
            $bulan_ini_nama = Carbon::now()->translatedFormat('F');
            $bulan_lalu_nama = Carbon::now()->subMonth()->translatedFormat('F');

            $pemasukan_bulan_ini = Pemasukan::where('id_user', $id_user_dec)->whereYear('created_at', $tahun_ini)->whereMonth('created_at', $bulan_ini)->sum('nominal');
            $pemasukan_bulan_lalu = Pemasukan::where('id_user', $id_user_dec)->whereYear('created_at', $bulan_lalu == 12 ? $tahun_lalu : $tahun_ini)->whereMonth('created_at', $bulan_lalu)->sum('nominal');

            $persen_bulan_ini = $pemasukan_bulan_lalu != 0 ? min((($pemasukan_bulan_ini - $pemasukan_bulan_lalu) / abs($pemasukan_bulan_lalu)) * 100, 100) : ($pemasukan_bulan_ini > 0 ? 100 : 0);

            // Chart data per bulan (12 bulan) untuk tahun ini & tahun lalu
            $chart_tahun_ini = [];
            $chart_tahun_lalu = [];
            for ($m = 1; $m <= 12; $m++) {
                $chart_tahun_ini[] = (int) Pemasukan::where('id_user', $id_user_dec)->whereYear('created_at', $tahun_ini)->whereMonth('created_at', $m)->sum('nominal');
                $chart_tahun_lalu[] = (int) Pemasukan::where('id_user', $id_user_dec)->whereYear('created_at', $tahun_lalu)->whereMonth('created_at', $m)->sum('nominal');
            }

            // Peralatan terlaris
            $peralatan_terlaris = Produk::where('id_user', $id_user_dec)
                ->leftJoin('detail_penyewaan', 'produk.id', '=', 'detail_penyewaan.id_produk')
                ->select('produk.*', DB::raw('COALESCE(SUM(detail_penyewaan.qty), 0) as total_sewa'))
                ->groupBy('produk.id', 'produk.id_user', 'produk.id_kategori', 'produk.nama', 'produk.deskripsi', 'produk.kode_produk', 'produk.jaminan', 'produk.gender', 'produk.video_produk', 'produk.created_at', 'produk.updated_at')
                ->orderByDesc('total_sewa')
                ->take(5)
                ->get();

            // Penyewa Berlangsung
            $penyewa_berlangsung = Penyewaan::join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
                ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                ->join('users', 'penyewaan.id_user', '=', 'users.id')
                ->where('produk.id_user', $id_user_dec)
                ->where('penyewaan.status_penyewaan', 'Aktif')
                ->select('penyewaan.id as id_penyewaan', 'users.name', 'users.foto', 'penyewaan.tanggal_mulai', 'penyewaan.tanggal_selesai')
                ->distinct()
                ->take(5)
                ->get();

            // Riwayat Penyewa (Selesai)
            $riwayat_penyewa = Penyewaan::join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
                ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                ->join('users', 'penyewaan.id_user', '=', 'users.id')
                ->where('produk.id_user', $id_user_dec)
                ->where('penyewaan.status_penyewaan', 'Selesai')
                ->select('penyewaan.id as id_penyewaan', 'users.name', 'users.foto', 'penyewaan.tanggal_selesai')
                ->distinct()
                ->take(5)
                ->get();

            // Denda Penyewa
            $denda_penyewa = Pengembalian::join('penyewaan', 'pengembalian.id_penyewaan', '=', 'penyewaan.id')
                ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
                ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                ->join('users', 'penyewaan.id_user', '=', 'users.id')
                ->where('produk.id_user', $id_user_dec)
                ->where('pengembalian.denda', '>', 0)
                ->select('penyewaan.id as id_penyewaan', 'users.name', 'users.foto', 'pengembalian.denda', 'pengembalian.status_denda')
                ->distinct()
                ->take(5)
                ->get();

            return view('customers.menu-dashboard-cust.dashboard')->with([
                'title' => 'Dashboard | Customer',
                'user_name' => $user_name,
                'tahun_ini' => $tahun_ini,
                'tahun_lalu' => $tahun_lalu,
                'pemasukan_tahun_ini' => $pemasukan_tahun_ini,
                'pemasukan_tahun_lalu' => $pemasukan_tahun_lalu,
                'persentase_tahun_ini' => round($kenaikan_persentase, 1),
                'persentase_tahun_lalu' => round($kenaikan_persentase_lalu, 1),
                'bulan_ini_nama' => $bulan_ini_nama,
                'bulan_lalu_nama' => $bulan_lalu_nama,
                'pemasukan_bulan_ini' => $pemasukan_bulan_ini,
                'pemasukan_bulan_lalu' => $pemasukan_bulan_lalu,
                'persentase_bulan_ini' => round($persen_bulan_ini, 1),
                'chart_tahun_ini' => json_encode($chart_tahun_ini),
                'chart_tahun_lalu' => json_encode($chart_tahun_lalu),
                'peralatan_terlaris' => $peralatan_terlaris,
                'penyewa_berlangsung' => $penyewa_berlangsung,
                'riwayat_penyewa' => $riwayat_penyewa,
                'denda_penyewa' => $denda_penyewa,
            ]);
        } catch (\Exception $e) {
            Log::error('Error DashboardCustController: ' . $e->getMessage());
            return view('customers.menu-dashboard-cust.dashboard')->with([
                'title' => 'Dashboard | Customer',
                'user_name' => 'User',
                'tahun_ini' => date('Y'),
                'tahun_lalu' => date('Y') - 1,
                'pemasukan_tahun_ini' => 0,
                'pemasukan_tahun_lalu' => 0,
                'persentase_tahun_ini' => 0,
                'persentase_tahun_lalu' => 0,
                'bulan_ini_nama' => date('F'),
                'bulan_lalu_nama' => 'Bulan Lalu',
                'pemasukan_bulan_ini' => 0,
                'pemasukan_bulan_lalu' => 0,
                'persentase_bulan_ini' => 0,
                'chart_tahun_ini' => json_encode(array_fill(0, 12, 0)),
                'chart_tahun_lalu' => json_encode(array_fill(0, 12, 0)),
                'peralatan_terlaris' => collect([]),
                'penyewa_berlangsung' => collect([]),
                'riwayat_penyewa' => collect([]),
                'denda_penyewa' => collect([]),
            ]);
        }
    }
}
