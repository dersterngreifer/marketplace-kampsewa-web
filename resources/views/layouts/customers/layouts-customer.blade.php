<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('template/azia/lib/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/azia/lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/azia/lib/typicons.font/typicons.css') }}" rel="stylesheet">
    <link href="{{ asset('template/azia/lib/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet">
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/logo/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/logo/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/logo/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/logo/favicons/site.webmanifest') }}">
    
    <link rel="stylesheet" href="{{ asset('template/azia/css/azia.css') }}">
    <link rel="stylesheet" href="{{ asset('css/input/input-category.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gradient/gradient-color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/arsir/corak.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cdn/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/universeio/input-for-data-feedback.css') }}">
    <link rel="stylesheet" href="{{ asset('css/input/numbertype.css') }}">
    <link rel="stylesheet" href=" {{ asset('css/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scrollbar/scrollbar-sidebar.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>{{ $title }}</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="--container">
        @include('components.navbars.navbar2')
        @yield('customer-content')
    </div>

    <script src="{{ asset('js/customer/customer-chart-pemasukan.js') }}"></script>
    <script src="{{ asset('template/azia/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/azia/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/azia/lib/ionicons/ionicons.js') }}"></script>
    <script src="{{ asset('template/azia/lib/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('template/azia/lib/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('template/azia/lib/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('template/azia/lib/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('template/azia/js/azia.js') }}"></script>
    <script src="{{ asset('template/azia/js/chart.flot.sampledata.js') }}"></script>
    <script src="{{ asset('template/azia/js/dashboard.sampledata.js') }}"></script>
    <script src="{{ asset('template/azia/js/cookie.js') }}" type="text/javascript"></script>

    @include('sweetalert::alert')
</body>

</html>
