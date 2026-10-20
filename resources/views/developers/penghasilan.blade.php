@include('components.modals.tambah-pemasukan-dev')
@include('components.modals.edit-pemasukan-dev')
@include('components.modals.import-pemasukan-dev')
@extends('layouts.developers.ly-dashboard')
@section('content')
    <div class="_container p-8 flex flex-col gap-6">
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

        {{-- Bagian Atas: Statistik & Grafik --}}
        <div class="--component-pertama w-full h-auto m-auto grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
            {{-- Kolom Kiri: Card Total Pertahun --}}
            <div class="--card-penghasilan-tahun-ini w-full h-full flex flex-col">
                <div class="--title-heading mb-3 flex items-center justify-between">
                    <p class="font-bold text-[18px] text-gray-800">Total Pertahun</p>
                </div>
                <div class="--card w-full h-full bg-white shadow-box-shadow-8 rounded-[20px] p-5 flex flex-col justify-between gap-4 border border-gray-100">
                    <div class="--header flex justify-between items-start">
                        <div class="--sub-1">
                            <p class="text-gray-400 font-medium text-[14px]">Tahun {{ $currentYear }}:</p>
                            <p class="text-[26px] font-bold text-gray-900 my-1">
                                Rp. {{ number_format($penghasilan_tahun_ini, 0, ',', '.') }}
                            </p>
                            <div class="--penghasilan-tahun-lalu mt-2">
                                <p class="text-[12px] text-gray-500 font-normal">Total penghasilan tahun lalu ({{ $currentYear - 1 }}):</p>
                                <p class="font-semibold text-[15px] text-gray-700">
                                    Rp. {{ number_format($penghasilan_tahun_lalu, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="--sub-2">
                            @if ($persentase_perubahan >= 0)
                                <span class="py-2 px-4 bg-[#F0FDF4] text-[#4AD07B] text-[13px] font-bold rounded-full flex items-center gap-1">
                                    Naik {{ number_format($persentase_perubahan, 1) }}% <i class="bi bi-arrow-up-right"></i>
                                </span>
                            @else
                                <span class="py-2 px-4 bg-[#FEF2F2] text-[#EF4444] text-[13px] font-bold rounded-full flex items-center gap-1">
                                    Turun {{ number_format(abs($persentase_perubahan), 1) }}% <i class="bi bi-arrow-down-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="--body w-full flex-1 flex items-center">
                        <canvas id="penghasilan" class="max-h-[290px] w-full"></canvas>
                    </div>
                    <div class="--footer text-[12px] text-gray-400 text-right pt-2 border-t border-gray-50">
                        Statistik transaksi penghasilan 12 bulan {{ $currentYear }}
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Total Periode Lainnya & Informasi Relevan Pelengkap --}}
            <div class="--card-penghasilan-hari-minggu-bulan-saat-ini flex flex-col justify-between gap-3 h-full">
                <div>
                    <div class="--title-heading flex items-center justify-between mb-3">
                        <p class="font-bold text-[18px] text-gray-800">Total Periode Lainnya</p>
                    </div>
                    <div class="--card w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Total Perbulan --}}
                        <div class="--card-penghasilan-hari-ini w-full p-5 bg-[#080E2E] rounded-[20px] text-white flex flex-col justify-between shadow-md">
                            <div class="--header">
                                <p class="text-gray-300 font-medium text-[15px] mb-1">Total Perbulan</p>
                                <div class="--total">
                                    <p class="text-[12px] font-normal text-gray-400">Bulan <b>{{ $currentMonthNameIndo }}</b> {{ $currentYear }}</p>
                                    <p class="text-white text-[20px] font-bold my-1">
                                        Rp. {{ number_format($monthCurrentTotal, 0, ',', '.') }}
                                    </p>
                                    @if ($persentase_perubahan_bulan >= 0)
                                        <p class="text-emerald-400 text-[12px] font-medium flex items-center gap-1">
                                            <i class="bi bi-graph-up-arrow"></i> Naik <b>{{ number_format($persentase_perubahan_bulan, 1) }}%</b> dari bulan lalu
                                        </p>
                                    @else
                                        <p class="text-rose-400 text-[12px] font-medium flex items-center gap-1">
                                            <i class="bi bi-graph-down-arrow"></i> Turun <b>{{ number_format(abs($persentase_perubahan_bulan), 1) }}%</b> dari bulan lalu
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="--body my-2 h-[80px]">
                                <canvas id="penghasilan-perbulan"></canvas>
                            </div>
                            <div class="--footer">
                                <p class="text-gray-400 text-[12px] font-medium mb-1">Total Bulan Lalu</p>
                                <div class="--card-total-bulan-lalu bg-white/10 backdrop-blur rounded-[12px] flex items-center justify-between p-2.5 border border-white/10">
                                    <div class="-total">
                                        <p class="font-medium text-gray-300 text-[11px]">Bulan {{ $lastMonthNameIndo }}</p>
                                        <p class="text-white font-bold text-[13px]">
                                            Rp. {{ number_format($monthPreviousTotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="--icon bg-white/20 rounded-lg p-1.5 text-white">
                                        <i class="text-[15px] bi bi-calendar2-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Total Perminggu --}}
                        <div class="--card-penghasilan-minggu-ini w-full p-5 bg-[#C7B4EE] rounded-[20px] text-[#080E2E] flex flex-col justify-between shadow-md">
                            <div class="--header">
                                <p class="text-[#2D1B69] font-medium text-[15px] mb-1">Total Perminggu</p>
                                <div class="--total">
                                    <p class="text-[12px] font-normal text-[#4A3B75]">Minggu Bulan <b>{{ $currentMonthNameIndo }}</b> {{ $currentYear }}</p>
                                    <p class="text-[#080E2E] text-[20px] font-bold my-1">
                                        Rp. {{ number_format($weekCurrentTotal, 0, ',', '.') }}
                                    </p>
                                    @if ($persentase_perubahan_minggu >= 0)
                                        <p class="text-[#15803D] text-[12px] font-medium flex items-center gap-1">
                                            <i class="bi bi-graph-up-arrow"></i> Naik <b>{{ number_format($persentase_perubahan_minggu, 1) }}%</b> dari minggu lalu
                                        </p>
                                    @else
                                        <p class="text-[#B91C1C] text-[12px] font-medium flex items-center gap-1">
                                            <i class="bi bi-graph-down-arrow"></i> Turun <b>{{ number_format(abs($persentase_perubahan_minggu), 1) }}%</b> dari minggu lalu
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="--body my-2 h-[80px]">
                                <canvas id="penghasilan-perminggu"></canvas>
                            </div>
                            <div class="--footer">
                                <p class="text-[#4A3B75] text-[12px] font-medium mb-1">Total Minggu Lalu</p>
                                <div class="--card-total-bulan-lalu bg-white/60 backdrop-blur rounded-[12px] flex items-center justify-between p-2.5 border border-purple-200">
                                    <div class="-total">
                                        <p class="font-medium text-gray-600 text-[11px]">Minggu ke-{{ $previousWeekNumber }}</p>
                                        <p class="text-[#080E2E] font-bold text-[13px]">
                                            Rp. {{ number_format($weekPreviousTotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="--icon bg-[#080E2E] rounded-lg p-1.5 text-white">
                                        <i class="text-[15px] bi bi-calendar2-range"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Penghasilan Hari Ini --}}
                <div class="--card-total-penghasilan-hari-ini flex items-center justify-between p-4 rounded-[18px] bg-gradient-to-r from-[#86C6CF] to-[#68B2BD] text-white shadow-md">
                    <div class="-image-title flex gap-3 items-center">
                        <div class="--image w-[45px] h-[45px] rounded-[12px] flex items-center justify-center bg-white/20">
                            <i class="text-white text-[20px] bi bi-cash-stack"></i>
                        </div>
                        <div class="--title">
                            <p class="font-bold text-white text-[15px]">Penghasilan Hari ini - {{ $todayNameIndo }}</p>
                            <p class="text-white/80 text-[12px]">Mendapatkan <b>Rp. {{ number_format($dayPreviousTotal, 0, ',', '.') }}</b> kemarin.</p>
                        </div>
                    </div>
                    <div class="-total text-[20px] font-extrabold text-white">
                        Rp. {{ number_format($dayCurrentTotal, 0, ',', '.') }}
                    </div>
                </div>

                {{-- INFORMASI RELEVAN PELENGKAP (MENGISI RUANG KOSONG AGAR SEJAJAR DENGAN CARD PERTAHUN) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Mini Card 1: Pemasukan Iklan Mitra --}}
                    <div class="p-3.5 px-4 rounded-[18px] bg-white border border-purple-100 shadow-sm flex items-center justify-between transition hover:border-purple-300">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                <i class="fi fi-rr-ad text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold text-gray-400">Pemasukan Iklan Mitra</p>
                                <p class="text-[15px] font-black text-gray-900 truncate">
                                    Rp. {{ number_format($totalAdRevenueMonth, 0, ',', '.') }}
                                </p>
                                <p class="text-[10px] text-purple-600 font-semibold">Bulan {{ $currentMonthNameIndo }}</p>
                            </div>
                        </div>
                        <a href="{{ route('developer.notifications.index', ['tab' => 'ads']) }}" title="Lihat Notifikasi Sewa Iklan"
                            class="text-gray-300 hover:text-purple-600 transition p-1">
                            <i class="bi bi-chevron-right text-xs"></i>
                        </a>
                    </div>

                    {{-- Mini Card 2: Komisi Sewa Platform --}}
                    <div class="p-3.5 px-4 rounded-[18px] bg-white border border-emerald-100 shadow-sm flex items-center justify-between transition hover:border-emerald-300">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <i class="fi fi-rr-receipt text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold text-gray-400">Komisi Sewa Platform</p>
                                <p class="text-[15px] font-black text-gray-900 truncate">
                                    Rp. {{ number_format($totalCommissionMonth, 0, ',', '.') }}
                                </p>
                                <p class="text-[10px] text-emerald-600 font-semibold">Bulan {{ $currentMonthNameIndo }}</p>
                            </div>
                        </div>
                        <a href="{{ route('developer.notifications.index', ['tab' => 'rentals']) }}" title="Lihat Notifikasi Aktivitas Sewa"
                            class="text-gray-300 hover:text-emerald-600 transition p-1">
                            <i class="bi bi-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>

                {{-- Mini Card 3: Rata-Rata Pemasukan Harian & Transaksi Tertinggi --}}
                <div class="p-3 px-4 rounded-[16px] bg-gradient-to-r from-slate-50 via-white to-slate-50 border border-slate-200/80 flex flex-wrap items-center justify-between gap-2 text-xs">
                    <div class="flex items-center gap-2 text-slate-700">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-violet-100 text-violet-700">
                            <i class="bi bi-calculator text-[11px]"></i>
                        </span>
                        <span class="font-medium text-slate-500">Rata-rata Pemasukan:</span>
                        <b class="text-slate-900 font-bold">Rp. {{ number_format($avgDailyIncomeMonth, 0, ',', '.') }}/hari</b>
                    </div>
                    @if ($highestTransactionMonth)
                        <div class="flex items-center gap-1.5 text-slate-500 text-[11px]">
                            <span class="text-amber-500"><i class="bi bi-trophy-fill"></i></span>
                            <span>Tertinggi Bulan Ini:</span>
                            <b class="text-emerald-600 font-black">Rp. {{ number_format($highestTransactionMonth->nominal, 0, ',', '.') }}</b>
                            <span class="text-slate-400 truncate max-w-[130px]">({{ $highestTransactionMonth->sumber }})</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Section: Aktivitas Input Manual Pemasukan oleh Admin KampSewa --}}
        <div class="p-6 bg-white shadow-box-shadow-8 rounded-[24px] border border-gray-100 flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h3 class="text-base font-black text-slate-900">Aktivitas Input Manual Admin KampSewa</h3>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar rekan admin yang mencatat transaksi pemasukan manual, riwayat terbaru & terlama, serta tautan profil admin.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-100">
                        <i class="bi bi-person-check-fill mr-1"></i> {{ $adminPemasukanStats->count() }} Admin Berkontribusi
                    </span>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200">
                        Total Manual: {{ number_format($manualPemasukanCount, 0, ',', '.') }} Data
                    </span>
                </div>
            </div>

            {{-- 2 Highlight Cards: Input Terbaru & Terlama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                {{-- Input Terbaru --}}
                <div class="p-4 rounded-2xl bg-gradient-to-r from-emerald-500/10 via-teal-500/5 to-transparent border border-emerald-200/80 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i class="bi bi-clock-history text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700">Input Manual Terbaru</span>
                            @if ($latestManualPemasukan)
                                <p class="text-xs font-black text-slate-900 truncate">
                                    {{ $latestManualPemasukan->user->name ?? 'Admin KampSewa' }} &bull;
                                    <span class="text-emerald-600 font-bold">Rp. {{ number_format($latestManualPemasukan->nominal, 0, ',', '.') }}</span>
                                </p>
                                <p class="text-[11px] text-slate-400 truncate">
                                    {{ $latestManualPemasukan->sumber }} &bull; {{ Carbon\Carbon::parse($latestManualPemasukan->created_at)->diffForHumans() }}
                                </p>
                            @else
                                <p class="text-xs text-slate-400">Belum ada transaksi manual</p>
                            @endif
                        </div>
                    </div>
                    @if ($latestManualPemasukan && $latestManualPemasukan->user)
                        <a href="{{ route('profile.index', ['nama_lengkap' => $latestManualPemasukan->user->name]) }}"
                            class="px-2.5 py-1.5 rounded-xl bg-white border border-emerald-200 text-emerald-700 hover:bg-emerald-50 text-[11px] font-bold shrink-0 transition active:scale-95 shadow-2xs">
                            Profil &rarr;
                        </a>
                    @endif
                </div>

                {{-- Input Terlama --}}
                <div class="p-4 rounded-2xl bg-gradient-to-r from-violet-500/10 via-indigo-500/5 to-transparent border border-violet-200/80 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-violet-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i class="bi bi-hourglass-split text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-violet-700">Input Manual Terlama</span>
                            @if ($oldestManualPemasukan)
                                <p class="text-xs font-black text-slate-900 truncate">
                                    {{ $oldestManualPemasukan->user->name ?? 'Admin KampSewa' }} &bull;
                                    <span class="text-violet-600 font-bold">Rp. {{ number_format($oldestManualPemasukan->nominal, 0, ',', '.') }}</span>
                                </p>
                                <p class="text-[11px] text-slate-400 truncate">
                                    {{ $oldestManualPemasukan->sumber }} &bull; {{ Carbon\Carbon::parse($oldestManualPemasukan->created_at)->translatedFormat('d M Y') }}
                                </p>
                            @else
                                <p class="text-xs text-slate-400">Belum ada transaksi manual</p>
                            @endif
                        </div>
                    </div>
                    @if ($oldestManualPemasukan && $oldestManualPemasukan->user)
                        <a href="{{ route('profile.index', ['nama_lengkap' => $oldestManualPemasukan->user->name]) }}"
                            class="px-2.5 py-1.5 rounded-xl bg-white border border-violet-200 text-violet-700 hover:bg-violet-50 text-[11px] font-bold shrink-0 transition active:scale-95 shadow-2xs">
                            Profil &rarr;
                        </a>
                    @endif
                </div>
            </div>

            {{-- Grid Rekan Admin Kontributor --}}
            <div>
                <p class="text-xs font-bold text-slate-500 mb-2.5">Daftar Admin Pencatat Pemasukan:</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2.5">
                    @forelse ($adminPemasukanStats as $adm)
                        <a href="{{ route('profile.index', ['nama_lengkap' => $adm->name]) }}"
                            class="p-3 rounded-2xl bg-slate-50 hover:bg-white border border-slate-200/80 hover:border-emerald-300 hover:shadow-md transition group flex flex-col gap-1.5">
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
                                    <p class="text-xs font-black text-slate-800 truncate group-hover:text-emerald-700 transition">
                                        {{ $adm->name }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">{{ $adm->pemasukan_perusahaan_count }} input</p>
                                </div>
                                <i class="bi bi-chevron-right text-xs text-slate-300 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition-transform"></i>
                            </div>
                            <div class="pt-1 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                                <span class="text-slate-400 text-[10px]">Total:</span>
                                <span class="font-bold text-emerald-600">Rp. {{ number_format($adm->pemasukan_perusahaan_sum_nominal, 0, ',', '.') }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-4 text-center text-xs text-slate-400">
                            Belum ada admin yang menginputkan data pemasukan manual.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Section: Distribusi Sumber Pemasukan (Filter Chips Mirip Pengeluaran) --}}
        <div class="p-5 bg-white shadow-box-shadow-8 rounded-[20px] border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                <div>
                    <h3 class="text-sm font-black text-slate-900">Distribusi Sumber Pemasukan</h3>
                    <p class="text-xs text-slate-400">Klik sumber di bawah ini untuk memfilter data pada tabel secara langsung.</p>
                </div>
                @if ($sumberFilter && $sumberFilter !== 'all')
                    <a href="{{ route('penghasilan.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">
                        <i class="bi bi-x-circle mr-1"></i>Reset Filter Sumber
                    </a>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Chip: Semua --}}
                <a href="{{ route('penghasilan.index') }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ empty($sumberFilter) || $sumberFilter === 'all' ? 'bg-[#080E2E] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    <i class="bi bi-grid-fill text-[11px]"></i>
                    <span>Semua Sumber</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-md {{ empty($sumberFilter) || $sumberFilter === 'all' ? 'bg-white/20 text-white' : 'bg-white text-slate-600' }} text-[10px]">
                        {{ number_format($list_pemasukan->total()) }}
                    </span>
                </a>

                {{-- Chip: Manual Input Admin --}}
                <a href="{{ route('penghasilan.index', ['sumber' => 'manual']) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $sumberFilter === 'manual' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                    <i class="bi bi-person-fill text-[11px]"></i>
                    <span>Input Manual Admin</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-md {{ $sumberFilter === 'manual' ? 'bg-white/20 text-white' : 'bg-white text-emerald-800' }} text-[10px]">
                        {{ number_format($manualPemasukanCount) }}
                    </span>
                </a>

                {{-- Chip: Otomatis Sistem --}}
                <a href="{{ route('penghasilan.index', ['sumber' => 'otomatis']) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $sumberFilter === 'otomatis' ? 'bg-sky-600 text-white shadow-sm' : 'bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200' }}">
                    <i class="bi bi-cpu text-[11px]"></i>
                    <span>Otomatis Sistem</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-md {{ $sumberFilter === 'otomatis' ? 'bg-white/20 text-white' : 'bg-white text-sky-800' }} text-[10px]">
                        {{ number_format($otomatisPemasukanCount) }}
                    </span>
                </a>

                {{-- Dynamic Chips per Sumber --}}
                @foreach ($distinctSumbers->take(6) as $s)
                    @php
                        $isActive = $sumberFilter === $s->sumber;
                    @endphp
                    <a href="{{ route('penghasilan.index', ['sumber' => $s->sumber]) }}"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $isActive ? 'bg-[#5038ED] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200' }}">
                        <span>{{ $s->sumber }}</span>
                        <span class="ml-1 px-1.5 py-0.2 rounded-md {{ $isActive ? 'bg-white/20 text-white' : 'bg-white text-slate-600' }} text-[10px]">
                            {{ number_format($s->count) }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Bagian Bawah: Data Tabel Pemasukan & Action Bar --}}
        <div class="--component-kedua mt-2">
            <div class="--title text-[18px] font-bold text-gray-800 mb-3">Data Pemasukan Perusahaan</div>
            
            {{-- Search & Action Bar (Sejajar dan Lurus Sempurna dengan Tinggi Sama h-10) --}}
            <div class="flex flex-col lg:flex-row w-full justify-between items-stretch lg:items-center gap-3 mb-4">
                {{-- Total users counter --}}
                <div class="_total flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center h-10 px-3.5 bg-[#EFF2F7] text-gray-700 text-xs font-bold rounded-xl border border-gray-200">
                        <i class="bi bi-people-fill text-[#5038ED] mr-1.5 text-sm"></i> {{ number_format($totalCustomer, 0, ',', '.') }} Customer
                    </span>
                    <span class="inline-flex items-center h-10 px-3.5 bg-[#F0FDF4] text-emerald-700 text-xs font-bold rounded-xl border border-emerald-200">
                        <i class="bi bi-receipt text-emerald-600 mr-1.5 text-sm"></i> {{ number_format($list_pemasukan->total(), 0, ',', '.') }} Transaksi
                    </span>
                </div>

                {{-- Action Group: Search, Export, Import, Tambah (Sejajar Sempurna) --}}
                <div class="_search-filter flex flex-wrap items-center gap-2.5">
                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('penghasilan.index') }}" class="relative m-0 p-0 flex items-center">
                        <input class="h-10 w-[240px] sm:w-[260px] pl-9 pr-8 text-xs font-medium text-slate-800 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-violet-400 transition"
                            type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari sumber/deskripsi...">
                        <i class="bi bi-search absolute left-3 text-slate-400 text-xs pointer-events-none"></i>
                        @if (!empty($search))
                            <a href="{{ route('penghasilan.index') }}" class="absolute right-2.5 text-slate-400 hover:text-slate-600 text-xs" title="Reset pencarian">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                    </form>

                    {{-- Tombol Export --}}
                    <a href="{{ route('penghasilan.export') }}" title="Export Data Pemasukan ke File CSV/Excel"
                        class="inline-flex items-center justify-center h-10 px-3.5 gap-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold rounded-xl shadow-sm transition active:scale-95">
                        <i class="fi fi-rr-inbox-out text-[13px] text-emerald-600"></i>
                        <span>Export</span>
                    </a>

                    {{-- Tombol Import --}}
                    <button type="button" onclick="modalHandlerImportPenghasilan(true)" title="Import Data Pemasukan dari File CSV"
                        class="inline-flex items-center justify-center h-10 px-3.5 gap-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold rounded-xl shadow-sm transition active:scale-95">
                        <i class="fi fi-rr-inbox-in text-[13px] text-violet-600"></i>
                        <span>Import</span>
                    </button>

                    {{-- Tombol Tambah Pemasukan --}}
                    <button type="button" id="tambah-pemasukan-customer"
                        class="inline-flex items-center justify-center h-10 px-4 gap-1.5 gradient-1 cursor-pointer text-white font-bold text-xs rounded-xl shadow-md hover:opacity-95 transition active:scale-95">
                        <i class="bi bi-plus-lg text-sm"></i>
                        <span>Tambah Pemasukan</span>
                    </button>
                </div>
            </div>

            {{-- Table Pemasukan Modern Putih --}}
            <div class="--table w-full h-auto mt-2">
                <div class="relative w-full overflow-hidden shadow-box-shadow-11 rounded-[20px] bg-white border border-gray-100">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5">#</th>
                                    <th scope="col" class="px-6 py-3.5">Sumber</th>
                                    <th scope="col" class="px-6 py-3.5">Tanggal</th>
                                    <th scope="col" class="px-6 py-3.5">Deskripsi</th>
                                    <th scope="col" class="px-6 py-3.5">Nominal</th>
                                    <th scope="col" class="px-6 py-3.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($list_pemasukan as $index => $item)
                                    @php
                                        $sumberBadge = 'bg-blue-50 text-blue-700 border-blue-200';
                                        if (stripos($item->sumber, 'iklan') !== false) {
                                            $sumberBadge = 'bg-purple-50 text-purple-700 border-purple-200';
                                        } elseif (stripos($item->sumber, 'pajak') !== false) {
                                            $sumberBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        } elseif (stripos($item->sumber, 'mitra') !== false) {
                                            $sumberBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                                        }
                                    @endphp
                                    <tr class="bg-white hover:bg-gray-50/80 transition">
                                        <td class="px-4 py-4 text-xs font-medium text-gray-400">
                                            {{ $list_pemasukan->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $sumberBadge }}">
                                                {{ $item->sumber }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                            <span class="py-1 px-3 bg-[#F0FDF4] text-[#22C55E] font-medium rounded-full border border-emerald-100">
                                                {{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 max-w-[320px] text-gray-700 text-xs truncate" title="{{ $item->deskripsi }}">
                                            {{ $item->deskripsi }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900 text-sm whitespace-nowrap">
                                                    Rp. {{ number_format($item->nominal, 0, ',', '.') }}
                                                </span>
                                                @if ($item->user)
                                                    <a href="{{ route('profile.index', ['nama_lengkap' => $item->user->name]) }}"
                                                        title="Diinput manual oleh: {{ $item->user->name }} (Klik untuk lihat profil)"
                                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 text-[11px] font-bold shadow-2xs transition active:scale-95 group">
                                                        <i class="bi bi-arrow-up-right text-[10px] group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                                                        <span class="truncate max-w-[85px]">{{ $item->user->name }}</span>
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200 text-[10px] font-bold" title="Pemasukan otomatis dari sistem/transaksi platform sewa">
                                                        <i class="bi bi-cpu text-[10px]"></i>
                                                        <span>Sistem</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="inline-flex items-center justify-center gap-1.5">
                                                {{-- Tombol Edit Setiap Row Data --}}
                                                <button type="button"
                                                    onclick="openEditPemasukanModal({{ $item->id }}, '{{ addslashes($item->sumber) }}', '{{ addslashes($item->deskripsi) }}', {{ $item->nominal }})"
                                                    class="w-8 h-8 inline-flex items-center justify-center text-violet-600 hover:text-violet-800 bg-violet-50 hover:bg-violet-100 rounded-xl transition shadow-2xs" title="Edit Data Pemasukan">
                                                    <i class="bi bi-pencil-square text-sm"></i>
                                                </button>

                                                {{-- Tombol Hapus dengan Konfirmasi Dialog Bagus --}}
                                                <form action="{{ route('penghasilan.destroy', $item->id) }}" method="POST" class="inline-flex items-center m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDeletePemasukan(this.form)"
                                                        class="w-8 h-8 inline-flex items-center justify-center text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 rounded-xl transition shadow-2xs" title="Hapus Data Pemasukan">
                                                        <i class="bi bi-trash-fill text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                            <i class="bi bi-inbox text-[36px] block mb-2 text-gray-300"></i>
                                            <p class="font-medium text-gray-500">Tidak ada data pemasukan ditemukan.</p>
                                            @if ($search)
                                                <a href="{{ route('penghasilan.index') }}" class="text-[#5038ED] text-xs underline mt-1 inline-block">Reset filter pencarian</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-4 px-2">
                    <p class="text-xs text-gray-500 font-medium">
                        Menampilkan {{ $list_pemasukan->firstItem() ?? 0 }} - {{ $list_pemasukan->lastItem() ?? 0 }} dari {{ $list_pemasukan->total() }} data
                    </p>
                    <div>
                        {{ $list_pemasukan->onEachSide(1)->links('components.paginate.custom-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pass dynamic chart datasets to global window object --}}
    <script>
        window.penghasilanPertahunData = @json($monthlyIncomeData);
        window.penghasilanPerbulanLabels = @json($recentMonthsLabels);
        window.penghasilanPerbulanData = @json($recentMonthsData);
        window.penghasilanPermingguData = @json($weeklyData);

        // Modal Tambah Pemasukan
        const modalTambah = document.getElementById('modal-tambah-pemasukan-customer');
        const idButtonTambah = document.getElementById('tambah-pemasukan-customer');
        const submitPemasukan = document.getElementById('tambah-pemasukan');
        const formTambahPemasukan = document.getElementById('form-tambah-pemasukan');
        const cancelButtonTambah = document.getElementById('cancel-tambah-pemasukan-web-customer');
        const closeButtonTambah = document.getElementById('close-modal-pemasukan');

        function modalHandlerPemasukanCustomer(val) {
            if (modalTambah) {
                if (val) {
                    modalTambah.classList.remove('hidden');
                    modalTambah.style.display = "flex";
                } else {
                    modalTambah.classList.add('hidden');
                    modalTambah.style.display = "none";
                }
            }
        }

        if (idButtonTambah) {
            idButtonTambah.addEventListener('click', () => modalHandlerPemasukanCustomer(true));
        }
        if (cancelButtonTambah) {
            cancelButtonTambah.addEventListener('click', () => modalHandlerPemasukanCustomer(false));
        }
        if (closeButtonTambah) {
            closeButtonTambah.addEventListener('click', () => modalHandlerPemasukanCustomer(false));
        }

        if (submitPemasukan && formTambahPemasukan) {
            submitPemasukan.addEventListener('click', function(event) {
                event.preventDefault();

                let sumber = document.getElementById('sumber_pemasukan_customer').value.trim();
                let deskripsi = document.getElementById('deskripsi_pemasukan_customer').value.trim();
                let nominal = document.getElementById('nominal_pemasukan_customer').value.trim();

                if (!sumber || !deskripsi || !nominal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Mohon isi semua field input!',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                }

                formTambahPemasukan.submit();
            });
        }

        // Modal Edit Pemasukan
        const modalEdit = document.getElementById('modal-edit-pemasukan');
        const formEdit = document.getElementById('form-edit-pemasukan');

        function modalHandlerEditPenghasilan(val) {
            if (modalEdit) {
                if (val) {
                    modalEdit.classList.remove('hidden');
                } else {
                    modalEdit.classList.add('hidden');
                }
            }
        }

        function openEditPemasukanModal(id, sumber, deskripsi, nominal) {
            if (!formEdit) return;
            formEdit.action = `/developer/dashboard/penghasilan/${id}`;
            document.getElementById('edit_sumber_pemasukan').value = sumber;
            document.getElementById('edit_deskripsi_pemasukan').value = deskripsi;
            document.getElementById('edit_nominal_pemasukan').value = nominal;
            modalHandlerEditPenghasilan(true);
        }

        // Modal Import Pemasukan
        const modalImport = document.getElementById('modal-import-penghasilan');

        function modalHandlerImportPenghasilan(val) {
            if (modalImport) {
                if (val) {
                    modalImport.classList.remove('hidden');
                } else {
                    modalImport.classList.add('hidden');
                }
            }
        }

        // Konfirmasi Hapus Dialog Bagus (SweetAlert2)
        function confirmDeletePemasukan(form) {
            Swal.fire({
                title: 'Hapus Catatan Pemasukan?',
                text: 'Data pemasukan ini akan dihapus secara permanen dari sistem.',
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
            if (typeof window.initPenghasilanPertahunChart === 'function') {
                window.initPenghasilanPertahunChart();
            }
            if (typeof window.initChartTotalPerbulan === 'function') {
                window.initChartTotalPerbulan();
            }
            if (typeof window.initChartTotalPerminggu === 'function') {
                window.initChartTotalPerminggu();
            }
        </script>
    @endpush
@endsection
