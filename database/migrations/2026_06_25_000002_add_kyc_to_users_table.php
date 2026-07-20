<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom KYC (verifikasi identitas) dan profil toko ke tabel users.
     * Kolom KYC meningkatkan keamanan platform rental (mengurangi risiko kehilangan barang).
     * Kolom profil toko memungkinkan toko tampil lebih lengkap di halaman publik.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verifikasi identitas (KYC)
            $table->string('nomor_identitas', 20)->nullable()->after('name_store')
                ->comment('NIK KTP atau nomor identitas lainnya');
            $table->string('foto_identitas')->nullable()->after('nomor_identitas')
                ->comment('Path/URL foto KTP atau identitas pengguna');
            $table->boolean('is_verified')->default(false)->after('foto_identitas')
                ->comment('Status verifikasi identitas oleh admin');

            // Profil toko
            $table->text('deskripsi_toko')->nullable()->after('is_verified')
                ->comment('Deskripsi singkat profil toko penyewa');
            $table->string('banner_toko')->nullable()->after('deskripsi_toko')
                ->comment('Path/URL gambar banner header profil toko');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_identitas',
                'foto_identitas',
                'is_verified',
                'deskripsi_toko',
                'banner_toko',
            ]);
        });
    }
};
