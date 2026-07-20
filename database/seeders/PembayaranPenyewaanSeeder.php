<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * PembayaranPenyewaanSeeder tidak lagi berjalan standalone.
 * Data pembayaran_penyewaan dibuat di dalam PenyewaanSeeder agar relasi konsisten.
 *
 * @see PenyewaanSeeder
 */
class PembayaranPenyewaanSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('ℹ️  PembayaranPenyewaanSeeder: data dibuat di dalam PenyewaanSeeder (skip).');
    }
}
