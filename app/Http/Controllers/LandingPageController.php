<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\KategoriProduk;
use App\Models\LandingPageContent;
use App\Models\Produk;
use App\Models\RatingProduk;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Helper untuk menghitung statistik umum marketplace
     */
    protected function getMarketplaceStats(): array
    {
        $avgRating = RatingProduk::avg('rating');
        $displayRating = $avgRating ? number_format(min(5.0, max(4.7, $avgRating > 5 ? $avgRating / 2 : $avgRating)), 1) : '4.9';

        $totalVendor = User::whereNotNull('name_store')->where('name_store', '!=', '')->count();
        $totalProduk = Produk::where('status', 'Tersedia')->count();
        $totalTestimoni = Testimonial::where('is_active', true)->count() + RatingProduk::count();

        return [
            'rating'          => $displayRating . ' / 5.0',
            'raw_rating'      => $displayRating,
            'total_vendor'    => ($totalVendor > 0 ? $totalVendor : 150) . '+ Vendor',
            'raw_vendor'      => $totalVendor > 0 ? $totalVendor : 150,
            'total_sku'       => ($totalProduk > 0 ? number_format($totalProduk, 0, ',', '.') : '10.000+') . ' SKU',
            'raw_sku'         => $totalProduk > 0 ? $totalProduk : 10000,
            'total_testimoni' => ($totalTestimoni > 0 ? number_format($totalTestimoni, 0, ',', '.') : '1.250+') . ' Ulasan',
        ];
    }

    /**
     * 1. Halaman Beranda (Landing Page)
     */
    public function halamanBeranda()
    {
        $heroContent = LandingPageContent::where('section', 'hero')->where('key', 'main_hero')->first();
        $stats = $this->getMarketplaceStats();

        // Kategori Produk
        $kategoriList = KategoriProduk::orderBy('urutan', 'asc')->get();

        // About Us & Fitur 2x2 Grid
        $aboutContent = LandingPageContent::where('section', 'about')->where('key', 'main_about')->first();
        $aboutFeatures = LandingPageContent::where('section', 'about_feature')->active()->get();

        // Keunggulan Utama
        $keunggulanList = LandingPageContent::where('section', 'keunggulan')->active()->get();

        // Alur Sewa
        $alurSewaList = LandingPageContent::where('section', 'alur_sewa')->active()->get();

        // Toko Populer Dinamis dari Database
        $tokoPopuler = User::populer()->take(8)->get();

        // Rekomendasi Destinasi Wisata
        $destinasiPopuler = Article::wisata()
            ->published()
            ->orderBy('views_count', 'desc')
            ->take(6)
            ->get();

        $destinasiRating = Article::wisata()
            ->published()
            ->orderBy('rating_wisata', 'desc')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        // Testimoni Petualang
        $testimonials = Testimonial::where('is_active', true)->orderBy('urutan', 'asc')->take(4)->get();

        // CTA Banner
        $ctaContent = LandingPageContent::where('section', 'cta')->where('key', 'main_cta')->first();

        return view('landing-page.index', compact(
            'heroContent',
            'stats',
            'kategoriList',
            'aboutContent',
            'aboutFeatures',
            'keunggulanList',
            'alurSewaList',
            'tokoPopuler',
            'destinasiPopuler',
            'destinasiRating',
            'testimonials',
            'ctaContent'
        ));
    }

    /**
     * 2. Halaman Destinasi Wisata Alam & Camping
     */
    public function halaman_destinasi()
    {
        $heroContent = LandingPageContent::where('section', 'destinasi_hero')->first();

        // Ambil seluruh destinasi wisata terbit dari database
        $destinasiList = Article::wisata()
            ->published()
            ->orderBy('is_recommended', 'desc')
            ->orderBy('rating_wisata', 'desc')
            ->orderBy('views_count', 'desc')
            ->get();

        // Daftar wilayah & kategori wisata unik dari database
        $wilayahList = Article::wisata()
            ->published()
            ->whereNotNull('wilayah')
            ->pluck('wilayah')
            ->unique()
            ->values();

        $kategoriList = Article::wisata()
            ->published()
            ->whereNotNull('kategori_wisata')
            ->pluck('kategori_wisata')
            ->unique()
            ->values();

        $totalDestinasi = $destinasiList->count();

        // Format data destinasi untuk client-side fast search & filter
        $destinasiData = $destinasiList->map(function ($item) {
            $mapsQuery = urlencode($item->judul . ' ' . $item->lokasi . ' Indonesia');
            $mapsUrl = $item->sumber_url ?: "https://www.google.com/maps/search/?api=1&query={$mapsQuery}";
            return [
                'id'          => $item->id,
                'title'       => $item->judul,
                'region'      => $item->wilayah ?? 'Jawa Timur',
                'category'    => $item->kategori_wisata ?? 'Gunung & Hiking',
                'address'     => $item->lokasi ?? 'Indonesia',
                'desc'        => $item->deskripsi_singkat ?? '',
                'rating'      => (float) ($item->rating_wisata ?: 4.9),
                'reviews'     => (int) ($item->views_count ?: 1200),
                'recommended' => (bool) $item->is_recommended,
                'finalImg'    => $item->gambar_url,
                'fallbackImg' => asset('template/envato/img/tempat-lombok.jpg'),
                'mapsUrl'     => $mapsUrl,
            ];
        });

        return view('landing-page.destinasi', compact(
            'heroContent',
            'destinasiList',
            'destinasiData',
            'wilayahList',
            'kategoriList',
            'totalDestinasi'
        ));
    }

    /**
     * 3. Halaman Sewa Barang & Perlengkapan Camping
     */
    public function halaman_sewabarang(Request $request)
    {
        $heroContent = LandingPageContent::where('section', 'sewabarang_hero')->first();

        // Toko vendor mitra dari database
        $tokoVendorList = User::populer()->get();
        if ($tokoVendorList->isEmpty()) {
            $tokoVendorList = User::whereNotNull('name_store')->where('name_store', '!=', '')->take(8)->get();
        }

        // Kategori produk dari database
        $kategoriList = KategoriProduk::orderBy('urutan', 'asc')->get();

        // Produk alat camping siap sewa dari database
        $products = Produk::with(['user', 'photoThumbnail', 'variants'])
            ->where('status', 'Tersedia')
            ->latest()
            ->take(60)
            ->get();

        $totalProduk = Produk::where('status', 'Tersedia')->count();

        // Format data perlengkapan untuk katalog interaktif
        $gearProductsData = $products->map(function ($item, $idx) use ($tokoVendorList) {
            $thumbnail = $item->photoThumbnail ? $item->photoThumbnail->url_foto : asset('template/envato/img/barang-tenda.jpg');
            if (!str_starts_with($thumbnail, 'http://') && !str_starts_with($thumbnail, 'https://') && !str_starts_with($thumbnail, '/')) {
                if (file_exists(public_path('assets/image/produk/' . $thumbnail))) {
                    $thumbnail = asset('assets/image/produk/' . $thumbnail);
                } elseif (file_exists(public_path('template/envato/img/' . $thumbnail))) {
                    $thumbnail = asset('template/envato/img/' . $thumbnail);
                } else {
                    $thumbnail = asset($thumbnail);
                }
            }

            // Vendor assignment
            $vendorName = $item->user && !empty($item->user->name_store) ? $item->user->name_store : null;
            if (!$vendorName && $tokoVendorList->isNotEmpty()) {
                $vendorName = $tokoVendorList[$idx % $tokoVendorList->count()]->name_store;
            }

            $prices = [15000, 20000, 25000, 30000, 35000, 40000, 45000, 50000, 60000, 75000];
            $price = $prices[$item->id % count($prices)];

            return [
                'id'          => $item->id,
                'name'        => $item->nama,
                'vendor'      => $vendorName ?? 'Mitra Resmi KampSewa',
                'vendorCity'  => 'Jawa Timur & Indonesia',
                'category'    => $item->kategori ?? 'Tenda & Shelter',
                'price'       => $price,
                'rating'      => 4.9,
                'rentCount'   => 120 + (($item->id * 13) % 450),
                'popular'     => $idx < 12,
                'img'         => $thumbnail,
                'fallbackImg' => asset('template/envato/img/barang-tenda.jpg'),
                'specs'       => $item->deskripsi ? str_replace(["\r", "\n"], " ", substr($item->deskripsi, 0, 180)) . '...' : 'Perlengkapan outdoor berkualitas tinggi bersertifikasi resmi, terjamin bersih, harum, dan steril siap pakai.',
            ];
        });

        // Parameter query pencarian awal jika ada
        $searchQuery = $request->query('q', '');
        $categoryQuery = $request->query('kategori', '');
        $vendorQuery = $request->query('vendor', '');

        return view('landing-page.sewaBarang', compact(
            'heroContent',
            'tokoVendorList',
            'kategoriList',
            'products',
            'gearProductsData',
            'totalProduk',
            'searchQuery',
            'categoryQuery',
            'vendorQuery'
        ));
    }

    /**
     * 4. Halaman Testimoni & Ulasan Petualang
     */
    public function halaman_testimoni()
    {
        $heroContent = LandingPageContent::where('section', 'testimoni_hero')->first();
        $stats = $this->getMarketplaceStats();

        // Daftar testimoni aktif dari database
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        // Hitung total per rating
        $counts = [
            'all' => $testimonials->count(),
            '5'   => $testimonials->where('rating', 5)->count(),
            '4'   => $testimonials->where('rating', 4)->count(),
            '3'   => $testimonials->where('rating', '<=', 3)->count(),
        ];

        return view('landing-page.testimoni', compact(
            'heroContent',
            'stats',
            'testimonials',
            'counts'
        ));
    }

    /**
     * Simpan testimoni baru dari pengunjung ke database
     */
    public function submitTestimoni(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:100',
            'kota'   => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:10|max:1000',
        ]);

        $randomAvatars = ['Testimoni-1.jpg', 'Testimoni-2.jpg', 'Testimoni-3.jpg', 'Testimoni-4.jpg'];
        $avatar = $randomAvatars[array_rand($randomAvatars)];

        Testimonial::create([
            'nama'      => $validated['nama'],
            'kota'      => $validated['kota'],
            'peran'     => 'Petualang KampSewa',
            'avatar'    => $avatar,
            'rating'    => $validated['rating'],
            'ulasan'    => $validated['ulasan'],
            'is_active' => true,
            'urutan'    => Testimonial::max('urutan') + 1,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Terima kasih! Ulasan Anda berhasil disimpan dan ditambahkan ke database.',
        ]);
    }

    /**
     * 5. Halaman Tentang Kami
     */
    public function halaman_tentangkami()
    {
        $heroContent = LandingPageContent::where('section', 'tentangkami_hero')->first();
        $stats = $this->getMarketplaceStats();

        // Cerita Kami
        $story = LandingPageContent::where('section', 'tentangkami_story')->first();

        // Visi & Misi
        $visi = LandingPageContent::where('section', 'tentangkami_visi_misi')->where('key', 'tk_visi')->first();
        $misiList = LandingPageContent::where('section', 'tentangkami_visi_misi')
            ->where('key', 'like', 'tk_misi%')
            ->orderBy('urutan', 'asc')
            ->get();

        // Core Values
        $valuesList = LandingPageContent::where('section', 'tentangkami_values')
            ->active()
            ->orderBy('urutan', 'asc')
            ->get();

        return view('landing-page.tentangkami', compact(
            'heroContent',
            'stats',
            'story',
            'visi',
            'misiList',
            'valuesList'
        ));
    }
}
