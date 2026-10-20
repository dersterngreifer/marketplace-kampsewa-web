@extends('layouts.developers.ly-dashboard')

@section('content')
    <style>
        .produk-masonry-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            grid-auto-rows: 8px;
            gap: 18px;
            align-items: start;
            width: 100%;
        }

        .produk-masonry-item {
            grid-row-end: span 1;
            min-width: 0;
        }

        .produk-masonry-content {
            width: 100%;
        }

        .produk-card-image {
            display: block;
            width: 100%;
            height: auto;
        }

        @media (min-width: 1280px) {
            .produk-masonry-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
        }

        @media (min-width: 1536px) {
            .produk-masonry-grid {
                grid-template-columns: repeat(auto-fit, minmax(265px, 1fr));
            }
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #F1F5F9;
            border-radius: 999px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 999px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #5038ED;
        }

        .swal2-popup.kampsewa-swal {
            border-radius: 28px !important;
            padding: 28px !important;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.22) !important;
        }

        .swal2-title.kampsewa-title {
            color: #19191B !important;
            font-size: 22px !important;
            font-weight: 800 !important;
        }

        .swal2-html-container.kampsewa-text {
            color: #64748B !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            line-height: 1.6 !important;
        }

        .kampsewa-confirm-button {
            border-radius: 16px !important;
            background: #5038ED !important;
            color: #ffffff !important;
            padding: 12px 22px !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            box-shadow: 0 12px 24px rgba(80, 56, 237, 0.25) !important;
        }

        .kampsewa-confirm-button:hover {
            background: #412CCB !important;
        }

        .kampsewa-cancel-button {
            border-radius: 16px !important;
            background: #F1F5F9 !important;
            color: #475569 !important;
            padding: 12px 22px !important;
            font-size: 14px !important;
            font-weight: 800 !important;
        }

        .kampsewa-cancel-button:hover {
            background: #E2E8F0 !important;
        }

        .swal2-icon.swal2-warning,
        .swal2-icon.swal2-error,
        .swal2-icon.swal2-success {
            border-color: #5038ED !important;
            color: #5038ED !important;
        }

        .swal2-success-line-tip,
        .swal2-success-line-long {
            background-color: #5038ED !important;
        }

        .swal2-success-ring {
            border-color: rgba(80, 56, 237, 0.25) !important;
        }
    </style>

    <div class="min-h-screen w-full bg-[#F6F7FB] px-4 py-5 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div
            class="mb-6 overflow-hidden rounded-[30px] bg-gradient-to-br from-[#19191B] via-[#24243A] to-[#5038ED] p-5 text-white shadow-lg sm:p-7"
        >
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <a
                        href="{{ route('detail-pengguna.index', ['user' => $user_id]) }}"
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-bold text-white backdrop-blur transition hover:bg-white/25"
                    >
                        <i class="fi fi-rr-arrow-small-left text-base"></i>
                        <span>Kembali</span>
                    </a>

                    <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">
                        Barang Disewakan Oleh {{ $name }}
                    </h1>

                    <p class="mt-2 max-w-3xl text-sm leading-6 text-white/75">
                        Menampilkan semua barang yang disediakan atau disewakan oleh customer
                        <b class="text-white">{{ $name }}</b>.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 sm:flex sm:items-center">
                    <div class="rounded-2xl bg-white/15 px-4 py-3 backdrop-blur">
                        <p class="text-xs text-white/70">Total Produk</p>
                        <p class="mt-1 text-xl font-extrabold">{{ $get_data_produk->count() }}</p>
                    </div>

                    <div class="rounded-2xl bg-rose-500/30 border border-rose-300/40 px-4 py-3 backdrop-blur">
                        <p class="text-xs text-rose-200">Ditangguhkan</p>
                        <p class="mt-1 text-xl font-extrabold text-rose-200">{{ $total_produk_suspend ?? 0 }}</p>
                    </div>

                    <div class="rounded-2xl bg-white/15 px-4 py-3 backdrop-blur">
                        <p class="text-xs text-white/70">Kategori</p>
                        <p class="mt-1 text-xl font-extrabold">{{ count($get_kategori) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Status Filter Tabs --}}
        <div class="mb-4 flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('detail-pengguna.produk-disewakan', array_merge(request()->query(), ['user' => $user_id, 'filter_status' => 'semua'])) }}"
                class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-xs font-black transition {{ ($filter_status ?? 'semua') === 'semua' ? 'bg-[#5038ED] text-white shadow-md shadow-[#5038ED]/25' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                <span>Semua Produk</span>
                <span class="rounded-full bg-black/10 px-2 py-0.5 text-[10px]">{{ ($total_produk_tersedia ?? 0) + ($total_produk_suspend ?? 0) }}</span>
            </a>

            <a href="{{ route('detail-pengguna.produk-disewakan', array_merge(request()->query(), ['user' => $user_id, 'filter_status' => 'suspend'])) }}"
                class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-xs font-black transition {{ ($filter_status ?? '') === 'suspend' || ($filter_status ?? '') === 'Ditangguhkan' ? 'bg-rose-600 text-white shadow-md shadow-rose-600/25 ring-2 ring-rose-300' : 'bg-white text-rose-600 border border-rose-200 hover:bg-rose-50' }}">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-rose-500"></span>
                </span>
                <span>Khusus Ditangguhkan (Suspend)</span>
                <span class="rounded-full bg-rose-100 text-rose-700 px-2 py-0.5 text-[10px] font-black">{{ $total_produk_suspend ?? 0 }}</span>
            </a>

            <a href="{{ route('detail-pengguna.produk-disewakan', array_merge(request()->query(), ['user' => $user_id, 'filter_status' => 'tersedia'])) }}"
                class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-xs font-black transition {{ ($filter_status ?? '') === 'tersedia' || ($filter_status ?? '') === 'Tersedia' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25' : 'bg-white text-emerald-600 border border-emerald-200 hover:bg-emerald-50' }}">
                <span>Khusus Tersedia (Aktif)</span>
                <span class="rounded-full bg-emerald-100 text-emerald-700 px-2 py-0.5 text-[10px] font-black">{{ $total_produk_tersedia ?? 0 }}</span>
            </a>
        </div>

        {{-- Filter, Search, Action --}}
        <div class="mb-6 rounded-[28px] border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <form
                id="productFilterForm"
                method="GET"
                action="{{ route('detail-pengguna.produk-disewakan', $user_id) }}"
                class="grid grid-cols-1 gap-3 xl:grid-cols-[auto_200px_220px_1fr_auto_auto] xl:items-center"
            >
                {{-- Check all --}}
                <label
                    class="flex h-12 cursor-pointer items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                >
                    <span class="relative flex items-center">
                        <input
                            type="checkbox"
                            id="check-all"
                            class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-slate-300 bg-white transition-all checked:border-[#5038ED] checked:bg-[#5038ED]"
                        >

                        <span
                            class="pointer-events-none absolute left-1/2 top-1/2 hidden -translate-x-1/2 -translate-y-1/2 text-white peer-checked:block"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </span>
                    </span>

                    <span>Pilih Semua</span>
                </label>

                {{-- Filter Category --}}
                <div class="relative">
                    <select
                        name="filter_category"
                        id="filter-category"
                        class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50 px-4 pr-10 text-sm font-bold text-[#19191B] outline-none transition focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10"
                    >
                        <option value="Semua Barang" {{ request('filter_category') == 'Semua Barang' ? 'selected' : '' }}>
                            Semua Barang
                        </option>

                        @foreach ($get_kategori as $kategori)
                            <option value="{{ $kategori }}" {{ request('filter_category') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                        <i class="fi fi-rr-angle-small-down"></i>
                    </div>
                </div>

                {{-- Filter Status (Semua / Ditangguhkan / Tersedia) --}}
                <div class="relative">
                    <select
                        name="filter_status"
                        id="filter-status"
                        onchange="document.getElementById('productFilterForm').submit()"
                        class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50 px-4 pr-10 text-sm font-bold text-[#19191B] outline-none transition focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10"
                    >
                        <option value="semua" {{ ($filter_status ?? 'semua') == 'semua' ? 'selected' : '' }}>
                            Semua Status
                        </option>
                        <option value="suspend" {{ ($filter_status ?? '') == 'suspend' || ($filter_status ?? '') == 'Ditangguhkan' ? 'selected' : '' }}>
                            ⛔ Ditangguhkan ({{ $total_produk_suspend ?? 0 }})
                        </option>
                        <option value="tersedia" {{ ($filter_status ?? '') == 'tersedia' || ($filter_status ?? '') == 'Tersedia' ? 'selected' : '' }}>
                            ✅ Tersedia ({{ $total_produk_tersedia ?? 0 }})
                        </option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                        <i class="fi fi-rr-angle-small-down"></i>
                    </div>
                </div>

                {{-- Search --}}
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                        <i class="fi fi-rr-search text-sm"></i>
                    </div>

                    <input
                        type="text"
                        name="cari_barang"
                        id="searchProduct"
                        value="{{ $cari_barang }}"
                        placeholder="Cari nama barang..."
                        autocomplete="off"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-medium text-[#19191B] outline-none transition placeholder:text-slate-400 focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10"
                    >
                </div>

                {{-- Delete Selected --}}
                <button
                    type="button"
                    id="deleteSelectedButton"
                    disabled
                    class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-red-500 px-5 text-sm font-bold text-white shadow-md shadow-red-500/20 transition hover:-translate-y-0.5 hover:bg-red-600 active:scale-[0.98] disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none disabled:hover:translate-y-0"
                >
                    <i class="fi fi-rr-trash text-sm"></i>
                    <span>Hapus</span>
                    <span id="selectedCount" class="hidden rounded-full bg-white/20 px-2 py-0.5 text-xs">0</span>
                </button>

                {{-- Reset --}}
                <a
                    href="{{ route('detail-pengguna.produk-disewakan', $user_id) }}"
                    class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 active:scale-[0.98]"
                >
                    <i class="fi fi-rr-refresh text-sm"></i>
                    <span>Reset</span>
                </a>
            </form>
        </div>

        {{-- Product List --}}
        @if ($get_data_produk->count() == 0)
            <div class="rounded-[30px] border border-dashed border-slate-300 bg-white p-8 shadow-sm">
                <div class="flex flex-col items-center justify-center gap-5 text-center lg:flex-row lg:text-left">
                    <img
                        class="w-[240px] max-w-full object-contain"
                        src="{{ asset('images/illustration/filling-survey.png') }}"
                        alt="Tidak ada produk"
                    >

                    <div>
                        <p class="text-4xl font-black text-[#19191B]">
                            OOPS!
                        </p>

                        <p class="mt-2 text-base font-semibold leading-7 text-slate-500">
                            Sepertinya {{ $name }} tidak memiliki produk yang sedang disewakan.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div id="produkMasonryGrid" class="produk-masonry-grid">
                @foreach ($get_data_produk as $item)
                    <article class="produk-masonry-item">
                        <div
                            class="produk-masonry-content group overflow-hidden rounded-[26px] border {{ $item->status === 'Ditangguhkan' ? 'border-rose-300 ring-2 ring-rose-100 bg-rose-50/20' : 'border-slate-200 bg-white' }} shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#5038ED]/30 hover:shadow-xl hover:shadow-slate-200/80"
                        >
                            {{-- Image --}}
                            <a
                                href="{{ route('detail-pengguna.detail-produk-disewakan', ['user' => $user_id, 'namaproduk' => $item->nama]) }}"
                                class="relative block overflow-hidden bg-slate-100"
                            >
                                <img
                                    class="produk-masonry-image produk-card-image transition duration-500 group-hover:scale-[1.03]"
                                    src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($item) }}"
                                    alt="{{ $item->nama }}"
                                    loading="lazy"
                                    onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22400%22%20height%3D%22300%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20300%22%3E%3Crect%20fill%3D%22%23f1f5f9%22%20width%3D%22400%22%20height%3D%22300%22%2F%3E%3Ctext%20fill%3D%22%2394a3b8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%3EKampSewa%20Produk%3C%2Ftext%3E%3C%2Fsvg%3E';"
                                >

                                {{-- Status Badges on Image --}}
                                @if ($item->status === 'Ditangguhkan')
                                    <div class="absolute left-3 top-3 z-10 flex flex-col gap-1.5">
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-600/90 px-3 py-1 text-xs font-black text-white shadow-md backdrop-blur">
                                            <span class="relative flex h-2 w-2">
                                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-rose-300 opacity-75"></span>
                                                <span class="relative inline-flex h-2 w-2 rounded-full bg-white"></span>
                                            </span>
                                            Ditangguhkan
                                        </span>
                                        @if ($item->latestSuspend && $item->latestSuspend->status_revisi === 'sudah_diperbaiki')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-600/95 px-2.5 py-0.5 text-[10px] font-black text-white shadow-md backdrop-blur ring-1 ring-emerald-300">
                                                <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                                                Direvisi Mitra
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div
                                    class="absolute inset-x-0 bottom-0 flex translate-y-full items-center justify-between bg-gradient-to-t from-black/85 via-black/40 to-transparent px-4 pb-4 pt-12 transition duration-300 group-hover:translate-y-0"
                                >
                                    <span class="text-sm font-bold text-white">
                                        Lihat Detail
                                    </span>

                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-[#5038ED]">
                                        <i class="fi fi-rr-angle-small-right text-xl"></i>
                                    </span>
                                </div>
                            </a>

                            {{-- Body --}}
                            <div class="p-4">
                                <div class="mb-3 flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <h2 class="line-clamp-2 text-base font-extrabold capitalize leading-6 text-[#19191B]">
                                            {{ $item->nama ?: 'Nama produk belum tersedia' }}
                                        </h2>

                                        <p class="mt-1 line-clamp-2 text-sm font-medium leading-6 text-slate-500">
                                            {{ $item->deskripsi ?: 'Produk ini belum memiliki deskripsi.' }}
                                        </p>
                                    </div>

                                    @if ($item->status === 'Ditangguhkan')
                                        <span class="shrink-0 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">
                                            Suspend
                                        </span>
                                    @else
                                        <span class="shrink-0 rounded-full bg-[#5038ED]/10 px-3 py-1 text-xs font-bold text-[#5038ED]">
                                            Tersedia
                                        </span>
                                    @endif
                                </div>

                                {{-- Alasan Suspend Box if Suspended --}}
                                @if ($item->status === 'Ditangguhkan')
                                    <div class="mb-3 rounded-2xl border border-rose-200 bg-rose-50/80 p-3 text-xs">
                                        <div class="flex items-center gap-1.5 font-bold text-rose-800">
                                            <i class="fi fi-rr-exclamation text-xs text-rose-600"></i>
                                            <span>Alasan Penangguhan:</span>
                                        </div>
                                        <p class="mt-1 font-semibold text-rose-700 line-clamp-2">
                                            {{ $item->latestSuspend->alasan ?? 'Pelanggaran syarat & ketentuan produk' }}
                                        </p>
                                        @if ($item->latestSuspend && $item->latestSuspend->status_revisi === 'sudah_diperbaiki')
                                            <p class="mt-1.5 text-[10px] font-extrabold text-emerald-700 flex items-center gap-1">
                                                <i class="fi fi-rr-check text-xs"></i> Mitra telah memperbaiki produk ini.
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                <div class="rounded-2xl bg-slate-50 p-3">
                                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                        Mulai Dari
                                    </p>

                                    <p class="mt-1 text-lg font-extrabold text-[#19191B]">
                                        Rp {{ number_format($item->harga_sewa_terkecil ?? 0, 0, ',', '.') }}
                                        <span class="text-xs font-bold text-slate-400">/Hari</span>
                                    </p>
                                </div>

                                {{-- Footer Action --}}
                                <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-3.5">
                                    <label
                                        class="inline-flex cursor-pointer items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 transition hover:bg-slate-50"
                                    >
                                        <span class="relative flex items-center">
                                            <input
                                                type="checkbox"
                                                data-id="{{ $item->id_produk }}"
                                                class="product-checkbox peer h-4 w-4 cursor-pointer appearance-none rounded-lg border-2 border-slate-300 bg-white transition-all checked:border-[#5038ED] checked:bg-[#5038ED]"
                                            >

                                            <span
                                                class="pointer-events-none absolute left-1/2 top-1/2 hidden -translate-x-1/2 -translate-y-1/2 text-white peer-checked:block"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </span>
                                        </span>
                                        <span class="text-[11px] font-bold text-slate-500">Pilih</span>
                                    </label>

                                    <div class="flex items-center gap-1.5">
                                        @if ($item->status === 'Ditangguhkan')
                                            <button type="button"
                                                data-product-id="{{ $item->id_produk }}"
                                                data-product-name="{{ $item->nama }}"
                                                onclick="confirmPulihkanFromBtn(this)"
                                                class="inline-flex h-9 items-center justify-center gap-1 rounded-xl bg-emerald-50 border border-emerald-200 px-3 text-xs font-black text-emerald-700 hover:bg-emerald-600 hover:text-white transition shadow-xs cursor-pointer">
                                                <i class="fi fi-rr-check text-xs"></i>
                                                <span>Pulihkan</span>
                                            </button>
                                        @else
                                            <button type="button"
                                                data-product-id="{{ $item->id_produk }}"
                                                data-product-name="{{ $item->nama }}"
                                                onclick="openSuspendModalFromBtn(this)"
                                                class="inline-flex h-9 items-center justify-center gap-1 rounded-xl bg-rose-50 border border-rose-200 px-3 text-xs font-black text-rose-600 hover:bg-rose-600 hover:text-white transition shadow-xs cursor-pointer">
                                                <i class="fi fi-rr-ban text-xs"></i>
                                                <span>Suspend</span>
                                            </button>
                                        @endif

                                        <a
                                            href="{{ route('detail-pengguna.detail-produk-disewakan', ['user' => $user_id, 'namaproduk' => $item->nama]) }}"
                                            class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl bg-[#5038ED] px-3.5 text-xs font-bold text-white shadow-md shadow-[#5038ED]/20 transition hover:bg-[#412CCB]"
                                        >
                                            <span>Detail</span>
                                            <i class="fi fi-rr-angle-small-right text-base"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Modal Suspend Produk --}}
    <div id="modalSuspendProduk" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 overflow-y-auto">
        <div class="w-full max-w-lg overflow-hidden rounded-3xl border border-rose-200 bg-white shadow-2xl animate-fadeIn">
            <div class="flex items-center justify-between border-b border-rose-100 bg-gradient-to-r from-rose-50 via-white to-white px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-rose-600 text-white shadow-md shadow-rose-600/30">
                        <i class="fi fi-rr-ban text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Tangguhkan / Suspend Produk</h3>
                        <p class="text-xs font-medium text-slate-500">Antisipasi foto penipuan, berbau seksual, atau pelanggaran</p>
                    </div>
                </div>
                <button type="button" onclick="closeSuspendModal()"
                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 cursor-pointer transition">
                    <i class="fi fi-rr-cross-small text-lg"></i>
                </button>
            </div>

            <form id="formSuspendProduk" method="POST" action="" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-1">Produk Yang Di-Suspend</label>
                    <p id="suspendProductName" class="text-sm font-extrabold text-slate-800 bg-slate-50 p-3 rounded-2xl border border-slate-200 line-clamp-2"></p>
                </div>

                <div>
                    <label for="alasan_suspend" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">
                        Alasan Penangguhan <span class="text-rose-500">*</span>
                    </label>
                    <select id="alasan_suspend" name="alasan" required onchange="handleAlasanChange(this.value)"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs sm:text-sm font-bold text-slate-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-500/20">
                        <option value="">-- Pilih Alasan Utama --</option>
                        <option value="Foto produk terindikasi penipuan / palsu / scam">Foto produk terindikasi penipuan / palsu / scam</option>
                        <option value="Foto produk mengandung unsur seksual / pornografi / tidak senonoh">Foto produk mengandung unsur seksual / pornografi / tidak senonoh</option>
                        <option value="Foto atau deskripsi melanggar norma / kekerasan / SARA">Foto atau deskripsi melanggar norma / kekerasan / SARA</option>
                        <option value="Informasi produk tidak sesuai dengan barang aslinya">Informasi produk tidak sesuai dengan barang aslinya</option>
                        <option value="Produk melanggar syarat dan ketentuan KampSewa">Produk melanggar syarat dan ketentuan KampSewa</option>
                        <option value="Lainnya">Lainnya (Tulis manual di catatan)</option>
                    </select>
                </div>

                <div>
                    <label for="catatan_suspend" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">
                        Catatan &amp; Instruksi Perbaikan Untuk Mitra <span class="text-slate-400 font-medium">(Opsional)</span>
                    </label>
                    <textarea id="catatan_suspend" name="catatan" rows="3"
                        placeholder="Tuliskan catatan detail bagian mana yang harus diperbaiki oleh pemilik produk (misal: ganti foto utama dengan foto asli)..."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs sm:text-sm font-medium text-slate-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-500/20"></textarea>
                </div>

                <div class="rounded-2xl bg-amber-50 border border-amber-200 p-3 flex items-start gap-2.5 text-xs text-amber-800">
                    <i class="fi fi-rr-info text-amber-600 mt-0.5 text-sm"></i>
                    <span>Notifikasi penangguhan dan instruksi perbaikan akan otomatis dikirimkan ke akun mitra pemilik produk secara real-time.</span>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeSuspendModal()"
                        class="rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 hover:bg-rose-700 px-5 py-2.5 text-xs font-black text-white shadow-md shadow-rose-600/30 transition hover:-translate-y-0.5 cursor-pointer">
                        <i class="fi fi-rr-ban text-xs"></i>
                        <span>Suspend Produk</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Form Pulihkan Produk Hidden --}}
    <form id="formPulihkanProduk" method="POST" action="" class="hidden">
        @csrf
        @method('PUT')
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const KampSewaSwal = Swal.mixin({
            customClass: {
                popup: 'kampsewa-swal',
                title: 'kampsewa-title',
                htmlContainer: 'kampsewa-text',
                confirmButton: 'kampsewa-confirm-button',
                cancelButton: 'kampsewa-cancel-button'
            },
            buttonsStyling: false
        });

        function openSuspendModalFromBtn(btn) {
            const idProduk = btn.getAttribute('data-product-id');
            const namaProduk = btn.getAttribute('data-product-name');
            openSuspendModal(idProduk, namaProduk);
        }

        function confirmPulihkanFromBtn(btn) {
            const idProduk = btn.getAttribute('data-product-id');
            const namaProduk = btn.getAttribute('data-product-name');
            confirmPulihkanProduk(idProduk, namaProduk);
        }

        function openSuspendModal(idProduk, namaProduk) {
            const modal = document.getElementById('modalSuspendProduk');
            const form = document.getElementById('formSuspendProduk');
            const nameEl = document.getElementById('suspendProductName');
            
            if (!modal || !form) return;

            form.action = "{{ url('/developer/dashboard/kelola-pengguna/produk') }}/" + idProduk + "/suspend";
            if (nameEl) nameEl.textContent = namaProduk || 'Produk';
            const alasanEl = document.getElementById('alasan_suspend');
            const catatanEl = document.getElementById('catatan_suspend');
            if (alasanEl) alasanEl.value = '';
            if (catatanEl) catatanEl.value = '';
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeSuspendModal() {
            const modal = document.getElementById('modalSuspendProduk');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        function handleAlasanChange(val) {
            const catatan = document.getElementById('catatan_suspend');
            if (!catatan) return;
            if (val === 'Lainnya') {
                catatan.required = true;
                catatan.placeholder = 'Wajib menuliskan rincian alasan penangguhan di sini...';
                catatan.focus();
            } else {
                catatan.required = false;
                catatan.placeholder = 'Tuliskan catatan detail bagian mana yang harus diperbaiki oleh pemilik produk (misal: ganti foto utama dengan foto asli)...';
            }
        }

        function confirmPulihkanProduk(idProduk, namaProduk) {
            const namaText = namaProduk ? `"${namaProduk}"` : 'produk ini';

            Swal.fire({
                title: 'Pulihkan Produk Ini?',
                html: `
                    <div class="text-left text-sm text-slate-600 space-y-3 mt-2">
                        <p>Apakah Anda yakin ingin memulihkan dan mengaktifkan kembali produk <span class="font-bold text-slate-900">${namaText}</span>?</p>
                        <div class="p-3.5 bg-emerald-50 rounded-2xl border border-emerald-200 text-xs text-emerald-800 flex items-start gap-2.5">
                            <i class="fi fi-rr-info text-emerald-600 shrink-0 mt-0.5 text-sm"></i>
                            <span>Produk akan kembali berstatus <b>Tersedia</b> dan langsung tampil di marketplace sehingga dapat disewa kembali oleh seluruh customer.</span>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fi fi-rr-check mr-1.5"></i> Ya, Pulihkan Sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[28px] p-6 shadow-2xl border border-slate-100',
                    title: 'text-[20px] font-black text-slate-900 pt-2',
                    actions: 'gap-3 mt-4 w-full flex justify-end',
                    confirmButton: 'inline-flex items-center justify-center gap-1.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-600/30 transition hover:-translate-y-0.5 cursor-pointer',
                    cancelButton: 'rounded-2xl bg-slate-100 hover:bg-slate-200 px-6 py-3 text-sm font-bold text-slate-600 transition cursor-pointer',
                },
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('formPulihkanProduk');
                    form.action = "{{ url('/developer/dashboard/kelola-pengguna/produk') }}/" + idProduk + "/pulihkan";
                    form.submit();
                }
            });
        }

        // Expose functions globally on window
        window.openSuspendModalFromBtn = openSuspendModalFromBtn;
        window.confirmPulihkanFromBtn = confirmPulihkanFromBtn;
        window.openSuspendModal = openSuspendModal;
        window.closeSuspendModal = closeSuspendModal;
        window.handleAlasanChange = handleAlasanChange;
        window.confirmPulihkanProduk = confirmPulihkanProduk;

        // Close modal when clicking backdrop or pressing Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSuspendModal();
        });
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('modalSuspendProduk');
            if (modal && e.target === modal) closeSuspendModal();
        });

        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('check-all');
            const productCheckboxes = document.querySelectorAll('.product-checkbox');
            const deleteSelectedButton = document.getElementById('deleteSelectedButton');
            const selectedCount = document.getElementById('selectedCount');
            const filterForm = document.getElementById('productFilterForm');
            const searchProduct = document.getElementById('searchProduct');
            const filterCategory = document.getElementById('filter-category');
            const masonryGrid = document.getElementById('produkMasonryGrid');

            function resizeMasonryItem(item) {
                if (!masonryGrid || !item) return;

                const rowHeight = parseInt(window.getComputedStyle(masonryGrid).getPropertyValue('grid-auto-rows'));
                const rowGap = parseInt(window.getComputedStyle(masonryGrid).getPropertyValue('row-gap'));
                const content = item.querySelector('.produk-masonry-content');

                if (!content || !rowHeight) return;

                const contentHeight = content.getBoundingClientRect().height;
                const rowSpan = Math.ceil((contentHeight + rowGap) / (rowHeight + rowGap));

                item.style.gridRowEnd = 'span ' + rowSpan;
            }

            function resizeAllMasonryItems() {
                document.querySelectorAll('.produk-masonry-item').forEach(function (item) {
                    resizeMasonryItem(item);
                });
            }

            document.querySelectorAll('.produk-masonry-image').forEach(function (image) {
                image.addEventListener('load', function () {
                    resizeAllMasonryItems();
                });

                image.addEventListener('error', function () {
                    resizeAllMasonryItems();
                });
            });

            window.addEventListener('load', function () {
                resizeAllMasonryItems();
            });

            window.addEventListener('resize', function () {
                resizeAllMasonryItems();
            });

            setTimeout(resizeAllMasonryItems, 300);
            setTimeout(resizeAllMasonryItems, 800);

            function updateDeleteButton() {
                const checkedItems = document.querySelectorAll('.product-checkbox:checked');
                const count = checkedItems.length;

                if (deleteSelectedButton) {
                    deleteSelectedButton.disabled = count === 0;
                }

                if (selectedCount) {
                    selectedCount.textContent = count;

                    if (count > 0) {
                        selectedCount.classList.remove('hidden');
                    } else {
                        selectedCount.classList.add('hidden');
                    }
                }

                if (checkAll) {
                    checkAll.checked = count > 0 && count === productCheckboxes.length;
                    checkAll.indeterminate = count > 0 && count < productCheckboxes.length;
                }
            }

            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    productCheckboxes.forEach(function (checkbox) {
                        checkbox.checked = checkAll.checked;
                    });

                    updateDeleteButton();
                });
            }

            productCheckboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', updateDeleteButton);
            });

            updateDeleteButton();

            let searchTimer = null;

            if (searchProduct && filterForm) {
                searchProduct.addEventListener('input', function () {
                    clearTimeout(searchTimer);

                    searchTimer = setTimeout(function () {
                        filterForm.submit();
                    }, 500);
                });
            }

            if (filterCategory && filterForm) {
                filterCategory.addEventListener('change', function () {
                    filterForm.submit();
                });
            }

            if (deleteSelectedButton) {
                deleteSelectedButton.addEventListener('click', function () {
                    const selectedIds = [];

                    document.querySelectorAll('.product-checkbox:checked').forEach(function (checkbox) {
                        selectedIds.push(checkbox.dataset.id);
                    });

                    if (selectedIds.length === 0) {
                        KampSewaSwal.fire({
                            icon: 'warning',
                            title: 'Belum ada produk dipilih',
                            text: 'Pilih minimal satu produk terlebih dahulu.',
                            confirmButtonText: 'Oke'
                        });

                        return;
                    }

                    KampSewaSwal.fire({
                        icon: 'warning',
                        title: 'Hapus produk terpilih?',
                        text: 'Yakin ingin menghapus ' + selectedIds.length + ' produk? Data yang dihapus tidak dapat dikembalikan.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (!result.isConfirmed) return;

                        fetch("{{ route('delete-selected-products') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                ids: selectedIds
                            })
                        })
                        .then(async function (response) {
                            if (!response.ok) {
                                throw new Error(await response.text());
                            }

                            KampSewaSwal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Produk terpilih berhasil dihapus.',
                                confirmButtonText: 'Oke'
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(function () {
                            KampSewaSwal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Produk gagal dihapus. Periksa kembali route atau controller delete selected products.',
                                confirmButtonText: 'Oke'
                            });
                        });
                    });
                });
            }
        });
    </script>
@endsection