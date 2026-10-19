@extends('layouts.customers.layouts-customer')
@section('customer-content')
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold mb-2">
                    <i class="bi bi-shop"></i> Point of Sales (POS) Toko
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Kasir & Order Offline</h1>
                <p class="text-gray-500 font-medium mt-1 text-sm sm:text-base">Catat penyewaan langsung dari pelanggan walk-in di toko fisik Anda. Stok produk dan laporan pemasukan toko akan tercatat otomatis.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('menu-transaksi.sewa-berlangsung', ['id_user' => Crypt::encrypt($id_user)]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-blue-600 font-bold text-sm shadow-sm transition-all">
                    <i class="bi bi-clock-history text-blue-600"></i> Lihat Sewa Berlangsung
                </a>
            </div>
        </div>

        <!-- Ringkasan Statistik Kasir Offline -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Order Offline</p>
                    <p class="text-2xl font-black text-gray-900 mt-0.5">{{ number_format($statsOffline['total_transaksi'] ?? 0, 0, ',', '.') }} <span class="text-xs font-semibold text-gray-400">Transaksi</span></p>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="bi bi-play-circle-fill"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sewa Offline Aktif</p>
                    <p class="text-2xl font-black text-emerald-600 mt-0.5">{{ number_format($statsOffline['aktif_sekarang'] ?? 0, 0, ',', '.') }} <span class="text-xs font-semibold text-gray-400">Sedang Disewa</span></p>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pemasukan Kasir Offline</p>
                    <p class="text-2xl font-black text-gray-900 mt-0.5">Rp {{ number_format($statsOffline['total_pendapatan'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Info Banner -->
        <div class="bg-indigo-50/80 border border-indigo-100 rounded-2xl p-4 flex gap-3 shadow-sm">
            <div class="text-indigo-600 mt-0.5"><i class="bi bi-info-circle-fill text-lg"></i></div>
            <div>
                <h4 class="text-sm font-bold text-indigo-950">Integrasi Kasir Offline & Aplikasi Mobile</h4>
                <p class="text-[13px] font-medium text-indigo-700 mt-0.5">Transaksi melalui Kasir Offline langsung berstatus <strong class="text-emerald-600">Aktif</strong> & <strong class="text-emerald-600">Lunas</strong>. Masukkan nomor WhatsApp pelanggan dengan benar agar riwayat penyewaan otomatis terhubung ke akun aplikasi pelanggan.</p>
            </div>
        </div>

        <form action="{{ route('kasir-offline.proses', ['id_user' => Crypt::encrypt($id_user)]) }}" method="POST" enctype="multipart/form-data" id="form-kasir">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Main Content (Quick Catalog & Selected Cart Items) -->
                <div class="lg:col-span-8 flex flex-col gap-6">
                    
                    <!-- Katalog Cepat Produk Toko -->
                    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <i class="bi bi-grid-fill text-emerald-600"></i> Katalog Cepat Produk Toko
                                </h2>
                                <p class="text-xs text-gray-500 mt-0.5">Klik varian produk di bawah untuk langsung menambahkan ke keranjang kasir.</p>
                            </div>
                            <div class="relative w-full sm:w-64">
                                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="text" id="catalog-search" placeholder="Cari nama peralatan..."
                                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                            </div>
                        </div>

                        @if($produk->isEmpty())
                            <div class="text-center py-8 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                <i class="bi bi-box2 text-2xl text-gray-300"></i>
                                <p class="text-sm font-bold text-gray-700 mt-2">Belum Ada Produk di Toko Anda</p>
                                <p class="text-xs text-gray-500 mt-1">Tambahkan produk terlebih dahulu di menu Produk untuk mulai menggunakan Kasir Offline.</p>
                            </div>
                        @else
                            <div id="catalog-grid" class="grid grid-cols-1 md:grid-cols-2 gap-3.5 max-h-[380px] overflow-y-auto pr-1">
                                @foreach($produk as $p)
                                    <div class="catalog-card border border-gray-100 hover:border-emerald-200 bg-gray-50/50 hover:bg-white rounded-2xl p-3.5 transition-all flex flex-col gap-3 shadow-sm"
                                         data-name="{{ strtolower($p->nama . ' ' . $p->kategori) }}">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $p->thumbnail_url }}" alt="{{ $p->nama }}"
                                                 class="w-14 h-14 rounded-xl object-cover border border-gray-200 bg-white shrink-0"
                                                 onerror="this.onerror=null;this.src='{{ asset('assets/image/customers/produk/default.png') }}';">
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 truncate">{{ $p->kategori ?: 'Outdoor' }}</span>
                                                    <span class="text-[11px] font-bold {{ $p->total_stok > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                                        Stok: {{ $p->total_stok }}
                                                    </span>
                                                </div>
                                                <h4 class="font-bold text-gray-900 text-sm truncate mt-1" title="{{ $p->nama }}">{{ $p->nama }}</h4>
                                                <p class="text-xs font-extrabold text-orange-600 mt-0.5">
                                                    Mulai Rp {{ number_format($p->harga_mulai, 0, ',', '.') }}<span class="text-[10px] font-medium text-gray-400">/hari</span>
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Daftar Tombol Varian Cepat -->
                                        <div class="flex flex-wrap gap-1.5 pt-2 border-t border-gray-100">
                                            @php $hasVariantButton = false; @endphp
                                            @foreach($p->variants as $v)
                                                @foreach($v->detailVariants as $dv)
                                                    @php $hasVariantButton = true; @endphp
                                                    <button type="button"
                                                        class="btn-quick-add text-left px-2.5 py-1.5 rounded-lg text-[11px] font-semibold border transition-all flex items-center gap-1.5 {{ $dv->stok > 0 ? 'bg-white border-gray-200 text-gray-700 hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700 shadow-2xs' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed opacity-60' }}"
                                                        {{ $dv->stok <= 0 ? 'disabled' : '' }}
                                                        data-produk-id="{{ $p->id }}"
                                                        data-variant-id="{{ $dv->id }}"
                                                        data-produk-nama="{{ $p->nama }}"
                                                        data-variant-label="{{ $v->warna }} - {{ $dv->ukuran }}"
                                                        data-stok="{{ $dv->stok }}"
                                                        data-harga="{{ $dv->harga_sewa }}">
                                                        <i class="bi bi-plus-circle-fill text-emerald-500"></i>
                                                        <span>{{ $v->warna }} ({{ $dv->ukuran }})</span>
                                                        <span class="text-[10px] font-bold text-gray-400">• Stok {{ $dv->stok }}</span>
                                                    </button>
                                                @endforeach
                                            @endforeach
                                            @if(!$hasVariantButton)
                                                <span class="text-[11px] text-gray-400 italic">Belum ada varian terdaftar</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Keranjang Daftar Pesanan -->
                    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-gray-100 pb-5">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <i class="bi bi-cart-check-fill text-blue-600"></i> Keranjang Pesanan Kasir
                                    <span id="cart-count-badge" class="px-2.5 py-0.5 text-xs font-extrabold rounded-full bg-blue-100 text-blue-700">0 Item</span>
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">Daftar peralatan yang akan disewa oleh pelanggan.</p>
                            </div>
                            <button type="button" id="add-item-btn" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white font-bold py-2.5 px-5 rounded-xl transition-all duration-300 shadow-sm hover:shadow-md text-sm flex items-center gap-2 group">
                                <i class="bi bi-plus-circle-fill group-hover:rotate-90 transition-transform duration-300"></i> Tambah Baris Manual
                            </button>
                        </div>

                        <div id="items-container" class="flex flex-col gap-4">
                            <!-- Dynamic Item Rows Will Be Appended Here -->
                            <div class="text-center py-12 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200" id="empty-state">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-gray-100">
                                    <i class="bi bi-cart-plus text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="text-gray-900 font-bold text-base mb-1">Keranjang Kasir Masih Kosong</h3>
                                <p class="text-gray-500 text-xs max-w-md mx-auto">Klik salah satu varian pada <strong>Katalog Cepat</strong> di atas atau tekan tombol <strong>"Tambah Baris Manual"</strong> untuk memilih barang.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar (Customer & Dates & Checkout) -->
                <div class="lg:col-span-4 flex flex-col gap-6 sticky top-24 z-10">
                    
                    <!-- Data Pelanggan & Jaminan Card -->
                    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-person-badge text-indigo-600"></i> Data Penyewa & Jaminan
                            </h3>
                        </div>
                        <div class="p-6 flex flex-col gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-bold text-gray-700">Nama Lengkap Penyewa <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="bi bi-person text-gray-400"></i>
                                    </div>
                                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required placeholder="Contoh: Budi Santoso"
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[13px] font-bold text-gray-700">Nomor WhatsApp / HP <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="bi bi-whatsapp text-emerald-500"></i>
                                    </div>
                                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" required placeholder="Contoh: 081234567890"
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                </div>
                                <p class="text-[11px] text-gray-500 font-medium"><i class="bi bi-link-45deg text-indigo-500"></i> Otomatis terhubung jika nomor terdaftar di aplikasi</p>
                            </div>

                            <div class="space-y-1.5 pt-1 border-t border-gray-100">
                                <label class="text-[13px] font-bold text-gray-700">Jaminan Identitas (KTP/SIM/KTM) <span class="text-red-500">*</span></label>
                                <input type="text" name="jaminan_sewa" id="jaminan_sewa" value="{{ old('jaminan_sewa', 'KTP Asli (Ditahan di Toko)') }}" required
                                    placeholder="Contoh: KTP Asli - 3507xxxxxxxx"
                                    class="w-full px-3.5 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <div class="flex flex-wrap gap-1.5 mt-1.5">
                                    <button type="button" class="jaminan-chip text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 text-gray-600 transition-colors" data-val="KTP Asli (Ditahan di Toko)">KTP Asli</button>
                                    <button type="button" class="jaminan-chip text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 text-gray-600 transition-colors" data-val="SIM A / C Asli">SIM Asli</button>
                                    <button type="button" class="jaminan-chip text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 text-gray-600 transition-colors" data-val="KTM / Kartu Pelajar">KTM / Pelajar</button>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="text-[12px] font-bold text-gray-600">Foto Identitas / Jaminan (Opsional)</label>
                                <input type="file" name="foto_jaminan" accept="image/*"
                                    class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer border border-gray-200 rounded-xl p-1.5 bg-gray-50/50">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[12px] font-bold text-gray-600">Catatan / Pesanan Khusus (Opsional)</label>
                                <input type="text" name="pesan" value="{{ old('pesan') }}" placeholder="Contoh: Ambil jam 9 pagi, kelengkapan dicek bersama"
                                    class="w-full px-3.5 py-2 bg-gray-50/50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Durasi Sewa & Pembayaran Card -->
                    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-calculator text-purple-600"></i> Durasi & Pembayaran
                            </h3>
                            <span id="durasi-badge" class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-purple-100 text-purple-700">1 Hari</span>
                        </div>
                        <div class="p-6 flex flex-col gap-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[12px] font-bold text-gray-700">Mulai Sewa <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required
                                        class="w-full px-3 py-2 bg-gray-50/50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-gray-700">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[12px] font-bold text-gray-700">Selesai Sewa <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d', strtotime('+1 day'))) }}" required
                                        class="w-full px-3 py-2 bg-gray-50/50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-gray-700">
                                </div>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="space-y-1.5">
                                <label class="text-[12px] font-bold text-gray-700">Metode Pembayaran Kasir</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="metode_pembayaran" value="Cash" class="peer sr-only" checked>
                                        <div class="py-2 px-3 rounded-xl border border-gray-200 text-center text-xs font-bold text-gray-600 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition-all">
                                            <i class="bi bi-cash-coin block text-base mb-0.5"></i> Tunai
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="metode_pembayaran" value="QRIS" class="peer sr-only">
                                        <div class="py-2 px-3 rounded-xl border border-gray-200 text-center text-xs font-bold text-gray-600 peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 transition-all">
                                            <i class="bi bi-qr-code-scan block text-base mb-0.5"></i> QRIS
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="metode_pembayaran" value="Transfer" class="peer sr-only">
                                        <div class="py-2 px-3 rounded-xl border border-gray-200 text-center text-xs font-bold text-gray-600 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:text-indigo-700 transition-all">
                                            <i class="bi bi-bank block text-base mb-0.5"></i> Transfer
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Biaya Tambahan / Denda -->
                            <div class="space-y-1">
                                <label class="text-[12px] font-bold text-gray-700">Biaya Tambahan / Denda Sebelumnya (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-xs font-bold">Rp</span>
                                    </div>
                                    <input type="number" name="denda" id="denda" min="0" value="{{ old('denda', 0) }}"
                                        class="w-full pl-10 pr-4 py-2 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-semibold focus:outline-none focus:bg-white focus:border-purple-500 transition-all text-gray-700">
                                </div>
                            </div>

                            <!-- Rincian Perhitungan -->
                            <div class="p-4 bg-orange-50/80 rounded-2xl border border-orange-100 space-y-2">
                                <div class="flex justify-between text-xs font-semibold text-gray-600">
                                    <span>Subtotal Barang / Hari</span>
                                    <span id="subtotal_harian_display">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-xs font-semibold text-gray-600">
                                    <span>Durasi Penyewaan</span>
                                    <span id="durasi_text_display">x 1 Hari</span>
                                </div>
                                <div class="flex justify-between text-xs font-semibold text-gray-600" id="row_denda_summary" style="display: none;">
                                    <span>Biaya Tambahan / Denda</span>
                                    <span id="denda_summary_display">Rp 0</span>
                                </div>
                                <div class="border-t border-orange-200/70 pt-2 mt-1 flex items-baseline justify-between">
                                    <span class="text-xs font-extrabold text-orange-700 uppercase tracking-wider">Total Tagihan</span>
                                    <span class="text-2xl font-black text-orange-600" id="total_harga_display">Rp 0</span>
                                </div>
                            </div>

                            <!-- Kalkulator Uang Diterima & Kembalian -->
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200/80 space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-bold text-gray-700">Uang Diterima (Rp)</label>
                                    <button type="button" id="btn-uang-pas" class="text-[11px] font-bold text-blue-600 hover:underline">Set Uang Pas</button>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-xs font-bold">Rp</span>
                                    </div>
                                    <input type="number" name="jumlah_dibayar" id="jumlah_dibayar" min="0" value="0"
                                        class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-800 focus:outline-none focus:border-emerald-500">
                                </div>
                                <div class="flex items-center justify-between pt-1">
                                    <span class="text-xs font-bold text-gray-500">Kembalian Pelanggan:</span>
                                    <span id="kembalian_display" class="text-sm font-black text-emerald-600">Rp 0</span>
                                </div>
                            </div>

                            <button type="submit" id="btn-submit" disabled class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-600/20 hover:shadow-xl transition-all flex items-center justify-center gap-2 mt-1">
                                <i class="bi bi-check-circle-fill"></i> Proses & Aktifkan Sewa
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <!-- Riwayat Transaksi Kasir Offline Terbaru -->
        <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden mt-4">
            <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-clock-history text-indigo-600"></i> Riwayat Transaksi Kasir Offline Terbaru
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">10 transaksi terakhir yang dicatat melalui Kasir Offline toko Anda.</p>
                </div>
                <a href="{{ route('menu-transaksi.sewa-berlangsung', ['id_user' => Crypt::encrypt($id_user)]) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                    Kelola Semua Transaksi <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3.5">ID / Waktu</th>
                            <th class="px-6 py-3.5">Pelanggan Offline</th>
                            <th class="px-6 py-3.5">Barang Disewa</th>
                            <th class="px-6 py-3.5">Periode Sewa</th>
                            <th class="px-6 py-3.5">Total & Metode</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($riwayatOffline ?? [] as $order)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-black text-gray-900">#{{ $order->id }}</span>
                                    <div class="text-[11px] text-gray-400 font-medium">{{ Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ $order->nama_pelanggan_display }}</div>
                                    <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                        <i class="bi bi-whatsapp text-emerald-500"></i> {{ $order->no_hp_display }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1 max-w-xs">
                                        @foreach($order->items_summary as $item)
                                            <div class="text-xs font-semibold text-gray-700 truncate">
                                                • {{ $item->nama }} <span class="text-gray-400">({{ $item->warna_produk }} - {{ $item->ukuran }} x{{ $item->qty }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-700">
                                    <div>{{ Carbon\Carbon::parse($order->tanggal_mulai)->format('d M Y') }}</div>
                                    <div class="text-gray-400">s/d {{ Carbon\Carbon::parse($order->tanggal_selesai)->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-black text-gray-900">Rp {{ number_format($order->pembayaran->total_pembayaran ?? 0, 0, ',', '.') }}</div>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600">
                                        <i class="bi bi-check-circle-fill"></i> {{ $order->pembayaran->status_pembayaran ?? 'Lunas' }} ({{ $order->pembayaran->metode ?? 'Cash' }})
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->status_penyewaan === 'Aktif')
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 inline-flex items-center gap-1">
                                            <i class="bi bi-play-circle-fill"></i> Aktif
                                        </span>
                                    @elseif($order->status_penyewaan === 'Selesai')
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-700 inline-flex items-center gap-1">
                                            <i class="bi bi-check2-all"></i> Selesai
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-700">
                                            {{ $order->status_penyewaan }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('menu-transaksi.terima-order-masuk', ['id_penyewaan' => Crypt::encrypt($order->id)]) }}"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white text-xs font-bold transition-all">
                                        <i class="bi bi-eye"></i> Detail / Kembali
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400 text-sm">
                                    Belum ada riwayat transaksi Kasir Offline di toko Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Hidden Template for New Item Row -->
    <template id="item-row-template">
        <div class="item-row bg-white border border-gray-200 rounded-2xl p-5 hover:border-blue-300 transition-colors shadow-sm relative group flex flex-col sm:flex-row gap-5">
            <button type="button" class="btn-remove-item absolute -top-3 -right-3 sm:static sm:order-last w-8 h-8 flex items-center justify-center rounded-full bg-red-100 text-red-500 hover:bg-red-500 hover:text-white transition-colors shadow-sm border border-white z-10" title="Hapus baris">
                <i class="bi bi-x-lg text-sm"></i>
            </button>

            <div class="flex-1 min-w-0 flex flex-col gap-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Produk Select -->
                    <div>
                        <label class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1.5 block">Pilih Produk</label>
                        <select class="produk-select w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all font-semibold text-gray-700" required>
                            <option value="" disabled selected>-- Daftar Produk --</option>
                            @foreach($produk as $p)
                                <option value="{{ $p->id }}" data-variants="{{ json_encode($p->variants) }}">
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" class="input-id-produk" name="items[INDEX][id_produk]" required disabled>
                    </div>
                    
                    <!-- Variant Select -->
                    <div>
                        <label class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1.5 block">Varian / Ukuran</label>
                        <select class="variant-select w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-gray-700 disabled:opacity-50" required disabled>
                            <option value="" disabled selected>Pilih Produk Dahulu</option>
                        </select>
                        <input type="hidden" class="input-id-variant" name="items[INDEX][id_detail_variant]" required disabled>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 items-end bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                    <div>
                        <label class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1.5 block">Kuantitas Sewa</label>
                        <div class="flex items-center gap-2">
                            <input type="number" class="qty-input w-20 px-3 py-2 bg-white border border-gray-200 rounded-lg text-center text-sm font-bold focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" value="1" min="1" required disabled name="items[INDEX][qty]">
                            <span class="text-xs font-semibold text-gray-500 stok-info bg-gray-200/50 px-2.5 py-1 rounded-md">Stok: -</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <label class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1 block">Harga Sewa (Per Hari)</label>
                        <div class="font-black text-gray-800 text-base subtotal-text truncate">Rp 0</div>
                        <input type="hidden" class="subtotal-val" value="0">
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('items-container');
            const btnAdd = document.getElementById('add-item-btn');
            const template = document.getElementById('item-row-template');
            const emptyState = document.getElementById('empty-state');
            const totalDisplay = document.getElementById('total_harga_display');
            const subtotalHarianDisplay = document.getElementById('subtotal_harian_display');
            const durasiBadge = document.getElementById('durasi-badge');
            const durasiTextDisplay = document.getElementById('durasi_text_display');
            const rowDendaSummary = document.getElementById('row_denda_summary');
            const dendaSummaryDisplay = document.getElementById('denda_summary_display');
            const cartCountBadge = document.getElementById('cart-count-badge');
            const btnSubmit = document.getElementById('btn-submit');
            const inputTglMulai = document.getElementById('tanggal_mulai');
            const inputTglSelesai = document.getElementById('tanggal_selesai');
            const inputDenda = document.getElementById('denda');
            const inputJumlahDibayar = document.getElementById('jumlah_dibayar');
            const kembalianDisplay = document.getElementById('kembalian_display');
            const btnUangPas = document.getElementById('btn-uang-pas');
            
            let itemIndex = 0;
            let currentGrandTotal = 0;
            let userEditedBayar = false;

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka || 0);
            }

            function getDurasiHari() {
                if (!inputTglMulai.value || !inputTglSelesai.value) return 0;
                const start = new Date(inputTglMulai.value + 'T00:00:00');
                const end = new Date(inputTglSelesai.value + 'T00:00:00');
                if (end < start) return 0;
                const diffDays = Math.round((end - start) / (1000 * 60 * 60 * 24));
                return Math.max(1, diffDays);
            }

            function updateKembalian() {
                const dibayar = parseFloat(inputJumlahDibayar.value) || 0;
                const selisih = dibayar - currentGrandTotal;
                if (selisih >= 0) {
                    kembalianDisplay.innerText = formatRupiah(selisih);
                    kembalianDisplay.className = 'text-sm font-black text-emerald-600';
                } else {
                    kembalianDisplay.innerText = 'Kurang ' + formatRupiah(Math.abs(selisih));
                    kembalianDisplay.className = 'text-sm font-black text-red-500';
                }
            }

            function calculateTotal() {
                const durasi = getDurasiHari();
                durasiBadge.innerText = durasi > 0 ? `${durasi} Hari` : 'Tanggal Tidak Valid';
                durasiTextDisplay.innerText = `x ${durasi} Hari`;

                let dailyTotal = 0;
                let isAllValid = true;
                let itemCount = 0;

                document.querySelectorAll('.item-row').forEach(row => {
                    itemCount++;
                    const subVal = parseFloat(row.querySelector('.subtotal-val').value) || 0;
                    const qty = parseInt(row.querySelector('.qty-input').value) || 0;
                    const stok = parseInt(row.querySelector('.qty-input').getAttribute('max')) || 0;
                    
                    if (qty > stok || qty <= 0 || subVal <= 0) {
                        isAllValid = false;
                    }

                    dailyTotal += (subVal * qty);
                    row.querySelector('.subtotal-text').innerText = formatRupiah(subVal * qty);
                });

                cartCountBadge.innerText = `${itemCount} Item`;
                subtotalHarianDisplay.innerText = formatRupiah(dailyTotal);

                const dendaVal = parseFloat(inputDenda.value) || 0;
                if (dendaVal > 0) {
                    rowDendaSummary.style.display = 'flex';
                    dendaSummaryDisplay.innerText = formatRupiah(dendaVal);
                } else {
                    rowDendaSummary.style.display = 'none';
                }

                currentGrandTotal = (dailyTotal * durasi) + dendaVal;
                totalDisplay.innerText = formatRupiah(currentGrandTotal);

                if (!userEditedBayar || parseFloat(inputJumlahDibayar.value) === 0) {
                    inputJumlahDibayar.value = currentGrandTotal;
                }
                updateKembalian();

                if (itemCount > 0 && isAllValid && durasi > 0 && inputTglMulai.value && inputTglSelesai.value) {
                    btnSubmit.removeAttribute('disabled');
                } else {
                    btnSubmit.setAttribute('disabled', 'disabled');
                }
            }

            inputTglMulai.addEventListener('change', function() {
                if (inputTglSelesai.value && inputTglSelesai.value < this.value) {
                    inputTglSelesai.value = this.value;
                }
                calculateTotal();
            });
            inputTglSelesai.addEventListener('change', calculateTotal);
            inputDenda.addEventListener('input', calculateTotal);
            inputJumlahDibayar.addEventListener('input', function() {
                userEditedBayar = true;
                updateKembalian();
            });
            btnUangPas.addEventListener('click', function() {
                userEditedBayar = false;
                inputJumlahDibayar.value = currentGrandTotal;
                updateKembalian();
            });

            // Quick Jaminan Chips
            document.querySelectorAll('.jaminan-chip').forEach(chip => {
                chip.addEventListener('click', function() {
                    document.getElementById('jaminan_sewa').value = this.getAttribute('data-val');
                });
            });

            // Catalog Search Filter
            const catalogSearch = document.getElementById('catalog-search');
            if (catalogSearch) {
                catalogSearch.addEventListener('input', function() {
                    const q = this.value.toLowerCase().trim();
                    document.querySelectorAll('.catalog-card').forEach(card => {
                        const name = card.getAttribute('data-name') || '';
                        card.style.display = name.includes(q) ? '' : 'none';
                    });
                });
            }

            function createItemRow(presetProdukId = null, presetVariantId = null) {
                emptyState.style.display = 'none';
                
                const clone = template.content.cloneNode(true);
                const row = clone.querySelector('.item-row');
                
                row.querySelector('.input-id-produk').name = `items[${itemIndex}][id_produk]`;
                row.querySelector('.input-id-variant').name = `items[${itemIndex}][id_detail_variant]`;
                row.querySelector('.qty-input').name = `items[${itemIndex}][qty]`;
                
                const selectProduk = row.querySelector('.produk-select');
                const selectVariant = row.querySelector('.variant-select');
                const inputIdProduk = row.querySelector('.input-id-produk');
                const inputIdVariant = row.querySelector('.input-id-variant');
                const qtyInput = row.querySelector('.qty-input');
                const stokInfo = row.querySelector('.stok-info');
                const subText = row.querySelector('.subtotal-text');
                const subVal = row.querySelector('.subtotal-val');
                const btnRemove = row.querySelector('.btn-remove-item');

                selectProduk.addEventListener('change', function() {
                    const selOption = this.options[this.selectedIndex];
                    const variantsData = JSON.parse(selOption.getAttribute('data-variants') || '[]');
                    
                    inputIdProduk.value = this.value;
                    inputIdProduk.removeAttribute('disabled');
                    
                    selectVariant.innerHTML = '<option value="" disabled selected>-- Pilih Varian --</option>';
                    selectVariant.removeAttribute('disabled');
                    
                    variantsData.forEach(v => {
                        const detailList = v.detail_variants || v.detailVariants || [];
                        detailList.forEach(dv => {
                            if (dv.stok > 0) {
                                const opt = document.createElement('option');
                                opt.value = dv.id;
                                opt.text = `${v.warna} - ${dv.ukuran} (${formatRupiah(dv.harga_sewa)}/hari)`;
                                opt.setAttribute('data-stok', dv.stok);
                                opt.setAttribute('data-harga', dv.harga_sewa);
                                selectVariant.appendChild(opt);
                            }
                        });
                    });

                    inputIdVariant.value = "";
                    inputIdVariant.setAttribute('disabled', 'disabled');
                    qtyInput.value = 1;
                    qtyInput.setAttribute('disabled', 'disabled');
                    stokInfo.innerText = "Stok: -";
                    stokInfo.className = "text-xs font-semibold text-gray-500 stok-info bg-gray-200/50 px-2.5 py-1 rounded-md";
                    subVal.value = 0;
                    subText.innerText = "Rp 0";
                    calculateTotal();
                });

                selectVariant.addEventListener('change', function() {
                    const selOption = this.options[this.selectedIndex];
                    if (!selOption || !selOption.value) return;
                    const stok = parseInt(selOption.getAttribute('data-stok')) || 0;
                    const harga = parseFloat(selOption.getAttribute('data-harga')) || 0;

                    inputIdVariant.value = this.value;
                    inputIdVariant.removeAttribute('disabled');

                    qtyInput.removeAttribute('disabled');
                    qtyInput.setAttribute('max', stok);
                    qtyInput.value = 1;
                    
                    stokInfo.innerText = `Sisa: ${stok}`;
                    stokInfo.className = "text-xs font-semibold stok-info px-2.5 py-1 rounded-md";
                    if (stok < 3) {
                        stokInfo.classList.add('bg-orange-100', 'text-orange-600');
                    } else {
                        stokInfo.classList.add('bg-blue-100', 'text-blue-600');
                    }

                    subVal.value = harga;
                    subText.innerText = formatRupiah(harga);

                    calculateTotal();
                });

                qtyInput.addEventListener('input', function() {
                    const max = parseInt(this.getAttribute('max')) || 1;
                    let val = parseInt(this.value) || 0;
                    if (val > max) {
                        this.value = max;
                        stokInfo.innerText = `Maks: ${max}`;
                        stokInfo.className = "text-xs font-semibold stok-info px-2.5 py-1 rounded-md bg-red-100 text-red-600";
                    } else {
                        stokInfo.innerText = `Sisa: ${max}`;
                        stokInfo.className = "text-xs font-semibold stok-info px-2.5 py-1 rounded-md bg-blue-100 text-blue-600";
                    }
                    calculateTotal();
                });

                btnRemove.addEventListener('click', function() {
                    row.remove();
                    if (container.querySelectorAll('.item-row').length === 0) {
                        emptyState.style.display = 'block';
                    }
                    calculateTotal();
                });

                container.appendChild(clone);
                itemIndex++;

                if (presetProdukId) {
                    selectProduk.value = presetProdukId;
                    selectProduk.dispatchEvent(new Event('change'));
                    if (presetVariantId) {
                        selectVariant.value = presetVariantId;
                        selectVariant.dispatchEvent(new Event('change'));
                    }
                }

                return row;
            }

            btnAdd.addEventListener('click', function() {
                createItemRow();
            });

            // Quick-add from Catalog Grid
            document.querySelectorAll('.btn-quick-add').forEach(btn => {
                btn.addEventListener('click', function() {
                    const produkId = this.getAttribute('data-produk-id');
                    const variantId = this.getAttribute('data-variant-id');
                    const maxStok = parseInt(this.getAttribute('data-stok')) || 0;

                    // Check if variant already exists in cart
                    let existingRow = null;
                    document.querySelectorAll('.item-row').forEach(row => {
                        const rowVarId = row.querySelector('.input-id-variant').value;
                        if (rowVarId && rowVarId === variantId) {
                            existingRow = row;
                        }
                    });

                    if (existingRow) {
                        const qInput = existingRow.querySelector('.qty-input');
                        const curQty = parseInt(qInput.value) || 1;
                        if (curQty < maxStok) {
                            qInput.value = curQty + 1;
                            qInput.dispatchEvent(new Event('input'));
                        }
                        existingRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else {
                        createItemRow(produkId, variantId);
                    }
                });
            });

            calculateTotal();
        });
    </script>
@endsection
