<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

class DetailPenggunaController extends Controller
{
    public function __construct()
    {
        $this->middleware('dev');
    }
    public function index(User $user)
{
    if ($user->type != 0) {
        return redirect()
            ->route('kelola-pengguna.index')
            ->with('error', 'Pengguna tidak ditemukan.');
    }

    $name = $user->name;

    $user_baru_terdaftar = User::select('users.*')
        ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
        ->where('users.type', 0)
        ->whereDate('users.created_at', Carbon::today())
        ->where('status_notifikasi_user.status', 'unread')
        ->orderByDesc('users.created_at')
        ->limit(10)
        ->get();

    $data = DB::table('users')
        ->leftJoin('produk', 'produk.id_user', '=', 'users.id')
        ->where('users.id', $user->id)
        ->where('users.type', 0)
        ->select(
            'users.id as user_id',
            'users.name',
            'users.email',
            'users.nomor_telephone',
            'users.created_at',
            'users.jenis_kelamin',
            'users.foto',
            'users.tanggal_lahir',
            'users.status',
            'users.background',
            'users.updated_at',
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
            'users.tanggal_lahir',
            'users.status',
            'users.background'
        )
        ->first();

    if (!$data) {
        return redirect()
            ->route('kelola-pengguna.index')
            ->with('error', 'Pengguna tidak ditemukan.');
    }

    $produk_disewakan_limit2 = Produk::with('foto')
        ->leftJoin('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
        ->leftJoin('detail_variant_produk', 'variant_produk.id', '=', 'detail_variant_produk.id_variant_produk')
        ->select(
            'produk.id',
            'produk.nama',
            'produk.status',
            DB::raw('COALESCE(SUM(detail_variant_produk.stok), 0) as stok_produk')
        )
        ->where('produk.id_user', $user->id)
        ->groupBy('produk.id', 'produk.nama', 'produk.status')
        ->latest('produk.created_at')
        ->limit(5)
        ->get();

    $feedback_terbaru = Feedback::with([
        'messages' => function ($query) {
            $query->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc');
        }
    ])
        ->where('id_user', $user->id)
        ->latest()
        ->limit(5)
        ->get();

    $total_feedback = Feedback::where('id_user', $user->id)->count();

    $feedback_dibalas = Feedback::where('id_user', $user->id)
        ->where(function ($query) {
            $query->where('status', 'Dibalas')
                ->orWhereHas('messages', function ($message) {
                    $message->where('sender_type', 'admin');
                });
        })
        ->count();

    $feedback_belum_dibalas = Feedback::where('id_user', $user->id)
        ->where(function ($query) {
            $query->where('status', 'Belum Dibalas')
                ->whereDoesntHave('messages', function ($message) {
                    $message->where('sender_type', 'admin');
                });
        })
        ->count();

    return view('developers.detail-pengguna')->with([
        'title' => 'Detail Pengguna',
        'name' => $name,
        'user_baru_terdaftar' => $user_baru_terdaftar,
        'data' => $data,
        'produk_disewakan_limit2' => $produk_disewakan_limit2,
        'feedback_terbaru' => $feedback_terbaru,
        'total_feedback' => $total_feedback,
        'feedback_dibalas' => $feedback_dibalas,
        'feedback_belum_dibalas' => $feedback_belum_dibalas,
        'user_id' => $user->id
    ]);
}
   public function showProdukDisewakan(User $user, Request $request)
{
    if ($user->type != 0) {
        return redirect()
            ->route('kelola-pengguna.index')
            ->with('error', 'Pengguna tidak ditemukan.');
    }

    $user_baru_terdaftar = User::select('users.*')
        ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
        ->where('users.type', 0)
        ->whereDate('users.created_at', Carbon::today())
        ->where('status_notifikasi_user.status', 'unread')
        ->orderByDesc('users.created_at')
        ->limit(10)
        ->get();

    $get_kategori = Produk::select('kategori')
        ->distinct()
        ->pluck('kategori')
        ->toArray();

    $filter_category = $request->input('filter_category', 'Semua Barang');
    $cari_barang = $request->input('cari_barang', '');

    $get_data_produk = Produk::with('foto')
        ->leftJoin('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
        ->leftJoin('detail_variant_produk', 'variant_produk.id', '=', 'detail_variant_produk.id_variant_produk')
        ->select(
            'produk.id as id_produk',
            'produk.id',
            'produk.nama',
            'produk.deskripsi',
            DB::raw('MIN(detail_variant_produk.harga_sewa) as harga_sewa_terkecil')
        )
        ->where('produk.id_user', $user->id)
        ->when($filter_category != 'Semua Barang', function ($query) use ($filter_category) {
            return $query->where('produk.kategori', $filter_category);
        })
        ->when($cari_barang, function ($query) use ($cari_barang) {
            return $query->where('produk.nama', 'like', "%{$cari_barang}%");
        })
        ->groupBy('produk.id', 'produk.nama', 'produk.deskripsi')
        ->get();

    return view('developers.detailpengguna-produkdisewakan')->with([
        'name' => $user->name,
        'title' => 'Produk Disewakan',
        'user_baru_terdaftar' => $user_baru_terdaftar,
        'get_kategori' => $get_kategori,
        'get_data_produk' => $get_data_produk,
        'filter_category' => $filter_category,
        'cari_barang' => $cari_barang,
        'user_id' => $user->id
    ]);
}
    public function showDetailProdukDisewakan(User $user, $namaproduk)
{
    if ($user->type != 0) {
        return redirect()
            ->route('kelola-pengguna.index')
            ->with('error', 'Pengguna tidak ditemukan.');
    }

    $user_baru_terdaftar = User::select('users.*')
        ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
        ->where('users.type', 0)
        ->whereDate('users.created_at', Carbon::today())
        ->where('status_notifikasi_user.status', 'unread')
        ->orderByDesc('users.created_at')
        ->limit(10)
        ->get();

    $produk = Produk::with(['foto'])
        ->where('id_user', $user->id)
        ->where('nama', $namaproduk)
        ->firstOrFail();

    $stok_produk = DB::table('detail_variant_produk')
        ->join('variant_produk', 'detail_variant_produk.id_variant_produk', '=', 'variant_produk.id')
        ->where('variant_produk.id_produk', $produk->id)
        ->sum('stok');

    $harga_sewa_terkecil = DB::table('detail_variant_produk')
        ->join('variant_produk', 'detail_variant_produk.id_variant_produk', '=', 'variant_produk.id')
        ->where('variant_produk.id_produk', $produk->id)
        ->min('harga_sewa');

    // Statistik tambahan
    $total_disewa = DB::table('detail_penyewaan')
        ->where('id_produk', $produk->id)
        ->count();

    $rating_data = DB::table('rating_produk')
        ->where('id_produk', $produk->id)
        ->selectRaw('AVG(rating) as rata_rata, COUNT(*) as total_rating')
        ->first();

    return view('developers.detail-produk-disewakan', [
        'title'           => 'Detail Produk Disewakan',
        'name'            => $user->name,
        'user_id'         => $user->id,
        'user_baru_terdaftar' => $user_baru_terdaftar,
        'nama_produk'     => $namaproduk,
        'produk'          => $produk,
        'stok_produk'     => $stok_produk,
        'harga_sewa_terkecil' => $harga_sewa_terkecil,
        'total_disewa'    => $total_disewa,
        'rata_rata_rating' => round($rating_data->rata_rata ?? 0, 1),
        'total_rating'    => $rating_data->total_rating ?? 0,
    ]);
}
    public function showDetailProdukSedangDisewa(User $user, $namaproduk)
{
    if ($user->type != 0) {
        return redirect()
            ->route('kelola-pengguna.index')
            ->with('error', 'Pengguna tidak ditemukan.');
    }

    $user_baru_terdaftar = User::select('users.*')
        ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
        ->where('users.type', 0)
        ->whereDate('users.created_at', Carbon::today())
        ->where('status_notifikasi_user.status', 'unread')
        ->orderByDesc('users.created_at')
        ->limit(10)
        ->get();

    $produk = Produk::with(['foto'])
        ->where('id_user', $user->id)
        ->where('nama', $namaproduk)
        ->firstOrFail();

    $stok_produk = DB::table('detail_variant_produk')
        ->join('variant_produk', 'detail_variant_produk.id_variant_produk', '=', 'variant_produk.id')
        ->where('variant_produk.id_produk', $produk->id)
        ->sum('stok');

    return view('developers.detail-barang-sedangdisewa', [
        'title' => 'Detail Produk Sedang Disewa',
        'name' => $user->name,
        'nama_produk' => $namaproduk,
        'user_baru_terdaftar' => $user_baru_terdaftar,
        'produk' => $produk,
        'stok_produk' => $stok_produk
    ]);
}

    public function deleteSelectedProducts(Request $request)
    {
        $ids = $request->input('ids');
        Produk::whereIn('id', $ids)->delete();

        return back()->with('success', 'Produk terpilih telah dihapus.');
    }

    public function update(Request $request, $id)
{
    $user = User::whereKey($id)
        ->where('type', 0)
        ->firstOrFail();

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($user->id),
        ],
        'nomor_telephone' => ['nullable', 'string', 'max:25'],
        'tanggal_lahir' => ['nullable', 'date'],
        'jenis_kelamin' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
        'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    if ($request->hasFile('foto')) {
        $fotoLama = $user->foto;

        if (
            $fotoLama &&
            !in_array(strtolower(trim($fotoLama)), ['belum di isi', 'belum di isi.']) &&
            Storage::disk('public')->exists($fotoLama)
        ) {
            Storage::disk('public')->delete($fotoLama);
        }

        $validated['foto'] = $request->file('foto')->store('customers/profile', 'public');
    }

    $user->update($validated);

    return back()->with('success', 'Data pengguna berhasil diperbarui.');
}

public function destroy($id)
{
    try {
        $user = User::whereKey($id)
            ->where('type', 0)
            ->firstOrFail();

        $fotoLama = $user->foto;

        if (
            $fotoLama &&
            !in_array(strtolower(trim($fotoLama)), ['belum di isi', 'belum di isi.']) &&
            Storage::disk('public')->exists($fotoLama)
        ) {
            Storage::disk('public')->delete($fotoLama);
        }

        $user->delete();

        return redirect()
            ->route('kelola-pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    } catch (Throwable $e) {
        return back()->with('error', 'Pengguna tidak dapat dihapus karena masih memiliki data terkait.');
    }

    }
}
