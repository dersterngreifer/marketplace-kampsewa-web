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

        <!-- Hero Banner Start -->
        <div class="page-hero-banner">
            <div class="page-hero-bg" style="background-image: url('{{ asset('images/pexels-toulouse-3195757.jpg') }}');"></div>
            <div class="page-hero-overlay d-flex align-items-center">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10 text-center">
                            <h4 class="text-white text-uppercase fw-bold mb-3" style="letter-spacing: 5px;">Tentang Kami</h4>
                            <h1 class="display-3 text-capitalize text-white mb-3 fw-bold">Mengenal KampSewa <span class="text-light">Lebih Dekat</span></h1>
                            <p class="mb-0 fs-5 text-white mx-auto" style="max-width: 750px;">Pelajari visi, misi, dan komitmen kami dalam menyediakan layanan penyewaan perlengkapan camping terbaik untuk mendukung setiap petualangan Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero Banner End -->
    </div>
    <!-- Navbar & Hero End -->

    <!-- Cerita Kami Start -->
    <div class="container-fluid tentang-kami py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="cerita-image-wrapper">
                        <img src="{{ asset('template/envato/img/home-4.jpg') }}" class="img-fluid rounded-4 shadow-lg" alt="Cerita KampSewa">
                        <div class="cerita-badge d-none d-lg-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <span class="d-block display-6 fw-bold text-white">2024</span>
                                <span class="d-block text-white small">Berdiri</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="cerita-content">
                        <h5 class="section-title text-primary px-3 mb-3">Cerita Kami</h5>
                        <h1 class="mb-4 fw-bold">Mendukung Setiap <span class="text-primary">Petualanganmu</span></h1>
                        <p class="mb-3" style="text-align: justify; line-height: 1.8;">KampSewa lahir dari kecintaan kami terhadap alam bebas dan keinginan untuk membuat kegiatan outdoor lebih mudah diakses oleh semua kalangan. Kami menyadari bahwa membeli peralatan camping berkualitas seringkali membutuhkan biaya yang besar, perawatan ekstra, dan ruang penyimpanan yang tidak sedikit di rumah.</p>
                        <p class="mb-4" style="text-align: justify; line-height: 1.8;">Oleh karena itu, kami hadir sebagai solusi yang praktis, ekonomis, dan berkelanjutan. Dengan menghubungkan para pecinta alam dengan vendor-vendor penyewaan alat outdoor terpercaya di berbagai daerah, kami memastikan setiap orang dapat menikmati keindahan alam dan pengalaman berkemah yang nyaman tanpa batasan finansial maupun logistik.</p>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center bg-light rounded-3 p-3">
                                    <div class="flex-shrink-0 bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                                        <i class="fa fa-check text-white"></i>
                                    </div>
                                    <span class="fw-medium">Lebih Hemat & Praktis</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center bg-light rounded-3 p-3">
                                    <div class="flex-shrink-0 bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                                        <i class="fa fa-check text-white"></i>
                                    </div>
                                    <span class="fw-medium">Dukung Vendor Lokal</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center bg-light rounded-3 p-3">
                                    <div class="flex-shrink-0 bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                                        <i class="fa fa-check text-white"></i>
                                    </div>
                                    <span class="fw-medium">Kualitas Terjamin</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center bg-light rounded-3 p-3">
                                    <div class="flex-shrink-0 bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                                        <i class="fa fa-check text-white"></i>
                                    </div>
                                    <span class="fw-medium">Ramah Lingkungan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cerita Kami End -->

    <!-- Visi Misi Start -->
    <div class="container-fluid bg-light visi-misi py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5" style="max-width: 900px;">
                <h5 class="section-title text-primary px-3">Visi & Misi</h5>
                <h1 class="mb-0 fw-bold">Semangat yang <span class="text-primary">Menggerakkan Kami</span></h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="visi-card h-100">
                        <div class="visi-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h2 class="text-primary mb-3">Visi Kami</h2>
                        <p class="mb-0 fs-5" style="line-height: 1.8;">"Menjadi platform penyewaan perlengkapan outdoor terdepan dan terpercaya di Indonesia yang menginspirasi lebih banyak orang untuk mencintai dan menjaga kelestarian alam melalui pengalaman petualangan yang aman, nyaman, dan terjangkau."</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="visi-card h-100">
                        <div class="visi-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h2 class="text-primary mb-3">Misi Kami</h2>
                        <ul class="mb-0 fs-5" style="line-height: 1.8; list-style: none; padding-left: 0;">
                            <li class="mb-3 d-flex align-items-start">
                                <span class="text-primary me-3 mt-1"><i class="fas fa-check-circle"></i></span>
                                <span>Menyediakan akses mudah, cepat, dan terpercaya ke peralatan camping berkualitas premium.</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <span class="text-primary me-3 mt-1"><i class="fas fa-check-circle"></i></span>
                                <span>Membangun ekosistem ekonomi kolaboratif yang memberdayakan vendor penyewaan alat outdoor lokal.</span>
                            </li>
                            <li class="mb-0 d-flex align-items-start">
                                <span class="text-primary me-3 mt-1"><i class="fas fa-check-circle"></i></span>
                                <span>Mendorong gaya hidup ramah lingkungan (eco-friendly) dengan mengurangi konsumsi berlebih melalui konsep berbagi (sewa).</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Visi Misi End -->

    <!-- Nilai Utama Start -->
    <div class="container-fluid nilai-utama py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5" style="max-width: 900px;">
                <h5 class="section-title text-primary px-3">Kenapa KampSewa?</h5>
                <h1 class="mb-0 fw-bold">Nilai-Nilai Utama <span class="text-primary">Pelayanan Kami</span></h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="nilai-card text-center p-4 h-100">
                        <div class="nilai-icon mx-auto mb-4">
                            <i class="fa fa-shield-alt fa-3x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Keamanan & Kualitas</h4>
                        <p class="mb-0">Kami memiliki standar tinggi. Semua peralatan dari mitra vendor selalu melalui proses pengecekan kelayakan pakai dan kebersihan sebelum disewakan kepada Anda.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="nilai-card text-center p-4 h-100">
                        <div class="nilai-icon mx-auto mb-4">
                            <i class="fa fa-handshake fa-3x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Mitra Terpercaya</h4>
                        <p class="mb-0">KampSewa menyeleksi dan bermitra dengan penyedia alat camping berpengalaman di berbagai kota untuk memastikan Anda mendapatkan pelayanan yang ramah, tepat waktu, dan profesional.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="nilai-card text-center p-4 h-100">
                        <div class="nilai-icon mx-auto mb-4">
                            <i class="fa fa-leaf fa-3x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Kelestarian Alam</h4>
                        <p class="mb-0">Kami sangat peduli pada bumi. Dengan memilih untuk menyewa, Anda ikut berkontribusi mengurangi produksi limbah dan jejak karbon, mendukung pariwisata yang berkelanjutan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Nilai Utama End -->

    <!-- Call to Action Start -->
    <div class="container-fluid bg-primary py-5 cta-section position-relative overflow-hidden">
        <div class="container py-5 text-center position-relative" style="z-index: 2;">
            <h1 class="text-white mb-4 fw-bold">Siap Memulai Petualangan Anda Selanjutnya?</h1>
            <p class="text-white mb-5 fs-5 opacity-90">Jelajahi berbagai pilihan destinasi wisata alam dan temukan peralatan camping terbaik di KampSewa hari ini juga.</p>
            <a href="{{ route('landing-page.halaman-destinasi') }}" class="btn btn-light rounded-pill py-3 px-5 text-primary fw-bold mx-2 mb-2 shadow-sm">Jelajahi Destinasi</a>
            <a href="{{ route('landing-page.halaman-sewabarang') }}" class="btn btn-outline-light rounded-pill py-3 px-5 fw-bold mx-2 mb-2">Sewa Perlengkapan</a>
        </div>
    </div>
    <!-- Call to Action End -->

    @include('landing-page.halamanbawah')
    @include('landing-page.footer')
</body>
</html>