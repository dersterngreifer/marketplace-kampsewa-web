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
        Schema::table('feedback_messages', function (Blueprint $table) {
            $table->foreignId('admin_id')
                ->nullable()
                ->after('sender_type')
                ->constrained('users')
                ->nullOnDelete();
        });

        // Initialize admin_id for existing messages
        \Illuminate\Support\Facades\DB::table('feedback_messages')
            ->where('sender_type', 'admin')
            ->update(['admin_id' => \Illuminate\Support\Facades\DB::raw('sender_id')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback_messages', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
    }
};
