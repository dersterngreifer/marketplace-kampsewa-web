<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\DetailIklan;
use App\Models\Iklan;
use App\Models\Pemasukan;
use App\Models\PembayaranIklan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
        $get_total_user_menggunakan_iklan = Iklan::count();

        $iklan_berlangsung = Iklan::join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
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

        $iklan_pending = Iklan::join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
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
                'durasi_hari'
            )->limit(5)->get();

        return view('customers.menu-iklan.iklan')->with([
            'title' => 'Iklan | Customer',
            'total_data_iklan' => $get_total_user_menggunakan_iklan,
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
        $validator = Validator::make(request()->all(), [
            'poster' => 'required|image|mimes:png,jpg,jpeg,webp|max:3072',
            'judul' => 'required|string|max:100',
            'sub_judul' => 'nullable|string|max:200',
            'deskripsi' => 'required|string|max:800',
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
            'deskripsi.required' => 'Deskripsi banner iklan wajib diisi.',
            'deskripsi.string' => 'Deskripsi banner iklan harus berupa teks.',
            'deskripsi.max' => 'Deskripsi banner iklan tidak boleh lebih dari 800 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $id_user_dec = Crypt::decryptString($id_user);
        $user_data = User::where('id', $id_user_dec)->first();

        // Cek apakah user sudah membuat iklan dalam durasi yang dipilih
        $endDate = Carbon::today()->subDays($durasi);
        $existingAd = Iklan::where('id_user', $id_user_dec)
            ->where('created_at', '>', $endDate)
            ->first();

        if ($existingAd) {
            Alert::toast('Anda tidak dapat membuat iklan baru sebelum ' . $durasi . ' hari dari transaksi terakhir.', 'warning');
            return redirect()->back()
                ->withErrors(['error' => 'Anda tidak dapat membuat iklan baru sebelum ' . $durasi . ' hari dari transaksi terakhir.'])
                ->withInput();
        }

        // Set up Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => $harga_iklan,
            ],
            'customer_details' => [
                'first_name' => $user_data->name,
                'email' => $user_data->email,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $iklan = new Iklan();
        $iklan->id_user = $id_user_dec;
        $iklan->judul = $request->judul;
        $iklan->sub_judul = $request->sub_judul ?: null;
        $iklan->deskripsi = $request->deskripsi;
        $iklan->target_type = $request->target_type ?: 'toko';
        $iklan->id_produk = ($request->target_type === 'produk' && !empty($request->id_produk)) ? (int) $request->id_produk : null;
        $iklan->status = 'active';
        $iklan->snap_token = $snapToken;

        if ($request->hasFile('poster')) {
            $poster = $request->file('poster');
            $posterName = 'advert_' . time() . '_' . Str::random(6) . '.' . $poster->getClientOriginalExtension();
            $poster->move(public_path('assets/image/customers/advert/'), $posterName);
            $iklan->poster = $posterName;
        }

        $iklan->save();
        $harga_iklan_enc = Crypt::encrypt($harga_iklan);
        $durasi_enc = Crypt::encryptString($durasi);

        Alert::success('Berhasil ditambahkan', 'success');
        return redirect('/customer/dashboard/input-pembayaran-iklan/' . $id_user . '/' . $harga_iklan_enc . '/' . $durasi_enc);
    }

    public function inputPembayaranIklan($id_user, $harga_iklan, $durasi)
    {
        $id_user_decrypt = Crypt::decryptString($id_user);
        $harga_iklan_dec = Crypt::decrypt($harga_iklan);
        $durasi_dec = Crypt::decryptString($durasi);

        // Ambil semua iklan yang statusnya pending atau aktif
        $iklanAktif = DB::table('detail_iklan')
            ->whereIn('status_iklan', ['pending', 'aktif'])
            ->orderBy('tanggal_mulai')
            ->get();

        // Tentukan tanggal mulai dan akhir
        $tanggalMulai = Carbon::today();
        $tanggalAkhir = Carbon::today()->addDays($durasi_dec);

        // Periksa ketersediaan tanggal dan limit
        list($tanggalMulai, $tanggalAkhir) = $this->cekKetersediaanTanggal($tanggalMulai, $tanggalAkhir, $iklanAktif, $durasi_dec);

        $iklan = Iklan::where('id_user', $id_user_decrypt)->latest()->first();
        $id_iklan = $iklan->id;
        $snap_token = $iklan->snap_token;

        return view('customers.menu-iklan.input-table-pembayaran')->with([
            'title' => 'Iklan | Customer',
            'id_user' => $id_user_decrypt,
            'harga_iklan' => $harga_iklan_dec,
            'id_iklan' => $id_iklan,
            'durasi' => $durasi_dec,
            'tanggal_mulai' => $tanggalMulai->toDateString(),
            'tanggal_akhir' => $tanggalAkhir->toDateString(),
            'snap_token' => $snap_token,
        ]);
    }

    public function simpanPembayaranIklan($id_user,Request $request)
    {
        $request->validate([
            'id_iklan' => 'required|string',
            'id_user' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date',
            'harga_iklan' => 'required|numeric',
        ]);

        $status_iklan = Carbon::today() < $request->tanggal_mulai ? 'pending' : 'aktif';

        try {
            // Simpan detail iklan
            DetailIklan::create([
                'id_iklan' => $request->id_iklan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'harga_iklan' => $request->harga_iklan,
                'status_iklan' => $status_iklan,
            ]);

            // Simpan pembayaran iklan
            PembayaranIklan::create([
                'id_iklan' => $request->id_iklan,
                'id_user' => $request->id_user,
                'metode_bayar' => 'Transfer',
                'total_bayar' => $request->harga_iklan,
                'status_bayar' => 'lunas',
            ]);

            // Simpan Pemasukan
            Pemasukan::create([
                'id_user' => $request->id_user,
                'sumber' => 'Layanan Iklan',
                'deskripsi' => 'Sumber pemasukan didapatkan dari Customer yang menggunakan layanan iklan',
                'nominal' => $request->harga_iklan,
            ]);

            Alert::toast('Terimakasih memberikan kepercayaan layanan iklan kami dengan berlangganan.', 'success');
            return redirect()->route('buat-iklan.index', ['id_user' => $id_user]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }

    public function kelolaIklan(Request $request, $id_user)
    {
        try {
            $id_user_decrypt = Crypt::decrypt($id_user);

            // Get the selected filter
            $filter = $request->input('filter_kelola_iklan', 'semua');

            // Base query to get all ads related to the user
            $query = Iklan::join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
                ->join('users', 'users.id', '=', 'iklan.id_user')
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
                    DB::raw('DATEDIFF(detail_iklan.tanggal_akhir, detail_iklan.tanggal_mulai) as durasi_hari')
                )
                ->where('iklan.id_user', $id_user_decrypt);

            // Apply filter if not 'semua'
            if ($filter !== 'semua') {
                $query->where('detail_iklan.status_iklan', ucfirst($filter));
            }

            // Get all filtered data
            $get_all_data = $query->get();

            // Separate ads by status
            $iklan_berlangsung = $get_all_data->where('status_iklan', 'Aktif');
            $iklan_pending = $get_all_data->where('status_iklan', 'Pending');
            $iklan_selesai = $get_all_data->where('status_iklan', 'Selesai');

            return view('customers.menu-iklan.kelola-iklan')->with([
                'title' => 'Kelola Iklan',
                'total_data_iklan' => $get_all_data->count(),
                'iklan_berlangsung' => $iklan_berlangsung,
                'iklan_pending' => $iklan_pending,
                'iklan_selesai' => $iklan_selesai,
                'all_iklan' => $get_all_data,
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
            $get_data = Iklan::where('id', $id_iklan);
            $get_data->delete();

            Alert::toast('Data berhasil dihapus!', 'success');
            return back();
        } catch (\Exception $error) {
            Log::error($error->getMessage());
        }
    }

    public function updateIklanView($id_iklan)
    {
        $id_decrypt = Crypt::decrypt($id_iklan);
        $get_data = Iklan::where('id', $id_decrypt)->first();
        return view('customers.menu-iklan.update-iklan')->with([
            'title' => 'Update Iklan',
            'data' => $get_data,
        ]);
    }

    public function updateIklan($id_iklan, $id_user)
    {
        try {
            request()->validate([
                'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'judul' => 'required|string',
                'sub_judul' => 'required|string',
                'deskripsi' => 'required|string',
            ]);

            $get_data = Iklan::findOrFail($id_iklan);

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

            Alert::toast('Data berhasil di update', 'success');
            return redirect()->route('kelola-iklan.index', ['id_user' => $id_user]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return back()->withErrors(['error' => $error->getMessage()]);
        }
    }
}
