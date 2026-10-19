<?php

namespace App\Helpers;

/**
 * Photo Helper untuk menangani internal dan external photos
 */
class PhotoHelper
{
    /**
     * Mendeteksi apakah URL adalah external atau internal
     * 
     * @param string $url
     * @return string 'internal' atau 'external'
     */
    public static function detectPhotoSource($url): string
    {
        if (!$url) {
            return 'internal';
        }

        $url = trim((string) $url);

        // Jika URL dimulai dengan http://, https://, atau //
        if (preg_match('/^(https?:\/\/|\/\/)/i', $url)) {
            return 'external';
        }

        return 'internal';
    }

    /**
     * Mendapatkan URL foto lengkap untuk ditampilkan
     * 
     * @param string $url
     * @param string $tipe_sumber
     * @return string
     */
    public static function getPhotoUrl($url, $tipe_sumber = null): string
    {
        $svgProductPlaceholder = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22400%22%20height%3D%22300%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20300%22%3E%3Crect%20fill%3D%22%23f1f5f9%22%20width%3D%22400%22%20height%3D%22300%22%2F%3E%3Ctext%20fill%3D%22%2394a3b8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%3EKampSewa%20Produk%3C%2Ftext%3E%3C%2Fsvg%3E';

        if (!$url) {
            return $svgProductPlaceholder;
        }

        $url = trim((string) $url);
        if (in_array(strtolower($url), ['', 'null', '-', 'default.png', 'placeholder-image.png'], true)) {
            return $svgProductPlaceholder;
        }

        // Jika external (URL lengkap dari internet), return langsung URL tersebut
        if (preg_match('/^(https?:\/\/|\/\/)/i', $url)) {
            return $url;
        }

        // Normalize path (ubah backslash menjadi forward slash dan hapus leading slash)
        $path = str_replace('\\', '/', $url);
        $path = ltrim($path, '/');

        // Hapus prefix public/ jika tersimpan di DB
        if (preg_match('/^public\//i', $path)) {
            $path = preg_replace('/^public\//i', '', $path);
        }

        // Jika sudah lengkap assets/image/customers/produk/
        if (preg_match('/^assets\/image\/customers\/produk\//i', $path)) {
            return asset($path);
        }

        // Jika prefix assets/ atau images/ lainnya
        if (preg_match('/^(assets\/|images\/)/i', $path)) {
            return asset($path);
        }

        // Default: lokasi file foto produk lokal pada public/assets/image/customers/produk/
        return asset('assets/image/customers/produk/' . $path);
    }

    /**
     * Mendapatkan foto pertama dari sebuah produk
     * 
     * @param \App\Models\Produk|object|array $produk
     * @return string URL foto
     */
    public static function getThumbnailUrl($produk): string
    {
        $svgProductPlaceholder = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22400%22%20height%3D%22300%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20300%22%3E%3Crect%20fill%3D%22%23f1f5f9%22%20width%3D%22400%22%20height%3D%22300%22%2F%3E%3Ctext%20fill%3D%22%2394a3b8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%3EKampSewa%20Produk%3C%2Ftext%3E%3C%2Fsvg%3E';

        if (empty($produk)) {
            return $svgProductPlaceholder;
        }

        // Cek properti langsung foto_product / gambar_product / url_foto
        if (isset($produk->foto_product) && !empty($produk->foto_product)) {
            return self::getPhotoUrl($produk->foto_product);
        }
        if (isset($produk->gambar_product) && !empty($produk->gambar_product)) {
            return self::getPhotoUrl($produk->gambar_product);
        }
        if (isset($produk->url_foto) && !empty($produk->url_foto)) {
            return self::getPhotoUrl($produk->url_foto);
        }

        if (!empty($produk->foto)) {
            // Jika foto adalah collection (dari relasi model)
            if (is_object($produk->foto) && method_exists($produk->foto, 'count') && $produk->foto->count() > 0) {
                $photo = $produk->foto->first();
                return self::getPhotoUrl($photo->url_foto ?? ($photo->foto ?? null), $photo->tipe_sumber ?? null);
            }
            
            // Jika foto adalah string (misal dari query builder alias)
            if (is_string($produk->foto)) {
                $fotoStr = trim($produk->foto);
                // Jika berupa JSON array string
                if (preg_match('/^\[.*\]$/', $fotoStr)) {
                    $decoded = json_decode($fotoStr, true);
                    if (is_array($decoded) && count($decoded) > 0) {
                        return self::getPhotoUrl($decoded[0]);
                    }
                }
                // Jika URL comma separated
                if (strpos($fotoStr, ',') !== false) {
                    $parts = explode(',', $fotoStr);
                    return self::getPhotoUrl(trim($parts[0]));
                }
                return self::getPhotoUrl($fotoStr);
            }
        }

        // Fallback: Jika relasi foto belum di-load atau kosong, cari foto pertama berdasarkan ID produk di tabel foto_produk
        $productId = $produk->id ?? ($produk->id_produk ?? null);
        if ($productId) {
            $firstFoto = \App\Models\FotoProduk::where('id_produk', $productId)->orderBy('urutan', 'asc')->first();
            if ($firstFoto) {
                return self::getPhotoUrl($firstFoto->url_foto ?? ($firstFoto->foto ?? null), $firstFoto->tipe_sumber ?? null);
            }
        }

        return $svgProductPlaceholder;
    }

    /**
     * Resolve user/admin avatar URL, initials, and gradient for UI and JSON responses.
     */
    public static function getUserAvatarData($user): array
    {
        $name = trim((string) ($user->name ?? 'Pengguna'));
        $photo = trim((string) ($user->foto ?? ''));
        $type = (int) ($user->type ?? 0);

        $folder = $type === 1
            ? 'assets/image/developers/profiles'
            : 'assets/image/customers/profile';

        $placeholders = ['', '-', 'null', 'belum di isi', 'belum di isi.', 'belum diisi', 'belum diisi.', 'default/profile.png', 'man.png'];
        $isEmpty = in_array(strtolower($photo), $placeholders, true);

        $avatarUrl = null;
        if (!$isEmpty) {
            if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://') || str_starts_with($photo, '//')) {
                $avatarUrl = $photo;
            } elseif (str_starts_with($photo, 'assets/') || str_starts_with($photo, 'images/')) {
                $avatarUrl = asset($photo);
            } else {
                $cleanPhoto = ltrim($photo, '/');
                if (file_exists(public_path($folder . '/' . $cleanPhoto))) {
                    $avatarUrl = asset($folder . '/' . $cleanPhoto);
                } elseif (file_exists(public_path('assets/image/customers/profile/' . $cleanPhoto))) {
                    $avatarUrl = asset('assets/image/customers/profile/' . $cleanPhoto);
                } elseif (file_exists(public_path('assets/image/developers/profiles/' . $cleanPhoto))) {
                    $avatarUrl = asset('assets/image/developers/profiles/' . $cleanPhoto);
                } else {
                    $avatarUrl = null;
                    $isEmpty = true;
                }
            }
        }

        $words = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if (count($words) >= 2) {
            $initials = strtoupper(implode('', array_map(fn ($w) => mb_substr($w, 0, 1), array_slice($words, 0, 2))));
        } else {
            $initials = strtoupper(mb_substr($name !== '' ? $name : '?', 0, 2));
        }

        $palettes = [
            'from-violet-500 to-indigo-600',
            'from-blue-500 to-indigo-600',
            'from-emerald-500 to-teal-600',
            'from-rose-500 to-pink-600',
            'from-amber-500 to-orange-600',
            'from-cyan-500 to-blue-600',
        ];
        $gradient = $palettes[abs(crc32($name !== '' ? $name : 'user')) % count($palettes)];

        return [
            'has_photo' => !$isEmpty && !empty($avatarUrl),
            'avatar_url' => $avatarUrl,
            'initials' => $initials ?: 'U',
            'gradient' => $gradient,
        ];
    }
}
