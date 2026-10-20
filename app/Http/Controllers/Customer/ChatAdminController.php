<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\PhotoHelper;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ChatAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('cust');
    }

    private function resolveCustomerId($id_user): int
    {
        try {
            return (int) Crypt::decrypt($id_user);
        } catch (\Exception $e) {
            return (int) (session('id_user') ?? auth()->id());
        }
    }

    private function formatUserPayload(User $user): array
    {
        $avatar = PhotoHelper::getUserAvatarData($user);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nomor_telephone' => $user->nomor_telephone ?: '-',
            'type' => (int) $user->type,
            'role_label' => (int) $user->type === 1 ? 'Admin & Developer Support' : ($user->name_store ?: 'Mitra KampSewa'),
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

    private function formatMessagePayload(ChatMessage $msg, int $currentUserId): array
    {
        $createdAt = $msg->created_at ? Carbon::parse($msg->created_at) : now();

        return [
            'id' => $msg->id,
            'room_id' => $msg->room_id,
            'sender_id' => (int) $msg->sender_id,
            'receiver_id' => (int) $msg->receiver_id,
            'is_mine' => (int) $msg->sender_id === $currentUserId,
            'message_text' => $msg->message_text,
            'is_read' => (bool) $msg->is_read,
            'time' => $createdAt->format('H:i'),
            'date_key' => $createdAt->format('Y-m-d'),
            'date_label' => $this->formatDateLabel($createdAt),
            'full_datetime' => $createdAt->translatedFormat('d M Y, H:i'),
        ];
    }

    public function index(Request $request, $id_user)
    {
        $customerId = $this->resolveCustomerId($id_user);

        $adminQuery = User::where('type', 1);
        $totalAdminsCount = (clone $adminQuery)->count();
        $initialLimit = 50;

        $admins = (clone $adminQuery)
            ->orderBy('name', 'asc')
            ->limit($initialLimit)
            ->get()
            ->map(function ($admin) use ($customerId) {
                $payload = $this->formatUserPayload($admin);
                $room = ChatRoom::where(function ($q) use ($customerId, $admin) {
                    $q->where('user1_id', $customerId)->where('user2_id', $admin->id);
                })->orWhere(function ($q) use ($customerId, $admin) {
                    $q->where('user1_id', $admin->id)->where('user2_id', $customerId);
                })->first();

                $payload['room_id'] = $room?->id;
                $payload['unread_count'] = $room
                    ? ChatMessage::where('room_id', $room->id)
                        ->where('receiver_id', $customerId)
                        ->where('is_read', false)
                        ->count()
                    : 0;
                return $payload;
            });

        return view('customers.chat-admin.index')->with([
            'title' => 'Chat Admin | KampSewa',
            'id_user' => $customerId,
            'encrypted_user_id' => Crypt::encrypt($customerId),
            'admins' => $admins,
            'total_admins_count' => $totalAdminsCount,
            'initial_limit' => $initialLimit,
            'has_more_admins' => $totalAdminsCount > $initialLimit,
            'remaining_admins' => max(0, $totalAdminsCount - $initialLimit),
            'selected_admin_id' => $request->query('admin_id'),
        ]);
    }

    public function getAdmins(Request $request, $id_user)
    {
        $customerId = $this->resolveCustomerId($id_user);
        $q = trim((string) $request->query('q', ''));
        $offset = max(0, (int) $request->query('offset', 0));
        $limit = max(1, min(100, (int) $request->query('limit', 50)));

        $query = User::where('type', 1);

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nomor_telephone', 'like', "%{$q}%");
            });
        }

        $total = (clone $query)->count();

        $admins = (clone $query)
            ->orderBy('name', 'asc')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function ($admin) use ($customerId) {
                $payload = $this->formatUserPayload($admin);
                $room = ChatRoom::where(function ($qr) use ($customerId, $admin) {
                    $qr->where('user1_id', $customerId)->where('user2_id', $admin->id);
                })->orWhere(function ($qr) use ($customerId, $admin) {
                    $qr->where('user1_id', $admin->id)->where('user2_id', $customerId);
                })->first();

                $payload['room_id'] = $room?->id;
                $payload['unread_count'] = $room
                    ? ChatMessage::where('room_id', $room->id)
                        ->where('receiver_id', $customerId)
                        ->where('is_read', false)
                        ->count()
                    : 0;
                return $payload;
            });

        $loadedCount = $offset + $admins->count();
        $hasMore = $loadedCount < $total;
        $remaining = max(0, $total - $loadedCount);

        return response()->json([
            'success' => true,
            'admins' => $admins,
            'total' => $total,
            'offset' => $loadedCount,
            'has_more' => $hasMore,
            'remaining' => $remaining,
        ]);
    }

    public function getRooms(Request $request, $id_user)
    {
        $customerId = $this->resolveCustomerId($id_user);

        // Hanya ambil room chat yang menghubungkan customer ini dengan ADMIN (type = 1)
        $rooms = ChatRoom::with(['user1', 'user2'])
            ->where(function ($q) use ($customerId) {
                $q->where(function ($sub) use ($customerId) {
                    $sub->where('user1_id', $customerId)
                        ->whereHas('user2', function ($u) {
                            $u->where('type', 1);
                        });
                })->orWhere(function ($sub) use ($customerId) {
                    $sub->where('user2_id', $customerId)
                        ->whereHas('user1', function ($u) {
                            $u->where('type', 1);
                        });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $formattedRooms = [];

        foreach ($rooms as $room) {
            $otherUser = (int) $room->user1_id === $customerId ? $room->user2 : $room->user1;
            // Pastikan partner adalah admin
            if (!$otherUser || (int) $otherUser->type !== 1) {
                continue;
            }

            $unreadCount = ChatMessage::where('room_id', $room->id)
                ->where('receiver_id', $customerId)
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
                'is_last_mine' => $lastMsg ? ((int) $lastMsg->sender_id === $customerId) : false,
                'is_read_last' => $lastMsg ? (bool) $lastMsg->is_read : false,
                'unread_count' => $unreadCount,
                'user' => $this->formatUserPayload($otherUser),
            ];
        }

        // Hanya hitung pesan belum dibaca yang dikirim oleh Admin (type = 1)
        $totalUnread = ChatMessage::where('receiver_id', $customerId)
            ->where('is_read', false)
            ->whereHas('sender', function ($q) {
                $q->where('type', 1);
            })
            ->count();

        return response()->json([
            'success' => true,
            'total_unread' => $totalUnread,
            'rooms' => $formattedRooms,
        ]);
    }

    public function getMessages(Request $request, $id_user, $admin_id)
    {
        $customerId = $this->resolveCustomerId($id_user);
        $adminId = (int) $admin_id;

        // Pastikan partner yang dituju adalah Admin (type = 1)
        $admin = User::where('id', $adminId)->where('type', 1)->firstOrFail();

        $room = ChatRoom::where(function ($q) use ($customerId, $adminId) {
            $q->where('user1_id', $customerId)->where('user2_id', $adminId);
        })->orWhere(function ($q) use ($customerId, $adminId) {
            $q->where('user1_id', $adminId)->where('user2_id', $customerId);
        })->first();

        $messages = [];

        if ($room) {
            // Tandai pesan dari admin ke customer ini sebagai sudah dibaca
            ChatMessage::where('room_id', $room->id)
                ->where('receiver_id', $customerId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $rawMessages = ChatMessage::where('room_id', $room->id)
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            foreach ($rawMessages as $msg) {
                $messages[] = $this->formatMessagePayload($msg, $customerId);
            }
        }

        return response()->json([
            'success' => true,
            'room_id' => $room?->id,
            'partner' => $this->formatUserPayload($admin),
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request, $id_user)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
        ]);

        $customerId = $this->resolveCustomerId($id_user);
        $receiverId = (int) $request->input('receiver_id');

        // Pastikan penerima adalah Admin KampSewa (type = 1)
        $admin = User::where('id', $receiverId)->where('type', 1)->firstOrFail();

        $text = trim($request->input('message'));

        $room = ChatRoom::where(function ($q) use ($customerId, $receiverId) {
            $q->where('user1_id', $customerId)->where('user2_id', $receiverId);
        })->orWhere(function ($q) use ($customerId, $receiverId) {
            $q->where('user1_id', $receiverId)->where('user2_id', $customerId);
        })->first();

        if (!$room) {
            $room = ChatRoom::create([
                'user1_id' => $customerId,
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
            'sender_id' => $customerId,
            'receiver_id' => $receiverId,
            'message_text' => $text,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'room_id' => $room->id,
            'message' => $this->formatMessagePayload($message, $customerId),
        ]);
    }
}
