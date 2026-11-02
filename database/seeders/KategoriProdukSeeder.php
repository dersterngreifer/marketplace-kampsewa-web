<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nama_kategori'     => 'Tenda Camping',
                'slug'              => 'tenda-camping',
                'icon'              => 'fas fa-campground',
                'gambar'            => 'barang-tenda.jpg',
                'deskripsi_singkat' => 'Dome, Tunnel, Glamping',
                'jumlah_pilihan'    => '120+ Pilihan',
                'urutan'            => 1,
            ],
            [
                'nama_kategori'     => 'Carrier & Ransel',
                'slug'              => 'carrier-ransel',
                'icon'              => 'fas fa-hiking',
                'gambar'            => 'barang-hiking.jpg',
                'deskripsi_singkat' => '35L, 45L, 60L - 80L',
                'jumlah_pilihan'    => '85+ Pilihan',
                'urutan'            => 2,
            ],
            [
                'nama_kategori'     => 'Sleeping Bag & Matras',
                'slug'              => 'sleeping-bag-matras',
                'icon'              => 'fas fa-bed',
                'gambar'            => 'barang-sleeping-bag.jpg',
                'deskripsi_singkat' => 'Polar, Bulu Angsa, Foil',
                'jumlah_pilihan'    => '150+ Pilihan',
                'urutan'            => 3,
            ],
            [
                'nama_kategori'     => 'Cooking Set & Kompor',
                'slug'              => 'cooking-set-kompor',
                'icon'              => 'fas fa-utensils',
                'gambar'            => 'barang-makan.webp',
                'deskripsi_singkat' => 'Nesting, Kompor Windproof',
                'jumlah_pilihan'    => '90+ Pilihan',
                'urutan'            => 4,
            ],
            [
                'nama_kategori'     => 'Lighting & Navigasi',
                'slug'              => 'lighting-navigasi',
                'icon'              => 'fas fa-lightbulb',
                'gambar'            => 'barang-gps.jpeg',
                'deskripsi_singkat' => 'Headlamp, GPS, Lentera',
                'jumlah_pilihan'    => '60+ Pilihan',
                'urutan'            => 5,
            ],
            [
                'nama_kategori'     => 'Flysheet & Hammock',
                'slug'              => 'flysheet-hammock',
                'icon'              => 'fas fa-feather-alt',
                'gambar'            => 'barang-flysheet.jpg',
                'deskripsi_singkat' => 'Waterproof 3x3, Single/Double',
                'jumlah_pilihan'    => '75+ Pilihan',
                'urutan'            => 6,
            ],
        ];

        foreach ($categories as $cat) {
            KategoriProduk::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }
    }
}
