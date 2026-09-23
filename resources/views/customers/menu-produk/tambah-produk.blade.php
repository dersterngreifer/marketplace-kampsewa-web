@extends('layouts.customers.layouts-customer')
@section('customer-content')
    <div class="max-w-[1000px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-6">
        <div class="mb-2">
            <a href="{{ route('menu-produk.kelola-produk', ['id_user' => Crypt::encrypt(session('id_user'))]) }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors">
                <i class="bi bi-arrow-left-short text-xl mr-1"></i> Kembali ke Kelola Produk
            </a>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50/50">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Tambah Barang Penyewaan</h1>
                <p class="text-sm text-gray-500 mt-2 font-medium">Tambahkan barang penyewaan! Anda bisa memasukkan data barang dengan banyak ukuran dan jenis, seperti warna, stok, dan harga sewa yang berbeda.</p>
            </div>
            
            <div class="p-6 sm:p-8">
                <form id="simpan-produk" action="{{ route('menu-produk.tambah-produk-post') }}"
                    class="w-full flex flex-col gap-8 h-auto" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_user" value="{{ $id }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="w-full">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Produk</label>
                            <input
                                class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                type="text" id="nama_produk" name="nama_produk" placeholder="Masukkan nama produk">
                        </div>
                        <div class="w-full">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Produk</label>
                            <textarea
                                class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                id="deskripsi_produk" name="deskripsi_produk" rows="5"
                                placeholder="Masukkan deskripsi produk" oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);"></textarea>
                        </div>
                        <div class="relative w-full">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Produk</label>
                            <input list="kategoriList" name="kategori_produk" id="grid-state" placeholder="Pilih atau ketik kategori baru..."
                                oninput="this.value = this.value.toLowerCase().replace(/\b\w/g, s => s.toUpperCase());"
                                class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors">
                            <datalist id="kategoriList">
                                @if(isset($user_categories) && count($user_categories) > 0)
                                    @foreach($user_categories as $kat)
                                        <option value="{{ ucfirst($kat) }}"></option>
                                    @endforeach
                                @else
                                    <option value="Tenda"></option>
                                    <option value="Pakaian"></option>
                                    <option value="Tas"></option>
                                    <option value="Sepatu"></option>
                                    <option value="Perlengkapan"></option>
                                @endif
                            </datalist>
                            @error('kategori_produk')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col col-span-1 md:col-span-2 lg:col-span-3">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Upload Foto Produk (Multiple)</label>
                            <p class="text-xs text-gray-500 font-medium mb-3">Anda bisa mengunggah banyak foto tanpa batas. Disarankan rasio ukuran 1:1.</p>
                            
                            <div class="w-full border-2 border-dashed border-gray-300 bg-gray-50 p-6 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer relative">
                                <input id="foto_produk" type="file" name="foto_produk[]" multiple accept="image/*"
                                    onchange="previewMultipleImages(event)"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="text-center z-0 pointer-events-none">
                                    <i class="bi bi-cloud-arrow-up text-4xl text-blue-500"></i>
                                    <p class="mt-2 text-sm font-medium text-gray-700">Klik atau Drag & Drop foto di sini</p>
                                    <p class="text-xs text-gray-500 mt-1">Format JPG, PNG (Unlimited)</p>
                                </div>
                            </div>
                            <div id="foto-preview-container" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4 empty:hidden">
                                <!-- Previews will be injected here -->
                            </div>
                            
                            <div class="mt-6 w-full border border-gray-200 bg-white p-5 rounded-xl shadow-sm">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Atau Paste External Image URLs</label>
                                <p class="text-xs text-gray-500 font-medium mb-3">Jika foto berasal dari internet, paste link (URL) gambar langsung di sini.</p>
                                <div id="url-inputs-container" class="flex flex-col gap-3 mb-3">
                                    <input type="url" name="foto_produk_url[]" placeholder="https://example.com/image.jpg" class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors">
                                </div>
                                <button type="button" onclick="addUrlInput()" class="inline-flex items-center gap-1 text-[13px] bg-gray-800 text-white py-1.5 px-3 rounded-lg hover:bg-gray-700 transition font-medium">
                                    <i class="bi bi-plus-lg"></i> Tambah URL
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6" id="variantContainer">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Varian & Detail Produk</h2>
                        <div class="variant bg-gray-50 border border-gray-200 rounded-xl p-5 mb-4 relative">
                            <div class="w-full md:w-1/2 mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Warna / Jenis Varian</label>
                                <input
                                    class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors"
                                    type="text" id="warna0" name="variants[0][warna]" placeholder="contoh: Merah, Basic, dll"
                                    oninput="this.value = this.value.toUpperCase();" required>
                            </div>
                            
                            <div class="size flex flex-col md:flex-row items-end gap-4 mt-4 bg-white p-4 border border-gray-200 rounded-lg shadow-sm">
                                <div class="w-full">
                                    <label class="block text-xs font-bold text-gray-700 mb-2">Ukuran / Kapasitas</label>
                                    <input
                                        class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                        type="text" id="ukuran" name="variants[0][sizes][0][ukuran]"
                                        placeholder="contoh: XXL/5x5/1-10 ORANG"
                                        oninput="this.value = this.value.toUpperCase();" required>
                                </div>
                                <div class="w-full">
                                    <label class="block text-xs font-bold text-gray-700 mb-2">Stok (pcs)</label>
                                    <input
                                        class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                        type="number" id="stok" name="variants[0][sizes][0][stok]"
                                        placeholder="contoh: 20" required>
                                </div>
                                <div class="w-full">
                                    <label class="block text-xs font-bold text-gray-700 mb-2">Harga Sewa / Hari (Rp)</label>
                                    <input
                                        class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                        type="text" id="harga_sewa" name="variants[0][sizes][0][harga_sewa]"
                                        placeholder="contoh: 15.000"
                                        oninput="formatRupiah(this)" required>
                                </div>
                            </div>
                            
                            <div class="sizeContainer mt-3 text-right w-full">
                                <button type="button" class="inline-flex items-center gap-1 px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-bold text-xs transition"
                                    onclick="addSize(this.parentElement)">
                                    <i class="bi bi-plus-lg"></i> Tambah Sub-varian
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-3 border-t border-gray-200 pt-6">
                        <button type="button" class="w-full sm:w-auto px-6 py-3 bg-gray-800 text-white rounded-xl font-bold text-sm hover:bg-gray-900 shadow-sm transition"
                            onclick="addVariant()">+ Tambah Varian Utama</button>
                        <button class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 shadow-md transition ml-auto"
                            id="simpan-data-produk">
                            <i class="bi bi-floppy-fill mr-1"></i> Simpan Data Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addVariant() {
            const variantContainer = document.getElementById('variantContainer');
            const variantCount = document.querySelectorAll('.variant').length;
            const newVariant = `
        <div class="variant bg-gray-50 border border-gray-200 rounded-xl p-5 mb-4 relative mt-4">
            <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 bg-white border border-gray-200 hover:bg-red-50 hover:border-red-200 rounded-lg p-2 transition shadow-sm flex items-center justify-center" onclick="removeVariant(this)" title="Hapus Varian Utama ini">
                <i class="bi bi-trash-fill"></i>
            </button>
            <div class="w-full md:w-1/2 mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Warna / Jenis Varian</label>
                <input class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors"
                    type="text" id="warna${variantCount}" name="variants[${variantCount}][warna]" placeholder="contoh: Merah, Basic, dll" oninput="this.value = this.value.toUpperCase();" required>
            </div>
            
            <div class="sizeContainer mt-3 text-right w-full">
                <button type="button" class="inline-flex items-center gap-1 px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-bold text-xs transition" onclick="addSize(this.parentElement)">
                    <i class="bi bi-plus-lg"></i> Tambah Sub-varian
                </button>
            </div>
        </div>
    `;
            variantContainer.insertAdjacentHTML('beforeend', newVariant);
            
            // Automatically add one size input inside the new variant
            const newestVariant = variantContainer.lastElementChild;
            addSize(newestVariant.querySelector('.sizeContainer'));
        }

        function addSize(sizeContainer) {
            const variantIndex = Array.from(document.querySelectorAll('.variant')).indexOf(sizeContainer.parentElement);
            const sizeCount = sizeContainer.parentElement.querySelectorAll('.size').length;
            const newSize = `
        <div class="size flex flex-col md:flex-row items-end gap-4 mt-4 bg-white p-4 border border-gray-200 rounded-lg shadow-sm">
            <div class="w-full">
                <label class="block text-xs font-bold text-gray-700 mb-2">Ukuran / Kapasitas</label>
                <input class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                    type="text" name="variants[${variantIndex}][sizes][${sizeCount}][ukuran]" placeholder="contoh: XXL/5x5/1-10 ORANG" oninput="this.value = this.value.toUpperCase();" required>
            </div>
            <div class="w-full">
                <label class="block text-xs font-bold text-gray-700 mb-2">Stok (pcs)</label>
                <input class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                    type="number" name="variants[${variantIndex}][sizes][${sizeCount}][stok]" placeholder="contoh: 20" required>
            </div>
            <div class="w-full">
                <label class="block text-xs font-bold text-gray-700 mb-2">Harga Sewa / Hari (Rp)</label>
                <input class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                    type="text" name="variants[${variantIndex}][sizes][${sizeCount}][harga_sewa]" placeholder="contoh: 15.000" oninput="formatRupiah(this)" required>
            </div>
            <div class="pb-1" ${sizeCount === 0 ? 'style="display:none;"' : ''}>
                <button type="button" class="px-3.5 py-2.5 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white border border-red-100 transition shadow-sm"
                    onclick="removeSize(this)" title="Hapus Sub-varian"><i class="bi bi-trash-fill"></i></button>
            </div>
        </div>
    `;
            sizeContainer.insertAdjacentHTML('beforebegin', newSize);
        }

        function removeVariant(button) {
            const variant = button.closest('.variant');
            if (variant) {
                variant.remove();
            }
        }

        function removeSize(button) {
            const size = button.closest('.size');
            if (size) {
                size.remove();
            }
        }

        function previewMultipleImages(event) {
            const container = document.getElementById('foto-preview-container');
            container.innerHTML = '';
            const files = event.target.files;
            if (files) {
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-[150px] object-cover rounded-lg border border-gray-300';
                        container.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        function addUrlInput() {
            const container = document.getElementById('url-inputs-container');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'foto_produk_url[]';
            input.placeholder = 'https://example.com/image.jpg';
            input.className = 'block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors mb-3';
            container.appendChild(input);
        }

        document.getElementById('simpan-data-produk').addEventListener('click', (event) => {
            event.preventDefault();
            let namaProduk = document.getElementById('nama_produk').value;
            let deskripsiProduk = document.getElementById('deskripsi_produk').value;
            let kategoriProduk = document.getElementById('grid-state').value;
            let fotoProduk = document.getElementById('foto_produk').files.length;
            let fotoUrls = Array.from(document.querySelectorAll('input[name="foto_produk_url[]"]')).filter(input => input.value.trim() !== '').length;
            let warna = document.getElementById('warna0').value;
            let stok = document.getElementById('stok').value;
            let hargaSewa = document.getElementById('harga_sewa').value;

            if (!namaProduk) {
                Swal.fire({
                    title: 'Nama Produk Kosong',
                    text: 'Silakan isi nama produk sebelum menyimpan.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            } else if (!deskripsiProduk) {
                Swal.fire({
                    title: 'Deskripsi Produk Kosong',
                    text: 'Silakan isi deskripsi sebelum menyimpan.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            } else if (kategoriProduk === 'Belum di isi') {
                Swal.fire({
                    title: 'Belum Menentukan Kategori',
                    text: 'Silakan tentukan kategori produk sebelum menyimpan.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            } else if (fotoProduk === 0 && fotoUrls === 0) {
                Swal.fire({
                    title: 'Belum Mengisi Foto Produk',
                    text: 'Silakan unggah setidaknya satu foto atau masukkan URL eksternal sebelum menyimpan.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            } else if (!warna) {
                Swal.fire({
                    title: 'Belum Mengisi Warna Produk',
                    text: 'Silakan isikan warna produk sebelum menyimpan.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            } else if (!ukuran) {
                Swal.fire({
                    title: 'Belum Mengisi Ukuran Produk',
                    text: 'Silakan isikan ukuran produk sebelum menyimpan.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            } else if (!hargaSewa) {
                Swal.fire({
                    title: 'Belum Mengisi Harga Sewa Produk',
                    text: 'Silakan isikan harga sewa produk sebelum menyimpan.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Apakah sudah yakin?',
                text: "Kamu akan save data ini dan bisa inputkan data produk lainnya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Bersihkan titik format ribuan sebelum submit agar validasi integer backend tidak error
                    document.querySelectorAll('input[name*="[harga_sewa]"]').forEach(input => {
                        input.value = input.value.replace(/\./g, '');
                    });
                    document.getElementById('simpan-produk').submit();
                } else {
                    Swal.fire('Cancelled', 'Save cancelled', 'info');
                }
            });
        });

        // Format number to Rupiah (adds dots)
        function formatRupiah(input) {
            let value = input.value.replace(/\D/g, ''); // Hapus semua karakter non-angka
            if (value !== '') {
                input.value = parseInt(value, 10).toLocaleString('id-ID'); // Format ke ribuan gaya Indonesia (titik)
            } else {
                input.value = '';
            }
        }

        function capitalizeFirstLetter(string) {
            return string.replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
        }

        var namaProdukInput = document.getElementById('nama_produk');
        namaProdukInput.addEventListener('input', function(event) {
            var inputValue = event.target.value;
            var capitalizedValue = capitalizeFirstLetter(inputValue);
            event.target.value = capitalizedValue;
        });
    </script>
@endsection
