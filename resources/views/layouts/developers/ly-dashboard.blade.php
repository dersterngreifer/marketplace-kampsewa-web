<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Font handled globally via app.css --}}

    {{-- import cdn icon bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- import cdn icon flaticon --}}
    <link rel="stylesheet" href="{{ asset('css/cdn/flaticon.css') }}">

    {{-- import ico tab bar --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/favicons/favicon.ico') }}">

    {{-- import style css --}}
    <link rel="stylesheet" href="{{ asset('css/gradient/gradient-color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/arsir/corak.css') }}">
    <link rel="stylesheet" href="{{ asset('css/universeio/input-for-data-feedback.css') }}">
    <link rel="stylesheet" href="{{ asset('css/input/numbertype.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts/fonts.css') }}">

    {{-- import icon untuk tab --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>{{ $title }}</title>

    {{-- import vite tailwind --}}
    @vite('resources/css/app.css')
</head>

@php
    $whiteBackgroundTitles = [
        'Profile | Developer Kamp Sewa',
        'Rekap Keuangan | Developer',
        'Pengeluaran',
        'Penghasilan',
        'Penyewaan',
        'Detail Produk Sedang Disewa',
        'Detail Produk Disewakan',
        'Produk Disewakan',
        'Detail Pengguna',
    ];

    $isWhiteBackground = in_array($title ?? '', $whiteBackgroundTitles, true);
@endphp

<body class="overflow-x-hidden font-poppins {{ $isWhiteBackground ? 'bg-white' : 'bg-[#EFF2F7]' }}">

    <div class="min-h-screen w-full">

        {{-- Sidebar --}}
        @include('components.sidebars.sideabar1')

        {{-- Navbar + Content --}}
        <main
            class="min-h-screen w-full transition-all duration-300 
            lg:pl-[280px] 
            small-desktop:pl-0
            {{ $isWhiteBackground ? 'bg-white' : 'bg-[#EFF2F7]' }}">

            @include('components.navbars.navbar1')

            <section class="w-full">
                @yield('content')
            </section>

        </main>

    </div>

    {{-- import library chartjs --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- import chart js style --}}
    <script src="{{ asset('js/chart-keuntungan.js') }}?v={{ file_exists(public_path('js/chart-keuntungan.js')) ? filemtime(public_path('js/chart-keuntungan.js')) : '1' }}"></script>
    <script src="{{ asset('js/chart-kerugian.js') }}?v={{ file_exists(public_path('js/chart-kerugian.js')) ? filemtime(public_path('js/chart-kerugian.js')) : '1' }}"></script>
    <script src="{{ asset('js/char-penghasilan-pertahun.js') }}?v={{ file_exists(public_path('js/char-penghasilan-pertahun.js')) ? filemtime(public_path('js/char-penghasilan-pertahun.js')) : '1' }}"></script>
    <script src="{{ asset('js/chart-totalperbulan.js') }}?v={{ file_exists(public_path('js/chart-totalperbulan.js')) ? filemtime(public_path('js/chart-totalperbulan.js')) : '1' }}"></script>
    <script src="{{ asset('js/chart-totalperminggu.js') }}?v={{ file_exists(public_path('js/chart-totalperminggu.js')) ? filemtime(public_path('js/chart-totalperminggu.js')) : '1' }}"></script>
    <script src="{{ asset('js/chart-pengeluaran.js') }}?v={{ file_exists(public_path('js/chart-pengeluaran.js')) ? filemtime(public_path('js/chart-pengeluaran.js')) : '1' }}"></script>
    <script src="{{ asset('js/chart-pebandingan-keuntungan.js') }}?v={{ file_exists(public_path('js/chart-pebandingan-keuntungan.js')) ? filemtime(public_path('js/chart-pebandingan-keuntungan.js')) : '1' }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    @include('sweetalert::alert')
</body>

</html>