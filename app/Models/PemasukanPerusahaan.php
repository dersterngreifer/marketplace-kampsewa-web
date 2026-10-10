<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemasukanPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'pemasukan_perusahaan';
    protected $fillable = [
        'sumber',
        'deskripsi',
        'nominal',
        'id_pembayaran_penyewaan',
    ];
}
