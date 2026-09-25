@extends('layouts.customers.layouts-customer')
@section('customer-content')
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-8">
        <div class="flex flex-col gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Manajemen Produk Anda!</h1>
                <p class="text-gray-500 font-medium mt-1">Halaman ini berisi data produk Anda. Anda bisa menambah, mengedit, menghapus, atau melihat produk yang sedang disewa.</p>
            </div>
            
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-gray-200 pb-4 lg:pb-0">
                <!-- Tabs -->
                <nav class="flex overflow-x-auto hide-scrollbar gap-6" aria-label="Tabs">
                    <a href="{{ route('menu-produk.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                       class="{{ $title === 'Produk Menu | KampSewa' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm lg:text-[15px] transition-colors">
                        Semua Produk
                    </a>
                    <a href="{{ route('menu-produk.kelola-produk', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                       class="{{ $title === 'Kelola Produk | KampSewa' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm lg:text-[15px] transition-colors">
                        Kelola Produk
                    </a>
                    <a href="{{ route('menu-produk.sedang-disewa', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                       class="{{ $title === 'Sedang Disewa | KampSewa' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm lg:text-[15px] transition-colors">
                        Sedang Disewa
                    </a>
                </nav>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-2">
            <div class="text-gray-900 font-bold text-sm">{{ $total_produk }} Produk Total</div>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <form method="GET" class="w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-search text-gray-400"></i>
                        </div>
                        <input type="search" value="{{ $search }}" name="search"
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white shadow-sm transition-shadow"
                            placeholder="Cari produk..." />
                    </div>
                </form>
                <a href="{{ route('menu-produk.tambah-produk', ['id_user' => Crypt::encrypt(session('id_user'))]) }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 hover:shadow-md transition-all text-sm">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Produk
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full flex flex-col">
            @if (!is_object($produk) || $produk->isEmpty())
                <div class="p-12 flex flex-col items-center justify-center min-h-[400px]">
                    <img class="w-48 h-auto object-cover opacity-80 mb-6" src="{{ asset('images/illustration/filling-survey.png') }}" alt="Not Found">
                    <h3 class="text-2xl font-black text-gray-800 mb-2">OOPS! Tidak Ada Produk</h3>
                    <p class="text-gray-500 text-center max-w-md font-medium">Sepertinya kata kunci "<span class="text-gray-900 font-bold">{{ $search }}</span>" tidak ditemukan dalam daftar produk Anda.</p>
                </div>
            @else
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-max text-sm text-left">
                        <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-bold">
                            <tr>
                                <th scope="col" class="px-6 py-4 rounded-tl-xl">Produk</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4">Stok</th>
                                <th scope="col" class="px-6 py-4 rounded-tr-xl text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 bg-white">
                            @foreach ($produk as $item)
                                <tr class="hover:bg-blue-50/30 transition-colors duration-200 group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="relative w-14 h-14 rounded-xl overflow-hidden border border-gray-100 shadow-sm group-hover:shadow transition-all">
                                                <img class="w-full h-full object-cover"
                                                    src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($item) }}"
                                                    alt="{{ $item->nama_produk }}"
                                                    onerror="this.onerror=null;this.src='{{ asset('images/illustration/filling-survey.png') }}';">
                                            </div>
                                            <div class="flex flex-col">
                                                <a href="{{ route('menu-produk.detail-produk', ['id_produk' => Crypt::encrypt($item->id_produk)]) }}" class="font-bold text-gray-900 text-[15px] hover:text-blue-600 transition-colors line-clamp-1 capitalize">
                                                    {{ $item->nama_produk }}
                                                </a>
                                                <span class="text-xs font-medium text-gray-400 mt-0.5">ID: #{{ substr(md5($item->id_produk), 0, 8) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(strtolower($item->status_produk) == 'tersedia')
                                            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                {{ $item->status_produk }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-orange-50 text-orange-600 border border-orange-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                                {{ $item->status_produk }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-700 font-bold border border-gray-100">
                                                {{ $item->stok_produk ?? 0 }}
                                            </div>
                                            <span class="text-xs font-medium text-gray-500">Unit</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('menu-produk.update-produk', ['id_produk' => Crypt::encrypt($item->id_produk), 'id_user' => Crypt::encrypt($item->id_user)]) }}" 
                                               class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow group/btn relative"
                                               title="Edit Produk">
                                                <i class="bi bi-pencil-square text-[15px]"></i>
                                            </a>
                                            
                                            <form id="delete-produk-{{ $item->id_produk }}"
                                                action="{{ route('menu-produk.delete', ['id_produk' => $item->id_produk]) }}"
                                                method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button id="delete-product-{{ $item->id_produk }}" type="button" 
                                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow"
                                                        title="Hapus Produk">
                                                    <i class="bi bi-trash3 text-[15px]"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            
            @if(is_object($produk) && !$produk->isEmpty())
                <div class="p-5 border-t border-gray-100 bg-gray-50/30 rounded-b-2xl">
                    {{ $produk->onEachSide(1)->links('components.paginate.custom-pagination') }}
                </div>
            @endif
        </div>
    <script>
        document.querySelectorAll('[id^="delete-product-"]').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Apakah sudah yakin?',
                    text: "Anda akan menghapus produk ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const productId = this.id.replace('delete-product-', '');
                        document.getElementById('delete-produk-' + productId).submit();
                    } else {
                        Swal.fire('Cancelled', 'Penghapusan dibatalkan', 'info');
                    }
                });
            });
        });
    </script>
@endsection
