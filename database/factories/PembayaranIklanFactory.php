<?php

namespace Database\Factories;

use App\Models\Iklan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PembayaranIklan>
 */
class PembayaranIklanFactory extends Factory
{
    /**
     * Define the model's default state.
     * Default: sudah lunas (settlement) via transfer bank.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $iklan        = Iklan::inRandomOrder()->first();
        $idIklan      = $iklan?->id ?? Iklan::factory();
        $idUser       = $iklan?->id_user ?? User::where('type', 0)->inRandomOrder()->first()?->id ?? User::factory();

        $metodeBayar  = $this->faker->randomElement(['gopay', 'bca_va', 'bni_va', 'mandiri_va', 'credit_card']);
        $totalBayar   = $this->faker->randomElement([150000, 200000, 300000, 500000]);

        return [
            'id_iklan'                    => $idIklan,
            'id_user'                     => $idUser,
            'midtrans_order_id'           => 'IKLAN-' . strtoupper(Str::random(8)) . '-' . now()->format('YmdHis'),
            'payment_type'                => $metodeBayar,
            'midtrans_transaction_status' => 'settlement',
            'metode_bayar'                => $metodeBayar,
            'total_bayar'                 => $totalBayar,
            'status_bayar'                => 'lunas',
        ];
    }

    /**
     * State: pembayaran pending (belum diselesaikan).
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'midtrans_transaction_status' => 'pending',
            'status_bayar'                => 'belum lunas',
        ]);
    }
}
