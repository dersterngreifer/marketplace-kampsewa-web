<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingProduk extends Model
{
    use HasFactory;

    protected $table = 'rating_produk';

    protected $fillable = [
        'id_user',
        'id_produk',
        'rating',
        'ulasan',
        'foto_ulasan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function getFotosUlasanAttribute()
    {
        $fotoList = [];
        if (!empty($this->foto_ulasan)) {
            $decoded = json_decode($this->foto_ulasan, true);
            if (is_array($decoded)) {
                foreach ($decoded as $fn) {
                    if (!empty($fn)) {
                        $fotoList[] = asset('assets/image/reviews/' . trim($fn));
                    }
                }
            } elseif (is_string($this->foto_ulasan) && trim($this->foto_ulasan) !== '') {
                $fotoList[] = asset('assets/image/reviews/' . trim($this->foto_ulasan));
            }
        }
        return $fotoList;
    }
}
