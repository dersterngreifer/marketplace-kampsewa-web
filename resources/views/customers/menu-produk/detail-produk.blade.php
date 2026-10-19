@extends('layouts.customers.layouts-customer')
@section('customer-content')
<div class="dp-wrapper">

    {{-- Breadcrumb --}}
    <nav class="dp-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('menu-produk.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="dp-breadcrumb__link">
            <i class="bi bi-box-seam"></i> Produk
        </a>
        <i class="bi bi-chevron-right dp-breadcrumb__sep"></i>
        <span class="dp-breadcrumb__current">{{ Str::limit($detail_produk->nama_produk, 50) }}</span>
    </nav>

    {{-- Main Card: 2 kolom (Foto | Judul+Deskripsi+Edit) --}}
    <div class="dp-card">

        {{-- LEFT: Image Gallery --}}
        <div class="dp-gallery">
            {{-- Thumbnail strip vertikal --}}
            <div class="dp-thumbs-wrapper" style="position: relative; height: 100%;">
                <div class="dp-thumbs" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0;">
                    @forelse ($detail_produk->foto as $foto)
                        <div class="dp-thumb"
                            onclick="dpChangeImage('{{ $foto->url_foto }}', '{{ str_starts_with($foto->url_foto, 'http') ? 'external' : $foto->tipe_sumber }}', this)">
                            <img src="{{ str_starts_with($foto->url_foto, 'http') ? $foto->url_foto : \App\Helpers\PhotoHelper::getPhotoUrl($foto->url_foto, $foto->tipe_sumber) }}"
                                alt="Thumbnail"
                                onerror="this.onerror=null;this.src='{{ asset('images/illustration/filling-survey.png') }}';">
                        </div>
                    @empty
                        <div class="dp-thumb">
                            <img src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($detail_produk) }}"
                                alt="Thumbnail"
                                onerror="this.onerror=null;this.src='{{ asset('images/illustration/filling-survey.png') }}';">
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Main image --}}
            <div class="dp-main-img-wrap group" style="cursor: zoom-in;" onclick="dpOpenModal()">
                <img id="dp-main-img"
                    src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($detail_produk) }}"
                    alt="{{ $detail_produk->nama_produk }}"
                    onerror="this.onerror=null;this.src='{{ asset('images/illustration/filling-survey.png') }}';">
                <span class="dp-category-badge">{{ $detail_produk->kategori_produk ?? 'Peralatan Kemah' }}</span>
                <div class="dp-view-overlay">
                    <i class="bi bi-eye"></i> View
                </div>
            </div>
        </div>

        {{-- RIGHT: Judul, Deskripsi, Tombol Edit --}}
        <div class="dp-info">

            {{-- Status + Rating --}}
            <div class="dp-meta">
                @if(strtolower($detail_produk->status_produk) == 'tersedia')
                    <span class="dp-badge dp-badge--green">
                        <span class="dp-badge__dot dp-badge__dot--pulse"></span>
                        {{ $detail_produk->status_produk }}
                    </span>
                @else
                    <span class="dp-badge dp-badge--orange">
                        <span class="dp-badge__dot"></span>
                        {{ $detail_produk->status_produk }}
                    </span>
                @endif
                <div class="dp-rating">
                    <i class="bi bi-star-fill dp-rating__star"></i>
                    <span class="dp-rating__score">{{ number_format($rating, 1) }}</span>
                    <span class="dp-rating__sep">·</span>
                    <a href="#dp-ulasan" class="dp-rating__link">{{ $total_ulasan }} Ulasan</a>
                </div>
            </div>

            {{-- Title --}}
            <h1 class="dp-title">{{ $detail_produk->nama_produk }}</h1>

            {{-- Description --}}
            <div class="dp-desc-wrap">
                <p class="dp-desc-label"><i class="bi bi-info-circle-fill"></i> Deskripsi Produk</p>
                <p class="dp-desc">{{ $detail_produk->deskripsi_produk }}</p>
            </div>

            {{-- Action --}}
            <a href="{{ route('menu-produk.update-produk', ['id_produk' => Crypt::encrypt($detail_produk->id_produk), 'id_user' => Crypt::encrypt(session('id_user'))]) }}"
                class="dp-btn-edit">
                <i class="bi bi-pencil-square"></i> Edit Produk
            </a>
        </div>

    </div>{{-- /dp-card --}}


    {{-- BOTTOM: grid 30% Varian | 70% Ulasan --}}
    <div class="dp-bottom">

        {{-- LEFT (30%): Varian Produk --}}
        <div class="dp-variants-section">
            <div class="dp-variants-section__header">
                <div class="dp-variants-section__title">
                    <i class="bi bi-palette-fill"></i>
                    <span>Varian Produk</span>
                </div>
                <span class="dp-variants-section__hint">Pilih warna</span>
            </div>

            {{-- Color Buttons — flex-wrap otomatis kebawah jika banyak --}}
            <div class="dp-colors">
                @foreach ($variant_details as $warna => $details)
                    <button class="dp-color-btn" onclick="dpShowVariant('{{ $warna }}', this)"
                        style="background-color:{{ $warna }};" title="{{ ucfirst($warna) }}"
                        data-label="{{ ucfirst($warna) }}">
                    </button>
                @endforeach
            </div>

            {{-- Variant Panel (tampil di bawah tombol warna) --}}
            <div class="dp-panels-wrap">
                @foreach ($variant_details as $warna => $details)
                    <div class="dp-variant-panel" id="dp-panel-{{ $warna }}">
                        <div class="dp-variant-panel__head">
                            <span class="dp-color-dot" style="background:{{ $warna }};"></span>
                            {{ ucfirst($warna) }}
                        </div>
                        <div class="dp-size-grid">
                            @foreach ($details as $item)
                                <div class="dp-size-card" style="--variant-color: {{ $warna }};">
                                    <div class="dp-size-card__top" style="align-items: flex-start;">
                                        <div class="dp-size-card__size">
                                            <span class="dp-size-label">Ukuran</span>
                                            <span class="dp-size-value">{{ $item->ukuran }}</span>
                                        </div>
                                        <span class="dp-stock" style="background-color: var(--variant-color); color: #fff; text-shadow: 0px 1px 2px rgba(0,0,0,0.5); border-color: rgba(0,0,0,0.1);">Stok: {{ $item->stok }}</span>
                                    </div>
                                    <div class="dp-size-card__price">Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- RIGHT (70%): Ulasan --}}
        <div id="dp-ulasan" class="dp-reviews">
            <div class="dp-reviews__header">
                <h2 class="dp-reviews__title">Ulasan &amp; Penilaian</h2>
                <div class="dp-reviews__summary">
                    <span class="dp-reviews__score">{{ number_format($rating, 1) }}<span class="dp-reviews__max">/5</span></span>
                    <span class="dp-reviews__count">Dari {{ $total_ulasan }} ulasan</span>
                </div>
            </div>

            @if($userRatings->isEmpty())
                <div class="dp-reviews__empty">
                    <i class="bi bi-chat-square-text"></i>
                    <p class="dp-reviews__empty-title">Belum Ada Ulasan</p>
                    <p class="dp-reviews__empty-sub">Produk ini belum menerima ulasan dari penyewa.</p>
                </div>
            @else
                <div class="dp-reviews__grid">
                    @foreach ($userRatings as $reviewIndex => $ratingUlasan)
                        @php
                            $score = (float) ($ratingUlasan->rating ?? 5);
                            if ($score > 5) {
                                $score = $score / 2;
                            }
                            $fullStars = floor($score);
                            $hasHalf = ($score - $fullStars) >= 0.5;
                            $emptyStars = max(0, 5 - $fullStars - ($hasHalf ? 1 : 0));
                            $fotos = $ratingUlasan->fotos_ulasan ?? [];
                        @endphp
                        <div class="dp-review-card">
                            <div class="dp-review-card__top">
                                <div class="dp-review-card__user">
                                    <div class="dp-review-card__avatar">
                                        <img src="@userPhoto($ratingUlasan->foto)" alt="{{ $ratingUlasan['user_name'] ?? 'Pelanggan' }}" onerror="this.src='{{ asset('images/illustration/filling-survey.png') }}';">
                                    </div>
                                    <div>
                                        <div class="dp-review-card__name-wrap">
                                            <p class="dp-review-card__name">{{ $ratingUlasan['user_name'] ?? 'Pelanggan' }}</p>
                                            <span class="dp-verified-badge" title="Penyewa Terverifikasi">
                                                <i class="bi bi-patch-check-fill"></i>
                                            </span>
                                        </div>
                                        <p class="dp-review-card__time">
                                            <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($ratingUlasan->created_at)->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="dp-review-card__rating-box">
                                    <div class="dp-review-card__stars">
                                        @for ($s = 0; $s < $fullStars; $s++)
                                            <i class="bi bi-star-fill text-amber-400"></i>
                                        @endfor
                                        @if ($hasHalf)
                                            <i class="bi bi-star-half text-amber-400"></i>
                                        @endif
                                        @for ($s = 0; $s < $emptyStars; $s++)
                                            <i class="bi bi-star text-slate-300"></i>
                                        @endfor
                                    </div>
                                    <span class="dp-review-score-badge">{{ number_format($score, 1) }}</span>
                                </div>
                            </div>

                            @if(!empty(trim($ratingUlasan['ulasan'] ?? '')))
                                <p class="dp-review-card__text">"{{ $ratingUlasan['ulasan'] }}"</p>
                            @endif

                            {{-- Review Photo List (Seperti pada Mobile App) --}}
                            @if (!empty($fotos) && count($fotos) > 0)
                                <div class="dp-review-photos-box">
                                    <div class="dp-review-photos-header">
                                        <span class="dp-review-photos-count">
                                            <i class="bi bi-images text-blue-500"></i>
                                            <span>{{ count($fotos) }} Foto Terlampir</span>
                                        </span>
                                        <span class="dp-review-photos-hint">
                                            <i class="bi bi-arrows-fullscreen"></i> Klik untuk preview &amp; slide
                                        </span>
                                    </div>
                                    <div class="dp-review-photos-list">
                                        @foreach ($fotos as $imgIdx => $imgUrl)
                                            <div class="dp-review-photo-item group"
                                                onclick="dpOpenReviewCarousel({{ $reviewIndex }}, {{ $imgIdx }})"
                                                role="button"
                                                tabindex="0"
                                                title="Lihat foto {{ $imgIdx + 1 }} full resolusi">
                                                <img src="{{ $imgUrl }}"
                                                    alt="Foto ulasan {{ $ratingUlasan['user_name'] ?? 'Pelanggan' }} #{{ $imgIdx + 1 }}"
                                                    loading="lazy"
                                                    onerror="this.onerror=null; this.src='{{ asset('images/illustration/filling-survey.png') }}';">
                                                <div class="dp-review-photo-overlay">
                                                    <i class="bi bi-zoom-in"></i>
                                                </div>
                                                <span class="dp-review-photo-idx">{{ $imgIdx + 1 }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>{{-- /dp-bottom --}}

</div>{{-- /dp-wrapper --}}

{{-- Modal for full resolution main product image --}}
<div id="dp-image-modal" class="dp-image-modal" onclick="dpCloseModal()">
    <span class="dp-image-modal-close" onclick="dpCloseModal()">&times;</span>
    <img id="dp-image-modal-img" class="dp-image-modal-content" onclick="event.stopPropagation()">
</div>

{{-- Fullscreen Carousel Lightbox Modal untuk Foto Ulasan --}}
<div id="dp-review-carousel-modal" class="dp-carousel-modal" onclick="dpCloseReviewCarousel(event)" style="display: none;">
    {{-- Top Controls --}}
    <div class="dp-carousel-topbar" onclick="event.stopPropagation()">
        <div class="dp-carousel-reviewer-info">
            <img id="dp-carousel-user-avatar" src="" alt="Avatar" class="dp-carousel-avatar" onerror="this.src='{{ asset('images/illustration/filling-survey.png') }}';">
            <div>
                <div class="dp-carousel-name-row">
                    <span id="dp-carousel-user-name" class="dp-carousel-user-name">User</span>
                    <span id="dp-carousel-rating-stars" class="dp-carousel-stars"></span>
                </div>
                <div class="dp-carousel-meta-row">
                    <span id="dp-carousel-date" class="dp-carousel-date">Tanggal</span>
                    <span class="dp-carousel-meta-sep">•</span>
                    <span id="dp-carousel-counter" class="dp-carousel-counter">Foto 1 dari 1</span>
                </div>
            </div>
        </div>

        <div class="dp-carousel-actions">
            <button type="button" class="dp-carousel-action-btn" onclick="dpDownloadCurrentPhoto()" title="Buka foto resolusi asli di tab baru">
                <i class="bi bi-box-arrow-up-right"></i>
            </button>
            <button type="button" class="dp-carousel-close-btn" onclick="dpCloseReviewCarousel()" title="Tutup (Esc)">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>

    {{-- Carousel Stage --}}
    <div class="dp-carousel-stage" onclick="event.stopPropagation()">
        <button type="button" id="dp-carousel-btn-prev" class="dp-carousel-nav-btn dp-carousel-nav-btn--prev" onclick="dpSlideReviewPhoto(-1)" title="Foto Sebelumnya (Arrow Left)">
            <i class="bi bi-chevron-left"></i>
        </button>

        <div class="dp-carousel-slide-wrap" id="dp-carousel-slide-wrap">
            <img id="dp-carousel-main-img" class="dp-carousel-main-img" src="" alt="Review Photo Full" />
        </div>

        <button type="button" id="dp-carousel-btn-next" class="dp-carousel-nav-btn dp-carousel-nav-btn--next" onclick="dpSlideReviewPhoto(1)" title="Foto Berikutnya (Arrow Right)">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>

    {{-- Bottom Bar --}}
    <div class="dp-carousel-bottombar" onclick="event.stopPropagation()">
        <div id="dp-carousel-caption" class="dp-carousel-caption">
            <i class="bi bi-chat-quote-fill dp-carousel-quote-icon"></i>
            <span id="dp-carousel-text">"Komentar ulasan..."</span>
        </div>

        <div id="dp-carousel-thumbs-strip" class="dp-carousel-thumbs-strip">
            {{-- Injected dynamically --}}
        </div>
    </div>
</div>

<style>
/* Modal CSS */
.dp-image-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0; top: 0; width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.85);
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(5px);
}
.dp-image-modal-content {
    max-width: 95vw;
    max-height: 95vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.3);
}
.dp-image-modal-close {
    position: absolute;
    top: 20px; right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}
.dp-image-modal-close:hover { color: #bbb; }
/* ==========================================================
   DETAIL PRODUK — Clean Custom CSS
   ========================================================== */

/* Wrapper */
.dp-wrapper {
    max-width: 1120px;
    margin: 0 auto;
    padding: 2rem 1.5rem 3rem;
    display: flex;
    flex-direction: column;
    gap: 1.75rem;
    /* CRITICAL: prevent wrapper from getting horizontal scroll */
    overflow-x: hidden;
}

/* Breadcrumb */
.dp-breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #6b7280; }
.dp-breadcrumb__link { display: flex; align-items: center; gap: 0.375rem; color: #6b7280; text-decoration: none; transition: color .2s; }
.dp-breadcrumb__link:hover { color: #2563eb; }
.dp-breadcrumb__sep { font-size: 0.7rem; color: #d1d5db; }
.dp-breadcrumb__current { color: #111827; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 400px; }

/* ==========================================================
   MAIN CARD — 2 kolom setara tinggi
   ========================================================== */
.dp-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 1.5rem;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
    display: grid;
    grid-template-columns: 1.15fr 1fr;
    align-items: stretch;   /* kedua kolom sama tinggi */
    overflow: hidden;
}

/* ==========================================================
   LEFT: Galeri Foto
   Inner grid: [80px thumb strip] [gambar utama]
   ========================================================== */
.dp-gallery {
    padding: 1.5rem;
    background: #fff;
    border-right: 1px solid #e5e7eb;
    display: grid;
    grid-template-columns: 80px 1fr;  /* 80px cukup untuk thumb 64px + scrollbar 4px + gap */
    gap: 0.75rem;
    align-items: start;
    /* Crop overflow dari dalam galeri saja */
    overflow: hidden;
}

/* Thumbnail strip vertikal */
.dp-thumbs {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    overflow-y: auto;
    overflow-x: hidden;   /* tidak boleh horizontal scroll */
    /* Scrollbar tipis pakai padding internal, bukan margin negatif */
    padding-right: 4px;
    scrollbar-width: thin;
    scrollbar-color: #d1d5db #f3f4f6;
    box-sizing: border-box;
}
.dp-thumbs::-webkit-scrollbar { width: 3px; background: #f3f4f6; border-radius: 3px; }
.dp-thumbs::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }

.dp-thumb {
    flex-shrink: 0;
    width: 60px;        /* lebih kecil dari kolom 80px — ada ruang scrollbar */
    height: 60px;
    border-radius: 0.5rem;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid #e5e7eb;
    opacity: .6;
    transition: all .2s ease;
    background: #f3f4f6;
    box-sizing: border-box;
}
.dp-thumb:hover, .dp-thumb.active {
    border-color: #3b82f6;
    opacity: 1;
    box-shadow: 0 0 0 3px rgba(59,130,246,.18);
}
.dp-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

/* Gambar utama — rasio 1:1, tidak ikut melar */
.dp-main-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    border-radius: 0.875rem;
    overflow: hidden;
    background: #f3f4f6;
}
.dp-main-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s ease; }
.dp-main-img-wrap:hover img { transform: scale(1.04); }

.dp-view-overlay {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.4);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    font-weight: 600;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}
.dp-main-img-wrap:hover .dp-view-overlay {
    opacity: 1;
}

.dp-category-badge {
    position: absolute;
    top: 0.75rem; left: 0.75rem;
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(8px);
    color: #1f2937;
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 0.25rem 0.625rem;
    border-radius: 0.4rem;
    border: 1px solid rgba(255,255,255,.4);
    pointer-events: none;
}

/* ==========================================================
   RIGHT: Info (Judul, Deskripsi, Edit)
   ========================================================== */
.dp-info {
    padding: 2rem 2.25rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    background: #fff;
}

.dp-meta { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }

.dp-badge {
    display: inline-flex; align-items: center; gap: 0.375rem;
    font-size: 0.75rem; font-weight: 800;
    padding: 0.3rem 0.75rem;
    border-radius: 9999px; border: 1px solid;
}
.dp-badge--green  { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
.dp-badge--orange { background: #fff7ed; color: #ea580c; border-color: #fed7aa; }
.dp-badge__dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
.dp-badge__dot--pulse { animation: dp-pulse 1.5s infinite; }
@keyframes dp-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .4; transform: scale(.8); }
}

.dp-rating {
    display: flex; align-items: center; gap: 0.375rem;
    font-size: 0.875rem;
    background: #f9fafb; border: 1px solid #e5e7eb;
    border-radius: 9999px; padding: 0.25rem 0.875rem;
}
.dp-rating__star  { color: #f59e0b; font-size: 0.8rem; }
.dp-rating__score { font-weight: 800; color: #111827; }
.dp-rating__sep   { color: #d1d5db; }
.dp-rating__link  { color: #6b7280; font-weight: 600; text-decoration: none; }
.dp-rating__link:hover { color: #2563eb; }

.dp-title {
    font-size: clamp(1.4rem, 2.2vw, 1.9rem);
    font-weight: 900; color: #111827;
    line-height: 1.25; letter-spacing: -.02em; margin: 0;
}

.dp-desc-label {
    font-size: 0.78rem; font-weight: 800; color: #2563eb;
    text-transform: uppercase; letter-spacing: .07em;
    margin-bottom: 0.375rem;
    display: flex; align-items: center; gap: 0.375rem;
}
.dp-desc {
    font-size: 0.88rem; color: #4b5563; line-height: 1.7; margin: 0;
    max-height: 140px;
    overflow-y: auto;
    padding-right: 4px;
    scrollbar-width: thin; scrollbar-color: #e5e7eb transparent;
}
.dp-desc::-webkit-scrollbar { width: 3px; }
.dp-desc::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 3px; }

.dp-btn-edit {
    display: flex; align-items: center; justify-content: center; gap: 0.625rem;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: #fff; font-size: 0.9rem; font-weight: 800; letter-spacing: .03em;
    padding: 0.875rem 1.5rem;
    border-radius: 0.875rem; text-decoration: none;
    box-shadow: 0 4px 16px rgba(37,99,235,.3);
    transition: all .25s ease;
    margin-top: auto;   /* dorong ke bawah mengisi tinggi kolom */
}
.dp-btn-edit:hover {
    background: linear-gradient(135deg, #1d4ed8, #4338ca);
    box-shadow: 0 6px 20px rgba(37,99,235,.4);
    transform: translateY(-2px); color: #fff;
}

/* ==========================================================
   BOTTOM: Grid 30% Varian | 70% Ulasan
   ========================================================== */
.dp-bottom {
    display: grid;
    grid-template-columns: 3fr 7fr;
    gap: 1.5rem;
    align-items: start;
}

/* --- Variant Section (30%) --- */
.dp-variants-section {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 1.25rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    /* Sticky agar varian tetap kelihatan saat scroll ulasan panjang */
    position: sticky;
    top: 1rem;
}
.dp-variants-section__header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; }
.dp-variants-section__title {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.95rem; font-weight: 800; color: #111827;
}
.dp-variants-section__title i { color: #2563eb; }
.dp-variants-section__hint { font-size: 0.75rem; color: #9ca3af; font-weight: 500; }

/* Color buttons — flex-wrap otomatis ke bawah jika banyak */
.dp-colors { display: flex; flex-wrap: wrap; gap: 0.625rem; }
.dp-color-btn {
    width: 2rem; height: 2rem;
    border-radius: 50%;
    border: 3px solid #e5e7eb;
    cursor: pointer;
    transition: all .2s ease;
    position: relative;
    box-shadow: 0 2px 5px rgba(0,0,0,.12);
    flex-shrink: 0;
}
.dp-color-btn::after {
    content: attr(data-label);
    position: absolute;
    bottom: calc(100% + 6px); left: 50%;
    transform: translateX(-50%);
    background: #111827; color: #fff;
    font-size: 0.62rem; font-weight: 700;
    padding: 2px 7px; border-radius: 4px;
    white-space: nowrap; opacity: 0;
    pointer-events: none; transition: opacity .15s;
    z-index: 10;
}
.dp-color-btn:hover::after { opacity: 1; }
.dp-color-btn:hover, .dp-color-btn.active {
    border-color: #3b82f6;
    transform: scale(1.18);
    box-shadow: 0 0 0 4px rgba(59,130,246,.22);
}

/* Panels */
.dp-panels-wrap { display: flex; flex-direction: column; gap: 0.75rem; }
.dp-variant-panel { display: none; background: #f8faff; border: 1px solid #e0e7ff; border-radius: 0.75rem; overflow: hidden; }
.dp-variant-panel.show { display: block; animation: dp-fadein .2s ease; }
@keyframes dp-fadein { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }

.dp-variant-panel__head {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.72rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .06em;
    color: #374151; padding: 0.5rem 0.875rem;
    background: #eff2ff; border-bottom: 1px solid #e0e7ff;
}
.dp-color-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; box-shadow: 0 0 0 2px rgba(0,0,0,.08); }

.dp-size-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.5rem;
    padding: 0.75rem;
}
.dp-size-card {
    background: color-mix(in srgb, var(--variant-color, #3b82f6) 8%, #ffffff);
    border: 1px solid color-mix(in srgb, var(--variant-color, #3b82f6) 25%, #ffffff);
    border-radius: 0.625rem;
    padding: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    transition: all .25s ease;
}
.dp-size-card:hover {
    border-color: var(--variant-color, #3b82f6);
    box-shadow: 0 4px 12px color-mix(in srgb, var(--variant-color, #3b82f6) 25%, transparent);
    background: color-mix(in srgb, var(--variant-color, #3b82f6) 14%, #ffffff);
    transform: translateY(-2px);
}
.dp-size-card__top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.dp-size-card__size {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}
.dp-size-label {
    font-size: 0.65rem;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.dp-size-value {
    font-weight: 900;
    color: #111827;
    font-size: 1.1rem;
}
.dp-size-card__price {
    font-weight: 900;
    color: #000000;
    font-size: 0.9rem;
    text-align: left;
}
.dp-stock {
    display: inline-flex; align-items: center; justify-content: center;
    background: #f3f4f6; border: 1px solid #e5e7eb;
    border-radius: 0.35rem; padding: 0.15rem 0.5rem;
    font-size: 0.7rem; font-weight: 700; color: #374151; min-width: 1.75rem;
}

/* --- Reviews Section (70%) --- */
.dp-reviews {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 1.25rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
.dp-reviews__header {
    display: flex; align-items: center; justify-content: space-between;
    padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;
}
.dp-reviews__title  { font-size: 1.1rem; font-weight: 900; color: #111827; margin: 0; }
.dp-reviews__summary { text-align: right; }
.dp-reviews__score  { font-size: 1.4rem; font-weight: 900; color: #111827; }
.dp-reviews__max    { font-size: 0.85rem; color: #9ca3af; font-weight: 500; }
.dp-reviews__count  { display: block; font-size: 0.78rem; color: #6b7280; margin-top: 2px; }

.dp-reviews__empty {
    background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 1rem;
    padding: 2.5rem; display: flex; flex-direction: column;
    align-items: center; gap: 0.5rem; text-align: center;
    color: #d1d5db; font-size: 2.5rem;
}
.dp-reviews__empty-title { font-size: 0.95rem; font-weight: 700; color: #4b5563; margin: 0; }
.dp-reviews__empty-sub   { font-size: 0.82rem; color: #9ca3af; margin: 0; }

.dp-reviews__grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }
.dp-review-card {
    background: #f9fafb; border: 1px solid #f1f5f9; border-radius: 1rem;
    padding: 1.1rem; display: flex; flex-direction: column; gap: 0.75rem;
    transition: all .25s ease;
}
.dp-review-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.06); background: #fff; border-color: #e2e8f0; }
.dp-review-card__top  { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; }
.dp-review-card__user { display: flex; align-items: center; gap: 0.65rem; min-width: 0; }
.dp-review-card__avatar { width: 38px; height: 38px; border-radius: 50%; overflow: hidden; border: 2px solid #e2e8f0; flex-shrink: 0; background: #f1f5f9; }
.dp-review-card__avatar img { width: 100%; height: 100%; object-fit: cover; }
.dp-review-card__name-wrap { display: flex; align-items: center; gap: 0.35rem; }
.dp-review-card__name  { font-size: 0.85rem; font-weight: 800; color: #1e293b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px; }
.dp-verified-badge { color: #10b981; font-size: 0.85rem; display: inline-flex; align-items: center; }
.dp-review-card__time  { font-size: 0.7rem; color: #94a3b8; margin: 0; display: flex; align-items: center; gap: 0.25rem; }
.dp-review-card__rating-box {
    display: inline-flex; align-items: center; gap: 0.35rem;
    background: #fff; padding: 0.2rem 0.5rem; border-radius: 9999px;
    border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03); flex-shrink: 0;
}
.dp-review-card__stars { display: flex; font-size: 0.72rem; gap: 1.5px; }
.dp-review-score-badge { font-size: 0.72rem; font-weight: 800; color: #334155; }
.dp-review-card__text  {
    font-size: 0.82rem; color: #475569; line-height: 1.6;
    background: #fff; border-radius: 0.75rem; padding: 0.75rem 0.875rem;
    border: 1px solid #f1f5f9; margin: 0; font-style: italic;
}

/* Review Photos Thumbnail Box */
.dp-review-photos-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.875rem;
    padding: 0.65rem;
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
}
.dp-review-photos-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.7rem;
    font-weight: 700;
}
.dp-review-photos-count {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: #334155;
}
.dp-review-photos-hint {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    color: #3b82f6;
    font-weight: 600;
    font-size: 0.68rem;
}
.dp-review-photos-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    overflow-x: auto;
    padding-bottom: 2px;
}
.dp-review-photos-list::-webkit-scrollbar {
    height: 4px;
}
.dp-review-photos-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 9999px;
}
.dp-review-photo-item {
    position: relative;
    width: 66px;
    height: 66px;
    flex-shrink: 0;
    border-radius: 0.75rem;
    overflow: hidden;
    border: 1.5px solid #e2e8f0;
    cursor: pointer;
    background: #f8fafc;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.dp-review-photo-item:hover {
    transform: translateY(-2px);
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
}
.dp-review-photo-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.dp-review-photo-item:hover img {
    transform: scale(1.1);
}
.dp-review-photo-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.1rem;
    opacity: 0;
    transition: opacity 0.2s ease;
}
.dp-review-photo-item:hover .dp-review-photo-overlay {
    opacity: 1;
}
.dp-review-photo-idx {
    position: absolute;
    bottom: 3px;
    right: 3px;
    background: rgba(15, 23, 42, 0.75);
    color: #fff;
    font-size: 9px;
    font-weight: 800;
    padding: 0.05rem 0.35rem;
    border-radius: 4px;
    line-height: 1.2;
}

/* Fullscreen Carousel Lightbox Modal */
.dp-carousel-modal {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: rgba(10, 15, 29, 0.96);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    user-select: none;
    -webkit-user-select: none;
    animation: dpModalFadeIn 0.25s ease-out forwards;
}

@keyframes dpModalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.dp-carousel-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    background: linear-gradient(180deg, rgba(0,0,0,0.6) 0%, transparent 100%);
    z-index: 10;
}

.dp-carousel-reviewer-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.dp-carousel-avatar {
    width: 44px;
    height: 44px;
    border-radius: 9999px;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,0.3);
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    background: #1e293b;
}

.dp-carousel-name-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.dp-carousel-user-name {
    color: #ffffff;
    font-weight: 800;
    font-size: 0.95rem;
}

.dp-carousel-stars {
    display: inline-flex;
    gap: 2px;
    font-size: 0.75rem;
    color: #fbbf24;
}

.dp-carousel-meta-row {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 2px;
}

.dp-carousel-meta-sep {
    color: #475569;
}

.dp-carousel-counter {
    color: #38bdf8;
    font-weight: 700;
    background: rgba(56, 189, 248, 0.12);
    padding: 0.1rem 0.5rem;
    border-radius: 9999px;
    border: 1px solid rgba(56, 189, 248, 0.25);
}

.dp-carousel-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.dp-carousel-action-btn,
.dp-carousel-close-btn {
    width: 42px;
    height: 42px;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.dp-carousel-action-btn:hover,
.dp-carousel-close-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: scale(1.08);
}

.dp-carousel-close-btn:hover {
    background: rgba(239, 68, 68, 0.85);
    border-color: rgba(239, 68, 68, 0.9);
}

/* Stage & Slide */
.dp-carousel-stage {
    position: relative;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    overflow: hidden;
}

.dp-carousel-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 52px;
    height: 52px;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 20;
    transition: all 0.2s ease;
    backdrop-filter: blur(8px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

.dp-carousel-nav-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-50%) scale(1.12);
}

.dp-carousel-nav-btn--prev { left: 1.5rem; }
.dp-carousel-nav-btn--next { right: 1.5rem; }

.dp-carousel-slide-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 90vw;
    max-height: 68vh;
    touch-action: pan-y;
}

.dp-carousel-main-img {
    max-width: 88vw;
    max-height: 65vh;
    object-fit: contain;
    border-radius: 1rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
    transition: opacity 0.2s ease, transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.dp-carousel-main-img.slide-anim-next {
    animation: dpSlideNext 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.dp-carousel-main-img.slide-anim-prev {
    animation: dpSlidePrev 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes dpSlideNext {
    from { opacity: 0.3; transform: translateX(40px) scale(0.97); }
    to { opacity: 1; transform: translateX(0) scale(1); }
}

@keyframes dpSlidePrev {
    from { opacity: 0.3; transform: translateX(-40px) scale(0.97); }
    to { opacity: 1; transform: translateX(0) scale(1); }
}

/* Bottom Bar */
.dp-carousel-bottombar {
    padding: 1rem 1.5rem 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.85rem;
    background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, transparent 100%);
    z-index: 10;
}

.dp-carousel-caption {
    max-width: 750px;
    width: 100%;
    background: rgba(30, 41, 59, 0.75);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 1rem;
    padding: 0.75rem 1.25rem;
    color: #e2e8f0;
    font-size: 0.88rem;
    line-height: 1.5;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}

.dp-carousel-quote-icon {
    color: #38bdf8;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.dp-carousel-thumbs-strip {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    overflow-x: auto;
    max-width: 90vw;
    padding: 4px 6px;
}

.dp-carousel-thumbs-strip::-webkit-scrollbar {
    height: 4px;
}
.dp-carousel-thumbs-strip::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 9999px;
}

.dp-carousel-thumb-mini {
    width: 52px;
    height: 52px;
    border-radius: 0.625rem;
    object-fit: cover;
    border: 2px solid transparent;
    opacity: 0.5;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.dp-carousel-thumb-mini:hover {
    opacity: 0.85;
    transform: scale(1.05);
}

.dp-carousel-thumb-mini.active {
    opacity: 1;
    border-color: #38bdf8;
    transform: scale(1.1);
    box-shadow: 0 0 12px rgba(56, 189, 248, 0.6);
}

@media (max-width: 640px) {
    .dp-carousel-topbar { padding: 0.75rem 1rem; }
    .dp-carousel-avatar { width: 36px; height: 36px; }
    .dp-carousel-user-name { font-size: 0.85rem; }
    .dp-carousel-nav-btn { width: 40px; height: 40px; font-size: 1.2rem; }
    .dp-carousel-nav-btn--prev { left: 0.5rem; }
    .dp-carousel-nav-btn--next { right: 0.5rem; }
    .dp-carousel-main-img { max-height: 55vh; max-width: 92vw; }
    .dp-carousel-caption { font-size: 0.78rem; padding: 0.5rem 0.85rem; }
    .dp-carousel-thumb-mini { width: 42px; height: 42px; }
}

/* ==========================================================
   RESPONSIVE
   ========================================================== */
@media (max-width: 1024px) {
    /* Card: 1 kolom pada tablet */
    .dp-card { grid-template-columns: 1fr; }
    .dp-gallery {
        border-right: none;
        border-bottom: 1px solid #e5e7eb;
        /* Flip: thumbnail horizontal di atas, gambar di bawah */
        grid-template-columns: 1fr;
    }
    .dp-thumbs {
        flex-direction: row;
        max-height: none;
        overflow-x: auto;
        overflow-y: hidden;
        padding-right: 0;
        padding-bottom: 4px;
    }
    .dp-thumbs::-webkit-scrollbar { height: 3px; width: auto; }
    .dp-thumb { order: 1; }
    .dp-main-img-wrap { order: 2; aspect-ratio: 16 / 9; height: auto; }

    /* Bottom: 1 kolom pada tablet */
    .dp-bottom { grid-template-columns: 1fr; }
    .dp-variants-section { position: static; }
}

@media (max-width: 640px) {
    .dp-wrapper { padding: 0.875rem; gap: 1rem; }
    .dp-info    { padding: 1.25rem; }
    .dp-gallery { padding: 1rem; gap: 0.5rem; }
    .dp-title   { font-size: 1.25rem; }
    .dp-thumb   { width: 52px; height: 52px; }
    .dp-variants-section, .dp-reviews { padding: 1rem; }
}
</style>


<script>
    function dpShowVariant(warna, btn) {
        const panel = document.getElementById('dp-panel-' + warna);
        const isActive = btn.classList.contains('active');

        if (isActive) {
            // Toggle off: hide this panel, deactivate button
            panel.classList.remove('show');
            btn.classList.remove('active');
        } else {
            // Hide all other panels & deactivate other buttons
            document.querySelectorAll('.dp-variant-panel').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('.dp-color-btn').forEach(el => el.classList.remove('active'));
            // Show this panel
            if (panel) panel.classList.add('show');
            btn.classList.add('active');
        }
    }

    function dpChangeImage(src, tipe, btn) {
        const img = document.getElementById('dp-main-img');
        if (!img) return;
        if (tipe === 'external') {
            img.src = src;
        } else {
            if (!src.startsWith('/') && !src.startsWith('assets/')) {
                img.src = "{{ asset('assets/image/customers/produk/') }}" + '/' + src;
            } else {
                img.src = "{{ asset('') }}" + src;
            }
        }
        // Update active thumb
        document.querySelectorAll('.dp-thumb').forEach(t => t.classList.remove('active'));
        if (btn) btn.classList.add('active');
    }

    // Auto-show first variant & first thumb active on load
    document.addEventListener('DOMContentLoaded', function () {
        const firstBtn = document.querySelector('.dp-color-btn');
        if (firstBtn) firstBtn.click();

        const firstThumb = document.querySelector('.dp-thumb');
        if (firstThumb) firstThumb.classList.add('active');
    });

    function dpOpenModal() {
        const modal = document.getElementById('dp-image-modal');
        const modalImg = document.getElementById('dp-image-modal-img');
        const mainImg = document.getElementById('dp-main-img');
        if (modal && modalImg && mainImg) {
            modal.style.display = "flex";
            modalImg.src = mainImg.src;
        }
    }
    
    function dpCloseModal() {
        const modal = document.getElementById('dp-image-modal');
        if (modal) {
            modal.style.display = "none";
        }
    }

    // ==========================================================
    // REVIEW PHOTO CAROUSEL & LIGHTBOX FULL RESOLUTION
    // ==========================================================
    window.dpReviewsData = @json($userRatings->values());

    let dpCarouselState = {
        isOpen: false,
        reviewIndex: 0,
        photoIndex: 0,
        photos: [],
        review: null,
        touchStartX: 0,
        touchStartY: 0
    };

    function dpOpenReviewCarousel(reviewIndex, photoIndex) {
        const review = window.dpReviewsData[reviewIndex];
        if (!review || !review.fotos_ulasan || review.fotos_ulasan.length === 0) return;

        dpCarouselState.isOpen = true;
        dpCarouselState.reviewIndex = reviewIndex;
        dpCarouselState.photoIndex = photoIndex;
        dpCarouselState.photos = review.fotos_ulasan;
        dpCarouselState.review = review;

        const modal = document.getElementById('dp-review-carousel-modal');
        if (!modal) return;

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Avatar
        const avatarEl = document.getElementById('dp-carousel-user-avatar');
        if (avatarEl) {
            let avatarUrl = "{{ asset('images/illustration/filling-survey.png') }}";
            if (review.foto && review.foto !== 'Belum Di isi' && review.foto !== 'man.png') {
                if (review.foto.startsWith('http://') || review.foto.startsWith('https://')) {
                    avatarUrl = review.foto;
                } else if (review.foto.startsWith('assets/') || review.foto.startsWith('images/')) {
                    avatarUrl = "{{ asset('') }}" + review.foto;
                } else {
                    avatarUrl = "{{ asset('assets/image/customers/profile') }}/" + review.foto;
                }
            }
            avatarEl.src = avatarUrl;
        }

        // Reviewer Name
        const nameEl = document.getElementById('dp-carousel-user-name');
        if (nameEl) nameEl.textContent = review.user_name || 'Pelanggan';

        // Stars
        const starsEl = document.getElementById('dp-carousel-rating-stars');
        if (starsEl) {
            let score = Number(review.rating) || 5;
            if (score > 5) score = score / 2;
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (score >= i) {
                    starsHtml += '<i class="bi bi-star-fill text-amber-400"></i>';
                } else if (score >= i - 0.5) {
                    starsHtml += '<i class="bi bi-star-half text-amber-400"></i>';
                } else {
                    starsHtml += '<i class="bi bi-star text-slate-500"></i>';
                }
            }
            starsEl.innerHTML = starsHtml;
        }

        // Date
        const dateEl = document.getElementById('dp-carousel-date');
        if (dateEl) {
            dateEl.textContent = review.created_at ? new Date(review.created_at).toLocaleDateString('id-ID', {
                day: 'numeric', month: 'short', year: 'numeric'
            }) : 'Ulasan Pembeli';
        }

        // Caption text
        const captionEl = document.getElementById('dp-carousel-text');
        if (captionEl) {
            captionEl.textContent = review.ulasan && review.ulasan.trim() !== '' ? `"${review.ulasan}"` : 'Tidak ada ulasan tertulis';
        }

        dpUpdateCarouselView();
    }

    function dpUpdateCarouselView(direction = null) {
        if (!dpCarouselState.isOpen) return;

        const photos = dpCarouselState.photos;
        const currentIdx = dpCarouselState.photoIndex;
        const currentUrl = photos[currentIdx];

        // Counter
        const counterEl = document.getElementById('dp-carousel-counter');
        if (counterEl) {
            counterEl.textContent = `Foto ${currentIdx + 1} dari ${photos.length}`;
        }

        // Main Image with animation
        const mainImg = document.getElementById('dp-carousel-main-img');
        if (mainImg) {
            mainImg.className = 'dp-carousel-main-img';
            if (direction === 'next') {
                void mainImg.offsetWidth;
                mainImg.classList.add('slide-anim-next');
            } else if (direction === 'prev') {
                void mainImg.offsetWidth;
                mainImg.classList.add('slide-anim-prev');
            }
            mainImg.src = currentUrl;
        }

        // Nav buttons visibility
        const prevBtn = document.getElementById('dp-carousel-btn-prev');
        const nextBtn = document.getElementById('dp-carousel-btn-next');
        if (photos.length <= 1) {
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
        } else {
            if (prevBtn) prevBtn.style.display = 'flex';
            if (nextBtn) nextBtn.style.display = 'flex';
        }

        // Thumbnails Strip
        const stripEl = document.getElementById('dp-carousel-thumbs-strip');
        if (stripEl) {
            if (photos.length <= 1) {
                stripEl.innerHTML = '';
            } else {
                stripEl.innerHTML = photos.map((url, idx) => `
                    <img src="${url}"
                        alt="Thumb ${idx + 1}"
                        onclick="dpJumpToReviewPhoto(${idx})"
                        class="dp-carousel-thumb-mini ${idx === currentIdx ? 'active' : ''}"
                        onerror="this.src='{{ asset('images/illustration/filling-survey.png') }}';" />
                `).join('');

                const activeThumb = stripEl.children[currentIdx];
                if (activeThumb) {
                    activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
            }
        }
    }

    function dpSlideReviewPhoto(step) {
        if (!dpCarouselState.isOpen || dpCarouselState.photos.length <= 1) return;
        const total = dpCarouselState.photos.length;
        dpCarouselState.photoIndex = (dpCarouselState.photoIndex + step + total) % total;
        dpUpdateCarouselView(step > 0 ? 'next' : 'prev');
    }

    function dpJumpToReviewPhoto(index) {
        if (!dpCarouselState.isOpen || index === dpCarouselState.photoIndex) return;
        const dir = index > dpCarouselState.photoIndex ? 'next' : 'prev';
        dpCarouselState.photoIndex = index;
        dpUpdateCarouselView(dir);
    }

    function dpCloseReviewCarousel(e) {
        if (e && e.target && (e.target.closest('.dp-carousel-stage img') || e.target.closest('.dp-carousel-nav-btn') || e.target.closest('.dp-carousel-topbar') || e.target.closest('.dp-carousel-bottombar'))) {
            return;
        }
        dpCarouselState.isOpen = false;
        const modal = document.getElementById('dp-review-carousel-modal');
        if (modal) modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function dpDownloadCurrentPhoto() {
        if (!dpCarouselState.isOpen) return;
        const currentUrl = dpCarouselState.photos[dpCarouselState.photoIndex];
        if (currentUrl) window.open(currentUrl, '_blank');
    }

    // Touch Swipe Event Listeners & Keyboard Navigation
    document.addEventListener('DOMContentLoaded', function () {
        const slideWrap = document.getElementById('dp-carousel-slide-wrap');
        if (slideWrap) {
            slideWrap.addEventListener('touchstart', function (e) {
                if (e.touches && e.touches.length > 0) {
                    dpCarouselState.touchStartX = e.touches[0].clientX;
                    dpCarouselState.touchStartY = e.touches[0].clientY;
                }
            }, { passive: true });

            slideWrap.addEventListener('touchend', function (e) {
                if (!dpCarouselState.isOpen || !e.changedTouches || e.changedTouches.length === 0) return;
                const endX = e.changedTouches[0].clientX;
                const endY = e.changedTouches[0].clientY;
                const deltaX = endX - dpCarouselState.touchStartX;
                const deltaY = endY - dpCarouselState.touchStartY;

                if (Math.abs(deltaX) > 45 && Math.abs(deltaX) > Math.abs(deltaY)) {
                    if (deltaX < 0) {
                        dpSlideReviewPhoto(1);
                    } else {
                        dpSlideReviewPhoto(-1);
                    }
                }
            }, { passive: true });
        }

        // Global Keyboard Event Listener
        document.addEventListener('keydown', function (e) {
            if (!dpCarouselState.isOpen) return;
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                dpSlideReviewPhoto(-1);
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                dpSlideReviewPhoto(1);
            } else if (e.key === 'Escape') {
                e.preventDefault();
                dpCloseReviewCarousel();
            }
        });
    });
</script>
@endsection
