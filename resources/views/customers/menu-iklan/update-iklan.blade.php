@extends('layouts.customers.layouts-customer')

@section('customer-content')
@php
    $posterUrl = null;
    if (!empty($data->poster)) {
        if (filter_var($data->poster, FILTER_VALIDATE_URL)) {
            $posterUrl = $data->poster;
        } elseif (str_starts_with($data->poster, 'assets/')) {
            $posterUrl = asset($data->poster);
        } else {
            $posterUrl = asset('assets/image/customers/advert/' . $data->poster);
        }
    }

    $durasi = ($detail_iklan && $detail_iklan->tanggal_mulai && $detail_iklan->tanggal_akhir)
        ? max(1, \Carbon\Carbon::parse($detail_iklan->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($detail_iklan->tanggal_akhir)))
        : null;

    $statusIklan = $detail_iklan ? $detail_iklan->status_iklan : ($data->status ?? 'Nonaktif');
@endphp

<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-6">
    {{-- Breadcrumb & Back Link --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('kelola-iklan.index', ['id_user' => Crypt::encrypt(session('id_user') ?? auth()->user()->id)]) }}"
           class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors">
            <i class="bi bi-arrow-left-short text-2xl mr-1"></i> Kembali ke Kelola Iklan
        </a>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold text-gray-400">ID Iklan: #{{ $data->id }}</span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border
                @if (strcasecmp($statusIklan, 'Aktif') === 0)
                    bg-emerald-50 text-emerald-700 border-emerald-200
                @elseif (strcasecmp($statusIklan, 'Pending') === 0)
                    bg-amber-50 text-amber-700 border-amber-200
                @elseif (strcasecmp($statusIklan, 'Nonaktif') === 0)
                    bg-red-50 text-red-700 border-red-200
                @elseif (strcasecmp($statusIklan, 'Selesai') === 0)
                    bg-slate-50 text-slate-700 border-slate-200
                @else
                    bg-blue-50 text-blue-700 border-blue-200
                @endif
            ">
                <span class="w-1.5 h-1.5 rounded-full
                    @if (strcasecmp($statusIklan, 'Aktif') === 0) bg-emerald-500
                    @elseif (strcasecmp($statusIklan, 'Pending') === 0) bg-amber-500
                    @elseif (strcasecmp($statusIklan, 'Nonaktif') === 0) bg-red-500
                    @else bg-blue-500
                    @endif
                "></span>
                {{ $statusIklan }}
            </span>
        </div>
    </div>

    {{-- Main Card Form --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="p-6 sm:p-8 lg:px-10 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 via-white to-blue-50/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-100/70 text-blue-700 font-bold text-xs rounded-full border border-blue-200 mb-3">
                    <i class="bi bi-pencil-square text-xs"></i> Edit Data Materi Iklan
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Perbarui Materi Banner Iklan</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium max-w-2xl leading-relaxed">
                    Perbarui judul, slogan, deskripsi lengkap, atau ganti file gambar poster iklan agar tampilan promosi toko Anda tetap menarik dan relevan bagi calon penyewa.
                </p>
            </div>

            @if ($detail_iklan)
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-left md:text-right shrink-0 min-w-[220px]">
                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Biaya Sewa</span>
                <span class="text-xl font-black text-blue-600 my-0.5 block">Rp {{ number_format($detail_iklan->harga_iklan, 0, ',', '.') }}</span>
                <div class="flex items-center md:justify-end gap-1.5 text-xs font-semibold text-gray-500 mt-1">
                    <i class="bi bi-calendar-event text-gray-400"></i>
                    <span>{{ \Carbon\Carbon::parse($detail_iklan->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($detail_iklan->tanggal_akhir)->format('d M Y') }}</span>
                </div>
                @if ($durasi)
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 mt-1.5">
                    <i class="bi bi-clock-history"></i> {{ $durasi }} Hari Masa Tayang
                </span>
                @endif
            </div>
            @endif
        </div>

        {{-- Card Body --}}
        <div class="p-6 sm:p-8 lg:p-10">
            {{-- Validation Errors --}}
            @if (isset($errors) && $errors->any())
                <div class="mb-8 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <p class="font-bold mb-1 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-red-600"></i> Terdapat kesalahan pengisian formulir:
                    </p>
                    <ul class="list-disc list-inside text-xs space-y-1 ml-1 text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Suspend Warning Banner --}}
            @if (isset($suspend_info) && $suspend_info->status_revisi !== 'selesai')
            <div class="w-full rounded-2xl border-2 border-red-200 bg-red-50/90 p-5 mb-8 text-left shadow-sm">
                <div class="flex items-center gap-2.5 text-red-800 font-black text-sm mb-1.5">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg"></i>
                    <span>Iklan ini Sedang Ditangguhkan (Suspend) oleh Admin</span>
                </div>
                <p class="text-xs text-red-700 font-semibold leading-relaxed">
                    <strong>Alasan Penangguhan:</strong> {{ $suspend_info->alasan }}
                </p>

                @php
                    $bgList = is_string($suspend_info->bagian_revisi) ? json_decode($suspend_info->bagian_revisi, true) : ($suspend_info->bagian_revisi ?? []);
                @endphp
                @if (!empty($bgList) && is_array($bgList))
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-red-900">Bagian yang wajib diperbaiki:</span>
                    @foreach ($bgList as $bg)
                    <span class="rounded-lg bg-red-100 text-red-800 border border-red-300 px-2.5 py-1 text-xs font-bold uppercase tracking-wider shadow-xs">
                        <i class="bi bi-pencil-fill text-[10px] mr-1"></i>
                        {{ $bg == 'poster' ? 'Gambar / Poster Banner' : ($bg == 'judul' ? 'Judul Banner' : ($bg == 'sub_judul' ? 'Sub Judul' : 'Deskripsi Iklan')) }}
                    </span>
                    @endforeach
                </div>
                @endif

                @if (!empty($suspend_info->catatan))
                <div class="mt-3 rounded-xl bg-white p-3.5 border border-red-200 text-xs text-gray-800 shadow-xs">
                    <span class="font-bold text-red-800 text-xs block mb-1">
                        <i class="bi bi-chat-left-text-fill mr-1"></i> Instruksi Tim Verifikasi KampSewa:
                    </span>
                    <p class="italic text-gray-700 leading-relaxed">{{ $suspend_info->catatan }}</p>
                </div>
                @endif

                <p class="text-xs text-gray-600 mt-3 flex items-center gap-1.5">
                    <i class="bi bi-info-circle text-blue-600"></i>
                    <span>Setelah Anda memperbaiki isian form dan menekan tombol <b>Simpan Perubahan</b>, admin akan otomatis menerima notifikasi untuk memeriksa dan mengaktifkan kembali iklan Anda.</span>
                </p>
            </div>
            @endif

            <form id="form-update-iklan"
                  enctype="multipart/form-data"
                  method="POST"
                  action="{{ route('kelola-iklan.update-iklan-post', ['id_iklan' => $data->id, 'id_user' => Crypt::encrypt(session('id_user') ?? auth()->user()->id)]) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                    {{-- Left Column: Upload Poster & Preview --}}
                    <div class="lg:col-span-5 flex flex-col gap-6">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <label for="file-input" class="text-sm font-bold text-gray-800 cursor-pointer">
                                    Poster Banner Iklan <span class="text-xs font-normal text-gray-400">(Opsional jika tidak diganti)</span>
                                </label>
                                <span class="text-xs font-semibold px-2 py-0.5 bg-blue-50 text-blue-600 rounded border border-blue-200 shrink-0">Rasio 16:9</span>
                            </div>
                            <p class="text-xs text-gray-500 mb-3 leading-relaxed">
                                Rekomendasi resolusi <strong>1280x720 px</strong> atau <strong>1920x1080 px</strong>. Format: JPG, PNG, WEBP (Maksimal 3MB).
                            </p>

                            {{-- Poster Preview Box 16:9 --}}
                            <div class="w-full aspect-video rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center relative group hover:border-blue-400 transition-colors shadow-inner">
                                <img id="poster-preview"
                                     class="w-full h-full object-cover {{ $posterUrl ? '' : 'hidden' }}"
                                     src="{{ $posterUrl ?? asset('images/Ad 1012px - 506px.png') }}"
                                     alt="Preview Poster Banner">
                                <div id="poster-placeholder" class="text-center p-6 {{ $posterUrl ? 'hidden' : '' }}">
                                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl mx-auto mb-3 shadow-sm group-hover:scale-105 transition-transform">
                                        <i class="bi bi-image"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">Pilih atau Drag file poster banner</p>
                                    <p class="text-xs text-gray-400 mt-1 max-w-[280px] mx-auto">Tampil proporsional dan tidak terpotong di banner mobile</p>
                                </div>
                            </div>

                            <div class="mt-3">
                                <input type="file"
                                       name="poster"
                                       id="file-input"
                                       accept="image/png,image/jpeg,image/jpg,image/webp"
                                       onchange="previewImage(event)"
                                       class="block w-full text-sm text-gray-500 file:me-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer border border-gray-300 rounded-xl p-2 bg-gray-50 focus:outline-none">
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1.5">Pilih berkas baru jika Anda ingin mengganti poster banner saat ini.</p>
                            @error('poster')
                                <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Info Ketentuan Revisi --}}
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-50/60 to-purple-50/40 border border-blue-100 flex flex-col gap-3">
                            <div class="flex items-center gap-2 text-blue-800 font-bold text-sm">
                                <i class="bi bi-lightbulb-fill text-blue-600"></i>
                                <span>Tips Efektivitas Iklan</span>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-2 leading-relaxed">
                                <li class="flex items-start gap-2">
                                    <i class="bi bi-check2-circle text-emerald-600 mt-0.5 shrink-0"></i>
                                    <span>Gunakan visual berkualitas tinggi tanpa watermark berlebih agar poster terlihat profesional.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bi bi-check2-circle text-emerald-600 mt-0.5 shrink-0"></i>
                                    <span>Tuliskan judul yang memuat kata kunci menarik seperti "Diskon", "Paket Hemat", atau nama perlengkapan populer.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bi bi-check2-circle text-emerald-600 mt-0.5 shrink-0"></i>
                                    <span>Deskripsi yang lengkap dan jelas meningkatkan kepercayaan calon penyewa hingga 70%.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Right Column: Form Inputs --}}
                    <div class="lg:col-span-7 flex flex-col gap-5">
                        {{-- 1. Judul Banner Iklan --}}
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-1.5 w-full">
                                <label for="input-judul" class="text-sm font-bold text-gray-800 inline-flex items-center gap-1.5 leading-none">
                                    Judul Banner Iklan <span class="text-red-500">*</span>
                                </label>
                                <span id="counter-judul" class="text-xs font-bold px-2.5 py-0.5 rounded-md border text-slate-500 bg-slate-100 border-slate-200 shrink-0 tabular-nums whitespace-nowrap">0 / 100</span>
                            </div>
                            <input type="text"
                                   name="judul"
                                   id="input-judul"
                                   value="{{ old('judul', $data->judul) }}"
                                   required
                                   maxlength="100"
                                   placeholder="Contoh: Promo Spesial Tenda Dome & Alat Camping Outdoor"
                                   class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-sm">
                            <p class="text-xs text-gray-400 mt-1">Judul utama banner yang terbaca calon penyewa (Maksimal 100 karakter).</p>
                            @error('judul')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 2. Sub Judul / Slogan Iklan --}}
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-1.5 w-full">
                                <label for="input-sub-judul" class="text-sm font-bold text-gray-800 inline-flex items-center gap-1.5 leading-none">
                                    Sub Judul / Tagline Iklan <span class="text-xs font-normal text-gray-400">(Opsional)</span>
                                </label>
                                <span id="counter-sub-judul" class="text-xs font-bold px-2.5 py-0.5 rounded-md border text-slate-500 bg-slate-100 border-slate-200 shrink-0 tabular-nums whitespace-nowrap">0 / 200</span>
                            </div>
                            <input type="text"
                                   name="sub_judul"
                                   id="input-sub-judul"
                                   value="{{ old('sub_judul', $data->sub_judul) }}"
                                   maxlength="200"
                                   placeholder="Contoh: Solusi perlengkapan mendaki gunung lengkap, steril dan terawat"
                                   class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-sm">
                            <p class="text-xs text-gray-400 mt-1">Sub-judul penjelas atau slogan kampanye iklan (Maksimal 200 karakter).</p>
                            @error('sub_judul')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 3. Deskripsi Lengkap Iklan --}}
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-1.5 w-full">
                                <label for="input-deskripsi" class="text-sm font-bold text-gray-800 inline-flex items-center gap-1.5 leading-none">
                                    Deskripsi Lengkap Iklan <span class="text-red-500">*</span>
                                </label>
                                <span id="counter-deskripsi" class="text-xs font-bold px-2.5 py-0.5 rounded-md border text-blue-600 bg-blue-50 border-blue-200 shrink-0 tabular-nums whitespace-nowrap">0 / 5.000</span>
                            </div>
                            <textarea name="deskripsi"
                                      id="input-deskripsi"
                                      rows="7"
                                      required
                                      maxlength="5000"
                                      placeholder="Jelaskan penawaran menarik, keunggulan produk sewa toko Anda, lokasi toko, rincian paket, syarat & ketentuan, atau fasilitas yang didapatkan pelanggan saat menyewa."
                                      class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-sm leading-relaxed">{{ old('deskripsi', $data->deskripsi) }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">Deskripsi ini akan muncul pada popup detail saat banner iklan ditekan pengguna di aplikasi mobile (Maksimal 5.000 karakter, seperti deskripsi YouTube).</p>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100 mt-4">
                            <a href="{{ route('kelola-iklan.index', ['id_user' => Crypt::encrypt(session('id_user') ?? auth()->user()->id)]) }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-bold transition shadow-sm">
                                <i class="bi bi-arrow-left"></i> Batal & Kembali
                            </a>
                            <button type="button"
                                    id="btn-submit-update"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 active:scale-95 transition">
                                <i class="bi bi-check-circle-fill"></i> Simpan Perubahan Iklan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('poster-preview');
                const placeholder = document.getElementById('poster-placeholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function initCharCounter(inputId, counterId, maxLen) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);
        if (input && counter) {
            const update = () => {
                const len = input.value.length;
                counter.textContent = `${len.toLocaleString('id-ID')} / ${maxLen.toLocaleString('id-ID')}`;
                if (len >= maxLen) {
                    counter.className = 'text-xs font-bold px-2.5 py-0.5 rounded-md border text-amber-700 bg-amber-50 border-amber-200 shrink-0 tabular-nums whitespace-nowrap';
                } else if (len > 0) {
                    counter.className = 'text-xs font-bold px-2.5 py-0.5 rounded-md border text-blue-600 bg-blue-50 border-blue-200 shrink-0 tabular-nums whitespace-nowrap';
                } else {
                    counter.className = 'text-xs font-bold px-2.5 py-0.5 rounded-md border text-slate-500 bg-slate-100 border-slate-200 shrink-0 tabular-nums whitespace-nowrap';
                }
            };
            input.addEventListener('input', update);
            update();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initCharCounter('input-judul', 'counter-judul', 100);
        initCharCounter('input-sub-judul', 'counter-sub-judul', 200);
        initCharCounter('input-deskripsi', 'counter-deskripsi', 5000);
    });

    document.getElementById('btn-submit-update').addEventListener('click', (event) => {
        event.preventDefault();
        const form = document.getElementById('form-update-iklan');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Perubahan',
            text: "Apakah Anda yakin ingin menyimpan perubahan data materi iklan ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Simpan Perubahan',
            cancelButtonText: 'Cek Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endsection
