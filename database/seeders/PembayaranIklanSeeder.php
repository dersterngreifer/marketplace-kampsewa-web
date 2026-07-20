<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * PembayaranIklanSeeder tidak lagi berjalan standalone.
 * Data pembayaran_iklan dibuat di dalam IklanSeeder agar relasi konsisten.
 *
 * @see IklanSeeder
 */
class PembayaranIklanSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('ℹ️  PembayaranIklanSeeder: data dibuat di dalam IklanSeeder (skip).');
    }
}
