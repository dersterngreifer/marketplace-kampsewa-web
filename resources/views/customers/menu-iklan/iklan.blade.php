@extends('layouts.customers.layouts-customer')
@section('customer-content')
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        <!-- Header Page -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Promosi & Iklan</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola dan tingkatkan visibilitas produk Anda di beranda.</p>
            </div>
            <a href="{{ route('kelola-iklan.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-blue-600 transition-colors shadow-sm">
                <i class="typcn typcn-cog-outline text-lg"></i> Kelola Iklan
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Kiri: Banner Utama & Info -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                <!-- Banner Hero -->
                <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-indigo-50/80 to-purple-50/50 rounded-3xl p-8 sm:p-10 shadow-sm border border-indigo-100/50">
                    <!-- Ornamen -->
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-200 opacity-20 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 right-10 -mb-10 w-32 h-32 bg-purple-200 opacity-30 rounded-full blur-xl"></div>
                    <div class="absolute top-10 left-10 w-20 h-20 bg-indigo-200 opacity-30 rounded-full blur-lg"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="flex-1">
                            <span class="inline-block py-1 px-3 rounded-full bg-indigo-100/80 backdrop-blur-sm text-[11px] font-extrabold tracking-wider uppercase mb-4 text-indigo-600 border border-indigo-200/50 shadow-sm">Premium Ads</span>
                            <h2 class="text-3xl sm:text-4xl font-black leading-tight mb-4 text-gray-900">Iklankan Peralatan<br>Kamping Anda!</h2>
                            <p class="text-gray-600 text-sm sm:text-base mb-8 max-w-md leading-relaxed font-medium">
                                Jangkau lebih banyak penyewa di area sekitar Anda. Promosikan peralatan kamping dengan biaya terjangkau dan tingkatkan keuntungan Anda secara signifikan hari ini.
                            </p>
                            <a href="{{ route('pilih-durasi-iklan.index', ['id_user' => Crypt::encryptString(session('id_user'))]) }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3.5 text-[15px] font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 hover:scale-105 transition-all duration-300 shadow-lg shadow-indigo-500/25">
                                <i class="bi bi-rocket-takeoff text-lg"></i> Mulai Buat Iklan
                            </a>
                        </div>
                        <div class="hidden md:block w-2/5 flex-shrink-0 relative">
                            <img class="w-full h-auto object-contain drop-shadow-xl hover:-translate-y-2 transition-transform duration-500 relative z-10"
                                src="{{ asset('images/illustration/Get-A-Job-Promotion--Streamline-Manila.png') }}"
                                alt="Promotion Illustration">
                        </div>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center gap-6 sm:gap-8">
                    <div class="w-24 h-24 sm:w-32 sm:h-32 flex-shrink-0 rounded-2xl bg-indigo-50/80 flex items-center justify-center relative overflow-hidden border border-indigo-100/50">
                         <img class="w-[85%] h-[85%] object-contain" src="{{ asset('images/illustration/Designer-Working--Streamline-Manila.png') }}" alt="Designer">
                    </div>
                    <div class="text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start gap-2 mb-3">
                            <div class="flex -space-x-2">
                                <img class="w-8 h-8 rounded-full border-2 border-white object-cover" src="https://ui-avatars.com/api/?name=User+1&background=random" alt="">
                                <img class="w-8 h-8 rounded-full border-2 border-white object-cover" src="https://ui-avatars.com/api/?name=User+2&background=random" alt="">
                                <img class="w-8 h-8 rounded-full border-2 border-white object-cover" src="https://ui-avatars.com/api/?name=User+3&background=random" alt="">
                            </div>
                            <span class="text-xs font-bold text-gray-500 ml-2 bg-gray-100 px-2 py-1 rounded-lg">{{ $total_data_iklan }}+ Pengguna</span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 leading-snug">Lebih Dari {{ $total_data_iklan }}+ User Telah Menggunakan Layanan Iklan.</h3>
                        <p class="text-gray-500 text-[14px] mt-2 font-medium">Bergabunglah dengan penyewa lainnya dan tingkatkan impresi pencarian produk Anda di halaman utama.</p>
                    </div>
                </div>
            </div>

            <!-- Kanan: List Iklan -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                
                <!-- Berlangsung -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-auto">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 text-[15px] flex items-center gap-2">
                            <i class="bi bi-broadcast text-blue-500"></i> Sedang Berlangsung
                        </h3>
                        <span class="bg-blue-100 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">{{ $iklan_berlangsung->count() }}</span>
                    </div>
                    <div class="p-4 flex flex-col gap-3">
                        @forelse ($iklan_berlangsung as $item)
                            <div class="flex items-center gap-3 p-3 rounded-2xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/50 transition-colors group bg-white">
                                <img class="w-14 h-14 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform"
                                    src="{{ asset('assets/image/customers/advert/' . $item->poster) }}" alt="{{ $item->judul }}" onerror="this.src='https://via.placeholder.com/150'">
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-bold text-gray-800 truncate">{{ $item->judul }}</p>
                                    <p class="text-[11px] font-medium text-gray-500 mt-0.5 flex items-center gap-1">
                                        <i class="bi bi-clock-history text-blue-500"></i> {{ $item->durasi_hari }} Hari
                                    </p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="block text-[12px] font-bold text-emerald-600">Rp {{ number_format($item->harga_iklan, 0, ',', '.') }}</span>
                                    <span class="inline-block mt-1 text-[10px] font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-md">Aktif</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-sm text-gray-400 font-medium flex flex-col items-center">
                                <i class="bi bi-inbox text-3xl mb-2 text-gray-200"></i>
                                Tidak ada iklan aktif
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Menunggu Giliran / Pending -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-auto">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 text-[15px] flex items-center gap-2">
                            <i class="bi bi-hourglass-split text-yellow-500"></i> Menunggu Giliran
                        </h3>
                        <span class="bg-yellow-100 text-yellow-600 text-xs font-bold px-2.5 py-1 rounded-lg">{{ $iklan_pending->count() }}</span>
                    </div>
                    <div class="p-4 flex flex-col gap-3">
                        @forelse ($iklan_pending as $item)
                            <div class="flex items-center gap-3 p-3 rounded-2xl border border-gray-100 hover:border-yellow-200 hover:bg-yellow-50/50 transition-colors group bg-white">
                                <img class="w-14 h-14 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform grayscale opacity-80"
                                    src="{{ asset('assets/image/customers/advert/' . $item->poster) }}" alt="{{ $item->judul }}" onerror="this.src='https://via.placeholder.com/150'">
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-bold text-gray-800 truncate">{{ $item->judul }}</p>
                                    <p class="text-[11px] font-medium text-gray-500 mt-0.5 flex items-center gap-1">
                                        <i class="bi bi-clock-history text-yellow-500"></i> {{ $item->durasi_hari }} Hari
                                    </p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="block text-[12px] font-bold text-emerald-600">Rp {{ number_format($item->harga_iklan, 0, ',', '.') }}</span>
                                    <span class="inline-block mt-1 text-[10px] font-bold text-yellow-600 bg-yellow-100 px-2 py-0.5 rounded-md">Pending</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-sm text-gray-400 font-medium flex flex-col items-center">
                                <i class="bi bi-inbox text-3xl mb-2 text-gray-200"></i>
                                Tidak ada iklan pending
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Riwayat / Selesai -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-auto">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 text-[15px] flex items-center gap-2">
                            <i class="bi bi-clock-history text-gray-400"></i> Riwayat Iklan
                        </h3>
                        <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-lg">{{ $iklan_selesai->count() }}</span>
                    </div>
                    <div class="p-4 flex flex-col gap-3">
                        @forelse ($iklan_selesai as $item)
                            <div class="flex items-center gap-3 p-3 rounded-2xl border border-gray-100 hover:bg-gray-50 transition-colors bg-white">
                                <img class="w-14 h-14 rounded-xl object-cover opacity-40 grayscale"
                                    src="{{ asset('assets/image/customers/advert/' . $item->poster) }}" alt="{{ $item->judul }}" onerror="this.src='https://via.placeholder.com/150'">
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-bold text-gray-500 truncate line-through">{{ $item->judul }}</p>
                                    <p class="text-[11px] font-medium text-gray-400 mt-0.5 flex items-center gap-1">
                                        <i class="bi bi-check-circle-fill text-gray-300"></i> Selesai ({{ $item->durasi_hari }} Hari)
                                    </p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="block text-[12px] font-bold text-gray-400">Rp {{ number_format($item->harga_iklan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-sm text-gray-400 font-medium flex flex-col items-center">
                                <i class="bi bi-archive text-3xl mb-2 text-gray-200"></i>
                                Belum ada riwayat
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
