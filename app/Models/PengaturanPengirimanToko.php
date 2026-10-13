<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanPengirimanToko extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_pengiriman_toko';

    protected $fillable = [
        'id_user',
        'layanan_antar',
        'biaya_antar',
        'keterangan',
        'minimal_transaksi_gratis_antar',
    ];

    protected $casts = [
        'layanan_antar' => 'boolean',
        'biaya_antar' => 'integer',
        'minimal_transaksi_gratis_antar' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
