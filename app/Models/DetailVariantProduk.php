<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailVariantProduk extends Model
{
    use HasFactory;
     // definisikan table untuk model ini
     protected $table = 'detail_variant_produk';
     // definisikan kolom yang di isi manual
     protected $fillable = [
         'id_variant_produk',
         'ukuran',
         'stok',
         'harga_sewa',
     ];

     public function variant()
     {
         return $this->belongsTo(VariantProduk::class, 'id_variant_produk');
     }
}
