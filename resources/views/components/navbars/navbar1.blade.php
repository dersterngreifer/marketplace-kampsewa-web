@php
    $developerName = session('nama_lengkap') ?? 'Developer';
    $developerPhoto = session('foto');
    $notificationCount = $user_baru_terdaftar ? $user_baru_terdaftar->count() : 0;
@endphp

<nav class="sticky top-0 z-30 w-full border-b border-slate-200/70 bg-white/80 backdrop-blur-xl">
    <div class="w-full px-5 py-4 sm:px-6 lg:px-8">
        <div class="flex min-h-[76px] items-center justify-between gap-4">

            {{-- Left Section --}}
            <div class="flex min-w-0 items-center gap-4">

                {{-- Mobile Menu Button --}}
                <button type="button" id="sidebarToggle"
                    class="hidden h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition-all duration-300 hover:border-violet-200 hover:bg-violet-50 hover:text-violet-600 small-desktop:flex">
                    <i class="bi bi-list text-[26px] leading-none"></i>
                </button>

                {{-- Title --}}
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <p class="truncate text-sm font-semibold text-slate-400">
                            Selamat Datang,
                        </p>
                    </div>

                    <h1 class="mt-1 truncate text-2xl font-black tracking-tight text-slate-900 sm:text-[26px]">
                        Developer<span class="text-violet-600">!</span>
                    </h1>
                </div>
            </div>

            {{-- Right Section --}}
            <div class="relative flex shrink-0 items-center gap-3 sm:gap-4">

                {{-- Notification --}}
                <div id="form-notification-as-read" class="relative">
                    <button type="button" id="iconButton"
                        class="group relative flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#B381F4] to-[#5038ED] text-white shadow-lg shadow-violet-500/25 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-violet-500/30">

                        <i class="fi fi-rr-bell mt-1 text-[18px]"></i>

                        <span id="count-notification"
    class="{{ $notificationCount > 0 ? 'flex' : 'hidden' }} absolute -right-2 -top-2 h-6 min-w-6 items-center justify-center rounded-full border-2 border-white bg-red-500 px-1.5 text-[11px] font-black text-white shadow-md">
    {{ $notificationCount }}
</span>
                    </button>
                </div>

                {{-- Divider --}}
                <div class="hidden h-10 w-px bg-slate-200 sm:block"></div>

                {{-- Profile --}}
                <a href="{{ route('profile.index', ['nama_lengkap' => session('nama_lengkap'), 'user_baru_daftar' => $user_baru_terdaftar]) }}"
                    class="group flex items-center gap-3 rounded-3xl border border-transparent p-1.5 pr-3 transition-all duration-300 hover:border-slate-200 hover:bg-white hover:shadow-md">

                    <div class="hidden text-right sm:block">
                        <p class="max-w-[160px] truncate text-sm font-bold text-slate-900">
                            {{ $developerName }}
                        </p>
                        <p class="text-xs font-medium text-slate-400">
                            Developer
                        </p>
                    </div>

                    <div class="relative">
                        <img class="h-12 w-12 rounded-2xl object-cover ring-2 ring-white shadow-md"
                            src="@userPhoto($developerPhoto)" alt="Foto Profil">

                        <span
                            class="absolute bottom-0 right-0 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500"></span>
                    </div>

                    <i class="bi bi-chevron-down hidden text-xs text-slate-400 transition-transform duration-300 group-hover:translate-y-0.5 sm:block"></i>
                </a>

                {{-- Notification Dropdown --}}
                <div id="dropdown-notification"
                    class="absolute right-0 top-[calc(100%+14px)] z-50 hidden w-[360px] overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/10 sm:w-[430px]">

                    {{-- Header Dropdown --}}
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                        <div>
                            <h2 class="text-base font-black text-slate-900">
                                Notifikasi
                            </h2>
                            <p class="text-xs font-medium text-slate-400">
                                Aktivitas terbaru pengguna
                            </p>
                        </div>

                       @if ($notificationCount > 0)
    <span id="dropdown-notification-badge"
        class="rounded-full bg-violet-50 px-3 py-1 text-xs font-bold text-violet-600">
        {{ $notificationCount }} baru
    </span>
@endif
                    </div>

                    {{-- Content Dropdown --}}
                    <div class="max-h-[330px] overflow-y-auto">
                        @if ($notificationCount == 0)
                            <div class="flex min-h-[220px] flex-col items-center justify-center px-6 text-center">
                                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100">
                                    <i class="fi fi-rr-bell-slash mt-1 text-2xl text-slate-400"></i>
                                </div>

                                <p class="text-sm font-bold text-slate-800">
                                    Tidak ada notifikasi
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Semua aktivitas terbaru akan muncul di sini.
                                </p>
                            </div>
                        @else
                            @foreach ($user_baru_terdaftar as $user)
                                <a href="{{ route('detail-pengguna.index', ['user' => $user->id]) }}"
                                    class="group flex gap-4 border-b border-slate-100 px-5 py-4 transition-all duration-300 hover:bg-slate-50">

                                    <img src="@userPhoto($user->foto)"
                                        class="h-12 w-12 shrink-0 rounded-2xl object-cover shadow-sm" alt="Foto User">

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-black text-slate-900">
                                                    {{ $user->name }}
                                                </p>

                                                <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                                    {{ $user->name }} telah melakukan registrasi aplikasi.
                                                </p>
                                            </div>

                                            <span class="shrink-0 whitespace-nowrap text-[11px] font-semibold text-slate-400">
                                                {{ $user->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>

                    {{-- Footer Dropdown --}}
                    @if ($notificationCount > 0)
                        <div class="border-t border-slate-100 bg-slate-50 px-5 py-4">
                            <a href="{{ route('notification.index') }}"
                                class="flex items-center justify-center gap-2 rounded-2xl bg-white px-4 py-3 text-sm font-bold text-violet-600 shadow-sm transition-all duration-300 hover:bg-violet-600 hover:text-white">
                                Lihat semua notifikasi
                                <i class="bi bi-arrow-right text-sm"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    const iconButton = document.getElementById('iconButton');
    const dropdownNotification = document.getElementById('dropdown-notification');
    const countNotification = document.getElementById('count-notification');
    const dropdownNotificationBadge = document.getElementById('dropdown-notification-badge');
    const sidebarToggle = document.getElementById('sidebarToggle');

    let notificationMarkedAsRead = false;

    function hideNotificationBadges() {
        if (countNotification) {
            countNotification.innerText = '0';
            countNotification.classList.add('hidden');
            countNotification.classList.remove('flex');
        }

        if (dropdownNotificationBadge) {
            dropdownNotificationBadge.classList.add('hidden');
        }
    }

    if (iconButton && dropdownNotification) {
        iconButton.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const willOpen = dropdownNotification.classList.contains('hidden');

            dropdownNotification.classList.toggle('hidden');

            /*
             | Badge langsung dihilangkan saat dropdown dibuka.
             | Tidak menunggu fetch berhasil supaya UI langsung berubah.
             */
            if (willOpen) {
                hideNotificationBadges();
            }

            if (willOpen && !notificationMarkedAsRead) {
                notificationMarkedAsRead = true;

                fetch(`{{ route('mark-notification-as-read') }}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal menandai notifikasi sebagai terbaca');
                    }

                    return response.json();
                })
                .then(data => {
                    hideNotificationBadges();
                })
                .catch(error => {
                    console.error(error.message);
                });
            }
        });

        dropdownNotification.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        document.addEventListener('click', () => {
            dropdownNotification.classList.add('hidden');
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                dropdownNotification.classList.add('hidden');
            }
        });
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            document.dispatchEvent(new CustomEvent('toggle-sidebar'));
        });
    }
</script>