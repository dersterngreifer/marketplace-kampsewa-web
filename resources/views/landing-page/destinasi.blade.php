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

        <!-- Cinematic Destinasi Hero Section (Dinamis dari Database) -->
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
                    <span>{{ $heroContent->badge ?? '#1 Eksplorasi Destinasi Wisata Alam & Camping Indonesia' }}</span>
                </div>

                <h1 class="about-hero-title">
                    {!! $heroContent->title ?? 'Jelajahi Surga Alam <br><span class="text-white">Nusantara Indonesia</span>' !!}
                </h1>

                <p class="about-hero-desc">
                    {{ $heroContent->subtitle ?? 'Temukan ratusan destinasi camping, glamping, gunung, pantai, dan air terjun terbaik dari Sabang sampai Merauke. Dilengkapi integrasi lokasi akurat langsung ke Google Maps!' }}
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

    <!-- Filter & Search Section Start (Dinamis dari Database) -->
    <section id="eksplor-destinasi" class="destinasi-filter-section">
        <div class="container">
            <div class="destinasi-filter-card">
                <!-- Search Bar Row -->
                <div class="dest-search-box">
                    <i class="fas fa-search dest-search-icon"></i>
                    <input type="text" id="searchInput" class="dest-search-input" placeholder="Cari nama destinasi, daerah, kota, alamat, atau gunung (contoh: Bromo, Bandung, Bali, Toba)...">
                    <button id="clearSearchBtn" class="dest-search-clear" title="Hapus pencarian"><i class="fas fa-times"></i></button>
                </div>

                <!-- Region Filter Tabs (Dinamis dari Database) -->
                <span class="dest-filter-label"><i class="fas fa-map-marked-alt text-primary me-2"></i> Filter Wilayah & Provinsi:</span>
                <div class="dest-region-tabs" id="regionFilterContainer">
                    <button class="dest-region-btn active" data-region="all">
                        <i class="fas fa-globe-asia"></i> <span>Semua Wilayah</span>
                    </button>
                    @foreach($wilayahList as $wil)
                        <button class="dest-region-btn" data-region="{{ $wil }}">
                            <i class="fas fa-map-marker-alt"></i> <span>{{ $wil }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Category Filter Chips (Dinamis dari Database) -->
                <span class="dest-filter-label"><i class="fas fa-tags text-warning me-2"></i> Kategori Wisata Alam:</span>
                <div class="dest-category-chips" id="categoryFilterContainer">
                    <button class="dest-category-btn active" data-category="all">
                        <span>Semua Kategori</span>
                    </button>
                    @foreach($kategoriList as $cat)
                        <button class="dest-category-btn" data-category="{{ $cat }}">
                            <span>{{ $cat }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Results status bar -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top gap-2">
                    <div id="resultsStatus" class="fw-bold text-dark font-display" style="font-size: 1.1rem;">
                        <i class="fas fa-list-ul text-primary me-2"></i> Menampilkan <span id="countDisplay" class="text-primary fw-bolder">{{ $totalDestinasi }}</span> destinasi wisata di Indonesia
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

            <!-- Destinations Grid Container (Dinamis dari Database) -->
            <div id="destGridContainer" class="dest-grid-container"></div>
        </div>
    </section>
    <!-- Filter & Search Section End -->

    <!-- Call to Action Banner -->
    <section class="cta-modern-section">
        <div class="container">
            <div class="cta-banner-card">
                <div class="cta-content-inner">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3"><i class="fas fa-campground me-1"></i> Perlengkapan Siap Pakai</span>
                    <h2>Sudah Menentukan Destinasi Impianmu?</h2>
                    <p>Pastikan perlengkapan camping dan trekkingmu lengkap, aman, dan steril sebelum berangkat. Sewa sekarang dari vendor mitra terpercaya!</p>
                    
                    <div class="cta-buttons-group">
                        <a href="{{ route('landing-page.halaman-sewabarang') }}" class="btn-modern-amber">
                            <i class="fas fa-search"></i>
                            <span>Cari Alat Camping</span>
                        </a>
                        <a href="{{ route('landing-page.halaman-beranda') }}" class="btn-outline-white">
                            <i class="fas fa-home"></i>
                            <span>Kembali ke Beranda</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Script Kustom Eksplorasi Destinasi Dinamis dari Database -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Data destinasi 100% dinamis dari database
        const rawDestinations = @json($destinasiData);

        let destDataList = Array.isArray(rawDestinations) ? rawDestinations : [];
        let currentRegion = "all";
        let currentCategory = "all";
        let currentSearchQuery = "";
        let currentSort = "recommended";

        // Element Referensi
        const gridContainer = document.getElementById("destGridContainer");
        const countDisplay = document.getElementById("countDisplay");
        const searchInput = document.getElementById("searchInput");
        const clearSearchBtn = document.getElementById("clearSearchBtn");
        const sortSelect = document.getElementById("sortSelect");

        // Render Destinasi dengan Filtering, Searching & Sorting
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
                    const matchTitle = (item.title || "").toLowerCase().includes(q);
                    const matchRegion = (item.region || "").toLowerCase().includes(q);
                    const matchAddress = (item.address || "").toLowerCase().includes(q);
                    const matchDesc = (item.desc || "").toLowerCase().includes(q);
                    const matchCat = (item.category || "").toLowerCase().includes(q);
                    if (!matchTitle && !matchRegion && !matchAddress && !matchDesc && !matchCat) {
                        return false;
                    }
                }
                return true;
            });

            // Sorting
            if (currentSort === "recommended") {
                filtered.sort((a, b) => (b.recommended === a.recommended) ? (b.rating - a.rating) : (b.recommended ? 1 : -1));
            } else if (currentSort === "name-asc") {
                filtered.sort((a, b) => (a.title || "").localeCompare(b.title || ""));
            } else if (currentSort === "name-desc") {
                filtered.sort((a, b) => (b.title || "").localeCompare(a.title || ""));
            } else if (currentSort === "rating-desc") {
                filtered.sort((a, b) => (b.rating - a.rating));
            }

            if (countDisplay) {
                countDisplay.textContent = filtered.length;
            }

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
                        if (searchInput) searchInput.value = "";
                        if (clearSearchBtn) clearSearchBtn.style.display = "none";
                        document.querySelectorAll(".dest-region-btn").forEach(b => b.classList.remove("active"));
                        const allReg = document.querySelector('.dest-region-btn[data-region="all"]');
                        if (allReg) allReg.classList.add("active");
                        document.querySelectorAll(".dest-category-btn").forEach(b => b.classList.remove("active"));
                        const allCat = document.querySelector('.dest-category-btn[data-category="all"]');
                        if (allCat) allCat.classList.add("active");
                        renderDestinations();
                    });
                }
                return;
            }

            filtered.forEach(item => {
                const mapsUrl = item.mapsUrl || `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(item.title + ' ' + item.address)}`;

                const cardHtml = `
                    <div class="dest-card-modern">
                        <div class="dest-card-img-wrapper">
                            <img src="${item.finalImg}" class="dest-card-img" alt="${item.title}" onerror="this.onerror=null; this.src='${item.fallbackImg}'">
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
                            <a href="${mapsUrl}" target="_blank" rel="noopener noreferrer" class="btn-google-maps">
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

        // Event Listeners untuk Filter Wilayah
        document.querySelectorAll(".dest-region-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                document.querySelectorAll(".dest-region-btn").forEach(b => b.classList.remove("active"));
                this.classList.add("active");
                currentRegion = this.getAttribute("data-region");
                renderDestinations();
            });
        });

        // Event Listeners untuk Filter Kategori
        document.querySelectorAll(".dest-category-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                document.querySelectorAll(".dest-category-btn").forEach(b => b.classList.remove("active"));
                this.classList.add("active");
                currentCategory = this.getAttribute("data-category");
                renderDestinations();
            });
        });

        // Event Listeners untuk Search Input
        if (searchInput) {
            searchInput.addEventListener("input", function () {
                currentSearchQuery = this.value;
                if (clearSearchBtn) {
                    clearSearchBtn.style.display = currentSearchQuery.length > 0 ? "flex" : "none";
                }
                renderDestinations();
            });
        }

        if (clearSearchBtn) {
            clearSearchBtn.addEventListener("click", function () {
                if (searchInput) searchInput.value = "";
                currentSearchQuery = "";
                this.style.display = "none";
                renderDestinations();
                if (searchInput) searchInput.focus();
            });
        }

        // Event Listeners untuk Sorting
        if (sortSelect) {
            sortSelect.addEventListener("change", function () {
                currentSort = this.value;
                renderDestinations();
            });
        }

        // Render pertama kali langsung dari database
        renderDestinations();
    });
    </script>

    @include('landing-page.halamanbawah')
    @include('landing-page.footer')
