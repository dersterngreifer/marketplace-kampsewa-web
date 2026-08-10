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

        <!-- Cinematic Sewa Barang Hero Section -->
        <section class="hero-sewabarang-modern">
            <!-- Background Slider -->
            <div class="hero-bg-carousel">
                <div id="heroSewaBarangCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('template/envato/img/home-5.jpg') }}" alt="Perlengkapan Camping">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('template/envato/img/barang-tenda.jpg') }}" alt="Tenda Camping">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('template/envato/img/barang-carrier.jpg') }}" alt="Tas Carrier">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('template/envato/img/barang-hiking.jpg') }}" alt="Hiking Gear">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gradient Overlay -->
            <div class="hero-overlay-gradient"></div>

            <!-- Hero Content -->
            <div class="container sewabarang-hero-content">
                <div class="badge-hero-pill">
                    <i class="fas fa-campground text-warning"></i>
                    <span>#1 Marketplace Rental Perlengkapan Outdoor & Camping Indonesia</span>
                </div>

                <h1 class="about-hero-title">
                    Sewa Perlengkapan <br>
                    <span class="text-white">Kualitas Terjamin & Steril</span>
                </h1>

                <p class="about-hero-desc">
                    Lengkapi petualangan alam bebasmu dengan ribuan peralatan camping dari toko vendor terverifikasi. Hemat biaya, praktis, siap pakai, dan terjamin kebersihannya!
                </p>

                <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                    <a href="#katalog-alat" class="btn-modern-amber">
                        <i class="fas fa-search"></i>
                        <span>Cari & Sewa Alat Sekarang</span>
                    </a>
                    <a href="#vendor-spotlight" class="btn-outline-white">
                        <i class="fas fa-store"></i>
                        <span>Mitra Toko Vendor</span>
                    </a>
                </div>
            </div>
        </section>
    </div>
    <!-- Navbar & Hero End -->

    <!-- Vendor Spotlight Section Start -->
    <section id="vendor-spotlight" class="vendor-spotlight-section">
        <div class="container">
            <div class="vendor-spotlight-header">
                <span class="badge bg-primary text-white rounded-pill px-3 py-2 mb-2 font-display fw-bold"><i class="fas fa-award text-warning me-1"></i> MITRA TERVERIFIKASI</span>
                <h2 class="display-6 fw-bolder mb-2">Daftar Toko Vendor Populer</h2>
                <p class="text-muted max-w-2xl mx-auto">Pilih dan filter perlengkapan dari toko rental resmi partner KampSewa dengan reputasi terbaik dan jaminan kualitas alat.</p>
            </div>

            <div class="vendor-grid-container" id="vendorGridContainer">
                <!-- Vendor Cards injected via JS -->
            </div>
        </div>
    </section>
    <!-- Vendor Spotlight Section End -->

    <!-- Catalog Filter & Grid Section Start -->
    <section id="katalog-alat" class="py-5" style="background: var(--ks-gray-50);">
        <div class="container">
            <!-- Filter Bar Card -->
            <div class="gear-filter-card">
                <!-- Search Box -->
                <div class="dest-search-box mb-4">
                    <i class="fas fa-search dest-search-icon"></i>
                    <input type="text" id="gearSearchInput" class="dest-search-input" placeholder="Cari nama alat, tenda, carrier, sleeping bag, atau toko vendor (contoh: Eiger, Tenda Dome, Consina)...">
                    <button id="clearGearSearchBtn" class="dest-search-clear" title="Hapus pencarian"><i class="fas fa-times"></i></button>
                </div>

                <!-- Vendor Filter Pills -->
                <span class="dest-filter-label"><i class="fas fa-store text-primary me-2"></i> Filter Toko Vendor:</span>
                <div class="dest-region-tabs mb-4" id="gearVendorFilterContainer">
                    <button class="dest-region-btn active" data-vendor="all">
                        <i class="fas fa-globe"></i> <span>Semua Toko (8 Vendor Mitra)</span>
                    </button>
                    <button class="dest-region-btn" data-vendor="Eiger Adventure Rental">
                        <i class="fas fa-store-alt"></i> <span>Eiger Rental</span>
                    </button>
                    <button class="dest-region-btn" data-vendor="Consina Outdoor Service">
                        <i class="fas fa-store-alt"></i> <span>Consina Outdoor</span>
                    </button>
                    <button class="dest-region-btn" data-vendor="Arei Outdoorgear">
                        <i class="fas fa-store-alt"></i> <span>Arei Outdoorgear</span>
                    </button>
                    <button class="dest-region-btn" data-vendor="The North Face Club">
                        <i class="fas fa-store-alt"></i> <span>The North Face</span>
                    </button>
                    <button class="dest-region-btn" data-vendor="Deuter Rental Center">
                        <i class="fas fa-store-alt"></i> <span>Deuter Center</span>
                    </button>
                    <button class="dest-region-btn" data-vendor="Avtech Adventure Store">
                        <i class="fas fa-store-alt"></i> <span>Avtech Adventure</span>
                    </button>
                    <button class="dest-region-btn" data-vendor="Jack Wolfskin Camp">
                        <i class="fas fa-store-alt"></i> <span>Jack Wolfskin</span>
                    </button>
                    <button class="dest-region-btn" data-vendor="Decathlon Quechua Rental">
                        <i class="fas fa-store-alt"></i> <span>Decathlon Quechua</span>
                    </button>
                </div>

                <!-- Category Chips -->
                <span class="dest-filter-label"><i class="fas fa-tags text-warning me-2"></i> Kategori Perlengkapan:</span>
                <div class="dest-category-chips" id="gearCategoryFilterContainer">
                    <button class="dest-category-btn active" data-category="all">
                        <span>Semua Kategori</span>
                    </button>
                    <button class="dest-category-btn" data-category="Tenda & Shelter">
                        <span>⛺ Tenda & Shelter</span>
                    </button>
                    <button class="dest-category-btn" data-category="Tas Carrier & Rucksack">
                        <span>🎒 Tas Carrier & Rucksack</span>
                    </button>
                    <button class="dest-category-btn" data-category="Tidur & Sleeping Bag">
                        <span>🛌 Tidur & Sleeping Bag</span>
                    </button>
                    <button class="dest-category-btn" data-category="Masak & Dapur Outdoor">
                        <span>🍳 Masak & Dapur Outdoor</span>
                    </button>
                    <button class="dest-category-btn" data-category="Penerangan & Senter">
                        <span>💡 Penerangan & Senter</span>
                    </button>
                    <button class="dest-category-btn" data-category="Trekking & Aksesoris">
                        <span>🥾 Trekking & Aksesoris</span>
                    </button>
                </div>

                <!-- Results Status & Sorting -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top gap-2">
                    <div id="gearResultsStatus" class="fw-bold text-dark font-display" style="font-size: 1.1rem;">
                        <i class="fas fa-boxes text-primary me-2"></i> Menampilkan <span id="gearCountDisplay" class="text-primary fw-bolder">0</span> alat camping siap sewa
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted text-sm fw-bold">Urutkan:</span>
                        <select id="gearSortSelect" class="form-select form-select-sm border-2 fw-bold" style="width: auto; border-radius: 10px;">
                            <option value="popular">🔥 Paling Populer & Sering Disewa</option>
                            <option value="rating-desc">⭐ Vendor Rating Tertinggi</option>
                            <option value="price-asc">💵 Harga Termurah</option>
                            <option value="price-desc">💵 Harga Termahal</option>
                            <option value="rent-desc">🔄 Sering Disewa (Total Rental)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Gear Grid Container -->
            <div id="gearGridContainer" class="gear-grid-container">
                <!-- Dynamic Gear Cards Injected via JS -->
            </div>
        </div>
    </section>
    <!-- Catalog Filter & Grid Section End -->

    <!-- Premium Compact Modal Detail Spesifikasi Alat (Mewah & Proporional) -->
    <div class="modal fade" id="gearDetailModal" tabindex="-1" aria-labelledby="gearDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content gear-modal-content">
                <!-- Circular Floating Close Button -->
                <button type="button" class="gear-modal-close-btn" data-bs-dismiss="modal" aria-label="Close" title="Tutup Etalase"><i class="fas fa-times"></i></button>
                <!-- Body -->
                <div class="modal-body p-0" id="gearDetailModalBody">
                    <!-- Dynamic modal content -->
                </div>
            </div>
        </div>
    </div>

    <!-- App Not Available Animated Popup Modal (Persis seperti tombol Google Play di Home) -->
    <div class="modal fade" id="rentAppModal" tabindex="-1" aria-labelledby="rentAppModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content app-modal-content">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <div class="app-modal-icon-wrapper">
                    <i class="fas fa-cloud-download-alt"></i>
                </div>
                
                <span class="app-modal-badge"><i class="fas fa-exclamation-circle me-1"></i> Wajib Menggunakan Aplikasi Mobile</span>
                
                <h3 class="fw-bold text-dark mt-2 mb-3" id="rentAppModalLabel">Aplikasi Belum Tersedia</h3>
                
                <p class="text-muted mb-4 px-2" style="font-size: 0.95rem; line-height: 1.6;">
                    Untuk melakukan transaksi penyewaan perlengkapan <strong id="rent-item-display" class="text-primary">Camping Gear</strong> dari toko mitra <strong id="rent-vendor-display" class="text-dark">KampSewa</strong>, Anda harus menggunakan dan mendownload aplikasi mobile resmi kami!
                    <br><br>
                    Saat ini aplikasi mobile KampSewa untuk <span class="fw-bold text-primary">Google Play & App Store</span> sedang dalam tahap akhir verifikasi. Silakan download aplikasi atau klik tombol di bawah untuk informasi terbaru:
                </p>
                
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <a href="#katalog-alat" data-bs-dismiss="modal" class="btn-nav-download d-inline-flex align-items-center justify-content-center px-5 py-3 mb-3 shadow" style="font-size: 1.05rem !important; width: 100%; max-width: 280px; text-decoration: none;">
                        <i class="fas fa-cloud-download-alt fa-lg"></i>
                        <span>Download Mobile App</span>
                    </a>
                    <button type="button" class="btn btn-link text-muted text-decoration-none btn-sm mt-1" data-bs-dismiss="modal">Kembali ke Eksplorasi Alat</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Vendor Showcase, Filter, Pencarian & Render Alat -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Dataset Toko Vendor Mitra
        const vendorsList = [
            {
                id: "Eiger Adventure Rental",
                name: "Eiger Adventure Rental",
                city: "Bandung & Jakarta",
                rating: 4.9,
                skuCount: 150,
                badge: "🔥 Top Populer",
                logo: "{{ asset('template/envato/img/toko-eiger.jpeg') }}"
            },
            {
                id: "Consina Outdoor Service",
                name: "Consina Outdoor Service",
                city: "Surabaya & Malang",
                rating: 4.8,
                skuCount: 120,
                badge: "⚡ Sering Disewa",
                logo: "{{ asset('template/envato/img/toko-consina.png') }}"
            },
            {
                id: "Arei Outdoorgear",
                name: "Arei Outdoorgear",
                city: "Yogyakarta & Solo",
                rating: 4.8,
                skuCount: 90,
                badge: "⭐ Paling Diminati",
                logo: "{{ asset('template/envato/img/toko-rei.jpg') }}"
            },
            {
                id: "The North Face Club",
                name: "The North Face Club",
                city: "Denpasar & Bali",
                rating: 5.0,
                skuCount: 80,
                badge: "🏆 Premium Quality",
                logo: "{{ asset('template/envato/img/toko-tnf.jpg') }}"
            },
            {
                id: "Deuter Rental Center",
                name: "Deuter Rental Center",
                city: "Semarang & Magelang",
                rating: 4.9,
                skuCount: 75,
                badge: "🎒 Spesialis Carrier",
                logo: "{{ asset('template/envato/img/toko-deuter.webp') }}"
            },
            {
                id: "Avtech Adventure Store",
                name: "Avtech Adventure Store",
                city: "Bogor & Depok",
                rating: 4.7,
                skuCount: 110,
                badge: "💵 Harga Sahabat",
                logo: "{{ asset('template/envato/img/toko-avtech.jpg') }}"
            },
            {
                id: "Jack Wolfskin Camp",
                name: "Jack Wolfskin Camp",
                city: "Malang & Batu",
                rating: 4.9,
                skuCount: 65,
                badge: "❄️ Spesialis Thermal",
                logo: "{{ asset('template/envato/img/toko-jack.jpg') }}"
            },
            {
                id: "Decathlon Quechua Rental",
                name: "Decathlon Quechua Rental",
                city: "Jakarta & Tangerang",
                rating: 4.8,
                skuCount: 200,
                badge: "⛺ Glamping Spesialis",
                logo: "{{ asset('template/envato/img/toko-deca.jpg') }}"
            }
        ];

        // 2. Daftar gambar lokal terverifikasi (100% dijamin ada di folder template/envato/img tanpa gambar rusak)
        const validLocalImages = [
            "{{ asset('template/envato/img/barang-tenda.jpg') }}",
            "{{ asset('template/envato/img/barang-sleeping-bag.jpg') }}",
            "{{ asset('template/envato/img/barang-hiking.jpg') }}",
            "{{ asset('template/envato/img/barang-kompor-portable.jpeg') }}",
            "{{ asset('template/envato/img/barang-matras.jpg') }}",
            "{{ asset('template/envato/img/barang-headlamp.webp') }}",
            "{{ asset('template/envato/img/barang-hammock.jpg') }}",
            "{{ asset('template/envato/img/barang-trekking-pole.jpg') }}",
            "{{ asset('template/envato/img/barang-flysheet.jpg') }}",
            "{{ asset('template/envato/img/barang-cooler-box.webp') }}",
            "{{ asset('template/envato/img/barang-makan.webp') }}",
            "{{ asset('template/envato/img/barang-portable.jpeg') }}",
            "{{ asset('template/envato/img/barang-set.webp') }}",
            "{{ asset('template/envato/img/barang-terpal.webp') }}",
            "{{ asset('template/envato/img/barang-Gaiters.jpg') }}",
            "{{ asset('template/envato/img/barang-Selimut-thermal.jpeg') }}",
            "{{ asset('template/envato/img/barang-bantal.jpg') }}",
            "{{ asset('template/envato/img/barang-gps.jpeg') }}"
        ];

        // 3. Dataset Lengkap Perlengkapan Camping (Diambil dari products.json di public atau Fallback Lokal)
        let gearList = [];

        // Fungsi penentu kategori berdasarkan nama/deskripsi
        function detectCategory(text) {
            const t = text.toLowerCase();
            if (t.includes("tenda") || t.includes("kemah") || t.includes("shelter") || t.includes("arpenaz") || t.includes("mh100") || t.includes("terpal") || t.includes("flysheet")) {
                return "Tenda & Shelter";
            }
            if (t.includes("tas") || t.includes("carrier") || t.includes("ransel") || t.includes("backpack") || t.includes("rucksack") || t.includes("daypack")) {
                return "Tas Carrier & Rucksack";
            }
            if (t.includes("tidur") || t.includes("sleeping") || t.includes("matras") || t.includes("kasur") || t.includes("bantal") || t.includes("selimut") || t.includes("thermal") || t.includes("hammock")) {
                return "Tidur & Sleeping Bag";
            }
            if (t.includes("masak") || t.includes("kompor") || t.includes("panci") || t.includes("makan") || t.includes("cooler") || t.includes("botol") || t.includes("nesting") || t.includes("portable")) {
                return "Masak & Dapur Outdoor";
            }
            if (t.includes("lampu") || t.includes("headlamp") || t.includes("senter") || t.includes("led") || t.includes("cahaya")) {
                return "Penerangan & Senter";
            }
            return "Trekking & Aksesoris";
        }

        // Fungsi generator harga sewa harian yang realistis
        function getRentalPrice(index) {
            const prices = [15000, 20000, 25000, 30000, 35000, 40000, 45000, 50000, 60000, 75000];
            return prices[index % prices.length];
        }

        // Fungsi memuat data dari products.json di direktori public
        function loadProductsData() {
            fetch("{{ asset('products.json') }}")
                .then(response => {
                    if (!response.ok) throw new Error("Gagal load products.json");
                    return response.json();
                })
                .then(data => {
                    if (Array.isArray(data) && data.length > 0) {
                        // Ambil 32 produk pertama agar katalog kaya & variatif
                        gearList = data.slice(0, 32).map((item, idx) => {
                            const vendorObj = vendorsList[idx % vendorsList.length];
                            const cat = detectCategory(item.name + " " + (item.description || ""));
                            const localImg = validLocalImages[idx % validLocalImages.length];
                            return {
                                id: idx + 1,
                                name: item.name,
                                vendor: vendorObj.name,
                                vendorCity: vendorObj.city,
                                category: cat,
                                price: getRentalPrice(idx),
                                rating: parseFloat(item.rating) || 4.8,
                                rentCount: parseInt(item.review_count) || Math.floor(Math.random() * 400) + 120,
                                popular: idx < 12,
                                img: item.listing_photo || localImg,
                                fallbackImg: localImg,
                                specs: item.description ? item.description.replace(/[\r\n]+/g, " | ").substring(0, 200) + "..." : "Perlengkapan outdoor berkualitas tinggi bersertifikasi resmi, terjamin bersih, harum, dan steril siap pakai."
                            };
                        });
                        renderGearCatalog();
                    } else {
                        useFallbackGearList();
                    }
                })
                .catch(err => {
                    console.warn("Menggunakan data fallback lokal terverifikasi:", err);
                    useFallbackGearList();
                });
        }

        // Dataset Fallback (Semua item dijamin 100% menggunakan foto lokal yang benar-benar ada)
        function useFallbackGearList() {
            gearList = [
                {
                    id: 1,
                    name: "Tenda Dome 4 Orang Double Layer",
                    vendor: "Eiger Adventure Rental",
                    vendorCity: "Bandung & Jakarta",
                    category: "Tenda & Shelter",
                    price: 45000,
                    rating: 4.9,
                    rentCount: 320,
                    popular: true,
                    img: validLocalImages[0],
                    fallbackImg: validLocalImages[0],
                    specs: "Kapasitas 4 Orang | Double Layer 100% Waterproof | Frame Alumunium Alloy | Anti Badai & Embun"
                },
                {
                    id: 2,
                    name: "Carrier Aircontact 60L + Raincover",
                    vendor: "Deuter Rental Center",
                    vendorCity: "Semarang & Magelang",
                    category: "Tas Carrier & Rucksack",
                    price: 40000,
                    rating: 4.9,
                    rentCount: 410,
                    popular: true,
                    img: validLocalImages[2],
                    fallbackImg: validLocalImages[2],
                    specs: "Kapasitas 60 Liter | Sistem Aircontact Backsystem Nyaman | Bonus Raincover Asli | Bahan Cordura Tahan Sobek"
                },
                {
                    id: 3,
                    name: "Sleeping Bag Polar Thermal Extream",
                    vendor: "Consina Outdoor Service",
                    vendorCity: "Surabaya & Malang",
                    category: "Tidur & Sleeping Bag",
                    price: 20000,
                    rating: 4.8,
                    rentCount: 550,
                    popular: true,
                    img: validLocalImages[1],
                    fallbackImg: validLocalImages[1],
                    specs: "Suhu Nyaman hingga 5°C | Bahan Dalam Polar Fleece Lembut | Ringan & Mudah Digulung | Sudah Dilaundry Steril"
                },
                {
                    id: 4,
                    name: "Kompor Portable Gas Mini Ultralight",
                    vendor: "Arei Outdoorgear",
                    vendorCity: "Yogyakarta & Solo",
                    category: "Masak & Dapur Outdoor",
                    price: 15000,
                    rating: 4.8,
                    rentCount: 620,
                    popular: true,
                    img: validLocalImages[3],
                    fallbackImg: validLocalImages[3],
                    specs: "Desain Kotak Lipat | Pemantik Api Piezo Otomatis | Material Stainless Steel Anti Karat | Kompatibel Gas Kaleng"
                },
                {
                    id: 5,
                    name: "Tenda Glamping Arpenaz Family 4.1",
                    vendor: "Decathlon Quechua Rental",
                    vendorCity: "Jakarta & Tangerang",
                    category: "Tenda & Shelter",
                    price: 75000,
                    rating: 5.0,
                    rentCount: 180,
                    popular: true,
                    img: validLocalImages[0],
                    fallbackImg: validLocalImages[0],
                    specs: "1 Kamar Tidur Besar + 1 Ruang Tamu Berdiri | Teknologi Fresh & Black Tahan Panas | Anti Bocor Teruji Angin Kencang"
                },
                {
                    id: 6,
                    name: "Matras Camping Aluminium Foil 2mm",
                    vendor: "Avtech Adventure Store",
                    vendorCity: "Bogor & Depok",
                    category: "Tidur & Sleeping Bag",
                    price: 15000,
                    rating: 4.7,
                    rentCount: 740,
                    popular: false,
                    img: validLocalImages[4],
                    fallbackImg: validLocalImages[4],
                    specs: "Ukuran 180 x 60 cm | Ketebalan 2mm Aluminium Foil | Menahan Dingin Tanah & Kelembaban | Sangat Ringan"
                },
                {
                    id: 7,
                    name: "Headlamp LED Waterproof Rechargeable",
                    vendor: "The North Face Club",
                    vendorCity: "Denpasar & Bali",
                    category: "Penerangan & Senter",
                    price: 25000,
                    rating: 5.0,
                    rentCount: 290,
                    popular: true,
                    img: validLocalImages[5],
                    fallbackImg: validLocalImages[5],
                    specs: "Kekuatan Cahaya 350 Lumens | Baterai Rechargeable USB-C | Sensor Tangan Sensor Gerak | Tahan Air Hujan IPX6"
                },
                {
                    id: 8,
                    name: "Hammock Single Parasut + Webbing",
                    vendor: "Jack Wolfskin Camp",
                    vendorCity: "Malang & Batu",
                    category: "Tidur & Sleeping Bag",
                    price: 15000,
                    rating: 4.8,
                    rentCount: 310,
                    popular: false,
                    img: validLocalImages[6],
                    fallbackImg: validLocalImages[6],
                    specs: "Kapasitas Beban Maksimal 150 kg | Bahan Parasut Silk Kuat | Dilengkapi 2 Webbing Tubular & Karabiner"
                },
                {
                    id: 9,
                    name: "Trekking Pole Antishock Duralumin",
                    vendor: "Eiger Adventure Rental",
                    vendorCity: "Bandung & Jakarta",
                    category: "Trekking & Aksesoris",
                    price: 20000,
                    rating: 4.9,
                    rentCount: 380,
                    popular: true,
                    img: validLocalImages[7],
                    fallbackImg: validLocalImages[7],
                    specs: "Sistem Antishock Meredam Getaran | Material Duralumin 6061 | Panjang Fleksibel 65-135 cm | Handle Ergonomis"
                },
                {
                    id: 10,
                    name: "Flysheet Waterproof 3x3 Meter",
                    vendor: "Consina Outdoor Service",
                    vendorCity: "Surabaya & Malang",
                    category: "Tenda & Shelter",
                    price: 25000,
                    rating: 4.8,
                    rentCount: 430,
                    popular: true,
                    img: validLocalImages[8],
                    fallbackImg: validLocalImages[8],
                    specs: "Bahan Polyester PU 2500mm 100% Anti Bocor | 19 Lubang Pengait Jahitan Bar-tack | Sangat Cocok untuk Shelter Masak"
                },
                {
                    id: 11,
                    name: "Cooler Box Insulated 24 Liter",
                    vendor: "Decathlon Quechua Rental",
                    vendorCity: "Jakarta & Tangerang",
                    category: "Masak & Dapur Outdoor",
                    price: 50000,
                    rating: 4.9,
                    rentCount: 150,
                    popular: false,
                    img: validLocalImages[9],
                    fallbackImg: validLocalImages[9],
                    specs: "Menjaga Suhu Dingin hingga 14 Jam Tanpa Es Batu | Kapasitas 24 Liter | Material Food Grade yang Mudah Dibersihkan"
                },
                {
                    id: 12,
                    name: "Cooking Set Nesting Aluminium 4 in 1",
                    vendor: "Arei Outdoorgear",
                    vendorCity: "Yogyakarta & Solo",
                    category: "Masak & Dapur Outdoor",
                    price: 25000,
                    rating: 4.8,
                    rentCount: 490,
                    popular: true,
                    img: validLocalImages[12],
                    fallbackImg: validLocalImages[12],
                    specs: "Terdiri dari 2 Panci + 2 Wajan Lipat | Bahan Anodized Aluminium Anti Lengket | Termasuk Spons Pembersih & Kantong"
                },
                {
                    id: 13,
                    name: "Alat Makan Outdoor Stainless Set",
                    vendor: "The North Face Club",
                    vendorCity: "Denpasar & Bali",
                    category: "Masak & Dapur Outdoor",
                    price: 15000,
                    rating: 5.0,
                    rentCount: 220,
                    popular: true,
                    img: validLocalImages[10],
                    fallbackImg: validLocalImages[10],
                    specs: "Set Sendok Garpu Pisau Stainless Food Grade | Praktis Dilengkapi Case Penyimpanan | Dijamin 100% Bersih & Steril"
                },
                {
                    id: 14,
                    name: "Kompor Portable Lipat Camping",
                    vendor: "Jack Wolfskin Camp",
                    vendorCity: "Malang & Batu",
                    category: "Masak & Dapur Outdoor",
                    price: 20000,
                    rating: 4.9,
                    rentCount: 340,
                    popular: true,
                    img: validLocalImages[11],
                    fallbackImg: validLocalImages[11],
                    specs: "Desain Lipat Ringan dan Praktis | Api Biru Stabil Tahan Angin | Kompatibel Gas Kaleng Butane"
                },
                {
                    id: 15,
                    name: "Terpal Shelter Waterproof Tebal",
                    vendor: "Avtech Adventure Store",
                    vendorCity: "Bogor & Depok",
                    category: "Tenda & Shelter",
                    price: 25000,
                    rating: 4.7,
                    rentCount: 270,
                    popular: false,
                    img: validLocalImages[13],
                    fallbackImg: validLocalImages[13],
                    specs: "Material Terpal Waterproof Tebal Tahan Sobek | Dilengkapi Lubang Ring Besi | Sangat Cocok untuk Alas atau Atap"
                },
                {
                    id: 16,
                    name: "Gaiters Pelindung Kaki Waterproof",
                    vendor: "Deuter Rental Center",
                    vendorCity: "Semarang & Magelang",
                    category: "Trekking & Aksesoris",
                    price: 15000,
                    rating: 4.8,
                    rentCount: 110,
                    popular: false,
                    img: validLocalImages[14],
                    fallbackImg: validLocalImages[14],
                    specs: "Melindungi Kaki dari Lumpur, Pacet, dan Kerikil | Bahan Ripstop Waterproof | Tali Strap Bawah Super Kuat"
                },
                {
                    id: 17,
                    name: "Selimut Thermal Emergency Rescue",
                    vendor: "Eiger Adventure Rental",
                    vendorCity: "Bandung & Jakarta",
                    category: "Tidur & Sleeping Bag",
                    price: 15000,
                    rating: 4.9,
                    rentCount: 360,
                    popular: true,
                    img: validLocalImages[15],
                    fallbackImg: validLocalImages[15],
                    specs: "Memantulkan 90% Panas Tubuh Mencegah Hipotermia | Ukuran 160x210 cm | Sangat Ringan dan Wajib Dipakai saat Darurat"
                },
                {
                    id: 18,
                    name: "Bantal Tiup Inflatable Camping",
                    vendor: "Consina Outdoor Service",
                    vendorCity: "Surabaya & Malang",
                    category: "Tidur & Sleeping Bag",
                    price: 15000,
                    rating: 4.8,
                    rentCount: 520,
                    popular: true,
                    img: validLocalImages[16],
                    fallbackImg: validLocalImages[16],
                    specs: "Mudah Ditiup dan Dikempeskan | Lapisan Beludru Lembut di Bagian Atas | Tidur Nyaman Tanpa Pegal Leher di Alam"
                },
                {
                    id: 19,
                    name: "GPS Navigator Handheld Outdoor",
                    vendor: "Decathlon Quechua Rental",
                    vendorCity: "Jakarta & Tangerang",
                    category: "Trekking & Aksesoris",
                    price: 50000,
                    rating: 4.9,
                    rentCount: 460,
                    popular: true,
                    img: validLocalImages[17],
                    fallbackImg: validLocalImages[17],
                    specs: "Akurasi Posisi Satelit Tinggi | Layar Warna Mudah Dibaca di Bawah Matahari | Tahan Banting dan Anti Air IPX7"
                }
            ];
            renderGearCatalog();
        }

        let currentVendor = "all";
        let currentCategory = "all";
        let currentSearch = "";
        let currentSort = "popular";

        // Referensi Elemen
        const vendorGrid = document.getElementById("vendorGridContainer");
        const gearGrid = document.getElementById("gearGridContainer");
        const countDisplay = document.getElementById("gearCountDisplay");
        const searchInput = document.getElementById("gearSearchInput");
        const clearBtn = document.getElementById("clearGearSearchBtn");
        const sortSelect = document.getElementById("gearSortSelect");

        // Fungsi Global trigger App Modal (Muncul saat klik Sewa Sekarang)
        window.showRentAppModal = function(itemName, vendorName) {
            document.getElementById("rent-item-display").textContent = itemName;
            document.getElementById("rent-vendor-display").textContent = vendorName;
            const modalEl = new bootstrap.Modal(document.getElementById('rentAppModal'));
            modalEl.show();
        };

        window.triggerAppModalFromDetail = function(itemName, vendorName) {
            // Tutup dulu modal detail, lalu buka modal aplikasi
            const detailEl = bootstrap.Modal.getInstance(document.getElementById('gearDetailModal'));
            if (detailEl) {
                detailEl.hide();
            }
            setTimeout(() => {
                showRentAppModal(itemName, vendorName);
            }, 300);
        };

        // 3. Render Vendor Spotlight Showcase
        function renderVendors() {
            vendorGrid.innerHTML = "";
            vendorsList.forEach(v => {
                const card = `
                    <div class="vendor-store-card">
                        <div class="vendor-card-img-wrapper">
                            <img src="${v.logo}" class="vendor-card-img" alt="${v.name}">
                            <span class="vendor-card-badge">${v.badge}</span>
                        </div>
                        <div class="vendor-card-body">
                            <h3 class="vendor-card-title">${v.name}</h3>
                            <div class="vendor-card-location">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                <span>${v.city}</span>
                            </div>
                            <div class="vendor-card-stats">
                                <div>⭐ ${v.rating}/5.0</div>
                                <div>📦 ${v.skuCount}+ Alat Ready</div>
                            </div>
                            <button class="btn-filter-vendor mt-auto" data-vendor-target="${v.name}">
                                <i class="fas fa-filter"></i> <span>Lihat Alat Vendor Ini</span>
                            </button>
                        </div>
                    </div>
                `;
                vendorGrid.insertAdjacentHTML("beforeend", card);
            });

            // Event Listener Klik Tombol "Lihat Alat Vendor Ini"
            document.querySelectorAll(".btn-filter-vendor").forEach(btn => {
                btn.addEventListener("click", function () {
                    const targetVendor = this.getAttribute("data-vendor-target");
                    currentVendor = targetVendor;
                    
                    // Update tab filter status
                    document.querySelectorAll("#gearVendorFilterContainer .dest-region-btn").forEach(b => {
                        b.classList.remove("active");
                        if (b.getAttribute("data-vendor") === targetVendor) {
                            b.classList.add("active");
                        }
                    });

                    // Scroll ke katalog alat
                    document.getElementById("katalog-alat").scrollIntoView({ behavior: "smooth" });
                    renderGearCatalog();
                });
            });
        }

        // 4. Render Katalog Alat Camping dengan Filtering & Sorting
        function renderGearCatalog() {
            let filtered = gearList.filter(item => {
                // Filter Toko Vendor
                if (currentVendor !== "all" && item.vendor !== currentVendor) {
                    return false;
                }
                // Filter Kategori
                if (currentCategory !== "all" && item.category !== currentCategory) {
                    return false;
                }
                // Filter Kata Kunci Search
                if (currentSearch.trim() !== "") {
                    const q = currentSearch.toLowerCase();
                    const matchName = item.name.toLowerCase().includes(q);
                    const matchVendor = item.vendor.toLowerCase().includes(q);
                    const matchCity = item.vendorCity.toLowerCase().includes(q);
                    const matchCat = item.category.toLowerCase().includes(q);
                    const matchSpecs = item.specs.toLowerCase().includes(q);
                    if (!matchName && !matchVendor && !matchCity && !matchCat && !matchSpecs) {
                        return false;
                    }
                }
                return true;
            });

            // Sorting
            if (currentSort === "popular") {
                filtered.sort((a, b) => (b.popular === a.popular) ? b.rentCount - a.rentCount : (b.popular ? 1 : -1));
            } else if (currentSort === "rating-desc") {
                filtered.sort((a, b) => b.rating - a.rating);
            } else if (currentSort === "price-asc") {
                filtered.sort((a, b) => a.price - b.price);
            } else if (currentSort === "price-desc") {
                filtered.sort((a, b) => b.price - a.price);
            } else if (currentSort === "rent-desc") {
                filtered.sort((a, b) => b.rentCount - a.rentCount);
            }

            countDisplay.textContent = filtered.length;
            gearGrid.innerHTML = "";

            if (filtered.length === 0) {
                gearGrid.innerHTML = `
                    <div class="dest-empty-state">
                        <i class="fas fa-box-open"></i>
                        <h3>Perlengkapan Tidak Ditemukan</h3>
                        <p>Tidak ada alat camping yang sesuai dengan filter toko vendor atau kata kunci pencarian Anda saat ini. Coba reset filter atau pilih kategori lain.</p>
                        <button id="resetGearFiltersBtn" class="btn-modern-amber mx-auto"><i class="fas fa-sync-alt"></i> <span>Reset Semua Filter</span></button>
                    </div>
                `;
                const resetBtn = document.getElementById("resetGearFiltersBtn");
                if (resetBtn) {
                    resetBtn.addEventListener("click", () => {
                        currentVendor = "all";
                        currentCategory = "all";
                        currentSearch = "";
                        searchInput.value = "";
                        clearBtn.style.display = "none";
                        document.querySelectorAll("#gearVendorFilterContainer .dest-region-btn").forEach(b => b.classList.remove("active"));
                        document.querySelector('#gearVendorFilterContainer .dest-region-btn[data-vendor="all"]').classList.add("active");
                        document.querySelectorAll("#gearCategoryFilterContainer .dest-category-btn").forEach(b => b.classList.remove("active"));
                        document.querySelector('#gearCategoryFilterContainer .dest-category-btn[data-category="all"]').classList.add("active");
                        renderGearCatalog();
                    });
                }
                return;
            }

            filtered.forEach(item => {
                const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(item.price);
                const fallbackUrl = item.fallbackImg || validLocalImages[0];
                const card = `
                    <div class="gear-card-modern">
                        <div class="gear-card-img-wrapper">
                            <img src="${item.img}" class="gear-card-img" alt="${item.name}" onerror="this.onerror=null; this.src='${fallbackUrl}';">
                            <div class="gear-card-badges">
                                <span class="gear-badge-status"><i class="fas fa-check-circle"></i> Ready & Steril</span>
                                <span class="gear-badge-cat">${item.category}</span>
                            </div>
                        </div>
                        <div class="gear-card-body">
                            <span class="vendor-tag-card"><i class="fas fa-store"></i> ${item.vendor} (${item.vendorCity})</span>
                            <h3 class="gear-card-title">${item.name}</h3>
                            <p class="gear-card-specs"><i class="fas fa-info-circle text-primary me-1"></i> ${item.specs}</p>
                            <div class="gear-price-box">
                                <div class="gear-price">${formattedPrice} <span>/ hari</span></div>
                                <div class="gear-stats"><i class="fas fa-star text-warning"></i> ${item.rating} (${item.rentCount}x)</div>
                            </div>
                        </div>
                        <div class="gear-card-footer">
                            <button type="button" class="btn-rent-now" onclick="showRentAppModal('${item.name.replace(/'/g, "\\'")}', '${item.vendor}')">
                                <i class="fas fa-mobile-alt"></i> <span>Sewa Sekarang</span>
                            </button>
                            <button class="btn-detail-gear" data-gear-id="${item.id}" title="Detail Spesifikasi Alat">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                `;
                gearGrid.insertAdjacentHTML("beforeend", card);
            });

            // Event Listener Klik Tombol Detail Alat
            document.querySelectorAll(".btn-detail-gear").forEach(btn => {
                btn.addEventListener("click", function () {
                    const id = parseInt(this.getAttribute("data-gear-id"));
                    const item = gearList.find(g => g.id === id);
                    if (item) {
                        showGearModal(item);
                    }
                });
            });
        }

        // 5. Modal Detail Spesifikasi Alat (Compact, Mewah, Tanpa tombol tutup & Tanpa WhatsApp)
        function showGearModal(item) {
            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(item.price);
            const fallbackUrl = item.fallbackImg || validLocalImages[0];
            
            // Pecah spesifikasi menjadi array pill
            const specArray = item.specs.split("|").map(s => s.trim()).filter(s => s.length > 0);
            let specPillsHtml = "";
            specArray.forEach(s => {
                specPillsHtml += `<div class="spec-pill-compact"><i class="fas fa-check text-success"></i> <span>${s}</span></div>`;
            });
            if (specPillsHtml === "") {
                specPillsHtml = `<div class="spec-pill-compact"><i class="fas fa-check text-success"></i> <span>${item.specs}</span></div>`;
            }

            const modalBody = document.getElementById("gearDetailModalBody");
            modalBody.innerHTML = `
                <div class="row g-4 align-items-center">
                    <!-- Left Column: Product Photo & Vendor Info -->
                    <div class="col-md-5">
                        <div class="gear-modal-img-box">
                            <img src="${item.img}" class="gear-modal-img" alt="${item.name}" onerror="this.onerror=null; this.src='${fallbackUrl}';">
                            <span class="badge bg-success text-white position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-check-circle me-1"></i> Ready & Steril</span>
                        </div>
                        <div class="p-3 bg-light rounded-4 mt-3 border d-flex align-items-center gap-3">
                            <div class="bg-white p-2 rounded-circle shadow-sm text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="fas fa-store fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">${item.vendor}</h6>
                                <span class="text-muted text-xs"><i class="fas fa-map-marker-alt text-danger me-1"></i> Kota ${item.vendorCity}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Title, Pricing, Specs & CTA -->
                    <div class="col-md-7 ps-md-4">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="gear-detail-badge-rating"><i class="fas fa-star text-warning"></i> ${item.rating} / 5.0 Rating</span>
                            <span class="gear-detail-badge-rent"><i class="fas fa-shopping-bag"></i> ${item.rentCount}x Disewa</span>
                        </div>
                        
                        <h3 class="fw-bolder text-dark mb-3 font-display" style="font-size: 1.45rem; line-height: 1.3;">${item.name}</h3>
                        
                        <div class="mb-3 pb-3 border-bottom d-flex align-items-baseline justify-content-between">
                            <div>
                                <span class="text-muted text-xs fw-bold uppercase tracking-wider d-block mb-1">Tarif Sewa Harian Resmi:</span>
                                <div class="text-primary fw-bolder font-display" style="font-size: 1.85rem;">${formattedPrice} <span class="text-muted fw-normal" style="font-size: 0.9rem;">/ 24 Jam</span></div>
                            </div>
                            <span class="gear-detail-badge-stock"><i class="fas fa-check-circle"></i> Stok Tersedia</span>
                        </div>
                        
                        <div class="mb-4">
                            <span class="text-dark fw-bold text-xs d-block mb-2"><i class="fas fa-list-ul text-warning me-2"></i> Fitur & Keunggulan Utama:</span>
                            <div class="d-flex flex-wrap gap-2">
                                ${specPillsHtml}
                            </div>
                        </div>

                        <!-- Tombol CTA Utama -->
                        <button type="button" class="btn-rent-app-modal" onclick="triggerAppModalFromDetail('${item.name.replace(/'/g, "\\'")}', '${item.vendor}')">
                            <i class="fas fa-mobile-alt fa-lg"></i> <span>Sewa Sekarang via Aplikasi</span> <i class="fas fa-arrow-right ms-auto"></i>
                        </button>
                        <div class="text-center mt-2">
                            <span class="text-muted" style="font-size: 0.78rem;"><i class="fas fa-shield-alt text-success me-1"></i> Transaksi 100% aman & terverifikasi via mobile app KampSewa</span>
                        </div>
                    </div>
                </div>
            `;
            const modalEl = new bootstrap.Modal(document.getElementById('gearDetailModal'));
            modalEl.show();
        }

        // 6. Event Listeners untuk Filter Toko Vendor
        document.querySelectorAll("#gearVendorFilterContainer .dest-region-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                document.querySelectorAll("#gearVendorFilterContainer .dest-region-btn").forEach(b => b.classList.remove("active"));
                this.classList.add("active");
                currentVendor = this.getAttribute("data-vendor");
                renderGearCatalog();
            });
        });

        // 7. Event Listeners untuk Filter Kategori
        document.querySelectorAll("#gearCategoryFilterContainer .dest-category-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                document.querySelectorAll("#gearCategoryFilterContainer .dest-category-btn").forEach(b => b.classList.remove("active"));
                this.classList.add("active");
                currentCategory = this.getAttribute("data-category");
                renderGearCatalog();
            });
        });

        // 8. Event Listeners untuk Search Input
        searchInput.addEventListener("input", function () {
            currentSearch = this.value;
            clearBtn.style.display = currentSearch.length > 0 ? "flex" : "none";
            renderGearCatalog();
        });

        clearBtn.addEventListener("click", function () {
            searchInput.value = "";
            currentSearch = "";
            this.style.display = "none";
            renderGearCatalog();
            searchInput.focus();
        });

        // 9. Event Listeners untuk Sorting
        sortSelect.addEventListener("change", function () {
            currentSort = this.value;
            renderGearCatalog();
        });

        // Inisialisasi Pertama
        renderVendors();
        loadProductsData();
    });
    </script>

    @include('landing-page.halamanbawah')
    @include('landing-page.footer')