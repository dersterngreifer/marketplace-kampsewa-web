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
        if (Schema::hasTable('pembayaran_iklan') && !Schema::hasColumn('pembayaran_iklan', 'read_at')) {
            Schema::table('pembayaran_iklan', function (Blueprint $table) {
                $table->timestamp('read_at')->nullable()->after('status_bayar');
                $table->index('read_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pembayaran_iklan') && Schema::hasColumn('pembayaran_iklan', 'read_at')) {
            Schema::table('pembayaran_iklan', function (Blueprint $table) {
                $table->dropIndex(['read_at']);
                $table->dropColumn('read_at');
            });
        }
    }
};
