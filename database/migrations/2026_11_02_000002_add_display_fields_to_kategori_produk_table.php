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
        Schema::table('kategori_produk', function (Blueprint $table) {
            $table->string('gambar', 255)->nullable()->after('icon')
                ->comment('Nama file gambar atau URL ilustrasi kategori di landing page');
            $table->string('deskripsi_singkat', 255)->nullable()->after('gambar')
                ->comment('Subjudul singkat kategori, contoh: Dome, Tunnel, Glamping');
            $table->string('jumlah_pilihan', 50)->nullable()->after('deskripsi_singkat')
                ->comment('Badge estimasi jumlah pilihan, contoh: 120+ Pilihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_produk', function (Blueprint $table) {
            $table->dropColumn([
                'gambar',
                'deskripsi_singkat',
                'jumlah_pilihan',
            ]);
        });
    }
};
