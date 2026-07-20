<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel master kategori produk yang bisa dikelola secara dinamis oleh admin.
     * Menggantikan kolom `kategori` (string) di tabel produk dengan relasi FK
     * sehingga admin bisa menambah/mengubah/menghapus kategori tanpa perlu
     * mengubah data produk satu per satu.
     *
     * NOTE: Kolom `kategori` (string) di tabel produk TIDAK dihapus untuk menjaga
     * kompatibilitas mundur. Migrasi data dari kolom lama ke kolom id_kategori_produk
     * dilakukan secara manual atau via seeder terpisah.
     */
    public function up(): void
    {
        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100)->unique()
                ->comment('Nama tampilan kategori, contoh: Tenda Dome, Sleeping Bag');
            $table->string('slug', 100)->unique()
                ->comment('URL-friendly version dari nama, contoh: tenda-dome, sleeping-bag');
            $table->string('icon', 50)->nullable()
                ->comment('Nama class icon (FontAwesome/Phosphor), contoh: fa-tent');
            $table->integer('urutan')->default(0)
                ->comment('Urutan tampilan kategori di halaman listing (ascending)');
            $table->timestamps();
        });

        // Tambahkan kolom id_kategori_produk ke tabel produk sebagai referensi FK baru
        // Kolom kategori (string) lama tetap ada untuk kompatibilitas mundur
        Schema::table('produk', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kategori_produk')->nullable()->after('kategori')
                ->comment('FK ke tabel kategori_produk (sistem kategori baru yang dinamis)');
            $table->foreign('id_kategori_produk')
                ->references('id')
                ->on('kategori_produk')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign(['id_kategori_produk']);
            $table->dropColumn('id_kategori_produk');
        });

        Schema::dropIfExists('kategori_produk');
    }
};
