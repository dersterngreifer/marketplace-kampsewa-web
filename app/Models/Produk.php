<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'id_user',
        'nama',
        'deskripsi',
        'status',
        'kategori',
    ];

    /**
     * Get all photos for the product
     */
    public function foto()
    {
        return $this->hasMany(FotoProduk::class, 'id_produk')->orderBy('urutan', 'asc');
    }

    /**
     * Get the first photo (thumbnail)
     */
    public function photoThumbnail()
    {
        return $this->hasOne(FotoProduk::class, 'id_produk')
            ->orderBy('urutan', 'asc');
    }

    /**
     * Get all likes for this product
     */
    public function likes()
    {
        return $this->hasMany(ProdukLike::class, 'id_produk');
    }

    /**
     * Get user that owns this product
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get promo associations for this product
     */
    public function promosiProduk()
    {
        return $this->hasMany(PromosiProduk::class, 'id_produk');
    }

    /**
     * Get promotions linked to this product
     */
    public function promosi()
    {
        return $this->belongsToMany(PromosiToko::class, 'promosi_produk', 'id_produk', 'id_promo');
    }

    /**
     * Get variant relations
     */
    public function variantProduk()
    {
        return $this->hasMany(VariantProduk::class, 'id_produk');
    }

    public function variants()
    {
        return $this->hasMany(VariantProduk::class, 'id_produk');
    }

    public function detailVariant()
    {
        return $this->hasManyThrough(DetailVariantProduk::class, VariantProduk::class, 'id_produk', 'id_variant_produk');
    }

    public function suspends()
    {
        return $this->hasMany(ProdukSuspend::class, 'id_produk');
    }

    public function latestSuspend()
    {
        return $this->hasOne(ProdukSuspend::class, 'id_produk')->latestOfMany();
    }
}
