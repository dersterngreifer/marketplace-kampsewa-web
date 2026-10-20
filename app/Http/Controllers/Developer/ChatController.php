<?php

namespace App\Http\Controllers\Developer;

use App\Helpers\PhotoHelper;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\CustomerNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    private function getCurrentAdminId(): int
    {
        return (int) (auth()->id() ?? session('id_user'));
    }

    private function formatUserPayload(User $user): array
    {
        $avatar = PhotoHelper::getUserAvatarData($user);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'name_store' => $user->name_store ?: null,
            'email' => $user->email ?: '-',
            'nomor_telephone' => $user->nomor_telephone ?: '-',
            'type' => (int) $user->type,
            'role_label' => !empty($user->name_store)
                ? 'Mitra Toko: ' . $user->name_store
                : ((int) $user->type === 1 ? 'Admin KampSewa' : 'Customer / Mitra'),
            'status' => $user->status ?: 'Aktif',
            'has_photo' => $avatar['has_photo'],
            'avatar_url' => $avatar['avatar_url'],
            'initials' => $avatar['initials'],
            'gradient' => $avatar['gradient'],
        ];
    }

    private function formatDateLabel(Carbon $date): string
    {
        if ($date->isToday()) {
            return 'Hari Ini';
        }
        if ($date->isYesterday()) {
            return 'Kemarin';
        }
        return $date->translatedFormat('d M Y');
    }

    private function formatRoomTime(?Carbon $date): string
    {
        if (!$date) {
            return '';
        }
        if ($date->isToday()) {
            return $date->format('H:i');
        }
        if ($date->isYesterday()) {
            return 'Kemarin';
        }
        return $date->format('d/m/y');
    }

    private function formatMessagePayload(ChatMessage $msg, int $currentAdminId): array
    {
        $createdAt = $msg->created_at ? Carbon::parse($msg->created_at) : now();

        return [
            'id' => $msg->id,
            'room_id' => $msg->room_id,
            'sender_id' => (int) $msg->sender_id,
            'receiver_id' => (int) $msg->receiver_id,
            'is_mine' => (int) $msg->sender_id === $currentAdminId,
            'message_text' => $msg->message_text,
            'is_read' => (bool) $msg->is_read,
            'time' => $createdAt->format('H:i'),
            'date_key' => $createdAt->format('Y-m-d'),
            'date_label' => $this->formatDateLabel($createdAt),
            'full_datetime' => $createdAt->translatedFormat('d M Y, H:i'),
        ];
    }

    private function getMitraBaseQuery()
    {
        // Khusus users customer mitra yang menyewakan produk (memiliki produk di tabel produk)
        return User::where('type', 0)
            ->whereHas('produk');
    }

    public function index(Request $request)
    {
        $adminId = $this->getCurrentAdminId();

        // Data notifikasi user baru untuk navbar1 developer
        $user_baru_terdaftar = User::join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
            ->where('users.type', 0)
            ->where('status_notifikasi_user.status', 'unread')
            ->select('users.*')
            ->get();

        $mitraQuery = $this->getMitraBaseQuery();
        $totalMitrasCount = (clone $mitraQuery)->count();

        // Ambil data awal 50 mitra
        $initialLimit = 50;
        $mitras = (clone $mitraQuery)
            ->orderByRaw('CASE WHEN name_store IS NOT NULL AND name_store != "" THEN 0 ELSE 1 END')
            ->orderBy('name', 'asc')
            ->limit($initialLimit)
            ->get()
            ->map(function ($mitra) {
                return $this->formatUserPayload($mitra);
            });

        return view('developers.chat')->with([
            'title' => 'Live Chat Mitra | Developer Kamp Sewa',
            'user_baru_terdaftar' => $user_baru_terdaftar,
            'admin_id' => $adminId,
            'mitras' => $mitras,
            'total_mitras_count' => $totalMitrasCount,
            'initial_limit' => $initialLimit,
            'has_more_mitras' => $totalMitrasCount > $initialLimit,
            'remaining_mitras' => max(0, $totalMitrasCount - $initialLimit),
            'selected_partner_id' => $request->query('partner_id'),
        ]);
    }

    public function searchMitras(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $offset = max(0, (int) $request->query('offset', 0));
        $limit = max(1, min(100, (int) $request->query('limit', 50)));

        $query = $this->getMitraBaseQuery();

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('name_store', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nomor_telephone', 'like', "%{$q}%");
            });
        }

        $total = (clone $query)->count();

        $mitras = (clone $query)
            ->orderByRaw('CASE WHEN name_store IS NOT NULL AND name_store != "" THEN 0 ELSE 1 END')
            ->orderBy('name', 'asc')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function ($mitra) {
                return $this->formatUserPayload($mitra);
            });

        $loadedCount = $offset + $mitras->count();
        $hasMore = $loadedCount < $total;
        $remaining = max(0, $total - $loadedCount);

        return response()->json([
            'success' => true,
            'mitras' => $mitras,
            'total' => $total,
            'offset' => $loadedCount,
            'has_more' => $hasMore,
            'remaining' => $remaining,
        ]);
    }

    public function getRooms(Request $request)
    {
        $adminId = $this->getCurrentAdminId();

        // Hanya ambil room chat yang menghubungkan ADMIN ini dengan customer/mitra (type = 0)
        $rooms = ChatRoom::with(['user1', 'user2'])
            ->where(function ($q) use ($adminId) {
                $q->where(function ($sub) use ($adminId) {
                    $sub->where('user1_id', $adminId)
                        ->whereHas('user2', function ($u) {
                            $u->where('type', 0);
                        });
                })->orWhere(function ($sub) use ($adminId) {
                    $sub->where('user2_id', $adminId)
                        ->whereHas('user1', function ($u) {
                            $u->where('type', 0);
                        });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $formattedRooms = [];

        foreach ($rooms as $room) {
            $otherUser = (int) $room->user1_id === $adminId ? $room->user2 : $room->user1;
            if (!$otherUser || (int) $otherUser->type !== 0) {
                continue;
            }

            $unreadCount = ChatMessage::where('room_id', $room->id)
                ->where('receiver_id', $adminId)
                ->where('is_read', false)
                ->count();

            $lastMsg = ChatMessage::where('room_id', $room->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $msgTime = $lastMsg?->created_at ? Carbon::parse($lastMsg->created_at) : ($room->updated_at ? Carbon::parse($room->updated_at) : null);

            $formattedRooms[] = [
                'room_id' => $room->id,
                'partner_id' => $otherUser->id,
                'last_message' => $lastMsg ? $lastMsg->message_text : ($room->last_message ?? 'Belum ada pesan'),
                'last_message_time' => $this->formatRoomTime($msgTime),
                'last_message_full_time' => $msgTime ? $msgTime->translatedFormat('d M Y, H:i') : '',
                'last_sender_id' => $lastMsg ? (int) $lastMsg->sender_id : null,
                'is_last_mine' => $lastMsg ? ((int) $lastMsg->sender_id === $adminId) : false,
                'is_read_last' => $lastMsg ? (bool) $lastMsg->is_read : false,
                'unread_count' => $unreadCount,
                'user' => $this->formatUserPayload($otherUser),
            ];
        }

        // Hanya hitung pesan belum dibaca yang dikirim oleh customer/mitra (type = 0)
        $totalUnread = ChatMessage::where('receiver_id', $adminId)
            ->where('is_read', false)
            ->whereHas('sender', function ($q) {
                $q->where('type', 0);
            })
            ->count();

        return response()->json([
            'success' => true,
            'total_unread' => $totalUnread,
            'rooms' => $formattedRooms,
        ]);
    }

    public function getMessages(Request $request, $partner_id)
    {
        $adminId = $this->getCurrentAdminId();
        $partnerId = (int) $partner_id;

        // Pastikan partner adalah Customer / Mitra (type = 0)
        $partner = User::where('id', $partnerId)->where('type', 0)->firstOrFail();

        $room = ChatRoom::where(function ($q) use ($adminId, $partnerId) {
            $q->where('user1_id', $adminId)->where('user2_id', $partnerId);
        })->orWhere(function ($q) use ($adminId, $partnerId) {
            $q->where('user1_id', $partnerId)->where('user2_id', $adminId);
        })->first();

        $messages = [];

        if ($room) {
            // Tandai pesan dari mitra/customer ke admin ini sebagai sudah dibaca
            ChatMessage::where('room_id', $room->id)
                ->where('receiver_id', $adminId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $rawMessages = ChatMessage::where('room_id', $room->id)
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            foreach ($rawMessages as $msg) {
                $messages[] = $this->formatMessagePayload($msg, $adminId);
            }
        }

        return response()->json([
            'success' => true,
            'room_id' => $room?->id,
            'partner' => $this->formatUserPayload($partner),
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
        ]);

        $adminId = $this->getCurrentAdminId();
        $receiverId = (int) $request->input('receiver_id');

        // Pastikan penerima adalah Customer / Mitra (type = 0)
        $receiver = User::where('id', $receiverId)->where('type', 0)->firstOrFail();

        $text = trim($request->input('message'));

        $room = ChatRoom::where(function ($q) use ($adminId, $receiverId) {
            $q->where('user1_id', $adminId)->where('user2_id', $receiverId);
        })->orWhere(function ($q) use ($adminId, $receiverId) {
            $q->where('user1_id', $receiverId)->where('user2_id', $adminId);
        })->first();

        if (!$room) {
            $room = ChatRoom::create([
                'user1_id' => $adminId,
                'user2_id' => $receiverId,
                'last_message' => $text,
            ]);
        } else {
            $room->last_message = $text;
            $room->updated_at = now();
            $room->save();
        }

        $message = ChatMessage::create([
            'room_id' => $room->id,
            'sender_id' => $adminId,
            'receiver_id' => $receiverId,
            'message_text' => $text,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'room_id' => $room->id,
            'message' => $this->formatMessagePayload($message, $adminId),
        ]);
    }
}
