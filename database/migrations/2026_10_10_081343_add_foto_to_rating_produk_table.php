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
        Schema::table('rating_produk', function (Blueprint $table) {
            $table->string('foto_ulasan')->nullable()->after('ulasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rating_produk', function (Blueprint $table) {
            $table->dropColumn('foto_ulasan');
        });
    }
};

