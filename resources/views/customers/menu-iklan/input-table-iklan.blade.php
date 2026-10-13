@extends('layouts.customers.layouts-customer')

@section('customer-content')
<div class="max-w-[1000px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-6">
    <!-- Breadcrumb & Back -->
    <div class="mb-1">
        <a href="{{ route('pilih-durasi-iklan.index', ['id_user' => $id_user]) }}"
           class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors">
            <i class="bi bi-arrow-left-short text-xl mr-1"></i> Kembali ke Pilih Durasi
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 font-bold text-xs rounded-full border border-purple-200 mb-2">
                    <i class="bi bi-megaphone-fill text-xs"></i> Kampanye Banner Iklan
                </span>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Input Konten Banner Iklan Anda</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">
                    Lengkapi banner poster rasio 16:9, judul, dan deskripsi iklan yang akan tayang di dashboard aplikasi mobile pengguna.
                </p>
            </div>
            <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-2xs text-right self-start sm:self-auto">
                <span class="text-xs text-gray-400 font-bold block">Paket Dipilih:</span>
                <span class="text-base font-black text-blue-600">Rp {{ number_format($harga_sewa_iklan, 0, ',', '.') }}</span>
                <span class="text-xs text-gray-500 font-bold block">{{ $durasi_sewa_iklan }} Hari Tayang</span>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <p class="font-bold mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Terdapat kesalahan pengisian:</p>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="simpan-iklan"
                  action="{{ route('layanan-iklan.simpan-iklan', ['id_user' => $id_user, 'harga_iklan' => $harga_sewa_iklan, 'durasi' => $durasi_sewa_iklan]) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="flex flex-col gap-6">
                @csrf
                <input type="hidden" name="id_user" value="{{ $id_user_dec }}">

                <!-- 1. Upload Banner Poster 16:9 -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1">
                        Upload Poster Banner Iklan (Rasio 16:9) <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-3">
                        Disarankan ukuran <strong>1280x720 px</strong> atau <strong>1920x1080 px</strong>. Format: JPG, PNG, WEBP (Maksimal 3MB).
                    </p>

                    <div class="w-full aspect-video rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center relative mb-3 group hover:border-blue-400 transition-colors">
                        <img id="poster-preview"
                             class="w-full h-full object-cover {{ old('poster') ? '' : 'hidden' }}"
                             src="{{ asset('images/Ad 1012px - 506px.png') }}"
                             alt="Preview Poster">
                        <div id="poster-placeholder" class="text-center p-6 {{ old('poster') ? 'hidden' : '' }}">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl mx-auto mb-2">
                                <i class="bi bi-image"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-700">Pilih atau Drag file poster banner di bawah</p>
                            <p class="text-xs text-gray-400 mt-0.5">Rasio 16:9 tampil proporsional tanpa terpotong di aplikasi mobile</p>
                        </div>
                    </div>

                    <input type="file"
                           name="poster"
                           id="file-input"
                           accept="image/png,image/jpeg,image/jpg,image/webp"
                           onchange="previewImage(event)"
                           required
                           class="block w-full text-sm text-gray-500 file:me-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer border border-gray-300 rounded-xl p-2 bg-gray-50">
                    @error('poster')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 2. Judul Iklan (Banner Title) -->
                <div>
                    <label for="input-judul" class="block text-sm font-bold text-gray-800 mb-1">
                        Judul Banner Iklan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="judul"
                           id="input-judul"
                           value="{{ old('judul') }}"
                           required
                           maxlength="100"
                           placeholder="Contoh: Promo Spesial Tenda Dome & Alat Camping Murah"
                           class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-2xs">
                    <p class="text-xs text-gray-400 mt-1">Judul utama banner yang akan terbaca oleh calon penyewa (Maksimal 100 karakter).</p>
                    @error('judul')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 3. Sub Judul / Slogan Iklan -->
                <div>
                    <label for="input-sub-judul" class="block text-sm font-bold text-gray-800 mb-1">
                        Sub Judul / Tagline Iklan (Opsional)
                    </label>
                    <input type="text"
                           name="sub_judul"
                           id="input-sub-judul"
                           value="{{ old('sub_judul') }}"
                           maxlength="200"
                           placeholder="Contoh: Solusi perlengkapan mendaki gunung lengkap dan terawat"
                           class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-2xs">
                    @error('sub_judul')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 4. Deskripsi Lengkap Iklan -->
                <div>
                    <label for="input-deskripsi" class="block text-sm font-bold text-gray-800 mb-1">
                        Deskripsi Iklan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi"
                              id="input-deskripsi"
                              rows="4"
                              required
                              maxlength="800"
                              placeholder="Jelaskan penawaran menarik, keunggulan produk sewa toko Anda, lokasi toko, atau fasilitas yang didapatkan pelanggan saat menyewa."
                              class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition-all shadow-2xs">{{ old('deskripsi') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Deskripsi ini akan tampil pada popup detail saat banner iklan ditekan pengguna di aplikasi mobile.</p>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 5. Target Klik Banner (Navigasi saat banner diklik) -->
                <div class="border-t border-gray-100 pt-5">
                    <label class="block text-sm font-bold text-gray-800 mb-2">
                        Tujuan Navigasi Banner Iklan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                        <label class="relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-blue-500 bg-blue-50/30" id="label-target-toko">
                            <input type="radio" name="target_type" value="toko" id="target-toko" checked class="w-4 h-4 text-blue-600 focus:ring-blue-500" onchange="toggleTarget(false)">
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Profil Toko Saya</span>
                                <span class="block text-xs text-gray-500 mt-0.5">Mengarahkan ke seluruh katalog toko Anda</span>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-gray-200 hover:bg-gray-50" id="label-target-produk">
                            <input type="radio" name="target_type" value="produk" id="target-produk" class="w-4 h-4 text-blue-600 focus:ring-blue-500" onchange="toggleTarget(true)">
                            <div>
                                <span class="block text-sm font-bold text-gray-900">Produk Pilihan Tertentu</span>
                                <span class="block text-xs text-gray-500 mt-0.5">Mengarahkan langsung ke detail 1 produk spesifik</span>
                            </div>
                        </label>
                    </div>

                    <div id="select-produk-container" class="hidden">
                        <label for="id_produk" class="block text-xs font-bold text-gray-700 mb-1">
                            Pilih Produk Toko yang Dipromosikan:
                        </label>
                        <select name="id_produk" id="id_produk" class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none shadow-2xs">
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

                <!-- Tombol Aksi -->
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
            labelToko.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-gray-200 hover:bg-gray-50';
            labelProduk.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-blue-500 bg-blue-50/30';
        } else {
            selectContainer.classList.add('hidden');
            labelToko.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-blue-500 bg-blue-50/30';
            labelProduk.className = 'relative flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all option-label border-gray-200 hover:bg-gray-50';
        }
    }

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
