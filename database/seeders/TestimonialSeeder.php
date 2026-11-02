<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'nama'      => 'Herman Maulana',
                'peran'     => 'Pendaki Gunung Semeru',
                'kota'      => 'Lumajang, Jawa Timur',
                'avatar'    => 'Testimoni-1.jpg',
                'rating'    => 5,
                'ulasan'    => 'Aplikasi KampSewa bener-bener game changer buat pendaki! Proses booking alat super cepat, antarmuka aplikasinya sangat ramah pengguna, dan konversi transaksinya 100% aman. Nggak perlu ribet cari sewaan alat offline lagi!',
                'urutan'    => 1,
                'is_active' => true,
            ],
            [
                'nama'      => 'Siti Nurhaliza',
                'peran'     => 'Family Camper Jatim',
                'kota'      => 'Malang, Jawa Timur',
                'avatar'    => 'Testimoni-2.jpg',
                'rating'    => 5,
                'ulasan'    => 'Sewa cooking set lengkap sama kompor gas portable buat kemping keluarga di Songgolangit. Alatnya bersih, steril, dan packaging rapi banget. Vendor mitra KampSewa beneran amanah dan profesional!',
                'urutan'    => 2,
                'is_active' => true,
            ],
            [
                'nama'      => 'Bayu Prasetyo',
                'peran'     => 'Solo Trekker Nusantara',
                'kota'      => 'Bandung, Jawa Barat',
                'avatar'    => 'Testimoni-3.jpg',
                'rating'    => 5,
                'ulasan'    => 'Sewa carrier Deuter 60L buat summit Rinjani. Barangnya original, busanya tebal dan punggung nggak sakit sama sekali. Harga sewa harian transparan tanpa biaya siluman. Bakal jadi aplikasi wajib tiap mau naik gunung!',
                'urutan'    => 3,
                'is_active' => true,
            ],
            [
                'nama'      => 'Kevin Jonathan',
                'peran'     => 'Outdoor Content Creator',
                'kota'      => 'Denpasar, Bali',
                'avatar'    => 'Testimoni-4.jpg',
                'rating'    => 5,
                'ulasan'    => 'Pertama kali sewa alat outdoor via online dan kaget prosesnya se-praktis ini. Fitur pencarian toko terdekat ngebantu banget saat butuh headlamp dan flysheet darurat pas camp di Kintamani. Mantap KampSewa!',
                'urutan'    => 4,
                'is_active' => true,
            ],
            [
                'nama'      => 'Dimas Aditya',
                'peran'     => 'Ketua Mapala Kampus',
                'kota'      => 'Yogyakarta, DIY',
                'avatar'    => 'Testimoni-1.jpg',
                'rating'    => 4,
                'ulasan'    => 'Pelayanan customer service di aplikasi sangat cepat tanggap pas saya butuh ganti tanggal booking tenda. Sistem escrow-nya bikin tenang karena dana baru diteruskan setelah barang diterima dengan aman.',
                'urutan'    => 5,
                'is_active' => true,
            ],
            [
                'nama'      => 'Citra Kirana',
                'peran'     => 'Glamping Enthusiast',
                'kota'      => 'Jakarta Selatan, DKI Jakarta',
                'avatar'    => 'Testimoni-2.jpg',
                'rating'    => 4,
                'ulasan'    => 'Pilihan tenda dome sangat variatif dari merk Eiger sampai Quechua. Pengembalian barang juga mudah dengan kurir mitra. Sangat recommended buat yang nggak mau ribet beli alat mahal cuma buat liburan sesekali.',
                'urutan'    => 6,
                'is_active' => true,
            ],
            [
                'nama'      => 'Wahyu Nugroho',
                'peran'     => 'Pecinta Alam Jateng',
                'kota'      => 'Semarang, Jawa Tengah',
                'avatar'    => 'Testimoni-3.jpg',
                'rating'    => 3,
                'ulasan'    => 'Kondisi tenda dan matras bersih dan layak pakai. Hanya saja verifikasi identitas agak teliti demi keamanan, tapi justru itu bukti sistem keamanannya bekerja. Sukses selalu buat KampSewa!',
                'urutan'    => 7,
                'is_active' => true,
            ],
            [
                'nama'      => 'Raditya Pratama',
                'peran'     => 'Backpacker & Camper',
                'kota'      => 'Surabaya, Jawa Timur',
                'avatar'    => 'Testimoni-4.jpg',
                'rating'    => 5,
                'ulasan'    => 'Fitur rekomendasi toko terdekat berdasarkan GPS di aplikasi mobile akurat banget! Langsung nemu vendor rental di dekat stasiun Malang pas mau trip ke Bromo. Menghemat waktu persiapan banget!',
                'urutan'    => 8,
                'is_active' => true,
            ],
            [
                'nama'      => 'Nadia Safitri',
                'peran'     => 'Traveler Mandiri',
                'kota'      => 'Bogor, Jawa Barat',
                'avatar'    => 'Testimoni-2.jpg',
                'rating'    => 5,
                'ulasan'    => 'Sewa sleeping bag 4 buah buat acara diklat kampus. Barangnya wangi dan steril, bukan yang apek seperti rental konvensional. Pasti bakal repeat order lagi untuk agenda pendakian berikutnya!',
                'urutan'    => 9,
                'is_active' => true,
            ],
            [
                'nama'      => 'Putri Ayu',
                'peran'     => 'Camping Traveler',
                'kota'      => 'Yogyakarta, DIY',
                'avatar'    => 'Testimoni-1.jpg',
                'rating'    => 3,
                'ulasan'    => 'Sistem aplikasi KampSewa sudah bagus dan sangat memudahkan penggiat alam. Semoga kedepannya semakin banyak fitur promo diskon atau poin reward untuk pengguna setia yang sering menyewa perlengkapan!',
                'urutan'    => 10,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $item) {
            Testimonial::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}
