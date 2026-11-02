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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('peran', 100)->nullable()
                ->comment('Contoh: Pendaki Gunung Rinjani, Family Camper Jember, Koordinator Mapala');
            $table->string('avatar', 255)->nullable()
                ->comment('Foto profil pemberi testimoni');
            $table->unsignedTinyInteger('rating')->default(5)
                ->comment('Rating bintang 1-5');
            $table->text('ulasan')
                ->comment('Teks ulasan testimoni');
            $table->boolean('is_active')->default(true)
                ->comment('Status tampil di landing page');
            $table->integer('urutan')->default(0)
                ->comment('Urutan tampil di landing page');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
