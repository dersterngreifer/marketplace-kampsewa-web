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
        Schema::table('iklan', function (Blueprint $table) {
            if (!Schema::hasColumn('iklan', 'id_produk')) {
                $table->unsignedBigInteger('id_produk')->nullable()->after('id_user');
                $table->foreign('id_produk')->references('id')->on('produk')->onDelete('set null');
            }
            if (!Schema::hasColumn('iklan', 'target_type')) {
                $table->string('target_type', 20)->default('store')->after('id_produk');
            }
            if (!Schema::hasColumn('iklan', 'badge_promo')) {
                $table->string('badge_promo', 50)->nullable()->after('target_type');
            }
            if (!Schema::hasColumn('iklan', 'tanggal_mulai')) {
                $table->dateTime('tanggal_mulai')->nullable()->after('badge_promo');
            }
            if (!Schema::hasColumn('iklan', 'tanggal_selesai')) {
                $table->dateTime('tanggal_selesai')->nullable()->after('tanggal_mulai');
            }
            if (!Schema::hasColumn('iklan', 'status')) {
                $table->enum('status', ['active', 'inactive', 'expired'])->default('active')->after('tanggal_selesai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iklan', function (Blueprint $table) {
            if (Schema::hasColumn('iklan', 'id_produk')) {
                $table->dropForeign(['id_produk']);
                $table->dropColumn('id_produk');
            }
            $columns = ['target_type', 'badge_promo', 'tanggal_mulai', 'tanggal_selesai', 'status'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('iklan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
