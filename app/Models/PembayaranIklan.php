<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranIklan extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_iklan';

    protected $fillable = [
        'id_iklan',
        'id_user',
        'midtrans_order_id',
        'payment_type',
        'midtrans_transaction_status',
        'metode_bayar',
        'total_bayar',
        'status_bayar',
    ];

    public function iklan()
    {
        return $this->belongsTo(Iklan::class, 'id_iklan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
