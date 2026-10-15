@extends('layouts.customers.layouts-customer')

@section('customer-content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex flex-col gap-6">
    <!-- Breadcrumb & Navigasi -->
    <div class="flex items-center justify-between">
        <button type="button"
                onclick="confirmCancelPayment()"
                class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors">
            <i class="bi bi-arrow-left-short text-2xl mr-1"></i> Batalkan & Kembali ke Kelola Iklan
        </button>
        <span class="text-xs font-semibold text-gray-400">Langkah 3 dari 3: Pembayaran & Aktivasi Iklan</span>
    </div>

    <!-- Main Card Container -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="p-6 sm:p-8 lg:px-10 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 via-white to-blue-50/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-100/70 text-blue-700 font-bold text-xs rounded-full border border-blue-200 mb-3">
                    <i class="bi bi-credit-card-2-front-fill text-xs"></i> Midtrans Payment Gateway
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Konfirmasi & Pembayaran Iklan</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium max-w-2xl leading-relaxed">
                    Periksa kembali rincian materi promosi serta periode tayang banner iklan Anda sebelum menyelesaikan proses transaksi.
                </p>
            </div>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl text-xs font-bold shrink-0">
                <i class="bi bi-shield-lock-fill text-amber-600 text-base"></i>
                <span>Midtrans Sandbox Mode Aktif</span>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-6 sm:p-8 lg:p-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                {{-- Kolom Kiri: Rincian Iklan --}}
                <div class="lg:col-span-7 flex flex-col gap-6">
                    {{-- Detail Banner Card --}}
                    <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50/40 flex flex-col gap-5">
                        <div class="flex items-center justify-between border-b border-gray-200/60 pb-3">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                                <i class="bi bi-image text-blue-600"></i> Materi Poster Banner
                            </span>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 border border-blue-100">
                                Rasio 16:9
                            </span>
                        </div>

                        {{-- Preview Poster --}}
                        <div class="relative w-full aspect-[16/9] rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 shadow-inner">
                            @if(isset($iklan) && $iklan->poster)
                                <img src="{{ asset('assets/image/customers/advert/' . $iklan->poster) }}"
                                     alt="{{ $iklan->judul ?? 'Poster Iklan' }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                    <i class="bi bi-card-image text-4xl mb-1"></i>
                                    <span class="text-xs font-medium">Banner Poster Iklan</span>
                                </div>
                            @endif
                        </div>

                        {{-- Judul & Deskripsi --}}
                        <div class="flex flex-col gap-2">
                            <h2 class="text-lg font-bold text-gray-900 leading-snug">
                                {{ $iklan->judul ?? 'Judul Banner Iklan' }}
                            </h2>
                            @if(isset($iklan) && $iklan->sub_judul)
                                <p class="text-xs font-semibold text-blue-600">
                                    {{ $iklan->sub_judul }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-600 leading-relaxed font-normal mt-1 bg-white p-3.5 rounded-xl border border-gray-100 max-h-48 overflow-y-auto whitespace-pre-line">
                                {{ $iklan->deskripsi ?? 'Tidak ada deskripsi' }}
                            </p>
                        </div>
                    </div>

                    {{-- Periode Penayangan Iklan --}}
                    <div class="p-6 rounded-2xl border border-gray-100 bg-white flex flex-col gap-4">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                            <i class="bi bi-calendar3 text-blue-600"></i> Jadwal Penayangan Iklan
                        </span>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                            <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-100 flex flex-col gap-1">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Tanggal Mulai</span>
                                <span class="text-base font-extrabold text-gray-900">
                                    {{ Carbon\Carbon::parse($tanggal_mulai)->translatedFormat('j F Y') }}
                                </span>
                                <span class="text-[11px] text-gray-500 font-medium">Otomatis tayang setelah lunas</span>
                            </div>

                            <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-100 flex flex-col gap-1">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Tanggal Berakhir</span>
                                <span class="text-base font-extrabold text-gray-900">
                                    {{ Carbon\Carbon::parse($tanggal_akhir)->translatedFormat('j F Y') }}
                                </span>
                                <span class="text-[11px] text-gray-500 font-medium">Berhenti tayang otomatis</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50 rounded-xl text-xs font-medium text-gray-600">
                            <span>Durasi Sewa Paket:</span>
                            <span class="font-extrabold text-gray-900">{{ $durasi }} Hari Penuh</span>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Rincian Tagihan & Tombol Bayar --}}
                <div class="lg:col-span-5 flex flex-col gap-6">
                    <div class="p-6 sm:p-7 rounded-2xl border-2 border-blue-100 bg-gradient-to-b from-white to-blue-50/20 shadow-sm flex flex-col gap-6">
                        <div>
                            <span class="text-xs font-extrabold uppercase tracking-wider text-gray-400 block mb-1">Total Pembayaran</span>
                            <div class="text-3xl font-black text-blue-600 tracking-tight">
                                Rp {{ number_format($harga_iklan, 0, ',', '.') }}
                            </div>
                            <span class="text-xs font-medium text-gray-500 mt-1 block">Termasuk seluruh durasi penayangan banner</span>
                        </div>

                        {{-- Rincian Biaya --}}
                        <div class="border-t border-b border-gray-100 py-4 flex flex-col gap-3 text-sm">
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Paket Iklan ({{ $durasi }} Hari)</span>
                                <span class="font-bold text-gray-900">Rp {{ number_format($harga_iklan, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Biaya Layanan Gateway</span>
                                <span class="font-bold text-emerald-600">GRATIS</span>
                            </div>
                            <div class="flex items-center justify-between text-gray-900 font-black pt-2 border-t border-dashed border-gray-200">
                                <span>Total Tagihan</span>
                                <span class="text-blue-600">Rp {{ number_format($harga_iklan, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Info Metode Pembayaran Midtrans --}}
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/70 flex flex-col gap-2">
                            <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                <i class="bi bi-wallet2 text-blue-600"></i> Pilihan Metode Pembayaran:
                            </span>
                            <p class="text-[12px] text-gray-500 leading-relaxed">
                                Tersedia Transfer Bank / Virtual Account (BCA, BNI, BRI, Mandiri, Permata), QRIS, GoPay, ShopeePay, dan Kartu Debit/Kredit melalui Midtrans.
                            </p>
                        </div>

                        {{-- Form Submit ke Backend --}}
                        <form id="simpan-pembayaran"
                              action="{{ route('simpan-pembayaran-iklan.simpan', ['id_user' => Crypt::encrypt(session('id_user'))]) }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="id_iklan" value="{{ $id_iklan }}">
                            <input type="hidden" name="id_user" value="{{ $id_user }}">
                            <input type="hidden" name="tanggal_mulai" value="{{ $tanggal_mulai }}">
                            <input type="hidden" name="tanggal_akhir" value="{{ $tanggal_akhir }}">
                            <input type="hidden" name="harga_iklan" value="{{ $harga_iklan }}">

                            {{-- Field yang diisi oleh callback Midtrans --}}
                            <input type="hidden" name="midtrans_order_id" id="midtrans_order_id" value="">
                            <input type="hidden" name="payment_type" id="payment_type" value="">
                            <input type="hidden" name="midtrans_transaction_status" id="midtrans_transaction_status" value="">

                            <div class="flex flex-col gap-3">
                                <button type="button"
                                        id="pay-button"
                                        class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-black text-sm rounded-xl shadow-lg shadow-blue-500/25 transition-all duration-200 cursor-pointer">
                                    <i class="bi bi-credit-card-2-front text-lg"></i> Bayar Iklan Sekarang
                                </button>

                                <a href="{{ route('cek-status-pembayaran-iklan', ['id_iklan' => Crypt::encrypt($id_iklan)]) }}"
                                   class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 font-bold text-xs rounded-xl transition-all">
                                    <i class="bi bi-arrow-repeat text-base"></i> Cek Status Pembayaran (Sudah Bayar)
                                </a>

                                <a href="{{ route('lanjut-pembayaran-iklan', ['id_iklan' => Crypt::encrypt($id_iklan)]) }}"
                                   class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100 font-bold text-xs rounded-xl transition-all"
                                   title="Dapatkan kode QR / Virtual Account baru jika sesi sebelumnya habis waktu">
                                    <i class="bi bi-arrow-clockwise text-sm"></i> Perbarui Sesi / Kode Pembayaran Baru
                                </a>

                                <button type="button"
                                        id="btn-cancel-transaksi"
                                        onclick="confirmCancelPayment()"
                                        class="w-full inline-flex items-center justify-center px-6 py-3 border border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-bold text-xs rounded-xl transition-all cursor-pointer">
                                    <i class="bi bi-arrow-left mr-1.5"></i> Simpan & Kembali ke Menu Iklan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Panduan Uji Coba Sandbox --}}
                    <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 text-amber-900 text-xs flex flex-col gap-1.5">
                        <span class="font-bold flex items-center gap-1.5 text-amber-800">
                            <i class="bi bi-info-circle-fill text-amber-600"></i> Panduan Uji Coba Midtrans Sandbox:
                        </span>
                        <p class="text-amber-700 leading-relaxed">
                            Pada popup Midtrans yang muncul, Anda dapat memilih metode simulator (QRIS, Virtual Account, GoPay sandbox) untuk menguji alur pembayaran. Transaksi yang belum dibayar akan tersimpan di daftar <b>Menunggu Pembayaran</b>. Jika batas waktu pembayaran habis, klik tombol <b>Perbarui Sesi</b>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Skrip Snap Midtrans --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.clientKey') }}"></script>
<script type="text/javascript">
    function confirmCancelPayment() {
        Swal.fire({
            title: 'Kembali ke Menu Iklan?',
            text: 'Materi iklan Anda telah tersimpan dan dapat dilanjutkan pembayarannya kapan saja melalui menu Iklan (Menunggu Pembayaran).',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Lihat Daftar Iklan',
            cancelButtonText: 'Tetap di Halaman Ini'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('buat-iklan.index', ['id_user' => Crypt::encrypt(session('id_user'))]) }}";
            }
        });
    }

    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function(event) {
            event.preventDefault();

            if (typeof snap === 'undefined') {
                Swal.fire({
                    title: 'Midtrans Tidak Dapat Dimuat',
                    text: 'Gagal memuat sistem pembayaran Midtrans Sandbox. Silakan periksa koneksi internet Anda atau muat ulang halaman.',
                    icon: 'error',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            const snapToken = '{{ $snap_token }}';
            if (!snapToken) {
                Swal.fire({
                    title: 'Sesi Pembayaran Perlu Diperbarui',
                    text: 'Token transaksi pembayaran belum tersedia atau telah kedaluwarsa. Klik tombol di bawah untuk memperbarui.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Perbarui Sesi Pembayaran',
                    cancelButtonText: 'Tutup'
                }).then((swalRes) => {
                    if (swalRes.isConfirmed) {
                        window.location.href = "{{ route('lanjut-pembayaran-iklan', ['id_iklan' => Crypt::encrypt($id_iklan)]) }}";
                    }
                });
                return;
            }

            snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('payment success', result);
                    document.getElementById('midtrans_order_id').value = result.order_id || '';
                    document.getElementById('payment_type').value = result.payment_type || '';
                    document.getElementById('midtrans_transaction_status').value = result.transaction_status || 'settlement';

                    Swal.fire({
                        title: 'Pembayaran Berhasil!',
                        text: 'Terima kasih, transaksi Anda telah sukses diproses. Sedang mengaktifkan materi iklan banner...',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        document.getElementById('simpan-pembayaran').submit();
                    });
                },
                onPending: function(result) {
                    console.log('payment pending', result);
                    document.getElementById('midtrans_order_id').value = result.order_id || '';
                    document.getElementById('payment_type').value = result.payment_type || '';
                    document.getElementById('midtrans_transaction_status').value = result.transaction_status || 'pending';

                    // Update form field via background fetch so it's registered on server
                    fetch("{{ route('simpan-pembayaran-iklan.simpan', ['id_user' => Crypt::encrypt(session('id_user'))]) }}", {
                        method: 'POST',
                        body: new FormData(document.getElementById('simpan-pembayaran')),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).catch(e => console.log('background save:', e));

                    Swal.fire({
                        title: 'Menunggu Pembayaran',
                        text: 'Instruksi pembayaran (QRIS / Virtual Account) telah diterbitkan. Silakan selesaikan pembayaran di aplikasi Anda.',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#10b981',
                        confirmButtonText: 'Lihat Daftar Iklan (Bayar Nanti)',
                        cancelButtonText: 'Saya Sudah Membayar'
                    }).then((swalRes) => {
                        if (swalRes.isConfirmed) {
                            document.getElementById('simpan-pembayaran').submit();
                        } else if (swalRes.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = "{{ route('cek-status-pembayaran-iklan', ['id_iklan' => Crypt::encrypt($id_iklan)]) }}";
                        }
                    });
                },
                onError: function(result) {
                    console.error('payment error', result);
                    const msg = (result && result.status_message) ? result.status_message : '';
                    const isExpired = msg.toLowerCase().includes('expire') || msg.toLowerCase().includes('kadaluarsa') || msg.toLowerCase().includes('expired');

                    Swal.fire({
                        title: isExpired ? 'Sesi Pembayaran Kedaluwarsa' : 'Pembayaran Gagal',
                        text: isExpired
                            ? 'Batas waktu pembayaran sebelumnya telah berakhir di Midtrans. Klik tombol di bawah untuk membuat sesi dan kode pembayaran baru yang aktif.'
                            : (msg || 'Terjadi kendala saat memproses pembayaran Midtrans.'),
                        icon: isExpired ? 'warning' : 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Perbarui Sesi Pembayaran',
                        cancelButtonText: 'Tutup'
                    }).then((swalRes) => {
                        if (swalRes.isConfirmed) {
                            window.location.href = "{{ route('lanjut-pembayaran-iklan', ['id_iklan' => Crypt::encrypt($id_iklan)]) }}";
                        }
                    });
                },
                onClose: function() {
                    Swal.fire({
                        title: 'Jendela Pembayaran Ditutup',
                        text: 'Materi iklan Anda aman tersimpan pada daftar "Menunggu Pembayaran". Klik tombol "Bayar Iklan Sekarang" jika ingin membuka kembali jendela pembayaran.',
                        icon: 'info',
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Mengerti'
                    });
                }
            });
        });
    }
</script>
@endsection
