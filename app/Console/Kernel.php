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
        // Jalankan setiap jam untuk:
        // 1. Menyelesaikan iklan yang sudah melewati tanggal_akhir
        // 2. Mengaktifkan iklan Pending yang tanggal_mulai sudah tercapai (maks 15 aktif)
        // 3. Menghapus otomatis transaksi draft iklan yang belum dibayar > 24 jam (1 hari)
        $schedule->command('app:update-status-iklan')->hourly();
        
        // Membatalkan order yang pending > 2 jam untuk rilis stok
        $schedule->command('orders:cancel-unpaid')->hourly();
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
