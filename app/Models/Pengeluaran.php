<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'id_user',
        'sumber',
        'deskripsi',
        'nominal',
        'kategori_pengeluaran', // Label kategori: operasional, gaji, marketing, dll
    ];
}
