@extends('layouts.customers.layouts-customer')
@section('customer-content')
    <div class="--container w-full px-6 py-4 h-auto sm:px-6 sm:py-4 flex justify-center">
        <div class="--table w-full flex flex-col gap-6">
            <div class="--action">
                <div class="--filter">
                    <form method="GET" id="form-filter-kelola-iklan">
                        @csrf
                        <div class="w-fit relative">
                            <select class="shadow-box-shadow-11 cursor-pointer rounded-lg bg-white appearance-none px-6 py-2"
                                name="filter_kelola_iklan" id="filter-kelola-iklan">
                                <option value="semua">Semua Status</option>
                                <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                                <option value="aktif">Aktif</option>
                                <option value="pending">Pending (Menunggu Giliran)</option>
                                <option value="nonaktif">Ditangguhkan (Suspended)</option>
                                <option value="selesai_paksa">Diselesaikan Paksa</option>
                                <option value="selesai">Selesai Normal</option>
                            </select>
                            <i class="absolute right-2 top-1/2 transform -translate-y-1/2 bi bi-caret-down-fill"></i>
                        </div>
                    </form>
                </div>
            </div>

            @if (isset($iklan_belum_bayar) && $iklan_belum_bayar->count() > 0)
            {{-- Alert Tagihan Menunggu Pembayaran --}}
            <div class="w-full rounded-2xl border-2 border-amber-300 bg-amber-50/90 p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="bi bi-wallet2 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-950">Ada {{ $iklan_belum_bayar->count() }} Iklan Menunggu Pembayaran</h4>
                        <p class="text-xs text-amber-700 mt-0.5">Selesaikan transaksi agar materi iklan Anda dapat segera dijadwalkan dan ditayangkan di beranda.</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('filter-kelola-iklan').value='menunggu_pembayaran'; document.getElementById('form-filter-kelola-iklan').submit();"
                    class="rounded-xl bg-amber-500 text-white px-4 py-2 text-xs font-bold hover:bg-amber-600 transition shadow-sm shrink-0">
                    Lihat Tagihan
                </button>
            </div>
            @endif

            @if (isset($iklan_nonaktif) && $iklan_nonaktif->count() > 0)
            {{-- Alert Penangguhan Iklan di Halaman Kelola Iklan --}}
            <div class="w-full rounded-2xl border-2 border-red-200 bg-red-50/80 p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="bi bi-exclamation-octagon text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-red-950">Ada {{ $iklan_nonaktif->count() }} Iklan yang Ditangguhkan (Suspend)</h4>
                        <p class="text-xs text-red-700 mt-0.5">Iklan sementara ditarik dari tayangan. Silakan klik tombol <b>Perbaiki</b> pada baris iklan terkait untuk membaca instruksi revisi dari admin.</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('filter-kelola-iklan').value='nonaktif'; document.getElementById('form-filter-kelola-iklan').submit();"
                    class="rounded-xl bg-red-600 text-white px-4 py-2 text-xs font-bold hover:bg-red-700 transition shadow-sm shrink-0">
                    Lihat Iklan Ditangguhkan
                </button>
            </div>
            @endif

            @if (isset($iklan_force_completed) && $iklan_force_completed->count() > 0)
            {{-- Alert Iklan Diselesaikan Paksa di Halaman Kelola Iklan --}}
            <div class="w-full rounded-2xl border-2 border-orange-300 bg-orange-50/90 p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="bi bi-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-orange-950">Ada {{ $iklan_force_completed->count() }} Iklan Diselesaikan Lebih Awal (Paksa) oleh Admin</h4>
                        <p class="text-xs text-orange-800 mt-0.5">Iklan telah dihentikan sebelum jadwal berakhir. Selama rentang waktu tayang belum terlewati, iklan ini dapat direaktifkan kembali jika Anda mengajukan permohonan ke admin.</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('filter-kelola-iklan').value='selesai_paksa'; document.getElementById('form-filter-kelola-iklan').submit();"
                    class="rounded-xl bg-orange-600 text-white px-4 py-2 text-xs font-bold hover:bg-orange-700 transition shadow-sm shrink-0">
                    Lihat Iklan Dihentikan
                </button>
            </div>
            @endif

            <div class="--table-content w-full">
                <div class="w-full">
                    <div class="w-full h-[700px] overflow-scroll px-0">
                        <table class="w-full bg-white min-w-max table-auto text-left">
                            <thead class="w-full">
                                <tr>
                                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                                        <p
                                            class="block antialiased font-sans text-sm text-blue-gray-900 font-normal leading-none opacity-70">
                                            Heading</p>
                                    </th>
                                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                                        <p
                                            class="block antialiased font-sans text-sm text-blue-gray-900 font-normal leading-none opacity-70">
                                            Harga</p>
                                    </th>
                                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                                        <p
                                            class="block antialiased font-sans text-sm text-blue-gray-900 font-normal leading-none opacity-70">
                                            Waktu</p>
                                    </th>
                                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                                        <p
                                            class="block antialiased font-sans text-sm text-blue-gray-900 font-normal leading-none opacity-70">
                                            Status</p>
                                    </th>
                                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                                        <p
                                            class="block antialiased font-sans text-sm text-blue-gray-900 font-normal leading-none opacity-70">
                                        </p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_iklan as $item)
                                    <tr>
                                        <td class="p-4 border-b border-blue-gray-50">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ asset('assets/image/customers/advert/'. $item->poster)}}"
                                                    alt="Spotify"
                                                    class="inline-block relative object-center w-12 h-12 rounded-lg border border-blue-gray-50 bg-blue-gray-50/50 object-contain p-1">
                                                <div>
                                                    <p
                                                        class="truncate max-w-xs block antialiased font-sans text-sm leading-normal text-blue-gray-900 font-bold">
                                                        {{ $item->judul }}</p>
                                                    <div class="truncate max-w-md">
                                                        <span class="text-sm font-normal">{{ $item->sub_judul }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 border-b border-blue-gray-50">
                                            <p
                                                class="block antialiased font-sans text-sm leading-normal text-blue-gray-900 font-normal">
                                                Rp. {{ number_format($item->harga_iklan ?? 0, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="p-4 border-b border-blue-gray-50">
                                            <p
                                                class="block antialiased font-sans text-sm leading-normal text-blue-gray-900 font-normal">
                                                {{ $item->durasi_hari }} Hari</p>
                                        </td>
                                        <td class="p-4 border-b border-blue-gray-50">
                                            <div class="w-max">
                                                @php
                                                    $statusTampilan = $item->status_tampilan ?? $item->status_iklan;
                                                @endphp
                                                @if ($statusTampilan == 'Menunggu Pembayaran')
                                                    <div class="relative grid items-center font-sans font-bold uppercase whitespace-nowrap select-none bg-amber-500 text-white py-1 px-2.5 text-xs rounded-md shadow-sm">
                                                        <span>Menunggu Pembayaran</span>
                                                    </div>
                                                @elseif ($statusTampilan == 'Diselesaikan Paksa')
                                                    <div class="flex flex-col gap-1">
                                                        <div class="relative grid items-center font-sans font-bold uppercase whitespace-nowrap select-none bg-orange-600 text-white py-1 px-2.5 text-xs rounded-md shadow-sm">
                                                            <span>Diselesaikan Paksa</span>
                                                        </div>
                                                        @php
                                                            $isStillActivePeriod = !empty($item->tanggal_akhir) && \Carbon\Carbon::today()->lte(\Carbon\Carbon::parse($item->tanggal_akhir));
                                                        @endphp
                                                        @if ($isStillActivePeriod)
                                                            <span class="text-[10px] text-amber-700 font-semibold bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200 flex items-center gap-1" title="Rentang masa tayang iklan masih aktif">
                                                                <i class="bi bi-clock-history"></i> Rentang Masih Aktif
                                                            </span>
                                                        @else
                                                            <span class="text-[10px] text-slate-500 font-semibold bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200 flex items-center gap-1" title="Rentang masa tayang iklan telah kadaluarsa">
                                                                <i class="bi bi-x-circle"></i> Waktu Berakhir
                                                            </span>
                                                        @endif
                                                    </div>
                                                @elseif ($statusTampilan == 'Selesai')
                                                    <div class="relative grid items-center font-sans font-bold uppercase whitespace-nowrap select-none bg-slate-100 text-slate-700 py-1 px-2.5 text-xs rounded-md">
                                                        <span>Selesai</span>
                                                    </div>
                                                @elseif ($statusTampilan == 'Pending')
                                                    <div class="relative grid items-center font-sans font-bold uppercase whitespace-nowrap select-none bg-amber-500/20 text-amber-900 py-1 px-2.5 text-xs rounded-md">
                                                        <span>Pending</span>
                                                    </div>
                                                @elseif ($statusTampilan == 'Aktif')
                                                    <div class="relative grid items-center font-sans font-bold uppercase whitespace-nowrap select-none bg-green-500/20 text-green-900 py-1 px-2.5 text-xs rounded-md">
                                                        <span>Aktif</span>
                                                    </div>
                                                @elseif (strtolower($statusTampilan) == 'nonaktif')
                                                    <div class="flex flex-col gap-1">
                                                        <div class="relative grid items-center font-sans font-bold uppercase whitespace-nowrap select-none bg-red-600 text-white py-1 px-2.5 text-xs rounded-md shadow-sm">
                                                            <span>Ditangguhkan</span>
                                                        </div>
                                                        @if (($item->suspend_status_revisi ?? '') === 'sudah_diperbaiki')
                                                             <span class="text-[10px] text-emerald-700 font-semibold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200 flex items-center gap-1">
                                                                <i class="bi bi-check-circle"></i> Sudah Direvisi
                                                            </span>
                                                        @else
                                                            <span class="text-[10px] text-red-600 font-semibold bg-red-50 px-1.5 py-0.5 rounded border border-red-200 flex items-center gap-1">
                                                                <i class="bi bi-exclamation-circle"></i> Butuh Perbaikan
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="p-4 border-b border-blue-gray-50">
                                            <div class="flex items-center gap-1.5">
                                                {{-- Tombol Bayar Sekarang jika belum lunas --}}
                                                @if (($item->status_tampilan ?? '') == 'Menunggu Pembayaran')
                                                <a href="{{ route('lanjut-pembayaran-iklan', ['id_iklan' => Crypt::encrypt($item->id_iklan)]) }}"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-sm transition"
                                                    title="Lanjutkan Pembayaran Iklan">
                                                    <i class="bi bi-credit-card-2-front"></i>
                                                    <span>Bayar Sekarang</span>
                                                </a>

                                                @if(!empty($item->midtrans_order_id))
                                                <a href="{{ route('cek-status-pembayaran-iklan', ['id_iklan' => Crypt::encrypt($item->id_iklan)]) }}"
                                                    class="p-1.5 rounded-lg text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200 font-bold transition"
                                                    title="Cek Status Transaksi di Midtrans">
                                                    <i class="bi bi-arrow-repeat text-sm"></i>
                                                </a>
                                                @endif
                                                @endif

                                                {{-- Tombol Perbaiki / Edit Iklan: Hanya untuk status Aktif dan Nonaktif yang belum selesai/kadaluarsa --}}
                                                @php
                                                    $isSelesaiOrKadaluarsa = in_array(strtolower($item->status_iklan ?? ''), ['selesai'])
                                                        || in_array(strtolower($statusTampilan ?? ''), ['selesai', 'diselesaikan paksa', 'menunggu pembayaran'])
                                                        || !empty($item->is_force_completed)
                                                        || (!empty($item->tanggal_akhir) && \Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($item->tanggal_akhir)));
                                                    $canEditIklan = in_array(strtolower($item->status_iklan ?? ''), ['aktif', 'nonaktif']) && !$isSelesaiOrKadaluarsa;
                                                @endphp
                                                @if ($canEditIklan)
                                                <button onclick="window.location.href='{{ route('kelola-iklan.update-iklan-view', ['id_iklan' => Crypt::encrypt($item->id_iklan)]) }}'"
                                                    class="relative align-middle select-none font-sans font-semibold text-center transition-all px-2.5 py-1.5 rounded-lg text-xs {{ strtolower($item->status_iklan) == 'nonaktif' ? 'bg-red-600 text-white hover:bg-red-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100 border border-gray-200' }}"
                                                    type="button" title="{{ strtolower($item->status_iklan) == 'nonaktif' ? 'Perbaiki Iklan Ini' : 'Update Iklan' }}">
                                                    <span class="flex items-center gap-1">
                                                        <i class="bi bi-pencil-square"></i>
                                                        @if (strtolower($item->status_iklan) == 'nonaktif')
                                                             <span>Perbaiki</span>
                                                        @endif
                                                    </span>
                                                </button>
                                                @endif

                                                {{-- Tombol Info Alasan Suspend --}}
                                                @if (strtolower($item->status_iklan ?? '') == 'nonaktif' && !empty($item->suspend_alasan))
                                                <button type="button" onclick='showSuspendDetailModal(@json($item))'
                                                    class="p-1.5 rounded-lg text-xs bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 font-bold"
                                                    title="Baca Alasan Penangguhan & Instruksi Admin">
                                                    <i class="bi bi-info-circle text-sm"></i>
                                                </button>
                                                @endif

                                                {{-- Tombol Info Alasan Selesai Paksa --}}
                                                @if (!empty($item->is_force_completed))
                                                <button type="button" onclick='showForceCompleteDetailModal(@json($item))'
                                                    class="p-1.5 rounded-lg text-xs bg-orange-50 text-orange-700 hover:bg-orange-100 border border-orange-200 font-bold"
                                                    title="Baca Alasan Penghentian Paksa oleh Admin">
                                                    <i class="bi bi-exclamation-circle text-sm"></i>
                                                </button>
                                                @endif

                                                <button
                                                    id="btn-form-delete-kelola-iklan-{{ $item->id_iklan}}"
                                                    class="relative align-middle select-none font-sans font-medium text-center uppercase transition-all w-8 h-8 rounded-lg text-xs text-gray-400 hover:text-red-600 hover:bg-red-50"
                                                    type="button" title="{{ (($item->status_tampilan ?? '') == 'Menunggu Pembayaran') ? 'Batalkan Draft Iklan' : 'Hapus Iklan' }}">
                                                    <i class="bi bi-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <form id="form-delete-kelola-iklan-{{ $item->id_iklan }}" action="{{ route('kelola-iklan.delete', ['id_iklan' => $item->id_iklan]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            var filter = "{{ request()->input('filter_kelola_iklan') }}";

            // Set the selected filter option in the dropdown select
            if (filter) {
                document.getElementById('filter-kelola-iklan').value = filter;
            }

            // Add change event listener to the dropdown select
            document.getElementById('filter-kelola-iklan').addEventListener('change', function() {
                // Submit the form
                document.getElementById('form-filter-kelola-iklan').submit();
            });
        });

        document.querySelectorAll('[id^="btn-form-delete-kelola-iklan-"]').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Apa kamu yakin?',
                    text: "Data ini tidak akan kembali ketika dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus ini!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const penghasilanId = this.id.replace('btn-form-delete-kelola-iklan-', '');
                        document.getElementById('form-delete-kelola-iklan-' + penghasilanId).submit();
                    } else {
                        Swal.fire('Dibatalkan', 'Penghapusan dibatalkan', 'info');
                    }
                });
            });
        });

        function showSuspendDetailModal(item) {
            document.getElementById('custModalJudul').innerText = item.judul || '-';
            document.getElementById('custModalAlasan').innerText = item.suspend_alasan || 'Pelanggaran ketentuan konten iklan';
            document.getElementById('custModalCatatan').innerText = item.suspend_catatan || 'Silakan sesuaikan isi materi iklan dengan kebijakan platform KampSewa.';

            var statusRevisiEl = document.getElementById('custModalStatusRevisi');
            if (item.suspend_status_revisi === 'sudah_diperbaiki') {
                statusRevisiEl.innerText = 'Sudah Direvisi (Menunggu Konfirmasi Admin)';
                statusRevisiEl.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200';
            } else {
                statusRevisiEl.innerText = 'Menunggu Revisi dari Anda';
                statusRevisiEl.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200';
            }

            var bagianContainer = document.getElementById('custModalBagianList');
            bagianContainer.innerHTML = '';
            var list = [];
            try {
                list = typeof item.suspend_bagian_revisi === 'string' ? JSON.parse(item.suspend_bagian_revisi) : (item.suspend_bagian_revisi || []);
            } catch (e) {
                list = [];
            }
            if (Array.isArray(list) && list.length > 0) {
                list.forEach(function(bg) {
                    var span = document.createElement('span');
                    span.className = 'rounded-md bg-red-100 text-red-800 border border-red-200 px-2 py-0.5 text-[11px] font-bold uppercase';
                    var label = bg;
                    if (bg === 'poster') label = 'Gambar / Poster Banner';
                    else if (bg === 'judul') label = 'Judul Iklan';
                    else if (bg === 'sub_judul') label = 'Sub Judul';
                    else if (bg === 'deskripsi') label = 'Deskripsi Iklan';
                    span.innerText = label;
                    bagianContainer.appendChild(span);
                });
            } else {
                bagianContainer.innerHTML = '<span class="text-xs text-gray-500 italic">Materi keseluruhan iklan</span>';
            }

            var editBaseUrl = '{{ url("customers/menu-iklan/kelola-iklan/update-iklan-view") }}';
            document.getElementById('custModalPerbaikiBtn').href = editBaseUrl + '/' + item.id_iklan;

            document.getElementById('modalCustomerSuspendDetail').classList.remove('hidden');
        }

        function closeCustomerSuspendModal() {
            document.getElementById('modalCustomerSuspendDetail').classList.add('hidden');
        }

        function showForceCompleteDetailModal(item) {
            document.getElementById('custFcModalJudul').innerText = item.judul || '-';
            document.getElementById('custFcModalAlasan').innerText = item.force_complete_alasan || 'Permintaan penghentian atau kebijakan operasional platform';
            document.getElementById('custFcModalCatatan').innerText = item.force_complete_catatan || 'Iklan ini telah dihentikan sebelum masa tayang normal berakhir.';

            var waktuEl = document.getElementById('custFcModalWaktu');
            if (item.force_completed_at) {
                waktuEl.innerText = item.force_completed_at;
            } else {
                waktuEl.innerText = '-';
            }

            var rentangEl = document.getElementById('custFcModalRentangStatus');
            var isMasihAktif = false;
            var tglAkhirFormatted = '-';
            if (item.tanggal_akhir) {
                var today = new Date();
                today.setHours(0,0,0,0);
                var endDate = new Date(item.tanggal_akhir);
                endDate.setHours(0,0,0,0);
                isMasihAktif = today <= endDate;
                tglAkhirFormatted = new Date(item.tanggal_akhir).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            }

            if (isMasihAktif) {
                rentangEl.innerHTML = `
                    <div class="p-4 sm:p-5 rounded-2xl bg-emerald-50/90 border-2 border-emerald-300 text-emerald-950 shadow-sm">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-600/30 mt-0.5">
                                <i class="bi bi-check-circle-fill text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-600 text-white shadow-xs">
                                        Rentang Masih Aktif
                                    </span>
                                    <span class="text-xs font-bold text-emerald-800 bg-emerald-100/80 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                        Masa Tayang s/d: ${tglAkhirFormatted}
                                    </span>
                                </div>
                                <h5 class="text-sm font-black text-emerald-900 mb-1">Dapat Direaktifkan oleh Admin!</h5>
                                <p class="text-xs text-emerald-800 leading-relaxed font-medium">
                                    Karena tenggat jadwal tayang iklan ini masih belum berakhir, iklan dapat diaktifkan kembali jika penghentian terjadi karena ketidaksengajaan atau Anda ingin melanjutkan promo. Silakan hubungi tim Admin untuk permohonan reaktivasi.
                                </p>
                            </div>
                        </div>
                    </div>`;
            } else {
                rentangEl.innerHTML = `
                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-100 border border-slate-200 text-slate-800">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-slate-300 text-slate-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="bi bi-slash-circle text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-slate-200 text-slate-700">
                                        Masa Tayang Kadaluarsa
                                    </span>
                                    <span class="text-xs font-semibold text-slate-500">Berakhir: ${tglAkhirFormatted}</span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                    Batas tanggal tayang iklan ini telah terlewati sehingga statusnya kini telah kadaluarsa permanen dan tidak dapat direaktifkan lagi.
                                </p>
                            </div>
                        </div>
                    </div>`;
            }

            document.getElementById('modalCustomerForceCompleteDetail').classList.remove('hidden');
        }

        function closeCustomerForceCompleteModal() {
            document.getElementById('modalCustomerForceCompleteDetail').classList.add('hidden');
        }
    </script>

    {{-- Modal Detail Penangguhan Iklan (Sisi Customer) --}}
    <div id="modalCustomerSuspendDetail" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm p-4 flex items-center justify-center">
        <div class="relative w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl border border-gray-100 my-8">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 bg-red-50/70">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-700">
                        <i class="bi bi-exclamation-octagon text-lg"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Alasan Iklan Ditangguhkan</h3>
                        <p class="text-xs text-gray-500">Instruksi perbaikan dari tim Admin KampSewa</p>
                    </div>
                </div>
                <button type="button" onclick="closeCustomerSuspendModal()" class="rounded-lg p-1.5 text-gray-400 hover:bg-white hover:text-gray-600 transition">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider block">Judul Iklan:</span>
                        <h4 id="custModalJudul" class="text-sm font-bold text-gray-900 mt-0.5"></h4>
                    </div>
                    <span id="custModalStatusRevisi"></span>
                </div>

                <div class="p-3.5 rounded-xl bg-red-50/80 border border-red-200">
                    <span class="text-xs font-bold text-red-800 block">Alasan Penangguhan:</span>
                    <p id="custModalAlasan" class="text-xs font-semibold text-red-700 mt-1"></p>
                </div>

                <div>
                    <span class="text-xs font-bold text-gray-700 block mb-1">Bagian yang Wajib Anda Perbaiki:</span>
                    <div id="custModalBagianList" class="flex flex-wrap gap-1.5"></div>
                </div>

                <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200">
                    <span class="text-xs font-bold text-gray-700 block mb-1">Catatan / Instruksi dari Admin:</span>
                    <p id="custModalCatatan" class="text-xs text-gray-600 italic whitespace-pre-line"></p>
                </div>
            </div>

            <div class="border-t border-gray-100 px-6 py-4 bg-gray-50 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeCustomerSuspendModal()" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-100 transition">
                    Tutup
                </button>
                <a id="custModalPerbaikiBtn" href="#" class="rounded-xl bg-red-600 text-white px-5 py-2 text-xs font-bold hover:bg-red-700 transition flex items-center gap-1.5 shadow-sm">
                    <i class="bi bi-pencil-square"></i> Perbaiki Sekarang
                </a>
            </div>
        </div>
    </div>

    {{-- Modal Detail Diselesaikan Paksa (Sisi Customer) - Spacious & Elegant --}}
    <div id="modalCustomerForceCompleteDetail" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm p-4 sm:p-6 flex items-center justify-center">
        <div class="relative w-full max-w-xl overflow-hidden rounded-[28px] bg-white shadow-2xl border border-gray-100 my-8">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 bg-gradient-to-r from-orange-50 via-amber-50/80 to-white">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-600 text-white shadow-md shadow-orange-600/25">
                        <i class="bi bi-stop-circle text-xl"></i>
                    </span>
                    <div>
                        <h3 class="text-base sm:text-lg font-black text-gray-900">Detail Penghentian Iklan</h3>
                        <p class="text-xs text-gray-500">Informasi penyelesaian paksa oleh Admin KampSewa</p>
                    </div>
                </div>
                <button type="button" onclick="closeCustomerForceCompleteModal()" class="rounded-xl p-2 text-gray-400 hover:bg-white hover:text-gray-600 transition shadow-xs">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            <div class="p-6 sm:p-7 space-y-5 max-h-[calc(100vh-210px)] overflow-y-auto">
                {{-- Judul Iklan Card --}}
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/90 border border-slate-200/80">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">
                        Judul Iklan:
                    </span>
                    <h4 id="custFcModalJudul" class="text-sm sm:text-base font-black text-slate-900 leading-snug"></h4>
                </div>

                {{-- Status Rentang Waktu Aktif Card (Full Width with Rich Padding) --}}
                <div id="custFcModalRentangStatus" class="w-full"></div>

                {{-- Alasan Penghentian --}}
                <div class="p-4 sm:p-5 rounded-2xl bg-orange-50/90 border border-orange-200">
                    <span class="text-xs font-extrabold text-orange-900 block mb-1">
                        <i class="bi bi-exclamation-triangle-fill text-orange-600 mr-1"></i> Alasan Penghentian:
                    </span>
                    <p id="custFcModalAlasan" class="text-xs sm:text-sm font-bold text-orange-800 leading-relaxed"></p>
                </div>

                {{-- Catatan dari Admin --}}
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <i class="bi bi-chat-left-text text-slate-500"></i> Catatan & Keterangan Admin:
                        </span>
                        <span class="text-[11px] font-semibold text-slate-500 bg-white px-2 py-0.5 rounded-md border border-slate-200 flex items-center gap-1">
                            <i class="bi bi-clock"></i> <span id="custFcModalWaktu">-</span>
                        </span>
                    </div>
                    <p id="custFcModalCatatan" class="text-xs sm:text-sm text-slate-600 italic whitespace-pre-line leading-relaxed"></p>
                </div>
            </div>

            <div class="border-t border-gray-100 px-6 py-4 bg-gray-50 flex items-center justify-end">
                <button type="button" onclick="closeCustomerForceCompleteModal()" class="rounded-xl border border-gray-200 bg-white px-6 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm active:scale-95">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection
