<?php

namespace App\Http\Controllers\Developer;

use App\Events\FeedbackMessageSent;
use App\Http\Controllers\Controller;
use App\Models\BalasFeedback;
use App\Models\DetailIklan;
use App\Models\Feedback;
use App\Models\FeedbackMessage;
use App\Models\Penyewaan;
use App\Models\RatingProduk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('dev');
    }

    public function index(Request $request)
    {
        // --- Mitra baru daftar hari ini (sebelumnya tidak dikirim ke view notifikasi)
        $mitra_baru_hari_ini = User::select('users.*')
            ->join('status_notifikasi_user', 'users.id', '=', 'status_notifikasi_user.id_user')
            ->where('users.type', 0)
            ->whereDate('users.created_at', Carbon::today())
            ->where('status_notifikasi_user.status', 'unread')
            ->orderByDesc('users.created_at')
            ->limit(10)
            ->get();

        // --- Iklan mitra yang akan expired dalam 3 hari ke depan
        $iklan_hampir_expired = DetailIklan::join('iklan', 'detail_iklan.id_iklan', '=', 'iklan.id')
            ->join('users', 'iklan.id_user', '=', 'users.id')
            ->where('detail_iklan.status_iklan', 'Aktif')
            ->whereBetween('detail_iklan.tanggal_akhir', [
                Carbon::today(),
                Carbon::today()->addDays(3),
            ])
            ->select(
                'users.name',
                'users.email',
                'users.foto',
                'iklan.judul',
                'iklan.id as id_iklan',
                'detail_iklan.id as id_detail_iklan',
                'detail_iklan.tanggal_akhir',
                'detail_iklan.status_iklan'
            )
            ->orderBy('detail_iklan.tanggal_akhir', 'asc')
            ->get();

        // --- Rating buruk (≤ 2 bintang) dari pelanggan dalam 7 hari terakhir
        $rating_buruk_baru = RatingProduk::with(['user', 'produk.user'])
            ->where('rating', '<=', 2)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        // --- Penyewaan baru yang masuk hari ini (monitoring komisi platform)
        // Struktur: penyewaan → detail_penyewaan → produk → users (mitra)
        $penyewaan_baru_hari_ini = DB::table('penyewaan')
            ->join('users as penyewa', 'penyewaan.id_user', '=', 'penyewa.id')
            ->join('detail_penyewaan', 'penyewaan.id', '=', 'detail_penyewaan.id_penyewaan')
            ->join('produk', 'detail_penyewaan.id_produk', '=', 'produk.id')
            ->join('users as mitra', 'produk.id_user', '=', 'mitra.id')
            ->join('pembayaran_penyewaan', 'penyewaan.id', '=', 'pembayaran_penyewaan.id_penyewaan')
            ->whereDate('penyewaan.created_at', Carbon::today())
            ->select(
                'penyewaan.id',
                'penyewaan.status_penyewaan',
                'penyewaan.created_at',
                'penyewa.name as nama_penyewa',
                'mitra.name as nama_mitra',
                'pembayaran_penyewaan.total_pembayaran',
                'pembayaran_penyewaan.pajak_platform',
                'pembayaran_penyewaan.status_pembayaran'
            )
            ->orderByDesc('penyewaan.created_at')
            ->distinct()
            ->limit(20)
            ->get();

        // --- Feedback queries (kode lama dipertahankan) ---
        $feedbackSort = $request->get('feedback_sort', 'date_latest');
        $feedbackKriteria = $request->get('feedback_kriteria', 'all');

        $replySort = $request->get('reply_sort', 'date_latest');
        $replyKriteria = $request->get('reply_kriteria', 'all');

        $customerReplySort = $request->get('customer_reply_sort', 'date_latest');
        $customerReplyKriteria = $request->get('customer_reply_kriteria', 'all');

        $baseFeedbackQuery = Feedback::leftJoin('users', 'users.id', '=', 'feedback.id_user')
            ->select(
                'feedback.*',
                'users.name',
                'users.email',
                'users.foto'
            );

        $feedbackQuery = (clone $baseFeedbackQuery)
            ->where('feedback.status', 'Belum Dibalas');

        $this->applyKriteriaFilter($feedbackQuery, $feedbackKriteria);
        $this->applyFeedbackSort($feedbackQuery, $feedbackSort);

        $feedback = $feedbackQuery
            ->paginate(12, ['*'], 'feedback_page')
            ->appends($request->query());

        $feedbackReplyQuery = (clone $baseFeedbackQuery)
            ->where('feedback.status', 'Dibalas');

        $this->applyKriteriaFilter($feedbackReplyQuery, $replyKriteria);
        $this->applyFeedbackSort($feedbackReplyQuery, $replySort);

        $feedbackReply = $feedbackReplyQuery
            ->paginate(10, ['*'], 'reply_page')
            ->appends($request->query());

        $customerReplies = $this->getCustomerReplies($customerReplySort, $customerReplyKriteria);

        return view('developers.notification')->with([
            'title'                  => 'Dashboard | Notification',
            // Data baru
            'mitra_baru_hari_ini'   => $mitra_baru_hari_ini,
            'iklan_hampir_expired'  => $iklan_hampir_expired,
            'rating_buruk_baru'     => $rating_buruk_baru,
            'penyewaan_baru_hari_ini' => $penyewaan_baru_hari_ini,
            // Data lama
            'user_baru_terdaftar'   => $mitra_baru_hari_ini, // backward-compat alias
            'feedback'              => $feedback,
            'reply'                 => $feedbackReply,
            'customerReplies'       => $customerReplies,
            'feedbackSort'          => $feedbackSort,
            'feedbackKriteria'      => $feedbackKriteria,
            'replySort'             => $replySort,
            'replyKriteria'         => $replyKriteria,
            'customerReplySort'     => $customerReplySort,
            'customerReplyKriteria' => $customerReplyKriteria,
        ]);
    }

    public function balasSemuaFeedback(Request $request)
    {
        try {
            $request->validate([
                'balasan' => 'required|string|max:255',
            ], [
                'balasan.required' => 'Pesan balasan wajib diisi.',
                'balasan.max' => 'Pesan balasan maksimal 255 karakter.',
            ]);

            $createdMessages = [];

            DB::transaction(function () use ($request, &$createdMessages) {
                $feedbackBelumDibalas = Feedback::where('status', 'Belum Dibalas')
                    ->lockForUpdate()
                    ->get();

                if ($feedbackBelumDibalas->isEmpty()) {
                    throw new \Exception('EMPTY_FEEDBACK');
                }

                $idUserBalas = Auth::id();

                foreach ($feedbackBelumDibalas as $item) {
                    /*
                     | Ini tetap dipertahankan supaya fitur lama kamu tidak rusak.
                     | Tabel balas_feedback masih menyimpan data balasan lama.
                     */
                    BalasFeedback::create([
                        'id_feedback' => $item->id,
                        'id_user' => $idUserBalas,
                        'balasan' => $request->balasan,
                    ]);

                    /*
                     | INI BAGIAN UPDATE PROSES "BALAS SEMUA"
                     | Tambahkan juga ke tabel feedback_messages
                     | supaya balasan admin muncul di modal chat.
                     */
                    $message = FeedbackMessage::create([
                        'feedback_id' => $item->id,
                        'sender_id' => $idUserBalas,
                        'sender_type' => 'admin',
                        'message' => $request->balasan,
                        'read_at' => now(),
                    ]);

                    $createdMessages[] = $message;
                }

                Feedback::whereIn('id', $feedbackBelumDibalas->pluck('id'))
                    ->update([
                        'status' => 'Dibalas',
                        'updated_at' => now(),
                    ]);
            });

            foreach ($createdMessages as $message) {
                broadcast(new FeedbackMessageSent($message));
            }

            Log::info('Berhasil membalas semua feedback yang belum dibalas.');

            Alert::toast('Balasan berhasil dikirim ke semua feedback', 'success');
            return redirect()->back();
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator->errors())
                ->withInput();
        } catch (\Exception $e) {
            if ($e->getMessage() === 'EMPTY_FEEDBACK') {
                Alert::toast('Tidak ada feedback yang perlu dibalas', 'info');
                return redirect()->back();
            }

            Log::error('Gagal membalas semua feedback: ' . $e->getMessage());

            Alert::toast('Terjadi kesalahan saat membalas feedback', 'error');
            return redirect()->back();
        }
    }

    public function deleteSelectedFeedback(Request $request)
    {
        $request->validate([
            'feedback_ids' => 'required|array',
            'feedback_ids.*' => 'exists:feedback,id',
        ]);

        $deletedCount = Feedback::whereIn('id', $request->feedback_ids)
            ->where('status', 'Belum Dibalas')
            ->delete();

        Alert::toast($deletedCount . ' feedback berhasil dihapus', 'success');

        return redirect()->back();
    }

    public function deleteSelectedReply(Request $request)
    {
        $request->validate([
            'feedback_ids' => 'required|array',
            'feedback_ids.*' => 'exists:feedback,id',
        ]);

        $deletedCount = Feedback::whereIn('id', $request->feedback_ids)
            ->where('status', 'Dibalas')
            ->delete();

        Alert::toast($deletedCount . ' feedback reply berhasil dihapus', 'success');

        return redirect()->back();
    }

    public function messages(Feedback $feedback)
    {
        $this->authorizeDeveloperAccess();

        $feedback->load('user');

        FeedbackMessage::where('feedback_id', $feedback->id)
            ->where('sender_type', 'customer')
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        $initialFeedback = [[
            'id' => 'initial-' . $feedback->id,
            'feedback_id' => $feedback->id,
            'sender_type' => 'customer',
            'sender_name' => $feedback->user?->name ?? 'Customer',
            'message' => $feedback->deskripsi,
            'created_at' => $feedback->created_at->format('d M Y H:i'),
            'is_initial' => true,
        ]];

        $messages = $feedback->messages()
            ->with('sender')
            ->oldest()
            ->get()
            ->map(function ($message) {
                return $this->formatMessage($message);
            })
            ->values()
            ->toArray();

        return response()->json([
            'feedback_id' => $feedback->id,
            'customer_name' => $feedback->user?->name ?? 'Customer',
            'messages' => array_merge($initialFeedback, $messages),
        ]);
    }

    public function storeMessage(Request $request, Feedback $feedback)
    {
        $this->authorizeDeveloperAccess();

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = FeedbackMessage::create([
            'feedback_id' => $feedback->id,
            'sender_id' => Auth::id(),
            'sender_type' => 'admin',
            'message' => $request->message,
            'read_at' => now(),
        ]);

        $feedback->update([
            'status' => 'Dibalas',
        ]);

        FeedbackMessage::where('feedback_id', $feedback->id)
            ->where('sender_type', 'customer')
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        broadcast(new FeedbackMessageSent($message));

        return response()->json([
            'success' => true,
            'message' => $this->formatMessage($message),
        ]);
    }

    public function customerReplies(Request $request)
    {
        $this->authorizeDeveloperAccess();

        $customerReplySort = $request->get('customer_reply_sort', 'date_latest');
        $customerReplyKriteria = $request->get('customer_reply_kriteria', 'all');

        $customerReplies = $this->getCustomerReplies($customerReplySort, $customerReplyKriteria);

        $paginationHtml = '';

        if ($customerReplies->hasPages()) {
            $paginationHtml = (string) $customerReplies
                ->links('components.paginate.custom-pagination');
        }

        return response()->json([
            'html' => view('components.cards.customer-reply-list-items', [
                'customerReplies' => $customerReplies,
            ])->render(), // Ini tidak apa-apa karena view() mengembalikan \Illuminate\View\View yang memiliki method render()

            'pagination' => $paginationHtml,

            'total' => $customerReplies->total(),
        ]);
    }

    private function getCustomerReplies($sort = 'date_latest', $kriteria = 'all'): LengthAwarePaginator
    {
        $query = Feedback::query()
            ->with(['user', 'latestMessage.sender'])
            ->withCount([
                'messages as unread_customer_messages_count' => function ($query) {
                    $query->where('sender_type', 'customer')
                        ->whereNull('read_at');
                },
            ])
            ->where('feedback.status', 'Dibalas')
            ->whereHas('latestMessage', function ($query) {
                $query->where('sender_type', 'customer');
            });

        $this->applyKriteriaFilter($query, $kriteria);
        $this->applyFeedbackSort($query, $sort);

        return $query
            ->paginate(9, ['*'], 'customer_reply_page')
            ->appends(request()->query());
    }

    private function formatMessage(FeedbackMessage $message)
    {
        $message->loadMissing('sender');

        return [
            'id' => $message->id,
            'feedback_id' => $message->feedback_id,
            'sender_type' => $message->sender_type,
            'sender_name' => $message->sender?->name ?? ucfirst($message->sender_type),
            'message' => $message->message,
            'created_at' => $message->created_at->format('d M Y H:i'),
        ];
    }

    private function authorizeDeveloperAccess()
    {
        abort_unless((int) Auth::user()->type === 1, 403);
    }

    private function applyKriteriaFilter($query, $kriteria)
    {
        switch ($kriteria) {
            case 'baik':
                $query->whereIn('feedback.kriteria', ['Sangat Baik', 'Baik']);
                break;

            case 'cukup':
                $query->where('feedback.kriteria', 'Cukup');
                break;

            case 'buruk':
                $query->whereIn('feedback.kriteria', ['Kurang', 'Sangat Kurang']);
                break;

            case 'sangat_baik':
                $query->where('feedback.kriteria', 'Sangat Baik');
                break;

            case 'baik_only':
                $query->where('feedback.kriteria', 'Baik');
                break;

            case 'kurang':
                $query->where('feedback.kriteria', 'Kurang');
                break;

            case 'sangat_kurang':
                $query->where('feedback.kriteria', 'Sangat Kurang');
                break;

            default:
                break;
        }

        return $query;
    }

    private function applyFeedbackSort($query, $sort)
    {
        $kriteriaOrderBest = "
        CASE feedback.kriteria
            WHEN 'Sangat Baik' THEN 1
            WHEN 'Baik' THEN 2
            WHEN 'Cukup' THEN 3
            WHEN 'Kurang' THEN 4
            WHEN 'Sangat Kurang' THEN 5
            ELSE 6
        END
    ";

        switch ($sort) {
            case 'date_oldest':
                $query->orderBy('feedback.created_at', 'asc');
                break;

            case 'kriteria_best':
                $query->orderByRaw($kriteriaOrderBest . ' ASC')
                    ->orderByDesc('feedback.created_at');
                break;

            case 'kriteria_worst':
                $query->orderByRaw($kriteriaOrderBest . ' DESC')
                    ->orderByDesc('feedback.created_at');
                break;

            case 'date_latest':
            default:
                $query->orderByDesc('feedback.created_at');
                break;
        }

        return $query;
    }

    public function balasTerpilihFeedback(Request $request)
    {
        try {
            $request->validate([
                'feedback_ids' => 'required|array|min:1',
                'feedback_ids.*' => 'exists:feedback,id',
                'balasan' => 'required|string|max:255',
            ], [
                'feedback_ids.required' => 'Pilih minimal satu feedback terlebih dahulu.',
                'feedback_ids.min' => 'Pilih minimal satu feedback terlebih dahulu.',
                'feedback_ids.*.exists' => 'Data feedback tidak valid.',
                'balasan.required' => 'Pesan balasan wajib diisi.',
                'balasan.max' => 'Pesan balasan maksimal 255 karakter.',
            ]);

            $createdMessages = [];

            DB::transaction(function () use ($request, &$createdMessages) {
                $feedbackTerpilih = Feedback::whereIn('id', $request->feedback_ids)
                    ->where('status', 'Belum Dibalas')
                    ->lockForUpdate()
                    ->get();

                if ($feedbackTerpilih->isEmpty()) {
                    throw new \Exception('EMPTY_SELECTED_FEEDBACK');
                }

                $idUserBalas = Auth::id();

                foreach ($feedbackTerpilih as $item) {
                    BalasFeedback::create([
                        'id_feedback' => $item->id,
                        'id_user' => $idUserBalas,
                        'balasan' => $request->balasan,
                    ]);

                    $message = FeedbackMessage::create([
                        'feedback_id' => $item->id,
                        'sender_id' => $idUserBalas,
                        'sender_type' => 'admin',
                        'message' => $request->balasan,
                        'read_at' => now(),
                    ]);

                    $createdMessages[] = $message;
                }

                Feedback::whereIn('id', $feedbackTerpilih->pluck('id'))
                    ->update([
                        'status' => 'Dibalas',
                        'updated_at' => now(),
                    ]);
            });

            foreach ($createdMessages as $message) {
                broadcast(new FeedbackMessageSent($message));
            }

            Alert::toast('Balasan berhasil dikirim ke feedback terpilih', 'success');
            return redirect()->back();
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator->errors())
                ->withInput();
        } catch (\Exception $e) {
            if ($e->getMessage() === 'EMPTY_SELECTED_FEEDBACK') {
                Alert::toast('Tidak ada feedback terpilih yang perlu dibalas', 'info');
                return redirect()->back();
            }

            Log::error('Gagal membalas feedback terpilih: ' . $e->getMessage());

            Alert::toast('Terjadi kesalahan saat membalas feedback terpilih', 'error');
            return redirect()->back();
        }
    }
}
