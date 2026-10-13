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
        Schema::create('pengaturan_pengiriman_toko', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->unique();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('layanan_antar')->default(true)->comment('Apakah toko melayani pengantaran barang');
            $table->unsignedInteger('biaya_antar')->default(0)->comment('Nominal tarif biaya antar dalam rupiah');
            $table->string('keterangan', 500)->nullable()->comment('Keterangan wilayah jangkauan atau syarat pengantaran');
            $table->unsignedInteger('minimal_transaksi_gratis_antar')->default(0)->comment('Minimal transaksi sewa untuk gratis ongkir, 0 jika tidak ada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_pengiriman_toko');
    }
};
