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
        Schema::create('landing_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section', 50)->index()
                ->comment('hero, about, about_feature, keunggulan, alur_sewa, cta, stats');
            $table->string('key', 50)->nullable()->index()
                ->comment('Kunci identifier unik per section');
            $table->string('title', 255)->nullable();
            $table->string('subtitle', 255)->nullable();
            $table->text('content')->nullable();
            $table->string('badge', 100)->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('icon_bg', 50)->nullable()
                ->comment('Kelas background icon, contoh: bg-light-emerald, bg-light-amber');
            $table->string('image', 255)->nullable();
            $table->string('link_url', 255)->nullable();
            $table->string('link_text', 100)->nullable();
            $table->string('step_number', 10)->nullable()
                ->comment('Contoh: 01, 02, 03, 04');
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_contents');
    }
};
