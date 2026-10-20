@extends('layouts.developers.ly-dashboard')

@include('components.modals.edit-profile')

@section('content')
    <div class="--container w-full min-h-screen pb-12">
        {{-- Banner Header --}}
        <div class="relative w-full h-[220px] sm:h-[260px] overflow-hidden bg-gradient-to-r from-[#080E2E] via-[#1E2756] to-[#5038ED]">
            <img class="w-full h-full object-cover mix-blend-overlay opacity-30"
                src="{{ asset('images/pexels-toulouse-3195757.jpg') }}" alt="Profile Banner">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
            
            {{-- Breadcrumb & Header info --}}
            <div class="absolute top-6 left-6 right-6 flex flex-wrap items-center justify-between gap-3 text-white/90">
                <div class="flex items-center gap-2 text-xs font-semibold">
                    <a href="{{ route('home.index') }}" class="hover:text-white transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-white/60">Profile Admin</span>
                    <span>/</span>
                    <span class="text-white font-bold">{{ $user->name }}</span>
                </div>

                @if (!$isOwnProfile)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold border border-white/20">
                        <i class="bi bi-person-badge-fill text-amber-300"></i>
                        <span>Profil Rekan Admin: {{ $user->name }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Main Content Section --}}
        <div class="px-4 sm:px-8 -mt-20 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- Kolom Kiri: Detail Profil Admin --}}
                <div class="lg:col-span-4 flex flex-col gap-4">
                    {{-- User Profile Card --}}
                    <div class="bg-white rounded-3xl p-6 shadow-box-shadow-11 border border-slate-100 flex flex-col items-center text-center">
                        <div class="relative mb-3">
                            @include('components.ui.avatar', [
                                'nama' => $user->name,
                                'foto' => $user->foto,
                                'type' => 1,
                                'class' => 'w-[100px] h-[100px]',
                                'rounded' => 'rounded-full',
                                'text' => 'text-3xl',
                                'extra' => 'border-4 border-white shadow-lg outline outline-2 outline-[#5038ED]',
                            ])
                            <span class="absolute bottom-1 right-1 w-5 h-5 bg-emerald-500 border-2 border-white rounded-full" title="Status Aktif"></span>
                        </div>

                        <h2 class="text-lg font-black text-slate-900">{{ $user->name }}</h2>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">ID: #{{ $user->id }} &bull; {{ $user->email }}</p>

                        <div class="flex flex-wrap items-center justify-center gap-2 mt-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-100 flex items-center gap-1">
                                <i class="bi bi-shield-lock-fill text-violet-600"></i> Admin KampSewa
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center gap-1">
                                <i class="bi bi-check-circle-fill text-emerald-600"></i> Aktif
                            </span>
                        </div>

                        @if ($isOwnProfile)
                            <button type="button" onclick="modalEditProfileHandler(true)"
                                class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-[#5038ED] to-[#7B61FF] text-white text-xs font-bold shadow-md hover:opacity-95 transition active:scale-95">
                                <i class="fi fi-rr-edit text-xs"></i>
                                <span>Edit Profile Saya</span>
                            </button>
                        @endif

                        {{-- Ringkasan Input Keuangan Admin --}}
                        <div class="grid grid-cols-2 gap-3 w-full mt-5 pt-5 border-t border-slate-100 text-left">
                            <div class="p-3 bg-emerald-50/70 border border-emerald-100 rounded-2xl">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Input Pemasukan</p>
                                <p class="text-base font-black text-emerald-800 mt-1">{{ number_format($totalInputPemasukan, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-emerald-600 font-medium truncate">Rp. {{ number_format($totalNominalPemasukan, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 bg-rose-50/70 border border-rose-100 rounded-2xl">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-rose-700">Input Pengeluaran</p>
                                <p class="text-base font-black text-rose-800 mt-1">{{ number_format($totalInputPengeluaran, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-rose-600 font-medium truncate">Rp. {{ number_format($totalNominalPengeluaran, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Personal --}}
                    <div class="bg-white rounded-3xl p-5 shadow-box-shadow-11 border border-slate-100 flex flex-col gap-3">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-1">Informasi Kontak & Akun</h3>

                        <div class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-slate-50 transition">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                                <i class="bi bi-telephone-fill text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] text-slate-400">Nomor Telepon</p>
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $user->nomor_telephone ?? 'Belum diisi' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-slate-50 transition">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                                <i class="bi bi-envelope-at-fill text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] text-slate-400">Email KampSewa</p>
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-slate-50 transition">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                                <i class="bi bi-calendar3 text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] text-slate-400">Tanggal Lahir</p>
                                <p class="text-xs font-bold text-slate-800 truncate">
                                    {{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') : 'Belum diisi' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-slate-50 transition">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                                <i class="bi bi-gender-ambiguous text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] text-slate-400">Jenis Kelamin</p>
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $user->jenis_kelamin ?? 'Belum diisi' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-slate-50 transition">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                                <i class="bi bi-clock-history text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] text-slate-400">Bergabung Sejak</p>
                                <p class="text-xs font-bold text-slate-800 truncate">
                                    {{ Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Action Buttons --}}
                    <div class="flex gap-2">
                        <a href="{{ route('penghasilan.index') }}"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold shadow-xs transition">
                            <i class="fi fi-rr-arrow-trend-up text-emerald-600"></i>
                            <span>Ke Pemasukan</span>
                        </a>
                        <a href="{{ route('pengeluaran.index') }}"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold shadow-xs transition">
                            <i class="fi fi-rr-arrow-trend-down text-rose-600"></i>
                            <span>Ke Pengeluaran</span>
                        </a>
                    </div>
                </div>

                {{-- Kolom Tengah: History Input Pemasukan Manual --}}
                <div class="lg:col-span-4 flex flex-col gap-4">
                    <div class="bg-white rounded-3xl p-5 shadow-box-shadow-11 border border-slate-100 flex flex-col h-full min-h-[600px]">
                        {{-- Header Card Pemasukan --}}
                        <div class="flex items-start justify-between gap-3 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <h3 class="text-sm font-black text-slate-900">Riwayat Input Pemasukan</h3>
                                </div>
                                <p class="text-xs text-slate-400 mt-0.5">Transaksi pemasukan manual yang dicatat oleh {{ $user->name }}.</p>
                            </div>
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-extrabold text-[11px] rounded-xl border border-emerald-100 shrink-0">
                                {{ number_format($totalInputPemasukan) }} Data
                            </span>
                        </div>

                        {{-- Total Header Banner --}}
                        <div class="mt-3 p-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-white/80 font-bold uppercase tracking-wider">Total Nominal Diinput</p>
                                <p class="text-base font-black text-white mt-0.5">Rp. {{ number_format($totalNominalPemasukan, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                                <i class="bi bi-wallet2 text-white text-base"></i>
                            </div>
                        </div>

                        {{-- List Pemasukan --}}
                        <div class="flex-1 flex flex-col gap-2.5 mt-3 overflow-y-auto max-h-[520px] pr-1">
                            @forelse ($historyPemasukan as $item)
                                <div class="p-3.5 rounded-2xl bg-slate-50/80 hover:bg-slate-100/80 border border-slate-100 transition flex flex-col gap-1.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-lg bg-emerald-100/70 text-emerald-800 border border-emerald-200">
                                            {{ $item->sumber }}
                                        </span>
                                        <span class="text-[13px] font-black text-emerald-600 whitespace-nowrap">
                                            +Rp. {{ number_format($item->nominal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-700 line-clamp-2 leading-relaxed">
                                        {{ $item->deskripsi }}
                                    </p>
                                    <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1 border-t border-slate-200/60">
                                        <span><i class="bi bi-calendar3 mr-1"></i>{{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}</span>
                                        <span class="font-bold text-slate-500">ID: #{{ $item->id }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-2">
                                        <i class="bi bi-inbox text-xl"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-600">Belum Ada Pemasukan</p>
                                    <p class="text-[11px] text-slate-400 max-w-[200px] mt-0.5">Admin ini belum pernah mencatat transaksi pemasukan manual.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        @if ($historyPemasukan->hasPages())
                            <div class="pt-3 border-t border-slate-100 mt-2">
                                {{ $historyPemasukan->links('components.paginate.custom-pagination') }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Kolom Kanan: History Input Pengeluaran --}}
                <div class="lg:col-span-4 flex flex-col gap-4">
                    <div class="bg-white rounded-3xl p-5 shadow-box-shadow-11 border border-slate-100 flex flex-col h-full min-h-[600px]">
                        {{-- Header Card Pengeluaran --}}
                        <div class="flex items-start justify-between gap-3 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                    <h3 class="text-sm font-black text-slate-900">Riwayat Input Pengeluaran</h3>
                                </div>
                                <p class="text-xs text-slate-400 mt-0.5">Transaksi pengeluaran yang dicatat oleh {{ $user->name }}.</p>
                            </div>
                            <span class="px-2.5 py-1 bg-rose-50 text-rose-700 font-extrabold text-[11px] rounded-xl border border-rose-100 shrink-0">
                                {{ number_format($totalInputPengeluaran) }} Data
                            </span>
                        </div>

                        {{-- Total Header Banner --}}
                        <div class="mt-3 p-3 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-600 text-white shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-white/80 font-bold uppercase tracking-wider">Total Nominal Diinput</p>
                                <p class="text-base font-black text-white mt-0.5">Rp. {{ number_format($totalNominalPengeluaran, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                                <i class="bi bi-cash-coin text-white text-base"></i>
                            </div>
                        </div>

                        {{-- List Pengeluaran --}}
                        <div class="flex-1 flex flex-col gap-2.5 mt-3 overflow-y-auto max-h-[520px] pr-1">
                            @forelse ($historyPengeluaran as $item)
                                @php
                                    $catBadge = 'bg-rose-100/70 text-rose-800 border-rose-200';
                                    if (stripos($item->kategori_pengeluaran, 'cloud') !== false || stripos($item->kategori_pengeluaran, 'server') !== false) {
                                        $catBadge = 'bg-blue-100/70 text-blue-800 border-blue-200';
                                    } elseif (stripos($item->kategori_pengeluaran, 'operasional') !== false) {
                                        $catBadge = 'bg-amber-100/70 text-amber-800 border-amber-200';
                                    } elseif (stripos($item->kategori_pengeluaran, 'gaji') !== false) {
                                        $catBadge = 'bg-emerald-100/70 text-emerald-800 border-emerald-200';
                                    }
                                @endphp
                                <div class="p-3.5 rounded-2xl bg-slate-50/80 hover:bg-slate-100/80 border border-slate-100 transition flex flex-col gap-1.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-lg border {{ $catBadge }}">
                                                {{ $item->kategori_pengeluaran ?? 'Operasional' }}
                                            </span>
                                            <span class="text-xs font-bold text-slate-800">{{ $item->sumber }}</span>
                                        </div>
                                        <span class="text-[13px] font-black text-rose-600 whitespace-nowrap">
                                            -Rp. {{ number_format($item->nominal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-700 line-clamp-2 leading-relaxed">
                                        {{ $item->deskripsi }}
                                    </p>
                                    <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1 border-t border-slate-200/60">
                                        <span><i class="bi bi-calendar3 mr-1"></i>{{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}</span>
                                        <span class="font-bold text-slate-500">ID: #{{ $item->id }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mb-2">
                                        <i class="bi bi-inbox text-xl"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-600">Belum Ada Pengeluaran</p>
                                    <p class="text-[11px] text-slate-400 max-w-[200px] mt-0.5">Admin ini belum pernah mencatat transaksi pengeluaran perusahaan.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        @if ($historyPengeluaran->hasPages())
                            <div class="pt-3 border-t border-slate-100 mt-2">
                                {{ $historyPengeluaran->links('components.paginate.custom-pagination') }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
