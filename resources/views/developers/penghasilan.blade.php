@include('components.modals.tambah-pemasukan-dev')
@extends('layouts.developers.ly-dashboard')
@section('content')
    <div class="_container p-8 flex flex-col gap-6">
        <div class="--component-filter w-full">
            <ul class="flex items-center gap-2">
                <li class="text-[12px] font-medium text-gray-500">Filter:</li>
                <li>
                    <a class="{{ $title == 'Penghasilan' ? 'bg-[#080E2E] text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} text-[14px] font-medium px-5 py-2 rounded-full transition"
                        href="{{ route('penghasilan.index') }}">Penghasilan</a>
                </li>
                <li>
                    <a class="{{ $title == 'Pengeluaran' ? 'bg-[#080E2E] text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} text-[14px] font-medium px-5 py-2 rounded-full transition"
                        href="{{ route('pengeluaran.index') }}">Pengeluaran</a>
                </li>
            </ul>
        </div>
        <hr class="border-gray-200">
        <div class="--component-pertama w-full h-auto m-auto grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="--card-penghasilan-tahun-ini w-full h-auto flex flex-col">
                <div class="--title-heading mb-3 flex items-center justify-between">
                    <p class="font-bold text-[18px] text-gray-800">Total Pertahun</p>
                </div>
                <div class="--card w-full bg-white shadow-box-shadow-8 rounded-[20px] p-5 flex flex-col justify-between gap-4 border border-gray-100">
                    <div class="--header flex justify-between items-start">
                        <div class="--sub-1">
                            <p class="text-gray-400 font-medium text-[14px]">Tahun {{ $currentYear }}:</p>
                            <p class="text-[26px] font-bold text-gray-900 my-1">
                                Rp. {{ number_format($penghasilan_tahun_ini, 0, ',', '.') }}
                            </p>
                            <div class="--penghasilan-tahun-lalu mt-2">
                                <p class="text-[12px] text-gray-500 font-normal">Total penghasilan tahun lalu ({{ $currentYear - 1 }}):</p>
                                <p class="font-semibold text-[15px] text-gray-700">
                                    Rp. {{ number_format($penghasilan_tahun_lalu, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="--sub-2">
                            @if ($persentase_perubahan >= 0)
                                <span class="py-2 px-4 bg-[#F0FDF4] text-[#4AD07B] text-[13px] font-bold rounded-full flex items-center gap-1">
                                    Naik {{ number_format($persentase_perubahan, 1) }}% <i class="bi bi-arrow-up-right"></i>
                                </span>
                            @else
                                <span class="py-2 px-4 bg-[#FEF2F2] text-[#EF4444] text-[13px] font-bold rounded-full flex items-center gap-1">
                                    Turun {{ number_format(abs($persentase_perubahan), 1) }}% <i class="bi bi-arrow-down-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="--body w-full">
                        <canvas id="penghasilan"></canvas>
                    </div>
                    <div class="--footer text-[12px] text-gray-400 text-right">
                        Statistik transaksi penghasilan 12 bulan {{ $currentYear }}
                    </div>
                </div>
            </div>
            <div class="--card-penghasilan-hari-minggu-bulan-saat-ini flex flex-col gap-3">
                <div class="--title-heading flex items-center justify-between mb-1">
                    <p class="font-bold text-[18px] text-gray-800">Total Periode Lainnya</p>
                </div>
                <div class="--card w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Total Perbulan --}}
                    <div class="--card-penghasilan-hari-ini w-full p-5 bg-[#080E2E] rounded-[20px] text-white flex flex-col justify-between shadow-md">
                        <div class="--header">
                            <p class="text-gray-300 font-medium text-[15px] mb-1">Total Perbulan</p>
                            <div class="--total">
                                <p class="text-[12px] font-normal text-gray-400">Bulan <b>{{ $currentMonthNameIndo }}</b> {{ $currentYear }}</p>
                                <p class="text-white text-[20px] font-bold my-1">
                                    Rp. {{ number_format($monthCurrentTotal, 0, ',', '.') }}
                                </p>
                                @if ($persentase_perubahan_bulan >= 0)
                                    <p class="text-emerald-400 text-[12px] font-medium flex items-center gap-1">
                                        <i class="bi bi-graph-up-arrow"></i> Naik <b>{{ number_format($persentase_perubahan_bulan, 1) }}%</b> dari bulan lalu
                                    </p>
                                @else
                                    <p class="text-rose-400 text-[12px] font-medium flex items-center gap-1">
                                        <i class="bi bi-graph-down-arrow"></i> Turun <b>{{ number_format(abs($persentase_perubahan_bulan), 1) }}%</b> dari bulan lalu
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="--body my-3 h-[90px]">
                            <canvas id="penghasilan-perbulan"></canvas>
                        </div>
                        <div class="--footer">
                            <p class="text-gray-400 text-[12px] font-medium mb-1">Total Bulan Lalu</p>
                            <div class="--card-total-bulan-lalu bg-white/10 backdrop-blur rounded-[12px] flex items-center justify-between p-3 border border-white/10">
                                <div class="-total">
                                    <p class="font-medium text-gray-300 text-[11px]">Bulan {{ $lastMonthNameIndo }}</p>
                                    <p class="text-white font-bold text-[14px]">
                                        Rp. {{ number_format($monthPreviousTotal, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="--icon bg-white/20 rounded-lg p-2 text-white">
                                    <i class="text-[16px] bi bi-calendar2-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Perminggu --}}
                    <div class="--card-penghasilan-minggu-ini w-full p-5 bg-[#C7B4EE] rounded-[20px] text-[#080E2E] flex flex-col justify-between shadow-md">
                        <div class="--header">
                            <p class="text-[#2D1B69] font-medium text-[15px] mb-1">Total Perminggu</p>
                            <div class="--total">
                                <p class="text-[12px] font-normal text-[#4A3B75]">Minggu Bulan <b>{{ $currentMonthNameIndo }}</b> {{ $currentYear }}</p>
                                <p class="text-[#080E2E] text-[20px] font-bold my-1">
                                    Rp. {{ number_format($weekCurrentTotal, 0, ',', '.') }}
                                </p>
                                @if ($persentase_perubahan_minggu >= 0)
                                    <p class="text-[#15803D] text-[12px] font-medium flex items-center gap-1">
                                        <i class="bi bi-graph-up-arrow"></i> Naik <b>{{ number_format($persentase_perubahan_minggu, 1) }}%</b> dari minggu lalu
                                    </p>
                                @else
                                    <p class="text-[#B91C1C] text-[12px] font-medium flex items-center gap-1">
                                        <i class="bi bi-graph-down-arrow"></i> Turun <b>{{ number_format(abs($persentase_perubahan_minggu), 1) }}%</b> dari minggu lalu
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="--body my-3 h-[90px]">
                            <canvas id="penghasilan-perminggu"></canvas>
                        </div>
                        <div class="--footer">
                            <p class="text-[#4A3B75] text-[12px] font-medium mb-1">Total Minggu Lalu</p>
                            <div class="--card-total-bulan-lalu bg-white/60 backdrop-blur rounded-[12px] flex items-center justify-between p-3 border border-purple-200">
                                <div class="-total">
                                    <p class="font-medium text-gray-600 text-[11px]">Minggu ke-{{ $previousWeekNumber }}</p>
                                    <p class="text-[#080E2E] font-bold text-[14px]">
                                        Rp. {{ number_format($weekPreviousTotal, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="--icon bg-[#080E2E] rounded-lg p-2 text-white">
                                    <i class="text-[16px] bi bi-calendar2-range"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Penghasilan Hari Ini --}}
                <div class="--card-total-penghasilan-hari-ini flex items-center justify-between p-4 rounded-[18px] bg-gradient-to-r from-[#86C6CF] to-[#68B2BD] text-white shadow-md">
                    <div class="-image-title flex gap-3 items-center">
                        <div class="--image w-[45px] h-[45px] rounded-[12px] flex items-center justify-center bg-white/20">
                            <i class="text-white text-[20px] bi bi-cash-stack"></i>
                        </div>
                        <div class="--title">
                            <p class="font-bold text-white text-[15px]">Penghasilan Hari ini - {{ $todayNameIndo }}</p>
                            <p class="text-white/80 text-[12px]">Mendapatkan <b>Rp. {{ number_format($dayPreviousTotal, 0, ',', '.') }}</b> kemarin.</p>
                        </div>
                    </div>
                    <div class="-total text-[20px] font-extrabold text-white">
                        Rp. {{ number_format($dayCurrentTotal, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="--component-kedua mt-2">
            <div class="--title text-[18px] font-bold text-gray-800 mb-3">Data Pemasukan Perusahaan</div>
            
            {{-- Search & Action Bar --}}
            <div class="flex flex-col md:flex-row w-full justify-between items-start md:items-center gap-4 mb-4">
                {{-- Total users counter --}}
                <div class="_total flex items-center gap-2">
                    <span class="px-3 py-1.5 bg-[#EFF2F7] text-gray-700 text-[13px] font-bold rounded-lg border border-gray-200">
                        <i class="bi bi-people-fill text-[#5038ED] mr-1"></i> {{ number_format($totalCustomer, 0, ',', '.') }} Customer Terdaftar
                    </span>
                    <span class="px-3 py-1.5 bg-[#F0FDF4] text-emerald-700 text-[13px] font-bold rounded-lg border border-emerald-200">
                        <i class="bi bi-receipt text-emerald-600 mr-1"></i> {{ number_format($list_pemasukan->total(), 0, ',', '.') }} Transaksi
                    </span>
                </div>

                {{-- Search & Buttons --}}
                <div class="_search-filter flex flex-wrap items-center gap-3">
                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('penghasilan.index') }}" class="relative flex items-center">
                        <input class="px-4 py-2 pl-10 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-[#7CA9CF] w-[260px] bg-white shadow-sm"
                            type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari sumber/deskripsi...">
                        <i class="bi bi-search absolute left-3.5 text-gray-400 text-sm"></i>
                        @if (!empty($search))
                            <a href="{{ route('penghasilan.index') }}" class="absolute right-3 text-gray-400 hover:text-gray-600 text-xs">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                    </form>

                    {{-- Tombol Tambah Data --}}
                    <div class="_btn-tambah-data">
                        <button id="tambah-pemasukan-customer" class="px-5 py-2 gradient-1 cursor-pointer text-white font-medium text-sm rounded-full shadow-md hover:opacity-95 transition flex items-center gap-2">
                            <i class="bi bi-plus-lg"></i>
                            <span>Tambah Pemasukan</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Table Pemasukan --}}
            <div class="--table w-full h-auto mt-2">
                <div class="relative w-full overflow-hidden shadow-box-shadow-11 rounded-[20px] bg-white border border-gray-100">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5">#</th>
                                    <th scope="col" class="px-6 py-3.5">Sumber</th>
                                    <th scope="col" class="px-6 py-3.5">Tanggal</th>
                                    <th scope="col" class="px-6 py-3.5">Deskripsi</th>
                                    <th scope="col" class="px-6 py-3.5">Nominal</th>
                                    <th scope="col" class="px-6 py-3.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($list_pemasukan as $index => $item)
                                    @php
                                        $sumberBadge = 'bg-blue-50 text-blue-700 border-blue-200';
                                        if (stripos($item->sumber, 'iklan') !== false) {
                                            $sumberBadge = 'bg-purple-50 text-purple-700 border-purple-200';
                                        } elseif (stripos($item->sumber, 'pajak') !== false) {
                                            $sumberBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        } elseif (stripos($item->sumber, 'mitra') !== false) {
                                            $sumberBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                                        }
                                    @endphp
                                    <tr class="bg-white hover:bg-gray-50/80 transition">
                                        <td class="px-4 py-4 text-xs font-medium text-gray-400">
                                            {{ $list_pemasukan->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $sumberBadge }}">
                                                {{ $item->sumber }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                            <span class="py-1 px-3 bg-[#F0FDF4] text-[#22C55E] font-medium rounded-full border border-emerald-100">
                                                {{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 max-w-[320px] text-gray-700 text-xs truncate" title="{{ $item->deskripsi }}">
                                            {{ $item->deskripsi }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 text-sm">
                                            Rp. {{ number_format($item->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <form action="{{ route('penghasilan.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan pemasukan ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                    <i class="bi bi-trash-fill text-[15px]"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                            <i class="bi bi-inbox text-[36px] block mb-2 text-gray-300"></i>
                                            <p class="font-medium text-gray-500">Tidak ada data pemasukan ditemukan.</p>
                                            @if ($search)
                                                <a href="{{ route('penghasilan.index') }}" class="text-[#5038ED] text-xs underline mt-1 inline-block">Reset filter pencarian</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-4 px-2">
                    <p class="text-xs text-gray-500 font-medium">
                        Menampilkan {{ $list_pemasukan->firstItem() ?? 0 }} - {{ $list_pemasukan->lastItem() ?? 0 }} dari {{ $list_pemasukan->total() }} data
                    </p>
                    <div>
                        {{ $list_pemasukan->onEachSide(1)->links('components.paginate.custom-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pass dynamic chart datasets to global window object --}}
    <script>
        window.penghasilanPertahunData = @json($monthlyIncomeData);
        window.penghasilanPerbulanLabels = @json($recentMonthsLabels);
        window.penghasilanPerbulanData = @json($recentMonthsData);
        window.penghasilanPermingguData = @json($weeklyData);

        const modal = document.getElementById('modal-tambah-pemasukan-customer');
        const idButton = document.getElementById('tambah-pemasukan-customer');
        const submitPemasukan = document.getElementById('tambah-pemasukan');
        const formTambahPemasukan = document.getElementById('form-tambah-pemasukan');
        const cancelButton = document.getElementById('cancel-tambah-pemasukan-web-customer');
        const closeButton = document.getElementById('close-modal-pemasukan');

        function modalHandlerPemasukanCustomer(val) {
            if (modal) {
                if (val) {
                    modal.classList.remove('hidden');
                    modal.style.display = "flex";
                } else {
                    modal.classList.add('hidden');
                    modal.style.display = "none";
                }
            }
        }

        if (idButton) {
            idButton.addEventListener('click', () => modalHandlerPemasukanCustomer(true));
        }
        if (cancelButton) {
            cancelButton.addEventListener('click', () => modalHandlerPemasukanCustomer(false));
        }
        if (closeButton) {
            closeButton.addEventListener('click', () => modalHandlerPemasukanCustomer(false));
        }

        if (submitPemasukan && formTambahPemasukan) {
            submitPemasukan.addEventListener('click', function(event) {
                event.preventDefault();

                let sumber = document.getElementById('sumber_pemasukan_customer').value.trim();
                let deskripsi = document.getElementById('deskripsi_pemasukan_customer').value.trim();
                let nominal = document.getElementById('nominal_pemasukan_customer').value.trim();

                if (!sumber || !deskripsi || !nominal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Mohon isi semua field input!',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                }

                formTambahPemasukan.submit();
            });
        }
    </script>

    @push('scripts')
        <script>
            if (typeof window.initPenghasilanPertahunChart === 'function') {
                window.initPenghasilanPertahunChart();
            }
            if (typeof window.initChartTotalPerbulan === 'function') {
                window.initChartTotalPerbulan();
            }
            if (typeof window.initChartTotalPerminggu === 'function') {
                window.initChartTotalPerminggu();
            }
        </script>
    @endpush
@endsection
