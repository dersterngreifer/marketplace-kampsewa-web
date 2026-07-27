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

        <!-- Cinematic Destinasi Hero Section -->
        <section class="hero-destinasi-modern">
            <!-- Background Slider -->
            <div class="hero-bg-carousel">
                <div id="heroDestinasiCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('template/envato/img/tempat-papuma.png') }}" alt="Tanjung Papuma">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('template/envato/img/tempat-tumpak-sewu.webp') }}" alt="Air Terjun Tumpak Sewu">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('template/envato/img/tempat-prau.webp') }}" alt="Gunung Prau">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('template/envato/img/tempat-Bali.jpg') }}" alt="Wisata Bali">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gradient Overlay -->
            <div class="hero-overlay-gradient"></div>

            <!-- Hero Content -->
            <div class="container destinasi-hero-content">
                <div class="badge-hero-pill">
                    <i class="fas fa-compass text-warning"></i>
                    <span>#1 Eksplorasi Destinasi Wisata Alam & Camping Indonesia</span>
                </div>

                <h1 class="about-hero-title">
                    Jelajahi Surga Alam <br>
                    <span class="text-gradient-amber">Nusantara Indonesia</span>
                </h1>

                <p class="about-hero-desc">
                    Temukan ratusan destinasi camping, glamping, gunung, pantai, dan air terjun terbaik dari Sabang sampai Merauke. Dilengkapi integrasi lokasi akurat langsung ke Google Maps!
                </p>

                <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                    <a href="#eksplor-destinasi" class="btn-modern-amber">
                        <i class="fas fa-search-location"></i>
                        <span>Mulai Eksplorasi Sekarang</span>
                    </a>
                    <a href="{{ route('landing-page.halaman-sewabarang') }}" class="btn-outline-white">
                        <i class="fas fa-campground"></i>
                        <span>Sewa Perlengkapan</span>
                    </a>
                </div>
            </div>
        </section>
    </div>
    <!-- Navbar & Hero End -->

    <!-- Filter & Search Section Start -->
    <section id="eksplor-destinasi" class="destinasi-filter-section">
        <div class="container">
            <div class="destinasi-filter-card">
                <!-- Search Bar Row -->
                <div class="dest-search-box">
                    <i class="fas fa-search dest-search-icon"></i>
                    <input type="text" id="searchInput" class="dest-search-input" placeholder="Cari nama destinasi, daerah, kota, alamat, atau gunung (contoh: Bromo, Bandung, Bali, Toba)...">
                    <button id="clearSearchBtn" class="dest-search-clear" title="Hapus pencarian"><i class="fas fa-times"></i></button>
                </div>

                <!-- Region Filter Tabs -->
                <span class="dest-filter-label"><i class="fas fa-map-marked-alt text-primary me-2"></i> Filter Wilayah & Provinsi:</span>
                <div class="dest-region-tabs" id="regionFilterContainer">
                    <button class="dest-region-btn active" data-region="all">
                        <i class="fas fa-globe-asia"></i> <span>Semua Wilayah</span>
                    </button>
                    <button class="dest-region-btn" data-region="Jawa Barat">
                        <i class="fas fa-mountain"></i> <span>Jawa Barat</span>
                    </button>
                    <button class="dest-region-btn" data-region="Jawa Tengah & DIY">
                        <i class="fas fa-campground"></i> <span>Jawa Tengah & DIY</span>
                    </button>
                    <button class="dest-region-btn" data-region="Jawa Timur">
                        <i class="fas fa-fire"></i> <span>Jawa Timur</span>
                    </button>
                    <button class="dest-region-btn" data-region="Bali & Nusa Tenggara">
                        <i class="fas fa-sun"></i> <span>Bali & Nusa Tenggara</span>
                    </button>
                    <button class="dest-region-btn" data-region="Sumatera">
                        <i class="fas fa-tree"></i> <span>Sumatera</span>
                    </button>
                    <button class="dest-region-btn" data-region="Sulawesi & Timur">
                        <i class="fas fa-water"></i> <span>Sulawesi & Indonesia Timur</span>
                    </button>
                </div>

                <!-- Category Filter Chips -->
                <span class="dest-filter-label"><i class="fas fa-tags text-warning me-2"></i> Kategori Wisata Alam:</span>
                <div class="dest-category-chips" id="categoryFilterContainer">
                    <button class="dest-category-btn active" data-category="all">
                        <span>Semua Kategori</span>
                    </button>
                    <button class="dest-category-btn" data-category="Gunung & Hiking">
                        <span>⛰️ Gunung & Hiking</span>
                    </button>
                    <button class="dest-category-btn" data-category="Pantai & Pulau">
                        <span>🏖️ Pantai & Pulau</span>
                    </button>
                    <button class="dest-category-btn" data-category="Danau & Air Terjun">
                        <span>🌊 Danau & Air Terjun</span>
                    </button>
                    <button class="dest-category-btn" data-category="Hutan & Taman Nasional">
                        <span>🌲 Hutan & Taman Nasional</span>
                    </button>
                    <button class="dest-category-btn" data-category="Glamping & Camp Area">
                        <span>⛺ Glamping & Camp Area</span>
                    </button>
                </div>

                <!-- Results status bar -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top gap-2">
                    <div id="resultsStatus" class="fw-bold text-dark font-display" style="font-size: 1.1rem;">
                        <i class="fas fa-list-ul text-primary me-2"></i> Menampilkan <span id="countDisplay" class="text-primary fw-bolder">0</span> destinasi wisata di Indonesia
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted text-sm fw-bold">Urutkan:</span>
                        <select id="sortSelect" class="form-select form-select-sm border-2 fw-bold" style="width: auto; border-radius: 10px;">
                            <option value="recommended">⭐ Rekomendasi Pilihan</option>
                            <option value="name-asc">🔤 Abjad (A - Z)</option>
                            <option value="name-desc">🔤 Abjad (Z - A)</option>
                            <option value="rating-desc">🏆 Rating Kepuasan</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Destinations Grid Container -->
            <div id="destGridContainer" class="dest-grid-container">
                <!-- Dynamic Content injected via JS -->
            </div>
            
            <!-- Loading indicator -->
            <div id="loadingIndicator" class="text-center py-5" style="display: none;">
                <div class="spinner-border text-primary" style="width: 3.5rem; height: 3.5rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-3 fw-bold text-muted">Mengambil data foto dari Wikipedia API Gratis...</p>
            </div>
        </div>
    </section>
    <!-- Filter & Search Section End -->

    <!-- JavaScript untuk Filter, Pencarian, Google Maps & Wikipedia REST API Gratis -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Dataset Lengkap Destinasi Wisata di Indonesia
        const rawDestinations = [
            {
                id: 1,
                title: "Ranca Upas Ciwidey",
                region: "Jawa Barat",
                category: "Glamping & Camp Area",
                address: "Jl. Raya Ciwidey - Patengan No.Km. 11, Patengan, Rancabali, Kabupaten Bandung, Jawa Barat 40973",
                desc: "Kawasan camping ground legendaris di Bandung Selatan dengan udara sejuk kabut pagi, pemandian air panas alami, dan penangkaran rusa yang jinak.",
                rating: 4.8,
                reviews: 1240,
                recommended: true,
                wikiQuery: "Ranca Upas",
                fallbackImg: "{{ asset('template/envato/img/tempat-ranca.webp') }}"
            },
            {
                id: 2,
                title: "Pantai Batu Karas",
                region: "Jawa Barat",
                category: "Pantai & Pulau",
                address: "Desa Batukaras, Cijulang, Kabupaten Pangandaran, Jawa Barat 46394",
                desc: "Perpaduan sempurna antara pantai tenang untuk bersantai dan ombak ramah untuk berselancar. Pilihan terbaik untuk mendirikan tenda di pesisir.",
                rating: 4.7,
                reviews: 890,
                recommended: false,
                wikiQuery: "Batukaras",
                fallbackImg: "{{ asset('template/envato/img/tempat-batu-karas.jpeg') }}"
            },
            {
                id: 3,
                title: "Tebing Keraton Dago",
                region: "Jawa Barat",
                category: "Hutan & Taman Nasional",
                address: "Lembang, Ciburial, Cimenyan, Kabupaten Bandung Barat, Jawa Barat 40198",
                desc: "Spot spektakuler untuk menyaksikan matahari terbit di atas lautan kabut yang menyelimuti lebatnya Hutan Raya Ir. H. Djuanda Bandung.",
                rating: 4.9,
                reviews: 1560,
                recommended: true,
                wikiQuery: "Tebing Keraton",
                fallbackImg: "{{ asset('template/envato/img/tempat-dago.webp') }}"
            },
            {
                id: 4,
                title: "Taman Nasional Gunung Gede Pangrango",
                region: "Jawa Barat",
                category: "Gunung & Hiking",
                address: "Jl. Raya Cibodas, Cipanas, Kabupaten Cianjur, Jawa Barat 43253",
                desc: "Destinasi favorit pendaki dengan keanekaragaman hayati edelweiss di Alun-Alun Suryakencana serta air terjun Cibeureum yang megah.",
                rating: 4.9,
                reviews: 3200,
                recommended: true,
                wikiQuery: "Taman Nasional Gunung Gede Pangrango",
                fallbackImg: "{{ asset('template/envato/img/tempat-taman-nasional.jpg') }}"
            },
            {
                id: 5,
                title: "Gunung Prau Dieng",
                region: "Jawa Tengah & DIY",
                category: "Gunung & Hiking",
                address: "Bakalan, Batur, Banjarnegara, Jawa Tengah 53456",
                desc: "Memiliki puncak bukit teletubbies dengan golden sunrise terbaik se-Asia Tenggara dan panorama jajaran Sindoro-Sumbing yang menakjubkan.",
                rating: 4.9,
                reviews: 2890,
                recommended: true,
                wikiQuery: "Gunung Prau",
                fallbackImg: "{{ asset('template/envato/img/tempat-prau.webp') }}"
            },
            {
                id: 6,
                title: "Wana Wisata Nglimut",
                region: "Jawa Tengah & DIY",
                category: "Glamping & Camp Area",
                address: "Nglimut, Gonoharjo, Limbangan, Kabupaten Kendal, Jawa Tengah 51383",
                desc: "Bumi perkemahan di lereng Gunung Ungaran dengan suasana hutan pinus yang asri, sumber air panas alami, dan rute trekking yang nyaman.",
                rating: 4.6,
                reviews: 640,
                recommended: false,
                wikiQuery: "Gunung Ungaran",
                fallbackImg: "{{ asset('template/envato/img/tempat-nglimut.webp') }}"
            },
            {
                id: 7,
                title: "Telaga Dringo Dieng",
                region: "Jawa Tengah & DIY",
                category: "Danau & Air Terjun",
                address: "Pekasiran, Batur, Banjarnegara, Jawa Tengah 53456",
                desc: "Sering disebut sebagai Ranu Kumbolonya Jawa Tengah. Telaga alami di dataran tinggi Dieng yang sangat cocok untuk camping tenang bersama sahabat.",
                rating: 4.8,
                reviews: 780,
                recommended: true,
                wikiQuery: "Telaga Dringo",
                fallbackImg: "{{ asset('template/envato/img/tempat-dringo.jpg') }}"
            },
            {
                id: 8,
                title: "Umbul Sidomukti Bandungan",
                region: "Jawa Tengah & DIY",
                category: "Glamping & Camp Area",
                address: "Manggung, Jimbaran, Bandungan, Kabupaten Semarang, Jawa Tengah 50661",
                desc: "Kawasan wisata alam pegunungan dengan kolam renang bertingkat dari mata air alami, flying fox lembah, dan area pondok wisata camping.",
                rating: 4.7,
                reviews: 1420,
                recommended: false,
                wikiQuery: "Bandungan, Semarang",
                fallbackImg: "{{ asset('template/envato/img/tempat-umbul.jpg') }}"
            },
            {
                id: 9,
                title: "Air Terjun Tumpak Sewu",
                region: "Jawa Timur",
                category: "Danau & Air Terjun",
                address: "Jl. Sidomulyo No. 1, Pronojiwo, Lumajang, Jawa Timur 67374",
                desc: "Air terjun berundak raksasa yang menyerupai tirai megah bak Niagaranya Indonesia, dengan latar belakang kegagahan Gunung Semeru.",
                rating: 5.0,
                reviews: 4100,
                recommended: true,
                wikiQuery: "Air terjun Tumpak Sewu",
                fallbackImg: "{{ asset('template/envato/img/tempat-tumpak-sewu.webp') }}"
            },
            {
                id: 10,
                title: "Taman Nasional Baluran",
                region: "Jawa Timur",
                category: "Hutan & Taman Nasional",
                address: "Jl. Raya Situbondo-Banyuwangi, Banyuputih, Kabupaten Situbondo, Jawa Timur 68374",
                desc: "Africa van Java dengan Savana Bekol yang eksotis, habitat satwa liar seperti banteng dan merak, serta keindahan pesisir Pantai Bama.",
                rating: 4.9,
                reviews: 2300,
                recommended: true,
                wikiQuery: "Taman Nasional Baluran",
                fallbackImg: "{{ asset('template/envato/img/tempat-taman-nasional-baluran.jpeg') }}"
            },
            {
                id: 11,
                title: "Tanjung Papuma Jember",
                region: "Jawa Timur",
                category: "Pantai & Pulau",
                address: "Jl. Raya Papuma, Lojejer, Wuluhan, Kabupaten Jember, Jawa Timur 68162",
                desc: "Pantai pasir putih menawan yang dikelilingi perbukitan hutan lindung dan batu karang eksotis Atu Buta di tengah empasan ombak laut selatan.",
                rating: 4.8,
                reviews: 1850,
                recommended: true,
                wikiQuery: "Pantai Papuma",
                fallbackImg: "{{ asset('template/envato/img/tempat-papuma.png') }}"
            },
            {
                id: 12,
                title: "Gunung Bromo Tengger Semeru",
                region: "Jawa Timur",
                category: "Gunung & Hiking",
                address: "Podokoyo, Tosari, Pasuruan, Jawa Timur 67248",
                desc: "Ikon pariwisata Indonesia dengan kawah aktif yang megah, lautan pasir berbisik, Bukit Teletubbies, dan keindahan sunrise Penanjakan.",
                rating: 5.0,
                reviews: 5600,
                recommended: true,
                wikiQuery: "Gunung Bromo",
                fallbackImg: "{{ asset('template/envato/img/home-1.jpg') }}"
            },
            {
                id: 13,
                title: "Kawasan Kebun Teh Gunung Gambir",
                region: "Jawa Timur",
                category: "Glamping & Camp Area",
                address: "Gelang, Sumberbaru, Kabupaten Jember, Jawa Timur 68158",
                desc: "Hamparan perkebunan teh peninggalan kolonial Belanda dengan jembatan layang kayu, camping area berhawa sejuk, dan suasana tenang alami.",
                rating: 4.6,
                reviews: 520,
                recommended: false,
                wikiQuery: "Jember",
                fallbackImg: "{{ asset('template/envato/img/tempat-gunung-gambir.jpg') }}"
            },
            {
                id: 14,
                title: "Gunung Rinjani Lombok",
                region: "Bali & Nusa Tenggara",
                category: "Gunung & Hiking",
                address: "Sembalun Lawang, Sembalun, Kabupaten Lombok Timur, Nusa Tenggara Barat 83656",
                desc: "Gunung berapi tertinggi kedua di Indonesia dengan keindahan magis Danau Segara Anak dan Gunung Barujari di tengah kaldera raksasa.",
                rating: 5.0,
                reviews: 4800,
                recommended: true,
                wikiQuery: "Gunung Rinjani",
                fallbackImg: "{{ asset('template/envato/img/tempat-lombok.jpg') }}"
            },
            {
                id: 15,
                title: "Kelingking Beach Nusa Penida",
                region: "Bali & Nusa Tenggara",
                category: "Pantai & Pulau",
                address: "Bunga Mekar, Nusa Penida, Kabupaten Klungkung, Bali 80771",
                desc: "Tebing ikonik berbentuk T-Rex yang menjorok ke samudra biru turkui, salah satu spot pantai paling terkenal di seluruh dunia.",
                rating: 4.9,
                reviews: 3900,
                recommended: true,
                wikiQuery: "Nusa Penida",
                fallbackImg: "{{ asset('template/envato/img/tempat-kelingking.webp') }}"
            },
            {
                id: 16,
                title: "Pantai Amed Karangasem",
                region: "Bali & Nusa Tenggara",
                category: "Pantai & Pulau",
                address: "Desa Amed, Abang, Kabupaten Karangasem, Bali 80852",
                desc: "Pesisir timur Bali dengan pemandangan langsung Gunung Agung dari tepi pantai pasir hitam, surga bagi pecinta snorkeling dan diving.",
                rating: 4.8,
                reviews: 1720,
                recommended: false,
                wikiQuery: "Amed, Bali",
                fallbackImg: "{{ asset('template/envato/img/tempat-amed.webp') }}"
            },
            {
                id: 17,
                title: "Campuhan Ridge Walk Ubud",
                region: "Bali & Nusa Tenggara",
                category: "Hutan & Taman Nasional",
                address: "Jl. Raya Campuhan, Sayan, Ubud, Kabupaten Gianyar, Bali 80571",
                desc: "Jalur trekking lembah perbukitan hijau di jantung budaya Ubud yang menawarkan keheningan alam serta pemandangan padang rumput tropis.",
                rating: 4.7,
                reviews: 2100,
                recommended: false,
                wikiQuery: "Ubud",
                fallbackImg: "{{ asset('template/envato/img/tempat-campuhan.jpeg') }}"
            },
            {
                id: 18,
                title: "Taman Nasional Komodo",
                region: "Bali & Nusa Tenggara",
                category: "Pantai & Pulau",
                address: "Pulau Komodo, Labuan Bajo, Komodo, Kabupaten Manggarai Barat, Nusa Tenggara Timur 86554",
                desc: "Situs Warisan Dunia UNESCO tempat habitat asli kadal raksasa Komodo, dengan keindahan Pink Beach dan panorama Pulau Padar yang menakjubkan.",
                rating: 5.0,
                reviews: 6200,
                recommended: true,
                wikiQuery: "Taman Nasional Komodo",
                fallbackImg: "{{ asset('template/envato/img/home-2.jpg') }}"
            },
            {
                id: 19,
                title: "Lembah Harau Payakumbuh",
                region: "Sumatera",
                category: "Danau & Air Terjun",
                address: "Tarantang, Harau, Kabupaten Lima Puluh Kota, Sumatera Barat 26271",
                desc: "Lembah subur yang diapit oleh tebing batu granit tegak lurus setinggi 100-500 meter dengan banyak air terjun jernih, bak Lembah Yosemite Indonesia.",
                rating: 4.9,
                reviews: 2450,
                recommended: true,
                wikiQuery: "Lembah Harau",
                fallbackImg: "{{ asset('template/envato/img/tempat-lembah-harau.webp') }}"
            },
            {
                id: 20,
                title: "Danau Maninjau Bukittinggi",
                region: "Sumatera",
                category: "Danau & Air Terjun",
                address: "Tanjung Raya, Kabupaten Agam, Sumatera Barat 26471",
                desc: "Danau vulkanik yang indah dan tenang yang terkenal dengan rute Kelok 44 serta kuliner ikan rinuak khas dataran tinggi Minangkabau.",
                rating: 4.7,
                reviews: 1320,
                recommended: false,
                wikiQuery: "Danau Maninjau",
                fallbackImg: "{{ asset('template/envato/img/tempat-maninjau.webp') }}"
            },
            {
                id: 21,
                title: "Danau Toba & Pulau Samosir",
                region: "Sumatera",
                category: "Danau & Air Terjun",
                address: "Pulau Samosir, Kabupaten Samosir, Sumatera Utara 22395",
                desc: "Danau vulkanik terbesar di dunia dengan Pulau Samosir di tengahnya. Menawarkan pesona budaya Batak serta spot camping tepi danau yang memukau.",
                rating: 5.0,
                reviews: 5100,
                recommended: true,
                wikiQuery: "Danau Toba",
                fallbackImg: "{{ asset('template/envato/img/home-3.jpg') }}"
            },
            {
                id: 22,
                title: "Tanjung Bira Bulukumba",
                region: "Sulawesi & Timur",
                category: "Pantai & Pulau",
                address: "Jl. Kapten Tendean, Bira, Bontobahari, Kabupaten Bulukumba, Sulawesi Selatan 92571",
                desc: "Pantai pasir putih yang sehalus tepung di ujung selatan Sulawesi, terkenal dengan sentra pembuatan kapal tradisional Phinisi.",
                rating: 4.8,
                reviews: 1980,
                recommended: true,
                wikiQuery: "Tanjung Bira",
                fallbackImg: "{{ asset('template/envato/img/tempat-tanjung-bira.webp') }}"
            },
            {
                id: 23,
                title: "Taman Nasional Kepulauan Kapoposang",
                region: "Sulawesi & Timur",
                category: "Pantai & Pulau",
                address: "Mattiro Ujung, Liukang Tupabbiring, Kabupaten Pangkajene Dan Kepulauan, Sulawesi Selatan 90671",
                desc: "Surga tersembunyi bagi para penyeram dengan terumbu karang yang masih sangat terjaga dan perairan biru jernih di Selat Makassar.",
                rating: 4.8,
                reviews: 740,
                recommended: false,
                wikiQuery: "Kepulauan Spermonde",
                fallbackImg: "{{ asset('template/envato/img/tempat-kepoposang.webp') }}"
            },
            {
                id: 24,
                title: "Pulau Samalona Makassar",
                region: "Sulawesi & Timur",
                category: "Pantai & Pulau",
                address: "Lae-Lae, Ujung Pandang, Kota Makassar, Sulawesi Selatan 90111",
                desc: "Pulau kecil eksotis berpasir putih yang hanya berjarak 30 menit berkendara perahu dari Pantai Losari Makassar. Tempat ideal untuk camping pulau.",
                rating: 4.7,
                reviews: 1150,
                recommended: false,
                wikiQuery: "Pulau Samalona",
                fallbackImg: "{{ asset('template/envato/img/tempat-samalona.webp') }}"
            },
            {
                id: 25,
                title: "Kepulauan Raja Ampat",
                region: "Sulawesi & Timur",
                category: "Pantai & Pulau",
                address: "Waisai, Kota Waisai, Kabupaten Raja Ampat, Papua Barat Daya 98482",
                desc: "Gugusan pulau karst dengan keanekaragaman hayati laut tertinggi di bumi. Destinasi impian setiap petualang alam dan penyelam dunia.",
                rating: 5.0,
                reviews: 6400,
                recommended: true,
                wikiQuery: "Raja Ampat",
                fallbackImg: "{{ asset('template/envato/img/home-6.jpg') }}"
            },
            {
                id: 26,
                title: "Dataran Tinggi Tana Toraja",
                region: "Sulawesi & Timur",
                category: "Gunung & Hiking",
                address: "Rantepao, Kabupaten Toraja Utara, Sulawesi Selatan 91831",
                desc: "Negeri di atas awan dengan keunikan budaya magis rumah adat Tongkonan, kuburan batu tebing Londa, dan panorama perbukitan hijau.",
                rating: 4.9,
                reviews: 3100,
                recommended: true,
                wikiQuery: "Tana Toraja",
                fallbackImg: "{{ asset('template/envato/img/tempat-rembangan.jpg') }}"
            }
        ];

        let destDataList = [];
        let currentRegion = "all";
        let currentCategory = "all";
        let currentSearchQuery = "";
        let currentSort = "recommended";

        // Element Referensi
        const gridContainer = document.getElementById("destGridContainer");
        const loadingIndicator = document.getElementById("loadingIndicator");
        const countDisplay = document.getElementById("countDisplay");
        const searchInput = document.getElementById("searchInput");
        const clearSearchBtn = document.getElementById("clearSearchBtn");
        const sortSelect = document.getElementById("sortSelect");

        // 1. Ambil Foto dari Wikipedia REST API Gratis + Fallback Placeholder
        async function fetchWikipediaImages() {
            loadingIndicator.style.display = "block";
            gridContainer.style.display = "none";

            const promises = rawDestinations.map(async (item) => {
                const query = item.wikiQuery || item.title;
                const wikiUrl = `https://id.wikipedia.org/api/rest_v1/page/summary/${encodeURIComponent(query)}`;
                
                try {
                    const response = await fetch(wikiUrl);
                    if (response.ok) {
                        const data = await response.json();
                        if (data && data.thumbnail && data.thumbnail.source) {
                            return { ...item, finalImg: data.thumbnail.source };
                        }
                    }
                } catch (err) {
                    console.warn(`Wiki API fallback for ${item.title}`, err);
                }
                return { ...item, finalImg: item.fallbackImg };
            });

            destDataList = await Promise.all(promises);
            loadingIndicator.style.display = "none";
            gridContainer.style.display = "grid";
            renderDestinations();
        }

        // 2. Render Destinasi dengan Filtering, Searching & Sorting
        function renderDestinations() {
            let filtered = destDataList.filter(item => {
                // Filter Wilayah
                if (currentRegion !== "all" && item.region !== currentRegion) {
                    return false;
                }
                // Filter Kategori
                if (currentCategory !== "all" && item.category !== currentCategory) {
                    return false;
                }
                // Filter Keyword Search
                if (currentSearchQuery.trim() !== "") {
                    const q = currentSearchQuery.toLowerCase();
                    const matchTitle = item.title.toLowerCase().includes(q);
                    const matchRegion = item.region.toLowerCase().includes(q);
                    const matchAddress = item.address.toLowerCase().includes(q);
                    const matchDesc = item.desc.toLowerCase().includes(q);
                    const matchCat = item.category.toLowerCase().includes(q);
                    if (!matchTitle && !matchRegion && !matchAddress && !matchDesc && !matchCat) {
                        return false;
                    }
                }
                return true;
            });

            // Sorting
            if (currentSort === "recommended") {
                filtered.sort((a, b) => (b.recommended === a.recommended) ? b.rating - a.rating : (b.recommended ? 1 : -1));
            } else if (currentSort === "name-asc") {
                filtered.sort((a, b) => a.title.localeCompare(b.title));
            } else if (currentSort === "name-desc") {
                filtered.sort((a, b) => b.title.localeCompare(a.title));
            } else if (currentSort === "rating-desc") {
                filtered.sort((a, b) => b.rating - a.rating);
            }

            countDisplay.textContent = filtered.length;
            gridContainer.innerHTML = "";

            if (filtered.length === 0) {
                gridContainer.innerHTML = `
                    <div class="dest-empty-state">
                        <i class="fas fa-search-location"></i>
                        <h3>Destinasi Tidak Ditemukan</h3>
                        <p>Kami tidak menemukan destinasi wisata dengan kata kunci atau filter tersebut. Coba ubah kata pencarian atau reset filter Anda.</p>
                        <button id="resetFiltersBtn" class="btn-modern-amber mx-auto"><i class="fas fa-sync-alt"></i> <span>Reset Semua Filter</span></button>
                    </div>
                `;
                const resetBtn = document.getElementById("resetFiltersBtn");
                if (resetBtn) {
                    resetBtn.addEventListener("click", () => {
                        currentRegion = "all";
                        currentCategory = "all";
                        currentSearchQuery = "";
                        searchInput.value = "";
                        clearSearchBtn.style.display = "none";
                        document.querySelectorAll(".dest-region-btn").forEach(b => b.classList.remove("active"));
                        document.querySelector('.dest-region-btn[data-region="all"]').classList.add("active");
                        document.querySelectorAll(".dest-category-btn").forEach(b => b.classList.remove("active"));
                        document.querySelector('.dest-category-btn[data-category="all"]').classList.add("active");
                        renderDestinations();
                    });
                }
                return;
            }

            filtered.forEach(item => {
                const mapsQuery = encodeURIComponent(`${item.title} ${item.address} Indonesia`);
                const googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${mapsQuery}`;

                const cardHtml = `
                    <div class="dest-card-modern">
                        <div class="dest-card-img-wrapper">
                            <img src="${item.finalImg}" class="dest-card-img" alt="${item.title}" onerror="this.src='${item.fallbackImg}'">
                            <div class="dest-card-badges">
                                <span class="dest-badge-region"><i class="fas fa-map-marker-alt text-danger"></i> ${item.region}</span>
                                <span class="dest-badge-category">${item.category}</span>
                            </div>
                        </div>
                        <div class="dest-card-body">
                            <h3 class="dest-card-title">${item.title}</h3>
                            <div class="dest-card-address">
                                <i class="fas fa-map-pin"></i>
                                <span>${item.address}</span>
                            </div>
                            <p class="dest-card-desc">${item.desc}</p>
                            <div class="dest-card-tags">
                                <span class="dest-tag"><i class="fas fa-star text-warning"></i> ${item.rating} / 5.0 (${item.reviews}+ ulasan)</span>
                                ${item.recommended ? '<span class="dest-tag" style="background: rgba(245,158,11,0.15); color: #D97706; border-color: #F59E0B;"><i class="fas fa-award"></i> Rekomendasi Unggulan</span>' : ''}
                                <span class="dest-tag"><i class="fas fa-check-circle text-success"></i> Perlengkapan Sewa Ready</span>
                            </div>
                        </div>
                        <div class="dest-card-footer">
                            <a href="${googleMapsUrl}" target="_blank" rel="noopener noreferrer" class="btn-google-maps">
                                <i class="fas fa-map-marked-alt"></i>
                                <span>Lihat di Google Maps</span>
                                <i class="fas fa-external-link-alt ms-1 text-xs" style="font-size: 0.75rem; opacity: 0.8;"></i>
                            </a>
                        </div>
                    </div>
                `;
                gridContainer.insertAdjacentHTML("beforeend", cardHtml);
            });
        }

        // 3. Event Listeners untuk Filter Wilayah
        document.querySelectorAll(".dest-region-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                document.querySelectorAll(".dest-region-btn").forEach(b => b.classList.remove("active"));
                this.classList.add("active");
                currentRegion = this.getAttribute("data-region");
                renderDestinations();
            });
        });

        // 4. Event Listeners untuk Filter Kategori
        document.querySelectorAll(".dest-category-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                document.querySelectorAll(".dest-category-btn").forEach(b => b.classList.remove("active"));
                this.classList.add("active");
                currentCategory = this.getAttribute("data-category");
                renderDestinations();
            });
        });

        // 5. Event Listeners untuk Search Input
        searchInput.addEventListener("input", function () {
            currentSearchQuery = this.value;
            clearSearchBtn.style.display = currentSearchQuery.length > 0 ? "flex" : "none";
            renderDestinations();
        });

        clearSearchBtn.addEventListener("click", function () {
            searchInput.value = "";
            currentSearchQuery = "";
            this.style.display = "none";
            renderDestinations();
            searchInput.focus();
        });

        // 6. Event Listeners untuk Sorting
        sortSelect.addEventListener("change", function () {
            currentSort = this.value;
            renderDestinations();
        });

        // Mulai Panggil API Wikipedia
        fetchWikipediaImages();
    });
    </script>

    @include('landing-page.halamanbawah')
    @include('landing-page.footer')