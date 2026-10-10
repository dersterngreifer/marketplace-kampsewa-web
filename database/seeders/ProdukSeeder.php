<?php

namespace Database\Seeders;

use App\Models\FotoProduk;
use App\Models\Produk;
use App\Helpers\PhotoHelper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = public_path('products.json');

        if (file_exists($jsonPath)) {
            $json = file_get_contents($jsonPath);
            $baseProducts = json_decode($json, true);
            
            // Duplicate the products to ensure we have at least 500
            $products = [];
            while (count($products) < 500) {
                $products = array_merge($products, $baseProducts);
            }
            // Limit to exactly 500 or slightly more to meet requirements without going crazy
            $products = array_slice($products, 0, 500);

            // Ambil kandidat seller dari database (Tipe 0: Customer)
            $kandidatSellers = \App\Models\User::where('type', 0)->inRandomOrder()->pluck('id')->toArray();
            
            // Tiap seller minimal punya 5 produk atau lebih
            $sellerIds = array_slice($kandidatSellers, 0, min(count($kandidatSellers), 100)); // distribute over 100 sellers
            
            // Kelompokan minimal 5 produk ke tiap seller
            $productChunks = array_chunk($products, 5);
            if (count($productChunks) > 1 && count(end($productChunks)) < 5) {
                $lastChunk = array_pop($productChunks);
                $pIndex = count($productChunks) - 1;
                $productChunks[$pIndex] = array_merge($productChunks[$pIndex], $lastChunk);
            }

            foreach ($productChunks as $idx => $chunk) {
                $sellerId = $sellerIds[$idx % count($sellerIds)];

                foreach ($chunk as $product) {
                    // Tentukan kategori berdasarkan nama atau default
                    $kategori = $this->detectCategory($product['name']);

                    // Buat produk
                    $produk = Produk::create([
                        'id_user' => $sellerId,
                        'nama' => $product['name'],
                        'deskripsi' => $product['description'],
                        'status' => 'Tersedia',
                        'kategori' => $kategori,
                    ]);

                    // Kumpulkan semua foto (listing_photo + detail_photos)
                    $allPhotos = [];
                    if (!empty($product['listing_photo'])) {
                        $allPhotos[] = $product['listing_photo'];
                    }
                    if (!empty($product['detail_photos']) && is_array($product['detail_photos'])) {
                        foreach ($product['detail_photos'] as $photoUrl) {
                            if ($photoUrl !== $product['listing_photo']) {
                                $allPhotos[] = $photoUrl;
                            }
                        }
                    }

                    // Jika benar-benar kosong, gunakan placeholder
                    if (empty($allPhotos)) {
                        $allPhotos[] = asset('images/placeholder-image.png');
                    }

                    // Simpan semua foto ke FotoProduk
                    foreach ($allPhotos as $index => $photoUrl) {
                        FotoProduk::create([
                            'id_produk' => $produk->id,
                            'url_foto' => $photoUrl,
                            'tipe_sumber' => PhotoHelper::detectPhotoSource($photoUrl),
                            'urutan' => $index,
                        ]);
                    }
                }
            }
        } else {
            // Jika products.json tidak ditemukan, buat data dummy menggunakan factory
            $sellerIds = \App\Models\User::where('type', 0)->inRandomOrder()->limit(100)->pluck('id');
            foreach ($sellerIds as $sellerId) {
                Produk::factory(5)->create(['id_user' => $sellerId]);
            }

            // Buat foto untuk setiap produk
            Produk::all()->each(function ($produk) {
                FotoProduk::factory(rand(3, 8))
                    ->create([
                        'id_produk' => $produk->id,
                    ]);
            });
        }
    }

    /**
     * Deteksi kategori dari nama produk
     */
    private function detectCategory(string $name): string
    {
        $name = strtolower($name);

        if (preg_match('/tenda|tent|kemah|camp/i', $name)) {
            return 'Tenda';
        } elseif (preg_match('/tas|bag|ransel|backpack/i', $name)) {
            return 'Tas';
        } elseif (preg_match('/sepatu|shoe|boots/i', $name)) {
            return 'Sepatu';
        } elseif (preg_match('/kantong|sleeping|matras|sleeping bag|tidur/i', $name)) {
            return 'Perlengkapan';
        }

        return 'Perlengkapan'; // Default kategori
    }
}

