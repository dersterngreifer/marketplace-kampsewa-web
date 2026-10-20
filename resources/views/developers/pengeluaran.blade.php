@include('components.modals.tambah-pengeluaran-dev')
@include('components.modals.edit-pengeluaran-dev')
@include('components.modals.import-pengeluaran-dev')
@extends('layouts.developers.ly-dashboard')
@section('content')
    <div class="--container w-full h-auto p-8 flex flex-col gap-6">
        {{-- Filter Tab Penghasilan / Pengeluaran --}}
        <div class="--component-filter w-full">
            <ul class="flex items-center gap-2">
                <li class="text-[12px] font-medium text-gray-500">Filter:</li>
                <li>
                    <a class="{{ $title == 'Penghasilan' ? 'bg-[#080E2E] text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} text-[14px] font-medium px-5 py-2 rounded-full transition"
                        href="{{ route('penghasilan.index') }}">Penghasilan</a>
                </li>
                <li>
                    <a class="{{ $title == 'Pengeluaran' ? 'bg-[#080E2E] text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} text-[14px] font-medium px-5 py-2 rounded-full transition"
                        href="{{ route('pengeluaran.index') }}">Pengeluaran</a>
                </li>
            </ul>
        </div>
        <hr class="border-gray-200">

        {{-- Bagian Atas: Statistik & Grafik Pengeluaran --}}
        <div class="--wrapper-card w-full h-auto grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
            {{-- Card 1: Pengeluaran Pertahun --}}
            <div class="--card-pengeluaran-pertahun bg-white p-5 flex flex-col justify-between gap-4 shadow-box-shadow-8 rounded-[20px] border border-gray-100">
                <div class="--header flex items-start justify-between">
                    <div class="--sub-header">
                        <p class="text-gray-400 font-medium text-[14px]">Pengeluaran Pertahun - {{ $currentYear }}</p>
                        <p class="text-[26px] font-bold text-gray-900 my-1">
                            Rp. {{ number_format($pengeluaran_tahun_ini, 0, ',', '.') }}
                        </p>
                        <div class="mt-1">
                            @if ($persentase_perubahan >= 0)
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-600 border border-rose-100">
                                    <i class="bi bi-arrow-up-right"></i> Naik {{ number_format($persentase_perubahan, 1) }}% dari tahun lalu
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <i class="bi bi-arrow-down-right"></i> Turun {{ number_format(abs($persentase_perubahan), 1) }}% dari tahun lalu
                                </span>
                            @endif
                        </div>
                    </div>
                    <span class="px-3.5 py-1.5 text-xs font-bold rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                        Tahun {{ $currentYear }}
                    </span>
                </div>

                <div class="--body w-full flex-1 flex items-center my-1">
                    <canvas id="chart-pengeluaran-pertahun" class="max-h-[260px] w-full"></canvas>
                </div>

                <div class="footer grid w-full grid-cols-1 sm:grid-cols-2 gap-2 pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2.5 bg-slate-50 p-2.5 rounded-2xl border border-slate-100">
                        <div class="w-9 h-9 bg-white rounded-xl shadow-sm flex items-center justify-center text-rose-500 shrink-0">
                            <i class="bi bi-calendar2-minus text-[15px]"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-400 font-medium">Tahun {{ $currentYear - 1 }}</p>
                            <p class="text-[13px] font-bold text-gray-800 truncate">Rp. {{ number_format($pengeluaran_tahun_lalu, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 bg-slate-50 p-2.5 rounded-2xl border border-slate-100">
                        <div class="w-9 h-9 bg-white rounded-xl shadow-sm flex items-center justify-center text-slate-600 shrink-0">
                            <i class="bi bi-folder-check text-[15px]"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-400 font-medium">Tahun {{ $currentYear - 2 }}</p>
                            <p class="text-[13px] font-bold text-gray-800 truncate">Rp. {{ number_format($pengeluaran_dua_tahun_lalu, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Pengeluaran Perbulan & Ringkasan Harian --}}
            <div class="--card-pengeluaran-perbulan bg-white p-5 flex flex-col justify-between gap-4 shadow-box-shadow-8 rounded-[20px] border border-gray-100">
                <div class="--header flex items-start justify-between">
                    <div class="--sub-header">
                        <p class="text-gray-400 font-medium text-[14px]">Pengeluaran Bulan Ini - {{ $currentMonthNameIndo }} {{ $currentYear }}</p>
                        <p class="text-[26px] font-bold text-gray-900 my-1">
                            Rp. {{ number_format($monthCurrentTotal, 0, ',', '.') }}
                        </p>
                        <div class="mt-1">
                            @if ($persentase_perubahan_bulan >= 0)
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-600 border border-rose-100">
                                    <i class="bi bi-arrow-up-right"></i> Naik {{ number_format($persentase_perubahan_bulan, 1) }}% dari bulan lalu
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <i class="bi bi-arrow-down-right"></i> Turun {{ number_format(abs($persentase_perubahan_bulan), 1) }}% dari bulan lalu
                                </span>
                            @endif
                        </div>
                    </div>
                    <span class="px-3.5 py-1.5 text-xs font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-100">
                        {{ $currentMonthNameIndo }}
                    </span>
                </div>

                <div class="--body w-full flex-1 flex items-center my-1">
                    <canvas id="chart-pengeluaran-perbulan" class="max-h-[260px] w-full"></canvas>
                </div>

                {{-- Footer: Pengeluaran Harian & Rata-rata --}}
                <div class="footer grid w-full grid-cols-1 sm:grid-cols-2 gap-2 pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2.5 bg-rose-50/50 p-2.5 rounded-2xl border border-rose-100">
                        <div class="w-9 h-9 bg-white rounded-xl shadow-sm flex items-center justify-center text-rose-600 shrink-0">
                            <i class="bi bi-clock-history text-[15px]"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-500 font-medium">Hari Ini ({{ $todayNameIndo }})</p>
                            <p class="text-[13px] font-bold text-gray-900 truncate">Rp. {{ number_format($dayCurrentTotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 bg-slate-50 p-2.5 rounded-2xl border border-slate-100">
                        <div class="w-9 h-9 bg-white rounded-xl shadow-sm flex items-center justify-center text-violet-600 shrink-0">
                            <i class="bi bi-calculator text-[15px]"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-500 font-medium">Rata-rata Harian</p>
                            <p class="text-[13px] font-bold text-gray-900 truncate">Rp. {{ number_format($avgDailyExpenseMonth, 0, ',', '.') }}/hari</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Aktivitas Input Pengeluaran oleh Admin KampSewa (Ditaruh Di Atas Distribusi Kategori Pengeluaran) --}}
        <div class="p-6 bg-white shadow-box-shadow-8 rounded-[24px] border border-gray-100 flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <h3 class="text-base font-black text-slate-900">Aktivitas Input Pengeluaran oleh Admin KampSewa</h3>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">Informasi rekan admin yang mencatat pos pengeluaran operasional perusahaan, riwayat terbaru & terlama, serta tautan profil admin.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-3 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-xl border border-rose-100">
                        <i class="bi bi-person-check-fill mr-1"></i> {{ $adminPengeluaranStats->count() }} Admin Berkontribusi
                    </span>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200">
                        Total Input: {{ number_format($totalAdminInputCount, 0, ',', '.') }} Data
                    </span>
                </div>
            </div>

            {{-- 2 Highlight Cards: Pengeluaran Terbaru & Terlama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                {{-- Pengeluaran Terbaru --}}
                <div class="p-4 rounded-2xl bg-gradient-to-r from-rose-500/10 via-pink-500/5 to-transparent border border-rose-200/80 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i class="bi bi-clock-history text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-rose-700">Pengeluaran Terbaru</span>
                            @if ($latestPengeluaran)
                                <p class="text-xs font-black text-slate-900 truncate">
                                    {{ $latestPengeluaran->user->name ?? 'Admin KampSewa' }} &bull;
                                    <span class="text-rose-600 font-bold">Rp. {{ number_format($latestPengeluaran->nominal, 0, ',', '.') }}</span>
                                </p>
                                <p class="text-[11px] text-slate-400 truncate">
                                    {{ $latestPengeluaran->kategori_pengeluaran ?? 'Operasional' }} &bull; {{ $latestPengeluaran->sumber }} &bull; {{ Carbon\Carbon::parse($latestPengeluaran->created_at)->diffForHumans() }}
                                </p>
                            @else
                                <p class="text-xs text-slate-400">Belum ada transaksi pengeluaran</p>
                            @endif
                        </div>
                    </div>
                    @if ($latestPengeluaran && $latestPengeluaran->user)
                        <a href="{{ route('profile.index', ['nama_lengkap' => $latestPengeluaran->user->name]) }}"
                            class="px-2.5 py-1.5 rounded-xl bg-white border border-rose-200 text-rose-700 hover:bg-rose-50 text-[11px] font-bold shrink-0 transition active:scale-95 shadow-2xs">
                            Profil &rarr;
                        </a>
                    @endif
                </div>

                {{-- Pengeluaran Terlama --}}
                <div class="p-4 rounded-2xl bg-gradient-to-r from-amber-500/10 via-orange-500/5 to-transparent border border-amber-200/80 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-amber-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i class="bi bi-hourglass-split text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-700">Pengeluaran Terlama</span>
                            @if ($oldestPengeluaran)
                                <p class="text-xs font-black text-slate-900 truncate">
                                    {{ $oldestPengeluaran->user->name ?? 'Admin KampSewa' }} &bull;
                                    <span class="text-amber-700 font-bold">Rp. {{ number_format($oldestPengeluaran->nominal, 0, ',', '.') }}</span>
                                </p>
                                <p class="text-[11px] text-slate-400 truncate">
                                    {{ $oldestPengeluaran->kategori_pengeluaran ?? 'Operasional' }} &bull; {{ $oldestPengeluaran->sumber }} &bull; {{ Carbon\Carbon::parse($oldestPengeluaran->created_at)->translatedFormat('d M Y') }}
                                </p>
                            @else
                                <p class="text-xs text-slate-400">Belum ada transaksi pengeluaran</p>
                            @endif
                        </div>
                    </div>
                    @if ($oldestPengeluaran && $oldestPengeluaran->user)
                        <a href="{{ route('profile.index', ['nama_lengkap' => $oldestPengeluaran->user->name]) }}"
                            class="px-2.5 py-1.5 rounded-xl bg-white border border-amber-200 text-amber-700 hover:bg-amber-50 text-[11px] font-bold shrink-0 transition active:scale-95 shadow-2xs">
                            Profil &rarr;
                        </a>
                    @endif
                </div>
            </div>

            {{-- Grid Rekan Admin Kontributor --}}
            <div>
                <p class="text-xs font-bold text-slate-500 mb-2.5">Daftar Admin Pencatat Pengeluaran:</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2.5">
                    @forelse ($adminPengeluaranStats as $adm)
                        <a href="{{ route('profile.index', ['nama_lengkap' => $adm->name]) }}"
                            class="p-3 rounded-2xl bg-slate-50 hover:bg-white border border-slate-200/80 hover:border-rose-300 hover:shadow-md transition group flex flex-col gap-1.5">
                            <div class="flex items-center gap-2">
                                @include('components.ui.avatar', [
                                    'nama' => $adm->name,
                                    'foto' => $adm->foto,
                                    'type' => 1,
                                    'class' => 'w-8 h-8 shrink-0',
                                    'rounded' => 'rounded-full',
                                    'text' => 'text-xs',
                                    'extra' => 'border border-slate-200',
                                ])
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-black text-slate-800 truncate group-hover:text-rose-700 transition">
                                        {{ $adm->name }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">{{ $adm->pengeluaran_perusahaan_count }} input</p>
                                </div>
                                <i class="bi bi-chevron-right text-xs text-slate-300 group-hover:text-rose-600 group-hover:translate-x-0.5 transition-transform"></i>
                            </div>
                            <div class="pt-1 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                                <span class="text-slate-400 text-[10px]">Total:</span>
                                <span class="font-bold text-rose-600">Rp. {{ number_format($adm->pengeluaran_perusahaan_sum_nominal, 0, ',', '.') }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-4 text-center text-xs text-slate-400">
                            Belum ada admin yang menginputkan data pengeluaran.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Ringkasan Kategori Pengeluaran (Chips Filter Interaktif) --}}
        <div class="p-5 bg-white shadow-box-shadow-8 rounded-[20px] border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                <div>
                    <h3 class="text-sm font-black text-slate-900">Distribusi Kategori Pengeluaran</h3>
                    <p class="text-xs text-slate-400">Klik kategori di bawah ini untuk memfilter data pada tabel secara langsung.</p>
                </div>
                @if ($kategoriFilter && $kategoriFilter !== 'all')
                    <a href="{{ route('pengeluaran.index') }}" class="text-xs font-bold text-rose-600 hover:underline">
                        <i class="bi bi-x-circle mr-1"></i>Reset Filter ({{ $kategoriFilter === 'manual' ? 'Input Manual Admin' : ($kategoriFilter === 'otomatis' ? 'Otomatis Sistem' : $kategoriFilter) }})
                    </a>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Chip: Semua Kategori --}}
                <a href="{{ route('pengeluaran.index', array_merge(request()->except('kategori'), ['kategori' => 'all'])) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ empty($kategoriFilter) || $kategoriFilter === 'all' ? 'bg-[#080E2E] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    <i class="bi bi-grid-fill text-[11px]"></i>
                    <span>Semua Kategori</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-md {{ empty($kategoriFilter) || $kategoriFilter === 'all' ? 'bg-white/20 text-white' : 'bg-white text-slate-600' }} text-[10px]">
                        {{ number_format($totalPengeluaranAll ?? $list_pengeluaran->total()) }}
                    </span>
                </a>

                {{-- Chip: Input Manual Admin (Sama persis dengan halaman penghasilan) --}}
                <a href="{{ route('pengeluaran.index', array_merge(request()->except('kategori'), ['kategori' => 'manual'])) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $kategoriFilter === 'manual' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200' }}">
                    <i class="bi bi-person-fill text-[11px]"></i>
                    <span>Input Manual Admin</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-md {{ $kategoriFilter === 'manual' ? 'bg-white/20 text-white' : 'bg-white text-rose-800' }} text-[10px]">
                        {{ number_format($totalAdminInputCount) }}
                    </span>
                </a>

                @if (($totalOtomatisInputCount ?? 0) > 0)
                    {{-- Chip: Otomatis Sistem --}}
                    <a href="{{ route('pengeluaran.index', array_merge(request()->except('kategori'), ['kategori' => 'otomatis'])) }}"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $kategoriFilter === 'otomatis' ? 'bg-sky-600 text-white shadow-sm' : 'bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200' }}">
                        <i class="bi bi-cpu text-[11px]"></i>
                        <span>Otomatis Sistem</span>
                        <span class="ml-1 px-1.5 py-0.2 rounded-md {{ $kategoriFilter === 'otomatis' ? 'bg-white/20 text-white' : 'bg-white text-sky-800' }} text-[10px]">
                            {{ number_format($totalOtomatisInputCount) }}
                        </span>
                    </a>
                @endif

                @foreach ($kategoriList as $kat)
                    @php
                        $isActive = ($kategoriFilter === $kat->kategori_pengeluaran);
                    @endphp
                    <a href="{{ route('pengeluaran.index', array_merge(request()->except('kategori'), ['kategori' => $kat->kategori_pengeluaran])) }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold transition border {{ $isActive ? 'bg-rose-500 text-white border-transparent shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border-slate-200' }}">
                        <span>{{ $kat->kategori_pengeluaran }}</span>
                        <span class="ml-1 px-1.5 py-0.2 rounded-md {{ $isActive ? 'bg-white/20 text-white' : 'bg-white text-slate-600' }} text-[10px]">
                            Rp. {{ number_format($kat->total, 0, ',', '.') }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Bagian Bawah: Data Tabel Pengeluaran & Action Bar --}}
        <div class="--component-kedua mt-2">
            <div class="--title text-[18px] font-bold text-gray-800 mb-3">Data Pengeluaran Perusahaan</div>
            
            {{-- Search & Action Bar (Sejajar dan Lurus Sempurna dengan Tinggi Sama h-10) --}}
            <div class="flex flex-col lg:flex-row w-full justify-between items-stretch lg:items-center gap-3 mb-4">
                {{-- Total pengeluaran counter --}}
                <div class="_total flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center h-10 px-3.5 bg-rose-50 text-rose-700 text-xs font-bold rounded-xl border border-rose-200">
                        <i class="bi bi-wallet2 text-rose-600 mr-1.5 text-sm"></i> {{ number_format($list_pengeluaran->total(), 0, ',', '.') }} Transaksi Pengeluaran
                    </span>
                    <span class="inline-flex items-center h-10 px-3.5 bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-200">
                        <i class="bi bi-cash-stack text-slate-600 mr-1.5 text-sm"></i> Total Rp. {{ number_format($pengeluaran_tahun_ini, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Action Group: Search, Export, Import, Tambah (Sejajar Sempurna) --}}
                <div class="_search-filter flex flex-wrap items-center gap-2.5">
                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('pengeluaran.index') }}" class="relative m-0 p-0 flex items-center">
                        @if ($kategoriFilter)
                            <input type="hidden" name="kategori" value="{{ $kategoriFilter }}">
                        @endif
                        <input class="h-10 w-[240px] sm:w-[260px] pl-9 pr-8 text-xs font-medium text-slate-800 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition"
                            type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari sumber/deskripsi...">
                        <i class="bi bi-search absolute left-3 text-slate-400 text-xs pointer-events-none"></i>
                        @if (!empty($search))
                            <a href="{{ route('pengeluaran.index') }}" class="absolute right-2.5 text-slate-400 hover:text-slate-600 text-xs" title="Reset pencarian">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                    </form>

                    {{-- Tombol Export --}}
                    <a href="{{ route('pengeluaran.export') }}" title="Export Data Pengeluaran ke File CSV/Excel"
                        class="inline-flex items-center justify-center h-10 px-3.5 gap-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold rounded-xl shadow-sm transition active:scale-95">
                        <i class="fi fi-rr-inbox-out text-[13px] text-rose-600"></i>
                        <span>Export</span>
                    </a>

                    {{-- Tombol Import --}}
                    <button type="button" onclick="modalHandlerImportPengeluaran(true)" title="Import Data Pengeluaran dari File CSV"
                        class="inline-flex items-center justify-center h-10 px-3.5 gap-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold rounded-xl shadow-sm transition active:scale-95">
                        <i class="fi fi-rr-inbox-in text-[13px] text-violet-600"></i>
                        <span>Import</span>
                    </button>

                    {{-- Tombol Tambah Pengeluaran --}}
                    <button type="button" onclick="modalHandlerPengeluaran(true)"
                        class="inline-flex items-center justify-center h-10 px-4 gap-1.5 bg-rose-500 hover:bg-rose-600 cursor-pointer text-white font-bold text-xs rounded-xl shadow-md transition active:scale-95">
                        <i class="bi bi-plus-lg text-sm"></i>
                        <span>Tambah Pengeluaran</span>
                    </button>
                </div>
            </div>

            {{-- Table Pengeluaran Modern Putih --}}
            <div class="--table w-full h-auto mt-2">
                <div class="relative w-full overflow-hidden shadow-box-shadow-11 rounded-[20px] bg-white border border-gray-100">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5">#</th>
                                    <th scope="col" class="px-6 py-3.5">Kategori</th>
                                    <th scope="col" class="px-6 py-3.5">Sumber / Vendor</th>
                                    <th scope="col" class="px-6 py-3.5">Tanggal</th>
                                    <th scope="col" class="px-6 py-3.5">Deskripsi</th>
                                    <th scope="col" class="px-6 py-3.5">Nominal</th>
                                    <th scope="col" class="px-6 py-3.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($list_pengeluaran as $index => $item)
                                    @php
                                        $badgeClass = 'bg-slate-50 text-slate-700 border-slate-200';
                                        if (stripos($item->kategori_pengeluaran, 'cloud') !== false || stripos($item->kategori_pengeluaran, 'server') !== false) {
                                            $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                        } elseif (stripos($item->kategori_pengeluaran, 'operasional') !== false) {
                                            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                        } elseif (stripos($item->kategori_pengeluaran, 'maintenance') !== false) {
                                            $badgeClass = 'bg-purple-50 text-purple-700 border-purple-200';
                                        } elseif (stripos($item->kategori_pengeluaran, 'gaji') !== false || stripos($item->kategori_pengeluaran, 'sdm') !== false) {
                                            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        } elseif (stripos($item->kategori_pengeluaran, 'pemasaran') !== false || stripos($item->kategori_pengeluaran, 'promosi') !== false) {
                                            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                        }
                                    @endphp
                                    <tr class="bg-white hover:bg-gray-50/80 transition">
                                        <td class="px-4 py-4 text-xs font-medium text-gray-400">
                                            {{ $list_pengeluaran->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $badgeClass }}">
                                                {{ $item->kategori_pengeluaran ?? 'Operasional' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-900">
                                            {{ $item->sumber }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                            <span class="py-1 px-3 bg-[#FEF2F2] text-[#EF4444] font-medium rounded-full border border-rose-100">
                                                {{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 max-w-[320px] text-gray-700 text-xs truncate" title="{{ $item->deskripsi }}">
                                            {{ $item->deskripsi }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="font-black text-rose-600 text-sm whitespace-nowrap">
                                                    Rp. {{ number_format($item->nominal, 0, ',', '.') }}
                                                </span>
                                                @if ($item->user)
                                                    <a href="{{ route('profile.index', ['nama_lengkap' => $item->user->name]) }}"
                                                        title="Diinput oleh: {{ $item->user->name }} (Klik untuk lihat profil)"
                                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 text-[11px] font-bold shadow-2xs transition active:scale-95 group">
                                                        <i class="bi bi-arrow-up-right text-[10px] group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                                        <span class="truncate max-w-[85px]">{{ $item->user->name }}</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="inline-flex items-center justify-center gap-1.5">
                                                {{-- Tombol Edit Setiap Row Data --}}
                                                <button type="button"
                                                    onclick="openEditPengeluaranModal({{ $item->id }}, '{{ addslashes($item->kategori_pengeluaran ?? 'Operasional') }}', '{{ addslashes($item->sumber) }}', '{{ addslashes($item->deskripsi) }}', {{ $item->nominal }})"
                                                    class="w-8 h-8 inline-flex items-center justify-center text-violet-600 hover:text-violet-800 bg-violet-50 hover:bg-violet-100 rounded-xl transition shadow-2xs" title="Edit Data Pengeluaran">
                                                    <i class="bi bi-pencil-square text-sm"></i>
                                                </button>

                                                {{-- Tombol Hapus dengan Konfirmasi Dialog Bagus (SweetAlert2) --}}
                                                <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" class="inline-flex items-center m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDeletePengeluaran(this.form)"
                                                        class="w-8 h-8 inline-flex items-center justify-center text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 rounded-xl transition shadow-2xs" title="Hapus Data Pengeluaran">
                                                        <i class="bi bi-trash-fill text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                            <i class="bi bi-inbox text-[36px] block mb-2 text-gray-300"></i>
                                            <p class="font-medium text-gray-500">Tidak ada data pengeluaran ditemukan.</p>
                                            @if ($search || $kategoriFilter)
                                                <a href="{{ route('pengeluaran.index') }}" class="text-rose-600 text-xs underline mt-1 inline-block">Reset filter pencarian</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination Links Menggunakan custom-pagination --}}
                <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-4 px-2">
                    <p class="text-xs text-gray-500 font-medium">
                        Menampilkan {{ $list_pengeluaran->firstItem() ?? 0 }} - {{ $list_pengeluaran->lastItem() ?? 0 }} dari {{ $list_pengeluaran->total() }} data
                    </p>
                    <div>
                        {{ $list_pengeluaran->onEachSide(1)->links('components.paginate.custom-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pass dynamic chart datasets to global window object --}}
    <script>
        window.pengeluaranPertahunData = @json($monthlyExpenseData);
        window.pengeluaranPerbulanLabels = @json($recentMonthsLabels);
        window.pengeluaranPerbulanData = @json($recentMonthsData);

        // Modal Tambah Pengeluaran
        const modalTambahPengeluaran = document.getElementById('modal-tambah-pengeluaran-dev');

        function modalHandlerPengeluaran(val) {
            if (modalTambahPengeluaran) {
                if (val) {
                    modalTambahPengeluaran.classList.remove('hidden');
                } else {
                    modalTambahPengeluaran.classList.add('hidden');
                }
            }
        }

        // Modal Edit Pengeluaran
        const modalEditPengeluaran = document.getElementById('modal-edit-pengeluaran');
        const formEditPengeluaran = document.getElementById('form-edit-pengeluaran');

        function modalHandlerEditPengeluaran(val) {
            if (modalEditPengeluaran) {
                if (val) {
                    modalEditPengeluaran.classList.remove('hidden');
                } else {
                    modalEditPengeluaran.classList.add('hidden');
                }
            }
        }

        function openEditPengeluaranModal(id, kategori, sumber, deskripsi, nominal) {
            if (!formEditPengeluaran) return;
            formEditPengeluaran.action = `/developer/dashboard/pengeluaran/${id}`;
            document.getElementById('edit_kategori_pengeluaran').value = kategori;
            document.getElementById('edit_sumber_pengeluaran').value = sumber;
            document.getElementById('edit_deskripsi_pengeluaran').value = deskripsi;
            document.getElementById('edit_nominal_pengeluaran').value = nominal;
            modalHandlerEditPengeluaran(true);
        }

        // Modal Import Pengeluaran
        const modalImportPengeluaran = document.getElementById('modal-import-pengeluaran');

        function modalHandlerImportPengeluaran(val) {
            if (modalImportPengeluaran) {
                if (val) {
                    modalImportPengeluaran.classList.remove('hidden');
                } else {
                    modalImportPengeluaran.classList.add('hidden');
                }
            }
        }

        // Konfirmasi Hapus Dialog Bagus (SweetAlert2)
        function confirmDeletePengeluaran(form) {
            Swal.fire({
                title: 'Hapus Catatan Pengeluaran?',
                text: 'Data pengeluaran ini akan dihapus secara permanen dari sistem.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#94A3B8',
                confirmButtonText: '<i class="bi bi-trash-fill mr-1"></i> Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl shadow-2xl border border-slate-100',
                    confirmButton: 'rounded-xl font-bold px-4 py-2.5 shadow-md',
                    cancelButton: 'rounded-xl font-bold px-4 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    @push('scripts')
        <script>
            if (typeof window.initPengeluaranPertahunChart === 'function') {
                window.initPengeluaranPertahunChart();
            }
            if (typeof window.initPengeluaranPerbulanChart === 'function') {
                window.initPengeluaranPerbulanChart();
            }
        </script>
    @endpush
@endsection
