<div class="flex flex-col gap-5">

    {{-- Statistic Cards --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- Total Produk --}}
        <div
            class="rounded-[26px] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-200/70">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#5038ED]/10 text-[#5038ED]">
                <i class="fi fi-rr-box-open text-xl"></i>
            </div>

            <p class="text-sm font-bold text-slate-400">
                Barang Disewakan
            </p>

            <p class="mt-2 text-3xl font-extrabold text-[#19191B]">
                {{ $data->total_product ?? 0 }}
            </p>

            <p class="mt-2 text-sm font-medium leading-6 text-slate-500">
                Total produk yang diposting oleh customer.
            </p>
        </div>

        {{-- Feedback Dibalas --}}
        <div
            class="rounded-[26px] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-200/70">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                <i class="fi fi-rr-comment-check text-xl"></i>
            </div>

            <p class="text-sm font-bold text-slate-400">
                Feedback Dibalas
            </p>

            <p class="mt-2 text-3xl font-extrabold text-[#19191B]">
                {{ $feedback_dibalas ?? 0 }}
            </p>

            <p class="mt-2 text-sm font-medium leading-6 text-slate-500">
                Feedback customer yang sudah mendapatkan balasan.
            </p>
        </div>

        {{-- Feedback Belum Dibalas --}}
        <div
            class="rounded-[26px] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-200/70">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-500">
                <i class="fi fi-rr-comment-exclamation text-xl"></i>
            </div>

            <p class="text-sm font-bold text-slate-400">
                Belum Dibalas
            </p>

            <p class="mt-2 text-3xl font-extrabold text-[#19191B]">
                {{ $feedback_belum_dibalas ?? 0 }}
            </p>

            <p class="mt-2 text-sm font-medium leading-6 text-slate-500">
                Feedback yang masih perlu ditindaklanjuti.
            </p>
        </div>
    </div>

    {{-- Feedback Terbaru --}}
<div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-extrabold text-[#19191B]">
                Feedback & Riwayat Chat
            </h2>
            <p class="mt-1 text-sm font-medium text-slate-500">
                Semua feedback customer ditampilkan dalam satu riwayat percakapan.
            </p>
        </div>

        <span class="w-fit rounded-full bg-[#5038ED]/10 px-3 py-1 text-xs font-bold text-[#5038ED]">
            {{ $total_feedback ?? 0 }} Feedback
        </span>
    </div>

    @if ($feedback_terbaru->isNotEmpty())
        <div class="max-h-[560px] space-y-5 overflow-y-auto pr-2 custom-chat-scroll">

            @foreach ($feedback_terbaru as $feedback)
                @php
                    $hasAdminReply = $feedback->messages->contains(function ($message) {
                        return $message->sender_type === 'admin';
                    });

                    $messages = collect();

                    // Feedback utama dari tabel feedback tetap dianggap sebagai chat pertama customer
                    if (!empty($feedback->deskripsi)) {
                        $messages->push((object) [
                            'sender_type' => 'customer',
                            'message' => $feedback->deskripsi,
                            'created_at' => $feedback->created_at,
                            'is_feedback_awal' => true,
                        ]);
                    }

                    // Tambahkan chat dari feedback_messages
                    foreach ($feedback->messages as $message) {
                        $isDuplicateFeedbackAwal =
                            $message->sender_type === 'customer' &&
                            trim($message->message) === trim($feedback->deskripsi ?? '');

                        if (!$isDuplicateFeedbackAwal) {
                            $message->is_feedback_awal = false;
                            $messages->push($message);
                        }
                    }

                    $messages = $messages->sortBy('created_at')->values();
                @endphp

                {{-- Separator feedback --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-slate-200"></div>

                    <div class="flex flex-wrap items-center justify-center gap-2 rounded-full bg-slate-50 px-3 py-1.5">
                        <span
                            class="rounded-full px-2.5 py-1 text-[11px] font-bold
                            {{ $feedback->kriteria == 'Sangat Baik' ? 'bg-emerald-50 text-emerald-600' : '' }}
                            {{ $feedback->kriteria == 'Baik' ? 'bg-blue-50 text-blue-600' : '' }}
                            {{ $feedback->kriteria == 'Cukup' ? 'bg-yellow-50 text-yellow-700' : '' }}
                            {{ $feedback->kriteria == 'Kurang' ? 'bg-orange-50 text-orange-600' : '' }}
                            {{ $feedback->kriteria == 'Sangat Kurang' ? 'bg-red-50 text-red-600' : '' }}"
                        >
                            {{ $feedback->kriteria }}
                        </span>

                        <span
                            class="rounded-full px-2.5 py-1 text-[11px] font-bold
                            {{ $feedback->status == 'Dibalas' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}"
                        >
                            {{ $feedback->status }}
                        </span>

                        <span class="text-[11px] font-bold text-slate-400">
                            {{ $feedback->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <div class="h-px flex-1 bg-slate-200"></div>
                </div>

                {{-- Bubble chat --}}
                <div class="space-y-3">
                    @foreach ($messages as $message)
                        @php
                            $senderName = $message->sender_type == 'admin' 
                                ? (isset($message->sender) ? $message->sender->name : 'Admin / Developer')
                                : ($data->name ?? 'Customer');
                            
                            $rawFoto = $message->sender_type == 'admin' 
                                ? (isset($message->sender) ? $message->sender->foto : null)
                                : ($data->foto ?? null);
                            
                            $userType = $message->sender_type == 'admin' ? 1 : 0;
                            $svgFallback = 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>';
                        @endphp
                        <div class="flex {{ $message->sender_type == 'admin' ? 'justify-end' : 'justify-start' }} items-end gap-2 mb-2">
                            @if ($message->sender_type != 'admin')
                                <img src="@userPhoto($rawFoto, $userType)" alt="{{ $senderName }}" onerror="this.src='{{ $svgFallback }}'; this.onerror=null;" class="h-8 w-8 rounded-full object-cover shrink-0" />
                            @endif
                            <div
                                class="max-w-[92%] rounded-2xl px-4 py-3 shadow-sm sm:max-w-[78%]
                                {{ $message->sender_type == 'admin'
                                    ? 'rounded-br-md bg-[#5038ED] text-white'
                                    : 'rounded-bl-md border border-slate-200 bg-white text-slate-700' }}"
                            >
                                <div class="mb-1 flex flex-wrap items-center gap-2">
                                    <span
                                        class="text-xs font-extrabold
                                        {{ $message->sender_type == 'admin' ? 'text-white/90' : 'text-[#5038ED]' }}"
                                    >
                                        {{ $senderName }}
                                    </span>

                                    @if (!empty($message->is_feedback_awal))
                                        <span
                                            class="rounded-full px-2 py-0.5 text-[10px] font-bold
                                            {{ $message->sender_type == 'admin'
                                                ? 'bg-white/15 text-white'
                                                : 'bg-[#5038ED]/10 text-[#5038ED]' }}"
                                        >
                                            Feedback Awal
                                        </span>
                                    @endif

                                    <span
                                        class="text-[11px] font-medium
                                        {{ $message->sender_type == 'admin' ? 'text-white/60' : 'text-slate-400' }}"
                                    >
                                        {{ $message->created_at ? $message->created_at->diffForHumans() : '-' }}
                                    </span>
                                </div>

                                <p class="text-sm font-medium leading-6">
                                    {{ $message->message }}
                                </p>
                            </div>
                            @if ($message->sender_type == 'admin')
                                <img src="@userPhoto($rawFoto, $userType)" alt="{{ $senderName }}" onerror="this.src='{{ $svgFallback }}'; this.onerror=null;" class="h-8 w-8 rounded-full object-cover shrink-0" />
                            @endif
                        </div>
                    @endforeach

                    {{-- Kalau feedback ini belum ada balasan admin --}}
                    @if (!$hasAdminReply)
                        <div class="flex justify-end">
                            <div class="max-w-[92%] rounded-2xl rounded-br-md border border-[#5038ED]/15 bg-[#5038ED]/10 px-4 py-3 text-[#5038ED] shadow-sm sm:max-w-[78%]">
                                <div class="mb-1 flex flex-wrap items-center gap-2">
                                    <span class="text-xs font-extrabold">
                                        Admin / Developer
                                    </span>

                                    <span class="rounded-full bg-white/70 px-2 py-0.5 text-[10px] font-bold">
                                        Belum Dibalas
                                    </span>
                                </div>

                                <p class="text-sm font-medium leading-6">
                                    Belum dibalas admin.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400">
                <i class="fi fi-rr-comment-alt text-xl"></i>
            </div>

            <p class="text-sm font-bold text-[#19191B]">
                Belum ada feedback
            </p>

            <p class="mt-1 text-sm font-medium text-slate-500">
                Customer ini belum pernah mengirim feedback.
            </p>
        </div>
    @endif
</div>

<style>
    .custom-chat-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .custom-chat-scroll::-webkit-scrollbar-track {
        background: #F1F5F9;
        border-radius: 999px;
    }

    .custom-chat-scroll::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 999px;
    }

    .custom-chat-scroll::-webkit-scrollbar-thumb:hover {
        background: #5038ED;
    }
</style>
</div>
