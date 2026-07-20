<?php

namespace Database\Seeders;

use App\Models\DetailIklan;
use App\Models\Iklan;
use App\Models\Pemasukan;
use App\Models\PembayaranIklan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IklanSeeder extends Seeder
{
    /**
     * Seeder iklan realistis:
     * - 10 Aktif  : tayang sekarang, sudah bayar lunas
     * - 10 Pending: antrian masa depan, sudah bayar lunas
     * - 10 Selesai: sudah berakhir di masa lalu, sudah bayar lunas
     *
     * Poster diambil dari public/posters.json.
     * Setiap iklan memiliki 1 detail_iklan dan 1 pembayaran_iklan yang konsisten.
     */
    public function run(): void
    {
        // Muat poster dari JSON
        $posterPath = public_path('posters.json');
        $posters    = file_exists($posterPath)
            ? json_decode(file_get_contents($posterPath), true)
            : [];

        // Ambil user type=0 (customer) sebagai pengiklan
        $userIds = User::where('type', 0)->inRandomOrder()->limit(30)->pluck('id')->toArray();
        if (empty($userIds)) {
            // Fallback jika tidak ada user
            $userIds = User::inRandomOrder()->limit(30)->pluck('id')->toArray();
        }

        $judulOptions = [
            'Promo Tenda Dome Murah Berkualitas',
            'Sewa Alat Camping Lengkap',
            'Diskon Sleeping Bag Premium',
            'Paket Camping Keluarga Hemat',
            'Promo Akhir Tahun Alat Outdoor',
            'Flash Sale Perlengkapan Camping',
            'Rental Carrier Backpack Terbaik',
            'Promo Spesial Tenda Gunung',
            'Paket Sewa Lengkap Weekend Camping',
            'Diskon Besar Peralatan Hiking',
            'Promo Matras Tidur Outdoor',
            'Sewa Tenda Camping Murah',
            'Penawaran Spesial Akhir Pekan',
            'Jasa Sewa Alat Outdoor Lengkap',
            'Paket Hemat Camping Bersama',
            'Diskon Ransel Gunung Berkualitas',
            'Promo Kompor Camping Portable',
            'Sewa Lampu Tenda LED Murah',
            'Flash Sale Jas Hujan Outdoor',
            'Paket Sewa Alat Lengkap Murah',
            'Promo Sarung Tangan Gunung',
            'Rental Alat Camping Murah',
            'Penawaran Terbaik Alat Outdoor',
            'Diskon Sepatu Hiking Premium',
            'Paket Sewa Alat Camping Keluarga',
            'Promo Helm Gunung Safety',
            'Sewa Tenda Tunnel Berkualitas',
            'Flash Sale Windbreaker Outdoor',
            'Paket Camping Weekend Hemat',
            'Diskon Peralatan Outdoor Lengkap',
        ];

        $metodeOptions  = ['gopay', 'bca_va', 'bni_va', 'mandiri_va', 'credit_card'];
        $hargaOptions   = [150000, 200000, 300000, 500000];

        $userIndex   = 0;
        $posterIndex = 0;
        $judulIndex  = 0;

        $getNextPoster = function () use (&$posters, &$posterIndex) {
            if (empty($posters)) return 'https://via.placeholder.com/1280x720?text=Iklan+Camping';
            $poster = $posters[$posterIndex % count($posters)];
            $posterIndex++;
            return $poster;
        };

        $getNextUser = function () use (&$userIds, &$userIndex) {
            $id = $userIds[$userIndex % count($userIds)];
            $userIndex++;
            return $id;
        };

        $getJudul = function () use (&$judulOptions, &$judulIndex) {
            $judul = $judulOptions[$judulIndex % count($judulOptions)];
            $judulIndex++;
            return $judul;
        };

        // ================================================================
        // 10 Iklan AKTIF (tayang sekarang)
        // ================================================================
        for ($i = 1; $i <= 10; $i++) {
            $userId    = $getNextUser();
            $harga     = $hargaOptions[array_rand($hargaOptions)];
            $metode    = $metodeOptions[array_rand($metodeOptions)];
            $mulai     = Carbon::today()->subDays(rand(1, 10));
            $akhir     = Carbon::today()->addDays(rand(5, 25));
            $createdAt = $mulai->copy()->subDays(rand(3, 7));

            $iklan = Iklan::create([
                'id_user'    => $userId,
                'poster'     => $getNextPoster(),
                'judul'      => $getJudul(),
                'sub_judul'  => 'Kualitas terbaik, harga terjangkau untuk petualangan Anda',
                'deskripsi'  => 'Kami menyediakan berbagai peralatan camping berkualitas tinggi dengan harga yang sangat terjangkau. Cocok untuk keluarga, komunitas, maupun solo traveler. Hubungi kami sekarang dan dapatkan penawaran terbaik!',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            DetailIklan::create([
                'id_iklan'      => $iklan->id,
                'tanggal_mulai' => $mulai->toDateString(),
                'tanggal_akhir' => $akhir->toDateString(),
                'harga_iklan'   => $harga,
                'status_iklan'  => 'Aktif',
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);

            PembayaranIklan::create([
                'id_iklan'                    => $iklan->id,
                'id_user'                     => $userId,
                'midtrans_order_id'           => 'IKLAN-' . strtoupper(Str::random(8)) . '-' . $createdAt->format('YmdHis'),
                'payment_type'                => $metode,
                'midtrans_transaction_status' => 'settlement',
                'metode_bayar'                => $metode,
                'total_bayar'                 => $harga,
                'status_bayar'                => 'lunas',
                'created_at'                  => $createdAt,
                'updated_at'                  => $createdAt,
            ]);

            // Catat ke pemasukan
            Pemasukan::create([
                'id_user'    => $userId,
                'sumber'     => 'Layanan Iklan',
                'deskripsi'  => 'Pembayaran iklan #' . $iklan->id . ' — ' . $createdAt->format('d M Y'),
                'nominal'    => $harga,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // ================================================================
        // 10 Iklan PENDING (antrian masa depan)
        // ================================================================
        for ($i = 1; $i <= 10; $i++) {
            $userId    = $getNextUser();
            $harga     = $hargaOptions[array_rand($hargaOptions)];
            $metode    = $metodeOptions[array_rand($metodeOptions)];
            $mulai     = Carbon::today()->addDays(rand(1, 30));
            $akhir     = $mulai->copy()->addDays(rand(7, 21));
            $createdAt = Carbon::today()->subDays(rand(0, 3));

            $iklan = Iklan::create([
                'id_user'    => $userId,
                'poster'     => $getNextPoster(),
                'judul'      => $getJudul(),
                'sub_judul'  => 'Segera dapatkan penawaran eksklusif ini sebelum kehabisan',
                'deskripsi'  => 'Jangan lewatkan kesempatan emas ini! Dapatkan berbagai peralatan camping premium dengan harga spesial. Stok terbatas, segera pesan sekarang.',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            DetailIklan::create([
                'id_iklan'      => $iklan->id,
                'tanggal_mulai' => $mulai->toDateString(),
                'tanggal_akhir' => $akhir->toDateString(),
                'harga_iklan'   => $harga,
                'status_iklan'  => 'Pending',
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);

            PembayaranIklan::create([
                'id_iklan'                    => $iklan->id,
                'id_user'                     => $userId,
                'midtrans_order_id'           => 'IKLAN-' . strtoupper(Str::random(8)) . '-' . $createdAt->format('YmdHis'),
                'payment_type'                => $metode,
                'midtrans_transaction_status' => 'settlement',
                'metode_bayar'                => $metode,
                'total_bayar'                 => $harga,
                'status_bayar'                => 'lunas',
                'created_at'                  => $createdAt,
                'updated_at'                  => $createdAt,
            ]);

            Pemasukan::create([
                'id_user'    => $userId,
                'sumber'     => 'Layanan Iklan',
                'deskripsi'  => 'Pembayaran iklan #' . $iklan->id . ' (pending antrian) — ' . $createdAt->format('d M Y'),
                'nominal'    => $harga,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // ================================================================
        // 10 Iklan SELESAI (sudah berakhir di masa lalu)
        // ================================================================
        for ($i = 1; $i <= 10; $i++) {
            $userId    = $getNextUser();
            $harga     = $hargaOptions[array_rand($hargaOptions)];
            $metode    = $metodeOptions[array_rand($metodeOptions)];
            $akhir     = Carbon::today()->subDays(rand(1, 30));
            $mulai     = $akhir->copy()->subDays(rand(7, 21));
            $createdAt = $mulai->copy()->subDays(rand(3, 7));

            $iklan = Iklan::create([
                'id_user'    => $userId,
                'poster'     => $getNextPoster(),
                'judul'      => $getJudul(),
                'sub_judul'  => 'Promo terbatas yang telah sukses menarik ribuan penyewa',
                'deskripsi'  => 'Terima kasih telah menggunakan layanan iklan kami. Kampanye iklan Anda telah berhasil menjangkau banyak pelanggan potensial.',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            DetailIklan::create([
                'id_iklan'      => $iklan->id,
                'tanggal_mulai' => $mulai->toDateString(),
                'tanggal_akhir' => $akhir->toDateString(),
                'harga_iklan'   => $harga,
                'status_iklan'  => 'Selesai',
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);

            PembayaranIklan::create([
                'id_iklan'                    => $iklan->id,
                'id_user'                     => $userId,
                'midtrans_order_id'           => 'IKLAN-' . strtoupper(Str::random(8)) . '-' . $createdAt->format('YmdHis'),
                'payment_type'                => $metode,
                'midtrans_transaction_status' => 'settlement',
                'metode_bayar'                => $metode,
                'total_bayar'                 => $harga,
                'status_bayar'                => 'lunas',
                'created_at'                  => $createdAt,
                'updated_at'                  => $createdAt,
            ]);

            Pemasukan::create([
                'id_user'    => $userId,
                'sumber'     => 'Layanan Iklan',
                'deskripsi'  => 'Pembayaran iklan #' . $iklan->id . ' (selesai) — ' . $createdAt->format('d M Y'),
                'nominal'    => $harga,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('✅ IklanSeeder: 30 iklan dibuat (10 Aktif + 10 Pending + 10 Selesai)');
    }
}
