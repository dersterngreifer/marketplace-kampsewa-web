@extends('layouts.customers.layouts-customer')
@section('customer-content')
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        <!-- Header Page -->
        @php
            $hasSuspended = isset($iklan_suspend) && $iklan_suspend->count() > 0;
        @endphp
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Promosi & Iklan</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola dan tingkatkan visibilitas produk Anda di beranda.</p>
            </div>
            <a href="{{ route('kelola-iklan.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl transition-all shadow-sm {{ $hasSuspended ? 'border-2 border-red-500 bg-red-50 text-red-700 hover:bg-red-100 ring-4 ring-red-100' : 'text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:text-blue-600' }}">
                <i class="typcn typcn-cog-outline text-lg {{ $hasSuspended ? 'text-red-600' : '' }}"></i>
                <span>Kelola Iklan</span>
                @if ($hasSuspended)
                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-black rounded-full bg-red-600 text-white animate-pulse">
                    {{ $iklan_suspend->count() }} Ditangguhkan
                </span>
                @endif
            </a>
        </div>

        @if ($hasSuspended)
        <!-- Kotak Box Warning Merah Pemberitahuan Iklan Suspend -->
        <div class="mb-8 overflow-hidden rounded-3xl border-2 border-red-300 bg-gradient-to-r from-red-50 via-rose-50/70 to-white p-5 sm:p-6 shadow-md">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-600 text-white shadow-lg shadow-red-500/30">
                        <i class="bi bi-exclamation-octagon-fill text-2xl"></i>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="inline-flex items-center gap-1 rounded-full bg-red-100 border border-red-200 px-2.5 py-0.5 text-[11px] font-black uppercase tracking-wider text-red-800">
                                <i class="bi bi-shield-exclamation text-xs"></i> Peringatan Penangguhan Iklan
                            </span>
                            <span class="rounded-full bg-red-600 text-white px-2.5 py-0.5 text-[11px] font-extrabold shadow-sm">
                                {{ $iklan_suspend->count() }} Iklan Ditangguhkan
                            </span>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-red-950">
                            Ada Iklan Anda yang Ditangguhkan (Suspend) oleh Admin!
                        </h3>
                        <p class="text-xs sm:text-sm text-red-700 mt-1 max-w-3xl leading-relaxed">
                            Iklan sementara ditarik dari tayangan aplikasi mobile karena memerlukan perbaikan (seperti poster 16:9, judul, atau deskripsi). Silakan klik tombol <b>Kelola Iklan</b> bergaris merah di atas atau tombol di bawah untuk melihat instruksi revisi dan memperbaikinya.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0 w-full md:w-auto">
                    <a href="{{ route('kelola-iklan.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}"
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-md shadow-red-600/25 active:scale-95">
                        <i class="typcn typcn-cog-outline text-lg"></i> Buka Kelola Iklan & Perbaiki
                    </a>
                </div>
            </div>
        </div>
        @endif

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

                <!-- Ditangguhkan / Suspend Card -->
                @if ($hasSuspended)
                <div class="bg-gradient-to-b from-red-50/90 to-white rounded-3xl border-2 border-red-300 shadow-md overflow-hidden flex flex-col h-auto">
                    <div class="px-5 py-4 border-b border-red-200 bg-red-100/60 flex items-center justify-between">
                        <h3 class="font-bold text-red-950 text-[15px] flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                            </span>
                            <i class="bi bi-exclamation-octagon text-red-600"></i> Ditangguhkan (Suspend)
                        </h3>
                        <span class="bg-red-600 text-white text-xs font-black px-2.5 py-1 rounded-lg shadow-sm">
                            {{ $iklan_suspend->count() }} Perlu Revisi
                        </span>
                    </div>
                    <div class="p-4 flex flex-col gap-3">
                        <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-800 leading-relaxed font-medium">
                            <i class="bi bi-info-circle-fill text-red-600 mr-1"></i> Perbaiki materi iklan agar admin dapat memulihkan tayangannya di mobile.
                        </div>

                        @foreach ($iklan_suspend as $item)
                            <div class="p-3.5 rounded-2xl border-2 border-red-200 bg-white shadow-sm flex flex-col gap-3">
                                <div class="flex items-start gap-3">
                                    <img class="w-16 h-16 rounded-xl object-cover shadow-sm border border-red-100 flex-shrink-0"
                                        src="{{ asset('assets/image/customers/advert/' . $item->poster) }}" alt="{{ $item->judul }}" onerror="this.src='https://via.placeholder.com/150'">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[13px] font-bold text-gray-900 truncate">{{ $item->judul }}</p>
                                        @if(!empty($item->suspend_alasan))
                                            <p class="text-[11px] font-semibold text-red-600 truncate mt-0.5">Alasan: {{ $item->suspend_alasan }}</p>
                                        @endif
                                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                            <span class="text-[10px] font-bold text-red-700 bg-red-50 px-2 py-0.5 rounded-md border border-red-200">
                                                {{ ($item->suspend_status_revisi ?? '') === 'sudah_diperbaiki' ? 'Menunggu Review Admin' : 'Wajib Perbaikan' }}
                                            </span>
                                            <span class="text-[11px] font-semibold text-gray-500">
                                                <i class="bi bi-clock-history text-red-500"></i> {{ $item->durasi_hari }} Hari
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-1 border-t border-gray-100 flex items-center justify-end">
                                    <a href="{{ route('kelola-iklan.update-iklan-view', ['id_iklan' => Crypt::encrypt($item->id_iklan)]) }}"
                                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-sm text-center">
                                        <i class="bi bi-pencil-square"></i> Perbaiki Iklan Ini
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Menunggu Pembayaran (Tagihan Tertunda) -->
                @if (isset($iklan_belum_bayar) && $iklan_belum_bayar->count() > 0)
                <div class="bg-gradient-to-b from-amber-50/90 to-white rounded-3xl border-2 border-amber-300 shadow-md overflow-hidden flex flex-col h-auto">
                    <div class="px-5 py-4 border-b border-amber-200 bg-amber-100/60 flex items-center justify-between">
                        <h3 class="font-bold text-amber-950 text-[15px] flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                            </span>
                            <i class="bi bi-wallet2 text-amber-600"></i> Menunggu Pembayaran
                        </h3>
                        <span class="bg-amber-500 text-white text-xs font-black px-2.5 py-1 rounded-lg shadow-sm">
                            {{ $iklan_belum_bayar->count() }} Tagihan
                        </span>
                    </div>
                    <div class="p-4 flex flex-col gap-3">
                        <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800 leading-relaxed font-medium">
                            <i class="bi bi-exclamation-circle-fill text-amber-600 mr-1"></i> Selesaikan pembayaran agar banner iklan Anda dapat segera dijadwalkan & aktif.
                        </div>

                        @foreach ($iklan_belum_bayar as $item)
                            <div class="p-3.5 rounded-2xl border-2 border-amber-200 bg-white shadow-sm flex flex-col gap-3">
                                <div class="flex items-start gap-3">
                                    <img class="w-16 h-16 rounded-xl object-cover shadow-sm border border-gray-100 flex-shrink-0"
                                        src="{{ asset('assets/image/customers/advert/' . $item->poster) }}" alt="{{ $item->judul }}" onerror="this.src='https://via.placeholder.com/150'">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[13px] font-bold text-gray-900 truncate">{{ $item->judul }}</p>
                                        @if(!empty($item->sub_judul))
                                            <p class="text-[11px] text-gray-500 truncate mt-0.5">{{ $item->sub_judul }}</p>
                                        @endif
                                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                            <span class="text-[12px] font-black text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">
                                                Rp {{ number_format($item->harga_iklan ?? 0, 0, ',', '.') }}
                                            </span>
                                            <span class="text-[11px] font-semibold text-gray-500">
                                                <i class="bi bi-clock-history text-amber-500"></i> {{ $item->durasi_hari }} Hari
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="grid grid-cols-2 gap-2 pt-1 border-t border-gray-100">
                                    <a href="{{ route('lanjut-pembayaran-iklan', ['id_iklan' => Crypt::encrypt($item->id_iklan)]) }}"
                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-sm shadow-blue-500/20 text-center">
                                        <i class="bi bi-credit-card-2-front"></i> Bayar Sekarang
                                    </a>

                                    <div class="flex items-center gap-1.5">
                                        @if(!empty($item->midtrans_order_id))
                                        <a href="{{ route('cek-status-pembayaran-iklan', ['id_iklan' => Crypt::encrypt($item->id_iklan)]) }}"
                                            title="Cek Status Pembayaran di Midtrans"
                                            class="inline-flex items-center justify-center gap-1 px-2.5 py-2 text-[11px] font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition flex-1 text-center">
                                            <i class="bi bi-arrow-repeat"></i> Cek Status
                                        </a>
                                        @endif

                                        <button type="button"
                                            onclick="confirmBatalIklan('{{ $item->id_iklan }}', '{{ addslashes($item->judul) }}')"
                                            title="Batalkan Iklan"
                                            class="inline-flex items-center justify-center p-2 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition border border-red-100">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <form id="form-batal-iklan-{{ $item->id_iklan }}"
                                    action="{{ route('batal-iklan', ['id_iklan' => $item->id_iklan]) }}"
                                    method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

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

    <script>
        function confirmBatalIklan(idIklan, judul) {
            Swal.fire({
                title: 'Batalkan Iklan Ini?',
                text: 'Materi iklan "' + judul + '" yang belum dibayar akan dihapus secara permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan Iklan',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-batal-iklan-' + idIklan).submit();
                }
            });
        }
    </script>
@endsection
