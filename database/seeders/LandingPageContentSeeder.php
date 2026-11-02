<?php

namespace Database\Seeders;

use App\Models\LandingPageContent;
use Illuminate\Database\Seeder;

class LandingPageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // 1. HERO SECTION (HOME)
            [
                'section'     => 'hero',
                'key'         => 'main_hero',
                'badge'       => '#1 Marketplace Sewa Alat Camping & Outdoor Indonesia',
                'title'       => 'Sewa Perlengkapan Outdoor <br><span class="text-white">Mudah, Hemat & Terpercaya</span>',
                'subtitle'    => 'Tak perlu repot membeli atau merawat barang mahal. Jelajahi ribuan peralatan camping berkualitas tinggi dari 150+ vendor terverifikasi siap menemani petualangan serumu.',
                'link_text'   => 'Cari Alat',
                'link_url'    => 'landing-page.halaman-sewabarang',
                'icon'        => 'fas fa-fire text-warning',
                'urutan'      => 1,
                'is_active'   => true,
            ],

            // 2. HERO STATS (Fallback / Live display)
            [
                'section'     => 'stats',
                'key'         => 'stat_rating',
                'title'       => '4.9 / 5.0',
                'subtitle'    => 'Rating Kepuasan',
                'icon'        => 'fas fa-star',
                'icon_bg'     => '',
                'urutan'      => 1,
                'is_active'   => true,
            ],
            [
                'section'     => 'stats',
                'key'         => 'stat_vendor',
                'title'       => '150+ Vendor',
                'subtitle'    => 'Mitra Terverifikasi',
                'icon'        => 'fas fa-handshake',
                'icon_bg'     => 'blue',
                'urutan'      => 2,
                'is_active'   => true,
            ],
            [
                'section'     => 'stats',
                'key'         => 'stat_sku',
                'title'       => '10.000+ SKU',
                'subtitle'    => 'Alat Ready & Steril',
                'icon'        => 'fas fa-boxes',
                'icon_bg'     => 'green',
                'urutan'      => 3,
                'is_active'   => true,
            ],

            // 3. ABOUT US (HOME)
            [
                'section'     => 'about',
                'key'         => 'main_about',
                'badge'       => 'Tentang Kami',
                'title'       => 'Revolusi Cara Kamu <br><span class="text-gradient-blue">Menikmati Alam</span>',
                'subtitle'    => '#1 Rental Outdoor Jawa Timur & Nusantara',
                'content'     => 'Di KampSewa, kami percaya bahwa petualangan harus dapat diakses oleh siapa saja tanpa hambatan biaya peralatan yang mahal. Kami hadir sebagai platform marketplace yang mempertemukan pecinta alam dengan penyedia jasa rental perlengkapan outdoor berkualitas tinggi.',
                'link_text'   => 'Kenali Kami Lebih Dekat',
                'link_url'    => 'landing-page.halaman-tentangkami',
                'image'       => 'template/envato/img/about-kamp.jpg',
                'urutan'      => 1,
                'is_active'   => true,
            ],

            // 4. ABOUT FEATURES (2x2 GRID)
            [
                'section'     => 'about_feature',
                'key'         => 'feat_pilihan',
                'title'       => 'Pilihan Terlengkap',
                'content'     => 'Ribuan SKU perlengkapan camping siap sewa dari merek lokal & internasional.',
                'icon'        => 'fas fa-check',
                'urutan'      => 1,
                'is_active'   => true,
            ],
            [
                'section'     => 'about_feature',
                'key'         => 'feat_steril',
                'title'       => 'Jaminan Steril & Bersih',
                'content'     => 'Seluruh alat disanitasi & diperiksa teliti sebelum diserahkan ke tanganmu.',
                'icon'        => 'fas fa-check',
                'urutan'      => 2,
                'is_active'   => true,
            ],
            [
                'section'     => 'about_feature',
                'key'         => 'feat_fleksibel',
                'title'       => 'Durasi Sewa Fleksibel',
                'content'     => 'Sewa harian, mingguan, atau paket khusus ekspedisi sesuai jadwalmu.',
                'icon'        => 'fas fa-check',
                'urutan'      => 3,
                'is_active'   => true,
            ],
            [
                'section'     => 'about_feature',
                'key'         => 'feat_antar',
                'title'       => 'Antar-Jemput Mudah',
                'content'     => 'Layanan kurir instan langsung ke rumah atau titik kumpul keberangkatan.',
                'icon'        => 'fas fa-check',
                'urutan'      => 4,
                'is_active'   => true,
            ],

            // 5. KEUNGGULAN UTAMA (WHY CHOOSE US - 6 CARDS)
            [
                'section'     => 'keunggulan',
                'key'         => 'why_ramah_lingkungan',
                'title'       => 'Bebas Limbah & Ramah Lingkungan',
                'content'     => 'Konsep shared-economy melalui sewa peralatan membantu mengurangi limbah produksi alat outdoor serta mendukung kelestarian alam nusantara.',
                'icon'        => 'fas fa-recycle',
                'icon_bg'     => 'bg-light-emerald',
                'urutan'      => 1,
                'is_active'   => true,
            ],
            [
                'section'     => 'keunggulan',
                'key'         => 'why_hemat_biaya',
                'title'       => 'Hemat Biaya Hingga 70%',
                'content'     => 'Nikmati perlengkapan spesifikasi mendaki gunung tertinggi tanpa perlu mengeluarkan uang jutaan rupiah untuk membeli atau biaya perawatan tahunan.',
                'icon'        => 'fas fa-money-bill-wave',
                'icon_bg'     => 'bg-light-amber',
                'urutan'      => 2,
                'is_active'   => true,
            ],
            [
                'section'     => 'keunggulan',
                'key'         => 'why_coba_dulu',
                'title'       => 'Coba Dulu, Beli Nanti',
                'content'     => 'Ragu membeli carrier atau tenda impian? Sewa dan uji kenyamanannya langsung di medan pendakian sebelum kamu memutuskan untuk membelinya.',
                'icon'        => 'fas fa-search-dollar',
                'icon_bg'     => 'bg-light-blue',
                'urutan'      => 3,
                'is_active'   => true,
            ],
            [
                'section'     => 'keunggulan',
                'key'         => 'why_keamanan',
                'title'       => 'Jaminan Keamanan & Transparansi',
                'content'     => 'Sistem transaksi marketplace bergaransi dengan review autentik dari pengguna, serta kejelasan standar spesifikasi barang yang disewakan.',
                'icon'        => 'fas fa-shield-alt',
                'icon_bg'     => 'bg-light-purple',
                'urutan'      => 4,
                'is_active'   => true,
            ],
            [
                'section'     => 'keunggulan',
                'key'         => 'why_pengiriman_instan',
                'title'       => 'Pengiriman Instan & Praktis',
                'content'     => 'Tak perlu repot bermacet-macetan ke toko rental. Kurir mitra kami siap mengantar dan menjemput perlengkapan tepat waktu di depan rumahmu.',
                'icon'        => 'fas fa-shipping-fast',
                'icon_bg'     => 'bg-light-rose',
                'urutan'      => 5,
                'is_active'   => true,
            ],
            [
                'section'     => 'keunggulan',
                'key'         => 'why_komunitas',
                'title'       => 'Komunitas Petualang Aktif',
                'content'     => 'Bergabung dalam ekosistem ribuan pendaki dan campers untuk berbagi informasi rute pendakian, tips bertahan di alam, hingga mencari rekan mabar.',
                'icon'        => 'fas fa-users',
                'icon_bg'     => 'bg-light-cyan',
                'urutan'      => 6,
                'is_active'   => true,
            ],

            // 6. ALUR SEWA (4 LANGKAH MUDAH)
            [
                'section'     => 'alur_sewa',
                'key'         => 'step_1',
                'step_number' => '01',
                'title'       => 'Cari & Pilih Alat',
                'content'     => 'Jelajahi katalog dan pilih perlengkapan outdoor berkualitas dari vendor terdekat sesuai kebutuhan petualanganmu.',
                'urutan'      => 1,
                'is_active'   => true,
            ],
            [
                'section'     => 'alur_sewa',
                'key'         => 'step_2',
                'step_number' => '02',
                'title'       => 'Tentukan Durasi',
                'content'     => 'Pilih tanggal pemakaian dan durasi sewa yang kamu inginkan dengan harga yang transparan dan bersahabat.',
                'urutan'      => 2,
                'is_active'   => true,
            ],
            [
                'section'     => 'alur_sewa',
                'key'         => 'step_3',
                'step_number' => '03',
                'title'       => 'Terima Perlengkapan',
                'content'     => 'Alat diantar oleh kurir ke lokasi tujuanmu dalam kondisi steril, harum, dan sudah lolos quality check fisik 100%.',
                'urutan'      => 3,
                'is_active'   => true,
            ],
            [
                'section'     => 'alur_sewa',
                'key'         => 'step_4',
                'step_number' => '04',
                'title'       => 'Nikmati Petualangan!',
                'content'     => 'Nikmati momen berharga di alam bebas! Setelah selesai, tinggal kembalikan barang dengan jadwal penjemputan mudah.',
                'urutan'      => 4,
                'is_active'   => true,
            ],

            // 7. HIGH-IMPACT CTA BANNER
            [
                'section'     => 'cta',
                'key'         => 'main_cta',
                'badge'       => 'Aplikasi KampSewa Mobile',
                'title'       => 'Siap Memulai Petualangan Serumu Berikutnya?',
                'subtitle'    => 'Unduh aplikasi KampSewa sekarang di smartphonemu! Nikmati kemudahan booking perlengkapan outdoor di mana saja, kapan saja, lengkap dengan promo diskon perdana untuk pengguna baru.',
                'icon'        => 'fas fa-rocket',
                'urutan'      => 1,
                'is_active'   => true,
            ],

            // 8. TENTANG KAMI PAGE - HERO
            [
                'section'     => 'tentangkami_hero',
                'key'         => 'tk_hero',
                'badge'       => '#1 Marketplace Sewa & Menyewakan Alat Outdoor Indonesia',
                'title'       => 'Mengenal KampSewa <br><span class="text-white">Lebih Dekat</span>',
                'subtitle'    => 'Pelajari visi, misi, dan komitmen kami dalam menyediakan layanan penyewaan perlengkapan camping terbaik untuk mendukung setiap petualangan Anda.',
                'urutan'      => 1,
                'is_active'   => true,
            ],

            // 9. TENTANG KAMI PAGE - CERITA KAMI (STORY)
            [
                'section'     => 'tentangkami_story',
                'key'         => 'tk_story',
                'badge'       => 'Cerita KampSewa',
                'title'       => 'Lahir dari Cinta pada <br><span class="text-gradient">Alam & Petualangan</span>',
                'subtitle'    => 'Solusi Terpercaya Mitra & Renter',
                'content'     => 'Berawal dari kegelisahan para pendaki muda di Jawa Timur yang sering terkendala mahalnya biaya pembelian peralatan outdoor, KampSewa didirikan pada tahun 2024. Kami menciptakan ekosistem terpadu yang mempertemukan pemilik rental lokal dengan jutaan petualang nusantara. Kini, siapa saja dapat menikmati keindahan puncak gunung dan keasrian alam tanpa perlu terbebani oleh mahalnya peralatan.',
                'image'       => 'template/envato/img/home-1.jpg',
                'urutan'      => 1,
                'is_active'   => true,
            ],

            // 10. TENTANG KAMI PAGE - VISI & MISI
            [
                'section'     => 'tentangkami_visi_misi',
                'key'         => 'tk_visi',
                'title'       => 'Visi Kami',
                'subtitle'    => 'Menjadi Platform Ekosistem Outdoor Terbesar di Asia Tenggara',
                'content'     => 'Mewujudkan ekosistem petualangan alam bebas yang inklusif, ramah lingkungan, dan dapat diakses dengan mudah, aman, serta terjangkau oleh seluruh lapisan masyarakat Indonesia.',
                'icon'        => 'fas fa-eye',
                'urutan'      => 1,
                'is_active'   => true,
            ],
            [
                'section'     => 'tentangkami_visi_misi',
                'key'         => 'tk_misi_1',
                'title'       => 'Misi 1: Aksesibilitas Terjangkau',
                'content'     => 'Menyediakan kemudahan sewa perlengkapan outdoor berkualitas tinggi dengan harga yang bersahabat dan transparan bagi semua petualang.',
                'icon'        => 'fas fa-hand-holding-usd',
                'urutan'      => 2,
                'is_active'   => true,
            ],
            [
                'section'     => 'tentangkami_visi_misi',
                'key'         => 'tk_misi_2',
                'title'       => 'Misi 2: Pemberdayaan UMKM Rental',
                'content'     => 'Membantu ratusan pemilik toko rental outdoor lokal mengelola inventaris, memperluas jangkauan pasar, dan meningkatkan omzet usaha lewat platform digital modern.',
                'icon'        => 'fas fa-store',
                'urutan'      => 3,
                'is_active'   => true,
            ],
            [
                'section'     => 'tentangkami_visi_misi',
                'key'         => 'tk_misi_3',
                'title'       => 'Misi 3: Pelestarian Lingkungan',
                'content'     => 'Mengurangi limbah konsumsi peralatan lewat prinsip sharing-economy dan mengedukasi etika konservasi alam bebas "Leave No Trace".',
                'icon'        => 'fas fa-seedling',
                'urutan'      => 4,
                'is_active'   => true,
            ],

            // 11. TENTANG KAMI PAGE - CORE VALUES
            [
                'section'     => 'tentangkami_values',
                'key'         => 'val_integritas',
                'title'       => 'Integritas & Keamanan',
                'content'     => 'Sistem escrow pembayaran transparan, verifikasi KYC identitas resmi, dan jaminan perlindungan bagi pemilik toko serta penyewa.',
                'icon'        => 'fas fa-shield-alt',
                'icon_bg'     => 'bg-light-emerald',
                'urutan'      => 1,
                'is_active'   => true,
            ],
            [
                'section'     => 'tentangkami_values',
                'key'         => 'val_kualitas',
                'title'       => 'Jaminan Kualitas & Higienis',
                'content'     => 'Standar quality control ketat untuk memastikan setiap tenda, sleeping bag, dan cooking set yang disewakan dalam kondisi steril dan siap pakai.',
                'icon'        => 'fas fa-award',
                'icon_bg'     => 'bg-light-amber',
                'urutan'      => 2,
                'is_active'   => true,
            ],
            [
                'section'     => 'tentangkami_values',
                'key'         => 'val_inovasi',
                'title'       => 'Inovasi Berkelanjutan',
                'content'     => 'Pengembangan teknologi aplikasi mobile dan web berkesinambungan dengan fitur pelacakan GPS, rekomendasi AI, dan integrasi kurir instan.',
                'icon'        => 'fas fa-lightbulb',
                'icon_bg'     => 'bg-light-blue',
                'urutan'      => 3,
                'is_active'   => true,
            ],
            [
                'section'     => 'tentangkami_values',
                'key'         => 'val_komunitas',
                'title'       => 'Kebersamaan & Komunitas',
                'content'     => 'Menghubungkan komunitas pecinta alam, mapala kampus, dan relawan konservasi untuk memajukan pariwisata alam nusantara secara lestari.',
                'icon'        => 'fas fa-users',
                'icon_bg'     => 'bg-light-rose',
                'urutan'      => 4,
                'is_active'   => true,
            ],

            // 12. DESTINASI PAGE - HERO
            [
                'section'     => 'destinasi_hero',
                'key'         => 'dest_hero',
                'badge'       => '#1 Eksplorasi Destinasi Wisata Alam & Camping Indonesia',
                'title'       => 'Jelajahi Surga Alam <br><span class="text-white">Nusantara Indonesia</span>',
                'subtitle'    => 'Temukan ratusan destinasi camping, glamping, gunung, pantai, dan air terjun terbaik dari Sabang sampai Merauke. Dilengkapi integrasi lokasi akurat langsung ke Google Maps!',
                'urutan'      => 1,
                'is_active'   => true,
            ],

            // 13. SEWA BARANG PAGE - HERO
            [
                'section'     => 'sewabarang_hero',
                'key'         => 'sb_hero',
                'badge'       => '#1 Marketplace Rental Perlengkapan Outdoor & Camping Indonesia',
                'title'       => 'Sewa Perlengkapan <br><span class="text-white">Kualitas Terjamin & Steril</span>',
                'subtitle'    => 'Lengkapi petualangan alam bebasmu dengan ribuan peralatan camping dari toko vendor terverifikasi. Hemat biaya, praktis, siap pakai, dan terjamin kebersihannya!',
                'urutan'      => 1,
                'is_active'   => true,
            ],

            // 14. TESTIMONI PAGE - HERO
            [
                'section'     => 'testimoni_hero',
                'key'         => 'testi_hero',
                'badge'       => 'APA KATA MEREKA?',
                'title'       => 'Testimoni Pelanggan <span class="text-warning">KampSewa</span>',
                'subtitle'    => 'Dengarkan cerita seru dan ulasan jujur dari ribuan petualang mengenai kemudahan, kecepatan, dan keamanan menyewa perlengkapan camping melalui aplikasi mobile KampSewa.',
                'urutan'      => 1,
                'is_active'   => true,
            ],
        ];

        foreach ($contents as $item) {
            LandingPageContent::updateOrCreate(
                [
                    'section' => $item['section'],
                    'key'     => $item['key'],
                ],
                $item
            );
        }
    }
}
