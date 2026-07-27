@include('landing-page.header')

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar & Hero Start -->
    <div class="container-fluid position-relative p-0">
        @include('landing-page.navbar')

        <!-- Cinematic About Hero Section -->
        <section class="hero-about-modern">
            <!-- Background Slider -->
            <div class="hero-bg-carousel">
                <div id="heroAboutCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('template/envato/img/home-4.jpg') }}" alt="Tentang KampSewa">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('template/envato/img/about-kamp.jpg') }}" alt="Komunitas Outdoor">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('template/envato/img/home-5.jpg') }}" alt="Camping Adventure">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gradient Overlay -->
            <div class="hero-overlay-gradient"></div>

            <!-- Hero Content -->
            <div class="container about-hero-content">
                <div class="badge-hero-pill">
                    <i class="fas fa-fire text-warning"></i>
                    <span>#1 Marketplace Sewa & Menyewakan Alat Outdoor Indonesia</span>
                </div>

                <h1 class="about-hero-title">
                    Mengenal KampSewa <br>
                    <span class="text-gradient-amber">Lebih Dekat</span>
                </h1>

                <p class="about-hero-desc">
                    Pelajari visi, misi, dan komitmen kami dalam menyediakan layanan penyewaan perlengkapan camping terbaik untuk mendukung setiap petualangan Anda.
                </p>

                <div class="about-hero-stats-bar">
                    <div class="about-stat-pill">
                        <div class="about-stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="about-stat-text">
                            <h4>2024</h4>
                            <span>Tahun Berdiri</span>
                        </div>
                    </div>
                    <div class="about-stat-pill">
                        <div class="about-stat-icon blue">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="about-stat-text">
                            <h4>150+ Vendor</h4>
                            <span>Mitra Terverifikasi</span>
                        </div>
                    </div>
                    <div class="about-stat-pill">
                        <div class="about-stat-icon emerald">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="about-stat-text">
                            <h4>10.000+ SKU</h4>
                            <span>Alat Ready & Steril</span>
                        </div>
                    </div>
                    <div class="about-stat-pill">
                        <div class="about-stat-icon purple">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="about-stat-text">
                            <h4>4.9 / 5.0</h4>
                            <span>Rating Kepuasan</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Navbar & Hero End -->

    <!-- Cerita Kami Start -->
    <section class="story-section-modern">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="story-collage-wrapper">
                        <div class="story-img-main">
                            <img src="{{ asset('template/envato/img/home-1.jpg') }}" alt="Cerita KampSewa">
                        </div>
                        
                        <!-- Floating Year Badge -->
                        <div class="story-floating-badge-2024">
                            <span class="year">2024</span>
                            <span class="label">Tahun Berdiri</span>
                        </div>

                        <!-- Floating Glass Info Badge -->
                        <div class="story-glass-card">
                            <div class="story-glass-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="story-glass-text">
                                <h5>Solusi Terpercaya</h5>
                                <span>Menghubungkan pendaki & vendor rental seluruh Indonesia.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="story-content-box">
                        <span class="badge-section">Cerita Kami</span>
                        <h2>Mendukung Setiap <br><span class="text-gradient-blue">Petualanganmu</span></h2>
                        
                        <p class="story-text-lead">
                            KampSewa lahir dari kecintaan kami terhadap alam bebas dan keinginan untuk membuat kegiatan outdoor lebih mudah diakses oleh semua kalangan. Kami menyadari bahwa membeli peralatan camping berkualitas seringkali membutuhkan biaya yang besar, perawatan ekstra, dan ruang penyimpanan yang tidak sedikit di rumah.
                        </p>
                        <p class="story-text-body">
                            Oleh karena itu, kami hadir sebagai solusi yang praktis, ekonomis, dan berkelanjutan. Dengan menghubungkan para pecinta alam dengan vendor-vendor penyewaan alat outdoor terpercaya di berbagai daerah, kami memastikan setiap orang dapat menikmati keindahan alam dan pengalaman berkemah yang nyaman tanpa batasan finansial maupun logistik.
                        </p>

                        <!-- 2x2 Feature Check Grid -->
                        <div class="story-check-grid">
                            <div class="story-check-item">
                                <div class="story-check-icon"><i class="fas fa-check"></i></div>
                                <span>Lebih Hemat & Praktis</span>
                            </div>
                            <div class="story-check-item">
                                <div class="story-check-icon"><i class="fas fa-check"></i></div>
                                <span>Dukung Vendor Lokal</span>
                            </div>
                            <div class="story-check-item">
                                <div class="story-check-icon"><i class="fas fa-check"></i></div>
                                <span>Kualitas Terjamin</span>
                            </div>
                            <div class="story-check-item">
                                <div class="story-check-icon"><i class="fas fa-check"></i></div>
                                <span>Ramah Lingkungan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Cerita Kami End -->

    <!-- Visi & Misi Start -->
    <section class="vision-mission-dark">
        <div class="container position-relative" style="z-index: 5;">
            <div class="section-header-modern mb-5" style="max-width: 820px;">
                <span class="badge-section" style="background: rgba(245, 158, 11, 0.15); color: #F59E0B;">Visi & Misi</span>
                <h2 class="text-white">Semangat yang <span class="text-gradient-amber">Menggerakkan Kami</span></h2>
                <p style="color: rgba(255, 255, 255, 0.75);">Landasan utama dalam membangun ekosistem penyewaan outdoor yang inklusif, aman, dan berorientasi pada kelestarian alam nusantara.</p>
            </div>

            <div class="row g-4">
                <!-- Visi Card -->
                <div class="col-lg-6">
                    <div class="vm-card-modern">
                        <div class="vm-card-header">
                            <div class="vm-icon-box amber">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div>
                                <h2>Visi Kami</h2>
                                <span>Tujuan & Cita-Cita Masa Depan</span>
                            </div>
                        </div>

                        <div class="visi-quote-box">
                            <i class="fas fa-quote-left visi-quote-icon"></i>
                            <p class="visi-quote-text">
                                "Menjadi platform penyewaan perlengkapan outdoor terdepan dan terpercaya di Indonesia yang menginspirasi lebih banyak orang untuk mencintai dan menjaga kelestarian alam melalui pengalaman petualangan yang aman, nyaman, dan terjangkau."
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Misi Card -->
                <div class="col-lg-6">
                    <div class="vm-card-modern">
                        <div class="vm-card-header">
                            <div class="vm-icon-box blue">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div>
                                <h2>Misi Kami</h2>
                                <span>Komitmen & Langkah Nyata</span>
                            </div>
                        </div>

                        <ul class="misi-list-modern">
                            <li class="misi-item-modern">
                                <div class="misi-number-badge">01</div>
                                <p>Menyediakan akses mudah, cepat, dan terpercaya ke peralatan camping berkualitas premium.</p>
                            </li>
                            <li class="misi-item-modern">
                                <div class="misi-number-badge">02</div>
                                <p>Membangun ekosistem ekonomi kolaboratif yang memberdayakan vendor penyewaan alat outdoor lokal.</p>
                            </li>
                            <li class="misi-item-modern">
                                <div class="misi-number-badge">03</div>
                                <p>Mendorong gaya hidup ramah lingkungan (eco-friendly) dengan mengurangi konsumsi berlebih melalui konsep berbagi (sewa).</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Visi & Misi End -->

    <!-- Nilai Utama Start -->
    <section class="values-section-modern">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Kenapa KampSewa?</span>
                <h2>Nilai-Nilai Utama <span class="text-gradient-blue">Pelayanan Kami</span></h2>
                <p>Standar keunggulan yang menjadi komitmen tak tertawar dalam setiap interaksi layanan, demi memberikan kenyamanan maksimal untuk Anda.</p>
            </div>

            <div class="row g-4">
                <!-- Value 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="value-card-modern">
                        <div class="value-icon-circle bg-light-blue">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Keamanan & Kualitas</h4>
                        <p>Kami memiliki standar tinggi. Semua peralatan dari mitra vendor selalu melalui proses pengecekan kelayakan pakai dan kebersihan sebelum disewakan kepada Anda.</p>
                    </div>
                </div>

                <!-- Value 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="value-card-modern">
                        <div class="value-icon-circle bg-light-amber">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4>Mitra Terpercaya</h4>
                        <p>KampSewa menyeleksi dan bermitra dengan penyedia alat camping berpengalaman di berbagai kota untuk memastikan Anda mendapatkan pelayanan yang ramah, tepat waktu, dan profesional.</p>
                    </div>
                </div>

                <!-- Value 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="value-card-modern">
                        <div class="value-icon-circle bg-light-emerald">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h4>Kelestarian Alam</h4>
                        <p>Kami sangat peduli pada bumi. Dengan memilih untuk menyewa, Anda ikut berkontribusi mengurangi produksi limbah dan jejak karbon, mendukung pariwisata yang berkelanjutan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Nilai Utama End -->

    <!-- Dampak Nyata Counter Start -->
    <section class="impact-banner-modern">
        <div class="container">
            <div class="impact-grid">
                <div class="impact-item">
                    <h3>2024</h3>
                    <p>Tahun Berdiri & Mengabdi</p>
                </div>
                <div class="impact-item">
                    <h3>150+</h3>
                    <p>Vendor & Mitra Toko</p>
                </div>
                <div class="impact-item">
                    <h3>10.000+</h3>
                    <p>SKU Alat Ready & Steril</p>
                </div>
                <div class="impact-item">
                    <h3>70%</h3>
                    <p>Hemat Biaya vs Membeli</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Dampak Nyata Counter End -->

    <!-- Call to Action Start -->
    <section class="cta-modern-section">
        <div class="container">
            <div class="cta-banner-card">
                <div class="cta-content-inner">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3"><i class="fas fa-compass me-1"></i> Mulai Langkahmu</span>
                    <h2>Siap Memulai Petualangan Anda Selanjutnya?</h2>
                    <p>Jelajahi berbagai pilihan destinasi wisata alam dan temukan peralatan camping terbaik di KampSewa hari ini juga.</p>
                    
                    <div class="cta-buttons-group">
                        <a href="{{ route('landing-page.halaman-destinasi') }}" class="btn-modern-amber">
                            <i class="fas fa-map-marked-alt"></i>
                            <span>Jelajahi Destinasi</span>
                        </a>
                        <a href="{{ route('landing-page.halaman-sewabarang') }}" class="btn-outline-white">
                            <i class="fas fa-campground"></i>
                            <span>Sewa Perlengkapan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Call to Action End -->

    @include('landing-page.halamanbawah')
    @include('landing-page.footer')