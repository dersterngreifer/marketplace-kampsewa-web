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

        {{-- Grid Daftar Produk Sedang Disewa --}}
        <div class="--list-sedang-disewa w-full mt-2">
            @if (isset($sedang_disewa) && $sedang_disewa->count() > 0)
                <div class="grid grid-cols-4 gap-6 small-desktop:grid-cols-3 mobile-max:grid-cols-1">
                    @foreach ($sedang_disewa as $item)
                        @php
                            $tgl_mulai = \Carbon\Carbon::parse($item->tanggal_mulai);
                            $tgl_selesai = \Carbon\Carbon::parse($item->tanggal_selesai);
                            $durasi = max(1, $tgl_mulai->diffInDays($tgl_selesai));
                        @endphp
                        <div class="--card bg-white rounded-[15px] shadow-box-shadow-8 border border-gray-100 overflow-hidden flex flex-col justify-between hover:shadow-lg transition duration-200">
                            <div class="--image-wrapper relative">
                                <img src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($item) }}" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'" alt="{{ $item->nama }}" class="w-full h-[180px] object-cover" />
                                <span class="absolute top-3 right-3 bg-[#FFCE56] text-gray-900 text-[11px] font-bold px-3 py-1 rounded-full shadow-sm">
                                    Sedang Disewa
                                </span>
                            </div>
                            <div class="p-4 flex flex-col gap-3 flex-grow justify-between">
                                <div>
                                    <h3 class="text-[16px] font-bold text-gray-800 line-clamp-1 mb-1">{{ $item->nama }}</h3>
                                    <p class="text-[12px] text-gray-500 line-clamp-1">Kode: {{ $item->kode_produk }} | Qty: <strong class="text-gray-700">{{ $item->qty_disewa }} pcs</strong></p>
                                    @if($item->warna_produk || $item->ukuran)
                                        <p class="text-[11px] text-gray-400 mt-0.5">Varian: {{ $item->warna_produk ?? '-' }} / {{ $item->ukuran ?? '-' }}</p>
                                    @endif
                                </div>
                                
                                <div class="--penyewa-info bg-gray-50 p-3 rounded-[10px] border border-gray-100 flex items-center justify-between mt-2">
                                    <div class="flex items-center gap-2.5">
                                        <img class="w-[36px] h-[36px] rounded-full object-cover border border-gray-200" src="{{ asset('assets/image/customers/profile/' . ($item->foto_penyewa ?: 'default.png')) }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($item->nama_penyewa) }}&background=random'" alt="">
                                        <div>
                                            <p class="text-[13px] font-bold text-gray-800 line-clamp-1">{{ $item->nama_penyewa }}</p>
                                            <p class="text-[11px] text-gray-500">Penyewa</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[12px] font-bold text-blue-600 block">{{ $durasi }} Hari</span>
                                        <span class="text-[10px] text-gray-400">Durasi</span>
                                    </div>
                                </div>

                                <div class="--footer-card pt-3 border-t border-gray-100 flex items-center justify-between text-[12px]">
                                    <span class="text-gray-500 font-medium">Selesai pada:</span>
                                    <span class="font-bold text-red-600">{{ $tgl_selesai->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 w-full flex justify-center">
                    {{ $sedang_disewa->links() }}
                </div>
            @else
                <div class="w-full bg-white rounded-[15px] p-12 text-center shadow-box-shadow-8 border border-gray-100 flex flex-col items-center justify-center gap-3">
                    <div class="w-16 h-16 bg-yellow-50 text-yellow-500 rounded-full flex items-center justify-center text-2xl font-bold">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h3 class="text-[18px] font-bold text-gray-700">Belum Ada Produk Sedang Disewa</h3>
                    <p class="text-[14px] text-gray-500 max-w-md">Saat ini tidak ada produk Anda yang sedang berstatus aktif disewa oleh pelanggan.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
