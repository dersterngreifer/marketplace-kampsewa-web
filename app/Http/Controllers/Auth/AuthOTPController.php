<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthOTPController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        $nomorTujuan = $request->input('phone');
        $kodeOtp = random_int(100000, 999999);

        // 1. Simpan OTP ke cache selama 5 menit (key dibuat unik per nomor)
        $cacheKey = 'otp_' . preg_replace('/[^0-9]/', '', $nomorTujuan);
        Cache::put($cacheKey, $kodeOtp, now()->addMinutes(5));

        // 2. Susun format pesan WhatsApp (Gunakan bold dan pemisah agar mencolok)
        $message = "*TEAM ABBMA*\n"
                 . "_Layanan Keamanan & Verifikasi Akun_\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n\n"
                 . "Halo Pengguna,\n\n"
                 . "Kami menerima permintaan *Lupa Password* untuk akun Anda. Gunakan kode verifikasi berikut untuk melanjutkan:\n\n"
                 . "👉 *{$kodeOtp}* 👈\n\n"
                 . "⚠️ *PENTING:*\n"
                 . "• Kode ini hanya berlaku selama *5 menit*.\n"
                 . "• Jangan pernah membagikan kode ini kepada siapa pun, termasuk pihak ABBMA.\n\n"
                 . "━━━━━━━━━━━━━━━━━━━━\n"
                 . "_Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan pesan ini._";

        // 3. Kirim ke WhatsApp Gateway Baileys
        $response = Http::timeout(10)->post('http://localhost:3000/api/send-otp', [
            'phone'   => $nomorTujuan,
            'message' => $message
        ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'OTP berhasil dikirim ke WhatsApp!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengirim OTP ke WhatsApp.'
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
