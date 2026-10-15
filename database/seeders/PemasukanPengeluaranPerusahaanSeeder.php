<?php

namespace Database\Seeders;

use App\Models\PemasukanPerusahaan;
use App\Models\PengeluaranPerusahaan;
use App\Models\PembayaranPenyewaan;
use App\Models\PembayaranIklan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemasukanPengeluaranPerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds for company income and expense records.
     */
    public function run(): void
    {
        // 1. Bersihkan data lama jika ada
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PemasukanPerusahaan::truncate();
        PengeluaranPerusahaan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::create(2026, 10, 15, 10, 0, 0);

        // 2. Import pemasukan dari transaksi nyata PembayaranPenyewaan (Pajak Platform)
        $penyewaans = PembayaranPenyewaan::where('pajak_platform', '>', 0)->get();
        $pemasukanData = [];

        foreach ($penyewaans as $p) {
            $createdAt = $p->created_at ? Carbon::parse($p->created_at) : $now->copy()->subDays(rand(1, 90));
            $pemasukanData[] = [
                'sumber' => 'Pajak Platform',
                'deskripsi' => 'Pajak platform penyewaan #' . $p->id . ' (' . ($p->metode_pembayaran ?? 'Online') . ')',
                'nominal' => (int) $p->pajak_platform,
                'id_pembayaran_penyewaan' => $p->id,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        // 3. Import pemasukan dari PembayaranIklan yang lunas
        $iklans = PembayaranIklan::where('status_bayar', 'lunas')->get();
        foreach ($iklans as $iklan) {
            $createdAt = $iklan->created_at ? Carbon::parse($iklan->created_at) : $now->copy()->subDays(rand(1, 60));
            $pemasukanData[] = [
                'sumber' => 'Layanan Iklan',
                'deskripsi' => 'Biaya penempatan promosi iklan mitra #' . ($iklan->id_iklan ?? $iklan->id),
                'nominal' => (int) $iklan->total_bayar,
                'id_pembayaran_penyewaan' => null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        // Chunk insert data transaksi riil
        foreach (array_chunk($pemasukanData, 200) as $chunk) {
            DB::table('pemasukan_perusahaan')->insert($chunk);
        }

        // 4. Seeder data historis tahun 2023, 2024, 2025, 2026 (Januari s.d. Oktober)
        $additionalPemasukan = [];
        $additionalPengeluaran = [];

        $years = [
            2023 => ['base_in' => 32000000, 'base_out' => 18000000, 'max_month' => 12],
            2024 => ['base_in' => 42000000, 'base_out' => 24000000, 'max_month' => 12],
            2025 => ['base_in' => 52000000, 'base_out' => 28000000, 'max_month' => 12],
            2026 => ['base_in' => 64000000, 'base_out' => 33000000, 'max_month' => 10], // Berjalan s.d. Oktober
        ];

        $sumberPemasukan = [
            ['sumber' => 'Service', 'desk' => 'Biaya penanganan dan administrasi transaksi checkout pelanggan'],
            ['sumber' => 'Layanan Iklan', 'desk' => 'Paket promosi banner rekomendasi toko mitra'],
            ['sumber' => 'Pajak Platform', 'desk' => 'Bagi hasil komisi sewa perlengkapan outdoor'],
            ['sumber' => 'Kemitraan Mitra', 'desk' => 'Biaya langganan akun mitra prioritas / official store'],
        ];

        $kategoriPengeluaran = [
            ['kat' => 'Server & Cloud', 'sumber' => 'AWS & Cloudflare', 'desk' => 'Langganan server cloud AWS, CDN, & database cluster'],
            ['kat' => 'Gaji & SDM', 'sumber' => 'Payroll Tim', 'desk' => 'Gaji tim pengembang backend, frontend, flutter, dan customer support'],
            ['kat' => 'Operasional', 'sumber' => 'Operasional Kantor', 'desk' => 'Sewa infrastruktur operasional, internet, dan utilitas kantor'],
            ['kat' => 'Pemasaran & Promosi', 'sumber' => 'Digital Marketing', 'desk' => 'Campaign iklan Google Ads, media sosial, dan voucher promo pengguna'],
            ['kat' => 'Maintenance', 'sumber' => 'Keamanan & Tool', 'desk' => 'Lisensi tool monitoring, security audit, dan pemeliharaan rutin sistem'],
            ['kat' => 'Operasional', 'sumber' => 'SMS & OTP Gateway', 'desk' => 'Biaya integrasi WhatsApp OTP & Payment Gateway Fee'],
        ];

        foreach ($years as $year => $config) {
            $maxM = $config['max_month'];
            for ($m = 1; $m <= $maxM; $m++) {
                // Tentukan jumlah hari dalam bulan
                $daysInMonth = ($year == 2026 && $m == 10) ? 15 : Carbon::create($year, $m, 1)->daysInMonth;

                // Buat 4-8 transaksi pemasukan per bulan
                $transCount = ($year == 2026 && $m == 10) ? 12 : rand(4, 7);
                $monthlyTargetIn = $config['base_in'] * (1 + ($m * 0.02) + (rand(-10, 15) / 100));
                $nominalPerTransIn = (int) ($monthlyTargetIn / $transCount);

                for ($i = 0; $i < $transCount; $i++) {
                    $day = rand(1, $daysInMonth);
                    $date = Carbon::create($year, $m, $day, rand(8, 20), rand(0, 59), rand(0, 59));
                    $source = $sumberPemasukan[array_rand($sumberPemasukan)];
                    $nom = (int) ($nominalPerTransIn * (rand(85, 120) / 100));

                    $additionalPemasukan[] = [
                        'sumber' => $source['sumber'],
                        'deskripsi' => $source['desk'] . ' - Periode ' . $date->format('M Y'),
                        'nominal' => $nom,
                        'id_pembayaran_penyewaan' => null,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                }

                // Buat 4-6 transaksi pengeluaran per bulan
                $transCountOut = ($year == 2026 && $m == 10) ? 6 : rand(4, 6);
                $monthlyTargetOut = $config['base_out'] * (1 + ($m * 0.015) + (rand(-8, 12) / 100));
                $nominalPerTransOut = (int) ($monthlyTargetOut / $transCountOut);

                for ($j = 0; $j < $transCountOut; $j++) {
                    $day = rand(1, $daysInMonth);
                    $date = Carbon::create($year, $m, $day, rand(9, 17), rand(0, 59), rand(0, 59));
                    $expense = $kategoriPengeluaran[array_rand($kategoriPengeluaran)];
                    $nomOut = (int) ($nominalPerTransOut * (rand(90, 115) / 100));

                    $additionalPengeluaran[] = [
                        'sumber' => $expense['sumber'],
                        'deskripsi' => $expense['desk'] . ' (' . $date->format('F Y') . ')',
                        'nominal' => $nomOut,
                        'kategori_pengeluaran' => $expense['kat'],
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                }
            }
        }

        // Khusus hari ini: 15 Oktober 2026 (Kamis) & Kemarin (14 Oktober 2026)
        // Pastikan ada transaksi hari ini dan kemarin untuk kartu harian
        $additionalPemasukan[] = [
            'sumber' => 'Service',
            'deskripsi' => 'Biaya administrasi transaksi sewa perlengkapan camping harian',
            'nominal' => 2450000,
            'id_pembayaran_penyewaan' => null,
            'created_at' => Carbon::create(2026, 10, 15, 8, 30, 0),
            'updated_at' => Carbon::create(2026, 10, 15, 8, 30, 0),
        ];
        $additionalPemasukan[] = [
            'sumber' => 'Layanan Iklan',
            'deskripsi' => 'Pembayaran aktivasi slot iklan mitra toko unggulan',
            'nominal' => 3500000,
            'id_pembayaran_penyewaan' => null,
            'created_at' => Carbon::create(2026, 10, 15, 11, 15, 0),
            'updated_at' => Carbon::create(2026, 10, 15, 11, 15, 0),
        ];

        // Transaksi kemarin (14 Oktober 2026)
        $additionalPemasukan[] = [
            'sumber' => 'Service',
            'deskripsi' => 'Biaya layanan sistem pemesanan online',
            'nominal' => 2100000,
            'id_pembayaran_penyewaan' => null,
            'created_at' => Carbon::create(2026, 10, 14, 10, 0, 0),
            'updated_at' => Carbon::create(2026, 10, 14, 10, 0, 0),
        ];
        $additionalPemasukan[] = [
            'sumber' => 'Pajak Platform',
            'deskripsi' => 'Pajak komisi platform penyewaan alat outdoor',
            'nominal' => 3350000,
            'id_pembayaran_penyewaan' => null,
            'created_at' => Carbon::create(2026, 10, 14, 14, 30, 0),
            'updated_at' => Carbon::create(2026, 10, 14, 14, 30, 0),
        ];

        // Pengeluaran hari ini dan kemarin
        $additionalPengeluaran[] = [
            'sumber' => 'Operasional Kantor',
            'deskripsi' => 'Biaya operasional harian & logistik support',
            'nominal' => 1250000,
            'kategori_pengeluaran' => 'Operasional',
            'created_at' => Carbon::create(2026, 10, 15, 9, 0, 0),
            'updated_at' => Carbon::create(2026, 10, 15, 9, 0, 0),
        ];
        $additionalPengeluaran[] = [
            'sumber' => 'Digital Marketing',
            'deskripsi' => 'Biaya harian promosi banner iklan Google & Meta',
            'nominal' => 1800000,
            'kategori_pengeluaran' => 'Pemasaran & Promosi',
            'created_at' => Carbon::create(2026, 10, 14, 11, 0, 0),
            'updated_at' => Carbon::create(2026, 10, 14, 11, 0, 0),
        ];

        // Insert additional pemasukan & pengeluaran in chunks
        foreach (array_chunk($additionalPemasukan, 200) as $chunk) {
            DB::table('pemasukan_perusahaan')->insert($chunk);
        }

        foreach (array_chunk($additionalPengeluaran, 200) as $chunk) {
            DB::table('pengeluaran_perusahaan')->insert($chunk);
        }
    }
}
