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
        Schema::table('promosi_toko', function (Blueprint $table) {
            if (!Schema::hasColumn('promosi_toko', 'konflik_diskon')) {
                $table->string('konflik_diskon', 20)->default('replace')->after('cakupan')->comment('Opsi penanganan konflik saat all_products: replace (samaratakan & timpa diskon lama) atau skip (pertahankan diskon lama)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promosi_toko', function (Blueprint $table) {
            if (Schema::hasColumn('promosi_toko', 'konflik_diskon')) {
                $table->dropColumn('konflik_diskon');
            }
        });
    }
};
