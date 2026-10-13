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
        if (!Schema::hasTable('promosi_produk')) {
            Schema::create('promosi_produk', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_promo');
                $table->unsignedBigInteger('id_produk');
                $table->timestamps();

                $table->foreign('id_promo')->references('id_promo')->on('promosi_toko')->onDelete('cascade');
                $table->foreign('id_produk')->references('id')->on('produk')->onDelete('cascade');
                $table->unique(['id_promo', 'id_produk'], 'unique_promo_produk');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promosi_produk');
    }
};
