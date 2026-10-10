<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => 'Admin ' . $faker->firstName,
                'email' => "admin$i@kampsewa.com",
                'password' => Hash::make('password123'),
                'type' => 1, // 1 for admin
                'nomor_telephone' => $faker->unique()->numerify('08##########'),
                'tanggal_lahir' => $faker->date('Y-m-d', '2000-01-01'),
                'foto' => 'Belum Di isi',
                'status' => null,
                'background' => 'Belum Di isi',
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'time_login' => null,
                'last_login' => null,
                'name_store' => null,
            ]);
        }
    }
}
