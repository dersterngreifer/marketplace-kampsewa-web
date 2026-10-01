<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getRooms(Request $request)
    {
        $userId = Auth::id();

        $rooms = ChatRoom::with(['user1', 'user2'])
            ->where('user1_id', $userId)
            ->orWhere('user2_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->get();

        $result = $rooms->map(function ($room) use ($userId) {
            $otherUser = $room->user1_id == $userId ? $room->user2 : $room->user1;
            
            $unreadCount = ChatMessage::where('room_id', $room->id)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();

            $lastMsg = ChatMessage::where('room_id', $room->id)->orderBy('created_at', 'desc')->first();
            $isReadLast = 1;
            if ($lastMsg && $lastMsg->sender_id == $userId) {
                $isReadLast = $lastMsg->is_read ? 1 : 0;
            }

            return [
                'room_id' => $room->id,
                'last_message' => $room->last_message,
                'updated_at' => $room->updated_at,
                'unread_count' => $unreadCount,
                'is_read_last' => $isReadLast,
                'user' => [
                    'id' => $otherUser->id ?? null,
                    'name' => $otherUser->name ?? null,
                    'foto_profile' => $otherUser->foto ?? null,
                    'nama_toko' => $otherUser->name_store ?? null,
                    'foto_toko' => $otherUser->foto_toko ?? null,
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getMessagesByRoom($room_id)
    {
        $userId = Auth::id();

        // Mark messages as read
        ChatMessage::where('room_id', $room_id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = ChatMessage::where('room_id', $room_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function getMessagesByUser($user_id_lawan)
    {
        $userId = Auth::id();

        $room = ChatRoom::where(function ($q) use ($userId, $user_id_lawan) {
            $q->where('user1_id', $userId)->where('user2_id', $user_id_lawan);
        })->orWhere(function ($q) use ($userId, $user_id_lawan) {
            $q->where('user1_id', $user_id_lawan)->where('user2_id', $userId);
        })->first();

        if (!$room) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        return $this->getMessagesByRoom($room->id);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $userId = Auth::id();
        $receiverId = $request->receiver_id;

        $room = ChatRoom::where(function ($q) use ($userId, $receiverId) {
            $q->where('user1_id', $userId)->where('user2_id', $receiverId);
        })->orWhere(function ($q) use ($userId, $receiverId) {
            $q->where('user1_id', $receiverId)->where('user2_id', $userId);
        })->first();

        if (!$room) {
            $room = ChatRoom::create([
                'user1_id' => $userId,
                'user2_id' => $receiverId,
                'last_message' => $request->message
            ]);
        } else {
            $room->update(['last_message' => $request->message]);
        }

        $message = ChatMessage::create([
            'room_id' => $room->id,
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'message_text' => $request->message,
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent',
            'data' => $message
        ]);
    }

    public function getUnreadSummary()
    {
        $userId = Auth::id();

        $totalUnread = ChatMessage::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_unread_count' => $totalUnread
            ]
        ]);
    }
}

