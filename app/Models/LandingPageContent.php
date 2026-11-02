<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageContent extends Model
{
    use HasFactory;

    protected $table = 'landing_page_contents';

    protected $fillable = [
        'section',
        'key',
        'title',
        'subtitle',
        'content',
        'badge',
        'icon',
        'icon_bg',
        'image',
        'link_url',
        'link_text',
        'step_number',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'urutan'    => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('urutan', 'asc');
    }

    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section)->active();
    }
}
