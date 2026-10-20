<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_perusahaan';
    protected $fillable = [
        'id_user',
        'sumber',
        'deskripsi',
        'nominal',
        'kategori_pengeluaran',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
