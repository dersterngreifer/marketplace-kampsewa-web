@include('landing-page.header')
<body>

        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Topbar Start -->
        
        <!-- Topbar End -->

        <!-- Navbar & Hero Start -->
        <div class="container-fluid position-relative p-0">
           
              @include('landing-page.navbar')
            </nav>

            <!-- Carousel Start -->
            <div class="carousel-header">
                <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active"></li>
                        <li data-bs-target="#carouselId" data-bs-slide-to="1"></li>
                        <li data-bs-target="#carouselId" data-bs-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <img src="{{asset('template/envato/img/home-4.jpg')}}" class="img-fluid" alt="Image">
                            <div class="carousel-caption">
                                <div class="p-3" style="max-width: 800px; text-align: center;">
                                <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 4px;">Rekomendasi</h4>
                                <h1 class="display-2 text-capitalize text-white mb-4">Destinasi Wisata Terbaik untuk Liburanmu</h1>
                                <p class="mb-4">Yuk, cari petualangan seru di destinasi pilihan kami! Dari pantai keren sampai gunung yang bikin takjub, semua ada di sini. Setiap tempat dipilih dengan hati-hati biar liburanmu makin berkesan.</p>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Carousel End -->
        </div>
       

         <!-- Packages Start -->
         <div class="container-fluid packages py-5">
            <div class="container py-5">
                <div class="mx-auto text-center mb-5" style="max-width: 900px;">
                    <h5 class="section-title px-3">Destinasi Pilihan</h5>
                    <h1 class="mb-0">Rekomendasi wisata terbaik untuk kamu.</h1>
                </div>
                <div class="packages-carousel owl-carousel d-flex flex-coloumn gap-4">
                    <div class="packages-item">
                        <div class="packages-img">
                            <img src="{{asset('template/envato/img/tempat-galaxy.jpg')}}" class="img-fluid w-100 rounded-top" alt="Image">
                            <div class="packages-info d-flex border border-start-0 border-end-0 position-absolute" style="width: 100%; bottom: 0; left: 0; z-index: 5;">
                                <small class="flex-fill text-center border-end py-2"><i class="fa fa-map-marker-alt me-2"></i>Desa Tempurejo</small>
                            </div>
    
                        </div>
                        <div class="packages-content bg-light">
                            <div class="p-4 pb-0">
                                <h5 class="mb-0">Taman Galaxy</h5>
                                <small class="text-uppercase">taman dan kebun binatang mini</small>
                                <div class="mb-3">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                                <p class="mb-4">Jember memiliki banyak sekali wisata yang menarik dan lengkap, salah satunya yaitu taman Galaxy. Taman Galaxy merupakan sebuah objek wisata yang didalamnya menyuguhkan keindahan taman seperti bukit Teletubbies dan juga kebun binatang.
                                                Diberi nama Bukit Teletubbies karena bentuk dan rumputnya seperti tempat dalam film cartoon Teletubbies.</p>
                            </div>
                            <div class="row bg-primary rounded-bottom mx-0">
                            </div>
                        </div>
                    </div>
                    <div class="packages-item">
                        <div class="packages-img">
                            <img src="{{asset('template/envato/img/tempat-glamping.jpg')}}" class="img-fluid w-100 rounded-top" alt="Image">
                            <div class="packages-info d-flex border border-start-0 border-end-0 position-absolute" style="width: 100%; bottom: 0; left: 0; z-index: 5;">
                                <small class="flex-fill text-center border-end py-2"><i class="fa fa-map-marker-alt me-2"></i>Kecamatan Dlingo, Bantul</small>
                            </div>
    
                        </div>
                        <div class="packages-content bg-light">
                            <div class="p-4 pb-0">
                                <h5 class="mb-0">Glamping Songgolangit</h5>
                                <small class="text-uppercase">Hutan Pinus</small>
                                <div class="mb-3">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                                <p class="mb-4">Fasilitas yang diberikan juga sangat lengkap, mulai dari kasur, kamar mandi pribadi, hingga sofa yang empuk. 
                                                Tidak hanya berkemah, wisatawan juga bisa mengunjungi beberapa spot foto instagrammable di destinasi wisata Seribu Batu Songgo Langit.
                                                Seperti jembatan panjang dari kayu, rumah ala film Hobbit, puncak Songgo Langit yang indah, hingga bermain flying fox dan jalan-jalan naik jeep.</p>
                            </div>
                            <div class="row bg-primary rounded-bottom mx-0">
                            </div>
                        </div>
                    </div>
                    <div class="packages-item">
                        <div class="packages-img">
                            <img src="{{asset('template/envato/img/tempat-Bali.jpg')}}" class="img-fluid w-100 rounded-top" alt="Image">
                            <div class="packages-info d-flex border border-start-0 border-end-0 position-absolute" style="width: 100%; bottom: 0; left: 0; z-index: 5;">
                                <small class="flex-fill text-center border-end py-2"><i class="fa fa-map-marker-alt me-2"></i>Kabupaten Tabanan</small>
                            </div>
    
                        </div>
                        <div class="packages-content bg-light">
                            <div class="p-4 pb-0">
                                <h5 class="mb-0">Pantai Tanah Lot</h5>
                                <small class="text-uppercase">Salah satu pura terfavorit dan sangat disucikan di Bali</small>
                                <div class="mb-3">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                                <p class="mb-4">salah satu Pura (Tempat Ibadah Umat Hindu) yang sangat disucikan di Bali, Indonesia. Di sini ada dua Pura yang terletak di atas batu besar. Satu terletak di atas bongkahan batu dan satunya terletak di atas tebing mirip dengan Pura Uluwatu. Pura Tanah Lot ini merupakan bagian dari Pura Dang Kahyangan. Pura Tanah Lot merupakan Pura laut tempat pemujaan dewa-dewa penjaga laut. Tanah Lot terkenal sebagai tempat yang indah untuk melihat matahari terbenam.</p>
                            </div>
                            <div class="row bg-primary rounded-bottom mx-0">
                            </div>
                        </div>
                    </div>
                    <div class="packages-item">
                        <div class="packages-img">
                            <img src="{{asset('template/envato/img/tempat-green-bowl.webp')}}" class="img-fluid w-100 rounded-top" alt="Image">
                            <div class="packages-info d-flex border border-start-0 border-end-0 position-absolute" style="width: 100%; bottom: 0; left: 0; z-index: 5;">
                                <small class="flex-fill text-center border-end py-2"><i class="fa fa-map-marker-alt me-2"></i>Desa Ungasan</small>
                            </div>
    
                        </div>
                        <div class="packages-content bg-light">
                            <div class="p-4 pb-0">
                                <h5 class="mb-0">Pantai Green Bowl</h5>
                                <small class="text-uppercase">salah satu pantai di Bali yang jarang disentuh wisatawan</small>
                                <div class="mb-3">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                                <p class="mb-4">Lokasinya memang agak susah ditemukan, dan untuk bisa menuju ke pantai ini, kamu harus menuruni ratusan anak tangga yang pastinya akan bikin kaki pegal-pegal saat harus pulang. Tapi begitu kamu sampai di beberapa anak tangga terakhir, kamu akan melupakan rasa lelahmu karena pemandangan yang sangat keren.</p>
                            </div>
                            <div class="row bg-primary rounded-bottom mx-0">
                            </div>
                        </div>
                    </div>
                    <div class="packages-item">
                        <div class="packages-img">
                            <img src="{{asset('template/envato/img/tempat-kelingking.webp')}}" class="img-fluid w-100 rounded-top" alt="Image">
                            <div class="packages-info d-flex border border-start-0 border-end-0 position-absolute" style="width: 100%; bottom: 0; left: 0; z-index: 5;">
                                <small class="flex-fill text-center border-end py-2"><i class="fa fa-map-marker-alt me-2"></i>Kabupaten Klungkung</small>
                            </div>
    
                        </div>
                        <div class="packages-content bg-light">
                            <div class="p-4 pb-0">
                                <h5 class="mb-0">Pantai Kelingking</h5>
                                <small class="text-uppercase">Pantai dengan Tebing yang indah</small>
                                <div class="mb-3">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                                <p class="mb-4">Tapi tau gak sih, kamu sebenarnya bisa turun mengikuti tangganya yang curam sampai ke pantainya di dasar? Memang masih jarang banget turis yang mau susah payah melakukannya karena memang sangat melelahkan. Tapi ketika kamu sampai di dasar, pantai di Kelingking Beach ini benar-benar akan membayar semua usaha kamu!</p>
                            </div>
                            <div class="row bg-primary rounded-bottom mx-0">
                            </div>
                        </div>
                    </div>
                    <div class="packages-item">
                        <div class="packages-img">
                            <img src="{{asset('template/envato/img/tempat-purwo.webp')}}" class="img-fluid w-100 rounded-top" alt="Image">
                            <div class="packages-info d-flex border border-start-0 border-end-0 position-absolute" style="width: 100%; bottom: 0; left: 0; z-index: 5;">
                                <small class="flex-fill text-center border-end py-2"><i class="fa fa-map-marker-alt me-2"></i>Banyuwangi</small>
                            </div>
    
                        </div>
                        <div class="packages-content bg-light">
                            <div class="p-4 pb-0">
                                <h5 class="mb-0">Taman Nasional Alas Purwo</h5>
                                <small class="text-uppercase">taman nasional, semula berstatus Suaka Margasatwa Banyuwangi Selatan</small>
                                <div class="mb-3">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                                <p class="mb-4">Taman Nasional Alas Purwo merupakan kawasan konservasi yang terletak di ujung timur Pulau Jawa. Secara administratif, Alas Purwo masuk wilayah Kecamatan Tegaldlimo dan Kecamatan Purwoharjo, Kabupaten Banyuwangi, Jawa Timu</p>
                            </div>
                            <div class="row bg-primary rounded-bottom mx-0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Packages End -->
         <!-- Destination Start -->
        <div class="container-fluid destination py-5">
            <div class="container py-5">
                <div class="mx-auto text-center mb-5" style="max-width: 900px;">
                    <h5 class="section-title px-3">Jelajahi wisatamu sekarang</h5>
                    <h1 class="mb-0">Lihat pilihan wisata didekat mu</h1>
                </div>
                <div class="tab-class text-center">
                    <ul class="nav nav-pills d-inline-flex justify-content-center mb-5">
                        <li class="nav-item">
                            <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                <span class="text-dark" style="width: 150px;">Jawa Timur</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex py-2 mx-3 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#tab-2">
                                <span class="text-dark" style="width: 150px;">Jawa Tengah</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#tab-3">
                                <span class="text-dark" style="width: 150px;">Jawa Barat</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#tab-4">
                                <span class="text-dark" style="width: 150px;">Bali</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#tab-5">
                                <span class="text-dark" style="width: 150px;">Sumatra</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#tab-6">
                                <span class="text-dark" style="width: 150px;">Sulawesi</span>
                            </a>
                        </li>
                    </ul>
                    <!-- Jawa Timur-->
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <div class="col-xl-8">
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="destination-img">
                                            <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-papuma.png')}}" alt="Destinasi Wisata">
                                                <div class="destination-overlay p-4">
                                                    <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                                    <h4 class="text-white mb-2 mt-3">Pantai Papuma</h4>
                                                    <a href="#" class="btn-hover text-white">Kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                                </div>
                                                <div class="search-icon">
                                                    <a href="img/destination-1.jpg" data-lightbox="destination-1"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="destination-img">
                                            <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-teluk-love.jpg')}}" alt="Destinasi Wisata">
                                                <div class="destination-overlay p-4">
                                                    <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                                    <h4 class="text-white mb-2 mt-3">Pantai Payangan</h4>
                                                    <a href="#" class="btn-hover text-white">Kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                                </div>
                                                <div class="search-icon">
                                                    <a href="img/destination-2.jpg" data-lightbox="destination-2"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="destination-img">
                                            <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-rembangan.jpg')}}" alt="Destinasi Wisata">
                                                <div class="destination-overlay p-4">
                                                    <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                                    <h4 class="text-white mb-2 mt-3">Puncak Rembangan</h4>
                                                    <a href="#" class="btn-hover text-white">Kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                                </div>
                                                <div class="search-icon">
                                                    <a href="img/destination-7.jpg" data-lightbox="destination-7"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="destination-img">
                                            <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-galaxy.jpg')}}" alt="Destinasi Wisata">
                                                <div class="destination-overlay p-4">
                                                    <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                                    <h4 class="text-white mb-2 mt-3">Taman Galaxy</h4>
                                                    <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                                </div>
                                                <div class="search-icon">
                                                    <a href="img/destination-8.jpg" data-lightbox="destination-8"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="destination-img h-100">
                                    <img class="img-fluid rounded w-100 h-100" src="{{ asset('template/envato/img/tempat-gunung-gambir.jpg') }}" style="object-fit: cover; min-height: 300px;" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Gunung Gambir</h4>
                                            <a href="#" class="btn-hover text-white">Kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-9.jpg" data-lightbox="destination-4"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-taman-nasional-baluran.jpeg')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Taman Nasional Baluran</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-4.jpg" data-lightbox="destination-4"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-purwo.webp')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Alas Purwo</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-5.jpg" data-lightbox="destination-5"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-gunung-pasang.jpg')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Gunung Pasang</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- jawa tengah -->
                        <div id="tab-2" class="tab-pane fade show p-0">
                            <div class="row g-4">
                                <div class="col-lg-4">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-prau.webp')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Sunrise Camp Gunung Prau</h4>
                                            <a href="#" class="btn-hover text-white">Kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-5.jpg" data-lightbox="destination-5"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-umbul.jpg')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Camp Area Umbul Bengkok</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-nglimut.webp')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Wana Wisata Hutan Pinus Nglimut</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-dringo.jpg')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Telaga Dringo</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- jawa barat -->
                        <div id="tab-3" class="tab-pane fade show p-0">
                            <div class="row g-4">
                            <div class="col-lg-6">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-batu-karas.jpeg')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Pantai Batu Karas</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-gua.jpeg')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Gua Sunyaragi</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-ranca.webp')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Ranca Upas</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-dago.webp')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Dago Dream Park</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- Bali -->
                        <div id="tab-4" class="tab-pane fade show p-0">
                            <div class="row g-4">
                            <div class="col-lg-6">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-banyumala.jpg')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Banyumala Twin Waterfall</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-campuhan.jpeg')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Campuhan Ridge Walk</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Sumatra -->
                        <div id="tab-5" class="tab-pane fade show p-0">
                            <div class="row g-4">
                            <div class="col-lg-6">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-ranau.webp')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Danau Ranau</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-khayangan.webp')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Bukit Khayangan</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-lembah-harau.webp')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Lembah Harau</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="destination-img">
                                    <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-maninjau.webp')}}" alt="Destinasi Wisata">
                                        <div class="destination-overlay p-4">
                                            <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                            <h4 class="text-white mb-2 mt-3">Danau Maninjau</h4>
                                            <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                        <div class="search-icon">
                                            <a href="img/destination-6.jpg" data-lightbox="destination-6"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- sulawesi -->
                        <div id="tab-6" class="tab-pane fade show p-0">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                            <div class="destination-img">
                                            <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-samalona.webp')}}" alt="Destinasi Wisata">
                                                <div class="destination-overlay p-4">
                                                    <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                                    <h4 class="text-white mb-2 mt-3">Pantai Samalona</h4>
                                                    <a href="#" class="btn-hover text-white">Kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                                </div>
                                                <div class="search-icon">
                                                    <a href="img/destination-1.jpg" data-lightbox="destination-1"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="destination-img">
                                            <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-tanjung-bira.webp')}}" alt="Destinasi Wisata">
                                                <div class="destination-overlay p-4">
                                                    <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                                    <h4 class="text-white mb-2 mt-3">Pantai Tanjung Bira</h4>
                                                    <a href="#" class="btn-hover text-white">Kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                                </div>
                                                <div class="search-icon">
                                                    <a href="img/destination-2.jpg" data-lightbox="destination-2"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="destination-img">
                                            <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-kepoposang.webp')}}" alt="Destinasi Wisata">
                                                <div class="destination-overlay p-4">
                                                    <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                                    <h4 class="text-white mb-2 mt-3">Pulau Kapoposang</h4>
                                                    <a href="#" class="btn-hover text-white">Kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                                </div>
                                                <div class="search-icon">
                                                    <a href="img/destination-7.jpg" data-lightbox="destination-7"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="destination-img">
                                            <img class="img-fluid w-100 rounded" src="{{asset('template/envato/img/tempat-cangke.webp')}}" alt="Destinasi Wisata">
                                                <div class="destination-overlay p-4">
                                                    <a href="#" class="btn btn-primary text-white rounded-pill border py-2 px-3">20 Photos</a>
                                                    <h4 class="text-white mb-2 mt-3"> Pulau Cangke</h4>
                                                    <a href="#" class="btn-hover text-white">kunjungi<i class="fa fa-arrow-right ms-2"></i></a>
                                                </div>
                                                <div class="search-icon">
                                                    <a href="img/destination-8.jpg" data-lightbox="destination-8"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <!-- Destination End -->




















@include('landing-page.halamanbawah')
       
      @include('landing-page.footer')