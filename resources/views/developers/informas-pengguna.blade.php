@extends('layouts.developers.ly-dashboard')

@section('content')
<div class="w-full px-4 py-5 sm:px-5 lg:px-6">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900">Informasi Pengguna</h1>
            <p class="mt-1 text-sm text-slate-500">Pantau aktivitas, pertumbuhan, dan status pengguna platform secara real-time.</p>
        </div>
        <div class="flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm font-bold text-slate-600">
            <i class="fi fi-rr-users flex text-[15px]"></i>
            {{ $count }} total pengguna terdaftar
        </div>
    </div>

    {{-- =============================================
         STAT CARDS (4 kartu dengan % perubahan)
         ============================================= --}}
    @php
        $statCards = [
            [
                'label'      => 'Pendaftar Hari Ini',
                'sub'        => 'Kemarin: ' . $user_pendaftar_kemarin . ' user',
                'value'      => $user_pendaftar_hari_ini,
                'pct'        => $pct_hari,
                'icon'       => 'fi-rr-calendar-day',
                'color'      => 'violet',
                'bg'         => 'bg-violet-50',
                'text'       => 'text-violet-600',
                'icon_bg'    => 'bg-violet-100',
            ],
            [
                'label'      => 'Pendaftar Minggu Ini',
                'sub'        => 'Minggu lalu: ' . $user_pendaftar_minggu_kemarin . ' user',
                'value'      => $user_pendaftar_minggu_ini,
                'pct'        => $pct_minggu,
                'icon'       => 'fi-rr-calendar-week',
                'color'      => 'blue',
                'bg'         => 'bg-blue-50',
                'text'       => 'text-blue-600',
                'icon_bg'    => 'bg-blue-100',
            ],
            [
                'label'      => 'Pendaftar Bulan Ini',
                'sub'        => 'Bulan lalu: ' . $user_pendaftar_bulan_kemarin . ' user',
                'value'      => $user_pendaftar_bulan_ini,
                'pct'        => $pct_bulan,
                'icon'       => 'fi-rr-calendar',
                'color'      => 'emerald',
                'bg'         => 'bg-emerald-50',
                'text'       => 'text-emerald-600',
                'icon_bg'    => 'bg-emerald-100',
            ],
            [
                'label'      => 'Pendaftar Tahun Ini',
                'sub'        => 'Tahun lalu: ' . $user_pendaftar_tahun_kemarin . ' user',
                'value'      => $user_pendaftar_tahun_ini,
                'pct'        => $pct_tahun,
                'icon'       => 'fi-rr-time-forward',
                'color'      => 'amber',
                'bg'         => 'bg-amber-50',
                'text'       => 'text-amber-600',
                'icon_bg'    => 'bg-amber-100',
            ],
        ];
    @endphp

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($statCards as $card)
        <div class="group relative overflow-hidden rounded-[28px] border border-slate-100 bg-white p-5 shadow-sm transition hover:shadow-md">
            {{-- Dekoratif bulat background --}}
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full {{ $card['bg'] }} opacity-40 blur-xl"></div>
            <div class="relative">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl {{ $card['icon_bg'] }} {{ $card['text'] }}">
                        <i class="fi {{ $card['icon'] }} flex text-lg"></i>
                    </div>
                    {{-- Badge persentase --}}
                    @if ($card['pct'] > 0)
                        <div class="flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-black text-emerald-600">
                            <i class="fi fi-rr-trending-up flex text-[10px]"></i>
                            +{{ $card['pct'] }}%
                        </div>
                    @elseif ($card['pct'] < 0)
                        <div class="flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-black text-red-500">
                            <i class="fi fi-rr-trending-down flex text-[10px]"></i>
                            {{ $card['pct'] }}%
                        </div>
                    @else
                        <div class="flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-400">
                            —
                        </div>
                    @endif
                </div>
                <p class="text-3xl font-black text-slate-900">{{ number_format($card['value']) }}</p>
                <p class="mt-1 text-xs font-bold uppercase tracking-wide text-slate-400">{{ $card['label'] }}</p>
                <p class="mt-1.5 text-[11px] text-slate-400">{{ $card['sub'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- =============================================
         LAYOUT DUA KOLOM
         ============================================= --}}
    <div class="flex flex-col gap-6 xl:flex-row">

        {{-- KOLOM KIRI: List Pengguna --}}
        <div class="flex flex-1 flex-col gap-5 min-w-0">

            {{-- Filter & Search --}}
            <form method="GET" action="" id="form-filter">
                <div class="flex flex-col gap-3 rounded-[28px] border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        {{-- Search --}}
                        <div class="relative flex-1">
                            <i class="fi fi-rr-search absolute left-4 top-1/2 -translate-y-1/2 flex text-slate-400 text-[15px]"></i>
                            <input type="text" name="cari" value="{{ $cari_customer }}"
                                placeholder="Cari nama, email, atau nomor HP..."
                                class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-medium text-slate-700 outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-100 transition">
                        </div>
                        {{-- Sort --}}
                        <div class="flex items-center gap-2">
                            <select name="filter" id="filter"
                                class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-100 transition cursor-pointer">
                                <option value="terbaru" {{ ($filter_customer ?? 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" {{ ($filter_customer ?? '') === 'terlama' ? 'selected' : '' }}>Terlama</option>
                            </select>
                        </div>
                    </div>
                    {{-- Filter Chips --}}
                    <div class="flex flex-wrap gap-2">
                        <button type="submit" name="tidak_aktif_sebulan" value="tidak_aktif_sebulan"
                            class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-bold transition cursor-pointer
                            {{ ($tidak_aktif_sebulan ?? '') === 'tidak_aktif_sebulan'
                                ? 'border-violet-400 bg-violet-600 text-white shadow-sm'
                                : 'border-slate-200 bg-white text-slate-600 hover:border-violet-300 hover:text-violet-600' }}">
                            <i class="fi fi-rr-time-delete flex text-[11px]"></i>
                            Tidak Aktif 1 Bulan
                        </button>
                        <button type="submit" name="produk_terbanyak" value="produk_terbanyak"
                            class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-bold transition cursor-pointer
                            {{ ($produk_terbanyak ?? '') === 'produk_terbanyak'
                                ? 'border-violet-400 bg-violet-600 text-white shadow-sm'
                                : 'border-slate-200 bg-white text-slate-600 hover:border-violet-300 hover:text-violet-600' }}">
                            <i class="fi fi-rr-box-alt flex text-[11px]"></i>
                            Produk Terbanyak
                        </button>
                        @if ($cari_customer || $tidak_aktif_sebulan || $produk_terbanyak)
                        <a href="{{ url()->current() }}"
                            class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-2 text-xs font-bold text-red-500 hover:bg-red-500 hover:text-white transition">
                            <i class="fi fi-rr-cross-circle flex text-[11px]"></i>
                            Reset Filter
                        </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Daftar Pengguna --}}
            <div class="overflow-hidden rounded-[28px] border border-slate-100 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <p class="text-sm font-black text-slate-700">
                        Menampilkan <span class="text-violet-600">{{ $users->count() }}</span> dari <span class="text-violet-600">{{ $count }}</span> pengguna
                    </p>
                </div>
                @if ($users->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach ($users as $item)
                    <a href="{{ route('detail-pengguna.index', ['user' => $item->user_id]) }}"
                        class="group flex items-center gap-4 px-5 py-4 hover:bg-violet-50/40 transition">
                        {{-- Avatar + status online --}}
                        <div class="relative shrink-0">
                            <img class="h-12 w-12 rounded-2xl object-cover"
                                src="@userPhoto($item->foto)" alt="{{ $item->name }}">
                            @if (($item->status ?? '') === 'online')
                            <span class="absolute -bottom-0.5 -right-0.5 flex h-3.5 w-3.5">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500"></span>
                            </span>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="truncate text-sm font-black text-slate-900 group-hover:text-violet-700 transition">{{ $item->name }}</p>
                                @if ($item->total_product > 0)
                                <span class="shrink-0 rounded-full bg-violet-50 px-2 py-0.5 text-[10px] font-black text-violet-600">
                                    {{ $item->total_product }} produk
                                </span>
                                @endif
                            </div>
                            <p class="mt-0.5 truncate text-xs text-slate-400">{{ $item->email ?? '-' }}</p>
                            <p class="text-[11px] text-slate-400">{{ $item->nomor_telephone ?? '-' }}</p>
                        </div>
                        {{-- Meta --}}
                        <div class="shrink-0 text-right hidden sm:block">
                            <p class="text-[11px] font-semibold text-slate-400">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                            </p>
                            @if ($item->last_login)
                            <p class="text-[10px] text-slate-300 mt-0.5">
                                Login {{ \Carbon\Carbon::parse($item->last_login)->diffForHumans() }}
                            </p>
                            @else
                            <p class="text-[10px] text-slate-300 mt-0.5">Belum pernah login</p>
                            @endif
                        </div>
                        <i class="fi fi-rr-angle-small-right flex text-lg text-slate-300 group-hover:text-violet-400 transition shrink-0"></i>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="flex min-h-[280px] flex-col items-center justify-center p-8 text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">
                        <i class="fi fi-rr-user-slash flex text-2xl"></i>
                    </div>
                    <p class="text-base font-black text-slate-800">Tidak Ada Data</p>
                    <p class="mt-1 text-sm text-slate-400">
                        @if ($cari_customer)
                            Tidak ada pengguna yang cocok dengan "<b>{{ $cari_customer }}</b>"
                        @else
                            Belum ada pengguna yang terdaftar.
                        @endif
                    </p>
                </div>
                @endif
                @if ($users->hasPages())
                <div class="border-t border-slate-100 p-4">
                    {{ $users->onEachSide(1)->appends(request()->query())->links('components.paginate.custom-pagination') }}
                </div>
                @endif
            </div>
        </div>

        {{-- KOLOM KANAN: Panel Sticky --}}
        <div class="w-full xl:w-[320px] shrink-0">
            <div class="flex flex-col gap-5 sticky top-4">

                {{-- Sedang Online --}}
                <div class="overflow-hidden rounded-[28px] border border-slate-100 bg-white shadow-sm">
                    <div class="border-b border-slate-100 p-5">
                        <div class="mb-1 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-emerald-600">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            </span>
                            Live
                        </div>
                        <h2 class="text-base font-black text-slate-900">Sedang Online</h2>
                        <p class="text-xs text-slate-400"><b class="text-emerald-600">{{ $count_user_online }}</b> pengguna aktif saat ini</p>
                    </div>
                    @if ($get_customer_online->count() > 0)
                    <div class="max-h-[240px] overflow-y-auto divide-y divide-slate-100">
                        @foreach ($get_customer_online as $item)
                        <div class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-50/30 transition">
                            <div class="relative shrink-0">
                                <img src="@userPhoto($item->foto)" class="h-9 w-9 rounded-xl object-cover" alt="">
                                <span class="absolute -bottom-0.5 -right-0.5 flex h-3 w-3">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex h-3 w-3 rounded-full border-2 border-white bg-emerald-500"></span>
                                </span>
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-slate-900">{{ $item->name }}</p>
                                <p class="text-[11px] text-emerald-500 font-semibold">Online</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="flex min-h-[120px] flex-col items-center justify-center p-6 text-center">
                        <i class="fi fi-rr-signal-alt-slash flex text-2xl text-slate-200 mb-2"></i>
                        <p class="text-xs font-semibold text-slate-400">Tidak ada pengguna online</p>
                    </div>
                    @endif
                </div>

                {{-- Sedang Sewa (Real Data) --}}
                <div class="overflow-hidden rounded-[28px] border border-slate-100 bg-white shadow-sm">
                    <div class="border-b border-slate-100 p-5">
                        <div class="mb-1 inline-flex items-center gap-1.5 rounded-full bg-violet-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-violet-600">
                            <i class="fi fi-rr-box-alt flex text-[10px]"></i>
                            Aktif
                        </div>
                        <h2 class="text-base font-black text-slate-900">Sedang Sewa</h2>
                        <p class="text-xs text-slate-400"><b class="text-violet-600">{{ $count_sedang_sewa }}</b> transaksi sewa berjalan</p>
                    </div>
                    @if ($get_customer_sedang_sewa->count() > 0)
                    <div class="max-h-[280px] overflow-y-auto divide-y divide-slate-100">
                        @foreach ($get_customer_sedang_sewa as $sewa)
                        <div class="px-4 py-3 hover:bg-violet-50/20 transition">
                            <div class="flex items-start gap-3">
                                <img src="@userPhoto($sewa->foto_penyewa)"
                                    class="h-9 w-9 shrink-0 rounded-xl object-cover mt-0.5" alt="">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-1">
                                        <p class="truncate text-sm font-bold text-slate-900">{{ $sewa->nama_penyewa }}</p>
                                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-black
                                            {{ $sewa->status_penyewaan === 'Aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                            {{ $sewa->status_penyewaan }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $sewa->nama_produk }}</p>
                                    <p class="text-[10px] text-slate-300">Mitra: {{ $sewa->nama_mitra }}</p>
                                    @if ($sewa->tanggal_mulai && $sewa->tanggal_selesai)
                                    <p class="mt-1 text-[10px] text-slate-400">
                                        {{ \Carbon\Carbon::parse($sewa->tanggal_mulai)->format('d M') }} –
                                        {{ \Carbon\Carbon::parse($sewa->tanggal_selesai)->format('d M Y') }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="flex min-h-[140px] flex-col items-center justify-center p-6 text-center">
                        <i class="fi fi-rr-box-open flex text-2xl text-slate-200 mb-2"></i>
                        <p class="text-xs font-semibold text-slate-400">Tidak ada transaksi sewa aktif</p>
                    </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
