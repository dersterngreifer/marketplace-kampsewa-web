<?php

namespace Database\Factories;

use App\Models\Penyewaan;
use App\Models\Produk;
use App\Models\VariantProduk;
use App\Models\DetailVariantProduk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailPenyewaan>
 */
class DetailPenyewaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $produk         = Produk::inRandomOrder()->first();
        $idProduk       = $produk?->id ?? Produk::factory();

        // Ambil detail variant produk jika ada
        $detailVariant  = DetailVariantProduk::inRandomOrder()->first();
        $idDetailVariant = $detailVariant?->id;

        $warna          = $detailVariant?->warna ?? $this->faker->colorName();
        $ukuran         = $detailVariant?->ukuran ?? $this->faker->randomElement(['S', 'M', 'L', 'XL']);
        $hargaSatuan    = $this->faker->randomElement([50000, 75000, 100000, 150000, 200000]);
        $qty            = rand(1, 3);
        $subtotal       = $hargaSatuan * $qty;

        return [
            'id_penyewaan'             => Penyewaan::inRandomOrder()->first()?->id ?? Penyewaan::factory(),
            'id_produk'                => $idProduk,
            'id_detail_variant_produk' => $idDetailVariant,
            'warna_produk'             => $warna,
            'ukuran'                   => $ukuran,
            'qty'                      => $qty,
            'harga_sewa_satuan'        => $hargaSatuan,
            'subtotal'                 => $subtotal,
            'created_at'               => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
