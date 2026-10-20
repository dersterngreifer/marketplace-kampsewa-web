@extends('layouts.developers.ly-dashboard')

@section('content')
    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">

        {{-- ============================================================
             1. HEADER & ACTION BAR
             ============================================================ --}}
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                {{-- Breadcrumb --}}
                <div class="mb-2 flex items-center gap-2 text-xs font-semibold text-slate-400">
                    <a href="{{ route('home.index') }}" class="transition hover:text-violet-600">Dashboard</a>
                    <i class="bi bi-chevron-right text-[10px]"></i>
                    <span class="text-violet-600">Notifikasi Aktivitas</span>
                </div>

                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">
                        Pusat Notifikasi Aktivitas
                    </h1>
                    @if ($totalUnreadCount > 0)
                        <span id="header-unread-badge"
                            class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-xs font-extrabold text-rose-600 ring-1 ring-rose-200">
                            <span class="h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                            {{ $totalUnreadCount }} Belum Dibaca
                        </span>
                    @else
                        <span id="header-unread-badge"
                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-extrabold text-emerald-600 ring-1 ring-emerald-200">
                            <i class="bi bi-check-circle-fill text-[11px]"></i>
                            Semua Telah Dibaca
                        </span>
                    @endif
                </div>
                <p class="mt-1 text-sm font-medium text-slate-500">
                    Pantau secara terpusat seluruh aktivitas pengguna di platform: registrasi akun baru, feedback & pertanyaan, transaksi sewa platform, hingga transaksi mitra yang menyewa jasa iklan.
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-3">
                <form id="form-mark-all-read" action="{{ route('developer.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" id="btn-page-mark-all"
                        class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-[#9B6CF2] to-[#5038ED] px-4 py-3 text-xs font-black text-white shadow-lg shadow-violet-500/25 outline-none transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-violet-500/35 active:translate-y-0 active:scale-95">
                        <i class="fi fi-rr-check-double text-[14px]"></i>
                        <span>Tandai Semua Sebagai Dibaca</span>
                    </button>
                </form>

                <a href="{{ route('notification.index') }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-xs font-bold text-slate-700 shadow-sm transition hover:border-violet-200 hover:bg-violet-50 hover:text-violet-600">
                    <i class="fi fi-rr-comment-alt-middle text-[13px]"></i>
                    <span>Buka Feedback Users</span>
                </a>
            </div>
        </div>

        {{-- ============================================================
             2. RINGKASAN METRIK AKTIVITAS (6 KARTU LENGKAP)
             ============================================================ --}}
        <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            {{-- User Baru Hari Ini --}}
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'users', 'time' => 'today']) }}"
                class="group rounded-3xl border border-blue-100 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 group-hover:scale-110 transition">
                        <i class="fi fi-rr-user-add text-lg"></i>
                    </span>
                    <span class="text-[11px] font-bold text-blue-500">Hari ini</span>
                </div>
                <h3 class="mt-3 text-2xl font-black text-slate-900">{{ $totalUsersToday }}</h3>
                <p class="text-xs font-semibold text-slate-500">Pendaftaran User</p>
            </a>

            {{-- Feedback Masuk --}}
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'feedback', 'time' => 'today']) }}"
                class="group rounded-3xl border border-violet-100 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-violet-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-violet-50 text-violet-600 group-hover:scale-110 transition">
                        <i class="fi fi-rr-comment-alt text-lg"></i>
                    </span>
                    <span class="text-[11px] font-bold text-violet-500">Hari ini</span>
                </div>
                <h3 class="mt-3 text-2xl font-black text-slate-900">{{ $totalFeedbackToday }}</h3>
                <p class="text-xs font-semibold text-slate-500">Feedback & Saran</p>
            </a>

            {{-- Aktivitas Sewa Platform --}}
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'rentals', 'time' => 'today']) }}"
                class="group rounded-3xl border border-emerald-100 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 group-hover:scale-110 transition">
                        <i class="fi fi-rr-box-alt text-lg"></i>
                    </span>
                    <span class="text-[11px] font-bold text-emerald-500">Hari ini</span>
                </div>
                <h3 class="mt-3 text-2xl font-black text-slate-900">{{ $totalRentalsToday }}</h3>
                <p class="text-xs font-semibold text-slate-500">Sewa Platform</p>
            </a>

            {{-- Sewa Iklan Mitra (Transaksi Jasa Iklan) --}}
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'ads', 'time' => 'today']) }}"
                class="group rounded-3xl border border-purple-100 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-purple-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-purple-50 text-purple-600 group-hover:scale-110 transition">
                        <i class="fi fi-rr-ad text-lg"></i>
                    </span>
                    <span class="text-[11px] font-bold text-purple-500">Hari ini</span>
                </div>
                <h3 class="mt-3 text-2xl font-black text-slate-900">{{ $totalAdTransactionsToday }}</h3>
                <p class="text-xs font-semibold text-slate-500">
                    Sewa Iklan Mitra
                    @if ($totalAdRevenueToday > 0)
                        <span class="block text-[10px] font-black text-purple-600">Rp {{ number_format($totalAdRevenueToday, 0, ',', '.') }}</span>
                    @endif
                </p>
            </a>

            {{-- Iklan Hampir Habis --}}
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'ads']) }}"
                class="group rounded-3xl border border-rose-100 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-rose-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 group-hover:scale-110 transition">
                        <i class="fi fi-rr-alarm-clock text-lg"></i>
                    </span>
                    <span class="text-[11px] font-bold text-rose-500">&le;3 Hari</span>
                </div>
                <h3 class="mt-3 text-2xl font-black text-slate-900">{{ $totalAdsExpiring }}</h3>
                <p class="text-xs font-semibold text-slate-500">Iklan Kadaluarsa</p>
            </a>

            {{-- Rating Buruk --}}
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'ratings', 'rating_star' => '1']) }}"
                class="group rounded-3xl border border-amber-100 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-amber-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 group-hover:scale-110 transition">
                        <i class="fi fi-rr-star text-lg"></i>
                    </span>
                    <span class="text-[11px] font-bold text-amber-500">7 Hari</span>
                </div>
                <h3 class="mt-3 text-2xl font-black text-slate-900">{{ $totalBadRatings }}</h3>
                <p class="text-xs font-semibold text-slate-500">Rating Buruk (&le;2&star;)</p>
            </a>
        </div>

        {{-- ============================================================
             3. TAB NAVIGATION (DIPISAHKAN PER FITUR)
             ============================================================ --}}
        <div class="mb-5 flex flex-wrap items-center gap-2 border-b border-slate-200/80 pb-3">
            @php
                $tabs = [
                    ['id' => 'all', 'label' => 'Semua Aktivitas', 'icon' => 'fi fi-rr-apps', 'badge' => null],
                    ['id' => 'users', 'label' => 'Pendaftaran User', 'icon' => 'fi fi-rr-user', 'badge' => $unreadUsersCount > 0 ? $unreadUsersCount : null],
                    ['id' => 'feedback', 'label' => 'Feedback Pengguna', 'icon' => 'fi fi-rr-comment-alt', 'badge' => $unreadFeedbackCount > 0 ? $unreadFeedbackCount : null],
                    ['id' => 'ads', 'label' => 'Sewa Iklan Mitra', 'icon' => 'fi fi-rr-ad', 'badge' => (($unreadAdPaymentsCount ?? 0) + ($unreadRevisedAdsCount ?? 0)) > 0 ? (($unreadAdPaymentsCount ?? 0) + ($unreadRevisedAdsCount ?? 0)) : null],
                    ['id' => 'product_revisions', 'label' => 'Revisi Produk Mitra', 'icon' => 'fi fi-rr-box-alt', 'badge' => ($unreadRevisedProductsCount ?? 0) > 0 ? $unreadRevisedProductsCount : null],
                    ['id' => 'rentals', 'label' => 'Aktivitas Sewa Platform', 'icon' => 'fi fi-rr-shopping-bag', 'badge' => null],
                    ['id' => 'ratings', 'label' => 'Ulasan & Rating', 'icon' => 'fi fi-rr-star', 'badge' => null],
                ];
            @endphp

            @foreach ($tabs as $tab)
                @php
                    $isActive = $currentTab === $tab['id'];
                @endphp
                <a href="{{ route('developer.notifications.index', array_merge(request()->except(['users_page', 'feedback_page', 'rentals_page', 'ads_page', 'ad_pay_page', 'ratings_page', 'rev_prod_page']), ['tab' => $tab['id']])) }}"
                    class="group relative inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-xs font-extrabold transition-all duration-200 {{ $isActive ? 'bg-gradient-to-r from-[#9B6CF2] to-[#5038ED] text-white shadow-md shadow-violet-500/25' : 'bg-white text-slate-600 hover:bg-violet-50 hover:text-violet-600 border border-slate-200/80' }}">
                    <i class="{{ $tab['icon'] }} text-[13px] {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-violet-600' }}"></i>
                    <span>{{ $tab['label'] }}</span>

                    @if ($tab['badge'])
                        <span class="rounded-full {{ $isActive ? 'bg-white text-violet-700' : 'bg-rose-500 text-white' }} px-1.5 py-0.5 text-[10px] font-black">
                            {{ $tab['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- ============================================================
             4. FILTER & SEARCH BAR
             ============================================================ --}}
        <div class="mb-6 rounded-3xl border border-slate-200/80 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('developer.notifications.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <input type="hidden" name="tab" value="{{ $currentTab }}">

                <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                    {{-- Search Input --}}
                    <div class="relative flex-1">
                        <i class="fi fi-rr-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Cari berdasarkan nama mitra, judul iklan, email, atau kata kunci..."
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50/70 py-2.5 pl-10 pr-4 text-xs font-medium text-slate-800 placeholder-slate-400 transition focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    </div>

                    {{-- Filter Waktu --}}
                    <div class="shrink-0">
                        <select name="time" onchange="this.form.submit()"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50/70 py-2.5 pl-3.5 pr-8 text-xs font-bold text-slate-700 transition focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                            <option value="all" {{ $timeFilter === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                            <option value="today" {{ $timeFilter === 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="this_week" {{ $timeFilter === 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="this_month" {{ $timeFilter === 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                        </select>
                    </div>

                    {{-- Filter Status (Unread / Read) --}}
                    @if (in_array($currentTab, ['all', 'users', 'feedback', 'product_revisions', 'ads']))
                        <div class="shrink-0">
                            <select name="status" onchange="this.form.submit()"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50/70 py-2.5 pl-3.5 pr-8 text-xs font-bold text-slate-700 transition focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="unread" {{ $statusFilter === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                                <option value="read" {{ $statusFilter === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                            </select>
                        </div>
                    @endif

                    {{-- Filter Khusus per Tab --}}
                    @if ($currentTab === 'ads')
                        <div class="shrink-0">
                            <select name="ad_pay_status" onchange="this.form.submit()"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50/70 py-2.5 pl-3.5 pr-8 text-xs font-bold text-slate-700 transition focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                                <option value="all" {{ request('ad_pay_status') === 'all' ? 'selected' : '' }}>Semua Status Pembayaran</option>
                                <option value="lunas" {{ request('ad_pay_status') === 'lunas' ? 'selected' : '' }}>Lunas / Settlement</option>
                                <option value="pending" {{ request('ad_pay_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                    @elseif ($currentTab === 'feedback')
                        <div class="shrink-0">
                            <select name="kriteria" onchange="this.form.submit()"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50/70 py-2.5 pl-3.5 pr-8 text-xs font-bold text-slate-700 transition focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                                <option value="all" {{ request('kriteria') === 'all' ? 'selected' : '' }}>Semua Kriteria</option>
                                <option value="Sangat Baik" {{ request('kriteria') === 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                                <option value="Baik" {{ request('kriteria') === 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Cukup" {{ request('kriteria') === 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                <option value="Kurang" {{ request('kriteria') === 'Kurang' ? 'selected' : '' }}>Kurang</option>
                                <option value="Sangat Kurang" {{ request('kriteria') === 'Sangat Kurang' ? 'selected' : '' }}>Sangat Kurang</option>
                            </select>
                        </div>
                    @elseif ($currentTab === 'ratings')
                        <div class="shrink-0">
                            <select name="rating_star" onchange="this.form.submit()"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50/70 py-2.5 pl-3.5 pr-8 text-xs font-bold text-slate-700 transition focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                                <option value="all" {{ request('rating_star') === 'all' ? 'selected' : '' }}>Semua Bintang</option>
                                <option value="5" {{ request('rating_star') === '5' ? 'selected' : '' }}>5 Bintang</option>
                                <option value="4" {{ request('rating_star') === '4' ? 'selected' : '' }}>4 Bintang</option>
                                <option value="3" {{ request('rating_star') === '3' ? 'selected' : '' }}>3 Bintang</option>
                                <option value="2" {{ request('rating_star') === '2' ? 'selected' : '' }}>2 Bintang</option>
                                <option value="1" {{ request('rating_star') === '1' ? 'selected' : '' }}>1 Bintang</option>
                            </select>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-2xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-slate-800">
                        <i class="fi fi-rr-filter text-[11px]"></i>
                        Terapkan
                    </button>

                    @if ($search !== '' || $timeFilter !== 'all' || $statusFilter !== 'all' || request()->filled('kriteria') || request()->filled('rating_star') || request()->filled('ad_pay_status'))
                        <a href="{{ route('developer.notifications.index', ['tab' => $currentTab]) }}"
                            class="inline-flex items-center gap-1.5 rounded-2xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-bold text-slate-500 transition hover:bg-slate-100">
                            <i class="fi fi-rr-cross-small text-[14px]"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ============================================================
             5. KONTEN PER TAB
             ============================================================ --}}

        {{-- ------------------------------------------------------------
             TAB: SEMUA AKTIVITAS (ALL STREAM)
             ------------------------------------------------------------ --}}
        @if ($currentTab === 'all')
            @if (isset($recentRevisedAds) && $recentRevisedAds->count() > 0)
                <div class="mb-6 rounded-3xl border-2 border-emerald-300 bg-emerald-50/50 p-5 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="relative flex h-3 w-3 shrink-0">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Perhatian: Ada Iklan yang Telah Direvisi Mitra!</h3>
                                <p class="text-xs text-slate-600">Terdapat {{ $recentRevisedAds->count() }} iklan promosi yang telah diperbaiki oleh mitra dan siap dipulihkan ke slot iklan aktif.</p>
                            </div>
                        </div>
                        <a href="{{ route('iklan.index') }}#card-iklan-suspend"
                            class="inline-flex items-center gap-1.5 rounded-2xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition active:scale-95 shadow-sm shrink-0">
                            <i class="fi fi-rr-play text-xs"></i> Tinjau di Manajemen Iklan
                        </a>
                    </div>
                </div>
            @endif

            @if (isset($recentRevisedProducts) && $recentRevisedProducts->count() > 0)
                <div class="mb-6 rounded-3xl border-2 border-indigo-300 bg-indigo-50/60 p-5 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="relative flex h-3 w-3 shrink-0">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-600"></span>
                            </span>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Perhatian: Ada Produk Mitra yang Telah Direvisi!</h3>
                                <p class="text-xs text-slate-600">Terdapat {{ $recentRevisedProducts->count() }} produk yang sebelumnya disuspend telah diperbaiki oleh pemilik toko dan siap ditinjau untuk dipulihkan.</p>
                            </div>
                        </div>
                        <a href="{{ route('developer.notifications.index', ['tab' => 'product_revisions']) }}"
                            class="inline-flex items-center gap-1.5 rounded-2xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition active:scale-95 shadow-sm shrink-0">
                            <i class="fi fi-rr-box-alt text-xs"></i> Tinjau Produk Direvisi
                        </a>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- Kolom 1: Transaksi Sewa Iklan Mitra Terbaru --}}
                <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                                <i class="fi fi-rr-ad text-sm"></i>
                            </span>
                            <div>
                                <h2 class="text-sm font-black text-slate-900">Transaksi Sewa Iklan Mitra</h2>
                                <p class="text-[11px] text-slate-400">Mitra yang menyewa jasa promosi KampSewa</p>
                            </div>
                        </div>
                        <a href="{{ route('developer.notifications.index', ['tab' => 'ads']) }}"
                            class="text-xs font-bold text-violet-600 hover:underline">Lihat Semua</a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentAdPayments as $adPay)
                            <div class="flex items-center justify-between gap-3 py-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    @include('components.ui.avatar', [
                                        'nama' => $adPay->user->name ?? 'Mitra',
                                        'foto' => $adPay->user->foto ?? null,
                                        'class' => 'h-10 w-10 shrink-0',
                                        'rounded' => 'rounded-xl',
                                    ])
                                    <div class="min-w-0">
                                        <p class="truncate text-xs font-bold text-slate-900">{{ $adPay->user->name ?? 'Mitra' }}</p>
                                        <p class="truncate text-[11px] text-slate-500">
                                            Iklan: <span class="font-semibold text-slate-800">{{ Str::limit($adPay->iklan->judul ?? 'Promosi Mitra', 35) }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-black text-emerald-600">
                                        Rp {{ number_format($adPay->total_bayar, 0, ',', '.') }}
                                    </span>
                                    <p class="mt-0.5 text-[10px] text-slate-400">{{ $adPay->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="py-6 text-center text-xs text-slate-400">Belum ada transaksi sewa iklan hari ini.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Kolom 2: Registrasi Pengguna Terbaru --}}
                <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                <i class="fi fi-rr-user-add text-sm"></i>
                            </span>
                            <div>
                                <h2 class="text-sm font-black text-slate-900">Pendaftaran Pengguna Baru</h2>
                                <p class="text-[11px] text-slate-400">Mitra & pelanggan yang baru registrasi</p>
                            </div>
                        </div>
                        <a href="{{ route('developer.notifications.index', ['tab' => 'users']) }}"
                            class="text-xs font-bold text-violet-600 hover:underline">Lihat Semua</a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentUsers as $user)
                            <div class="flex items-center justify-between gap-3 py-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    @include('components.ui.avatar', [
                                        'nama' => $user->name,
                                        'foto' => $user->foto,
                                        'class' => 'h-10 w-10 shrink-0',
                                        'rounded' => 'rounded-xl',
                                    ])
                                    <div class="min-w-0">
                                        <p class="truncate text-xs font-bold text-slate-900">{{ $user->name }}</p>
                                        <p class="truncate text-[11px] text-slate-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 text-[10px] font-semibold text-slate-400">
                                    {{ $user->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @empty
                            <p class="py-6 text-center text-xs text-slate-400">Belum ada pengguna baru hari ini.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Kolom 3: Feedback Masuk --}}
                <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                                <i class="fi fi-rr-comment-alt text-sm"></i>
                            </span>
                            <div>
                                <h2 class="text-sm font-black text-slate-900">Feedback & Masukan Pengguna</h2>
                                <p class="text-[11px] text-slate-400">Saran, kendala, atau pesan bantuan</p>
                            </div>
                        </div>
                        <a href="{{ route('developer.notifications.index', ['tab' => 'feedback']) }}"
                            class="text-xs font-bold text-violet-600 hover:underline">Lihat Semua</a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentFeedbacks as $fb)
                            <div class="py-3">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-xs font-bold text-slate-900">{{ $fb->user->name ?? 'Pengguna' }}</p>
                                    <span class="rounded bg-violet-100 px-1.5 py-0.5 text-[10px] font-bold text-violet-700">
                                        {{ $fb->kriteria }}
                                    </span>
                                </div>
                                <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">"{{ $fb->deskripsi }}"</p>
                                <div class="mt-1 flex items-center justify-between">
                                    <span class="text-[10px] text-slate-400">{{ $fb->created_at->diffForHumans() }}</span>
                                    <span class="text-[10px] font-bold {{ $fb->status === 'Dibalas' ? 'text-emerald-600' : 'text-amber-500' }}">
                                        {{ $fb->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="py-6 text-center text-xs text-slate-400">Belum ada feedback baru.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Kolom 4: Aktivitas Sewa Platform --}}
                <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                <i class="fi fi-rr-box-alt text-sm"></i>
                            </span>
                            <div>
                                <h2 class="text-sm font-black text-slate-900">Aktivitas Sewa Antar Pengguna</h2>
                                <p class="text-[11px] text-slate-400">Customer menyewa peralatan ke Mitra</p>
                            </div>
                        </div>
                        <a href="{{ route('developer.notifications.index', ['tab' => 'rentals']) }}"
                            class="text-xs font-bold text-violet-600 hover:underline">Lihat Semua</a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentRentals as $rent)
                            <div class="flex items-center justify-between gap-3 py-3">
                                <div class="min-w-0">
                                    <p class="truncate text-xs font-bold text-slate-900">{{ $rent->nama_penyewa }}</p>
                                    <p class="truncate text-[11px] text-slate-400">Sewa: {{ $rent->nama_produk }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-600">
                                        {{ $rent->status_penyewaan }}
                                    </span>
                                    <p class="mt-0.5 text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($rent->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="py-6 text-center text-xs text-slate-400">Belum ada transaksi sewa hari ini.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        @endif

        {{-- ------------------------------------------------------------
             TAB: PENDAFTARAN USER (USERS)
             ------------------------------------------------------------ --}}
        @if ($currentTab === 'users')
            <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-black text-slate-900">Daftar Pendaftaran Akun Pengguna / Mitra</h2>
                        <p class="text-xs text-slate-400">Menampilkan seluruh pengguna yang baru maupun sudah lama terdaftar di KampSewa.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-500">Total: {{ $usersList->total() }} akun</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($usersList as $user)
                        @php
                            $isUnread = ($user->notif_status === 'unread' || is_null($user->notif_status));
                        @endphp
                        <div class="flex flex-col gap-4 p-5 transition hover:bg-slate-50/70 sm:flex-row sm:items-center sm:justify-between {{ $isUnread ? 'bg-violet-50/30' : '' }}">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="relative">
                                    @include('components.ui.avatar', [
                                        'nama' => $user->name,
                                        'foto' => $user->foto,
                                        'class' => 'h-12 w-12',
                                        'rounded' => 'rounded-2xl',
                                    ])
                                    @if ($isUnread)
                                        <span class="absolute -top-1 -right-1 h-3.5 w-3.5 rounded-full border-2 border-white bg-rose-500"></span>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="truncate text-sm font-black text-slate-900">{{ $user->name }}</p>
                                        @if ($isUnread)
                                            <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-extrabold text-rose-700">Baru</span>
                                        @endif
                                        @if (!empty($user->nomor_identitas))
                                            <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700">KYC Terverifikasi</span>
                                        @else
                                            <span class="rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-700">Belum KYC</span>
                                        @endif
                                    </div>

                                    <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-400">
                                        <span><i class="fi fi-rr-envelope mr-1"></i>{{ $user->email }}</span>
                                        <span><i class="fi fi-rr-phone-call mr-1"></i>{{ $user->nomor_telephone ?? '-' }}</span>
                                        <span><i class="fi fi-rr-clock mr-1"></i>Terdaftar {{ $user->created_at->diffForHumans() }} ({{ $user->created_at->format('d M Y, H:i') }})</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-center">
                                @if ($isUnread)
                                    <button type="button" onclick="markSingleItemRead('user', {{ $user->id }}, this)"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:bg-slate-100">
                                        <i class="fi fi-rr-check text-[11px]"></i>
                                        Tandai Dibaca
                                    </button>
                                @endif

                                <a href="{{ route('detail-pengguna.index', ['user' => $user->id]) }}"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-violet-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-violet-700">
                                    <span>Detail Pengguna</span>
                                    <i class="bi bi-chevron-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <i class="fi fi-rr-user-slash text-4xl text-slate-300"></i>
                            <p class="mt-2 text-sm font-bold text-slate-700">Tidak ada data pengguna ditemukan</p>
                            <p class="text-xs text-slate-400">Coba ubah filter atau kata kunci pencarian Anda.</p>
                        </div>
                    @endforelse
                </div>

                @if ($usersList->hasPages())
                    <div class="border-t border-slate-100 p-4">
                        {{ $usersList->links('components.paginate.custom-pagination') }}
                    </div>
                @endif
            </div>
        @endif

        {{-- ------------------------------------------------------------
             TAB: FEEDBACK PENGGUNA (FEEDBACK)
             ------------------------------------------------------------ --}}
        @if ($currentTab === 'feedback')
            <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-black text-slate-900">Feedback & Masukan Pengguna</h2>
                        <p class="text-xs text-slate-400">Daftar ulasan dan pesan bantuan dari mitra maupun pelanggan aplikasi.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-500">Total: {{ $feedbackList->total() }} feedback</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($feedbackList as $fb)
                        @php
                            $isUnread = is_null($fb->read_at);
                            $badgeColor = match ($fb->kriteria) {
                                'Sangat Baik' => 'bg-emerald-100 text-emerald-800',
                                'Baik' => 'bg-blue-100 text-blue-800',
                                'Cukup' => 'bg-amber-100 text-amber-800',
                                'Kurang' => 'bg-orange-100 text-orange-800',
                                default => 'bg-rose-100 text-rose-800',
                            };
                        @endphp
                        <div class="flex flex-col gap-4 p-5 transition hover:bg-slate-50/70 sm:flex-row sm:items-start sm:justify-between {{ $isUnread ? 'bg-violet-50/30' : '' }}">
                            <div class="flex items-start gap-3.5 min-w-0 flex-1">
                                <div class="relative mt-0.5">
                                    @include('components.ui.avatar', [
                                        'nama' => $fb->user->name ?? 'Customer',
                                        'foto' => $fb->user->foto ?? null,
                                        'class' => 'h-11 w-11',
                                        'rounded' => 'rounded-2xl',
                                    ])
                                    @if ($isUnread)
                                        <span class="absolute -top-1 -right-1 h-3.5 w-3.5 rounded-full border-2 border-white bg-violet-600"></span>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-sm font-black text-slate-900">{{ $fb->user->name ?? 'Pengguna' }}</p>
                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $badgeColor }}">
                                            {{ $fb->kriteria }}
                                        </span>
                                        @if ($isUnread)
                                            <span class="rounded-full bg-violet-100 px-2 py-0.5 text-[10px] font-extrabold text-violet-700">Baru</span>
                                        @endif
                                        <span class="rounded px-1.5 py-0.5 text-[10px] font-bold {{ $fb->status === 'Dibalas' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                            Status: {{ $fb->status }}
                                        </span>
                                    </div>

                                    <div class="mt-2 rounded-2xl bg-slate-50 p-3.5 text-xs leading-relaxed text-slate-700 border border-slate-100">
                                        "{{ $fb->deskripsi }}"
                                    </div>

                                    <div class="mt-2 flex items-center gap-4 text-[11px] text-slate-400">
                                        <span><i class="fi fi-rr-clock mr-1"></i>{{ $fb->created_at->diffForHumans() }} ({{ $fb->created_at->format('d M Y, H:i') }})</span>
                                        @if ($fb->read_at)
                                            <span class="text-emerald-600"><i class="bi bi-check-all mr-1"></i>Dibaca pada {{ $fb->read_at->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-start shrink-0">
                                @if ($isUnread)
                                    <button type="button" onclick="markSingleItemRead('feedback', {{ $fb->id }}, this)"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:bg-slate-100">
                                        <i class="fi fi-rr-check text-[11px]"></i>
                                        Tandai Dibaca
                                    </button>
                                @endif

                                <a href="{{ route('notification.index') }}"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-violet-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-violet-700">
                                    <i class="fi fi-rr-comment-alt text-[11px]"></i>
                                    <span>Lihat & Balas Chat</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <i class="fi fi-rr-comment-alt-slash text-4xl text-slate-300"></i>
                            <p class="mt-2 text-sm font-bold text-slate-700">Tidak ada feedback ditemukan</p>
                            <p class="text-xs text-slate-400">Coba atur ulang filter pencarian Anda.</p>
                        </div>
                    @endforelse
                </div>

                @if ($feedbackList->hasPages())
                    <div class="border-t border-slate-100 p-4">
                        {{ $feedbackList->links('components.paginate.custom-pagination') }}
                    </div>
                @endif
            </div>
        @endif

        {{-- ------------------------------------------------------------
             TAB: SEWA IKLAN MITRA (ADS) - TRANSAKSI IKLAN DARI MITRA
             ------------------------------------------------------------ --}}
        @if ($currentTab === 'ads')
            {{-- Section Khusus Iklan yang Telah Direvisi Mitra --}}
            @if (isset($revisedAdsList) && $revisedAdsList->count() > 0)
                <div class="mb-6 overflow-hidden rounded-3xl border-2 border-emerald-300 bg-white shadow-sm ring-4 ring-emerald-50">
                    <div class="border-b border-emerald-100 bg-emerald-50/50 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                <h2 class="text-base font-black text-slate-900">Iklan Direvisi Mitra (Menunggu Ditinjau & Diaktifkan)</h2>
                                <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-black text-emerald-800 border border-emerald-200">
                                    {{ $revisedAdsList->total() }} Menunggu Tindakan
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Mitra telah merevisi materi iklan yang sebelumnya ditangguhkan (suspend). Silakan tinjau dan pulihkan ke slot iklan aktif.</p>
                        </div>
                        <a href="{{ route('iklan.index') }}#card-iklan-suspend"
                            class="inline-flex items-center gap-1.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:from-emerald-700 hover:to-teal-700 transition active:scale-95 shrink-0">
                            <i class="fi fi-rr-play text-xs"></i> Kelola di Menu Iklan
                        </a>
                    </div>

                    <div class="divide-y divide-emerald-50">
                        @foreach ($revisedAdsList as $revAd)
                            @php
                                $isUnreadRev = is_null($revAd->read_at);
                            @endphp
                            <div class="flex flex-col gap-4 p-5 transition hover:bg-emerald-50/30 lg:flex-row lg:items-center lg:justify-between {{ $isUnreadRev ? 'bg-emerald-50/20' : '' }}">
                                <div class="flex items-start gap-3.5 min-w-0 flex-1">
                                    @include('components.ui.avatar', [
                                        'nama' => $revAd->user->name ?? 'Mitra',
                                        'foto' => $revAd->user->foto ?? null,
                                        'class' => 'h-12 w-12 shrink-0',
                                        'rounded' => 'rounded-2xl',
                                    ])

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="text-sm font-black text-slate-900">{{ $revAd->user->name ?? 'Mitra' }}</p>
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-black text-emerald-800 border border-emerald-200 whitespace-nowrap shrink-0">
                                                <span class="relative flex h-1.5 w-1.5">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                                </span>
                                                Sudah Direvisi Mitra
                                            </span>
                                            @if (!empty($revAd->user->name_store))
                                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-700">
                                                    <i class="fi fi-rr-shop mr-1"></i>{{ $revAd->user->name_store }}
                                                </span>
                                            @endif
                                        </div>

                                        <p class="mt-1 text-xs font-semibold text-slate-800">
                                            Iklan: <span class="font-bold text-violet-700">{{ $revAd->iklan->judul ?? 'Iklan Promosi' }}</span>
                                        </p>
                                        <p class="mt-0.5 text-xs text-slate-500">
                                            Alasan Suspend Awal: <span class="italic text-slate-600 font-medium">{{ $revAd->alasan }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="text-xs text-slate-400 font-medium">
                                        Direvisi {{ $revAd->updated_at ? $revAd->updated_at->diffForHumans() : 'baru saja' }}
                                    </span>
                                    <a href="{{ route('iklan.index') }}#card-iklan-suspend"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-300 bg-white px-3 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-50 shadow-xs transition active:scale-95">
                                        <i class="fi fi-rr-eye text-xs"></i> Tinjau & Pulihkan
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-black text-slate-900">Transaksi Sewa Jasa Iklan Mitra KampSewa</h2>
                            <span class="rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-black text-purple-700">
                                Layanan Promosi Berbayar
                            </span>
                        </div>
                        <p class="text-xs text-slate-400">Menampilkan mitra toko yang menyewa slot iklan promosi KampSewa beserta detail nominal dan status pembayarannya.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-500">Total: {{ $adPaymentsList->total() }} transaksi sewa iklan</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($adPaymentsList as $pay)
                        @php
                            $isUnread = is_null($pay->read_at);
                        @endphp
                        <div class="flex flex-col gap-4 p-5 transition hover:bg-slate-50/70 lg:flex-row lg:items-center lg:justify-between {{ $isUnread ? 'bg-purple-50/30' : '' }}">
                            <div class="flex items-start sm:items-center gap-3.5 min-w-0 flex-1">
                                <div class="relative">
                                    @include('components.ui.avatar', [
                                        'nama' => $pay->user->name ?? 'Mitra',
                                        'foto' => $pay->user->foto ?? null,
                                        'class' => 'h-12 w-12 shrink-0',
                                        'rounded' => 'rounded-2xl',
                                    ])
                                    @if ($isUnread)
                                        <span class="absolute -top-1 -right-1 h-3.5 w-3.5 rounded-full border-2 border-white bg-purple-500"></span>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-sm font-black text-slate-900">{{ $pay->user->name ?? 'Mitra Pengiklan' }}</p>
                                        @if ($isUnread)
                                            <span class="rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-extrabold text-purple-700">Baru</span>
                                        @endif
                                        @if (!empty($pay->user->name_store))
                                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-700">
                                                <i class="fi fi-rr-shop mr-1"></i>{{ $pay->user->name_store }}
                                            </span>
                                        @endif
                                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-black {{ strtolower($pay->status_bayar) === 'lunas' || strtolower($pay->midtrans_transaction_status) === 'settlement' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ strtoupper($pay->status_bayar ?? 'PENDING') }}
                                        </span>
                                    </div>

                                    <p class="mt-1 text-xs font-semibold text-slate-700 line-clamp-1">
                                        Iklan: <span class="font-bold text-violet-700">{{ $pay->iklan->judul ?? 'Promosi Mitra' }}</span>
                                    </p>

                                    <div class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-400">
                                        <span>Metode: <b class="text-slate-700">{{ $pay->metode_bayar ?? 'Transfer Midtrans' }}</b></span>
                                        @if (!empty($pay->midtrans_order_id))
                                            <span class="font-mono text-[11px] text-slate-500">Order ID: {{ $pay->midtrans_order_id }}</span>
                                        @endif
                                        <span><i class="fi fi-rr-clock mr-1"></i>{{ $pay->created_at->diffForHumans() }} ({{ $pay->created_at->format('d M Y, H:i') }})</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                <div class="text-left sm:text-right">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Biaya Sewa Iklan</span>
                                    <span class="text-base font-black text-emerald-600">
                                        Rp {{ number_format($pay->total_bayar, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if ($isUnread)
                                        <button type="button" onclick="markSingleItemRead('ad_payment', {{ $pay->id }}, this)"
                                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:bg-slate-100">
                                            <i class="fi fi-rr-check text-[11px]"></i>
                                            Tandai Dibaca
                                        </button>
                                    @endif

                                    <a href="{{ route('penghasilan.index') }}" title="Lihat di Laporan Keuangan"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100">
                                        <i class="fi fi-rr-receipt text-[11px]"></i>
                                        <span>Pemasukan</span>
                                    </a>

                                    <a href="{{ route('iklan.index') }}"
                                        class="inline-flex items-center gap-1.5 rounded-xl bg-violet-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-violet-700">
                                        <span>Kelola Iklan</span>
                                        <i class="bi bi-chevron-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <i class="fi fi-rr-ad text-4xl text-slate-300"></i>
                            <p class="mt-2 text-sm font-bold text-slate-700">Tidak ada transaksi sewa iklan ditemukan</p>
                            <p class="text-xs text-slate-400">Belum ada mitra yang menyewa slot iklan pada rentang filter ini.</p>
                        </div>
                    @endforelse
                </div>

                @if ($adPaymentsList->hasPages())
                    <div class="border-t border-slate-100 p-4">
                        {{ $adPaymentsList->links('components.paginate.custom-pagination') }}
                    </div>
                @endif
            </div>
        @endif

        {{-- ------------------------------------------------------------
             TAB: AKTIVITAS SEWA PLATFORM (RENTALS) - OPSI 1
             ------------------------------------------------------------ --}}
        @if ($currentTab === 'rentals')
            <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-black text-slate-900">Aktivitas Transaksi Sewa Antar Pengguna di Platform</h2>
                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-black text-emerald-700">
                                Transaksi Marketplace
                            </span>
                        </div>
                        <p class="text-xs text-slate-400">Monitoring transaksi sewa antara customer dan mitra toko serta perolehan komisi platform KampSewa.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-500">Total: {{ $rentalsList->total() }} transaksi</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($rentalsList as $rent)
                        <div class="flex flex-col gap-4 p-5 transition hover:bg-slate-50/70 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 font-bold">
                                    <i class="fi fi-rr-box-alt text-lg"></i>
                                </div>

                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-sm font-black text-slate-900">{{ $rent->nama_penyewa }}</p>
                                        <span class="text-xs text-slate-400">&bull;</span>
                                        <span class="text-xs font-semibold text-slate-600">Mitra: <b>{{ $rent->nama_mitra }}</b></span>
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-black text-emerald-700">
                                            {{ $rent->status_penyewaan }}
                                        </span>
                                    </div>

                                    <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                        <span>Barang: <b class="text-slate-800">{{ $rent->nama_produk }}</b></span>
                                        <span>Total: <b class="text-slate-900">Rp {{ number_format($rent->total_pembayaran, 0, ',', '.') }}</b></span>
                                        @if ($rent->pajak_platform > 0)
                                            <span class="text-emerald-600 font-bold">Komisi Platform: Rp {{ number_format($rent->pajak_platform, 0, ',', '.') }}</span>
                                        @endif
                                        <span><i class="fi fi-rr-clock mr-1"></i>{{ \Carbon\Carbon::parse($rent->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <a href="{{ route('penyewaan.index') }}"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-slate-800">
                                    <span>Lihat di Penyewaan</span>
                                    <i class="bi bi-chevron-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <i class="fi fi-rr-box-alt text-4xl text-slate-300"></i>
                            <p class="mt-2 text-sm font-bold text-slate-700">Tidak ada transaksi penyewaan ditemukan</p>
                            <p class="text-xs text-slate-400">Coba ubah rentang waktu filter.</p>
                        </div>
                    @endforelse
                </div>

                @if ($rentalsList->hasPages())
                    <div class="border-t border-slate-100 p-4">
                        {{ $rentalsList->links('components.paginate.custom-pagination') }}
                    </div>
                @endif
            </div>
        @endif

        {{-- ------------------------------------------------------------
             TAB: ULASAN & RATING (RATINGS)
             ------------------------------------------------------------ --}}
        @if ($currentTab === 'ratings')
            <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-black text-slate-900">Ulasan & Rating Produk Pelanggan</h2>
                        <p class="text-xs text-slate-400">Pantau feedback kepuasan pelanggan terhadap barang sewaan yang disediakan mitra.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-500">Total: {{ $ratingsList->total() }} ulasan</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($ratingsList as $rt)
                        @php
                            $isBad = ($rt->rating <= 2);
                        @endphp
                        <div class="flex flex-col gap-4 p-5 transition hover:bg-slate-50/70 sm:flex-row sm:items-start sm:justify-between {{ $isBad ? 'bg-amber-50/30' : '' }}">
                            <div class="flex items-start gap-3.5 min-w-0 flex-1">
                                <div class="mt-0.5">
                                    @include('components.ui.avatar', [
                                        'nama' => $rt->user->name ?? 'Pelanggan',
                                        'foto' => $rt->user->foto ?? null,
                                        'class' => 'h-11 w-11',
                                        'rounded' => 'rounded-2xl',
                                    ])
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-sm font-black text-slate-900">{{ $rt->user->name ?? 'Pelanggan' }}</p>
                                        <div class="flex items-center gap-0.5 text-amber-400">
                                            @for ($s = 1; $s <= 5; $s++)
                                                <i class="fi fi-{{ $s <= $rt->rating ? 'ss' : 'rr' }}-star text-[11px] {{ $s <= $rt->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="rounded px-2 py-0.5 text-[10px] font-black {{ $isBad ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $rt->rating }} Bintang
                                        </span>
                                        @if ($isBad)
                                            <span class="rounded bg-rose-50 px-2 py-0.5 text-[10px] font-black text-rose-600">Perlu Perhatian</span>
                                        @endif
                                    </div>

                                    <p class="mt-1 text-xs font-semibold text-slate-600">
                                        Produk: <span class="font-bold text-violet-700">{{ $rt->produk->nama ?? '-' }}</span>
                                        @if ($rt->produk && $rt->produk->user)
                                            <span class="text-slate-400">&bull; Mitra: {{ $rt->produk->user->name }}</span>
                                        @endif
                                    </p>

                                    @if ($rt->ulasan)
                                        <p class="mt-2 rounded-2xl bg-slate-50 p-3 text-xs leading-relaxed text-slate-700 italic border border-slate-100">
                                            "{{ $rt->ulasan }}"
                                        </p>
                                    @endif

                                    <p class="mt-2 text-[11px] text-slate-400">
                                        <i class="fi fi-rr-clock mr-1"></i>{{ $rt->created_at->diffForHumans() }} ({{ $rt->created_at->format('d M Y') }})
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <i class="fi fi-rr-star text-4xl text-slate-300"></i>
                            <p class="mt-2 text-sm font-bold text-slate-700">Tidak ada ulasan ditemukan</p>
                            <p class="text-xs text-slate-400">Belum ada review pada kategori filter ini.</p>
                        </div>
                    @endforelse
                </div>

                @if ($ratingsList->hasPages())
                    <div class="border-t border-slate-100 p-4">
                        {{ $ratingsList->links('components.paginate.custom-pagination') }}
                    </div>
                @endif
            </div>
        @endif

        {{-- ------------------------------------------------------------
             TAB: REVISI PRODUK MITRA
             ------------------------------------------------------------ --}}
        @if ($currentTab === 'product_revisions')
            <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 bg-slate-50/50 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                            <h2 class="text-base font-black text-slate-900">Revisi Produk Mitra (Menunggu Ditinjau & Dipulihkan)</h2>
                            <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-black text-emerald-800 border border-emerald-200">
                                {{ $revisedProductsList->total() }} Menunggu Tindakan
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Mitra toko telah memperbarui informasi/foto produk yang sebelumnya disuspend. Silakan tinjau dan pulihkan kembali statusnya menjadi Tersedia.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-500">Total: {{ $revisedProductsList->total() }} Produk Direvisi</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($revisedProductsList as $revProd)
                        @php
                            $isUnreadRev = is_null($revProd->read_at);
                        @endphp
                        <div class="flex flex-col gap-4 p-5 transition hover:bg-slate-50/80 lg:flex-row lg:items-center lg:justify-between {{ $isUnreadRev ? 'bg-indigo-50/25' : '' }}">
                            <div class="flex items-start gap-4 min-w-0 flex-1">
                                {{-- Thumbnail Produk --}}
                                <div class="relative h-16 w-16 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-xs">
                                    <img src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($revProd->produk) }}"
                                        alt="{{ $revProd->produk->nama ?? 'Produk' }}"
                                        class="h-full w-full object-cover"
                                        onerror="this.onerror=null;this.src='{{ asset('images/illustration/filling-survey.png') }}';">
                                    @if ($isUnreadRev)
                                        <span class="absolute top-1 right-1 h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-sm font-black text-slate-900">{{ $revProd->produk->nama ?? 'Produk Mitra' }}</p>
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-black text-emerald-800 border border-emerald-200 whitespace-nowrap shrink-0">
                                            <span class="relative flex h-1.5 w-1.5">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                            </span>
                                            Sudah Direvisi Mitra
                                        </span>
                                        @if (!empty($revProd->produk->kategori))
                                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600">
                                                {{ $revProd->produk->kategori }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                        <span class="font-bold text-slate-700">Mitra: {{ $revProd->user->name ?? '-' }}</span>
                                        @if (!empty($revProd->user->name_store))
                                            <span class="text-slate-400">&bull; Toko: {{ $revProd->user->name_store }}</span>
                                        @endif
                                        <span class="text-slate-400">&bull; Email: {{ $revProd->user->email ?? '-' }}</span>
                                    </div>

                                    <div class="mt-2 rounded-xl bg-slate-50 border border-slate-100 p-2.5 text-xs text-slate-600">
                                        <p><span class="font-bold text-rose-600"><i class="fi fi-rr-ban mr-1"></i>Alasan Suspend Awal:</span> <span class="font-medium text-slate-800">{{ $revProd->alasan }}</span></p>
                                        @if (!empty($revProd->catatan))
                                            <p class="mt-1"><span class="font-bold text-amber-600"><i class="fi fi-rr-comment-alt mr-1"></i>Catatan Admin:</span> <span class="italic text-slate-700">{{ $revProd->catatan }}</span></p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2.5 shrink-0 lg:pl-4">
                                <span class="text-xs text-slate-400 font-semibold whitespace-nowrap">
                                    <i class="fi fi-rr-clock mr-1"></i>{{ $revProd->updated_at ? $revProd->updated_at->diffForHumans() : 'baru saja' }}
                                </span>

                                @if ($isUnreadRev)
                                    <button type="button"
                                        onclick="markSingleItemRead('produk_revisi', {{ $revProd->id }}, this)"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-50 shadow-xs transition active:scale-95">
                                        <i class="fi fi-rr-check text-xs"></i> Tandai Dibaca
                                    </button>
                                @endif

                                @if ($revProd->produk)
                                    <a href="{{ route('detail-pengguna.detail-produk-disewakan', ['user' => $revProd->id_user, 'namaproduk' => $revProd->produk->nama]) }}"
                                        class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-3.5 py-1.5 text-xs font-extrabold text-white shadow-sm hover:from-violet-700 hover:to-indigo-700 transition active:scale-95 whitespace-nowrap">
                                        <i class="fi fi-rr-eye text-xs"></i> Tinjau & Pulihkan
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-400">
                                <i class="fi fi-rr-box-alt text-3xl"></i>
                            </div>
                            <p class="mt-3 text-sm font-bold text-slate-800">Tidak ada produk direvisi</p>
                            <p class="mt-0.5 text-xs text-slate-400">Belum ada mitra yang mengajukan perbaikan produk yang ditangguhkan.</p>
                        </div>
                    @endforelse
                </div>

                @if ($revisedProductsList->hasPages())
                    <div class="border-t border-slate-100 p-4">
                        {{ $revisedProductsList->links('components.paginate.custom-pagination') }}
                    </div>
                @endif
            </div>
        @endif

    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formMarkAll = document.getElementById('form-mark-all-read');
        const btnPageMarkAll = document.getElementById('btn-page-mark-all');

        if (formMarkAll) {
            formMarkAll.addEventListener('submit', (e) => {
                e.preventDefault();

                if (btnPageMarkAll) {
                    btnPageMarkAll.disabled = true;
                    btnPageMarkAll.innerHTML = '<i class="fi fi-rr-spinner animate-spin text-[14px]"></i> Memproses...';
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                fetch(formMarkAll.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Semua notifikasi aktivitas telah ditandai sebagai dibaca.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        window.location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    formMarkAll.submit();
                });
            });
        }
    });

    function markSingleItemRead(type, id, btnElement) {
        if (!type || !id) return;

        if (btnElement) {
            btnElement.disabled = true;
            btnElement.innerHTML = '<i class="fi fi-rr-spinner animate-spin text-[11px]"></i>';
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch(`{{ route('developer.notifications.mark-item-read') }}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ type, id })
        })
        .then(res => res.json())
        .then(data => {
            if (btnElement) {
                btnElement.innerHTML = '<i class="fi fi-rr-check text-[11px]"></i> Dibaca';
                btnElement.classList.add('bg-emerald-50', 'text-emerald-600', 'border-emerald-200');
            }
            if (window.Swal) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Item ditandai sebagai dibaca',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
        .catch(err => {
            console.error(err);
            if (btnElement) {
                btnElement.disabled = false;
                btnElement.innerHTML = '<i class="fi fi-rr-check text-[11px]"></i> Tandai Dibaca';
            }
        });
    }
</script>
@endpush
