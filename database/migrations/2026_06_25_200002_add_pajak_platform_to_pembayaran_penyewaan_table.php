<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom pajak/komisi platform ke tabel pembayaran_penyewaan.
     * Admin mendapat penghasilan dari potongan pajak setiap transaksi
     * antara Mitra (pemilik toko) dan Pelanggan (penyewa alat camping).
     * Kolom ini diisi saat transaksi dikonfirmasi.
     */
    public function up(): void
    {
        Schema::table('pembayaran_penyewaan', function (Blueprint $table) {
            $table->unsignedInteger('pajak_platform')->default(0)
                ->after('total_pembayaran')
                ->comment('Nominal pajak/komisi platform yang dihitung dari total_pembayaran');

            $table->decimal('persen_pajak', 5, 2)->default(0)
                ->after('pajak_platform')
                ->comment('Persentase pajak yang diambil platform, misal: 5.00 berarti 5%');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran_penyewaan', function (Blueprint $table) {
            $table->dropColumn(['pajak_platform', 'persen_pajak']);
        });
    }
};
