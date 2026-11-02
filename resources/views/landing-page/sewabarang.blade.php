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

        <!-- Cinematic Sewa Barang Hero Section (Dinamis dari Database) -->
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
                    <span>{{ $heroContent->badge ?? '#1 Marketplace Rental Perlengkapan Outdoor & Camping Indonesia' }}</span>
                </div>

                <h1 class="about-hero-title">
                    {!! $heroContent->title ?? 'Sewa Perlengkapan <br><span class="text-white">Kualitas Terjamin & Steril</span>' !!}
                </h1>

                <p class="about-hero-desc">
                    {{ $heroContent->subtitle ?? 'Lengkapi petualangan alam bebasmu dengan ribuan peralatan camping dari toko vendor terverifikasi. Hemat biaya, praktis, siap pakai, dan terjamin kebersihannya!' }}
                </p>

                <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                    <a href="#katalog-alat" class="btn-modern-amber">
                        <i class="fas fa-search"></i>
                        <span>Cari & Sewa Alat Sekarang</span>
                    </a>
                    <a href="#toko-vendor-spotlight" class="btn-outline-white">
                        <i class="fas fa-store"></i>
                        <span>Lihat Toko Mitra Resmi</span>
                    </a>
                </div>
            </div>
        </section>
    </div>
    <!-- Navbar & Hero End -->

    <!-- Toko Vendor Spotlight Showcase (Dinamis dari Database) -->
    <section id="toko-vendor-spotlight" class="vendor-spotlight-section">
        <div class="container">
            <div class="section-header-modern">
                <span class="badge-section">Mitra Resmi Rental</span>
                <h2>Toko Vendor Outdoor Terverifikasi</h2>
                <p>Pilih perlengkapan dari toko rental resmi terpercaya di berbagai kota dengan jaminan kualitas alat 100% prima dan bersih.</p>
            </div>

            <!-- Grid Toko Vendor Dinamis dari Database (Responsive 4 Kolom) -->
            <div class="row g-4 mb-5" id="vendorGridContainer">
                @foreach($tokoVendorList as $v)
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 d-flex">
                        <div class="vendor-store-card w-100 d-flex flex-column">
                            <div class="vendor-card-img-wrapper">
                                <img src="{{ $v->foto_toko_url }}" class="vendor-card-img" alt="{{ $v->name_store }}" onerror="this.onerror=null; this.src='{{ asset('template/envato/img/toko-eiger.jpeg') }}';">
                                <span class="vendor-card-badge"><i class="fas fa-check-circle me-1"></i> {{ $v->badge_toko ?? 'Verified Mitra' }}</span>
                            </div>
                            <div class="vendor-card-body d-flex flex-column flex-grow-1">
                                <h3 class="vendor-card-title">{{ $v->name_store }}</h3>
                                <div class="vendor-card-location">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    <span>{{ $v->slogan_toko ?? 'Jawa Timur & Nusantara' }}</span>
                                </div>
                                <div class="vendor-card-stats">
                                    <div>⭐ {{ number_format($v->rating_toko ?? 5.0, 1) }}/5.0</div>
                                    <div>📦 {{ $v->total_penyewaan_toko ?? 150 }}+ Transaksi</div>
                                </div>
                                <button class="btn-filter-vendor mt-auto" data-vendor-target="{{ $v->name_store }}">
                                    <i class="fas fa-filter"></i> <span>Lihat Alat Toko Ini</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Toko Vendor Spotlight End -->

    <!-- Catalog Filter & Grid Section Start (Dinamis dari Database) -->
    <section id="katalog-alat" class="gear-catalog-section">
        <div class="container">
            <!-- Filter Bar Card -->
            <div class="gear-filter-card">
                <!-- Search Box -->
                <div class="dest-search-box mb-4">
                    <i class="fas fa-search dest-search-icon"></i>
                    <input type="text" id="gearSearchInput" class="dest-search-input" value="{{ $searchQuery }}" placeholder="Cari nama alat, tenda, carrier, sleeping bag, atau toko vendor (contoh: Eiger, Tenda Dome, Consina)...">
                    <button id="clearGearSearchBtn" class="dest-search-clear" title="Hapus pencarian" style="{{ !empty($searchQuery) ? 'display:flex;' : '' }}"><i class="fas fa-times"></i></button>
                </div>

                <!-- Vendor Filter Pills (Dinamis dari Database) -->
                <span class="dest-filter-label"><i class="fas fa-store text-primary me-2"></i> Filter Toko Vendor:</span>
                <div class="dest-region-tabs d-flex flex-wrap gap-2 mb-4" id="gearVendorFilterContainer">
                    <button class="dest-region-btn {{ empty($vendorQuery) ? 'active' : '' }}" data-vendor="all">
                        <i class="fas fa-globe"></i> <span>Semua Toko ({{ $tokoVendorList->count() }} Mitra)</span>
                    </button>
                    @foreach($tokoVendorList as $vendor)
                        <button class="dest-region-btn {{ $vendorQuery === $vendor->name_store ? 'active' : '' }}" data-vendor="{{ $vendor->name_store }}">
                            <i class="fas fa-store-alt"></i> <span>{{ $vendor->name_store }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Category Chips (Dinamis dari Database) -->
                <span class="dest-filter-label"><i class="fas fa-tags text-warning me-2"></i> Kategori Perlengkapan:</span>
                <div class="dest-category-chips d-flex flex-wrap gap-2" id="gearCategoryFilterContainer">
                    <button class="dest-category-btn {{ empty($categoryQuery) ? 'active' : '' }}" data-category="all">
                        <span>Semua Kategori</span>
                    </button>
                    @foreach($kategoriList as $cat)
                        <button class="dest-category-btn {{ $categoryQuery === $cat->nama_kategori ? 'active' : '' }}" data-category="{{ $cat->nama_kategori }}">
                            <span><i class="{{ $cat->icon ?? 'fas fa-campground' }}"></i> {{ $cat->nama_kategori }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Results Status & Sorting -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top gap-2">
                    <div id="gearResultsStatus" class="fw-bold text-dark font-display" style="font-size: 1.1rem;">
                        <i class="fas fa-boxes text-primary me-2"></i> Menampilkan <span id="gearCountDisplay" class="text-primary fw-bolder">{{ $totalProduk }}</span> alat camping siap sewa
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

            <!-- Gear Grid Container (Dinamis dari Database) -->
            <div id="gearGridContainer" class="gear-grid-container"></div>
        </div>
    </section>
    <!-- Catalog Filter & Grid Section End -->

    <!-- Premium Compact Modal Detail Spesifikasi Alat -->
    <div class="modal fade" id="gearDetailModal" tabindex="-1" aria-labelledby="gearDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content gear-modal-content">
                <button type="button" class="gear-modal-close-btn" data-bs-dismiss="modal" aria-label="Close" title="Tutup Etalase"><i class="fas fa-times"></i></button>
                <div class="modal-body p-0" id="gearDetailModalBody"></div>
            </div>
        </div>
    </div>

    <!-- App Not Available Animated Popup Modal -->
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
                        <span>Download App</span>
                    </a>
                    <button type="button" class="btn btn-link text-muted text-decoration-none btn-sm mt-1" data-bs-dismiss="modal">Kembali ke Etalase</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Katalog Perlengkapan Dinamis dari Database -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Data perlengkapan alat 100% dinamis dari database
        const gearList = @json($gearProductsData);

        let currentVendor = "{{ $vendorQuery ?: 'all' }}";
        let currentCategory = "{{ $categoryQuery ?: 'all' }}";
        let currentSearch = "{{ $searchQuery ?: '' }}";
        let currentSort = "popular";

        // Referensi Elemen
        const gearGrid = document.getElementById("gearGridContainer");
        const countDisplay = document.getElementById("gearCountDisplay");
        const searchInput = document.getElementById("gearSearchInput");
        const clearBtn = document.getElementById("clearGearSearchBtn");
        const sortSelect = document.getElementById("gearSortSelect");

        window.showRentAppModal = function(itemName, vendorName) {
            document.getElementById("rent-item-display").textContent = itemName;
            document.getElementById("rent-vendor-display").textContent = vendorName;
            const modalEl = new bootstrap.Modal(document.getElementById('rentAppModal'));
            modalEl.show();
        };

        window.triggerAppModalFromDetail = function(itemName, vendorName) {
            const detailEl = bootstrap.Modal.getInstance(document.getElementById('gearDetailModal'));
            if (detailEl) {
                detailEl.hide();
            }
            setTimeout(() => {
                showRentAppModal(itemName, vendorName);
            }, 300);
        };

        // Render Katalog Alat Camping dengan Filtering & Sorting
        function renderGearCatalog() {
            let filtered = gearList.filter(item => {
                // Filter Toko Vendor
                if (currentVendor !== "all" && item.vendor !== currentVendor) {
                    return false;
                }
                // Filter Kategori
                if (currentCategory !== "all") {
                    const itemCat = (item.category || "").toLowerCase();
                    const targetCat = currentCategory.toLowerCase();
                    if (!itemCat.includes(targetCat) && !targetCat.includes(itemCat)) {
                        return false;
                    }
                }
                // Filter Kata Kunci Search
                if (currentSearch.trim() !== "") {
                    const q = currentSearch.toLowerCase();
                    const matchName = (item.name || "").toLowerCase().includes(q);
                    const matchVendor = (item.vendor || "").toLowerCase().includes(q);
                    const matchCity = (item.vendorCity || "").toLowerCase().includes(q);
                    const matchCat = (item.category || "").toLowerCase().includes(q);
                    const matchSpecs = (item.specs || "").toLowerCase().includes(q);
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

            if (countDisplay) {
                countDisplay.textContent = filtered.length;
            }

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
                        if (searchInput) searchInput.value = "";
                        if (clearBtn) clearBtn.style.display = "none";
                        document.querySelectorAll("#gearVendorFilterContainer .dest-region-btn").forEach(b => b.classList.remove("active"));
                        const allVen = document.querySelector('#gearVendorFilterContainer .dest-region-btn[data-vendor="all"]');
                        if (allVen) allVen.classList.add("active");
                        document.querySelectorAll("#gearCategoryFilterContainer .dest-category-btn").forEach(b => b.classList.remove("active"));
                        const allCat = document.querySelector('#gearCategoryFilterContainer .dest-category-btn[data-category="all"]');
                        if (allCat) allCat.classList.add("active");
                        renderGearCatalog();
                    });
                }
                return;
            }

            filtered.forEach(item => {
                const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(item.price);
                const fallbackUrl = item.fallbackImg || "{{ asset('template/envato/img/barang-tenda.jpg') }}";
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
                            <span class="vendor-tag-card"><i class="fas fa-store"></i> ${item.vendor}</span>
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

        // Modal Detail Spesifikasi Alat
        function showGearModal(item) {
            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(item.price);
            const fallbackUrl = item.fallbackImg || "{{ asset('template/envato/img/barang-tenda.jpg') }}";
            
            const specArray = (item.specs || "").split("|").map(s => s.trim()).filter(s => s.length > 0);
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
                                <span class="text-muted text-xs"><i class="fas fa-map-marker-alt text-danger me-1"></i> Mitra Terverifikasi</span>
                            </div>
                        </div>
                    </div>

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

        // Event Listener Tombol "Lihat Alat Toko Ini" di Spotlight
        document.querySelectorAll(".btn-filter-vendor").forEach(btn => {
            btn.addEventListener("click", function () {
                const targetVendor = this.getAttribute("data-vendor-target");
                currentVendor = targetVendor;
                
                document.querySelectorAll("#gearVendorFilterContainer .dest-region-btn").forEach(b => {
                    b.classList.remove("active");
                    if (b.getAttribute("data-vendor") === targetVendor) {
                        b.classList.add("active");
                    }
                });

                document.getElementById("katalog-alat").scrollIntoView({ behavior: "smooth" });
                renderGearCatalog();
            });
        });

        // Event Listeners untuk Filter Toko Vendor
        document.querySelectorAll("#gearVendorFilterContainer .dest-region-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                document.querySelectorAll("#gearVendorFilterContainer .dest-region-btn").forEach(b => b.classList.remove("active"));
                this.classList.add("active");
                currentVendor = this.getAttribute("data-vendor");
                renderGearCatalog();
            });
        });

        // Event Listeners untuk Filter Kategori
        document.querySelectorAll("#gearCategoryFilterContainer .dest-category-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                document.querySelectorAll("#gearCategoryFilterContainer .dest-category-btn").forEach(b => b.classList.remove("active"));
                this.classList.add("active");
                currentCategory = this.getAttribute("data-category");
                renderGearCatalog();
            });
        });

        // Event Listeners untuk Search Input
        if (searchInput) {
            searchInput.addEventListener("input", function () {
                currentSearch = this.value;
                if (clearBtn) {
                    clearBtn.style.display = currentSearch.length > 0 ? "flex" : "none";
                }
                renderGearCatalog();
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener("click", function () {
                if (searchInput) searchInput.value = "";
                currentSearch = "";
                this.style.display = "none";
                renderGearCatalog();
                if (searchInput) searchInput.focus();
            });
        }

        // Event Listeners untuk Sorting
        if (sortSelect) {
            sortSelect.addEventListener("change", function () {
                currentSort = this.value;
                renderGearCatalog();
            });
        }

        // Render pertama kali
        renderGearCatalog();
    });
    </script>

    @include('landing-page.halamanbawah')
    @include('landing-page.footer')
