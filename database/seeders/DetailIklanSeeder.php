<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DetailIklanSeeder tidak lagi berjalan standalone.
 * Data detail_iklan dibuat di dalam IklanSeeder agar relasi konsisten.
 *
 * @see IklanSeeder
 */
class DetailIklanSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('ℹ️  DetailIklanSeeder: data dibuat di dalam IklanSeeder (skip).');
    }
}
