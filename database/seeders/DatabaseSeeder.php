<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan pemanggilan memperhatikan dependency antar tabel:
     * - Tier 1: Tabel utama (users)
     * - Tier 2: Tabel yang hanya bergantung pada users
     * - Tier 3: Tabel produk & iklan (bergantung pada users)
     * - Tier 4: Tabel yang bergantung pada produk (variant)
     * - Tier 5: Tabel transaksi penyewaan (bergantung pada users & produk)
     *
     * Catatan: DetailIklan, PembayaranIklan, DetailPenyewaan, dan PembayaranPenyewaan
     * dibuat langsung di dalam IklanSeeder dan PenyewaanSeeder agar relasi konsisten.
     */
    public function run(): void
    {
        // Tier 1: Tabel utama tanpa foreign key
        $this->call(UserSeeder::class);

        // Tier 2: Tabel yang hanya bergantung pada users
        $this->call([
            ResetPasswordSeeder::class,
            StatusNotifikasiUserSeeder::class,
            BankSeeder::class,
            FeedbackSeeder::class,
            PemasukanSeeder::class,
            PengeluaranSeeder::class,
            AlamatSeeder::class,
            RiwayatPencarianSeeder::class,
        ]);

        // Tier 3: Produk & Iklan (bergantung pada users)
        // IklanSeeder sudah mengurus detail_iklan & pembayaran_iklan sekaligus
        $this->call([
            ProdukSeeder::class,
            IklanSeeder::class,
        ]);

        // Tier 4: Tabel yang bergantung pada produk
        $this->call([
            RatingSeeder::class,        // FK: produk, users
            VariantProdukSeeder::class, // FK: produk
        ]);

        // Tier 5: Transaksi penyewaan
        // PenyewaanSeeder sudah mengurus detail_penyewaan & pembayaran_penyewaan sekaligus
        $this->call([
            PenyewaanSeeder::class,
        ]);
    }
}
