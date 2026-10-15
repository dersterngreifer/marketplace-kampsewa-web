<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class KelolaPenggunaMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('dev');
    }

    public function index(Request $request)
    {
        $user_baru_terdaftar = User::select('users.*')
            ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
            ->where('users.type', 0)
            ->whereDate('users.created_at', Carbon::today())
            ->where('status_notifikasi_user.status', 'unread')
            ->orderByDesc('users.created_at')
            ->limit(10)
            ->get();

        $get_total_user = User::where('type', 0)->count();

        $cari_customer = $request->query('cari_customer');
        $filter = $request->query('filter');

        $query = DB::table('produk')
            ->rightJoin('users', 'produk.id_user', '=', 'users.id')
            ->where('users.type', 0)
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.nomor_telephone',
                'users.created_at',
                'users.jenis_kelamin',
                'users.foto',
                DB::raw('COUNT(produk.id) as total_product')
            )
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'users.nomor_telephone',
                'users.created_at',
                'users.jenis_kelamin',
                'users.foto'
            );

        if (!empty($cari_customer)) {
            $query->where(function ($query) use ($cari_customer) {
                $query->where('users.name', 'like', '%' . $cari_customer . '%')
                    ->orWhere('users.nomor_telephone', 'like', '%' . $cari_customer . '%')
                    ->orWhere('users.email', 'like', '%' . $cari_customer . '%');
            });
        }

        if ($filter == 'terlama') {
            $query->orderBy('users.created_at', 'asc');
        } elseif ($filter == 'punya_produk') {
            $query->havingRaw('COUNT(produk.id) > 0')
                ->orderBy('users.created_at', 'desc');
        } else {
            $query->orderBy('users.created_at', 'desc');
        }

        $results = $query->paginate(10)->appends($request->query());

        return view('developers.kelola-pengguna')->with([
            'title' => 'Kelola Pengguna | Developer Kamp Sewa',
            'user_baru_terdaftar' => $user_baru_terdaftar,
            'get_total_user' => $get_total_user,
            'data' => $results,
            'cari_customer' => $cari_customer,
            'filter' => $filter
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'number_phone' => ['required', 'string', 'max:20', 'unique:users,nomor_telephone'],
            'password' => ['required', 'string', 'min:6'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
        ], [
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'number_phone.required' => 'Nomor telepon wajib diisi.',
            'number_phone.unique' => 'Nomor telepon sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'photo.max' => 'Ukuran foto maksimal 10 MB.',
        ]);

        DB::beginTransaction();

        try {
            $photoPath = 'Belum Di isi';

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $photoPath = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/image/customers/profile/'), $photoPath);
            }

            $user = new User();
            $user->name = $validated['fullname'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->type = 0;
            $user->nomor_telephone = $validated['number_phone'];
            $user->tanggal_lahir = $validated['date_of_birth'];
            $user->foto = $photoPath;
            $user->jenis_kelamin = $validated['gender'];
            $user->status = null;
            $user->background = 'Belum Di isi';
            $user->time_login = null;
            $user->last_login = null;
            $user->name_store = null;
            $user->save();

            if (Schema::hasTable('status_notifikasi_user')) {
                $dataNotifikasi = [
                    'id_user' => $user->id,
                    'status' => 'unread',
                ];

                if (Schema::hasColumn('status_notifikasi_user', 'created_at')) {
                    $dataNotifikasi['created_at'] = now();
                }

                if (Schema::hasColumn('status_notifikasi_user', 'updated_at')) {
                    $dataNotifikasi['updated_at'] = now();
                }

                DB::table('status_notifikasi_user')->insert($dataNotifikasi);
            }

            DB::commit();

            return redirect()
                ->route('kelola-pengguna.index')
                ->with('success', 'Customer berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan customer: ' . $th->getMessage());
        }
    }

    public function createAdmin()
    {
        return view('developers.tambah-admin')->with([
            'title' => 'Tambah Admin KampSewa | Developer',
        ]);
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'number_phone' => ['required', 'string', 'max:20', 'unique:users,nomor_telephone'],
            'password' => ['required', 'string', 'min:6'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
        ], [
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'number_phone.required' => 'Nomor telepon wajib diisi.',
            'number_phone.unique' => 'Nomor telepon sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'photo.max' => 'Ukuran foto maksimal 10 MB.',
        ]);

        DB::beginTransaction();

        try {
            $photoPath = 'Belum Di isi';

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $photoPath = time() . '_admin_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/image/customers/profile/'), $photoPath);
            }

            $user = new User();
            $user->name = $validated['fullname'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->type = 1; // 1 for Admin
            $user->nomor_telephone = $validated['number_phone'];
            $user->tanggal_lahir = $validated['date_of_birth'];
            $user->foto = $photoPath;
            $user->jenis_kelamin = $validated['gender'];
            $user->status = null;
            $user->background = 'Belum Di isi';
            $user->time_login = null;
            $user->last_login = null;
            $user->name_store = null;
            $user->save();

            DB::commit();

            return redirect()
                ->route('kelola-pengguna.index')
                ->with('success', 'Admin KampSewa berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan Admin: ' . $th->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $user = User::where('type', 0)->findOrFail($id);

        DB::beginTransaction();

        try {
            $this->deleteUserPhoto($user->foto);

            $user->delete();

            DB::commit();

            return redirect()
                ->route('kelola-pengguna.index')
                ->with('success', 'Customer berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()
                ->with('error', 'Gagal menghapus customer: ' . $th->getMessage());
        }
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ], [
            'user_ids.required' => 'Pilih minimal satu customer yang ingin dihapus.',
        ]);

        $users = User::where('type', 0)
            ->whereIn('id', $validated['user_ids'])
            ->get();

        if ($users->isEmpty()) {
            return back()->with('error', 'Tidak ada customer yang valid untuk dihapus.');
        }

        DB::beginTransaction();

        try {
            foreach ($users as $user) {
                $this->deleteUserPhoto($user->foto);
                $user->delete();
            }

            DB::commit();

            return redirect()
                ->route('kelola-pengguna.index')
                ->with('success', $users->count() . ' customer berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()
                ->with('error', 'Gagal menghapus customer terpilih: ' . $th->getMessage());
        }
    }

    private function deleteUserPhoto(?string $photo): void
    {
        if (
            !$photo ||
            in_array(strtolower(trim($photo)), ['belum di isi', 'belum di isi.', 'belum diisi', 'belum diisi.', 'man.png', 'null', '-'], true) ||
            str_starts_with($photo, 'http://') ||
            str_starts_with($photo, 'https://') ||
            str_starts_with($photo, '//')
        ) {
            return;
        }

        $cleanPhoto = ltrim($photo, '/');
        $customerPhotoPath = public_path('assets/image/customers/profile/' . $cleanPhoto);
        if (File::exists($customerPhotoPath)) {
            @File::delete($customerPhotoPath);
        }
    }
}