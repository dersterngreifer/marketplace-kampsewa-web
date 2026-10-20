<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukSuspend extends Model
{
    use HasFactory;

    protected $table = 'produk_suspends';

    protected $fillable = [
        'id_produk',
        'id_user',
        'id_admin',
        'alasan',
        'catatan',
        'status_revisi',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function getAlasanSuspendAttribute()
    {
        return $this->attributes['alasan'] ?? null;
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }
}
