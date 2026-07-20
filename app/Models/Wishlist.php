<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlist';

    protected $fillable = [
        'id_user',
        'id_produk',
    ];

    /**
     * User pemilik wishlist.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Produk yang di-wishlist.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
