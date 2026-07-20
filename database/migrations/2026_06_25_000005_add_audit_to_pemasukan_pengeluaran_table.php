<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan referensi FK pembayaran ke tabel pemasukan dan
     * kolom kategori ke tabel pengeluaran untuk audit trail keuangan.
     */
    public function up(): void
    {
        // Tambah referensi ke pembayaran_penyewaan di tabel pemasukan
        Schema::table('pemasukan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pembayaran_penyewaan')->nullable()->after('nominal')
                ->comment('Referensi transaksi yang menghasilkan pemasukan ini');

            $table->foreign('id_pembayaran_penyewaan')
                ->references('id')
                ->on('pembayaran_penyewaan')
                ->onDelete('set null');
        });

        // Tambah kolom kategori pengeluaran di tabel pengeluaran
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->string('kategori_pengeluaran', 100)->nullable()->after('nominal')
                ->comment('Label kategori pengeluaran (operasional, gaji, marketing, dll)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            $table->dropForeign(['id_pembayaran_penyewaan']);
            $table->dropColumn('id_pembayaran_penyewaan');
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropColumn('kategori_pengeluaran');
        });
    }
};
