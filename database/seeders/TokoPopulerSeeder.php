<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TokoPopulerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $popularStores = [
            [
                'email'                 => 'partner.rei@kampsewa.id',
                'name'                  => 'Rei Outdoor Admin',
                'name_store'            => 'Rei Outdoor Gear',
                'foto_toko'             => 'toko-rei.jpg',
                'banner_toko'           => 'home-1.jpg',
                'slogan_toko'           => 'Specialist Tenda & Ransel',
                'badge_toko'            => 'Terlaris Minggu Ini',
                'rating_toko'           => 4.9,
                'total_penyewaan_toko'  => 1240,
                'is_verified'           => true,
                'is_populer'            => true,
                'deskripsi_toko'        => 'Penyedia perlengkapan outdoor terkemuka spesialis tenda kemah, ransel ekspedisi, dan gear pendakian gunung berkualitas tinggi.',
                'nomor_telephone'       => '081234567801',
                'status'                => 'Online',
            ],
            [
                'email'                 => 'partner.eiger@kampsewa.id',
                'name'                  => 'Eiger Adventure Admin',
                'name_store'            => 'Eiger Adventure Store',
                'foto_toko'             => 'toko-eiger.jpeg',
                'banner_toko'           => 'home-4.jpg',
                'slogan_toko'           => 'Top Brand Outdoor Gear',
                'badge_toko'            => 'Mitra Utama',
                'rating_toko'           => 5.0,
                'total_penyewaan_toko'  => 2850,
                'is_verified'           => true,
                'is_populer'            => true,
                'deskripsi_toko'        => 'Official Store Eiger Tropical Adventure. Menyewakan perlengkapan camping dan mendaki gunung standar tropis Indonesia terlengkap.',
                'nomor_telephone'       => '081234567802',
                'status'                => 'Online',
            ],
            [
                'email'                 => 'partner.consina@kampsewa.id',
                'name'                  => 'Consina Official Admin',
                'name_store'            => 'Consina The Outdoor Lifestyle',
                'foto_toko'             => 'toko-consina.png',
                'banner_toko'           => 'home-5.jpg',
                'slogan_toko'           => 'Carrier & Apparel Camping',
                'badge_toko'            => 'Terlaris Minggu Ini',
                'rating_toko'           => 4.9,
                'total_penyewaan_toko'  => 1890,
                'is_verified'           => true,
                'is_populer'            => true,
                'deskripsi_toko'        => 'Pilihan utama pecinta alam untuk carrier tahan banting, jaket gunung polar, dan aneka perlengkapan camping gaya hidup bebas.',
                'nomor_telephone'       => '081234567803',
                'status'                => 'Online',
            ],
            [
                'email'                 => 'partner.tnf@kampsewa.id',
                'name'                  => 'The North Face Admin',
                'name_store'            => 'The North Face ID',
                'foto_toko'             => 'toko-tnf.jpg',
                'banner_toko'           => 'home-2.jpg',
                'slogan_toko'           => 'Premium Mountaineering',
                'badge_toko'            => 'Official Brand',
                'rating_toko'           => 4.9,
                'total_penyewaan_toko'  => 1420,
                'is_verified'           => true,
                'is_populer'            => true,
                'deskripsi_toko'        => 'Perlengkapan mountaineering kelas dunia untuk kondisi cuaca ekstrem. Tenda ekspedisi, sleeping bag minus degree, dan technical outerwear.',
                'nomor_telephone'       => '081234567804',
                'status'                => 'Online',
            ],
            [
                'email'                 => 'partner.decathlon@kampsewa.id',
                'name'                  => 'Decathlon Indonesia Admin',
                'name_store'            => 'Decathlon Indonesia',
                'foto_toko'             => 'toko-deca.jpg',
                'banner_toko'           => 'home-3.jpg',
                'slogan_toko'           => 'Peralatan Camping & Olahraga',
                'badge_toko'            => 'Terpopuler',
                'rating_toko'           => 4.8,
                'total_penyewaan_toko'  => 3100,
                'is_verified'           => true,
                'is_populer'            => true,
                'deskripsi_toko'        => 'Menyewakan produk Quechua & Forclaz original: tenda Fresh&Black, matras self-inflating, dan perlengkapan camping keluarga terjangkau.',
                'nomor_telephone'       => '081234567805',
                'status'                => 'Online',
            ],
            [
                'email'                 => 'partner.deuter@kampsewa.id',
                'name'                  => 'Deuter Official Admin',
                'name_store'            => 'Deuter Official Store',
                'foto_toko'             => 'toko-deuter.webp',
                'banner_toko'           => 'home-1.jpg',
                'slogan_toko'           => 'Legendary German Backpacks',
                'badge_toko'            => 'Top Quality',
                'rating_toko'           => 5.0,
                'total_penyewaan_toko'  => 980,
                'is_verified'           => true,
                'is_populer'            => true,
                'deskripsi_toko'        => 'Spesialis carrier ergonomis berteknologi Aircontact dari Jerman. Pilihan terbaik untuk kenyamanan tulang punggung saat hiking berjarak jauh.',
                'nomor_telephone'       => '081234567806',
                'status'                => 'Online',
            ],
            [
                'email'                 => 'partner.avtech@kampsewa.id',
                'name'                  => 'Avtech Adventure Admin',
                'name_store'            => 'Avtech Adventure Equipment',
                'foto_toko'             => 'toko-avtech.jpg',
                'banner_toko'           => 'home-4.jpg',
                'slogan_toko'           => 'Tenda & Sleeping Bag Profesional',
                'badge_toko'            => 'Favorit Pendaki',
                'rating_toko'           => 4.8,
                'total_penyewaan_toko'  => 1650,
                'is_verified'           => true,
                'is_populer'            => true,
                'deskripsi_toko'        => 'Brand outdoor andalan pendaki lokal. Tenda dome kokoh, flysheet waterproof tebal, dan matras aluminium foil dengan harga sewa super ramah.',
                'nomor_telephone'       => '081234567807',
                'status'                => 'Online',
            ],
            [
                'email'                 => 'partner.jack@kampsewa.id',
                'name'                  => 'Jack Wolfskin Admin',
                'name_store'            => 'Jack Wolfskin Store',
                'foto_toko'             => 'toko-jack.jpg',
                'banner_toko'           => 'home-5.jpg',
                'slogan_toko'           => 'German Outdoor Brand',
                'badge_toko'            => 'Premium Gear',
                'rating_toko'           => 4.9,
                'total_penyewaan_toko'  => 870,
                'is_verified'           => true,
                'is_populer'            => true,
                'deskripsi_toko'        => 'Menyewakan produk outdoor premium asal Jerman dengan teknologi Texapore tahan air dan angin. Pas untuk pendakian di cuaca hujan lebat.',
                'nomor_telephone'       => '081234567808',
                'status'                => 'Online',
            ],
        ];

        foreach ($popularStores as $storeData) {
            User::updateOrCreate(
                ['email' => $storeData['email']],
                array_merge($storeData, [
                    'password'          => Hash::make('password123'),
                    'type'              => 0,
                    'tanggal_lahir'     => '1995-01-01',
                    'jenis_kelamin'     => 'Laki-laki',
                    'background'        => 'Mitra Vendor Resmi KampSewa',
                    'email_verified_at' => now(),
                ])
            );
        }

        // Pastikan toko milik Agung Kurniawan juga terverifikasi dan berstatus populer jika ada
        User::where('id', 5011)->update([
            'is_populer'   => true,
            'is_verified'  => true,
            'slogan_toko'  => 'Mitra Rental Alat Camping Unggulan',
            'badge_toko'   => 'Verified Partner',
            'rating_toko'  => 5.0,
        ]);
    }
}
