<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/cdn-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gradient/gradient-color.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/favicons/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F1F5F9; min-height: 100vh; }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }

        .card {
            background: #FFFFFF;
            border-radius: 20px;
            max-width: 560px;
            width: 100%;
            padding: 40px 36px;
            box-shadow: 0 20px 50px -12px rgba(80, 56, 237, 0.2);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .card::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(179, 129, 244, 0.15) 0%, transparent 70%);
        }

        .logo {
            width: 72px;
            margin-bottom: 20px;
            object-fit: contain;
        }

        .icon-badge {
            width: 96px;
            height: 96px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFF7ED 0%, #FEF3C7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #FDBA74;
        }
        .icon-badge i { font-size: 40px; color: #EA580C; }

        .title {
            font-size: 24px;
            font-weight: 900;
            color: #0F172A;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-size: 14px;
            color: #64748B;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .steps {
            text-align: left;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 14px;
            padding: 22px 24px;
            margin-bottom: 28px;
        }

        .steps-title {
            font-size: 13px;
            font-weight: 800;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .steps-title i { color: #7C3AED; }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 8px 0;
        }
        .step:not(:last-child) { border-bottom: 1px dashed #E2E8F0; }

        .step-num {
            flex-shrink: 0;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7C3AED, #5038ED);
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1px;
        }

        .step-text {
            font-size: 13.5px;
            color: #334155;
            line-height: 1.5;
        }
        .step-text strong { color: #0F172A; }

        .btn-group {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 24px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            background: linear-gradient(135deg, #7C3AED, #5038ED);
            color: #FFFFFF;
            box-shadow: 0 10px 24px -6px rgba(80, 56, 237, 0.5);
            transition: all 0.25s ease;
        }
        .btn-primary:hover { transform: translateY(-2px); }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 24px;
            border-radius: 12px;
            border: 1.5px solid #E2E8F0;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            background: #FFFFFF;
            color: #334155;
            transition: all 0.25s ease;
        }
        .btn-ghost:hover { background: #F8FAFC; }

        .note {
            margin-top: 20px;
            font-size: 12px;
            color: #94A3B8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .note i { color: #22C55E; }

        @media (max-width: 480px) {
            .card { padding: 32px 22px; }
            .btn-group { flex-direction: column; }
            .btn-primary, .btn-ghost { width: 100%; justify-content: center; }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <img class="logo" src="{{ asset('assets/logo/favicons/android-chrome-512x512.png') }}" alt="KampSewa Logo">

            <div class="icon-badge">
                <i class="fas fa-id-card"></i>
            </div>

            <h1 class="title">Lengkapi Verifikasi Identitas</h1>
            <p class="subtitle">
                Untuk dapat mengakses dashboard dan menjadi mitra penyewa di KampSewa,
                Anda harus melengkapi verifikasi identitas (NIK KTP) terlebih dahulu melalui
                aplikasi mobile KampSewa.
            </p>

            <div class="steps">
                <div class="steps-title">
                    <i class="fas fa-list-check"></i> Langkah-Langkah
                </div>
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-text">Buka aplikasi <strong>KampSewa</strong> di smartphone Anda.</div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-text">Login menggunakan akun yang sama dengan akun ini.</div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-text">Masuk ke menu <strong>Profil</strong> &rarr; <strong>Verifikasi Identitas</strong>.</div>
                </div>
                <div class="step">
                    <div class="step-num">4</div>
                    <div class="step-text">Isi <strong>Nomor Identitas (NIK KTP)</strong> dan unggah <strong>foto KTP</strong>.</div>
                </div>
                <div class="step">
                    <div class="step-num">5</div>
                    <div class="step-text">Setelah tersimpan, kembali login di website ini. Dashboard langsung terbuka.</div>
                </div>
            </div>

            <div class="btn-group">
                <a class="btn-primary" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-power-off"></i> Logout
                </a>
            </div>

            <p class="note">
                <i class="fas fa-shield-alt"></i> Data Anda aman dan hanya digunakan untuk verifikasi.
            </p>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    @include('sweetalert::alert')
</body>

</html>
