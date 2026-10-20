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

        {{-- Banner Alert Perlu Revisi --}}
        @if (($total_suspend_perlu_revisi ?? 0) > 0)
            <div class="w-full rounded-2xl border-2 border-rose-300 bg-rose-50/90 p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="bi bi-exclamation-octagon text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-rose-950">Ada {{ $total_suspend_perlu_revisi }} Produk Anda yang Ditangguhkan (Suspend)</h4>
                        <p class="text-xs text-rose-700 mt-0.5">Produk sementara disembunyikan dari publik. Silakan klik tombol <b>Edit / Perbaiki</b> untuk membaca instruksi revisi dari admin dan memperbarui data produk.</p>
                    </div>
                </div>
                <a href="{{ route('menu-produk.kelola-produk', ['id_user' => Crypt::encrypt($id_user_raw ?? session('id_user')), 'filter_status' => 'ditangguhkan']) }}"
                    class="rounded-xl bg-rose-600 text-white px-4 py-2 text-xs font-bold hover:bg-rose-700 transition shadow-sm shrink-0">
                    Lihat Produk Ditangguhkan
                </a>
            </div>
        @endif

        {{-- Banner Alert Sudah Direvisi & Menunggu Verifikasi --}}
        @if (($total_suspend_direvisi ?? 0) > 0)
            <div class="w-full rounded-2xl border-2 border-emerald-300 bg-emerald-50/90 p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="bi bi-patch-check text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-emerald-950">Ada {{ $total_suspend_direvisi }} Produk yang Sudah Direvisi & Menunggu Verifikasi</h4>
                        <p class="text-xs text-emerald-800 mt-0.5">Perbaikan produk Anda telah tersimpan dan Admin KampSewa telah diberi notifikasi untuk meninjau dan memulihkan produk Anda.</p>
                    </div>
                </div>
                <a href="{{ route('menu-produk.kelola-produk', ['id_user' => Crypt::encrypt($id_user_raw ?? session('id_user')), 'filter_status' => 'direvisi']) }}"
                    class="rounded-xl bg-emerald-600 text-white px-4 py-2 text-xs font-bold hover:bg-emerald-700 transition shadow-sm shrink-0">
                    Lihat Produk Direvisi
                </a>
            </div>
        @endif

        {{-- Quick Filter Status Tabs --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('menu-produk.kelola-produk', ['id_user' => Crypt::encrypt($id_user_raw ?? session('id_user')), 'filter_status' => 'semua', 'search' => $search]) }}"
               class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-bold transition {{ ($filter_status ?? 'semua') === 'semua' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                <span>Semua Produk</span>
                <span class="rounded-full bg-black/10 px-2 py-0.5 text-[10px]">{{ $total_semua ?? $total_produk }}</span>
            </a>

            <a href="{{ route('menu-produk.kelola-produk', ['id_user' => Crypt::encrypt($id_user_raw ?? session('id_user')), 'filter_status' => 'tersedia', 'search' => $search]) }}"
               class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-bold transition {{ ($filter_status ?? '') === 'tersedia' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-emerald-700 border border-emerald-200 hover:bg-emerald-50' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Tersedia (Aktif)</span>
                <span class="rounded-full bg-emerald-100 text-emerald-800 px-2 py-0.5 text-[10px] font-black">{{ $total_tersedia ?? 0 }}</span>
            </a>

            @if (($total_suspend ?? 0) > 0)
                <a href="{{ route('menu-produk.kelola-produk', ['id_user' => Crypt::encrypt($id_user_raw ?? session('id_user')), 'filter_status' => 'ditangguhkan', 'search' => $search]) }}"
                   class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-bold transition {{ ($filter_status ?? '') === 'ditangguhkan' || ($filter_status ?? '') === 'suspend' ? 'bg-rose-600 text-white shadow-sm ring-2 ring-rose-300' : 'bg-white text-rose-700 border border-rose-200 hover:bg-rose-50' }}">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                    <span>Ditangguhkan</span>
                    <span class="rounded-full bg-rose-100 text-rose-800 px-2 py-0.5 text-[10px] font-black">{{ $total_suspend ?? 0 }}</span>
                </a>
            @endif

            @if (($total_suspend_direvisi ?? 0) > 0)
                <a href="{{ route('menu-produk.kelola-produk', ['id_user' => Crypt::encrypt($id_user_raw ?? session('id_user')), 'filter_status' => 'direvisi', 'search' => $search]) }}"
                   class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-bold transition {{ ($filter_status ?? '') === 'direvisi' ? 'bg-emerald-700 text-white shadow-sm ring-2 ring-emerald-300' : 'bg-white text-emerald-700 border border-emerald-200 hover:bg-emerald-50' }}">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Sudah Direvisi</span>
                    <span class="rounded-full bg-emerald-100 text-emerald-800 px-2 py-0.5 text-[10px] font-black">{{ $total_suspend_direvisi ?? 0 }}</span>
                </a>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-2">
            <div class="text-gray-900 font-bold text-sm">{{ $total_produk }} Produk Ditampilkan</div>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <form method="GET" class="w-full sm:w-auto flex items-center gap-2">
                    @if(!empty($filter_status) && $filter_status !== 'semua')
                        <input type="hidden" name="filter_status" value="{{ $filter_status }}">
                    @endif
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
                                                @php
                                                    $fotoFirst = ($item->foto && $item->foto->count() > 0) ? $item->foto->first() : null;
                                                    $rawFoto = $fotoFirst->url_foto ?? ($item->foto_product ?? ($item->url_foto ?? null));
                                                    if (empty($rawFoto) && !empty($item->id_produk)) {
                                                        $dbFoto = \App\Models\FotoProduk::where('id_produk', $item->id_produk)->orderBy('urutan', 'asc')->first();
                                                        $rawFoto = $dbFoto ? $dbFoto->url_foto : null;
                                                    }

                                                    if (!empty($rawFoto)) {
                                                        if (preg_match('/^(https?:\/\/|\/\/)/i', $rawFoto)) {
                                                            // Jika dari database berbentuk URL eksternal
                                                            $fotoSrc = $rawFoto;
                                                        } else {
                                                            // Jika berupa path/nama file di local server public/assets/image/customers/produk
                                                            $cleanPath = ltrim(str_replace('\\', '/', $rawFoto), '/');
                                                            if (str_starts_with($cleanPath, 'public/')) {
                                                                $cleanPath = substr($cleanPath, 7);
                                                            }
                                                            if (str_starts_with($cleanPath, 'assets/image/customers/produk/')) {
                                                                $fotoSrc = asset($cleanPath);
                                                            } elseif (str_starts_with($cleanPath, 'assets/') || str_starts_with($cleanPath, 'images/')) {
                                                                $fotoSrc = asset($cleanPath);
                                                            } else {
                                                                $fotoSrc = asset('assets/image/customers/produk/' . $cleanPath);
                                                            }
                                                        }
                                                    } else {
                                                        $fotoSrc = \App\Helpers\PhotoHelper::getThumbnailUrl($item);
                                                    }
                                                @endphp
                                                <img class="w-full h-full object-cover"
                                                    src="{{ $fotoSrc }}"
                                                    alt="{{ $item->nama_produk }}"
                                                    onerror="this.onerror=null;this.src='{{ asset('images/illustration/filling-survey.png') }}';">
                                            </div>
                                            <div class="flex flex-col">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('menu-produk.detail-produk', ['id_produk' => Crypt::encrypt($item->id_produk)]) }}" class="font-bold text-gray-900 text-[15px] hover:text-blue-600 transition-colors line-clamp-1 capitalize">
                                                        {{ $item->nama_produk }}
                                                    </a>
                                                    @if(strtolower($item->status_produk) == 'ditangguhkan')
                                                        @if($item->latestSuspend && $item->latestSuspend->status_revisi === 'sudah_diperbaiki')
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200 shrink-0">
                                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                                                Direvisi
                                                            </span>
                                                        @elseif($item->latestSuspend && $item->latestSuspend->status_revisi === 'menunggu_perbaikan')
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200 shrink-0">
                                                                <i class="bi bi-exclamation-triangle-fill text-rose-500 text-[9px]"></i>
                                                                Perlu Revisi
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-0.5">
                                                    <span class="text-xs font-medium text-gray-400">ID: #{{ substr(md5($item->id_produk), 0, 8) }}</span>
                                                    @if(strtolower($item->status_produk) == 'ditangguhkan' && $item->latestSuspend && !empty($item->latestSuspend->alasan ?? $item->latestSuspend->alasan_suspend))
                                                        <span class="text-xs text-rose-600 font-medium truncate max-w-[320px]" title="Alasan Ditangguhkan: {{ $item->latestSuspend->alasan ?? $item->latestSuspend->alasan_suspend }}">
                                                            &bull; Alasan: {{ $item->latestSuspend->alasan ?? $item->latestSuspend->alasan_suspend }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(strtolower($item->status_produk) == 'tersedia')
                                            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                {{ $item->status_produk }}
                                            </span>
                                        @elseif(strtolower($item->status_produk) == 'ditangguhkan')
                                            <div class="flex flex-col gap-1.5 items-start">
                                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200 shadow-xs">
                                                    <span class="relative flex h-2 w-2">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                                    </span>
                                                    Ditangguhkan
                                                </span>
                                                @if($item->latestSuspend && $item->latestSuspend->status_revisi === 'sudah_diperbaiki')
                                                    <span class="inline-flex items-center gap-1.5 py-0.5 px-2.5 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs" title="Perbaikan produk telah diajukan & sedang ditinjau admin">
                                                        <span class="relative flex h-2 w-2">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                        </span>
                                                        Sudah Direvisi
                                                    </span>
                                                @elseif($item->latestSuspend && $item->latestSuspend->status_revisi === 'menunggu_perbaikan')
                                                    <span class="inline-flex items-center gap-1 py-0.5 px-2 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="Silakan lakukan perbaikan produk">
                                                        <i class="bi bi-clock-history text-amber-500"></i> Perlu Perbaikan
                                                    </span>
                                                @endif
                                            </div>
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
                                               class="w-9 h-9 flex items-center justify-center rounded-xl {{ strtolower($item->status_produk) == 'ditangguhkan' ? 'bg-amber-100 text-amber-700 hover:bg-amber-600 hover:text-white ring-2 ring-amber-300' : 'bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white' }} transition-all shadow-sm hover:shadow group/btn relative"
                                               title="{{ strtolower($item->status_produk) == 'ditangguhkan' ? 'Perbaiki / Edit Produk' : 'Edit Produk' }}">
                                                <i class="bi {{ strtolower($item->status_produk) == 'ditangguhkan' ? 'bi-tools' : 'bi-pencil-square' }} text-[15px]"></i>
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
