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
        'status_iklan',
        'is_force_completed',
        'force_complete_alasan',
        'force_complete_catatan',
        'force_completed_at',
    ];

    protected $casts = [
        'is_force_completed' => 'boolean',
        'force_completed_at' => 'datetime',
    ];

    public function iklan()
    {
        return $this->belongsTo(Iklan::class, 'id_iklan');
    }

    public function latestSuspend()
    {
        return $this->hasOne(IklanSuspend::class, 'id_detail_iklan')->latestOfMany();
    }

    public function latestForceComplete()
    {
        return $this->hasOne(IklanForceComplete::class, 'id_detail_iklan')->latestOfMany();
    }
}
