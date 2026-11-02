<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_populer')->default(false)->after('is_verified')
                ->comment('Flag apakah toko ditampilkan di daftar toko populer landing page');
            $table->string('slogan_toko', 255)->nullable()->after('deskripsi_toko')
                ->comment('Slogan singkat toko / spesialisasi, contoh: Specialist Tenda & Ransel');
            $table->string('badge_toko', 100)->nullable()->after('slogan_toko')
                ->comment('Badge status toko, contoh: Terlaris Minggu Ini, Mitra Utama, Official Brand');
            $table->decimal('rating_toko', 3, 1)->default(5.0)->after('badge_toko')
                ->comment('Rating rata-rata toko untuk display landing page (skala 1-5)');
            $table->unsignedInteger('total_penyewaan_toko')->default(0)->after('rating_toko')
                ->comment('Total transaksi sewa toko yang berhasil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_populer',
                'slogan_toko',
                'badge_toko',
                'rating_toko',
                'total_penyewaan_toko',
            ]);
        });
    }
};
