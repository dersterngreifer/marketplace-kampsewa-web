<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    use HasFactory;
    protected $table='penyewaan';
    protected $fillable=[
        'id_user',
        'tipe_pesanan',
        'nama_pelanggan_offline',
        'no_hp_offline',
        'tanggal_mulai',
        'tanggal_selesai',
        'pesan',
        'opsi_pengiriman',
        'alamat_pengiriman',
        'status_penyewaan',
    ];

    public function pembayaran()
    {
        return $this->hasOne(PembayaranPenyewaan::class, 'id_penyewaan');
    }

    public function details()
    {
        return $this->hasMany(DetailPenyewaan::class, 'id_penyewaan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
