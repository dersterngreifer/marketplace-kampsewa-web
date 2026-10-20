@php
    $developerName = session('nama_lengkap') ?? 'Developer';
    $developerPhoto = session('foto');

    // Ambil user baru terdaftar hari ini jika belum tersedia
    if (!isset($user_baru_terdaftar) || !($user_baru_terdaftar instanceof \Illuminate\Support\Collection) || $user_baru_terdaftar->isEmpty()) {
        try {
            $user_baru_terdaftar = \App\Models\User::join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
                ->where('users.type', 0)
                ->whereDate('users.created_at', \Carbon\Carbon::today())
                ->where('status_notifikasi_user.status', 'unread')
                ->select('users.*')
                ->orderByDesc('users.created_at')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $user_baru_terdaftar = collect();
        }
    }

    // Ambil feedback chat baru yang belum dibaca
    $devUnreadFeedbacks = \App\Models\Feedback::with('user')
        ->whereNull('read_at')
        ->orderByDesc('created_at')
        ->take(5)
        ->get();

    // Ambil pesan chat feedback baru dari customer yang belum dibaca
    $devUnreadCustomerMessages = \App\Models\FeedbackMessage::with(['feedback.user', 'sender'])
        ->where('sender_type', 'customer')
        ->whereNull('read_at')
        ->orderByDesc('created_at')
        ->take(5)
        ->get();

    $devUnreadFeedbackCount = \App\Models\Feedback::whereNull('read_at')->count()
        + \App\Models\FeedbackMessage::where('sender_type', 'customer')
            ->whereNull('read_at')
            ->count();

    $devUnreadUsersCount = \App\Models\StatusNotifikasiUser::where('status', 'unread')->count();

    // Transaksi sewa iklan mitra terbaru
    $devRecentAdPayments = \App\Models\PembayaranIklan::with(['user', 'iklan'])
        ->orderByDesc('created_at')
        ->take(4)
        ->get();

    $devUnreadAdPaymentsCount = \App\Models\PembayaranIklan::whereNull('read_at')->count();

    // Notifikasi Iklan yang baru direvisi oleh Mitra
    $devRevisedAds = \App\Models\IklanSuspend::with(['user', 'iklan', 'detailIklan'])
        ->join('detail_iklan', 'iklan_suspends.id_detail_iklan', '=', 'detail_iklan.id')
        ->where('detail_iklan.status_iklan', 'Nonaktif')
        ->where('iklan_suspends.status_revisi', 'sudah_diperbaiki')
        ->select('iklan_suspends.*')
        ->orderByDesc('iklan_suspends.updated_at')
        ->take(5)
        ->get();

    $devUnreadRevisedAdsCount = \App\Models\IklanSuspend::join('detail_iklan', 'iklan_suspends.id_detail_iklan', '=', 'detail_iklan.id')
        ->where('detail_iklan.status_iklan', 'Nonaktif')
        ->where('iklan_suspends.status_revisi', 'sudah_diperbaiki')
        ->whereNull('iklan_suspends.read_at')
        ->count();

    // Notifikasi Produk yang baru direvisi oleh Mitra
    $devRevisedProducts = \App\Models\ProdukSuspend::with(['user', 'produk'])
        ->where('status_revisi', 'sudah_diperbaiki')
        ->orderByDesc('updated_at')
        ->take(5)
        ->get();

    $devUnreadRevisedProductsCount = \App\Models\ProdukSuspend::where('status_revisi', 'sudah_diperbaiki')
        ->whereNull('read_at')
        ->count();

    $notificationCount = $devUnreadUsersCount + $devUnreadFeedbackCount + $devUnreadAdPaymentsCount + $devUnreadRevisedAdsCount + $devUnreadRevisedProductsCount;

    $devNavAdminId = auth()->id() ?? session('id_user');
    $devUnreadChatCount = $devNavAdminId
        ? \App\Models\ChatMessage::where('receiver_id', $devNavAdminId)
            ->where('is_read', false)
            ->whereHas('sender', function ($q) {
                $q->where('type', 0);
            })
            ->count()
        : 0;
@endphp

<nav id="main-navbar"
    class="sticky top-0 z-30 w-full border-b border-slate-200/70 bg-white/80 backdrop-blur-xl transition-shadow duration-300 motion-reduce:transition-none">
    <div class="w-full px-5 py-4 sm:px-6 lg:px-8">
        <div class="flex min-h-[76px] items-center justify-between gap-4">

            {{-- Left Section --}}
            <div class="flex min-w-0 items-center gap-4">

                {{-- Sidebar Toggle --}}
                <button type="button" id="sidebarToggle" aria-label="Buka atau tutup sidebar"
                    class="hidden h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm outline-none transition-all duration-300 hover:-translate-y-0.5 hover:border-violet-200 hover:bg-violet-50 hover:text-violet-600 hover:shadow-[0_10px_20px_-8px_rgba(124,58,237,0.35)] focus-visible:ring-2 focus-visible:ring-violet-500/40 active:translate-y-0 active:scale-95 motion-reduce:transition-none small-desktop:flex">
                    <i class="bi bi-list text-[26px] leading-none"></i>
                </button>

                {{-- Title --}}
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="absolute inline-flex h-full w-full rounded-full bg-emerald-400/60 motion-safe:animate-ping"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        </span>
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

                {{-- Live Chat Mitra Button --}}
                <a href="{{ route('developer.chat.index') }}" aria-label="Live Chat Mitra" title="Live Chat Mitra & Customer"
                    class="group relative flex h-12 w-12 items-center justify-center rounded-2xl border border-violet-200/80 bg-violet-50/70 text-violet-600 outline-none transition-all duration-300 hover:-translate-y-0.5 hover:bg-violet-600 hover:text-white hover:shadow-[0_12px_24px_-8px_rgba(124,58,237,0.45)]">
                    <i class="bi bi-chat-dots-fill text-[19px] transition-transform duration-300 group-hover:scale-110"></i>
                    <span id="navbar-dev-chat-badge"
                        class="{{ $devUnreadChatCount > 0 ? 'flex' : 'hidden' }} absolute -right-2 -top-2 h-6 min-w-6 items-center justify-center rounded-full border-2 border-white bg-rose-500 px-1.5 text-[11px] font-black text-white shadow-[0_6px_12px_-4px_rgba(244,63,94,0.7)]">
                        {{ $devUnreadChatCount > 99 ? '99+' : $devUnreadChatCount }}
                    </span>
                </a>

                {{-- Notification --}}
                <div id="form-notification-as-read" class="relative">
                    <button type="button" id="iconButton" aria-label="Notifikasi" aria-haspopup="true"
                        aria-expanded="false" aria-controls="dropdown-notification"
                        class="group relative flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#B381F4] to-[#5038ED] text-white outline-none ring-offset-2 transition-all duration-300 shadow-[0_10px_22px_-8px_rgba(80,56,237,0.55)] hover:-translate-y-0.5 hover:shadow-[0_16px_30px_-8px_rgba(80,56,237,0.65)] focus-visible:ring-2 focus-visible:ring-violet-500 active:translate-y-0 active:scale-95 active:shadow-[0_6px_14px_-6px_rgba(80,56,237,0.55)] motion-reduce:transition-none">

                        <i
                            class="fi fi-rr-bell mt-1 origin-top text-[18px] transition-transform duration-300 group-hover:rotate-12 motion-reduce:transition-none"></i>

                        <span id="count-notification"
                            class="{{ $notificationCount > 0 ? 'flex' : 'hidden' }} absolute -right-2 -top-2 h-6 min-w-6 items-center justify-center rounded-full border-2 border-white bg-rose-500 px-1.5 text-[11px] font-black text-white shadow-[0_6px_12px_-4px_rgba(244,63,94,0.7)]">
                            <span
                                class="absolute inset-0 -z-10 rounded-full bg-rose-400/60 motion-safe:animate-ping"></span>
                            {{ $notificationCount }}
                        </span>
                    </button>
                </div>

                {{-- Divider --}}
                <div class="hidden h-10 w-px bg-gradient-to-b from-transparent via-slate-200 to-transparent sm:block">
                </div>

                {{-- Profile --}}
                <a href="{{ route('profile.index', ['nama_lengkap' => session('nama_lengkap') ?? 'developer', 'user_baru_daftar' => $user_baru_terdaftar]) }}"
                    class="group flex items-center gap-3 rounded-3xl border border-transparent p-1.5 pr-3 outline-none transition-all duration-300 hover:border-violet-100 hover:bg-white hover:shadow-[0_14px_30px_-12px_rgba(80,56,237,0.28)] focus-visible:border-violet-200 focus-visible:ring-2 focus-visible:ring-violet-500/30 active:scale-[0.98] motion-reduce:transition-none">

                    <div class="hidden text-right sm:block">
                        <p
                            class="max-w-[160px] truncate text-sm font-bold text-slate-900 transition-colors duration-300 group-hover:text-violet-700">
                            {{ $developerName }}
                        </p>
                        <p class="text-xs font-medium text-slate-400">
                            Developer
                        </p>
                    </div>

                    <div class="relative">
                        @include('components.ui.avatar', [
                            'nama' => $developerName,
                            'foto' => $developerPhoto,
                            'type' => 1,
                            'class' => 'h-12 w-12',
                            'rounded' => 'rounded-2xl',
                            'text' => 'text-[13px]',
                            'extra' => 'ring-2 ring-white shadow-md transition-all duration-300 group-hover:ring-violet-200 group-hover:shadow-violet-500/30',
                        ])

                        <span
                            class="absolute bottom-0 right-0 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500"></span>
                    </div>

                    <i
                        class="bi bi-chevron-right hidden text-xs text-slate-300 transition-all duration-300 group-hover:translate-x-0.5 group-hover:text-violet-500 sm:block"></i>
                </a>

                {{-- Notification Dropdown --}}
                <div id="dropdown-notification" role="region" aria-label="Daftar notifikasi"
                    class="pointer-events-none invisible absolute right-0 top-[calc(100%+14px)] z-50 w-[calc(100vw-2rem)] sm:w-[500px] sm:max-w-[500px] origin-top-right translate-y-2 scale-95 overflow-hidden rounded-3xl border border-slate-200/80 bg-white opacity-0 shadow-[0_28px_60px_-14px_rgba(80,56,237,0.28),0_10px_24px_-10px_rgba(15,23,42,0.14)] transition-all duration-200 ease-out motion-reduce:transition-none">

                    {{-- Header Dropdown --}}
                    <div
                        class="flex items-center justify-between border-b border-slate-100 bg-gradient-to-r from-violet-50/70 via-white to-white px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-violet-600 shadow-[0_8px_16px_-8px_rgba(124,58,237,0.45)] ring-1 ring-violet-100">
                                <i class="fi fi-rr-bell mt-0.5 text-base"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-black text-slate-900">
                                    Notifikasi
                                </h2>
                                <p class="text-xs font-medium text-slate-400">
                                    Aktivitas terbaru pengguna
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span id="dropdown-notification-badge"
                                class="{{ $notificationCount > 0 ? '' : 'hidden' }} rounded-full bg-violet-100/80 px-2.5 py-1 text-[11px] font-bold text-violet-700 ring-1 ring-violet-200/70">
                                {{ $notificationCount }} baru
                            </span>

                            <button type="button" id="btn-mark-all-read" title="Tandai semua notifikasi sudah dibaca"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-violet-200/80 bg-white px-2.5 py-1.5 text-xs font-bold text-violet-600 shadow-sm transition hover:bg-violet-600 hover:text-white hover:border-violet-600 active:scale-95">
                                <i class="fi fi-rr-check-double text-[11px]"></i>
                                <span class="hidden sm:inline">Tandai Dibaca</span>
                            </button>
                        </div>
                    </div>

                    {{-- Content Dropdown --}}
                    <div class="max-h-[330px] overflow-y-auto overscroll-contain">
                        @if ($notificationCount == 0 && $devRevisedAds->isEmpty() && $devRevisedProducts->isEmpty() && $devRecentAdPayments->isEmpty() && $user_baru_terdaftar->isEmpty() && $devUnreadFeedbacks->isEmpty() && $devUnreadCustomerMessages->isEmpty())
                            <div class="flex min-h-[220px] flex-col items-center justify-center px-6 text-center">
                                <div
                                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-slate-50 to-slate-100 ring-1 ring-slate-200/70">
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
                            {{-- Notifikasi Produk Direvisi Mitra --}}
                            @foreach ($devRevisedProducts as $revProd)
                                @if ($revProd->produk)
                                    <a href="{{ route('detail-pengguna.detail-produk-disewakan', ['user' => $revProd->id_user, 'namaproduk' => $revProd->produk->nama]) }}"
                                        class="group relative flex gap-4 border-b border-slate-100 px-5 py-4 outline-none transition-all duration-300 last:border-b-0 hover:bg-indigo-50/60 focus-visible:bg-indigo-50/60 motion-reduce:transition-none">
                                        <span
                                            class="absolute inset-y-3 left-0 w-1 origin-center scale-y-0 rounded-r-full bg-gradient-to-b from-indigo-400 to-indigo-600 transition-transform duration-300 group-hover:scale-y-100 group-focus-visible:scale-y-100 motion-reduce:transition-none"></span>

                                        @include('components.ui.avatar', [
                                            'nama' => $revProd->user->name ?? 'Mitra',
                                            'foto' => $revProd->user->foto ?? null,
                                            'class' => 'h-12 w-12',
                                            'rounded' => 'rounded-2xl',
                                            'text' => 'text-[13px]',
                                            'extra' => 'shadow-sm ring-2 ring-indigo-200 transition-all duration-300 group-hover:ring-indigo-300 group-hover:shadow-indigo-500/25',
                                        ])

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <p class="truncate max-w-[140px] sm:max-w-[200px] text-sm font-black text-slate-900 transition-colors duration-300 group-hover:text-indigo-700">
                                                            {{ $revProd->user->name ?? 'Mitra' }}
                                                        </p>
                                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-0.5 text-[10px] font-extrabold text-indigo-800 border border-indigo-200 whitespace-nowrap shrink-0">
                                                            <span class="relative flex h-2 w-2">
                                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                                                            </span>
                                                            Produk Direvisi
                                                        </span>
                                                    </div>

                                                    <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                                        Produk <span class="font-bold text-slate-700">"{{ $revProd->produk->nama ?? 'Produk' }}"</span> telah direvisi oleh mitra dan siap ditinjau untuk dipulihkan.
                                                    </p>
                                                </div>

                                                <span class="flex shrink-0 items-center gap-1.5 whitespace-nowrap text-[11px] font-semibold text-slate-400">
                                                    @if (is_null($revProd->read_at))
                                                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                                    @endif
                                                    {{ $revProd->updated_at ? $revProd->updated_at->diffForHumans() : 'Baru' }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach

                            {{-- Notifikasi Iklan Direvisi Mitra --}}
                            @foreach ($devRevisedAds as $revAd)
                                <a href="{{ route('iklan.index') }}#card-iklan-suspend"
                                    class="group relative flex gap-4 border-b border-slate-100 px-5 py-4 outline-none transition-all duration-300 last:border-b-0 hover:bg-emerald-50/60 focus-visible:bg-emerald-50/60 motion-reduce:transition-none">
                                    <span
                                        class="absolute inset-y-3 left-0 w-1 origin-center scale-y-0 rounded-r-full bg-gradient-to-b from-emerald-400 to-emerald-600 transition-transform duration-300 group-hover:scale-y-100 group-focus-visible:scale-y-100 motion-reduce:transition-none"></span>

                                    @include('components.ui.avatar', [
                                        'nama' => $revAd->user->name ?? 'Mitra',
                                        'foto' => $revAd->user->foto ?? null,
                                        'class' => 'h-12 w-12',
                                        'rounded' => 'rounded-2xl',
                                        'text' => 'text-[13px]',
                                        'extra' => 'shadow-sm ring-2 ring-emerald-200 transition-all duration-300 group-hover:ring-emerald-300 group-hover:shadow-emerald-500/25',
                                    ])

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <p class="truncate max-w-[140px] sm:max-w-[200px] text-sm font-black text-slate-900 transition-colors duration-300 group-hover:text-emerald-700">
                                                        {{ $revAd->user->name ?? 'Mitra' }}
                                                    </p>
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-extrabold text-emerald-800 border border-emerald-200 whitespace-nowrap shrink-0">
                                                        <span class="relative flex h-2 w-2">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                        </span>
                                                        Iklan Direvisi
                                                    </span>
                                                </div>

                                                <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                                    Iklan <span class="font-bold text-slate-700">"{{ $revAd->iklan->judul ?? 'Iklan Promosi' }}"</span> telah direvisi oleh mitra dan siap ditinjau untuk dipulihkan.
                                                </p>
                                            </div>

                                            <span class="flex shrink-0 items-center gap-1.5 whitespace-nowrap text-[11px] font-semibold text-slate-400">
                                                @if (is_null($revAd->read_at))
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                @endif
                                                {{ $revAd->updated_at ? $revAd->updated_at->diffForHumans() : 'Baru' }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            {{-- Notifikasi Transaksi Sewa Iklan Mitra --}}
                            @foreach ($devRecentAdPayments as $adPay)
                                <a href="{{ route('developer.notifications.index', ['tab' => 'ads']) }}"
                                    class="group relative flex gap-4 border-b border-slate-100 px-5 py-4 outline-none transition-all duration-300 last:border-b-0 hover:bg-violet-50/60 focus-visible:bg-violet-50/60 motion-reduce:transition-none">
                                    <span
                                        class="absolute inset-y-3 left-0 w-1 origin-center scale-y-0 rounded-r-full bg-gradient-to-b from-[#B381F4] to-[#5038ED] transition-transform duration-300 group-hover:scale-y-100 group-focus-visible:scale-y-100 motion-reduce:transition-none"></span>

                                    @include('components.ui.avatar', [
                                        'nama' => $adPay->user->name ?? 'Mitra',
                                        'foto' => $adPay->user->foto ?? null,
                                        'class' => 'h-12 w-12',
                                        'rounded' => 'rounded-2xl',
                                        'text' => 'text-[13px]',
                                        'extra' => 'shadow-sm ring-2 ring-transparent transition-all duration-300 group-hover:ring-purple-200 group-hover:shadow-purple-500/25',
                                    ])

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <p class="truncate max-w-[140px] sm:max-w-[200px] text-sm font-black text-slate-900 transition-colors duration-300 group-hover:text-violet-700">
                                                        {{ $adPay->user->name ?? 'Mitra' }}
                                                    </p>
                                                    <span class="rounded bg-purple-100 px-2 py-0.5 text-[10px] font-extrabold text-purple-700 whitespace-nowrap shrink-0">
                                                        Sewa Iklan
                                                    </span>
                                                </div>

                                                <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                                    Sewa promosi: <span class="font-bold text-slate-700">{{ $adPay->iklan->judul ?? 'Iklan Mitra' }}</span> (Rp {{ number_format($adPay->total_bayar, 0, ',', '.') }})
                                                </p>
                                            </div>

                                            <span class="flex shrink-0 items-center gap-1.5 whitespace-nowrap text-[11px] font-semibold text-slate-400">
                                                @if (is_null($adPay->read_at))
                                                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                                                @endif
                                                {{ $adPay->created_at ? $adPay->created_at->diffForHumans() : 'Baru' }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            {{-- Notifikasi Feedback Baru --}}
                            @foreach ($devUnreadFeedbacks as $fb)
                                <a href="{{ route('notification.index') }}"
                                    class="group relative flex gap-4 border-b border-slate-100 px-5 py-4 outline-none transition-all duration-300 last:border-b-0 hover:bg-violet-50/60 focus-visible:bg-violet-50/60 motion-reduce:transition-none">
                                    <span
                                        class="absolute inset-y-3 left-0 w-1 origin-center scale-y-0 rounded-r-full bg-gradient-to-b from-[#B381F4] to-[#5038ED] transition-transform duration-300 group-hover:scale-y-100 group-focus-visible:scale-y-100 motion-reduce:transition-none"></span>

                                    @include('components.ui.avatar', [
                                        'nama' => $fb->user->name ?? 'Customer',
                                        'foto' => $fb->user->foto ?? null,
                                        'class' => 'h-12 w-12',
                                        'rounded' => 'rounded-2xl',
                                        'text' => 'text-[13px]',
                                        'extra' => 'shadow-sm ring-2 ring-transparent transition-all duration-300 group-hover:ring-violet-200 group-hover:shadow-violet-500/25',
                                    ])

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <p class="truncate max-w-[140px] sm:max-w-[200px] text-sm font-black text-slate-900 transition-colors duration-300 group-hover:text-violet-700">
                                                        {{ $fb->user->name ?? 'Pengguna' }}
                                                    </p>
                                                    <span class="rounded bg-violet-100 px-2 py-0.5 text-[10px] font-extrabold text-violet-700 whitespace-nowrap shrink-0">
                                                        Chat Feedback
                                                    </span>
                                                </div>

                                                <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                                    "{{ $fb->deskripsi }}"
                                                </p>
                                            </div>

                                            <span class="flex shrink-0 items-center gap-1.5 whitespace-nowrap text-[11px] font-semibold text-slate-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-violet-500 animate-pulse"></span>
                                                {{ $fb->created_at ? $fb->created_at->diffForHumans() : 'Baru' }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            {{-- Notifikasi Balasan Chat Feedback dari Customer --}}
                            @foreach ($devUnreadCustomerMessages as $cMsg)
                                <a href="{{ route('notification.index') }}"
                                    class="group relative flex gap-4 border-b border-slate-100 px-5 py-4 outline-none transition-all duration-300 last:border-b-0 hover:bg-violet-50/60 focus-visible:bg-violet-50/60 motion-reduce:transition-none">
                                    <span
                                        class="absolute inset-y-3 left-0 w-1 origin-center scale-y-0 rounded-r-full bg-gradient-to-b from-[#B381F4] to-[#5038ED] transition-transform duration-300 group-hover:scale-y-100 group-focus-visible:scale-y-100 motion-reduce:transition-none"></span>

                                    @include('components.ui.avatar', [
                                        'nama' => $cMsg->sender->name ?? 'Customer',
                                        'foto' => $cMsg->sender->foto ?? null,
                                        'class' => 'h-12 w-12',
                                        'rounded' => 'rounded-2xl',
                                        'text' => 'text-[13px]',
                                        'extra' => 'shadow-sm ring-2 ring-transparent transition-all duration-300 group-hover:ring-violet-200 group-hover:shadow-violet-500/25',
                                    ])

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <p class="truncate max-w-[140px] sm:max-w-[200px] text-sm font-black text-slate-900 transition-colors duration-300 group-hover:text-violet-700">
                                                        {{ $cMsg->sender->name ?? 'Pengguna' }}
                                                    </p>
                                                    <span class="rounded bg-violet-100 px-2 py-0.5 text-[10px] font-extrabold text-violet-700 whitespace-nowrap shrink-0">
                                                        Pesan Feedback
                                                    </span>
                                                </div>

                                                <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                                    "{{ $cMsg->message }}"
                                                </p>
                                            </div>

                                            <span class="flex shrink-0 items-center gap-1.5 whitespace-nowrap text-[11px] font-semibold text-slate-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-violet-500 animate-pulse"></span>
                                                {{ $cMsg->created_at ? $cMsg->created_at->diffForHumans() : 'Baru' }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            {{-- Notifikasi User Baru Terdaftar --}}
                            @foreach ($user_baru_terdaftar as $user)
                                <a href="{{ route('detail-pengguna.index', ['user' => $user->id]) }}"
                                    class="group relative flex gap-4 border-b border-slate-100 px-5 py-4 outline-none transition-all duration-300 last:border-b-0 hover:bg-violet-50/60 focus-visible:bg-violet-50/60 motion-reduce:transition-none">

                                    {{-- Accent bar saat hover --}}
                                    <span
                                        class="absolute inset-y-3 left-0 w-1 origin-center scale-y-0 rounded-r-full bg-gradient-to-b from-[#B381F4] to-[#5038ED] transition-transform duration-300 group-hover:scale-y-100 group-focus-visible:scale-y-100 motion-reduce:transition-none"></span>

                                    @include('components.ui.avatar', [
                                        'nama' => $user->name,
                                        'foto' => $user->foto,
                                        'class' => 'h-12 w-12',
                                        'rounded' => 'rounded-2xl',
                                        'text' => 'text-[13px]',
                                        'extra' => 'shadow-sm ring-2 ring-transparent transition-all duration-300 group-hover:ring-violet-200 group-hover:shadow-violet-500/25',
                                    ])

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <p
                                                        class="truncate max-w-[140px] sm:max-w-[200px] text-sm font-black text-slate-900 transition-colors duration-300 group-hover:text-violet-700">
                                                        {{ $user->name }}
                                                    </p>
                                                    <span class="rounded bg-blue-100 px-2 py-0.5 text-[10px] font-extrabold text-blue-700 whitespace-nowrap shrink-0">
                                                        Mitra Baru
                                                    </span>
                                                </div>

                                                <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                                    {{ $user->name }} telah melakukan registrasi aplikasi.
                                                </p>
                                            </div>

                                            <span
                                                class="flex shrink-0 items-center gap-1.5 whitespace-nowrap text-[11px] font-semibold text-slate-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                                {{ $user->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>

                    {{-- Footer Dropdown --}}
                    <div class="border-t border-slate-100 bg-slate-50/80 px-5 py-4">
                        <a href="{{ route('developer.notifications.index') }}"
                            class="group flex items-center justify-center gap-2 rounded-2xl bg-white px-4 py-3 text-sm font-bold text-violet-600 shadow-sm ring-1 ring-violet-100 outline-none transition-all duration-300 hover:bg-gradient-to-r hover:from-[#9b6cf2] hover:to-[#5038ED] hover:text-white hover:shadow-[0_14px_24px_-10px_rgba(80,56,237,0.6)] hover:ring-transparent focus-visible:ring-2 focus-visible:ring-violet-500 active:scale-[0.98] motion-reduce:transition-none">
                            Lihat semua notifikasi
                            <i
                                class="bi bi-arrow-right text-sm transition-transform duration-300 group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    (() => {
        const navbar = document.getElementById('main-navbar');
        const iconButton = document.getElementById('iconButton');
        const dropdownNotification = document.getElementById('dropdown-notification');
        const countNotification = document.getElementById('count-notification');
        const dropdownNotificationBadge = document.getElementById('dropdown-notification-badge');
        const btnMarkAllRead = document.getElementById('btn-mark-all-read');
        const sidebarToggle = document.getElementById('sidebarToggle');

        let notificationMarkedAsRead = false;

        const closedClasses = ['invisible', 'pointer-events-none', 'opacity-0', 'translate-y-2', 'scale-95'];
        const openClasses = ['visible', 'opacity-100', 'translate-y-0', 'scale-100'];

        /* Shadow navbar muncul setelah halaman di-scroll */
        if (navbar) {
            const syncNavbarShadow = () => {
                navbar.classList.toggle('shadow-[0_12px_32px_-16px_rgba(80,56,237,0.25)]', window.scrollY > 8);
            };

            syncNavbarShadow();
            window.addEventListener('scroll', syncNavbarShadow, { passive: true });
        }

        function hideNotificationBadges() {
            if (countNotification) {
                countNotification.classList.add('hidden');
                countNotification.classList.remove('flex');
            }

            if (dropdownNotificationBadge) {
                dropdownNotificationBadge.classList.add('hidden');
            }

            // Juga sembunyikan badge di sidebar jika ada
            const sidebarNotifBadge = document.getElementById('sidebar-dev-activity-notif-badge');
            if (sidebarNotifBadge) {
                sidebarNotifBadge.classList.add('hidden');
            }
        }

        function isDropdownOpen() {
            return iconButton.getAttribute('aria-expanded') === 'true';
        }

        function setDropdown(open) {
            dropdownNotification.classList.remove(...(open ? closedClasses : openClasses));
            dropdownNotification.classList.add(...(open ? openClasses : closedClasses));
            iconButton.setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        function markNotificationAsRead() {
            if (notificationMarkedAsRead) return;

            notificationMarkedAsRead = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch(`{{ route('developer.notifications.mark-all-read') }}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal menandai notifikasi sebagai terbaca');
                    }
                    return response.json();
                })
                .then(() => {
                    hideNotificationBadges();
                })
                .catch(error => {
                    console.error(error.message);
                });
        }

        if (btnMarkAllRead) {
            btnMarkAllRead.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                btnMarkAllRead.disabled = true;
                btnMarkAllRead.innerHTML = '<i class="fi fi-rr-spinner animate-spin text-[11px]"></i> Loading...';

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                fetch(`{{ route('developer.notifications.mark-all-read') }}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                })
                .then(res => res.json())
                .then(data => {
                    notificationMarkedAsRead = true;
                    hideNotificationBadges();
                    btnMarkAllRead.innerHTML = '<i class="fi fi-rr-check text-[11px]"></i> Sudah Dibaca';
                    btnMarkAllRead.classList.remove('text-violet-600', 'border-violet-200/80');
                    btnMarkAllRead.classList.add('bg-emerald-50', 'text-emerald-600', 'border-emerald-200');
                    if (window.Swal) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Semua notifikasi ditandai sebagai dibaca',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    btnMarkAllRead.disabled = false;
                    btnMarkAllRead.innerHTML = '<i class="fi fi-rr-check-double text-[11px]"></i> Tandai Dibaca';
                });
            });
        }

        if (iconButton && dropdownNotification) {
            iconButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const willOpen = !isDropdownOpen();

                setDropdown(willOpen);

                if (willOpen) {
                    hideNotificationBadges();
                    markNotificationAsRead();
                }
            });

            dropdownNotification.addEventListener('click', (e) => {
                e.stopPropagation();
            });

            document.addEventListener('click', () => {
                if (isDropdownOpen()) setDropdown(false);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && isDropdownOpen()) {
                    setDropdown(false);
                    iconButton.focus();
                }
            });
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                document.dispatchEvent(new CustomEvent('toggle-sidebar'));
            });
        }
    })();
</script>