<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct() {
        $this->middleware('dev');
    }
    public function index($nama_lengkap){
          // ambil user berdasarkan yang baru saja terdaftar
          $user_baru_terdaftar = User::select('users.*')
          ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
          ->where('users.type', 0)
          ->whereDate('users.created_at', Carbon::today())
          ->where('status_notifikasi_user.status', 'unread')
          ->orderByDesc('users.created_at')->limit(10)
          ->get();
        return view('developers.menu-profile.profile', ['title' => 'Profile | Developer Kamp Sewa', 'name' => $nama_lengkap, 'user_baru_terdaftar' => $user_baru_terdaftar]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'nomor_telephone' => ['required', 'string', 'max:20', 'unique:users,nomor_telephone,' . $user->id],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'email.unique' => 'Email sudah dipakai user lain.',
            'nomor_telephone.unique' => 'Nomor telepon sudah dipakai user lain.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $fotoLama = $user->foto;
        $folderDeveloper = 'assets/image/developers/profiles';

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nomor_telephone' => $validated['nomor_telephone'],
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
        ];

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($folderDeveloper), $filename);

            $data['foto'] = $filename;
        } elseif (empty($fotoLama) || in_array(strtolower(trim((string) $fotoLama)), ['belum di isi', 'belum di isi.', 'belum diisi', 'belum diisi.', 'man.png', 'null', '-'], true)) {
            $data['foto'] = null;
        }

        $user->update($data);

        $this->hapusFotoLama($fotoLama, $folderDeveloper);

        $request->session()->put([
            'nama_lengkap' => $user->name,
            'email' => $user->email,
            'nomor_telephone' => $user->nomor_telephone,
            'tanggal_lahir' => $user->tanggal_lahir,
            'jenis_kelamin' => $user->jenis_kelamin,
            'foto' => $user->foto,
        ]);

        return back()->with('success', 'Profile berhasil diperbarui.');
    }

    private function hapusFotoLama($fotoLama, $folderDeveloper)
    {
        if (empty($fotoLama) || !is_string($fotoLama)) {
            return;
        }

        if (str_starts_with($fotoLama, 'http://') || str_starts_with($fotoLama, 'https://') || str_starts_with($fotoLama, 'storage/')) {
            return;
        }

        $path = public_path($folderDeveloper . '/' . ltrim($fotoLama, '/'));

        if (is_file($path)) {
            @unlink($path);
        }
    }
}
