<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformasiPenggunaController extends Controller
{
    public function __construct()
    {
        $this->middleware('dev');
    }
    public function index(Request $request)
    {
        // --- Notifikasi sidebar (digunakan oleh layout)
        $user_baru_terdaftar = User::select('users.*')
            ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
            ->where('users.type', 0)
            ->whereDate('users.created_at', Carbon::today())
            ->where('status_notifikasi_user.status', 'unread')
            ->orderByDesc('users.created_at')->limit(10)
            ->get();

        // =========================================================
        // STAT CARDS — Pendaftar dengan persentase perubahan
        // =========================================================

        // Hari ini vs kemarin
        $user_pendaftar_hari_ini     = User::whereDate('created_at', Carbon::today())->where('type', 0)->count();
        $user_pendaftar_kemarin      = User::whereDate('created_at', Carbon::yesterday())->where('type', 0)->count();
        $pct_hari                    = $user_pendaftar_kemarin > 0
            ? round((($user_pendaftar_hari_ini - $user_pendaftar_kemarin) / $user_pendaftar_kemarin) * 100, 1)
            : ($user_pendaftar_hari_ini > 0 ? 100 : 0);

        // Minggu ini vs minggu lalu
        $user_pendaftar_minggu_ini     = User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('type', 0)->count();
        $user_pendaftar_minggu_kemarin = User::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->where('type', 0)->count();
        $pct_minggu                    = $user_pendaftar_minggu_kemarin > 0
            ? round((($user_pendaftar_minggu_ini - $user_pendaftar_minggu_kemarin) / $user_pendaftar_minggu_kemarin) * 100, 1)
            : ($user_pendaftar_minggu_ini > 0 ? 100 : 0);

        // Bulan ini vs bulan lalu
        $user_pendaftar_bulan_ini     = User::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('type', 0)->count();
        $user_pendaftar_bulan_kemarin = User::whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->where('type', 0)->count();
        $pct_bulan                    = $user_pendaftar_bulan_kemarin > 0
            ? round((($user_pendaftar_bulan_ini - $user_pendaftar_bulan_kemarin) / $user_pendaftar_bulan_kemarin) * 100, 1)
            : ($user_pendaftar_bulan_ini > 0 ? 100 : 0);

        // Tahun ini vs tahun lalu
        $user_pendaftar_tahun_ini     = User::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('type', 0)->count();
        $user_pendaftar_tahun_kemarin = User::whereBetween('created_at', [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()])->where('type', 0)->count();
        $pct_tahun                    = $user_pendaftar_tahun_kemarin > 0
            ? round((($user_pendaftar_tahun_ini - $user_pendaftar_tahun_kemarin) / $user_pendaftar_tahun_kemarin) * 100, 1)
            : ($user_pendaftar_tahun_ini > 0 ? 100 : 0);

        // =========================================================
        // USER ONLINE & SEDANG SEWA
        // =========================================================

        // Sedang online
        $get_customer_online = User::where('type', 0)->where('status', 'online')->get();
        $count_user_online   = $get_customer_online->count();

        // Sedang sewa: user yang punya penyewaan Aktif atau Pending
        $get_customer_sedang_sewa = DB::table('penyewaan')
            ->join('users as penyewa', 'penyewaan.id_user', '=', 'penyewa.id')
            ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->join('users as mitra', 'produk.id_user', '=', 'mitra.id')
            ->whereIn('penyewaan.status_penyewaan', ['Aktif', 'Pending'])
            ->select(
                'penyewaan.id as id_penyewaan',
                'penyewaan.status_penyewaan',
                'penyewaan.tanggal_mulai',
                'penyewaan.tanggal_selesai',
                'penyewaan.created_at as sewa_created_at',
                'penyewa.id as id_penyewa',
                'penyewa.name as nama_penyewa',
                'penyewa.foto as foto_penyewa',
                'mitra.name as nama_mitra',
                'produk.nama as nama_produk'
            )
            ->distinct()
            ->orderByDesc('penyewaan.created_at')
            ->limit(15)
            ->get();
        $count_sedang_sewa = $get_customer_sedang_sewa->count();

        // =========================================================
        // DAFTAR PENGGUNA (tabel utama dengan filter & search)
        // =========================================================

        $cari_customer    = $request->query('cari');
        $filter_customer  = $request->query('filter', 'terbaru');
        $tidak_aktif      = $request->query('tidak_aktif_sebulan');
        $produk_terbanyak = $request->query('produk_terbanyak');

        $query = DB::table('produk')
            ->rightJoin('users', 'produk.id_user', '=', 'users.id')
            ->whereIn('users.type', [0])
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.nomor_telephone',
                'users.created_at',
                'users.jenis_kelamin',
                'users.foto',
                'users.status',
                'users.last_login',
                DB::raw('COUNT(produk.id) as total_product')
            )
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'users.nomor_telephone',
                'users.created_at',
                'users.jenis_kelamin',
                'users.foto',
                'users.status',
                'users.last_login'
            );

        if (!empty($cari_customer)) {
            $query->where(function ($q) use ($cari_customer) {
                $q->where('users.name', 'like', '%' . $cari_customer . '%')
                    ->orWhere('users.nomor_telephone', 'like', '%' . $cari_customer . '%')
                    ->orWhere('users.email', 'like', '%' . $cari_customer . '%');
            });
        }

        if ($tidak_aktif === 'tidak_aktif_sebulan') {
            $query->where(function ($q) {
                $q->whereBetween('users.last_login', [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth(),
                ])->orWhereNull('users.last_login');
            });
        }

        if ($produk_terbanyak === 'produk_terbanyak') {
            $query->orderByDesc('total_product');
        } elseif ($filter_customer === 'terlama') {
            $query->orderBy('users.created_at', 'asc');
        } else {
            $query->orderBy('users.created_at', 'desc');
        }

        $users        = $query->paginate(10);
        $get_all_user = User::where('type', 0)->count();

        return view('developers.informas-pengguna')->with([
            'title'                         => 'Informasi Pengguna',
            'user_baru_terdaftar'           => $user_baru_terdaftar,
            // Stat cards
            'user_pendaftar_hari_ini'       => $user_pendaftar_hari_ini,
            'user_pendaftar_kemarin'        => $user_pendaftar_kemarin,
            'pct_hari'                      => $pct_hari,
            'user_pendaftar_minggu_ini'     => $user_pendaftar_minggu_ini,
            'user_pendaftar_minggu_kemarin' => $user_pendaftar_minggu_kemarin,
            'pct_minggu'                    => $pct_minggu,
            'user_pendaftar_bulan_ini'      => $user_pendaftar_bulan_ini,
            'user_pendaftar_bulan_kemarin'  => $user_pendaftar_bulan_kemarin,
            'pct_bulan'                     => $pct_bulan,
            'user_pendaftar_tahun_ini'      => $user_pendaftar_tahun_ini,
            'user_pendaftar_tahun_kemarin'  => $user_pendaftar_tahun_kemarin,
            'pct_tahun'                     => $pct_tahun,
            // Panel kanan
            'get_customer_online'           => $get_customer_online,
            'count_user_online'             => $count_user_online,
            'get_customer_sedang_sewa'      => $get_customer_sedang_sewa,
            'count_sedang_sewa'             => $count_sedang_sewa,
            // Daftar utama
            'users'                         => $users,
            'count'                         => $get_all_user,
            'cari_customer'                 => $cari_customer,
            'filter_customer'               => $filter_customer,
            'tidak_aktif_sebulan'           => $tidak_aktif,
            'produk_terbanyak'              => $produk_terbanyak,
        ]);
    }
}
