<?php

namespace App\Console\Commands;

use App\Models\DetailIklan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateStatusIklan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-status-iklan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-update status iklan: selesaikan yang kadaluarsa, aktifkan pending sesuai slot (maks 10 aktif)';

    /**
     * Batas maksimal iklan yang boleh aktif bersamaan.
     */
    const MAX_IKLAN_AKTIF = 10;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::today();

        // ----------------------------------------------------------------
        // STEP 1: Selesaikan iklan yang sudah melewati tanggal_akhir
        // ----------------------------------------------------------------
        $selesai = DetailIklan::where('status_iklan', 'Aktif')
            ->whereDate('tanggal_akhir', '<', $today)
            ->update(['status_iklan' => 'Selesai']);

        if ($selesai > 0) {
            $this->info("✅ {$selesai} iklan diselesaikan (tanggal_akhir sudah lewat).");
        }

        // ----------------------------------------------------------------
        // STEP 2: Hitung slot aktif yang tersisa
        // ----------------------------------------------------------------
        $jumlahAktif   = DetailIklan::where('status_iklan', 'Aktif')->count();
        $slotTersedia  = self::MAX_IKLAN_AKTIF - $jumlahAktif;

        $this->line("ℹ️  Iklan aktif saat ini: {$jumlahAktif} / " . self::MAX_IKLAN_AKTIF);

        if ($slotTersedia <= 0) {
            $this->info('ℹ️  Slot iklan aktif sudah penuh (10/10). Tidak ada yang diaktifkan.');
            return self::SUCCESS;
        }

        // ----------------------------------------------------------------
        // STEP 3: Aktifkan iklan Pending yang tanggal_mulai ≤ hari ini
        //         Prioritas FIFO: diurutkan berdasarkan created_at terlama
        //         Maksimal sebanyak slot yang tersedia
        // ----------------------------------------------------------------
        $diaktifkan = DetailIklan::where('status_iklan', 'Pending')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_akhir', '>=', $today)
            ->orderBy('created_at', 'asc')
            ->limit($slotTersedia)
            ->pluck('id');

        if ($diaktifkan->isEmpty()) {
            $this->info('ℹ️  Tidak ada iklan Pending yang memenuhi syarat untuk diaktifkan.');
            return self::SUCCESS;
        }

        DetailIklan::whereIn('id', $diaktifkan)->update(['status_iklan' => 'Aktif']);

        $this->info("🚀 {$diaktifkan->count()} iklan berhasil diaktifkan (slot tersedia: {$slotTersedia}).");

        return self::SUCCESS;
    }
}
