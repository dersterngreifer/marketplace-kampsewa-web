<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\StatusNotifikasiUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:30|regex:/^[a-zA-Z\s]*$/',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'nomor_telephone' => 'required|string|max:13|min:10|regex:/^08[0-9]{1,13}$/',
            'tanggal_lahir' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'background' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan',
            'remember_token' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $request->merge([
            'type' => 0,
            'status' => 'offline',
            'is_verified' => 0,
            'remember_token' => Str::random(100),
        ]);

        // check apakah nomor telephone sudah ada
        $nomor_telephone = $request->nomor_telephone;
        $user_nomor = User::where('nomor_telephone', $nomor_telephone)->first();
        if ($user_nomor) {
            return response()->json([
                'error' => 'Nomor Sudah Terdaftar',
            ], 409);
        }

        // check email
        $email_user = $request->email;
        $check_email_user = User::where('email', $email_user)->first();
        if($check_email_user) {
            return response()->json([
                'error' => 'Email Sudah Terdaftar Masrbo!',
            ], 409);
        }

        if (strlen($request->name) > 30 || strlen($request->name) < 7) {
            return response()->json(['error' => 'Nama memiliki batas maksimal 30 karakter dan batas minimal 7 karakter'], 400);
        } else if (preg_match('/[0-9]/', $request->name)) {
            return response()->json(['error' => 'Nama hanya boleh mengandung huruf'], 400);
        } else if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 'Email tidak valid'], 400);
        } else if (strlen($request->password) < 8 || strlen($request->password) > 16) {
            return response()->json(['error' => 'Password harus terdiri dari 8 atau 16 karakter'], 400);
        } else if (strlen($request->nomor_telephone) < 10 || strlen($request->nomor_telephone) > 13) {
            return response()->json(['error' => 'Nomor telepon harus terdiri dari 10 atau 13 karakter'], 400);
        } else if (!preg_match('/^08[0-9]{1,13}$/', $request->nomor_telephone)) {
            return response()->json(['error' => 'Nomor telepon harus dimulai dengan 08'], 400);
        } else if (!ctype_digit($request->nomor_telephone)) {
            return response()->json(['error' => 'Nomor telepon hanya boleh berisi angka'], 400);
        } else if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            if ($foto->getSize() > 2048 * 1024) {
                return response()->json(['error' => 'Gambar profil melebihi batas ukuran yang diizinkan.'], 400);
            }
            if (!in_array($foto->getClientOriginalExtension(), ['jpeg', 'png', 'jpg'])) {
                return response()->json(['error' => 'Hanya menudukung format jpeg, png, jpg.'], 400);
            }
        } else if ($request->hasFile('background')) {
            $background = $request->file('background');
            if ($background->getSize() > 2048 * 1024) {
                return response()->json(['error' => 'Gambar latar belakang melebihi batas ukuran yang diizinkan.'], 400);
            }
            if (!in_array($background->getClientOriginalExtension(), ['jpeg', 'png', 'jpg'])) {
                return response()->json(['error' => 'Hanya menudukun format jpeg, png, jpg'], 400);
            }
        }

        $request->merge([
            'type' => 0,
            'status' => 'active',
            'is_verified' => 0,
            'remember_token' => Str::random(100),
            'time_login' => null,
            'last_login' => null,
            'name_store' => null,
        ]);

        $user = new User($request->all());
        $user->password = bcrypt($request->password);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('assets/image/customers/profile/'), $fotoName);
            $user->foto = $fotoName;
        } else {
            $user->foto = 'man.png';
        }

        if ($request->hasFile('background')) {
            $background = $request->file('background');
            $backgroundName = time() . '.' . $background->getClientOriginalExtension();
            $background->move(public_path('assets/image/customers/background/'), $backgroundName);
            $user->background = $backgroundName;
        } else {
            $user->background = 'Belum Di isikan';
        }

        $user->save();

        $notifikasi = new StatusNotifikasiUser();
        $notifikasi->id_user = $user->id;
        $notifikasi->status = 'unread';
        $notifikasi->save();

        // Check for existing offline orders and link them to this new user
        \App\Models\Penyewaan::where('tipe_pesanan', 'offline')
            ->where('no_hp_offline', $request->nomor_telephone)
            ->whereNull('id_user')
            ->update([
                'id_user' => $user->id
            ]);

        return response()->json(['message' => 'User berhasil didaftarkan'], 201);
    }
}
