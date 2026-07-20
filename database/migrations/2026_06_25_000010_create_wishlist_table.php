<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel wishlist / favorit produk pengguna.
     * Memungkinkan penyewa menandai alat kamping yang ingin mereka sewa di lain waktu,
     * dan membantu pemilik toko memahami produk mana yang paling banyak diminati.
     */
    public function up(): void
    {
        Schema::create('wishlist', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_produk');
            $table->foreign('id_produk')
                ->references('id')
                ->on('produk')
                ->onDelete('cascade');

            $table->timestamps();

            // Satu user tidak bisa mewishlist produk yang sama lebih dari satu kali
            $table->unique(['id_user', 'id_produk'], 'wishlist_user_produk_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist');
    }
};
