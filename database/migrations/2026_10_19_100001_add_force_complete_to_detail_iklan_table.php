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
        if (Schema::hasTable('detail_iklan')) {
            Schema::table('detail_iklan', function (Blueprint $table) {
                if (!Schema::hasColumn('detail_iklan', 'is_force_completed')) {
                    $table->boolean('is_force_completed')->default(false)->after('status_iklan')->index();
                }
                if (!Schema::hasColumn('detail_iklan', 'force_complete_alasan')) {
                    $table->string('force_complete_alasan', 255)->nullable()->after('is_force_completed');
                }
                if (!Schema::hasColumn('detail_iklan', 'force_complete_catatan')) {
                    $table->text('force_complete_catatan')->nullable()->after('force_complete_alasan');
                }
                if (!Schema::hasColumn('detail_iklan', 'force_completed_at')) {
                    $table->timestamp('force_completed_at')->nullable()->after('force_complete_catatan');
                }
            });
        }

        if (!Schema::hasTable('iklan_force_completes')) {
            Schema::create('iklan_force_completes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('id_iklan')->index();
                $table->unsignedBigInteger('id_detail_iklan')->nullable()->index();
                $table->unsignedBigInteger('id_user')->index();
                $table->string('alasan', 255);
                $table->text('catatan')->nullable();
                $table->string('status', 50)->default('selesai_paksa')->index(); // selesai_paksa, direaktifkan
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
        Schema::dropIfExists('iklan_force_completes');

        if (Schema::hasTable('detail_iklan')) {
            Schema::table('detail_iklan', function (Blueprint $table) {
                $columns = ['is_force_completed', 'force_complete_alasan', 'force_complete_catatan', 'force_completed_at'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('detail_iklan', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
