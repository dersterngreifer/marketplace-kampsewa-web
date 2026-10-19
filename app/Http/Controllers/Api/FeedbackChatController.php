<?php

namespace App\Http\Controllers\Api;

use App\Events\FeedbackMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\FeedbackMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackChatController extends Controller
{
    /**
     * List feedback milik user login.
     * Untuk developer type=1, menampilkan semua feedback.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Feedback::query()
            ->with(['user', 'latestMessage.sender'])
            ->withCount([
                'messages as unread_admin_messages_count' => function ($query) {
                    $query->where('sender_type', 'admin')
                        ->whereNull('read_at');
                },
                'messages as unread_customer_messages_count' => function ($query) {
                    $query->where('sender_type', 'customer')
                        ->whereNull('read_at');
                },
            ])
            ->latest();

        if ((int) $user->type === 0) {
            $query->where('id_user', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $feedbacks = $query
            ->paginate($request->get('per_page', 10))
            ->appends($request->query());

        $feedbacks->getCollection()->transform(function ($feedback) {
            if ($feedback->latestMessage && $feedback->latestMessage->sender) {
                $sender = $feedback->latestMessage->sender;
                $sender->foto = $this->getUserFotoUrl($sender);
            }
            return $feedback;
        });

        return response()->json([
            'success' => true,
            'message' => 'Data feedback berhasil diambil.',
            'data' => $feedbacks,
        ]);
    }

    /**
     * Customer membuat feedback awal.
     * Ini opsional, tapi berguna untuk aplikasi mobile/API.
     */
    public function storeFeedback(Request $request)
    {
        $user = Auth::user();

        if ((int) $user->type !== 0) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya customer yang dapat membuat feedback.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required|string',
            'kriteria' => 'required|in:Sangat Baik,Baik,Cukup,Kurang,Sangat Kurang',
        ], [
            'deskripsi.required' => 'Deskripsi feedback wajib diisi.',
            'kriteria.required' => 'Kriteria feedback wajib diisi.',
            'kriteria.in' => 'Kriteria feedback tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $feedback = Feedback::create([
            'id_user' => $user->id,
            'deskripsi' => $request->deskripsi,
            'kriteria' => $request->kriteria,
            'status' => 'Belum Dibalas',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil dikirim.',
            'data' => $feedback,
        ], 201);
    }

    /**
     * Ambil isi chat berdasarkan feedback.
     */
    public function messages(Feedback $feedback)
    {
        $this->authorizeFeedbackAccess($feedback);

        $user = Auth::user();

        $feedback->load('user');

        if ((int) $user->type === 0) {
            FeedbackMessage::where('feedback_id', $feedback->id)
                ->where('sender_type', 'admin')
                ->whereNull('read_at')
                ->update([
                    'read_at' => now(),
                ]);
        }

        if ((int) $user->type === 1) {
            FeedbackMessage::where('feedback_id', $feedback->id)
                ->where('sender_type', 'customer')
                ->whereNull('read_at')
                ->update([
                    'read_at' => now(),
                ]);
        }

        $initialFeedback = [
            [
                'id' => 'initial-' . $feedback->id,
                'feedback_id' => $feedback->id,
                'sender_type' => 'customer',
                'sender_id' => $feedback->id_user,
                'sender_name' => $feedback->user?->name ?? 'Customer',
                'sender_foto' => $this->getUserFotoUrl($feedback->user),
                'message' => $feedback->deskripsi,
                'created_at' => $feedback->created_at?->format('Y-m-d H:i:s'),
                'is_initial' => true,
            ],
        ];

        $messagesCollection = $feedback->messages()
            ->with('sender')
            ->oldest()
            ->get();

        $messages = $messagesCollection->map(function ($message) {
                return $this->formatMessage($message);
            })
            ->values()
            ->toArray();

        $lastAdminMessage = $messagesCollection->reverse()->first(function ($message) {
            return $message->sender_type === 'admin';
        });

        $lastAdminName = $lastAdminMessage ? ($lastAdminMessage->sender?->name ?? 'Admin') : null;
        $lastAdminFoto = $lastAdminMessage ? $this->getUserFotoUrl($lastAdminMessage->sender) : null;

        return response()->json([
            'success' => true,
            'message' => 'Data chat berhasil diambil.',
            'data' => [
                'feedback' => [
                    'id' => $feedback->id,
                    'id_user' => $feedback->id_user,
                    'customer_name' => $feedback->user?->name ?? 'Customer',
                    'kriteria' => $feedback->kriteria,
                    'status' => $feedback->status,
                    'created_at' => $feedback->created_at?->format('Y-m-d H:i:s'),
                    'last_admin_name' => $lastAdminName,
                    'last_admin_foto' => $lastAdminFoto,
                ],
                'messages' => array_merge($initialFeedback, $messages),
            ],
        ]);
    }

    /**
     * Kirim pesan chat.
     * Customer type=0 akan tercatat sebagai sender_type customer.
     * Developer/admin type=1 akan tercatat sebagai sender_type admin.
     */
    public function storeMessage(Request $request, Feedback $feedback)
    {
        $this->authorizeFeedbackAccess($feedback);

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
        ], [
            'message.required' => 'Pesan wajib diisi.',
            'message.max' => 'Pesan maksimal 1000 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        $senderType = (int) $user->type === 1 ? 'admin' : 'customer';
        $resolvedAdminId = $senderType === 'admin'
            ? $user->id
            : ($request->filled('admin_id') ? (int) $request->admin_id : null);

        $message = FeedbackMessage::create([
            'feedback_id' => $feedback->id,
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'admin_id' => $resolvedAdminId,
            'message' => $request->message,
            'read_at' => null,
        ]);

        if ($senderType === 'admin') {
            $feedback->update([
                'status' => 'Dibalas',
            ]);

            FeedbackMessage::where('feedback_id', $feedback->id)
                ->where('sender_type', 'customer')
                ->where(function($q) use ($user) {
                    $q->where('admin_id', $user->id)
                      ->orWhereNull('admin_id');
                })
                ->whereNull('read_at')
                ->update([
                    'read_at' => now(),
                ]);
        }

        broadcast(new FeedbackMessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim.',
            'data' => $this->formatMessage($message),
        ], 201);
    }

    private function authorizeFeedbackAccess(Feedback $feedback)
    {
        $user = Auth::user();

        $isDeveloper = (int) $user->type === 1;
        $isOwner = (int) $feedback->id_user === (int) $user->id;

        if (!$isDeveloper && !$isOwner) {
            abort(response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke feedback ini.',
            ], 403));
        }
    }

    private function formatMessage(FeedbackMessage $message)
    {
        $message->loadMissing('sender');

        return [
            'id' => $message->id,
            'feedback_id' => $message->feedback_id,
            'sender_id' => $message->sender_id,
            'admin_id' => (int) ($message->admin_id ?: ($message->sender_type === 'admin' ? $message->sender_id : 0)),
            'sender_type' => $message->sender_type,
            'sender_name' => $message->sender?->name ?? ucfirst($message->sender_type),
            'sender_foto' => $this->getUserFotoUrl($message->sender),
            'message' => $message->message,
            'read_at' => $message->read_at?->format('Y-m-d H:i:s'),
            'created_at' => $message->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    public function chatRooms(Request $request)
    {
        $user = Auth::user();

        // Cari semua feedback milik user ini
        $query = Feedback::query()
            ->with(['user', 'latestMessage.sender', 'messages.sender'])
            ->latest();

        if ((int) $user->type === 0) {
            $query->where('id_user', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Auto-heal feedback_messages where sender_type is admin but admin_id is null
        FeedbackMessage::where('sender_type', 'admin')
            ->whereNull('admin_id')
            ->update(['admin_id' => \Illuminate\Support\Facades\DB::raw('sender_id')]);

        $feedbacks = $query->get();

        $rooms = collect();

        foreach ($feedbacks as $feedback) {
            $adminIds = $feedback->messages
                ->where('sender_type', 'admin')
                ->map(function ($msg) {
                    return $msg->admin_id ?: $msg->sender_id;
                })
                ->filter()
                ->unique();
            
            // Cek juga tabel balas_feedback jika belum ada balasan admin
            if ($adminIds->isEmpty()) {
                $legacyBalas = \App\Models\BalasFeedback::where('id_feedback', $feedback->id)->get();
                if ($legacyBalas->isNotEmpty()) {
                    $adminIds = $legacyBalas->pluck('id_user')->filter()->unique();
                    foreach ($legacyBalas as $balas) {
                        FeedbackMessage::firstOrCreate([
                            'feedback_id' => $feedback->id,
                            'sender_id' => $balas->id_user,
                            'sender_type' => 'admin',
                            'message' => $balas->balasan,
                        ], [
                            'admin_id' => $balas->id_user,
                            'read_at' => now(),
                            'created_at' => $balas->created_at,
                        ]);
                    }
                }
            }

            if ($adminIds->isEmpty()) {
                // Belum ada balasan admin
                $feedbackData = $feedback->toArray();
                $feedbackData['room_id'] = $feedback->id . '-0';
                $feedbackData['admin_id'] = 0;
                $feedbackData['admin'] = null;
                $feedbackData['last_admin_name'] = null;
                $feedbackData['last_admin_foto'] = null;
                $rooms->push($feedbackData);
            } else {
                foreach ($adminIds as $adminId) {
                    $cloned = $feedback->toArray();
                    $cloned['feedback_id'] = $feedback->id;
                    $cloned['id'] = $feedback->id . '-' . $adminId; // overwrite 'id' as unique room_id for UI list parsing
                    $cloned['room_id'] = $cloned['id'];
                    $cloned['admin_id'] = (int) $adminId;
                    
                    // Hitung unread untuk spesifik admin ini
                    $unreadAdminCount = $feedback->messages
                        ->where('sender_type', 'admin')
                        ->filter(function($msg) use ($adminId) {
                            return ($msg->admin_id == $adminId || $msg->sender_id == $adminId);
                        })
                        ->whereNull('read_at')
                        ->count();
                        
                    $unreadCustomerCount = $feedback->messages
                        ->where('sender_type', 'customer')
                        ->where(function($msg) use ($adminId) {
                            return $msg->admin_id == $adminId || is_null($msg->admin_id);
                        })
                        ->whereNull('read_at')
                        ->count();

                    $cloned['unread_admin_messages_count'] = $unreadAdminCount;
                    $cloned['unread_customer_messages_count'] = $unreadCustomerCount;
                    
                    // Root Admin Info untuk UI fallback
                    $lastAdminMessage = $feedback->messages
                        ->where('sender_type', 'admin')
                        ->filter(function($msg) use ($adminId) {
                            return ($msg->admin_id == $adminId || $msg->sender_id == $adminId);
                        })
                        ->last();
                        
                    $adminUser = $lastAdminMessage?->sender ?: \App\Models\User::find($adminId);
                    $adminName = $adminUser?->name ?? 'Admin KampSewa';
                    $adminFoto = $this->getUserFotoUrl($adminUser);

                    $cloned['admin'] = [
                        'id' => (int) $adminId,
                        'name' => $adminName,
                        'foto' => $adminFoto,
                    ];
                    $cloned['last_admin_name'] = $adminName;
                    $cloned['last_admin_foto'] = $adminFoto;
                    
                    // Cari pesan terakhir dari admin ini atau customer
                    $latest = $feedback->messages
                        ->filter(function($msg) use ($adminId) {
                            $isCustomerForThisAdmin = $msg->sender_type === 'customer' && ($msg->admin_id == $adminId || is_null($msg->admin_id));
                            $isAdmin = $msg->sender_type === 'admin' && ($msg->admin_id == $adminId || $msg->sender_id == $adminId);
                            return $isCustomerForThisAdmin || $isAdmin;
                        })->last();
                        
                    if ($latest) {
                        $latestArray = $latest->toArray();
                        if ($latest->sender) {
                            $latestArray['sender'] = $latest->sender->toArray();
                            $latestArray['sender']['foto'] = $this->getUserFotoUrl($latest->sender);
                        }
                        $cloned['latest_message'] = $latestArray;
                    }
                    $rooms->push($cloned);
                }
            }
        }

        // Apply Sorting based on latest_message.created_at DESC
        $rooms = $rooms->sortByDesc(function ($room) {
            return $room['latest_message']['created_at'] ?? $room['created_at'];
        })->values();

        // Apply unread filter if requested
        if ($request->is_unread == 1 || $request->filter == 'belum_dibaca') {
            $rooms = $rooms->filter(function ($room) use ($user) {
                if ((int) $user->type === 1) { // Developer/Admin
                    return isset($room['unread_customer_messages_count']) && $room['unread_customer_messages_count'] > 0;
                } else { // Customer
                    return isset($room['unread_admin_messages_count']) && $room['unread_admin_messages_count'] > 0;
                }
            })->values();
        }

        // Paginate manually if needed, or just return all
        return response()->json([
            'success' => true,
            'message' => 'Data chat rooms berhasil diambil.',
            'data' => $rooms->all(),
        ]);
    }

    public function roomMessages(Feedback $feedback, $admin_id)
    {
        $this->authorizeFeedbackAccess($feedback);

        $user = Auth::user();
        $feedback->load(['user', 'messages.sender']);

        $admin_id = (int) $admin_id;

        // Auto-heal admin_id in feedback_messages if NULL but sender_type == admin
        FeedbackMessage::where('feedback_id', $feedback->id)
            ->where('sender_type', 'admin')
            ->whereNull('admin_id')
            ->update(['admin_id' => \Illuminate\Support\Facades\DB::raw('sender_id')]);

        // Jika admin_id == 0, coba cari admin yang sudah membalas feedback ini
        if ($admin_id === 0) {
            $existingAdminId = $feedback->messages()
                ->where('sender_type', 'admin')
                ->latest()
                ->value('admin_id') ?: $feedback->messages()
                ->where('sender_type', 'admin')
                ->latest()
                ->value('sender_id');

            if (!$existingAdminId) {
                // Cek juga tabel balas_feedback
                $legacyBalas = \App\Models\BalasFeedback::where('id_feedback', $feedback->id)->latest()->first();
                if ($legacyBalas) {
                    $existingAdminId = $legacyBalas->id_user;
                    FeedbackMessage::firstOrCreate([
                        'feedback_id' => $feedback->id,
                        'sender_id' => $legacyBalas->id_user,
                        'sender_type' => 'admin',
                        'message' => $legacyBalas->balasan,
                    ], [
                        'admin_id' => $legacyBalas->id_user,
                        'read_at' => now(),
                        'created_at' => $legacyBalas->created_at,
                    ]);
                }
            }

            if ($existingAdminId) {
                $admin_id = (int) $existingAdminId;
            }
        }

        if ((int) $user->type === 0 && $admin_id > 0) {
            FeedbackMessage::where('feedback_id', $feedback->id)
                ->where('sender_type', 'admin')
                ->where(function($q) use ($admin_id) {
                    $q->where('admin_id', $admin_id)
                      ->orWhere('sender_id', $admin_id);
                })
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        $initialFeedback = [
            [
                'id' => 'initial-' . $feedback->id,
                'feedback_id' => $feedback->id,
                'sender_type' => 'customer',
                'sender_id' => $feedback->id_user,
                'sender_name' => $feedback->user?->name ?? 'Customer',
                'sender_foto' => $this->getUserFotoUrl($feedback->user),
                'message' => $feedback->deskripsi,
                'created_at' => $feedback->created_at?->format('Y-m-d H:i:s'),
                'is_initial' => true,
            ],
        ];

        $messagesQuery = $feedback->messages()->with('sender');
        if ($admin_id > 0) {
            $messagesQuery->where(function($query) use ($admin_id) {
                $query->where(function($q) use ($admin_id) {
                    $q->where('sender_type', 'customer')
                      ->where(function($q2) use ($admin_id) {
                          $q2->where('admin_id', $admin_id)
                             ->orWhereNull('admin_id');
                      });
                })->orWhere(function($q) use ($admin_id) {
                    $q->where('sender_type', 'admin')
                      ->where(function($q2) use ($admin_id) {
                          $q2->where('admin_id', $admin_id)
                             ->orWhere('sender_id', $admin_id);
                      });
                });
            });
        } else {
            // Belum pernah ada balasan admin
            $messagesQuery->where('sender_type', 'customer');
        }

        $messagesCollection = $messagesQuery->oldest()->get();

        $messages = $messagesCollection->map(function ($message) {
            return $this->formatMessage($message);
        })->values()->toArray();

        $lastAdminMessage = $feedback->messages()
            ->with('sender')
            ->where('sender_type', 'admin')
            ->when($admin_id > 0, function($q) use ($admin_id) {
                $q->where(function($q2) use ($admin_id) {
                    $q2->where('admin_id', $admin_id)
                       ->orWhere('sender_id', $admin_id);
                });
            })
            ->latest()
            ->first();

        $adminUser = $lastAdminMessage?->sender;
        if (!$adminUser && $admin_id > 0) {
            $adminUser = \App\Models\User::find($admin_id);
        }

        $lastAdminName = $adminUser?->name;
        $lastAdminFoto = $this->getUserFotoUrl($adminUser);

        $adminData = null;
        if ($admin_id > 0 && $adminUser) {
            $adminData = [
                'id' => $admin_id,
                'name' => $adminUser->name,
                'foto' => $lastAdminFoto,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Data chat room berhasil diambil.',
            'data' => [
                'feedback' => [
                    'id' => $feedback->id,
                    'room_id' => $feedback->id . '-' . $admin_id,
                    'admin_id' => $admin_id,
                    'id_user' => $feedback->id_user,
                    'customer_name' => $feedback->user?->name ?? 'Customer',
                    'kriteria' => $feedback->kriteria,
                    'status' => $feedback->status,
                    'created_at' => $feedback->created_at?->format('Y-m-d H:i:s'),
                    'admin' => $adminData,
                    'last_admin_name' => $lastAdminName,
                    'last_admin_foto' => $lastAdminFoto,
                ],
                'messages' => array_merge($initialFeedback, $messages),
            ],
        ]);
    }

    public function storeRoomMessage(Request $request, Feedback $feedback, $admin_id)
    {
        $this->authorizeFeedbackAccess($feedback);

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        $senderType = (int) $user->type === 1 ? 'admin' : 'customer';

        $resolvedAdminId = (int) $admin_id > 0 ? (int) $admin_id : ($senderType === 'admin' ? $user->id : null);

        if ($senderType === 'customer' && is_null($resolvedAdminId)) {
            $existingAdminId = $feedback->messages()
                ->where('sender_type', 'admin')
                ->latest()
                ->value('admin_id') ?: $feedback->messages()
                ->where('sender_type', 'admin')
                ->latest()
                ->value('sender_id');
            if ($existingAdminId) {
                $resolvedAdminId = (int) $existingAdminId;
            }
        }

        $message = FeedbackMessage::create([
            'feedback_id' => $feedback->id,
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'admin_id' => $resolvedAdminId,
            'message' => $request->message,
            'read_at' => $senderType === 'admin' ? now() : null,
        ]);

        if ($senderType === 'admin') {
            $feedback->update([
                'status' => 'Dibalas',
            ]);

            FeedbackMessage::where('feedback_id', $feedback->id)
                ->where('sender_type', 'customer')
                ->where(function($q) use ($user) {
                    $q->where('admin_id', $user->id)
                      ->orWhereNull('admin_id');
                })
                ->whereNull('read_at')
                ->update([
                    'read_at' => now(),
                ]);
        }

        broadcast(new FeedbackMessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Pesan room berhasil dikirim.',
            'data' => $this->formatMessage($message),
        ], 201);
    }

    private function getUserFotoUrl($user)
    {
        if (!$user || empty($user->foto) || $user->foto === 'Belum Di isi') {
            return null;
        }
        if ((int) $user->type === 1) {
            return asset('assets/image/developers/profiles/' . $user->foto);
        }
        return asset('assets/image/customers/profile/' . $user->foto);
    }

    public function unreadCount(Request $request)
    {
        $user = Auth::user();
        $totalUnread = 0;

        if ((int) $user->type === 1) {
            // Admin/Developer
            $totalUnread = FeedbackMessage::where('sender_type', 'customer')
                ->whereNull('read_at')
                ->count();
        } else {
            // Customer
            $feedbacks = Feedback::where('id_user', $user->id)->pluck('id');
            $totalUnread = FeedbackMessage::whereIn('feedback_id', $feedbacks)
                ->where('sender_type', 'admin')
                ->whereNull('read_at')
                ->count();
        }

        return response()->json([
            'success' => true,
            'message' => 'Total unread feedback chat berhasil diambil.',
            'data' => [
                'total_unread_feedback_chat' => $totalUnread
            ]
        ]);
    }
}