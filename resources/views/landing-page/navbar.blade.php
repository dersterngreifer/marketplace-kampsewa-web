<nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5">
    <a href="{{ route('landing-page.halaman-beranda') }}" class="navbar-brand p-0">
        <h1 class="m-0">
            <img src="{{asset('template/envato/img/logog-kampsewa.png')}}" alt="KampSewa" sizes="">KampSewa</h1>
    </a>
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars text-primary fs-3"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0 align-items-lg-center">
            <a href="{{ route('landing-page.halaman-beranda') }}" class="nav-item nav-link {{ Request::routeIs('landing-page.halaman-beranda') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('landing-page.halaman-tentangkami') }}" class="nav-item nav-link {{ Request::routeIs('landing-page.halaman-tentangkami') ? 'active' : '' }}">
                <i class="fas fa-info-circle"></i>
                <span>Tentang Kami</span>
            </a>
            <a href="{{ route('landing-page.halaman-destinasi') }}" class="nav-item nav-link {{ Request::routeIs('landing-page.halaman-destinasi') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt"></i>
                <span>Destinasi</span>
            </a>
            <a href="{{ route('landing-page.halaman-testimoni') }}" class="nav-item nav-link {{ Request::routeIs('landing-page.halaman-testimoni') ? 'active' : '' }}">
                <i class="fas fa-star"></i>
                <span>Testimoni</span>
            </a>
            <a href="{{ route('landing-page.halaman-sewabarang') }}" class="nav-item nav-link {{ Request::routeIs('landing-page.halaman-sewabarang') ? 'active' : '' }}">
                <i class="fas fa-campground"></i>
                <span>Perlengkapan Camping</span>
            </a>
        </div>
        <div class="d-flex align-items-center ms-lg-4 mt-3 mt-lg-0">
            <a href="#download-app" class="btn-nav-download">
                <i class="fas fa-cloud-download-alt"></i>
                <span>Download App</span>
            </a>
        </div>
    </div>
</nav>
