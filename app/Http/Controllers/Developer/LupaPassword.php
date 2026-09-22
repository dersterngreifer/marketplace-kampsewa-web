<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class LupaPassword extends Controller
{
    public function index()
    {
        return view('auth.lupa-password', ['title' => 'Verifikasi Nomor Telephone']);
    }

    public function sendOTP(Request $request)
    {
        $validate = $request->validate([
            'nomor_telephone' => 'required|numeric|exists:users',
        ]);
        $user = User::where('nomor_telephone', $validate['nomor_telephone'])->first();
        if ($user) {
            $OTP = rand(100000, 999999);

            $resetPassword = new ResetPassword();
            $resetPassword->id_user = $user->id;
            $resetPassword->nomor_telephone = $user->nomor_telephone;
            $resetPassword->otp = $OTP;
            $resetPassword->expired_at = now()->addMinutes(1);
            $resetPassword->save();

            $telfon = $validate['nomor_telephone'];
            $encrypt_telfon = Crypt::encryptString($telfon);
            $nama_user = $user->name;

            $message = "*TEAM ABBMA*\n"
                     . "_Layanan Keamanan & Verifikasi Akun_\n"
                     . "━━━━━━━━━━━━━━━━━━━━\n\n"
                     . "Halo *$nama_user*,\n\n"
                     . "Kami menerima permintaan *Lupa Password* untuk akun KampSewa Anda. Gunakan kode verifikasi berikut untuk melanjutkan:\n\n"
                     . "👉 *$OTP* 👈\n\n"
                     . "⚠️ *PENTING:*\n"
                     . "• Kode ini hanya berlaku selama *1 menit*.\n"
                     . "• Jangan pernah membagikan kode ini kepada siapa pun, termasuk pihak KampSewa.\n\n"
                     . "━━━━━━━━━━━━━━━━━━━━\n"
                     . "_Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan pesan ini._";

            try {
                \Illuminate\Support\Facades\Http::timeout(10)->post('http://localhost:3000/api/send-otp', [
                    'phone'   => $telfon,
                    'message' => $message
                ]);
            } catch (\Exception $e) {
                // Ignore error and proceed or log it
            }

            Alert::toast('Kode OTP verifikasi terkirim, cek pesan WhatsApp anda!', 'success');
            return redirect('lupa-password/check-kode-otp/' . $encrypt_telfon);
        } else {
            Alert::toast('Nomor telepon tidak ditemukan', 'error');
            return back()->with('error', 'Nomor telepon tidak ditemukan');
        }
    }

    public function indexCheckOTP($nomor_telephone)
    {
        return view('auth.verifikasi-kode-otp', ['title' => 'Verifikasi Kode OTP', 'nomor_telephone' => $nomor_telephone]);
    }
    public function checkOTP($nomor_telephone, Request $request)
    {
        $nomor_telephone_dec = Crypt::decryptString($nomor_telephone);
        $otpInput = $request->only(['otp1', 'otp2', 'otp3', 'otp4', 'otp5', 'otp6']);
        $otpCode = implode('', $otpInput);
        $kode_otp = ResetPassword::where('nomor_telephone', $nomor_telephone_dec)->orderBy('created_at', 'desc')->first();
        if ($kode_otp && $kode_otp->otp === $otpCode) {
            if (Carbon::now()->gt($kode_otp->expired_at)) {
                ResetPassword::where('nomor_telephone', $kode_otp->nomor_telephone)->delete();
                Alert::toast('Kode OTP yang Anda masukkan telah kadaluarsa', 'error');
                return back()->with('error', 'Kode OTP yang Anda masukkan telah kadaluarsa');
            } else {
                Alert::toast('Kode OTP Berhasil di Verifikasi', 'success');
                return redirect('lupa-password/reset-password/' . $nomor_telephone);
            }
        } else {
            Alert::toast('Kode OTP yang Anda masukkan tidak sesuai', 'error');
            return back()->with('error', 'Kode OTP yang Anda masukkan tidak sesuai');
        }
    }
    public function indexResetPassword($nomor_telephone)
    {
        return view('auth.reset-password', ['title' => 'Reset Password', 'nomor_telephone' => $nomor_telephone]);
    }
    public function resetPassword($nomor_telephone, Request $request)
    {
        $nomor_dec = Crypt::decryptString($nomor_telephone);
        $validate = $request->validate([
            'password' => 'required|min:8',
            'confirm-password' => 'required|min:8',
        ]);
        if ($validate['confirm-password'] !== $validate['password']) {
            Alert::toast('Konfirmasi password tidak cocok dengan password', 'error');
            return back()->withErrors(['confirm-password' => 'Konfirmasi password tidak cocok dengan password']);
        }
        $user = User::where('nomor_telephone', $nomor_dec)->first();
        if ($user) {
            $user->update([
                'password' => bcrypt($validate['password']),
            ]);
            Alert::toast('Password Anda berhasil diperbarui!', 'success');
            return redirect('/login')->with('success', 'Password Anda berhasil diperbarui!');
        } else {
            Alert::toast('Nomor telepon tidak ditemukan', 'error');
            return back()->with('error', 'Nomor telepon tidak ditemukan');
        }
    }

    public function kirimUlang($nomor_telephone) {
        $nomor = Crypt::decryptString($nomor_telephone);
        $user = User::where('nomor_telephone', $nomor)->first();
        if ($user) {
            $OTP = rand(100000, 999999);

            $resetPassword = new ResetPassword();
            $resetPassword->id_user = $user->id;
            $resetPassword->nomor_telephone = $user->nomor_telephone;
            $resetPassword->otp = $OTP;
            $resetPassword->expired_at = now()->addMinutes(1);
            $resetPassword->save();

            $telfon = $user->nomor_telephone;
            $nama_user = $user->name;

            $message = "*TEAM ABBMA*\n"
                     . "_Layanan Keamanan & Verifikasi Akun_\n"
                     . "━━━━━━━━━━━━━━━━━━━━\n\n"
                     . "Halo *$nama_user*,\n\n"
                     . "Kami menerima permintaan *Lupa Password* untuk akun KampSewa Anda. Gunakan kode verifikasi berikut untuk melanjutkan:\n\n"
                     . "👉 *$OTP* 👈\n\n"
                     . "⚠️ *PENTING:*\n"
                     . "• Kode ini hanya berlaku selama *1 menit*.\n"
                     . "• Jangan pernah membagikan kode ini kepada siapa pun, termasuk pihak KampSewa.\n\n"
                     . "━━━━━━━━━━━━━━━━━━━━\n"
                     . "_Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan pesan ini._";

            try {
                \Illuminate\Support\Facades\Http::timeout(10)->post('http://localhost:3000/api/send-otp', [
                    'phone'   => $telfon,
                    'message' => $message
                ]);
            } catch (\Exception $e) {
                // Ignore error and proceed or log it
            }

            Alert::toast('Kode OTP verifikasi terkirim, cek pesan WhatsApp anda!', 'success');
            return back()->with('success', 'Kode OTP verifikasi terkirim, cek pesan WhatsApp anda!');
        } else {
            Alert::toast('Nomor telepon tidak ditemukan', 'error');
            return back()->with('error', 'Nomor telepon tidak ditemukan');
        }
    }
}
