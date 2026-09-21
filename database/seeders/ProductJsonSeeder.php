<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\VariantProduk;
use App\Models\DetailVariantProduk;
use App\Models\FotoProduk;
use App\Models\User;
use App\Models\RatingProduk;
use Illuminate\Support\Facades\File;

class ProductJsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Dapatkan atau buat user test@gmail.com
        $user = User::firstOrCreate(
            ['email' => 'test@gmail.com'],
            [
                'name' => 'Pemilik Toko Test',
                'password' => bcrypt('password123'),
                'nomor_telephone' => '081234567890',
                'type' => 0,
            ]
        );

        // Siapkan reviewer (buat 5 user dummy jika kurang)
        $reviewers = User::where('email', '!=', 'test@gmail.com')->inRandomOrder()->take(5)->get();
        if ($reviewers->count() < 5) {
            $reviewers = User::factory(5)->create();
        }

        $jsonPath = public_path('products.json');
        if (!File::exists($jsonPath)) {
            $this->command->error('File products.json tidak ditemukan di public/products.json!');
            return;
        }

        $jsonContent = File::get($jsonPath);
        $products = json_decode($jsonContent, true);

        if (!$products) {
            $this->command->error('Format products.json tidak valid!');
            return;
        }

        $ulasanKomentar = [
            "Barang sangat bagus dan berkualitas!",
            "Pengiriman cepat dan barang sesuai deskripsi, recomended!",
            "Kondisi barang mulus, sangat cocok untuk camping.",
            "Cukup baik, sesuai dengan harga sewanya.",
            "Luar biasa, tidak mengecewakan sama sekali.",
            "Warna dan ukurannya pas, terima kasih!",
            "Bakal sewa lagi di sini, pelayanannya mantap.",
            "Sangat memuaskan, kualitas barang original.",
        ];

        foreach ($products as $item) {
            // Kategori
            $kategori = 'Perlengkapan';
            $namaLower = strtolower($item['name']);
            if (strpos($namaLower, 'tenda') !== false) {
                $kategori = 'Tenda';
            } elseif (strpos($namaLower, 'tas') !== false || strpos($namaLower, 'carrier') !== false) {
                $kategori = 'Tas';
            } elseif (strpos($namaLower, 'sepatu') !== false) {
                $kategori = 'Sepatu';
            } elseif (strpos($namaLower, 'pakaian') !== false || strpos($namaLower, 'jaket') !== false) {
                $kategori = 'Pakaian';
            }

            // Parse harga
            $priceStr = $item['price'] ?? '0';
            $priceStr = preg_replace('/[^0-9]/', '', $priceStr);
            $hargaDasar = intval($priceStr);
            if ($hargaDasar == 0) $hargaDasar = 25000; 

            // Create Produk
            $produk = Produk::create([
                'id_user' => $user->id,
                'nama' => $item['name'],
                'deskripsi' => $item['description'] ?? 'Tidak ada deskripsi',
                'kategori' => $kategori,
                'status' => 'Tersedia',
                'foto_depan' => $item['listing_photo'] ?? 'Belum di isi',
                'foto_belakang' => 'Belum di isi',
                'foto_kiri' => 'Belum di isi',
                'foto_kanan' => 'Belum di isi',
            ]);

            // Foto produk
            $allPhotos = [];
            if (!empty($item['listing_photo'])) {
                $allPhotos[] = $item['listing_photo'];
            }
            if (!empty($item['detail_photos']) && is_array($item['detail_photos'])) {
                foreach ($item['detail_photos'] as $photo) {
                    if ($photo !== $item['listing_photo']) {
                        $allPhotos[] = $photo;
                    }
                }
            }
            foreach ($allPhotos as $index => $photoUrl) {
                FotoProduk::create([
                    'id_produk' => $produk->id,
                    'url_foto' => $photoUrl,
                    'tipe_sumber' => 'external',
                    'urutan' => $index + 1
                ]);
            }
            if (count($allPhotos) > 0) {
                $produk->foto_depan = $allPhotos[0] ?? 'Belum di isi';
                $produk->foto_belakang = $allPhotos[1] ?? 'Belum di isi';
                $produk->foto_kiri = $allPhotos[2] ?? 'Belum di isi';
                $produk->foto_kanan = $allPhotos[3] ?? 'Belum di isi';
                $produk->save();
            }

            // Variants: Warnas & Ukurans dengan harga berbeda
            $warnas = ['Hitam', 'Biru', 'Hijau Army'];
            $ukurans = ['Medium (M)', 'Large (L)', 'Extra Large (XL)'];

            // Tiap warna punya ukuran yang harganya naik dikit
            foreach ($warnas as $indexWarna => $warna) {
                $variant = VariantProduk::create([
                    'id_produk' => $produk->id,
                    'warna' => $warna
                ]);

                foreach ($ukurans as $indexUkuran => $ukuran) {
                    // Beri harga beda tiap varian dan ukuran
                    $hargaSewaUkuran = $hargaDasar + ($indexUkuran * 5000) + ($indexWarna * 2000);
                    DetailVariantProduk::create([
                        'id_variant_produk' => $variant->id,
                        'ukuran' => $ukuran,
                        'stok' => rand(5, 30),
                        'harga_sewa' => $hargaSewaUkuran
                    ]);
                }
            }

            // Rating & Ulasan (3 sampai 5 ulasan per produk)
            $jumlahUlasan = rand(3, 5);
            $reviewerAcak = $reviewers->random($jumlahUlasan);
            
            foreach ($reviewerAcak as $reviewer) {
                RatingProduk::create([
                    'id_user' => $reviewer->id,
                    'id_produk' => $produk->id,
                    'rating' => rand(4, 5), // Rating bagus 4-5
                    'ulasan' => $ulasanKomentar[array_rand($ulasanKomentar)],
                ]);
            }
        }

        $this->command->info('Sukses mengimport ' . count($products) . ' produk beserta variant, ukuran (beda harga), dan ulasan rating untuk user test@gmail.com!');
    }
}
