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
                            <i class="{{ $heroContent->icon ?? 'fas fa-fire text-warning' }}"></i>
                            <span>{{ $heroContent->badge ?? '#1 Marketplace Sewa Alat Camping & Outdoor Indonesia' }}</span>
                        </div>

                        <!-- Main Headline -->
                        <h1 class="hero-title-main">
                            {!! $heroContent->title ?? 'Sewa Perlengkapan Outdoor <br><span class="text-white">Mudah, Hemat & Terpercaya</span>' !!}
                        </h1>

                        <!-- Subtitle -->
                        <p class="hero-subtitle">
                            {{ $heroContent->subtitle ?? 'Tak perlu repot membeli atau merawat barang mahal. Jelajahi ribuan peralatan camping berkualitas tinggi dari 150+ vendor terverifikasi siap menemani petualangan serumu.' }}
                        </p>

                        <!-- Interactive Glass Search Bar -->
                        <div class="hero-glass-search">
                            <div class="search-category-pills">
                                @forelse($kategoriList as $cat)
                                    <a href="{{ route('landing-page.halaman-sewabarang') }}?kategori={{ urlencode($cat->nama_kategori) }}" class="cat-pill text-decoration-none">
                                        <i class="{{ $cat->icon ?? 'fas fa-campground' }}"></i> {{ $cat->nama_kategori }}
                                    </a>
                                @empty
                                    <span class="cat-pill"><i class="fas fa-campground"></i> Tenda</span>
                                    <span class="cat-pill"><i class="fas fa-hiking"></i> Carrier & Ransel</span>
                                    <span class="cat-pill"><i class="fas fa-shoe-prints"></i> Sepatu Gunung</span>
                                    <span class="cat-pill"><i class="fas fa-utensils"></i> Cooking Set</span>
                                    <span class="cat-pill"><i class="fas fa-lightbulb"></i> Headlamp & GPS</span>
                                    <span class="cat-pill"><i class="fas fa-bed"></i> Sleeping Bag</span>
                                @endforelse
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

                        <!-- Floating Live Stats Dinamis dari Database -->
                        <div class="hero-floating-stats">
                            <div class="stat-item-hero">
                                <div class="stat-icon-circle">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="stat-text-hero">
                                    <h4>{{ $stats['rating'] ?? '4.9 / 5.0' }}</h4>
                                    <span>Rating Kepuasan</span>
                                </div>
                            </div>
                            <div class="stat-item-hero">
                                <div class="stat-icon-circle blue">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="stat-text-hero">
                                    <h4>{{ $stats['total_vendor'] ?? '150+ Vendor' }}</h4>
                                    <span>Mitra Terverifikasi</span>
                                </div>
                            </div>
                            <div class="stat-item-hero">
                                <div class="stat-icon-circle green">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <div class="stat-text-hero">
                                    <h4>{{ $stats['total_sku'] ?? '10.000+ SKU' }}</h4>
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

    <!-- Popular Categories Showcase Start (Dinamis dari Database) -->
    <section id="katalog-section" class="category-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Katalog Terlengkap</span>
                <h2>Jelajahi Kategori Perlengkapan</h2>
                <p>Temukan segala kebutuhan petualanganmu dari kategori pilihan yang telah teruji kualitas dan kenyamanannya di lapangan.</p>
            </div>

            <div class="row g-4">
                @forelse($kategoriList as $kategori)
                    <div class="col-sm-6 col-md-4 col-lg-4">
                        <a href="{{ route('landing-page.halaman-sewabarang') }}?kategori={{ urlencode($kategori->nama_kategori) }}" class="category-card-modern">
                            <div class="cat-img-wrapper">
                                <img src="{{ $kategori->gambar_url }}" alt="{{ $kategori->nama_kategori }}" onerror="this.onerror=null; this.src='{{ asset('template/envato/img/barang-tenda.jpg') }}';">
                                <span class="cat-count-badge">{{ $kategori->jumlah_pilihan ?? 'Pilihan Lengkap' }}</span>
                            </div>
                            <div class="cat-content-box">
                                <div>
                                    <h4>{{ $kategori->nama_kategori }}</h4>
                                    <small class="text-muted">{{ $kategori->deskripsi_singkat ?? 'Koleksi Terbaik' }}</small>
                                </div>
                                <div class="cat-arrow-btn">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">Kategori perlengkapan sedang diperbarui.</p>
                    </div>
                @endforelse
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

    <!-- Modern About Us Start (Dinamis dari Database) -->
    <section class="about-modern-section">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="about-img-collage">
                        <div class="about-img-main">
                            <img src="{{ asset($aboutContent->image ?? 'template/envato/img/about-kamp.jpg') }}" alt="Tentang KampSewa">
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
                    <span class="badge-section">{{ $aboutContent->badge ?? 'Tentang Kami' }}</span>
                    <h2 class="mb-3" style="font-size: clamp(2rem, 3.5vw, 2.8rem); font-weight: 800;">
                        {!! $aboutContent->title ?? 'Revolusi Cara Kamu <br><span class="text-gradient-blue">Menikmati Alam</span>' !!}
                    </h2>
                    <p class="text-muted mb-4" style="font-size: 1.05rem; line-height: 1.7;">
                        {{ $aboutContent->content ?? 'Di KampSewa, kami percaya bahwa petualangan harus dapat diakses oleh siapa saja tanpa hambatan biaya peralatan yang mahal. Kami hadir sebagai platform marketplace yang mempertemukan pecinta alam dengan penyedia jasa rental perlengkapan outdoor berkualitas tinggi.' }}
                    </p>

                    <!-- Feature 2x2 Grid Dinamis dari Database -->
                    <div class="feature-grid-2x2">
                        @forelse($aboutFeatures as $feature)
                            <div class="feature-check-card">
                                <div class="feature-check-header">
                                    <div class="check-icon-circle"><i class="{{ $feature->icon ?? 'fas fa-check' }}"></i></div>
                                    <h5>{{ $feature->title }}</h5>
                                </div>
                                <p>{{ $feature->content }}</p>
                            </div>
                        @empty
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
                        @endforelse
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

    <!-- Why Choose Us (Keunggulan) Start (Dinamis dari Database) -->
    <section class="why-choose-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Keunggulan Utama</span>
                <h2>Kenapa Harus Pilih KampSewa?</h2>
                <p>Kami menghadirkan pengalaman penyewaan alat camping generasi baru yang mengutamakan kenyamanan, keamanan, dan kelestarian lingkungan.</p>
            </div>

            <div class="why-grid-modern">
                @forelse($keunggulanList as $item)
                    <div class="why-card-modern">
                        <div class="why-icon-box {{ $item->icon_bg ?? 'bg-light-emerald' }}">
                            <i class="{{ $item->icon ?? 'fas fa-shield-alt' }}"></i>
                        </div>
                        <h4>{{ $item->title }}</h4>
                        <p>{{ $item->content }}</p>
                    </div>
                @empty
                    <div class="why-card-modern">
                        <div class="why-icon-box bg-light-emerald">
                            <i class="fas fa-recycle"></i>
                        </div>
                        <h4>Bebas Limbah & Ramah Lingkungan</h4>
                        <p>Konsep shared-economy melalui sewa peralatan membantu mengurangi limbah produksi alat outdoor serta mendukung kelestarian alam nusantara.</p>
                    </div>
                    <div class="why-card-modern">
                        <div class="why-icon-box bg-light-amber">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h4>Hemat Biaya Hingga 70%</h4>
                        <p>Nikmati perlengkapan spesifikasi mendaki gunung tertinggi tanpa perlu mengeluarkan uang jutaan rupiah untuk membeli atau biaya perawatan tahunan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Why Choose Us End -->

    <!-- How It Works (Alur Sewa 4 Langkah) Start (Dinamis dari Database) -->
    <section class="workflow-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Alur Sewa</span>
                <h2>4 Langkah Mudah Memulai Petualangan</h2>
                <p>Proses penyewaan di KampSewa dirancang super simpel dan cepat agar kamu bisa langsung fokus merencanakan keseruan campingmu.</p>
            </div>

            <div class="workflow-grid">
                @forelse($alurSewaList as $step)
                    <div class="workflow-step-card">
                        <div class="step-num-circle">{{ $step->step_number ?? sprintf('%02d', $loop->iteration) }}</div>
                        <h4>{{ $step->title }}</h4>
                        <p>{{ $step->content }}</p>
                    </div>
                @empty
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
                @endforelse
            </div>
        </div>
    </section>
    <!-- How It Works End -->

    <!-- Destinations Section Start (Dinamis dari Database: Tabel Articles) -->
    <section class="destinations-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Rekomendasi Destinasi</span>
                <h2>Jelajahi Spot Camping & Outdoor Favorit</h2>
                <p>Belum punya ide mau camping ke mana? Lihat beberapa destinasi wisata alam terpopuler pilihan komunitas petualang KampSewa langsung dari database kami.</p>
            </div>

            <div class="dest-filter-container text-center mb-5">
                <button type="button" class="dest-filter-btn active" data-filter="populer" onclick="switchDestFilter('populer', this)">
                    <i class="fas fa-fire me-2"></i> Spot Terpopuler
                </button>
                <button type="button" class="dest-filter-btn" data-filter="rating" onclick="switchDestFilter('rating', this)">
                    <i class="fas fa-star me-2"></i> Rating Tertinggi
                </button>
            </div>

            <!-- Destination Grid Container (Dinamis dari Database) -->
            <div id="dest-grid-container" class="row g-4 text-start">
                @foreach($destinasiPopuler as $item)
                    @php
                        $mapsQuery = urlencode($item->judul . ' ' . $item->lokasi . ' Indonesia');
                        $mapsUrl = $item->sumber_url ?: "https://www.google.com/maps/search/?api=1&query={$mapsQuery}";
                        $viewDisplay = number_format($item->views_count, 0, ',', '.') . ' Views';
                    @endphp
                    <div class="col-md-6 col-lg-4 dest-item-card" data-category="populer">
                        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="destination-card-modern" title="Lihat lokasi {{ $item->judul }} di Google Maps">
                            <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" onerror="this.onerror=null; this.src='{{ asset('template/envato/img/tempat-lombok.jpg') }}';">
                            <div class="dest-overlay-bottom">
                                <span class="dest-badge-loc"><i class="fas fa-map-marker-alt text-warning"></i> {{ $item->lokasi }}</span>
                                <h4 class="dest-title">{{ $item->judul }}</h4>
                                <div class="dest-meta-row">
                                    <span><i class="fas fa-star text-warning"></i> 5.0 ({{ $viewDisplay }})</span>
                                    <span class="text-info fw-bold">Buka Peta <i class="fas fa-map-marked-alt ms-1"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

                @foreach($destinasiRating as $item)
                    @php
                        $mapsQuery = urlencode($item->judul . ' ' . $item->lokasi . ' Indonesia');
                        $mapsUrl = $item->sumber_url ?: "https://www.google.com/maps/search/?api=1&query={$mapsQuery}";
                        $viewDisplay = number_format($item->views_count, 0, ',', '.') . ' Views';
                    @endphp
                    <div class="col-md-6 col-lg-4 dest-item-card" data-category="rating" style="display: none;">
                        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="destination-card-modern" title="Lihat lokasi {{ $item->judul }} di Google Maps">
                            <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" onerror="this.onerror=null; this.src='{{ asset('template/envato/img/tempat-papuma.png') }}';">
                            <div class="dest-overlay-bottom">
                                <span class="dest-badge-loc"><i class="fas fa-map-marker-alt text-warning"></i> {{ $item->lokasi }}</span>
                                <h4 class="dest-title">{{ $item->judul }}</h4>
                                <div class="dest-meta-row">
                                    <span><i class="fas fa-star text-warning"></i> 5.0 ({{ $viewDisplay }})</span>
                                    <span class="text-info fw-bold">Buka Peta <i class="fas fa-map-marked-alt ms-1"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('landing-page.halaman-destinasi') }}" class="btn-modern-primary">
                    <span>Lihat Semua Destinasi</span>
                    <i class="fas fa-map-marked-alt"></i>
                </a>
            </div>
        </div>
    </section>
    <!-- Destinations Section End -->

    <!-- Partner & Vendor Terpercaya (Toko Populer Dinamis dari Database) Start -->
    <section class="vendor-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Mitra Resmi</span>
                <h2>Partner & Vendor Store Terpercaya</h2>
                <p>Bekerja sama dengan brand outdoor nomor satu dan penyedia jasa rental terverifikasi untuk menjamin kepuasan petualanganmu.</p>
            </div>

            <div class="row g-4">
                @forelse($tokoPopuler as $toko)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="vendor-card-modern">
                            <div class="vendor-banner-img">
                                <img src="{{ $toko->foto_toko_url }}" alt="{{ $toko->name_store }}" onerror="this.onerror=null; this.src='{{ asset('template/envato/img/toko-eiger.jpeg') }}';">
                                @if($toko->is_verified)
                                    <span class="vendor-verified-tag"><i class="fas fa-check-circle"></i> Verified</span>
                                @endif
                            </div>
                            <div class="vendor-info-box">
                                <h4>{{ $toko->name_store }}</h4>
                                <p>{{ $toko->slogan_toko ?? $toko->deskripsi_toko ?? 'Rental Alat Outdoor Terpercaya' }}</p>
                                <span class="vendor-stats-pill">
                                    <i class="fas fa-star text-warning me-1"></i> {{ number_format($toko->rating_toko ?? 5.0, 1) }} | {{ $toko->badge_toko ?? 'Mitra Unggulan' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">Daftar toko populer sedang dipersiapkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Partner & Vendor Terpercaya End -->

    <!-- Testimonials & Stories Start (Dinamis dari Database) -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Kata Mereka</span>
                <h2>Cerita Seru Petualang KampSewa</h2>
                <p>Simak pengalaman nyata dari pendaki gunung, campers, dan komunitas yang telah merasakan kemudahan menyewa perlengkapan di KampSewa.</p>
            </div>

            <div class="row g-4">
                @forelse($testimonials as $testi)
                    <div class="col-md-6 col-lg-3">
                        <div class="testimonial-card-modern">
                            <div>
                                <div class="testi-stars">
                                    @for($i = 0; $i < ($testi->rating ?? 5); $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <p class="testi-quote-text">
                                    "{{ $testi->ulasan }}"
                                </p>
                            </div>
                            <div class="testi-user-row">
                                <div class="testi-avatar">
                                    <img src="{{ $testi->avatar_url }}" alt="{{ $testi->nama }}" onerror="this.onerror=null; this.src='{{ asset('template/envato/img/Testimoni-1.jpg') }}';">
                                </div>
                                <div class="testi-user-info">
                                    <h5>{{ $testi->nama }}</h5>
                                    <span>{{ $testi->peran }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">Testimoni sedang dipersiapkan.</p>
                    </div>
                @endforelse
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

    <!-- High-Impact CTA Banner Start (Dinamis dari Database) -->
    <section class="cta-modern-section" id="download-app">
        <div class="container">
            <div class="cta-banner-card">
                <div class="cta-content-inner">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3"><i class="fas fa-rocket me-1"></i> {{ $ctaContent->badge ?? 'Aplikasi KampSewa Mobile' }}</span>
                    <h2>{{ $ctaContent->title ?? 'Siap Memulai Petualangan Serumu Berikutnya?' }}</h2>
                    <p>{{ $ctaContent->subtitle ?? 'Unduh aplikasi KampSewa sekarang di smartphonemu! Nikmati kemudahan booking perlengkapan outdoor di mana saja, kapan saja, lengkap dengan promo diskon perdana untuk pengguna baru.' }}</p>
                    
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

    <!-- Destination Filter Script (Instant & Dynamic dari Database) -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        window.switchDestFilter = function(filter, btnElement) {
            document.querySelectorAll('.dest-filter-btn').forEach(b => b.classList.remove('active'));
            if (btnElement) {
                btnElement.classList.add('active');
            }
            
            document.querySelectorAll('.dest-item-card').forEach(card => {
                if (card.getAttribute('data-category') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        };

        window.showAppModal = function(platform) {
            const el = document.getElementById('app-platform-name');
            if (el) el.textContent = platform;
        };
    });
    </script>

    <!-- JavaScript Libraries -->
    @include('landing-page.footer')
