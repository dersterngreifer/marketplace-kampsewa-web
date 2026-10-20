@extends('layouts.developers.ly-dashboard')

@section('content')
<div class="w-full px-4 py-5 sm:px-5 lg:px-6">

    {{-- Breadcrumb / Back --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('detail-pengguna.produk-disewakan', $user_id) }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm hover:bg-slate-50 transition active:scale-95">
                <i class="fi fi-rr-arrow-small-left flex text-lg"></i>
                Kembali ke Daftar Produk
            </a>
            <div class="flex items-center gap-2 text-sm text-slate-400">
                <span>/</span>
                <span class="font-semibold text-slate-600">{{ $name }}</span>
                <span>/</span>
                <span class="font-semibold text-violet-600 line-clamp-1">{{ $produk->nama }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if ($produk->status === 'Ditangguhkan')
                <form id="formPulihkanTop" action="{{ route('kelola-pengguna.produk-pulihkan', $produk->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="button" onclick="confirmPulihkanDetailProduk('formPulihkanTop', '{{ addslashes($produk->nama) }}')"
                        class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 text-xs sm:text-sm font-black text-white shadow-md shadow-emerald-600/30 transition hover:-translate-y-0.5 cursor-pointer">
                        <i class="fi fi-rr-check text-sm"></i>
                        <span>Pulihkan Produk</span>
                    </button>
                </form>
            @else
                <button type="button" onclick="openDetailSuspendModal()"
                    class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 hover:bg-rose-700 px-5 py-2.5 text-xs sm:text-sm font-black text-white shadow-md shadow-rose-600/30 transition hover:-translate-y-0.5 cursor-pointer">
                    <i class="fi fi-rr-ban text-sm"></i>
                    <span>Tangguhkan / Suspend Produk</span>
                </button>
            @endif
        </div>
    </div>

    {{-- Banner Peringatan Jika Produk Sedang Di-Suspend --}}
    @if ($produk->status === 'Ditangguhkan')
        <div class="mb-6 rounded-[28px] border-2 border-rose-300 bg-gradient-to-r from-rose-50 via-white to-rose-50/50 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-600 text-white shadow-md shadow-rose-600/30">
                        <i class="fi fi-rr-ban text-2xl"></i>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-600 px-3 py-1 text-xs font-black text-white shadow-xs">
                                <span class="h-2 w-2 rounded-full bg-white animate-ping"></span>
                                Produk Sedang Ditangguhkan (Suspend)
                            </span>
                            @if ($suspend_info && $suspend_info->status_revisi === 'sudah_diperbaiki')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-800 border border-emerald-300">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Mitra Telah Memperbaiki Produk Ini (Siap Ditinjau)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-800 border border-amber-300">
                                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                    Menunggu Perbaikan Dari Mitra
                                </span>
                            @endif
                        </div>
                        <h3 class="mt-2 text-base font-extrabold text-slate-900">
                            Alasan: <span class="text-rose-700">{{ $suspend_info->alasan ?? 'Pelanggaran syarat & ketentuan produk' }}</span>
                        </h3>
                        @if (!empty($suspend_info->catatan))
                            <p class="mt-1 text-xs text-slate-600 font-medium bg-white/90 p-3 rounded-xl border border-rose-100">
                                <span class="font-bold text-slate-700">Catatan Admin:</span> {{ $suspend_info->catatan }}
                            </p>
                        @endif
                        <p class="mt-2 text-[11px] text-slate-400">
                            Ditangguhkan oleh <strong>{{ $suspend_info->admin->name ?? 'Admin KampSewa' }}</strong> pada {{ $suspend_info->created_at ? $suspend_info->created_at->translatedFormat('d F Y, H:i') : '-' }}
                        </p>
                    </div>
                </div>

                <div class="shrink-0 flex items-center gap-3">
                    <form id="formPulihkanBanner" action="{{ route('kelola-pengguna.produk-pulihkan', $produk->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="button" onclick="confirmPulihkanDetailProduk('formPulihkanBanner', '{{ addslashes($produk->nama) }}')"
                            class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 hover:bg-emerald-700 px-5 py-3 text-xs sm:text-sm font-black text-white shadow-lg shadow-emerald-600/30 transition hover:-translate-y-0.5 cursor-pointer">
                            <i class="fi fi-rr-check text-sm"></i>
                            <span>Pulihkan &amp; Aktifkan Produk</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col gap-6 xl:flex-row">

        {{-- KIRI: Galeri Foto --}}
        <div class="w-full xl:w-[360px] shrink-0">
            <div class="overflow-hidden rounded-[28px] border border-slate-100 bg-white shadow-sm">
                {{-- Foto utama --}}
                <div class="relative overflow-hidden rounded-t-[28px] bg-slate-100">
                    <img id="mainPhoto"
                        class="h-[320px] w-full object-cover transition duration-300"
                        src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($produk) }}"
                        alt="{{ $produk->nama }}"
                        onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22400%22%20height%3D%22300%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20300%22%3E%3Crect%20fill%3D%22%23f1f5f9%22%20width%3D%22400%22%20height%3D%22300%22%2F%3E%3Ctext%20fill%3D%22%2394a3b8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%3EKampSewa%20Produk%3C%2Ftext%3E%3C%2Fsvg%3E';">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-t-[28px]"></div>
                    {{-- Status badge --}}
                    <div class="absolute left-4 top-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold shadow
                            {{ $produk->status === 'Tersedia' ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-white' }}">
                            <span class="relative flex h-1.5 w-1.5">
                                @if ($produk->status === 'Tersedia')
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"></span>
                                @endif
                                <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-white"></span>
                            </span>
                            {{ $produk->status }}
                        </span>
                    </div>
                </div>
                {{-- Thumbnail grid --}}
                @if ($produk->foto->count() > 0)
                <div class="grid grid-cols-4 gap-2 p-3">
                    @foreach ($produk->foto as $p)
                    <button type="button"
                        onclick="document.getElementById('mainPhoto').src='{{ \App\Helpers\PhotoHelper::getPhotoUrl($p->url_foto, $p->tipe_sumber) }}'"
                        class="overflow-hidden rounded-xl border-2 border-transparent hover:border-violet-400 transition">
                        <img class="h-16 w-full object-cover"
                            src="{{ \App\Helpers\PhotoHelper::getPhotoUrl($p->url_foto, $p->tipe_sumber) }}" alt=""
                            onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22400%22%20height%3D%22300%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20300%22%3E%3Crect%20fill%3D%22%23f1f5f9%22%20width%3D%22400%22%20height%3D%22300%22%2F%3E%3Ctext%20fill%3D%22%2394a3b8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%3EKampSewa%20Produk%3C%2Ftext%3E%3C%2Fsvg%3E';">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Mini Stats --}}
            <div class="mt-4 grid grid-cols-3 gap-3">
                <div class="rounded-[20px] border border-slate-100 bg-white p-4 shadow-sm text-center">
                    <p class="text-2xl font-black text-violet-600">{{ $stok_produk }}</p>
                    <p class="mt-0.5 text-[11px] font-bold uppercase tracking-wide text-slate-400">Stok</p>
                </div>
                <div class="rounded-[20px] border border-slate-100 bg-white p-4 shadow-sm text-center">
                    <p class="text-2xl font-black text-emerald-600">{{ $total_disewa }}</p>
                    <p class="mt-0.5 text-[11px] font-bold uppercase tracking-wide text-slate-400">Disewa</p>
                </div>
                <div class="rounded-[20px] border border-slate-100 bg-white p-4 shadow-sm text-center">
                    <p class="text-2xl font-black text-amber-500">{{ $rata_rata_rating > 0 ? $rata_rata_rating : '—' }}</p>
                    <p class="mt-0.5 text-[11px] font-bold uppercase tracking-wide text-slate-400">Rating</p>
                </div>
            </div>
        </div>

        {{-- KANAN: Detail Produk --}}
        <div class="flex flex-1 flex-col gap-5 min-w-0">

            {{-- Header --}}
            <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-400">{{ $produk->kategori }}</p>
                        <h1 class="text-2xl font-black text-slate-900">{{ $produk->nama }}</h1>
                        <div class="mt-2 flex flex-wrap items-center gap-3">
                            {{-- Rating --}}
                            @if ($total_rating > 0)
                            <div class="flex items-center gap-1.5">
                                @for ($s = 1; $s <= 5; $s++)
                                    <i class="fi fi-{{ $s <= round($rata_rata_rating) ? 'ss' : 'rr' }}-star flex text-[13px] {{ $s <= round($rata_rata_rating) ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                @endfor
                                <span class="text-sm font-bold text-slate-700">{{ $rata_rata_rating }}</span>
                                <span class="text-sm text-slate-400">({{ $total_rating }} ulasan)</span>
                            </div>
                            @else
                            <span class="text-sm text-slate-400">Belum ada rating</span>
                            @endif
                            <span class="text-slate-300">•</span>
                            <span class="text-sm font-semibold text-slate-500">{{ $total_disewa }}x disewa</span>
                        </div>
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-xs font-bold text-slate-400">Mulai dari</p>
                        <p class="text-3xl font-black text-slate-900">
                            Rp {{ number_format($harga_sewa_terkecil ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="text-xs font-bold text-slate-400">/hari</p>
                    </div>
                </div>

                <div class="mt-5 border-t border-slate-100 pt-5">
                    <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi Produk</p>
                    <p class="text-sm leading-relaxed text-slate-600">
                        {{ $produk->deskripsi ?: 'Produk ini belum memiliki deskripsi.' }}
                    </p>
                </div>
            </div>

            {{-- Info Mitra --}}
            <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-sm">
                <p class="mb-4 text-sm font-black text-slate-700">Informasi Mitra Pemilik</p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('detail-pengguna.index', $user_id) }}"
                        class="group flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 hover:bg-violet-50 hover:border-violet-200 transition flex-1">
                        <i class="fi fi-rr-user flex h-10 w-10 items-center justify-center rounded-xl bg-white text-violet-500 text-lg shadow-sm"></i>
                        <div>
                            <p class="text-sm font-black text-slate-900 group-hover:text-violet-700 transition">{{ $name }}</p>
                            <p class="text-xs text-slate-400">Lihat profil mitra</p>
                        </div>
                        <i class="fi fi-rr-angle-small-right flex text-slate-300 group-hover:text-violet-400 transition ml-auto"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Suspend Produk --}}
    <div id="modalDetailSuspend" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 overflow-y-auto">
        <div class="w-full max-w-lg overflow-hidden rounded-3xl border border-rose-200 bg-white shadow-2xl animate-fadeIn">
            <div class="flex items-center justify-between border-b border-rose-100 bg-gradient-to-r from-rose-50 via-white to-white px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-rose-600 text-white shadow-md shadow-rose-600/30">
                        <i class="fi fi-rr-ban text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Tangguhkan / Suspend Produk</h3>
                        <p class="text-xs font-medium text-slate-500">Antisipasi foto penipuan, berbau seksual, atau pelanggaran</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetailSuspendModal()"
                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 cursor-pointer transition">
                    <i class="fi fi-rr-cross-small text-lg"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('kelola-pengguna.produk-suspend', $produk->id) }}" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-1">Produk Yang Di-Suspend</label>
                    <p class="text-sm font-extrabold text-slate-800 bg-slate-50 p-3 rounded-2xl border border-slate-200 line-clamp-2">
                        {{ $produk->nama }}
                    </p>
                </div>

                <div>
                    <label for="detail_alasan_suspend" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">
                        Alasan Penangguhan <span class="text-rose-500">*</span>
                    </label>
                    <select id="detail_alasan_suspend" name="alasan" required onchange="handleDetailAlasanChange(this.value)"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs sm:text-sm font-bold text-slate-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-500/20">
                        <option value="">-- Pilih Alasan Utama --</option>
                        <option value="Foto produk terindikasi penipuan / palsu / scam">Foto produk terindikasi penipuan / palsu / scam</option>
                        <option value="Foto produk mengandung unsur seksual / pornografi / tidak senonoh">Foto produk mengandung unsur seksual / pornografi / tidak senonoh</option>
                        <option value="Foto atau deskripsi melanggar norma / kekerasan / SARA">Foto atau deskripsi melanggar norma / kekerasan / SARA</option>
                        <option value="Informasi produk tidak sesuai dengan barang aslinya">Informasi produk tidak sesuai dengan barang aslinya</option>
                        <option value="Produk melanggar syarat dan ketentuan KampSewa">Produk melanggar syarat dan ketentuan KampSewa</option>
                        <option value="Lainnya">Lainnya (Tulis manual di catatan)</option>
                    </select>
                </div>

                <div>
                    <label for="detail_catatan_suspend" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">
                        Catatan &amp; Instruksi Perbaikan Untuk Mitra <span class="text-slate-400 font-medium">(Opsional)</span>
                    </label>
                    <textarea id="detail_catatan_suspend" name="catatan" rows="3"
                        placeholder="Tuliskan catatan detail bagian mana yang harus diperbaiki oleh pemilik produk (misal: ganti foto utama dengan foto asli)..."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs sm:text-sm font-medium text-slate-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-500/20"></textarea>
                </div>

                <div class="rounded-2xl bg-amber-50 border border-amber-200 p-3 flex items-start gap-2.5 text-xs text-amber-800">
                    <i class="fi fi-rr-info text-amber-600 mt-0.5 text-sm"></i>
                    <span>Notifikasi penangguhan dan instruksi perbaikan akan otomatis dikirimkan ke akun mitra pemilik produk secara real-time.</span>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeDetailSuspendModal()"
                        class="rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 hover:bg-rose-700 px-5 py-2.5 text-xs font-black text-white shadow-md shadow-rose-600/30 transition hover:-translate-y-0.5 cursor-pointer">
                        <i class="fi fi-rr-ban text-xs"></i>
                        <span>Suspend Produk</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmPulihkanDetailProduk(formId, namaProduk) {
        const namaText = namaProduk ? `"${namaProduk}"` : 'produk ini';

        Swal.fire({
            title: 'Pulihkan Produk Ini?',
            html: `
                <div class="text-left text-sm text-slate-600 space-y-3 mt-2">
                    <p>Apakah Anda yakin ingin memulihkan dan mengaktifkan kembali produk <span class="font-bold text-slate-900">${namaText}</span>?</p>
                    <div class="p-3.5 bg-emerald-50 rounded-2xl border border-emerald-200 text-xs text-emerald-800 flex items-start gap-2.5">
                        <i class="fi fi-rr-info text-emerald-600 shrink-0 mt-0.5 text-sm"></i>
                        <span>Produk akan kembali berstatus <b>Tersedia</b> dan langsung tampil di marketplace sehingga dapat disewa kembali oleh seluruh customer.</span>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fi fi-rr-check mr-1.5"></i> Ya, Pulihkan Sekarang',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[28px] p-6 shadow-2xl border border-slate-100',
                title: 'text-[20px] font-black text-slate-900 pt-2',
                actions: 'gap-3 mt-4 w-full flex justify-end',
                confirmButton: 'inline-flex items-center justify-center gap-1.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-600/30 transition hover:-translate-y-0.5 cursor-pointer',
                cancelButton: 'rounded-2xl bg-slate-100 hover:bg-slate-200 px-6 py-3 text-sm font-bold text-slate-600 transition cursor-pointer',
            },
            buttonsStyling: false,
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById(formId);
                if (form) form.submit();
            }
        });
    }

    function openDetailSuspendModal() {
        const modal = document.getElementById('modalDetailSuspend');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDetailSuspendModal() {
        const modal = document.getElementById('modalDetailSuspend');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function handleDetailAlasanChange(val) {
        const catatan = document.getElementById('detail_catatan_suspend');
        if (val === 'Lainnya') {
            catatan.required = true;
            catatan.placeholder = 'Wajib menuliskan rincian alasan penangguhan di sini...';
            catatan.focus();
        } else {
            catatan.required = false;
            catatan.placeholder = 'Tuliskan catatan detail bagian mana yang harus diperbaiki oleh pemilik produk (misal: ganti foto utama dengan foto asli)...';
        }
    }
</script>
@endsection
