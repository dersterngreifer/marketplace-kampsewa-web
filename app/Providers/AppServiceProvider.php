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
         * - Jika $foto sudah berupa path relatif storage/ → dibungkus asset()
         * - Jika $foto adalah nama file lokal → dibungkus asset() ke path profil
         * - Jika $foto null/kosong → fallback ke placeholder default
         *
         * Folder penyimpanan mengikuti level user:
         * - type 1 (developer/admin) → assets/image/developers/profiles
         * - type 0 (customer)         → assets/image/customers/profile
         *
         * Contoh pemakaian di Blade:
         *   <img src="@userPhoto($item->foto)" alt="">
         *   <img src="@userPhoto(session('foto'), session('level'))" alt="">
         */
        Blade::directive('userPhoto', function (string $expression): string {
            return "<?php echo (function(\$foto, \$type = 0) {
                \$folder = (int) \$type === 1
                    ? 'assets/image/developers/profiles'
                    : 'assets/image/customers/profile';

                if (empty(\$foto)) {
                    return asset(\$folder . '/man.png');
                }
                if (str_starts_with(\$foto, 'http://') || str_starts_with(\$foto, 'https://')) {
                    return e(\$foto);
                }
                if (str_starts_with(\$foto, 'storage/')) {
                    return e(asset(\$foto));
                }
                return asset(\$folder . '/' . ltrim(\$foto, '/'));
            })($expression); ?>";
        });
    }
}
