<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'id_user',
        'deskripsi',
        'kriteria',
        'status',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function messages()
    {
        return $this->hasMany(FeedbackMessage::class, 'feedback_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(FeedbackMessage::class, 'feedback_id')->latestOfMany();
    }
}
