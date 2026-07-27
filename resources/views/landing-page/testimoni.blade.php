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
                            <img src="{{asset('template/envato/img/home-6.jpg')}}" class="img-fluid" alt="Image">
                            <div class="carousel-caption">
                                <div class="p-3" style="max-width: 800px; text-align: center;">
                                <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 4px;">Apa kata mereka?</h4>
                                <h1 class="display-2 text-capitalize text-white mb-4">Testimoni Pelanggan</h1>
                                <p class="mb-4">Dengerin cerita seru dari pelanggan kita! Testimoni asli dan kisah-kisah keren dari mereka yang udah jalan-jalan ke destinasi kita dan nyewa barang dari kita.</p>
                            </div>
                            </div>
                        </div>
                        
                       
                    </div>
                    
                </div>
            </div>
            <!-- Carousel End -->
        </div>

        <div class="container-fluid testimonial py-5">
            <div class="container py-5">
                <div class="mx-auto text-center mb-5" style="max-width: 900px;">
                    <h5 class="section-title px-3">Testimoni</h5>
                    <h1 class="mb-0">Testimoni pelanggan KampSewa</h1>
                </div>
                <div class="testimonial-carousel owl-carousel">
                    <div class="testimonial-item text-center rounded pb-4">
                        <div class="testimonial-comment bg-light rounded p-4">
                            <p class="text-center mb-5">Pengalaman camping saya jadi lebih menyenangkan berkat KampSewa! Peralatan yang mereka sediakan sangat lengkap dan berkualitas.
                                                        Tenda yang kami sewa sangat mudah dipasang dan nyaman. Pasti akan sewa lagi untuk trip berikutnya!</p>
                        </div>
                        <div class="testimonial-img p-1">
                            <img src="{{'template/envato/img/Testimoni-1.jpg'}}" class="img-fluid rounded-circle" alt="Image">
                        </div>
                        <div style="margin-top: -35px;">
                            <h5 class="mb-0">Herman Maulana</h5>
                            <p class="mb-0">Lumajang, Jawa Timur</p>
                            <div class="d-flex justify-content-center">
                            <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item text-center rounded pb-4">
                        <div class="testimonial-comment bg-light rounded p-4">
                            <p class="text-center mb-5">Saya sangat puas dengan layanan KampSewa. 
                                                        Sleeping bag yang saya sewa sangat hangat dan nyaman, sempurna untuk malam dingin di gunung. Stafnya juga sangat ramah dan membantu. Highly recommended
                            </p>
                        </div>
                        <div class="testimonial-img p-1">
                        <img src="{{'template/envato/img/Testimoni-2.jpg'}}" class="img-fluid rounded-circle" alt="Image">
                        </div>
                        <div style="margin-top: -35px;">
                            <h5 class="mb-0">Lukman Ikhsan</h5>
                            <p class="mb-0">Sukabumi, Jawa Barat</p>
                            <div class="d-flex justify-content-center">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item text-center rounded pb-4">
                        <div class="testimonial-comment bg-light rounded p-4">
                            <p class="text-center mb-5">KampSewa benar-benar memudahkan persiapan camping saya. 
                                                        Semua peralatan, mulai dari kompor portable hingga alat navigasi, tersedia dengan kondisi baik. Sewa barang jadi simpel dan praktis!
                            </p>
                        </div>
                        <div class="testimonial-img p-1">
                        <img src="{{'template/envato/img/Testimoni-3.jpg'}}" class="img-fluid rounded-circle" alt="Image">
                        </div>
                        <div style="margin-top: -35px;">
                            <h5 class="mb-0">Teti Kusuma</h5>
                            <p class="mb-0">Bondowoso, Jawa Timur</p>
                            <div class="d-flex justify-content-center">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item text-center rounded pb-4">
                        <div class="testimonial-comment bg-light rounded p-4">
                            <p class="text-center mb-5">Pelayanan di KampSewa top banget! Saya sewa matras dan kursi lipat untuk camping keluarga, dan semua barangnya dalam kondisi sangat baik. 
                                                        Harganya juga sangat terjangkau. Terima kasih, KampSewa!
                            </p>
                        </div>
                        <div class="testimonial-img p-1">
                        <img src="{{'template/envato/img/Testimoni-4.jpg'}}" class="img-fluid rounded-circle" alt="Image">
                        </div>
                        <div style="margin-top: -35px;">
                            <h5 class="mb-0">Yusuf Akmal</h5>
                            <p class="mb-0">Banyuwangi, Jawa Timur</p>
                            <div class="d-flex justify-content-center">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        @include('landing-page.halamanbawah')
       
      @include('landing-page.footer')