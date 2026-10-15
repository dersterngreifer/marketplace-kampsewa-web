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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['wisata', 'berita'])->index();
            $table->string('judul', 255);
            $table->string('slug', 255)->nullable()->index();
            $table->text('gambar')->nullable();
            $table->text('deskripsi_singkat')->nullable();
            $table->longText('konten')->nullable();
            $table->string('lokasi', 255)->nullable()->index();
            $table->text('sumber_url')->nullable();
            $table->string('sumber_nama', 100)->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['published', 'draft', 'archived'])->default('published')->index();
            $table->unsignedInteger('views_count')->default(0)->index();
            $table->timestamps();

            // Composite indexes for fast sorting and filtering
            $table->index(['tipe', 'status', 'created_at']);
            $table->index(['tipe', 'status', 'views_count']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
