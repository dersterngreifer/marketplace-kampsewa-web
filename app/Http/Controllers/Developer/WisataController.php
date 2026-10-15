<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class WisataController extends Controller
{
    public function __construct()
    {
        $this->middleware('dev');
    }

    /**
     * Helper to handle image file upload or image URL
     */
    protected function handleImageUpload(Request $request, ?string $currentImage = null): ?string
    {
        $destinationPath = public_path('assets/image/rekomendasi-wisata');

        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true, true);
        }

        // 1. Opsi Upload File Foto Langsung
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = 'wisata_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

            // Remove old uploaded local file if exists
            if ($currentImage && !Str::startsWith($currentImage, ['http://', 'https://', '//'])) {
                $oldPath1 = $destinationPath . '/' . $currentImage;
                $oldPath2 = public_path('assets/image/articles/' . $currentImage);
                if (File::exists($oldPath1)) {
                    @File::delete($oldPath1);
                }
                if (File::exists($oldPath2)) {
                    @File::delete($oldPath2);
                }
            }

            $file->move($destinationPath, $filename);
            return $filename;
        }

        // 2. Opsi Input Link URL Foto
        if ($request->filled('gambar_url')) {
            $url = trim($request->input('gambar_url'));
            if ($currentImage && !Str::startsWith($currentImage, ['http://', 'https://', '//'])) {
                $oldPath1 = $destinationPath . '/' . $currentImage;
                $oldPath2 = public_path('assets/image/articles/' . $currentImage);
                if (File::exists($oldPath1)) {
                    @File::delete($oldPath1);
                }
                if (File::exists($oldPath2)) {
                    @File::delete($oldPath2);
                }
            }
            return $url;
        }

        return $currentImage;
    }

    /**
     * Display listing of Rekomendasi Wisata
     */
    public function index(Request $request)
    {
        // 1. Notifikasi user baru terdaftar untuk navbar
        $user_baru_terdaftar = User::select('users.*')
            ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
            ->where('users.type', 0)
            ->whereDate('users.created_at', Carbon::today())
            ->where('status_notifikasi_user.status', 'unread')
            ->orderByDesc('users.created_at')
            ->limit(10)
            ->get();

        // 2. Statistik Ringkasan Wisata
        $total_wisata = Article::wisata()->count();
        $total_views = Article::wisata()->sum('views_count');
        $total_published = Article::wisata()->where('status', 'published')->count();
        $total_draft = Article::wisata()->where('status', 'draft')->count();
        $wisata_terpopuler = Article::wisata()->orderByDesc('views_count')->first();

        // 3. Query dengan Filtering & Sorting
        $query = Article::wisata()->with('author');

        // Search
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhere('deskripsi_singkat', 'like', "%{$search}%")
                    ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status') && in_array($request->status, ['published', 'draft', 'archived'])) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'terbaru');
        switch ($sortBy) {
            case 'terlama':
                $query->orderBy('created_at', 'asc');
                break;
            case 'terpopuler':
                $query->orderByDesc('views_count');
                break;
            case 'a_z':
                $query->orderBy('judul', 'asc');
                break;
            case 'z_a':
                $query->orderBy('judul', 'desc');
                break;
            case 'terbaru':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $perPage = 10;
        $wisata_list = $query->paginate($perPage)->withQueryString();

        $title = 'Rekomendasi Wisata | Developer Kamp Sewa';

        return view('developers.wisata', compact(
            'title',
            'user_baru_terdaftar',
            'wisata_list',
            'total_wisata',
            'total_views',
            'total_published',
            'total_draft',
            'wisata_terpopuler'
        ));
    }

    /**
     * Store new Rekomendasi Wisata
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'             => 'required|string|max:255',
            'lokasi'            => 'required|string|max:255',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'konten'            => 'required|string',
            'sumber_nama'       => 'nullable|string|max:100',
            'sumber_url'        => 'nullable|url|max:500',
            'status'            => 'required|in:published,draft,archived',
            'gambar'            => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'gambar_url'        => 'nullable|string|max:500',
        ]);

        $authorId = auth()->id() ?? User::where('type', 1)->value('id');

        $gambar = $this->handleImageUpload($request);

        $article = Article::create([
            'tipe'              => 'wisata',
            'judul'             => $validated['judul'],
            'slug'              => Str::slug($validated['judul']) . '-' . time(),
            'gambar'            => $gambar,
            'deskripsi_singkat' => $validated['deskripsi_singkat'] ?? Str::limit(strip_tags($validated['konten']), 150),
            'konten'            => $validated['konten'],
            'lokasi'            => $validated['lokasi'],
            'sumber_nama'       => $validated['sumber_nama'] ?? 'KampSewa Travel',
            'sumber_url'        => $validated['sumber_url'],
            'author_id'         => $authorId,
            'status'            => $validated['status'],
            'views_count'       => 0,
        ]);

        Alert::success('Berhasil Ditambahkan', 'Destinasi wisata "' . $article->judul . '" berhasil ditambahkan.');
        return redirect()->route('developer.wisata.index')->with('success', 'Destinasi wisata berhasil ditambahkan!');
    }

    /**
     * Detail Wisata (AJAX or direct)
     */
    public function show($id)
    {
        $wisata = Article::wisata()->with('author')->findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'status'  => 'success',
                'data'    => $wisata->toApiArray(),
                'raw'     => $wisata,
            ]);
        }

        return redirect()->route('developer.wisata.index');
    }

    /**
     * Update existing Rekomendasi Wisata
     */
    public function update(Request $request, $id)
    {
        $wisata = Article::wisata()->findOrFail($id);

        $validated = $request->validate([
            'judul'             => 'required|string|max:255',
            'lokasi'            => 'required|string|max:255',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'konten'            => 'required|string',
            'sumber_nama'       => 'nullable|string|max:100',
            'sumber_url'        => 'nullable|url|max:500',
            'status'            => 'required|in:published,draft,archived',
            'gambar'            => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'gambar_url'        => 'nullable|string|max:500',
        ]);

        $gambar = $this->handleImageUpload($request, $wisata->gambar);

        $wisata->update([
            'judul'             => $validated['judul'],
            'slug'              => Str::slug($validated['judul']) . '-' . $wisata->id,
            'gambar'            => $gambar,
            'deskripsi_singkat' => $validated['deskripsi_singkat'] ?? Str::limit(strip_tags($validated['konten']), 150),
            'konten'            => $validated['konten'],
            'lokasi'            => $validated['lokasi'],
            'sumber_nama'       => $validated['sumber_nama'] ?? $wisata->sumber_nama,
            'sumber_url'        => $validated['sumber_url'],
            'status'            => $validated['status'],
        ]);

        Alert::success('Berhasil Diperbarui', 'Data destinasi wisata berhasil diperbarui.');
        return redirect()->route('developer.wisata.index')->with('success', 'Data destinasi wisata berhasil diperbarui!');
    }

    /**
     * Delete Rekomendasi Wisata
     */
    public function destroy($id)
    {
        $wisata = Article::wisata()->findOrFail($id);

        // Delete uploaded file if stored locally
        if ($wisata->gambar && !Str::startsWith($wisata->gambar, ['http://', 'https://', '//'])) {
            $localPath1 = public_path('assets/image/rekomendasi-wisata/' . $wisata->gambar);
            $localPath2 = public_path('assets/image/articles/' . $wisata->gambar);
            if (File::exists($localPath1)) {
                @File::delete($localPath1);
            }
            if (File::exists($localPath2)) {
                @File::delete($localPath2);
            }
        }

        $judul = $wisata->judul;
        $wisata->delete();

        Alert::success('Berhasil Dihapus', 'Destinasi wisata "' . $judul . '" telah berhasil dihapus.');
        return redirect()->route('developer.wisata.index')->with('success', 'Destinasi wisata berhasil dihapus!');
    }
}
