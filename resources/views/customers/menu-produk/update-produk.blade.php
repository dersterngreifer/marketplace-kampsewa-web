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
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Update Barang Penyewaan</h1>
                <p class="text-sm text-gray-500 mt-2 font-medium">Perbarui informasi, foto, dan varian barang penyewaan Anda.</p>
            </div>
            
            <div class="p-6 sm:p-8">
                <form id="form-update" action="{{ route('menu-produk.update-produk-put', ['id_produk' => $produk->id]) }}"
                    class="w-full flex flex-col gap-8 h-auto" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_user" value="{{ $id_user }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="w-full">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Produk</label>
                            <input
                                class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                type="text" id="nama_produk" name="nama_produk" placeholder="Masukkan nama produk" value="{{ $produk->nama }}">
                        </div>
                        <div class="w-full">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Produk</label>
                            <input
                                class="block w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                type="text" id="deskripsi_produk" name="deskripsi_produk"
                                placeholder="Masukkan deskripsi produk" value="{{ $produk->deskripsi }}">
                        </div>
                        <div class="relative w-full">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Produk</label>
                            <input list="kategoriList" name="kategori_produk_update" id="grid-state" placeholder="Pilih atau ketik kategori baru..." value="{{ $produk->kategori }}"
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
                            @error('kategori_produk_update')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col col-span-1 md:col-span-2 lg:col-span-3">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Foto Produk Tersimpan</label>
                            <p class="text-xs text-gray-500 font-medium mb-3">Foto yang sudah ada. Centang untuk menghapus foto.</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-4" id="existing-photos">
                                @foreach ($foto_produk as $foto)
                                    <div class="relative group border border-gray-200 rounded-xl overflow-hidden" id="foto-card-{{ $foto->id }}">
                                        <img src="{{ $foto->tipe_sumber == 'external' ? $foto->url_foto : asset('assets/image/customers/produk/' . $foto->url_foto) }}" class="w-full h-[120px] object-cover">
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <label class="cursor-pointer bg-red-500 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-red-600 shadow-sm flex items-center gap-1">
                                                <input type="checkbox" name="deleted_fotos[]" value="{{ $foto->id }}" class="hidden" onchange="toggleDeleteFoto(this, {{ $foto->id }})">
                                                <i class="bi bi-trash-fill"></i> Hapus
                                            </label>
                                        </div>
                                        <div id="foto-overlay-{{ $foto->id }}" class="absolute inset-0 bg-red-500 bg-opacity-80 flex-col items-center justify-center hidden">
                                            <i class="bi bi-trash text-white text-xl"></i>
                                            <span class="text-white text-xs font-bold mt-1">Akan Dihapus</span>
                                            <button type="button" class="mt-2 bg-white text-red-500 text-[10px] px-2 py-0.5 rounded font-bold" onclick="cancelDeleteFoto({{ $foto->id }})">Batal</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <label class="block text-sm font-bold text-gray-700 mb-1 mt-2">Upload Tambahan Foto Baru (Multiple)</label>
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
                        
                        @foreach ($variants as $variantIndex => $variant)
                            <div class="variant bg-gray-50 border border-gray-200 rounded-xl p-5 mb-4 relative mt-4">
                                <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 bg-white border border-gray-200 hover:bg-red-50 hover:border-red-200 rounded-lg p-2 transition shadow-sm flex items-center justify-center" onclick="removeVariant(this)" title="Hapus Varian Utama ini">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                                <div class="w-full md:w-1/2 mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Warna / Jenis Varian</label>
                                    <input
                                        class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors"
                                        type="text" id="warna{{ $variantIndex }}" name="variants[{{ $variantIndex }}][warna]" placeholder="contoh: Merah, Basic, dll"
                                        value="{{ $variant->warna }}" required>
                                </div>
                                
                                @if ($detail_variants->has($variant->id))
                                    @foreach ($detail_variants[$variant->id] as $sizeIndex => $detail)
                                        <div class="size flex flex-col md:flex-row items-end gap-4 mt-4 bg-white p-4 border border-gray-200 rounded-lg shadow-sm">
                                            <div class="w-full">
                                                <label class="block text-xs font-bold text-gray-700 mb-2">Ukuran / Kapasitas</label>
                                                <input
                                                    class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                                    type="text" name="variants[{{ $variantIndex }}][sizes][{{ $sizeIndex }}][ukuran]"
                                                    value="{{ $detail->ukuran }}" placeholder="contoh: 3x4 / 4 Orang" required>
                                            </div>
                                            <div class="w-full">
                                                <label class="block text-xs font-bold text-gray-700 mb-2">Stok (pcs)</label>
                                                <input
                                                    class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                                    type="number" name="variants[{{ $variantIndex }}][sizes][{{ $sizeIndex }}][stok]"
                                                    value="{{ $detail->stok }}" placeholder="contoh: 20" required>
                                            </div>
                                            <div class="w-full">
                                                <label class="block text-xs font-bold text-gray-700 mb-2">Harga Sewa / Hari (Rp)</label>
                                                <input
                                                    class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                                    type="number" name="variants[{{ $variantIndex }}][sizes][{{ $sizeIndex }}][harga_sewa]"
                                                    value="{{ $detail->harga_sewa }}" placeholder="contoh: 15000" required>
                                            </div>
                                            <div class="pb-1">
                                                <button type="button" class="px-3.5 py-2.5 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white border border-red-100 transition shadow-sm"
                                                    onclick="removeSize(this)" title="Hapus Sub-varian"><i class="bi bi-trash-fill"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                
                                <div class="sizeContainer mt-3 text-right w-full">
                                    <button type="button" class="inline-flex items-center gap-1 px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-bold text-xs transition"
                                        onclick="addSize(this.parentElement)">
                                        <i class="bi bi-plus-lg"></i> Tambah Sub-varian
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-3 border-t border-gray-200 pt-6">
                        <button type="button" class="w-full sm:w-auto px-6 py-3 bg-gray-800 text-white rounded-xl font-bold text-sm hover:bg-gray-900 shadow-sm transition"
                            onclick="addVariant()">+ Tambah Varian Utama</button>
                        <button class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 shadow-md transition ml-auto"
                            id="simpan-update">
                            <i class="bi bi-floppy-fill mr-1"></i> Simpan Data Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleDeleteFoto(checkbox, id) {
            const overlay = document.getElementById('foto-overlay-' + id);
            if (checkbox.checked) {
                overlay.style.display = 'flex';
            } else {
                overlay.style.display = 'none';
            }
        }
        
        function cancelDeleteFoto(id) {
            const overlay = document.getElementById('foto-overlay-' + id);
            const checkbox = document.querySelector('input[name="deleted_fotos[]"][value="'+id+'"]');
            checkbox.checked = false;
            overlay.style.display = 'none';
        }

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
                    type="text" id="warna${variantCount}" name="variants[${variantCount}][warna]" placeholder="contoh: Merah, Basic, dll" required>
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
            const sizeCount = sizeContainer.parentElement.querySelectorAll('.size').length + 1;
            const newSize = `
        <div class="size flex flex-col md:flex-row items-end gap-4 mt-4 bg-white p-4 border border-gray-200 rounded-lg shadow-sm">
            <div class="w-full">
                <label class="block text-xs font-bold text-gray-700 mb-2">Ukuran / Kapasitas</label>
                <input class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                    type="text" name="variants[${variantIndex}][sizes][${sizeCount}][ukuran]" placeholder="contoh: 3x4 / 4 Orang" required>
            </div>
            <div class="w-full">
                <label class="block text-xs font-bold text-gray-700 mb-2">Stok (pcs)</label>
                <input class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                    type="number" name="variants[${variantIndex}][sizes][${sizeCount}][stok]" placeholder="contoh: 20" required>
            </div>
            <div class="w-full">
                <label class="block text-xs font-bold text-gray-700 mb-2">Harga Sewa / Hari (Rp)</label>
                <input class="block w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                    type="number" name="variants[${variantIndex}][sizes][${sizeCount}][harga_sewa]" placeholder="contoh: 15000" required>
            </div>
            <div class="pb-1">
                <button type="button" class="px-3.5 py-2.5 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white border border-red-100 transition shadow-sm"
                    onclick="removeSize(this)" title="Hapus Sub-varian"><i class="bi bi-trash-fill"></i></button>
            </div>
        </div>
    `;
            sizeContainer.insertAdjacentHTML('beforebegin', newSize);
            sizeContainer.parentElement.querySelector('.size:last-child button').style.display = 'inline-flex';
        }

        function removeVariant(button) {
            const variant = button.parentElement.parentElement;
            variant.remove();
        }

        function removeSize(button) {
            const size = button.parentElement.parentElement;
            size.remove();
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
                        img.className = 'w-full h-[120px] object-cover rounded-lg border border-gray-300';
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

        document.getElementById('simpan-update').addEventListener('click', (event) => {
            event.preventDefault();
            let namaProduk = document.getElementById('nama_produk').value;
            let deskripsiProduk = document.getElementById('deskripsi_produk').value;

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
            }

            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Pastikan data sudah benar sebelum menyimpan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-update').submit();
                }
            });
        });

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

        document.getElementById('deskripsi_produk').addEventListener('input', function(event) {
            var inputValue = event.target.value;
            var capitalizedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1);
            event.target.value = capitalizedValue;
        });
    </script>
@endsection
