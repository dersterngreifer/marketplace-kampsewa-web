<nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm w-full transition-all duration-300">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-[70px] items-center">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md shadow-blue-500/30">
                    K
                </div>
                <a href="{{ route('dashboard-cust', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="font-bold text-2xl text-gray-800 tracking-tight hover:text-blue-600 transition-colors">KampSewa.</a>
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
                        {{ in_array($title, ['Order Selesai', 'Denda Pelanggan', 'Sewa Berlangsung', 'Terima Order Masuk', 'Order Masuk', 'Kelola Iklan', 'Iklan | Customer']) ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 group-hover:text-blue-600' }}">
                        <div class="flex items-center gap-2 transition-transform duration-200">
                            <i class="typcn typcn-shopping-bag text-[20px]"></i> Transaksi
                            <i id="transaksi-chevron" class="bi bi-chevron-down text-[12px] ml-1 transition-transform duration-300 group-hover:rotate-180"></i>
                        </div>
                    </button>
                    
                    <div id="transaksi-dropdown" class="absolute top-[70px] left-0 w-max rounded-2xl shadow-xl bg-white border border-gray-100 z-50 overflow-hidden transform transition-all duration-300 opacity-0 pointer-events-none translate-y-4 group-hover:opacity-100 group-hover:pointer-events-auto group-hover:translate-y-0">
                        <div class="p-3 space-y-1">
                            <a href="{{ route('menu-transaksi.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all duration-200 whitespace-nowrap">
                                <div class="w-8 h-8 rounded-lg bg-blue-100/50 flex items-center justify-center text-blue-600"><i class="bi bi-cart-check"></i></div>
                                Penyewaan & Transaksi
                            </a>
                            <a href="{{ route('kasir-offline.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition-all duration-200 whitespace-nowrap">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100/50 flex items-center justify-center text-emerald-600"><i class="bi bi-shop"></i></div>
                                Kasir / Order Offline
                            </a>
                            <a href="{{ route('buat-iklan.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-purple-50 hover:text-purple-600 rounded-xl transition-all duration-200 whitespace-nowrap">
                                <div class="w-8 h-8 rounded-lg bg-purple-100/50 flex items-center justify-center text-purple-600"><i class="bi bi-megaphone"></i></div>
                                Buat Promosi / Iklan
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
            <div class="hidden md:flex items-center space-x-6">
                <!-- Notifications Dropdown -->
                <div class="relative">
                    <button type="button" id="notif-btn" class="relative p-2 text-gray-400 hover:text-blue-600 transition-colors bg-gray-50 hover:bg-blue-50 rounded-full focus:outline-none">
                        <i class="typcn typcn-messages text-[22px]"></i>
                        <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-white"></span>
                        </span>
                    </button>
                    
                    <!-- Notification Popup -->
                    <div id="notif-dropdown" class="hidden absolute right-0 top-14 w-80 rounded-2xl shadow-xl bg-white border border-gray-100 z-50 overflow-hidden transform transition-all">
                        <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-sm font-bold text-gray-800">Notifikasi</h3>
                            <span class="text-xs font-semibold bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">3 Baru</span>
                        </div>
                        <div class="max-h-[300px] overflow-y-auto">
                            <!-- Dummy Item 1 -->
                            <a href="#" class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50/50 relative">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <i class="bi bi-bag-check text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">Pesanan Baru Masuk</p>
                                    <p class="text-xs text-gray-500 line-clamp-2 mt-0.5">Budi Santoso menyewa alat Anda untuk 2 hari kedepan.</p>
                                    <p class="text-[10px] text-gray-400 mt-1">2 menit yang lalu</p>
                                </div>
                                <span class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></span>
                            </a>
                            
                            <!-- Dummy Item 2 -->
                            <a href="#" class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50/50 relative">
                                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center flex-shrink-0">
                                    <i class="bi bi-exclamation-triangle text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">Peringatan Keterlambatan</p>
                                    <p class="text-xs text-gray-500 line-clamp-2 mt-0.5">Penyewaan atas nama Siti telah melewati batas pengembalian.</p>
                                    <p class="text-[10px] text-gray-400 mt-1">1 jam yang lalu</p>
                                </div>
                                <span class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></span>
                            </a>

                            <!-- Dummy Item 3 -->
                            <a href="#" class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors relative">
                                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                    <i class="bi bi-wallet2 text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">Pembayaran Berhasil</p>
                                    <p class="text-xs text-gray-500 line-clamp-2 mt-0.5">Dana sebesar Rp 150.000 telah masuk ke saldo Anda.</p>
                                    <p class="text-[10px] text-gray-400 mt-1">Kemarin</p>
                                </div>
                            </a>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-50 bg-gray-50/50 text-center">
                            <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">Lihat Semua Notifikasi</a>
                        </div>
                    </div>
                </div>

                <div class="w-[1px] h-8 bg-gray-200"></div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button type="button" id="profile-btn" class="flex items-center gap-3 p-1.5 pr-3 rounded-full hover:bg-gray-50 transition-colors focus:outline-none border border-transparent hover:border-gray-100">
                        <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" src="@userPhoto(session('foto'))" alt="User avatar">
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
                <a href="{{ route('menu-transaksi.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[15px] font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500"><i class="bi bi-cart-check"></i></div>
                    Penyewaan & Transaksi
                </a>
                <a href="{{ route('kasir-offline.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[15px] font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600"><i class="bi bi-shop"></i></div>
                    Kasir / Order Offline
                </a>
                <a href="{{ route('buat-iklan.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[15px] font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500"><i class="bi bi-megaphone"></i></div>
                    Buat Promosi / Iklan
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
            
            <div class="h-px bg-gray-100 my-4"></div>
            
            <div class="flex items-center px-4 py-2">
                <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-100" src="@userPhoto(session('foto'))" alt="">
                <div class="ml-4 flex-1">
                    <div class="text-[15px] font-bold text-gray-800">{{ session('nama_lengkap') ?? 'Customer' }}</div>
                    <div class="text-[12px] font-medium text-gray-500">Customer Area</div>
                </div>
                
                <!-- Mobile Notification Button -->
                <button type="button" onclick="document.getElementById('mobile-notif-dropdown').classList.toggle('hidden')" class="relative p-2 ml-auto text-gray-400 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 rounded-full transition-colors focus:outline-none">
                    <i class="typcn typcn-messages text-xl"></i>
                    <span class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full bg-red-500 border-2 border-gray-50"></span>
                </button>
            </div>
            
            <!-- Mobile Notification Dropdown -->
            <div id="mobile-notif-dropdown" class="hidden px-2 mt-1 mx-4 bg-gray-50 border border-gray-100 rounded-xl overflow-hidden">
                <div class="py-2 px-3 border-b border-gray-200">
                    <h4 class="text-xs font-bold text-gray-700">Notifikasi Baru (3)</h4>
                </div>
                <div class="max-h-[250px] overflow-y-auto">
                    <!-- Dummy Item 1 -->
                    <a href="#" class="flex gap-3 px-3 py-3 border-b border-gray-200 hover:bg-gray-100 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-bag-check text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">Pesanan Baru Masuk</p>
                            <p class="text-[11px] text-gray-500 line-clamp-1 mt-0.5">Budi Santoso menyewa alat Anda.</p>
                        </div>
                    </a>
                    
                    <!-- Dummy Item 2 -->
                    <a href="#" class="flex gap-3 px-3 py-3 border-b border-gray-200 hover:bg-gray-100 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-exclamation-triangle text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">Peringatan Keterlambatan</p>
                            <p class="text-[11px] text-gray-500 line-clamp-1 mt-0.5">Penyewaan Siti melewati batas.</p>
                        </div>
                    </a>

                    <!-- Dummy Item 3 -->
                    <a href="#" class="flex gap-3 px-3 py-3 hover:bg-gray-100 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-wallet2 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-700 truncate">Pembayaran Berhasil</p>
                            <p class="text-[11px] text-gray-500 line-clamp-1 mt-0.5">Dana Rp 150.000 telah masuk.</p>
                        </div>
                    </a>
                </div>
                <div class="py-2 text-center bg-gray-100/50">
                    <a href="#" class="text-xs font-bold text-blue-600">Lihat Semua</a>
                </div>
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
