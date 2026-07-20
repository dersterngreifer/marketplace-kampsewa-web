<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel keranjang belanja sementara sebelum checkout penyewaan.
     * Memungkinkan penyewa memilih beberapa alat kamping dari toko
     * dan menyusunnya terlebih dahulu sebelum membuat order penyewaan.
     */
    public function up(): void
    {
        Schema::create('keranjang', function (Blueprint $table) {
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

            // Nullable karena produk mungkin tidak memiliki varian
            $table->unsignedBigInteger('id_detail_variant_produk')->nullable()
                ->comment('Varian spesifik (warna+ukuran) yang dipilih, null jika produk tidak bervariant');
            $table->foreign('id_detail_variant_produk')
                ->references('id')
                ->on('detail_variant_produk')
                ->onDelete('set null');

            $table->integer('qty')->default(1)
                ->comment('Jumlah item yang ingin disewa');

            $table->timestamps();

            // Satu user tidak bisa menambah produk+varian yang sama dua kali ke keranjang
            $table->unique(['id_user', 'id_produk', 'id_detail_variant_produk'], 'keranjang_user_produk_variant_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
