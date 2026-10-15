@extends('layouts.developers.ly-dashboard')
@section('content')
    <div class="--container w-full h-auto p-8 flex flex-col gap-6">
        {{-- Section 1: 4 Ringkasan Kartu Keuangan --}}
        <div class="--empat-card-pertama w-full grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Bagian Keuntungan --}}
            <div class="--card-keuntungan-pertahun-perbulan w-full flex flex-col gap-3">
                <div class="--title-heading text-[16px] font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    Keuntungan Bersih (Net Profit)
                </div>
                <div class="--card-design w-full grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Keuntungan Pertahun --}}
                    <div class="--card-keuntungan-pertahun bg-white shadow-box-shadow-8 border border-gray-100 flex p-5 rounded-[20px] flex-col gap-4 justify-between">
                        <div class="--header w-full flex items-start justify-between">
                            <div class="--title-desk">
                                <p class="text-[13px] font-semibold text-gray-500">Total Tahun {{ $currentYear }}</p>
                                <p class="text-[11px] text-gray-400">Seluruh keuntungan tahun berjalan.</p>
                            </div>
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-[11px] font-bold rounded-lg border border-gray-200">
                                {{ $currentYear }}
                            </span>
                        </div>
                        <div class="--footer flex flex-col gap-2">
                            <p class="text-[20px] font-extrabold text-emerald-600">
                                Rp. {{ number_format($keuntunganTahunIni, 0, ',', '.') }}
                            </p>
                            <div class="--presentase flex items-center gap-1.5 flex-wrap">
                                @if ($persenKeuntunganTahun >= 0)
                                    <span class="text-[11px] font-bold py-1 px-2 bg-[#D8F2EE] text-[#339185] rounded-[6px] flex items-center gap-1">
                                        <i class="bi bi-arrow-up"></i> {{ number_format($persenKeuntunganTahun, 1) }}%
                                    </span>
                                @else
                                    <span class="text-[11px] font-bold py-1 px-2 bg-rose-50 text-rose-600 rounded-[6px] flex items-center gap-1">
                                        <i class="bi bi-arrow-down"></i> {{ number_format(abs($persenKeuntunganTahun), 1) }}%
                                    </span>
                                @endif
                                <p class="text-[11px] text-gray-500">Dari tahun lalu (<b>{{ $currentYear - 1 }}</b>)</p>
                            </div>
                        </div>
                    </div>

                    {{-- Keuntungan Perbulan --}}
                    <div class="--card-keuntungan-perbulan bg-white shadow-box-shadow-8 border border-gray-100 flex p-5 rounded-[20px] flex-col gap-4 justify-between">
                        <div class="--header w-full flex items-start justify-between">
                            <div class="--title-desk">
                                <p class="text-[13px] font-semibold text-gray-500">Bulan {{ $currentMonthNameIndo }}</p>
                                <p class="text-[11px] text-gray-400">Keuntungan bulan berjalan.</p>
                            </div>
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-[11px] font-bold rounded-lg border border-gray-200">
                                {{ $currentMonthNameIndo }}
                            </span>
                        </div>
                        <div class="--footer flex flex-col gap-2">
                            <p class="text-[20px] font-extrabold text-emerald-600">
                                Rp. {{ number_format($keuntunganBulanIni, 0, ',', '.') }}
                            </p>
                            <div class="--presentase flex items-center gap-1.5 flex-wrap">
                                @if ($persenKeuntunganBulan >= 0)
                                    <span class="text-[11px] font-bold py-1 px-2 bg-[#D8F2EE] text-[#339185] rounded-[6px] flex items-center gap-1">
                                        <i class="bi bi-arrow-up"></i> {{ number_format($persenKeuntunganBulan, 1) }}%
                                    </span>
                                @else
                                    <span class="text-[11px] font-bold py-1 px-2 bg-rose-50 text-rose-600 rounded-[6px] flex items-center gap-1">
                                        <i class="bi bi-arrow-down"></i> {{ number_format(abs($persenKeuntunganBulan), 1) }}%
                                    </span>
                                @endif
                                <p class="text-[11px] text-gray-500">Dari bulan lalu (<b>{{ $lastMonthNameIndo }}</b>)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Kerugian / Pengeluaran --}}
            <div class="--card-kerugian-perbulan-perbulan w-full flex flex-col gap-3">
                <div class="--title-heading text-[16px] font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    Kerugian / Pengeluaran Perusahaan
                </div>
                <div class="--card-design w-full grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Kerugian Pertahun --}}
                    <div class="--card-kerugian-pertahun bg-white shadow-box-shadow-8 border border-gray-100 flex p-5 rounded-[20px] flex-col gap-4 justify-between">
                        <div class="--header w-full flex items-start justify-between">
                            <div class="--title-desk">
                                <p class="text-[13px] font-semibold text-gray-500">Total Tahun {{ $currentYear }}</p>
                                <p class="text-[11px] text-gray-400">Seluruh pengeluaran tahun berjalan.</p>
                            </div>
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-[11px] font-bold rounded-lg border border-gray-200">
                                {{ $currentYear }}
                            </span>
                        </div>
                        <div class="--footer flex flex-col gap-2">
                            <p class="text-[20px] font-extrabold text-rose-600">
                                Rp. {{ number_format($pengeluaranTahunIni, 0, ',', '.') }}
                            </p>
                            <div class="--presentase flex items-center gap-1.5 flex-wrap">
                                @if ($persenPengeluaranTahun >= 0)
                                    <span class="text-[11px] font-bold py-1 px-2 bg-rose-50 text-rose-600 rounded-[6px] flex items-center gap-1">
                                        <i class="bi bi-arrow-up"></i> {{ number_format($persenPengeluaranTahun, 1) }}%
                                    </span>
                                @else
                                    <span class="text-[11px] font-bold py-1 px-2 bg-[#D8F2EE] text-[#339185] rounded-[6px] flex items-center gap-1">
                                        <i class="bi bi-arrow-down"></i> {{ number_format(abs($persenPengeluaranTahun), 1) }}%
                                    </span>
                                @endif
                                <p class="text-[11px] text-gray-500">Dari tahun lalu (<b>{{ $currentYear - 1 }}</b>)</p>
                            </div>
                        </div>
                    </div>

                    {{-- Kerugian Perbulan --}}
                    <div class="--card-kerugian-perbulan bg-white shadow-box-shadow-8 border border-gray-100 flex p-5 rounded-[20px] flex-col gap-4 justify-between">
                        <div class="--header w-full flex items-start justify-between">
                            <div class="--title-desk">
                                <p class="text-[13px] font-semibold text-gray-500">Bulan {{ $currentMonthNameIndo }}</p>
                                <p class="text-[11px] text-gray-400">Pengeluaran bulan berjalan.</p>
                            </div>
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-[11px] font-bold rounded-lg border border-gray-200">
                                {{ $currentMonthNameIndo }}
                            </span>
                        </div>
                        <div class="--footer flex flex-col gap-2">
                            <p class="text-[20px] font-extrabold text-rose-600">
                                Rp. {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}
                            </p>
                            <div class="--presentase flex items-center gap-1.5 flex-wrap">
                                @if ($persenPengeluaranBulan >= 0)
                                    <span class="text-[11px] font-bold py-1 px-2 bg-rose-50 text-rose-600 rounded-[6px] flex items-center gap-1">
                                        <i class="bi bi-arrow-up"></i> {{ number_format($persenPengeluaranBulan, 1) }}%
                                    </span>
                                @else
                                    <span class="text-[11px] font-bold py-1 px-2 bg-[#D8F2EE] text-[#339185] rounded-[6px] flex items-center gap-1">
                                        <i class="bi bi-arrow-down"></i> {{ number_format(abs($persenPengeluaranBulan), 1) }}%
                                    </span>
                                @endif
                                <p class="text-[11px] text-gray-500">Dari bulan lalu (<b>{{ $lastMonthNameIndo }}</b>)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Grafik Perbandingan Keuntungan Pertahun --}}
        <div class="--card-statistik w-full">
            <div class="--card-statistik-perbandingan-keuntungan-pertahun flex flex-col gap-4 shadow-box-shadow-8 p-6 rounded-[20px] bg-white border border-gray-100">
                <div class="--header flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-4">
                    <div>
                        <p class="text-[18px] font-bold text-gray-900">Perbandingan Keuntungan Pertahun (2023 - 2026)</p>
                        <p class="text-xs text-gray-400">Tren laba bersih per bulan dalam satuan Juta Rupiah (Rp Jt)</p>
                    </div>
                    <div class="--title-total flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#287f71]"></div>
                            <div>
                                <p class="text-[11px] font-medium text-gray-500">Tahun 2026</p>
                                <p class="text-[14px] font-bold text-gray-800">Rp. {{ number_format($totalKeuntunganPerTahun[2026] ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#EB862B]"></div>
                            <div>
                                <p class="text-[11px] font-medium text-gray-500">Tahun 2025</p>
                                <p class="text-[14px] font-bold text-gray-800">Rp. {{ number_format($totalKeuntunganPerTahun[2025] ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#B381F4]"></div>
                            <div>
                                <p class="text-[11px] font-medium text-gray-500">Tahun 2024</p>
                                <p class="text-[14px] font-bold text-gray-800">Rp. {{ number_format($totalKeuntunganPerTahun[2024] ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#CFF500] border border-gray-300"></div>
                            <div>
                                <p class="text-[11px] font-medium text-gray-500">Tahun 2023</p>
                                <p class="text-[14px] font-bold text-gray-800">Rp. {{ number_format($totalKeuntunganPerTahun[2023] ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="--body w-full">
                    <canvas id="pebandingan-keuntungan-pertahun"></canvas>
                </div>
            </div>
        </div>

        {{-- Section 3: Data Table Rekap Keuangan Harian --}}
        <div class="--component-kedua mt-2">
            <div class="--title text-[18px] font-bold text-gray-800 mb-3">Data Rekap Keuangan Harian</div>
            
            {{-- Toolbar: Counter & Search --}}
            <div class="flex flex-col sm:flex-row w-full justify-between items-start sm:items-center gap-4 mb-4">
                <div class="_total flex items-center gap-2">
                    <span class="px-3 py-1.5 bg-[#EFF2F7] text-gray-700 text-[13px] font-bold rounded-lg border border-gray-200">
                        <i class="bi bi-calendar-event text-[#5038ED] mr-1"></i> {{ number_format($rekapData->total(), 0, ',', '.') }} Rekap Tanggal
                    </span>
                </div>

                {{-- Search Filter --}}
                <div class="_search-filter flex items-center gap-3">
                    <form method="GET" action="{{ route('rekap-keuangan.index') }}" class="relative flex items-center">
                        <input class="px-4 py-2 pl-10 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-[#7CA9CF] w-[260px] bg-white shadow-sm"
                            type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari tanggal (YYYY-MM-DD)...">
                        <i class="bi bi-search absolute left-3.5 text-gray-400 text-sm"></i>
                        @if (!empty($search))
                            <a href="{{ route('rekap-keuangan.index') }}" class="absolute right-3 text-gray-400 hover:text-gray-600 text-xs">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="--table w-full h-auto mt-2">
                <div class="relative w-full overflow-hidden shadow-box-shadow-11 rounded-[20px] bg-white border border-gray-100">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5">#</th>
                                    <th scope="col" class="px-6 py-3.5">Tanggal</th>
                                    <th scope="col" class="px-6 py-3.5">Total Pemasukan</th>
                                    <th scope="col" class="px-6 py-3.5">Total Pengeluaran</th>
                                    <th scope="col" class="px-6 py-3.5">Keuntungan Bersih</th>
                                    <th scope="col" class="px-6 py-3.5 text-center">Status Margin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($rekapData as $index => $item)
                                    @php
                                        $isSurplus = $item->keuntungan_bersih >= 0;
                                    @endphp
                                    <tr class="bg-white hover:bg-gray-50/80 transition">
                                        <td class="px-4 py-4 text-xs font-medium text-gray-400">
                                            {{ $rekapData->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-800">
                                            <span class="py-1 px-3 bg-gray-100 rounded-full border border-gray-200">
                                                <i class="bi bi-calendar3 mr-1 text-gray-500"></i>
                                                {{ Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-emerald-600">
                                            Rp. {{ number_format($item->total_pemasukan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-rose-500">
                                            Rp. {{ number_format($item->total_pengeluaran, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                            Rp. {{ number_format($item->keuntungan_bersih, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($isSurplus)
                                                <span class="py-1 px-3 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200 inline-flex items-center gap-1">
                                                    <i class="bi bi-check-circle-fill"></i> Surplus
                                                </span>
                                            @else
                                                <span class="py-1 px-3 bg-rose-50 text-rose-700 text-xs font-bold rounded-full border border-rose-200 inline-flex items-center gap-1">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> Defisit
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                            <i class="bi bi-inbox text-[36px] block mb-2 text-gray-300"></i>
                                            <p class="font-medium text-gray-500">Tidak ada data rekap keuangan ditemukan.</p>
                                            @if ($search)
                                                <a href="{{ route('rekap-keuangan.index') }}" class="text-[#5038ED] text-xs underline mt-1 inline-block">Reset filter pencarian</a>
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
                    <p class="text-xs text-gray-500">
                        Menampilkan {{ $rekapData->firstItem() ?? 0 }} - {{ $rekapData->lastItem() ?? 0 }} dari {{ $rekapData->total() }} data
                    </p>
                    <div>
                        {{ $rekapData->onEachSide(1)->links('components.paginate.custom-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pass multi-year dataset to global window object --}}
    <script>
        window.rekapKeuntunganChartData = @json($chartDatasets);
    </script>

    @push('scripts')
        <script>
            if (typeof window.initChartPerbandinganKeuntungan === 'function') {
                window.initChartPerbandinganKeuntungan();
            }
        </script>
    @endpush
@endsection
