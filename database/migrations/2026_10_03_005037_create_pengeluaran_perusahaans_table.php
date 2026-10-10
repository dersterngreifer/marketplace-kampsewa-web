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
        Schema::create('pengeluaran_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('sumber', 100)->nullable(false);
            $table->string('deskripsi', 255)->nullable(false);
            $table->integer('nominal')->nullable(false);
            $table->string('kategori_pengeluaran', 100)->nullable()
                  ->comment('Label kategori pengeluaran (operasional, gaji, marketing, dll)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_perusahaan');
    }
};
