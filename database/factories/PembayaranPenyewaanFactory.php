<?php

namespace Database\Factories;

use App\Models\Penyewaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PembayaranPenyewaan>
 */
class PembayaranPenyewaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $penyewaan      = Penyewaan::inRandomOrder()->first();
        $idPenyewaan    = $penyewaan?->id ?? Penyewaan::factory();

        $hargaSatuan    = $this->faker->randomElement([50000, 75000, 100000, 150000, 200000]);
        $qty            = rand(1, 5);
        $hari           = rand(2, 7);
        $jumlah         = $hargaSatuan * $qty * $hari;
        $biayaAdmin     = 10000;
        $jaminan        = $this->faker->randomElement([50000, 100000, 150000, 200000]);
        $total          = $jumlah + $biayaAdmin;
        $persenPajak    = 5.00;   // 5% komisi platform
        $pajakPlatform  = (int) round($total * $persenPajak / 100);

        return [
            'id_penyewaan'         => $idPenyewaan,
            'bukti_pembayaran'     => 'payments/bukti_' . $this->faker->numerify('######') . '.jpg',
            'jaminan_sewa'         => $jaminan,
            'jumlah_pembayaran'    => $jumlah,
            'kembalian_pembayaran' => 0,
            'biaya_admin'          => $biayaAdmin,
            'kurang_pembayaran'    => 0,
            'total_pembayaran'     => $total,
            'pajak_platform'       => $pajakPlatform,
            'persen_pajak'         => $persenPajak,
            'metode'               => $this->faker->randomElement(['transfer', 'bayar_ditempat']),
            'jenis_transaksi'      => $this->faker->randomElement(['ambil ditempat', 'antar ditempat']),
            'status_pembayaran'    => 'lunas',
            'created_at'           => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * State: belum lunas (untuk penyewaan pending/berlangsung).
     */
    public function belumLunas(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_pembayaran' => 'Belum lunas',
            'kembalian_pembayaran' => 0,
        ]);
    }
}
