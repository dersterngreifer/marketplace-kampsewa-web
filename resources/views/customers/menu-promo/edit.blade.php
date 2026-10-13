@extends('layouts.customers.layouts-customer')

@section('customer-content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-6">
    <!-- Breadcrumb & Back -->
    <div class="mb-1">
        <a href="{{ route('menu-promo.index', ['id_user' => $id_user]) }}"
           class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors">
            <i class="bi bi-arrow-left-short text-xl mr-1"></i> Kembali ke Daftar Diskon
        </a>
    </div>

    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 font-bold text-xs rounded-full border border-amber-200 mb-2">
                <i class="bi bi-pencil-square text-xs"></i> Edit Diskon Produk
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Edit Program Diskon Produk</h1>
            <p class="text-sm text-gray-500 font-medium mt-1">
                Perbarui skema potongan harga, produk yang dipromosikan, atau masa berlaku diskon sewa.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <p class="font-bold mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Terdapat beberapa kesalahan pengisian:</p>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="form-diskon-edit"
          action="{{ route('menu-promo.update', ['id_promo' => $promo->id_promo, 'id_user' => $id_user]) }}"
          method="POST"
          class="flex flex-col gap-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Informasi Diskon & Nilai Potongan -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-100 shadow-sm flex flex-col gap-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
                    <i class="bi bi-tag-fill text-blue-600"></i> Skema & Besaran Diskon Produk
                </h2>
                <p class="text-xs text-gray-500 mt-0.5 font-medium">Beri nama program diskon dan tentukan apakah menggunakan persentase atau nominal tetap.</p>
            </div>

            <!-- Nama Program Diskon -->
            <div>
                <label for="nama_diskon" class="block text-sm font-bold text-gray-800 mb-1">
                    Nama Program Diskon <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nama_diskon"
                       id="nama_diskon"
                       value="{{ old('nama_diskon', $promo->nama_diskon) }}"
                       required
                       maxlength="150"
                       placeholder="Contoh: Diskon Musim Liburan 15%, Promo Weekend Hemat, atau Flash Sale Tenda"
                       class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-2xs">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <!-- Tipe Diskon (Persen vs Nominal) -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">
                        Tipe Potongan Harga <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label {{ $promo->tipe_diskon === 'persen' ? 'border-blue-500 bg-blue-50/40' : 'border-gray-200 hover:bg-gray-50' }}" id="label-tipe-persen">
                            <input type="radio" name="tipe_diskon" value="persen" id="tipe-persen" {{ $promo->tipe_diskon === 'persen' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500" onchange="handleTipeChange('persen')">
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Persentase (%)</span>
                                <span class="block text-[11px] text-gray-500 mt-0.5">Misal: 10%, 20%, 50%</span>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label {{ $promo->tipe_diskon === 'nominal' ? 'border-blue-500 bg-blue-50/40' : 'border-gray-200 hover:bg-gray-50' }}" id="label-tipe-nominal">
                            <input type="radio" name="tipe_diskon" value="nominal" id="tipe-nominal" {{ $promo->tipe_diskon === 'nominal' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500" onchange="handleTipeChange('nominal')">
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Nominal Tetap (Rp)</span>
                                <span class="block text-[11px] text-gray-500 mt-0.5">Misal: Potongan Rp 25.000</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Nilai Diskon -->
                <div>
                    <label for="nilai_diskon" class="block text-sm font-bold text-gray-800 mb-2">
                        <span id="label-nilai-diskon">{{ $promo->tipe_diskon === 'persen' ? 'Besaran Diskon (%)' : 'Nominal Potongan Harga (Rp)' }}</span> <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <div id="prefix-addon" class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none {{ $promo->tipe_diskon === 'persen' ? 'hidden' : '' }}">
                            <span class="text-gray-600 font-bold text-sm">Rp</span>
                        </div>
                        <input type="number"
                               name="nilai_diskon"
                               id="nilai_diskon"
                               min="1"
                               {{ $promo->tipe_diskon === 'persen' ? 'max=100' : '' }}
                               step="1"
                               value="{{ old('nilai_diskon', (int) $promo->nilai_diskon) }}"
                               required
                               oninput="recalculateDiscountPreview()"
                               class="block w-full border border-gray-300 rounded-xl {{ $promo->tipe_diskon === 'persen' ? 'px-4' : 'pl-12 pr-4' }} py-3 text-lg font-black text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-2xs">
                        <div id="suffix-addon" class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none {{ $promo->tipe_diskon === 'persen' ? '' : 'hidden' }}">
                            <span class="text-gray-600 font-bold text-base">%</span>
                        </div>
                    </div>
                    <span id="badge-diskon-preview" class="inline-block mt-2 text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200">
                        Diskon: {{ (int) $promo->nilai_diskon }}{{ $promo->tipe_diskon === 'persen' ? '%' : ' Rp' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Section 2: Cakupan & Pilihan Produk -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-100 shadow-sm flex flex-col gap-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
                    <i class="bi bi-boxes text-blue-600"></i> Cakupan Produk yang Didiskon
                </h2>
                <p class="text-xs text-gray-500 mt-0.5 font-medium">Terapkan diskon ini untuk semua produk di toko atau hanya untuk produk-produk pilihan tertentu.</p>
            </div>

            @if($activeStoreWide && $activeStoreWide->id_promo != $promo->id_promo)
                <!-- Banner Peringatan Diskon Keseluruhan Masih Aktif -->
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 flex items-start gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-amber-600 text-xl flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-amber-900">Toko Anda Sedang Memiliki Diskon Keseluruhan Lain yang Aktif</h4>
                        <p class="text-xs text-amber-800 mt-0.5">
                            Program <strong>"{{ $activeStoreWide->nama_diskon }}"</strong> ({{ $activeStoreWide->tipe_diskon === 'persen' ? round($activeStoreWide->nilai_diskon) . '%' : 'Rp ' . number_format($activeStoreWide->nilai_diskon, 0, ',', '.') }}) saat ini sedang aktif hingga <strong>{{ $activeStoreWide->tanggal_selesai ? $activeStoreWide->tanggal_selesai->translatedFormat('d F Y H:i') : '-' }}</strong>.
                        </p>
                        <p class="text-[11px] text-amber-700 mt-1">
                            Anda tidak dapat mengubah cakupan promo ini menjadi diskon keseluruhan toko karena sudah ada diskon keseluruhan lain yang berjalan.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Radio Cakupan -->
            @php
                $isAllDisabled = ($activeStoreWide && $activeStoreWide->id_promo != $promo->id_promo);
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="relative flex items-center gap-3 p-4 border-2 rounded-xl {{ $isAllDisabled ? 'opacity-60 cursor-not-allowed bg-gray-100 border-gray-200' : 'cursor-pointer transition-all option-label ' . ($promo->cakupan === 'all_products' ? 'border-blue-500 bg-blue-50/40' : 'border-gray-200 hover:bg-gray-50') }}" id="label-cakupan-all">
                    <input type="radio" name="cakupan" value="all_products" id="cakupan-all" {{ $isAllDisabled ? 'disabled' : ($promo->cakupan === 'all_products' ? 'checked' : '') }} class="w-4 h-4 text-blue-600 focus:ring-blue-500" onchange="handleCakupanChange('all')">
                    <div>
                        <span class="block text-sm font-bold text-gray-900">
                            Semua Produk di Toko
                            @if($isAllDisabled)
                                <span class="text-[10px] font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded-full ml-1.5">Sedang Berjalan</span>
                            @endif
                        </span>
                        <span class="block text-xs text-gray-500 mt-0.5">Seluruh katalog toko Anda otomatis mendapatkan diskon ini</span>
                    </div>
                </label>

                <label class="relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label {{ $promo->cakupan === 'selected_products' ? 'border-blue-500 bg-blue-50/40' : 'border-gray-200 hover:bg-gray-50' }}" id="label-cakupan-selected">
                    <input type="radio" name="cakupan" value="selected_products" id="cakupan-selected" {{ $promo->cakupan === 'selected_products' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500" onchange="handleCakupanChange('selected')">
                    <div>
                        <span class="block text-sm font-bold text-gray-900">Produk Pilihan Tertentu</span>
                        <span class="block text-xs text-gray-500 mt-0.5">Pilih produk-produk tertentu yang ingin Anda diskon</span>
                    </div>
                </label>
            </div>

            <!-- Sub-opsi Penanganan Konflik Produk Diskon (Hanya saat All Products) -->
            <div id="konflik-diskon-container" class="{{ ($promo->cakupan === 'all_products' && !$isAllDisabled) ? '' : 'hidden' }} flex flex-col gap-3 p-4 bg-amber-50/60 rounded-xl border border-amber-200">
                <div class="flex items-center gap-2">
                    <i class="bi bi-shield-exclamation text-amber-600 text-lg"></i>
                    <div>
                        <span class="text-xs font-black text-amber-900 block">Kebijakan Penanganan Produk yang Sudah Memiliki Diskon:</span>
                        <span class="text-[11px] text-amber-700">Jika ada produk toko yang sedang aktif promo diskon lain, tentukan bagaimana sistem menanganinya:</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-1">
                    <label class="flex items-start gap-3 p-3.5 bg-white rounded-xl border-2 {{ ($promo->konflik_diskon ?? 'replace') === 'replace' ? 'border-blue-500' : 'border-gray-200' }} cursor-pointer transition-all shadow-xs" id="label-konflik-replace">
                        <input type="radio" name="konflik_diskon" value="replace" {{ ($promo->konflik_diskon ?? 'replace') === 'replace' ? 'checked' : '' }} class="mt-0.5 w-4 h-4 text-blue-600 focus:ring-blue-500" onchange="handleKonflikChange('replace')">
                        <div>
                            <span class="block text-xs font-bold text-gray-900">1. Samaratakan (Replace Diskon Lama)</span>
                            <span class="block text-[11px] text-gray-500 mt-0.5 leading-snug">Diskon disamaratakan ke semua produk toko. Promo lama pada produk-produk tersebut otomatis digantikan diskon ini.</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3.5 bg-white rounded-xl border {{ ($promo->konflik_diskon ?? 'replace') === 'skip' ? 'border-blue-500' : 'border-gray-200' }} hover:bg-gray-50 cursor-pointer transition-all shadow-xs" id="label-konflik-skip">
                        <input type="radio" name="konflik_diskon" value="skip" {{ ($promo->konflik_diskon ?? 'replace') === 'skip' ? 'checked' : '' }} class="mt-0.5 w-4 h-4 text-blue-600 focus:ring-blue-500" onchange="handleKonflikChange('skip')">
                        <div>
                            <span class="block text-xs font-bold text-gray-900">2. Pertahankan Diskon Lama (Lewati)</span>
                            <span class="block text-[11px] text-gray-500 mt-0.5 leading-snug">Produk yang sudah memiliki diskon aktif dibiarkan tetap dengan diskon lamanya. Diskon baru hanya diaplikasikan pada produk sisanya.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Tabel Pemilihan Produk (Tampil jika selected_products) -->
            <div id="product-selector-container" class="{{ $promo->cakupan === 'selected_products' ? '' : 'hidden' }} flex flex-col gap-4 border-t border-gray-100 pt-5">
                <!-- Header Box & Selected Counter -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-gray-50 to-blue-50/40 p-4 rounded-xl border border-gray-200">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-800 block">Pilih Produk Tertentu untuk Diskon</span>
                            <span id="selected-counter-badge" class="px-2.5 py-0.5 text-[11px] font-black bg-blue-100 text-blue-700 rounded-full border border-blue-200">
                                <span id="selected-count-number">0</span> Produk Terpilih
                            </span>
                        </div>
                        <span class="text-xs text-gray-500 mt-0.5 block">Centang produk yang ingin dimasukkan ke dalam program diskon. Produk yang aktif di program diskon lain terkunci otomatis.</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Dropdown Menu Aksi Cepat per Kategori -->
                        @if($categories->isNotEmpty())
                            <div class="relative inline-block text-left" id="dropdown-category-wrapper">
                                <button type="button" onclick="toggleCategoryDropdown()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-2xs">
                                    <i class="bi bi-tag-fill text-blue-600"></i> Centang per Kategori <i class="bi bi-chevron-down text-[10px]"></i>
                                </button>
                                <div id="dropdown-category-menu" class="hidden absolute right-0 mt-2 w-64 rounded-xl shadow-lg bg-white ring-1 ring-black/5 z-20 p-2 space-y-1">
                                    <div class="px-2 py-1 text-[10px] font-bold text-gray-400 border-b border-gray-100 mb-1 uppercase tracking-wider">
                                        PILIH SEMUA PER KATEGORI
                                    </div>
                                    @foreach($categories as $cat)
                                        @php
                                            $catCount = $products->where('kategori', $cat)->count();
                                        @endphp
                                        <div class="flex items-center justify-between px-2 py-1.5 rounded-lg hover:bg-gray-50 transition-colors text-xs">
                                            <span class="font-medium text-gray-800 truncate max-w-[130px]">{{ $cat }} ({{ $catCount }})</span>
                                            <div class="flex items-center gap-1">
                                                <button type="button" onclick="selectCategoryDirect('{{ addslashes($cat) }}', true)" class="px-2 py-0.5 text-[10px] font-bold text-blue-600 hover:bg-blue-50 border border-blue-200 rounded" title="Centang semua produk kategori {{ $cat }}">
                                                    + Pilih
                                                </button>
                                                <button type="button" onclick="selectCategoryDirect('{{ addslashes($cat) }}', false)" class="px-1.5 py-0.5 text-[10px] font-semibold text-gray-500 hover:bg-gray-100 border border-gray-200 rounded" title="Batal centang kategori {{ $cat }}">
                                                    ✕
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <button type="button" onclick="selectAllProductsGlobal(true)" class="px-3 py-1.5 text-xs font-bold text-blue-600 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors shadow-2xs">
                            Pilih Semua
                        </button>
                        <button type="button" onclick="selectAllProductsGlobal(false)" class="px-3 py-1.5 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-2xs">
                            Batal Semua
                        </button>
                    </div>
                </div>

                <!-- Bar Filter Kategori Produk (Dinamis dari kategori produk milik mitra di database) -->
                @if($categories->isNotEmpty())
                    <div class="flex flex-col gap-2 p-3 bg-gray-50/80 rounded-xl border border-gray-200">
                        <div class="flex items-center justify-between text-xs font-bold text-gray-700">
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-funnel-fill text-blue-600"></i> Filter Kategori Produk Toko Anda:
                            </span>
                            <span class="text-[11px] font-normal text-gray-400">Klik tab kategori untuk memfilter tampilan list produk</span>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-1.5" id="category-filter-chips">
                            <!-- Chip Semua Kategori -->
                            <button type="button"
                                    onclick="filterByCategory('all', this)"
                                    class="category-chip px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow-2xs"
                                    data-category="all">
                                Semua ({{ $products->count() }})
                            </button>

                            <!-- Chip Berdasarkan Kategori yang Ada di Database Produk Mitra -->
                            @foreach($categories as $cat)
                                @php
                                    $catCount = $products->where('kategori', $cat)->count();
                                @endphp
                                <button type="button"
                                        onclick="filterByCategory('{{ addslashes($cat) }}', this)"
                                        class="category-chip px-3 py-1.5 rounded-lg text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-all"
                                        data-category="{{ $cat }}">
                                    {{ $cat }} ({{ $catCount }})
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Search & Action Toolbar untuk Kategori yang Sedang Aktif -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text"
                               id="product-search-input"
                               oninput="applyFilterSearch()"
                               placeholder="Cari nama produk di dalam list..."
                               class="w-full pl-9 pr-4 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                    </div>

                    <!-- Contextual Buttons (Pilih/Batal Produk yang Sedang Tampil/Filter Aktif) -->
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button type="button"
                                id="btn-select-filtered"
                                onclick="selectCurrentlyVisibleProducts(true)"
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="bi bi-check2-all text-sm"></i> <span id="label-btn-select-filtered">Pilih Semua (Semua)</span>
                        </button>
                        <button type="button"
                                id="btn-unselect-filtered"
                                onclick="selectCurrentlyVisibleProducts(false)"
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="bi bi-x text-sm"></i> <span id="label-btn-unselect-filtered">Batal (Semua)</span>
                        </button>
                    </div>
                </div>

                <div id="product-list-container" class="max-h-[450px] overflow-y-auto border border-gray-200 rounded-xl divide-y divide-gray-100 bg-white">
                    @foreach ($products as $prod)
                        @php
                            $hargaNormal = $prod->detailVariant ? ($prod->detailVariant->min('harga_sewa') ?? 0) : 0;
                            $isSelected = in_array($prod->id, $selectedProductIds);
                            $isLocked = !empty($prod->active_discount) && ($prod->active_discount['id_promo'] != $promo->id_promo);
                        @endphp
                        <label class="product-item-row flex items-center justify-between p-4 {{ $isLocked ? 'bg-amber-50/40 opacity-80 cursor-not-allowed border-l-4 border-l-amber-400' : 'hover:bg-gray-50/80 cursor-pointer' }} transition-colors group"
                               data-id="{{ $prod->id }}"
                               data-category="{{ $prod->kategori ?? '' }}"
                               data-name="{{ strtolower($prod->nama) }}">
                            <div class="flex items-center gap-3 min-w-0">
                                @if ($isLocked)
                                    <input type="checkbox"
                                           disabled
                                           data-category="{{ $prod->kategori ?? '' }}"
                                           class="product-checkbox-locked w-4 h-4 text-gray-400 rounded border-gray-300 cursor-not-allowed">
                                @else
                                    <input type="checkbox"
                                           name="selected_products[]"
                                           value="{{ $prod->id }}"
                                           data-category="{{ $prod->kategori ?? '' }}"
                                           data-price="{{ $hargaNormal }}"
                                           {{ $isSelected ? 'checked' : '' }}
                                           class="product-checkbox w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer"
                                           onchange="onProductCheckboxChange()">
                                @endif
                                
                                <div class="w-12 h-12 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    @if($prod->photoThumbnail)
                                        <img src="{{ asset('assets/image/customers/produk/' . $prod->photoThumbnail->url_foto) }}" class="w-full h-full object-cover" alt="{{ $prod->nama }}" onerror="this.src='{{ asset('images/placeholder-image.png') }}'">
                                    @else
                                        <i class="bi bi-box-seam text-gray-400 text-lg"></i>
                                    @endif
                                </div>

                                <div class="truncate">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors truncate">{{ $prod->nama }}</h4>
                                        @if($isLocked)
                                            <span class="inline-flex items-center gap-1 text-[10px] font-black text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full border border-amber-300 flex-shrink-0">
                                                <i class="bi bi-lock-fill text-[9px]"></i> Aktif di: {{ $prod->active_discount['nama_diskon'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="inline-block text-[11px] font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">{{ $prod->kategori ?? 'Umum' }}</span>
                                        @if($isLocked)
                                            <span class="text-[11px] text-amber-700 italic font-medium">Terkunci di promo lain.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="text-right flex-shrink-0 ml-4">
                                <span class="text-xs text-gray-400 block">Harga Normal: Rp {{ number_format($hargaNormal, 0, ',', '.') }}</span>
                                <span class="text-xs font-black {{ $isLocked ? 'text-amber-700' : 'text-emerald-600' }} block preview-discounted-price" data-original="{{ $hargaNormal }}">
                                    @if($isLocked)
                                        Saat Ini: Rp {{ number_format($hargaNormal, 0, ',', '.') }}
                                    @else
                                        Setelah Diskon: Rp {{ number_format($hargaNormal, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                        </label>
                    @endforeach

                    <!-- Empty state bila tidak ada produk yang cocok dengan pencarian / kategori -->
                    <div id="filter-empty-state" class="hidden p-8 text-center text-gray-400">
                        <i class="bi bi-search text-2xl mb-1 block"></i>
                        <p class="text-xs font-bold text-gray-600">Tidak ada produk yang cocok dengan filter atau pencarian</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Jadwal & Status -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-100 shadow-sm flex flex-col gap-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
                    <i class="bi bi-calendar-check text-blue-600"></i> Jadwal Berlaku & Status Diskon
                </h2>
                <p class="text-xs text-gray-500 mt-0.5 font-medium">Tentukan rentang tanggal aktif dan status publikasi program diskon.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal Mulai -->
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-bold text-gray-800 mb-1">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local"
                           name="tanggal_mulai"
                           id="tanggal_mulai"
                           value="{{ old('tanggal_mulai', $promo->tanggal_mulai ? $promo->tanggal_mulai->format('Y-m-d\TH:i') : '') }}"
                           required
                           class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none shadow-2xs">
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-bold text-gray-800 mb-1">
                        Tanggal Berakhir <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local"
                           name="tanggal_selesai"
                           id="tanggal_selesai"
                           value="{{ old('tanggal_selesai', $promo->tanggal_selesai ? $promo->tanggal_selesai->format('Y-m-d\TH:i') : '') }}"
                           required
                           class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none shadow-2xs">
                </div>
            </div>

            <!-- Status Diskon -->
            <div>
                <label for="status" class="block text-sm font-bold text-gray-800 mb-1">
                    Status Program Diskon <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status" class="block w-full sm:w-64 border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none shadow-2xs">
                    <option value="active" {{ old('status', $promo->status) == 'active' ? 'selected' : '' }}>Langsung Aktifkan (Published)</option>
                    <option value="draft" {{ old('status', $promo->status) == 'draft' ? 'selected' : '' }}>Simpan Sebagai Draft</option>
                    <option value="inactive" {{ old('status', $promo->status) == 'inactive' ? 'selected' : '' }}>Nonaktifkan</option>
                </select>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
            <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg transition-all duration-200">
                <i class="bi bi-check-circle-fill text-base"></i> Simpan Pembaruan Diskon
            </button>
            <a href="{{ route('menu-promo.index', ['id_user' => $id_user]) }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 border border-gray-300 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 transition-colors text-center">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    let currentTipe = '{{ $promo->tipe_diskon }}';

    function handleTipeChange(tipe) {
        currentTipe = tipe;
        const inputNilai = document.getElementById('nilai_diskon');
        const labelNilai = document.getElementById('label-nilai-diskon');
        const prefix = document.getElementById('prefix-addon');
        const suffix = document.getElementById('suffix-addon');
        const labelPersen = document.getElementById('label-tipe-persen');
        const labelNominal = document.getElementById('label-tipe-nominal');

        if (tipe === 'persen') {
            labelPersen.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-blue-500 bg-blue-50/40';
            labelNominal.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-gray-200 hover:bg-gray-50';
            labelNilai.innerText = 'Besaran Diskon (%)';
            prefix.classList.add('hidden');
            suffix.classList.remove('hidden');
            inputNilai.max = 100;
            inputNilai.placeholder = '10';
            inputNilai.classList.remove('pl-12');
            inputNilai.classList.add('px-4');
        } else {
            labelPersen.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-gray-200 hover:bg-gray-50';
            labelNominal.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-blue-500 bg-blue-50/40';
            labelNilai.innerText = 'Nominal Potongan Harga (Rp)';
            prefix.classList.remove('hidden');
            suffix.classList.add('hidden');
            inputNilai.removeAttribute('max');
            inputNilai.placeholder = '25000';
            inputNilai.classList.remove('px-4');
            inputNilai.classList.add('pl-12');
        }

        recalculateDiscountPreview();
    }

    function handleCakupanChange(cakupan) {
        const selectorContainer = document.getElementById('product-selector-container');
        const konflikContainer = document.getElementById('konflik-diskon-container');
        const labelAll = document.getElementById('label-cakupan-all');
        const labelSelected = document.getElementById('label-cakupan-selected');

        if (cakupan === 'selected') {
            selectorContainer.classList.remove('hidden');
            if (konflikContainer) konflikContainer.classList.add('hidden');
            labelAll.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-gray-200 hover:bg-gray-50';
            labelSelected.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-blue-500 bg-blue-50/40';
        } else {
            selectorContainer.classList.add('hidden');
            if (konflikContainer) konflikContainer.classList.remove('hidden');
            labelAll.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-blue-500 bg-blue-50/40';
            labelSelected.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-gray-200 hover:bg-gray-50';
        }
    }

    function handleKonflikChange(konflik) {
        const labelReplace = document.getElementById('label-konflik-replace');
        const labelSkip = document.getElementById('label-konflik-skip');

        if (konflik === 'replace') {
            labelReplace.className = 'flex items-start gap-3 p-3.5 bg-white rounded-xl border-2 border-blue-500 cursor-pointer transition-all shadow-xs';
            labelSkip.className = 'flex items-start gap-3 p-3.5 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition-all shadow-xs';
        } else {
            labelReplace.className = 'flex items-start gap-3 p-3.5 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition-all shadow-xs';
            labelSkip.className = 'flex items-start gap-3 p-3.5 bg-white rounded-xl border-2 border-blue-500 cursor-pointer transition-all shadow-xs';
        }
    }

    let currentCategoryFilter = 'all';

    function toggleCategoryDropdown() {
        const menu = document.getElementById('dropdown-category-menu');
        if (menu) menu.classList.toggle('hidden');
    }

    // Tutup dropdown jika user klik di luar area dropdown
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('dropdown-category-wrapper');
        const menu = document.getElementById('dropdown-category-menu');
        if (wrapper && menu && !wrapper.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    function filterByCategory(cat, btnElement) {
        currentCategoryFilter = cat;

        // Reset semua style chip kategori
        const chips = document.querySelectorAll('.category-chip');
        chips.forEach(chip => {
            chip.className = 'category-chip px-3 py-1.5 rounded-lg text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-all';
        });

        // Aktifkan style chip yang dipilih
        if (btnElement) {
            btnElement.className = 'category-chip px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow-2xs';
        }

        // Perbarui label tombol aksi kontekstual
        const labelSelect = document.getElementById('label-btn-select-filtered');
        const labelUnselect = document.getElementById('label-btn-unselect-filtered');
        if (cat === 'all') {
            if (labelSelect) labelSelect.innerText = 'Pilih Semua (Semua)';
            if (labelUnselect) labelUnselect.innerText = 'Batal (Semua)';
        } else {
            if (labelSelect) labelSelect.innerText = 'Pilih Semua di "' + cat + '"';
            if (labelUnselect) labelUnselect.innerText = 'Batal di "' + cat + '"';
        }

        applyFilterSearch();
    }

    function applyFilterSearch() {
        const searchInput = document.getElementById('product-search-input');
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const rows = document.querySelectorAll('.product-item-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowCat = row.getAttribute('data-category') || '';
            const rowName = row.getAttribute('data-name') || '';

            const matchCat = (currentCategoryFilter === 'all') || (rowCat === currentCategoryFilter);
            const matchSearch = (!query) || rowName.includes(query);

            if (matchCat && matchSearch) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        const emptyState = document.getElementById('filter-empty-state');
        if (emptyState) {
            if (visibleCount === 0 && rows.length > 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }
    }

    function selectCurrentlyVisibleProducts(checked) {
        // Pilih atau batalkan hanya produk yang SEDANG TAMPIL (sesuai filter kategori/pencarian) dan TIDAK TERKUNCI
        const rows = document.querySelectorAll('.product-item-row:not(.hidden)');
        rows.forEach(row => {
            const cb = row.querySelector('.product-checkbox');
            if (cb) {
                cb.checked = checked;
            }
        });
        updateSelectedCounter();
        recalculateDiscountPreview();
    }

    function selectCategoryDirect(cat, checked) {
        // Centang/batalkan langsung seluruh produk kategori tertentu yang tidak terkunci
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(cb => {
            const cbCat = cb.getAttribute('data-category') || '';
            if (cbCat === cat) {
                cb.checked = checked;
            }
        });
        updateSelectedCounter();
        recalculateDiscountPreview();
        const menu = document.getElementById('dropdown-category-menu');
        if (menu) menu.classList.add('hidden');
    }

    function selectAllProductsGlobal(checked) {
        // Centang/batalkan seluruh produk di toko (hanya yang tidak terkunci)
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(cb => cb.checked = checked);
        updateSelectedCounter();
        recalculateDiscountPreview();
    }

    function onProductCheckboxChange() {
        updateSelectedCounter();
        recalculateDiscountPreview();
    }

    function updateSelectedCounter() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const countNumber = document.getElementById('selected-count-number');
        if (countNumber) {
            countNumber.innerText = checkedBoxes.length;
        }
    }

    function recalculateDiscountPreview() {
        const inputNilai = document.getElementById('nilai_diskon');
        const badgePreview = document.getElementById('badge-diskon-preview');
        const val = parseFloat(inputNilai.value) || 0;

        if (currentTipe === 'persen') {
            badgePreview.innerText = 'Diskon: ' + val + '% dari harga sewa';
        } else {
            badgePreview.innerText = 'Potongan Tetap: Rp ' + new Intl.NumberFormat('id-ID').format(val) + ' per produk';
        }

        const previewItems = document.querySelectorAll('.preview-discounted-price');
        previewItems.forEach(el => {
            const original = parseFloat(el.getAttribute('data-original')) || 0;
            let discounted = original;
            if (currentTipe === 'persen') {
                discounted = Math.max(0, original - Math.round((original * val) / 100));
            } else {
                discounted = Math.max(0, original - val);
            }
            el.innerText = 'Setelah Diskon: Rp ' + new Intl.NumberFormat('id-ID').format(discounted);
        });
    }

    updateSelectedCounter();
    recalculateDiscountPreview();
</script>
@endsection
