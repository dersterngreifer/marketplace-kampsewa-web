<?php

namespace App\Console;

use App\Models\DetailIklan;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Jalankan setiap hari pukul 00:05 untuk:
        // 1. Menyelesaikan iklan yang sudah melewati tanggal_akhir
        // 2. Mengaktifkan iklan Pending yang tanggal_mulai sudah tercapai (maks 10 aktif)
        $schedule->command('app:update-status-iklan')->dailyAt('00:05');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
