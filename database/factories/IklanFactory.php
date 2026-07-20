<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Iklan>
 */
class IklanFactory extends Factory
{
    /**
     * Cache URL poster dari public/posters.json.
     */
    protected static ?array $posters = null;

    /**
     * Ambil array URL poster (lazy load).
     */
    protected static function getPosters(): array
    {
        if (static::$posters === null) {
            $path = public_path('posters.json');
            static::$posters = file_exists($path)
                ? json_decode(file_get_contents($path), true)
                : [];
        }

        return static::$posters;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $posters = static::getPosters();
        $poster  = !empty($posters)
            ? $posters[array_rand($posters)]
            : $this->faker->imageUrl(1280, 720, 'camping');

        $judulOptions = [
            'Promo Tenda Dome Murah Berkualitas',
            'Sewa Alat Camping Lengkap',
            'Diskon Sleeping Bag Premium',
            'Paket Camping Keluarga Hemat',
            'Promo Akhir Tahun Alat Outdoor',
            'Flash Sale Perlengkapan Camping',
            'Rental Carrier Backpack Terbaik',
            'Promo Spesial Tenda Gunung',
            'Paket Sewa Lengkap Weekend Camping',
            'Diskon Besar Peralatan Hiking',
        ];

        return [
            'id_user'   => User::where('type', 0)->inRandomOrder()->first()?->id ?? User::factory(),
            'poster'    => $poster,
            'judul'     => $this->faker->randomElement($judulOptions),
            'sub_judul' => $this->faker->sentence(6),
            'deskripsi' => $this->faker->paragraph(3),
        ];
    }
}
