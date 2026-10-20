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
        Schema::table('iklan_suspends', function (Blueprint $table) {
            if (!Schema::hasColumn('iklan_suspends', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('status_revisi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iklan_suspends', function (Blueprint $table) {
            if (Schema::hasColumn('iklan_suspends', 'read_at')) {
                $table->dropColumn('read_at');
            }
        });
    }
};
