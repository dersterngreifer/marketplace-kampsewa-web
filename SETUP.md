# Setup & Running Marketplace Kampsewa Web

## Tahapan Setup

| No | Langkah | Perintah | Keterangan |
|----|---------|----------|------------|
| 1 | Install dependency PHP | `composer install` | Menginstall semua package PHP yang dibutuhkan |
| 2 | Install dependency Frontend | `npm install` | Menginstall semua package JavaScript/CSS yang dibutuhkan |
| 3 | Generate App Key | `php artisan key:generate` | Membuat application key untuk enkripsi |
| 4 | Konfigurasi Environment | Copy `.env.example` menjadi `.env`, lalu atur: <br>- `DB_DATABASE=nama_database` <br>- `DB_USERNAME=root` <br>- `DB_PASSWORD=` <br>- `REVERB_APP_ID=` <br>- `REVERB_APP_KEY=` <br>- `REVERB_APP_SECRET=` | Sesuaikan dengan konfigurasi lokal |
| 5 | Migrasi Database | `php artisan migrate` | Menjalankan migrasi tabel database |
| 6 | Seed Database (opsional) | `php artisan db:seed` | Mengisi data awal/dummy |

## Sesi Menjalankan Aplikasi

Jalankan perintah berikut secara bersamaan di terminal/CMD yang berbeda:

| No | Perintah | Fungsi |
|----|----------|--------|
| 1 | `npm run dev` | Menjalankan Vite dev server untuk asset frontend |
| 2 | `php artisan schedule:work` | Menjalankan scheduler Laravel |
| 3 | `php artisan reverb:start` | Menjalankan WebSocket server Reverb |
| 4 | `php artisan serve` | Menjalankan development server Laravel |

> **Catatan:** Aplikasi dapat diakses di `http://127.0.0.1:8000` setelah `php artisan serve` berjalan.
