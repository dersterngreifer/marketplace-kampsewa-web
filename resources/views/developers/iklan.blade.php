@extends('layouts.developers.ly-dashboard')

@section('content')
@php
    $resolvePoster = function($poster) {
        if (empty($poster)) {
            return 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22640%22%20height%3D%22360%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20640%20360%22%3E%3Crect%20fill%3D%22%23f1f5f9%22%20width%3D%22640%22%20height%3D%22360%22%2F%3E%3Ctext%20fill%3D%22%2394a3b8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2220%22%20dy%3D%2210.5%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%3EBanner%20Iklan%20(16%3A9)%3C%2Ftext%3E%3C%2Fsvg%3E';
        }
        if (\Illuminate\Support\Str::startsWith($poster, ['http://', 'https://'])) {
            return $poster;
        }
        if (\Illuminate\Support\Str::startsWith($poster, 'assets/')) {
            return asset($poster);
        }
        return asset('assets/image/customers/advert/' . $poster);
    };

    $svgFallback = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22640%22%20height%3D%22360%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20640%20360%22%3E%3Crect%20fill%3D%22%23f1f5f9%22%20width%3D%22640%22%20height%3D%22360%22%2F%3E%3Ctext%20fill%3D%22%2394a3b8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2220%22%20dy%3D%2210.5%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%3EBanner%20Iklan%20(16%3A9)%3C%2Ftext%3E%3C%2Fsvg%3E';
@endphp

<div class="w-full px-4 py-5 sm:px-5 lg:px-6">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full bg-violet-100/70 px-3 py-1 text-xs font-bold text-violet-700 mb-2">
                <i class="fi fi-rr-ad flex text-[13px]"></i>
                Fitur Banner Iklan 16:9 & Manajemen Slot Rotasi
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Manajemen Iklan Customer</h1>
            <p class="mt-1 text-sm font-medium text-slate-500 max-w-3xl">
                Platform membatasi maksimal <b>15 iklan aktif</b> yang tayang di aplikasi mobile. Iklan yang melewati batas tenggat akan <b>otomatis selesai sendiri</b>, dan slot kosong akan otomatis diisi oleh iklan dari antrian menunggu giliran.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <div class="flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200 px-4 py-2 text-xs font-bold text-emerald-700 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                </span>
                {{ $get_count_total_iklan_aktif }} / {{ $max_iklan_aktif }} Slot Aktif
            </div>
            <div class="flex items-center gap-2 rounded-full bg-amber-50 border border-amber-200 px-4 py-2 text-xs font-bold text-amber-700 shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-amber-500"></span>
                {{ $get_count_total_iklan_pending }} Menunggu Giliran
            </div>
            <div class="flex items-center gap-2 rounded-full bg-red-50 border border-red-200 px-4 py-2 text-xs font-bold text-red-700 shadow-sm">
                <i class="fi fi-rr-ban flex text-[12px]"></i>
                {{ $get_count_total_iklan_suspend }} Ditangguhkan
            </div>
            <div class="flex items-center gap-2 rounded-full bg-slate-100 border border-slate-200 px-4 py-2 text-xs font-bold text-slate-600 shadow-sm">
                <i class="fi fi-rr-check flex text-[12px]"></i>
                {{ $get_count_total_iklan_selesai }} Selesai
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
        {{-- Card 1: Slot Iklan Aktif --}}
        <div class="rounded-[24px] bg-white p-5 shadow-sm border border-slate-100 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <i class="fi fi-rr-megaphone flex text-[20px]"></i>
                </div>
                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">Maks. 15 Slot</span>
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Iklan Aktif Mobile</p>
            <div class="mt-1 flex items-baseline gap-1.5">
                <span class="text-3xl font-black text-slate-900">{{ $get_count_total_iklan_aktif }}</span>
                <span class="text-sm font-bold text-slate-400">/ {{ $max_iklan_aktif }} slot terisi</span>
            </div>
            <p class="mt-1 text-xs text-slate-400">
                @if ($sisa_slot_aktif > 0)
                    <span class="text-emerald-600 font-bold">{{ $sisa_slot_aktif }} slot kosong</span> siap diisi
                @else
                    <span class="text-amber-600 font-bold">Slot penuh (15/15)</span>
                @endif
            </p>
        </div>

        {{-- Card 2: Antrian Menunggu Giliran --}}
        <div class="rounded-[24px] bg-white p-5 shadow-sm border border-slate-100 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                    <i class="fi fi-rr-hourglass flex text-[20px]"></i>
                </div>
                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700">Rotasi FIFO</span>
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Menunggu Giliran</p>
            <p class="mt-1 text-3xl font-black text-slate-900">{{ number_format($get_count_total_iklan_pending, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-400">Iklan antrian yang belum aktif</p>
        </div>

        {{-- Card 3: Iklan Ditangguhkan (Suspend) --}}
        <div class="rounded-[24px] bg-white p-5 shadow-sm border border-slate-100 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                    <i class="fi fi-rr-ban flex text-[20px]"></i>
                </div>
                <span class="rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-bold text-red-700">Perlu Revisi</span>
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Ditangguhkan</p>
            <p class="mt-1 text-3xl font-black text-slate-900">{{ number_format($get_count_total_iklan_suspend, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-400">Iklan butuh revisi mitra</p>
        </div>

        {{-- Card 4: Total Transaksi Iklan --}}
        <div class="rounded-[24px] bg-white p-5 shadow-sm border border-slate-100 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                    <i class="fi fi-rr-receipt flex text-[20px]"></i>
                </div>
                <span class="rounded-full bg-violet-50 px-2.5 py-1 text-[11px] font-bold text-violet-600">Total Sewa</span>
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Total Transaksi Iklan</p>
            <p class="mt-1 text-3xl font-black text-slate-900">{{ number_format($get_count_total_transaksi_iklan, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-400">Seluruh pesanan iklan mitra</p>
        </div>

        {{-- Card 5: Pendapatan Iklan Bulan Ini --}}
        <div class="rounded-[24px] bg-white p-5 shadow-sm border border-slate-100 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i class="fi fi-rr-wallet flex text-[20px]"></i>
                </div>
                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-600">Bulan Ini</span>
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Pendapatan Sewa Iklan</p>
            <p class="mt-1 text-2xl font-black text-slate-900">Rp {{ number_format($pendapatan_iklan_bulan_ini, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-400">Iklan lunas periode berjalan</p>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="flex flex-col gap-6 xl:flex-row">

        {{-- Kolom Kiri: Antrian Iklan & Riwayat --}}
        <div class="flex flex-1 flex-col gap-6 min-w-0">

            {{-- Alert Penjelasan Otomatisasi Slot & Kadaluarsa --}}
            <div class="rounded-3xl border border-blue-200/80 bg-gradient-to-r from-blue-50/90 via-indigo-50/50 to-white p-5 shadow-sm">
                <div class="flex items-start gap-3.5">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md">
                        <i class="fi fi-rr-refresh text-base"></i>
                    </div>
                    <div class="text-xs leading-relaxed text-slate-600">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="font-black text-slate-900 text-sm">Mekanisme Otomatisasi Iklan & Batas 15 Slot Aktif</span>
                            <span class="rounded-full bg-blue-100 text-blue-800 font-bold px-2.5 py-0.5 text-[10px]">Auto-Sync Aktif</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-slate-600">
                            <li><b>Otomatis Selesai:</b> Iklan yang tanggal akhirnya sudah melewati hari ini otomatis berubah status menjadi <b>Selesai</b> tanpa perlu klik manual.</li>
                            <li><b>Batas Kuota 15 Iklan:</b> Hanya <b>15 iklan aktif</b> yang tampil di beranda aplikasi mobile. Sisanya ({{ $get_count_total_iklan_pending }} iklan) berstatus <b>Pending</b> menunggu giliran.</li>
                            <li><b>Rotasi Otomatis (FIFO):</b> Ketika salah satu dari 15 iklan aktif selesai atau diselesaikan, slot kosong akan <b>otomatis diisi oleh iklan berikutnya</b> dari antrian pending.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Alert Iklan Hampir Kadaluarsa (&le; 3 Hari) --}}
            @if ($iklan_hampir_expired->count() > 0)
            <div class="w-full overflow-hidden rounded-[28px] border border-red-200/80 bg-white shadow-sm">
                <div class="border-b border-red-100 bg-red-50/50 p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-red-700">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-red-600"></span>
                                </span>
                                Akan Segera Berakhir (&le; 3 Hari)
                            </div>
                            <h2 class="text-xl font-black text-slate-900">Iklan Mendekati Tenggat Waktu (Auto-Complete)</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Sebanyak <b>{{ $iklan_hampir_expired->count() }}</b> iklan aktif ini akan otomatis diselesaikan sistem saat tanggal akhir tercapai.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($iklan_hampir_expired as $item)
                    @php
                        $posterExp = $resolvePoster($item->poster);
                        $isProductExp = in_array(strtolower((string) $item->target_type), ['product', 'produk'], true) || !empty($item->id_produk);
                        $targetLabelExp = $isProductExp ? 'Produk: ' . ($item->nama_produk ?? 'Item Terkait') : 'Toko: ' . ($item->name_store ?? $item->name);
                    @endphp
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-5 hover:bg-slate-50/70 transition">
                        <div class="flex items-center gap-3.5 min-w-0">
                            {{-- Poster Thumbnail 16:9 --}}
                            <div class="relative w-28 sm:w-32 aspect-video shrink-0 overflow-hidden rounded-xl bg-slate-100 border border-slate-200 shadow-sm cursor-pointer"
                                onclick='openDetailModal(@json($item), @json($posterExp))'>
                                <img src="{{ $posterExp }}" class="h-full w-full object-cover" alt="{{ $item->judul }}" onerror="this.src='{{ $svgFallback }}'; this.onerror=null;">
                                <div class="absolute inset-0 bg-black/10 hover:bg-black/20 flex items-center justify-center transition">
                                    <i class="fi fi-rr-eye text-white text-xs opacity-80"></i>
                                </div>
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="rounded-md {{ $isProductExp ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }} px-2 py-0.5 text-[10px] font-bold">
                                        <i class="{{ $isProductExp ? 'fi fi-rr-box' : 'fi fi-rr-shop' }} mr-1"></i>
                                        {{ $targetLabelExp }}
                                    </span>
                                    @if (!empty($item->badge_promo))
                                    <span class="rounded-md bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 text-[10px] font-bold">
                                        {{ $item->badge_promo }}
                                    </span>
                                    @endif
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 truncate">{{ $item->judul }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-2 truncate">
                                    <span>Mitra: <b class="text-slate-600">{{ $item->name }}</b></span>
                                    @if (!empty($item->name_store))
                                    <span>&bull;</span>
                                    <span class="text-slate-500 font-medium">{{ $item->name_store }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-3 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-100">
                            <div class="text-left md:text-right">
                                <p class="text-xs font-black text-red-600 flex items-center md:justify-end gap-1">
                                    <i class="fi fi-rr-clock text-[11px]"></i>
                                    Berakhir {{ \Carbon\Carbon::parse($item->tanggal_akhir)->diffForHumans() }}
                                </p>
                                <p class="text-[11px] text-slate-400">Tenggat: {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button"
                                    onclick='openDetailModal(@json($item), @json($posterExp))'
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition active:scale-95">
                                    <i class="fi fi-rr-info text-[11px]"></i> Detail
                                </button>
                                <form method="POST" action="{{ route('iklan.selesaikan', $item->id_detail_iklan) }}">
                                    @csrf @method('PUT')
                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-red-500 px-3 py-2 text-xs font-bold text-white hover:bg-red-600 transition active:scale-95 shadow-sm"
                                        title="Selesaikan sekarang & buka 1 slot untuk antrian">
                                        <i class="fi fi-rr-check text-[11px]"></i> Selesaikan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Card Iklan Ditangguhkan (Suspend) --}}
            <div class="w-full overflow-hidden rounded-[28px] border-2 border-red-200/90 bg-white shadow-sm">
                <div class="border-b border-red-100 bg-red-50/40 p-5 sm:p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-red-700 border border-red-200/60">
                                <i class="fi fi-rr-ban flex text-[10px]"></i>
                                Iklan Ditangguhkan (Suspend)
                            </div>
                            <h2 class="text-xl font-black text-slate-900">Daftar Iklan Ditangguhkan (Suspend)</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Terdapat <b>{{ $get_count_total_iklan_suspend }}</b> iklan yang ditarik dari mobile karena butuh perbaikan oleh mitra.
                            </p>
                        </div>
                    </div>
                </div>

                @if (isset($data_iklan_suspend) && $data_iklan_suspend->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach ($data_iklan_suspend as $suspend)
                    @php
                        $posterSuspend = $resolvePoster($suspend->poster);
                        $isProductTarget = in_array(strtolower((string) $suspend->target_type), ['product', 'produk'], true) || !empty($suspend->id_produk);
                        $targetLabel = $isProductTarget ? 'Produk: ' . ($suspend->nama_produk ?? 'ID Produk #' . $suspend->id_produk) : 'Toko: ' . ($suspend->name_store ?? $suspend->name);
                    @endphp

                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 p-5 hover:bg-red-50/30 transition">
                        {{-- Sisi Kiri: Poster 16:9 + Info Iklan --}}
                        <div class="flex items-start gap-4 min-w-0 flex-1">
                            {{-- Poster Thumbnail (16:9) --}}
                            <div class="relative w-28 sm:w-36 aspect-video shrink-0 overflow-hidden rounded-2xl bg-slate-100 border border-slate-200 shadow-sm cursor-pointer group"
                                onclick='openDetailModal(@json($suspend), @json($posterSuspend))'>
                                <img src="{{ $posterSuspend }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" alt="{{ $suspend->judul }}" onerror="this.src='{{ $svgFallback }}'; this.onerror=null;">
                                <div class="absolute inset-0 bg-slate-900/20 group-hover:bg-slate-900/40 flex items-center justify-center transition opacity-0 group-hover:opacity-100">
                                    <span class="rounded-lg bg-black/60 px-2 py-1 text-[10px] font-bold text-white flex items-center gap-1">
                                        <i class="fi fi-rr-eye text-[10px]"></i> Lihat
                                    </span>
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                {{-- Target & Suspend Badges --}}
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <span class="inline-flex items-center gap-1 rounded-md {{ $isProductTarget ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }} px-2.5 py-0.5 text-[11px] font-bold">
                                        <i class="{{ $isProductTarget ? 'fi fi-rr-box' : 'fi fi-rr-shop' }} text-[10px]"></i>
                                        {{ $targetLabel }}
                                    </span>
                                    <span class="rounded-md bg-red-100 text-red-800 border border-red-200 px-2.5 py-0.5 text-[10px] font-bold flex items-center gap-1">
                                        <i class="fi fi-rr-ban text-[10px]"></i> Ditangguhkan
                                    </span>
                                    @if (($suspend->suspend_status_revisi ?? '') === 'sudah_diperbaiki')
                                    <span class="rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-bold flex items-center gap-1">
                                        <i class="fi fi-rr-check-circle text-[10px]"></i> Sudah Direvisi Mitra
                                    </span>
                                    @else
                                    <span class="rounded-md bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 text-[10px] font-bold">
                                        Menunggu Revisi Mitra
                                    </span>
                                    @endif
                                </div>

                                {{-- Judul & Sub Judul --}}
                                <h3 class="text-base font-bold text-slate-900 line-clamp-1 cursor-pointer hover:text-red-600 transition"
                                    onclick='openDetailModal(@json($suspend), @json($posterSuspend))'>
                                    {{ $suspend->judul }}
                                </h3>
                                
                                {{-- Alasan Penangguhan --}}
                                @if (!empty($suspend->suspend_alasan))
                                <div class="mt-1.5 p-2 rounded-xl bg-red-50 border border-red-200/70 text-xs text-red-800 flex items-start gap-1.5">
                                    <i class="fi fi-rr-info text-[11px] mt-0.5 shrink-0 text-red-600"></i>
                                    <div>
                                        <span class="font-bold">Alasan:</span> {{ $suspend->suspend_alasan }}
                                        @if (!empty($suspend->suspend_catatan))
                                        <p class="mt-0.5 text-[11px] text-red-700 font-medium">Catatan: {{ $suspend->suspend_catatan }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                {{-- Data Pemilik Toko & Jadwal --}}
                                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                    <div class="flex items-center gap-1.5">
                                        <img src="@userPhoto($suspend->foto)" class="h-5 w-5 rounded-full object-cover" alt="">
                                        <span class="font-bold text-slate-700">{{ $suspend->name }}</span>
                                        @if (!empty($suspend->name_store))
                                        <span class="text-slate-400">({{ $suspend->name_store }})</span>
                                        @endif
                                    </div>
                                    <span>&bull;</span>
                                    <span class="flex items-center gap-1 text-slate-400">
                                        <i class="fi fi-rr-calendar text-[11px]"></i>
                                        Jadwal: {{ \Carbon\Carbon::parse($suspend->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($suspend->tanggal_akhir)->format('d M Y') }}
                                        <span class="text-slate-600 font-bold">({{ $suspend->durasi_hari ?? \Carbon\Carbon::parse($suspend->tanggal_mulai)->diffInDays($suspend->tanggal_akhir) }} hari)</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Sisi Kanan: Status Pembayaran, Tarif, dan Tombol Aksi --}}
                        <div class="flex flex-wrap lg:flex-nowrap items-center justify-between lg:justify-end gap-3 shrink-0 pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                            {{-- Info Transaksi --}}
                            <div class="text-left lg:text-right pr-2">
                                <p class="text-sm font-black text-slate-900">Rp {{ number_format($suspend->nominal_bayar ?? $suspend->harga_iklan ?? 0, 0, ',', '.') }}</p>
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 mt-0.5">
                                    Lunas
                                </span>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="flex items-center gap-2">
                                <button type="button"
                                    onclick='openDetailModal(@json($suspend), @json($posterSuspend))'
                                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition active:scale-95 shadow-sm">
                                    <i class="fi fi-rr-eye text-[12px] text-slate-400"></i> Detail
                                </button>

                                <form method="POST" action="{{ route('iklan.aktivasi', $suspend->id_detail_iklan) }}">
                                    @csrf @method('PUT')
                                    <button type="submit" title="{{ $sisa_slot_aktif > 0 ? 'Pulihkan iklan ke slot aktif' : 'Slot penuh (15/15)' }}"
                                        class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition active:scale-95 shadow-sm">
                                        <i class="fi fi-rr-play text-[11px]"></i> Pulihkan Iklan
                                    </button>
                                </form>

                                <form id="form_delete_{{ $suspend->id_iklan_main }}"
                                    action="{{ route('iklan.delete-iklan-pending', ['id_iklan' => $suspend->id_iklan_main]) }}"
                                    method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" data-id="{{ $suspend->id_iklan_main }}"
                                        onclick="confirmDelete(this.dataset.id)"
                                        title="Hapus iklan ini secara permanen"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition active:scale-95">
                                        <i class="fi fi-rr-trash text-[13px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex min-h-[160px] flex-col items-center justify-center p-8 text-center">
                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <i class="fi fi-rr-check-circle flex text-xl text-emerald-500"></i>
                    </div>
                    <p class="text-sm font-black text-slate-800">Tidak Ada Iklan Ditangguhkan</p>
                    <p class="mt-1 text-xs text-slate-400 max-w-sm">
                        Semua iklan berjalan sesuai pedoman komunitas dan tidak ada iklan mitra yang di-suspend saat ini.
                    </p>
                </div>
                @endif

                @if (isset($data_iklan_suspend) && $data_iklan_suspend->hasPages())
                <div class="border-t border-slate-100 p-4">
                    {{ $data_iklan_suspend->appends(['cari' => $cari])->links('components.paginate.custom-pagination') }}
                </div>
                @endif
            </div>

            {{-- Antrian Iklan Pending (Menunggu Giliran) --}}
            <div class="w-full overflow-hidden rounded-[28px] border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5 sm:p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-amber-700 border border-amber-200/60">
                                <i class="fi fi-rr-hourglass flex text-[10px]"></i>
                                Antrian Menunggu Giliran Tayang
                            </div>
                            <h2 class="text-xl font-black text-slate-900">Daftar Antrian Iklan (Pending)</h2>
                            <p class="mt-1 text-sm text-slate-400">
                                Terdapat <b>{{ $get_count_total_iklan_pending }}</b> iklan mitra yang sedang menunggu giliran aktif saat slot kosong tersedia.
                            </p>
                        </div>

                        {{-- Search Filter --}}
                        <form method="GET" action="{{ route('iklan.index') }}" class="flex gap-2">
                            <div class="relative w-full sm:w-72">
                                <i class="fi fi-rr-search absolute left-4 top-1/2 -translate-y-1/2 flex text-slate-400 text-[14px]"></i>
                                <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari mitra / toko / judul..."
                                    class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-xs font-medium text-slate-700 outline-none transition focus:border-violet-500 focus:bg-white focus:ring-4 focus:ring-violet-100">
                                @if (!empty($cari))
                                <a href="{{ route('iklan.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 hover:text-slate-600">
                                    <i class="fi fi-rr-cross-small text-[14px]"></i>
                                </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                @if ($user_pending->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach ($user_pending as $user)
                    @php
                        $posterPending = $resolvePoster($user->poster);
                        $midtransStatus = $user->midtrans_transaction_status ?? null;
                        $statusBayar = $user->status_bayar ?? 'belum bayar';
                        $nominalBayar = $user->nominal_bayar ?? $user->harga_iklan ?? 0;
                        $isProductTarget = in_array(strtolower((string) $user->target_type), ['product', 'produk'], true) || !empty($user->id_produk);
                        $targetLabel = $isProductTarget ? 'Produk: ' . ($user->nama_produk ?? 'ID Produk #' . $user->id_produk) : 'Toko: ' . ($user->name_store ?? $user->name);
                    @endphp

                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 p-5 hover:bg-slate-50/80 transition">
                        {{-- Sisi Kiri: Poster 16:9 + Info Iklan --}}
                        <div class="flex items-start gap-4 min-w-0 flex-1">
                            {{-- Poster Thumbnail (16:9) --}}
                            <div class="relative w-28 sm:w-36 aspect-video shrink-0 overflow-hidden rounded-2xl bg-slate-100 border border-slate-200 shadow-sm cursor-pointer group"
                                onclick='openDetailModal(@json($user), @json($posterPending))'>
                                <img src="{{ $posterPending }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" alt="{{ $user->judul }}" onerror="this.src='{{ $svgFallback }}'; this.onerror=null;">
                                <div class="absolute inset-0 bg-slate-900/20 group-hover:bg-slate-900/40 flex items-center justify-center transition opacity-0 group-hover:opacity-100">
                                    <span class="rounded-lg bg-black/60 px-2 py-1 text-[10px] font-bold text-white flex items-center gap-1">
                                        <i class="fi fi-rr-eye text-[10px]"></i> Lihat
                                    </span>
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                {{-- Target & Promo Badges --}}
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <span class="inline-flex items-center gap-1 rounded-md {{ $isProductTarget ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }} px-2.5 py-0.5 text-[11px] font-bold">
                                        <i class="{{ $isProductTarget ? 'fi fi-rr-box' : 'fi fi-rr-shop' }} text-[10px]"></i>
                                        {{ $targetLabel }}
                                    </span>
                                    @if (!empty($user->badge_promo))
                                    <span class="rounded-md bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 text-[11px] font-bold">
                                        {{ $user->badge_promo }}
                                    </span>
                                    @endif
                                    <span class="rounded-md bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 text-[10px] font-bold">
                                        Antrian Rotasi FIFO
                                    </span>
                                </div>

                                {{-- Judul & Sub Judul --}}
                                <h3 class="text-base font-bold text-slate-900 line-clamp-1 cursor-pointer hover:text-violet-600 transition"
                                    onclick='openDetailModal(@json($user), @json($posterPending))'>
                                    {{ $user->judul }}
                                </h3>
                                @if (!empty($user->sub_judul))
                                <p class="text-xs font-medium text-slate-500 line-clamp-1 mt-0.5">{{ $user->sub_judul }}</p>
                                @endif

                                {{-- Data Pemilik Toko --}}
                                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                    <div class="flex items-center gap-1.5">
                                        <img src="@userPhoto($user->foto)" class="h-5 w-5 rounded-full object-cover" alt="">
                                        <span class="font-bold text-slate-700">{{ $user->name }}</span>
                                        @if (!empty($user->name_store))
                                        <span class="text-slate-400">({{ $user->name_store }})</span>
                                        @endif
                                    </div>
                                    <span>&bull;</span>
                                    <span class="flex items-center gap-1 text-slate-400">
                                        <i class="fi fi-rr-calendar text-[11px]"></i>
                                        Jadwal: {{ \Carbon\Carbon::parse($user->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($user->tanggal_akhir)->format('d M Y') }}
                                        <span class="text-slate-600 font-bold">({{ $user->durasi_hari ?? \Carbon\Carbon::parse($user->tanggal_mulai)->diffInDays($user->tanggal_akhir) }} hari)</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Sisi Kanan: Status Pembayaran, Tarif, dan Tombol Aksi --}}
                        <div class="flex flex-wrap lg:flex-nowrap items-center justify-between lg:justify-end gap-3 shrink-0 pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                            {{-- Info Transaksi --}}
                            <div class="text-left lg:text-right pr-2">
                                <p class="text-sm font-black text-slate-900">Rp {{ number_format($nominalBayar, 0, ',', '.') }}</p>
                                <div class="mt-0.5">
                                    @if ($statusBayar === 'lunas' || $midtransStatus === 'settlement')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">
                                        <i class="fi fi-rr-check-circle text-[9px]"></i> Lunas (Midtrans)
                                    </span>
                                    @elseif ($midtransStatus === 'pending')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-[10px] font-bold text-amber-700">
                                        <i class="fi fi-rr-hourglass-end text-[9px]"></i> Bayar Pending
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-[10px] font-bold text-slate-600">
                                        {{ ucfirst($statusBayar) }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="flex items-center gap-2">
                                <button type="button"
                                    onclick='openDetailModal(@json($user), @json($posterPending))'
                                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition active:scale-95 shadow-sm">
                                    <i class="fi fi-rr-eye text-[12px] text-slate-400"></i> Detail
                                </button>

                                @if ($statusBayar === 'lunas' || $midtransStatus === 'settlement')
                                <form method="POST" action="{{ route('iklan.aktivasi', $user->id_detail_iklan) }}">
                                    @csrf @method('PUT')
                                    <button type="submit" title="{{ $sisa_slot_aktif > 0 ? 'Aktifkan ke slot kosong' : 'Slot penuh (15/15)' }}"
                                        class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition active:scale-95 shadow-sm">
                                        <i class="fi fi-rr-megaphone text-[11px]"></i> Aktifkan
                                    </button>
                                </form>
                                @else
                                <form method="POST" action="{{ route('iklan.aktivasi', $user->id_detail_iklan) }}">
                                    @csrf @method('PUT')
                                    <button type="submit" title="Aktivasi manual oleh admin"
                                        class="inline-flex items-center gap-1.5 rounded-xl bg-violet-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-violet-700 transition active:scale-95 shadow-sm">
                                        <i class="fi fi-rr-bolt text-[11px]"></i> Aktivasi Manual
                                    </button>
                                </form>
                                @endif

                                <form id="form_delete_{{ $user->id_iklan_main }}"
                                    action="{{ route('iklan.delete-iklan-pending', ['id_iklan' => $user->id_iklan_main]) }}"
                                    method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" data-id="{{ $user->id_iklan_main }}"
                                        onclick="confirmDelete(this.dataset.id)"
                                        title="Hapus iklan dari antrian"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition active:scale-95">
                                        <i class="fi fi-rr-trash text-[13px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex min-h-[220px] flex-col items-center justify-center p-8 text-center">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">
                        <i class="fi fi-rr-megaphone flex text-2xl"></i>
                    </div>
                    <p class="text-base font-black text-slate-900">Tidak Ada Antrian Iklan</p>
                    <p class="mt-1 text-sm text-slate-400 max-w-sm">
                        {{ !empty($cari) ? 'Tidak ditemukan iklan yang sesuai dengan kata kunci pencarian.' : 'Saat ini tidak ada iklan dalam antrian pending. Semua slot iklan aktif terisi atau telah diproses.' }}
                    </p>
                    @if (!empty($cari))
                    <a href="{{ route('iklan.index') }}" class="mt-3 text-xs font-bold text-violet-600 hover:underline">Reset Pencarian</a>
                    @endif
                </div>
                @endif

                @if ($user_pending->hasPages())
                <div class="border-t border-slate-100 p-4">
                    {{ $user_pending->appends(['cari' => $cari])->onEachSide(1)->links('components.paginate.custom-pagination') }}
                </div>
                @endif
            </div>

            {{-- Riwayat Iklan Selesai --}}
            <div class="w-full overflow-hidden rounded-[28px] border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-slate-600">
                                <i class="fi fi-rr-time-past flex text-[10px]"></i>
                                Arsip Iklan Selesai
                            </div>
                            <h2 class="text-xl font-black text-slate-900">Riwayat Iklan Selesai (Kadaluarsa)</h2>
                            <p class="mt-1 text-sm text-slate-400">Daftar iklan promosi yang masa tayangnya telah berakhir dan diselesaikan secara otomatis.</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                            Total: {{ $get_count_iklan_selesai }} Selesai
                        </span>
                    </div>
                </div>

                @if ($data_iklan_selesai->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach ($data_iklan_selesai as $item)
                    @php
                        $posterSelesai = $resolvePoster($item->poster);
                        $isProductSelesai = in_array(strtolower((string) $item->target_type), ['product', 'produk'], true) || !empty($item->id_produk);
                        $targetLabelSelesai = $isProductSelesai ? 'Produk: ' . ($item->nama_produk ?? 'Item Terkait') : 'Toko: ' . ($item->name_store ?? $item->name);
                    @endphp
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-5 py-4 hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3.5 min-w-0">
                            {{-- Poster 16:9 Thumbnail --}}
                            <div class="relative w-24 sm:w-28 aspect-video shrink-0 overflow-hidden rounded-xl bg-slate-100 border border-slate-200 cursor-pointer"
                                onclick='openDetailModal(@json($item), @json($posterSelesai))'>
                                <img src="{{ $posterSelesai }}" class="h-full w-full object-cover grayscale opacity-80" alt="{{ $item->judul }}" onerror="this.src='{{ $svgFallback }}'; this.onerror=null;">
                            </div>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600">
                                        {{ $targetLabelSelesai }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-900 truncate">{{ $item->judul }}</h4>
                                <p class="text-xs text-slate-400 mt-0.5 truncate">
                                    Mitra: <span class="font-medium text-slate-600">{{ $item->name }}</span>
                                    @if(!empty($item->name_store)) &bull; {{ $item->name_store }} @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
                            <div class="text-left sm:text-right">
                                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-bold text-slate-500">Otomatis Selesai</span>
                                <p class="text-[11px] text-slate-400 mt-1">Berakhir: {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}</p>
                            </div>

                            <button type="button"
                                onclick='openDetailModal(@json($item), @json($posterSelesai))'
                                class="rounded-xl border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-100 transition shadow-sm"
                                title="Lihat detail iklan">
                                <i class="fi fi-rr-eye flex text-[13px]"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex min-h-[160px] items-center justify-center p-8 text-center">
                    <p class="text-sm font-semibold text-slate-400">Belum ada riwayat iklan selesai.</p>
                </div>
                @endif

                @if ($data_iklan_selesai->hasPages())
                <div class="border-t border-slate-100 p-4">
                    {{ $data_iklan_selesai->onEachSide(1)->links('components.paginate.custom-pagination') }}
                </div>
                @endif
            </div>

        </div>

        {{-- Kolom Kanan: Iklan Sedang Aktif (Tepat Maksimal 15 Slot Tayang di Mobile) --}}
        <div class="w-full xl:w-[380px] shrink-0">
            <div class="sticky top-4 space-y-4">

                <div class="overflow-hidden rounded-[28px] border border-slate-200/80 bg-white shadow-sm">
                    {{-- Header Live --}}
                    <div class="border-b border-slate-100 p-5 bg-gradient-to-b from-emerald-50/50 to-transparent">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-emerald-700">
                                <span class="relative flex h-2 w-2">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                </span>
                                Tayang di Mobile
                            </div>
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-100/70 px-2.5 py-0.5 rounded-full">
                                {{ $get_count_total_iklan_aktif }} / {{ $max_iklan_aktif }} Slot
                            </span>
                        </div>
                        <h2 class="mt-2 text-lg font-black text-slate-900">Iklan Sedang Aktif (Maks 15)</h2>
                        <p class="mt-0.5 text-xs text-slate-500 leading-relaxed">
                            Sebanyak <b>{{ $get_count_total_iklan_aktif }} iklan</b> ini yang saat ini tampil pada carousel aplikasi mobile. Iklan akan otomatis selesai dan membuka slot baru saat masa sewa berakhir.
                        </p>
                    </div>

                    {{-- List Iklan Aktif --}}
                    <div class="max-h-[calc(100vh-260px)] overflow-y-auto divide-y divide-slate-100">
                        @forelse ($data_iklan_aktif as $aktif)
                        @php
                            $posterAktif = $resolvePoster($aktif->poster);
                            $sisaHariAktif = \Carbon\Carbon::parse($aktif->tanggal_akhir)->diffInDays(\Carbon\Carbon::today(), false);
                            $sisaHariAbs = abs($sisaHariAktif);
                            $isProductAktif = in_array(strtolower((string) $aktif->target_type), ['product', 'produk'], true) || !empty($aktif->id_produk);
                            $targetLabelAktif = $isProductAktif ? 'Produk: ' . ($aktif->nama_produk ?? 'Item Terkait') : 'Toko: ' . ($aktif->name_store ?? $aktif->name);
                        @endphp
                        <div class="p-4 transition hover:bg-slate-50/60">
                            {{-- Card Poster (16:9 Standard) --}}
                            <div class="relative w-full aspect-video overflow-hidden rounded-2xl bg-slate-900 border border-slate-200 shadow-sm cursor-pointer group"
                                onclick='openDetailModal(@json($aktif), @json($posterAktif))'>
                                <img src="{{ $posterAktif }}"
                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                    alt="{{ $aktif->judul }}"
                                    onerror="this.src='{{ $svgFallback }}'; this.onerror=null;"
                                    loading="lazy">

                                {{-- Badges Top Overlay --}}
                                <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                                    <span class="rounded-lg bg-emerald-600/90 backdrop-blur-sm px-2 py-0.5 text-[10px] font-black uppercase text-white shadow-sm flex items-center gap-1">
                                        <span class="h-1.5 w-1.5 rounded-full bg-white"></span> LIVE
                                    </span>
                                    @if (!empty($aktif->badge_promo))
                                    <span class="rounded-lg bg-amber-500/95 backdrop-blur-sm px-2 py-0.5 text-[10px] font-black uppercase text-white shadow-sm">
                                        {{ $aktif->badge_promo }}
                                    </span>
                                    @endif
                                </div>

                                {{-- Target Badge Top Right --}}
                                <div class="absolute top-2.5 right-2.5">
                                    <span class="rounded-lg {{ $isProductAktif ? 'bg-purple-600/90' : 'bg-blue-600/90' }} backdrop-blur-sm px-2 py-0.5 text-[10px] font-bold text-white shadow-sm">
                                        <i class="{{ $isProductAktif ? 'fi fi-rr-box' : 'fi fi-rr-shop' }} mr-0.5"></i>
                                        {{ $isProductAktif ? 'Produk' : 'Toko' }}
                                    </span>
                                </div>

                                {{-- Title Bottom Overlay --}}
                                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-3 pt-6">
                                    <p class="text-xs font-bold text-white line-clamp-1">{{ $aktif->judul }}</p>
                                    @if (!empty($aktif->sub_judul))
                                    <p class="text-[11px] text-slate-200/90 line-clamp-1 mt-0.5">{{ $aktif->sub_judul }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Mitra & Expiry Info --}}
                            <div class="mt-3 flex items-center justify-between">
                                <div class="flex items-center gap-2 min-w-0">
                                    <img src="@userPhoto($aktif->foto)" class="h-7 w-7 rounded-xl object-cover shrink-0" alt="">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate">{{ $aktif->name }}</p>
                                        <p class="text-[11px] text-slate-400 truncate">{{ $aktif->name_store ?? 'Mitra KampSewa' }}</p>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <p class="text-[11px] font-black {{ $sisaHariAbs <= 3 ? 'text-red-500' : 'text-slate-700' }}">
                                        {{ $sisaHariAbs }} hari lagi
                                    </p>
                                    <p class="text-[10px] text-slate-400">Otomatis selesai: {{ \Carbon\Carbon::parse($aktif->tanggal_akhir)->format('d M Y') }}</p>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="mt-3 grid grid-cols-3 gap-2">
                                <button type="button"
                                    onclick='openDetailModal(@json($aktif), @json($posterAktif))'
                                    class="w-full rounded-xl border border-slate-200 bg-white py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition active:scale-95 shadow-sm text-center">
                                    Detail
                                </button>
                                <button type="button"
                                    onclick='openSuspendModal(@json($aktif), @json($posterAktif))'
                                    class="w-full rounded-xl bg-amber-50 py-2 text-xs font-bold text-amber-700 hover:bg-amber-500 hover:text-white transition active:scale-95 text-center flex items-center justify-center gap-1"
                                    title="Tangguhkan iklan dengan alasan & alihkan ke antrian">
                                    <i class="fi fi-rr-ban text-[11px]"></i> Suspend
                                </button>
                                <form method="POST" action="{{ route('iklan.selesaikan', $aktif->id_detail_iklan) }}" class="w-full">
                                    @csrf @method('PUT')
                                    <button type="submit" class="w-full rounded-xl bg-slate-100 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition active:scale-95 text-center"
                                        title="Selesaikan sekarang & buka slot untuk antrian">
                                        Selesaikan
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="flex min-h-[220px] flex-col items-center justify-center p-6 text-center">
                            <i class="fi fi-rr-megaphone flex text-2xl text-slate-300 mb-2"></i>
                            <p class="text-sm font-bold text-slate-700">Belum Ada Iklan Aktif</p>
                            <p class="mt-1 text-xs text-slate-400">Aktifkan iklan dari antrian pending untuk menampilkannya di mobile.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

{{-- MODAL DETAIL IKLAN (RASIO 16:9 & DETAIL TRANSAKSI LENGKAP) --}}
<div id="modalDetailIklan" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm p-4 sm:p-6 transition-all duration-300 flex items-center justify-center">
    <div class="relative w-full max-w-2xl overflow-hidden rounded-[32px] bg-white shadow-2xl border border-slate-100 my-8">
        {{-- Header Modal --}}
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 bg-slate-50/70">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                    <i class="fi fi-rr-ad text-[14px]"></i>
                </span>
                <div>
                    <h3 class="text-base font-black text-slate-900">Detail Banner Iklan Mitra</h3>
                    <p class="text-[11px] text-slate-400">Pratinjau visual 16:9 dan rincian transaksi</p>
                </div>
            </div>
            <button type="button" onclick="closeDetailModal()" class="flex h-8 w-8 items-center justify-center rounded-xl bg-white text-slate-400 hover:bg-slate-200 hover:text-slate-700 transition">
                <i class="fi fi-rr-cross text-xs"></i>
            </button>
        </div>

        {{-- Body Modal --}}
        <div class="max-h-[calc(85vh-120px)] overflow-y-auto p-6 space-y-5">
            {{-- Visual Banner 16:9 Utuh --}}
            <div class="relative w-full aspect-video overflow-hidden rounded-2xl bg-slate-900 border border-slate-200 shadow-md">
                <img id="modalPoster" src="" class="h-full w-full object-cover" alt="Banner Iklan 16:9">
                <div class="absolute top-3 left-3 flex items-center gap-2">
                    <span id="modalTargetBadge" class="rounded-lg bg-blue-600/90 backdrop-blur-sm px-2.5 py-1 text-[11px] font-black uppercase text-white shadow-sm">
                        Toko Mitra
                    </span>
                    <span id="modalBadgePromo" class="hidden rounded-lg bg-amber-500/95 backdrop-blur-sm px-2.5 py-1 text-[11px] font-black uppercase text-white shadow-sm"></span>
                </div>
                <div class="absolute bottom-2 right-2">
                    <span class="rounded bg-black/60 px-2 py-1 text-[10px] font-bold text-white">Standar 16:9</span>
                </div>
            </div>

            {{-- Judul & Deskripsi --}}
            <div>
                <h2 id="modalJudul" class="text-xl font-black text-slate-900"></h2>
                <p id="modalSubJudul" class="mt-1 text-sm font-semibold text-violet-600"></p>
                <div class="mt-3 rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Deskripsi Promosi</p>
                    <p id="modalDeskripsi" class="text-xs leading-relaxed text-slate-600 whitespace-pre-line"></p>
                </div>
            </div>

            {{-- Grid Info Mitra & Jadwal --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Data Mitra Toko --}}
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-[11px] font-black uppercase tracking-wider text-slate-400 mb-2 flex items-center gap-1.5">
                        <i class="fi fi-rr-shop text-violet-600"></i> Informasi Mitra Toko
                    </p>
                    <div class="flex items-center gap-3">
                        <img id="modalMitraFoto" src="" class="h-10 w-10 rounded-2xl object-cover shrink-0" alt="">
                        <div class="min-w-0">
                            <p id="modalMitraName" class="text-sm font-bold text-slate-900 truncate"></p>
                            <p id="modalMitraStore" class="text-xs text-violet-600 font-semibold truncate"></p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 text-xs space-y-1 text-slate-500">
                        <p class="flex items-center gap-2 truncate">
                            <i class="fi fi-rr-envelope text-slate-400"></i>
                            <span id="modalMitraEmail">-</span>
                        </p>
                        <p class="flex items-center gap-2 truncate">
                            <i class="fi fi-rr-phone-call text-slate-400"></i>
                            <span id="modalMitraPhone">-</span>
                        </p>
                    </div>
                </div>

                {{-- Data Target & Transaksi --}}
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-[11px] font-black uppercase tracking-wider text-slate-400 mb-2 flex items-center gap-1.5">
                        <i class="fi fi-rr-receipt text-emerald-600"></i> Transaksi & Jadwal Tayang
                    </p>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Target Promosi:</span>
                            <span id="modalTargetDetail" class="font-bold text-slate-800">Toko Publik</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Biaya Sewa Iklan:</span>
                            <span id="modalNominal" class="font-black text-slate-900 text-sm">Rp 0</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Status Pembayaran:</span>
                            <span id="modalStatusBayar" class="font-bold"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Periode Tayang:</span>
                            <span id="modalJadwal" class="font-semibold text-slate-700"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Durasi:</span>
                            <span id="modalDurasi" class="font-bold text-violet-700"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Status Iklan:</span>
                            <span id="modalStatusIklanBadge" class="font-bold text-emerald-600">Aktif</span>
                        </div>
                    </div>
                </div>

                {{-- Informasi Penangguhan / Suspend (Tampil jika iklan berstatus Nonaktif / Suspended) --}}
                <div id="modalSuspendSection" class="hidden rounded-2xl border border-red-200 bg-red-50/70 p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-red-800 font-bold text-xs">
                            <i class="fi fi-rr-ban"></i>
                            <span>Catatan Penangguhan (Suspend):</span>
                        </div>
                        <span id="modalSuspendStatusRevisiBadge" class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-white border"></span>
                    </div>
                    <p id="modalSuspendAlasan" class="text-xs font-bold text-red-700"></p>
                    <div id="modalSuspendBagianRevisi" class="flex flex-wrap gap-1.5 pt-1"></div>
                    <div id="modalSuspendCatatanBox" class="mt-2 rounded-xl bg-white/90 p-2.5 text-[11px] text-slate-700 border border-red-100">
                        <p class="font-bold text-slate-500 text-[10px] uppercase tracking-wide">Pesan Instruksi untuk Mitra:</p>
                        <p id="modalSuspendCatatan" class="mt-0.5 text-slate-700 whitespace-pre-line"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Modal Detail --}}
        <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50 px-6 py-4">
            <div id="modalFooterActions"></div>
            <button type="button" onclick="closeDetailModal()" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-100 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- MODAL SUSPEND IKLAN DENGAN ALASAN & CHECKLIST REVISI --}}
<div id="modalSuspendIklan" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm p-4 sm:p-6 transition-all duration-300 flex items-center justify-center">
    <div class="relative w-full max-w-xl overflow-hidden rounded-[28px] bg-white shadow-2xl border border-slate-100 my-8">
        {{-- Header Modal --}}
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 bg-amber-50/70">
            <div class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <i class="fi fi-rr-ban text-base"></i>
                </span>
                <div>
                    <h3 class="text-base font-black text-slate-900">Tangguhkan (Suspend) Iklan</h3>
                    <p class="text-[11px] text-slate-500">Iklan akan diturunkan dari mobile dan mitra wajib melakukan perbaikan.</p>
                </div>
            </div>
            <button type="button" onclick="closeSuspendModal()" class="rounded-xl p-2 text-slate-400 hover:bg-white hover:text-slate-600 transition">
                <i class="fi fi-rr-cross text-xs"></i>
            </button>
        </div>

        {{-- Form Suspend --}}
        <form id="formSuspendIklan" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4 max-h-[calc(100vh-220px)] overflow-y-auto">
                {{-- Preview Singkat Iklan --}}
                <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <img id="suspendPreviewImg" src="" class="w-20 aspect-video rounded-xl object-cover border border-slate-200 bg-white shrink-0" alt="">
                    <div class="min-w-0 flex-1">
                        <p id="suspendPreviewJudul" class="text-xs font-bold text-slate-900 truncate">-</p>
                        <p id="suspendPreviewMitra" class="text-[11px] text-slate-500 truncate mt-0.5">-</p>
                    </div>
                </div>

                {{-- Pilih Alasan Utama --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Alasan Penangguhan / Suspend <span class="text-red-500">*</span>
                    </label>
                    <select name="alasan" id="suspendAlasanSelect" required class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-medium text-slate-700 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition">
                        <option value="">-- Pilih Alasan Penangguhan --</option>
                        <option value="Gambar / Poster Banner Buram atau Tidak Sesuai Rasio 16:9">Gambar / Poster Banner Buram atau Tidak Sesuai Standar (16:9)</option>
                        <option value="Poster Banner Mengandung Konten / Gambar yang Tidak Pantas">Poster Banner Mengandung Konten / Gambar yang Tidak Pantas</option>
                        <option value="Judul atau Sub Judul Menyesatkan / Clickbait">Judul atau Sub Judul Menyesatkan / Clickbait</option>
                        <option value="Deskripsi Iklan Mengandung Unsur Spam atau Kata Tidak Senonoh">Deskripsi Iklan Mengandung Unsur Spam / Kata Tidak Pantas</option>
                        <option value="Informasi Promo atau Produk Tidak Sesuai Kenyataan">Informasi Promo atau Produk Tidak Sesuai Kenyataan</option>
                        <option value="Lainnya">Lainnya (Jelaskan detail pada catatan)</option>
                    </select>
                </div>

                {{-- Bagian yang Wajib Diperbaiki Mitra --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Bagian yang Wajib Diperbaiki oleh Mitra <span class="text-slate-400 font-normal">(Centang yang berlaku)</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="bagian_revisi[]" value="poster" class="rounded text-amber-600 focus:ring-amber-400">
                            <span class="font-semibold text-slate-700">Gambar / Poster</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="bagian_revisi[]" value="judul" class="rounded text-amber-600 focus:ring-amber-400">
                            <span class="font-semibold text-slate-700">Judul Iklan</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="bagian_revisi[]" value="sub_judul" class="rounded text-amber-600 focus:ring-amber-400">
                            <span class="font-semibold text-slate-700">Sub Judul</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="bagian_revisi[]" value="deskripsi" class="rounded text-amber-600 focus:ring-amber-400">
                            <span class="font-semibold text-slate-700">Deskripsi Iklan</span>
                        </label>
                    </div>
                </div>

                {{-- Catatan / Instruksi Detail untuk Mitra --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Instruksi & Catatan untuk Mitra <span class="text-slate-400 font-normal">(Akan tampil di dashboard mitra)</span>
                    </label>
                    <textarea name="catatan" rows="3" required placeholder="Jelaskan detail apa yang salah dan apa yang harus diganti atau diperbaiki oleh mitra..."
                        class="w-full rounded-xl border border-slate-200 bg-white p-3 text-xs text-slate-700 placeholder:text-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition"></textarea>
                </div>
            </div>

            {{-- Footer Modal Suspend --}}
            <div class="border-t border-slate-100 px-6 py-4 bg-slate-50/70 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeSuspendModal()" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-100 transition active:scale-95">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-amber-500 px-5 py-2.5 text-xs font-bold text-white hover:bg-amber-600 transition active:scale-95 shadow-sm flex items-center gap-2">
                    <i class="fi fi-rr-ban text-xs"></i>
                    Tangguhkan & Beritahu Mitra
                </button>
            </div>
        </form>
    </div>
</div>

@include('sweetalert::alert')

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus iklan ini?',
            text: 'Iklan dan data transaksi terkait akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[28px] p-6 shadow-2xl',
                title: 'text-[20px] font-black text-slate-900',
                actions: 'gap-3',
                confirmButton: 'rounded-2xl bg-red-500 px-6 py-3 text-sm font-bold text-white',
                cancelButton: 'rounded-2xl bg-slate-100 px-6 py-3 text-sm font-bold text-slate-600',
            },
            buttonsStyling: false,
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('form_delete_' + id).submit();
            }
        });
    }

    function openDetailModal(data, posterUrl) {
        document.getElementById('modalPoster').src = posterUrl || '';
        document.getElementById('modalJudul').innerText = data.judul || '-';
        document.getElementById('modalSubJudul').innerText = data.sub_judul || '';
        document.getElementById('modalDeskripsi').innerText = data.deskripsi || data.sub_judul || 'Tidak ada deskripsi detail.';

        // Badge Target
        var isProduct = (data.target_type === 'product' || data.target_type === 'produk' || (data.id_produk && data.id_produk > 0));
        var targetBadge = document.getElementById('modalTargetBadge');
        var targetDetail = document.getElementById('modalTargetDetail');

        if (isProduct) {
            targetBadge.innerText = 'Promosi Produk';
            targetBadge.className = 'rounded-lg bg-purple-600/90 backdrop-blur-sm px-2.5 py-1 text-[11px] font-black uppercase text-white shadow-sm';
            targetDetail.innerText = data.nama_produk ? data.nama_produk : ('Produk ID #' + data.id_produk);
        } else {
            targetBadge.innerText = 'Branding Toko';
            targetBadge.className = 'rounded-lg bg-blue-600/90 backdrop-blur-sm px-2.5 py-1 text-[11px] font-black uppercase text-white shadow-sm';
            targetDetail.innerText = data.name_store || data.name || 'Profil Toko Publik';
        }

        // Badge Promo
        var badgePromo = document.getElementById('modalBadgePromo');
        if (data.badge_promo) {
            badgePromo.innerText = data.badge_promo;
            badgePromo.classList.remove('hidden');
        } else {
            badgePromo.classList.add('hidden');
        }

        // Mitra Info
        var defaultFotoSvg = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22128%22%20height%3D%22128%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20128%20128%22%3E%3Ccircle%20cx%3D%2264%22%20cy%3D%2264%22%20r%3D%2264%22%20fill%3D%22%23e2e8f0%22%2F%3E%3Cpath%20d%3D%22M64%2064a24%2024%200%20100-48%2024%2024%200%20000%2048zm0%2012c-26.7%200-48%2016-48%2036v8h96v-8c0-20-21.3-36-48-36z%22%20fill%3D%22%2394a3b8%22%2F%3E%3C%2Fsvg%3E';
        var fotoUrl = defaultFotoSvg;
        if (data.foto && !['belum di isi', 'belum di isi.', 'null', '-'].includes(data.foto.toLowerCase())) {
            var rawFoto = data.foto.trim();
            if (rawFoto.startsWith('http://') || rawFoto.startsWith('https://') || rawFoto.startsWith('//')) {
                fotoUrl = rawFoto;
            } else if (rawFoto.startsWith('assets/') || rawFoto.startsWith('images/')) {
                fotoUrl = '{{ asset("") }}/' + rawFoto.replace(/^\/+/, '');
            } else {
                fotoUrl = '{{ asset("assets/image/customers/profile") }}/' + rawFoto;
            }
        }
        var modalFotoEl = document.getElementById('modalMitraFoto');
        modalFotoEl.src = fotoUrl;
        modalFotoEl.onerror = function() { this.src = defaultFotoSvg; };
        document.getElementById('modalMitraName').innerText = data.name || '-';
        document.getElementById('modalMitraStore').innerText = data.name_store ? ('Toko: ' + data.name_store) : 'Mitra Toko';
        document.getElementById('modalMitraEmail').innerText = data.email || '-';
        document.getElementById('modalMitraPhone').innerText = data.nomor_telephone || '-';

        // Jadwal & Biaya
        var nominal = data.nominal_bayar || data.harga_iklan || 0;
        document.getElementById('modalNominal').innerText = 'Rp ' + Number(nominal).toLocaleString('id-ID');

        var statusBayar = data.status_bayar || '-';
        var midtransStatus = data.midtrans_transaction_status || '';
        var statusBayarEl = document.getElementById('modalStatusBayar');

        if (statusBayar === 'lunas' || midtransStatus === 'settlement') {
            statusBayarEl.innerHTML = '<span class="text-emerald-600 font-bold">Lunas (Midtrans)</span>';
        } else if (midtransStatus === 'pending') {
            statusBayarEl.innerHTML = '<span class="text-amber-600 font-bold">Menunggu Pembayaran</span>';
        } else {
            statusBayarEl.innerHTML = '<span class="text-slate-500 font-bold">' + statusBayar.toUpperCase() + '</span>';
        }

        var tglMulai = data.tanggal_mulai ? new Date(data.tanggal_mulai).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
        var tglAkhir = data.tanggal_akhir ? new Date(data.tanggal_akhir).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
        document.getElementById('modalJadwal').innerText = tglMulai + ' - ' + tglAkhir;

        var durasi = data.durasi_hari || '-';
        document.getElementById('modalDurasi').innerText = durasi + ' Hari';

        var statusIklan = (data.status_iklan || 'Aktif').toUpperCase();
        var statusIklanEl = document.getElementById('modalStatusIklanBadge');
        if (statusIklan === 'NONAKTIF') {
            statusIklanEl.className = 'font-bold text-red-600';
            statusIklanEl.innerText = 'Ditangguhkan (Suspended)';
        } else if (statusIklan === 'PENDING') {
            statusIklanEl.className = 'font-bold text-amber-600';
            statusIklanEl.innerText = 'Menunggu Giliran (Pending)';
        } else if (statusIklan === 'AKTIF') {
            statusIklanEl.className = 'font-bold text-emerald-600';
            statusIklanEl.innerText = 'Aktif Tayang di Mobile';
        } else {
            statusIklanEl.className = 'font-bold text-slate-500';
            statusIklanEl.innerText = statusIklan;
        }

        // Section Info Suspend
        var suspendSection = document.getElementById('modalSuspendSection');
        if (data.suspend_alasan || statusIklan === 'NONAKTIF') {
            suspendSection.classList.remove('hidden');
            document.getElementById('modalSuspendAlasan').innerText = data.suspend_alasan ? ('Alasan: ' + data.suspend_alasan) : 'Alasan: Ditangguhkan oleh admin.';
            document.getElementById('modalSuspendCatatan').innerText = data.suspend_catatan || 'Tidak ada catatan khusus dari admin.';

            var statusRevisiBadge = document.getElementById('modalSuspendStatusRevisiBadge');
            if (data.suspend_status_revisi === 'sudah_diperbaiki') {
                statusRevisiBadge.innerText = 'Sudah Direvisi Mitra';
                statusRevisiBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200';
            } else {
                statusRevisiBadge.innerText = 'Menunggu Revisi Mitra';
                statusRevisiBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200';
            }

            var bagianContainer = document.getElementById('modalSuspendBagianRevisi');
            bagianContainer.innerHTML = '';
            var bagianList = [];
            try {
                bagianList = typeof data.suspend_bagian_revisi === 'string' ? JSON.parse(data.suspend_bagian_revisi) : (data.suspend_bagian_revisi || []);
            } catch (e) {
                bagianList = [];
            }
            if (Array.isArray(bagianList) && bagianList.length > 0) {
                bagianList.forEach(function(item) {
                    var span = document.createElement('span');
                    span.className = 'rounded-md bg-red-100 text-red-800 px-2 py-0.5 text-[10px] font-bold uppercase';
                    span.innerText = 'Wajib Ganti: ' + item;
                    bagianContainer.appendChild(span);
                });
            }
        } else {
            suspendSection.classList.add('hidden');
        }

        // Footer Actions: HANYA tampilkan tombol Suspend jika iklan AKTIF. Jika NONAKTIF, tampilkan tombol Pulihkan.
        var footerActions = document.getElementById('modalFooterActions');
        footerActions.innerHTML = '';
        if (statusIklan === 'AKTIF') {
            var btnSuspend = document.createElement('button');
            btnSuspend.type = 'button';
            btnSuspend.className = 'rounded-xl bg-amber-50 px-4 py-2 text-xs font-bold text-amber-700 hover:bg-amber-500 hover:text-white transition flex items-center gap-1.5 active:scale-95';
            btnSuspend.innerHTML = '<i class="fi fi-rr-ban text-xs"></i> Suspend Iklan Ini';
            btnSuspend.onclick = function() {
                closeDetailModal();
                openSuspendModal(data, posterUrl);
            };
            footerActions.appendChild(btnSuspend);
        } else if (statusIklan === 'NONAKTIF') {
            var formPulihkan = document.createElement('form');
            formPulihkan.method = 'POST';
            formPulihkan.action = '{{ route("iklan.aktivasi", ":id") }}'.replace(':id', data.id_detail_iklan);
            formPulihkan.innerHTML = `@csrf @method('PUT')
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition flex items-center gap-1.5 active:scale-95 shadow-sm">
                    <i class="fi fi-rr-play text-xs"></i> Pulihkan / Aktifkan Iklan
                </button>`;
            footerActions.appendChild(formPulihkan);
        }

        var modal = document.getElementById('modalDetailIklan');
        modal.classList.remove('hidden');
    }

    function closeDetailModal() {
        var modal = document.getElementById('modalDetailIklan');
        modal.classList.add('hidden');
    }

    function openSuspendModal(data, posterUrl) {
        var form = document.getElementById('formSuspendIklan');
        form.action = '{{ route("iklan.nonaktifkan", ":id") }}'.replace(':id', data.id_detail_iklan);

        var defaultFallback = '{{ asset("assets/image/customers/advert/iklan_default.png") }}';
        document.getElementById('suspendPreviewImg').src = posterUrl || defaultFallback;
        document.getElementById('suspendPreviewJudul').innerText = data.judul || '-';
        document.getElementById('suspendPreviewMitra').innerText = 'Mitra: ' + (data.name_store || data.name || '-');

        // Reset form fields
        document.getElementById('suspendAlasanSelect').value = '';
        form.querySelectorAll('input[type="checkbox"]').forEach(function(cb) { cb.checked = false; });
        form.querySelector('textarea[name="catatan"]').value = '';

        document.getElementById('modalSuspendIklan').classList.remove('hidden');
    }

    function closeSuspendModal() {
        document.getElementById('modalSuspendIklan').classList.add('hidden');
    }

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModal();
            closeSuspendModal();
        }
    });

    // Close on backdrop click
    document.getElementById('modalDetailIklan').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });

    document.getElementById('modalSuspendIklan').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSuspendModal();
        }
    });
</script>
@endpush
@endsection