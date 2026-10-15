<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * @userPhoto($foto, $type = 0)
         *
         * Menghasilkan nilai src foto profil yang fleksibel:
         * - Jika $foto adalah URL eksternal (http/https) → digunakan langsung
         * - Jika $foto sudah berupa path relatif assets/ atau images/ → dibungkus asset()
         * - Jika $foto adalah nama file lokal → dibungkus asset() ke folder public/assets/image/...
         * - Jika $foto null/kosong → fallback ke placeholder default
         *
         * Folder penyimpanan mengikuti level user di public/assets/image/:
         * - type 1 (developer/admin) → assets/image/developers/profiles
         * - type 0 (customer)         → assets/image/customers/profile
         *
         * Contoh pemakaian di Blade:
         *   <img src="@userPhoto($item->foto)" alt="">
         *   <img src="@userPhoto(session('foto'), session('level'))" alt="">
         */
        Blade::directive('userPhoto', function (string $expression): string {
            return "<?php echo (function(\$foto, \$type = 0) {
                \$svgFallback = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22128%22%20height%3D%22128%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20128%20128%22%3E%3Ccircle%20cx%3D%2264%22%20cy%3D%2264%22%20r%3D%2264%22%20fill%3D%22%23e2e8f0%22%2F%3E%3Cpath%20d%3D%22M64%2064a24%2024%200%20100-48%2024%2024%200%20000%2048zm0%2012c-26.7%200-48%2016-48%2036v8h96v-8c0-20-21.3-36-48-36z%22%20fill%3D%22%2394a3b8%22%2F%3E%3C%2Fsvg%3E';

                \$folder = (int) \$type === 1
                    ? 'assets/image/developers/profiles'
                    : 'assets/image/customers/profile';

                \$fotoStr = trim((string) \$foto);
                \$placeholders = ['', '-', 'null', 'belum di isi', 'belum di isi.', 'belum diisi', 'belum diisi.', 'man.png', 'default/profile.png'];
                if (in_array(strtolower(\$fotoStr), \$placeholders, true)) {
                    return \$svgFallback;
                }

                // Jika URL eksternal (http, https, protocol-relative)
                if (str_starts_with(\$fotoStr, 'http://') || str_starts_with(\$fotoStr, 'https://') || str_starts_with(\$fotoStr, '//')) {
                    return e(\$fotoStr);
                }

                // Jika sudah berupa path relatif assets/ atau images/
                if (str_starts_with(\$fotoStr, 'assets/') || str_starts_with(\$fotoStr, 'images/')) {
                    return e(asset(\$fotoStr));
                }

                \$cleanPath = ltrim(\$fotoStr, '/');
                if (file_exists(public_path(\$folder . '/' . \$cleanPath))) {
                    return asset(\$folder . '/' . \$cleanPath);
                }

                if (file_exists(public_path('assets/image/customers/profile/' . \$cleanPath))) {
                    return asset('assets/image/customers/profile/' . \$cleanPath);
                }

                return asset(\$folder . '/' . \$cleanPath);
            })($expression); ?>";
        });
    }
}
