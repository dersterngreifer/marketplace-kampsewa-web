@extends('layouts.developers.ly-dashboard')

@section('content')
    <div class="w-full px-4 py-5 sm:px-5 lg:px-6">

        <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-slate-900">
                    Pusat Notifikasi
                </h1>
                <p class="mt-1 text-sm font-medium text-slate-500">
                    Pantau semua aktivitas platform: feedback, mitra baru, iklan kadaluarsa, rating buruk, dan transaksi masuk.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-2 rounded-full bg-violet-50 px-4 py-2 text-sm font-bold text-violet-600">
                    <i class="fi fi-rr-comment-alt flex text-[15px]"></i>
                    {{ $feedback->total() }} feedback belum dibalas
                </div>
                @if ($mitra_baru_hari_ini->count() > 0)
                <div class="flex items-center gap-2 rounded-full bg-blue-50 px-4 py-2 text-sm font-bold text-blue-600">
                    <i class="fi fi-rr-user-add flex text-[15px]"></i>
                    {{ $mitra_baru_hari_ini->count() }} mitra baru
                </div>
                @endif
                @if ($iklan_hampir_expired->count() > 0)
                <div class="flex items-center gap-2 rounded-full bg-red-50 px-4 py-2 text-sm font-bold text-red-500">
                    <i class="fi fi-rr-alarm-clock flex text-[15px]"></i>
                    {{ $iklan_hampir_expired->count() }} iklan hampir habis
                </div>
                @endif
                @if ($rating_buruk_baru->count() > 0)
                <div class="flex items-center gap-2 rounded-full bg-amber-50 px-4 py-2 text-sm font-bold text-amber-600">
                    <i class="fi fi-rr-star flex text-[15px]"></i>
                    {{ $rating_buruk_baru->count() }} rating buruk
                </div>
                @endif
            </div>
        </div>

        <form id="replyAllForm" method="POST" action="{{ route('notification.balas-semua-feedback') }}" class="hidden">
            @csrf
            <textarea name="balasan" id="replyAllMessage"></textarea>
        </form>

        <form id="replySelectedForm" method="POST" action="{{ route('notification.balas-terpilih-feedback') }}"
            class="hidden">
            @csrf
            <div id="replySelectedIds"></div>
            <textarea name="balasan" id="replySelectedMessage"></textarea>
        </form>

        <div class="flex w-full flex-col gap-6">

            {{-- ============================================================
                 KARTU 1: Mitra Baru Hari Ini
                 ============================================================ --}}
            @if ($mitra_baru_hari_ini->count() > 0)
            <div class="w-full overflow-hidden rounded-[28px] border border-blue-200/60 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-blue-600">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                </span>
                                Baru Hari Ini
                            </div>
                            <h2 class="text-xl font-black text-slate-900">Mitra Baru Mendaftar</h2>
                            <p class="mt-1 text-sm text-slate-400">
                                {{ $mitra_baru_hari_ini->count() }} mitra baru mendaftar hari ini. Lakukan onboarding atau verifikasi akun.
                            </p>
                        </div>
                        <a href="{{ route('kelola-pengguna.index') }}"
                            class="shrink-0 inline-flex items-center gap-2 rounded-2xl bg-blue-50 px-4 py-2.5 text-sm font-bold text-blue-600 hover:bg-blue-600 hover:text-white transition">
                            <i class="fi fi-rr-users flex text-[14px]"></i>
                            Kelola Pengguna
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($mitra_baru_hari_ini as $mitra)
                    <div class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="@userPhoto($mitra->foto)" class="h-10 w-10 rounded-2xl object-cover" alt="">
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $mitra->name }}</p>
                                <p class="text-xs text-slate-400">{{ $mitra->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-400">{{ $mitra->created_at->diffForHumans() }}</span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-600">
                                <i class="fi fi-rr-user-add flex text-[10px]"></i> Baru
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ============================================================
                 KARTU 2: Iklan Hampir Kadaluarsa
                 ============================================================ --}}
            @if ($iklan_hampir_expired->count() > 0)
            <div class="w-full overflow-hidden rounded-[28px] border border-red-200/60 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-red-600">
                                <i class="fi fi-rr-alarm-clock flex text-[10px]"></i>
                                Perlu Tindakan
                            </div>
                            <h2 class="text-xl font-black text-slate-900">Iklan Hampir Kadaluarsa</h2>
                            <p class="mt-1 text-sm text-slate-400">
                                {{ $iklan_hampir_expired->count() }} iklan mitra akan berakhir dalam 3 hari ke depan.
                            </p>
                        </div>
                        <a href="{{ route('iklan.index') }}"
                            class="shrink-0 inline-flex items-center gap-2 rounded-2xl bg-red-50 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-500 hover:text-white transition">
                            <i class="fi fi-rr-megaphone flex text-[14px]"></i>
                            Kelola Iklan
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($iklan_hampir_expired as $item)
                    <div class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-red-50/30 transition">
                        <div class="flex items-center gap-3">
                            <img src="@userPhoto($item->foto)" class="h-10 w-10 rounded-2xl object-cover" alt="">
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $item->name }}</p>
                                <p class="text-xs text-slate-400 line-clamp-1">{{ $item->judul }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-black text-red-500">Berakhir {{ \Carbon\Carbon::parse($item->tanggal_akhir)->diffForHumans() }}</p>
                            <p class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ============================================================
                 KARTU 3: Rating Buruk Terbaru
                 ============================================================ --}}
            @if ($rating_buruk_baru->count() > 0)
            <div class="w-full overflow-hidden rounded-[28px] border border-amber-200/60 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5 sm:p-6">
                    <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-amber-600">
                        <i class="fi fi-rr-star flex text-[10px]"></i>
                        7 Hari Terakhir
                    </div>
                    <h2 class="text-xl font-black text-slate-900">Rating Buruk Masuk</h2>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ $rating_buruk_baru->count() }} rating bintang 1–2 dari pelanggan terhadap produk mitra dalam 7 hari terakhir.
                    </p>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($rating_buruk_baru as $rating)
                    <div class="px-5 py-4 hover:bg-amber-50/30 transition">
                        <div class="flex items-start gap-3">
                            <img src="@userPhoto($rating->user?->foto)" class="h-9 w-9 shrink-0 rounded-xl object-cover mt-0.5" alt="">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-bold text-slate-900">{{ $rating->user?->name ?? 'Pelanggan' }}</p>
                                    <div class="flex items-center gap-0.5">
                                        @for ($s = 1; $s <= 5; $s++)
                                            <i class="fi fi-{{ $s <= $rating->rating ? 'ss' : 'rr' }}-star flex text-[12px] {{ $s <= $rating->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Produk: <b>{{ $rating->produk?->nama ?? '-' }}</b></p>
                                @if ($rating->ulasan)
                                <p class="mt-1 text-xs text-slate-400 italic line-clamp-2">"{{ $rating->ulasan }}"</p>
                                @endif
                                <p class="mt-1 text-[11px] text-slate-300">{{ $rating->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ============================================================
                 KARTU 4: Penyewaan Baru Hari Ini (monitoring komisi)
                 ============================================================ --}}
            @if ($penyewaan_baru_hari_ini->count() > 0)
            <div class="w-full overflow-hidden rounded-[28px] border border-emerald-200/60 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-emerald-600">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                </span>
                                Hari Ini
                            </div>
                            <h2 class="text-xl font-black text-slate-900">Transaksi Penyewaan Baru</h2>
                            <p class="mt-1 text-sm text-slate-400">
                                {{ $penyewaan_baru_hari_ini->count() }} transaksi antara mitra dan pelanggan hari ini.
                                Total komisi: <b class="text-emerald-600">Rp {{ number_format($penyewaan_baru_hari_ini->sum('pajak_platform'), 0, ',', '.') }}</b>
                            </p>
                        </div>
                        <a href="{{ route('penyewaan.index') }}"
                            class="shrink-0 inline-flex items-center gap-2 rounded-2xl bg-emerald-50 px-4 py-2.5 text-sm font-bold text-emerald-600 hover:bg-emerald-600 hover:text-white transition">
                            <i class="fi fi-rr-box-alt flex text-[14px]"></i>
                            Lihat Penyewaan
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($penyewaan_baru_hari_ini as $trx)
                    <div class="flex items-center justify-between gap-4 px-5 py-3.5 hover:bg-emerald-50/20 transition">
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $trx->nama_penyewa }}</p>
                            <p class="text-xs text-slate-400">Mitra: {{ $trx->nama_mitra }}</p>
                        </div>
                        <div class="flex items-center gap-4 text-right">
                            <div>
                                <p class="text-sm font-bold text-slate-700">Rp {{ number_format($trx->total_pembayaran, 0, ',', '.') }}</p>
                                @if ($trx->pajak_platform > 0)
                                <p class="text-[11px] text-emerald-600 font-semibold">Komisi: Rp {{ number_format($trx->pajak_platform, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            @php
                                $statusColor = match($trx->status_pembayaran ?? '') {
                                    'Lunas' => 'bg-emerald-50 text-emerald-600',
                                    'Belum lunas' => 'bg-amber-50 text-amber-600',
                                    default => 'bg-slate-100 text-slate-500',
                                };
                            @endphp
                            <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $statusColor }}">
                                {{ $trx->status_pembayaran ?? 'Pending' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Kartu feedback yang sudah ada sebelumnya --}}
            @include('components.cards.card-feedback')
            @include('components.cards.card-feedback-dibalas')
            @include('components.cards.card-feedback-customer-replies')
        </div>
    </div>

    <div id="feedbackChatModal" class="fixed inset-0 z-[80] hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-chat-close></div>

        <div class="relative mx-auto flex min-h-screen w-full max-w-3xl items-center justify-center p-4">
            <div class="flex max-h-[88vh] w-full flex-col overflow-hidden rounded-[28px] bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 p-5">
                    <div>
                        <p class="text-xs font-black uppercase tracking-wide text-violet-500">
                            Feedback Chat
                        </p>
                        <h3 id="feedbackChatTitle" class="mt-1 text-xl font-black text-slate-900">
                            Lihat Balasan
                        </h3>
                    </div>

                    <button type="button" data-chat-close
                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-500 hover:bg-slate-200">
                        <i class="fi fi-rr-cross-small flex text-xl"></i>
                    </button>
                </div>

                <div id="feedbackChatMessages" class="flex-1 space-y-3 overflow-y-auto bg-slate-50 p-5">
                    <div class="text-center text-sm font-semibold text-slate-400">
                        Memuat chat...
                    </div>
                </div>

                <form id="feedbackChatForm" class="shrink-0 border-t border-slate-100 bg-white px-4 py-3 sm:px-5">
                    @csrf
                    <div
                        class="flex items-end gap-3 rounded-[24px] border border-slate-200 bg-slate-50 p-2 shadow-inner transition-all duration-300 focus-within:border-violet-300 focus-within:bg-white focus-within:ring-4 focus-within:ring-violet-100">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                            <i class="fi fi-rr-comment-alt flex text-[16px]"></i>
                        </div>

                        <textarea id="feedbackChatInput" rows="1" maxlength="1000" placeholder="Tulis balasan untuk customer..."
                            class="max-h-32 min-h-[40px] flex-1 resize-none border-0 bg-transparent px-0 py-2 text-sm leading-6 text-slate-700 placeholder:text-slate-400 focus:border-0 focus:outline-none focus:ring-0"></textarea>

                        <button type="submit"
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#B381F4] to-[#5038ED] text-white shadow-lg shadow-violet-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-violet-500/30 active:scale-95"
                            title="Kirim balasan">
                            <i class="fi fi-rr-paper-plane flex text-[16px]"></i>
                        </button>
                    </div>

                    <div class="mt-2 flex items-center justify-between px-2">
                        <p class="text-[11px] font-semibold text-slate-400">
                            Tekan tombol kirim untuk membalas feedback.
                        </p>

                        <span id="feedbackChatCounter" class="text-[11px] font-bold text-slate-400">
                            0/1000
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const defaultReplyMessage =
                'Terima kasih atas feedback Anda! Kami sangat menghargai masukan yang Anda berikan. Kami akan segera mempertimbangkan masukan Anda untuk meningkatkan pengalaman pengguna kami.';

            const replyAllButton = document.getElementById('replyAllButton');
            const replyAllForm = document.getElementById('replyAllForm');
            const replyAllMessage = document.getElementById('replyAllMessage');

            const replySelectedButton = document.getElementById('replySelectedButton');
            const replySelectedForm = document.getElementById('replySelectedForm');
            const replySelectedMessage = document.getElementById('replySelectedMessage');
            const replySelectedIds = document.getElementById('replySelectedIds');

            const chatModal = document.getElementById('feedbackChatModal');
            const chatTitle = document.getElementById('feedbackChatTitle');
            const chatMessages = document.getElementById('feedbackChatMessages');
            const chatForm = document.getElementById('feedbackChatForm');
            const chatInput = document.getElementById('feedbackChatInput');
            const chatCounter = document.getElementById('feedbackChatCounter');
     const customerReplyList = document.getElementById('customerReplyList');
const customerReplyTotal = document.getElementById('customerReplyTotal');
const customerReplyPagination = document.getElementById('customerReplyPagination');

            let activeFeedbackId = null;
            let renderedMessageIds = new Set();
            let chatLoadedOnce = false;
            let isSendingMessage = false;

            const chatRoutes = {
                messages: "{{ route('notification.feedback-messages.index', ['feedback' => '__ID__']) }}",
                store: "{{ route('notification.feedback-messages.store', ['feedback' => '__ID__']) }}",
                customerReplies: "{{ route('notification.customer-replies') }}",
            };

            function getCsrfToken() {
                const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                if (metaToken) {
                    return metaToken;
                }

                const chatToken = document.querySelector('#feedbackChatForm input[name="_token"]')?.value;

                if (chatToken) {
                    return chatToken;
                }

                const replyAllToken = document.querySelector('#replyAllForm input[name="_token"]')?.value;

                if (replyAllToken) {
                    return replyAllToken;
                }

                const anyToken = document.querySelector('input[name="_token"]')?.value;

                if (anyToken) {
                    return anyToken;
                }

                return '';
            }

            function buildUrl(template, feedbackId) {
                return template.replace('__ID__', feedbackId);
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function showSendingSwal() {
                Swal.fire({
                    title: 'Mengirim balasan...',
                    text: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-[28px] p-6 shadow-2xl',
                        title: 'text-[20px] font-black text-slate-900',
                        htmlContainer: 'text-sm text-slate-500',
                    },
                    didOpen: function() {
                        Swal.showLoading();
                    },
                });
            }

            function showReplyDialog(config) {
                Swal.fire({
                    title: config.title,
                    html: config.html,
                    input: 'textarea',
                    inputValue: defaultReplyMessage,
                    inputPlaceholder: config.placeholder,
                    inputAttributes: {
                        maxlength: 255,
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Kirim Balasan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        popup: 'rounded-[28px] p-6 shadow-2xl',
                        title: 'text-[22px] font-black text-slate-900',
                        htmlContainer: 'text-sm text-slate-500',
                        input: 'rounded-2xl border-slate-200 text-sm focus:border-violet-500 focus:ring-violet-500',
                        actions: 'gap-3',
                        confirmButton: 'rounded-2xl bg-gradient-to-br from-[#B381F4] to-[#5038ED] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-violet-500/25 hover:shadow-xl',
                        cancelButton: 'rounded-2xl bg-slate-100 px-6 py-3 text-sm font-bold text-slate-600 hover:bg-slate-200',
                    },
                    buttonsStyling: false,
                    inputValidator: function(value) {
                        if (!value || value.trim() === '') {
                            return 'Pesan balasan wajib diisi.';
                        }

                        if (value.length > 255) {
                            return 'Pesan balasan maksimal 255 karakter.';
                        }
                    },
                }).then(config.onConfirm);
            }

            /*
             |--------------------------------------------------------------------------
             | Balas Semua
             |--------------------------------------------------------------------------
             */
            if (replyAllButton && replyAllForm && replyAllMessage) {
                replyAllButton.addEventListener('click', function() {
                    showReplyDialog({
                        title: 'Balas semua feedback?',
                        html: `
                            <p class="mb-4 text-sm text-slate-500">
                                Pesan ini akan dikirim sebagai balasan untuk semua feedback dengan status <b>Belum Dibalas</b>.
                            </p>
                        `,
                        placeholder: 'Tulis balasan untuk semua feedback...',
                        onConfirm: function(result) {
                            if (!result.isConfirmed) {
                                return;
                            }

                            replyAllMessage.value = result.value.trim();

                            showSendingSwal();
                            replyAllForm.submit();
                        },
                    });
                });
            }

            /*
             |--------------------------------------------------------------------------
             | Balas Terpilih
             |--------------------------------------------------------------------------
             */
            if (replySelectedButton && replySelectedForm && replySelectedMessage && replySelectedIds) {
                replySelectedButton.addEventListener('click', function() {
                    const selectedCheckboxes = Array.from(document.querySelectorAll(
                        '.feedback-checkbox:checked'));
                    const selectedIds = selectedCheckboxes.map(function(checkbox) {
                        return checkbox.value;
                    });

                    if (selectedIds.length === 0) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Pilih minimal satu feedback terlebih dahulu.',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                        });

                        return;
                    }

                    showReplyDialog({
                        title: 'Balas feedback terpilih?',
                        html: `
                            <p class="mb-4 text-sm text-slate-500">
                                Pesan ini akan dikirim untuk <b>${selectedIds.length} feedback terpilih</b>.
                            </p>
                        `,
                        placeholder: 'Tulis balasan untuk feedback terpilih...',
                        onConfirm: function(result) {
                            if (!result.isConfirmed) {
                                return;
                            }

                            replySelectedIds.innerHTML = '';

                            selectedIds.forEach(function(id) {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'feedback_ids[]';
                                input.value = id;
                                replySelectedIds.appendChild(input);
                            });

                            replySelectedMessage.value = result.value.trim();

                            showSendingSwal();
                            replySelectedForm.submit();
                        },
                    });
                });
            }

            /*
             |--------------------------------------------------------------------------
             | Delete Checkbox Setup
             |--------------------------------------------------------------------------
             */
            function setupCheckboxDelete(config) {
                const form = document.getElementById(config.formId);
                const checkAll = document.getElementById(config.checkAllId);
                const deleteButton = document.getElementById(config.deleteButtonId);

                if (!form || !deleteButton) {
                    return;
                }

                const getCheckboxes = function() {
                    return Array.from(document.querySelectorAll(config.checkboxSelector));
                };

                const getChecked = function() {
                    return getCheckboxes().filter(function(checkbox) {
                        return checkbox.checked;
                    });
                };

                if (checkAll) {
                    checkAll.addEventListener('change', function() {
                        getCheckboxes().forEach(function(checkbox) {
                            checkbox.checked = checkAll.checked;
                        });
                    });
                }

                document.addEventListener('change', function(event) {
                    if (!event.target.matches(config.checkboxSelector)) {
                        return;
                    }

                    if (!checkAll) {
                        return;
                    }

                    const checkboxes = getCheckboxes();
                    const checked = getChecked();

                    checkAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
                });

                deleteButton.addEventListener('click', function() {
                    const selectedCount = getChecked().length;

                    if (selectedCount === 0) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Pilih minimal satu data terlebih dahulu.',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                        });

                        return;
                    }

                    Swal.fire({
                        title: 'Hapus data terpilih?',
                        text: `${selectedCount} ${config.itemName} akan dihapus permanen.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-[28px] p-6 shadow-2xl',
                            title: 'text-[22px] font-black text-slate-900',
                            htmlContainer: 'text-sm text-slate-500',
                            icon: 'border-0',
                            actions: 'gap-3',
                            confirmButton: 'rounded-2xl bg-red-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-red-500/20 hover:bg-red-600',
                            cancelButton: 'rounded-2xl bg-slate-100 px-6 py-3 text-sm font-bold text-slate-600 hover:bg-slate-200',
                        },
                        buttonsStyling: false,
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }

            setupCheckboxDelete({
                formId: 'deleteFeedbackForm',
                checkAllId: 'checkAllFeedback',
                deleteButtonId: 'deleteFeedbackButton',
                checkboxSelector: '.feedback-checkbox',
                itemName: 'feedback',
            });

            setupCheckboxDelete({
                formId: 'deleteReplyForm',
                checkAllId: 'checkAllReply',
                deleteButtonId: 'deleteReplyButton',
                checkboxSelector: '.reply-checkbox',
                itemName: 'feedback reply',
            });

            /*
             |--------------------------------------------------------------------------
             | Feedback Chat
             |--------------------------------------------------------------------------
             */
            function resizeChatInput() {
                if (!chatInput) {
                    return;
                }

                chatInput.style.height = 'auto';
                chatInput.style.height = Math.min(chatInput.scrollHeight, 128) + 'px';

                if (chatCounter) {
                    chatCounter.textContent = `${chatInput.value.length}/1000`;
                }
            }

            function scrollChatToBottom() {
                if (!chatMessages) {
                    return;
                }

                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function messageBubbleHtml(message) {
                const isAdmin = message.sender_type === 'admin';
                const id = escapeHtml(message.id);

                return `
                    <div data-message-id="${id}" class="flex ${isAdmin ? 'justify-end' : 'justify-start'}">
                        <div class="max-w-[82%] rounded-2xl px-4 py-3 shadow-sm ${
                            isAdmin
                                ? 'bg-gradient-to-br from-[#B381F4] to-[#5038ED] text-white'
                                : 'border border-slate-200 bg-white text-slate-700'
                        }">
                            <div class="mb-1 flex items-center gap-2">
                                <span class="text-[11px] font-black ${isAdmin ? 'text-white/80' : 'text-slate-400'}">
                                    ${escapeHtml(message.sender_name)}
                                </span>

                                ${message.is_initial ? `
                                            <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-black text-amber-500">
                                                Feedback Awal
                                            </span>
                                        ` : ''}
                            </div>

                            <p class="whitespace-pre-line text-sm leading-6">
                                ${escapeHtml(message.message)}
                            </p>

                            <p class="mt-2 text-[10px] font-semibold ${isAdmin ? 'text-white/70' : 'text-slate-400'}">
                                ${escapeHtml(message.created_at)}
                            </p>
                        </div>
                    </div>
                `;
            }

            function appendChatMessage(message) {
                if (!message || !chatMessages) {
                    return;
                }

                const id = String(message.id);

                if (renderedMessageIds.has(id)) {
                    return;
                }

                renderedMessageIds.add(id);
                chatMessages.insertAdjacentHTML('beforeend', messageBubbleHtml(message));
                scrollChatToBottom();
            }

            function renderChatMessages(messages) {
                if (!chatMessages) {
                    return;
                }

                renderedMessageIds = new Set();

                if (!messages || messages.length === 0) {
                    chatMessages.innerHTML = `
                        <div class="text-center text-sm font-semibold text-slate-400">
                            Belum ada pesan.
                        </div>
                    `;
                    return;
                }

                chatMessages.innerHTML = '';

                messages.forEach(function(message) {
                    appendChatMessage(message);
                });

                scrollChatToBottom();
            }

            function loadChatMessages(feedbackId, showLoading = false) {
                if (!feedbackId || !chatMessages) {
                    return;
                }

                if (showLoading) {
                    chatMessages.innerHTML = `
                        <div class="text-center text-sm font-semibold text-slate-400">
                            Memuat chat...
                        </div>
                    `;
                }

                fetch(buildUrl(chatRoutes.messages, feedbackId), {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    })
                    .then(async function(response) {
                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Gagal memuat chat.');
                        }

                        return data;
                    })
                    .then(function(data) {
                        renderChatMessages(data.messages);
                        chatLoadedOnce = true;
                        loadCustomerReplies();
                    })
                    .catch(function(error) {
                        console.error(error);

                        if (showLoading) {
                            chatMessages.innerHTML = `
                                <div class="text-center text-sm font-semibold text-red-500">
                                    Gagal memuat chat.
                                </div>
                            `;
                        }
                    });
            }

            function syncChatMessagesWithoutBlink(feedbackId) {
                if (!feedbackId || !chatLoadedOnce) {
                    return;
                }

                fetch(buildUrl(chatRoutes.messages, feedbackId), {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    })
                    .then(response => response.json())
                    .then(function(data) {
                        if (!data.messages) {
                            return;
                        }

                        data.messages.forEach(function(message) {
                            appendChatMessage(message);
                        });
                    })
                    .catch(function(error) {
                        console.error(error);
                    });
            }

            function loadCustomerReplies() {
    if (!customerReplyList) {
        return;
    }

    const queryString = window.location.search || '';

    fetch(chatRoutes.customerReplies + queryString, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(async function(response) {
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Gagal memuat reply masuk.');
            }

            return data;
        })
        .then(function(data) {
            customerReplyList.innerHTML = data.html || `
                <div class="flex min-h-[180px] flex-col items-center justify-center px-6 text-center">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">
                        <i class="fi fi-rr-comment-alt flex text-xl"></i>
                    </div>
                    <p class="text-base font-black text-slate-900">
                        Belum ada balasan lanjutan
                    </p>
                    <p class="mt-1 text-sm text-slate-400">
                        Customer yang membalas lagi akan tampil di sini.
                    </p>
                </div>
            `;

            const total = Number(data.total ?? 0);

            if (customerReplyTotal) {
                customerReplyTotal.textContent = `${total} chat`;
            }

            if (customerReplyPagination) {
                customerReplyPagination.innerHTML = data.pagination || '';
            }
        })
        .catch(function(error) {
            console.error(error);

            if (customerReplyTotal) {
                customerReplyTotal.textContent = '0 chat';
            }

            if (customerReplyPagination) {
                customerReplyPagination.innerHTML = '';
            }
        });
}

            function openChatModal(feedbackId, customerName) {
                if (!chatModal || !chatTitle || !chatInput) {
                    return;
                }

                activeFeedbackId = feedbackId;
                renderedMessageIds = new Set();
                chatLoadedOnce = false;

                chatTitle.textContent = `Chat dengan ${customerName || 'Customer'}`;
                chatInput.value = '';
                resizeChatInput();

                chatModal.classList.remove('hidden');

                loadChatMessages(feedbackId, true);
            }

            function closeChatModal() {
                if (!chatModal) {
                    return;
                }

                chatModal.classList.add('hidden');
                activeFeedbackId = null;
                renderedMessageIds = new Set();
                chatLoadedOnce = false;
            }

            if (chatInput) {
                chatInput.addEventListener('input', resizeChatInput);

                chatInput.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter' && !event.shiftKey) {
                        event.preventDefault();

                        if (chatForm && typeof chatForm.requestSubmit === 'function') {
                            chatForm.requestSubmit();
                        } else if (chatForm) {
                            chatForm.dispatchEvent(new Event('submit', {
                                cancelable: true,
                                bubbles: true,
                            }));
                        }
                    }
                });
            }

            document.addEventListener('click', function(event) {
                const chatButton = event.target.closest('[data-chat-button]');
                const closeButton = event.target.closest('[data-chat-close]');

                if (chatButton) {
                    openChatModal(
                        chatButton.dataset.feedbackId,
                        chatButton.dataset.customerName
                    );
                }

                if (closeButton) {
                    closeChatModal();
                }
            });

            if (chatForm) {
                chatForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    if (!activeFeedbackId || !chatInput || isSendingMessage) {
                        return;
                    }

                    const message = chatInput.value.trim();

                    if (!message) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Pesan tidak boleh kosong.',
                            showConfirmButton: false,
                            timer: 2200,
                            timerProgressBar: true,
                        });

                        return;
                    }

                    const token = getCsrfToken();

                    if (!token) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'CSRF token tidak ditemukan. Pastikan meta csrf-token ada di layout.',
                            showConfirmButton: false,
                            timer: 3500,
                            timerProgressBar: true,
                        });

                        return;
                    }

                    isSendingMessage = true;

                    const submitButton = chatForm.querySelector('button[type="submit"]');

                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-60', 'cursor-not-allowed');
                    }

                    fetch(buildUrl(chatRoutes.store, activeFeedbackId), {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({
                                message: message,
                            }),
                        })
                        .then(async function(response) {
                            const contentType = response.headers.get('content-type') || '';
                            const data = contentType.includes('application/json') ?
                                await response.json() : {};

                            if (!response.ok) {
                                if (response.status === 419) {
                                    throw new Error(
                                        'CSRF token mismatch. Refresh halaman lalu coba lagi.');
                                }

                                throw new Error(data.message || 'Gagal mengirim pesan.');
                            }

                            return data;
                        })
                        .then(function(data) {
                            if (data.success) {
                                appendChatMessage(data.message);

                                chatInput.value = '';
                                resizeChatInput();

                                loadCustomerReplies();
                            }
                        })
                        .catch(function(error) {
                            console.error(error);

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: error.message || 'Gagal mengirim pesan.',
                                showConfirmButton: false,
                                timer: 3500,
                                timerProgressBar: true,
                            });
                        })
                        .finally(function() {
                            isSendingMessage = false;

                            if (submitButton) {
                                submitButton.disabled = false;
                                submitButton.classList.remove('opacity-60', 'cursor-not-allowed');
                            }
                        });
                });
            }

            if (window.Echo) {
                window.Echo.private('feedback.admin')
                    .listen('.feedback.message.sent', function(event) {
                        const incomingMessage = event.message;

                        if (
                            activeFeedbackId &&
                            String(activeFeedbackId) === String(incomingMessage.feedback_id)
                        ) {
                            appendChatMessage(incomingMessage);
                        }

                        loadCustomerReplies();
                    });
            }

            setInterval(function() {
                if (activeFeedbackId) {
                    syncChatMessagesWithoutBlink(activeFeedbackId);
                }

                loadCustomerReplies();
            }, 5000);
        });
    </script>
@endpush
