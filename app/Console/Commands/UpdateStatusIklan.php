<?php

namespace App\Console\Commands;

use App\Services\IklanService;
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
    protected $description = 'Auto-update status iklan: selesaikan yang kadaluarsa, batasi maks 15 aktif, aktifkan antrian pending';

    /**
     * Batas maksimal iklan yang boleh aktif bersamaan.
     */
    const MAX_IKLAN_AKTIF = 15;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->line('⏳ Memulai sinkronisasi otomatis status iklan...');

        $result = IklanService::autoSyncStatusIklan();

        if ($result['expired_count'] > 0) {
            $this->info("✅ {$result['expired_count']} iklan diselesaikan otomatis (masa tayang berakhir).");
        }

        if ($result['demoted_count'] > 0) {
            $this->warn("⚠️  {$result['demoted_count']} iklan dialihkan ke antrian Pending (melebihi kapasitas maksimal 15 slot).");
        }

        if ($result['promoted_count'] > 0) {
            $this->info("🚀 {$result['promoted_count']} iklan antrian berhasil diaktifkan ke slot yang tersedia.");
        }

        if (!empty($result['deleted_unpaid_count']) && $result['deleted_unpaid_count'] > 0) {
            $this->info("🗑️  {$result['deleted_unpaid_count']} draft transaksi iklan yang belum dibayar > 24 jam berhasil dibersihkan otomatis.");
        }

        $this->line("ℹ️  Status saat ini: {$result['total_active']} / {$result['max_slot']} iklan aktif, {$result['total_pending']} iklan dalam antrian pending.");

        return self::SUCCESS;
    }
}
