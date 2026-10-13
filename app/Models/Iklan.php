<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iklan extends Model
{
    use HasFactory;

    protected $table = 'iklan';

    protected $fillable = [
        'id_user',
        'id_produk',
        'target_type',
        'badge_promo',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'poster',
        'judul',
        'sub_judul',
        'deskripsi',
        'snap_token',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function detailIklan()
    {
        return $this->hasMany(DetailIklan::class, 'id_iklan');
    }

    public function latestDetail()
    {
        return $this->hasOne(DetailIklan::class, 'id_iklan')->latestOfMany();
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranIklan::class, 'id_iklan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
