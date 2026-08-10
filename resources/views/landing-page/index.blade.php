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

        <!-- Cinematic Hero Section -->
        <section class="hero-modern">
            <!-- Background Slider -->
            <div class="hero-bg-carousel">
                <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('template/envato/img/home-5.jpg') }}" alt="Camping Adventure">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('template/envato/img/home-4.jpg') }}" alt="Mountain Hiking">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('template/envato/img/home-1.jpg') }}" alt="Outdoor Equipment">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gradient Overlay -->
            <div class="hero-overlay-gradient"></div>

            <!-- Hero Content -->
            <div class="container hero-content-wrapper">
                <div class="row align-items-center">
                    <div class="col-lg-10 col-xl-9">
                        <!-- Pill Badge -->
                        <div class="badge-hero-pill">
                            <i class="fas fa-fire text-warning"></i>
                            <span>#1 Marketplace Sewa Alat Camping & Outdoor Indonesia</span>
                        </div>

                        <!-- Main Headline -->
                        <h1 class="hero-title-main">
                            Sewa Perlengkapan Outdoor <br>
                            <span class="text-white">Mudah, Hemat & Terpercaya</span>
                        </h1>

                        <!-- Subtitle -->
                        <p class="hero-subtitle">
                            Tak perlu repot membeli atau merawat barang mahal. Jelajahi ribuan peralatan camping berkualitas tinggi dari 150+ vendor terverifikasi siap menemani petualangan serumu.
                        </p>

                        <!-- Interactive Glass Search Bar -->
                        <div class="hero-glass-search">
                            <div class="search-category-pills">
                                <span class="cat-pill"><i class="fas fa-campground"></i> Tenda</span>
                                <span class="cat-pill"><i class="fas fa-hiking"></i> Carrier & Ransel</span>
                                <span class="cat-pill"><i class="fas fa-shoe-prints"></i> Sepatu Gunung</span>
                                <span class="cat-pill"><i class="fas fa-utensils"></i> Cooking Set</span>
                                <span class="cat-pill"><i class="fas fa-lightbulb"></i> Headlamp & GPS</span>
                                <span class="cat-pill"><i class="fas fa-bed"></i> Sleeping Bag</span>
                            </div>
                            
                            <form action="{{ route('landing-page.halaman-sewabarang') }}" method="GET">
                                <div class="search-input-row">
                                    <div class="search-field-group">
                                        <i class="fas fa-search"></i>
                                        <input type="text" name="q" placeholder="Cari tenda dome, carrier 60L, kompor portable...">
                                    </div>
                                    <button type="submit" class="btn-modern-amber border-0 py-3 px-4">
                                        <span>Cari Alat</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Floating Live Stats -->
                        <div class="hero-floating-stats">
                            <div class="stat-item-hero">
                                <div class="stat-icon-circle">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="stat-text-hero">
                                    <h4>4.9 / 5.0</h4>
                                    <span>Rating Kepuasan</span>
                                </div>
                            </div>
                            <div class="stat-item-hero">
                                <div class="stat-icon-circle blue">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="stat-text-hero">
                                    <h4>150+ Vendor</h4>
                                    <span>Mitra Terverifikasi</span>
                                </div>
                            </div>
                            <div class="stat-item-hero">
                                <div class="stat-icon-circle green">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <div class="stat-text-hero">
                                    <h4>10.000+ SKU</h4>
                                    <span>Alat Ready & Steril</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <a href="#katalog-section" class="hero-scroll-indicator d-none d-md-flex" aria-label="Scroll ke Katalog">
                <div class="mouse-icon"><div class="wheel"></div></div>
                <span>Jelajahi Katalog</span>
            </a>
        </section>
    </div>
    <!-- Navbar & Hero End -->

    <!-- Popular Categories Showcase Start -->
    <section id="katalog-section" class="category-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Katalog Terlengkap</span>
                <h2>Jelajahi Kategori Perlengkapan</h2>
                <p>Temukan segala kebutuhan petualanganmu dari kategori pilihan yang telah teruji kualitas dan kenyamanannya di lapangan.</p>
            </div>

            <div class="row g-4">
                <div class="col-sm-6 col-md-4 col-lg-4">
                    <a href="{{ route('landing-page.halaman-sewabarang') }}" class="category-card-modern">
                        <div class="cat-img-wrapper">
                            <img src="{{ asset('template/envato/img/barang-tenda.jpg') }}" alt="Tenda Camping">
                            <span class="cat-count-badge">120+ Pilihan</span>
                        </div>
                        <div class="cat-content-box">
                            <div>
                                <h4>Tenda Camping</h4>
                                <small class="text-muted">Dome, Tunnel, Glamping</small>
                            </div>
                            <div class="cat-arrow-btn">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-4">
                    <a href="{{ route('landing-page.halaman-sewabarang') }}" class="category-card-modern">
                        <div class="cat-img-wrapper">
                            <img src="{{ asset('template/envato/img/barang-hiking.jpg') }}" alt="Carrier & Ransel">
                            <span class="cat-count-badge">85+ Pilihan</span>
                        </div>
                        <div class="cat-content-box">
                            <div>
                                <h4>Carrier & Ransel</h4>
                                <small class="text-muted">35L, 45L, 60L - 80L</small>
                            </div>
                            <div class="cat-arrow-btn">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-4">
                    <a href="{{ route('landing-page.halaman-sewabarang') }}" class="category-card-modern">
                        <div class="cat-img-wrapper">
                            <img src="{{ asset('template/envato/img/barang-sleeping-bag.jpg') }}" alt="Sleeping Bag & Matras">
                            <span class="cat-count-badge">150+ Pilihan</span>
                        </div>
                        <div class="cat-content-box">
                            <div>
                                <h4>Sleeping Bag & Matras</h4>
                                <small class="text-muted">Polar, Bulu Angsa, Foil</small>
                            </div>
                            <div class="cat-arrow-btn">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-4">
                    <a href="{{ route('landing-page.halaman-sewabarang') }}" class="category-card-modern">
                        <div class="cat-img-wrapper">
                            <img src="{{ asset('template/envato/img/barang-makan.webp') }}" alt="Cooking Set & Kompor">
                            <span class="cat-count-badge">90+ Pilihan</span>
                        </div>
                        <div class="cat-content-box">
                            <div>
                                <h4>Cooking Set & Kompor</h4>
                                <small class="text-muted">Nesting, Kompor Windproof</small>
                            </div>
                            <div class="cat-arrow-btn">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-4">
                    <a href="{{ route('landing-page.halaman-sewabarang') }}" class="category-card-modern">
                        <div class="cat-img-wrapper">
                            <img src="{{ asset('template/envato/img/barang-gps.jpeg') }}" alt="Lighting & Navigasi">
                            <span class="cat-count-badge">60+ Pilihan</span>
                        </div>
                        <div class="cat-content-box">
                            <div>
                                <h4>Lighting & Navigasi</h4>
                                <small class="text-muted">Headlamp, GPS, Lentera</small>
                            </div>
                            <div class="cat-arrow-btn">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-4">
                    <a href="{{ route('landing-page.halaman-sewabarang') }}" class="category-card-modern">
                        <div class="cat-img-wrapper">
                            <img src="{{ asset('template/envato/img/barang-flysheet.jpg') }}" alt="Flysheet & Hammock">
                            <span class="cat-count-badge">75+ Pilihan</span>
                        </div>
                        <div class="cat-content-box">
                            <div>
                                <h4>Flysheet & Hammock</h4>
                                <small class="text-muted">Waterproof 3x3, Single/Double</small>
                            </div>
                            <div class="cat-arrow-btn">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('landing-page.halaman-sewabarang') }}" class="btn-modern-primary">
                    <span>Lihat Seluruh Perlengkapan</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
    <!-- Popular Categories Showcase End -->

    <!-- Modern About Us Start -->
    <section class="about-modern-section">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="about-img-collage">
                        <div class="about-img-main">
                            <img src="{{ asset('template/envato/img/about-kamp.jpg') }}" alt="Tentang KampSewa">
                        </div>
                        <!-- Floating Glass Experience Badge -->
                        <div class="about-floating-glass-card">
                            <div class="glass-card-header">
                                <div class="glass-icon-badge">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div>
                                    <h5>#1 Rental Outdoor</h5>
                                    <span>Jawa Timur & Nusantara</span>
                                </div>
                            </div>
                            <div class="glass-card-text">
                                <p>Menghubungkan ribuan petualang dengan vendor terpercaya sejak 2021.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <span class="badge-section">Tentang Kami</span>
                    <h2 class="mb-3" style="font-size: clamp(2rem, 3.5vw, 2.8rem); font-weight: 800;">
                        Revolusi Cara Kamu <br><span class="text-gradient-blue">Menikmati Alam</span>
                    </h2>
                    <p class="text-muted mb-4" style="font-size: 1.05rem; line-height: 1.7;">
                        Di KampSewa, kami percaya bahwa petualangan harus dapat diakses oleh siapa saja tanpa hambatan biaya peralatan yang mahal. Kami hadir sebagai platform marketplace yang mempertemukan pecinta alam dengan penyedia jasa rental perlengkapan outdoor berkualitas tinggi.
                    </p>

                    <!-- Feature 2x2 Grid -->
                    <div class="feature-grid-2x2">
                        <div class="feature-check-card">
                            <div class="feature-check-header">
                                <div class="check-icon-circle"><i class="fas fa-check"></i></div>
                                <h5>Pilihan Terlengkap</h5>
                            </div>
                            <p>Ribuan SKU perlengkapan camping siap sewa dari merek lokal & internasional.</p>
                        </div>

                        <div class="feature-check-card">
                            <div class="feature-check-header">
                                <div class="check-icon-circle"><i class="fas fa-check"></i></div>
                                <h5>Jaminan Steril & Bersih</h5>
                            </div>
                            <p>Seluruh alat disanitasi & diperiksa teliti sebelum diserahkan ke tanganmu.</p>
                        </div>

                        <div class="feature-check-card">
                            <div class="feature-check-header">
                                <div class="check-icon-circle"><i class="fas fa-check"></i></div>
                                <h5>Durasi Sewa Fleksibel</h5>
                            </div>
                            <p>Sewa harian, mingguan, atau paket khusus ekspedisi sesuai jadwalmu.</p>
                        </div>

                        <div class="feature-check-card">
                            <div class="feature-check-header">
                                <div class="check-icon-circle"><i class="fas fa-check"></i></div>
                                <h5>Antar-Jemput Mudah</h5>
                            </div>
                            <p>Layanan kurir instan langsung ke rumah atau titik kumpul keberangkatan.</p>
                        </div>
                    </div>

                    <div class="mt-2">
                        <a href="{{ route('landing-page.halaman-tentangkami') }}" class="btn-modern-primary">
                            <span>Kenali Kami Lebih Dekat</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modern About Us End -->

    <!-- Why Choose Us (Keunggulan) Start -->
    <section class="why-choose-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Keunggulan Utama</span>
                <h2>Kenapa Harus Pilih KampSewa?</h2>
                <p>Kami menghadirkan pengalaman penyewaan alat camping generasi baru yang mengutamakan kenyamanan, keamanan, dan kelestarian lingkungan.</p>
            </div>

            <div class="why-grid-modern">
                <!-- Card 1 -->
                <div class="why-card-modern">
                    <div class="why-icon-box bg-light-emerald">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <h4>Bebas Limbah & Ramah Lingkungan</h4>
                    <p>Konsep shared-economy melalui sewa peralatan membantu mengurangi limbah produksi alat outdoor serta mendukung kelestarian alam nusantara.</p>
                </div>

                <!-- Card 2 -->
                <div class="why-card-modern">
                    <div class="why-icon-box bg-light-amber">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h4>Hemat Biaya Hingga 70%</h4>
                    <p>Nikmati perlengkapan spesifikasi mendaki gunung tertinggi tanpa perlu mengeluarkan uang jutaan rupiah untuk membeli atau biaya perawatan tahunan.</p>
                </div>

                <!-- Card 3 -->
                <div class="why-card-modern">
                    <div class="why-icon-box bg-light-blue">
                        <i class="fas fa-search-dollar"></i>
                    </div>
                    <h4>Coba Dulu, Beli Nanti</h4>
                    <p>Ragu membeli carrier atau tenda impian? Sewa dan uji kenyamanannya langsung di medan pendakian sebelum kamu memutuskan untuk membelinya.</p>
                </div>

                <!-- Card 4 -->
                <div class="why-card-modern">
                    <div class="why-icon-box bg-light-purple">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Jaminan Keamanan & Transparansi</h4>
                    <p>Sistem transaksi marketplace bergaransi dengan review autentik dari pengguna, serta kejelasan standar spesifikasi barang yang disewakan.</p>
                </div>

                <!-- Card 5 -->
                <div class="why-card-modern">
                    <div class="why-icon-box bg-light-rose">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h4>Pengiriman Instan & Praktis</h4>
                    <p>Tak perlu repot bermacet-macetan ke toko rental. Kurir mitra kami siap mengantar dan menjemput perlengkapan tepat waktu di depan rumahmu.</p>
                </div>

                <!-- Card 6 -->
                <div class="why-card-modern">
                    <div class="why-icon-box bg-light-cyan">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Komunitas Petualang Aktif</h4>
                    <p>Bergabung dalam ekosistem ribuan pendaki dan campers untuk berbagi informasi rute pendakian, tips bertahan di alam, hingga mencari rekan mabar.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Why Choose Us End -->

    <!-- NEW! How It Works (4 Langkah Mudah) Start -->
    <section class="workflow-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Alur Sewa</span>
                <h2>4 Langkah Mudah Memulai Petualangan</h2>
                <p>Proses penyewaan di KampSewa dirancang super simpel dan cepat agar kamu bisa langsung fokus merencanakan keseruan campingmu.</p>
            </div>

            <div class="workflow-grid">
                <div class="workflow-step-card">
                    <div class="step-num-circle">01</div>
                    <h4>Cari & Pilih Alat</h4>
                    <p>Jelajahi katalog dan pilih perlengkapan outdoor berkualitas dari vendor terdekat sesuai kebutuhan petualanganmu.</p>
                </div>

                <div class="workflow-step-card">
                    <div class="step-num-circle">02</div>
                    <h4>Tentukan Durasi</h4>
                    <p>Pilih tanggal pemakaian dan durasi sewa yang kamu inginkan dengan harga yang transparan dan bersahabat.</p>
                </div>

                <div class="workflow-step-card">
                    <div class="step-num-circle">03</div>
                    <h4>Terima Perlengkapan</h4>
                    <p>Alat diantar oleh kurir ke lokasi tujuanmu dalam kondisi steril, harum, dan sudah lolos quality check fisik 100%.</p>
                </div>

                <div class="workflow-step-card">
                    <div class="step-num-circle">04</div>
                    <h4>Nikmati Petualangan!</h4>
                    <p>Nikmati momen berharga di alam bebas! Setelah selesai, tinggal kembalikan barang dengan jadwal penjemputan mudah.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- How It Works End -->

    <!-- Destinations Section Start -->
    <section class="destinations-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Rekomendasi Destinasi</span>
                <h2>Jelajahi Spot Camping & Outdoor Favorit</h2>
                <p>Belum punya ide mau camping ke mana? Lihat beberapa destinasi wisata alam terpopuler pilihan komunitas petualang KampSewa.</p>
            </div>

            <div class="dest-filter-container text-center mb-5">
                <button type="button" class="dest-filter-btn active" data-filter="populer" onclick="switchDestFilter('populer', this)">
                    <i class="fas fa-fire me-2"></i> Spot Terpopuler
                </button>
                <button type="button" class="dest-filter-btn" data-filter="rating" onclick="switchDestFilter('rating', this)">
                    <i class="fas fa-star me-2"></i> Rating Tertinggi
                </button>
            </div>

            <!-- Loading Spinner / Status -->
            <div id="dest-loading-status" class="text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted fw-bold">Mengambil data wisata populer &amp; rating tertinggi dari API Gratis Indonesia...</p>
            </div>

            <!-- Destination Grid Container -->
            <div id="dest-grid-container" class="row g-4 text-start" style="display: none;"></div>

                <div class="text-center mt-5">
                    <a href="{{ route('landing-page.halaman-destinasi') }}" class="btn-modern-primary">
                        <span>Lihat Semua Destinasi</span>
                        <i class="fas fa-map-marked-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Destinations Section End -->

    <!-- Partner & Vendor Terpercaya Start -->
    <section class="vendor-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Mitra Resmi</span>
                <h2>Partner & Vendor Store Terpercaya</h2>
                <p>Bekerja sama dengan brand outdoor nomor satu dan penyedia jasa rental terverifikasi untuk menjamin kepuasan petualanganmu.</p>
            </div>

            <div class="row g-4">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="vendor-card-modern">
                        <div class="vendor-banner-img">
                            <img src="{{ asset('template/envato/img/toko-rei.jpg') }}" alt="Rei Outdoor Gear">
                            <span class="vendor-verified-tag"><i class="fas fa-check-circle"></i> Verified</span>
                        </div>
                        <div class="vendor-info-box">
                            <h4>Rei Outdoor Gear</h4>
                            <p>Specialist Tenda & Ransel</p>
                            <span class="vendor-stats-pill"><i class="fas fa-star text-warning me-1"></i> 4.9 | Terlaris Minggu Ini</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="vendor-card-modern">
                        <div class="vendor-banner-img">
                            <img src="{{ asset('template/envato/img/toko-eiger.jpeg') }}" alt="Eiger Adventure">
                            <span class="vendor-verified-tag"><i class="fas fa-check-circle"></i> Verified</span>
                        </div>
                        <div class="vendor-info-box">
                            <h4>Eiger Adventure Store</h4>
                            <p>Top Brand Outdoor Gear</p>
                            <span class="vendor-stats-pill"><i class="fas fa-star text-warning me-1"></i> 5.0 | Mitra Utama</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="vendor-card-modern">
                        <div class="vendor-banner-img">
                            <img src="{{ asset('template/envato/img/toko-consina.png') }}" alt="Consina Outdoor">
                            <span class="vendor-verified-tag"><i class="fas fa-check-circle"></i> Verified</span>
                        </div>
                        <div class="vendor-info-box">
                            <h4>Consina The Outdoor Lifestyle</h4>
                            <p>Carrier & Apparel Camping</p>
                            <span class="vendor-stats-pill"><i class="fas fa-star text-warning me-1"></i> 4.9 | Terlaris Minggu Ini</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="vendor-card-modern">
                        <div class="vendor-banner-img">
                            <img src="{{ asset('template/envato/img/toko-tnf.jpg') }}" alt="The North Face">
                            <span class="vendor-verified-tag"><i class="fas fa-check-circle"></i> Verified</span>
                        </div>
                        <div class="vendor-info-box">
                            <h4>The North Face ID</h4>
                            <p>Premium Mountaineering</p>
                            <span class="vendor-stats-pill"><i class="fas fa-star text-warning me-1"></i> 4.9 | Official Brand</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="vendor-card-modern">
                        <div class="vendor-banner-img">
                            <img src="{{ asset('template/envato/img/toko-deca.jpg') }}" alt="Decathlon">
                            <span class="vendor-verified-tag"><i class="fas fa-check-circle"></i> Verified</span>
                        </div>
                        <div class="vendor-info-box">
                            <h4>Decathlon Indonesia</h4>
                            <p>Peralatan Camping & Olahraga</p>
                            <span class="vendor-stats-pill"><i class="fas fa-star text-warning me-1"></i> 4.8 | Terpopuler</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="vendor-card-modern">
                        <div class="vendor-banner-img">
                            <img src="{{ asset('template/envato/img/toko-deuter.webp') }}" alt="Deuter Store">
                            <span class="vendor-verified-tag"><i class="fas fa-check-circle"></i> Verified</span>
                        </div>
                        <div class="vendor-info-box">
                            <h4>Deuter Official Store</h4>
                            <p>Legendary German Backpacks</p>
                            <span class="vendor-stats-pill"><i class="fas fa-star text-warning me-1"></i> 5.0 | Top Quality</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="vendor-card-modern">
                        <div class="vendor-banner-img">
                            <img src="{{ asset('template/envato/img/toko-avtech.jpg') }}" alt="Avtech Store">
                            <span class="vendor-verified-tag"><i class="fas fa-check-circle"></i> Verified</span>
                        </div>
                        <div class="vendor-info-box">
                            <h4>Avtech Adventure Equipment</h4>
                            <p>Tenda & Sleeping Bag Profesional</p>
                            <span class="vendor-stats-pill"><i class="fas fa-star text-warning me-1"></i> 4.8 | Favorit Pendaki</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="vendor-card-modern">
                        <div class="vendor-banner-img">
                            <img src="{{ asset('template/envato/img/toko-jack.jpg') }}" alt="Jack Wolfskin">
                            <span class="vendor-verified-tag"><i class="fas fa-check-circle"></i> Verified</span>
                        </div>
                        <div class="vendor-info-box">
                            <h4>Jack Wolfskin Store</h4>
                            <p>German Outdoor Brand</p>
                            <span class="vendor-stats-pill"><i class="fas fa-star text-warning me-1"></i> 4.9 | Premium Gear</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Partner & Vendor Terpercaya End -->

    <!-- NEW! Testimonials & Stories Start -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Kata Mereka</span>
                <h2>Cerita Seru Petualang KampSewa</h2>
                <p>Simak pengalaman nyata dari pendaki gunung, campers, dan komunitas yang telah merasakan kemudahan menyewa perlengkapan di KampSewa.</p>
            </div>

            <div class="row g-4">
                <!-- Testi 1 -->
                <div class="col-md-6 col-lg-3">
                    <div class="testimonial-card-modern">
                        <div>
                            <div class="testi-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="testi-quote-text">
                                "KampSewa bener-bener penyelamat! Mau naik gunung Rinjani tapi carrier lagi dipinjem temen. Cari di sini langsung dapet Eiger yang super bersih dan harum. Harga sewanya ramah banget buat kantong mahasiswa!"
                            </p>
                        </div>
                        <div class="testi-user-row">
                            <div class="testi-avatar">
                                <img src="{{ asset('template/envato/img/Testimoni-1.jpg') }}" alt="Rizky Ramadhan">
                            </div>
                            <div class="testi-user-info">
                                <h5>Rizky Ramadhan</h5>
                                <span>Pendaki Gunung Rinjani</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testi 2 -->
                <div class="col-md-6 col-lg-3">
                    <div class="testimonial-card-modern">
                        <div>
                            <div class="testi-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="testi-quote-text">
                                "Sewa cooking set lengkap sama tenda glamping buat liburan keluarga akhir pekan di Songgolangit. Barang diantar tepat waktu ke lokasi camp. Pelayanan vendornya ramah, bakal jadi langganan terus!"
                            </p>
                        </div>
                        <div class="testi-user-row">
                            <div class="testi-avatar">
                                <img src="{{ asset('template/envato/img/Testimoni-2.jpg') }}" alt="Anisa & Keluarga">
                            </div>
                            <div class="testi-user-info">
                                <h5>Anisa & Keluarga</h5>
                                <span>Family Camper Jember</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testi 3 -->
                <div class="col-md-6 col-lg-3">
                    <div class="testimonial-card-modern">
                        <div>
                            <div class="testi-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="testi-quote-text">
                                "Sebagai content creator outdoor, sering banget butuh coba berbagai macam alat baru sebelum beli. Fitur 'Coba Dulu Beli Nanti' di KampSewa inovatif banget. Kualitas barang dijamin 100% layak pakai!"
                            </p>
                        </div>
                        <div class="testi-user-row">
                            <div class="testi-avatar">
                                <img src="{{ asset('template/envato/img/Testimoni-3.jpg') }}" alt="Bima Satria">
                            </div>
                            <div class="testi-user-info">
                                <h5>Bima Satria</h5>
                                <span>Solo Camper & Creator</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testi 4 -->
                <div class="col-md-6 col-lg-3">
                    <div class="testimonial-card-modern">
                        <div>
                            <div class="testi-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="testi-quote-text">
                                "Buat acara diklat pecinta alam kampus, kita butuh 15 tenda dome dan 30 sleeping bag. Lewat KampSewa tinggal pesan borongan dari satu vendor terpercaya, langsung diantar ke kampus. Sangat mempermudah kepanitiaan!"
                            </p>
                        </div>
                        <div class="testi-user-row">
                            <div class="testi-avatar">
                                <img src="{{ asset('template/envato/img/Testimoni-4.jpg') }}" alt="Dewi Lestari">
                            </div>
                            <div class="testi-user-info">
                                <h5>Dewi Lestari</h5>
                                <span>Koordinator Mapala</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('landing-page.halaman-testimoni') }}" class="btn-modern-primary">
                    <span>Lihat Seluruh Ulasan</span>
                    <i class="fas fa-comments"></i>
                </a>
            </div>
        </div>
    </section>
    <!-- Testimonials End -->

    <!-- High-Impact CTA Banner Start -->
    <section class="cta-modern-section" id="download-app">
        <div class="container">
            <div class="cta-banner-card">
                <div class="cta-content-inner">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3"><i class="fas fa-rocket me-1"></i> Aplikasi KampSewa Mobile</span>
                    <h2>Siap Memulai Petualangan Serumu Berikutnya?</h2>
                    <p>Unduh aplikasi KampSewa sekarang di smartphonemu! Nikmati kemudahan booking perlengkapan outdoor di mana saja, kapan saja, lengkap dengan promo diskon perdana untuk pengguna baru.</p>
                    
                    <div class="cta-buttons-group">
                        <a href="#appNotAvailableModal" data-bs-toggle="modal" onclick="showAppModal('Google Play')" class="btn-app-store">
                            <i class="fab fa-google-play fa-lg text-primary"></i>
                            <div>
                                <small class="d-block text-muted" style="font-size: 0.7rem; line-height: 1;">GET IT ON</small>
                                <span class="fw-bold" style="font-size: 1rem;">Google Play</span>
                            </div>
                        </a>
                        <a href="#appNotAvailableModal" data-bs-toggle="modal" onclick="showAppModal('App Store')" class="btn-app-store">
                            <i class="fab fa-apple fa-lg text-dark"></i>
                            <div>
                                <small class="d-block text-muted" style="font-size: 0.7rem; line-height: 1;">Download on the</small>
                                <span class="fw-bold" style="font-size: 1rem;">App Store</span>
                            </div>
                        </a>
                        <a href="{{ route('landing-page.halaman-sewabarang') }}" class="btn-outline-white">
                            <span>Jelajahi Lewat Web</span>
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- High-Impact CTA Banner End -->

    <!-- App Not Available Animated Popup Modal -->
    <div class="modal fade" id="appNotAvailableModal" tabindex="-1" aria-labelledby="appNotAvailableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content app-modal-content">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <div class="app-modal-icon-wrapper">
                    <i class="fas fa-cloud-download-alt"></i>
                </div>
                
                <span class="app-modal-badge"><i class="fas fa-info-circle me-1"></i> Status Aplikasi Mobile</span>
                
                <h3 class="fw-bold text-dark mt-2 mb-3" id="appNotAvailableModalLabel">Aplikasi Belum Tersedia</h3>
                
                <p class="text-muted mb-4 px-2" style="font-size: 0.95rem; line-height: 1.6;">
                    Saat ini aplikasi mobile KampSewa untuk <strong id="app-platform-name" class="text-primary">Google Play</strong> sedang dalam tahap akhir verifikasi dan pengembangan. 
                    <br><br>
                    Silakan download atau akses platform kami melalui tombol berikut ini:
                </p>
                
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <a href="{{ route('landing-page.halaman-sewabarang') }}" class="btn-nav-download d-inline-flex align-items-center justify-content-center px-5 py-3 mb-3 shadow" style="font-size: 1.05rem !important; width: 100%; max-width: 280px; text-decoration: none;">
                        <i class="fas fa-cloud-download-alt fa-lg"></i>
                        <span>Download App</span>
                    </a>
                    <button type="button" class="btn btn-link text-muted text-decoration-none btn-sm mt-1" data-bs-dismiss="modal">Kembali ke Beranda</button>
                </div>
            </div>
        </div>
    </div>

    @include('landing-page.halamanbawah')

    <!-- Dynamic Destination Loader & Filter Script from API Gratis -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const destMetadata = [
            // Spot Terpopuler
            { title: "Gunung Rinjani", queryTitle: "Gunung_Rinjani", location: "Lombok, NTB", rating: "5.0", reviews: "5.2k Review", category: "populer", forceLocalImg: true, fallbackImg: "{{ asset('template/envato/img/tempat-lombok.jpg') }}" },
            { title: "Ranu Kumbolo", queryTitle: "Ranu_Kumbolo", location: "Semeru, Jawa Timur", rating: "4.9", reviews: "6.1k Review", category: "populer", fallbackImg: "{{ asset('template/envato/img/tempat-tumpak-sewu.webp') }}" },
            { title: "Gunung Bromo", queryTitle: "Gunung_Bromo", location: "Probolinggo, Jawa Timur", rating: "4.9", reviews: "8.4k Review", category: "populer", fallbackImg: "{{ asset('template/envato/img/tempat-gunung-gambir.jpg') }}" },
            { title: "Taman Nasional Baluran", queryTitle: "Taman_Nasional_Baluran", location: "Situbondo, Jawa Timur", rating: "4.9", reviews: "4.3k Review", category: "populer", forceLocalImg: true, fallbackImg: "{{ asset('template/envato/img/tempat-taman-nasional-baluran.jpeg') }}" },
            { title: "Gunung Prau", queryTitle: "Gunung_Prau", location: "Dieng, Jawa Tengah", rating: "4.8", reviews: "3.9k Review", category: "populer", fallbackImg: "{{ asset('template/envato/img/tempat-dringo.jpg') }}" },
            { title: "Gunung Merbabu", queryTitle: "Gunung_Merbabu", location: "Magelang, Jawa Tengah", rating: "4.9", reviews: "4.7k Review", category: "populer", fallbackImg: "{{ asset('template/envato/img/tempat-nglimut.webp') }}" },

            // Rating Tertinggi
            { title: "Gunung Gede Pangrango", queryTitle: "Taman_Nasional_Gunung_Gede_Pangrango", location: "Cianjur, Jawa Barat", rating: "5.0", reviews: "6.8k Review", category: "rating", forceLocalImg: true, fallbackImg: "{{ asset('template/envato/img/tempat-taman-nasional.jpg') }}" },
            { title: "Ranca Upas", queryTitle: "Ranca_Upas", location: "Bandung, Jawa Barat", rating: "5.0", reviews: "5.1k Review", category: "rating", fallbackImg: "{{ asset('template/envato/img/tempat-dago.webp') }}" },
            { title: "Pantai Tanjung Papuma", queryTitle: "Pantai_Papuma", location: "Jember, Jawa Timur", rating: "5.0", reviews: "3.8k Review", category: "rating", fallbackImg: "{{ asset('template/envato/img/tempat-papuma.png') }}" },
            { title: "Danau Segara Anak", queryTitle: "Segara_Anak", location: "Lombok, NTB", rating: "5.0", reviews: "2.9k Review", category: "rating", fallbackImg: "{{ asset('template/envato/img/tempat-khayangan.webp') }}" },
            { title: "Wae Rebo", queryTitle: "Wae_Rebo", location: "Manggarai, NTT", rating: "5.0", reviews: "1.8k Review", category: "rating", fallbackImg: "{{ asset('template/envato/img/tempat-suluban.webp') }}" },
            { title: "Gunung Papandayan", queryTitle: "Gunung_Papandayan", location: "Garut, Jawa Barat", rating: "4.9", reviews: "4.2k Review", category: "rating", fallbackImg: "{{ asset('template/envato/img/tempat-glamping.jpg') }}" }
        ];

        let destDataLoaded = [];
        let currentFilter = 'populer';

        window.switchDestFilter = function(filter, btnElement) {
            currentFilter = filter;
            document.querySelectorAll('.dest-filter-btn').forEach(b => b.classList.remove('active'));
            if (btnElement) {
                btnElement.classList.add('active');
            }
            renderDestinations();
        };

        window.showAppModal = function(platform) {
            const el = document.getElementById('app-platform-name');
            if(el) el.textContent = platform;
        };

        function renderDestinations() {
            const container = document.getElementById('dest-grid-container');
            if (!container) return;
            const filtered = destDataLoaded.filter(d => d.category === currentFilter);
            
            const fallbackSvg = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400' fill='%231e293b'><rect width='600' height='400' fill='%230f172a'/><text x='50%' y='50%' font-family='sans-serif' font-size='22' font-weight='bold' fill='%2394a3b8' dominant-baseline='middle' text-anchor='middle'>🖼️ Gambar Tidak Tersedia</text></svg>";

            container.innerHTML = filtered.map(item => `
                <div class="col-md-6 col-lg-4">
                    <a href="${item.mapsUrl}" target="_blank" rel="noopener noreferrer" class="destination-card-modern" title="Lihat lokasi ${item.title} di Google Maps">
                        <img src="${item.image}" alt="${item.title}" onerror="this.onerror=null; if(this.src !== '${item.fallbackImg}') { this.src='${item.fallbackImg}'; } else { this.src=\"${fallbackSvg}\"; }">
                        <div class="dest-overlay-bottom">
                            <span class="dest-badge-loc"><i class="fas fa-map-marker-alt text-warning"></i> ${item.location}</span>
                            <h4 class="dest-title">${item.title}</h4>
                            <div class="dest-meta-row">
                                <span><i class="fas fa-star text-warning"></i> ${item.rating} (${item.reviews})</span>
                                <span class="text-info fw-bold">Lihat Detail <i class="fas fa-map-marked-alt ms-1"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            `).join('');
        }

        async function fetchFreeApiDestinations() {
            try {
                const titles = destMetadata.map(d => d.queryTitle).join('|');
                const apiUrl = `https://id.wikipedia.org/w/api.php?action=query&format=json&origin=*&prop=pageimages|extracts&exintro&explaintext&piprop=thumbnail&pithumbsize=600&titles=${encodeURIComponent(titles)}`;
                
                const response = await fetch(apiUrl);
                const data = await response.json();
                const pages = data?.query?.pages || {};
                
                const pageMap = {};
                Object.values(pages).forEach(p => {
                    if (p.title) {
                        pageMap[p.title.toLowerCase().replace(/ /g, '_')] = p;
                        pageMap[p.title.toLowerCase()] = p;
                    }
                });

                destDataLoaded = destMetadata.map(item => {
                    const normalizedKey = item.queryTitle.toLowerCase();
                    const normalizedTitle = item.title.toLowerCase();
                    const apiItem = pageMap[normalizedKey] || pageMap[normalizedTitle] || {};
                    
                    const apiImage = apiItem.thumbnail?.source;
                    const isBadOrLogoImg = apiImage && (
                        apiImage.toLowerCase().includes('logo') ||
                        apiImage.toLowerCase().includes('lambang') ||
                        apiImage.toLowerCase().includes('emblem') ||
                        apiImage.toLowerCase().includes('icon') ||
                        apiImage.toLowerCase().includes('seal') ||
                        apiImage.toLowerCase().includes('svg') ||
                        apiImage.toLowerCase().includes('badge') ||
                        apiImage.toLowerCase().includes('coat') ||
                        apiImage.toLowerCase().includes('taman_nasional') ||
                        item.forceLocalImg
                    );
                    const finalImage = (!apiImage || isBadOrLogoImg) ? item.fallbackImg : apiImage;
                    
                    const mapsQuery = encodeURIComponent(`${item.title} ${item.location} Indonesia`);
                    const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${mapsQuery}`;

                    return {
                        ...item,
                        image: finalImage,
                        mapsUrl: mapsUrl
                    };
                });
            } catch (e) {
                console.warn("API Gratis fallback mode activated:", e);
                destDataLoaded = destMetadata.map(item => ({
                    ...item,
                    image: item.fallbackImg,
                    mapsUrl: `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(item.title + ' ' + item.location + ' Indonesia')}`
                }));
            } finally {
                const loadingEl = document.getElementById('dest-loading-status');
                const gridEl = document.getElementById('dest-grid-container');
                if (loadingEl) loadingEl.style.display = 'none';
                if (gridEl) gridEl.style.display = 'flex';
                renderDestinations();
            }
        }

        fetchFreeApiDestinations();
    });
    </script>

    <!-- JavaScript Libraries -->
    @include('landing-page.footer')
