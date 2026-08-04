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

        // Jika URL dimulai dengan http:// atau https://, maka external
        if (preg_match('/^https?:\/\//', $url)) {
            return 'external';
        }

        // Jika URL dimulai dengan /, assets/, atau produk/ maka internal
        if (preg_match('/^(\/|assets\/|products\/|produk\/)/', $url)) {
            return 'internal';
        }

        // Default internal
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
        if (!$url) {
            return asset('images/placeholder-image.png');
        }

        // Jika tidak ada tipe sumber, deteksi otomatis
        if (!$tipe_sumber) {
            $tipe_sumber = self::detectPhotoSource($url);
        }

        // Jika external (URL lengkap), return as is
        if ($tipe_sumber === 'external') {
            return $url;
        }

        // Jika internal, gunakan asset helper
        // Normalize path
        $path = ltrim($url, '/');
        
        // Jika sudah memiliki assets/ prefix, gunakan langsung
        if (preg_match('/^assets\//', $path)) {
            return asset($path);
        }

        // Jika tidak, asumsikan path di dalam assets/image/customers/produk/
        return asset('assets/image/customers/produk/' . $path);
    }

    /**
     * Mendapatkan foto pertama dari sebuah produk
     * 
     * @param \App\Models\Produk $produk
     * @return string URL foto
     */
    public static function getThumbnailUrl($produk): string
    {
        if (!empty($produk->foto)) {
            // Jika foto adalah collection (dari relasi model)
            if (is_object($produk->foto) && method_exists($produk->foto, 'count') && $produk->foto->count() > 0) {
                $photo = $produk->foto->first();
                return self::getPhotoUrl($photo->url_foto, $photo->tipe_sumber);
            }
            
            // Jika foto adalah string (misal dari query builder alias)
            if (is_string($produk->foto)) {
                return self::getPhotoUrl($produk->foto, 'internal');
            }
        }

        // Fallback ke foto_depan jika masih ada
        if (!empty($produk->foto_depan)) {
            return self::getPhotoUrl($produk->foto_depan, 'internal');
        }

        return asset('images/placeholder-image.png');
    }
}
