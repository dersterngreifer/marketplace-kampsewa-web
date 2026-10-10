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
    public function index()
    {
        // ambil user berdasarkan yang baru saja terdaftar
        $user_baru_terdaftar = User::select('users.*')
            ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
            ->where('users.type', 0)
            ->whereDate('users.created_at', Carbon::today())
            ->where('status_notifikasi_user.status', 'unread')
            ->orderByDesc('users.created_at')->limit(10)
            ->get();

        // get total penghasilan tahun saat ini
        $penghasilan_tahun_ini = PemasukanPerusahaan::whereYear('created_at', Carbon::now()->year)->sum('nominal');

        // get total penghasilan tahun lalu
        $penghasilan_tahun_lalu = PemasukanPerusahaan::whereYear('created_at', Carbon::now()->year - 1)->sum('nominal');

        // hitung presentase dari perbandingan penghasilan tahun ini - tahun lalu
        if ($penghasilan_tahun_lalu != 0) {
            $persentase_perubahan = (($penghasilan_tahun_ini - $penghasilan_tahun_lalu) / $penghasilan_tahun_lalu) * 100;
        } else {
            $persentase_perubahan = $penghasilan_tahun_ini > 0 ? 100 : 0;
        }

        // get total penghasilan bulan lalu dan sekarang
        $monthPreviousTotal = PemasukanPerusahaan::whereMonth('created_at', Carbon::now()->subMonth()->month)->sum('nominal');
        $monthCurrentTotal = PemasukanPerusahaan::whereMonth('created_at', Carbon::now()->month)->sum('nominal');

        for ($month = 1; $month <= Carbon::now()->month; $month++) {
            $total = DB::table('pemasukan_perusahaan')
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $month)
                ->sum('nominal');

            $totalPemasukanPerbulan[] = [
                'month' => Carbon::create()->month($month)->format('F'),
                'total' => $total,
            ];
        }

        $totalPemasukanPerbulanSebelumBulanSaatIni = 0;
        for ($month = 1; $month <= Carbon::now()->month - 1; $month++) {
            $total = DB::table('pemasukan_perusahaan')
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $month)
                ->sum('nominal');

            $totalPemasukanPerbulanSebelumBulanSaatIni += $total;
        }

        // persentase perbandingan penghasilan total bulan ini dan total bulan lalu
        if ($monthPreviousTotal != 0) {
            $persentase_perbandingan_total_bulan_ini = (($monthCurrentTotal - $totalPemasukanPerbulanSebelumBulanSaatIni) / $totalPemasukanPerbulanSebelumBulanSaatIni) * 100;
        } else {
            $persentase_perbandingan_total_bulan_ini = $monthCurrentTotal > 0 ? 100 : 0;
        }

        $list_pemasukan = PemasukanPerusahaan::where('sumber', 'Service')
            ->orWhere('sumber', 'Layanan Iklan')
            ->get();

        return view('developers.penghasilan')->with([
            'title' => 'Penghasilan',
            'user_baru_terdaftar' => $user_baru_terdaftar,
            'penghasilan_tahun_ini' => $penghasilan_tahun_ini,
            'penghasilan_tahun_lalu' => $penghasilan_tahun_lalu,
            'persentase_perubahan' => $persentase_perubahan,
            'monthPreviousTotal' => $monthPreviousTotal,
            'monthCurrentTotal' => $monthCurrentTotal,
            'totalPemasukanPerbulan' => $totalPemasukanPerbulan,
            'totalPemasukanPerbulanSebelumBulanSaatIni' => $persentase_perbandingan_total_bulan_ini,
            'list_pemasukan' => $list_pemasukan,
        ]);
    }

    public function tambahPenghasilan($id_user)
    {
        try {
            request()->validate([
                'id_user' => 'string',
                'sumber' => 'required|string|max:50|min:5',
                'deskripsi' => 'required|string|max:255',
                'nominal' => 'required|integer',
            ]);

            $pemasukan = new PemasukanPerusahaan();
            $pemasukan->id_user = request()->id_user;
            $pemasukan->sumber = strtoupper(request()->sumber);
            $pemasukan->deskripsi = request()->deskripsi;
            $pemasukan->nominal = request()->nominal;

            $pemasukan->save();

            Alert::toast('Data berhasil di simpan', 'success');
            return back();
        } catch (\Exception $error) {
            Log::error($error->getMessage());
        }
    }
}
