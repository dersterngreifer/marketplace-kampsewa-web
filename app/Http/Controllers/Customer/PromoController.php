<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\PromosiProduk;
use App\Models\PromosiToko;
use App\Services\PromoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PromoController extends Controller
{
    public function __construct()
    {
        $this->middleware('cust');
    }

    private function resolveUserId($id_user)
    {
        if (is_numeric($id_user)) {
            return (int) $id_user;
        }

        try {
            return Crypt::decrypt($id_user);
        } catch (\Exception $e) {
            try {
                return Crypt::decryptString($id_user);
            } catch (\Exception $e2) {
                return session('id_user') ?: auth()->id();
            }
        }
    }

    /**
     * Tampilan daftar program diskon produk milik toko mitra.
     */
    public function index($id_user)
    {
        $userId = $this->resolveUserId($id_user);
        $now = Carbon::now();

        // Ambil semua diskon produk milik mitra
        $promos = PromosiToko::with(['produk'])
            ->where('id_user', $userId)
            ->orderByDesc('created_at')
            ->get();

        $totalActive = $promos->filter(function ($p) use ($now) {
            return $p->is_active && $p->status === 'active' && $p->tanggal_mulai <= $now && $p->tanggal_selesai >= $now;
        })->count();

        $totalDraft = $promos->where('status', 'draft')->count();

        $totalExpired = $promos->filter(function ($p) use ($now) {
            return $p->tanggal_selesai < $now;
        })->count();

        $activeStoreWide = PromoService::getActiveStoreWidePromo($userId);

        return view('customers.menu-promo.index', [
            'title'           => 'Diskon Produk Toko | KampSewa',
            'id_user'         => $id_user,
            'id_user_dec'     => $userId,
            'promos'          => $promos,
            'activeStoreWide' => $activeStoreWide,
            'totalActive'     => $totalActive,
            'totalDraft'      => $totalDraft,
            'totalExpired'    => $totalExpired,
            'now'             => $now,
        ]);
    }

    /**
     * Tampilan form tambah program diskon produk baru.
     */
    public function create($id_user)
    {
        $userId = $this->resolveUserId($id_user);

        // Ambil produk milik mitra untuk pilihan produk tertentu
        $products = Produk::with(['photoThumbnail', 'detailVariant'])
            ->where('id_user', $userId)
            ->orderBy('nama', 'asc')
            ->get();

        // Cek apakah toko sudah memiliki diskon keseluruhan aktif (all_products)
        $activeStoreWide = PromoService::getActiveStoreWidePromo($userId);

        // Cek produk mana saja yang saat ini sedang aktif dalam diskon
        $activeDiscountMap = PromoService::getActiveProductsDiscountMap($userId);

        foreach ($products as $prod) {
            $prod->active_discount = $activeDiscountMap[$prod->id] ?? null;
        }

        // Ambil daftar kategori unik produk milik mitra dari database
        $categories = $products->pluck('kategori')->filter()->unique()->values();

        return view('customers.menu-promo.create', [
            'title'             => 'Buat Diskon Produk Baru | KampSewa',
            'id_user'           => $id_user,
            'id_user_dec'       => $userId,
            'products'          => $products,
            'categories'        => $categories,
            'activeStoreWide'   => $activeStoreWide,
            'activeDiscountMap' => $activeDiscountMap,
        ]);
    }

    /**
     * Simpan diskon produk baru ke database (Murni potongan harga produk).
     */
    public function store(Request $request, $id_user)
    {
        $userId = $this->resolveUserId($id_user);

        $request->validate([
            'nama_diskon'       => 'required|string|max:150',
            'tipe_diskon'       => 'required|in:persen,nominal',
            'nilai_diskon'      => 'required|numeric|min:1',
            'cakupan'           => 'required|in:all_products,selected_products',
            'konflik_diskon'    => 'nullable|in:replace,skip',
            'selected_products' => 'nullable|array',
            'tanggal_mulai'     => 'required|date',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
            'status'            => 'required|in:active,draft,inactive',
        ], [
            'nama_diskon.required'           => 'Nama program diskon wajib diisi.',
            'nilai_diskon.required'          => 'Besaran nilai diskon wajib diisi.',
            'nilai_diskon.min'               => 'Nilai diskon minimal 1.',
            'tanggal_selesai.after_or_equal' => 'Periode berakhir harus sama atau setelah periode mulai.',
        ]);

        $isActive = $request->status !== 'inactive';

        // 1. ATURAN DISKON KESELURUHAN (all_products):
        // Mitra TIDAK BISA membuat diskon keseluruhan baru jika toko masih memiliki diskon keseluruhan yang aktif.
        if ($request->cakupan === 'all_products' && $isActive) {
            $existingStoreWide = PromoService::getActiveStoreWidePromo($userId);
            if ($existingStoreWide) {
                Alert::error(
                    'Diskon Toko Masih Aktif',
                    "Toko Anda saat ini sudah memiliki program diskon keseluruhan aktif ('{$existingStoreWide->nama_diskon}'). Anda tidak dapat membuat diskon keseluruhan baru sebelum program tersebut berakhir atau dinonaktifkan/dihapus. Silakan edit program diskon tersebut jika ingin mengubah nilainya."
                );
                return redirect()->back()->withInput();
            }
        }

        // 2. ATURAN PRODUK PILIHAN TERTENTU (selected_products):
        // Produk yang sedang memiliki diskon aktif TIDAK BISA didiskon ganda (hanya bisa edit diskon produk tersebut).
        if ($request->cakupan === 'selected_products') {
            if (empty($request->selected_products)) {
                Alert::error('Gagal', 'Pilih minimal satu produk jika memilih cakupan Produk Tertentu.');
                return redirect()->back()->withInput();
            }

            if ($isActive) {
                $activeDiscountMap = PromoService::getActiveProductsDiscountMap($userId);
                $conflicts = [];

                foreach ($request->selected_products as $prodId) {
                    if (isset($activeDiscountMap[$prodId])) {
                        $prodName = Produk::where('id', $prodId)->value('nama') ?? "ID $prodId";
                        $conflicts[] = "'{$prodName}' (aktif di '{$activeDiscountMap[$prodId]['nama_diskon']}')";
                    }
                }

                if (!empty($conflicts)) {
                    $conflictList = implode(', ', $conflicts);
                    Alert::error(
                        'Produk Sedang Berdiskon Aktif',
                        "Produk berikut sedang memiliki program diskon aktif: {$conflictList}. Anda tidak dapat menambahkan data diskon baru untuk produk tersebut. Silakan buka menu edit pada diskon terkait untuk mengubahnya."
                    );
                    return redirect()->back()->withInput();
                }
            }
        }

        DB::beginTransaction();
        try {
            $konflikDiskon = $request->cakupan === 'all_products' ? ($request->konflik_diskon ?? 'replace') : 'replace';

            $promo = PromosiToko::create([
                'id_user'         => $userId,
                'nama_diskon'     => $request->nama_diskon,
                'judul_promo'     => $request->nama_diskon,
                'tipe_diskon'     => $request->tipe_diskon,
                'nilai_diskon'    => $request->nilai_diskon,
                'cakupan'         => $request->cakupan,
                'konflik_diskon'  => $konflikDiskon,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'is_banner'       => false,
                'is_active'       => $isActive,
                'status'          => $request->status,
            ]);

            // Jika cakupan produk tertentu
            if ($request->cakupan === 'selected_products' && !empty($request->selected_products)) {
                foreach ($request->selected_products as $prodId) {
                    PromosiProduk::create([
                        'id_promo'  => $promo->id_promo,
                        'id_produk' => (int) $prodId,
                    ]);
                }
            }

            // Jika cakupan all_products dan opsi replace dipilih:
            // Samaratakan ke seluruh produk & replace data diskon lama pada produk-produk yang bersangkutan
            if ($request->cakupan === 'all_products' && $isActive && $konflikDiskon === 'replace') {
                PromoService::handleStoreWideReplaceConflict($userId, $promo->id_promo);
            }

            DB::commit();

            Alert::success('Berhasil', 'Program diskon produk toko berhasil dibuat dan diterapkan ke katalog!');
            return redirect()->route('menu-promo.index', ['id_user' => $id_user]);
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Form edit diskon produk toko.
     */
    public function edit($id_promo, $id_user)
    {
        $userId = $this->resolveUserId($id_user);

        $promo = PromosiToko::where('id_promo', $id_promo)
            ->where('id_user', $userId)
            ->firstOrFail();

        $products = Produk::with(['photoThumbnail', 'detailVariant'])
            ->where('id_user', $userId)
            ->orderBy('nama', 'asc')
            ->get();

        $selectedProductIds = PromosiProduk::where('id_promo', $id_promo)
            ->pluck('id_produk')
            ->toArray();

        // Cek promo keseluruhan aktif lain selain promo yang sedang diedit ini
        $activeStoreWide = PromoService::getActiveStoreWidePromo($userId, $id_promo);

        // Peta produk diskon aktif selain promo yang sedang diedit ini
        $activeDiscountMap = PromoService::getActiveProductsDiscountMap($userId, $id_promo);

        foreach ($products as $prod) {
            $prod->active_discount = $activeDiscountMap[$prod->id] ?? null;
        }

        // Ambil daftar kategori unik produk milik mitra dari database
        $categories = $products->pluck('kategori')->filter()->unique()->values();

        return view('customers.menu-promo.edit', [
            'title'               => 'Edit Diskon Produk | KampSewa',
            'id_user'             => $id_user,
            'id_user_dec'         => $userId,
            'promo'               => $promo,
            'products'            => $products,
            'categories'          => $categories,
            'selectedProductIds'  => $selectedProductIds,
            'activeStoreWide'     => $activeStoreWide,
            'activeDiscountMap'   => $activeDiscountMap,
        ]);
    }

    /**
     * Update data diskon produk toko.
     */
    public function update(Request $request, $id_promo, $id_user)
    {
        $userId = $this->resolveUserId($id_user);

        $promo = PromosiToko::where('id_promo', $id_promo)
            ->where('id_user', $userId)
            ->firstOrFail();

        $request->validate([
            'nama_diskon'       => 'required|string|max:150',
            'tipe_diskon'       => 'required|in:persen,nominal',
            'nilai_diskon'      => 'required|numeric|min:1',
            'cakupan'           => 'required|in:all_products,selected_products',
            'konflik_diskon'    => 'nullable|in:replace,skip',
            'selected_products' => 'nullable|array',
            'tanggal_mulai'     => 'required|date',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
            'status'            => 'required|in:active,draft,inactive',
        ], [
            'nama_diskon.required'           => 'Nama program diskon wajib diisi.',
            'nilai_diskon.required'          => 'Besaran nilai diskon wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Periode berakhir harus sama atau setelah periode mulai.',
        ]);

        $isActive = $request->status !== 'inactive';

        // 1. Cek diskon keseluruhan aktif lain
        if ($request->cakupan === 'all_products' && $isActive) {
            $existingStoreWide = PromoService::getActiveStoreWidePromo($userId, $id_promo);
            if ($existingStoreWide) {
                Alert::error(
                    'Diskon Toko Masih Aktif',
                    "Toko Anda saat ini sudah memiliki diskon keseluruhan aktif lainnya ('{$existingStoreWide->nama_diskon}'). Anda tidak dapat mengaktifkan 2 diskon keseluruhan bersamaan."
                );
                return redirect()->back()->withInput();
            }
        }

        // 2. Cek konflik produk pada selected_products (kecuali produk yang sudah ada di promo ini)
        if ($request->cakupan === 'selected_products') {
            if (empty($request->selected_products)) {
                Alert::error('Gagal', 'Pilih minimal satu produk jika memilih cakupan Produk Tertentu.');
                return redirect()->back()->withInput();
            }

            if ($isActive) {
                $activeDiscountMap = PromoService::getActiveProductsDiscountMap($userId, $id_promo);
                $conflicts = [];

                foreach ($request->selected_products as $prodId) {
                    if (isset($activeDiscountMap[$prodId])) {
                        $prodName = Produk::where('id', $prodId)->value('nama') ?? "ID $prodId";
                        $conflicts[] = "'{$prodName}' (aktif di '{$activeDiscountMap[$prodId]['nama_diskon']}')";
                    }
                }

                if (!empty($conflicts)) {
                    $conflictList = implode(', ', $conflicts);
                    Alert::error(
                        'Produk Sedang Berdiskon Aktif',
                        "Produk berikut sedang memiliki program diskon aktif di promo lain: {$conflictList}. Anda tidak dapat mendiskon ganda produk ini."
                    );
                    return redirect()->back()->withInput();
                }
            }
        }

        DB::beginTransaction();
        try {
            $konflikDiskon = $request->cakupan === 'all_products' ? ($request->konflik_diskon ?? 'replace') : 'replace';

            $promo->update([
                'nama_diskon'     => $request->nama_diskon,
                'judul_promo'     => $request->nama_diskon,
                'tipe_diskon'     => $request->tipe_diskon,
                'nilai_diskon'    => $request->nilai_diskon,
                'cakupan'         => $request->cakupan,
                'konflik_diskon'  => $konflikDiskon,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'is_banner'       => false,
                'is_active'       => $isActive,
                'status'          => $request->status,
            ]);

            // Sinkronisasi relasi produk
            PromosiProduk::where('id_promo', $promo->id_promo)->delete();
            if ($request->cakupan === 'selected_products' && !empty($request->selected_products)) {
                foreach ($request->selected_products as $prodId) {
                    PromosiProduk::create([
                        'id_promo'  => $promo->id_promo,
                        'id_produk' => (int) $prodId,
                    ]);
                }
            }

            // Jika all_products dengan replace
            if ($request->cakupan === 'all_products' && $isActive && $konflikDiskon === 'replace') {
                PromoService::handleStoreWideReplaceConflict($userId, $promo->id_promo);
            }

            DB::commit();

            Alert::success('Berhasil', 'Program diskon produk berhasil diperbarui!');
            return redirect()->route('menu-promo.index', ['id_user' => $id_user]);
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Hapus diskon produk toko.
     */
    public function destroy($id_promo, $id_user)
    {
        $userId = $this->resolveUserId($id_user);

        $promo = PromosiToko::where('id_promo', $id_promo)
            ->where('id_user', $userId)
            ->firstOrFail();

        $promo->delete();

        Alert::success('Berhasil', 'Program diskon produk telah dihapus.');
        return redirect()->route('menu-promo.index', ['id_user' => $id_user]);
    }

    /**
     * Toggle status diskon aktif / nonaktif cepat.
     */
    public function toggleStatus($id_promo, $id_user)
    {
        $userId = $this->resolveUserId($id_user);

        $promo = PromosiToko::where('id_promo', $id_promo)
            ->where('id_user', $userId)
            ->firstOrFail();

        $newActive = !$promo->is_active;
        $promo->update([
            'is_active' => $newActive,
            'status'    => $newActive ? 'active' : 'inactive',
        ]);

        $statusMsg = $newActive ? 'diaktifkan' : 'dinonaktifkan';
        Alert::success('Berhasil', "Diskon produk berhasil {$statusMsg}.");
        return redirect()->route('menu-promo.index', ['id_user' => $id_user]);
    }
}
