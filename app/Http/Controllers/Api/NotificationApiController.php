<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerNotification;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    /**
     * GET /api/notifikasi
     * Mengambil daftar notifikasi untuk pengguna yang sedang login di mobile app.
     * Query params:
     * - limit (default 20)
     * - unread_only (boolean, default false)
     */
    public function index(Request $request)
    {
        $userId = auth('sanctum')->id();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $limit = (int) $request->query('limit', 20);
        $unreadOnly = filter_var($request->query('unread_only', false), FILTER_VALIDATE_BOOLEAN);

        $query = CustomerNotification::where('id_user', $userId)
            ->orderBy('is_read', 'asc')
            ->orderByDesc('created_at');

        if ($unreadOnly) {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate($limit);
        $unreadCount = CustomerNotification::where('id_user', $userId)->where('is_read', false)->count();

        return response()->json([
            'success'      => true,
            'message'      => 'Daftar notifikasi berhasil diambil.',
            'unread_count' => $unreadCount,
            'data'         => $notifications->items(),
            'pagination'   => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'per_page'     => $notifications->perPage(),
                'total'        => $notifications->total(),
            ],
        ], 200);
    }

    /**
     * POST /api/notifikasi/{id}/read
     * Menandai satu notifikasi sebagai telah dibaca.
     */
    public function markRead(Request $request, $id)
    {
        $userId = auth('sanctum')->id();
        $notif = CustomerNotification::where('id', $id)
            ->where('id_user', $userId)
            ->first();

        if (!$notif) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);
        }

        $notif->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil ditandai sebagai dibaca.',
            'data'    => $notif,
        ], 200);
    }

    /**
     * POST /api/notifikasi/mark-all-read
     * Menandai semua notifikasi user sebagai telah dibaca.
     */
    public function markAllRead(Request $request)
    {
        $userId = auth('sanctum')->id();

        CustomerNotification::where('id_user', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil ditandai sebagai dibaca.',
        ], 200);
    }
}
