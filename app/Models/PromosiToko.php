<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromosiToko extends Model
{
    use HasFactory;

    protected $table = 'promosi_toko';
    protected $primaryKey = 'id_promo';

    protected $fillable = [
        'id_user',
        'nama_diskon',
        'judul_promo',
        'deskripsi_promo',
        'poster',
        'tipe_diskon',
        'nilai_diskon',
        'cakupan',
        'konflik_diskon',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_banner',
        'is_active',
        'status',
    ];

    public function getNamaDiskonAttribute($value)
    {
        return $value ?: ($this->attributes['judul_promo'] ?? 'Diskon Produk');
    }

    protected $casts = [
        'nilai_diskon'    => 'float',
        'tanggal_mulai'   => 'datetime',
        'tanggal_selesai' => 'datetime',
        'is_banner'       => 'boolean',
        'is_active'       => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function promosiProduk()
    {
        return $this->hasMany(PromosiProduk::class, 'id_promo', 'id_promo');
    }

    public function produk()
    {
        return $this->belongsToMany(Produk::class, 'promosi_produk', 'id_promo', 'id_produk');
    }

    public function scopeActive($query)
    {
        $now = Carbon::now();
        return $query->where('is_active', true)
            ->where('status', 'active')
            ->where('tanggal_mulai', '<=', $now)
            ->where('tanggal_selesai', '>=', $now);
    }
}
