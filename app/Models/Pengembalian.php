<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'id_penyewaan',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'kondisi_barang',
        'denda',
        'status_denda',
        'catatan',
        'bukti_kondisi',
        'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal_kembali_rencana'  => 'date',
        'tanggal_kembali_aktual'   => 'datetime',
        'denda'                    => 'integer',
    ];

    /**
     * Relasi ke penyewaan.
     */
    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class, 'id_penyewaan');
    }

    /**
     * Admin / pemilik toko yang mencatat pengembalian.
     */
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
