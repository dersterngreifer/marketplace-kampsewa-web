<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah status produk dari ENUM terbatas menjadi VARCHAR(50) agar mendukung status 'Ditangguhkan'
        DB::statement("ALTER TABLE `produk` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'Tersedia'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `produk` MODIFY COLUMN `status` ENUM('Tersedia', 'Tidak Tersedia') NOT NULL DEFAULT 'Tersedia'");
    }
};
