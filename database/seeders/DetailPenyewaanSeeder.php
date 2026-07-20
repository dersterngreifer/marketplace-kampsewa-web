<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DetailPenyewaanSeeder tidak lagi berjalan standalone.
 * Data detail_penyewaan dibuat di dalam PenyewaanSeeder agar relasi konsisten.
 *
 * @see PenyewaanSeeder
 */
class DetailPenyewaanSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('ℹ️  DetailPenyewaanSeeder: data dibuat di dalam PenyewaanSeeder (skip).');
    }
}
