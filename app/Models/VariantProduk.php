<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantProduk extends Model
{
    use HasFactory;
    // definisikan table untuk model ini
    protected $table = 'variant_produk';
    // definisikan kolom yang di isi manual
    protected $fillable = [
        'id_produk',
        'warna',
    ];

    public function detailVariants()
    {
        return $this->hasMany(DetailVariantProduk::class, 'id_variant_produk');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
