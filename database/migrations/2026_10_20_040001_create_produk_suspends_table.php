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
        if (!Schema::hasTable('produk_suspends')) {
            Schema::create('produk_suspends', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('id_produk')->index();
                $table->unsignedBigInteger('id_user')->index();
                $table->unsignedBigInteger('id_admin')->nullable()->index();
                $table->string('alasan', 255);
                $table->text('catatan')->nullable();
                $table->string('status_revisi', 50)->default('menunggu_perbaikan')->index(); // 'menunggu_perbaikan', 'sudah_diperbaiki', 'selesai'
                $table->timestamp('read_at')->nullable()->index();
                $table->timestamps();

                $table->foreign('id_produk')->references('id')->on('produk')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_suspends');
    }
};
