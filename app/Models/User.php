<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'nomor_telephone',
        'tanggal_lahir',
        'foto',
        'status',
        'background',
        'jenis_kelamin',
        'time_login',
        'last_login',
        'name_store',
        'foto_toko',
        // KYC — verifikasi identitas
        'nomor_identitas',
        'foto_identitas',
        'is_verified',
        // Profil toko
        'deskripsi_toko',
        'banner_toko',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'followed_id', 'follower_id')->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id')->withTimestamps();
    }

    public function promosiToko()
    {
        return $this->hasMany(PromosiToko::class, 'id_user');
    }

    public function iklan()
    {
        return $this->hasMany(Iklan::class, 'id_user');
    }

    public function pengaturanPengiriman()
    {
        return $this->hasOne(PengaturanPengirimanToko::class, 'id_user');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'id_user');
    }

    public function penyewaan()
    {
        return $this->hasMany(Penyewaan::class, 'id_user');
    }

    public function pemasukanPerusahaan()
    {
        return $this->hasMany(PemasukanPerusahaan::class, 'id_user');
    }

    public function pengeluaranPerusahaan()
    {
        return $this->hasMany(PengeluaranPerusahaan::class, 'id_user');
    }

    /**
     * Resolve route binding by ID, Encrypted ID, or Name.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field) {
            return $this->where($field, $value)->first();
        }

        // 1. Cari berdasarkan ID numerik
        if (is_numeric($value)) {
            $user = $this->where('id', (int) $value)->first();
            if ($user) return $user;
        }

        // 2. Cari berdasarkan ID terenkripsi (Crypt)
        try {
            $decrypted = \Illuminate\Support\Facades\Crypt::decrypt($value);
            if (is_numeric($decrypted)) {
                $user = $this->where('id', (int) $decrypted)->first();
                if ($user) return $user;
            }
        } catch (\Throwable $e) {
            // Bukan string terenkripsi, lanjutkan
        }

        // 3. Cari berdasarkan Nama Lengkap (exact atau URL decoded)
        $decoded = trim(urldecode($value));
        $user = $this->where('name', $decoded)->orWhere('name', $value)->first();
        if ($user) return $user;

        // 4. Cari berdasarkan substring / fuzzy Nama
        return $this->where('name', 'LIKE', '%' . $decoded . '%')->first();
    }
}
