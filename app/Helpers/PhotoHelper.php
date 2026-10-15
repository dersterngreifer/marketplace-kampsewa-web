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

        // Jika external (URL lengkap), return as is
        if (preg_match('/^(https?:\/\/|\/\/)/i', $url)) {
            return $url;
        }

        // Normalize path
        $path = ltrim($url, '/');
        
        // Jika sudah memiliki assets/ atau images/ prefix
        if (preg_match('/^(assets\/|images\/)/i', $path)) {
            return asset($path);
        }

        // Jika file ada di assets/image/customers/produk/
        if (file_exists(public_path('assets/image/customers/produk/' . $path))) {
            return asset('assets/image/customers/produk/' . $path);
        }

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

        return $svgProductPlaceholder;
    }
}
