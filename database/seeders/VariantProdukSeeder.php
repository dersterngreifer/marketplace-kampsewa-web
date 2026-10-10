<?php

namespace Database\Seeders;

use App\Models\VariantProduk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VariantProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = \App\Models\Produk::all();
        
        foreach ($products as $product) {
            \App\Models\VariantProduk::factory()->create([
                'id_produk' => $product->id,
            ]);
        }
    }
}
