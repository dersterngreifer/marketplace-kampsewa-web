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
        Schema::table('pemasukan_perusahaan', function (Blueprint $table) {
            if (!Schema::hasColumn('pemasukan_perusahaan', 'id_user')) {
                $table->unsignedBigInteger('id_user')->nullable()->after('id')
                    ->comment('Admin kampsewa yang menginputkan data pemasukan manual');
                $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
            }
        });

        Schema::table('pengeluaran_perusahaan', function (Blueprint $table) {
            if (!Schema::hasColumn('pengeluaran_perusahaan', 'id_user')) {
                $table->unsignedBigInteger('id_user')->nullable()->after('id')
                    ->comment('Admin kampsewa yang menginputkan data pengeluaran');
                $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasukan_perusahaan', function (Blueprint $table) {
            if (Schema::hasColumn('pemasukan_perusahaan', 'id_user')) {
                $table->dropForeign(['id_user']);
                $table->dropColumn('id_user');
            }
        });

        Schema::table('pengeluaran_perusahaan', function (Blueprint $table) {
            if (Schema::hasColumn('pengeluaran_perusahaan', 'id_user')) {
                $table->dropForeign(['id_user']);
                $table->dropColumn('id_user');
            }
        });
    }
};
