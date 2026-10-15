<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find default author admin
        $admin = User::where('type', 1)->first() ?? User::first();
        $adminId = $admin ? $admin->id : null;

        // Truncate first to avoid duplicates
        Article::truncate();

        $now = now();

        // Helper locations detector
        $locationsMap = [
            'bali'         => 'Bali, Indonesia',
            'selat bali'   => 'Banyuwangi, Jawa Timur',
            'banyuwangi'   => 'Banyuwangi, Jawa Timur',
            'malang'       => 'Malang, Jawa Timur',
            'batu'         => 'Kota Batu, Jawa Timur',
            'bromo'        => 'Taman Nasional Bromo Tengger Semeru, Jawa Timur',
            'semeru'       => 'Taman Nasional Bromo Tengger Semeru, Jawa Timur',
            'jogja'        => 'DI Yogyakarta',
            'yogyakarta'   => 'DI Yogyakarta',
            'bandung'      => 'Bandung, Jawa Barat',
            'bogor'        => 'Bogor, Jawa Barat',
            'puncak'       => 'Puncak, Jawa Barat',
            'sukabumi'     => 'Sukabumi, Jawa Barat',
            'cianjur'      => 'Cianjur, Jawa Barat',
            'pangrango'    => 'Taman Nasional Gunung Gede Pangrango, Jawa Barat',
            'rinjani'      => 'Lombok, Nusa Tenggara Barat',
            'lombok'       => 'Lombok, Nusa Tenggara Barat',
            'labuan bajo'  => 'Labuan Bajo, Nusa Tenggara Timur',
            'flores'       => 'Flores, Nusa Tenggara Timur',
            'raja ampat'   => 'Raja Ampat, Papua Barat',
            'dieng'        => 'Dieng Plateau, Jawa Tengah',
            'semarang'     => 'Semarang, Jawa Tengah',
            'solo'         => 'Surakarta, Jawa Tengah',
            'jakarta'      => 'DKI Jakarta',
            'karimunjawa'  => 'Karimunjawa, Jawa Tengah',
            'belitung'     => 'Belitung, Kepulauan Bangka Belitung',
            'lampung'      => 'Lampung, Sumatera',
            'padang'       => 'Padang, Sumatera Barat',
            'medan'        => 'Sumatera Utara',
            'toba'         => 'Danau Toba, Sumatera Utara',
            'wakatobi'     => 'Wakatobi, Sulawesi Tenggara',
            'manado'       => 'Manado, Sulawesi Utara',
            'toraja'       => 'Tana Toraja, Sulawesi Selatan',
        ];

        $defaultLocations = [
            'Taman Nasional Bromo Tengger Semeru, Jawa Timur',
            'Kawasan Wisata Alam Batu, Jawa Timur',
            'Kawasan Camping Ground Gunung Gede Pangrango, Jawa Barat',
            'Dataran Tinggi Dieng, Jawa Tengah',
            'Kawasan Wisata Kintamani, Bali',
            'Kawasan Wisata Ranca Upas Ciwidey, Bandung',
            'Kawasan Alam Sukamantri, Taman Nasional Gunung Salak, Bogor',
            'Lembah Sembalun, Taman Nasional Gunung Rinjani, Lombok',
            'Kawasan Wisata Kaliurang, Sleman, DI Yogyakarta',
            'Taman Wisata Alam Gunung Pancar, Sentul, Bogor',
        ];

        // 1. Seed Wisata from public/detik_wisata_450.json
        $detikFile = public_path('detik_wisata_450.json');
        if (file_exists($detikFile)) {
            $detikData = json_decode(file_get_contents($detikFile), true) ?: [];
            $this->command->info('Seeding ' . count($detikData) . ' Wisata from detik_wisata_450.json...');

            $articlesBatch = [];
            foreach ($detikData as $index => $item) {
                $title = trim($item['title'] ?? 'Destinasi Wisata Camping Rekomendasi');
                $desc = trim($item['description'] ?? '');
                $photo = trim($item['photo_url'] ?? '');
                $url = trim($item['article_url'] ?? '');

                // Detect location
                $matchedLocation = null;
                $searchText = strtolower($title . ' ' . $desc);
                foreach ($locationsMap as $keyword => $loc) {
                    if (str_contains($searchText, $keyword)) {
                        $matchedLocation = $loc;
                        break;
                    }
                }
                if (!$matchedLocation) {
                    $matchedLocation = $defaultLocations[$index % count($defaultLocations)];
                }

                // Vary created_at to support time filters:
                // First 20 items: today
                // Next 50 items: this week (1-6 days ago)
                // Next 100 items: this month (7-25 days ago)
                // Remainder: earlier this year
                if ($index < 20) {
                    $createdAt = (clone $now)->subHours(rand(1, 10))->subMinutes(rand(1, 59));
                } elseif ($index < 70) {
                    $createdAt = (clone $now)->subDays(rand(1, 6))->subHours(rand(1, 12));
                } elseif ($index < 170) {
                    $createdAt = (clone $now)->subDays(rand(8, 25))->subHours(rand(1, 12));
                } else {
                    $createdAt = (clone $now)->subDays(rand(35, 180))->subHours(rand(1, 12));
                }

                $content = $desc . "\n\n" .
                    "Destinasi wisata alam ini menawarkan panorama menakjubkan dan udara sejuk yang sangat cocok untuk melepas penat dari rutinitas perkotaan. Kawasan ini telah menjadi salah satu pilihan favorit para pegiat outdoor dan keluarga yang ingin menikmati sensasi bermalam di bawah hamparan bintang (stargazing).\n\n" .
                    "Aksesibilitas menuju lokasi cukup mudah dijangkau dengan kendaraan roda dua maupun roda empat. Area perkemahan menyediakan tanah lapang berkontur datar, sumber air bersih, toilet umum, pos pengamanan, serta persewaan perlengkapan camping bagi pengunjung yang tidak membawa tenda pribadi.\n\n" .
                    "Tips berkunjung: Pastikan memeriksa prakiraan cuaca terkini sebelum berangkat, siapkan pakaian hangat, gunakan sepatu trekking anti-slip, dan selalu bawa kantong sampah untuk menjaga kelestarian alam bersama.";

                $slug = Str::slug($title);
                if (empty($slug)) {
                    $slug = 'wisata-rekomendasi';
                }
                $slug = $slug . '-' . ($index + 1);

                $articlesBatch[] = [
                    'tipe'              => 'wisata',
                    'judul'             => $title,
                    'slug'              => Str::limit($slug, 250, ''),
                    'gambar'            => $photo,
                    'deskripsi_singkat' => Str::limit($desc, 450),
                    'konten'            => $content,
                    'lokasi'            => $matchedLocation,
                    'sumber_url'        => $url,
                    'sumber_nama'       => 'Detik Travel',
                    'author_id'         => $adminId,
                    'status'            => 'published',
                    'views_count'       => rand(120, 4800),
                    'created_at'        => $createdAt,
                    'updated_at'        => $createdAt,
                ];

                if (count($articlesBatch) >= 100) {
                    Article::insert($articlesBatch);
                    $articlesBatch = [];
                }
            }

            if (!empty($articlesBatch)) {
                Article::insert($articlesBatch);
                $articlesBatch = [];
            }
        }

        // 2. Seed Berita from public/bbc_wisata_500.json
        $bbcFile = public_path('bbc_wisata_500.json');
        if (file_exists($bbcFile)) {
            $bbcData = json_decode(file_get_contents($bbcFile), true) ?: [];
            $this->command->info('Seeding ' . count($bbcData) . ' Berita from bbc_wisata_500.json...');

            $articlesBatch = [];
            foreach ($bbcData as $index => $item) {
                $title = trim($item['title'] ?? 'Kabar & Berita Outdoor Terkini');
                $desc = trim($item['description'] ?? '');
                $photo = trim($item['photo_url'] ?? '');
                $url = trim($item['article_url'] ?? '');

                // Vary created_at for time filters
                if ($index < 25) {
                    $createdAt = (clone $now)->subHours(rand(1, 8))->subMinutes(rand(1, 55));
                } elseif ($index < 80) {
                    $createdAt = (clone $now)->subDays(rand(1, 6))->subHours(rand(1, 12));
                } elseif ($index < 200) {
                    $createdAt = (clone $now)->subDays(rand(8, 25))->subHours(rand(1, 12));
                } else {
                    $createdAt = (clone $now)->subDays(rand(35, 180))->subHours(rand(1, 12));
                }

                $content = $desc . "\n\n" .
                    "Perkembangan kabar dan peristiwa ini menjadi perhatian penting bagi publik luas. Pemahaman mendalam mengenai isu terkait sangat diperlukan agar masyarakat dan pecinta kegiatan alam dapat mengambil langkah antisipatif serta edukatif dalam kehidupan sehari-hari.\n\n" .
                    "Pihak-pihak terkait dan otoritas setempat terus melakukan pemantauan berkala guna memastikan situasi terkendali dan masyarakat mendapatkan informasi yang akurat dari sumber terpercaya.\n\n" .
                    "Bagi Anda yang hendak melakukan mobilitas atau perjalanan ke area terkait, disarankan untuk selalu memantau perkembangan berita resmi dan mempersiapkan perlengkapan serta keselamatan secara optimal.";

                $slug = Str::slug($title);
                if (empty($slug)) {
                    $slug = 'berita-terkini';
                }
                $slug = $slug . '-berita-' . ($index + 1);

                $articlesBatch[] = [
                    'tipe'              => 'berita',
                    'judul'             => $title,
                    'slug'              => Str::limit($slug, 250, ''),
                    'gambar'            => $photo,
                    'deskripsi_singkat' => Str::limit($desc, 450),
                    'konten'            => $content,
                    'lokasi'            => null,
                    'sumber_url'        => $url,
                    'sumber_nama'       => 'BBC News Indonesia',
                    'author_id'         => $adminId,
                    'status'            => 'published',
                    'views_count'       => rand(180, 5200),
                    'created_at'        => $createdAt,
                    'updated_at'        => $createdAt,
                ];

                if (count($articlesBatch) >= 100) {
                    Article::insert($articlesBatch);
                    $articlesBatch = [];
                }
            }

            if (!empty($articlesBatch)) {
                Article::insert($articlesBatch);
                $articlesBatch = [];
            }
        }

        $this->command->info('ArticleSeeder completed successfully! Total articles in DB: ' . Article::count());
    }
}
