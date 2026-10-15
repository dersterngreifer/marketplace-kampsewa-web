<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ArticleAdminController extends Controller
{
    /**
     * Helper to handle image upload
     */
    protected function handleImageUpload(Request $request, string $tipe = 'wisata', ?string $currentImage = null): ?string
    {
        $folder = $tipe === 'wisata' ? 'rekomendasi-wisata' : 'berita-terkini';
        $destinationPath = public_path('assets/image/' . $folder);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = $tipe . '_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            // Remove old local image if exists
            if ($currentImage && !Str::startsWith($currentImage, ['http://', 'https://', '//'])) {
                if (File::exists($destinationPath . '/' . $currentImage)) {
                    File::delete($destinationPath . '/' . $currentImage);
                }
                $legacyPath = public_path('assets/image/articles/' . $currentImage);
                if (File::exists($legacyPath)) {
                    File::delete($legacyPath);
                }
            }

            $file->move($destinationPath, $filename);
            return $filename;
        }

        // If provided as a URL string in the request body
        if ($request->filled('gambar_url') || ($request->filled('gambar') && is_string($request->input('gambar')))) {
            $url = $request->input('gambar_url') ?? $request->input('gambar');
            if ($currentImage && !Str::startsWith($currentImage, ['http://', 'https://', '//'])) {
                if (File::exists($destinationPath . '/' . $currentImage)) {
                    File::delete($destinationPath . '/' . $currentImage);
                }
                $legacyPath = public_path('assets/image/articles/' . $currentImage);
                if (File::exists($legacyPath)) {
                    File::delete($legacyPath);
                }
            }
            return $url;
        }

        return $currentImage;
    }

    /**
     * POST /api/admin/wisata
     */
    public function storeWisata(Request $request)
    {
        $validated = $request->validate([
            'judul'             => 'required|string|max:255',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'konten'            => 'required|string',
            'lokasi'            => 'required|string|max:255',
            'sumber_url'        => 'nullable|string|max:500',
            'sumber_nama'       => 'nullable|string|max:100',
            'status'            => 'nullable|in:published,draft,archived',
        ]);

        $admin = auth()->user() ?? User::where('type', 1)->first();
        $gambar = $this->handleImageUpload($request, 'wisata');

        $slugBase = Str::slug($validated['judul']) ?: 'wisata';
        $slug = $slugBase . '-' . time();

        $article = Article::create([
            'tipe'              => 'wisata',
            'judul'             => $validated['judul'],
            'slug'              => $slug,
            'gambar'            => $gambar,
            'deskripsi_singkat' => $validated['deskripsi_singkat'] ?? Str::limit(strip_tags($validated['konten']), 200),
            'konten'            => $validated['konten'],
            'lokasi'            => $validated['lokasi'],
            'sumber_url'        => $validated['sumber_url'] ?? null,
            'sumber_nama'       => $validated['sumber_nama'] ?? 'Admin KampSewa',
            'author_id'         => $admin ? $admin->id : null,
            'status'            => $validated['status'] ?? 'published',
            'views_count'       => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rekomendasi wisata baru berhasil ditambahkan',
            'data'    => $article->toApiArray(true),
        ], 201);
    }

    /**
     * POST/PUT /api/admin/wisata/{id}
     */
    public function updateWisata(Request $request, $id)
    {
        $article = Article::wisata()->findOrFail($id);

        $validated = $request->validate([
            'judul'             => 'sometimes|required|string|max:255',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'konten'            => 'sometimes|required|string',
            'lokasi'            => 'nullable|string|max:255',
            'sumber_url'        => 'nullable|string|max:500',
            'sumber_nama'       => 'nullable|string|max:100',
            'status'            => 'nullable|in:published,draft,archived',
        ]);

        if ($request->hasFile('gambar') || $request->filled('gambar') || $request->filled('gambar_url')) {
            $validated['gambar'] = $this->handleImageUpload($request, 'wisata', $article->gambar);
        }

        if (isset($validated['judul']) && $validated['judul'] !== $article->judul) {
            $validated['slug'] = Str::slug($validated['judul']) . '-' . $article->id;
        }

        $article->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data rekomendasi wisata berhasil diperbarui',
            'data'    => $article->fresh()->toApiArray(true),
        ]);
    }

    /**
     * DELETE /api/admin/wisata/{id}
     */
    public function destroyWisata($id)
    {
        $article = Article::wisata()->findOrFail($id);
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rekomendasi wisata berhasil dihapus',
            'data'    => null,
        ]);
    }

    /**
     * POST /api/admin/berita
     */
    public function storeBerita(Request $request)
    {
        $validated = $request->validate([
            'judul'             => 'required|string|max:255',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'konten'            => 'required|string',
            'sumber_url'        => 'nullable|string|max:500',
            'sumber_nama'       => 'nullable|string|max:100',
            'status'            => 'nullable|in:published,draft,archived',
        ]);

        $admin = auth()->user() ?? User::where('type', 1)->first();
        $gambar = $this->handleImageUpload($request, 'berita');

        $slugBase = Str::slug($validated['judul']) ?: 'berita';
        $slug = $slugBase . '-berita-' . time();

        $article = Article::create([
            'tipe'              => 'berita',
            'judul'             => $validated['judul'],
            'slug'              => $slug,
            'gambar'            => $gambar,
            'deskripsi_singkat' => $validated['deskripsi_singkat'] ?? Str::limit(strip_tags($validated['konten']), 200),
            'konten'            => $validated['konten'],
            'lokasi'            => null,
            'sumber_url'        => $validated['sumber_url'] ?? null,
            'sumber_nama'       => $validated['sumber_nama'] ?? 'Tim Editorial KampSewa',
            'author_id'         => $admin ? $admin->id : null,
            'status'            => $validated['status'] ?? 'published',
            'views_count'       => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berita baru berhasil ditambahkan',
            'data'    => $article->toApiArray(true),
        ], 201);
    }

    /**
     * POST/PUT /api/admin/berita/{id}
     */
    public function updateBerita(Request $request, $id)
    {
        $article = Article::berita()->findOrFail($id);

        $validated = $request->validate([
            'judul'             => 'sometimes|required|string|max:255',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'konten'            => 'sometimes|required|string',
            'sumber_url'        => 'nullable|string|max:500',
            'sumber_nama'       => 'nullable|string|max:100',
            'status'            => 'nullable|in:published,draft,archived',
        ]);

        if ($request->hasFile('gambar') || $request->filled('gambar') || $request->filled('gambar_url')) {
            $validated['gambar'] = $this->handleImageUpload($request, 'berita', $article->gambar);
        }

        if (isset($validated['judul']) && $validated['judul'] !== $article->judul) {
            $validated['slug'] = Str::slug($validated['judul']) . '-berita-' . $article->id;
        }

        $article->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berita berhasil diperbarui',
            'data'    => $article->fresh()->toApiArray(true),
        ]);
    }

    /**
     * DELETE /api/admin/berita/{id}
     */
    public function destroyBerita($id)
    {
        $article = Article::berita()->findOrFail($id);
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dihapus',
            'data'    => null,
        ]);
    }
}
