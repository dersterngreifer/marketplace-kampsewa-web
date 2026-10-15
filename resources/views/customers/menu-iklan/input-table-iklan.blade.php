@extends('layouts.customers.layouts-customer')

@section('customer-content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-6">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('pilih-durasi-iklan.index', ['id_user' => $id_user]) }}"
           class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors">
            <i class="bi bi-arrow-left-short text-2xl mr-1"></i> Kembali ke Pilih Durasi
        </a>
        <span class="text-xs font-semibold text-gray-400">Langkah 2 dari 3: Input Konten Iklan</span>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="p-6 sm:p-8 lg:px-10 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 via-white to-purple-50/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-purple-100/70 text-purple-700 font-bold text-xs rounded-full border border-purple-200 mb-3">
                    <i class="bi bi-megaphone-fill text-xs"></i> Kampanye Banner Iklan Mitra
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Input Konten Banner Iklan Anda</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium max-w-2xl leading-relaxed">
                    Lengkapi materi poster rasio 16:9, judul promosi, serta deskripsi banner iklan yang akan tampil di halaman utama aplikasi mobile pengguna.
                </p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-left md:text-right shrink-0 min-w-[200px]">
                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Paket Dipilih</span>
                <span class="text-xl font-black text-blue-600 my-0.5 block">Rp {{ number_format($harga_sewa_iklan, 0, ',', '.') }}</span>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 mt-1">
                    <i class="bi bi-clock-history"></i> {{ $durasi_sewa_iklan }} Hari Tayang
                </span>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-6 sm:p-8 lg:p-10">
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

            <form id="simpan-iklan"
                  action="{{ route('layanan-iklan.simpan-iklan', ['id_user' => $id_user, 'harga_iklan' => $harga_sewa_iklan, 'durasi' => $durasi_sewa_iklan]) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_user" value="{{ $id_user_dec }}">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                    {{-- Left Column: Upload Poster & Preview --}}
                    <div class="lg:col-span-5 flex flex-col gap-6">
                        <div>
                            <div class="flex d-flex items-center align-items-center justify-between justify-content-between gap-2 mb-1.5" style="display: flex !important; align-items: center !important; justify-content: space-between !important;">
                                <label for="file-input" class="text-sm font-bold text-gray-800 mb-0" style="margin-bottom: 0 !important; cursor: pointer;">
                                    Poster Banner Iklan <span class="text-red-500">*</span>
                                </label>
                                <span class="text-xs font-semibold px-2 py-0.5 bg-blue-50 text-blue-600 rounded border border-blue-200 shrink-0" style="white-space: nowrap;">Rasio 16:9</span>
                            </div>
                            <p class="text-xs text-gray-500 mb-3 leading-relaxed">
                                Rekomendasi resolusi <strong>1280x720 px</strong> atau <strong>1920x1080 px</strong>. Format: JPG, PNG, WEBP (Maksimal 3MB).
                            </p>

                            {{-- Poster Preview Box 16:9 --}}
                            <div class="w-full aspect-video rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center relative group hover:border-blue-400 transition-colors shadow-inner">
                                <img id="poster-preview"
                                     class="w-full h-full object-cover {{ old('poster') ? '' : 'hidden' }}"
                                     src="{{ asset('images/Ad 1012px - 506px.png') }}"
                                     alt="Preview Poster Banner">
                                <div id="poster-placeholder" class="text-center p-6 {{ old('poster') ? 'hidden' : '' }}">
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
                                       required
                                       class="block w-full text-sm text-gray-500 file:me-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer border border-gray-300 rounded-xl p-2 bg-gray-50 focus:outline-none">
                            </div>
                            @error('poster')
                                <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Info Ketentuan Penayangan --}}
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-50/60 to-purple-50/40 border border-blue-100 flex flex-col gap-3">
                            <div class="flex items-center gap-2 text-blue-800 font-bold text-sm">
                                <i class="bi bi-info-circle-fill text-blue-600"></i>
                                <span>Ketentuan Penayangan Iklan</span>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-2 leading-relaxed">
                                <li class="flex items-start gap-2">
                                    <i class="bi bi-check2-circle text-emerald-600 mt-0.5 shrink-0"></i>
                                    <span>Banner langsung aktif dan tayang otomatis setelah pembayaran berhasil.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bi bi-check2-circle text-emerald-600 mt-0.5 shrink-0"></i>
                                    <span>Maksimal 15 slot iklan aktif bersamaan di rotasi carousel mobile.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bi bi-check2-circle text-emerald-600 mt-0.5 shrink-0"></i>
                                    <span>Jika 15 slot penuh, iklan akan antre di posisi giliran berikutnya secara adil.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Right Column: Form Inputs --}}
                    <div class="lg:col-span-7 flex flex-col gap-5">
                        <!-- 1. Judul Iklan (Banner Title) -->
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-1.5 w-full">
                                <label for="input-judul" class="!m-0 !p-0 !h-auto !w-auto text-sm font-bold text-gray-800 inline-flex items-center gap-1.5 leading-none">
                                    Judul Banner Iklan <span class="text-red-500">*</span>
                                </label>
                                <span id="counter-judul" class="text-xs font-bold px-2.5 py-0.5 rounded-md border text-slate-500 bg-slate-100 border-slate-200 shrink-0 tabular-nums whitespace-nowrap">0 / 100</span>
                            </div>
                            <input type="text"
                                   name="judul"
                                   id="input-judul"
                                   value="{{ old('judul') }}"
                                   required
                                   maxlength="100"
                                   placeholder="Contoh: Promo Spesial Tenda Dome & Alat Camping Outdoor"
                                   class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-sm">
                            <p class="text-xs text-gray-400 mt-1">Judul utama banner yang terbaca calon penyewa (Maksimal 100 karakter).</p>
                            @error('judul')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 2. Sub Judul / Slogan Iklan -->
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-1.5 w-full">
                                <label for="input-sub-judul" class="!m-0 !p-0 !h-auto !w-auto text-sm font-bold text-gray-800 inline-flex items-center gap-1.5 leading-none">
                                    Sub Judul / Tagline Iklan <span class="text-xs font-normal text-gray-400">(Opsional)</span>
                                </label>
                                <span id="counter-sub-judul" class="text-xs font-bold px-2.5 py-0.5 rounded-md border text-slate-500 bg-slate-100 border-slate-200 shrink-0 tabular-nums whitespace-nowrap">0 / 200</span>
                            </div>
                            <input type="text"
                                   name="sub_judul"
                                   id="input-sub-judul"
                                   value="{{ old('sub_judul') }}"
                                   maxlength="200"
                                   placeholder="Contoh: Solusi perlengkapan mendaki gunung lengkap, steril dan terawat"
                                   class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-sm">
                            <p class="text-xs text-gray-400 mt-1">Sub-judul penjelas atau slogan kampanye iklan (Maksimal 200 karakter).</p>
                            @error('sub_judul')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 3. Deskripsi Lengkap Iklan -->
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-1.5 w-full">
                                <label for="input-deskripsi" class="!m-0 !p-0 !h-auto !w-auto text-sm font-bold text-gray-800 inline-flex items-center gap-1.5 leading-none">
                                    Deskripsi Lengkap Iklan <span class="text-red-500">*</span>
                                </label>
                                <span id="counter-deskripsi" class="text-xs font-bold px-2.5 py-0.5 rounded-md border text-blue-600 bg-blue-50 border-blue-200 shrink-0 tabular-nums whitespace-nowrap">0 / 5.000</span>
                            </div>
                            <textarea name="deskripsi"
                                      id="input-deskripsi"
                                      rows="6"
                                      required
                                      maxlength="5000"
                                      placeholder="Jelaskan penawaran menarik, keunggulan produk sewa toko Anda, lokasi toko, rincian paket, syarat & ketentuan, atau fasilitas yang didapatkan pelanggan saat menyewa."
                                      class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-sm leading-relaxed">{{ old('deskripsi') }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">Deskripsi ini akan muncul pada popup detail saat banner iklan ditekan pengguna di aplikasi mobile (Maksimal 5.000 karakter, seperti deskripsi YouTube).</p>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 4. Target Klik Banner (Navigasi saat banner diklik) -->
                        <div class="border-t border-gray-100 pt-5">
                            <label class="block text-sm font-bold text-gray-800 mb-2">
                                Tujuan Navigasi Banner Iklan <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-500 mb-3">Tentukan ke mana pengunjung akan diarahkan saat menekan banner iklan Anda.</p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-3.5">
                                <label class="relative flex items-center gap-3.5 p-4 border-2 rounded-2xl cursor-pointer transition-all option-label border-blue-500 bg-blue-50/40 shadow-sm" id="label-target-toko">
                                    <input type="radio" name="target_type" value="toko" id="target-toko" checked class="w-4 h-4 text-blue-600 focus:ring-blue-500" onchange="toggleTarget(false)">
                                    <div>
                                        <span class="block text-sm font-bold text-gray-900">Profil Toko Saya</span>
                                        <span class="block text-xs text-gray-500 mt-0.5">Mengarahkan ke seluruh katalog toko Anda</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center gap-3.5 p-4 border-2 rounded-2xl cursor-pointer transition-all option-label border-gray-200 hover:bg-gray-50" id="label-target-produk">
                                    <input type="radio" name="target_type" value="produk" id="target-produk" class="w-4 h-4 text-blue-600 focus:ring-blue-500" onchange="toggleTarget(true)">
                                    <div>
                                        <span class="block text-sm font-bold text-gray-900">Produk Pilihan Tertentu</span>
                                        <span class="block text-xs text-gray-500 mt-0.5">Mengarahkan langsung ke detail 1 produk spesifik</span>
                                    </div>
                                </label>
                            </div>

                            <div id="select-produk-container" class="hidden mt-3">
                                <label for="id_produk" class="block text-xs font-bold text-gray-700 mb-1.5">
                                    Pilih Produk Toko yang Dipromosikan:
                                </label>
                                <select name="id_produk" id="id_produk" class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none shadow-sm">
                                    <option value="">-- Pilih Produk --</option>
                                    @if(isset($produkList) && $produkList->count() > 0)
                                        @foreach($produkList as $prod)
                                            <option value="{{ $prod->id }}" {{ old('id_produk') == $prod->id ? 'selected' : '' }}>
                                                {{ $prod->nama }} (Kategori: {{ $prod->kategori }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Tombol Aksi Submit -->
                        <div class="border-t border-gray-100 pt-6 flex flex-col sm:flex-row items-center gap-3">
                            <button type="button"
                                    id="btn-submit-iklan"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg transition-all duration-200">
                                <i class="bi bi-credit-card-2-front"></i> Simpan & Lanjut ke Pembayaran Iklan
                            </button>
                            <a href="{{ route('buat-iklan.index', ['id_user' => $id_user]) }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 border border-gray-300 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 transition-colors text-center">
                                Batalkan
                            </a>
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
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleTarget(isProduk) {
        const selectContainer = document.getElementById('select-produk-container');
        const labelToko = document.getElementById('label-target-toko');
        const labelProduk = document.getElementById('label-target-produk');

        if (isProduk) {
            selectContainer.classList.remove('hidden');
            labelToko.className = 'relative flex items-center gap-3.5 p-4 border-2 rounded-2xl cursor-pointer transition-all option-label border-gray-200 hover:bg-gray-50';
            labelProduk.className = 'relative flex items-center gap-3.5 p-4 border-2 rounded-2xl cursor-pointer transition-all option-label border-blue-500 bg-blue-50/40 shadow-sm';
        } else {
            selectContainer.classList.add('hidden');
            labelToko.className = 'relative flex items-center gap-3.5 p-4 border-2 rounded-2xl cursor-pointer transition-all option-label border-blue-500 bg-blue-50/40 shadow-sm';
            labelProduk.className = 'relative flex items-center gap-3.5 p-4 border-2 rounded-2xl cursor-pointer transition-all option-label border-gray-200 hover:bg-gray-50';
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

    document.getElementById('btn-submit-iklan').addEventListener('click', (event) => {
        event.preventDefault();
        const form = document.getElementById('simpan-iklan');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Pemasangan Iklan',
            text: "Data iklan akan disimpan dan diarahkan ke proses pembayaran Midtrans.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Lanjutkan Pembayaran',
            cancelButtonText: 'Cek Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endsection
