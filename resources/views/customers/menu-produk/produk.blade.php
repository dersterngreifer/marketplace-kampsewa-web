@extends('layouts.customers.layouts-customer')
@section('customer-content')
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-8">
        
        <!-- Header -->
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Manajemen Produk Anda!</h1>
            <p class="text-gray-500 font-medium text-sm sm:text-base max-w-4xl">
                Halaman ini berisi data produk Anda. Anda bisa menambah, mengedit, dan menghapus produk,
                melihat produk yang sedang disewa, mengurutkan berdasarkan produk terlaris, harga, maupun waktu pembuatan. 
                Jika bingung, lihat <a href="#" class="text-blue-600 hover:text-blue-700 font-bold hover:underline transition-all">Dokumentasi</a>.
            </p>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex overflow-x-auto hide-scrollbar border-b border-gray-200 pb-px">
            <nav class="flex gap-6" aria-label="Tabs">
                <a href="{{ route('menu-produk.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                   class="{{ $title == 'Produk Menu | KampSewa' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm lg:text-[15px] transition-colors">
                    Semua Produk
                </a>
                <a href="{{ route('menu-produk.kelola-produk', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm lg:text-[15px] transition-colors">
                    Kelola Produk
                </a>
                <a href="{{ route('menu-produk.sedang-disewa', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm lg:text-[15px] transition-colors">
                    Sedang Disewa
                </a>
            </nav>
        </div>

        <!-- Main Content (Filter + Grid) -->
        <div class="flex flex-col lg:flex-row gap-8 items-start relative">
            
            <!-- Sidebar Filter -->
            <div class="w-full lg:w-1/4 flex-shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-8 z-10">
                <form id="formSide" method="GET" class="flex flex-col gap-6">
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="bi bi-funnel"></i> Filter Pencarian
                        </h3>
                    </div>

                    <!-- Search -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-gray-700">Nama Produk</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-search text-gray-400"></i>
                            </div>
                            <input name="search" value="{{ $search }}"
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                type="search" placeholder="Cari nama produk...">
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-gray-700">Kategori</label>
                        <div class="relative">
                            <select name="filter_side" class="w-full pl-4 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none cursor-pointer transition-all">
                                <option value="" {{ empty($filter_side) ? 'selected' : '' }}>Semua Kategori</option>
                                @foreach ($user_categories as $kategori)
                                    <option value="{{ strtolower($kategori) }}" {{ strtolower($filter_side) == strtolower($kategori) ? 'selected' : '' }}>
                                        {{ ucfirst($kategori) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button id="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-sm hover:shadow-md transition-all focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center gap-2">
                            <i class="bi bi-check2-circle"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Product Grid -->
            <div class="w-full lg:w-3/4 flex flex-col gap-5">
                
                <!-- Toolbar Area -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-2xl shadow-sm border border-gray-100 p-3 px-5">
                    <p class="text-sm font-bold text-gray-700">Menampilkan <span class="text-blue-600">{{ $produk->total() }}</span> Hasil Produk</p>
                    
                    <form id="filterFormRight" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                        <span class="text-sm text-gray-500 hidden sm:inline">Urutkan:</span>
                        <div class="relative w-full sm:w-48">
                            <select name="filter_right" id="filterRight" class="w-full pl-3 pr-8 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none cursor-pointer">
                                <option value="terbaru" {{ $filter_right == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" {{ $filter_right == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                <option value="termahal" {{ $filter_right == 'termahal' ? 'selected' : '' }}>Harga Tertinggi</option>
                                <option value="termurah" {{ $filter_right == 'termurah' ? 'selected' : '' }}>Harga Terendah</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2.5 pointer-events-none text-gray-400">
                                <i class="bi bi-sort-down text-sm"></i>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Products -->
                @if ($produk->count() == 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 flex flex-col items-center justify-center min-h-[400px]">
                        <img class="w-48 h-auto object-cover opacity-80 mb-6" src="{{ asset('images/illustration/filling-survey.png') }}" alt="Not Found">
                        <h3 class="text-2xl font-black text-gray-800 mb-2">OOPS! Tidak Ada Produk</h3>
                        <p class="text-gray-500 text-center max-w-md font-medium">Sepertinya kata kunci "<span class="text-gray-900 font-bold">{{ $search }}</span>" tidak ditemukan dalam daftar produk Anda. Silakan coba dengan kata kunci lain.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        @foreach ($produk as $item)
                            <a href="{{ route('menu-produk.detail-produk', ['id_produk' => Crypt::encrypt($item->id_produk)]) }}" class="group block h-full">
                                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 h-full flex flex-col group-hover:-translate-y-1">
                                    <!-- Image Container -->
                                    <div class="relative w-full aspect-square overflow-hidden bg-gray-50">
                                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($item) }}"
                                            alt="{{ $item->nama_produk }}"
                                            onerror="this.onerror=null;this.src='{{ asset('images/illustration/filling-survey.png') }}';">
                                        
                                        <!-- Overlay gradient & Category Badge -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        <div class="absolute top-3 left-3">
                                            <span class="px-2.5 py-1 text-[10px] sm:text-xs font-bold uppercase tracking-wider bg-white/90 backdrop-blur-sm text-gray-800 rounded-lg shadow-sm">
                                                {{ $item->kategori_produk }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-4 flex flex-col flex-grow justify-between gap-3">
                                        <div>
                                            <h3 class="text-sm sm:text-base font-bold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors leading-tight">
                                                {{ $item->nama_produk }}
                                            </h3>
                                        </div>
                                        
                                        <div class="flex flex-col gap-2 mt-auto">
                                            <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500">
                                                <i class="bi bi-box-seam text-gray-400"></i>
                                                <span>Stok: <strong class="text-gray-700">{{ $item->stok_produk }}</strong></span>
                                            </div>
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-[13px] sm:text-sm font-black text-orange-500">
                                                    Rp {{ number_format($item->harga_sewa_min, 0, ',', '.') }}<span class="text-[10px] text-gray-400 font-medium">/hari</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $produk->onEachSide(1)->links('components.paginate.custom-pagination') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            var searchValue = "{{ $search }}";
            var filterSideValue = "{{ $filter_side }}";

            // Sidebar Submit
            document.getElementById('submit').addEventListener('click', function(event){
                event.preventDefault();
                var formSide = document.getElementById('formSide');
                var filterRightValue = document.getElementById('filterRight').value;

                var urlParams = new URLSearchParams(window.location.search);
                urlParams.set('filter_right', filterRightValue);

                var formData = new FormData(formSide);
                formData.forEach((value, key) => {
                    urlParams.set(key, value);
                });

                window.location.href = window.location.pathname + '?' + urlParams.toString();
            });

            // Sorting Right change
            document.getElementById('filterRight').addEventListener('change', function() {
                var filterRightValue = this.value;
                var urlParams = new URLSearchParams(window.location.search);
                urlParams.set('filter_right', filterRightValue);
                if (searchValue) urlParams.set('search', searchValue);
                if (filterSideValue) urlParams.set('filter_side', filterSideValue);

                window.location.href = window.location.pathname + '?' + urlParams.toString();
            });
        });
    </script>
@endsection
