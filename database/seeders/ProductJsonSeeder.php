<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\VariantProduk;
use App\Models\DetailVariantProduk;
use App\Models\FotoProduk;
use App\Models\User;
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
        $user = User::where('email', 'user@gmail.com')->first();
        if (!$user) {
            $this->command->error('User dengan email user@gmail.com tidak ditemukan!');
            return;
        }

        $jsonPath = public_path('products.json');
        if (!File::exists($jsonPath)) {
            $this->command->error('File products.json tidak ditemukan!');
            return;
        }

        $jsonContent = File::get($jsonPath);
        $products = json_decode($jsonContent, true);

        if (!$products) {
            $this->command->error('Format products.json tidak valid!');
            return;
        }

        foreach ($products as $item) {
            // Tentukan kategori sederhana dari kata kunci nama
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

            // Parse harga: Rp2.159.000 -> 2159000
            $priceStr = $item['price'] ?? '0';
            $priceStr = preg_replace('/[^0-9]/', '', $priceStr);
            $hargaSewa = intval($priceStr);
            if ($hargaSewa == 0) $hargaSewa = 10000; // Harga default jika tidak ada

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

            // Insert FotoProduk (unlimited)
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

            // Sync fallback
            if (count($allPhotos) > 0) {
                $produk->foto_depan = $allPhotos[0] ?? 'Belum di isi';
                $produk->foto_belakang = $allPhotos[1] ?? 'Belum di isi';
                $produk->foto_kiri = $allPhotos[2] ?? 'Belum di isi';
                $produk->foto_kanan = $allPhotos[3] ?? 'Belum di isi';
                $produk->save();
            }

            // Buat default Variant & Detail Variant
            $variant = VariantProduk::create([
                'id_produk' => $produk->id,
                'warna' => 'Default'
            ]);

            DetailVariantProduk::create([
                'id_variant_produk' => $variant->id,
                'ukuran' => 'Semua Ukuran',
                'stok' => rand(10, 50),
                'harga_sewa' => $hargaSewa
            ]);
        }

        $this->command->info('Sukses mengimport ' . count($products) . ' produk!');
    }
}
