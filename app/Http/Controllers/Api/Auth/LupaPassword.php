<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\CssSelector\Node\FunctionNode;

class LupaPassword extends Controller
{
    public function verifikasiPhone(Request $request) {
        $validate = $request->validate([
            'nomor_telephone' => 'required|numeric|exists:users,nomor_telephone',
        ], [
            'nomor_telephone.exists' => 'Nomor telepon tidak terdaftar.'
        ]);

        try {
            $user = User::where('nomor_telephone', $validate['nomor_telephone'])
                        ->where('type', 0)
                        ->first();

            if ($user) {
                $OTP = rand(100000, 999999);

                $resetPassword = new ResetPassword();
                $resetPassword->id_user = $user->id;
                $resetPassword->nomor_telephone = $user->nomor_telephone;
                $resetPassword->otp = $OTP;
                $resetPassword->expired_at = now()->addMinutes(1);
                $resetPassword->save();

                $telfon = $validate['nomor_telephone'];
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
                    $response_sms = \Illuminate\Support\Facades\Http::timeout(10)->post('http://localhost:3000/api/send-otp', [
                        'phone'   => $telfon,
                        'message' => $message
                    ]);
                    $isSuccess = $response_sms->successful();
                } catch (\Exception $e) {
                    $isSuccess = false;
                }

                if ($isSuccess) {
                    return response()->json([
                        'status' => 'success',
                        'message' => "OTP berhasil dikirim ke nomor telepon yang terdaftar: $telfon."
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terjadi kesalahan, harap coba lagi.'
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor telepon tidak terdaftar atau tidak memiliki izin untuk menerima OTP.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan, harap coba lagi.'
            ], 500);
        }
    }

    // fungsi verifikasi otp ini digunakan seteleh user melewati tahap
    // check pengiriman otp dengan menggunakan nomor telephone yang terdaftar
    public Function verifikasiOTP($nomor_telephone, Request $request) {
        // variable name yang harus sama dengan textfield
        // pada kode flutter yang digunakan untuk mencocokkan request
        $name_form = 'otp';

        // digunakan untuk custom pesan error pada validasi input
        $messages = [
            'otp.required' => 'Kode OTP harus diisi.',
            'otp.numeric' => 'Kode OTP harus berupa angka.',
            'otp.exists' => 'Kode OTP tidak valid.',
        ];

        // melakukan validasi berdasarkan pengiriman request dari input
        // dalam variable $name_form
        $validate = $request->validate([
            $name_form => 'required|numeric|exists:reset_password,otp',
        ], $messages);

        // melakukan check apakah otp yang diinputkan sesuai dengan otp dan nomor telephone yang ada pada table reset_password
        // dan data yang diambil adalah data terbaru dengan menggunakan orderBy desc
        $check_otp_user = ResetPassword::where('nomor_telephone', $nomor_telephone)->orderBy('created_at', 'desc')->first();

        // melakukan pengecekan apakah $check_otp_user ada dan apakah kolom otp
        // sama dengan $validate[$name_form] yang di inputkan oleh user
        if($check_otp_user && $check_otp_user->otp == $validate[$name_form]) {

            // melakukan check apakah kode otp yang dimasukkan expired atau tidak
            if(Carbon::now()->gt($check_otp_user->expired_at)) {

                // jika check sesuai maka hapus seluruh data otp yang
                // terkait dengan nomor_telephone user
                ResetPassword::where('nomor_telephone', $nomor_telephone)->delete();

                // berikan respons pesan bahwa kode otp telah kadaluarsa
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kode OTP yang Anda masukkan telah kadaluarsa.'
                ], 400);
            } else {
                // kirimkan respon bahwa kode otp berhasil di verifikasi
                return response()->json([
                    'status' => 'success',
                    'message' => 'Kode OTP Berhasil di Verifikasi'
                ], 200);
            }
        } else {
            // berikan respons pesan bahwa kode otp yang dimasukkan tidak sesuai
            return response()->json([
                'status' => 'error',
                'message' => 'Kode OTP yang Anda masukkan tidak sesuai.'
            ], 400);
        }
    }

    // fungsi reset password ini digunakan seteleh user melewati tahap
    // pengecekan otp dan melewati tahap verifikasi otp
    public function resetPassword($nomor_telephone, Request $request) {
        // variable name yang harus sama dengan textfield
        // pada kode flutter yang digunakan untuk mencocokkan request
        $name_form = ['password', 'confirm-password'];

        // custom pesan error untuk input
        $message = [
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
            'confirm-password.required' => 'Konfirmasi password harus diisi.',
            'confirm-password.min' => 'Konfirmasi password harus terdiri dari minimal 8 karakter.',
        ];

        // validasi dari name input dengan mencocokkan kedalam
        // kolom table users
        $validate = $request->validate([
            'password' => 'required|min:8',
            'confirm-password' => 'required|min:8',
        ], $message);

        // buat variable yang menampung isi untuk mengambil data
        // dari tabe users sesuai dengan nomor_telephone
        $table_users = User::where('nomor_telephone', $nomor_telephone)->where('type', 0)->first();

        // check apakah confirmasi password sama dengan password
        if($validate[$name_form[1]] !== $validate[$name_form[0]]) {
            // kirimkan respon bahwa konfirmasi password tidak cocok
            return response()->json([
                'status' => 'error',
                'message' => 'Konfirmasi password tidak cocok!'
            ], 400);
        }

        // check apakah data user ada
        if($table_users) {
            // data user ada maka update password dengan fungsi bycript / hash
            $table_users->update([
                'password' => bcrypt($validate[$name_form[0]]),
            ]);

            // kirimkan respon bahwa password anda telah diperbarui
            return response()->json([
                'status' => 'success',
                'message' => 'Password anda telah diperbarui!'
            ], 200);
        } else {
            // kirimkan respon bahwa user tidak ditemukan
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan!'
            ], 400);
        }
    }

    // fungsi untuk tombol kirim ulang kode otp
    function kirimUlangOTP($nomor_telephone) {
        // Mengambil data user berdasarkan nomor_telephone
        $data_user = User::where('nomor_telephone', $nomor_telephone)->first();

        // Check apakah ada data user dengan nomor telepon yang diberikan
        if ($data_user) {
            // Generate OTP baru
            $OTP = rand(100000, 999999);

            // Simpan OTP ke dalam tabel reset_passwords
            $resetPassword = new ResetPassword();
            $resetPassword->id_user = $data_user->id;
            $resetPassword->nomor_telephone = $data_user->nomor_telephone;
            $resetPassword->otp = $OTP;
            $resetPassword->expired_at = now()->addMinutes(2);
            $resetPassword->save();

            // Konfigurasi untuk mengirim pesan OTP melalui API SMS
            $telfon = $data_user->nomor_telephone;
            $nama_user = $data_user->name;

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
                // Kirim permintaan untuk mengirim pesan OTP
                $response_sms = \Illuminate\Support\Facades\Http::timeout(10)->post('http://localhost:3000/api/send-otp', [
                    'phone'   => $telfon,
                    'message' => $message
                ]);
                $isSuccess = $response_sms->successful();
            } catch (\Exception $e) {
                $isSuccess = false;
            }

            // Periksa apakah pengiriman OTP berhasil
            if ($isSuccess) {
                // Jika berhasil, kirim respons JSON dengan status 'success'
                return response()->json([
                    'status' => 'success',
                    'message' => "OTP berhasil dikirim ulang ke nomor telepon yang terdaftar: $telfon."
                ], 200);
            } else {
                // Jika gagal mengirim OTP, kirim respons JSON dengan status 'error'
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengirim OTP ulang, harap coba lagi.'
                ], 500);
            }
        } else {
            // Jika nomor telepon tidak terdaftar, kirim respons JSON dengan status 'error'
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor telepon tidak terdaftar.'
            ], 404);
        }
    }
}
