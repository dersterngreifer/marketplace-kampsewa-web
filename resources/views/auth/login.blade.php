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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* =============================================
           LEFT FORM PANEL – PREMIUM REDESIGN
           (Sisi kanan / foto tidak diubah sama sekali)
           ============================================= */

        body { margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Subtle animated background for left panel */
        ._form {
            position: relative;
            background: #FFFFFF;
            overflow: hidden;
        }

        /* Decorative blobs */
        ._form::before {
            content: '';
            position: absolute;
            top: -120px;
            left: -120px;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(80, 56, 237, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        ._form::after {
            content: '';
            position: absolute;
            bottom: -100px;
            right: -80px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(179, 129, 244, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ---- Back Button ---- */
        .back-home-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 700;
            color: #FFFFFF;
            text-decoration: none;
            position: absolute;
            top: 28px;
            left: 36px;
            padding: 8px 18px;
            border-radius: 50px;
            background: linear-gradient(135deg, #7C3AED 0%, #5038ED 100%);
            border: none;
            box-shadow: 0 6px 16px -2px rgba(80, 56, 237, 0.35);
            transition: all 0.25s ease;
            z-index: 10;
        }
        .back-home-link:hover {
            color: #FFFFFF;
            transform: translateX(-3px);
            box-shadow: 0 10px 24px -2px rgba(80, 56, 237, 0.5);
            background: linear-gradient(135deg, #6D28D9 0%, #4338CA 100%);
        }

        /* ---- Form Card Wrapper ---- */
        .login-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 420px;
        }

        /* ---- Logo badge ---- */
        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 28px;
        }
        .logo-wrapper img {
            width: 80px;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(80,56,237,0.15));
        }

        /* ---- Heading ---- */
        .login-title {
            text-align: center;
            font-size: 28px;
            font-weight: 900;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 6px 0;
        }
        .login-subtitle {
            text-align: center;
            font-size: 13.5px;
            font-weight: 500;
            color: #64748B;
            margin: 0 0 28px 0;
            line-height: 1.6;
        }

        /* ---- Divider ---- */
        .form-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }
        .form-divider::before, .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #E2E8F0;
        }
        .form-divider span {
            font-size: 11px;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        /* ---- Input Fields ---- */
        .input-group-label {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 7px;
        }
        .input-field-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 14px;
            padding: 13px 18px;
            transition: all 0.25s ease;
        }
        .input-field-wrap:focus-within {
            background: #FFFFFF;
            border-color: #5038ED;
            box-shadow: 0 0 0 4px rgba(80, 56, 237, 0.10);
        }
        .input-field-wrap .field-icon {
            color: #94A3B8;
            font-size: 15px;
            transition: color 0.2s;
        }
        .input-field-wrap:focus-within .field-icon {
            color: #5038ED;
        }
        .input-field-wrap input {
            background: transparent;
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
            font-weight: 500;
            color: #0F172A;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .input-field-wrap input::placeholder {
            color: #94A3B8;
            font-weight: 400;
        }

        /* ---- Submit Button ---- */
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #7C3AED 0%, #5038ED 100%);
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 800;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            box-shadow: 0 10px 28px -4px rgba(80, 56, 237, 0.45);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.3px;
        }
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 36px -4px rgba(80, 56, 237, 0.55);
            background: linear-gradient(135deg, #6D28D9 0%, #4338CA 100%);
        }
        .btn-login:active { transform: translateY(0); }

        /* ---- Forgot password ---- */
        .forgot-link {
            color: #5038ED;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }
        .forgot-link:hover { text-decoration: underline; }

        /* ---- Security badge ---- */
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-top: 20px;
            color: #94A3B8;
            font-size: 12px;
            font-weight: 600;
        }
        .security-badge i { color: #22C55E; font-size: 13px; }

        /* Responsive */
        @media (max-width: 767px) {
            ._form { padding: 80px 24px 40px !important; }
            .back-home-link { top: 20px; left: 20px; }
        }
    </style>
</head>

<body>
    <div class="_container font-poppins grid w-full mobile-max:grid-cols-1 mobile-max:gap-[30px] grid-cols-2 h-screen">

        {{-- ============================================================
             KIRI: FORM LOGIN — REDESIGNED PREMIUM
             ============================================================ --}}
        <div class="_form w-full mobile-max:p-[20px] flex justify-center items-center" style="padding: 48px 40px;">

            {{-- Tombol Kembali --}}
            <a href="{{ route('landing-page.halaman-beranda') }}" class="back-home-link">
                <i class="fas fa-arrow-left" style="font-size: 11px;"></i>
                Kembali ke Beranda
            </a>

            <div class="login-card">
                {{-- Logo --}}
                <div class="logo-wrapper">
                    <img src="{{ asset('images/logo-test.png') }}" alt="KampSewa Logo">
                </div>

                {{-- Heading --}}
                <h1 class="login-title">Login</h1>
                <p class="login-subtitle">Masuk ke dashboard sesuai dengan level akun Anda.</p>

                {{-- Divider --}}
                <div class="form-divider"><span>Masukkan Kredensial Anda</span></div>

                {{-- Form --}}
                <form id="form" action="{{ route('login') }}" method="POST">
                    @csrf

                    <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                        {{-- Email / No. Telepon --}}
                        <div>
                            <label class="input-group-label">Email atau Nomor Telepon</label>
                            <div class="input-field-wrap">
                                <i class="fi fi-rr-user field-icon"></i>
                                <input type="text" name="nomor_telfon" placeholder="Masukkan Email atau nomor Telfon">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="input-group-label">Password</label>
                            <div class="input-field-wrap">
                                <i class="fi fi-rr-key field-icon"></i>
                                <input type="password" name="password" placeholder="Masukkan password">
                            </div>
                        </div>

                        {{-- Forgot Password --}}
                        <div style="display: flex; justify-content: flex-end;">
                            <a class="forgot-link" href="{{ route('lupa-password.index') }}">Lupa Password?</a>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Log In</span>
                    </button>
                </form>

                {{-- Security note --}}
                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Koneksi Aman & Terenkripsi SSL</span>
                </div>
            </div>
        </div>

        {{-- ============================================================
             KANAN: DEKORASI FOTO — TIDAK DIUBAH SAMA SEKALI (ORIGINAL)
             ============================================================ --}}
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
