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
                    @foreach ($userRatings as $ratingUlasan)
                        <div class="dp-review-card">
                            <div class="dp-review-card__top">
                                <div class="dp-review-card__user">
                                    <div class="dp-review-card__avatar">
                                        <img src="@userPhoto($ratingUlasan->foto)" alt="{{ $ratingUlasan['user_name'] }}">
                                    </div>
                                    <div>
                                        <p class="dp-review-card__name">{{ $ratingUlasan['user_name'] }}</p>
                                        <p class="dp-review-card__time">{{ \Carbon\Carbon::parse($ratingUlasan->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="dp-review-card__stars">
                                    @for ($i = 0; $i < floor($ratingUlasan['rating'] / 2); $i++)
                                        <i class="bi bi-star-fill"></i>
                                    @endfor
                                    @if ($ratingUlasan['rating'] % 2)
                                        <i class="bi bi-star-half"></i>
                                    @endif
                                </div>
                            </div>
                            <p class="dp-review-card__text">"{{ $ratingUlasan['ulasan'] }}"</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>{{-- /dp-bottom --}}

</div>{{-- /dp-wrapper --}}

{{-- Modal for full resolution image --}}
<div id="dp-image-modal" class="dp-image-modal" onclick="dpCloseModal()">
    <span class="dp-image-modal-close" onclick="dpCloseModal()">&times;</span>
    <img id="dp-image-modal-img" class="dp-image-modal-content" onclick="event.stopPropagation()">
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

.dp-reviews__grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; }
.dp-review-card {
    background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 0.875rem;
    padding: 1rem; display: flex; flex-direction: column; gap: 0.625rem;
    transition: box-shadow .2s;
}
.dp-review-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); background: #fff; }
.dp-review-card__top  { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.625rem; }
.dp-review-card__user { display: flex; align-items: center; gap: 0.5rem; }
.dp-review-card__avatar { width: 34px; height: 34px; border-radius: 50%; overflow: hidden; border: 2px solid #e5e7eb; flex-shrink: 0; background: #f3f4f6; }
.dp-review-card__avatar img { width: 100%; height: 100%; object-fit: cover; }
.dp-review-card__name  { font-size: 0.82rem; font-weight: 700; color: #111827; margin: 0; }
.dp-review-card__time  { font-size: 0.72rem; color: #9ca3af; margin: 0; }
.dp-review-card__stars { display: flex; color: #f59e0b; font-size: 0.72rem; gap: 1px; flex-shrink: 0; }
.dp-review-card__text  { font-size: 0.82rem; color: #6b7280; line-height: 1.6; background: #fff; border-radius: 0.5rem; padding: 0.625rem 0.75rem; border: 1px solid #f3f4f6; margin: 0; }

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
</script>
@endsection
