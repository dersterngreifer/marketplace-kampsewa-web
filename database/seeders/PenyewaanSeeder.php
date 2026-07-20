<?php

namespace Database\Seeders;

use App\Models\DetailPenyewaan;
use App\Models\PembayaranPenyewaan;
use App\Models\Penyewaan;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PenyewaanSeeder extends Seeder
{
    /**
     * Seeder penyewaan realistis:
     * - 20 Selesai   : masa lalu, sudah lunas
     * - 15 Berlangsung: sedang berjalan, sudah lunas
     * - 15 Pending   : menunggu konfirmasi, belum lunas
     *
     * Setiap penyewaan memiliki 1-3 detail_penyewaan dan 1 pembayaran_penyewaan.
     */
    public function run(): void
    {
        $userIds  = User::where('type', 0)->inRandomOrder()->limit(50)->pluck('id')->toArray();
        $produkIds = Produk::inRandomOrder()->limit(50)->pluck('id')->toArray();

        if (empty($userIds)) {
            $userIds = User::inRandomOrder()->limit(50)->pluck('id')->toArray();
        }

        if (empty($produkIds)) {
            $this->command->warn('⚠️  Tidak ada produk di database. Jalankan ProdukSeeder terlebih dahulu.');
            return;
        }

        $hargaOptions = [50000, 75000, 100000, 150000, 200000];
        $ukuranOptions = ['S', 'M', 'L', 'XL', 'Free Size'];
        $warnaOptions  = ['Hitam', 'Merah', 'Biru', 'Hijau', 'Abu-abu', 'Orange', 'Coklat'];
        $pesanOptions  = [
            'Mohon alat dalam kondisi baik saat dikirim.',
            'Tolong sertakan manual pemakaian jika ada.',
            'Saya butuh pengiriman ke alamat saya.',
            'Apakah bisa diantar lebih awal?',
            'Terima kasih, ditunggu konfirmasinya.',
            'Mohon dipacking dengan aman.',
            'Alat camping untuk 4 orang ya.',
            'Akan digunakan untuk pendakian.',
            '',
        ];

        $userIndex = 0;
        $getNextUser = function () use (&$userIds, &$userIndex) {
            $id = $userIds[$userIndex % count($userIds)];
            $userIndex++;
            return $id;
        };

        // ================================================================
        // 20 Penyewaan SELESAI (masa lalu)
        // ================================================================
        for ($i = 1; $i <= 20; $i++) {
            $userId      = $getNextUser();
            $mulai       = Carbon::today()->subDays(rand(14, 90));
            $selesai     = $mulai->copy()->addDays(rand(3, 10));
            $createdAt   = $mulai->copy()->subDays(rand(1, 5));

            $penyewaan = Penyewaan::create([
                'id_user'          => $userId,
                'tanggal_mulai'    => $mulai->toDateString(),
                'tanggal_selesai'  => $selesai->toDateString(),
                'pesan'            => $pesanOptions[array_rand($pesanOptions)],
                'status_penyewaan' => 'selesai',
                'created_at'       => $createdAt,
                'updated_at'       => $selesai,
            ]);

            $this->buatDetailDanPembayaran($penyewaan, $produkIds, $hargaOptions, $ukuranOptions, $warnaOptions, 'lunas', $createdAt);
        }

        // ================================================================
        // 15 Penyewaan BERLANGSUNG
        // ================================================================
        for ($i = 1; $i <= 15; $i++) {
            $userId    = $getNextUser();
            $mulai     = Carbon::today()->subDays(rand(1, 5));
            $selesai   = Carbon::today()->addDays(rand(2, 10));
            $createdAt = $mulai->copy()->subDays(rand(1, 3));

            $penyewaan = Penyewaan::create([
                'id_user'          => $userId,
                'tanggal_mulai'    => $mulai->toDateString(),
                'tanggal_selesai'  => $selesai->toDateString(),
                'pesan'            => $pesanOptions[array_rand($pesanOptions)],
                'status_penyewaan' => 'berlangsung',
                'created_at'       => $createdAt,
                'updated_at'       => $createdAt,
            ]);

            $this->buatDetailDanPembayaran($penyewaan, $produkIds, $hargaOptions, $ukuranOptions, $warnaOptions, 'lunas', $createdAt);
        }

        // ================================================================
        // 15 Penyewaan PENDING (menunggu konfirmasi)
        // ================================================================
        for ($i = 1; $i <= 15; $i++) {
            $userId    = $getNextUser();
            $mulai     = Carbon::today()->addDays(rand(1, 14));
            $selesai   = $mulai->copy()->addDays(rand(3, 7));
            $createdAt = now()->subHours(rand(1, 48));

            $penyewaan = Penyewaan::create([
                'id_user'          => $userId,
                'tanggal_mulai'    => $mulai->toDateString(),
                'tanggal_selesai'  => $selesai->toDateString(),
                'pesan'            => $pesanOptions[array_rand($pesanOptions)],
                'status_penyewaan' => 'pending',
                'created_at'       => $createdAt,
                'updated_at'       => $createdAt,
            ]);

            $this->buatDetailDanPembayaran($penyewaan, $produkIds, $hargaOptions, $ukuranOptions, $warnaOptions, 'Belum lunas', $createdAt);
        }

        $this->command->info('✅ PenyewaanSeeder: 50 penyewaan dibuat (20 Selesai + 15 Berlangsung + 15 Pending)');
    }

    /**
     * Buat 1-3 detail_penyewaan dan 1 pembayaran_penyewaan untuk satu penyewaan.
     */
    private function buatDetailDanPembayaran(
        Penyewaan $penyewaan,
        array $produkIds,
        array $hargaOptions,
        array $ukuranOptions,
        array $warnaOptions,
        string $statusPembayaran,
        \Carbon\Carbon $createdAt
    ): void {
        $jumlahItem      = rand(1, 3);
        $totalSubtotal   = 0;
        $hariSewa        = Carbon::parse($penyewaan->tanggal_mulai)
            ->diffInDays(Carbon::parse($penyewaan->tanggal_selesai));
        $hariSewa        = max($hariSewa, 1);

        for ($j = 0; $j < $jumlahItem; $j++) {
            $idProduk    = $produkIds[array_rand($produkIds)];
            $harga       = $hargaOptions[array_rand($hargaOptions)];
            $qty         = rand(1, 2);
            $subtotal    = $harga * $qty * $hariSewa;
            $totalSubtotal += $subtotal;

            DetailPenyewaan::create([
                'id_penyewaan'             => $penyewaan->id,
                'id_produk'                => $idProduk,
                'id_detail_variant_produk' => null,
                'warna_produk'             => $warnaOptions[array_rand($warnaOptions)],
                'ukuran'                   => $ukuranOptions[array_rand($ukuranOptions)],
                'qty'                      => $qty,
                'harga_sewa_satuan'        => $harga,
                'subtotal'                 => $subtotal,
                'created_at'               => $createdAt,
                'updated_at'               => $createdAt,
            ]);
        }

        $biayaAdmin    = 10000;
        $jaminan       = rand(1, 4) * 50000;
        $totalBayar    = $totalSubtotal + $biayaAdmin;
        $persenPajak   = 5.00;
        $pajakPlatform = (int) round($totalBayar * $persenPajak / 100);

        PembayaranPenyewaan::create([
            'id_penyewaan'         => $penyewaan->id,
            'bukti_pembayaran'     => $statusPembayaran === 'lunas'
                ? 'payments/bukti_' . str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) . '.jpg'
                : null,
            'jaminan_sewa'         => $jaminan,
            'jumlah_pembayaran'    => $totalSubtotal,
            'kembalian_pembayaran' => 0,
            'biaya_admin'          => $biayaAdmin,
            'kurang_pembayaran'    => $statusPembayaran !== 'lunas' ? $totalBayar : 0,
            'total_pembayaran'     => $totalBayar,
            'pajak_platform'       => $statusPembayaran === 'lunas' ? $pajakPlatform : 0,
            'persen_pajak'         => $persenPajak,
            'metode'               => 'transfer',
            'jenis_transaksi'      => 'ambil ditempat',
            'status_pembayaran'    => $statusPembayaran,
            'created_at'           => $createdAt,
            'updated_at'           => $createdAt,
        ]);
    }
}
