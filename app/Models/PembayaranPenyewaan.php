<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPenyewaan extends Model
{
    use HasFactory;
    protected $table='pembayaran_penyewaan';
    protected $fillable=[
        'id_penyewaan',
        'bukti_pembayaran',
        'jaminan_sewa',
        'jumlah_pembayaran',
        'kembalian_pembayaran',
        'biaya_admin',
        'kurang_pembayaran',
        'total_pembayaran',
        'pajak_platform',
        'persen_pajak',
        'metode',
        'jenis_transaksi',
        'status_pembayaran',
    ];
}
