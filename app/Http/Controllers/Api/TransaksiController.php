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

            // Mengisi tabel penyewaan
            $table_penyewaan = new Penyewaan();
            $table_penyewaan->id_user = $id_user;
            $table_penyewaan->tanggal_mulai = request()->input('tanggal_mulai');
            $table_penyewaan->tanggal_selesai = request()->input('tanggal_selesai');
            $table_penyewaan->pesan = request()->input('pesan') ?: 'Tidak ada pesan';
            $table_penyewaan->status_penyewaan = 'Pending';
            $table_penyewaan->save();

            // Selalu buat entri PembayaranPenyewaan dengan status default 'Belum lunas'
            $table_pembayaran = new PembayaranPenyewaan();
            $table_pembayaran->id_penyewaan = $table_penyewaan->id;
            $table_pembayaran->bukti_pembayaran = 'Belum di isi';
            $table_pembayaran->jaminan_sewa = 'Belum di isi';
            $table_pembayaran->jumlah_pembayaran = 0;
            $table_pembayaran->kembalian_pembayaran = 0;
            $table_pembayaran->biaya_admin = request()->input('biaya_admin', 0);
            $table_pembayaran->kurang_pembayaran = 0;
            $table_pembayaran->total_pembayaran = 0;
            $table_pembayaran->metode = request()->input('metode');
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
            $pembayaran->kurang_pembayaran = $request->kurang_pembayaran;
            $pembayaran->total_pembayaran = $request->total_pembayaran;
            $pembayaran->metode = 'Transfer';
            $pembayaran->jenis_transaksi = 'Ambil ditempat';
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
        $id_user = request()->query('id_user');
        $name_store = request()->query('name_store');

        $bank = Bank::select('bank.id', 'bank.id_user', 'bank.rekening', DB::raw('UPPER(bank.bank) as bank'))
            ->where(function ($query) use ($id_user, $name_store) {
                if ($id_user) {
                    $query->where('id_user', $id_user);
                }
                if ($name_store) {
                    $query->orWhere('name_store', $name_store);
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
            $tipe_riwayat = $request->query('tipe_riwayat');
            $id_user = $request->query('id_user');
            if (!in_array($tipe_riwayat, ['belum-bayar', 'pengambilan', 'berlangsung', 'selesai', 'dibatalkan'])) {
                return response()->json([
                    'message' => 'error',
                    'error' => 'Invalid tipe_riwayat atau tipe riwayat tidak ada!'
                ], 400);
            }
            $query = User::leftJoin('penyewaan', 'users.id', '=', 'penyewaan.id_user')
                ->leftJoin('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
                ->leftJoin('pembayaran_penyewaan', 'penyewaan.id', '=', 'pembayaran_penyewaan.id_penyewaan')
                ->leftJoin('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                ->leftJoin('users as store_user', 'produk.id_user', '=', 'store_user.id')
                ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
                ->select(
                    'users.id as id_user',
                    'penyewaan.id as id_penyewaan',
                    'detail_penyewaan.id as id_detail_penyewaan',
                    'store_user.name_store',
                    'produk.id as id_produk',
                    'produk.nama as nama_produk',
                    'detail_penyewaan.qty as qty',
                    DB::raw("CONCAT('Size ', detail_penyewaan.ukuran, ' / Warna ', detail_penyewaan.warna_produk) as deskripsi_produk"),
                    'detail_penyewaan.ukuran',
                    'detail_penyewaan.warna_produk as warna',
                    DB::raw(
                        "CASE
                        WHEN DATEDIFF(penyewaan.tanggal_selesai, penyewaan.tanggal_mulai) = 1 THEN CONCAT(DATEDIFF(penyewaan.tanggal_selesai, penyewaan.tanggal_mulai), ' Hari')
                        ELSE CONCAT(DATEDIFF(penyewaan.tanggal_selesai, penyewaan.tanggal_mulai), ' Hari')
                    END as durasi"
                    ),
                    'pembayaran_penyewaan.status_pembayaran',
                    DB::raw('AVG(rating_produk.rating) as rating'),
                    'detail_penyewaan.subtotal as harga',
                    'pembayaran_penyewaan.total_pembayaran',
                )->where('users.id', $id_user);
            if ($tipe_riwayat === 'belum-bayar') {
                $query->where('penyewaan.status_penyewaan', 'Pending')->where('pembayaran_penyewaan.status_pembayaran', 'Belum lunas');
            } elseif ($tipe_riwayat === 'pengambilan') {
                $query->where('penyewaan.status_penyewaan', 'Pending')->where('pembayaran_penyewaan.status_pembayaran', 'Lunas');
            } else if ($tipe_riwayat === 'berlangsung') {
                $query->where('penyewaan.status_penyewaan', 'Aktif')->where('pembayaran_penyewaan.status_pembayaran', 'Lunas');
            } else if ($tipe_riwayat === 'selesai') {
                $query->where('penyewaan.status_penyewaan', 'Selesai')->where('pembayaran_penyewaan.status_pembayaran', 'Lunas');
            } else if ($tipe_riwayat === 'dibatalkan') {
                $query->where('penyewaan.status_penyewaan', 'Dibatalkan');
            }
            $query->groupBy(
                'users.id',
                'penyewaan.id',
                'detail_penyewaan.id',
                'store_user.name_store',
                'produk.id',
                'produk.nama',
                'detail_penyewaan.qty',
                'detail_penyewaan.ukuran',
                'detail_penyewaan.warna_produk',
                'penyewaan.tanggal_selesai',
                'penyewaan.tanggal_mulai',
                'pembayaran_penyewaan.status_pembayaran',
                'pembayaran_penyewaan.total_pembayaran',
                'detail_penyewaan.subtotal'
            );
            $result = $query->get();
            $data_pertama = $result->first();
            if (!$data_pertama) {
                return response()->json([
                    'message' => 'error',
                    'error' => 'Tidak ada data terkait!'
                ], 404);
            }
            $total_produk_lainnya = DB::table('detail_penyewaan')
                ->where('detail_penyewaan.id_penyewaan', $data_pertama->id_penyewaan)
                ->count() - 1;
            
            $produk = \App\Models\Produk::with('foto')->find($data_pertama->id_produk);
            $foto_produk = \App\Helpers\PhotoHelper::getThumbnailUrl($produk);

            return response()->json([
                'message' => 'success',
                'response' => [
                    'id' => $data_pertama->id_user,
                    'id_penyewaan' => $data_pertama->id_penyewaan,
                    'id_detail_penyewaan' => $data_pertama->id_detail_penyewaan,
                    'nama_toko' => $data_pertama->name_store,
                    'nama_produk' => $data_pertama->nama_produk,
                    'foto_produk' => $foto_produk,
                    'qty' => $data_pertama->qty,
                    'deskripsi_produk' => $data_pertama->deskripsi_produk,
                    'ukuran' => $data_pertama->ukuran,
                    'warna' => $data_pertama->warna,
                    'qty_produk_lain' => $total_produk_lainnya,
                    'rating' => $data_pertama->rating,
                    'subtotal_harga' => $data_pertama->harga,
                    'total_pesanan' => $data_pertama->total_pembayaran,
                    'durasi' => $data_pertama->durasi,
                    'status_transaksi' => $data_pertama->status_pembayaran,
                ],
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
}
