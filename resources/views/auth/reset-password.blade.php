<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/favicons/favicon.ico') }}">
    <title>{{ $title }}</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="_container w-full h-screen flex justify-center items-center mobile-max:p-4 mobile-max:h-auto">
        <div class="_form w-[400px] p-2">
            <form action="{{ route('lupa-password.change-password', ['nomor_telephone' => $nomor_telephone]) }}"
                method="POST" class="flex flex-col gap-4">
                @csrf
                <div class="_image w-full flex justify-center">
                    <img class="w-[200px] object-cover"
                        src="{{ asset('assets/vector/grapy-man-giving-a-five-star-rating-to-product.png') }}"
                        alt="">
                </div>
                <div class="_title">
                    <h1 class="text-[24px] text-center font-medium">Buat Password</h1>
                    <p class="text-[14px] text-center">Buat password baru anda dan masukkan confirm password untuk
                        update data anda.</p>
                </div>
                <div class="_feild-password flex flex-col gap-2">
                    <label for="password" class="text-[14px] font-medium">Masukkan Password Baru:</label>
                    <div class="_email w-full">
                        <input placeholder="ex: 354yctei4936"
                            class="border focus:outline-[#5038ED] w-full border-solid rounded-[10px] text-[14px] p-2"
                            type="password" name="password" id="password">
                        @error('password')
                            <div class="text-red-500 mt-2 text-sm font-medium">Input Password belum anda masukkan!</div>
                        @enderror
                    </div>
                    <label for="confirm-password" class="text-[14px] font-medium">Konfirmasi Password:</label>
                    <div class="_email w-full">
                        <input placeholder="same ex"
                            class="border focus:outline-[#5038ED] w-full border-solid rounded-[10px] text-[14px] p-2"
                            type="password" name="confirm-password" id="confirm-password">
                        @error('confirm-password')
                            <div class="text-red-500 mt-2 text-sm font-medium">Input Confirm belum anda masukkan!</div>
                        @enderror
                    </div>
                </div>
                <div class="_button-submit w-full">
                    <button type="submit"
                        class="w-full p-2 bg-gradient-to-bl rounded-[10px] from-[#B381F4] to-[#5038ED] text-white text-[14px] font-medium">Ubah
                        Password</button>
                </div>
                <p class="text-[14px] font-medium text-center">Ke halaman login? <a href="{{ route('login') }}"
                        class="text-[#5038ED] hover:underline">klik disini!</a></p>
            </form>
        </div>
    </div>

    @include('sweetalert::alert')
</body>

</html>
