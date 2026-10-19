<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class NotificationController extends Controller
{
    /**
     * Mark a specific notification as read and redirect to its destination.
     */
    public function baca($id)
    {
        $userId = session('id_user') ?? (auth()->check() ? auth()->id() : null);

        $notif = CustomerNotification::where('id', $id)
            ->when($userId, function ($q) use ($userId) {
                $q->where('id_user', $userId);
            })
            ->first();

        if ($notif) {
            $notif->markAsRead();
            $targetUrl = $notif->url;
        } else {
            $targetUrl = null;
        }

        if (empty($targetUrl)) {
            $encUserId = $userId ? Crypt::encrypt($userId) : '';
            return redirect()->route('dashboard-cust', ['id_user' => $encUserId]);
        }

        // Pastikan redirect selalu berada di host yang sedang diakses (relative path)
        $parsed = parse_url($targetUrl);
        $path = $parsed['path'] ?? '/';
        if (!empty($parsed['query'])) {
            $path .= '?' . $parsed['query'];
        }

        return redirect($path);
    }

    /**
     * Mark all notifications as read for current user.
     */
    public function markAllRead(Request $request)
    {
        $userId = session('id_user') ?? (auth()->check() ? auth()->id() : null);
        if ($userId) {
            CustomerNotification::where('id_user', $userId)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Semua notifikasi telah ditandai dibaca']);
        }

        return back();
    }
}
