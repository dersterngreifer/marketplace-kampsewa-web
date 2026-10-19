@extends('layouts.customers.layouts-customer')
@section('customer-content')
    @php
        $encryptedUserId = Crypt::encrypt(session('id_user'));
        $backRoute = route('menu-transaksi.index', ['id_user' => $encryptedUserId]);
        $backLabel = 'Order Masuk';
        if ($data->status_penyewaan === 'Pengambilan') {
            $backRoute = route('menu-transaksi.pengambilan', ['id_user' => $encryptedUserId]);
            $backLabel = 'Pengambilan';
        } elseif ($data->status_penyewaan === 'Aktif') {
            $backRoute = route('menu-transaksi.sewa-berlangsung', ['id_user' => $encryptedUserId]);
            $backLabel = 'Sewa Berlangsung';
        } elseif (in_array($data->status_penyewaan, ['Selesai', 'Pengembalian', 'Dibatalkan'])) {
            $backRoute = route('menu-transaksi.order-selesai', ['id_user' => $encryptedUserId]);
            $backLabel = 'Order Selesai';
        }

        $isOffline = ($data->tipe_pesanan ?? '') === 'offline';
        $isLunas = ($data->status_pembayaran ?? '') === 'Lunas';
        $isDiantar = (($data->biaya_antar ?? 0) > 0 || strtolower($data->jenis_transaksi ?? '') === 'diantar' || ($data->opsi_pengiriman ?? '') === 'Antar Ke Alamatmu');

        // Clean phone for WhatsApp link
        $rawPhone = preg_replace('/[^0-9]/', '', $data->nomor_telephone ?? '');
        if (\Illuminate\Support\Str::startsWith($rawPhone, '0')) {
            $waPhone = '62' . substr($rawPhone, 1);
        } else {
            $waPhone = $rawPhone;
        }

        // Stepper state
        $stepMap = [
            'Pending' => 1,
            'Pengambilan' => 2,
            'Aktif' => 3,
            'Pengembalian' => 3,
            'Selesai' => 4,
            'Dibatalkan' => 0,
        ];
        $currentStep = $stepMap[$data->status_penyewaan] ?? 1;
    @endphp

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-6">

        <!-- Breadcrumb & Top Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ $backRoute }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-blue-600 text-xs font-bold shadow-2xs transition-all">
                    <i class="bi bi-arrow-left text-sm"></i> Kembali ke {{ $backLabel }}
                </a>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-bold text-gray-500">Detail Transaksi #{{ $data->id_penyewaan }}</span>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($data->status_penyewaan === 'Pending')
                    <button type="button" onclick="scrollToElement('section-konfirmasi-order')"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition-all">
                        <i class="bi bi-check2-circle"></i> Konfirmasi Order Ini
                    </button>
                @elseif ($data->status_penyewaan === 'Aktif' || $data->status_penyewaan === 'Pengembalian')
                    <button type="button" onclick="scrollToElement('section-pengembalian')"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition-all">
                        <i class="bi bi-box-arrow-in-down"></i> Proses Pengembalian Barang
                    </button>
                @endif
            </div>
        </div>

        <!-- Hero Order Header Card -->
        <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 sm:p-8 flex flex-col gap-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-6 border-b border-gray-100">
                <div class="flex flex-col gap-2">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-gray-900 text-white tracking-wide">
                            ORDER #{{ $data->id_penyewaan }}
                        </span>
                        @if ($isOffline)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1.5">
                                <i class="bi bi-shop"></i> Transaksi Kasir Offline (POS)
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1.5">
                                <i class="bi bi-phone"></i> Pesanan Aplikasi Online
                            </span>
                        @endif
                        @if (!empty($data->created_at))
                            <span class="text-xs font-medium text-gray-400 flex items-center gap-1">
                                <i class="bi bi-clock"></i> Dibuat {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d M Y, H:i') }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight mt-1">
                        Rincian Pesanan & Penyewaan
                    </h1>
                </div>

                <!-- Status Badges -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="px-4 py-2.5 rounded-2xl border {{ $isLunas ? 'bg-emerald-50/70 border-emerald-200 text-emerald-700' : 'bg-red-50/70 border-red-200 text-red-700' }} flex items-center gap-2.5">
                        <i class="bi {{ $isLunas ? 'bi-patch-check-fill text-emerald-600' : 'bi-exclamation-circle-fill text-red-600' }} text-lg"></i>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-wider opacity-75">Status Pembayaran</div>
                            <div class="text-sm font-black">{{ $data->status_pembayaran ?: 'Belum Lunas' }} ({{ $data->metode ?: '-' }})</div>
                        </div>
                    </div>

                    @php
                        $statusBadgeStyle = match($data->status_penyewaan) {
                            'Pending' => 'bg-amber-50/80 border-amber-200 text-amber-700',
                            'Pengambilan' => 'bg-indigo-50/80 border-indigo-200 text-indigo-700',
                            'Aktif' => 'bg-emerald-50/80 border-emerald-200 text-emerald-700',
                            'Selesai' => 'bg-blue-50/80 border-blue-200 text-blue-700',
                            'Dibatalkan' => 'bg-red-50/80 border-red-200 text-red-700',
                            default => 'bg-gray-50 border-gray-200 text-gray-700',
                        };
                    @endphp
                    <div class="px-4 py-2.5 rounded-2xl border {{ $statusBadgeStyle }} flex items-center gap-2.5">
                        <i class="bi bi-activity text-lg"></i>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-wider opacity-75">Status Penyewaan</div>
                            <div class="text-sm font-black">
                                {{ $data->status_penyewaan }}
                                @if($data->status_penyewaan == 'Selesai' && isset($pengembalian) && $pengembalian->status_denda == 'belum_dibayar')
                                    <span class="text-red-600 text-xs font-bold">(Denda Belum Lunas)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Stepper -->
            @if ($data->status_penyewaan !== 'Dibatalkan')
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $steps = [
                            1 => ['label' => 'Order Masuk', 'desc' => 'Verifikasi pesanan & bayar', 'icon' => 'bi-Funnel'],
                            2 => ['label' => 'Siap Diambil', 'desc' => 'Serah terima peralatan', 'icon' => 'bi-box-seam'],
                            3 => ['label' => 'Sewa Berlangsung', 'desc' => 'Barang sedang disewa', 'icon' => 'bi-play-circle'],
                            4 => ['label' => 'Selesai & Kembali', 'desc' => 'Barang telah dikembalikan', 'icon' => 'bi-check2-all'],
                        ];
                    @endphp
                    @foreach ($steps as $stepNum => $stepInfo)
                        @php
                            $isDone = $currentStep > $stepNum || ($currentStep === 4 && $stepNum === 4);
                            $isActive = $currentStep === $stepNum && $currentStep !== 4;
                        @endphp
                        <div class="p-3.5 rounded-2xl border transition-all flex items-center gap-3
                            {{ $isDone ? 'bg-emerald-50/50 border-emerald-200 text-emerald-800' : ($isActive ? 'bg-blue-50/70 border-blue-300 text-blue-900 ring-2 ring-blue-500/10' : 'bg-gray-50/60 border-gray-100 text-gray-400') }}">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm shrink-0
                                {{ $isDone ? 'bg-emerald-600 text-white' : ($isActive ? 'bg-blue-600 text-white' : 'bg-gray-200/70 text-gray-500') }}">
                                @if ($isDone)
                                    <i class="bi bi-check-lg text-base"></i>
                                @else
                                    {{ $stepNum }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-extrabold truncate">{{ $stepInfo['label'] }}</div>
                                <div class="text-[11px] font-medium truncate opacity-75">{{ $stepInfo['desc'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3">
                    <i class="bi bi-x-octagon-fill text-xl text-red-600"></i>
                    <div>
                        <h4 class="text-sm font-bold">Pesanan Ini Telah Dibatalkan</h4>
                        <p class="text-xs font-medium mt-0.5">Stok peralatan dari pesanan ini telah dikembalikan ke inventaris toko Anda.</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Contextual Alerts (Payment Status & Fraud Warning) -->
        @if (!$isLunas)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-2xs">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="bi bi-exclamation-diamond-fill text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-950">Pembayaran Pelanggan Belum Lunas (COD)</h4>
                        <p class="text-xs font-medium text-amber-800 mt-1">Sebelum menyiapkan peralatan dan menekan tombol <strong>Terima Order</strong>, harap menginputkan nominal pembayaran dan jaminan KTP pelanggan pada form <strong>Input Pembayaran COD</strong> di bawah.</p>
                    </div>
                </div>
                <button type="button" onclick="scrollToElement('section-pembayaran-cod')"
                    class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold whitespace-nowrap transition-all shrink-0">
                    Input Pembayaran COD
                </button>
            </div>
        @else
            <div class="bg-emerald-50/90 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3.5 shadow-2xs">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="bi bi-check-circle-fill text-lg"></i>
                </div>
                <p class="text-xs sm:text-sm font-semibold text-emerald-900">
                    Status pembayaran pelanggan telah <strong>Lunas</strong>. Pastikan kelengkapan peralatan dan identitas jaminan penyewa sesuai dengan rincian pesanan.
                </p>
            </div>
        @endif

        @if (isset($data->metode) && $data->metode == 'Transfer' && $data->status_penyewaan == 'Pending')
            <div class="bg-red-50 border-l-4 border-red-500 rounded-2xl p-4 sm:p-5 flex items-start gap-3.5 shadow-2xs">
                <i class="bi bi-shield-exclamation text-2xl text-red-600 shrink-0 mt-0.5"></i>
                <div>
                    <h4 class="text-xs sm:text-sm font-black text-red-800 uppercase tracking-wide">Peringatan Keamanan Verifikasi Transfer</h4>
                    <p class="text-xs font-semibold text-red-700 mt-1 leading-relaxed">
                        Jangan pernah menekan tombol <strong>"Terima Order"</strong> sebelum Anda memastikan dana benar-benar masuk pada <strong>Mutasi Rekening Bank</strong> Anda. Bukti gambar struk yang diunggah dapat dimanipulasi/diedit oleh pihak tidak bertanggung jawab.
                    </p>
                </div>
            </div>
        @endif

        <!-- Main 2-Column Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left Column: Rented Products & Customer Info (7 cols) -->
            <div class="lg:col-span-7 flex flex-col gap-6">

                <!-- Daftar Barang yang Disewa -->
                <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="bi bi-box-seam-fill"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-gray-900">Daftar Peralatan yang Disewa</h2>
                                <p class="text-xs text-gray-500">Rincian produk, varian warna/ukuran, dan kuantitas sewa</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-blue-50 text-blue-700">
                            {{ $details->count() }} Produk
                        </span>
                    </div>

                    <div class="p-6 flex flex-col gap-5">
                        @foreach ($details as $id_produk => $detailGroup)
                            @php
                                $produk = $detailGroup->first();
                                $totalSubtotal = $detailGroup->sum('subtotal');
                            @endphp
                            <div class="border border-gray-100 rounded-2xl p-4 sm:p-5 bg-gray-50/30 hover:bg-white hover:border-blue-100 transition-all shadow-2xs flex flex-col gap-4">
                                <div class="flex items-center gap-4">
                                    <img class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-2xl border border-gray-200 bg-white shadow-2xs shrink-0"
                                         src="@productPhoto($produk->produk_foto)"
                                         alt="{{ $produk->produk_nama }}"
                                         onerror="this.onerror=null;this.src='{{ asset('assets/image/customers/produk/default.png') }}';">
                                    <div class="min-w-0 flex-1">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 mb-1">
                                            <i class="bi bi-tag-fill"></i> {{ $produk->produk_kategori ?: 'Peralatan Outdoor' }}
                                        </span>
                                        <h3 class="text-base sm:text-lg font-extrabold text-gray-900 truncate">{{ $produk->produk_nama }}</h3>
                                        <p class="text-xs font-medium text-gray-400 mt-0.5">ID Produk: #{{ $id_produk }}</p>
                                    </div>
                                </div>

                                <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-gray-50 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                                <th class="px-4 py-2.5">Warna</th>
                                                <th class="px-4 py-2.5">Ukuran</th>
                                                <th class="px-4 py-2.5 text-center">Jumlah</th>
                                                <th class="px-4 py-2.5 text-right">Subtotal / Hari</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 text-xs sm:text-sm">
                                            @foreach ($detailGroup as $detail)
                                                <tr>
                                                    <td class="px-4 py-3 font-semibold text-gray-800">{{ $detail->warna_produk }}</td>
                                                    <td class="px-4 py-3 font-medium text-gray-600">{{ $detail->ukuran }}</td>
                                                    <td class="px-4 py-3 text-center">
                                                        <span class="px-2.5 py-0.5 rounded-md font-bold bg-gray-100 text-gray-800">{{ $detail->qty }} Pcs</span>
                                                    </td>
                                                    <td class="px-4 py-3 text-right font-bold text-gray-900">
                                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-orange-50/50 border-t border-orange-100 text-xs">
                                            <tr>
                                                <td colspan="3" class="px-4 py-2.5 font-semibold text-gray-600">Subtotal Produk (Per Hari)</td>
                                                <td class="px-4 py-2.5 text-right font-bold text-gray-800">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="px-4 py-2.5 font-extrabold text-orange-700">Total Harga Produk (x{{ $durasi }} Hari)</td>
                                                <td class="px-4 py-2.5 text-right font-black text-orange-600 text-sm">Rp {{ number_format($totalSubtotal * $durasi, 0, ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Informasi Penyewa / Pelanggan -->
                <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="bi bi-person-vcard-fill"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-gray-900">Informasi Penyewa</h2>
                                <p class="text-xs text-gray-500">Data identitas, kontak WhatsApp, dan alamat pelanggan</p>
                            </div>
                        </div>
                        @if (!empty($waPhone))
                            <a href="https://wa.me/{{ $waPhone }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white text-xs font-bold transition-all border border-emerald-200">
                                <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                            </a>
                        @endif
                    </div>

                    <div class="p-6 flex flex-col gap-5">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-gray-50/70 border border-gray-100">
                            <div class="flex items-center gap-3">
                                <img class="w-12 h-12 rounded-2xl object-cover border border-gray-200 bg-white shrink-0"
                                     src="@userPhoto($data->foto)" alt="{{ $data->name }}">
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Nama Penyewa</p>
                                    <p class="text-sm font-extrabold text-gray-900 truncate">{{ $data->name }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-indigo-600 flex items-center justify-center shrink-0">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">No. Telepon / WA</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $data->nomor_telephone ?: '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-purple-600 flex items-center justify-center shrink-0">
                                    <i class="bi bi-gender-ambiguous"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Gender / Tipe</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">
                                        {{ ($data->jenis_kelamin && $data->jenis_kelamin !== '-') ? $data->jenis_kelamin : ($isOffline ? 'Walk-in (POS)' : '-') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat Pelanggan -->
                        <div class="p-4 rounded-2xl border border-gray-100 bg-white flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat Penyewa / Lokasi</p>
                                <p class="text-sm font-semibold text-gray-800 mt-1 leading-relaxed">{{ $address }}</p>
                            </div>
                        </div>

                        <!-- Rekening Bank Pelanggan (Jika Ada) -->
                        @if ($banks && $banks->isNotEmpty())
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">Rekening Bank Penyewa</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($banks as $item)
                                        <div class="p-3.5 rounded-xl border border-gray-200 bg-gray-50/50 flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-2.5">
                                                <i class="bi bi-bank2 text-indigo-600"></i>
                                                <div>
                                                    <div class="text-xs font-extrabold text-gray-900">{{ $item->bank }}</div>
                                                    <div class="text-xs font-medium text-gray-500">{{ $item->rekening }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right Column: Jadwal, Tagihan, Bukti Pembayaran & Jaminan (5 cols) -->
            <div class="lg:col-span-5 flex flex-col gap-6">

                <!-- Jadwal & Rincian Pembayaran Card -->
                <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-gray-900">Jadwal & Ringkasan Tagihan</h2>
                                <p class="text-xs text-gray-500">Durasi penyewaan dan rincian pembayaran</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-purple-100 text-purple-700">
                            {{ $durasi }} Hari
                        </span>
                    </div>

                    <div class="p-6 flex flex-col gap-5">
                        <!-- Tanggal Mulai & Selesai -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-200/80 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold text-emerald-700 uppercase">Mulai Sewa</p>
                                    <p class="text-sm font-black text-gray-900">{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-rose-50/60 border border-rose-200/80 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold text-rose-700 uppercase">Selesai Sewa</p>
                                    <p class="text-sm font-black text-gray-900">{{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan / Pesan Penyewa -->
                        <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200/70 flex items-start gap-3">
                            <i class="bi bi-chat-left-quote-fill text-indigo-500 text-base mt-0.5"></i>
                            <div class="min-w-0 flex-1">
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pesan / Catatan Penyewa</p>
                                <p class="text-xs sm:text-sm font-semibold text-gray-800 mt-0.5">{{ $data->pesan ?: 'Tidak ada catatan khusus' }}</p>
                            </div>
                        </div>

                        <!-- Opsi Pengiriman -->
                        <div class="p-4 rounded-2xl border border-gray-100 bg-gray-50/40 flex flex-col gap-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500">Metode Pengambilan / Pengiriman</span>
                                @if ($isDiantar)
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 font-bold text-xs rounded-full inline-flex items-center gap-1">
                                        <i class="bi bi-truck"></i> Diantar ke Alamat
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-gray-200/70 text-gray-700 font-bold text-xs rounded-full inline-flex items-center gap-1">
                                        <i class="bi bi-shop"></i> Ambil di Toko (Gratis)
                                    </span>
                                @endif
                            </div>
                            @if (!empty($data->alamat_pengiriman))
                                <div class="text-xs text-gray-600 bg-white p-3 rounded-xl border border-gray-200 mt-1">
                                    <strong class="text-gray-800">Alamat Pengantaran:</strong> {{ $data->alamat_pengiriman }}
                                </div>
                            @endif
                        </div>

                        <!-- Rincian Nominal Tagihan -->
                        <div class="p-5 rounded-2xl bg-orange-50/70 border border-orange-200/70 flex flex-col gap-3">
                            <div class="flex items-center justify-between text-xs font-semibold text-gray-600">
                                <span>Metode Pembayaran</span>
                                <span class="font-bold text-gray-900 uppercase">{{ $data->metode ?: '-' }}</span>
                            </div>

                            @if (($data->biaya_antar ?? 0) > 0)
                                <div class="flex items-center justify-between text-xs font-semibold text-gray-600">
                                    <span>Biaya Pengantaran</span>
                                    <span class="font-bold text-gray-800">Rp {{ number_format($data->biaya_antar, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            @if (($data->biaya_admin ?? 0) > 0)
                                <div class="flex items-center justify-between text-xs font-semibold text-gray-600">
                                    <span>Biaya Layanan / Admin</span>
                                    <span class="font-bold text-gray-800">Rp {{ number_format($data->biaya_admin, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between text-xs font-semibold text-gray-600">
                                <span>Jumlah Dibayar Pelanggan</span>
                                <span class="font-extrabold text-gray-900 text-sm">Rp {{ number_format($data->jumlah_pembayaran ?? 0, 0, ',', '.') }}</span>
                            </div>

                            @if (($data->kembalian_pembayaran ?? 0) > 0)
                                <div class="flex items-center justify-between text-xs font-semibold text-emerald-700">
                                    <span>Kembalian Pembayaran</span>
                                    <span class="font-bold">Rp {{ number_format($data->kembalian_pembayaran, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="border-t border-orange-200 pt-3 mt-1 flex items-baseline justify-between">
                                <span class="text-xs font-extrabold text-orange-800 uppercase tracking-wider">Total Tagihan Sewa</span>
                                <span class="text-2xl font-black text-orange-600">Rp {{ number_format($data->total_pembayaran ?? $harus_dibayar, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bukti Pembayaran & Jaminan Identitas Card -->
                <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900">Bukti Pembayaran & Jaminan</h2>
                            <p class="text-xs text-gray-500">Dokumen verifikasi pembayaran dan identitas penyewa</p>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Bukti Pembayaran -->
                        <div class="p-4 bg-gray-50/80 rounded-2xl border border-gray-100 flex flex-col gap-3">
                            <p class="font-bold text-gray-700 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                <i class="bi bi-receipt text-blue-600"></i> Bukti Pembayaran
                            </p>
                            @if ($isOffline || (isset($data->metode) && in_array($data->metode, ['COD', 'Cash'])))
                                <div class="flex flex-col items-center justify-center p-5 bg-white rounded-xl border border-gray-200/80 h-full text-center">
                                    <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2">
                                        <i class="bi bi-cash-coin text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-gray-800 text-xs sm:text-sm">
                                        {{ $isOffline ? 'Transaksi Kasir (POS)' : 'Cash on Delivery (COD)' }}
                                    </p>
                                    <p class="text-[11px] text-gray-500 mt-1">Pembayaran diterima langsung oleh kasir/toko.</p>
                                </div>
                            @elseif ($data->bukti_pembayaran && $data->bukti_pembayaran !== 'Belum di isi')
                                @php
                                    $urlBukti = Str::startsWith($data->bukti_pembayaran, ['http://', 'https://'])
                                        ? $data->bukti_pembayaran
                                        : asset('assets/image/customers/pembayaran/' . $data->bukti_pembayaran);
                                @endphp
                                <div class="relative group cursor-pointer bg-white rounded-xl border border-gray-200 overflow-hidden" onclick="window.open('{{ $urlBukti }}', '_blank')">
                                    <img class="w-full h-44 object-contain bg-white p-1" src="{{ $urlBukti }}" alt="Bukti Pembayaran">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1">
                                        <i class="bi bi-zoom-in"></i> Perbesar Gambar
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center p-5 bg-white rounded-xl border border-dashed border-gray-200 h-full text-center text-gray-400">
                                    <i class="bi bi-image text-2xl mb-1"></i>
                                    <span class="text-xs italic">{{ $data->bukti_pembayaran ?: 'Belum diunggah' }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Bukti Jaminan / KTP -->
                        <div class="p-4 bg-gray-50/80 rounded-2xl border border-gray-100 flex flex-col gap-3">
                            <p class="font-bold text-gray-700 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                <i class="bi bi-person-badge text-indigo-600"></i> Jaminan / Identitas
                            </p>
                            @php
                                $jaminanVal = $data->jaminan_sewa ?? '';
                                $isJaminanImage = \Illuminate\Support\Str::startsWith($jaminanVal, ['http://', 'https://'])
                                    || preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)$/i', $jaminanVal);
                            @endphp
                            @if (isset($data->metode) && $data->metode == 'COD' && (!$jaminanVal || $jaminanVal == 'Belum di isi'))
                                <div class="flex flex-col items-center justify-center p-5 bg-white rounded-xl border border-gray-200/80 h-full text-center">
                                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-2">
                                        <i class="bi bi-person-vcard text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-gray-800 text-xs sm:text-sm">Cek KTP Saat Serah Terima</p>
                                    <p class="text-[11px] text-gray-500 mt-1">Pastikan memeriksa KTP/Identitas asli penyewa.</p>
                                </div>
                            @elseif ($jaminanVal && $jaminanVal !== 'Belum di isi' && $isJaminanImage)
                                @php
                                    $urlJaminan = Str::startsWith($jaminanVal, ['http://', 'https://'])
                                        ? $jaminanVal
                                        : asset('assets/image/customers/jaminan/' . $jaminanVal);
                                @endphp
                                <div class="relative group cursor-pointer bg-white rounded-xl border border-gray-200 overflow-hidden" onclick="window.open('{{ $urlJaminan }}', '_blank')">
                                    <img class="w-full h-44 object-contain bg-white p-1" src="{{ $urlJaminan }}" alt="Bukti Jaminan">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1">
                                        <i class="bi bi-zoom-in"></i> Perbesar Gambar
                                    </div>
                                </div>
                            @elseif ($jaminanVal && $jaminanVal !== 'Belum di isi')
                                <div class="flex flex-col items-center justify-center p-5 bg-white rounded-xl border border-emerald-200 h-full text-center">
                                    <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2">
                                        <i class="bi bi-person-vcard-fill text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-gray-900 text-xs sm:text-sm">{{ $jaminanVal }}</p>
                                    <p class="text-[11px] text-emerald-600 font-semibold mt-1">Jaminan Tercatat</p>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center p-5 bg-white rounded-xl border border-dashed border-gray-200 h-full text-center text-gray-400">
                                    <i class="bi bi-person-vcard text-2xl mb-1"></i>
                                    <span class="text-xs italic">{{ $data->jaminan_sewa ?: 'Belum diisi' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ACTION SECTION 1: Input Pembayaran COD (Jika Belum Lunas) -->
        <div id="section-pembayaran-cod" class="bg-white rounded-[24px] shadow-sm border border-amber-200 overflow-hidden {{ $data->status_pembayaran == 'Belum lunas' ? '' : 'hidden' }}">
            <div class="px-6 py-5 bg-amber-50/70 border-b border-amber-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                        <i class="bi bi-cash-stack text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900">Form Input Pembayaran COD & Jaminan</h3>
                        <p class="text-xs text-gray-600">Masukkan nominal pembayaran tunai dari pelanggan beserta nomor identitas/KTP jaminan.</p>
                    </div>
                </div>
                <div class="px-4 py-2 rounded-xl bg-orange-100 text-orange-800 text-xs font-extrabold">
                    Tagihan Harus Dibayar: Rp {{ number_format($harus_dibayar, 0, ',', '.') }}
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <form id="form-pembayaran-cod" class="flex flex-col gap-6"
                      action="{{ route('menu-transaksi.input-pembayaran-cod', ['id_penyewaan' => $data->id_penyewaan]) }}"
                      method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="harus_dibayar" value="{{ $harus_dibayar }}">
                    <input type="hidden" name="jumlah_pembayaran" id="jumlah_pembayaran_hidden">
                    <input type="hidden" name="kembalian_pembayaran" id="kembalian_pembayaran_hidden">
                    <input type="hidden" name="kurang_pembayaran" id="kurang_pembayaran_hidden">
                    <input type="hidden" name="total_pembayaran" id="total_pembayaran_hidden">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div class="space-y-1.5">
                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider">Jumlah Uang Diterima <span class="text-red-500">*</span></label>
                            <input id="jumlah_pembayaran" type="text" placeholder="Contoh: Rp. 150.000"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-800 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider">Kembalian Pembayaran</label>
                            <input readonly id="kembalian_pembayaran" type="text" placeholder="Rp. 0"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-emerald-700 cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider">Kurang Pembayaran</label>
                            <input readonly id="kurang_pembayaran" type="text" placeholder="Rp. 0"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-red-600 cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider">Total Pembayaran Diterima</label>
                            <input readonly id="total_pembayaran" type="text" placeholder="Rp. 0"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider">Nomor KTP / Identitas Jaminan <span class="text-red-500">*</span></label>
                            <input id="jaminan_sewa" name="jaminan_sewa" type="text" placeholder="Contoh: KTP Asli - 3507123456780001 (Ditahan di Toko)"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button id="simpan-pembayaran" type="button"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all">
                            <i class="bi bi-save2-fill"></i> Simpan Pembayaran COD & Lanjutkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ACTION SECTION 2: Terima / Tolak Order (Jika Status == Pending) -->
        @if ($data->status_penyewaan == 'Pending')
            <div id="section-konfirmasi-order" class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 sm:p-8 flex flex-col gap-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                            <i class="bi bi-check2-square text-blue-600"></i> Konfirmasi Penerimaan Order Masuk
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                            Jika pesanan sesuai dan pembayaran telah valid, klik <strong>Terima Order</strong> untuk memindahkan pesanan ke tahap Pengambilan.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <form id="form-confirm-order"
                          action="{{ route('menu-transaksi.confirm-order-masuk', ['id_penyewaan' => $data->id_penyewaan, 'id_user' => Crypt::encrypt(session('id_user')), 'parameter' => 1]) }}"
                          method="POST">
                        @csrf
                        @method('PUT')
                        <button id="terima-order" type="button" {{ $data->status_pembayaran == 'Belum lunas' ? 'disabled' : '' }}
                            class="w-full py-3.5 px-6 rounded-xl font-bold text-sm flex items-center justify-center gap-2 transition-all shadow-md
                            {{ $data->status_pembayaran == 'Belum lunas' ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700 text-white hover:shadow-lg' }}">
                            <i class="bi bi-check-circle-fill text-base"></i> Terima & Siapkan Pesanan
                        </button>
                    </form>

                    <form action="{{ route('menu-transaksi.batalkan-order', ['id_penyewaan' => $data->id_penyewaan, 'id_user' => Crypt::encrypt(session('id_user'))]) }}"
                          method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menolak/membatalkan pesanan ini? Stok produk akan otomatis dikembalikan.');">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                            class="w-full py-3.5 px-6 rounded-xl font-bold text-sm bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 transition-all flex items-center justify-center gap-2">
                            <i class="bi bi-x-circle-fill text-base"></i> Tolak / Batalkan Pesanan
                        </button>
                    </form>
                </div>
            </div>

        <!-- ACTION SECTION 2B: Serahkan Barang (Jika Status == Pengambilan) -->
        @elseif ($data->status_penyewaan == 'Pengambilan')
            <div class="bg-white rounded-[24px] shadow-sm border border-indigo-100 p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900">Siap Diserahkan kepada Penyewa</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Tekan tombol di samping apabila peralatan sudah diambil/diterima oleh pelanggan untuk mengubah status menjadi <strong>Sewa Berlangsung (Aktif)</strong>.</p>
                    </div>
                </div>
                <form action="{{ route('menu-transaksi.serahkan-barang', ['id_penyewaan' => $data->id_penyewaan, 'id_user' => Crypt::encrypt(session('id_user'))]) }}" method="POST" class="shrink-0">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md hover:shadow-lg transition-all">
                        <i class="bi bi-check2-circle text-base"></i> Serahkan Barang Sekarang
                    </button>
                </form>
            </div>

        <!-- ACTION SECTION 3: Proses Pengembalian Barang & Denda (Jika Status == Aktif / Pengembalian) -->
        @elseif ($data->status_penyewaan == 'Pengembalian' || $data->status_penyewaan == 'Aktif')
            <div id="section-pengembalian" class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 bg-emerald-50/60 border-b border-emerald-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <i class="bi bi-box-arrow-in-down text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900">Proses Pengembalian Barang & Pemeriksaan Kondisi</h3>
                        <p class="text-xs text-gray-600">Periksa kondisi fisik barang yang dikembalikan pelanggan, catat denda jika ada keterlambatan/kerusakan, dan selesaikan penyewaan.</p>
                    </div>
                </div>

                <form id="form-proses-pengembalian"
                      action="{{ route('menu-transaksi.proses-pengembalian', ['id_penyewaan' => $data->id_penyewaan, 'id_user' => Crypt::encrypt(session('id_user'))]) }}"
                      method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 flex flex-col gap-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="space-y-1.5">
                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider">Kondisi Barang Saat Kembali</label>
                            <select name="kondisi_barang" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:outline-none focus:bg-white focus:border-emerald-500 transition-all">
                                <option value="Baik">Baik (Normal - Stok Dikembalikan)</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                                <option value="Hilang">Hilang (Stok Tidak Dikembalikan)</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider">Total Denda (Rp)</label>
                            <input type="number" id="denda_input" name="denda" value="{{ isset($denda_keterlambatan) ? $denda_keterlambatan : 0 }}" min="0"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-800 focus:outline-none focus:bg-white focus:border-emerald-500 transition-all" placeholder="0">
                            @if(isset($info_keterlambatan) && $info_keterlambatan != '')
                                <p class="text-[11px] font-semibold mt-1 flex items-center gap-1 {{ $denda_keterlambatan > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                    <i class="bi {{ $denda_keterlambatan > 0 ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill' }}"></i> {{ $info_keterlambatan }}
                                </p>
                            @endif
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider">Status Pembayaran Denda</label>
                            <select name="status_denda" id="status_denda" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:outline-none focus:bg-white focus:border-emerald-500 transition-all">
                                <option value="tidak_ada" {{ (isset($denda_keterlambatan) && $denda_keterlambatan > 0) ? 'disabled' : 'selected' }}>Tidak Ada Denda</option>
                                <option value="belum_dibayar" {{ (isset($denda_keterlambatan) && $denda_keterlambatan > 0) ? 'selected' : '' }}>Belum Dibayar (Tanggungan)</option>
                                <option value="lunas">Lunas (Langsung Catat ke Pemasukan)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider">Catatan Kondisi / Kerusakan</label>
                            <textarea name="catatan" rows="3"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:outline-none focus:bg-white focus:border-emerald-500 transition-all"
                                placeholder="Contoh: Barang lengkap dan bersih, tidak ada kerusakan..."></textarea>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider">Bukti Foto Kondisi Barang (Opsional)</label>
                            <input type="file" name="bukti_kondisi" accept="image/*"
                                class="w-full text-xs text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-gray-200 rounded-xl p-2 bg-gray-50">
                            <p class="text-[11px] text-gray-400 font-medium mt-1">*Format: JPG, PNG, maksimal 3MB.</p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 font-bold text-sm text-white shadow-md hover:shadow-lg transition-all">
                            <i class="bi bi-check2-all text-base"></i> Selesaikan Penyewaan & Kembalikan Stok
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- ACTION SECTION 4: Informasi Pengembalian & Pelunasan Denda (Jika Status == Selesai) -->
        @if ($data->status_penyewaan == 'Selesai' && isset($pengembalian))
            <div class="bg-white rounded-[24px] shadow-sm border {{ $pengembalian->status_denda === 'belum_dibayar' ? 'border-red-200' : 'border-gray-100' }} overflow-hidden">
                <div class="px-6 py-5 {{ $pengembalian->status_denda === 'belum_dibayar' ? 'bg-red-50/70 border-b border-red-100' : 'bg-blue-50/50 border-b border-blue-100' }} flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $pengembalian->status_denda === 'belum_dibayar' ? 'bg-red-600 text-white' : 'bg-blue-600 text-white' }} flex items-center justify-center shrink-0">
                            <i class="bi {{ $pengembalian->status_denda === 'belum_dibayar' ? 'bi-exclamation-triangle-fill' : 'bi-clipboard2-check-fill' }} text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900">Laporan Pengembalian Barang</h3>
                            <p class="text-xs text-gray-600">Dicatat pada {{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali_aktual)->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if ($pengembalian->status_denda === 'belum_dibayar')
                        <form action="{{ route('menu-transaksi.lunasi-denda', ['id_penyewaan' => $data->id_penyewaan, 'id_user' => Crypt::encrypt(session('id_user'))]) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 font-bold text-xs text-white shadow-sm transition-all"
                                onclick="return confirm('Apakah pelanggan sudah melunasi denda ini? Denda akan dicatat ke dalam tabel Pemasukan toko Anda.')">
                                <i class="bi bi-check-circle-fill"></i> Tandai Denda Lunas (Rp {{ number_format($pengembalian->denda, 0, ',', '.') }})
                            </button>
                        </form>
                    @endif
                </div>

                <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-400 uppercase">Kondisi Barang</p>
                        <p class="text-sm font-black text-gray-900 mt-1">{{ $pengembalian->kondisi_barang }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-400 uppercase">Nominal Denda & Status</p>
                        <p class="text-sm font-black {{ $pengembalian->status_denda === 'belum_dibayar' ? 'text-red-600' : 'text-emerald-600' }} mt-1">
                            Rp {{ number_format($pengembalian->denda ?? 0, 0, ',', '.') }}
                            <span class="text-xs font-bold">({{ strtoupper(str_replace('_', ' ', $pengembalian->status_denda)) }})</span>
                        </p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-400 uppercase">Catatan Pengembalian</p>
                        <p class="text-sm font-semibold text-gray-700 mt-1">{{ $pengembalian->catatan ?: '-' }}</p>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <script>
        var jumlah_pembayaran = document.getElementById('jumlah_pembayaran');
        var terimaOrder = document.getElementById('terima-order');
        var btnSimpanPembayaran = document.getElementById('simpan-pembayaran');

        if (terimaOrder && localStorage.getItem('storageBtnClicked') === 'true') {
            terimaOrder.removeAttribute('disabled');
            terimaOrder.style.opacity = '1';
        }

        if (btnSimpanPembayaran) {
            btnSimpanPembayaran.addEventListener('click', function(even) {
                even.preventDefault();
                var jumlahPembayaran = document.getElementById('jumlah_pembayaran_hidden').value;
                var jaminanSewa = document.getElementById('jaminan_sewa').value;
                if (jumlahPembayaran === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Anda belum memasukkan pembayaran COD Pelanggan!',
                    });
                } else if (jaminanSewa === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Harap masukkan informasi KTP/Lainnya pelanggan untuk jaminan toko anda sendiri!',
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Konfirmasi',
                        text: 'Apakah Anda yakin ingin menyimpan pembayaran ini?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, simpan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            localStorage.setItem('storageBtnClicked', 'true');
                            if (terimaOrder) {
                                terimaOrder.removeAttribute('disabled');
                                terimaOrder.style.opacity = '1';
                            }
                            document.getElementById('form-pembayaran-cod').submit();
                        }
                    });
                }
            });
        }

        if (terimaOrder) {
            terimaOrder.addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Konfirmasi Order',
                    text: 'Setelah anda menerima order ini maka pesanan akan masuk ke tahap Pengambilan!',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Terima Order!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (localStorage.getItem('storageBtnClicked') !== null) {
                            localStorage.removeItem('storageBtnClicked');
                        }
                        document.getElementById('form-confirm-order').submit();
                    }
                });
            });
        }

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        if (jumlah_pembayaran) {
            jumlah_pembayaran.addEventListener('input', function(e) {
                var input = this.value.replace(/[^0-9]/g, '');
                var formatted = formatRupiah(input, 'Rp. ');

                this.value = formatted;

                var jumlahPembayaran = parseInt(input, 10);
                document.getElementById('jumlah_pembayaran_hidden').value = isNaN(jumlahPembayaran) ? '' : jumlahPembayaran;

                var harusDibayar = parseInt(document.getElementById('harus_dibayar').value.replace(/[^0-9]/g, ''), 10) || 0;
                var kembalianPembayaran = jumlahPembayaran > harusDibayar ? jumlahPembayaran - harusDibayar : 0;
                var kurangPembayaran = jumlahPembayaran < harusDibayar ? harusDibayar - jumlahPembayaran : 0;
                var totalPembayaran = jumlahPembayaran || 0;

                document.getElementById('kembalian_pembayaran').value = formatRupiah(kembalianPembayaran.toString(), 'Rp. ');
                document.getElementById('kurang_pembayaran').value = formatRupiah(kurangPembayaran.toString(), 'Rp. ');
                document.getElementById('total_pembayaran').value = formatRupiah(totalPembayaran.toString(), 'Rp. ');

                document.getElementById('kembalian_pembayaran_hidden').value = kembalianPembayaran;
                document.getElementById('kurang_pembayaran_hidden').value = kurangPembayaran;
                document.getElementById('total_pembayaran_hidden').value = totalPembayaran;
            });
        }

        function scrollToElement(targetId) {
            var element = document.getElementById(targetId || 'terima-order');
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        var dendaInput = document.getElementById('denda_input');
        if (dendaInput) {
            dendaInput.addEventListener('input', function(e) {
                var val = parseInt(this.value) || 0;
                var selectDenda = document.getElementById('status_denda');
                var optionTidakAda = selectDenda.querySelector('option[value="tidak_ada"]');
                var optionBelumDibayar = selectDenda.querySelector('option[value="belum_dibayar"]');
                
                if (val > 0) {
                    optionTidakAda.disabled = true;
                    if (selectDenda.value === 'tidak_ada') {
                        optionBelumDibayar.selected = true;
                    }
                } else {
                    optionTidakAda.disabled = false;
                    optionTidakAda.selected = true;
                }
            });
        }
    </script>
@endsection
