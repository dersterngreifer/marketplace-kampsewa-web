<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan foreign key referensi varian dan snapshot harga ke tabel detail_penyewaan.
     * id_detail_variant_produk memungkinkan sistem memotong stok secara akurat.
     * harga_sewa_satuan menyimpan harga per hari SAAT transaksi terjadi (snapshot),
     * sehingga laporan keuangan tetap akurat meskipun pemilik toko mengubah harga kemudian.
     */
    public function up(): void
    {
        Schema::table('detail_penyewaan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_detail_variant_produk')->nullable()->after('id_produk')
                ->comment('FK ke detail_variant_produk untuk pemotongan stok yang akurat');
            $table->integer('harga_sewa_satuan')->nullable()->after('subtotal')
                ->comment('Snapshot harga sewa per hari saat transaksi dilakukan');

            $table->foreign('id_detail_variant_produk')
                ->references('id')
                ->on('detail_variant_produk')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_penyewaan', function (Blueprint $table) {
            $table->dropForeign(['id_detail_variant_produk']);
            $table->dropColumn(['id_detail_variant_produk', 'harga_sewa_satuan']);
        });
    }
};
