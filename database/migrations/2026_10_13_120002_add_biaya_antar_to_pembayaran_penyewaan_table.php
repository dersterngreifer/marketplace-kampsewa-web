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
        if (Schema::hasTable('pembayaran_penyewaan') && !Schema::hasColumn('pembayaran_penyewaan', 'biaya_antar')) {
            Schema::table('pembayaran_penyewaan', function (Blueprint $table) {
                $table->unsignedInteger('biaya_antar')->default(0)->after('biaya_admin')->comment('Biaya pengantaran barang ke pelanggan');
            });
        }

        if (Schema::hasTable('penyewaan')) {
            Schema::table('penyewaan', function (Blueprint $table) {
                if (!Schema::hasColumn('penyewaan', 'opsi_pengiriman')) {
                    $table->string('opsi_pengiriman', 50)->default('Ambil Ditempat')->after('pesan')->comment('Opsi pengiriman: Ambil Ditempat / Antar Ke Alamatmu');
                }
                if (!Schema::hasColumn('penyewaan', 'alamat_pengiriman')) {
                    $table->text('alamat_pengiriman')->nullable()->after('opsi_pengiriman')->comment('Alamat tujuan pengantaran jika opsi diantar dipilih');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pembayaran_penyewaan') && Schema::hasColumn('pembayaran_penyewaan', 'biaya_antar')) {
            Schema::table('pembayaran_penyewaan', function (Blueprint $table) {
                $table->dropColumn('biaya_antar');
            });
        }

        if (Schema::hasTable('penyewaan')) {
            Schema::table('penyewaan', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('penyewaan', 'opsi_pengiriman')) {
                    $columns[] = 'opsi_pengiriman';
                }
                if (Schema::hasColumn('penyewaan', 'alamat_pengiriman')) {
                    $columns[] = 'alamat_pengiriman';
                }
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
