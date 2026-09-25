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
            $products = json_decode($json, true);

            // Ambil kandidat seller dari database (Tipe 0: Customer)
            $kandidatSellers = \App\Models\User::where('type', 0)->inRandomOrder()->pluck('id')->toArray();
            $maksimalSellers = floor(count($products) / 5);
            $sellerIds = array_slice($kandidatSellers, 0, $maksimalSellers > 0 ? $maksimalSellers : 1);

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

                    // Tambahkan foto detail
                    if (isset($product['detail_photos']) && is_array($product['detail_photos'])) {
                        foreach ($product['detail_photos'] as $index => $photoUrl) {
                            FotoProduk::create([
                                'id_produk' => $produk->id,
                                'url_foto' => $photoUrl,
                                'tipe_sumber' => PhotoHelper::detectPhotoSource($photoUrl),
                                'urutan' => $index,
                            ]);
                        }
                    }

                    // Jika tidak ada detail photos, minimal tambahkan foto listing
                    if (!isset($product['detail_photos']) || empty($product['detail_photos'])) {
                        FotoProduk::create([
                            'id_produk' => $produk->id,
                            'url_foto' => $product['listing_photo'],
                            'tipe_sumber' => PhotoHelper::detectPhotoSource($product['listing_photo']),
                            'urutan' => 0,
                        ]);
                    }
                }
            }
        } else {
            // Jika products.json tidak ditemukan, buat data dummy menggunakan factory
            $sellerIds = \App\Models\User::where('type', 0)->inRandomOrder()->limit(10)->pluck('id');
            foreach ($sellerIds as $sellerId) {
                Produk::factory(rand(5, 10))->create(['id_user' => $sellerId]);
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

