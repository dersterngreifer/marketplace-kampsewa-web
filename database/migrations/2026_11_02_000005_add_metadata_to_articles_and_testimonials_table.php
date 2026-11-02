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
        // 1. Tambah metadata ke tabel articles untuk filter destinasi wisata dinamis
        Schema::table('articles', function (Blueprint $table) {
            $table->string('kategori_wisata', 100)->nullable()->after('lokasi')
                ->comment('Kategori wisata: Gunung & Hiking, Pantai & Pulau, Danau & Air Terjun, Hutan & Taman Nasional, Glamping & Camp Area');
            $table->string('wilayah', 100)->nullable()->after('kategori_wisata')
                ->comment('Wilayah: Jawa Barat, Jawa Tengah & DIY, Jawa Timur, Bali & Nusa Tenggara, Sumatera, Sulawesi & Timur');
            $table->decimal('rating_wisata', 3, 1)->default(4.9)->after('wilayah')
                ->comment('Rating destinasi wisata skala 1-5');
            $table->boolean('is_recommended')->default(false)->after('rating_wisata')
                ->comment('Flag destinasi rekomendasi unggulan');
        });

        // 2. Tambah kota ke tabel testimonials
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('kota', 100)->nullable()->after('peran')
                ->comment('Kota atau domisili pemberi testimoni, contoh: Lumajang, Jawa Timur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'kategori_wisata',
                'wilayah',
                'rating_wisata',
                'is_recommended',
            ]);
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('kota');
        });
    }
};
