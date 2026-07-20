<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penyewaan>
 */
class PenyewaanFactory extends Factory
{
    /**
     * Define the model's default state.
     * Default: penyewaan selesai (masa lalu).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mulai    = Carbon::today()->subDays(rand(30, 120));
        $selesai  = $mulai->copy()->addDays(rand(3, 14));

        return [
            'id_user'          => User::where('type', 0)->inRandomOrder()->first()?->id ?? User::factory(),
            'tanggal_mulai'    => $mulai->toDateString(),
            'tanggal_selesai'  => $selesai->toDateString(),
            'pesan'            => $this->faker->sentence(10),
            'status_penyewaan' => 'selesai',
            'created_at'       => $mulai->copy()->subDays(rand(1, 7)),
        ];
    }

    /**
     * State: penyewaan sedang berlangsung.
     */
    public function berlangsung(): static
    {
        return $this->state(function () {
            $mulai   = Carbon::today()->subDays(rand(1, 5));
            $selesai = Carbon::today()->addDays(rand(2, 10));

            return [
                'tanggal_mulai'    => $mulai->toDateString(),
                'tanggal_selesai'  => $selesai->toDateString(),
                'status_penyewaan' => 'berlangsung',
                'created_at'       => $mulai->copy()->subDays(rand(1, 3)),
            ];
        });
    }

    /**
     * State: penyewaan pending (belum dimulai).
     */
    public function pending(): static
    {
        return $this->state(function () {
            $mulai   = Carbon::today()->addDays(rand(1, 14));
            $selesai = $mulai->copy()->addDays(rand(3, 10));

            return [
                'tanggal_mulai'    => $mulai->toDateString(),
                'tanggal_selesai'  => $selesai->toDateString(),
                'status_penyewaan' => 'pending',
                'created_at'       => now()->subDays(rand(0, 2)),
            ];
        });
    }
}
