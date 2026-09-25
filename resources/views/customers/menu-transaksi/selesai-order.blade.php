@extends('layouts.customers.layouts-customer')
@section('customer-content')
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-8">
        <!-- Header & Tabs -->
        <div class="flex flex-col gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Manajemen Transaksi</h1>
                <p class="text-gray-500 font-medium mt-1">Kelola semua pesanan masuk, penyewaan aktif, dan riwayat transaksi toko Anda.</p>
            </div>
            
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-gray-200 pb-4 lg:pb-0">
                <!-- Tabs -->
                <nav class="flex overflow-x-auto hide-scrollbar gap-6" aria-label="Tabs">
                    <a href="{{ route('menu-transaksi.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                       class="{{ $title === 'Order Masuk' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm lg:text-[15px] transition-colors">
                        Order Masuk
                    </a>
                    <a href="{{ route('menu-transaksi.sewa-berlangsung', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                       class="{{ $title === 'Sewa Berlangsung' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm lg:text-[15px] transition-colors">
                        Sewa Berlangsung
                    </a>
                    <a href="{{ route('menu-transaksi.order-selesai', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" 
                       class="{{ $title === 'Order Selesai' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm lg:text-[15px] transition-colors">
                        Selesai
                    </a>
                </nav>

                <!-- Filters -->
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <form method="GET" class="w-full sm:w-auto">
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-search text-gray-400"></i>
                            </div>
                            <input type="search" value="{{ $search }}" name="search"
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white shadow-sm transition-shadow"
                                placeholder="Cari nama penyewa..." id="exampleFormControlInput3" />
                        </div>
                    </form>
                    
                    <form method="GET" id="form-filter-order-selesai" class="w-full sm:w-auto">
                        <div class="relative">
                            <select name="filter-order-selesai" id="filter-order-selesai"
                                class="block w-full sm:w-48 pl-3 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white shadow-sm cursor-pointer appearance-none">
                                <option value="Semua">Semua Status</option>
                                <option value="Pengembalian">Belum Dikonfirmasi</option>
                                <option value="Selesai">Sudah Dikonfirmasi</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Alert Info -->
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex gap-3 shadow-sm">
            <div class="text-emerald-500 mt-0.5"><i class="bi bi-info-circle-fill text-lg"></i></div>
            <div>
                <h4 class="text-sm font-bold text-emerald-900">Riwayat & Pengembalian</h4>
                <p class="text-[13px] font-medium text-emerald-700 mt-1">Silakan pilih tombol <strong>ACC</strong> untuk menyetujui pelanggan yang melakukan pengembalian barang. Anda juga bisa menggunakan filter <strong>Belum Dikonfirmasi</strong> untuk mempermudah pencarian.</p>
            </div>
        </div>

        <!-- Table Data -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Penyewa</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Durasi Sewa</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pembayaran</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($data as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <img class="w-10 h-10 rounded-full object-cover border border-gray-200"
                                            src="@userPhoto($item->foto_users)" alt="{{ $item->nama_penyewa }}">
                                        <div class="font-bold text-gray-900 text-sm">{{ $item->nama_penyewa }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <img class="w-12 h-12 rounded-xl object-cover border border-gray-200 shadow-sm"
                                            src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($item) }}" alt="{{ $item->nama }}">
                                        <div class="text-sm font-bold text-gray-800 max-w-[200px] truncate" title="{{ $item->nama }}">{{ $item->nama }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs font-bold text-gray-800"><i class="bi bi-calendar-event text-blue-500 mr-1"></i> {{ Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</span>
                                        <span class="text-xs font-medium text-gray-500 pl-4">s/d {{ Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-bold {{ $item->status_pembayaran == 'Lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $item->status_pembayaran }}
                                        </span>
                                        <span class="text-[11px] font-medium text-gray-500 ml-1 border-b border-gray-300 border-dashed pb-0.5">Via {{ $item->metode }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $item->status_penyewaan == 'Pengembalian' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }} shadow-sm flex items-center gap-1.5 w-fit">
                                        @if ($item->status_penyewaan == 'Pengembalian')
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Belum Dikonfirmasi
                                        @else
                                            <i class="bi bi-check2-all text-[14px]"></i> Selesai
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if ($item->status_penyewaan == 'Pengembalian')
                                        <a href="{{ route('menu-transaksi.terima-order-masuk', ['id_penyewaan' => Crypt::encrypt($item->id_penyewaan)]) }}"
                                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 hover:shadow-md hover:-translate-y-0.5 transition-all focus:ring-2 focus:ring-amber-500 focus:ring-offset-1">
                                            <i class="bi bi-clipboard-check"></i> ACC
                                        </a>
                                    @else
                                        <a href="{{ route('menu-transaksi.terima-order-masuk', ['id_penyewaan' => Crypt::encrypt($item->id_penyewaan)]) }}"
                                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 hover:shadow-sm hover:-translate-y-0.5 transition-all focus:ring-2 focus:ring-gray-300 focus:ring-offset-1">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-check-circle text-2xl text-gray-400"></i>
                                        </div>
                                        <h4 class="text-base font-bold text-gray-700 mb-1">Belum ada riwayat selesai</h4>
                                        <p class="text-sm text-gray-500">Transaksi yang sudah selesai akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var filterOrderSelesai = "{{ request()->input('filter-order-selesai') }}";
            var searchQuery = "{{ request()->input('search') }}";

            if (filterOrderSelesai) {
                document.getElementById('filter-order-selesai').value = filterOrderSelesai;
            }

            if (searchQuery) {
                document.querySelector('input[name="search"]').value = searchQuery;
            }

            document.getElementById('filter-order-selesai').addEventListener('change', submitForm);

            function submitForm() {
                var filterOrderSelesai = document.getElementById('filter-order-selesai').value;
                var search = document.querySelector('input[name="search"]').value;

                var url = window.location.pathname + '?'; 
                if (filterOrderSelesai) {
                    url += 'filter-order-selesai=' + encodeURIComponent(filterOrderSelesai) + '&';
                }
                if (search) {
                    url += 'search=' + encodeURIComponent(search);
                }

                window.location.href = url;
            }

            document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitForm();
                }
            });
        });
    </script>
@endsection
