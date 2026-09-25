<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Bank;
use App\Models\DetailPenyewaan;
use App\Models\DetailVariantProduk;
use App\Models\Pemasukan;
use App\Models\PembayaranPenyewaan;
use App\Models\Pengembalian;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class TransaksiMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('cust');
    }
    public function index(Request $request, $id_user)
    {
        try {
            // Decrypt id_user
            $id_user_decrypt = Crypt::decrypt($id_user);

            // Ambil tanggal awal dan tanggal akhir dari query
            $filter_tanggal_awal = $request->query('tanggal_awal');
            $filter_tanggal_akhir = $request->query('tanggal_akhir');
            $search = $request->query('search');

            // Query untuk mengambil data transaksi
            $query = User::leftJoin('penyewaan', 'users.id', '=', 'penyewaan.id_user')
                ->leftJoin('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
                ->leftJoin('pembayaran_penyewaan', 'penyewaan.id', '=', 'pembayaran_penyewaan.id_penyewaan')
                ->leftJoin('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                ->leftJoin('foto_produk', function ($join) {
                    $join->on('produk.id', '=', 'foto_produk.id_produk')
                         ->where('foto_produk.urutan', 1);
                })
                ->leftJoin('users as penyewa', 'produk.id_user', '=', 'penyewa.id')
                ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
                ->select(
                    'users.id as id_user_penyewa',
                    'users.foto as foto_users',
                    'users.name as nama_penyewa',
                    'penyewaan.id as id_penyewaan',
                    'penyewaan.tanggal_mulai',
                    'penyewaan.tanggal_selesai',
                    'penyewaan.status_penyewaan',
                    'pembayaran_penyewaan.status_pembayaran',
                    'pembayaran_penyewaan.metode',
                    'produk.id as id_produk',
                    'foto_produk.url_foto as foto',
                    'produk.nama'
                )
                ->where('penyewa.id', $id_user_decrypt)
                ->where('penyewaan.status_penyewaan', 'Pending');

            // Filter berdasarkan rentang tanggal jika ada
            if ($filter_tanggal_awal && $filter_tanggal_akhir) {
                $query->whereBetween('penyewaan.created_at', [$filter_tanggal_awal, $filter_tanggal_akhir]);
            } elseif ($filter_tanggal_awal) {
                $query->whereDate('penyewaan.created_at', $filter_tanggal_awal);
            } elseif ($filter_tanggal_akhir) {
                $query->whereDate('penyewaan.created_at', $filter_tanggal_akhir);
            }

            if ($search) {
                $query->where('users.name', 'like', '%' . $search . '%');
            }

            $data = $query->get();

            // Membuat koleksi baru untuk hasil akhir
            $result = collect();

            $seenUsers = [];

            foreach ($data as $item) {
                if (!isset($seenUsers[$item->id_user_penyewa])) {
                    $first_product = DB::table('detail_penyewaan')
                        ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                        ->leftJoin('foto_produk', function ($join) {
                            $join->on('produk.id', '=', 'foto_produk.id_produk')
                                 ->where('foto_produk.urutan', 1);
                        })
                        ->where('detail_penyewaan.id_penyewaan', $item->id_penyewaan)
                        ->select('produk.id as id_produk', 'foto_produk.url_foto as foto', 'produk.nama')
                        ->first();

                    if ($first_product) {
                        $item->id_produk = $first_product->id_produk;
                        $item->foto = $first_product->foto;
                        $item->nama = $first_product->nama;
                    }

                    $result->push($item);
                    $seenUsers[$item->id_user_penyewa] = true;
                }
            }

            // Jika permintaan dari AJAX, kirim JSON response
            if ($request->ajax()) {
                return response()->json(['data' => $result]);
            }

            return view('customers.menu-transaksi.home-transaksi')->with([
                'title' => 'Order Masuk',
                'id_user' => $id_user_decrypt,
                'data' => $result,
                'search' => $search,
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
        }
    }

    public function terimaOrderMasuk($id_penyewaan)
    {
        $id_penyewaan_decrypt = Crypt::decrypt($id_penyewaan);

        // Query untuk data yang hanya mengambil satu baris
        $singleData = Penyewaan::leftJoin('users', 'users.id', '=', 'penyewaan.id_user')
            ->leftJoin('pembayaran_penyewaan', 'penyewaan.id', '=', 'pembayaran_penyewaan.id_penyewaan')
            ->select(
                'users.id as id_user',
                'users.name',
                'users.foto',
                'users.nomor_telephone',
                'users.jenis_kelamin',
                'penyewaan.id as id_penyewaan',
                'penyewaan.tanggal_mulai',
                'penyewaan.tanggal_selesai',
                'penyewaan.status_penyewaan',
                'penyewaan.pesan',
                'pembayaran_penyewaan.id as id_pembayaran',
                'pembayaran_penyewaan.bukti_pembayaran',
                'pembayaran_penyewaan.jaminan_sewa',
                'pembayaran_penyewaan.jumlah_pembayaran',
                'pembayaran_penyewaan.total_pembayaran',
                'pembayaran_penyewaan.status_pembayaran',
                'pembayaran_penyewaan.biaya_admin'
            )->where('penyewaan.id', $id_penyewaan_decrypt)->first();

        // Query untuk data dari tabel 'bank'
        $banks = Bank::where('id_user', $singleData->id_user)->get();

        // Query untuk data dari tabel 'alamat'
        $address = Alamat::where('id_user', $singleData->id_user)->where('type', 0)->first();

        if ($address) {
            $latitude = $address->latitude;
            $longitude = $address->longitude;
            $addressString = $this->getAddressFromCoordinates($latitude, $longitude);
        } else {
            $addressString = 'Address not found'; // Atau sesuaikan dengan penanganan kasus jika alamat tidak ditemukan
        }

        // Query untuk data dari tabel 'detail_penyewaan' dan mengelompokkan berdasarkan id_produk
        $details = DetailPenyewaan::leftJoin('produk', 'produk.id', '=', 'detail_penyewaan.id_produk')
            ->leftJoin('foto_produk', function ($join) {
                $join->on('produk.id', '=', 'foto_produk.id_produk')
                     ->where('foto_produk.urutan', 1);
            })
            ->select(
                'produk.id as id_produk',
                'produk.nama as produk_nama',
                'produk.kategori as produk_kategori',
                'foto_produk.url_foto as produk_foto',
                'detail_penyewaan.warna_produk',
                'detail_penyewaan.ukuran',
                'detail_penyewaan.qty',
                'detail_penyewaan.subtotal'
            )
            ->where('detail_penyewaan.id_penyewaan', $id_penyewaan_decrypt)
            ->get()
            ->groupBy('id_produk');

        $total_harus_dibayar = DetailPenyewaan::where('id_penyewaan', $id_penyewaan_decrypt)->sum('subtotal');

        // Logic for Denda Keterlambatan
        $denda_keterlambatan = 0;
        $info_keterlambatan = '';
        if ($singleData->status_penyewaan == 'Aktif' || $singleData->status_penyewaan == 'Pengembalian') {
            $tanggal_selesai = \Carbon\Carbon::parse($singleData->tanggal_selesai);
            $deadline = $tanggal_selesai->copy()->setTime(21, 0, 0); // Deadline at 21:00
            
            $now = \Carbon\Carbon::now();
            
            if ($now->greaterThan($deadline)) {
                $hours_late = $deadline->diffInHours($now);
                
                $start = \Carbon\Carbon::parse($singleData->tanggal_mulai);
                $end = \Carbon\Carbon::parse($singleData->tanggal_selesai);
                $durasi = $start->diffInDays($end) + 1;
                $harga_sewa_per_hari = $durasi > 0 ? ($total_harus_dibayar / $durasi) : $total_harus_dibayar;

                if ($hours_late <= 3) {
                    $info_keterlambatan = "Telat {$hours_late} jam (Masih dalam batas toleransi).";
                    $denda_keterlambatan = 0;
                } elseif ($hours_late > 3 && $hours_late <= 6) {
                    $info_keterlambatan = "Telat {$hours_late} jam (Denda ringan).";
                    $denda_keterlambatan = $harga_sewa_per_hari * 0.25;
                } elseif ($hours_late > 6 && $hours_late <= 12) {
                    $info_keterlambatan = "Telat {$hours_late} jam (Denda setengah harga sewa per hari).";
                    $denda_keterlambatan = $harga_sewa_per_hari * 0.5;
                } else {
                    $days_late = ceil($hours_late / 24); 
                    $info_keterlambatan = "Telat {$hours_late} jam ({$days_late} hari) (Denda sewa penuh per hari terlambat).";
                    $denda_keterlambatan = $harga_sewa_per_hari * $days_late;
                }
                $denda_keterlambatan = round($denda_keterlambatan);
            } else {
                $info_keterlambatan = 'Pengembalian tepat waktu (sebelum jam 21:00 pada tanggal selesai).';
            }
        }

        return view('customers.menu-transaksi.terima-order-masuk')->with([
            'title' => 'Terima Order Masuk',
            'data' => $singleData,
            'banks' => $banks,
            'address' => $addressString,
            'details' => $details,
            'harus_dibayar' => $total_harus_dibayar,
            'denda_keterlambatan' => $denda_keterlambatan,
            'info_keterlambatan' => $info_keterlambatan,
        ]);
    }

    private function getAddressFromCoordinates($latitude, $longitude)
    {
        $url = "https://nominatim.openstreetmap.org/reverse?lat={$latitude}&lon={$longitude}&format=json";

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['display_name'])) {
                    return $data['display_name'];
                }
            }

            return 'Address not found';
        } catch (\Exception $e) {
            return 'Error fetching address: ' . $e->getMessage();
        }
    }

    public function inputPembayaranCOD($id_penyewaan)
    {
        try {
            // Validasi input
            $validatedData = request()->validate([
                'jumlah_pembayaran' => 'required|integer',
                'kembalian_pembayaran' => 'required|integer',
                'kurang_pembayaran' => 'required|integer',
                'total_pembayaran' => 'required|integer',
                'jaminan_sewa' => 'required|string',
            ]);

            $pembayaran_penyewaan = PembayaranPenyewaan::where('id_penyewaan', $id_penyewaan)->first();
            if ($pembayaran_penyewaan) {
                $pembayaran_penyewaan->update([
                    'jaminan_sewa' => $validatedData['jaminan_sewa'],
                    'jumlah_pembayaran' => $validatedData['jumlah_pembayaran'],
                    'kembalian_pembayaran' => $validatedData['kembalian_pembayaran'],
                    'kurang_pembayaran' => $validatedData['kurang_pembayaran'],
                    'total_pembayaran' => $validatedData['total_pembayaran'],
                    'status_pembayaran' => 'Lunas',
                ]);

                // Catat ke tabel pemasukan untuk toko
                $store_user_id = DB::table('detail_penyewaan')
                    ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                    ->where('detail_penyewaan.id_penyewaan', $id_penyewaan)
                    ->value('produk.id_user');

                if ($store_user_id) {
                    $this->catatPemasukan($store_user_id, $pembayaran_penyewaan);
                }

                Alert::toast('Berhasil menyimpan pembayaran COD & mencatat pemasukan!', 'success');
                return redirect()->back();
            }
            Alert::toast('Gagal menyimpan silahkan ulangi lagi!', 'warning');
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function confirmOrderMasuk($id_penyewaan, $id_user, $parameter)
    {
        try {
            if ($parameter == 1) {
                $penyewaan = Penyewaan::where('id', $id_penyewaan)->update(['status_penyewaan' => 'Aktif']);
                // Jika pembayaran via Transfer dan masih menunggu verifikasi, set Lunas & catat pemasukan
                $pembayaran = PembayaranPenyewaan::where('id_penyewaan', $id_penyewaan)->first();
                if ($pembayaran && ($pembayaran->metode === 'Transfer' || $pembayaran->status_pembayaran === 'Menunggu Verifikasi')) {
                    $pembayaran->status_pembayaran = 'Lunas';
                    $pembayaran->save();

                    $store_user_id = DB::table('detail_penyewaan')
                        ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                        ->where('detail_penyewaan.id_penyewaan', $id_penyewaan)
                        ->value('produk.id_user');
                    if ($store_user_id) {
                        $this->catatPemasukan($store_user_id, $pembayaran);
                    }
                }

                if ($penyewaan) {
                    Alert::toast('Order diterima! Penyewaan aktif & pembayaran diverifikasi.', 'success');
                    return redirect('customer/dashboard/transaksi/' . $id_user);
                } else {
                    return response()->json(['message' => 'Update failed'], 500);
                }
            } else {
                $penyewaan = Penyewaan::where('id', $id_penyewaan)->update(['status_penyewaan' => 'Selesai']);
                $this->restoreStok($id_penyewaan);
                if ($penyewaan) {
                    Alert::toast('Pengembalian berhasil disimpan dan stok produk dikembalikan!', 'success');
                    return redirect('customer/dashboard/order-selesai/' . $id_user);
                } else {
                    return response()->json(['message' => 'Update failed'], 500);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in confirmOrderMasuk: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function batalkanOrder($id_penyewaan, $id_user)
    {
        try {
            $penyewaan = Penyewaan::find($id_penyewaan);
            if ($penyewaan) {
                $penyewaan->status_penyewaan = 'Dibatalkan';
                $penyewaan->save();
                $this->restoreStok($id_penyewaan);
                Alert::toast('Pesanan berhasil ditolak/dibatalkan dan stok telah dikembalikan!', 'success');
            } else {
                Alert::toast('Pesanan tidak ditemukan!', 'error');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Error batalkanOrder: ' . $e->getMessage());
            Alert::toast('Terjadi kesalahan!', 'error');
            return redirect()->back();
        }
    }

    public function prosesPengembalian(Request $request, $id_penyewaan, $id_user)
    {
        try {
            $penyewaan = Penyewaan::find($id_penyewaan);
            if (!$penyewaan) {
                Alert::toast('Pesanan tidak ditemukan!', 'error');
                return redirect()->back();
            }

            $buktiName = 'Belum di isi';
            if ($request->hasFile('bukti_kondisi')) {
                $file = $request->file('bukti_kondisi');
                $buktiName = time() . '_kondisi.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/image/customers/pengembalian/'), $buktiName);
            }

            $denda = (int) $request->input('denda', 0);
            $status_denda = $request->input('status_denda', 'Tidak Ada');

            Pengembalian::create([
                'id_penyewaan' => $id_penyewaan,
                'tanggal_kembali_rencana' => $penyewaan->tanggal_selesai,
                'tanggal_kembali_aktual' => now(),
                'kondisi_barang' => $request->input('kondisi_barang', 'Baik'),
                'denda' => $denda,
                'status_denda' => $status_denda,
                'catatan' => $request->input('catatan', '-'),
                'bukti_kondisi' => $buktiName,
                'dicatat_oleh' => auth()->id() ?? Crypt::decrypt($id_user),
            ]);

            $penyewaan->status_penyewaan = 'Selesai';
            $penyewaan->save();

            if ($request->input('kondisi_barang') !== 'Hilang') {
                $this->restoreStok($id_penyewaan);
            }

            if ($denda > 0 && $status_denda === 'Lunas') {
                $store_user_id = Crypt::decrypt($id_user);
                Pemasukan::create([
                    'id_user' => $store_user_id,
                    'sumber' => 'Denda Penyewaan',
                    'deskripsi' => 'Denda Pengembalian (Order ID: ' . $id_penyewaan . ')',
                    'nominal' => $denda,
                    'id_pembayaran_penyewaan' => null,
                ]);
            }

            Alert::toast('Proses pengembalian berhasil disimpan!', 'success');
            return redirect('customer/dashboard/order-selesai/' . $id_user);
        } catch (\Exception $e) {
            Log::error('Error prosesPengembalian: ' . $e->getMessage());
            Alert::toast('Gagal memproses pengembalian: ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    private function catatPemasukan($id_user, $pembayaran)
    {
        if (!$pembayaran) return;
        $exists = Pemasukan::where('id_pembayaran_penyewaan', $pembayaran->id)->exists();
        if (!$exists && $pembayaran->total_pembayaran > 0) {
            Pemasukan::create([
                'id_user' => $id_user,
                'sumber' => 'Penyewaan',
                'deskripsi' => 'Layanan Penyewaan Toko (ID Order: ' . $pembayaran->id_penyewaan . ')',
                'nominal' => $pembayaran->total_pembayaran,
                'id_pembayaran_penyewaan' => $pembayaran->id,
            ]);
            if ($pembayaran->biaya_admin > 0) {
                Pemasukan::create([
                    'id_user' => $id_user,
                    'sumber' => 'Service',
                    'deskripsi' => 'Biaya Admin (ID Order: ' . $pembayaran->id_penyewaan . ')',
                    'nominal' => $pembayaran->biaya_admin,
                    'id_pembayaran_penyewaan' => $pembayaran->id,
                ]);
            }
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

    public function sewaBerlangsung($id_user, Request $request)
    {
        // Decrypt id_user
        $id_user_decrypt = Crypt::decrypt($id_user);

        // Ambil tanggal awal dan tanggal akhir dari query
        $filter_tanggal_awal = $request->query('tanggal_awal');
        $filter_tanggal_akhir = $request->query('tanggal_akhir');
        $search = $request->query('search');

        // Query untuk mengambil data transaksi
        $query = User::leftJoin('penyewaan', 'users.id', '=', 'penyewaan.id_user')
            ->leftJoin('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->leftJoin('pembayaran_penyewaan', 'penyewaan.id', '=', 'pembayaran_penyewaan.id_penyewaan')
            ->leftJoin('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->leftJoin('foto_produk', function ($join) {
                $join->on('produk.id', '=', 'foto_produk.id_produk')
                     ->where('foto_produk.urutan', 1);
            })
            ->leftJoin('users as penyewa', 'produk.id_user', '=', 'penyewa.id')
            ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
            ->select(
                'users.id as id_user_penyewa',
                'users.foto as foto_users',
                'users.name as nama_penyewa',
                'penyewaan.id as id_penyewaan',
                'penyewaan.tanggal_mulai',
                'penyewaan.tanggal_selesai',
                'penyewaan.status_penyewaan',
                'pembayaran_penyewaan.status_pembayaran',
                'pembayaran_penyewaan.metode',
                'produk.id as id_produk',
                'foto_produk.url_foto as foto',
                'produk.nama'
            )
            ->where('penyewa.id', $id_user_decrypt)
            ->where('penyewaan.status_penyewaan', 'Aktif');

        // Filter berdasarkan rentang tanggal jika ada
        if ($filter_tanggal_awal && $filter_tanggal_akhir) {
            $query->whereBetween('penyewaan.created_at', [$filter_tanggal_awal, $filter_tanggal_akhir]);
        } elseif ($filter_tanggal_awal) {
            $query->whereDate('penyewaan.created_at', $filter_tanggal_awal);
        } elseif ($filter_tanggal_akhir) {
            $query->whereDate('penyewaan.created_at', $filter_tanggal_akhir);
        }

        if ($search) {
            $query->where('users.name', 'like', '%' . $search . '%');
        }

        $data = $query->get();

        // Membuat koleksi baru untuk hasil akhir
        $result = collect();

        $seenUsers = [];

        foreach ($data as $item) {
            if (!isset($seenUsers[$item->id_user_penyewa])) {
                $first_product = DB::table('detail_penyewaan')
                    ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                    ->leftJoin('foto_produk', function ($join) {
                        $join->on('produk.id', '=', 'foto_produk.id_produk')
                             ->where('foto_produk.urutan', 1);
                    })
                    ->where('detail_penyewaan.id_penyewaan', $item->id_penyewaan)
                    ->select('produk.id as id_produk', 'foto_produk.url_foto as foto', 'produk.nama')
                    ->first();

                if ($first_product) {
                    $item->id_produk = $first_product->id_produk;
                    $item->foto = $first_product->foto;
                    $item->nama = $first_product->nama;
                }

                $result->push($item);
                $seenUsers[$item->id_user_penyewa] = true;
            }
        }

        // Jika permintaan dari AJAX, kirim JSON response
        if ($request->ajax()) {
            return response()->json(['data' => $result]);
        }

        return view('customers.menu-transaksi.sewa-berlangsung')->with([
            'title' => 'Sewa Berlangsung',
            'id_user' => $id_user_decrypt,
            'data' => $result,
            'search' => $search,
        ]);
    }

    public function dendaTransaksi($id_user)
    {
        return view('customers.menu-transaksi.denda-transaksi')->with([
            'title' => 'Denda Pelanggan',
        ]);
    }

    public function orderSelesai($id_user, Request $request)
    {
        try {
            // Decrypt id_user
            $id_user_decrypt = Crypt::decrypt($id_user);

            // Ambil tanggal awal dan tanggal akhir dari query
            $filter = $request->query('filter-order-selesai');
            $search = $request->query('search');

            // Query untuk mengambil data transaksi
            $query = User::leftJoin('penyewaan', 'users.id', '=', 'penyewaan.id_user')
                ->leftJoin('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
                ->leftJoin('pembayaran_penyewaan', 'penyewaan.id', '=', 'pembayaran_penyewaan.id_penyewaan')
                ->leftJoin('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                ->leftJoin('foto_produk', function ($join) {
                    $join->on('produk.id', '=', 'foto_produk.id_produk')
                         ->where('foto_produk.urutan', 1);
                })
                ->leftJoin('users as penyewa', 'produk.id_user', '=', 'penyewa.id')
                ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
                ->select(
                    'users.id as id_user_penyewa',
                    'users.foto as foto_users',
                    'users.name as nama_penyewa',
                    'penyewaan.id as id_penyewaan',
                    'penyewaan.tanggal_mulai',
                    'penyewaan.tanggal_selesai',
                    'penyewaan.status_penyewaan',
                    'pembayaran_penyewaan.status_pembayaran',
                    'pembayaran_penyewaan.metode',
                    'produk.id as id_produk',
                    'foto_produk.url_foto as foto',
                    'produk.nama'
                )
                ->where('penyewa.id', $id_user_decrypt);

            if ($filter && $filter != 'Semua') {
                $query->where(function ($query) use ($filter) {
                    $query->where('penyewaan.status_penyewaan', $filter);
                });
            } else {
                $query->where(function ($query) {
                    $query->where('penyewaan.status_penyewaan', 'Pengembalian')
                        ->orWhere('penyewaan.status_penyewaan', 'Selesai');
                });
            }

            if ($search) {
                $query->where('users.name', 'like', '%' . $search . '%');
            }

            $data = $query->get();

            // Membuat koleksi baru untuk hasil akhir
            $result = collect();

            $seenUsers = [];

            foreach ($data as $item) {
                if (!isset($seenUsers[$item->id_user_penyewa])) {
                    $first_product = DB::table('detail_penyewaan')
                        ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
                        ->leftJoin('foto_produk', function ($join) {
                            $join->on('produk.id', '=', 'foto_produk.id_produk')
                                 ->where('foto_produk.urutan', 1);
                        })
                        ->where('detail_penyewaan.id_penyewaan', $item->id_penyewaan)
                        ->select('produk.id as id_produk', 'foto_produk.url_foto as foto', 'produk.nama')
                        ->first();

                    if ($first_product) {
                        $item->id_produk = $first_product->id_produk;
                        $item->foto = $first_product->foto;
                        $item->nama = $first_product->nama;
                    }

                    $result->push($item);
                    $seenUsers[$item->id_user_penyewa] = true;
                }
            }

            // Jika permintaan dari AJAX, kirim JSON response
            if ($request->ajax()) {
                return response()->json(['data' => $result]);
            }

            return view('customers.menu-transaksi.selesai-order')->with([
                'title' => 'Order Selesai',
                'id_user' => $id_user_decrypt,
                'data' => $result,
                'search' => $search,
            ]);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
        }
    }
}
