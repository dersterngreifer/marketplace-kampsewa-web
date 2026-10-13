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
        if (!Schema::hasTable('promosi_toko')) {
            Schema::create('promosi_toko', function (Blueprint $table) {
                $table->id('id_promo');
                $table->unsignedBigInteger('id_user');
                $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
                $table->string('judul_promo', 150);
                $table->text('deskripsi_promo')->nullable();
                $table->string('poster', 255)->nullable();
                $table->enum('tipe_diskon', ['persen', 'nominal'])->default('persen');
                $table->decimal('nilai_diskon', 10, 2);
                $table->enum('cakupan', ['all_products', 'selected_products'])->default('all_products');
                $table->dateTime('tanggal_mulai');
                $table->dateTime('tanggal_selesai');
                $table->boolean('is_banner')->default(false);
                $table->boolean('is_active')->default(true);
                $table->enum('status', ['active', 'draft', 'inactive'])->default('active');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promosi_toko');
    }
};
