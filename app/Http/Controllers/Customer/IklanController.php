<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\DetailIklan;
use App\Models\Iklan;
use App\Models\PemasukanPerusahaan;
use App\Models\PembayaranIklan;
use App\Models\User;
use App\Services\IklanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class IklanController extends Controller
{
    public function __construct()
    {
        $this->middleware('cust');
    }

    public function index($id_user)
    {
        $user_id = Crypt::decrypt($id_user);

        // Hapus otomatis transaksi iklan yang belum dibayar lebih dari 1 hari (24 jam)
        IklanService::autoCleanupUnpaidAds(24);

        $get_total_user_menggunakan_iklan = Iklan::count();

        // Iklan yang belum dibayar / menunggu pembayaran (status_bayar != 'lunas' atau status_iklan = 'Menunggu Pembayaran')
        $iklan_belum_bayar = Iklan::leftJoin('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->select(
                'iklan.id as id_iklan',
                'iklan.poster',
                'iklan.judul',
                'iklan.sub_judul',
                'iklan.created_at',
                'iklan.snap_token',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.status_iklan',
                DB::raw('COALESCE(DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai), 0) as durasi_hari'),
                'pembayaran_iklan.metode_bayar',
                'pembayaran_iklan.midtrans_order_id',
                'pembayaran_iklan.status_bayar'
            )
            ->where('iklan.id_user', $user_id)
            ->where(function ($q) {
                $q->where('detail_iklan.status_iklan', 'Menunggu Pembayaran')
                  ->orWhere('pembayaran_iklan.status_bayar', 'pending')
                  ->orWhereNull('pembayaran_iklan.status_bayar')
                  ->orWhere('pembayaran_iklan.status_bayar', '!=', 'lunas');
            })
            ->where(function ($q) {
                $q->whereNull('detail_iklan.status_iklan')
                  ->orWhereNotIn('detail_iklan.status_iklan', ['Selesai', 'Nonaktif']);
            })
            ->where('iklan.status', '!=', 'expired')
            ->groupBy(
                'iklan.id',
                'iklan.poster',
                'iklan.judul',
                'iklan.sub_judul',
                'iklan.created_at',
                'iklan.snap_token',
                'detail_iklan.id',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.status_iklan',
                'durasi_hari',
                'pembayaran_iklan.metode_bayar',
                'pembayaran_iklan.midtrans_order_id',
                'pembayaran_iklan.status_bayar'
            )
            ->orderBy('iklan.created_at', 'desc')
            ->get();

        // Iklan sedang berlangsung (wajib lunas & status_iklan = Aktif)
        $iklan_berlangsung = Iklan::join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->join('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->join('users', 'users.id', '=', 'iklan.id_user')
            ->select(
                'iklan.id as id_iklan',
                'iklan.poster',
                'iklan.judul',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                DB::raw('DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai) as durasi_hari')
            )->where('iklan.id_user', $user_id)
            ->where('detail_iklan.status_iklan', 'Aktif')
            ->where('pembayaran_iklan.status_bayar', 'lunas')
            ->groupBy(
                'iklan.id',
                'iklan.poster',
                'iklan.judul',
                'detail_iklan.id',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'durasi_hari'
            )->get();

        // Iklan menunggu giliran / antrean (wajib lunas & status_iklan = Pending)
        $iklan_pending = Iklan::join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->join('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->join('users', 'users.id', '=', 'iklan.id_user')
            ->select(
                'iklan.id as id_iklan',
                'iklan.poster',
                'iklan.judul',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                DB::raw('DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai) as durasi_hari')
            )->where('iklan.id_user', $user_id)
            ->where('detail_iklan.status_iklan', 'Pending')
            ->where('pembayaran_iklan.status_bayar', 'lunas')
            ->groupBy(
                'iklan.id',
                'iklan.poster',
                'iklan.judul',
                'detail_iklan.id',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'durasi_hari'
            )->get();

        // Iklan yang diselesaikan paksa oleh admin
        $iklan_force_completed = Iklan::join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->select(
                'iklan.id as id_iklan',
                'iklan.poster',
                'iklan.judul',
                'iklan.sub_judul',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.status_iklan',
                'detail_iklan.is_force_completed',
                'detail_iklan.force_complete_alasan',
                'detail_iklan.force_complete_catatan',
                'detail_iklan.force_completed_at',
                DB::raw('COALESCE(DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai), 0) as durasi_hari')
            )
            ->where('iklan.id_user', $user_id)
            ->where('detail_iklan.status_iklan', 'Selesai')
            ->where('detail_iklan.is_force_completed', true)
            ->orderBy('detail_iklan.force_completed_at', 'desc')
            ->get();

        // Riwayat iklan selesai
        $iklan_selesai = Iklan::join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->join('users', 'users.id', '=', 'iklan.id_user')
            ->select(
                'iklan.id as id_iklan',
                'iklan.poster',
                'iklan.judul',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.is_force_completed',
                'detail_iklan.force_complete_alasan',
                'detail_iklan.force_complete_catatan',
                'detail_iklan.force_completed_at',
                DB::raw('DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai) as durasi_hari')
            )->where('iklan.id_user', $user_id)
            ->where('detail_iklan.status_iklan', 'Selesai')
            ->groupBy(
                'iklan.id',
                'iklan.poster',
                'iklan.judul',
                'detail_iklan.id',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.is_force_completed',
                'detail_iklan.force_complete_alasan',
                'detail_iklan.force_complete_catatan',
                'detail_iklan.force_completed_at',
                'durasi_hari'
            )
            ->orderByDesc('detail_iklan.is_force_completed')
            ->orderByDesc('detail_iklan.force_completed_at')
            ->orderByDesc('detail_iklan.tanggal_akhir')
            ->limit(10)->get();

        // Iklan yang sedang ditangguhkan (suspend) oleh admin
        $iklan_suspend = Iklan::join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->leftJoin('iklan_suspends', function ($join) {
                $join->on('iklan.id', '=', 'iklan_suspends.id_iklan')
                     ->whereRaw('iklan_suspends.id = (SELECT MAX(id) FROM iklan_suspends WHERE iklan_suspends.id_iklan = iklan.id)');
            })
            ->select(
                'iklan.id as id_iklan',
                'iklan.poster',
                'iklan.judul',
                'iklan.sub_judul',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.status_iklan',
                DB::raw('COALESCE(DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai), 0) as durasi_hari'),
                'iklan_suspends.alasan as suspend_alasan',
                'iklan_suspends.catatan as suspend_catatan',
                'iklan_suspends.bagian_revisi as suspend_bagian_revisi',
                'iklan_suspends.status_revisi as suspend_status_revisi',
                'iklan_suspends.created_at as suspend_created_at'
            )
            ->where('iklan.id_user', $user_id)
            ->where('detail_iklan.status_iklan', 'Nonaktif')
            ->groupBy(
                'iklan.id',
                'iklan.poster',
                'iklan.judul',
                'iklan.sub_judul',
                'detail_iklan.id',
                'detail_iklan.tanggal_mulai',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.harga_iklan',
                'detail_iklan.status_iklan',
                'durasi_hari',
                'suspend_alasan',
                'suspend_catatan',
                'suspend_bagian_revisi',
                'suspend_status_revisi',
                'suspend_created_at'
            )
            ->orderBy('detail_iklan.updated_at', 'desc')
            ->get();

        return view('customers.menu-iklan.iklan')->with([
            'title' => 'Iklan | Customer',
            'id_user' => $id_user,
            'total_data_iklan' => $get_total_user_menggunakan_iklan,
            'iklan_belum_bayar' => $iklan_belum_bayar,
            'iklan_suspend' => $iklan_suspend,
            'iklan_force_completed' => $iklan_force_completed,
            'iklan_berlangsung' => $iklan_berlangsung,
            'iklan_pending' => $iklan_pending,
            'iklan_selesai' => $iklan_selesai,
        ]);
    }




    public function pilihDurasiIklan($id_user)
    {
        $id_user_dec = Crypt::decryptString($id_user);

        // $bank = Bank::where('id_user', $id_user_dec)->count();
        // if ($bank == 0) {
        //     Alert::warning('Nomor Rekening Belum di isi', 'Silahkan lengkapi data nomor rekening anda dahulu, pilih Menu Transaksi lalu pilih Set Lokasi Toko & Rekening')->persistent(true);
        //     return back();
        // }

        return view('customers.menu-iklan.pilih-durasi-iklan')->with([
            'title' => 'Iklan | Customer',
            'id_user' => $id_user,
        ]);
    }

    public function layananIklan($id_user, $harga_iklan)
    {
        $id_user_decrypt  = Crypt::decryptString($id_user);

        $paket = $this->getDurasiDanHargaBerdasarkanPaket($harga_iklan);
        $harga_sewa_iklan = $paket['harga'];
        $durasi_sewa_iklan = $paket['durasi'];

        // Ambil produk milik toko mitra jika mitra ingin menautkan banner ke produk tertentu
        $produkList = \App\Models\Produk::where('id_user', $id_user_decrypt)->get();

        return view('customers.menu-iklan.input-table-iklan')->with([
            'title' => 'Iklan | Customer',
            'id_user_dec' => $id_user_decrypt,
            'id_user' => $id_user,
            'harga_sewa_iklan' => $harga_sewa_iklan,
            'durasi_sewa_iklan' => $durasi_sewa_iklan,
            'produkList' => $produkList,
        ]);
    }

    private function getDurasiDanHargaBerdasarkanPaket($harga_iklan)
    {
        $paket = [
            'paling-murah' => ['durasi' => 1, 'harga' => 50000],
            'murah' => ['durasi' => 2, 'harga' => 90000],
            'sedang' => ['durasi' => 3, 'harga' => 120000],
            'ideal' => ['durasi' => 5, 'harga' => 200000],
            'populer' => ['durasi' => 7, 'harga' => 250000],
            'premium' => ['durasi' => 10, 'harga' => 300000],
            'ultimate' => ['durasi' => 14, 'harga' => 400000],
        ];

        return $paket[$harga_iklan] ?? ['durasi' => 1, 'harga' => 10000];
    }

    private function cekKetersediaanTanggal($tanggalMulai, $tanggalAkhir, $iklanAktif, $durasi)
    {
        $tanggalMulaiAwal = $tanggalMulai;

        while (true) {
            $iklanDalamRentang = $iklanAktif->filter(function ($iklan) use ($tanggalMulai, $tanggalAkhir) {
                $iklanMulai = Carbon::parse($iklan->tanggal_mulai);
                $iklanAkhir = Carbon::parse($iklan->tanggal_akhir);
                return ($tanggalMulai->between($iklanMulai, $iklanAkhir) || $tanggalAkhir->between($iklanMulai, $iklanAkhir));
            });

            // Jika jumlah iklan dalam rentang kurang dari 10, kembalikan tanggal awal
            if ($iklanDalamRentang->count() < 10) {
                break;
            }

            // Jika jumlah iklan dalam rentang >= 10, geser tanggal mulai dan akhir
            $tanggalMulai = $tanggalMulaiAwal->addDay();
            $tanggalAkhir = $tanggalMulai->copy()->addDays($durasi);
        }

        return [$tanggalMulai, $tanggalAkhir];
    }

    public function simpanIklan($id_user, $harga_iklan, $durasi, Request $request)
    {
        // Normalisasi line breaks (\r\n -> \n) agar penghitungan karakter sama persis antara browser (JavaScript / HTML5 maxlength) dan PHP
        if ($request->has('deskripsi')) {
            $request->merge([
                'deskripsi' => str_replace(["\r\n", "\r"], "\n", $request->input('deskripsi'))
            ]);
        }
        if ($request->has('sub_judul')) {
            $request->merge([
                'sub_judul' => str_replace(["\r\n", "\r"], "\n", $request->input('sub_judul'))
            ]);
        }
        if ($request->has('judul')) {
            $request->merge([
                'judul' => str_replace(["\r\n", "\r"], "\n", $request->input('judul'))
            ]);
        }

        $validator = Validator::make($request->all(), [
            'poster' => 'required|image|mimes:png,jpg,jpeg,webp|max:3072',
            'judul' => 'required|string|max:100',
            'sub_judul' => 'nullable|string|max:200',
            'deskripsi' => 'required|string|max:5000',
            'target_type' => 'nullable|in:toko,produk',
            'id_produk' => 'nullable',
        ], [
            'poster.required' => 'Poster banner iklan wajib diunggah.',
            'poster.image' => 'Poster banner harus berupa file gambar.',
            'poster.mimes' => 'Format file poster harus: png, jpg, jpeg, webp.',
            'poster.max' => 'Ukuran poster tidak boleh lebih dari 3MB.',
            'judul.required' => 'Judul banner iklan wajib diisi.',
            'judul.string' => 'Judul banner iklan harus berupa teks.',
            'judul.max' => 'Judul banner iklan tidak boleh lebih dari 100 karakter.',
            'sub_judul.string' => 'Sub judul banner iklan harus berupa teks.',
            'sub_judul.max' => 'Sub judul banner iklan tidak boleh lebih dari 200 karakter.',
            'deskripsi.required' => 'Deskripsi banner iklan wajib diisi.',
            'deskripsi.string' => 'Deskripsi banner iklan harus berupa teks.',
            'deskripsi.max' => 'Deskripsi banner iklan tidak boleh lebih dari 5.000 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $id_user_dec = Crypt::decryptString($id_user);
        $user_data = User::where('id', $id_user_dec)->first();

        // Bersihkan terlebih dahulu draft iklan yang belum dibayar > 24 jam agar tidak memblokir pembuatan iklan baru
        IklanService::autoCleanupUnpaidAds(24);

        // Cek apakah user masih memiliki tagihan iklan yang belum diselesaikan
        $pendingAd = Iklan::leftJoin('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->where('iklan.id_user', $id_user_dec)
            ->where(function ($q) {
                $q->where('detail_iklan.status_iklan', 'Menunggu Pembayaran')
                  ->orWhere('pembayaran_iklan.status_bayar', 'pending')
                  ->orWhereNull('pembayaran_iklan.id');
            })
            ->where('iklan.status', '!=', 'expired')
            ->first();

        if ($pendingAd) {
            Alert::warning('Tagihan Tertunda', 'Anda masih memiliki materi iklan yang belum diselesaikan pembayarannya. Silakan lanjutkan pembayaran atau batalkan iklan tersebut terlebih dahulu.');
            return redirect()->route('buat-iklan.index', ['id_user' => $id_user]);
        }

        // Cek cooldown hanya untuk iklan yang sudah LUNAS dan masih aktif/antrean
        $endDate = Carbon::today()->subDays($durasi);
        $existingAd = Iklan::join('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
            ->join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->where('iklan.id_user', $id_user_dec)
            ->where('pembayaran_iklan.status_bayar', 'lunas')
            ->whereIn('detail_iklan.status_iklan', ['Aktif', 'Pending'])
            ->where('iklan.created_at', '>', $endDate)
            ->first();

        if ($existingAd) {
            Alert::toast('Anda tidak dapat membuat iklan baru sebelum ' . $durasi . ' hari dari transaksi terakhir.', 'warning');
            return redirect()->back()
                ->withErrors(['error' => 'Anda tidak dapat membuat iklan baru sebelum ' . $durasi . ' hari dari transaksi terakhir.'])
                ->withInput();
        }

        // Set up Midtrans configuration
        $serverKey = config('midtrans.serverKey');
        if (empty($serverKey)) {
            Alert::error('Konfigurasi Midtrans Belum Diatur', 'MIDTRANS_SERVER_KEY belum diisi di file .env. Silakan masukkan Server Key dari dashboard Midtrans Sandbox Anda.');
            return redirect()->back()
                ->withErrors(['error' => 'MIDTRANS_SERVER_KEY belum diisi pada file .env.'])
                ->withInput();
        }

        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized', true);
        \Midtrans\Config::$is3ds = config('midtrans.is3ds', true);

        $orderId = 'IKLAN-' . $id_user_dec . '-' . time() . '-' . rand(100, 999);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $harga_iklan,
            ],
            'customer_details' => [
                'first_name' => $user_data->name ?? 'Customer',
                'email' => $user_data->email ?? 'customer@kampsewa.com',
                'phone' => $user_data->nomor_telephone ?? '',
            ],
            'item_details' => [
                [
                    'id' => 'PAKET-IKLAN-' . $durasi,
                    'price' => (int) $harga_iklan,
                    'quantity' => 1,
                    'name' => 'Paket Iklan ' . $durasi . ' Hari',
                ]
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Alert::error('Gagal Menghubungi Midtrans', $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Gagal membuat transaksi Midtrans: ' . $e->getMessage()])
                ->withInput();
        }

        // Hitung ketersediaan tanggal penayangan
        $iklanAktif = DB::table('detail_iklan')
            ->whereIn('status_iklan', ['Pending', 'Aktif', 'pending', 'aktif'])
            ->orderBy('tanggal_mulai')
            ->get();
        $tanggalMulai = Carbon::today();
        $tanggalAkhir = Carbon::today()->addDays((int) $durasi);
        list($tanggalMulai, $tanggalAkhir) = $this->cekKetersediaanTanggal($tanggalMulai, $tanggalAkhir, $iklanAktif, (int) $durasi);

        $iklan = new Iklan();
        $iklan->id_user = $id_user_dec;
        $iklan->judul = $request->judul;
        $iklan->sub_judul = $request->sub_judul ?: null;
        $iklan->deskripsi = $request->deskripsi;
        $iklan->target_type = $request->target_type ?: 'toko';
        $iklan->id_produk = ($request->target_type === 'produk' && !empty($request->id_produk)) ? (int) $request->id_produk : null;
        $iklan->status = 'inactive';
        $iklan->snap_token = $snapToken;

        if ($request->hasFile('poster')) {
            $poster = $request->file('poster');
            $posterName = 'advert_' . time() . '_' . Str::random(6) . '.' . $poster->getClientOriginalExtension();
            $poster->move(public_path('assets/image/customers/advert/'), $posterName);
            $iklan->poster = $posterName;
        }

        $iklan->save();

        // Segera buat catatan DetailIklan dan PembayaranIklan berstatus Menunggu Pembayaran
        // Agar jika browser tertutup/kembali, materi iklan tidak hilang dan bisa dilanjutkan
        DetailIklan::create([
            'id_iklan' => $iklan->id,
            'tanggal_mulai' => $tanggalMulai->toDateString(),
            'tanggal_akhir' => $tanggalAkhir->toDateString(),
            'harga_iklan' => $harga_iklan,
            'status_iklan' => 'Menunggu Pembayaran',
        ]);

        PembayaranIklan::create([
            'id_iklan' => $iklan->id,
            'id_user' => $id_user_dec,
            'midtrans_order_id' => $orderId,
            'payment_type' => 'midtrans',
            'midtrans_transaction_status' => 'pending',
            'metode_bayar' => 'Menunggu Pilihan Pembayaran',
            'total_bayar' => $harga_iklan,
            'status_bayar' => 'pending',
        ]);

        $harga_iklan_enc = Crypt::encrypt($harga_iklan);
        $durasi_enc = Crypt::encryptString($durasi);

        Alert::success('Materi Iklan Tersimpan', 'Silakan selesaikan pembayaran untuk mengaktifkan iklan.');
        return redirect('/customer/dashboard/input-pembayaran-iklan/' . $id_user . '/' . $harga_iklan_enc . '/' . $durasi_enc);
    }

    public function inputPembayaranIklan($id_user, $harga_iklan, $durasi)
    {
        $id_user_decrypt = Crypt::decryptString($id_user);
        $harga_iklan_dec = Crypt::decrypt($harga_iklan);
        $durasi_dec = Crypt::decryptString($durasi);

        $iklan = Iklan::where('id_user', $id_user_decrypt)->latest()->first();
        $id_iklan = $iklan ? $iklan->id : null;
        $snap_token = $iklan ? $iklan->snap_token : null;
        $pembayaran = $iklan ? PembayaranIklan::where('id_iklan', $iklan->id)->first() : null;

        // Jika snap token kosong atau iklan dibuat lebih dari 15 menit lalu (potensi QRIS/VA kedaluwarsa), perbarui token secara otomatis
        if ($iklan && (empty($snap_token) || $iklan->updated_at < Carbon::now()->subMinutes(15))) {
            try {
                list($snap_token, $newOrderId) = $this->generateFreshSnapToken($iklan, $pembayaran, $id_user_decrypt, $harga_iklan_dec, $durasi_dec);
            } catch (\Exception $e) {
                Log::warning('Auto refresh token on inputPembayaranIklan failed: ' . $e->getMessage());
            }
        }

        $detail = $iklan ? DetailIklan::where('id_iklan', $iklan->id)->first() : null;
        if ($detail) {
            $tanggalMulai = Carbon::parse($detail->tanggal_mulai);
            $tanggalAkhir = Carbon::parse($detail->tanggal_akhir);
        } else {
            // Fallback kalkulasi
            $iklanAktif = DB::table('detail_iklan')
                ->whereIn('status_iklan', ['Pending', 'Aktif', 'pending', 'aktif'])
                ->orderBy('tanggal_mulai')
                ->get();
            $tanggalMulai = Carbon::today();
            $tanggalAkhir = Carbon::today()->addDays((int) $durasi_dec);
            list($tanggalMulai, $tanggalAkhir) = $this->cekKetersediaanTanggal($tanggalMulai, $tanggalAkhir, $iklanAktif, (int) $durasi_dec);
        }

        return view('customers.menu-iklan.input-table-pembayaran')->with([
            'title' => 'Pembayaran Iklan | Customer',
            'id_user' => $id_user_decrypt,
            'id_user_enc' => $id_user,
            'harga_iklan' => $harga_iklan_dec,
            'id_iklan' => $id_iklan,
            'durasi' => $durasi_dec,
            'tanggal_mulai' => $tanggalMulai->toDateString(),
            'tanggal_akhir' => $tanggalAkhir->toDateString(),
            'snap_token' => $snap_token,
            'iklan' => $iklan,
        ]);
    }

    /**
     * Helper untuk membuat Snap Token Midtrans baru yang selalu segar dan aktif.
     * Mencegah error "Transaksi sudah kedaluwarsa" jika sesi pembayaran lama telah habis waktu.
     */
    private function generateFreshSnapToken($iklan, $pembayaran, $userId, $hargaIklan, $durasi)
    {
        $serverKey = config('midtrans.serverKey');
        if (empty($serverKey)) {
            throw new \Exception('MIDTRANS_SERVER_KEY belum diisi di file .env.');
        }

        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized', true);
        \Midtrans\Config::$is3ds = config('midtrans.is3ds', true);

        $newOrderId = 'IKLAN-' . $userId . '-' . $iklan->id . '-' . time() . '-' . rand(100, 999);
        $user = User::find($userId) ?? auth()->user();

        $params = [
            'transaction_details' => [
                'order_id' => $newOrderId,
                'gross_amount' => (int) $hargaIklan,
            ],
            'customer_details' => [
                'first_name' => $user->name ?? 'Customer',
                'email' => $user->email ?? 'customer@kampsewa.com',
                'phone' => $user->nomor_telephone ?? '',
            ],
            'item_details' => [
                [
                    'id' => 'PAKET-IKLAN-' . $durasi,
                    'price' => (int) $hargaIklan,
                    'quantity' => 1,
                    'name' => 'Paket Iklan ' . $durasi . ' Hari',
                ]
            ],
        ];

        $freshSnapToken = \Midtrans\Snap::getSnapToken($params);
        $iklan->update(['snap_token' => $freshSnapToken]);

        if ($pembayaran) {
            $pembayaran->update([
                'midtrans_order_id' => $newOrderId,
                'midtrans_transaction_status' => 'pending',
            ]);
        }

        return [$freshSnapToken, $newOrderId];
    }

    public function lanjutPembayaranIklan($id_iklan)
    {
        try {
            try {
                $id_iklan_dec = Crypt::decrypt($id_iklan);
            } catch (\Exception $e) {
                try {
                    $id_iklan_dec = Crypt::decryptString($id_iklan);
                } catch (\Exception $e2) {
                    $id_iklan_dec = $id_iklan;
                }
            }

            $user_id = auth()->user()->id ?? session('id_user');
            $iklan = Iklan::where('id', $id_iklan_dec)
                ->where('id_user', $user_id)
                ->firstOrFail();

            $detail = DetailIklan::where('id_iklan', $id_iklan_dec)->first();
            $pembayaran = PembayaranIklan::where('id_iklan', $id_iklan_dec)->first();

            // Cek apakah sudah lunas di database
            if ($pembayaran && strtolower($pembayaran->status_bayar) === 'lunas') {
                Alert::info('Sudah Dibayar', 'Iklan ini sudah berhasil dibayar dan aktif.');
                return redirect()->route('buat-iklan.index', ['id_user' => Crypt::encrypt($user_id)]);
            }

            // Cek status ke Midtrans via API secara otomatis jika order_id sudah ada
            if ($pembayaran && !empty($pembayaran->midtrans_order_id)) {
                $serverKey = config('midtrans.serverKey');
                if (!empty($serverKey)) {
                    \Midtrans\Config::$serverKey = $serverKey;
                    \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);

                    try {
                        $midtransStatus = \Midtrans\Transaction::status($pembayaran->midtrans_order_id);
                        $trxStatus = strtolower($midtransStatus->transaction_status ?? '');
                        if (in_array($trxStatus, ['settlement', 'capture', 'success'])) {
                            $pembayaran->update([
                                'status_bayar' => 'lunas',
                                'midtrans_transaction_status' => $trxStatus,
                                'payment_type' => $midtransStatus->payment_type ?? $pembayaran->payment_type,
                            ]);

                            $statusIklan = ($detail && Carbon::today() >= Carbon::parse($detail->tanggal_mulai)) ? 'Aktif' : 'Pending';
                            if ($detail) {
                                $detail->update(['status_iklan' => $statusIklan]);
                            }
                            $iklan->update(['status' => 'active']);

                            PemasukanPerusahaan::firstOrCreate(
                                ['deskripsi' => 'Sumber pemasukan dari layanan iklan customer (Order ID: ' . $pembayaran->midtrans_order_id . ')'],
                                [
                                    'sumber' => 'Layanan Iklan',
                                    'nominal' => $pembayaran->total_bayar ?? ($detail ? $detail->harga_iklan : 0),
                                ]
                            );

                            Alert::success('Pembayaran Terverifikasi!', 'Pembayaran Anda telah diterima dan materi iklan telah aktif.');
                            return redirect()->route('buat-iklan.index', ['id_user' => Crypt::encrypt($user_id)]);
                        }
                    } catch (\Exception $midtransError) {
                        Log::info('Midtrans check status skipped: ' . $midtransError->getMessage());
                    }
                }
            }

            $harga_iklan = $detail ? $detail->harga_iklan : 50000;
            $durasi = ($detail && $detail->tanggal_mulai && $detail->tanggal_akhir)
                ? max(1, Carbon::parse($detail->tanggal_mulai)->diffInDays(Carbon::parse($detail->tanggal_akhir)))
                : 1;

            // Selalu generate Snap Token baru dan Order ID baru yang aktif agar bebas dari error kedaluwarsa Midtrans
            list($snapToken, $newOrderId) = $this->generateFreshSnapToken($iklan, $pembayaran, $user_id, $harga_iklan, $durasi);

            return view('customers.menu-iklan.input-table-pembayaran')->with([
                'title' => 'Pembayaran Iklan | Customer',
                'id_user' => $user_id,
                'id_user_enc' => Crypt::encryptString($user_id),
                'harga_iklan' => $harga_iklan,
                'id_iklan' => $iklan->id,
                'durasi' => $durasi,
                'tanggal_mulai' => $detail ? $detail->tanggal_mulai : Carbon::today()->toDateString(),
                'tanggal_akhir' => $detail ? $detail->tanggal_akhir : Carbon::today()->addDays($durasi)->toDateString(),
                'snap_token' => $snapToken,
                'iklan' => $iklan,
            ]);
        } catch (\Exception $e) {
            Log::error('Error lanjutPembayaranIklan: ' . $e->getMessage());
            Alert::error('Gagal Membuka Pembayaran', $e->getMessage());
            return redirect()->route('buat-iklan.index', ['id_user' => Crypt::encrypt(session('id_user'))]);
        }
    }

    public function cekStatusPembayaranIklan($id_iklan)
    {
        try {
            try {
                $id_iklan_dec = Crypt::decrypt($id_iklan);
            } catch (\Exception $e) {
                try {
                    $id_iklan_dec = Crypt::decryptString($id_iklan);
                } catch (\Exception $e2) {
                    $id_iklan_dec = $id_iklan;
                }
            }

            $user_id = auth()->user()->id ?? session('id_user');
            $iklan = Iklan::where('id', $id_iklan_dec)
                ->where('id_user', $user_id)
                ->firstOrFail();

            $detail = DetailIklan::where('id_iklan', $id_iklan_dec)->first();
            $pembayaran = PembayaranIklan::where('id_iklan', $id_iklan_dec)->first();

            if (!$pembayaran || empty($pembayaran->midtrans_order_id)) {
                Alert::warning('Data Belum Tersedia', 'Riwayat pembayaran untuk iklan ini belum memiliki ID pesanan.');
                return back();
            }

            $serverKey = config('midtrans.serverKey');
            if (empty($serverKey)) {
                Alert::error('Konfigurasi Error', 'Server Key Midtrans belum diisi pada file konfigurasi.');
                return back();
            }

            \Midtrans\Config::$serverKey = $serverKey;
            \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);

            try {
                $midtransStatus = \Midtrans\Transaction::status($pembayaran->midtrans_order_id);
                $trxStatus = strtolower($midtransStatus->transaction_status ?? '');
                $fraudStatus = strtolower($midtransStatus->fraud_status ?? '');

                if (in_array($trxStatus, ['settlement', 'capture', 'success']) && (empty($fraudStatus) || $fraudStatus === 'accept')) {
                    $pembayaran->update([
                        'status_bayar' => 'lunas',
                        'midtrans_transaction_status' => $trxStatus,
                        'payment_type' => $midtransStatus->payment_type ?? $pembayaran->payment_type,
                    ]);

                    $statusIklan = ($detail && Carbon::today() >= Carbon::parse($detail->tanggal_mulai)) ? 'Aktif' : 'Pending';
                    if ($detail) {
                        $detail->update(['status_iklan' => $statusIklan]);
                    }
                    $iklan->update(['status' => 'active']);

                    PemasukanPerusahaan::firstOrCreate(
                        ['deskripsi' => 'Sumber pemasukan dari layanan iklan customer (Order ID: ' . $pembayaran->midtrans_order_id . ')'],
                        [
                            'sumber' => 'Layanan Iklan',
                            'nominal' => $pembayaran->total_bayar ?? ($detail ? $detail->harga_iklan : 0),
                        ]
                    );

                    Alert::success('Pembayaran Terverifikasi!', 'Pembayaran iklan Anda telah berhasil diterima dan materi iklan telah diaktifkan!')->persistent(true);
                    return back();
                } elseif (in_array($trxStatus, ['pending'])) {
                    Alert::info('Menunggu Pembayaran', 'Status di sistem Midtrans masih menunggu pembayaran. Silakan selesaikan pembayaran sesuai metode yang dipilih.');
                    return back();
                } elseif (in_array($trxStatus, ['expire', 'cancel', 'deny'])) {
                    $pembayaran->update([
                        'midtrans_transaction_status' => $trxStatus,
                    ]);

                    $durasi = ($detail && $detail->tanggal_mulai && $detail->tanggal_akhir)
                        ? max(1, Carbon::parse($detail->tanggal_mulai)->diffInDays(Carbon::parse($detail->tanggal_akhir)))
                        : 1;

                    // Otomatis buat sesi token segar baru agar siap dibayar
                    $this->generateFreshSnapToken($iklan, $pembayaran, $user_id, $detail ? $detail->harga_iklan : 50000, $durasi);

                    Alert::warning('Sesi Transaksi Diperbarui', 'Transaksi sebelumnya telah kedaluwarsa di Midtrans. Sistem telah memperbarui sesi pembayaran baru untuk Anda. Silakan klik tombol "Bayar Sekarang".')->persistent(true);
                    return back();
                } else {
                    Alert::info('Status Transaksi', 'Status transaksi Midtrans: ' . ucfirst($trxStatus));
                    return back();
                }
            } catch (\Exception $midtransErr) {
                Alert::warning('Belum Ditemukan di Midtrans', 'Transaksi belum tercatat di sistem Midtrans. Silakan selesaikan pembayaran via tombol "Bayar Sekarang".');
                return back();
            }
        } catch (\Exception $e) {
            Log::error('Error cekStatusPembayaranIklan: ' . $e->getMessage());
            Alert::error('Gagal Mengecek Status', $e->getMessage());
            return back();
        }
    }

    public function batalIklan($id_iklan)
    {
        try {
            try {
                $id_iklan_dec = Crypt::decrypt($id_iklan);
            } catch (\Exception $e) {
                try {
                    $id_iklan_dec = Crypt::decryptString($id_iklan);
                } catch (\Exception $e2) {
                    $id_iklan_dec = $id_iklan;
                }
            }

            $user_id = auth()->user()->id ?? session('id_user');
            $iklan = Iklan::where('id', $id_iklan_dec)
                ->where('id_user', $user_id)
                ->firstOrFail();

            $pembayaran = PembayaranIklan::where('id_iklan', $id_iklan_dec)->first();
            if ($pembayaran && strtolower($pembayaran->status_bayar) === 'lunas') {
                Alert::error('Tidak Dapat Dibatalkan', 'Iklan ini sudah lunas dibayar dan tidak dapat dibatalkan.');
                return back();
            }

            // Hapus file poster jika ada
            if (!empty($iklan->poster)) {
                $filePath = public_path('assets/image/customers/advert/' . $iklan->poster);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            PembayaranIklan::where('id_iklan', $id_iklan_dec)->delete();
            DetailIklan::where('id_iklan', $id_iklan_dec)->delete();
            $iklan->delete();

            Alert::toast('Draft iklan yang belum dibayar berhasil dibatalkan.', 'success');
            return back();
        } catch (\Exception $e) {
            Log::error('Error batalIklan: ' . $e->getMessage());
            Alert::error('Gagal Membatalkan Iklan', $e->getMessage());
            return back();
        }
    }

    public function simpanPembayaranIklan($id_user, Request $request)
    {
        $request->validate([
            'id_iklan' => 'required',
            'id_user' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date',
            'harga_iklan' => 'required|numeric',
            'midtrans_order_id' => 'nullable|string',
            'payment_type' => 'nullable|string',
            'midtrans_transaction_status' => 'nullable|string',
        ]);

        $orderId = $request->midtrans_order_id ?: ('IKLAN-' . time());
        $paymentType = $request->payment_type ?: 'midtrans';
        $transactionStatus = $request->midtrans_transaction_status ?: 'settlement';

        // Tentukan status kelulusan pembayaran
        $isLunas = in_array(strtolower($transactionStatus), ['settlement', 'capture', 'success']);
        $statusBayar = $isLunas ? 'lunas' : 'pending';

        // Status iklan:
        // Jika lunas: Aktif jika tanggal_mulai <= hari ini, Pending jika tanggal_mulai > hari ini
        // Jika belum lunas (pending): Menunggu Pembayaran
        $status_iklan = $isLunas
            ? (Carbon::today() >= Carbon::parse($request->tanggal_mulai) ? 'Aktif' : 'Pending')
            : 'Menunggu Pembayaran';

        try {
            // Format nama metode pembayaran
            $metodeBayarFormatted = match (strtolower($paymentType)) {
                'bank_transfer' => 'Bank Transfer / VA',
                'echannel' => 'Mandiri Bill',
                'qris' => 'QRIS',
                'gopay' => 'GoPay',
                'shopeepay' => 'ShopeePay',
                'cstore' => 'Indomaret / Alfamart',
                'credit_card' => 'Kartu Kredit / Debit',
                default => ucwords(str_replace('_', ' ', $paymentType)),
            };

            // Update or create detail iklan
            DetailIklan::updateOrCreate(
                ['id_iklan' => $request->id_iklan],
                [
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_akhir' => $request->tanggal_akhir,
                    'harga_iklan' => $request->harga_iklan,
                    'status_iklan' => $status_iklan,
                ]
            );

            // Update or create riwayat pembayaran iklan
            PembayaranIklan::updateOrCreate(
                ['id_iklan' => $request->id_iklan],
                [
                    'id_user' => $request->id_user,
                    'midtrans_order_id' => $orderId,
                    'payment_type' => $paymentType,
                    'midtrans_transaction_status' => $transactionStatus,
                    'metode_bayar' => $metodeBayarFormatted,
                    'total_bayar' => $request->harga_iklan,
                    'status_bayar' => $statusBayar,
                ]
            );

            // Simpan pemasukan sistem jika status lunas
            if ($isLunas) {
                PemasukanPerusahaan::firstOrCreate(
                    ['deskripsi' => 'Sumber pemasukan dari layanan iklan customer (Order ID: ' . $orderId . ')'],
                    [
                        'sumber' => 'Layanan Iklan',
                        'nominal' => $request->harga_iklan,
                    ]
                );
                Iklan::where('id', $request->id_iklan)->update(['status' => 'active']);
                Alert::toast('Terima kasih! Pembayaran iklan Anda berhasil dan iklan telah aktif.', 'success');
            } else {
                Alert::toast('Materi iklan tersimpan. Silakan selesaikan transaksi pada daftar Menunggu Pembayaran.', 'info');
            }

            return redirect()->route('buat-iklan.index', ['id_user' => $id_user]);
        } catch (\Exception $e) {
            Log::error('Error simpanPembayaranIklan: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function kelolaIklan(Request $request, $id_user)
    {
        try {
            $id_user_decrypt = Crypt::decrypt($id_user);

            // Bersihkan otomatis transaksi iklan yang belum dibayar lebih dari 1 hari (24 jam)
            IklanService::autoCleanupUnpaidAds(24);

            // Sinkronisasi status iklan (kadaluarsa -> Selesai)
            IklanService::autoSyncStatusIklan();

            // Get the selected filter
            $filter = $request->input('filter_kelola_iklan', 'semua');

            // Base query to get all ads related to the user with latest suspend information
            $query = Iklan::leftJoin('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
                ->leftJoin('pembayaran_iklan', 'iklan.id', '=', 'pembayaran_iklan.id_iklan')
                ->join('users', 'users.id', '=', 'iklan.id_user')
                ->leftJoin('iklan_suspends', function ($join) {
                    $join->on('iklan.id', '=', 'iklan_suspends.id_iklan')
                         ->whereRaw('iklan_suspends.id = (SELECT MAX(id) FROM iklan_suspends WHERE iklan_suspends.id_iklan = iklan.id)');
                })
                ->select(
                    'iklan.id as id_iklan',
                    'iklan.poster',
                    'iklan.judul',
                    'iklan.sub_judul',
                    'iklan.deskripsi',
                    'iklan.snap_token',
                    'detail_iklan.id as id_detail_iklan',
                    'detail_iklan.tanggal_mulai',
                    'detail_iklan.tanggal_akhir',
                    'detail_iklan.harga_iklan',
                    'detail_iklan.status_iklan',
                    'detail_iklan.is_force_completed',
                    'detail_iklan.force_complete_alasan',
                    'detail_iklan.force_complete_catatan',
                    'detail_iklan.force_completed_at',
                    'pembayaran_iklan.status_bayar',
                    'pembayaran_iklan.midtrans_order_id',
                    DB::raw('COALESCE(DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai), 0) as durasi_hari'),
                    'iklan_suspends.alasan as suspend_alasan',
                    'iklan_suspends.catatan as suspend_catatan',
                    'iklan_suspends.bagian_revisi as suspend_bagian_revisi',
                    'iklan_suspends.status_revisi as suspend_status_revisi',
                    'iklan_suspends.created_at as suspend_created_at'
                )
                ->where('iklan.id_user', $id_user_decrypt);

            $get_all_data = $query->get();

            // Tambahkan computed property status_tampilan
            $get_all_data->transform(function ($item) {
                $isExpiredByDate = !empty($item->tanggal_akhir) && Carbon::today()->gt(Carbon::parse($item->tanggal_akhir));

                if (empty($item->status_bayar) || strtolower($item->status_bayar) !== 'lunas') {
                    $item->status_tampilan = 'Menunggu Pembayaran';
                } elseif (strcasecmp($item->status_iklan, 'Selesai') === 0 || $isExpiredByDate || !empty($item->is_force_completed)) {
                    $item->status_tampilan = $item->is_force_completed ? 'Diselesaikan Paksa' : 'Selesai';
                } elseif (strcasecmp($item->status_iklan, 'Nonaktif') === 0) {
                    $item->status_tampilan = 'Nonaktif';
                } elseif (strcasecmp($item->status_iklan, 'Aktif') === 0) {
                    $item->status_tampilan = 'Aktif';
                } else {
                    $item->status_tampilan = 'Pending';
                }
                return $item;
            });

            // Filter data
            if ($filter !== 'semua') {
                $filtered_data = $get_all_data->filter(function ($item) use ($filter) {
                    if ($filter === 'menunggu_pembayaran') {
                        return $item->status_tampilan === 'Menunggu Pembayaran';
                    } elseif ($filter === 'selesai_paksa') {
                        return (bool) $item->is_force_completed;
                    } elseif ($filter === 'selesai') {
                        return strcasecmp($item->status_iklan, 'Selesai') === 0;
                    }
                    return strcasecmp($item->status_tampilan, $filter) === 0;
                });
            } else {
                $filtered_data = $get_all_data;
            }

            // Pisahkan per kategori
            $iklan_belum_bayar = $get_all_data->filter(fn($item) => $item->status_tampilan === 'Menunggu Pembayaran');
            $iklan_berlangsung = $get_all_data->filter(fn($item) => $item->status_tampilan === 'Aktif');
            $iklan_pending = $get_all_data->filter(fn($item) => $item->status_tampilan === 'Pending');
            $iklan_selesai = $get_all_data->filter(fn($item) => strcasecmp($item->status_iklan, 'Selesai') === 0);
            $iklan_nonaktif = $get_all_data->filter(fn($item) => $item->status_tampilan === 'Nonaktif');
            $iklan_force_completed = $get_all_data->filter(fn($item) => (bool) $item->is_force_completed);

            return view('customers.menu-iklan.kelola-iklan')->with([
                'title' => 'Kelola Iklan',
                'total_data_iklan' => $get_all_data->count(),
                'iklan_belum_bayar' => $iklan_belum_bayar,
                'iklan_berlangsung' => $iklan_berlangsung,
                'iklan_pending' => $iklan_pending,
                'iklan_selesai' => $iklan_selesai,
                'iklan_nonaktif' => $iklan_nonaktif,
                'iklan_force_completed' => $iklan_force_completed,
                'all_iklan' => $filtered_data,
                'selected_filter' => $filter,
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return back()->withErrors(['error' => $error->getMessage()]);
        }
    }

    public function deleteKelolaIklan($id_iklan)
    {
        try {
            $iklan = Iklan::where('id', $id_iklan)->first();
            if ($iklan && !empty($iklan->poster)) {
                $filePath = public_path('assets/image/customers/advert/' . $iklan->poster);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            PembayaranIklan::where('id_iklan', $id_iklan)->delete();
            DetailIklan::where('id_iklan', $id_iklan)->delete();
            Iklan::where('id', $id_iklan)->delete();

            Alert::toast('Data berhasil dihapus!', 'success');
            return back();
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return back()->withErrors(['error' => $error->getMessage()]);
        }
    }

    public function updateIklanView($id_iklan)
    {
        try {
            $id_decrypt = Crypt::decrypt($id_iklan);
        } catch (\Exception $e) {
            $id_decrypt = $id_iklan;
        }
        $get_data = Iklan::where('id', $id_decrypt)->first();
        $detail_iklan = DetailIklan::where('id_iklan', $id_decrypt)->first();

        // Validasi: Cegah edit jika iklan berstatus Selesai, Diselesaikan Paksa, atau tanggal akhir sudah lewat
        if ($detail_iklan) {
            $isExpired = !empty($detail_iklan->tanggal_akhir) && Carbon::today()->gt(Carbon::parse($detail_iklan->tanggal_akhir));
            if (strcasecmp($detail_iklan->status_iklan, 'Selesai') === 0 || !empty($detail_iklan->is_force_completed) || $isExpired) {
                Alert::warning('Iklan Tidak Dapat Diubah', 'Iklan ini telah berstatus selesai atau kadaluarsa sehingga tidak dapat diedit kembali.');
                return back();
            }
        }

        $suspend_info = \App\Models\IklanSuspend::where('id_iklan', $id_decrypt)->latest()->first();

        return view('customers.menu-iklan.update-iklan')->with([
            'title' => 'Update Iklan',
            'data' => $get_data,
            'detail_iklan' => $detail_iklan,
            'suspend_info' => $suspend_info,
        ]);
    }

    public function updateIklan($id_iklan, $id_user, Request $request)
    {
        try {
            // Normalisasi line breaks (\r\n -> \n)
            if ($request->has('deskripsi')) {
                $request->merge([
                    'deskripsi' => str_replace(["\r\n", "\r"], "\n", $request->input('deskripsi'))
                ]);
            }
            if ($request->has('sub_judul')) {
                $request->merge([
                    'sub_judul' => str_replace(["\r\n", "\r"], "\n", $request->input('sub_judul'))
                ]);
            }
            if ($request->has('judul')) {
                $request->merge([
                    'judul' => str_replace(["\r\n", "\r"], "\n", $request->input('judul'))
                ]);
            }

            $request->validate([
                'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
                'judul' => 'required|string|max:100',
                'sub_judul' => 'nullable|string|max:200',
                'deskripsi' => 'required|string|max:5000',
            ], [
                'poster.image' => 'Poster banner harus berupa file gambar.',
                'poster.mimes' => 'Format file poster harus: jpeg, png, jpg, webp.',
                'poster.max' => 'Ukuran poster tidak boleh lebih dari 3MB.',
                'judul.required' => 'Judul banner iklan wajib diisi.',
                'judul.max' => 'Judul banner iklan tidak boleh lebih dari 100 karakter.',
                'sub_judul.max' => 'Sub judul banner iklan tidak boleh lebih dari 200 karakter.',
                'deskripsi.required' => 'Deskripsi banner iklan wajib diisi.',
                'deskripsi.max' => 'Deskripsi banner iklan tidak boleh lebih dari 5.000 karakter.',
            ]);

            $get_data = Iklan::findOrFail($id_iklan);
            $detail_iklan = DetailIklan::where('id_iklan', $id_iklan)->first();

            // Validasi: Cegah update jika iklan berstatus Selesai, Diselesaikan Paksa, atau tanggal akhir sudah lewat
            if ($detail_iklan) {
                $isExpired = !empty($detail_iklan->tanggal_akhir) && Carbon::today()->gt(Carbon::parse($detail_iklan->tanggal_akhir));
                if (strcasecmp($detail_iklan->status_iklan, 'Selesai') === 0 || !empty($detail_iklan->is_force_completed) || $isExpired) {
                    Alert::warning('Iklan Tidak Dapat Diubah', 'Iklan ini telah berstatus selesai atau kadaluarsa sehingga tidak dapat diedit kembali.');
                    return back();
                }
            }

            $list_update = [
                'judul' => request()->input('judul'),
                'sub_judul' => request()->input('sub_judul'),
                'deskripsi' => request()->input('deskripsi'),
            ];

            if (request()->hasFile('poster')) {
                $file = request()->file('poster');
                $destinationPath = public_path('assets/image/customers/advert');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $filename);

                // Simpan path relatif ke database
                $foto_url = $filename;
                $list_update['poster'] = $foto_url;
            }

            $get_data->update($list_update);

            // Jika iklan ini sedang disuspend, tandai revisi sudah dikirim mitra
            $suspend = \App\Models\IklanSuspend::where('id_iklan', $id_iklan)
                ->where('status_revisi', 'menunggu_perbaikan')
                ->latest()
                ->first();

            if ($suspend) {
                $suspend->update([
                    'status_revisi' => 'sudah_diperbaiki',
                ]);
                Alert::success('Perubahan Disimpan!', 'Iklan berhasil diperbarui dan status revisi telah diteruskan ke admin untuk peninjauan & pemulihan.')->persistent(true);
            } else {
                Alert::toast('Data berhasil di update', 'success');
            }

            return redirect()->route('kelola-iklan.index', ['id_user' => $id_user]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return back()->withErrors(['error' => $error->getMessage()]);
        }
    }
}
