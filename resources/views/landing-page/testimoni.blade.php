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
                        <!-- Background Image -->
                        <img src="{{ asset('template/envato/img/home-6.jpg') }}" class="img-fluid" alt="Testimoni Pelanggan KampSewa" style="width: 100%; height: 100vh; object-fit: cover;">
                        
                        <!-- Dark Overlay -->
                        <div class="testimoni-hero-overlay"></div>
                        
                        <!-- Cinematic Title & Caption (Dinamis dari Database) -->
                        <div class="carousel-caption" style="z-index: 5;">
                            <div class="testimoni-hero-content text-center">
                                <span class="badge bg-warning text-dark px-4 py-2 rounded-pill fw-bold mb-3 shadow-sm font-display" style="letter-spacing: 2px; font-size: 0.85rem;"><i class="fas fa-quote-right me-2"></i> {{ $heroContent->badge ?? 'APA KATA MEREKA?' }}</span>
                                <h1 class="display-3 fw-bolder text-white mb-4 font-display" style="text-shadow: 0 4px 25px rgba(0,0,0,0.7);">{!! $heroContent->title ?? 'Testimoni Pelanggan <span class="text-warning">KampSewa</span>' !!}</h1>
                                <p class="lead text-light mb-4 mx-auto" style="max-width: 760px; font-size: 1.15rem; line-height: 1.8; opacity: 0.95; text-shadow: 0 2px 10px rgba(0,0,0,0.6);">{{ $heroContent->subtitle ?? 'Dengarkan cerita seru dan ulasan jujur dari ribuan petualang mengenai kemudahan, kecepatan, dan keamanan menyewa perlengkapan camping melalui aplikasi mobile KampSewa.' }}</p>
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

    <!-- Trust Impact Banner Section (Dinamis dari Database) -->
    <div class="container position-relative" style="z-index: 20;">
        <div class="testimoni-trust-banner">
            <div class="testimoni-trust-grid">
                <div class="trust-stat-box">
                    <h3>{{ $stats['raw_rating'] ?? '4.9' }} <span style="font-size: 1.3rem; color: var(--ks-gray-400);">/ 5.0</span></h3>
                    <p><i class="fas fa-star text-warning me-1"></i> Rating Kepuasan App</p>
                </div>
                <div class="trust-stat-box">
                    <h3>{{ $stats['total_testimoni'] ?? '1.250+' }}</h3>
                    <p><i class="fas fa-check-circle text-primary me-1"></i> Ulasan Pengguna</p>
                </div>
                <div class="trust-stat-box">
                    <h3>100%</h3>
                    <p><i class="fas fa-shield-alt text-warning me-1"></i> Transaksi Aman</p>
                </div>
                <div class="trust-stat-box">
                    <h3>{{ $stats['total_vendor'] ?? '150+' }}</h3>
                    <p><i class="fas fa-store text-danger me-1"></i> Vendor Mitra Resmi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Testimonial Grid & Filter Section Start (Dinamis dari Database) -->
    <section class="py-5 mt-4" id="testimoniGridSection">
        <div class="container py-4">
            <!-- Section Header -->
            <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                <span class="badge-section mb-3"><i class="fas fa-heart text-danger me-2"></i> ULASAN APLIKASI KAMPSEWA</span>
                <h2 class="section-title-modern">Feedback <span class="text-gradient">Nyata</span> Pengguna Aplikasi</h2>
                <p class="text-muted mt-3" style="font-size: 1.05rem; line-height: 1.7;">Bukti kepuasan petualang dari berbagai daerah di Indonesia terhadap fitur, kecepatan bertransaksi, dan pelayanan dari platform mobile KampSewa.</p>
                
                <!-- Simplified Filtering & Sorting Controls -->
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mt-4 pt-2">
                    <!-- Category Filter Buttons (Dinamis dari Database) -->
                    <div class="d-flex flex-wrap justify-content-center gap-2" id="testimoniFilterContainer">
                        <button class="testimoni-filter-btn active" data-filter="all">
                            <i class="fas fa-th-large text-warning"></i> <span>Semua ({{ $counts['all'] }})</span>
                        </button>
                        <button class="testimoni-filter-btn" data-filter="5">
                            <i class="fas fa-star text-warning"></i> <span>Sangat Bagus ({{ $counts['5'] }})</span>
                        </button>
                        <button class="testimoni-filter-btn" data-filter="4">
                            <i class="fas fa-thumbs-up text-primary"></i> <span>Bagus ({{ $counts['4'] }})</span>
                        </button>
                        <button class="testimoni-filter-btn" data-filter="3">
                            <i class="fas fa-smile text-secondary"></i> <span>Cukup ({{ $counts['3'] }})</span>
                        </button>
                    </div>

                    <!-- Sorting Select -->
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

            <!-- Testimonial Grid (Dinamis dari Database) -->
            <div class="row g-4" id="testimoniCardsContainer">
                @forelse($testimonials as $testi)
                    <div class="col-lg-4 col-md-6 testimoni-card-item" data-rating="{{ $testi->rating }}" data-name="{{ $testi->nama }}" data-date="{{ $testi->created_at ? $testi->created_at->format('Y-m-d') : '2026-07-01' }}">
                        <div class="testimoni-card-modern">
                            <i class="fas fa-quote-right testimoni-quote-icon"></i>
                            <div class="testimoni-content-wrap">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="text-warning">
                                        @for($i = 0; $i < $testi->rating; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @for($i = $testi->rating; $i < 5; $i++)
                                            <i class="far fa-star"></i>
                                        @endfor
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs fw-bold">{{ number_format($testi->rating, 1) }} / 5.0</span>
                                </div>
                                <p class="testimoni-text">"{{ $testi->ulasan }}"</p>
                                <div class="testimoni-user-footer">
                                    <img src="{{ $testi->avatar_url }}" class="testimoni-user-img" alt="{{ $testi->nama }}" onerror="this.onerror=null; this.src='{{ asset('template/envato/img/Testimoni-1.jpg') }}';">
                                    <div class="testi-user-info">
                                        <h5>{{ $testi->nama }}</h5>
                                        <p><i class="fas fa-map-marker-alt text-danger"></i> {{ $testi->kota ?? 'Indonesia' }} • <span class="text-muted">{{ $testi->created_at ? $testi->created_at->format('M Y') : 'Jul 2026' }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-comment-slash fs-1 text-muted mb-3 d-block"></i>
                        <h5 class="fw-bold text-dark">Belum ada ulasan di database</h5>
                        <p class="text-muted text-sm">Jadilah orang pertama yang menuliskan pengalaman petualangan Anda!</p>
                    </div>
                @endforelse
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

    <!-- Modal Tulis Ulasan (Tersimpan Langsung ke Database) -->
    <div class="modal fade" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px; border: none; box-shadow: 0 25px 60px rgba(0,0,0,0.25); overflow: hidden;">
                <!-- Header Modal -->
                <div class="p-4" style="background: linear-gradient(135deg, var(--ks-dark) 0%, var(--ks-primary-dark) 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-display fw-bold" style="color: #FFFFFF !important;"><i class="fas fa-star text-warning me-2"></i> Tulis Ulasan & Testimoni</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <!-- Body Modal -->
                <div class="modal-body p-4 bg-white">
                    <form id="reviewForm" onsubmit="submitReviewForm(event)">
                        @csrf
                        <div class="mb-3">
                            <label class="testimoni-label-modern">Nama Lengkap</label>
                            <input type="text" name="nama" id="inputNama" class="form-control testimoni-input-modern" placeholder="Contoh: Budi Santoso" required>
                        </div>
                        <div class="mb-3">
                            <label class="testimoni-label-modern">Kota Domisili</label>
                            <input type="text" name="kota" id="inputKota" class="form-control testimoni-input-modern" placeholder="Contoh: Surabaya, Jawa Timur" required>
                        </div>
                        <div class="mb-3">
                            <label class="testimoni-label-modern">Kategori Penilaian Aplikasi KampSewa</label>
                            <select name="rating" id="inputRating" class="form-select testimoni-input-modern fw-bold text-dark" required>
                                <option value="5">⭐⭐⭐⭐⭐ Sangat Bagus (5.0 / 5.0)</option>
                                <option value="4">⭐⭐⭐⭐ Bagus (4.0 / 5.0)</option>
                                <option value="3">⭐⭐⭐ Cukup (3.0 / 5.0)</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="testimoni-label-modern">Ulasan Pengalaman Menggunakan Aplikasi KampSewa</label>
                            <textarea name="ulasan" id="inputUlasan" class="form-control testimoni-input-modern" rows="4" placeholder="Ceritakan kemudahan fitur booking, transparansi deposit, atau layanan customer support di aplikasi kami..." required></textarea>
                        </div>
                        <button type="submit" id="btnSubmitReview" class="btn btn-primary w-100 py-3 rounded-pill fw-bold font-display shadow-sm">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Ulasan Aplikasi Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('landing-page.halamanbawah')
    @include('landing-page.footer')

    <!-- Script Kustom Filter, Sorting & Submit Testimoni ke Database -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const filterButtons = document.querySelectorAll(".testimoni-filter-btn");
        const sortSelect = document.getElementById("testimoniSortSelect");
        const container = document.getElementById("testimoniCardsContainer");
        let cardItems = Array.from(document.querySelectorAll(".testimoni-card-item"));

        let currentFilter = "all";

        window.showWriteReviewModal = function() {
            const modalEl = document.getElementById("writeReviewModal");
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        };

        function filterAndSortCards() {
            const sortValue = sortSelect ? sortSelect.value : "newest";

            // Filter
            let filtered = cardItems.filter(card => {
                const rating = card.getAttribute("data-rating");
                if (currentFilter === "all") return true;
                if (currentFilter === "3") {
                    return parseInt(rating) <= 3;
                }
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
                    return dateB.localeCompare(dateA);
                }
            });

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
                });
            }
        }

        filterButtons.forEach(btn => {
            btn.addEventListener("click", function() {
                filterButtons.forEach(b => b.classList.remove("active"));
                this.classList.add("active");
                currentFilter = this.getAttribute("data-filter");
                filterAndSortCards();
            });
        });

        if (sortSelect) {
            sortSelect.addEventListener("change", filterAndSortCards);
        }

        // Handle AJAX submit form review ke database
        window.submitReviewForm = async function(event) {
            event.preventDefault();
            const btn = document.getElementById("btnSubmitReview");
            const form = document.getElementById("reviewForm");
            
            const nama = document.getElementById("inputNama").value;
            const kota = document.getElementById("inputKota").value;
            const rating = document.getElementById("inputRating").value;
            const ulasan = document.getElementById("inputUlasan").value;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan ke database...';

            try {
                const response = await fetch("{{ route('landing-page.submit-testimoni') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ nama, kota, rating, ulasan })
                });

                const result = await response.json();
                if (response.ok && result.status === 'success') {
                    alert(result.message || "Ulasan Anda berhasil disimpan ke database!");
                    form.reset();
                    const modalEl = document.getElementById("writeReviewModal");
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    location.reload();
                } else {
                    alert(result.message || "Gagal menyimpan ulasan. Periksa inputan Anda.");
                }
            } catch (err) {
                console.error("Error submitting review:", err);
                alert("Terjadi kesalahan koneksi saat menyimpan ulasan.");
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Kirim Ulasan Aplikasi Sekarang';
            }
        };
    });
    </script>
