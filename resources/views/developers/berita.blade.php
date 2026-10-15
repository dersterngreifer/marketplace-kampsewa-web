@extends('layouts.developers.ly-dashboard')

@section('content')
<div class="w-full px-4 py-5 sm:px-5 lg:px-6">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full bg-violet-100/70 px-3 py-1 text-xs font-bold text-violet-700 mb-2">
                <i class="fi fi-rr-newspaper flex text-[13px]"></i>
                Fitur Berita & Artikel Edukasi Outdoor Terkini
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Manajemen Berita Terkini</h1>
            <p class="mt-1 text-sm font-medium text-slate-500 max-w-3xl">
                Kelola seluruh artikel berita seputar dunia pendakian, event alam terbuka, tips camping, serta kabar terkini untuk aplikasi mobile KampSewa secara dinamis.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <button type="button" onclick="openTambahModal()"
                class="flex items-center gap-2 rounded-2xl bg-gradient-to-br from-[#B381F4] to-[#5038ED] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-violet-500/25 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-violet-500/35">
                <i class="bi bi-plus-lg text-base"></i>
                Tambah Berita Baru
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
        {{-- Card 1: Total Berita --}}
        <div class="rounded-[24px] bg-white p-5 shadow-xs border border-slate-100 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                    <i class="fi fi-rr-newspaper flex text-[20px]"></i>
                </div>
                <span class="rounded-full bg-violet-50 px-2.5 py-1 text-[11px] font-bold text-violet-700">Database</span>
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Total Artikel Berita</p>
            <p class="mt-1 text-3xl font-black text-slate-900">{{ number_format($total_berita, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-400">Kabar & edukasi outdoor</p>
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

        {{-- Card 3: Berita Terpopuler --}}
        <div class="rounded-[24px] bg-white p-5 shadow-xs border border-slate-100 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                    <i class="fi fi-rr-trophy flex text-[20px]"></i>
                </div>
                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700">Top Trending</span>
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Berita Terpopuler</p>
            <p class="mt-1 text-base font-black text-slate-900 truncate" title="{{ $berita_terpopuler->judul ?? '-' }}">
                {{ $berita_terpopuler->judul ?? 'Belum ada data' }}
            </p>
            <p class="mt-1 text-xs text-amber-600 font-bold">
                <i class="bi bi-fire"></i> {{ $berita_terpopuler ? number_format($berita_terpopuler->views_count, 0, ',', '.') : 0 }} pembaca
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
        <form method="GET" action="{{ route('developer.berita.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-12">
            
            {{-- Search Input --}}
            <div class="relative lg:col-span-5">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari judul berita, sumber, deskripsi..."
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
                    <option value="a_z" {{ request('sort_by') == 'a_z' ? 'selected' : '' }}>Judul A - Z</option>
                    <option value="z_a" {{ request('sort_by') == 'z_a' ? 'selected' : '' }}>Judul Z - A</option>
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
                    <a href="{{ route('developer.berita.index') }}"
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
                Daftar Berita Terkini
                <span class="ml-2 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                    {{ $berita_list->total() }} data
                </span>
            </h2>
            <p class="text-xs text-slate-400">
                Menampilkan halaman {{ $berita_list->currentPage() }} dari {{ $berita_list->lastPage() }}
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Judul Berita & Sumber</th>
                        <th class="px-6 py-4">Pengunggah</th>
                        <th class="px-6 py-4">Pembaca</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal Rilis</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse ($berita_list as $index => $item)
                        @php
                            $rowNum = ($berita_list->currentPage() - 1) * $berita_list->perPage() + $loop->iteration;
                            $imgSrc = $item->gambar_url;
                        @endphp
                        <tr class="transition-colors hover:bg-slate-50/70">
                            {{-- No --}}
                            <td class="px-6 py-4 text-xs font-bold text-slate-400">
                                {{ $rowNum }}
                            </td>

                            {{-- Judul Berita & Sumber --}}
                            <td class="px-6 py-4 max-w-md">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $imgSrc }}" alt="{{ $item->judul }}"
                                        onerror="this.src='{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}'"
                                        class="h-14 w-20 shrink-0 rounded-xl object-cover border border-slate-100 shadow-xs">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 truncate" title="{{ $item->judul }}">
                                            {{ $item->judul }}
                                        </p>
                                        <div class="mt-1 flex items-center gap-1.5 text-xs text-violet-600 font-semibold truncate">
                                            <i class="bi bi-newspaper text-[11px]"></i>
                                            <span class="truncate">{{ $item->sumber_nama ?? 'Portal Berita' }}</span>
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
                                        title="Edit Berita">
                                        <i class="bi bi-pencil-fill text-xs"></i>
                                    </button>

                                    {{-- Hapus --}}
                                    <form action="{{ route('developer.berita.destroy', $item->id) }}" method="POST"
                                        id="delete-form-{{ $item->id }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $item->id }}', '{{ addslashes($item->judul) }}')"
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition hover:bg-rose-50 hover:text-rose-600"
                                            title="Hapus Berita">
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
                                    <i class="fi fi-rr-document-signed text-2xl"></i>
                                </div>
                                <p class="mt-3 text-base font-bold text-slate-700">Tidak ada data artikel berita</p>
                                <p class="text-xs text-slate-400">Silakan sesuaikan kata kunci filter atau tambah berita baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Custom Pagination --}}
        <div class="border-t border-slate-100 px-6 py-4">
            {{ $berita_list->links('components.paginate.custom-pagination') }}
        </div>
    </div>

</div>

{{-- ======================================================== --}}
{{-- MODAL TAMBAH BERITA                                      --}}
{{-- ======================================================== --}}
<div id="modalTambahBerita" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity p-4 sm:p-6">
    <div class="flex min-h-full items-center justify-center">
        <div class="w-full max-w-3xl transform overflow-hidden rounded-[28px] bg-white p-6 sm:p-8 shadow-2xl transition-all">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                        <i class="bi bi-plus-circle-fill text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Tambah Artikel Berita Baru</h3>
                        <p class="text-xs font-medium text-slate-400">Lengkapi artikel berita seputar event dan edukasi outdoor.</p>
                    </div>
                </div>
                <button type="button" onclick="closeTambahModal()" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            <form action="{{ route('developer.berita.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
                @csrf
                
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Judul Berita --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Judul Berita Terkini <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" required value="{{ old('judul') }}"
                            placeholder="Contoh: Tips Mendirikan Tenda Saat Musim Hujan dan Menjaga Perlengkapan Tetap Kering"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                    </div>

                    {{-- Nama Portal Sumber --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Nama Portal Sumber</label>
                        <input type="text" name="sumber_nama" value="{{ old('sumber_nama', 'BBC News Indonesia') }}"
                            placeholder="Contoh: BBC News Indonesia / Detik Outdoor"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none">
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

                    {{-- Cover Foto / Thumbnail: 2 Opsi (Upload File atau Input URL) --}}
                    <div class="sm:col-span-2 space-y-2">
                        <label class="block text-xs font-bold text-slate-700">Cover Foto / Thumbnail Berita <span class="text-rose-500">*</span></label>
                        {{-- Tab Pilihan 2 Opsi Full Sejajar --}}
                        <div class="grid grid-cols-2 gap-1.5 rounded-xl bg-slate-100 p-1 text-xs font-bold w-full">
                            <button type="button" onclick="switchImageMethod('upload', 'tambahBerita')" id="tabUpload_tambahBerita"
                                class="w-full flex items-center justify-center gap-1.5 rounded-lg py-2 px-3 transition bg-white text-violet-700 shadow-xs whitespace-nowrap text-xs font-bold">
                                <i class="bi bi-cloud-arrow-up text-sm"></i> <span>Upload File Foto</span>
                            </button>
                            <button type="button" onclick="switchImageMethod('url', 'tambahBerita')" id="tabUrl_tambahBerita"
                                class="w-full flex items-center justify-center gap-1.5 rounded-lg py-2 px-3 transition text-slate-500 hover:text-slate-800 whitespace-nowrap text-xs font-bold">
                                <i class="bi bi-link-45deg text-sm"></i> <span>Input URL Foto</span>
                            </button>
                        </div>

                        {{-- Opsi 1: Upload File Foto Langsung --}}
                        <div id="sectionUpload_tambahBerita" class="space-y-2">
                            <div class="flex items-center gap-4">
                                <img id="previewUpload_tambahBerita" src="{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}"
                                    class="hidden h-16 w-24 shrink-0 rounded-xl object-cover border border-slate-200 shadow-xs">
                                <div class="flex-1">
                                    <input type="file" name="gambar" id="file_tambahBerita" accept="image/*"
                                        onchange="handleFilePreview(event, 'previewUpload_tambahBerita')"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-violet-600 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-white hover:file:bg-violet-700">
                                    <p class="mt-1 text-[11px] text-slate-400">File tersimpan langsung di folder: <span class="font-bold text-violet-600">public\assets\image\berita-terkini</span></p>
                                </div>
                            </div>
                        </div>

                        {{-- Opsi 2: Input Tautan / Link URL Foto --}}
                        <div id="sectionUrl_tambahBerita" class="hidden space-y-2">
                            <div class="flex items-center gap-4">
                                <img id="previewUrl_tambahBerita" src="{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}"
                                    onerror="this.src='{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}'"
                                    class="h-16 w-24 shrink-0 rounded-xl object-cover border border-slate-200 shadow-xs">
                                <div class="flex-1">
                                    <input type="url" name="gambar_url" id="url_tambahBerita" value="{{ old('gambar_url') }}"
                                        placeholder="https://ichef.bbci.co.uk/... atau tautan gambar berita online lainnya"
                                        oninput="handleUrlPreview(this.value, 'previewUrl_tambahBerita')"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none">
                                    <p class="mt-1 text-[11px] text-slate-400">Gunakan tautan gambar berita online yang aktif dan dapat diakses publik.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan Singkat --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Deskripsi Singkat (Preview Card Mobile)</label>
                        <textarea name="deskripsi_singkat" rows="2"
                            placeholder="Ringkasan singkat berita untuk preview kartu di halaman dashboard aplikasi mobile..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">{{ old('deskripsi_singkat') }}</textarea>
                    </div>

                    {{-- Konten Lengkap --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Isi Konten Lengkap Artikel Berita <span class="text-rose-500">*</span></label>
                        <textarea name="konten" rows="6" required
                            placeholder="Tuliskan berita lengkap, opini, kronologi, atau panduan edukasi outdoor..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">{{ old('konten') }}</textarea>
                    </div>

                    {{-- Sumber URL Asli --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">URL / Link Sumber Asli Eksternal</label>
                        <input type="url" name="sumber_url" value="{{ old('sumber_url') }}"
                            placeholder="https://www.bbc.com/indonesia/articles/..."
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
                        Simpan Berita Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL EDIT BERITA                                        --}}
{{-- ======================================================== --}}
<div id="modalEditBerita" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity p-4 sm:p-6">
    <div class="flex min-h-full items-center justify-center">
        <div class="w-full max-w-3xl transform overflow-hidden rounded-[28px] bg-white p-6 sm:p-8 shadow-2xl transition-all">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                        <i class="bi bi-pencil-square text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Edit Artikel Berita</h3>
                        <p class="text-xs font-medium text-slate-400">Perbarui konten artikel berita terkini.</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            <form id="formEditBerita" action="" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Judul Berita --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Judul Berita <span class="text-rose-500">*</span></label>
                        <input type="text" id="editJudul" name="judul" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                    </div>

                    {{-- Nama Portal Sumber --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Nama Portal Sumber</label>
                        <input type="text" id="editSumberNama" name="sumber_nama"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700">
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

                    {{-- Cover Foto / Thumbnail: 2 Opsi Edit (Upload File Baru atau Input URL Baru) --}}
                    <div class="sm:col-span-2 space-y-2">
                        <label class="block text-xs font-bold text-slate-700">Thumbnail Berita Saat Ini</label>
                        {{-- Tab Pilihan 2 Opsi Edit Full Sejajar --}}
                        <div class="grid grid-cols-2 gap-1.5 rounded-xl bg-slate-100 p-1 text-xs font-bold w-full">
                            <button type="button" onclick="switchImageMethod('upload', 'editBerita')" id="tabUpload_editBerita"
                                class="w-full flex items-center justify-center gap-1.5 rounded-lg py-2 px-3 transition bg-white text-amber-700 shadow-xs whitespace-nowrap text-xs font-bold">
                                <i class="bi bi-cloud-arrow-up text-sm"></i> <span>Upload File Foto</span>
                            </button>
                            <button type="button" onclick="switchImageMethod('url', 'editBerita')" id="tabUrl_editBerita"
                                class="w-full flex items-center justify-center gap-1.5 rounded-lg py-2 px-3 transition text-slate-500 hover:text-slate-800 whitespace-nowrap text-xs font-bold">
                                <i class="bi bi-link-45deg text-sm"></i> <span>Input URL Foto</span>
                            </button>
                        </div>

                        {{-- Opsi 1: Ganti dengan Upload File Foto --}}
                        <div id="sectionUpload_editBerita" class="space-y-2">
                            <div class="flex items-center gap-4">
                                <img id="editPreviewImgUpload" src="" alt="Thumbnail Saat Ini" 
                                    onerror="this.src='{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}'"
                                    class="h-16 w-24 shrink-0 rounded-xl object-cover border border-slate-200 shadow-xs">
                                <div class="flex-1 space-y-1.5">
                                    <input type="file" name="gambar" id="file_editBerita" accept="image/*"
                                        onchange="handleFilePreview(event, 'editPreviewImgUpload')"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-600 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-white hover:file:bg-amber-700">
                                    <p class="text-[11px] text-slate-400">Pilih file baru untuk mengganti thumbnail. Tersimpan ke: <span class="font-bold text-amber-600">public\assets\image\berita-terkini</span></p>
                                </div>
                            </div>
                        </div>

                        {{-- Opsi 2: Ganti dengan Link URL Foto --}}
                        <div id="sectionUrl_editBerita" class="hidden space-y-2">
                            <div class="flex items-center gap-4">
                                <img id="editPreviewImgUrl" src="" alt="Thumbnail URL" 
                                    onerror="this.src='{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}'"
                                    class="h-16 w-24 shrink-0 rounded-xl object-cover border border-slate-200 shadow-xs">
                                <div class="flex-1 space-y-1.5">
                                    <input type="url" id="editGambarUrl" name="gambar_url" placeholder="https://ichef.bbci.co.uk/... atau URL baru"
                                        oninput="handleUrlPreview(this.value, 'editPreviewImgUrl')"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-700 focus:border-amber-500 focus:bg-white focus:outline-none">
                                    <p class="text-[11px] text-slate-400">Masukkan link URL gambar baru jika ingin mengganti thumbnail via link online.</p>
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
                        <label class="text-xs font-bold text-slate-700">Isi Konten Lengkap Berita <span class="text-rose-500">*</span></label>
                        <textarea id="editKonten" name="konten" rows="6" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10"></textarea>
                    </div>

                    {{-- URL Sumber Asli --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">URL / Link Sumber Asli Eksternal</label>
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
{{-- MODAL DETAIL LENGKAP BERITA (BLOG PREVIEW)               --}}
{{-- ======================================================== --}}
<div id="modalDetailBerita" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity p-4 sm:p-6">
    <div class="flex min-h-full items-center justify-center">
        <div class="w-full max-w-3xl transform overflow-hidden rounded-[28px] bg-white shadow-2xl transition-all">
            
            {{-- Cover Banner --}}
            <div class="relative h-64 sm:h-80 w-full overflow-hidden bg-slate-900">
                <img id="detailBanner" src="" alt="Cover Berita"
                    onerror="this.src='{{ asset('assets/logo/favicons/android-chrome-192x192.png') }}'"
                    class="h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent"></div>
                
                {{-- Top Controls --}}
                <div class="absolute left-6 right-6 top-6 flex items-center justify-between">
                    <span id="detailBadgeStatus" class="rounded-full bg-violet-600/80 px-3 py-1 text-xs font-bold text-white backdrop-blur">
                        Berita Terkini
                    </span>
                    <button type="button" onclick="closeDetailModal()"
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-black/40 text-white backdrop-blur hover:bg-black/60">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Banner Bottom Info --}}
                <div class="absolute bottom-6 left-6 right-6">
                    <div class="flex items-center gap-2 text-violet-300 text-xs font-bold mb-1">
                        <i class="bi bi-newspaper"></i>
                        <span id="detailSumberNamaHeader">BBC News Indonesia</span>
                    </div>
                    <h2 id="detailJudul" class="text-xl sm:text-2xl font-black text-white leading-tight">
                        Judul Berita
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
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Konten / Isi Berita Lengkap</p>
                    <div id="detailKonten" class="text-sm font-normal text-slate-700 leading-relaxed space-y-3 whitespace-pre-line max-h-96 overflow-y-auto pr-2">
                        -
                    </div>
                </div>

                {{-- Sumber Web Eksternal --}}
                <div class="rounded-2xl border border-violet-100 bg-violet-50/50 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-violet-900">Portal Sumber Berita:</p>
                        <p id="detailSumberNama" class="text-xs text-violet-700 font-medium">BBC News Indonesia</p>
                    </div>
                    <a id="detailSumberUrlBtn" href="#" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-violet-500/20 hover:bg-violet-700">
                        <i class="bi bi-box-arrow-up-right"></i>
                        Baca Selengkapnya di Sumber Asli
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
        document.getElementById('file_tambahBerita').value = '';
        document.getElementById('url_tambahBerita').value = '';
        var prevUpload = document.getElementById('previewUpload_tambahBerita');
        if (prevUpload) prevUpload.classList.add('hidden');
        switchImageMethod('upload', 'tambahBerita');
        document.getElementById('modalTambahBerita').classList.remove('hidden');
    }

    function closeTambahModal() {
        document.getElementById('modalTambahBerita').classList.add('hidden');
    }

    function openEditModal(item) {
        var form = document.getElementById('formEditBerita');
        form.action = '{{ route("developer.berita.update", ":id") }}'.replace(':id', item.id);

        document.getElementById('editJudul').value = item.judul || '';
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
                previewSrc = '{{ asset("assets/image/berita-terkini") }}/' + rawGambar;
            }
        }

        document.getElementById('file_editBerita').value = '';
        document.getElementById('editGambarUrl').value = isUrl ? item.gambar : '';

        document.getElementById('editPreviewImgUpload').src = previewSrc;
        document.getElementById('editPreviewImgUrl').src = isUrl ? item.gambar : defaultFallback;

        if (isUrl) {
            switchImageMethod('url', 'editBerita');
        } else {
            switchImageMethod('upload', 'editBerita');
        }

        document.getElementById('modalEditBerita').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEditBerita').classList.add('hidden');
    }

    function openDetailModal(data) {
        document.getElementById('detailJudul').innerText = data.judul || '-';
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

        var sumberNama = data.sumber_nama || 'Portal Berita';
        document.getElementById('detailSumberNama').innerText = sumberNama;
        document.getElementById('detailSumberNamaHeader').innerText = sumberNama;

        var sumberBtn = document.getElementById('detailSumberUrlBtn');
        if (data.sumber_url) {
            sumberBtn.href = data.sumber_url;
            sumberBtn.classList.remove('hidden');
        } else {
            sumberBtn.classList.add('hidden');
        }

        document.getElementById('modalDetailBerita').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('modalDetailBerita').classList.add('hidden');
    }

    function confirmDelete(id, judul) {
        Swal.fire({
            title: 'Hapus Berita Terkini?',
            text: 'Anda yakin ingin menghapus artikel berita "' + judul + '"? Tindakan ini tidak dapat dibatalkan.',
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
    ['modalTambahBerita', 'modalEditBerita', 'modalDetailBerita'].forEach(function(modalId) {
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
