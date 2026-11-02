<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $table = 'testimonials';

    protected $fillable = [
        'nama',
        'peran',
        'kota',
        'avatar',
        'rating',
        'ulasan',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'rating'    => 'integer',
        'is_active' => 'boolean',
        'urutan'    => 'integer',
    ];

    public function getAvatarUrlAttribute(): string
    {
        if (empty($this->avatar)) {
            return asset('template/envato/img/Testimoni-1.jpg');
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        if (file_exists(public_path($this->avatar))) {
            return asset($this->avatar);
        }

        if (file_exists(public_path('template/envato/img/' . $this->avatar))) {
            return asset('template/envato/img/' . $this->avatar);
        }

        return asset($this->avatar);
    }
}
