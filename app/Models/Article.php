<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [
        'tipe',
        'judul',
        'slug',
        'gambar',
        'deskripsi_singkat',
        'konten',
        'lokasi',
        'sumber_url',
        'sumber_nama',
        'author_id',
        'status',
        'views_count',
    ];

    protected $casts = [
        'views_count' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * Relationship with author (User)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope for published articles
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for wisata
     */
    public function scopeWisata($query)
    {
        return $query->where('tipe', 'wisata');
    }

    /**
     * Scope for berita
     */
    public function scopeBerita($query)
    {
        return $query->where('tipe', 'berita');
    }

    /**
     * Accessor: Ensure sumber_url is sanitized and always starts with http:// or https://
     */
    public function getSumberUrlAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $trimmed = trim((string) $value);
        if (in_array(strtolower($trimmed), ['null', '-', 'undefined', ''], true)) {
            return null;
        }

        if (!Str::startsWith($trimmed, ['http://', 'https://'])) {
            return 'https://' . $trimmed;
        }

        return $trimmed;
    }

    /**
     * Mutator: Sanitize and ensure proper scheme before saving sumber_url
     */
    public function setSumberUrlAttribute($value): void
    {
        if (empty($value)) {
            $this->attributes['sumber_url'] = null;
            return;
        }

        $trimmed = trim((string) $value);
        if (in_array(strtolower($trimmed), ['null', '-', 'undefined', ''], true)) {
            $this->attributes['sumber_url'] = null;
            return;
        }

        if (!Str::startsWith($trimmed, ['http://', 'https://'])) {
            $trimmed = 'https://' . $trimmed;
        }

        $this->attributes['sumber_url'] = $trimmed;
    }

    /**
     * Get full image URL
     */
    public function getGambarUrlAttribute(): string
    {
        $svgFallback = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22640%22%20height%3D%22360%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20640%20360%22%3E%3Crect%20fill%3D%22%23f1f5f9%22%20width%3D%22640%22%20height%3D%22360%22%2F%3E%3Ctext%20fill%3D%22%2394a3b8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2220%22%20dy%3D%2210.5%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%3EKampSewa%20Artikel%3C%2Ftext%3E%3C%2Fsvg%3E';

        if (empty($this->gambar)) {
            return $svgFallback;
        }

        $gambar = trim((string) $this->gambar);
        if (in_array(strtolower($gambar), ['', 'null', '-'], true)) {
            return $svgFallback;
        }

        if (Str::startsWith($gambar, ['http://', 'https://', '//'])) {
            return $gambar;
        }

        if (Str::startsWith($gambar, ['assets/', 'images/'])) {
            return asset($gambar);
        }

        $clean = ltrim($gambar, '/');

        if ($this->tipe === 'wisata') {
            if (file_exists(public_path('assets/image/rekomendasi-wisata/' . $clean))) {
                return asset('assets/image/rekomendasi-wisata/' . $clean);
            }
            if (file_exists(public_path('assets/image/articles/' . $clean))) {
                return asset('assets/image/articles/' . $clean);
            }
            return asset('assets/image/rekomendasi-wisata/' . $clean);
        }

        if (file_exists(public_path('assets/image/berita-terkini/' . $clean))) {
            return asset('assets/image/berita-terkini/' . $clean);
        }
        if (file_exists(public_path('assets/image/articles/' . $clean))) {
            return asset('assets/image/articles/' . $clean);
        }
        return asset('assets/image/berita-terkini/' . $clean);
    }

    /**
     * Format author data for API output
     */
    public function formatAuthor(): array
    {
        $author = $this->author;
        $svgAvatarFallback = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22128%22%20height%3D%22128%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20128%20128%22%3E%3Ccircle%20cx%3D%2264%22%20cy%3D%2264%22%20r%3D%2264%22%20fill%3D%22%23e2e8f0%22%2F%3E%3Cpath%20d%3D%22M64%2064a24%2024%200%20100-48%2024%2024%200%20000%2048zm0%2012c-26.7%200-48%2016-48%2036v8h96v-8c0-20-21.3-36-48-36z%22%20fill%3D%22%2394a3b8%22%2F%3E%3C%2Fsvg%3E';

        if (!$author) {
            return [
                'id'           => null,
                'name'         => $this->tipe === 'wisata' ? 'Admin Rekomendasi KampSewa' : 'Tim Editorial KampSewa',
                'nama'         => $this->tipe === 'wisata' ? 'Admin Rekomendasi KampSewa' : 'Tim Editorial KampSewa',
                'foto_profile' => $svgAvatarFallback,
                'foto'         => $svgAvatarFallback,
            ];
        }

        $fotoProfile = trim((string) $author->foto);
        if (empty($fotoProfile) || in_array(strtolower($fotoProfile), ['belum di isi', 'belum di isi.', 'belum diisi', 'belum diisi.', 'null', '-'], true)) {
            $fotoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($author->name) . '&background=5038ed&color=ffffff';
        } elseif (Str::startsWith($fotoProfile, ['http://', 'https://', '//'])) {
            $fotoUrl = $fotoProfile;
        } elseif (Str::startsWith($fotoProfile, ['assets/', 'images/'])) {
            $fotoUrl = asset($fotoProfile);
        } elseif (file_exists(public_path('assets/image/customers/profile/' . $fotoProfile))) {
            $fotoUrl = asset('assets/image/customers/profile/' . $fotoProfile);
        } elseif (file_exists(public_path('assets/image/developers/profiles/' . $fotoProfile))) {
            $fotoUrl = asset('assets/image/developers/profiles/' . $fotoProfile);
        } else {
            $fotoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($author->name) . '&background=5038ed&color=ffffff';
        }

        return [
            'id'           => $author->id,
            'name'         => $author->name,
            'nama'         => $author->name,
            'foto_profile' => $fotoUrl,
            'foto'         => $fotoUrl,
        ];
    }

    /**
     * Format into standard API array
     *
     * @param bool $includeKonten Include full content body (for detail view)
     * @return array
     */
    public function toApiArray(bool $includeKonten = false): array
    {
        $createdAt = $this->created_at ?? now();

        $data = [
            'id'                => $this->id,
            'tipe'              => $this->tipe,
            'judul'             => $this->judul,
            'slug'              => $this->slug,
            'gambar'            => $this->gambar_url,
            'deskripsi_singkat' => $this->deskripsi_singkat,
        ];

        if ($includeKonten) {
            $data['konten'] = $this->konten;
        }

        if ($this->tipe === 'wisata') {
            $data['lokasi'] = $this->lokasi ?? 'Indonesia';
        }

        $data['sumber_url']   = $this->sumber_url;
        $data['sumber_nama']  = $this->sumber_nama;
        $data['views_count']  = (int) $this->views_count;
        $data['author']       = $this->formatAuthor();
        $data['created_at']   = $createdAt->toIso8601String();

        if ($includeKonten) {
            $data['updated_at'] = ($this->updated_at ?? $createdAt)->toIso8601String();
        }

        // Indonesian human-readable date & time in WIB timezone
        $carbonWib = Carbon::parse($createdAt)->timezone('Asia/Jakarta');
        $data['tanggal_upload'] = (clone $carbonWib)->locale('id')->isoFormat('D MMMM Y');
        $data['waktu_upload']   = (clone $carbonWib)->format('H:i') . ' WIB';
        $data['waktu_lalu']     = (clone $carbonWib)->locale('id')->diffForHumans();

        return $data;
    }
}
