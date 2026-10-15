<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/scrollbar/scrollbar-sidebar.css') }}">
</head>

<body>
    @php
        $currentTitle = $title ?? '';

        $menuGroups = [
            [
                'label' => 'Utama',
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'icon' => 'fi fi-rr-house-chimney-window',
                        'href' => route('home.index'),
                        'active_titles' => ['Dashboard | Developer Kamp Sewa'],
                    ],
                    [
                        'label' => 'Notifications',
                        'icon' => 'fi fi-rr-bell',
                        'href' => route('notification.index'),
                        'active_titles' => ['Dashboard | Notification'],
                    ],
                ],
            ],
            [
                'label' => 'Customer',
                'items' => [
                    [
                        'label' => 'Kelola',
                        'icon' => 'fi fi-rr-user-gear',
                        'href' => route('kelola-pengguna.index'),
                        'active_titles' => [
                            'Detail Produk Sedang Disewa',
                            'Detail Produk Disewakan',
                            'Produk Disewakan',
                            'Kelola Pengguna | Developer Kamp Sewa',
                            'Detail Pengguna',
                        ],
                    ],
                    [
                        'label' => 'Informasi',
                        'icon' => 'fi fi-rr-file-user',
                        'href' => route('informasi-pengguna.index'),
                        'active_titles' => ['Informasi Pengguna'],
                    ],
                ],
            ],
            [
                'label' => 'Transaksi',
                'items' => [
                    [
                        'label' => 'Iklan Customer',
                        'icon' => 'fi fi-rr-ad',
                        'href' => route('iklan.index'),
                        'active_titles' => ['Iklan Customer'],
                    ],
                ],
            ],
            [
                'label' => 'Konten & Edukasi',
                'items' => [
                    [
                        'label' => 'Rekomendasi Wisata',
                        'icon' => 'fi fi-rr-map-marker-home',
                        'href' => route('developer.wisata.index'),
                        'active_titles' => ['Rekomendasi Wisata | Developer Kamp Sewa'],
                    ],
                    [
                        'label' => 'Berita Terkini',
                        'icon' => 'fi fi-rr-newspaper',
                        'href' => route('developer.berita.index'),
                        'active_titles' => ['Berita Terkini | Developer Kamp Sewa'],
                    ],
                ],
            ],
            [
                'label' => 'Keuangan & Laporan',
                'items' => [
                    [
                        'label' => 'Penghasilan & Pengeluaran',
                        'icon' => 'fi fi-rr-revenue-alt',
                        'href' => route('penghasilan.index'),
                        'active_titles' => ['Penghasilan', 'Pengeluaran'],
                    ],
                    [
                        'label' => 'Rekap Keuangan',
                        'icon' => 'fi fi-rr-book',
                        'href' => route('rekap-keuangan.index'),
                        'active_titles' => ['Rekap Keuangan | Developer'],
                    ],
                ],
            ],
            [
                'label' => 'Settings',
                'items' => [
                    [
                        'label' => 'Profile',
                        'icon' => null,
                        'href' => route('profile.index', ['nama_lengkap' => session('nama_lengkap') ?? 'developer']),
                        'active_titles' => ['Profile | Developer Kamp Sewa'],
                    ],
                ],
            ],
            [
                'label' => 'Manajemen Admin',
                'items' => [
                    [
                        'label' => 'Tambah Admin',
                        'icon' => 'fi fi-rr-user-add',
                        'href' => route('kelola-pengguna.create-admin'),
                        'active_titles' => ['Tambah Admin KampSewa | Developer'],
                    ],
                ],
            ],
        ];
    @endphp

    {{-- Mobile Overlay --}}
    <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/40 backdrop-blur-sm lg:hidden">
    </div>

    <aside id="developerSidebar"
        class="fixed left-0 top-0 z-50 flex h-screen w-[280px] -translate-x-full flex-col border-r border-slate-200/80 bg-white/95 shadow-[0_20px_60px_rgba(15,23,42,0.16)] backdrop-blur-xl transition-transform duration-300 ease-in-out lg:z-40 lg:translate-x-0">

        {{-- Brand --}}
        <div class="px-5 pt-5">
            <a href="{{ route('home.index') }}"
                class="group flex items-center gap-3 rounded-3xl border border-slate-100 bg-gradient-to-br from-white to-slate-50 p-3 transition-all duration-300 hover:border-violet-200 hover:shadow-lg hover:shadow-violet-100/70">

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-xs border border-slate-100 overflow-hidden">
                    <img class="w-9 h-9 object-contain rounded-xl" src="{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}" alt="KampSewa">
                </div>

                <div class="min-w-0">
                    <h1 class="truncate text-lg font-black tracking-tight text-slate-900">
                        KampSewa<span class="text-violet-600">.</span>
                    </h1>
                    <p class="truncate text-xs font-medium text-slate-400">
                        Developer Panel
                    </p>
                </div>
            </a>
            <button type="button" id="sidebarClose"
                class="mt-3 flex h-11 w-full items-center justify-center gap-2 rounded-2xl bg-slate-100 text-sm font-bold text-slate-600 transition-all duration-300 hover:bg-red-50 hover:text-red-500 lg:hidden">
                <i class="bi bi-x-lg text-sm"></i>
                Tutup Menu
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="mt-4 flex-1 overflow-y-auto overflow-x-hidden px-3 pb-4">
            <div class="space-y-5">
                @foreach ($menuGroups as $group)
                    <div>
                        <div class="mb-2 px-3">
                            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">
                                {{ $group['label'] }}
                            </p>
                        </div>

                        <div class="space-y-1">
                            @foreach ($group['items'] as $item)
                                @php
                                    $isActive = in_array($currentTitle, $item['active_titles'], true);
                                @endphp

                                <a href="{{ $item['href'] }}"
                                    class="group relative flex h-12 items-center gap-3 rounded-2xl px-3 text-sm font-semibold transition-all duration-300
                                    {{ $isActive
                                        ? 'bg-gradient-to-br from-[#B381F4] to-[#5038ED] text-white shadow-lg shadow-violet-500/25'
                                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}">

                                    {{-- Active Glow --}}
                                    @if ($isActive)
                                        <span
                                            class="absolute left-1 top-1/2 h-5 w-1 -translate-y-1/2 rounded-full bg-white/80"></span>
                                    @endif

                                    {{-- Icon Box --}}
                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl transition-all duration-300
                                        {{ $isActive
                                            ? 'bg-white/20 text-white'
                                            : 'bg-slate-100 text-slate-500 group-hover:bg-white group-hover:text-violet-600 group-hover:shadow-sm' }}">

                                        @if ($item['icon'])
                                            <i class="{{ $item['icon'] }} mt-1 text-[17px]"></i>
                                        @else
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        @endif
                                    </span>

                                    <span class="truncate">
                                        {{ $item['label'] }}
                                    </span>

                                    @if ($isActive)
                                        <span class="ml-auto h-2 w-2 rounded-full bg-white"></span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </nav>

        {{-- Bottom Card --}}
        <div class="border-t border-slate-100 p-4">
            <div
                class="mb-3 rounded-3xl bg-gradient-to-br from-slate-900 to-slate-800 p-4 text-white shadow-xl shadow-slate-900/10">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10">
                        <i class="fi fi-rr-user mt-1 text-sm"></i>
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold">
                            {{ session('nama_lengkap') ?? 'Developer' }}
                        </p>
                        <p class="truncate text-xs text-slate-300">
                            Admin KampSewa
                        </p>
                    </div>
                </div>
            </div>

            {{-- Logout --}}
            <form action="{{ route('logout') }}" method="POST" class="w-full" id="logoutFormSidebar">
                @csrf

                <button id="logoutButtonSidebar" type="button"
                    class="group flex h-12 w-full items-center gap-3 rounded-2xl px-3 text-sm font-semibold text-red-500 transition-all duration-300 hover:bg-red-50 hover:text-red-600">

                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-500 transition-all duration-300 group-hover:bg-white group-hover:shadow-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                    </span>

                    <span class="truncate">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutButtonSidebar = document.getElementById('logoutButtonSidebar');
            const logoutFormSidebar = document.getElementById('logoutFormSidebar');

            if (!logoutButtonSidebar || !logoutFormSidebar) {
                return;
            }

            logoutButtonSidebar.addEventListener('click', function(event) {
                event.preventDefault();

                if (typeof Swal === 'undefined') {
                    const confirmLogout = confirm('Apakah kamu yakin ingin logout?');

                    if (confirmLogout) {
                        logoutFormSidebar.submit();
                    }

                    return;
                }

                Swal.fire({
                    title: 'Keluar dari akun?',
                    text: 'Kamu akan logout dari dashboard KampSewa.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, logout',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,

                    customClass: {
                        popup: 'rounded-[28px] p-6 shadow-2xl',
                        title: 'text-[22px] font-black text-slate-900',
                        htmlContainer: 'text-sm text-slate-500',
                        icon: 'border-0',
                        actions: 'gap-3',
                        confirmButton: 'rounded-2xl bg-gradient-to-br from-[#B381F4] to-[#5038ED] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-violet-500/25 hover:shadow-xl',
                        cancelButton: 'rounded-2xl bg-slate-100 px-6 py-3 text-sm font-bold text-slate-600 hover:bg-slate-200',
                    },

                    buttonsStyling: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        logoutFormSidebar.submit();
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('developerSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');

            function openSidebar() {
                if (!sidebar || !overlay) return;

                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');

                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                if (!sidebar || !overlay) return;

                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');

                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    openSidebar();
                });
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', function() {
                    closeSidebar();
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    closeSidebar();
                });
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            document.addEventListener('toggle-sidebar', function() {
                openSidebar();
            });

            const sidebarLinks = document.querySelectorAll('#developerSidebar a');

            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });
        });
    </script>
</body>

</html>
