@extends('layouts.developers.ly-dashboard')

@section('content')
<div class="w-full px-4 py-5 sm:px-5 lg:px-6">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900">Manajemen Iklan</h1>
            <p class="mt-1 text-sm font-medium text-slate-500">
                Pantau dan kelola semua iklan mitra yang aktif, antrian, maupun yang akan kadaluarsa.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex items-center gap-2 rounded-full bg-amber-50 px-4 py-2 text-sm font-bold text-amber-600">
                <i class="fi fi-rr-hourglass flex text-[14px]"></i>
                {{ $get_count_total_iklan_pending }} antrian
            </div>
            <div class="flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-sm font-bold text-emerald-600">
                <i class="fi fi-rr-megaphone flex text-[14px]"></i>
                {{ $get_count_total_iklan_aktif }} aktif
            </div>
            <div class="flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm font-bold text-slate-500">
                <i class="fi fi-rr-check flex text-[14px]"></i>
                {{ $get_count_total_iklan_selesai }} selesai
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-[24px] bg-white p-5 shadow-sm border border-slate-100">
            <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                <i class="fi fi-rr-receipt flex text-[18px]"></i>
            </div>
            <p class="text-xs font-black uppercase tracking-wide text-slate-400">Total Transaksi Iklan</p>
            <p class="mt-1 text-3xl font-black text-slate-900">{{ $get_count_total_transaksi_iklan }}</p>
        </div>
        <div class="rounded-[24px] bg-white p-5 shadow-sm border border-slate-100">
            <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                <i class="fi fi-rr-wallet flex text-[18px]"></i>
            </div>
            <p class="text-xs font-black uppercase tracking-wide text-slate-400">Pendapatan Iklan Bulan Ini</p>
            <p class="mt-1 text-2xl font-black text-slate-900">Rp {{ number_format($pendapatan_iklan_bulan_ini, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-[24px] bg-white p-5 shadow-sm border border-slate-100">
            <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                <i class="fi fi-rr-percentage flex text-[18px]"></i>
            </div>
            <p class="text-xs font-black uppercase tracking-wide text-slate-400">Pajak Transaksi Bulan Ini</p>
            <p class="mt-1 text-2xl font-black text-slate-900">Rp {{ number_format($pajak_bulan_ini, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-[24px] bg-white p-5 shadow-sm border border-slate-100">
            <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-2xl bg-red-50 text-red-500">
                <i class="fi fi-rr-alarm-clock flex text-[18px]"></i>
            </div>
            <p class="text-xs font-black uppercase tracking-wide text-slate-400">Iklan Hampir Kadaluarsa</p>
            <p class="mt-1 text-3xl font-black text-slate-900">{{ $iklan_hampir_expired->count() }}</p>
        </div>
    </div>

    <div class="flex flex-col gap-6 xl:flex-row">
        {{-- Kolom Kiri --}}
        <div class="flex flex-1 flex-col gap-6">

            {{-- Alert Iklan Hampir Kadaluarsa --}}
            @if ($iklan_hampir_expired->count() > 0)
            <div class="w-full overflow-hidden rounded-[28px] border border-red-200/60 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5 sm:p-6">
                    <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-red-600">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-red-500"></span>
                        </span>
                        Perlu Tindakan
                    </div>
                    <h2 class="text-xl font-black text-slate-900">Iklan Hampir Kadaluarsa</h2>
                    <p class="mt-1 text-sm text-slate-400">Iklan berikut akan berakhir dalam 3 hari ke depan. Hubungi mitra untuk perpanjangan.</p>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($iklan_hampir_expired as $item)
                    <div class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <img src="@userPhoto($item->foto)" class="h-10 w-10 rounded-2xl object-cover" alt="">
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $item->name }}</p>
                                <p class="text-xs text-slate-400 line-clamp-1">{{ $item->judul }}</p>
                            </div>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-xs font-bold text-red-500">Berakhir {{ \Carbon\Carbon::parse($item->tanggal_akhir)->diffForHumans() }}</p>
                            <p class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('iklan.selesaikan', $item->id_detail_iklan) }}">
                                @csrf @method('PUT')
                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-200 transition">
                                    <i class="fi fi-rr-check flex text-[11px]"></i> Selesaikan
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Antrian Iklan Pending --}}
            <div class="w-full overflow-hidden rounded-[28px] border border-slate-200/70 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5 sm:p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-amber-600">
                                <i class="fi fi-rr-hourglass flex text-[10px]"></i>
                                Menunggu Giliran
                            </div>
                            <h2 class="text-xl font-black text-slate-900">Antrian Iklan</h2>
                            <p class="mt-1 text-sm text-slate-400">{{ $get_count_total_iklan_pending }} iklan mitra menunggu jadwal tampil.</p>
                        </div>
                        {{-- Search --}}
                        <form method="GET" action="{{ route('iklan.index') }}" class="flex gap-2">
                            <div class="relative">
                                <i class="fi fi-rr-search absolute left-4 top-1/2 -translate-y-1/2 flex text-slate-400 text-[14px]"></i>
                                <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari mitra/judul..."
                                    class="h-11 w-64 rounded-2xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm font-medium text-slate-700 outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-100">
                            </div>
                        </form>
                    </div>
                </div>

                @if ($user_pending->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach ($user_pending as $user)
                    <div class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-xs font-black text-slate-500">
                                {{ $loop->iteration }}
                            </span>
                            <img src="@userPhoto($user->foto)" class="h-10 w-10 rounded-2xl object-cover shrink-0" alt="">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $user->judul }}</p>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            {{-- Status Midtrans --}}
                            @php
                            $midtransStatus = $user->midtrans_transaction_status ?? null;
                            $statusBayar = $user->status_bayar ?? '-';
                            @endphp
                            @if ($statusBayar === 'lunas' || $midtransStatus === 'settlement')
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold text-emerald-600">Lunas (Midtrans)</span>
                            @elseif ($midtransStatus === 'pending')
                            <span class="rounded-full bg-amber-50 px-3 py-1 text-[11px] font-bold text-amber-600">Bayar Pending</span>
                            @else
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500">{{ ucfirst($statusBayar) }}</span>
                            @endif

                            <span class="text-xs font-semibold text-slate-400">
                                {{ \Carbon\Carbon::parse($user->tanggal_mulai)->format('d M Y') }}
                            </span>

                            {{-- Aksi --}}
                            @if ($statusBayar === 'lunas' || $midtransStatus === 'settlement')
                            <form method="POST" action="{{ route('iklan.aktivasi', $user->id_detail_iklan) }}">
                                @csrf @method('PUT')
                                <button type="submit" title="Aktifkan iklan"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-500 px-3 py-2 text-xs font-bold text-white hover:bg-emerald-600 transition active:scale-95">
                                    <i class="fi fi-rr-megaphone flex text-[11px]"></i> Aktifkan
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('iklan.aktivasi', $user->id_detail_iklan) }}">
                                @csrf @method('PUT')
                                <button type="submit" title="Aktivasi manual"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-violet-50 px-3 py-2 text-xs font-bold text-violet-600 hover:bg-violet-600 hover:text-white transition active:scale-95">
                                    <i class="fi fi-rr-bolt flex text-[11px]"></i> Aktivasi Manual
                                </button>
                            </form>
                            @endif

                            <form id="form_delete_{{ $user->id_iklan_main }}"
                                action="{{ route('iklan.delete-iklan-pending', ['id_iklan' => $user->id_iklan_main]) }}"
                                method="POST">
                                @csrf @method('DELETE')
                                <button type="button" data-id="{{ $user->id_iklan_main }}"
                                    onclick="confirmDelete(this.dataset.id)"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition active:scale-95">
                                    <i class="fi fi-rr-trash flex text-[13px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex min-h-[200px] flex-col items-center justify-center p-8 text-center">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">
                        <i class="fi fi-rr-megaphone flex text-xl"></i>
                    </div>
                    <p class="text-base font-black text-slate-900">Tidak Ada Antrian</p>
                    <p class="mt-1 text-sm text-slate-400">Semua iklan sudah diproses.</p>
                </div>
                @endif

                @if ($user_pending->hasPages())
                <div class="border-t border-slate-100 p-4">
                    {{ $user_pending->onEachSide(1)->links('components.paginate.custom-pagination') }}
                </div>
                @endif
            </div>

            {{-- Riwayat Iklan Selesai --}}
            <div class="w-full overflow-hidden rounded-[28px] border border-slate-200/70 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5 sm:p-6">
                    <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-slate-500">
                        <i class="fi fi-rr-time-past flex text-[10px]"></i> Riwayat
                    </div>
                    <h2 class="text-xl font-black text-slate-900">Riwayat Iklan Selesai</h2>
                    <p class="mt-1 text-sm text-slate-400">{{ $get_count_iklan_selesai }} iklan sudah selesai masa tayangnya.</p>
                </div>

                @if ($data_iklan_selesai->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach ($data_iklan_selesai as $item)
                    <div class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-xs font-black text-slate-500">
                                {{ $loop->iteration }}
                            </span>
                            <img src="@userPhoto($item->foto)" class="h-10 w-10 rounded-2xl object-cover shrink-0" alt="">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $item->name }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $item->judul }}</p>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500">{{ $item->status_iklan }}</span>
                            <span class="text-xs text-slate-400">Berakhir {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex min-h-[160px] items-center justify-center p-8 text-center">
                    <p class="text-sm font-semibold text-slate-400">Belum ada riwayat iklan selesai.</p>
                </div>
                @endif

                @if ($data_iklan_selesai->hasPages())
                <div class="border-t border-slate-100 p-4">
                    {{ $data_iklan_selesai->onEachSide(1)->links('components.paginate.custom-pagination') }}
                </div>
                @endif
            </div>

        </div>

        {{-- Kolom Kanan: Iklan Sedang Aktif (sticky) --}}
        <div class="w-full xl:w-[340px] shrink-0">
            <div class="sticky top-4">
                <div class="overflow-hidden rounded-[28px] border border-slate-200/70 bg-white shadow-sm">
                    <div class="border-b border-slate-100 p-5">
                        <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-emerald-600">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            </span>
                            Live
                        </div>
                        <h2 class="text-lg font-black text-slate-900">Iklan Sedang Aktif</h2>
                        <p class="mt-0.5 text-sm text-slate-400"><b>{{ $get_count_total_iklan_aktif }}</b> iklan sedang tayang.</p>
                    </div>

                    <div class="max-h-[calc(100vh-280px)] overflow-y-auto divide-y divide-slate-100">
                        @forelse ($data_iklan_aktif as $user)
                        <div class="p-4">
                            <div class="relative overflow-hidden rounded-2xl">
                                @php
                                    $posterSrc     = str_starts_with($user->poster, 'http')
                                        ? $user->poster
                                        : asset('assets/image/customers/advert/' . $user->poster);
                                    $posterFallback = asset('assets/image/default-poster.jpg');
                                @endphp
                                <img class="h-[140px] w-full object-cover rounded-2xl"
                                    src="{{ $posterSrc }}"
                                    alt="{{ $user->judul }}"
                                    onerror="this.src='{{ $posterFallback }}'; this.onerror=null;"
                                    loading="lazy">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 rounded-b-2xl">
                                    <p class="text-xs font-bold text-white line-clamp-1">{{ $user->judul }}</p>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <img src="@userPhoto($user->foto)" class="h-7 w-7 rounded-xl object-cover" alt="">
                                    <p class="text-xs font-bold text-slate-700">{{ $user->name }}</p>
                                </div>
                                <div class="text-right">
                                    @php
                                    $sisaHari = \Carbon\Carbon::parse($user->tanggal_akhir)->diffInDays(\Carbon\Carbon::today());
                                    @endphp
                                    <p class="text-[11px] font-bold {{ $sisaHari <= 1 ? 'text-red-500' : 'text-slate-400' }}">
                                        {{ $sisaHari }} hari lagi
                                    </p>
                                    <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($user->tanggal_akhir)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="mt-2 flex gap-2">
                                <form method="POST" action="{{ route('iklan.nonaktifkan', $user->id_detail_iklan) }}" class="flex-1">
                                    @csrf @method('PUT')
                                    <button type="submit" class="w-full rounded-xl bg-red-50 py-2 text-xs font-bold text-red-500 hover:bg-red-500 hover:text-white transition active:scale-95">
                                        Nonaktifkan
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('iklan.selesaikan', $user->id_detail_iklan) }}" class="flex-1">
                                    @csrf @method('PUT')
                                    <button type="submit" class="w-full rounded-xl bg-slate-100 py-2 text-xs font-bold text-slate-600 hover:bg-slate-200 transition active:scale-95">
                                        Selesaikan
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="flex min-h-[200px] flex-col items-center justify-center p-6 text-center">
                            <i class="fi fi-rr-megaphone flex text-2xl text-slate-300 mb-3"></i>
                            <p class="text-sm font-bold text-slate-400">Belum ada iklan aktif.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('sweetalert::alert')

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus iklan ini?',
            text: 'Iklan yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[28px] p-6 shadow-2xl',
                title: 'text-[20px] font-black text-slate-900',
                actions: 'gap-3',
                confirmButton: 'rounded-2xl bg-red-500 px-6 py-3 text-sm font-bold text-white',
                cancelButton: 'rounded-2xl bg-slate-100 px-6 py-3 text-sm font-bold text-slate-600',
            },
            buttonsStyling: false,
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('form_delete_' + id).submit();
            }
        });
    }
</script>
@endpush
@endsection