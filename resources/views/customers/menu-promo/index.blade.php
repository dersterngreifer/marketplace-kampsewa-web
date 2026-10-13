@extends('layouts.customers.layouts-customer')

@section('customer-content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-6">

    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 font-bold text-xs rounded-full border border-amber-200">
                    <i class="bi bi-tag-fill text-xs"></i> Fitur Diskon Produk Toko
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 font-bold text-xs rounded-full border border-blue-200">
                    <i class="bi bi-shop text-xs"></i> Katalog Mitra
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Kelola Diskon Produk Toko</h1>
            <p class="text-sm text-gray-500 font-medium mt-1 max-w-3xl">
                Atur potongan harga sewa katalog produk toko Anda. Diskon aktif otomatis memunculkan <strong>Harga Coret</strong> dan label persentase diskon pada aplikasi mobile.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('buat-iklan.index', ['id_user' => $id_user]) }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-blue-600 transition-colors shadow-2xs">
                <i class="bi bi-megaphone"></i> Menu Iklan Banner
            </a>
            <a href="{{ route('menu-promo.create', ['id_user' => $id_user]) }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 active:scale-95 transition-all shadow-md shadow-blue-500/20">
                <i class="bi bi-plus-circle-fill text-base"></i> Buat Diskon Baru
            </a>
        </div>
    </div>

    @if($activeStoreWide)
        <!-- Banner Info Diskon Keseluruhan Aktif -->
        <div class="p-5 rounded-2xl bg-gradient-to-r from-blue-50 via-indigo-50/40 to-blue-50/30 border border-blue-200 text-blue-900 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-2xs">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center flex-shrink-0 text-lg shadow-sm">
                    <i class="bi bi-shop"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-black uppercase tracking-wider text-blue-900">Diskon Keseluruhan Toko Sedang Aktif</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-200/80 text-blue-800">Store-Wide</span>
                    </div>
                    <p class="text-xs text-blue-800 mt-1 leading-relaxed">
                        Program <strong>"{{ $activeStoreWide->nama_diskon }}"</strong> 
                        <span class="font-bold text-blue-900">({{ $activeStoreWide->tipe_diskon === 'persen' ? round($activeStoreWide->nilai_diskon) . '%' : 'Rp ' . number_format($activeStoreWide->nilai_diskon, 0, ',', '.') }})</span> 
                        dengan strategi <strong>{{ ($activeStoreWide->konflik_diskon ?? 'replace') === 'replace' ? 'Samaratakan (Replace Diskon Lama)' : 'Pertahankan Diskon Lama (Skip)' }}</strong> 
                        berlaku hingga <strong>{{ $activeStoreWide->tanggal_selesai ? $activeStoreWide->tanggal_selesai->translatedFormat('d F Y H:i') : '-' }}</strong>.
                    </p>
                </div>
            </div>
            <a href="{{ route('menu-promo.edit', ['id_promo' => $activeStoreWide->id_promo, 'id_user' => $id_user]) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-blue-300 hover:bg-blue-50 text-blue-700 text-xs font-bold rounded-xl transition-all shadow-xs self-start sm:self-auto flex-shrink-0">
                <i class="bi bi-pencil-square"></i> Edit Diskon Toko
            </a>
        </div>
    @endif

    <!-- Summary Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Diskon Aktif Berjalan</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $totalActive }}</h3>
                <p class="text-xs text-gray-400 mt-1 font-medium">Berlaku saat ini pada produk sewa</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="bi bi-fire"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Draft Diskon</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $totalDraft }}</h3>
                <p class="text-xs text-gray-400 mt-1 font-medium">Disimpan untuk persiapan promo</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="bi bi-file-earmark-text"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Telah Berakhir</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $totalExpired }}</h3>
                <p class="text-xs text-gray-400 mt-1 font-medium">Promo yang sudah lewat masa berlaku</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
    </div>

    <!-- Main Content: Promo List -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50">
            <div>
                <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
                    <i class="bi bi-percent text-blue-600"></i> Daftar Program Diskon Produk Toko
                </h2>
                <p class="text-xs text-gray-500 mt-0.5 font-medium">Kelola jadwal, persentase potongan harga, dan cakupan produk yang didiskon.</p>
            </div>
            <span class="text-xs font-bold text-gray-600 bg-white px-3 py-1.5 rounded-xl border border-gray-200 self-start sm:self-auto shadow-2xs">
                Total: {{ $promos->count() }} Program Diskon
            </span>
        </div>

        <div class="p-6">
            @if ($promos->isEmpty())
                <div class="py-16 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-3xl mb-3">
                        <i class="bi bi-tag"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Belum Ada Program Diskon</h3>
                    <p class="text-xs text-gray-500 max-w-md mt-1 mb-6">
                        Tingkatkan jumlah sewa dengan memberikan potongan harga untuk produk camping di toko Anda (misal diskon 10% atau potongan Rp 20.000).
                    </p>
                    <a href="{{ route('menu-promo.create', ['id_user' => $id_user]) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-md transition-all">
                        <i class="bi bi-plus-lg"></i> Buat Diskon Pertama Anda
                    </a>
                </div>
            @else
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-max text-sm text-left">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Program Diskon</th>
                                <th class="px-6 py-4">Besaran Potongan</th>
                                <th class="px-6 py-4">Cakupan Produk</th>
                                <th class="px-6 py-4">Periode Berlaku</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @foreach ($promos as $promo)
                                @php
                                    $isCurrentlyActive = $promo->is_active && $promo->status === 'active' && $promo->tanggal_mulai <= $now && $promo->tanggal_selesai >= $now;
                                    $isExpired = $promo->tanggal_selesai < $now;
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg flex-shrink-0 font-black">
                                                <i class="bi bi-percent"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-sm">{{ $promo->nama_diskon }}</h4>
                                                <p class="text-[11px] text-gray-400 mt-0.5">Dibuat {{ $promo->created_at ? $promo->created_at->format('d M Y') : '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($promo->tipe_diskon === 'persen')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-50 text-red-700 font-black text-xs rounded-full border border-red-200">
                                                <i class="bi bi-arrow-down-short text-base"></i> Diskon {{ (int) $promo->nilai_diskon }}%
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 font-black text-xs rounded-full border border-emerald-200">
                                                <i class="bi bi-dash"></i> Potongan Rp {{ number_format($promo->nilai_diskon, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($promo->cakupan === 'all_products')
                                            <div>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg">
                                                    <i class="bi bi-boxes"></i> Semua Produk Toko
                                                </span>
                                                <span class="block text-[10px] text-gray-500 font-medium mt-1">
                                                    Strategi: {{ ($promo->konflik_diskon ?? 'replace') === 'replace' ? 'Samaratakan / Replace' : 'Pertahankan Diskon Lama' }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-50 text-purple-700 text-xs font-bold rounded-lg">
                                                <i class="bi bi-check2-square"></i> {{ $promo->produk->count() }} Produk Pilihan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-600">
                                        <div class="flex items-center gap-1 font-semibold text-gray-900">
                                            <i class="bi bi-calendar-event text-gray-400"></i>
                                            {{ $promo->tanggal_mulai ? $promo->tanggal_mulai->format('d M Y') : '-' }} - {{ $promo->tanggal_selesai ? $promo->tanggal_selesai->format('d M Y') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($isExpired)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                                Expired
                                            </span>
                                        @elseif ($isCurrentlyActive)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Aktif
                                            </span>
                                        @elseif ($promo->status === 'draft')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                Draft
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Toggle Aktif/Nonaktif -->
                                            <form action="{{ route('menu-promo.toggle', ['id_promo' => $promo->id_promo, 'id_user' => $id_user]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                        title="{{ $promo->is_active ? 'Nonaktifkan Diskon' : 'Aktifkan Diskon' }}"
                                                        class="p-2 rounded-lg text-xs font-bold border {{ $promo->is_active ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }} transition-colors">
                                                     <i class="bi {{ $promo->is_active ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
                                                </button>
                                            </form>

                                            <!-- Edit -->
                                            <a href="{{ route('menu-promo.edit', ['id_promo' => $promo->id_promo, 'id_user' => $id_user]) }}"
                                               class="p-2 rounded-lg text-xs font-bold text-blue-600 border border-blue-200 hover:bg-blue-50 transition-colors"
                                               title="Edit Diskon">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- Hapus -->
                                            <form action="{{ route('menu-promo.destroy', ['id_promo' => $promo->id_promo, 'id_user' => $id_user]) }}" method="POST"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus program diskon ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 rounded-lg text-xs font-bold text-red-600 border border-red-200 hover:bg-red-50 transition-colors"
                                                        title="Hapus Diskon">
                                                    <i class="bi bi-trash"></i>
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
        </div>
    </div>
</div>
@endsection
