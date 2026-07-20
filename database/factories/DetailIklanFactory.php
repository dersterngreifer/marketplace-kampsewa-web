<?php

namespace Database\Factories;

use App\Models\Iklan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailIklan>
 */
class DetailIklanFactory extends Factory
{
    /**
     * Define the model's default state.
     * Default: iklan Pending (masa depan, belum aktif).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mulai = Carbon::today()->addDays(rand(1, 30));
        $akhir = $mulai->copy()->addDays(rand(7, 30));

        return [
            'id_iklan'      => Iklan::inRandomOrder()->first()?->id ?? Iklan::factory(),
            'tanggal_mulai' => $mulai->toDateString(),
            'tanggal_akhir' => $akhir->toDateString(),
            'harga_iklan'   => $this->faker->randomElement([150000, 200000, 300000, 500000]),
            'status_iklan'  => 'Pending',
        ];
    }

    /**
     * State: iklan Aktif (sedang berjalan hari ini).
     */
    public function aktif(): static
    {
        return $this->state(function (array $attributes) {
            $mulai = Carbon::today()->subDays(rand(1, 10));
            $akhir = Carbon::today()->addDays(rand(5, 20));

            return [
                'tanggal_mulai' => $mulai->toDateString(),
                'tanggal_akhir' => $akhir->toDateString(),
                'status_iklan'  => 'Aktif',
            ];
        });
    }

    /**
     * State: iklan Selesai (sudah berakhir di masa lalu).
     */
    public function selesai(): static
    {
        return $this->state(function (array $attributes) {
            $mulai = Carbon::today()->subDays(rand(30, 90));
            $akhir = Carbon::today()->subDays(rand(1, 29));

            return [
                'tanggal_mulai' => $mulai->toDateString(),
                'tanggal_akhir' => $akhir->toDateString(),
                'status_iklan'  => 'Selesai',
            ];
        });
    }
}
