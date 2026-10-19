<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerNotification extends Model
{
    use HasFactory;

    protected $table = 'customer_notifications';

    protected $fillable = [
        'id_user',
        'tipe',
        'id_referensi',
        'judul',
        'pesan',
        'alasan',
        'url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Helper to create customer notification easily.
     */
    public static function notifyCustomer($userId, $tipe, $judul, $pesan, $alasan = null, $url = null, $idReferensi = null)
    {
        return self::create([
            'id_user'      => $userId,
            'tipe'         => $tipe,
            'id_referensi' => $idReferensi,
            'judul'        => $judul,
            'pesan'        => $pesan,
            'alasan'       => $alasan,
            'url'          => $url,
            'is_read'      => false,
        ]);
    }
}
