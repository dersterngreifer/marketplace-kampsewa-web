<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\DetailIklan;
use App\Models\Iklan;
use App\Models\Pemasukan;
use App\Models\PembayaranIklan;
use App\Models\PembayaranPenyewaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class IklanController extends Controller
{
    public function __construct()
    {
        $this->middleware('dev');
    }

    public function index(Request $request)
    {
        // ambil user berdasarkan yang baru saja terdaftar
        $user_baru_terdaftar = User::select('users.*')
            ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
            ->where('users.type', 0)
            ->whereDate('users.created_at', Carbon::today())
            ->where('status_notifikasi_user.status', 'unread')
            ->orderByDesc('users.created_at')->limit(10)
            ->get();

        // get count total transaksi iklan
        $get_count_total_transaksi_iklan = PembayaranIklan::all()->count();

        // get count total iklan pending
        $get_count_total_iklan_pending = DetailIklan::where('status_iklan', 'like', '%pending%')->count();

        // get count total iklan aktif
        $get_count_total_iklan_aktif = DetailIklan::where('status_iklan', 'like', '%aktif%')->count();

        // get count total iklan selesai
        $get_count_total_iklan_selesai = DetailIklan::where('status_iklan', 'like', '%selesai%')->count();

        // total pendapatan iklan bulan ini
        $pendapatan_iklan_bulan_ini = PembayaranIklan::where('status_bayar', 'lunas')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_bayar');

        // total pajak platform bulan ini (dari transaksi penyewaan mitra-pelanggan)
        $pajak_bulan_ini = PembayaranPenyewaan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('pajak_platform');

        // ambil request input cari
        $cari = $request->query('cari');

        // get data user iklan pending
        $user_pending = User::join('iklan', 'users.id', '=', 'iklan.id_user')
            ->join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->where('detail_iklan.status_iklan', 'like', '%pending%')
            ->select(
                'users.id as id_user',
                'users.name',
                'users.email',
                'users.foto',
                'iklan.judul',
                'iklan.id as id_iklan_main',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.status_iklan',
                'pembayaran_iklan.status_bayar',
                'pembayaran_iklan.midtrans_transaction_status',
                'pembayaran_iklan.payment_type',
                'pembayaran_iklan.total_bayar as nominal_bayar',
                'pembayaran_iklan.id as id_pembayaran_iklan'
            )
            ->distinct();

        if (!empty($cari)) {
            $user_pending->where(function ($query) use ($cari) {
                $query->where('users.name', 'like', '%' . $cari . '%')
                    ->orWhere('users.nomor_telephone', 'like', '%' . $cari . '%')
                    ->orWhere('users.email', 'like', '%' . $cari . '%')
                    ->orWhere('iklan.judul', 'like', '%' . $cari . '%');
            });
        }

        $user_pending = $user_pending->paginate(10);

        // get data iklan selesai
        $data_iklan_selesai = User::join('iklan', 'users.id', '=', 'iklan.id_user')
            ->join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->where('detail_iklan.status_iklan', 'like', '%selesai%')
            ->select(
                'users.id as id_user',
                'users.name',
                'users.foto',
                'iklan.judul',
                'iklan.id as id_iklan_main',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.status_iklan'
            )
            ->distinct()->paginate(10);
        $get_count_iklan_selesai = $data_iklan_selesai->count();

        // get data iklan aktif
        $data_iklan_aktif = User::join('iklan', 'users.id', '=', 'iklan.id_user')
            ->join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->where('detail_iklan.status_iklan', 'like', '%aktif%')
            ->select(
                'users.id as id_user',
                'users.name',
                'users.foto',
                'iklan.judul',
                'iklan.poster',
                'iklan.id as id_iklan_main',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.status_iklan'
            )
            ->distinct()->limit(10)->get();

        // iklan yang akan expired dalam 3 hari ke depan
        $iklan_hampir_expired = DetailIklan::join('iklan', 'detail_iklan.id_iklan', '=', 'iklan.id')
            ->join('users', 'iklan.id_user', '=', 'users.id')
            ->where('detail_iklan.status_iklan', 'Aktif')
            ->whereBetween('detail_iklan.tanggal_akhir', [
                Carbon::today(),
                Carbon::today()->addDays(3),
            ])
            ->select('users.name', 'users.foto', 'iklan.judul', 'iklan.id as id_iklan_main', 'detail_iklan.*')
            ->get();

        return view('developers.iklan')->with([
            'title'                          => 'Iklan Customer',
            'user_baru_terdaftar'            => $user_baru_terdaftar,
            'get_count_total_transaksi_iklan' => $get_count_total_transaksi_iklan,
            'get_count_total_iklan_pending'  => $get_count_total_iklan_pending,
            'get_count_total_iklan_aktif'    => $get_count_total_iklan_aktif,
            'get_count_total_iklan_selesai'  => $get_count_total_iklan_selesai,
            'pendapatan_iklan_bulan_ini'     => $pendapatan_iklan_bulan_ini,
            'pajak_bulan_ini'               => $pajak_bulan_ini,
            'user_pending'                  => $user_pending,
            'cari'                          => $cari,
            'data_iklan_selesai'            => $data_iklan_selesai,
            'data_iklan_aktif'              => $data_iklan_aktif,
            'get_count_iklan_selesai'       => $get_count_iklan_selesai,
            'iklan_hampir_expired'          => $iklan_hampir_expired,
        ]);
    }

    /**
     * Admin mengaktifkan iklan secara manual.
     * Berguna jika Midtrans callback terlambat/gagal namun mitra sudah bayar.
     */
    public function aktivasiIklan($id_detail_iklan)
    {
        $detail = DetailIklan::findOrFail($id_detail_iklan);
        $detail->update(['status_iklan' => 'Aktif']);

        // Jika ada pembayaran yang belum lunas, tandai lunas
        PembayaranIklan::where('id_iklan', $detail->id_iklan)
            ->where('status_bayar', '!=', 'lunas')
            ->update([
                'status_bayar' => 'lunas',
                'midtrans_transaction_status' => 'settlement',
            ]);

        // Catat ke pemasukan jika belum tercatat
        $sudahDicatat = Pemasukan::where('id_user', function ($q) use ($detail) {
            $q->select('id_user')->from('iklan')->where('id', $detail->id_iklan);
        })
            ->where('sumber', 'Layanan Iklan')
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if (!$sudahDicatat) {
            $pembayaran = PembayaranIklan::where('id_iklan', $detail->id_iklan)->first();
            if ($pembayaran) {
                Pemasukan::create([
                    'id_user'   => $pembayaran->id_user,
                    'sumber'    => 'Layanan Iklan',
                    'deskripsi' => 'Aktivasi manual iklan oleh admin — ' . now()->format('d M Y'),
                    'nominal'   => $pembayaran->total_bayar,
                ]);
            }
        }

        Alert::toast('Iklan berhasil diaktifkan.', 'success');
        return back();
    }

    /**
     * Admin menonaktifkan iklan (suspend).
     */
    public function nonaktifkanIklan($id_detail_iklan)
    {
        $detail = DetailIklan::findOrFail($id_detail_iklan);
        $detail->update(['status_iklan' => 'Nonaktif']);

        Alert::toast('Iklan telah dinonaktifkan.', 'info');
        return back();
    }

    /**
     * Admin menyelesaikan iklan (mark as selesai).
     */
    public function selesaikanIklan($id_detail_iklan)
    {
        $detail = DetailIklan::findOrFail($id_detail_iklan);
        $detail->update(['status_iklan' => 'Selesai']);

        Alert::toast('Iklan telah diselesaikan.', 'success');
        return back();
    }

    public function deleteIklanPending($id_iklan)
    {
        Iklan::where('id', $id_iklan)->delete();
        Alert::toast('Berhasil Dihapus.');
        return back();
    }
}
