<?php

namespace Database\Seeders;

use App\Models\DetailVariantProduk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailVariantProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = \App\Models\VariantProduk::all();
        
        foreach ($variants as $variant) {
            \App\Models\DetailVariantProduk::factory()->create([
                'id_variant_produk' => $variant->id,
            ]);
        }
    }
}
