<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';

    protected $fillable = [
        'id_user',
        'id_produk',
        'id_detail_variant_produk',
        'qty',
    ];

    protected $casts = [
        'qty' => 'integer',
    ];

    /**
     * User pemilik keranjang.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Produk yang ada di keranjang.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    /**
     * Varian produk spesifik (warna + ukuran) yang dipilih.
     */
    public function detailVariant()
    {
        return $this->belongsTo(DetailVariantProduk::class, 'id_detail_variant_produk');
    }
}
