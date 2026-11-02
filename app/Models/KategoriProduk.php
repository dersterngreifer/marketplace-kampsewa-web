<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'icon',
        'gambar',
        'deskripsi_singkat',
        'jumlah_pilihan',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    /**
     * Auto-generate slug dari nama_kategori jika tidak diisi.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama_kategori);
            }
        });
    }

    /**
     * URL Gambar kategori
     */
    public function getGambarUrlAttribute(): string
    {
        if (empty($this->gambar)) {
            return asset('template/envato/img/barang-tenda.jpg');
        }

        if (str_starts_with($this->gambar, 'http://') || str_starts_with($this->gambar, 'https://')) {
            return $this->gambar;
        }

        if (file_exists(public_path('template/envato/img/' . $this->gambar))) {
            return asset('template/envato/img/' . $this->gambar);
        }

        return asset('template/envato/img/' . $this->gambar);
    }

    /**
     * Semua produk yang termasuk dalam kategori ini.
     */
    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_kategori_produk');
    }
}
