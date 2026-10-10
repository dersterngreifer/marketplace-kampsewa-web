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
        Schema::create('pemasukan_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('sumber', 100)->nullable(false);
            $table->string('deskripsi', 255)->nullable(false);
            $table->integer('nominal')->nullable(false);
            $table->unsignedBigInteger('id_pembayaran_penyewaan')->nullable()
                  ->comment('Referensi transaksi yang menghasilkan pemasukan ini');
            $table->foreign('id_pembayaran_penyewaan')
                  ->references('id')
                  ->on('pembayaran_penyewaan')
                  ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan_perusahaan');
    }
};
