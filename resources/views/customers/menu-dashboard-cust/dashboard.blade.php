@extends('layouts.customers.layouts-customer')
@section('customer-content')
    <script>
        window.chartTahunIni = {!! $chart_tahun_ini ?? '[]' !!};
        window.chartTahunLalu = {!! $chart_tahun_lalu ?? '[]' !!};
        window.labelTahunIni = "{{ $tahun_ini ?? date('Y') }}";
        window.labelTahunLalu = "{{ $tahun_lalu ?? date('Y') - 1 }}";
    </script>
    
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl sm:text-3xl text-gray-900 tracking-tight">Hi, Selamat Pagi {{ $user_name ?? 'Agung' }}! <span class="wave">👋</span></h2>
                <p class="text-gray-500 text-[15px] mt-1 font-medium">Berikut adalah ringkasan performa dan aktivitas penyewaan Anda hari ini.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('menu-produk.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-50 hover:text-blue-600 transition-colors shadow-sm">
                    <i class="typcn typcn-folder-add text-lg"></i> Tambah Produk
                </a>
            </div>
        </div>

        <!-- Perbandingan Pemasukan (Charts) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">
            <!-- Pemasukan Pertahun -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 flex flex-col gap-6">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div>
                        <h3 class="text-[17px] font-bold text-gray-800">Total Pemasukan Pertahun</h3>
                        <p class="text-[13px] text-gray-500 mt-1 font-medium">Perbandingan tahun ini dan tahun lalu</p>
                    </div>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition-colors">
                        <i class="bi bi-three-dots"></i>
                    </button>
                </div>
                
                <div class="flex flex-wrap gap-8 items-center bg-gray-50/50 p-4 rounded-2xl border border-gray-50">
                    <div class="flex-1 min-w-[140px]">
                        <p class="text-[12px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tahun Lalu ({{ $tahun_lalu ?? 2023 }})</p>
                        <div class="flex items-end gap-3">
                            <p class="text-[22px] font-black text-gray-900">Rp {{ number_format($pemasukan_tahun_lalu ?? 0, 0, ',', '.') }}</p>
                            <div class="flex items-center gap-1 mb-1">
                                <span class="flex items-center justify-center w-5 h-5 rounded-full {{ ($persentase_tahun_lalu ?? 0) >= 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                    <i class="bi {{ ($persentase_tahun_lalu ?? 0) >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }} text-[14px] font-bold"></i>
                                </span>
                                <span class="text-[12px] font-bold {{ ($persentase_tahun_lalu ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ ($persentase_tahun_lalu ?? 0) >= 0 ? '+' : '' }}{{ $persentase_tahun_lalu ?? 0 }}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-px h-12 bg-gray-200 hidden sm:block"></div>
                    <div class="flex-1 min-w-[140px]">
                        <p class="text-[12px] font-bold text-blue-600 uppercase tracking-wider mb-1">Tahun Ini ({{ $tahun_ini ?? 2024 }})</p>
                        <div class="flex items-end gap-3">
                            <p class="text-[22px] font-black text-gray-900">Rp {{ number_format($pemasukan_tahun_ini ?? 0, 0, ',', '.') }}</p>
                            <div class="flex items-center gap-1 mb-1">
                                <span class="flex items-center justify-center w-5 h-5 rounded-full {{ ($persentase_tahun_ini ?? 0) >= 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                    <i class="bi {{ ($persentase_tahun_ini ?? 0) >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }} text-[14px] font-bold"></i>
                                </span>
                                <span class="text-[12px] font-bold {{ ($persentase_tahun_ini ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ ($persentase_tahun_ini ?? 0) >= 0 ? '+' : '' }}{{ $persentase_tahun_ini ?? 0 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="w-full mt-2 relative">
                    <canvas id="customer-chart-pemasukan-dsb"></canvas>
                </div>
                
                <div class="flex items-center gap-6 pt-4 mt-2 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[rgb(86,11,208)] shadow-sm"></span>
                        <p class="text-[13px] font-semibold text-gray-600">Tahun Kemarin</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[rgb(3,118,253)] shadow-sm"></span>
                        <p class="text-[13px] font-semibold text-gray-600">Tahun Ini</p>
                    </div>
                </div>
            </div>

            <!-- Pemasukan Perbulan -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 flex flex-col gap-6">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div>
                        <h3 class="text-[17px] font-bold text-gray-800">Total Pemasukan Perbulan</h3>
                        <p class="text-[13px] text-gray-500 mt-1 font-medium">Tren pendapatan bulanan tahun ini</p>
                    </div>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition-colors">
                        <i class="bi bi-three-dots"></i>
                    </button>
                </div>
                
                <div class="flex flex-wrap gap-8 items-center bg-gray-50/50 p-4 rounded-2xl border border-gray-50">
                    <div class="flex-1 min-w-[140px]">
                        <p class="text-[12px] font-bold text-gray-500 uppercase tracking-wider mb-1">Bulan Lalu ({{ $bulan_lalu_nama ?? 'Mei' }})</p>
                        <div class="flex items-end gap-3">
                            <p class="text-[22px] font-black text-gray-900">Rp {{ number_format($pemasukan_bulan_lalu ?? 0, 0, ',', '.') }}</p>
                            <div class="flex items-center gap-1 mb-1">
                                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-gray-200 text-gray-500">
                                    <i class="bi bi-dash text-[14px] font-bold"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-px h-12 bg-gray-200 hidden sm:block"></div>
                    <div class="flex-1 min-w-[140px]">
                        <p class="text-[12px] font-bold text-purple-600 uppercase tracking-wider mb-1">Bulan Ini ({{ $bulan_ini_nama ?? 'Juni' }})</p>
                        <div class="flex items-end gap-3">
                            <p class="text-[22px] font-black text-gray-900">Rp {{ number_format($pemasukan_bulan_ini ?? 0, 0, ',', '.') }}</p>
                            <div class="flex items-center gap-1 mb-1">
                                <span class="flex items-center justify-center w-5 h-5 rounded-full {{ ($persentase_bulan_ini ?? 0) >= 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                    <i class="bi {{ ($persentase_bulan_ini ?? 0) >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }} text-[14px] font-bold"></i>
                                </span>
                                <span class="text-[12px] font-bold {{ ($persentase_bulan_ini ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ ($persentase_bulan_ini ?? 0) >= 0 ? '+' : '' }}{{ $persentase_bulan_ini ?? 0 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="w-full mt-2 relative">
                    <canvas id="customer-chart-pemasukan-perbulan-dsb"></canvas>
                </div>
                
                <div class="flex items-center gap-6 pt-4 mt-2 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#FFCE56] shadow-sm"></span>
                        <p class="text-[13px] font-semibold text-gray-600">Tahun Kemarin</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#4BC0C0] shadow-sm"></span>
                        <p class="text-[13px] font-semibold text-gray-600">Tahun Ini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peralatan Terlaris -->
        <div class="w-full flex flex-col gap-5 mt-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Peralatan Terlaris 🔥</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Produk Anda yang paling banyak disewa.</p>
                </div>
                <a href="{{ route('menu-produk.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="text-sm text-blue-600 font-bold hover:text-blue-800 transition-colors flex items-center gap-1">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
                @forelse ($peralatan_terlaris as $item)
                    <div class="bg-white rounded-[20px] shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group flex flex-col">
                        <div class="relative w-full pt-[75%] overflow-hidden bg-gray-50">
                            <img src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($item) }}" 
                                onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'" 
                                alt="{{ $item->nama }}" 
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-lg shadow-sm border border-white/50">
                                <span class="text-[11px] font-black text-blue-600 flex items-center gap-1">
                                    <i class="bi bi-star-fill text-yellow-400"></i> Top
                                </span>
                            </div>
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h5 class="mb-1 text-[14px] font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $item->nama }}</h5>
                            
                            <div class="mt-auto pt-3 flex flex-col gap-2">
                                <span class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md self-start">
                                    {{ $item->total_sewa }}x disewa
                                </span>
                                <span class="text-[14px] font-black text-emerald-600">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}<span class="text-[10px] text-gray-400 font-medium">/hari</span></span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 md:col-span-3 lg:col-span-5 p-12 bg-white rounded-[24px] border border-dashed border-gray-300 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <i class="bi bi-box-seam text-2xl text-gray-400"></i>
                        </div>
                        <h4 class="text-base font-bold text-gray-700 mb-1">Belum ada produk terlaris</h4>
                        <p class="text-sm text-gray-500 max-w-md">Data peralatan terlaris akan muncul di sini setelah ada transaksi penyewaan yang berhasil.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Info List (Penyewa, Riwayat, Denda) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
            
            <!-- Penyewa Berlangsung -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 flex flex-col">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-[16px] font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Penyewa Aktif
                        </h3>
                    </div>
                    <span class="bg-blue-50 text-blue-600 text-xs font-bold px-2 py-1 rounded-lg">{{ count($penyewa_berlangsung) }}</span>
                </div>
                
                <div class="flex flex-col gap-3 flex-grow">
                    @forelse ($penyewa_berlangsung as $pb)
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-gray-50 bg-gray-50/50 hover:bg-blue-50/50 hover:border-blue-100 transition-colors group">
                            <div class="flex items-center gap-3">
                                <img class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm" 
                                    src="{{ asset('assets/image/customers/profile/' . ($pb->foto ?: 'default.png')) }}" 
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($pb->name) }}&background=random'" alt="{{ $pb->name }}">
                                <div>
                                    <p class="text-[13px] font-bold text-gray-800 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $pb->name }}</p>
                                    <p class="text-[11px] text-gray-500 font-medium flex items-center gap-1 mt-0.5"><i class="bi bi-calendar-check text-blue-400"></i> Selesai: {{ \Carbon\Carbon::parse($pb->tanggal_selesai)->format('d M') }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-md">Berjalan</span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-32 text-center">
                            <i class="bi bi-person-x text-2xl text-gray-300 mb-2"></i>
                            <p class="text-[13px] text-gray-400 font-medium">Tidak ada penyewa aktif</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                    <a href="{{ route('menu-transaksi.sewa-berlangsung', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="text-[13px] font-bold text-blue-600 hover:text-blue-800 transition-colors">Lihat Semua →</a>
                </div>
            </div>

            <!-- Riwayat Penyewa -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 flex flex-col">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-[16px] font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-clock-history text-gray-400"></i> Riwayat Transaksi
                        </h3>
                    </div>
                    <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2 py-1 rounded-lg">{{ count($riwayat_penyewa) }}</span>
                </div>
                
                <div class="flex flex-col gap-3 flex-grow">
                    @forelse ($riwayat_penyewa as $rp)
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-gray-50 bg-gray-50/50 hover:bg-emerald-50/50 hover:border-emerald-100 transition-colors group">
                            <div class="flex items-center gap-3">
                                <img class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm grayscale opacity-80" 
                                    src="{{ asset('assets/image/customers/profile/' . ($rp->foto ?: 'default.png')) }}" 
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($rp->name) }}&background=random'" alt="{{ $rp->name }}">
                                <div>
                                    <p class="text-[13px] font-bold text-gray-600 line-clamp-1">{{ $rp->name }}</p>
                                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Selesai: {{ \Carbon\Carbon::parse($rp->tanggal_selesai)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-md"><i class="bi bi-check2"></i></span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-32 text-center">
                            <i class="bi bi-receipt text-2xl text-gray-300 mb-2"></i>
                            <p class="text-[13px] text-gray-400 font-medium">Belum ada riwayat transaksi</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                    <a href="{{ route('menu-transaksi.order-selesai', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="text-[13px] font-bold text-gray-500 hover:text-gray-800 transition-colors">Lihat Semua →</a>
                </div>
            </div>

            <!-- Denda Penyewa -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 flex flex-col">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-[16px] font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-exclamation-octagon text-red-500"></i> Denda & Penalti
                        </h3>
                    </div>
                    <span class="bg-red-50 text-red-600 text-xs font-bold px-2 py-1 rounded-lg">{{ count($denda_penyewa) }}</span>
                </div>
                
                <div class="flex flex-col gap-3 flex-grow">
                    @forelse ($denda_penyewa as $dp)
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-red-50 bg-red-50/30 hover:bg-red-50 hover:border-red-100 transition-colors group">
                            <div class="flex items-center gap-3">
                                <img class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm" 
                                    src="{{ asset('assets/image/customers/profile/' . ($dp->foto ?: 'default.png')) }}" 
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($dp->name) }}&background=random'" alt="{{ $dp->name }}">
                                <div>
                                    <p class="text-[13px] font-bold text-gray-800 line-clamp-1 group-hover:text-red-700 transition-colors">{{ $dp->name }}</p>
                                    <p class="text-[11px] font-black text-red-600 mt-0.5">Rp {{ number_format($dp->denda, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-1 bg-white text-red-600 border border-red-200 rounded-md truncate max-w-[60px] text-center" title="{{ $dp->status_denda }}">{{ $dp->status_denda }}</span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-32 text-center">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center mb-2">
                                <i class="bi bi-shield-check text-lg text-emerald-500"></i>
                            </div>
                            <p class="text-[13px] text-gray-400 font-medium">Bebas dari denda/pelanggaran</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                    <a href="{{ route('menu-transaksi.denda-transaksi', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="text-[13px] font-bold text-red-500 hover:text-red-700 transition-colors">Lihat Semua →</a>
                </div>
            </div>

        </div>
    </div>
@endsection
