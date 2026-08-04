@extends('layouts.customers.layouts-customer')
@section('customer-content')
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-8">
        
        <!-- Header Section -->
        <div class="flex flex-col gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Kasir & Point of Sales</h1>
                <p class="text-gray-500 font-medium mt-1">Catat pesanan dari pelanggan yang datang langsung ke toko fisik Anda. Masukkan nomor HP dengan benar agar riwayat pesanan otomatis terhubung jika pelanggan menggunakan aplikasi di masa depan.</p>
            </div>
        </div>

        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 flex gap-3 shadow-sm">
            <div class="text-indigo-500 mt-0.5"><i class="bi bi-info-circle-fill text-lg"></i></div>
            <div>
                <h4 class="text-sm font-bold text-indigo-900">Informasi Kasir</h4>
                <p class="text-[13px] font-medium text-indigo-700 mt-1">Transaksi yang dibuat melalui Kasir Offline akan langsung berstatus <strong class="text-emerald-600">Aktif</strong> dan pembayarannya dianggap Lunas (Cash).</p>
            </div>
        </div>

        <form action="{{ route('kasir-offline.proses', ['id_user' => Crypt::encrypt($id_user)]) }}" method="POST" id="form-kasir">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Main Content (Select Products) -->
                <div class="lg:col-span-8 flex flex-col gap-6">
                    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-gray-100 pb-5">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <i class="bi bi-box-seam text-blue-600"></i> Daftar Pesanan
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">Tambahkan barang yang akan disewa oleh pelanggan.</p>
                            </div>
                            <button type="button" id="add-item-btn" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white font-bold py-2.5 px-5 rounded-xl transition-all duration-300 shadow-sm hover:shadow-md text-sm flex items-center gap-2 group">
                                <i class="bi bi-plus-circle-fill group-hover:rotate-90 transition-transform duration-300"></i> Tambah Item
                            </button>
                        </div>

                        <div id="items-container" class="flex flex-col gap-4">
                            <!-- Dynamic Item Rows Will Be Appended Here -->
                            <div class="text-center py-16 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200" id="empty-state">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                                    <i class="bi bi-cart-x text-3xl text-gray-300"></i>
                                </div>
                                <h3 class="text-gray-900 font-bold text-lg mb-1">Keranjang Kosong</h3>
                                <p class="text-gray-500 text-sm">Silakan klik tombol "Tambah Item" untuk memulai.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar (Customer & Dates & Checkout) -->
                <div class="lg:col-span-4 flex flex-col gap-6 sticky top-24 z-10">
                    
                    <!-- Data Pelanggan Card -->
                    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-person-badge text-indigo-600"></i> Informasi Pelanggan
                            </h3>
                        </div>
                        <div class="p-6 flex flex-col gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-bold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="bi bi-person text-gray-400"></i>
                                    </div>
                                    <input type="text" name="nama_pelanggan" required placeholder="Contoh: Budi Santoso"
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-bold text-gray-700">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="bi bi-whatsapp text-gray-400"></i>
                                    </div>
                                    <input type="number" name="no_hp" required placeholder="Contoh: 08123456789"
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                </div>
                                <p class="text-[11px] text-gray-500 font-medium mt-1"><i class="bi bi-info-circle"></i> Sinkronisasi otomatis ke aplikasi</p>
                            </div>
                        </div>
                    </div>

                    <!-- Durasi Sewa Card -->
                    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-calendar-event text-purple-600"></i> Durasi Penyewaan
                            </h3>
                        </div>
                        <div class="p-6 flex flex-col gap-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-bold text-gray-700">Mulai <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" required
                                        class="w-full px-3 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-gray-700">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-bold text-gray-700">Selesai <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" required
                                        class="w-full px-3 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-gray-700">
                                </div>
                            </div>
                            
                            <div class="space-y-1.5 mt-2">
                                <label class="text-[13px] font-bold text-gray-700">Denda / Biaya Tambahan (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm font-bold">Rp</span>
                                    </div>
                                    <input type="number" name="denda" id="denda" min="0" value="0"
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all text-gray-700">
                                </div>
                                <p class="text-[11px] text-gray-500 font-medium mt-1">Isi jika pelanggan memiliki denda/tanggungan sebelumnya</p>
                            </div>

                            <div class="p-4 bg-orange-50 rounded-xl border border-orange-100 mt-2">
                                <div class="text-xs text-orange-600 font-bold uppercase tracking-wider mb-1">Total Tagihan</div>
                                <div class="text-3xl font-black text-orange-600 truncate" id="total_harga_display">Rp 0</div>
                            </div>

                            <button type="submit" id="btn-submit" disabled class="w-full bg-gray-800 hover:bg-gray-900 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all focus:ring-4 focus:ring-gray-200 flex items-center justify-center gap-2 mt-2">
                                <i class="bi bi-check-circle-fill"></i> Proses Transaksi
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Hidden Template for New Item Row -->
    <template id="item-row-template">
        <div class="item-row bg-white border border-gray-200 rounded-2xl p-5 hover:border-blue-300 transition-colors shadow-sm relative group flex flex-col sm:flex-row gap-5">
            <!-- Hapus Button (Absolute on mobile, relative on desktop) -->
            <button type="button" class="btn-remove-item absolute -top-3 -right-3 sm:static sm:order-last w-8 h-8 flex items-center justify-center rounded-full bg-red-100 text-red-500 hover:bg-red-500 hover:text-white transition-colors shadow-sm border border-white z-10">
                <i class="bi bi-x-lg text-sm"></i>
            </button>

            <div class="flex-1 min-w-0 flex flex-col gap-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Produk Select -->
                    <div>
                        <label class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1.5 block">Pilih Produk</label>
                        <select class="produk-select w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all font-semibold text-gray-700" required>
                            <option value="" disabled selected>-- Daftar Produk --</option>
                            @foreach($produk as $p)
                                <option value="{{ $p->id }}" data-variants="{{ json_encode($p->variants) }}">
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" class="input-id-produk" name="items[INDEX][id_produk]" required disabled>
                    </div>
                    
                    <!-- Variant Select -->
                    <div>
                        <label class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1.5 block">Varian / Ukuran</label>
                        <select class="variant-select w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-gray-700 disabled:opacity-50" required disabled>
                            <option value="" disabled selected>Pilih Produk Dahulu</option>
                        </select>
                        <input type="hidden" class="input-id-variant" name="items[INDEX][id_detail_variant]" required disabled>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 items-end bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                    <div>
                        <label class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1.5 block">Kuantitas</label>
                        <div class="flex items-center gap-3">
                            <input type="number" class="qty-input w-20 px-3 py-2 bg-white border border-gray-200 rounded-lg text-center text-sm font-bold focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" value="1" min="1" required disabled name="items[INDEX][qty]">
                            <span class="text-xs font-semibold text-gray-500 stok-info bg-gray-200/50 px-2.5 py-1 rounded-md">Stok: -</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <label class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1 block">Subtotal (Per Hari)</label>
                        <div class="font-black text-gray-800 text-base subtotal-text truncate">Rp 0</div>
                        <input type="hidden" class="subtotal-val" value="0">
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('items-container');
            const btnAdd = document.getElementById('add-item-btn');
            const template = document.getElementById('item-row-template');
            const emptyState = document.getElementById('empty-state');
            const totalDisplay = document.getElementById('total_harga_display');
            const btnSubmit = document.getElementById('btn-submit');
            const inputTglMulai = document.getElementById('tanggal_mulai');
            const inputTglSelesai = document.getElementById('tanggal_selesai');
            const inputDenda = document.getElementById('denda');
            
            let itemIndex = 0;

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
            }

            function calculateTotal() {
                let durasi = 0;
                if(inputTglMulai.value && inputTglSelesai.value) {
                    const start = new Date(inputTglMulai.value);
                    const end = new Date(inputTglSelesai.value);
                    const diffTime = Math.abs(end - start);
                    durasi = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                }

                if(durasi <= 0) durasi = 0;

                let grandTotal = 0;
                let isAllValid = true;
                let itemCount = 0;

                document.querySelectorAll('.item-row').forEach(row => {
                    itemCount++;
                    const subVal = parseFloat(row.querySelector('.subtotal-val').value) || 0;
                    const qty = parseInt(row.querySelector('.qty-input').value) || 0;
                    const stok = parseInt(row.querySelector('.qty-input').getAttribute('max')) || 0;
                    
                    if(qty > stok) isAllValid = false;
                    if(subVal === 0 || qty === 0) isAllValid = false;

                    grandTotal += (subVal * qty * durasi);
                });
                
                const dendaVal = parseFloat(inputDenda.value) || 0;
                grandTotal += dendaVal;

                totalDisplay.innerText = formatRupiah(grandTotal);

                if(itemCount > 0 && isAllValid && durasi > 0 && inputTglMulai.value && inputTglSelesai.value) {
                    btnSubmit.removeAttribute('disabled');
                } else {
                    btnSubmit.setAttribute('disabled', 'disabled');
                }
            }

            inputTglMulai.addEventListener('change', calculateTotal);
            inputTglSelesai.addEventListener('change', calculateTotal);
            inputDenda.addEventListener('input', calculateTotal);

            btnAdd.addEventListener('click', function() {
                emptyState.style.display = 'none';
                
                const clone = template.content.cloneNode(true);
                const row = clone.querySelector('.item-row');
                
                // Update names with index
                row.querySelector('.input-id-produk').name = `items[${itemIndex}][id_produk]`;
                row.querySelector('.input-id-variant').name = `items[${itemIndex}][id_detail_variant]`;
                row.querySelector('.qty-input').name = `items[${itemIndex}][qty]`;
                
                const selectProduk = row.querySelector('.produk-select');
                const selectVariant = row.querySelector('.variant-select');
                const inputIdProduk = row.querySelector('.input-id-produk');
                const inputIdVariant = row.querySelector('.input-id-variant');
                const qtyInput = row.querySelector('.qty-input');
                const stokInfo = row.querySelector('.stok-info');
                const subText = row.querySelector('.subtotal-text');
                const subVal = row.querySelector('.subtotal-val');
                const btnRemove = row.querySelector('.btn-remove-item');

                selectProduk.addEventListener('change', function() {
                    const selOption = this.options[this.selectedIndex];
                    const variantsData = JSON.parse(selOption.getAttribute('data-variants') || '[]');
                    
                    inputIdProduk.value = this.value;
                    inputIdProduk.removeAttribute('disabled');
                    
                    // Reset variant dropdown
                    selectVariant.innerHTML = '<option value="" disabled selected>-- Pilih Varian --</option>';
                    selectVariant.removeAttribute('disabled');
                    
                    variantsData.forEach(v => {
                        v.detail_variants.forEach(dv => {
                            if(dv.stok > 0) {
                                const opt = document.createElement('option');
                                opt.value = dv.id;
                                opt.text = `${v.warna} - ${dv.ukuran} (Rp ${dv.harga_sewa})`;
                                opt.setAttribute('data-stok', dv.stok);
                                opt.setAttribute('data-harga', dv.harga_sewa);
                                selectVariant.appendChild(opt);
                            }
                        });
                    });

                    // Reset values
                    inputIdVariant.value = "";
                    inputIdVariant.setAttribute('disabled', 'disabled');
                    qtyInput.value = 1;
                    qtyInput.setAttribute('disabled', 'disabled');
                    stokInfo.innerText = "Stok: -";
                    stokInfo.className = "text-xs font-semibold text-gray-500 stok-info bg-gray-200/50 px-2.5 py-1 rounded-md";
                    subVal.value = 0;
                    subText.innerText = "Rp 0";
                    calculateTotal();
                });

                selectVariant.addEventListener('change', function() {
                    const selOption = this.options[this.selectedIndex];
                    const stok = selOption.getAttribute('data-stok');
                    const harga = selOption.getAttribute('data-harga');

                    inputIdVariant.value = this.value;
                    inputIdVariant.removeAttribute('disabled');

                    qtyInput.removeAttribute('disabled');
                    qtyInput.setAttribute('max', stok);
                    qtyInput.value = 1;
                    
                    stokInfo.innerText = `Sisa: ${stok}`;
                    stokInfo.className = "text-xs font-semibold stok-info px-2.5 py-1 rounded-md";
                    if(stok < 3) {
                        stokInfo.classList.add('bg-orange-100', 'text-orange-600');
                    } else {
                        stokInfo.classList.add('bg-blue-100', 'text-blue-600');
                    }

                    subVal.value = harga;
                    subText.innerText = formatRupiah(harga);

                    calculateTotal();
                });

                qtyInput.addEventListener('input', function() {
                    const max = parseInt(this.getAttribute('max'));
                    let val = parseInt(this.value);
                    if(val > max) {
                        this.value = max;
                        stokInfo.innerText = `Maks: ${max}`;
                        stokInfo.className = "text-xs font-semibold stok-info px-2.5 py-1 rounded-md bg-red-100 text-red-600";
                    } else {
                        stokInfo.innerText = `Sisa: ${max}`;
                        stokInfo.className = "text-xs font-semibold stok-info px-2.5 py-1 rounded-md bg-blue-100 text-blue-600";
                    }
                    calculateTotal();
                });

                btnRemove.addEventListener('click', function() {
                    row.remove();
                    if(container.querySelectorAll('.item-row').length === 0) {
                        emptyState.style.display = 'block';
                    }
                    calculateTotal();
                });

                container.appendChild(clone);
                itemIndex++;
            });
        });
    </script>
@endsection
