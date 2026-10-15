<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * GET /api/berita
     * Menampilkan daftar berita terkini seputar dunia pendakian & outdoor dengan dukungan:
     * - Sorting: terbaru, terlama, terpopuler, a_z, z_a
     * - Filter Waktu: semua, hari_ini, minggu_ini, bulan_ini, tahun_ini
     * - Pencarian bebas (search): judul, deskripsi, konten
     * - Limit (khusus dashboard preview): limit=5 atau limit=6
     * - Pagination: page & per_page
     */
    public function index(Request $request)
    {
        $query = Article::with('author')->berita()->published();

        // 1. Search Filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi_singkat', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%")
                  ->orWhere('sumber_nama', 'like', "%{$search}%");
            });
        }

        // 2. Filter Waktu (Time Range Filter)
        $filterWaktu = strtolower($request->input('filter_waktu', 'semua'));
        switch ($filterWaktu) {
            case 'hari_ini':
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'minggu_ini':
            case 'this_week':
                $query->where('created_at', '>=', Carbon::now()->startOfWeek());
                break;
            case 'bulan_ini':
            case 'this_month':
                $query->where('created_at', '>=', Carbon::now()->startOfMonth());
                break;
            case 'tahun_ini':
            case 'this_year':
                $query->where('created_at', '>=', Carbon::now()->startOfYear());
                break;
            case 'semua':
            default:
                break;
        }

        // 3. Sorting (Pengurutan)
        $sortBy = strtolower($request->input('sort_by', 'terbaru'));
        switch ($sortBy) {
            case 'terlama':
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'terpopuler':
            case 'popular':
                $query->orderBy('views_count', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'a_z':
            case 'asc':
                $query->orderBy('judul', 'asc');
                break;
            case 'z_a':
            case 'desc':
                $query->orderBy('judul', 'desc');
                break;
            case 'terbaru':
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // 4. Execution: Limit Mode (untuk Dashboard Preview) vs Paginated Mode
        if ($request->filled('limit')) {
            $limit = max(1, (int) $request->input('limit'));
            $total = (clone $query)->count();
            $items = $query->limit($limit)->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar berita terkini berhasil diambil',
                'data'    => $items->map(fn($item) => $item->toApiArray(false)),
                'pagination' => [
                    'current_page' => 1,
                    'per_page'     => $limit,
                    'total'        => $total,
                    'last_page'    => (int) ceil($total / $limit),
                ],
            ]);
        }

        // Paginated Mode (Default per_page: 10)
        $perPage = max(1, (int) $request->input('per_page', 10));
        $paginator = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar berita terkini berhasil diambil',
            'data'    => collect($paginator->items())->map(fn($item) => $item->toApiArray(false)),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/berita/{id}
     * Menampilkan detail artikel berita lengkap dan otomatis menambahkan views_count (+1)
     */
    public function show($id)
    {
        $article = Article::with('author')
            ->berita()
            ->published()
            ->where(function ($q) use ($id) {
                if (is_numeric($id)) {
                    $q->where('id', (int) $id)->orWhere('slug', $id);
                } else {
                    $q->where('slug', $id);
                }
            })
            ->first();

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        // Tambahkan jumlah tayangan (views_count + 1)
        $article->increment('views_count');

        return response()->json([
            'success' => true,
            'message' => 'Detail berita berhasil diambil',
            'data'    => $article->toApiArray(true), // Sertakan konten lengkap
        ]);
    }
}
