<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom Midtrans tracking ke tabel pembayaran_iklan.
     * Karena transaksi Admin-Mitra menggunakan Midtrans (payment gateway),
     * kita perlu menyimpan order_id dari Midtrans agar bisa diverifikasi
     * melalui Midtrans dashboard dan callback.
     */
    public function up(): void
    {
        Schema::table('pembayaran_iklan', function (Blueprint $table) {
            // Order ID unik yang dikirim ke Midtrans saat checkout
            $table->string('midtrans_order_id')->nullable()
                ->after('id_user')
                ->comment('Order ID yang dikirim ke Midtrans untuk tracking transaksi');

            // Metode pembayaran yang dipilih user di Midtrans
            $table->string('payment_type')->nullable()
                ->after('midtrans_order_id')
                ->comment('Metode pembayaran di Midtrans: gopay, credit_card, bca_va, dll');

            // Status transaksi Midtrans yang lebih granular
            $table->string('midtrans_transaction_status')->nullable()
                ->after('payment_type')
                ->comment('Status Midtrans: settlement, pending, deny, expire, cancel');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran_iklan', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'payment_type',
                'midtrans_transaction_status',
            ]);
        });
    }
};
