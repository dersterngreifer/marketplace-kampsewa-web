<p align="center">
  <a href="https://github.com" target="_blank">
    <img src="public/assets/logo/logo-kampsewa.png" width="180" alt="KampSewa Logo">
  </a>
</p>

<h1 align="center">🏕️ KampSewa Web</h1>

<p align="center">
  <strong>Platform Marketplace Persewaan Peralatan Camping & Outdoor Berbasis Laravel & Blade</strong>
</p>

<p align="center">
  Solusi digital persewaan perlengkapan petualangan luar ruangan (outdoor, hiking, camping) terintegrasi untuk kalangan mahasiswa dan umum dengan arsitektur modern, sistem template modular <strong>Blade</strong>, real-time chat, dan payment gateway.
</p>

<p align="center">
  <a href="https://github.com"><img src="https://img.shields.io/badge/Language-Blade-f7523f?style=for-the-badge&logo=laravel&logoColor=white" alt="Blade"></a>
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 10"></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-^8.1-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.1+"></a>
  <a href="https://laravel.com/docs/reverb"><img src="https://img.shields.io/badge/Realtime-Laravel_Reverb-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Reverb"></a>
  <a href="https://midtrans.com"><img src="https://img.shields.io/badge/Payment-Midtrans-002B49?style=for-the-badge" alt="Midtrans"></a>
  <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/UI-Tailwind%20%26%20Bootstrap-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="UI Styling"></a>
  <a href="https://mysql.com"><img src="https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License"></a>
</p>

---

## 📖 Tentang KampSewa

**KampSewa** adalah platform marketplace penyewaan alat berkemah dan aktivitas luar ruangan yang menjembatani para pemilik rental alat outdoor dengan komunitas pecinta alam, pendaki, maupun mahasiswa kampus. 

Aplikasi web ini dibangun dengan memanfaatkan kekuatan **Laravel Framework** dan arsitektur tampilan responsif berbasis **Blade Template Engine**, menghadirkan pengalaman pengguna yang cepat, terstruktur, dan elegan.

---

## 🌟 Sorotan Utama & Arsitektur Blade

Project ini dirancang dengan struktur template **Blade** yang modular, memudahkan kustomisasi UI serta menjaga keterbacaan kode:

```text
resources/views/
├── auth/            # Halaman otentikasi (Login, Register, OTP Lupa Password)
├── components/      # Reusable Blade UI Components (Modal, Card, Navbar, Form)
├── customers/       # Dashboard penyewa & pemilik toko persewaan
├── developers/      # Dashboard manajemen sistem (Administrator / Developer)
├── landing-page/    # Etalase publik, katalog produk, artikel & wisata
└── layouts/         # Master layouts & kerangka tema aplikasi
```

- **Reusable Component System:** Memanfaatkan `<x-slot>` dan directive `@include` / `@extends` untuk konsistensi layout di seluruh halaman.
- **Blade Optimization:** Bersih dari boilerplate yang membebani statistik repository, menjadikan **Blade** sebagai bahasa dominan pada visualisasi GitHub.

---

## ✨ Fitur Unggulan

### 🏕️ 1. Manajemen Katalog & Persewaan Perlengkapan
- Katalog lengkap: tenda dome, sleeping bag, carrier, kompor outdoor, nesting, matras, headlamp, tracking pole, dll.
- Dukungan varian produk, galeri multi-foto, stok inventaris, dan harga sewa harian.
- Perhitungan sewa otomatis berdasarkan durasi hari rental dan sistem denda keterlambatan pengembalian.

### 💬 2. Real-Time Chat (Laravel Reverb WebSocket)
- Komunikasi dua arah langsung (instant messaging) antara penyewa (customer) dan pemilik rental (toko).
- Status pesan terkirim, live typing, dan unread badge secara real-time.

### 💳 3. Pembayaran Digital Otomatis (Midtrans Payment Gateway)
- Integrasi Midtrans Snap untuk kemudahan checkout otomatis.
- Mendukung metode pembayaran QRIS (GoPay, ShopeePay), Virtual Account (BCA, BNI, BRI, Mandiri), dan Transfer Bank.
- Webhook callback instan untuk sinkronisasi status pembayaran penyewaan maupun pembayaran iklan.

### 📍 4. Geocoding & Lokasi Toko Rental (Nominatim Geocoder)
- Menemukan toko persewaan outdoor terdekat berdasarkan lokasi geospasial pengguna.
- Penentuan titik jemput dan delivery alat outdoor.

### 📊 5. Laporan Keuangan & Export PDF (DomPDF)
- Dashboard grafik finansial: tren pemasukan, pengeluaran, keuntungan, serta rekap laba kotor & bersih.
- Cetak otomatis invoice transaksi rental dan laporan keuangan periodik dalam format PDF.

### 🛡️ 6. Multi-Role & Sistem Verifikasi Identitas (KYC)
- **Customer / Toko:** Menyewa produk, membuka toko rental, mengelola etalase alat, mengelola pesanan masuk, dan melihat analitik toko.
- **Developer / Admin:** Monitoring platform, verifikasi KYC pengguna, moderasi produk/iklan, manajemen artikel edukasi, dan pembukuan komisi platform.

---

## 🛠️ Tech Stack & Dependensi

| Kategori | Teknologi | Deskripsi |
|---|---|---|
| **Language** | **Blade** & **PHP ^8.1** | Templating engine utama dan bahasa backend |
| **Framework** | **Laravel 10.x** | Full-stack web application framework |
| **Realtime Service** | **Laravel Reverb** | Official ultra-fast WebSocket server untuk Laravel |
| **Payment Gateway** | **Midtrans PHP SDK** | Gateway pembayaran multi-channel otomatis |
| **UI Styling** | **Tailwind CSS & Bootstrap** | Kombinasi styling utility & komponen responsif |
| **Build Tool** | **Vite** | Bundler frontend cepat untuk asset JS/CSS |
| **Geocoding** | **Geocoder Nominatim** | Penyedia data geolokasi & alamat peta |
| **PDF Generator** | **Barryvdh DomPDF** | Engine generate file invoice & laporan PDF |
| **Alerts** | **SweetAlert (RealRashid)** | Notifikasi interaktif dan dialog konfirmasi elegan |

---

## 🚀 Panduan Memulai (Quick Start)

### Prasyarat Sistem
- PHP >= 8.1 (dengan ekstensi `pdo_mysql`, `mbstring`, `fileinfo`, `curl`, `gd`)
- Composer >= 2.0
- Node.js (v18+) & NPM
- MySQL Database Server

### Langkah Instalasi

1. **Clone Repositori:**
   ```bash
   git clone https://github.com/<username>/marketplace-kampsewa-web.git
   cd marketplace-kampsewa-web
   ```

2. **Install Dependensi:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment:**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Atur Database & Layanan di `.env`:**
   Sesuaikan kredensial database dan kredensial Midtrans / Reverb:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kampsewa_db
   DB_USERNAME=root
   DB_PASSWORD=

   # Reverb WebSocket
   REVERB_APP_ID=your-app-id
   REVERB_APP_KEY=your-app-key
   REVERB_APP_SECRET=your-app-secret
   REVERB_HOST="localhost"
   REVERB_PORT=8080
   REVERB_SCHEME="http"

   # Midtrans Gateway
   MIDTRANS_SERVER_KEY=your-server-key
   MIDTRANS_CLIENT_KEY=your-client-key
   MIDTRANS_IS_PRODUCTION=false
   ```

5. **Migrasi Database & Seeder:**
   ```bash
   php artisan migrate --seed
   ```

6. **Build Asset & Link Storage:**
   ```bash
   php artisan storage:link
   ```

---

## 🏃 Menjalankan Aplikasi

Jalankan perintah berikut pada terminal terpisah untuk menjalankan semua servis secara optimal:

| No | Terminal | Perintah | Fungsi |
|---|---|---|---|
| 1 | **Frontend Dev** | `npm run dev` | Menjalankan Vite hot-reload compiler |
| 2 | **Web Server** | `php artisan serve` | Menjalankan web server lokal di `http://127.0.0.1:8000` |
| 3 | **WebSocket** | `php artisan reverb:start` | Menjalankan server live chat real-time |
| 4 | **Scheduler** | `php artisan schedule:work` | Menjalankan cron pekerjaan terjadwal (opsional) |

---

## 📁 Struktur Direktori

```text
marketplace-kampsewa-web/
├── app/
│   ├── Http/Controllers/    # Controller modular (Auth, Customer, Developer, Landing)
│   ├── Models/                 # Eloquent ORM Models (Produk, Penyewaan, Chat, dll)
│   └── Services/               # Layanan bisnis khusus
├── config/                     # Konfigurasi sistem
├── database/                   # Migrations, seeders, and factories
├── public/                     # Public static files, images, and logo
├── resources/
│   ├── css/ & js/              # Sumber file aset Vite
│   └── views/                  # Blade templates (tampilan modular aplikasi)
├── routes/
│   ├── web.php                 # Route definisi navigasi aplikasi
│   └── channels.php            # Route channel WebSocket Reverb
└── storage/                    # Penyimpanan upload berkas & log
```

---

## 💡 Konfigurasi GitHub Repository

Agar tampilan repositori di GitHub semakin menarik dan mudah ditemukan oleh komunitas pengembang:

1. **Tambahkan Topics di Menu GitHub Repository:**
   > `blade` · `blade-templates` · `laravel` · `php` · `marketplace` · `outdoor-rental` · `reverb-websocket` · `midtrans`
2. **Atur Deskripsi Repository:**
   > *🏕️ KampSewa Web — Platform Marketplace Persewaan Alat Camping & Outdoor berbasis Laravel & Blade Template Engine.*

---

## 📄 Lisensi

Project ini dirilis di bawah lisensi [MIT License](LICENSE).

<p align="center">
  Dibuat dengan ❤️ untuk mendukung kegiatan outdoor dan pecinta alam Indonesia 🏕️
</p>
