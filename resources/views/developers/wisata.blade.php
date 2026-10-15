@extends('layouts.developers.ly-dashboard')

@section('content')
<div class="w-full px-4 py-5 sm:px-5 lg:px-6">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full bg-violet-100/70 px-3 py-1 text-xs font-bold text-violet-700 mb-2">
                <i class="fi fi-rr-map-marker-home flex text-[13px]"></i>
                Fitur Rekomendasi Destinasi Wisata & Camping
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Manajemen Rekomendasi Wisata</h1>
            <p class="mt-1 text-sm font-medium text-slate-500 max-w-3xl">
                Kelola seluruh konten rekomendasi destinasi alam, tempat camping, dan wisata outdoor yang tampil di aplikasi mobile KampSewa secara dinamis.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <button type="button" onclick="openTambahModal()"
                class="flex items-center gap-2 rounded-2xl bg-gradient-to-br from-[#B381F4] to-[#5038ED] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-violet-500/25 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-violet-500/35">
                <i class="bi bi-plus-lg text-base"></i>
                Tambah Wisata Baru
            </button>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="mb-6 flex items-center justify-between rounded-2xl bg-emerald-50 border border-emerald-200 p-4 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500 text-white">
                    <i class="bi bi-check-lg text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-emerald-900">Operasi Berhasil</p>
                    <p class="text-xs text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 p-4 shadow-xs">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-500 text-white">
                    <i class="bi bi-exclamation-triangle text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-rose-900">Terdapat Kesalahan Input</p>
                    <p class="text-xs text-rose-700">Periksa kembali form yang Anda kirimkan.</p>
                </div>
            </div>
            <ul class="list-disc pl-12 text-xs text-rose-700 space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Card 1: Total Wisata --}}
        <div class="rounded-[24px] bg-white p-5 shadow-xs border border-slate-100 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                    <i class="fi fi-rr-map-marker-home flex text-[20px]"></i>
                </div>
                <span class="rounded-full bg-violet-50 px-2.5 py-1 text-[11px] font-bold text-violet-700">Database</span>
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Total Destinasi</p>
            <p class="mt-1 text-3xl font-black text-slate-900">{{ number_format($total_wisata, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-400">Rekomendasi tempat wisata</p>
        </div>

        {{-- Card 2: Total Tayangan / Pembaca --}}
        <div class="rounded-[24px] bg-white p-5 shadow-xs border border-slate-100 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <i class="fi fi-rr-eye flex text-[20px]"></i>
                </div>
                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">Total Views</span>
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Total Dibaca / Dilihat</p>
            <p class="mt-1 text-3xl font-black text-slate-900">{{ number_format($total_views, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-400">Akumulasi pembaca mobile</p>
        </div>

        {{-- Card 3: Wisata Terpopuler --}}
        <div class="rounded-[24px] bg-white p-5 shadow-xs border border-slate-100 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                    <i class="fi fi-rr-trophy flex text-[20px]"></i>
                </div>
                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700">Top Trending</span>
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Destinasi Terpopuler</p>
            <p class="mt-1 text-base font-black text-slate-900 truncate" title="{{ $wisata_terpopuler->judul ?? '-' }}">
                {{ $wisata_terpopuler->judul ?? 'Belum ada data' }}
            </p>
            <p class="mt-1 text-xs text-amber-600 font-bold">
                <i class="bi bi-fire"></i> {{ $wisata_terpopuler ? number_format($wisata_terpopuler->views_count, 0, ',', '.') : 0 }} pembaca
            </p>
        </div>

        {{-- Card 4: Status Publikasi --}}
        <div class="rounded-[24px] bg-white p-5 shadow-xs border border-slate-100 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i class="fi fi-rr-check-circle flex text-[20px]"></i>
                </div>
                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700">Status</span>
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Status Publikasi</p>
            <div class="mt-1 flex items-baseline gap-2">
                <span class="text-2xl font-black text-emerald-600">{{ $total_published }}</span>
                <span class="text-xs font-bold text-slate-400">Tayang</span>
                <span class="text-slate-300">/</span>
                <span class="text-2xl font-black text-amber-600">{{ $total_draft }}</span>
                <span class="text-xs font-bold text-slate-400">Draft</span>
            </div>
            <p class="mt-1 text-xs text-slate-400">Status tayang di mobile</p>
        </div>
    </div>

    {{-- Filter, Search & Sorting Bar --}}
    <div class="mb-6 rounded-[24px] bg-white p-4 sm:p-5 shadow-xs border border-slate-100">
        <form method="GET" action="{{ route('developer.wisata.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-12">
            
            {{-- Search Input --}}
            <div class="relative lg:col-span-5">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari destinasi wisata, lokasi kota, deskripsi..."
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-medium text-slate-700 placeholder:text-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
            </div>

            {{-- Filter Status --}}
            <div class="lg:col-span-3">
                <select name="status"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published (Tayang)</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft (Disimpan)</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived (Arsip)</option>
                </select>
            </div>

            {{-- Sorting --}}
            <div class="lg:col-span-3">
                <select name="sort_by"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                    <option value="terbaru" {{ request('sort_by') == 'terbaru' ? 'selected' : '' }}>Terbaru Dirilis</option>
                    <option value="terlama" {{ request('sort_by') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    <option value="terpopuler" {{ request('sort_by') == 'terpopuler' ? 'selected' : '' }}>Terpopuler (Views Tertinggi)</option>
                    <option value="a_z" {{ request('sort_by') == 'a_z' ? 'selected' : '' }}>Nama A - Z</option>
                    <option value="z_a" {{ request('sort_by') == 'z_a' ? 'selected' : '' }}>Nama Z - A</option>
                </select>
            </div>

            {{-- Submit & Reset Buttons --}}
            <div class="flex items-center gap-2 lg:col-span-1">
                <button type="submit"
                    class="flex h-12 w-full items-center justify-center rounded-2xl bg-slate-900 text-white transition hover:bg-slate-800"
                    title="Terapkan Filter">
                    <i class="bi bi-funnel-fill text-sm"></i>
                </button>
                @if (request()->hasAny(['search', 'status', 'sort_by']))
                    <a href="{{ route('developer.wisata.index') }}"
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-100 hover:text-slate-700"
                        title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise text-sm"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="overflow-hidden rounded-[28px] border border-slate-200/80 bg-white shadow-xs">
        <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900">
                Daftar Rekomendasi Wisata
                <span class="ml-2 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                    {{ $wisata_list->total() }} data
                </span>
            </h2>
            <p class="text-xs text-slate-400">
                Menampilkan halaman {{ $wisata_list->currentPage() }} dari {{ $wisata_list->lastPage() }}
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Destinasi & Lokasi</th>
                        <th class="px-6 py-4">Pengunggah</th>
                        <th class="px-6 py-4">Pembaca</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal Rilis</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse ($wisata_list as $index => $item)
                        @php
                            $rowNum = ($wisata_list->currentPage() - 1) * $wisata_list->perPage() + $loop->iteration;
                            $imgSrc = $item->gambar_url;
                        @endphp
                        <tr class="transition-colors hover:bg-slate-50/70">
                            {{-- No --}}
                            <td class="px-6 py-4 text-xs font-bold text-slate-400">
                                {{ $rowNum }}
                            </td>

                            {{-- Destinasi & Lokasi --}}
                            <td class="px-6 py-4 max-w-md">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $imgSrc }}" alt="{{ $item->judul }}"
                                        onerror="this.src='{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}'"
                                        class="h-14 w-20 shrink-0 rounded-xl object-cover border border-slate-100 shadow-xs">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 truncate" title="{{ $item->judul }}">
                                            {{ $item->judul }}
                                        </p>
                                        <div class="mt-1 flex items-center gap-1.5 text-xs text-slate-500 truncate">
                                            <i class="bi bi-geo-alt-fill text-rose-500 text-[11px]"></i>
                                            <span class="truncate">{{ $item->lokasi ?? 'Lokasi belum diisi' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Author --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <img src="@userPhoto($item->author->foto ?? null, 1)" 
                                        onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>'; this.onerror=null;"
                                        class="h-7 w-7 rounded-full object-cover border border-slate-200">
                                    <span class="text-xs font-semibold text-slate-700 truncate max-w-[120px]">
                                        {{ $item->author->name ?? $item->author->nama ?? 'Admin KampSewa' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Pembaca / Views --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                                    <i class="bi bi-eye text-slate-500"></i>
                                    {{ number_format($item->views_count, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($item->status == 'published')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Published
                                    </span>
                                @elseif ($item->status == 'draft')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 border border-amber-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Draft
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">
                                        Archived
                                    </span>
                                @endif
                            </td>

                            {{-- Tanggal Rilis --}}
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                <p class="font-bold text-slate-700">{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</p>
                                <p class="text-[11px] text-slate-400">{{ $item->created_at ? $item->created_at->format('H:i') . ' WIB' : '' }}</p>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    {{-- Detail --}}
                                    <button type="button" onclick="openDetailModal({{ json_encode($item->toApiArray()) }})"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition hover:bg-violet-50 hover:text-violet-600"
                                        title="Lihat Detail Lengkap">
                                        <i class="bi bi-eye-fill text-sm"></i>
                                    </button>

                                    {{-- Edit --}}
                                    <button type="button" onclick="openEditModal({{ json_encode($item) }})"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition hover:bg-amber-50 hover:text-amber-600"
                                        title="Edit Wisata">
                                        <i class="bi bi-pencil-fill text-xs"></i>
                                    </button>

                                    {{-- Hapus --}}
                                    <form action="{{ route('developer.wisata.destroy', $item->id) }}" method="POST"
                                        id="delete-form-{{ $item->id }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $item->id }}', '{{ addslashes($item->judul) }}')"
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition hover:bg-rose-50 hover:text-rose-600"
                                            title="Hapus Destinasi">
                                            <i class="bi bi-trash-fill text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                    <i class="fi fi-rr-map-marker-slash text-2xl"></i>
                                </div>
                                <p class="mt-3 text-base font-bold text-slate-700">Tidak ada data rekomendasi wisata</p>
                                <p class="text-xs text-slate-400">Silakan sesuaikan kata kunci filter atau tambah wisata baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Custom Pagination --}}
        <div class="border-t border-slate-100 px-6 py-4">
            {{ $wisata_list->links('components.paginate.custom-pagination') }}
        </div>
    </div>

</div>

{{-- ======================================================== --}}
{{-- MODAL TAMBAH WISATA                                      --}}
{{-- ======================================================== --}}
<div id="modalTambahWisata" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity p-4 sm:p-6">
    <div class="flex min-h-full items-center justify-center">
        <div class="w-full max-w-3xl transform overflow-hidden rounded-[28px] bg-white p-6 sm:p-8 shadow-2xl transition-all">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                        <i class="bi bi-plus-circle-fill text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Tambah Destinasi Wisata</h3>
                        <p class="text-xs font-medium text-slate-400">Lengkapi data destinasi wisata atau spot camping rekomendasi baru.</p>
                    </div>
                </div>
                <button type="button" onclick="closeTambahModal()" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            <form action="{{ route('developer.wisata.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
                @csrf
                
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Judul Destinasi --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Nama Destinasi / Judul Wisata <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" required value="{{ old('judul') }}"
                            placeholder="Contoh: Menikmati Keindahan Sunrise di Lembah Teletubbies Bromo"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                    </div>

                    {{-- Lokasi Wisata --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Lokasi / Daerah Destinasi <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="bi bi-geo-alt-fill absolute left-3.5 top-1/2 -translate-y-1/2 text-rose-500"></i>
                            <input type="text" name="lokasi" required value="{{ old('lokasi') }}"
                                placeholder="Contoh: Batu, Jawa Timur"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Status Publikasi <span class="text-rose-500">*</span></label>
                        <select name="status" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                            <option value="published" selected>Published (Langsung Tayang di Mobile)</option>
                            <option value="draft">Draft (Simpan sebagai Draf)</option>
                            <option value="archived">Archived (Diarsipkan)</option>
                        </select>
                    </div>

                    {{-- Cover Foto / Gambar: 2 Opsi (Upload File atau Input URL) --}}
                    <div class="sm:col-span-2 space-y-2">
                        <label class="block text-xs font-bold text-slate-700">Cover Foto Destinasi <span class="text-rose-500">*</span></label>
                        {{-- Tab Pilihan 2 Opsi Full Sejajar --}}
                        <div class="grid grid-cols-2 gap-1.5 rounded-xl bg-slate-100 p-1 text-xs font-bold w-full">
                            <button type="button" onclick="switchImageMethod('upload', 'tambahWisata')" id="tabUpload_tambahWisata"
                                class="w-full flex items-center justify-center gap-1.5 rounded-lg py-2 px-3 transition bg-white text-violet-700 shadow-xs whitespace-nowrap text-xs font-bold">
                                <i class="bi bi-cloud-arrow-up text-sm"></i> <span>Upload File Foto</span>
                            </button>
                            <button type="button" onclick="switchImageMethod('url', 'tambahWisata')" id="tabUrl_tambahWisata"
                                class="w-full flex items-center justify-center gap-1.5 rounded-lg py-2 px-3 transition text-slate-500 hover:text-slate-800 whitespace-nowrap text-xs font-bold">
                                <i class="bi bi-link-45deg text-sm"></i> <span>Input URL Foto</span>
                            </button>
                        </div>

                        {{-- Opsi 1: Upload File Foto Langsung --}}
                        <div id="sectionUpload_tambahWisata" class="space-y-2">
                            <div class="flex items-center gap-4">
                                <img id="previewUpload_tambahWisata" src="{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}"
                                    class="hidden h-16 w-24 shrink-0 rounded-xl object-cover border border-slate-200 shadow-xs">
                                <div class="flex-1">
                                    <input type="file" name="gambar" id="file_tambahWisata" accept="image/*"
                                        onchange="handleFilePreview(event, 'previewUpload_tambahWisata')"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-violet-600 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-white hover:file:bg-violet-700">
                                    <p class="mt-1 text-[11px] text-slate-400">File tersimpan langsung di folder: <span class="font-bold text-violet-600">public\assets\image\rekomendasi-wisata</span></p>
                                </div>
                            </div>
                        </div>

                        {{-- Opsi 2: Input Tautan / Link URL Foto --}}
                        <div id="sectionUrl_tambahWisata" class="hidden space-y-2">
                            <div class="flex items-center gap-4">
                                <img id="previewUrl_tambahWisata" src="{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}"
                                    onerror="this.src='{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}'"
                                    class="h-16 w-24 shrink-0 rounded-xl object-cover border border-slate-200 shadow-xs">
                                <div class="flex-1">
                                    <input type="url" name="gambar_url" id="url_tambahWisata" value="{{ old('gambar_url') }}"
                                        placeholder="https://images.unsplash.com/... atau tautan gambar web eksternal"
                                        oninput="handleUrlPreview(this.value, 'previewUrl_tambahWisata')"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none">
                                    <p class="mt-1 text-[11px] text-slate-400">Gunakan tautan gambar online yang aktif dan dapat diakses publik.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan Singkat --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Deskripsi Singkat (Preview Card Mobile)</label>
                        <textarea name="deskripsi_singkat" rows="2"
                            placeholder="Ringkasan 1-2 kalimat pengantar yang akan tampil pada kartu artikel di mobile..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">{{ old('deskripsi_singkat') }}</textarea>
                    </div>

                    {{-- Konten Lengkap --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Isi Konten Lengkap Ala Blog <span class="text-rose-500">*</span></label>
                        <textarea name="konten" rows="6" required
                            placeholder="Tuliskan ulasan menyeluruh, tips rute, fasilitas tempat camping, daya tarik wisata..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">{{ old('konten') }}</textarea>
                    </div>

                    {{-- Sumber Portal / Website Asli --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Nama Sumber Rujukan</label>
                        <input type="text" name="sumber_nama" value="{{ old('sumber_nama', 'Detik Travel') }}"
                            placeholder="Contoh: Detik Travel / Dinas Pariwisata"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">URL / Link Sumber Asli</label>
                        <input type="url" name="sumber_url" value="{{ old('sumber_url') }}"
                            placeholder="https://travel.detik.com/..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" onclick="closeTambahModal()"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-gradient-to-br from-[#B381F4] to-[#5038ED] px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40">
                        Simpan Wisata Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL EDIT WISATA                                        --}}
{{-- ======================================================== --}}
<div id="modalEditWisata" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity p-4 sm:p-6">
    <div class="flex min-h-full items-center justify-center">
        <div class="w-full max-w-3xl transform overflow-hidden rounded-[28px] bg-white p-6 sm:p-8 shadow-2xl transition-all">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                        <i class="bi bi-pencil-square text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Edit Destinasi Wisata</h3>
                        <p class="text-xs font-medium text-slate-400">Perbarui informasi destinasi wisata rekomendasi.</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            <form id="formEditWisata" action="" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Judul Destinasi --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Nama Destinasi / Judul Wisata <span class="text-rose-500">*</span></label>
                        <input type="text" id="editJudul" name="judul" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                    </div>

                    {{-- Lokasi Wisata --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Lokasi / Daerah Destinasi <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="bi bi-geo-alt-fill absolute left-3.5 top-1/2 -translate-y-1/2 text-rose-500"></i>
                            <input type="text" id="editLokasi" name="lokasi" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Status Publikasi <span class="text-rose-500">*</span></label>
                        <select id="editStatus" name="status" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                            <option value="published">Published (Tayang)</option>
                            <option value="draft">Draft (Draf)</option>
                            <option value="archived">Archived (Diarsipkan)</option>
                        </select>
                    </div>

                    {{-- Cover Foto / Gambar: 2 Opsi Edit (Upload File Baru atau Input URL Baru) --}}
                    <div class="sm:col-span-2 space-y-2">
                        <label class="block text-xs font-bold text-slate-700">Foto Cover Destinasi Saat Ini</label>
                        {{-- Tab Pilihan 2 Opsi Edit Full Sejajar --}}
                        <div class="grid grid-cols-2 gap-1.5 rounded-xl bg-slate-100 p-1 text-xs font-bold w-full">
                            <button type="button" onclick="switchImageMethod('upload', 'editWisata')" id="tabUpload_editWisata"
                                class="w-full flex items-center justify-center gap-1.5 rounded-lg py-2 px-3 transition bg-white text-amber-700 shadow-xs whitespace-nowrap text-xs font-bold">
                                <i class="bi bi-cloud-arrow-up text-sm"></i> <span>Upload File Foto</span>
                            </button>
                            <button type="button" onclick="switchImageMethod('url', 'editWisata')" id="tabUrl_editWisata"
                                class="w-full flex items-center justify-center gap-1.5 rounded-lg py-2 px-3 transition text-slate-500 hover:text-slate-800 whitespace-nowrap text-xs font-bold">
                                <i class="bi bi-link-45deg text-sm"></i> <span>Input URL Foto</span>
                            </button>
                        </div>

                        {{-- Opsi 1: Ganti dengan Upload File Foto --}}
                        <div id="sectionUpload_editWisata" class="space-y-2">
                            <div class="flex items-center gap-4">
                                <img id="editPreviewImgUpload" src="" alt="Cover Saat Ini" 
                                    onerror="this.src='{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}'"
                                    class="h-16 w-24 shrink-0 rounded-xl object-cover border border-slate-200 shadow-xs">
                                <div class="flex-1 space-y-1.5">
                                    <input type="file" name="gambar" id="file_editWisata" accept="image/*"
                                        onchange="handleFilePreview(event, 'editPreviewImgUpload')"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-600 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-white hover:file:bg-amber-700">
                                    <p class="text-[11px] text-slate-400">Pilih file baru untuk mengganti foto. Tersimpan ke: <span class="font-bold text-amber-600">public\assets\image\rekomendasi-wisata</span></p>
                                </div>
                            </div>
                        </div>

                        {{-- Opsi 2: Ganti dengan Link URL Foto --}}
                        <div id="sectionUrl_editWisata" class="hidden space-y-2">
                            <div class="flex items-center gap-4">
                                <img id="editPreviewImgUrl" src="" alt="Cover URL" 
                                    onerror="this.src='{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}'"
                                    class="h-16 w-24 shrink-0 rounded-xl object-cover border border-slate-200 shadow-xs">
                                <div class="flex-1 space-y-1.5">
                                    <input type="url" id="editGambarUrl" name="gambar_url" placeholder="https://images.unsplash.com/... atau URL baru"
                                        oninput="handleUrlPreview(this.value, 'editPreviewImgUrl')"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-700 focus:border-amber-500 focus:bg-white focus:outline-none">
                                    <p class="text-[11px] text-slate-400">Masukkan link URL gambar baru jika ingin mengganti cover via link online.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan Singkat --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Deskripsi Singkat (Preview Card Mobile)</label>
                        <textarea id="editDeskripsiSingkat" name="deskripsi_singkat" rows="2"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10"></textarea>
                    </div>

                    {{-- Konten Lengkap --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Isi Konten Lengkap Ala Blog <span class="text-rose-500">*</span></label>
                        <textarea id="editKonten" name="konten" rows="6" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10"></textarea>
                    </div>

                    {{-- Sumber Portal / Website Asli --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Nama Sumber Rujukan</label>
                        <input type="text" id="editSumberNama" name="sumber_nama"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">URL / Link Sumber Asli</label>
                        <input type="url" id="editSumberUrl" name="sumber_url"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" onclick="closeEditModal()"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL DETAIL LENGKAP WISATA (BLOG PREVIEW)               --}}
{{-- ======================================================== --}}
<div id="modalDetailWisata" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity p-4 sm:p-6">
    <div class="flex min-h-full items-center justify-center">
        <div class="w-full max-w-3xl transform overflow-hidden rounded-[28px] bg-white shadow-2xl transition-all">
            
            {{-- Cover Banner --}}
            <div class="relative h-64 sm:h-80 w-full overflow-hidden bg-slate-900">
                <img id="detailBanner" src="" alt="Cover Wisata"
                    onerror="this.src='{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}'"
                    class="h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent"></div>
                
                {{-- Top Controls --}}
                <div class="absolute left-6 right-6 top-6 flex items-center justify-between">
                    <span id="detailBadgeStatus" class="rounded-full bg-white/20 px-3 py-1 text-xs font-bold text-white backdrop-blur">
                        Rekomendasi Wisata
                    </span>
                    <button type="button" onclick="closeDetailModal()"
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-black/40 text-white backdrop-blur hover:bg-black/60">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Banner Bottom Info --}}
                <div class="absolute bottom-6 left-6 right-6">
                    <div class="flex items-center gap-2 text-rose-400 text-xs font-bold mb-1">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span id="detailLokasi">-</span>
                    </div>
                    <h2 id="detailJudul" class="text-xl sm:text-2xl font-black text-white leading-tight">
                        Judul Wisata
                    </h2>
                </div>
            </div>

            {{-- Body Content --}}
            <div class="p-6 sm:p-8 space-y-6">
                
                {{-- Metadata Row --}}
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div class="flex items-center gap-3">
                        <img id="detailAuthorImg" src="" alt="Author"
                            onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>'; this.onerror=null;"
                            class="h-11 w-11 rounded-2xl object-cover border border-slate-200">
                        <div>
                            <p id="detailAuthorNama" class="text-sm font-bold text-slate-900">Admin KampSewa</p>
                            <p class="text-xs text-slate-400">
                                <span id="detailTanggalUpload">-</span> • <span id="detailWaktuUpload">-</span> (<span id="detailWaktuLalu">-</span>)
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3.5 py-1.5 text-xs font-bold text-emerald-700">
                            <i class="bi bi-eye-fill"></i>
                            <span id="detailViews">0</span> pembaca
                        </div>
                    </div>
                </div>

                {{-- Deskripsi Singkat Box --}}
                <div class="rounded-2xl bg-slate-50 border border-slate-200/80 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Ringkasan Singkat</p>
                    <p id="detailDeskripsiSingkat" class="text-sm font-medium text-slate-700 leading-relaxed">
                        -
                    </p>
                </div>

                {{-- Full Konten Artikel --}}
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Konten / Deskripsi Lengkap Ala Blog</p>
                    <div id="detailKonten" class="text-sm font-normal text-slate-700 leading-relaxed space-y-3 whitespace-pre-line max-h-96 overflow-y-auto pr-2">
                        -
                    </div>
                </div>

                {{-- Sumber Web Eksternal --}}
                <div class="rounded-2xl border border-violet-100 bg-violet-50/50 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-violet-900">Sumber Referensi Artikel:</p>
                        <p id="detailSumberNama" class="text-xs text-violet-700 font-medium">Detik Travel</p>
                    </div>
                    <a id="detailSumberUrlBtn" href="#" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-violet-500/20 hover:bg-violet-700">
                        <i class="bi bi-box-arrow-up-right"></i>
                        Kunjungi Sumber Web Asli
                    </a>
                </div>

                {{-- Close Button --}}
                <div class="border-t border-slate-100 pt-5 text-right">
                    <button type="button" onclick="closeDetailModal()"
                        class="rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800">
                        Tutup Detail
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    // --- Switch Image Method & Live Previews ---
    function switchImageMethod(method, scope) {
        var sectionUpload = document.getElementById('sectionUpload_' + scope);
        var sectionUrl = document.getElementById('sectionUrl_' + scope);
        var tabUpload = document.getElementById('tabUpload_' + scope);
        var tabUrl = document.getElementById('tabUrl_' + scope);
        var activeColor = scope.includes('edit') ? 'text-amber-700' : 'text-violet-700';

        var baseClass = 'w-full flex items-center justify-center gap-1.5 rounded-lg py-2 px-3 transition whitespace-nowrap text-xs font-bold ';
        var activeClass = baseClass + 'bg-white ' + activeColor + ' shadow-xs';
        var inactiveClass = baseClass + 'text-slate-500 hover:text-slate-800';

        if (method === 'upload') {
            sectionUpload.classList.remove('hidden');
            sectionUrl.classList.add('hidden');
            tabUpload.className = activeClass;
            tabUrl.className = inactiveClass;
        } else {
            sectionUpload.classList.add('hidden');
            sectionUrl.classList.remove('hidden');
            tabUrl.className = activeClass;
            tabUpload.className = inactiveClass;
        }
    }

    function handleFilePreview(event, previewImgId) {
        var file = event.target.files[0];
        if (!file) return;
        var preview = document.getElementById(previewImgId);
        if (preview) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    }

    function handleUrlPreview(url, previewImgId) {
        var preview = document.getElementById(previewImgId);
        var fallback = '{{ asset("assets/logo/favicons/android-chrome-192x192.png") }}';
        if (preview) {
            preview.src = (url && url.trim().length > 0) ? url.trim() : fallback;
            preview.classList.remove('hidden');
        }
    }

    // --- Modals Logic ---
    function openTambahModal() {
        document.getElementById('file_tambahWisata').value = '';
        document.getElementById('url_tambahWisata').value = '';
        var prevUpload = document.getElementById('previewUpload_tambahWisata');
        if (prevUpload) prevUpload.classList.add('hidden');
        switchImageMethod('upload', 'tambahWisata');
        document.getElementById('modalTambahWisata').classList.remove('hidden');
    }

    function closeTambahModal() {
        document.getElementById('modalTambahWisata').classList.add('hidden');
    }

    function openEditModal(item) {
        var form = document.getElementById('formEditWisata');
        form.action = '{{ route("developer.wisata.update", ":id") }}'.replace(':id', item.id);

        document.getElementById('editJudul').value = item.judul || '';
        document.getElementById('editLokasi').value = item.lokasi || '';
        document.getElementById('editStatus').value = item.status || 'published';
        document.getElementById('editDeskripsiSingkat').value = item.deskripsi_singkat || '';
        document.getElementById('editKonten').value = item.konten || '';
        document.getElementById('editSumberNama').value = item.sumber_nama || '';
        document.getElementById('editSumberUrl').value = item.sumber_url || '';

        var defaultFallback = '{{ asset("assets/logo/favicons/android-chrome-192x192.png") }}';
        var isUrl = (item.gambar && (item.gambar.startsWith('http://') || item.gambar.startsWith('https://') || item.gambar.startsWith('//')));

        var previewSrc = defaultFallback;
        if (item.gambar) {
            var rawGambar = item.gambar.trim();
            if (isUrl) {
                previewSrc = rawGambar;
            } else if (rawGambar.startsWith('assets/') || rawGambar.startsWith('storage/')) {
                previewSrc = '{{ asset("") }}' + rawGambar;
            } else {
                previewSrc = '{{ asset("assets/image/rekomendasi-wisata") }}/' + rawGambar;
            }
        }

        document.getElementById('file_editWisata').value = '';
        document.getElementById('editGambarUrl').value = isUrl ? item.gambar : '';

        document.getElementById('editPreviewImgUpload').src = previewSrc;
        document.getElementById('editPreviewImgUrl').src = isUrl ? item.gambar : defaultFallback;

        if (isUrl) {
            switchImageMethod('url', 'editWisata');
        } else {
            switchImageMethod('upload', 'editWisata');
        }

        document.getElementById('modalEditWisata').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEditWisata').classList.add('hidden');
    }

    function openDetailModal(data) {
        document.getElementById('detailJudul').innerText = data.judul || '-';
        document.getElementById('detailLokasi').innerText = data.lokasi || 'Lokasi belum dispesifikasikan';
        document.getElementById('detailViews').innerText = Number(data.views_count || 0).toLocaleString('id-ID');
        document.getElementById('detailTanggalUpload').innerText = data.tanggal_upload || '-';
        document.getElementById('detailWaktuUpload').innerText = data.waktu_upload || '-';
        document.getElementById('detailWaktuLalu').innerText = data.waktu_lalu || '-';
        document.getElementById('detailDeskripsiSingkat').innerText = data.deskripsi_singkat || '-';
        document.getElementById('detailKonten').innerText = data.konten || '-';

        var authorName = (data.author && (data.author.name || data.author.nama)) ? (data.author.name || data.author.nama) : 'Admin KampSewa';
        document.getElementById('detailAuthorNama').innerText = authorName;

        var defaultAvatarSvg = 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>';
        var authorPhoto = (data.author && (data.author.foto_profile || data.author.foto)) ? (data.author.foto_profile || data.author.foto) : defaultAvatarSvg;
        document.getElementById('detailAuthorImg').src = authorPhoto;

        var banner = document.getElementById('detailBanner');
        banner.src = data.gambar || '{{ asset("assets/logo/favicons/android-chrome-192x192.png") }}';

        var sumberNama = data.sumber_nama || 'Sumber Resmi Eksternal';
        document.getElementById('detailSumberNama').innerText = sumberNama;

        var sumberBtn = document.getElementById('detailSumberUrlBtn');
        if (data.sumber_url) {
            sumberBtn.href = data.sumber_url;
            sumberBtn.classList.remove('hidden');
        } else {
            sumberBtn.classList.add('hidden');
        }

        document.getElementById('modalDetailWisata').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('modalDetailWisata').classList.add('hidden');
    }

    function confirmDelete(id, judul) {
        Swal.fire({
            title: 'Hapus Destinasi Wisata?',
            text: 'Anda yakin ingin menghapus "' + judul + '"? Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Sekarang!',
            cancelButtonText: 'Batal',
            borderRadius: '20px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTambahModal();
            closeEditModal();
            closeDetailModal();
        }
    });

    // Close on backdrop click
    ['modalTambahWisata', 'modalEditWisata', 'modalDetailWisata'].forEach(function(modalId) {
        var modalEl = document.getElementById(modalId);
        if (modalEl) {
            modalEl.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush
@endsection
