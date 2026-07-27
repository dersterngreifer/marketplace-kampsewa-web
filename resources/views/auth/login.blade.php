<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- todo import dari file css  --}}
    <link rel="stylesheet" href="{{ asset('css/cdn-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gradient/gradient-color.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/logo.ico') }}">

    {{-- Font handled globally via app.css --}}
    <title>{{ $title }}</title>

    {{-- todo import vite tailwindcss framework css --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    {{-- todo pembungkus utama --}}
    <div class="_container font-poppins grid w-full mobile-max:grid-cols-1 mobile-max:gap-[30px] grid-cols-2 h-screen">

        {{-- todo sub container --}}
        <div class="_form w-full mobile-max:p-[20px] flex justify-center items-center">

            {{-- todo form login --}}
            <form id="form" action="{{ route('login') }}" method="POST">
                @csrf

                {{-- todo logo --}}
                <div class="_logo w-full flex justify-center mb-[30px] items-center"><img
                        class="w-[100px] object-contain" src="{{ asset('images/logo-test.png') }}" alt=""></div>

                {{-- todo judul login dan deskripsi --}}
                <h1 class="text-center text-[28px] uppercase font-black">Login</h1>
                <p class="text-center text-[14px] font-normal">Login untuk memasuki dashboard sesuai dengan level anda.
                </p>

                {{-- todo kolom field --}}
                <div class="_input w-full flex flex-col gap-[10px] mt-[30px]">
                    <div
                        class="_input-username w-full focus-within:border-[#5038ED] focus-within:border-[2px] rounded-[10px] bg-[#F0EDFF] p-[10px] flex items-center gap-[10px]">
                        <div class="_icon"><i class="text-[20px] font-bold fi fi-rr-user"></i></div>
                        <input class="bg-transparent w-full text-[14px] font-normal focus:outline-none" type="text"
                            name="nomor_telfon" placeholder="Masukkan Email atau nomor Telfon">
                    </div>
                    <div
                        class="_input-password w-full focus-within:border-[#5038ED] focus-within:border-[2px] rounded-[10px] bg-[#F0EDFF] p-[10px] flex items-center gap-[10px]">
                        <div class="_icon"><i class="text-[20px] font-bold fi fi-rr-key"></i></div>
                        <input class="bg-transparent w-full text-[14px] font-normal focus:outline-none" type="password"
                            name="password" placeholder="Masukkan password">
                    </div>
                    <div class="_forgot-password w-full flex justify-end"><a
                            class="text-[#343B7C] text-[14px] underline" href="{{ route('lupa-password.index') }}">Lupa
                            Password?</a></div>
                </div>

                {{-- todo tombol login --}}
                <div class="_button w-full mt-[30px]"><button type="submit"
                        class="p-[15px] bg-linear-1 gradient-1 rounded-[10px] w-full text-white text-[14px]">Log
                        In</button></div>
            </form>
        </div>

        {{-- todo dekorasi gambar orang --}}
        <div class="_form-content mobile-max:p-[20px] w-full"
            style="background: linear-gradient(to bottom left, #B381F4, #5038ED);">
            <div class="_sub-form-content flex justify-center items-center w-full object-cover bg-no-repeat h-screen"
                style="background: url({{ asset('images/bgloginfix.png') }})">
                <div class="_card relative w-[400px] h-[450px] bg-rgb-1 rounded-[10px] border-[2px] border-rgb-2">
                    <div class="_text w-full h-auto flex justify-center p-[20px] items-center">
                        <p class="text-white font-bold text-[24px]">Autentikasi Login dan kelola dashboard anda!</p>
                    </div>
                    <div
                        class="_image-rounded mobile-max:mr-[-15px] absolute right-0 mr-[-30px] p-[15px] flex justify-center items-center w-[60px] h-[60px] bg-white rounded-full">
                        <img class="w-full" src="{{ asset('images/thunderbolt.png') }}" alt="">
                    </div>
                    <div class="_img mobile-max:p-[25px] absolute right-[-20] w-[400px] h-[400px] bottom-0"><img
                            class="w-full object-cover" src="{{ asset('images/people-with-laptop.png') }}"
                            alt=""></div>
                </div>
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
</body>

</html>
