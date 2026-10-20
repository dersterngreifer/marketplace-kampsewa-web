@include('components.modals.tambah-admin-dev')
@include('components.modals.edit-admin-dev')

@extends('layouts.developers.ly-dashboard')

@section('content')
<div class="min-h-screen w-full bg-[#F6F7FB] px-4 py-6 sm:px-6 lg:px-8 flex flex-col gap-6">

    {{-- Header Banner --}}
    <div class="overflow-hidden rounded-[28px] bg-gradient-to-br from-[#080E2E] via-[#1E2756] to-[#5038ED] p-6 text-white shadow-xl sm:p-8 relative">
        <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-white/5 blur-xl"></div>
        <div class="absolute -bottom-16 left-1/3 w-64 h-64 rounded-full bg-violet-500/10 blur-2xl"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-fit rounded-full bg-white/15 px-3 py-1 text-xs font-semibold backdrop-blur text-white flex items-center gap-1.5 border border-white/20">
                        <i class="bi bi-shield-lock-fill text-amber-300"></i> Manajemen Administrator
                    </span>
                    <span class="w-fit rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-bold text-emerald-300 border border-emerald-400/30">
                        Full Hak Akses
                    </span>
                </div>
                <h1 class="text-2xl font-black tracking-tight sm:text-3xl text-white">
                    Kelola & Tambah Admin KampSewa
                </h1>
                <p class="mt-2 max-w-2xl text-xs sm:text-sm leading-relaxed text-slate-300">
                    Kelola data seluruh rekan administrator sistem Developer Panel. Semua admin memiliki hak akses setara dan fleksibel untuk mengelola, melihat profil, mengedit, maupun menghapus data rekan admin.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <button type="button" onclick="modalTambahAdminHandler(true)"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white text-slate-900 font-extrabold text-xs shadow-lg hover:bg-slate-100 transition active:scale-95">
                    <i class="bi bi-person-plus-fill text-violet-600 text-sm"></i>
                    <span>+ Tambah Admin Baru</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Section 1: 4 Kartu Statistik Admin --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Admin --}}
        <div class="bg-white rounded-3xl p-5 shadow-box-shadow-8 border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0">
                <i class="bi bi-people-fill text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Admin</p>
                <p class="text-xl font-black text-slate-900 mt-0.5">{{ number_format($totalAdmin) }} Orang</p>
                <p class="text-[11px] text-emerald-600 font-semibold">Semua status aktif</p>
            </div>
        </div>

        {{-- Card 2: Admin Laki-laki --}}
        <div class="bg-white rounded-3xl p-5 shadow-box-shadow-8 border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                <i class="bi bi-gender-male text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Admin Laki-laki</p>
                <p class="text-xl font-black text-slate-900 mt-0.5">{{ number_format($adminLakiCount) }} Orang</p>
                <p class="text-[11px] text-slate-400 font-medium">{{ $totalAdmin > 0 ? number_format(($adminLakiCount / $totalAdmin) * 100, 1) : 0 }}% dari total</p>
            </div>
        </div>

        {{-- Card 3: Admin Perempuan --}}
        <div class="bg-white rounded-3xl p-5 shadow-box-shadow-8 border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center shrink-0">
                <i class="bi bi-gender-female text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Admin Perempuan</p>
                <p class="text-xl font-black text-slate-900 mt-0.5">{{ number_format($adminPerempuanCount) }} Orang</p>
                <p class="text-[11px] text-slate-400 font-medium">{{ $totalAdmin > 0 ? number_format(($adminPerempuanCount / $totalAdmin) * 100, 1) : 0 }}% dari total</p>
            </div>
        </div>

        {{-- Card 4: Kontribusi Transaksi --}}
        <div class="bg-white rounded-3xl p-5 shadow-box-shadow-8 border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i class="bi bi-receipt-cutoff text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kontribusi Transaksi</p>
                <p class="text-xl font-black text-slate-900 mt-0.5">{{ number_format($totalPemasukanInput + $totalPengeluaranInput) }} Data</p>
                <p class="text-[10px] text-slate-400 truncate">{{ number_format($totalPemasukanInput) }} masuk &bull; {{ number_format($totalPengeluaranInput) }} keluar</p>
            </div>
        </div>
    </div>

    {{-- Section 2: Tabel Daftar Admin KampSewa --}}
    <div class="bg-white rounded-[28px] border border-slate-200/80 p-5 sm:p-7 shadow-box-shadow-8 flex flex-col gap-4">
        
        {{-- Toolbar: Counter, Search & Tombol Tambah --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center h-10 px-3.5 bg-violet-50 text-violet-700 text-xs font-bold rounded-xl border border-violet-100">
                    <i class="bi bi-shield-check text-violet-600 mr-1.5"></i> {{ number_format($adminList->total()) }} Administrator Terdaftar
                </span>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                {{-- Search Form --}}
                <form method="GET" action="{{ route('kelola-pengguna.create-admin') }}" class="relative m-0 p-0 flex items-center">
                    <input class="h-10 w-[240px] sm:w-[260px] pl-9 pr-8 text-xs font-medium text-slate-800 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                        type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, email, hp...">
                    <i class="bi bi-search absolute left-3 text-slate-400 text-xs pointer-events-none"></i>
                    @if (!empty($search))
                        <a href="{{ route('kelola-pengguna.create-admin') }}" class="absolute right-2.5 text-slate-400 hover:text-slate-600 text-xs" title="Reset pencarian">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                </form>

                <button type="button" onclick="modalTambahAdminHandler(true)"
                    class="inline-flex items-center justify-center h-10 px-4 gap-1.5 bg-[#5038ED] hover:bg-[#412CCB] text-white text-xs font-bold rounded-xl shadow-md transition active:scale-95">
                    <i class="bi bi-plus-lg text-sm"></i>
                    <span>Tambah Admin</span>
                </button>
            </div>
        </div>

        {{-- Table List Admin --}}
        <div class="relative w-full overflow-hidden rounded-2xl border border-slate-100">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-500">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-700 border-b border-slate-100 font-bold">
                        <tr>
                            <th scope="col" class="px-4 py-3.5 text-center">#</th>
                            <th scope="col" class="px-6 py-3.5">Administrator</th>
                            <th scope="col" class="px-6 py-3.5">Kontak</th>
                            <th scope="col" class="px-6 py-3.5">Gender & Lahir</th>
                            <th scope="col" class="px-6 py-3.5">Kontribusi Input</th>
                            <th scope="col" class="px-6 py-3.5">Bergabung</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($adminList as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-4 text-center text-xs font-medium text-slate-400">
                                    {{ $adminList->firstItem() + $index }}
                                </td>
                                
                                {{-- Administrator --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        @include('components.ui.avatar', [
                                            'nama' => $item->name,
                                            'foto' => $item->foto,
                                            'type' => 1,
                                            'class' => 'w-10 h-10 shrink-0',
                                            'rounded' => 'rounded-2xl',
                                            'text' => 'text-xs',
                                            'extra' => 'border border-slate-200 shadow-2xs',
                                        ])
                                        <div class="min-w-0">
                                            <a href="{{ route('profile.index', ['nama_lengkap' => $item->name]) }}"
                                                class="text-xs font-black text-slate-900 hover:text-violet-600 transition flex items-center gap-1">
                                                <span>{{ $item->name }}</span>
                                                <i class="bi bi-arrow-up-right text-[10px] text-slate-400"></i>
                                            </a>
                                            <p class="text-[11px] text-slate-400 truncate">{{ $item->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kontak --}}
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-slate-700">
                                    <div class="flex items-center gap-1.5">
                                        <i class="bi bi-telephone text-slate-400 text-xs"></i>
                                        <span>{{ $item->nomor_telephone ?? '-' }}</span>
                                    </div>
                                </td>

                                {{-- Gender & Lahir --}}
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold {{ $item->jenis_kelamin == 'Laki-laki' ? 'text-sky-600' : 'text-pink-600' }}">
                                            <i class="bi {{ $item->jenis_kelamin == 'Laki-laki' ? 'bi-gender-male' : 'bi-gender-female' }}"></i>
                                            {{ $item->jenis_kelamin ?? 'Belum Diisi' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $item->tanggal_lahir ? Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d M Y') : '-' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Kontribusi Input --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold" title="Pemasukan Diinput">
                                            <i class="bi bi-arrow-down-left mr-0.5"></i> {{ $item->pemasukan_perusahaan_count }} Masuk
                                        </span>
                                        <span class="px-2 py-0.5 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold" title="Pengeluaran Diinput">
                                            <i class="bi bi-arrow-up-right mr-0.5"></i> {{ $item->pengeluaran_perusahaan_count }} Keluar
                                        </span>
                                    </div>
                                </td>

                                {{-- Bergabung --}}
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center justify-center gap-1.5">
                                        {{-- Lihat Profil --}}
                                        <a href="{{ route('profile.index', ['nama_lengkap' => $item->name]) }}"
                                            class="w-8 h-8 inline-flex items-center justify-center text-slate-600 hover:text-violet-600 bg-slate-50 hover:bg-violet-50 rounded-xl transition shadow-2xs"
                                            title="Lihat Profil Admin">
                                            <i class="bi bi-person-badge text-sm"></i>
                                        </a>

                                        {{-- Edit Admin --}}
                                        <button type="button"
                                            onclick="openEditAdminModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ addslashes($item->email) }}', '{{ addslashes($item->nomor_telephone ?? '') }}', '{{ $item->tanggal_lahir ?? '' }}', '{{ $item->jenis_kelamin ?? '' }}')"
                                            class="w-8 h-8 inline-flex items-center justify-center text-violet-600 hover:text-violet-800 bg-violet-50 hover:bg-violet-100 rounded-xl transition shadow-2xs"
                                            title="Edit Data Admin">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </button>

                                        {{-- Hapus Admin --}}
                                        <form action="{{ route('kelola-pengguna.destroy-admin', $item->id) }}" method="POST" class="inline-flex items-center m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteAdmin(this.form, '{{ addslashes($item->name) }}')"
                                                class="w-8 h-8 inline-flex items-center justify-center text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 rounded-xl transition shadow-2xs"
                                                title="Hapus Admin">
                                                <i class="bi bi-trash-fill text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                        <i class="bi bi-inbox text-xl"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-600">Tidak ada administrator ditemukan</p>
                                    @if ($search)
                                        <a href="{{ route('kelola-pengguna.create-admin') }}" class="text-xs text-violet-600 underline mt-1 inline-block">Reset filter pencarian</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
            <p class="text-xs text-slate-500">
                Menampilkan {{ $adminList->firstItem() ?? 0 }} - {{ $adminList->lastItem() ?? 0 }} dari {{ $adminList->total() }} administrator
            </p>
            <div>
                {{ $adminList->links('components.paginate.custom-pagination') }}
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function modalTambahAdminHandler(val) {
        const modal = document.getElementById('modal-tambah-admin');
        if (modal) modal.classList.toggle('hidden', !val);
    }

    function modalEditAdminHandler(val) {
        const modal = document.getElementById('modal-edit-admin');
        if (modal) modal.classList.toggle('hidden', !val);
    }

    function openEditAdminModal(id, fullname, email, phone, birth, gender) {
        const form = document.getElementById('form-edit-admin');
        if (form) {
            form.action = `{{ url('/developer/dashboard/kelola-pengguna/admin') }}/${id}`;
        }
        const nameInput = document.getElementById('edit-admin-fullname');
        if (nameInput) nameInput.value = fullname || '';

        const emailInput = document.getElementById('edit-admin-email');
        if (emailInput) emailInput.value = email || '';

        const phoneInput = document.getElementById('edit-admin-phone');
        if (phoneInput) phoneInput.value = phone || '';

        const birthInput = document.getElementById('edit-admin-birth');
        if (birthInput) birthInput.value = birth || '';

        const genderSelect = document.getElementById('edit-admin-gender');
        if (genderSelect) genderSelect.value = gender || '';

        const passInput = document.getElementById('edit-admin-password');
        if (passInput) passInput.value = '';

        modalEditAdminHandler(true);
    }

    function confirmDeleteAdmin(form, name) {
        if (window.Swal) {
            Swal.fire({
                title: `Hapus Admin ${name}?`,
                text: 'Akun administrator ini akan dihapus secara permanen dari sistem.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#94A3B8',
                confirmButtonText: '<i class="bi bi-trash-fill mr-1"></i> Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl shadow-2xl border border-slate-100',
                    confirmButton: 'rounded-xl font-bold px-4 py-2.5 shadow-md',
                    cancelButton: 'rounded-xl font-bold px-4 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else if (confirm(`Apakah Anda yakin ingin menghapus admin ${name}?`)) {
            form.submit();
        }
    }
</script>
@endpush
@endsection
