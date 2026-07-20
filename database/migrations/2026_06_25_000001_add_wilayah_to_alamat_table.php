<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom wilayah administratif ke tabel alamat.
     * Berguna untuk fitur filter rental berdasarkan kota/provinsi.
     */
    public function up(): void
    {
        Schema::table('alamat', function (Blueprint $table) {
            $table->string('kecamatan', 100)->nullable()->after('detail_lainnya');
            $table->string('kota_kabupaten', 100)->nullable()->after('kecamatan');
            $table->string('provinsi', 100)->nullable()->after('kota_kabupaten');
            $table->string('kode_pos', 10)->nullable()->after('provinsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alamat', function (Blueprint $table) {
            $table->dropColumn(['kecamatan', 'kota_kabupaten', 'provinsi', 'kode_pos']);
        });
    }
};
