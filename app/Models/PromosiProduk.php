<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromosiProduk extends Model
{
    use HasFactory;

    protected $table = 'promosi_produk';

    protected $fillable = [
        'id_promo',
        'id_produk',
    ];

    public function promo()
    {
        return $this->belongsTo(PromosiToko::class, 'id_promo', 'id_promo');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }
}
