@extends('layouts.developers.ly-dashboard')

@section('content')
<div class="w-full px-4 py-5 sm:px-5 lg:px-6">

    {{-- Breadcrumb / Back --}}
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('detail-pengguna.produk-disewakan', $user_id) }}"
            class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm hover:bg-slate-50 transition active:scale-95">
            <i class="fi fi-rr-arrow-small-left flex text-lg"></i>
            Kembali ke Daftar Produk
        </a>
        <div class="flex items-center gap-2 text-sm text-slate-400">
            <span>/</span>
            <span class="font-semibold text-slate-600">{{ $name }}</span>
            <span>/</span>
            <span class="font-semibold text-violet-600 line-clamp-1">{{ $produk->nama }}</span>
        </div>
    </div>

    <div class="flex flex-col gap-6 xl:flex-row">

        {{-- KIRI: Galeri Foto --}}
        <div class="w-full xl:w-[360px] shrink-0">
            <div class="overflow-hidden rounded-[28px] border border-slate-100 bg-white shadow-sm">
                {{-- Foto utama --}}
                <div class="relative overflow-hidden rounded-t-[28px] bg-slate-100">
                    <img id="mainPhoto"
                        class="h-[320px] w-full object-cover transition duration-300"
                        src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($produk) }}"
                        alt="{{ $produk->nama }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-t-[28px]"></div>
                    {{-- Status badge --}}
                    <div class="absolute left-4 top-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold shadow
                            {{ $produk->status === 'Tersedia' ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-white' }}">
                            <span class="relative flex h-1.5 w-1.5">
                                @if ($produk->status === 'Tersedia')
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"></span>
                                @endif
                                <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-white"></span>
                            </span>
                            {{ $produk->status }}
                        </span>
                    </div>
                </div>
                {{-- Thumbnail grid --}}
                @if ($produk->foto->count() > 0)
                <div class="grid grid-cols-4 gap-2 p-3">
                    @foreach ($produk->foto as $p)
                    <button type="button"
                        onclick="document.getElementById('mainPhoto').src='{{ \App\Helpers\PhotoHelper::getPhotoUrl($p->url_foto, $p->tipe_sumber) }}'"
                        class="overflow-hidden rounded-xl border-2 border-transparent hover:border-violet-400 transition">
                        <img class="h-16 w-full object-cover"
                            src="{{ \App\Helpers\PhotoHelper::getPhotoUrl($p->url_foto, $p->tipe_sumber) }}" alt="">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Mini Stats --}}
            <div class="mt-4 grid grid-cols-3 gap-3">
                <div class="rounded-[20px] border border-slate-100 bg-white p-4 shadow-sm text-center">
                    <p class="text-2xl font-black text-violet-600">{{ $stok_produk }}</p>
                    <p class="mt-0.5 text-[11px] font-bold uppercase tracking-wide text-slate-400">Stok</p>
                </div>
                <div class="rounded-[20px] border border-slate-100 bg-white p-4 shadow-sm text-center">
                    <p class="text-2xl font-black text-emerald-600">{{ $total_disewa }}</p>
                    <p class="mt-0.5 text-[11px] font-bold uppercase tracking-wide text-slate-400">Disewa</p>
                </div>
                <div class="rounded-[20px] border border-slate-100 bg-white p-4 shadow-sm text-center">
                    <p class="text-2xl font-black text-amber-500">{{ $rata_rata_rating > 0 ? $rata_rata_rating : '—' }}</p>
                    <p class="mt-0.5 text-[11px] font-bold uppercase tracking-wide text-slate-400">Rating</p>
                </div>
            </div>
        </div>

        {{-- KANAN: Detail Produk --}}
        <div class="flex flex-1 flex-col gap-5 min-w-0">

            {{-- Header --}}
            <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-400">{{ $produk->kategori }}</p>
                        <h1 class="text-2xl font-black text-slate-900">{{ $produk->nama }}</h1>
                        <div class="mt-2 flex flex-wrap items-center gap-3">
                            {{-- Rating --}}
                            @if ($total_rating > 0)
                            <div class="flex items-center gap-1.5">
                                @for ($s = 1; $s <= 5; $s++)
                                    <i class="fi fi-{{ $s <= round($rata_rata_rating) ? 'ss' : 'rr' }}-star flex text-[13px] {{ $s <= round($rata_rata_rating) ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                @endfor
                                <span class="text-sm font-bold text-slate-700">{{ $rata_rata_rating }}</span>
                                <span class="text-sm text-slate-400">({{ $total_rating }} ulasan)</span>
                            </div>
                            @else
                            <span class="text-sm text-slate-400">Belum ada rating</span>
                            @endif
                            <span class="text-slate-300">•</span>
                            <span class="text-sm font-semibold text-slate-500">{{ $total_disewa }}x disewa</span>
                        </div>
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-xs font-bold text-slate-400">Mulai dari</p>
                        <p class="text-3xl font-black text-slate-900">
                            Rp {{ number_format($harga_sewa_terkecil ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="text-xs font-bold text-slate-400">/hari</p>
                    </div>
                </div>

                <div class="mt-5 border-t border-slate-100 pt-5">
                    <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi Produk</p>
                    <p class="text-sm leading-relaxed text-slate-600">
                        {{ $produk->deskripsi ?: 'Produk ini belum memiliki deskripsi.' }}
                    </p>
                </div>
            </div>

            {{-- Info Mitra --}}
            <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-sm">
                <p class="mb-4 text-sm font-black text-slate-700">Informasi Mitra Pemilik</p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('detail-pengguna.index', $user_id) }}"
                        class="group flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 hover:bg-violet-50 hover:border-violet-200 transition flex-1">
                        <i class="fi fi-rr-user flex h-10 w-10 items-center justify-center rounded-xl bg-white text-violet-500 text-lg shadow-sm"></i>
                        <div>
                            <p class="text-sm font-black text-slate-900 group-hover:text-violet-700 transition">{{ $name }}</p>
                            <p class="text-xs text-slate-400">Lihat profil mitra</p>
                        </div>
                        <i class="fi fi-rr-angle-small-right flex text-slate-300 group-hover:text-violet-400 transition ml-auto"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
