<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pencatatan pengembalian barang sewaan.
     * Penting untuk marketplace rental karena memungkinkan sistem mencatat:
     * - Kapan barang benar-benar dikembalikan
     * - Kondisi barang saat dikembalikan
     * - Perhitungan dan penagihan denda keterlambatan / kerusakan
     */
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penyewaan');
            $table->foreign('id_penyewaan')
                ->references('id')
                ->on('penyewaan')
                ->onDelete('cascade');

            $table->date('tanggal_kembali_rencana')
                ->comment('Tanggal selesai sewa yang disepakati di awal');
            $table->dateTime('tanggal_kembali_aktual')->nullable()
                ->comment('Tanggal dan jam aktual barang dikembalikan ke toko');

            $table->enum('kondisi_barang', ['baik', 'kotor', 'rusak_ringan', 'rusak_berat', 'hilang'])
                ->default('baik')
                ->comment('Kondisi barang saat dikembalikan oleh penyewa');

            $table->integer('denda')->default(0)
                ->comment('Total nominal denda (keterlambatan + kerusakan/kehilangan)');
            $table->enum('status_denda', ['tidak_ada', 'belum_dibayar', 'lunas'])
                ->default('tidak_ada')
                ->comment('Status pembayaran denda');

            $table->text('catatan')->nullable()
                ->comment('Catatan tambahan dari admin mengenai kondisi pengembalian');
            $table->string('bukti_kondisi')->nullable()
                ->comment('Path/URL foto kondisi barang saat dikembalikan');

            // Admin yang mencatat pengembalian
            $table->unsignedBigInteger('dicatat_oleh')->nullable()
                ->comment('ID admin/pemilik toko yang mencatat proses pengembalian');
            $table->foreign('dicatat_oleh')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
