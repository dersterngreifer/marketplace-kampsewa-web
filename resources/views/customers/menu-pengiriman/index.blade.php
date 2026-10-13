@extends('layouts.customers.layouts-customer')

@section('customer-content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-8">

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 font-bold text-xs rounded-full border border-blue-200">
                    <i class="bi bi-truck text-xs"></i> Pengaturan Toko Mitra
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 font-bold text-xs rounded-full border border-emerald-200">
                    <i class="bi bi-phone text-xs"></i> Sinkron Mobile App
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Pengaturan & Edit Biaya Antar Toko</h1>
            <p class="text-sm text-gray-500 font-medium mt-1">
                Atur nominal tarif antar barang ke penyewa. Biaya ini akan otomatis muncul pada opsi <strong class="text-gray-700">"Antar Ke Alamatmu"</strong> di checkout aplikasi mobile.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('menu-transaksi.index', ['id_user' => $id_user]) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-50 hover:text-blue-600 transition-colors shadow-sm">
                <i class="bi bi-cart-check"></i> Daftar Pesanan
            </a>
            <a href="{{ route('dashboard-cust', ['id_user' => $id_user]) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-50 border border-blue-200 text-blue-600 text-sm font-bold rounded-xl hover:bg-blue-100 transition-colors">
                <i class="bi bi-speedometer2"></i> Dashboard Toko
            </a>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Status Layanan -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Layanan Antar</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-block w-3 h-3 rounded-full {{ $pengaturan->layanan_antar ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    <h3 class="text-xl font-black {{ $pengaturan->layanan_antar ? 'text-emerald-600' : 'text-gray-500' }}">
                        {{ $pengaturan->layanan_antar ? 'Layanan Aktif' : 'Layanan Nonaktif' }}
                    </h3>
                </div>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $pengaturan->layanan_antar ? 'Pelanggan mobile bisa pilih antar ke alamat' : 'Pelanggan hanya bisa ambil di toko' }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl {{ $pengaturan->layanan_antar ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-xl flex-shrink-0">
                <i class="bi bi-truck"></i>
            </div>
        </div>

        <!-- Tarif Saat Ini -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider">Tarif Antar Saat Ini</p>
                <h3 class="text-2xl sm:text-3xl font-black text-gray-900 mt-1">
                    Rp {{ number_format($pengaturan->biaya_antar, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-gray-400 mt-1">Dikenakan per transaksi pengantaran</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>

        <!-- Total Pesanan -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-purple-600 uppercase tracking-wider">Total Pesanan Diantar</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <h3 class="text-2xl sm:text-3xl font-black text-gray-900">{{ $totalPesananDiantar }}</h3>
                    <span class="text-xs font-bold text-gray-500">kali diantar</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $totalPesananAmbil }} pesanan diambil di tempat</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>
    </div>

    <!-- Main Content: Form Edit Tarif (Left) & Mobile Preview (Right) -->
    <div class="flex flex-col lg:flex-row gap-8 items-start w-full">

        <!-- Form Edit Tarif (Left Column) -->
        <div class="w-full lg:w-7/12 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
                        <i class="bi bi-pencil-square text-blue-600"></i> Form Edit Tarif & Layanan Pengantaran
                    </h2>
                    <p class="text-xs text-gray-500 font-medium mt-1">
                        Ubah tarif biaya pengantaran barang toko Anda. Klik simpan untuk menerapkan langsung ke mobile app.
                    </p>
                </div>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg self-start sm:self-auto">
                    <i class="bi bi-check-circle-fill"></i> Toko: {{ $user->name_store ?? 'Mitra KampSewa' }}
                </span>
            </div>

            <div class="p-6 sm:p-8">
                @if (isset($errors) && $errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                        <p class="font-bold mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Terdapat kesalahan pengisian:</p>
                        <ul class="list-disc list-inside text-xs space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('menu-pengiriman.update', ['id_user' => $id_user]) }}" method="POST" class="flex flex-col gap-6">
                    @csrf

                    <!-- 1. Pilihan Status Layanan Antar -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">
                            Ketersediaan Layanan Pengantaran <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Opsi Aktif -->
                            <label class="relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 option-label {{ $pengaturan->layanan_antar ? 'border-emerald-500 bg-emerald-50/40 text-emerald-900' : 'border-gray-200 bg-white hover:bg-gray-50' }}" id="label-layanan-aktif">
                                <input type="radio" name="layanan_antar" value="1" id="radio-aktif"
                                       class="w-4 h-4 text-emerald-600 focus:ring-emerald-500"
                                       {{ $pengaturan->layanan_antar ? 'checked' : '' }}
                                       onchange="toggleLayananAntar(true)">
                                <div class="flex-1">
                                    <span class="block text-sm font-bold text-gray-900">Aktifkan Pengantaran</span>
                                    <span class="block text-xs text-gray-500 mt-0.5">Pelanggan bisa memilih "Antar Ke Alamatmu"</span>
                                </div>
                            </label>

                            <!-- Opsi Nonaktif -->
                            <label class="relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 option-label {{ !$pengaturan->layanan_antar ? 'border-red-500 bg-red-50/40 text-red-900' : 'border-gray-200 bg-white hover:bg-gray-50' }}" id="label-layanan-nonaktif">
                                <input type="radio" name="layanan_antar" value="0" id="radio-nonaktif"
                                       class="w-4 h-4 text-red-600 focus:ring-red-500"
                                       {{ !$pengaturan->layanan_antar ? 'checked' : '' }}
                                       onchange="toggleLayananAntar(false)">
                                <div class="flex-1">
                                    <span class="block text-sm font-bold text-gray-900">Hanya Ambil di Toko</span>
                                    <span class="block text-xs text-gray-500 mt-0.5">Opsi pengantaran ditutup/dinonaktifkan</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 2. Input Nominal Biaya Antar Toko -->
                    <div class="border-t border-gray-100 pt-5">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="biaya_antar" class="block text-sm font-bold text-gray-800">
                                Nominal Tarif Biaya Antar (Rp) <span class="text-red-500">*</span>
                            </label>
                            <span id="preview-formatted-input" class="text-xs font-black text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200">
                                Terbaca: Rp {{ number_format($pengaturan->biaya_antar, 0, ',', '.') }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mb-2.5">
                            Nominal ini otomatis ditambahkan ke total transaksi ketika pelanggan checkout dengan opsi pengantaran.
                        </p>

                        <div class="relative flex items-center">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-600 font-black text-base">Rp</span>
                            </div>
                            <input type="number"
                                   name="biaya_antar"
                                   id="biaya_antar"
                                   min="0"
                                   step="1000"
                                   value="{{ old('biaya_antar', $pengaturan->biaya_antar) }}"
                                   required
                                   class="block w-full pl-12 pr-4 py-3.5 border-2 border-gray-300 rounded-xl text-lg font-black text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-sm"
                                   placeholder="Contoh: 15000">
                        </div>

                        <!-- Preset Pilihan Tarif Cepat -->
                        <div class="mt-3">
                            <span class="text-xs font-bold text-gray-500 block mb-1.5">Preset Nominal Cepat:</span>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="setTarif(0)" class="px-3 py-1.5 text-xs font-bold rounded-lg border border-gray-300 bg-white hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-colors shadow-2xs">
                                    Gratis (Rp 0)
                                </button>
                                <button type="button" onclick="setTarif(5000)" class="px-3 py-1.5 text-xs font-bold rounded-lg border border-gray-300 bg-white hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-colors shadow-2xs">
                                    Rp 5.000
                                </button>
                                <button type="button" onclick="setTarif(10000)" class="px-3 py-1.5 text-xs font-bold rounded-lg border border-gray-300 bg-white hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-colors shadow-2xs">
                                    Rp 10.000
                                </button>
                                <button type="button" onclick="setTarif(15000)" class="px-3 py-1.5 text-xs font-bold rounded-lg border border-gray-300 bg-white hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-colors shadow-2xs">
                                    Rp 15.000
                                </button>
                                <button type="button" onclick="setTarif(20000)" class="px-3 py-1.5 text-xs font-bold rounded-lg border border-gray-300 bg-white hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-colors shadow-2xs">
                                    Rp 20.000
                                </button>
                                <button type="button" onclick="setTarif(25000)" class="px-3 py-1.5 text-xs font-bold rounded-lg border border-gray-300 bg-white hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-colors shadow-2xs">
                                    Rp 25.000
                                </button>
                                <button type="button" onclick="setTarif(30000)" class="px-3 py-1.5 text-xs font-bold rounded-lg border border-gray-300 bg-white hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-colors shadow-2xs">
                                    Rp 30.000
                                </button>
                                <button type="button" onclick="setTarif(50000)" class="px-3 py-1.5 text-xs font-bold rounded-lg border border-gray-300 bg-white hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-colors shadow-2xs">
                                    Rp 50.000
                                </button>
                            </div>
                        </div>

                        @error('biaya_antar')
                            <p class="text-xs text-red-500 font-medium mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 3. Catatan Jangkauan / Radius / Jam Antar -->
                    <div class="border-t border-gray-100 pt-5">
                        <label for="keterangan" class="block text-sm font-bold text-gray-800 mb-1">
                            Catatan Jangkauan / Ketentuan Pengantaran
                        </label>
                        <p class="text-xs text-gray-500 mb-2">
                            Informasikan jangkauan maksimal (misal: radius km, nama kecamatan, atau jam operasional antar kurir toko).
                        </p>
                        <textarea name="keterangan"
                                  id="keterangan"
                                  rows="3"
                                  class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-800 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-sm"
                                  placeholder="Contoh: Pengantaran mencakup area kampus dan maksimal radius 10 km dari toko. Waktu antar pukul 09.00 - 17.00 WIB.">{{ old('keterangan', $pengaturan->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="text-xs text-red-500 font-medium mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 4. Minimal Transaksi Bebas Ongkir (Opsional) -->
                    <div class="border-t border-gray-100 pt-5">
                        <label for="minimal_transaksi_gratis_antar" class="block text-sm font-bold text-gray-800 mb-1">
                            Minimal Transaksi Bebas Biaya Antar (Opsional)
                        </label>
                        <p class="text-xs text-gray-500 mb-2">
                            Jika diisi lebih dari 0, penyewa dengan nominal sewa mencapai angka ini akan otomatis mendapat bebas biaya antar.
                        </p>
                        <div class="relative flex items-center">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold text-sm">Rp</span>
                            </div>
                            <input type="number"
                                   name="minimal_transaksi_gratis_antar"
                                   id="minimal_transaksi_gratis_antar"
                                   min="0"
                                   step="5000"
                                   value="{{ old('minimal_transaksi_gratis_antar', $pengaturan->minimal_transaksi_gratis_antar) }}"
                                   class="block w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-xl text-sm font-bold text-gray-800 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-sm"
                                   placeholder="0 (Isi 0 jika tidak ada promo gratis ongkir)">
                        </div>
                    </div>

                    <!-- Tombol Aksi Simpan -->
                    <div class="border-t border-gray-100 pt-6 flex flex-col sm:flex-row items-center gap-3">
                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg transition-all duration-200">
                            <i class="bi bi-check2-circle text-lg"></i> Simpan & Terapkan Perubahan Tarif
                        </button>
                        <a href="{{ route('dashboard-cust', ['id_user' => $id_user]) }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 border border-gray-300 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 transition-colors text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Live Preview Sisi Pengguna Mobile (Right Column) -->
        <div class="w-full lg:w-5/12 flex flex-col gap-5">
            <!-- Mockup Card Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Mockup Header -->
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span>
                        <span class="text-xs font-bold text-gray-700 ml-2">Simulasi Tampilan di Mobile User</span>
                    </div>
                    <span class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-200">
                        Live Preview
                    </span>
                </div>

                <div class="p-5 flex flex-col gap-4">
                    <p class="text-xs text-gray-500">
                        Berikut adalah tampilan kartu yang muncul pada langkah <strong class="text-gray-700">Opsi Pengiriman</strong> dan <strong class="text-gray-700">Checkout Transaksi</strong> di aplikasi pengguna:
                    </p>

                    <!-- Preview Card 1: Ambil Ditempat -->
                    <div class="p-4 rounded-xl border border-gray-200 bg-white flex items-start gap-3 shadow-2xs">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center text-lg flex-shrink-0">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center">
                                <h4 class="text-sm font-bold text-gray-900">Ambil Ditempat</h4>
                                <span class="text-xs font-bold text-emerald-600">Gratis (Rp 0)</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">Ambil mandiri ke alamat toko mitra</p>
                        </div>
                    </div>

                    <!-- Preview Card 2: Antar Ke Alamatmu (Live Updated) -->
                    <div id="preview-box-antar" class="p-4 rounded-xl border-2 {{ $pengaturan->layanan_antar ? 'border-blue-500 bg-blue-50/40' : 'border-gray-200 bg-gray-50 opacity-60' }} flex items-start gap-3 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl {{ $pengaturan->layanan_antar ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-lg flex-shrink-0">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center">
                                <h4 class="text-sm font-bold text-gray-900">Antar Ke Alamatmu</h4>
                                <span id="preview-badge-biaya" class="text-xs font-black {{ $pengaturan->layanan_antar ? 'text-blue-600' : 'text-gray-400' }}">
                                    @if($pengaturan->layanan_antar)
                                        Rp {{ number_format($pengaturan->biaya_antar, 0, ',', '.') }}
                                    @else
                                        (Tidak Tersedia)
                                    @endif
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">Barang dikirim oleh kurir toko ke alamat Anda</p>
                            <div id="preview-keterangan" class="text-[11px] text-gray-600 mt-2 bg-white p-2.5 rounded-lg border border-gray-200">
                                {{ $pengaturan->keterangan ?: 'Melayani area sekitar toko mitra.' }}
                            </div>
                        </div>
                    </div>

                    <!-- Preview Rincian Pembayaran di Layar Checkout Mobile -->
                    <div class="mt-2 p-4 rounded-xl bg-gray-50 border border-gray-200 flex flex-col gap-2">
                        <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                            <h5 class="text-xs font-black uppercase tracking-wider text-gray-600">Simulasi Rincian Pembayaran</h5>
                            <span class="text-[10px] font-bold text-gray-400">Checkout Mobile</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>Subtotal Sewa Produk (2 Hari)</span>
                            <span class="font-bold text-gray-900">Rp 100.000</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>Biaya Pengantaran Toko</span>
                            <span id="preview-rincian-biaya" class="font-black text-blue-600">
                                Rp {{ number_format($pengaturan->biaya_antar, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>Biaya Layanan Admin</span>
                            <span class="font-bold text-emerald-600">Free</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 flex justify-between text-sm font-black text-gray-900">
                            <span>Total Pembayaran</span>
                            <span id="preview-total-pembayaran" class="text-blue-600">
                                Rp {{ number_format(100000 + $pengaturan->biaya_antar, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Petunjuk Integrasi -->
            <div class="bg-blue-50/60 rounded-2xl p-5 border border-blue-100 flex items-start gap-3">
                <i class="bi bi-info-circle-fill text-blue-600 text-lg flex-shrink-0 mt-0.5"></i>
                <div class="text-xs text-blue-900 leading-relaxed">
                    <p class="font-bold mb-1">Informasi Sinkronisasi Sistem:</p>
                    <p>Setelah Anda menyimpan tarif di formulir ini, setiap pelanggan yang menyewa dari toko <strong class="font-bold">{{ $user->name_store ?? 'Anda' }}</strong> di aplikasi Flutter akan langsung melihat nominal terbaru tanpa perlu restart aplikasi.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi Pengiriman Terbaru -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full flex flex-col">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                    <i class="bi bi-clock-history text-blue-600"></i> Riwayat Pesanan Pengantaran Toko
                </h3>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Daftar transaksi penyewaan terbaru dengan opsi pengiriman pelanggan.</p>
            </div>
            <a href="{{ route('menu-transaksi.index', ['id_user' => $id_user]) }}"
               class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 self-start sm:self-auto">
                Lihat Semua Pesanan <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @if ($pesananTerbaru->isEmpty())
            <div class="p-12 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-3xl mb-3">
                    <i class="bi bi-inbox"></i>
                </div>
                <h4 class="text-base font-bold text-gray-800">Belum Ada Transaksi Pengantaran</h4>
                <p class="text-xs text-gray-500 max-w-sm mt-1">
                    Pesanan yang masuk dari pelanggan melalui aplikasi mobile dengan opsi pengantaran akan tercatat di sini.
                </p>
            </div>
        @else
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-max text-sm text-left">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">No. Pesanan</th>
                            <th class="px-6 py-4">Penyewa</th>
                            <th class="px-6 py-4">Opsi Pengiriman</th>
                            <th class="px-6 py-4">Biaya Antar</th>
                            <th class="px-6 py-4">Alamat Tujuan</th>
                            <th class="px-6 py-4">Status Pesanan</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach ($pesananTerbaru as $p)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    #{{ $p->id_penyewaan }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900">{{ $p->nama_penyewa }}</p>
                                    <p class="text-xs text-gray-400">{{ $p->telp_penyewa ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $isDiantar = in_array(strtolower($p->opsi_pengiriman ?? ''), ['diantar', 'diantarkan', 'antar ke alamatmu']);
                                    @endphp
                                    @if ($isDiantar)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="bi bi-truck"></i> Diantarkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                            <i class="bi bi-shop"></i> Ambil Sendiri
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    Rp {{ number_format($p->biaya_antar ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-xs max-w-xs truncate text-gray-600" title="{{ $p->alamat_pengiriman ?? '-' }}">
                                    {{ $p->alamat_pengiriman ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ ucfirst(str_replace('_', ' ', $p->status_penyewaan ?? 'Pending')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('menu-transaksi.terima-order-masuk', ['id_penyewaan' => $p->id_penyewaan, 'id_user' => $id_user]) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-bold transition-all">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

<!-- Client-side Interactive Synchronization Script -->
<script>
    const inputBiaya = document.getElementById('biaya_antar');
    const inputKet = document.getElementById('keterangan');
    const previewFormatted = document.getElementById('preview-formatted-input');
    const previewBadge = document.getElementById('preview-badge-biaya');
    const previewRincian = document.getElementById('preview-rincian-biaya');
    const previewTotal = document.getElementById('preview-total-pembayaran');
    const previewKet = document.getElementById('preview-keterangan');
    const previewBoxAntar = document.getElementById('preview-box-antar');

    const labelAktif = document.getElementById('label-layanan-aktif');
    const labelNonaktif = document.getElementById('label-layanan-nonaktif');
    const radioAktif = document.getElementById('radio-aktif');

    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function setTarif(nominal) {
        inputBiaya.value = nominal;
        updatePreview();
    }

    function toggleLayananAntar(isAktif) {
        if (isAktif) {
            labelAktif.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 option-label border-emerald-500 bg-emerald-50/40 text-emerald-900';
            labelNonaktif.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 option-label border-gray-200 bg-white hover:bg-gray-50';
        } else {
            labelAktif.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 option-label border-gray-200 bg-white hover:bg-gray-50';
            labelNonaktif.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 option-label border-red-500 bg-red-50/40 text-red-900';
        }
        updatePreview();
    }

    function updatePreview() {
        const biaya = parseInt(inputBiaya.value) || 0;
        const aktif = radioAktif.checked;
        const formatted = 'Rp ' + formatRupiah(biaya);

        previewFormatted.innerText = 'Terbaca: ' + formatted;

        if (!aktif) {
            previewBoxAntar.className = 'p-4 rounded-xl border-2 border-gray-200 bg-gray-50 opacity-60 flex items-start gap-3 transition-all duration-200';
            previewBadge.innerText = '(Tidak Tersedia)';
            previewBadge.className = 'text-xs font-black text-gray-400';
            previewRincian.innerText = 'Rp 0';
            previewTotal.innerText = 'Rp 100.000';
        } else {
            previewBoxAntar.className = 'p-4 rounded-xl border-2 border-blue-500 bg-blue-50/40 flex items-start gap-3 transition-all duration-200';
            previewBadge.innerText = formatted;
            previewBadge.className = 'text-xs font-black text-blue-600';
            previewRincian.innerText = formatted;
            previewTotal.innerText = 'Rp ' + formatRupiah(100000 + biaya);
        }

        if (inputKet.value.trim() !== '') {
            previewKet.innerText = inputKet.value;
        } else {
            previewKet.innerText = 'Melayani area sekitar toko mitra.';
        }
    }

    inputBiaya.addEventListener('input', updatePreview);
    inputKet.addEventListener('input', updatePreview);
</script>
@endsection
