<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Bank;
use App\Models\DetailPenyewaan;
use App\Models\DetailVariantProduk;
use App\Models\Pemasukan;
use App\Models\PembayaranPenyewaan;
use App\Models\Penyewaan;
use App\Models\Produk;
use App\Models\User;
use App\Models\VariantProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function checkout($id_user)
    {
        try {
            $user = User::find($id_user);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            if (empty($user->nomor_identitas)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Harap lengkapi verifikasi identitas (KTP) Anda sebelum melakukan penyewaan barang.'
                ], 403);
            }

            $message_error = [
                'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
                'tanggal_mulai.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
                'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
                'tanggal_selesai.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
                'pesan.string' => 'Pesan harus berupa teks.',
                'pesan.max' => 'Pesan tidak boleh lebih dari 255 karakter.',
                'produk_details.required' => 'Detail produk wajib diisi.',
                'produk_details.array' => 'Detail produk harus berupa array.',
                'produk_details.*.id_produk.required' => 'ID produk wajib diisi.',
                'produk_details.*.id_produk.integer' => 'ID produk harus berupa angka.',
                'produk_details.*.warna.required' => 'Warna produk wajib diisi.',
                'produk_details.*.warna.string' => 'Warna produk harus berupa teks.',
                'produk_details.*.ukuran.required' => 'Ukuran produk wajib diisi.',
                'produk_details.*.ukuran.string' => 'Ukuran produk harus berupa teks.',
                'produk_details.*.qty.required' => 'Jumlah produk wajib diisi.',
                'produk_details.*.qty.integer' => 'Jumlah produk harus berupa angka.',
                'produk_details.*.subtotal.required' => 'Subtotal wajib diisi.',
                'produk_details.*.subtotal.integer' => 'Subtotal harus berupa angka.',
            ];

            request()->validate([
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'pesan' => 'nullable|string|max:255',
                'produk_details' => 'required|array',
                'produk_details.*.id_produk' => 'required|integer',
                'produk_details.*.warna' => 'required|string',
                'produk_details.*.ukuran' => 'required|string',
                'produk_details.*.qty' => 'required|integer',
                'produk_details.*.subtotal' => 'required|integer',
                'metode' => 'required|string',
                'biaya_admin' => 'required|integer',
            ], $message_error);

            $produk_details = request()->input('produk_details');
            $resolved_variants = [];

            // 1. Validasi stok semua produk terlebih dahulu (Atomic check)
            foreach ($produk_details as $detail) {
                $detail_variant_produk = DetailVariantProduk::join('variant_produk', 'detail_variant_produk.id_variant_produk', '=', 'variant_produk.id')
                    ->where('variant_produk.id_produk', $detail['id_produk'])
                    ->where('variant_produk.warna', $detail['warna'])
                    ->where('detail_variant_produk.ukuran', $detail['ukuran'])
                    ->select('detail_variant_produk.*')
                    ->first();

                if (!$detail_variant_produk) {
                    return response()->json([
                        'message' => 'Varian produk tidak ditemukan',
                        'id_produk' => $detail['id_produk'],
                        'warna' => $detail['warna'],
                        'ukuran' => $detail['ukuran']
                    ], 404);
                }

                if ($detail_variant_produk->stok < $detail['qty']) {
                    return response()->json([
                        'message' => 'Stok produk tidak mencukupi',
                        'id_produk' => $detail['id_produk'],
                        'warna' => $detail['warna'],
                        'ukuran' => $detail['ukuran'],
                        'stok_tersedia' => $detail_variant_produk->stok,
                        'qty_diminta' => $detail['qty']
                    ], 400);
                }

                $resolved_variants[] = [
                    'input' => $detail,
                    'model' => $detail_variant_produk
                ];
            }

            // Menentukan opsi pengiriman dan alamat
            $opsi_pengiriman = request()->input('opsi_pengiriman') 
                ?? request()->input('jenis_transaksi') 
                ?? 'Ambil Ditempat';
            $alamat_pengiriman = request()->input('alamat_pengiriman') 
                ?? request()->input('alamat') 
                ?? null;

            // Mengisi tabel penyewaan
            $table_penyewaan = new Penyewaan();
            $table_penyewaan->id_user = $id_user;
            $table_penyewaan->tanggal_mulai = request()->input('tanggal_mulai');
            $table_penyewaan->tanggal_selesai = request()->input('tanggal_selesai');
            $table_penyewaan->pesan = request()->input('pesan') ?: 'Tidak ada pesan';
            $table_penyewaan->opsi_pengiriman = $opsi_pengiriman;
            $table_penyewaan->alamat_pengiriman = $alamat_pengiriman;
            $table_penyewaan->status_penyewaan = 'Pending';
            $table_penyewaan->save();

            // Hitung durasi hari dari tanggal penyewaan
            $start = \Carbon\Carbon::parse($table_penyewaan->tanggal_mulai);
            $end = \Carbon\Carbon::parse($table_penyewaan->tanggal_selesai);
            $durasi = max(1, $start->diffInDays($end));

            // Hitung subtotal sewa produk
            $subtotal_produk = collect($resolved_variants)->sum(function($item) use ($durasi) {
                return $item['input']['subtotal'] * $durasi;
            });

            // Tentukan biaya antar jika opsi pengantaran dipilih
            $is_diantar = (strtolower($opsi_pengiriman) === 'diantar' || stripos($opsi_pengiriman, 'antar') !== false);
            $biaya_antar = 0;
            if ($is_diantar) {
                $biaya_antar = (int) request()->input('biaya_antar', 0);
                // Fallback otomatis ambil dari database jika frontend mengirim 0 tapi opsi antar dipilih
                if ($biaya_antar <= 0 && !empty($resolved_variants)) {
                    $first_produk = Produk::find($resolved_variants[0]['input']['id_produk']);
                    if ($first_produk && $first_produk->id_user) {
                        $pengaturan = \App\Models\PengaturanPengirimanToko::where('id_user', $first_produk->id_user)->first();
                        if ($pengaturan && $pengaturan->layanan_antar) {
                            $biaya_antar = (int) $pengaturan->biaya_antar;
                        }
                    }
                }
            }

            $biaya_admin = (int) request()->input('biaya_admin', 0);
            $total_semua = $subtotal_produk + $biaya_antar + $biaya_admin;

            // Selalu buat entri PembayaranPenyewaan dengan status default 'Belum lunas'
            $table_pembayaran = new PembayaranPenyewaan();
            $table_pembayaran->id_penyewaan = $table_penyewaan->id;
            $table_pembayaran->bukti_pembayaran = 'Belum di isi';
            $table_pembayaran->jaminan_sewa = 'Belum di isi';
            $table_pembayaran->jumlah_pembayaran = 0;
            $table_pembayaran->kembalian_pembayaran = 0;
            $table_pembayaran->biaya_admin = $biaya_admin;
            $table_pembayaran->biaya_antar = $biaya_antar;
            $table_pembayaran->kurang_pembayaran = $total_semua;
            $table_pembayaran->total_pembayaran = $total_semua;
            $table_pembayaran->metode = request()->input('metode');
            $table_pembayaran->jenis_transaksi = $is_diantar ? 'diantar' : 'ambil ditempat';
            $table_pembayaran->status_pembayaran = 'Belum lunas';
            $table_pembayaran->save();

            // Mengisi tabel detail_penyewaan dan kurangi stok
            foreach ($resolved_variants as $item) {
                $detail = $item['input'];
                $variant_model = $item['model'];

                $table_detail_penyewaan = new DetailPenyewaan();
                $table_detail_penyewaan->id_penyewaan = $table_penyewaan->id;
                $table_detail_penyewaan->id_produk = $detail['id_produk'];
                $table_detail_penyewaan->id_detail_variant_produk = $variant_model->id;
                $table_detail_penyewaan->warna_produk = $detail['warna'];
                $table_detail_penyewaan->ukuran = $detail['ukuran'];
                $table_detail_penyewaan->qty = $detail['qty'];
                $table_detail_penyewaan->harga_sewa_satuan = $variant_model->harga_sewa ?? ($detail['subtotal'] / max(1, $detail['qty']));
                $table_detail_penyewaan->subtotal = $detail['subtotal'];
                $table_detail_penyewaan->save();

                // Mengurangi stok produk di tabel detail_variant_produk
                $variant_model->stok -= $detail['qty'];
                $variant_model->save();
            }

            $detail_penyewaan = DetailPenyewaan::where('id_penyewaan', $table_penyewaan->id)->get();

            return response()->json([
                'message' => 'success',
                'penyewaan' => $table_penyewaan,
                'pembayaran' => $table_pembayaran,
                'detail_penyewaan' => $detail_penyewaan,
            ], 200);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
        }
    }

    public function pembayaran(Request $request)
    {
        $id_toko = $request->query('id_toko');

        try {
            $validate = Validator::make($request->all(), [
                'id_penyewaan' => 'required|integer',
                'bukti_pembayaran' => 'required|image|mimes:png,jpg|max:3000',
                'jaminan_sewa' => 'required|image|mimes:png,jpg|max:3000',
                'jumlah_pembayaran' => 'required|integer',
                'kembalian_pembayaran' => 'required|integer',
                'biaya_admin' => 'required|integer',
                'kurang_pembayaran' => 'required|integer',
                'total_pembayaran' => 'required|integer',
            ]);

            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }

            $pembayaran = PembayaranPenyewaan::where('id_penyewaan', $request->id_penyewaan)->first();
            if (!$pembayaran) {
                $pembayaran = new PembayaranPenyewaan();
                $pembayaran->id_penyewaan = $request->id_penyewaan;
            }
            $pembayaran->jumlah_pembayaran = $request->jumlah_pembayaran;
            $pembayaran->kembalian_pembayaran = $request->kembalian_pembayaran;
            $pembayaran->biaya_admin = $request->biaya_admin;
            if ($request->has('biaya_antar')) {
                $pembayaran->biaya_antar = (int) $request->input('biaya_antar');
            }
            $pembayaran->kurang_pembayaran = $request->kurang_pembayaran;
            $pembayaran->total_pembayaran = $request->total_pembayaran;
            $pembayaran->metode = 'Transfer';
            if ($request->has('jenis_transaksi') || $request->has('opsi_pengiriman')) {
                $pembayaran->jenis_transaksi = $request->input('jenis_transaksi') ?? $request->input('opsi_pengiriman');
            } elseif (empty($pembayaran->jenis_transaksi)) {
                $pembayaran->jenis_transaksi = 'ambil ditempat';
            }
            $pembayaran->status_pembayaran = 'Menunggu Verifikasi';

            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPembayaran = $request->file('bukti_pembayaran');
                $buktiPembayaranName = time() . '_bukti.' . $buktiPembayaran->getClientOriginalExtension();
                $buktiPembayaran->move(public_path('assets/image/customers/pembayaran/'), $buktiPembayaranName);
                $pembayaran->bukti_pembayaran = $buktiPembayaranName;
            }

            if ($request->hasFile('jaminan_sewa')) {
                $jaminanSewa = $request->file('jaminan_sewa');
                $jaminanSewaName = time() . '_jaminan.' . $jaminanSewa->getClientOriginalExtension();
                $jaminanSewa->move(public_path('assets/image/customers/jaminan/'), $jaminanSewaName);
                
                // Add Watermark
                $this->addWatermarkToImage(public_path('assets/image/customers/jaminan/') . $jaminanSewaName);
                
                $pembayaran->jaminan_sewa = $jaminanSewaName;
            }

            $pembayaran->save();

            return response()->json(['message' => 'Pembayaran berhasil disimpan', 'data' => $pembayaran], 201);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan pada server.', 'error' => $error->getMessage()], 500);
        }
    }

    public function lokasiToko()
    {
        $id_user_toko = request()->query('id_user');
        $name_store = request()->query('name_store');
        $id_user_pemesanan = request()->query('id_user_pemesanan');
        $id_alamat_user_pemesanan = request()->query('id_alamat_user');

        if (!$id_user_toko && !$name_store) {
            return response()->json(['error' => 'Harus ada parameter id_user atau name_store'], 400);
        }

        $relation_table = Alamat::join('users', 'users.id', '=', 'alamat.id_user')
            ->select(
                'alamat.id as id_alamat',
                'alamat.longitude',
                'alamat.latitude',
                'alamat.type',
                'alamat.detail_lainnya',
                'users.name as user_name'
            )
            ->where('alamat.type', 1)
            ->where(function ($query) use ($id_user_toko, $name_store) {
                if ($id_user_toko) {
                    $query->where('alamat.id_user', $id_user_toko);
                }
                if ($name_store) {
                    $query->orWhere('users.name', $name_store);
                }
            })
            ->get();

        if ($relation_table->isEmpty()) {
            return response()->json(['error' => 'Tidak ada data yang ditemukan'], 404);
        }

        if ($id_user_pemesanan && $id_alamat_user_pemesanan) {
            $lokasi_pemesanan = Alamat::where('id', $id_alamat_user_pemesanan)
                ->where('id_user', $id_user_pemesanan)
                ->first();

            if (!$lokasi_pemesanan) {
                return response()->json(['error' => 'Lokasi pemesanan tidak ditemukan'], 404);
            }

            $relation_table->transform(function ($item) {
                $address = $this->getAddressFromCoordinates($item->latitude, $item->longitude);
                $item->address = $address;
                return $item;
            });

            $relation_table = $relation_table->map(function ($item) use ($lokasi_pemesanan) {
                $distance_km = $this->calculateDistance($lokasi_pemesanan->latitude, $lokasi_pemesanan->longitude, $item->latitude, $item->longitude);
                $item->jarak_km = round($distance_km, 2);
                $item->jarak_mil = round($distance_km * 0.621371, 2);
                return $item;
            })->sortBy('jarak_km');

            $nearest_location = $relation_table->first();

            return response()->json([
                'message' => 'success',
                'lokasi_toko' => $nearest_location,
            ]);
        } else {
            $relation_table->transform(function ($item) {
                $address = $this->getAddressFromCoordinates($item->latitude, $item->longitude);
                $item->address = $address;
                return $item;
            });

            return response()->json([
                'message' => 'success',
                'lokasi_toko' => $relation_table,
            ]);
        }
    }

    private function getAddressFromCoordinates($latitude, $longitude)
    {
        $response = Http::get("https://nominatim.openstreetmap.org/reverse", [
            'lat' => $latitude,
            'lon' => $longitude,
            'format' => 'json'
        ]);
        $data = $response->json();
        if ($response->successful() && isset($data['display_name'])) {
            return $data['display_name'];
        }
        return 'Address not found';
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earth_radius * $c;
        return $distance;
    }

    public function bankToko()
    {
        $id_user = request()->query('id_user') ?? request()->input('id_user');
        $name_store = request()->query('name_store') ?? request()->input('name_store');

        // Jika user terautentikasi dan id_user tidak dikirim, gunakan user yang sedang login
        if (!$id_user && auth('sanctum')->check()) {
            $id_user = auth('sanctum')->id();
        }

        // Jika tidak ada parameter filter, jangan bocorkan bank milik pengguna/toko lain
        if (!$id_user && !$name_store) {
            return response()->json([
                'message' => 'success',
                'bank' => [],
            ]);
        }

        $bank = Bank::select('bank.id', 'bank.id_user', 'bank.rekening', DB::raw('UPPER(bank.bank) as bank'))
            ->where(function ($query) use ($id_user, $name_store) {
                if ($id_user) {
                    $query->where('id_user', $id_user);
                }
                if ($name_store) {
                    $query->orWhereHas('user', function ($q) use ($name_store) {
                        $q->where('name_store', $name_store);
                    });
                }
            })
            ->get();

        return response()->json([
            'message' => 'success',
            'bank' => $bank,
        ]);
    }

    public function riwayat(Request $request)
    {
        try {
            // parameter riwayat berdasarkan nama tab bar UI Flutter
            // belum-bayar(Belum Bayar), pengambilan(Pengambilan),
            // berlangsung(Berlangsung), selesai(Selesai), dibatalkan(Dibatalkan)
            $tipe_riwayat = $request->query('tipe_riwayat') ?? $request->query('status');
            $id_user = $request->query('id_user');
            
            // Jika dikirim, validasi. Jika tidak dikirim, ambil semua
            if ($tipe_riwayat && !in_array($tipe_riwayat, ['belum-bayar', 'pengambilan', 'berlangsung', 'selesai', 'dibatalkan', 'denda-belum-lunas', 'Belum Bayar', 'Pengambilan', 'Berlangsung', 'Selesai', 'Dibatalkan', 'Denda Belum Lunas'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status atau tipe riwayat!'
                ], 400);
            }

            // Normalisasi status string dari frontend ke slug format backend
            if ($tipe_riwayat) {
                $tipe_riwayat = strtolower(str_replace(' ', '-', $tipe_riwayat));
            }

            $query = User::leftJoin('penyewaan', 'users.id', '=', 'penyewaan.id_user')
                ->leftJoin('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
                ->leftJoin('pembayaran_penyewaan', 'penyewaan.id', '=', 'pembayaran_penyewaan.id_penyewaan')
                ->leftJoin('pengembalian', 'penyewaan.id', '=', 'pengembalian.id_penyewaan')
                ->leftJoin('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                ->leftJoin('users as store_user', 'produk.id_user', '=', 'store_user.id')
                ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
                ->select(
                    'users.id as id_user',
                    'penyewaan.id as id_penyewaan',
                    'detail_penyewaan.id as id_detail_penyewaan',
                    'store_user.name_store',
                    'store_user.id as id_toko_pemilik',
                    'produk.id as id_produk',
                    'produk.nama as nama_produk',
                    'detail_penyewaan.qty as qty',
                    DB::raw("CONCAT('Size ', detail_penyewaan.ukuran, ' / Warna ', detail_penyewaan.warna_produk) as deskripsi_produk"),
                    'detail_penyewaan.ukuran',
                    'detail_penyewaan.warna_produk as warna',
                    'penyewaan.tanggal_mulai',
                    'penyewaan.tanggal_selesai',
                    DB::raw(
                        "CASE
                        WHEN DATEDIFF(penyewaan.tanggal_selesai, penyewaan.tanggal_mulai) = 1 THEN CONCAT(DATEDIFF(penyewaan.tanggal_selesai, penyewaan.tanggal_mulai), ' Hari')
                        ELSE CONCAT(DATEDIFF(penyewaan.tanggal_selesai, penyewaan.tanggal_mulai), ' Hari')
                    END as durasi"
                    ),
                    'penyewaan.status_penyewaan',
                    'pembayaran_penyewaan.status_pembayaran',
                    'pembayaran_penyewaan.metode',
                    DB::raw('AVG(rating_produk.rating) as rating'),
                    'detail_penyewaan.subtotal as harga',
                    'pembayaran_penyewaan.total_pembayaran',
                    'pembayaran_penyewaan.biaya_antar',
                    'pembayaran_penyewaan.jenis_transaksi',
                    'penyewaan.opsi_pengiriman',
                    'penyewaan.alamat_pengiriman',
                    'pengembalian.status_denda',
                    'pengembalian.denda as nominal_denda_tersimpan'
                )->where('users.id', $id_user);

            if ($tipe_riwayat === 'belum-bayar') {
                $query->where('penyewaan.status_penyewaan', 'Pending');
            } elseif ($tipe_riwayat === 'pengambilan') {
                $query->where('penyewaan.status_penyewaan', 'Pengambilan');
            } elseif ($tipe_riwayat === 'berlangsung') {
                $query->where('penyewaan.status_penyewaan', 'Aktif')->where('pembayaran_penyewaan.status_pembayaran', 'Lunas');
            } elseif ($tipe_riwayat === 'selesai') {
                $query->where('penyewaan.status_penyewaan', 'Selesai')->where('pembayaran_penyewaan.status_pembayaran', 'Lunas');
            } elseif ($tipe_riwayat === 'dibatalkan') {
                $query->where('penyewaan.status_penyewaan', 'Dibatalkan');
            } elseif ($tipe_riwayat === 'denda-belum-lunas') {
                $query->where('pengembalian.status_denda', 'belum_dibayar');
            }
            $query->groupBy(
                'users.id',
                'penyewaan.id',
                'detail_penyewaan.id',
                'store_user.name_store',
                'store_user.id',
                'produk.id',
                'produk.nama',
                'detail_penyewaan.qty',
                'detail_penyewaan.ukuran',
                'detail_penyewaan.warna_produk',
                'penyewaan.tanggal_selesai',
                'penyewaan.tanggal_mulai',
                'penyewaan.status_penyewaan',
                'pembayaran_penyewaan.status_pembayaran',
                'pembayaran_penyewaan.metode',
                'pembayaran_penyewaan.total_pembayaran',
                'pembayaran_penyewaan.biaya_antar',
                'pembayaran_penyewaan.jenis_transaksi',
                'penyewaan.opsi_pengiriman',
                'penyewaan.alamat_pengiriman',
                'detail_penyewaan.subtotal',
                'pengembalian.status_denda',
                'pengembalian.denda'
            );
            $result = $query->get();
            if ($result->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengambil riwayat transaksi',
                    'data' => []
                ], 200);
            }

            $history_data = [];
            $grouped = $result->groupBy('id_penyewaan');

            foreach ($grouped as $id_penyewaan => $items) {
                $first_item = $items->first();
                $total_produk_lainnya = DB::table('detail_penyewaan')
                    ->where('id_penyewaan', $id_penyewaan)
                    ->count() - 1;
                
                $produk = \App\Models\Produk::with('foto')->find($first_item->id_produk);
                $foto_produk = \App\Helpers\PhotoHelper::getThumbnailUrl($produk);

                // Menentukan status transaksi format frontend
                $status_transaksi = 'Belum Bayar';
                if ($first_item->status_penyewaan == 'Pending' && $first_item->status_pembayaran == 'Belum lunas') {
                    $status_transaksi = 'Belum Bayar';
                } elseif ($first_item->status_penyewaan == 'Pending' && $first_item->status_pembayaran == 'Lunas') {
                    $status_transaksi = 'Pending';
                } elseif ($first_item->status_penyewaan == 'Pengambilan') {
                    $status_transaksi = 'Pengambilan';
                } elseif ($first_item->status_penyewaan == 'Aktif') {
                    $status_transaksi = 'Berlangsung';
                } elseif ($first_item->status_penyewaan == 'Selesai' || $first_item->status_penyewaan == 'Pengembalian') {
                    if (isset($first_item->status_denda) && $first_item->status_denda == 'belum_dibayar') {
                        $status_transaksi = 'Selesai (Belum Lunas)';
                    } else {
                        $status_transaksi = 'Selesai';
                    }
                } elseif ($first_item->status_penyewaan == 'Dibatalkan') {
                    $status_transaksi = 'Dibatalkan';
                }

                $bank_toko = \App\Models\Bank::where('id_user', $first_item->id_toko_pemilik)->first();

                // Fallback untuk pesanan lawas yang total_pembayarannya masih 0
                $total_pembayaran_aktual = $first_item->total_pembayaran;
                if ($total_pembayaran_aktual == 0) {
                    $durasi = (int) filter_var($first_item->durasi, FILTER_SANITIZE_NUMBER_INT);
                    $durasi = max(1, $durasi); // Hindari 0 hari
                    // Jumlahkan seluruh subtotal dari $items (termasuk produk lainnya), lalu kalikan durasi
                    $total_pembayaran_aktual = $items->sum('harga') * $durasi;
                }

                  $mapped_items = $items->map(function ($detail) {
                      $produk_item = \App\Models\Produk::with('foto')->find($detail->id_produk);
                      return [
                          'id_detail_penyewaan' => $detail->id_detail_penyewaan,
                          'id_produk' => $detail->id_produk,
                          'nama_produk' => $detail->nama_produk,
                          'foto_produk' => \App\Helpers\PhotoHelper::getThumbnailUrl($produk_item),
                          'qty' => $detail->qty,
                          'ukuran' => $detail->ukuran,
                          'warna' => $detail->warna,
                          'harga' => $detail->harga,
                      ];
                  })->values()->all();

                  $is_terlambat = false;
                  $durasi_terlambat = null;
                  $nominal_denda = null;

                  if ($first_item->status_penyewaan == 'Aktif' || $first_item->status_penyewaan == 'Pengembalian') {
                      $tanggal_selesai = \Carbon\Carbon::parse($first_item->tanggal_selesai);
                      $deadline = $tanggal_selesai->copy()->setTime(21, 0, 0);
                      $now = \Carbon\Carbon::now();
                      
                      if ($now->greaterThan($deadline)) {
                          $is_terlambat = true;
                          $hours_late = $deadline->diffInHours($now);
                          $harga_sewa_per_hari = $items->sum('harga');
                          
                          if ($hours_late <= 3) {
                              $durasi_terlambat = "{$hours_late} Jam";
                              $nominal_denda = 0;
                          } elseif ($hours_late > 3 && $hours_late <= 6) {
                              $durasi_terlambat = "{$hours_late} Jam";
                              $nominal_denda = $harga_sewa_per_hari * 0.25;
                          } elseif ($hours_late > 6 && $hours_late <= 12) {
                              $durasi_terlambat = "{$hours_late} Jam";
                              $nominal_denda = $harga_sewa_per_hari * 0.5;
                          } else {
                              $days_late = ceil($hours_late / 24);
                              $days_late_str = floor($hours_late / 24);
                              $hours_late_rem = $hours_late % 24;
                              $durasi_parts = [];
                              if ($days_late_str > 0) $durasi_parts[] = $days_late_str . " Hari";
                              if ($hours_late_rem > 0) $durasi_parts[] = $hours_late_rem . " Jam";
                              if (empty($durasi_parts)) $durasi_parts[] = "{$hours_late} Jam";
                              
                              $durasi_terlambat = implode(' ', $durasi_parts);
                              $nominal_denda = $harga_sewa_per_hari * $days_late;
                          }
                          $nominal_denda = round($nominal_denda);
                      }
                  }

                  $history_data[] = [
                      'id_transaksi' => $first_item->id_penyewaan,
                      'nama_toko' => $first_item->name_store,
                      'foto_produk' => $foto_produk,
                      'nama_produk' => $first_item->nama_produk,
                      'variasi_warna' => $first_item->warna,
                      'variasi_ukuran' => $first_item->ukuran,
                      'qty' => $first_item->qty,
                      'harga' => $first_item->harga,
                      'total_pesanan' => $total_pembayaran_aktual,
                      'qty_produk_lain' => max(0, $total_produk_lainnya),
                      'rating' => $first_item->rating ?? 0,
                      'hari' => (int) filter_var($first_item->durasi, FILTER_SANITIZE_NUMBER_INT),
                      'status_transaksi' => $status_transaksi,
                      'metode' => $first_item->metode,
                      'tanggal_mulai' => $first_item->tanggal_mulai,
                      'tanggal_selesai' => $first_item->tanggal_selesai,
                      'waktu_deadline_pengembalian' => \Carbon\Carbon::parse($first_item->tanggal_selesai)->setTime(21, 0, 0)->toDateTimeString(),
                      'is_terlambat' => $is_terlambat,
                      'durasi_terlambat' => $durasi_terlambat,
                      'nominal_denda' => $nominal_denda,
                      'status_denda' => $first_item->status_denda,
                      'nominal_denda_tersimpan' => $first_item->nominal_denda_tersimpan,
                      'rekening_bank' => $bank_toko ? $bank_toko->rekening : null,
                      'nama_bank' => $bank_toko ? $bank_toko->bank : null,
                      'jenis_transaksi' => $first_item->jenis_transaksi ?? $first_item->opsi_pengiriman ?? 'Ambil Ditempat',
                      'opsi_pengiriman' => $first_item->opsi_pengiriman ?? $first_item->jenis_transaksi ?? 'Ambil Ditempat',
                      'biaya_antar' => (int) ($first_item->biaya_antar ?? 0),
                      'alamat_pengiriman' => $first_item->alamat_pengiriman ?? null,
                      'items' => $mapped_items,
                  ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil riwayat transaksi',
                'data' => $history_data,
            ], 200);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([
                'message' => 'error',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    public function bayarSekarang(Request $request)
    {
        try {
            $request->validate([
                'bukti_pembayaran' => 'required|image|mimes:png,jpg',
                'jaminan_sewa' => 'required|image|mimes:png,jpg',
                'jumlah_pembayaran' => 'required|integer',
                'total_pembayaran' => 'required|integer',
            ]);

            $id_penyewaan = $request->query('id_penyewaan');
            $pembayaran_penyewaan = PembayaranPenyewaan::where('id_penyewaan', $id_penyewaan)->first();

            if (!$pembayaran_penyewaan) {
                return response()->json([
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $update = [
                'jumlah_pembayaran' => $request->input('jumlah_pembayaran'),
                'total_pembayaran' => $request->input('total_pembayaran'),
                'metode' => 'Transfer',
                'status_pembayaran' => 'Lunas',
            ];

            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $destinationPath = public_path('assets/image/customers/pembayaran/');
                $filename = time() . '_bukti' . $file->getClientOriginalName();
                $file->move($destinationPath, $filename);
                $foto_url = $filename;
                $update['bukti_pembayaran'] = $foto_url;
            }

            if ($request->hasFile('jaminan_sewa')) {
                $file = $request->file('jaminan_sewa');
                $destinationPath = public_path('assets/image/customers/jaminan/');
                $filename = time() . '_jaminan' . $file->getClientOriginalName();
                $file->move($destinationPath, $filename);
                
                // Add Watermark
                $this->addWatermarkToImage($destinationPath . $filename);
                
                $foto_url = $filename;
                $update['jaminan_sewa'] = $foto_url;
            }

            $pembayaran_penyewaan->update($update);

            return response()->json([
                'message' => 'Data berhasil diperbarui',
                'data' => $pembayaran_penyewaan,
            ], 200);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json([
                'message' => 'error',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    public function rincianProduk(Request $request)
    {

    }

    public function batalkanPesanan($id_penyewaan)
    {
        try {
            $penyewaan = Penyewaan::find($id_penyewaan);
            if (!$penyewaan) {
                return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
            }
            if ($penyewaan->status_penyewaan !== 'Pending') {
                return response()->json(['message' => 'Hanya pesanan berstatus Pending yang dapat dibatalkan'], 400);
            }

            $penyewaan->status_penyewaan = 'Dibatalkan';
            $penyewaan->save();

            $this->restoreStok($id_penyewaan);

            return response()->json(['message' => 'Pesanan berhasil dibatalkan dan stok dikembalikan'], 200);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server', 'error' => $error->getMessage()], 500);
        }
    }

    private function restoreStok($id_penyewaan)
    {
        $details = DetailPenyewaan::where('id_penyewaan', $id_penyewaan)->get();
        foreach ($details as $detail) {
            $variant = null;
            if ($detail->id_detail_variant_produk) {
                $variant = DetailVariantProduk::find($detail->id_detail_variant_produk);
            }
            if (!$variant) {
                $variant = DetailVariantProduk::join('variant_produk', 'detail_variant_produk.id_variant_produk', '=', 'variant_produk.id')
                    ->where('variant_produk.id_produk', $detail->id_produk)
                    ->where('variant_produk.warna', $detail->warna_produk)
                    ->where('detail_variant_produk.ukuran', $detail->ukuran)
                    ->select('detail_variant_produk.*')
                    ->first();
            }
            if ($variant) {
                $variant->stok += $detail->qty;
                $variant->save();
            }
        }
    }

    private function addWatermarkToImage($imagePath)
    {
        try {
            $info = getimagesize($imagePath);
            $mime = $info['mime'];

            switch ($mime) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($imagePath);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($imagePath);
                    break;
                default:
                    return; // Skip if not jpeg or png
            }

            $width = imagesx($image);
            $height = imagesy($image);

            $text = "HANYA UNTUK SEWA DI KAMPSEWA - " . date('d-m-Y');
            
            // Set watermark color (white with transparency if supported, or just light gray)
            $color = imagecolorallocatealpha($image, 255, 255, 255, 70); // 70 is alpha (0-127)
            $shadow = imagecolorallocatealpha($image, 0, 0, 0, 70);
            
            // Basic built-in font
            $font = 5; 
            
            // Calculate text dimensions
            $textWidth = imagefontwidth($font) * strlen($text);
            $textHeight = imagefontheight($font);

            // Calculate x and y to center the text
            $x = ($width - $textWidth) / 2;
            $y = ($height - $textHeight) / 2;

            // Optional: Draw text multiple times across the image for a tiled watermark
            for ($i = 0; $i < $height; $i += 100) {
                for ($j = 0; $j < $width; $j += 200) {
                    imagestring($image, $font, $j + 2, $i + 2, $text, $shadow);
                    imagestring($image, $font, $j, $i, $text, $color);
                }
            }

            // Save the image back
            switch ($mime) {
                case 'image/jpeg':
                    imagejpeg($image, $imagePath, 90);
                    break;
                case 'image/png':
                    imagepng($image, $imagePath);
                    break;
            }

            imagedestroy($image);
        } catch (\Exception $e) {
            Log::error("Watermark failed: " . $e->getMessage());
        }
    }
}

