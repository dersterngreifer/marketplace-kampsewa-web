<?php
// cspell:disable

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Bank;
use App\Models\DetailPenyewaan;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function detailUser($id_user)
    {
        try {
            // Ambil data user lengkap termasuk field KYC & profil toko
            $get_data_user = User::select(
                'id',
                'name',
                'email',
                'nomor_telephone',
                'foto',
                'tanggal_lahir',
                'name_store',
                'type',
                'nomor_identitas',
                'foto_identitas',
                'is_verified',
                'deskripsi_toko',
                'banner_toko',
                'background',
                'jenis_kelamin'
            )
                ->where('id', $id_user)
                ->first();

            // Cek apakah data ada
            if (!$get_data_user) {
                return response()->json([
                    'message' => 'Data tidak ditemukan!',
                ], 404);
            }

            // Tambahkan field penanda apakah user memiliki toko
            $get_data_user->is_toko = !empty($get_data_user->name_store);

            // Kalkulasi Rating Toko dan Jumlah Ulasan (berdasarkan produk milik toko ini)
            $rating_info = \Illuminate\Support\Facades\DB::table('produk')
                ->leftJoin('rating_produk', 'produk.id', '=', 'rating_produk.id_produk')
                ->where('produk.id_user', $id_user)
                ->select(
                    \Illuminate\Support\Facades\DB::raw('IFNULL(ROUND(AVG(rating_produk.rating), 1), 0) as rating_toko'),
                    \Illuminate\Support\Facades\DB::raw('COUNT(rating_produk.id) as total_ulasan_toko')
                )
                ->first();

            $get_data_user->rating_toko = $rating_info ? (float) $rating_info->rating_toko : 0;
            $get_data_user->total_ulasan_toko = $rating_info ? (int) $rating_info->total_ulasan_toko : 0;

            // Tampilkan respons data
            return response()->json([
                'message' => 'success',
                'data_users' => $get_data_user,
            ]);
        } catch (\Exception $e) {
            // Tangani pengecualian
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function pemesananUser($id_user)
    {
        try {
            $user = User::find($id_user);
            if (!$user) {
                return response()->json([
                    'message' => 'Data tidak ditemukan!',
                ], 404);
            }

            $list_pemesanan = Penyewaan::where('id_user', $id_user)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'message' => 'success',
                'pemesanan_user' => $list_pemesanan,
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data pemesanan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function editProfile($id_user, Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'name' => 'required|string|max:50',
                'email' => 'required|email|unique:users,email,' . $id_user,
                'nomor_telephone' => 'required|string|max:13|min:11',
                'tanggal_lahir' => 'required|date',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan',
            ]);

            // Dapatkan user yang akan diperbarui
            $user = User::findOrFail($id_user);

            // Persiapan data yang akan diperbarui
            $update_data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'nomor_telephone' => $request->input('nomor_telephone'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
            ];

            if ($request->has('jenis_kelamin')) {
                $update_data['jenis_kelamin'] = $request->input('jenis_kelamin');
            }

            // Jika ada file foto yang diunggah
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $destinationPath = public_path('assets/image/customers/profile');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $filename);

                // Simpan path relatif ke database
                $foto_url = $filename;
                $update_data['foto'] = $foto_url;
            }

            // Update data user
            $user->update($update_data);

            // Tanggapan sukses
            return response()->json([
                'message' => 'Data berhasil diperbarui',
                'data' => $user,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Tanggapan jika user tidak ditemukan
            return response()->json([
                'message' => 'User tidak ditemukan',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tanggapan jika validasi gagal
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Tanggapan jika terjadi kesalahan lainnya
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function tambahAlamatUser(Request $request)
    {
        try {
            $request->validate([
                'id_user' => 'required|integer',
                'longitude' => 'required|string',
                'latitude' => 'required|string',
                'detail_lainnya' => 'nullable|string',
                'type' => 'nullable|integer',
            ]);

            $table_alamat = new Alamat();
            $table_alamat->id_user = $request->input('id_user');
            $table_alamat->longitude = $request->input('longitude');
            $table_alamat->latitude = $request->input('latitude');
            if ($request->input('detail_lainnya') == '' || $request->input('detail_lainnya') == null) {
                $table_alamat->detail_lainnya = 'Belum di isi.';
            } else {
                $table_alamat->detail_lainnya = $request->input('detail_lainnya');
            }
            $table_alamat->type = $request->input('type');
            $table_alamat->save();

            return response()->json([
                'message' => 'Alamat berhasil disimpan',
                'data disimpan' => $table_alamat,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menambah data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function listAlamatUser($id_user)
    {
        try {
            $get_list_alamat = Alamat::join('users', 'users.id', '=', 'alamat.id_user')
                ->select('alamat.*', 'users.name', 'users.nomor_telephone')
                ->where(function ($query) {
                    $query->where('alamat.type', 0)
                        ->orWhere('alamat.type', 2);
                })
                ->where('alamat.id_user', $id_user)
                ->distinct()
                ->get();
            return response()->json([
                'message' => 'success',
                'alamat_user' => $get_list_alamat,
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menampilkan data alamat.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function detailAlamatUser($id_alamat)
    {
        try {
            $get_detail_alamat = Alamat::where('id', $id_alamat)->first();
            if ($get_detail_alamat) {
                return response()->json([
                    'message' => 'success',
                    'detail_alamat' => $get_detail_alamat,
                ], 200);
            }
            return response()->json([
                'message' => 'Terjadi kesalahan saat menampilkan data',
                'detail_alamat' => $get_detail_alamat,
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menampilkan detail data alamat.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateAlamatUser($id_alamat, Request $request)
    {
        try {
            $request->validate([
                'longitude' => 'required|string',
                'latitude' => 'required|string',
                'detail_lainnya' => 'nullable|string',
                'type' => 'nullable|integer',
            ]);

            $table_alamat = Alamat::where('id', $id_alamat)->update([
                'longitude' => $request->input('longitude'),
                'latitude' => $request->input('latitude'),
                'detail_lainnya' => $request->input('detail_lainnya'),
                'type' => $request->input('type'),
            ]);

            if ($table_alamat) {
                $alamat = Alamat::find($id_alamat);
                return response()->json([
                    'message' => 'success update',
                    'data_update' => $alamat
                ], 200);
            }

            return response()->json([
                'message' => 'Data tidak ada.',
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat update data alamat.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updatePasswordUser($id_user)
    {
        try {
            request()->validate([
                'password' => 'required|string|max:20|min:8',
            ]);
            $table_user = User::where('id', $id_user)->update([
                'password' => bcrypt(request()->input('password')),
            ]);
            if ($table_user) {
                $user = User::find($id_user);
                return response()->json([
                    'message' => 'success update password',
                    'data_update' => $user,
                ], 200);
            }
            return response()->json([
                'message' => 'data tidak ada atau terjadi kesalahan saat update data',
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat update password.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function tambahBank()
    {
        try {
            request()->validate([
                'id_user' => 'required|integer',
                'rekening' => 'required|string',
                'bank' => 'required|string',
            ]);

            $bank = new Bank();
            $bank->id_user = request()->id_user;
            $bank->rekening = request()->rekening;
            $bank->bank = strtoupper(request()->bank);
            $bank->save();

            $get_data = Bank::where('id_user', request()->id_user)->first();

            return response()->json([
                'message' => 'success',
                'data_result' => $get_data,
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat update password.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateBank(Request $request, $id_bank)
    {
        try {
            $request->validate([
                'rekening' => 'required|string',
                'bank' => 'required|string',
            ]);

            $bank = \App\Models\Bank::find($id_bank);
            if (!$bank) {
                return response()->json([
                    'message' => 'Data bank tidak ditemukan.',
                ], 404);
            }

            $bank->rekening = $request->rekening;
            $bank->bank = strtoupper($request->bank);
            $bank->save();

            return response()->json([
                'message' => 'Berhasil memperbarui metode pembayaran',
                'data_result' => $bank,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat update bank.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteBank($id_bank)
    {
        try {
            $bank = \App\Models\Bank::find($id_bank);
            if (!$bank) {
                return response()->json([
                    'message' => 'Data bank tidak ditemukan.',
                ], 404);
            }

            $bank->delete();

            return response()->json([
                'message' => 'Berhasil menghapus metode pembayaran',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus bank.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteAlamatUser($id_alamat)
    {
        try {
            $get_data = Alamat::where('id', $id_alamat)->first();
            if (!$get_data) {
                return response()->json([
                    'message' => 'Alamat tidak ditemukan',
                ], 404);
            }
            $get_data->delete();

            return response()->json([
                'message' => 'success delete',
            ], 200);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat update password.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function inputKYC($id_user)
    {
        try {
            request()->validate([
                'nomor_identitas' => 'required|string|digits:16',
                'foto_identitas' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $user = User::find($id_user);
            if (!$user) {
                return response()->json([
                    'message' => 'User tidak ditemukan',
                ], 404);
            }

            $update_data = [
                'nomor_identitas' => request()->nomor_identitas,
                'is_verified' => true,
            ];

            if (request()->hasFile('foto_identitas')) {
                $file = request()->file('foto_identitas');
                $destinationPath = public_path('assets/image/customers/identitas');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $filename);
                $update_data['foto_identitas'] = $filename;
            }

            $user->update($update_data);

            return response()->json([
                'message' => 'success',
                'data_user' => $user,
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat input identitas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function tambahStore($id_user)
    {
        try {
            request()->validate([
                'name_store' => 'required|string',
                'deskripsi_toko' => 'nullable|string',
                'banner_toko' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'longitude' => 'required|string',
                'latitude' => 'required|string',
                'detail_lainnya' => 'nullable|string|max:255',
            ]);
            $userCheck = User::find($id_user);
            if (!$userCheck) {
                return response()->json([
                    'message' => 'User tidak ada',
                ], 404);
            }

            if (empty($userCheck->nomor_identitas)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Harap lengkapi verifikasi identitas (KTP) Anda sebelum membuka layanan penyewaan.'
                ], 403);
            }

            $users = User::where('id', $id_user);
            $update_store = ['name_store' => request()->name_store];
            if (request()->has('deskripsi_toko') && request()->deskripsi_toko != null) {
                $update_store['deskripsi_toko'] = request()->deskripsi_toko;
            }
            if (request()->hasFile('banner_toko')) {
                $file = request()->file('banner_toko');
                $destinationPath = public_path('assets/image/customers/banner');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $filename);
                $update_store['banner_toko'] = $filename;
            }
            $users->update($update_store);

            $alamat = new Alamat();
            $alamat->id_user = $id_user;
            $alamat->longitude = request()->longitude;
            $alamat->latitude = request()->latitude;
            if (request()->detail_lainnya == null || request()->detail_lainnya == '') {
                $alamat->detail_lainnya = 'Tidak di isi';
            }
            $alamat->detail_lainnya = request()->detail_lainnya;
            $alamat->type = 1;
            $alamat->save();
            $get_alamat = Alamat::where('id_user', $id_user)->orderBy('created_at', 'desc');
            return response()->json([
                'message' => 'success',
                'users' => $users->first(),
                'alamat' => $get_alamat->first(),
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat update password.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        // Menghapus token saat ini yang digunakan untuk otentikasi
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

     public function verifyKtp(Request $request)
        {
            // 1. Validasi berkas upload
            $request->validate([
                'foto_identitas' => 'required|image|mimes:jpeg,png,jpg|max:5120', // maks 5MB
            ]);

            $file = $request->file('foto_identitas');

            // 2. Konversi file gambar ke format base64
            $imageBase64 = base64_encode(file_get_contents($file->getRealPath()));

            $apiKey  = env('ROBOFLOW_API_KEY');
            $modelId = env('ROBOFLOW_MODEL_ID');
            $version = env('ROBOFLOW_VERSION');

            // URL endpoint resmi Roboflow
            $url = "https://detect.roboflow.com/{$modelId}/{$version}?api_key={$apiKey}";

            try {
                // 3. Tembak API Roboflow langsung dari Laravel
                $response = Http::withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])->timeout(20)->send('POST', $url, [
                    'body' => $imageBase64,
                ]);

                if (!$response->successful()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal menghubungi server AI Roboflow.',
                    ], 500);
                }

                $result = $response->json();
                $predictions = $result['predictions'] ?? [];

                // 4. Logika Validasi: Cek apakah ada objek KTP yang terdeteksi
                if (empty($predictions)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Foto ditolak: Objek KTP tidak ditemukan. Pastikan Anda mengunggah foto KTP yang
  jelas.',
                    ], 422);
                }

                // Ambil deteksi dengan tingkat keyakinan tertinggi
                $bestMatch = $predictions[0];
                $confidence = $bestMatch['confidence'] ?? 0;
                $class = strtolower($bestMatch['class'] ?? '');

                // 5. Pastikan nama class-nya adalah "ktpdepan"
                if ($class !== 'ktpdepan') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Foto ditolak: Pastikan Anda memfoto bagian depan KTP dengan jelas.',
                    ], 422);
                }

                // 6. Cek ambang batas keyakinan (KITA NAIKKAN JADI 85% ATAU 0.85)
                if ($confidence < 0.85) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Foto KTP kurang jelas (Akurasi: ' . round($confidence * 100) . '%). Harap foto
  ulang dari jarak lebih dekat.',
                    ], 422);
                }

                // BERHASIL!
                // Catatan: Tidak perlu menggunakan $file->move() di sini!
                // Aplikasi mobile (Flutter) akan melanjutkan alur memanggil endpoint 'input-kyc'
                // untuk menyimpan file aslinya dan mencatat di database.

                return response()->json([
                    'success' => true,
                    'confidence' => round($confidence * 100, 1) . '%',
                    'message' => 'KTP berhasil diverifikasi dan valid!',
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                ], 500);
            }
        }
}
