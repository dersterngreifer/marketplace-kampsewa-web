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
        if (!Schema::hasTable('iklan_suspends')) {
            Schema::create('iklan_suspends', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('id_iklan')->index();
                $table->unsignedBigInteger('id_detail_iklan')->nullable()->index();
                $table->unsignedBigInteger('id_user')->index();
                $table->string('alasan', 255);
                $table->text('bagian_revisi')->nullable(); // JSON: ['poster', 'judul', 'sub_judul', 'deskripsi']
                $table->text('catatan')->nullable();
                $table->string('status_revisi', 50)->default('menunggu_perbaikan')->index(); // menunggu_perbaikan, sudah_diperbaiki, selesai
                $table->timestamps();

                $table->foreign('id_iklan')->references('id')->on('iklan')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iklan_suspends');
    }
};
