@php
    $navUserId = session('id_user') ?? (auth()->check() ? auth()->id() : null);
    $navSuspendedCount = 0;
    $navForceCompletedCount = 0;
    $navPendingOrdersCount = 0;
    $navCustomerNotifications = collect();
    $navUnreadCount = 0;

    if ($navUserId) {
        // Cek iklan yang disuspend
        $navSuspendedCount = \App\Models\Iklan::join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->where('iklan.id_user', $navUserId)
            ->where('detail_iklan.status_iklan', 'Nonaktif')
            ->count();

        // Cek iklan yang diselesaikan paksa (berhenti paksa)
        $navForceCompletedCount = \App\Models\Iklan::join('detail_iklan', 'iklan.id', '=', 'detail_iklan.id_iklan')
            ->where('iklan.id_user', $navUserId)
            ->where('detail_iklan.status_iklan', 'Selesai')
            ->where('detail_iklan.is_force_completed', true)
            ->count();

        // Cek pesanan sewa masuk yang pending (untuk mitra toko)
        $navPendingOrdersCount = \App\Models\Penyewaan::join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->where('produk.id_user', $navUserId)
            ->where('penyewaan.status_penyewaan', 'Pending')
            ->distinct('penyewaan.id')
            ->count('penyewaan.id');

        // Ambil notifikasi pengguna
        $navCustomerNotifications = \App\Models\CustomerNotification::where('id_user', $navUserId)
            ->orderBy('is_read', 'asc')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $navUnreadCount = \App\Models\CustomerNotification::where('id_user', $navUserId)
            ->where('is_read', false)
            ->count();

        $navUnreadChatCount = \App\Models\ChatMessage::where('receiver_id', $navUserId)
            ->where('is_read', false)
            ->whereHas('sender', function ($q) {
                $q->where('type', 1);
            })
            ->count();
    } else {
        $navUnreadChatCount = 0;
    }

    $hasAdWarnings = ($navSuspendedCount > 0 || $navForceCompletedCount > 0);
    $hasRedUnread = $navCustomerNotifications->where('is_read', false)->whereIn('tipe', ['iklan_suspend', 'iklan_force_complete', 'pesanan_dibatalkan', 'produk_suspend'])->count() > 0;
@endphp
<nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm w-full transition-all duration-300">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-[70px] items-center">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('dashboard-cust', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('assets/logo/favicons/android-chrome-512x512.png') }}" alt="KampSewa Logo" class="w-10 h-10 object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
                    <span class="font-bold text-2xl text-gray-800 tracking-tight group-hover:text-blue-600 transition-colors">KampSewa.</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-2 lg:space-x-4 items-center h-full">
                <a href="{{ route('dashboard-cust', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                   class="inline-flex items-center px-4 h-[70px] text-[15px] font-medium transition-all duration-200 border-b-2 relative group
                   {{ $title == 'Dashboard | Customer' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }}">
                   <div class="flex items-center gap-2 group-hover:-translate-y-0.5 transition-transform duration-200">
                       <i class="typcn typcn-chart-area-outline text-[20px]"></i> Dashboard
                   </div>
                </a>
                
                <!-- Dropdown Transaksi -->
                <div class="relative h-[70px] flex items-center group">
                    <button id="transaksi-btn" class="inline-flex items-center px-4 h-[70px] text-[15px] font-medium transition-all duration-200 border-b-2 relative focus:outline-none focus:ring-0 outline-none cursor-default
                        {{ in_array($title, ['Kasir / Order Offline', 'Order Selesai', 'Denda Pelanggan', 'Sewa Berlangsung', 'Terima Order Masuk', 'Order Masuk', 'Kelola Iklan', 'Iklan | Customer', 'Kelola Promo & Diskon | KampSewa', 'Buat Promo & Diskon Baru | KampSewa', 'Edit Promo Toko | KampSewa', 'Pengaturan Biaya Antar | KampSewa']) ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 group-hover:text-blue-600' }}">
                        <div class="flex items-center gap-2 transition-transform duration-200 relative">
                            <i class="typcn typcn-shopping-bag text-[20px]"></i> Transaksi
                            @if ($hasAdWarnings)
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                </span>
                            @elseif ($navPendingOrdersCount > 0)
                                <span class="inline-flex items-center justify-center px-1.5 py-0.2 text-[10px] font-bold leading-none text-white bg-blue-600 rounded-full animate-pulse">
                                    {{ $navPendingOrdersCount }}
                                </span>
                            @endif
                            <i id="transaksi-chevron" class="bi bi-chevron-down text-[12px] ml-1 transition-transform duration-300 group-hover:rotate-180"></i>
                        </div>
                    </button>
                    
                    <div id="transaksi-dropdown" class="absolute top-[70px] left-0 w-max rounded-2xl shadow-xl bg-white border border-gray-100 z-50 overflow-hidden transform transition-all duration-300 opacity-0 pointer-events-none translate-y-4 group-hover:opacity-100 group-hover:pointer-events-auto group-hover:translate-y-0">
                        <div class="p-3 space-y-1">
                            <a href="{{ route('menu-transaksi.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center justify-between gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all duration-200 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100/50 flex items-center justify-center text-blue-600"><i class="bi bi-cart-check"></i></div>
                                    Penyewaan & Transaksi
                                </div>
                                @if ($navPendingOrdersCount > 0)
                                    <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full ml-2">
                                        {{ $navPendingOrdersCount }} Baru
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('kasir-offline.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition-all duration-200 whitespace-nowrap">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100/50 flex items-center justify-center text-emerald-600"><i class="bi bi-shop"></i></div>
                                Kasir / Order Offline
                            </a>
                            <a href="{{ route('menu-pengiriman.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-teal-50 hover:text-teal-600 rounded-xl transition-all duration-200 whitespace-nowrap">
                                <div class="w-8 h-8 rounded-lg bg-teal-100/50 flex items-center justify-center text-teal-600"><i class="bi bi-truck"></i></div>
                                Biaya Antar & Pengiriman
                            </a>
                            <a href="{{ route('menu-promo.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-amber-50 hover:text-amber-600 rounded-xl transition-all duration-200 whitespace-nowrap">
                                <div class="w-8 h-8 rounded-lg bg-amber-100/50 flex items-center justify-center text-amber-600"><i class="bi bi-percent"></i></div>
                                Diskon Produk Toko
                            </a>
                            <a href="{{ route('buat-iklan.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center justify-between gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-purple-50 hover:text-purple-600 rounded-xl transition-all duration-200 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-purple-100/50 flex items-center justify-center text-purple-600"><i class="bi bi-megaphone"></i></div>
                                    Buat Promosi / Iklan
                                </div>
                                @if ($hasAdWarnings)
                                    <span class="inline-flex items-center justify-center text-red-500 animate-pulse ml-2" title="Ada iklan Anda yang ditangguhkan atau dihentikan paksa!">
                                        <i class="bi bi-exclamation-circle-fill text-sm"></i>
                                    </span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('menu-produk.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                   class="inline-flex items-center px-4 h-[70px] text-[15px] font-medium transition-all duration-200 border-b-2 relative group
                   {{ in_array($title, ['Update Produk', 'Detail Produk', 'Tambah Produk', 'Sedang Disewa | KampSewa', 'Kelola Produk | KampSewa', 'Produk Menu | KampSewa']) ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }}">
                   <div class="flex items-center gap-2 group-hover:-translate-y-0.5 transition-transform duration-200">
                       <i class="typcn typcn-shopping-cart text-[20px]"></i> Produk
                   </div>
                </a>

                <a href="{{ route('keuangan.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                   class="inline-flex items-center px-4 h-[70px] text-[15px] font-medium transition-all duration-200 border-b-2 relative group
                   {{ in_array($title, ['Menu Pengeluaran', 'Menu Keuangan']) ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }}">
                   <div class="flex items-center gap-2 group-hover:-translate-y-0.5 transition-transform duration-200">
                       <i class="typcn typcn-chart-bar-outline text-[20px]"></i> Keuangan & Laporan
                   </div>
                </a>
            </div>

            <!-- Right side (Profile & Messages) -->
            <div class="hidden md:flex items-center space-x-3 lg:space-x-4">
                <!-- Chat Admin Quick Button -->
                <a href="{{ route('customer.chat-admin.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}"
                   class="relative p-2 text-gray-400 hover:text-blue-600 transition-colors bg-gray-50 hover:bg-blue-50 rounded-full focus:outline-none"
                   title="Chat Admin & Bantuan">
                    <i class="bi bi-chat-dots text-[20px]"></i>
                    @if ($navUnreadChatCount > 0)
                    <span id="nav-chat-admin-badge" class="absolute -top-1 -right-1 flex h-5 min-w-[20px] px-1.5 items-center justify-center rounded-full text-[10px] font-extrabold text-white bg-rose-500 border-2 border-white shadow-sm animate-pulse">
                        {{ $navUnreadChatCount > 99 ? '99+' : $navUnreadChatCount }}
                    </span>
                    @endif
                </a>

                <!-- Notifications Dropdown -->
                <div class="relative">
                    <button type="button" id="notif-btn" class="relative p-2 text-gray-400 hover:text-blue-600 transition-colors bg-gray-50 hover:bg-blue-50 rounded-full focus:outline-none">
                        <i class="bi bi-bell text-[20px]"></i>
                        @if ($navUnreadCount > 0)
                        <span class="absolute -top-1 -right-1 flex h-5 min-w-[20px] px-1.5 items-center justify-center rounded-full text-[10px] font-extrabold text-white {{ $hasRedUnread ? 'bg-red-500' : 'bg-blue-600' }} border-2 border-white shadow-sm animate-pulse">
                            {{ $navUnreadCount > 99 ? '99+' : $navUnreadCount }}
                        </span>
                        @endif
                    </button>
                    
                    <!-- Notification Popup -->
                    <div id="notif-dropdown" class="hidden absolute right-0 top-14 w-88 rounded-2xl shadow-xl bg-white border border-gray-100 z-50 overflow-hidden transform transition-all" style="width: 22rem;">
                        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/70">
                            <h3 class="text-sm font-bold text-gray-800">Notifikasi</h3>
                            @if ($navUnreadCount > 0)
                                <span class="text-[11px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                                    {{ $navUnreadCount }} Baru
                                </span>
                            @else
                                <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                                    Semua Terbaca
                                </span>
                            @endif
                        </div>
                        <div class="max-h-[350px] overflow-y-auto divide-y divide-gray-50">
                            @forelse ($navCustomerNotifications as $notif)
                                @php
                                    $isSuspend = in_array($notif->tipe, ['iklan_suspend', 'produk_suspend']);
                                    $isForce = $notif->tipe === 'iklan_force_complete';
                                    $isReaktif = in_array($notif->tipe, ['iklan_reaktif', 'produk_pulihkan']);
                                    $isPesanan = in_array($notif->tipe, ['pesanan_masuk', 'order_masuk', 'penyewaan_baru']);
                                    $isPembayaran = in_array($notif->tipe, ['pembayaran_masuk', 'bukti_pembayaran']);
                                    $isBatal = in_array($notif->tipe, ['pesanan_dibatalkan', 'order_dibatalkan']);
                                    $isUlasan = in_array($notif->tipe, ['ulasan_baru', 'review_produk', 'komentar_produk']);
                                    $isUnread = !$notif->is_read;

                                    if ($isSuspend) {
                                        $bgColor = $isUnread ? 'bg-red-50/80 hover:bg-red-100/70' : 'bg-white hover:bg-gray-50';
                                        $iconBg = 'bg-red-100 text-red-600';
                                        $iconClass = 'bi bi-exclamation-octagon-fill';
                                        $badgeText = $notif->tipe === 'produk_suspend' ? 'Produk Ditangguhkan' : 'Ditangguhkan';
                                        $badgeClass = 'bg-red-100 text-red-700';
                                    } elseif ($isForce) {
                                        $bgColor = $isUnread ? 'bg-orange-50/80 hover:bg-orange-100/70' : 'bg-white hover:bg-gray-50';
                                        $iconBg = 'bg-orange-100 text-orange-600';
                                        $iconClass = 'bi bi-stop-circle-fill';
                                        $badgeText = 'Berhenti Paksa';
                                        $badgeClass = 'bg-orange-100 text-orange-800';
                                    } elseif ($isReaktif) {
                                        $bgColor = $isUnread ? 'bg-emerald-50/80 hover:bg-emerald-100/70' : 'bg-white hover:bg-gray-50';
                                        $iconBg = 'bg-emerald-100 text-emerald-600';
                                        $iconClass = 'bi bi-check-circle-fill';
                                        $badgeText = $notif->tipe === 'produk_pulihkan' ? 'Produk Dipulihkan' : 'Direaktifkan';
                                        $badgeClass = 'bg-emerald-100 text-emerald-700';
                                    } elseif ($isPesanan) {
                                        $bgColor = $isUnread ? 'bg-blue-50/80 hover:bg-blue-100/70' : 'bg-white hover:bg-gray-50';
                                        $iconBg = 'bg-blue-100 text-blue-600';
                                        $iconClass = 'bi bi-bag-check-fill';
                                        $badgeText = 'Pesanan Baru';
                                        $badgeClass = 'bg-blue-100 text-blue-700';
                                    } elseif ($isPembayaran) {
                                        $bgColor = $isUnread ? 'bg-emerald-50/80 hover:bg-emerald-100/70' : 'bg-white hover:bg-gray-50';
                                        $iconBg = 'bg-emerald-100 text-emerald-600';
                                        $iconClass = 'bi bi-credit-card-2-front-fill';
                                        $badgeText = 'Pembayaran';
                                        $badgeClass = 'bg-emerald-100 text-emerald-700';
                                    } elseif ($isBatal) {
                                        $bgColor = $isUnread ? 'bg-rose-50/80 hover:bg-rose-100/70' : 'bg-white hover:bg-gray-50';
                                        $iconBg = 'bg-rose-100 text-rose-600';
                                        $iconClass = 'bi bi-x-circle-fill';
                                        $badgeText = 'Dibatalkan';
                                        $badgeClass = 'bg-rose-100 text-rose-700';
                                    } elseif ($isUlasan) {
                                        $bgColor = $isUnread ? 'bg-amber-50/80 hover:bg-amber-100/70' : 'bg-white hover:bg-gray-50';
                                        $iconBg = 'bg-amber-100 text-amber-600';
                                        $iconClass = 'bi bi-star-fill';
                                        $badgeText = 'Ulasan Baru';
                                        $badgeClass = 'bg-amber-100 text-amber-800';
                                    } else {
                                        $bgColor = $isUnread ? 'bg-indigo-50/60 hover:bg-indigo-100/60' : 'bg-white hover:bg-gray-50';
                                        $iconBg = 'bg-indigo-100 text-indigo-600';
                                        $iconClass = 'bi bi-bell-fill';
                                        $badgeText = 'Info';
                                        $badgeClass = 'bg-indigo-100 text-indigo-700';
                                    }
                                @endphp

                                <a href="{{ route('customer.notifikasi.baca', $notif->id) }}" class="flex gap-3 px-4 py-3 {{ $bgColor }} transition-colors relative group">
                                    <div class="w-9 h-9 rounded-full {{ $iconBg }} flex items-center justify-center flex-shrink-0 shadow-xs">
                                        <i class="{{ $iconClass }} text-base"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1 mb-0.5">
                                            <p class="text-xs font-bold text-gray-900 truncate">{{ $notif->judul }}</p>
                                            <span class="text-[9px] font-black px-1.5 py-0.5 rounded {{ $badgeClass }} shrink-0">
                                                {{ $badgeText }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-gray-600 line-clamp-2 leading-relaxed">{{ $notif->pesan }}</p>
                                        <div class="flex items-center justify-between mt-1 text-[10px] text-gray-400">
                                            <span>{{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Baru saja' }}</span>
                                            <span class="text-blue-600 font-semibold group-hover:underline flex items-center gap-0.5">
                                                Detail <i class="bi bi-chevron-right text-[9px]"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @if ($isUnread)
                                        <span class="w-2 h-2 rounded-full {{ $isSuspend ? 'bg-red-500 animate-ping' : ($isForce ? 'bg-orange-500 animate-ping' : ($isPesanan ? 'bg-blue-600 animate-ping' : ($isUlasan ? 'bg-amber-500 animate-ping' : 'bg-blue-500'))) }} mt-1.5 flex-shrink-0"></span>
                                    @endif
                                </a>
                            @empty
                                <div class="py-8 px-4 text-center">
                                    <div class="w-12 h-12 mx-auto rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mb-2">
                                        <i class="bi bi-bell-slash text-xl"></i>
                                    </div>
                                    <p class="text-xs font-bold text-gray-700">Belum Ada Notifikasi</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">Semua pemberitahuan dan aktivitas terbaru Anda akan muncul di sini.</p>
                                </div>
                            @endforelse
                        </div>
                        @if ($navUnreadCount > 0)
                        <div class="px-4 py-2.5 border-t border-gray-100 bg-gray-50/70 flex justify-end items-center text-xs">
                            <form action="{{ route('customer.notifikasi.mark-all-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="font-semibold text-gray-500 hover:text-blue-600 transition flex items-center gap-1.5">
                                    <i class="bi bi-check2-all text-sm"></i> Tandai Semua Dibaca
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="w-[1px] h-8 bg-gray-200"></div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button type="button" id="profile-btn" class="flex items-center gap-3 p-1.5 pr-3 rounded-full hover:bg-gray-50 transition-colors focus:outline-none border border-transparent hover:border-gray-100">
                        <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" 
                            src="@userPhoto(session('foto'))" 
                            onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>'; this.onerror=null;"
                            alt="User avatar">
                        <div class="text-left hidden lg:block">
                            <p class="text-[14px] font-bold text-gray-800 leading-none">{{ session('nama_lengkap') ?? 'Customer' }}</p>
                            <p class="text-[12px] font-medium text-gray-500 mt-1">Customer Area</p>
                        </div>
                        <i class="bi bi-chevron-down text-gray-400 text-[10px] ml-1 transition-transform duration-300" id="profile-chevron"></i>
                    </button>
                    
                    <div id="profile-dropdown" class="hidden absolute right-0 top-14 w-56 rounded-2xl shadow-xl bg-white border border-gray-100 z-50 overflow-hidden transform transition-all">
                        <div class="p-2">
                            <div class="px-3 py-3 lg:hidden mb-1 border-b border-gray-100">
                                <p class="text-[14px] font-bold text-gray-800">{{ session('nama_lengkap') ?? 'Customer' }}</p>
                                <p class="text-[12px] font-medium text-gray-500">Customer Area</p>
                            </div>
                            <a href="{{ route('customer.chat-admin.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}"
                                class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-semibold text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200 mb-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100/50 flex items-center justify-center text-blue-600">
                                        <i class="bi bi-chat-dots-fill text-base"></i>
                                    </div>
                                    <span>Chat Admin</span>
                                </div>
                                @if ($navUnreadChatCount > 0)
                                    <span class="text-[10px] font-bold bg-rose-500 text-white px-2 py-0.5 rounded-full">
                                        {{ $navUnreadChatCount > 99 ? '99+' : $navUnreadChatCount }}
                                    </span>
                                @endif
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-red-100/50 flex items-center justify-center"><i class="typcn typcn-power-outline text-lg"></i></div>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden'); document.getElementById('mobile-menu').classList.toggle('opacity-0'); document.getElementById('mobile-menu').classList.toggle('-translate-y-4')" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-blue-600 hover:bg-blue-50 focus:outline-none transition-colors">
                    <i class="bi bi-list text-3xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100 hidden opacity-0 -translate-y-4 transition-all duration-300 ease-in-out absolute w-full shadow-lg" id="mobile-menu">
        <div class="px-4 py-6 space-y-2 max-h-[calc(100vh-70px)] overflow-y-auto">
            <a href="{{ route('dashboard-cust', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-[15px] font-semibold transition-colors
               {{ $title == 'Dashboard | Customer' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                <i class="typcn typcn-chart-area-outline text-xl"></i> Dashboard
            </a>
            
            <div class="pt-4 pb-2">
                <p class="px-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Transaksi</p>
                <a href="{{ route('menu-transaksi.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-[15px] font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500"><i class="bi bi-cart-check"></i></div>
                        Penyewaan & Transaksi
                    </div>
                    @if ($navPendingOrdersCount > 0)
                        <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                            {{ $navPendingOrdersCount }} Baru
                        </span>
                    @endif
                </a>
                <a href="{{ route('kasir-offline.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[15px] font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600"><i class="bi bi-shop"></i></div>
                    Kasir / Order Offline
                </a>
                <a href="{{ route('menu-pengiriman.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[15px] font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center text-teal-600"><i class="bi bi-truck"></i></div>
                    Biaya Antar & Pengiriman
                </a>
                <a href="{{ route('menu-promo.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[15px] font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600"><i class="bi bi-percent"></i></div>
                    Diskon Produk Toko
                </a>
                <a href="{{ route('buat-iklan.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-[15px] font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500"><i class="bi bi-megaphone"></i></div>
                        Buat Promosi / Iklan
                    </div>
                    @if ($hasAdWarnings)
                        <span class="inline-flex items-center justify-center text-red-500 animate-pulse ml-auto" title="Ada iklan Anda yang ditangguhkan atau dihentikan paksa!">
                            <i class="bi bi-exclamation-circle-fill text-lg"></i>
                        </span>
                    @endif
                </a>
            </div>

            <a href="{{ route('menu-produk.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-[15px] font-semibold transition-colors mt-2
               {{ in_array($title, ['Update Produk', 'Detail Produk', 'Tambah Produk', 'Sedang Disewa | KampSewa', 'Kelola Produk | KampSewa', 'Produk Menu | KampSewa']) ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                <i class="typcn typcn-shopping-cart text-xl"></i> Produk
            </a>

            <a href="{{ route('keuangan.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-[15px] font-semibold transition-colors mt-2
               {{ in_array($title, ['Menu Pengeluaran', 'Menu Keuangan']) ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                <i class="typcn typcn-chart-bar-outline text-xl"></i> Keuangan & Laporan
            </a>

            <a href="{{ route('customer.chat-admin.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
               class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-[15px] font-semibold transition-colors mt-2
               {{ $title == 'Chat Admin | KampSewa' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-chat-dots text-lg"></i> Chat Admin
                </div>
                @if ($navUnreadChatCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-500 text-white">
                        {{ $navUnreadChatCount > 99 ? '99+' : $navUnreadChatCount }} Baru
                    </span>
                @endif
            </a>
            
            <div class="h-px bg-gray-100 my-4"></div>
            
            <div class="flex items-center px-4 py-2">
                <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-100" 
                    src="@userPhoto(session('foto'))" 
                    onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>'; this.onerror=null;"
                    alt="">
                <div class="ml-4 flex-1">
                    <div class="text-[15px] font-bold text-gray-800">{{ session('nama_lengkap') ?? 'Customer' }}</div>
                    <div class="text-[12px] font-medium text-gray-500">Customer Area</div>
                </div>
                
                <!-- Mobile Notification Button -->
                <button type="button" onclick="document.getElementById('mobile-notif-dropdown').classList.toggle('hidden')" class="relative p-2 ml-auto text-gray-400 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 rounded-full transition-colors focus:outline-none">
                    <i class="typcn typcn-messages text-xl"></i>
                    @if ($navUnreadCount > 0)
                        <span class="absolute -top-1 -right-1 flex h-4.5 min-w-[18px] px-1 items-center justify-center rounded-full text-[9px] font-extrabold text-white {{ $hasRedUnread ? 'bg-red-500' : 'bg-blue-600' }} border-2 border-white shadow-sm animate-pulse">
                            {{ $navUnreadCount > 99 ? '99+' : $navUnreadCount }}
                        </span>
                    @endif
                </button>
            </div>
            
            <!-- Mobile Notification Dropdown -->
            <div id="mobile-notif-dropdown" class="hidden px-2 mt-1 mx-4 bg-gray-50 border border-gray-100 rounded-xl overflow-hidden">
                <div class="py-2 px-3 border-b border-gray-200 flex justify-between items-center">
                    <h4 class="text-xs font-bold text-gray-700">Notifikasi</h4>
                    @if ($navUnreadCount > 0)
                        <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                            {{ $navUnreadCount }} Baru
                        </span>
                    @else
                        <span class="text-[9px] font-bold bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full">
                            Semua Terbaca
                        </span>
                    @endif
                </div>
                <div class="max-h-[250px] overflow-y-auto divide-y divide-gray-100">
                    @forelse ($navCustomerNotifications as $notif)
                        @php
                            $isSuspend = $notif->tipe === 'iklan_suspend';
                            $isForce = $notif->tipe === 'iklan_force_complete';
                            $isReaktif = $notif->tipe === 'iklan_reaktif';
                            $isPesanan = in_array($notif->tipe, ['pesanan_masuk', 'order_masuk', 'penyewaan_baru']);
                            $isPembayaran = in_array($notif->tipe, ['pembayaran_masuk', 'bukti_pembayaran']);
                            $isBatal = in_array($notif->tipe, ['pesanan_dibatalkan', 'order_dibatalkan']);
                            $isUlasan = in_array($notif->tipe, ['ulasan_baru', 'review_produk', 'komentar_produk']);
                            $isUnread = !$notif->is_read;

                            if ($isSuspend) {
                                $bgColor = $isUnread ? 'bg-red-50/80 hover:bg-red-100/70' : 'bg-white hover:bg-gray-50';
                                $iconBg = 'bg-red-100 text-red-600';
                                $iconClass = 'bi bi-exclamation-octagon-fill';
                                $badgeText = 'Ditangguhkan';
                                $badgeClass = 'bg-red-100 text-red-700';
                            } elseif ($isForce) {
                                $bgColor = $isUnread ? 'bg-orange-50/80 hover:bg-orange-100/70' : 'bg-white hover:bg-gray-50';
                                $iconBg = 'bg-orange-100 text-orange-600';
                                $iconClass = 'bi bi-stop-circle-fill';
                                $badgeText = 'Berhenti Paksa';
                                $badgeClass = 'bg-orange-100 text-orange-800';
                            } elseif ($isReaktif) {
                                $bgColor = $isUnread ? 'bg-emerald-50/80 hover:bg-emerald-100/70' : 'bg-white hover:bg-gray-50';
                                $iconBg = 'bg-emerald-100 text-emerald-600';
                                $iconClass = 'bi bi-check-circle-fill';
                                $badgeText = 'Direaktifkan';
                                $badgeClass = 'bg-emerald-100 text-emerald-700';
                            } elseif ($isPesanan) {
                                $bgColor = $isUnread ? 'bg-blue-50/80 hover:bg-blue-100/70' : 'bg-white hover:bg-gray-50';
                                $iconBg = 'bg-blue-100 text-blue-600';
                                $iconClass = 'bi bi-bag-check-fill';
                                $badgeText = 'Pesanan Baru';
                                $badgeClass = 'bg-blue-100 text-blue-700';
                            } elseif ($isPembayaran) {
                                $bgColor = $isUnread ? 'bg-emerald-50/80 hover:bg-emerald-100/70' : 'bg-white hover:bg-gray-50';
                                $iconBg = 'bg-emerald-100 text-emerald-600';
                                $iconClass = 'bi bi-credit-card-2-front-fill';
                                $badgeText = 'Pembayaran';
                                $badgeClass = 'bg-emerald-100 text-emerald-700';
                            } elseif ($isBatal) {
                                $bgColor = $isUnread ? 'bg-rose-50/80 hover:bg-rose-100/70' : 'bg-white hover:bg-gray-50';
                                $iconBg = 'bg-rose-100 text-rose-600';
                                $iconClass = 'bi bi-x-circle-fill';
                                $badgeText = 'Dibatalkan';
                                $badgeClass = 'bg-rose-100 text-rose-700';
                            } elseif ($isUlasan) {
                                $bgColor = $isUnread ? 'bg-amber-50/80 hover:bg-amber-100/70' : 'bg-white hover:bg-gray-50';
                                $iconBg = 'bg-amber-100 text-amber-600';
                                $iconClass = 'bi bi-star-fill';
                                $badgeText = 'Ulasan Baru';
                                $badgeClass = 'bg-amber-100 text-amber-800';
                            } else {
                                $bgColor = $isUnread ? 'bg-indigo-50/60 hover:bg-indigo-100/60' : 'bg-white hover:bg-gray-50';
                                $iconBg = 'bg-indigo-100 text-indigo-600';
                                $iconClass = 'bi bi-bell-fill';
                                $badgeText = 'Info';
                                $badgeClass = 'bg-indigo-100 text-indigo-700';
                            }
                        @endphp

                        <a href="{{ route('customer.notifikasi.baca', $notif->id) }}" class="flex gap-2.5 px-3 py-2.5 {{ $bgColor }} transition-colors">
                            <div class="w-8 h-8 rounded-full {{ $iconBg }} flex items-center justify-center shrink-0">
                                <i class="{{ $iconClass }} text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <p class="text-[11px] font-bold text-gray-900 truncate">{{ $notif->judul }}</p>
                                    <span class="text-[8px] font-bold px-1 py-0.2 rounded {{ $badgeClass }} shrink-0">{{ $badgeText }}</span>
                                </div>
                                <p class="text-[10px] text-gray-600 truncate mt-0.5">{{ $notif->pesan }}</p>
                                <div class="flex items-center justify-between mt-1 text-[9px] text-gray-400">
                                    <span>{{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Baru saja' }}</span>
                                    <span class="text-blue-600 font-semibold flex items-center gap-0.5">
                                        Detail <i class="bi bi-chevron-right text-[8px]"></i>
                                    </span>
                                </div>
                            </div>
                            @if ($isUnread)
                                <span class="w-2 h-2 rounded-full {{ $isSuspend ? 'bg-red-500 animate-ping' : ($isForce ? 'bg-orange-500 animate-ping' : ($isPesanan ? 'bg-blue-600 animate-ping' : 'bg-blue-500')) }} mt-1.5 flex-shrink-0"></span>
                            @endif
                        </a>
                    @empty
                        <div class="py-6 text-center text-xs text-gray-400">
                            Belum ada notifikasi
                        </div>
                    @endforelse
                </div>
                @if ($navUnreadCount > 0)
                <div class="py-2 px-3 border-t border-gray-200 bg-gray-100/50 flex justify-end items-center">
                    <form action="{{ route('customer.notifikasi.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-gray-500 hover:text-blue-600 transition flex items-center gap-1">
                            <i class="bi bi-check2-all"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                </div>
                @endif
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-[15px] font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors">
                    <i class="typcn typcn-power-outline text-xl"></i> Sign Out
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('button[onclick*="mobile-menu"]');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.onclick = function(e) {
                e.preventDefault();
                if (mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.remove('hidden');
                    setTimeout(() => {
                        mobileMenu.classList.remove('opacity-0', '-translate-y-4');
                    }, 10);
                } else {
                    mobileMenu.classList.add('opacity-0', '-translate-y-4');
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300);
                }
            };
        }

        // Notification dropdown toggle on click (desktop)
        const notifBtn = document.getElementById('notif-btn');
        const notifDropdown = document.getElementById('notif-dropdown');
        if (notifBtn && notifDropdown) {
            notifBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                // Close profile dropdown if open
                if (document.getElementById('profile-dropdown') && !document.getElementById('profile-dropdown').classList.contains('hidden')) {
                    document.getElementById('profile-dropdown').classList.add('hidden');
                    if (document.getElementById('profile-chevron')) {
                        document.getElementById('profile-chevron').classList.remove('rotate-180');
                    }
                }
                notifDropdown.classList.toggle('hidden');
            });
        }

        // Profile dropdown toggle on click (desktop)
        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');
        const profileChevron = document.getElementById('profile-chevron');
        if (profileBtn && profileDropdown) {
            profileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                // Close notif dropdown if open
                if (notifDropdown && !notifDropdown.classList.contains('hidden')) {
                    notifDropdown.classList.add('hidden');
                }
                profileDropdown.classList.toggle('hidden');
                if (profileChevron) {
                    profileChevron.classList.toggle('rotate-180');
                }
            });
        }

        // Click outside to close desktop dropdowns
        document.addEventListener('click', function(e) {
            if (notifDropdown && !notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
                notifDropdown.classList.add('hidden');
            }
            if (profileDropdown && !profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
                profileDropdown.classList.add('hidden');
                if (profileChevron) {
                    profileChevron.classList.remove('rotate-180');
                }
            }
        });
    });
</script>
