<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IklanForceComplete extends Model
{
    use HasFactory;

    protected $table = 'iklan_force_completes';

    protected $fillable = [
        'id_iklan',
        'id_detail_iklan',
        'id_user',
        'alasan',
        'catatan',
        'status',
    ];

    public function iklan()
    {
        return $this->belongsTo(Iklan::class, 'id_iklan', 'id');
    }

    public function detailIklan()
    {
        return $this->belongsTo(DetailIklan::class, 'id_detail_iklan', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
