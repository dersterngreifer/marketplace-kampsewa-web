<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemasukanPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'pemasukan_perusahaan';
    protected $fillable = [
        'id_user',
        'sumber',
        'deskripsi',
        'nominal',
        'id_pembayaran_penyewaan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pembayaranPenyewaan()
    {
        return $this->belongsTo(PembayaranPenyewaan::class, 'id_pembayaran_penyewaan');
    }
}
