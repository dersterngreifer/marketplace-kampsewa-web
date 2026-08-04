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
        Schema::table('penyewaan', function (Blueprint $table) {
            // Make id_user nullable for offline orders
            $table->unsignedBigInteger('id_user')->nullable()->change();
            
            // Add new offline columns
            $table->enum('tipe_pesanan', ['online', 'offline'])->default('online')->after('id_user');
            $table->string('nama_pelanggan_offline')->nullable()->after('tipe_pesanan');
            $table->string('no_hp_offline')->nullable()->after('nama_pelanggan_offline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            // Revert id_user to non-nullable (might fail if there are null values)
            $table->unsignedBigInteger('id_user')->nullable(false)->change();
            
            $table->dropColumn('tipe_pesanan');
            $table->dropColumn('nama_pelanggan_offline');
            $table->dropColumn('no_hp_offline');
        });
    }
};
