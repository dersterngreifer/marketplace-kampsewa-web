<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailIklan extends Model
{
    use HasFactory;

    protected $table = 'detail_iklan';

    protected $fillable = [
        'id_iklan',
        'tanggal_mulai',
        'tanggal_akhir',
        'harga_iklan',
        'status_iklan'
    ];

    public function iklan()
    {
        return $this->belongsTo(Iklan::class, 'id_iklan');
    }

    public function latestSuspend()
    {
        return $this->hasOne(IklanSuspend::class, 'id_detail_iklan')->latestOfMany();
    }
}
