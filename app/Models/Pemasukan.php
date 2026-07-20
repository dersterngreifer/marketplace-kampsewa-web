<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;

    protected $table = 'pemasukan';

    protected $fillable = [
        'id_user',
        'sumber',
        'deskripsi',
        'nominal',
        'id_pembayaran_penyewaan', // Referensi transaksi untuk audit trail
    ];
}
