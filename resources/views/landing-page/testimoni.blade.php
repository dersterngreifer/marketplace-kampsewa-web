@include('landing-page.header')
<body>

        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar & Hero Start -->
        <div class="container-fluid position-relative p-0">
            @include('landing-page.navbar')

            <!-- Hero Carousel with Window-Fit Height & Cinematic Dark Overlay -->
            <div class="carousel-header">
                <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active" style="min-height: 100vh; position: relative;">
                            <!-- Background Image (Tetap sesuai instruksi user) -->
                            <img src="{{asset('template/envato/img/home-6.jpg')}}" class="img-fluid" alt="Testimoni Pelanggan KampSewa" style="width: 100%; height: 100vh; object-fit: cover;">
                            
                            <!-- Dark Overlay -->
                            <div class="testimoni-hero-overlay"></div>
                            
                            <!-- Cinematic Title & Caption -->
                            <div class="carousel-caption" style="z-index: 5;">
                                <div class="testimoni-hero-content text-center">
                                    <span class="badge bg-warning text-dark px-4 py-2 rounded-pill fw-bold mb-3 shadow-sm font-display" style="letter-spacing: 2px; font-size: 0.85rem;"><i class="fas fa-quote-right me-2"></i> APA KATA MEREKA?</span>
                                    <h1 class="display-3 fw-bolder text-white mb-4 font-display" style="text-shadow: 0 4px 25px rgba(0,0,0,0.7);">Testimoni Pelanggan <span class="text-warning">KampSewa</span></h1>
                                    <p class="lead text-light mb-4 mx-auto" style="max-width: 760px; font-size: 1.15rem; line-height: 1.8; opacity: 0.95; text-shadow: 0 2px 10px rgba(0,0,0,0.6);">Dengarkan cerita seru dan ulasan jujur dari ribuan petualang mengenai kemudahan, kecepatan, dan keamanan menyewa perlengkapan camping melalui aplikasi mobile KampSewa.</p>
                                    <div class="d-flex justify-content-center gap-3 mt-4">
                                        <a href="#testimoniGridSection" class="btn btn-warning rounded-pill py-3 px-5 fw-bold text-dark shadow font-display"><i class="fas fa-star me-2"></i> Baca Semua Ulasan</a>
                                        <button type="button" class="btn btn-outline-light rounded-pill py-3 px-5 fw-bold font-display" onclick="showWriteReviewModal()"><i class="fas fa-pen me-2"></i> Tulis Ulasan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Carousel End -->
        </div>

        <!-- Trust Impact Banner Section -->
        <div class="container position-relative" style="z-index: 20;">
            <div class="testimoni-trust-banner">
                <div class="testimoni-trust-grid">
                    <div class="trust-stat-box">
                        <h3>4.9 <span style="font-size: 1.3rem; color: var(--ks-gray-400);">/ 5.0</span></h3>
                        <p><i class="fas fa-star text-warning me-1"></i> Rating Kepuasan App</p>
                    </div>
                    <div class="trust-stat-box">
                        <h3>1.250+</h3>
                        <p><i class="fas fa-check-circle text-primary me-1"></i> Ulasan Pengguna</p>
                    </div>
                    <div class="trust-stat-box">
                        <h3>100%</h3>
                        <p><i class="fas fa-shield-alt text-warning me-1"></i> Transaksi Aman</p>
                    </div>
                    <div class="trust-stat-box">
                        <h3>150+</h3>
                        <p><i class="fas fa-store text-danger me-1"></i> Vendor Mitra Resmi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Testimonial Grid & Filter Section Start -->
        <section class="py-5 mt-4" id="testimoniGridSection">
            <div class="container py-4">
                <!-- Section Header -->
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <span class="badge-section mb-3"><i class="fas fa-heart text-danger me-2"></i> ULASAN APLIKASI KAMPSEWA</span>
                    <h2 class="section-title-modern">Feedback <span class="text-gradient">Nyata</span> Pengguna Aplikasi</h2>
                    <p class="text-muted mt-3" style="font-size: 1.05rem; line-height: 1.7;">Bukti kepuasan petualang dari berbagai daerah di Indonesia terhadap fitur, kecepatan bertransaksi, dan pelayanan dari platform mobile KampSewa.</p>
                    
                    <!-- Simplified Filtering & Sorting Controls (Sesuai instruksi: Sangat Bagus, Bagus, Cukup + Sorting asc/desc/terkecil/terbesar) -->
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mt-4 pt-2">
                        <!-- Category Filter Buttons -->
                        <div class="d-flex flex-wrap justify-content-center gap-2" id="testimoniFilterContainer">
                            <button class="testimoni-filter-btn active" data-filter="all">
                                <i class="fas fa-th-large text-warning"></i> <span>Semua (9)</span>
                            </button>
                            <button class="testimoni-filter-btn" data-filter="5">
                                <i class="fas fa-star text-warning"></i> <span>Sangat Bagus (6)</span>
                            </button>
                            <button class="testimoni-filter-btn" data-filter="4">
                                <i class="fas fa-thumbs-up text-primary"></i> <span>Bagus (2)</span>
                            </button>
                            <button class="testimoni-filter-btn" data-filter="3">
                                <i class="fas fa-smile text-secondary"></i> <span>Cukup (1)</span>
                            </button>
                        </div>

                        <!-- Sorting Select (Asc, Desc, Terkecil, Terbesar) -->
                        <div class="ms-lg-3">
                            <select id="testimoniSortSelect" class="form-select rounded-pill px-4 py-2 border border-2 fw-bold text-dark" style="width: auto; background: #F8FAFC; cursor: pointer; font-size: 0.9rem;">
                                <option value="newest">⏰ Ulasan Terbaru</option>
                                <option value="highest">📈 Rating Terbesar (5.0 → 3.0)</option>
                                <option value="lowest">📉 Rating Terkecil (3.0 → 5.0)</option>
                                <option value="asc">🅰️ Nama Pelanggan (A - Z)</option>
                                <option value="desc">🇿 Nama Pelanggan (Z - A)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Grid (Fokus pada feedback KampSewa App, tanpa info menyewa dari... dan tanpa garis hijau) -->
                <div class="row g-4" id="testimoniCardsContainer">
                    <!-- Review Card 1 (Rating 5.0) -->
                    <div class="col-lg-4 col-md-6 testimoni-card-item" data-rating="5" data-name="Herman Maulana" data-date="2026-07-20">
                        <div class="testimoni-card-modern">
                            <i class="fas fa-quote-right testimoni-quote-icon"></i>
                            <div class="testimoni-content-wrap">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs fw-bold">5.0 / 5.0</span>
                                </div>
                                <p class="testimoni-text">"Aplikasi KampSewa bener-bener game changer buat pendaki! Proses booking alat super cepat, antarmuka aplikasinya sangat ramah pengguna, dan konversi transaksinya 100% aman. Nggak perlu ribet cari sewaan alat offline lagi!"</p>
                                <div class="testimoni-user-footer">
                                    <img src="{{asset('template/envato/img/Testimoni-1.jpg')}}" class="testimoni-user-img" alt="Herman Maulana" onerror="this.onerror=null; this.src='{{asset('template/envato/img/home-1.jpg')}}';">
                                    <div class="testimoni-user-info">
                                        <h5>Herman Maulana</h5>
                                        <p><i class="fas fa-map-marker-alt text-danger"></i> Lumajang, Jawa Timur • <span class="text-muted">Jul 2026</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 2 (Rating 5.0) -->
                    <div class="col-lg-4 col-md-6 testimoni-card-item" data-rating="5" data-name="Lukman Ikhsan" data-date="2026-06-15">
                        <div class="testimoni-card-modern">
                            <i class="fas fa-quote-right testimoni-quote-icon"></i>
                            <div class="testimoni-content-wrap">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs fw-bold">5.0 / 5.0</span>
                                </div>
                                <p class="testimoni-text">"Sangat puas dengan fitur deposit digital dan navigasi peta vendor di aplikasi KampSewa! Semua informasi harga sewa transparan tanpa biaya tersembunyi. Customer service aplikasinya juga fast respons 24/7!"</p>
                                <div class="testimoni-user-footer">
                                    <img src="{{asset('template/envato/img/Testimoni-2.jpg')}}" class="testimoni-user-img" alt="Lukman Ikhsan" onerror="this.onerror=null; this.src='{{asset('template/envato/img/home-2.jpg')}}';">
                                    <div class="testimoni-user-info">
                                        <h5>Lukman Ikhsan</h5>
                                        <p><i class="fas fa-map-marker-alt text-danger"></i> Sukabumi, Jawa Barat • <span class="text-muted">Jun 2026</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 3 (Rating 4.8 -> Bagus) -->
                    <div class="col-lg-4 col-md-6 testimoni-card-item" data-rating="4" data-name="Teti Kusuma" data-date="2026-07-10">
                        <div class="testimoni-card-modern">
                            <i class="fas fa-quote-right testimoni-quote-icon"></i>
                            <div class="testimoni-content-wrap">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs fw-bold">4.8 / 5.0</span>
                                </div>
                                <p class="testimoni-text">"Fitur pencarian dan filter di mobile app KampSewa sangat membantu! Saya bisa langsung nemuin vendor yang lokasinya paling dekat dengan titik keberangkatan ke Kawah Ijen. Aplikasi yang wajib diinstall petualang!"</p>
                                <div class="testimoni-user-footer">
                                    <img src="{{asset('template/envato/img/Testimoni-3.jpg')}}" class="testimoni-user-img" alt="Teti Kusuma" onerror="this.onerror=null; this.src='{{asset('template/envato/img/home-3.jpg')}}';">
                                    <div class="testimoni-user-info">
                                        <h5>Teti Kusuma</h5>
                                        <p><i class="fas fa-map-marker-alt text-danger"></i> Bondowoso, Jawa Timur • <span class="text-muted">Jul 2026</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 4 (Rating 5.0) -->
                    <div class="col-lg-4 col-md-6 testimoni-card-item" data-rating="5" data-name="Yusuf Akmal" data-date="2026-05-20">
                        <div class="testimoni-card-modern">
                            <i class="fas fa-quote-right testimoni-quote-icon"></i>
                            <div class="testimoni-content-wrap">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs fw-bold">5.0 / 5.0</span>
                                </div>
                                <p class="testimoni-text">"Desain aplikasinya juara banget, sangat mudah dipahami bahkan untuk pemula. Fitur notifikasi pengingat pengembalian alat sangat membantu agar kita tidak kena denda keterlambatan. Sukses terus untuk KampSewa!"</p>
                                <div class="testimoni-user-footer">
                                    <img src="{{asset('template/envato/img/Testimoni-4.jpg')}}" class="testimoni-user-img" alt="Yusuf Akmal" onerror="this.onerror=null; this.src='{{asset('template/envato/img/home-4.jpg')}}';">
                                    <div class="testimoni-user-info">
                                        <h5>Yusuf Akmal</h5>
                                        <p><i class="fas fa-map-marker-alt text-danger"></i> Banyuwangi, Jawa Timur • <span class="text-muted">May 2026</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 5 (Rating 4.5 -> Bagus) -->
                    <div class="col-lg-4 col-md-6 testimoni-card-item" data-rating="4" data-name="Rina Wulandari" data-date="2026-07-02">
                        <div class="testimoni-card-modern">
                            <i class="fas fa-quote-right testimoni-quote-icon"></i>
                            <div class="testimoni-content-wrap">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs fw-bold">4.5 / 5.0</span>
                                </div>
                                <p class="testimoni-text">"Sangat terkesan dengan standarisasi kualitas di aplikasi ini. Semua vendor yang mitra KampSewa sudah terverifikasi ketat, jadi kita sebagai pengguna merasa sangat tenang dan aman saat menyewa perlengkapan camping."</p>
                                <div class="testimoni-user-footer">
                                    <img src="{{asset('template/envato/img/Testimoni-1.jpg')}}" class="testimoni-user-img" alt="Rina Wulandari" onerror="this.onerror=null; this.src='{{asset('template/envato/img/home-5.jpg')}}';">
                                    <div class="testimoni-user-info">
                                        <h5>Rina Wulandari</h5>
                                        <p><i class="fas fa-map-marker-alt text-danger"></i> Bandung, Jawa Barat • <span class="text-muted">Jul 2026</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 6 (Rating 5.0) -->
                    <div class="col-lg-4 col-md-6 testimoni-card-item" data-rating="5" data-name="Dimas Aditya" data-date="2026-06-28">
                        <div class="testimoni-card-modern">
                            <i class="fas fa-quote-right testimoni-quote-icon"></i>
                            <div class="testimoni-content-wrap">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs fw-bold">5.0 / 5.0</span>
                                </div>
                                <p class="testimoni-text">"Satu kata: Luar biasa! Aplikasi KampSewa bikin rencana pendakian gunung jadi jauh lebih terorganisir. Sistem pembayaran di app sangat lengkap mulai dari QRIS, E-Wallet, sampai virtual account bank."</p>
                                <div class="testimoni-user-footer">
                                    <img src="{{asset('template/envato/img/Testimoni-2.jpg')}}" class="testimoni-user-img" alt="Dimas Aditya" onerror="this.onerror=null; this.src='{{asset('template/envato/img/home-1.jpg')}}';">
                                    <div class="testimoni-user-info">
                                        <h5>Dimas Aditya</h5>
                                        <p><i class="fas fa-map-marker-alt text-danger"></i> Malang, Jawa Timur • <span class="text-muted">Jun 2026</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 7 (Rating 5.0) -->
                    <div class="col-lg-4 col-md-6 testimoni-card-item" data-rating="5" data-name="Reza Rahadian" data-date="2026-04-12">
                        <div class="testimoni-card-modern">
                            <i class="fas fa-quote-right testimoni-quote-icon"></i>
                            <div class="testimoni-content-wrap">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs fw-bold">5.0 / 5.0</span>
                                </div>
                                <p class="testimoni-text">"Sebagai anak gunung yang sering naik turun jalur Jawa Tengah, aplikasi KampSewa adalah penyelamat! Kita bisa cek ketersediaan stok barang secara real-time langsung dari layar HP tanpa harus telepon vendor satu-satu."</p>
                                <div class="testimoni-user-footer">
                                    <img src="{{asset('template/envato/img/Testimoni-4.jpg')}}" class="testimoni-user-img" alt="Reza Rahadian" onerror="this.onerror=null; this.src='{{asset('template/envato/img/home-3.jpg')}}';">
                                    <div class="testimoni-user-info">
                                        <h5>Reza Rahadian</h5>
                                        <p><i class="fas fa-map-marker-alt text-danger"></i> Semarang, Jawa Tengah • <span class="text-muted">Apr 2026</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 8 (Rating 5.0) -->
                    <div class="col-lg-4 col-md-6 testimoni-card-item" data-rating="5" data-name="Siska Pratiwi" data-date="2026-07-05">
                        <div class="testimoni-card-modern">
                            <i class="fas fa-quote-right testimoni-quote-icon"></i>
                            <div class="testimoni-content-wrap">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs fw-bold">5.0 / 5.0</span>
                                </div>
                                <p class="testimoni-text">"Pengalaman pertama pakai aplikasi KampSewa sangat berkesan. Sangat praktis untuk booking alat camping keluarga jauh-jauh hari sebelum tanggal keberangkatan. Aplikasinya juga ringan dan jarang bug."</p>
                                <div class="testimoni-user-footer">
                                    <img src="{{asset('template/envato/img/Testimoni-3.jpg')}}" class="testimoni-user-img" alt="Siska Pratiwi" onerror="this.onerror=null; this.src='{{asset('template/envato/img/home-2.jpg')}}';">
                                    <div class="testimoni-user-info">
                                        <h5>Siska Pratiwi</h5>
                                        <p><i class="fas fa-map-marker-alt text-danger"></i> Surabaya, Jawa Timur • <span class="text-muted">Jul 2026</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Card 9 (Rating 3.8 -> Cukup) -->
                    <div class="col-lg-4 col-md-6 testimoni-card-item" data-rating="3" data-name="Putri Ayu" data-date="2026-06-10">
                        <div class="testimoni-card-modern">
                            <i class="fas fa-quote-right testimoni-quote-icon"></i>
                            <div class="testimoni-content-wrap">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs fw-bold">3.8 / 5.0</span>
                                </div>
                                <p class="testimoni-text">"Sistem aplikasi KampSewa sudah bagus dan sangat memudahkan penggiat alam. Semoga kedepannya semakin banyak fitur promo diskon atau poin reward untuk pengguna setia yang sering menyewa perlengkapan!"</p>
                                <div class="testimoni-user-footer">
                                    <img src="{{asset('template/envato/img/Testimoni-1.jpg')}}" class="testimoni-user-img" alt="Putri Ayu" onerror="this.onerror=null; this.src='{{asset('template/envato/img/home-4.jpg')}}';">
                                    <div class="testimoni-user-info">
                                        <h5>Putri Ayu</h5>
                                        <p><i class="fas fa-map-marker-alt text-danger"></i> Yogyakarta, DIY • <span class="text-muted">Jun 2026</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Tulis Pengalaman CTA -->
                <div class="testimoni-cta-section my-5">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-4 mb-lg-0">
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-3 font-display"><i class="fas fa-star me-1"></i> SUARA KONSUMEN</span>
                            <h2 class="display-5 fw-bolder text-white font-display mb-3">Punya Pengalaman Menarik Bersama Aplikasi KampSewa?</h2>
                            <p class="text-light mb-0" style="font-size: 1.1rem; line-height: 1.7; opacity: 0.9; max-width: 650px;">
                                Bagikan feedback jujurmu mengenai performa aplikasi, kenyamanan fitur booking, atau saran perbaikan untuk tim developer kami!
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end text-center">
                            <button type="button" class="btn btn-warning btn-lg px-4 py-3 rounded-pill fw-bold shadow-lg text-dark font-display d-inline-flex align-items-center gap-2" onclick="showWriteReviewModal()">
                                <i class="fas fa-pen-fancy"></i> <span>Tulis Ulasan Kamu</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modern Testimonial Grid & Filter Section End -->

        <!-- Modal Tulis Ulasan ( Diperbaiki: Teks Header Putih & Custom Inputs ) -->
        <div class="modal fade" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 24px; border: none; box-shadow: 0 25px 60px rgba(0,0,0,0.25); overflow: hidden;">
                    <!-- Header Modal (Teks Putih #FFFFFF Sesuai Instruksi) -->
                    <div class="p-4" style="background: linear-gradient(135deg, var(--ks-dark) 0%, var(--ks-primary-dark) 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-display fw-bold" style="color: #FFFFFF !important;"><i class="fas fa-star text-warning me-2"></i> Tulis Ulasan & Testimoni</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <!-- Body Modal dengan Custom Premium Input styling -->
                    <div class="modal-body p-4 bg-white">
                        <form id="reviewForm" onsubmit="submitReviewForm(event)">
                            <div class="mb-3">
                                <label class="testimoni-label-modern">Nama Lengkap</label>
                                <input type="text" class="form-control testimoni-input-modern" placeholder="Contoh: Budi Santoso" required>
                            </div>
                            <div class="mb-3">
                                <label class="testimoni-label-modern">Kota Domisili</label>
                                <input type="text" class="form-control testimoni-input-modern" placeholder="Contoh: Surabaya, Jawa Timur" required>
                            </div>
                            <div class="mb-3">
                                <label class="testimoni-label-modern">Kategori Penilaian Aplikasi KampSewa</label>
                                <select class="form-select testimoni-input-modern fw-bold text-dark" required>
                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Bagus (5.0 / 5.0)</option>
                                    <option value="4">⭐⭐⭐⭐ Bagus (4.0 / 5.0)</option>
                                    <option value="3">⭐⭐⭐ Cukup (3.0 / 5.0)</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="testimoni-label-modern">Ulasan Pengalaman Menggunakan Aplikasi KampSewa</label>
                                <textarea class="form-control testimoni-input-modern" rows="4" placeholder="Ceritakan kemudahan fitur booking, transparansi deposit, atau layanan customer support di aplikasi kami..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold font-display shadow-sm">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Ulasan Aplikasi Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('landing-page.halamanbawah')
       
      @include('landing-page.footer')

      <!-- Script Kustom Filter & Sorting Testimoni -->
      <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterButtons = document.querySelectorAll(".testimoni-filter-btn");
            const sortSelect = document.getElementById("testimoniSortSelect");
            const container = document.getElementById("testimoniCardsContainer");
            const cardItems = Array.from(document.querySelectorAll(".testimoni-card-item"));

            let currentFilter = "all";

            function filterAndSortCards() {
                const sortValue = sortSelect ? sortSelect.value : "newest";

                // Filter
                let filtered = cardItems.filter(card => {
                    const rating = card.getAttribute("data-rating");
                    if (currentFilter === "all") return true;
                    return rating === currentFilter;
                });

                // Sort
                filtered.sort((a, b) => {
                    const ratingA = parseInt(a.getAttribute("data-rating")) || 5;
                    const ratingB = parseInt(b.getAttribute("data-rating")) || 5;
                    const nameA = (a.getAttribute("data-name") || "").toLowerCase();
                    const nameB = (b.getAttribute("data-name") || "").toLowerCase();
                    const dateA = a.getAttribute("data-date") || "";
                    const dateB = b.getAttribute("data-date") || "";

                    if (sortValue === "highest") {
                        return ratingB - ratingA;
                    } else if (sortValue === "lowest") {
                        return ratingA - ratingB;
                    } else if (sortValue === "asc") {
                        return nameA.localeCompare(nameB);
                    } else if (sortValue === "desc") {
                        return nameB.localeCompare(nameA);
                    } else {
                        // newest by default
                        return dateB.localeCompare(dateA);
                    }
                });

                // Clear container and append filtered
                container.innerHTML = "";
                if (filtered.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-search fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="fw-bold text-dark">Belum ada ulasan di kategori ini</h5>
                            <p class="text-muted text-sm">Coba pilih kategori penilaian atau filter lainnya.</p>
                        </div>
                    `;
                } else {
                    filtered.forEach(card => {
                        container.appendChild(card);
                        card.style.display = "block";
                        card.style.opacity = "0";
                        setTimeout(() => {
                            card.style.opacity = "1";
                            card.style.transition = "opacity 0.35s ease";
                        }, 20);
                    });
                }
            }

            // Filter Button click events
            filterButtons.forEach(btn => {
                btn.addEventListener("click", function() {
                    filterButtons.forEach(b => b.classList.remove("active"));
                    this.classList.add("active");
                    currentFilter = this.getAttribute("data-filter");
                    filterAndSortCards();
                });
            });

            // Sorting change event
            if (sortSelect) {
                sortSelect.addEventListener("change", function() {
                    filterAndSortCards();
                });
            }
        });

        // Function untuk buka Modal Tulis Ulasan
        function showWriteReviewModal() {
            const modal = new bootstrap.Modal(document.getElementById('writeReviewModal'));
            modal.show();
        }

        // Function submit form review
        function submitReviewForm(e) {
            e.preventDefault();
            const modalEl = document.getElementById('writeReviewModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            alert("Terima kasih! Feedback dan testimoni Anda mengenai aplikasi KampSewa telah berhasil dikirim dan akan segera dipublikasikan di halaman ini.");
            e.target.reset();
        }
      </script>
</body>