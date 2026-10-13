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
        // 1. Penyesuaian tabel promosi_toko untuk murni Diskon Produk Toko (tanpa dependensi poster banner)
        if (Schema::hasTable('promosi_toko')) {
            Schema::table('promosi_toko', function (Blueprint $table) {
                if (!Schema::hasColumn('promosi_toko', 'nama_diskon')) {
                    $table->string('nama_diskon', 150)->nullable()->after('id_user');
                }
                if (Schema::hasColumn('promosi_toko', 'judul_promo')) {
                    $table->string('judul_promo', 150)->nullable()->change();
                }
                if (Schema::hasColumn('promosi_toko', 'poster')) {
                    $table->string('poster', 255)->nullable()->change();
                }
                if (Schema::hasColumn('promosi_toko', 'deskripsi_promo')) {
                    $table->text('deskripsi_promo')->nullable()->change();
                }
            });
        }

        // 2. Penyesuaian tabel iklan untuk fitur Iklan Banner mandiri
        if (Schema::hasTable('iklan')) {
            Schema::table('iklan', function (Blueprint $table) {
                if (!Schema::hasColumn('iklan', 'target_type')) {
                    $table->string('target_type', 50)->default('toko')->after('id_user');
                }
                if (!Schema::hasColumn('iklan', 'id_produk')) {
                    $table->unsignedBigInteger('id_produk')->nullable()->after('target_type');
                }
                if (!Schema::hasColumn('iklan', 'sub_judul')) {
                    $table->string('sub_judul', 200)->nullable()->after('judul');
                }
                if (!Schema::hasColumn('iklan', 'badge_promo')) {
                    $table->string('badge_promo', 100)->nullable()->after('id_produk');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('promosi_toko')) {
            Schema::table('promosi_toko', function (Blueprint $table) {
                if (Schema::hasColumn('promosi_toko', 'nama_diskon')) {
                    $table->dropColumn('nama_diskon');
                }
            });
        }
    }
};
