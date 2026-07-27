<div class="container-fluid py-5" style="background-color: #ffffff !important; border-top: 2px solid #e8ecf0 !important;">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Col 1: Brand & Info KampSewa -->
            <div class="col-md-6 col-lg-4 col-xl-4">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-3 fw-bold" style="color: #1a1f2e !important; font-size: 1.6rem;"><i class="fas fa-campground me-2" style="color: var(--bs-primary);"></i>KampSewa</h4>
                    <p style="color: #555 !important; line-height: 1.7; font-size: 0.92rem; margin-bottom: 20px;">
                        <strong>Marketplace Sewa &amp; Menyewakan Perlengkapan Outdoor No. 1 di Indonesia.</strong> Mempertemukan para pendaki &amp; pecinta alam (Penyewa) dengan para Pemilik Toko / Mitra Rental terpercaya dalam satu ekosistem terpadu.
                    </p>
                    <a href="#" style="color: #555 !important; text-decoration: none; line-height: 2.2;"><i class="fas fa-home me-2 text-primary"></i>Jember, Jawa Timur, Indonesia</a>
                    <a href="mailto:Kampsewa.id@gmail.com" style="color: #555 !important; text-decoration: none; line-height: 2.2;"><i class="fas fa-envelope me-2 text-primary"></i>Kampsewa.id@gmail.com</a>
                    <a href="tel:+6281331640909" style="color: #555 !important; text-decoration: none; line-height: 2.2;"><i class="fas fa-phone me-2 text-primary"></i>+62 813-3164-0909</a>
                    
                    <h5 class="mt-4 mb-2 fw-bold" style="color: #1a1f2e !important; font-size: 1.05rem;"><i class="far fa-clock me-2 text-primary"></i>Jam Operasional Layanan</h5>
                    <p style="color: #555 !important; margin-bottom: 0; font-size: 0.9rem;">Senin - Minggu: 24 Jam Online (App &amp; Web)</p>
                    
                    <div class="d-flex align-items-center mt-3 pt-1">
                        <span class="fw-bold me-3" style="color: #1a1f2e; font-size: 0.9rem;">Ikuti Kami:</span>
                        <a class="btn-square btn btn-primary rounded-circle mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn-square btn btn-primary rounded-circle mx-1" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="btn-square btn btn-primary rounded-circle mx-1" href="#"><i class="fab fa-instagram"></i></a>
                        <a class="btn-square btn btn-primary rounded-circle mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <!-- Col 2: Menu Navigasi -->
            <div class="col-md-6 col-lg-3 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 fw-bold" style="color: #1a1f2e !important; border-bottom: 2px solid var(--bs-primary); display: inline-block; padding-bottom: 8px; width: fit-content;">Menu Navigasi</h4>
                    <a href="{{ route('landing-page.halaman-beranda') }}" class="footer-menu-link"><i class="fas fa-angle-right me-2 text-primary"></i>Home</a>
                    <a href="{{ route('landing-page.halaman-destinasi') }}" class="footer-menu-link"><i class="fas fa-angle-right me-2 text-primary"></i>Destinasi</a>
                    <a href="{{ route('landing-page.halaman-testimoni') }}" class="footer-menu-link"><i class="fas fa-angle-right me-2 text-primary"></i>Testimoni</a>
                    <a href="{{ route('landing-page.halaman-sewabarang') }}" class="footer-menu-link"><i class="fas fa-angle-right me-2 text-primary"></i>Perlengkapan Camping</a>
                    <a href="#download-app" class="footer-menu-link" style="color: var(--bs-primary) !important; font-weight: 600;"><i class="fas fa-mobile-alt me-2 text-warning"></i>Download Aplikasi Mobile</a>
                    <a href="#" class="footer-menu-link mt-2 pt-2 border-top" style="font-size: 0.88rem;"><i class="fas fa-user-shield me-2 text-success"></i><strong>Portal Admin Toko (Mitra)</strong></a>
                </div>
            </div>

            <!-- Col 3: Layanan Pelanggan & Panduan Marketplace (Interactive Accordion) -->
            <div class="col-md-12 col-lg-5 col-xl-5">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-2 fw-bold" style="color: #1a1f2e !important; border-bottom: 2px solid var(--bs-primary); display: inline-block; padding-bottom: 8px; width: fit-content;">Layanan &amp; Panduan Marketplace</h4>
                    <p class="text-muted mb-3" style="font-size: 0.85rem;">Klik pada setiap tata cara di bawah ini untuk melihat penjelasan lengkap sistem marketplace sewa &amp; menyewakan KampSewa:</p>
                    
                    <div class="footer-guide-container" id="footerMarketplaceAccordion">
                        <!-- Guide 1: Untuk Penyewa -->
                        <div class="footer-guide-item">
                            <a class="footer-guide-toggle" data-bs-toggle="collapse" href="#guideRenter" role="button" aria-expanded="false" aria-controls="guideRenter">
                                <span class="guide-icon-left"><i class="fas fa-hiking"></i> <span>Tata Cara Menyewa (Untuk Pendaki / Renter)</span></span>
                                <i class="fas fa-chevron-down guide-arrow"></i>
                            </a>
                            <div class="collapse" id="guideRenter" data-bs-parent="#footerMarketplaceAccordion">
                                <div class="footer-guide-content">
                                    <strong>Panduan Sewa Alat Outdoor via Mobile App / Web:</strong>
                                    <ol>
                                        <li><strong>Cari &amp; Bandingkan:</strong> Telusuri katalog perlengkapan camping berdasarkan lokasi kota, kategori, dan tanggal sewa via Aplikasi Mobile atau Web KampSewa.</li>
                                        <li><strong>Booking Instan:</strong> Pilih toko rental terverifikasi dengan rating terbaik, lalu lakukan pemesanan dan pembayaran secara aman melalui sistem escrow marketplace.</li>
                                        <li><strong>Ambil atau Diantar:</strong> Ambil perlengkapan langsung ke gerai toko mitra atau gunakan layanan pesan antar. Serahkan identitas resmi (KTP/SIM/KTM) sebagai jaminan standar sewa.</li>
                                        <li><strong>Kembalikan Tepat Waktu:</strong> Kembalikan alat sesuai masa sewa dalam kondisi baik untuk mendapatkan poin reward serta ulasan positif dari toko!</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Guide 2: Untuk Pemilik Toko / Vendor -->
                        <div class="footer-guide-item">
                            <a class="footer-guide-toggle" data-bs-toggle="collapse" href="#guideVendor" role="button" aria-expanded="false" aria-controls="guideVendor">
                                <span class="guide-icon-left"><i class="fas fa-store-alt"></i> <span>Tata Cara Menyewakan (Untuk Pemilik Toko / Mitra)</span></span>
                                <i class="fas fa-chevron-down guide-arrow"></i>
                            </a>
                            <div class="collapse" id="guideVendor" data-bs-parent="#footerMarketplaceAccordion">
                                <div class="footer-guide-content">
                                    <strong>Kelola Usaha Rentalmu dengan Website Admin Manajemen Toko:</strong>
                                    <ol>
                                        <li><strong>Daftar Gratis Menjadi Mitra:</strong> Daftarkan toko rental outdoor atau barang camping pribadimu ke dalam ekosistem marketplace KampSewa tanpa biaya pendaftaran.</li>
                                        <li><strong>Manajemen via Website Admin:</strong> Gunakan portal <em>Web Admin Khusus Mitra</em> untuk mengatur stok katalog barang, harga sewa harian, jadwal ketersediaan (kalender booking), serta promo toko dari komputer/laptop.</li>
                                        <li><strong>Terima Pesanan Real-Time:</strong> Dapatkan notifikasi pesanan masuk dari pendaki di seluruh Indonesia dan lakukan verifikasi persetujuan sewa dengan 1 klik.</li>
                                        <li><strong>Pantau Pendapatan &amp; Laporan:</strong> Pantau statistik penyewaan, riwayat transaksi, dan pencairan dana hasil sewa langsung ke rekening bank usaha Anda secara transparan.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Guide 3: Syarat & Ketentuan -->
                        <div class="footer-guide-item">
                            <a class="footer-guide-toggle" data-bs-toggle="collapse" href="#guideTerms" role="button" aria-expanded="false" aria-controls="guideTerms">
                                <span class="guide-icon-left"><i class="fas fa-file-contract"></i> <span>Syarat &amp; Ketentuan Jaminan Sewa</span></span>
                                <i class="fas fa-chevron-down guide-arrow"></i>
                            </a>
                            <div class="collapse" id="guideTerms" data-bs-parent="#footerMarketplaceAccordion">
                                <div class="footer-guide-content">
                                    <ul>
                                        <li><strong>Verifikasi Identitas:</strong> Untuk menjaga keamanan bersama di dalam marketplace, Penyewa wajib menunjukkan/menitipkan identitas asli yang sah (KTP/SIM/Paspor) kepada Pemilik Toko saat serah terima barang.</li>
                                        <li><strong>Kondisi Barang:</strong> Barang yang disewa wajib dijaga dengan baik. Kerusakan akibat kelalaian atau keterlambatan pengembalian akan dikenakan biaya ganti rugi/denda sesuai tarif standar yang tercantum pada perjanjian toko rental.</li>
                                        <li><strong>Perlindungan Mitra &amp; Renter:</strong> KampSewa menjamin keaslian spesifikasi barang dari toko terverifikasi dan memberikan garansi mediasi jika terjadi ketidaksesuaian pesanan.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Guide 4: Pusat Bantuan (FAQ) -->
                        <div class="footer-guide-item">
                            <a class="footer-guide-toggle" data-bs-toggle="collapse" href="#guideHelp" role="button" aria-expanded="false" aria-controls="guideHelp">
                                <span class="guide-icon-left"><i class="fas fa-question-circle"></i> <span>Pusat Bantuan &amp; Layanan Mediasi (FAQ)</span></span>
                                <i class="fas fa-chevron-down guide-arrow"></i>
                            </a>
                            <div class="collapse" id="guideHelp" data-bs-parent="#footerMarketplaceAccordion">
                                <div class="footer-guide-content">
                                    <p class="mb-2"><strong>Butuh Bantuan saat Sewa atau Kelola Toko?</strong></p>
                                    <ul>
                                        <li><strong>Perpanjangan Sewa Darurat:</strong> Jika terjebak cuaca buruk di gunung, segera ajukan perpanjangan masa sewa langsung melalui aplikasi sebelum masa habis agar tidak terkena denda maksimal.</li>
                                        <li><strong>Layanan Mediasi:</strong> Tim Customer Service KampSewa siap membantu memediasi kendala komunikasi antara Penyewa dan Pemilik Toko secara adil 24/7.</li>
                                        <li><strong>Kontak Dukungan:</strong> Email: <a href="mailto:Kampsewa.id@gmail.com" class="text-primary fw-bold">Kampsewa.id@gmail.com</a> | WhatsApp CS: <strong class="text-dark">+62 813-3164-0909</strong>.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Copyright Start -->
<div class="container-fluid py-4" style="background-color: #f0f3f7 !important; border-top: 1px solid #dee2e6 !important;">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-md-6 text-center text-md-end mb-md-0" style="color: #666 !important; font-size: 0.82rem;">
                <i class="fas fa-copyright me-2" style="color: var(--bs-primary);"></i>
                <a href="{{ route('landing-page.halaman-beranda') }}" style="color: #333 !important; font-weight: 600; text-decoration: none;">KampSewa Marketplace</a>, Hak Cipta Dilindungi.
            </div>
            <div class="col-md-6 text-center text-md-start" style="color: #666 !important; font-size: 0.82rem;">
                Dikembangkan untuk Ekosistem Outdoor &copy; {{ date('Y') }}
            </div>
        </div>
    </div>
</div>
<!-- Copyright End -->

<!-- Back to Top -->
<a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>