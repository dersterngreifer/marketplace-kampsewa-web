@extends('layouts.developers.ly-dashboard')

@section('content')
<style>
    .dev-chat-scrollbar {
        overflow-y: auto !important;
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, 0.45) transparent;
        -webkit-overflow-scrolling: touch;
        touch-action: pan-y;
    }
    .dev-chat-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .dev-chat-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .dev-chat-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.35);
        border-radius: 9999px;
    }
    .dev-chat-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(100, 116, 139, 0.55);
    }
    .dev-chat-pattern {
        background-color: #f8fafc;
        background-image: radial-gradient(#cbd5e1 0.75px, transparent 0.75px);
        background-size: 20px 20px;
    }
    /* Hilangkan resizer & garis pojok kanan pada input */
    #devMessageInput,
    .no-corner-indicator {
        resize: none !important;
        outline: none !important;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }
    #devMessageInput::-webkit-resizer,
    #devMessageInput::-webkit-scrollbar,
    #devMessageInput::-webkit-scrollbar-corner,
    #devMessageInput::-webkit-scrollbar-thumb,
    #devMessageInput::-webkit-scrollbar-track,
    .no-corner-indicator::-webkit-resizer,
    .no-corner-indicator::-webkit-scrollbar,
    .no-corner-indicator::-webkit-scrollbar-corner,
    .no-corner-indicator::-webkit-scrollbar-thumb,
    .no-corner-indicator::-webkit-scrollbar-track,
    input::-ms-clear,
    input::-ms-reveal,
    textarea::-ms-clear,
    textarea::-ms-reveal {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
</style>

<div class="w-full px-5 py-6 sm:px-6 lg:px-8 space-y-6">

    {{-- Top Header Card --}}
    <div class="rounded-3xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-start sm:items-center gap-4">
                <div class="flex h-13 w-13 sm:h-14 sm:w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#B381F4] to-[#5038ED] text-white shadow-[0_12px_24px_-8px_rgba(80,56,237,0.55)]">
                    <i class="bi bi-chat-heart-fill text-2xl"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 ring-1 ring-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            Live Chat Realtime
                        </span>
                        <span class="rounded-full bg-violet-50 px-2.5 py-0.5 text-[11px] font-bold text-violet-700 ring-1 ring-violet-200/70">
                            Admin Aktif: {{ session('nama_lengkap') ?? 'Developer' }}
                        </span>
                    </div>
                    <h1 class="mt-1 text-xl sm:text-2xl font-black tracking-tight text-slate-900">
                        Live Chat Customer &amp; Mitra Toko
                    </h1>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500">
                        Menampilkan seluruh pesan dari Customer / Mitra yang memilih Anda sebagai Admin tujuan konsultasi.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 self-start lg:self-center">
                <div class="flex items-center gap-3 rounded-2xl border border-slate-200/70 bg-slate-50/80 px-4 py-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-100 text-violet-700 font-black text-xs" id="statTotalRooms">
                        0
                    </div>
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-700 leading-tight">Ruang Chat</p>
                        <p class="text-[10px] text-slate-400">Masuk ke akun Anda</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-2xl border border-rose-200/70 bg-rose-50/50 px-4 py-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-500 text-white font-black text-xs" id="statTotalUnread">
                        0
                    </div>
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-700 leading-tight">Belum Dibaca</p>
                        <p class="text-[10px] text-slate-400">Menunggu balasan</p>
                    </div>
                </div>

                <button type="button" onclick="openMitraModal()"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-[#B381F4] to-[#5038ED] px-4 py-3 text-xs sm:text-sm font-bold text-white shadow-[0_10px_22px_-8px_rgba(80,56,237,0.55)] transition-all hover:-translate-y-0.5 cursor-pointer">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Mulai Chat Mitra</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Main Split-Pane Chat Interface --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-xs min-h-[650px] h-[calc(100vh-220px)] max-h-[820px]">

        {{-- Left Column: Chat List & Mitra Directory --}}
        <div class="lg:col-span-4 flex flex-col h-full min-h-0 max-h-full overflow-hidden border-r border-slate-200/80 bg-white">
            {{-- Sidebar Controls --}}
            <div class="p-4 border-b border-slate-100 space-y-3 shrink-0">
                {{-- Tabs --}}
                <div class="grid grid-cols-2 gap-1.5 rounded-2xl bg-slate-100 p-1">
                    <button type="button" id="devTabRooms" onclick="switchDevTab('rooms')"
                        class="flex items-center justify-center gap-2 rounded-xl py-2 px-3 text-xs font-extrabold transition-all bg-white text-violet-700 shadow-xs cursor-pointer">
                        <i class="bi bi-inbox-fill"></i>
                        <span>Daftar Chat</span>
                        <span id="devSidebarUnreadBadge" class="hidden rounded-full bg-rose-500 px-1.5 py-0.2 text-[10px] font-black text-white">0</span>
                    </button>
                    <button type="button" id="devTabMitras" onclick="switchDevTab('mitras')"
                        class="flex items-center justify-center gap-2 rounded-xl py-2 px-3 text-xs font-extrabold transition-all text-slate-600 hover:text-slate-900 cursor-pointer">
                        <i class="bi bi-shop"></i>
                        <span>Mitra ({{ number_format($total_mitras_count ?? count($mitras)) }})</span>
                    </button>
                </div>

                {{-- Search --}}
                <div class="relative">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="devSearchInput" oninput="handleDevSearch(this.value)"
                        placeholder="Cari nama mitra, toko, atau pesan..."
                        spellcheck="false" autocomplete="off" autocorrect="off"
                        class="w-full rounded-xl border border-slate-200/80 bg-slate-50 pl-9 pr-4 py-2.5 text-xs font-medium text-slate-800 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all">
                </div>

                {{-- Filter Bar --}}
                <div id="devFilterBar" class="flex items-center justify-between gap-2 pt-0.5">
                    <div class="flex items-center gap-1.5">
                        <button type="button" id="devFilterAll" onclick="setDevRoomFilter('all')"
                            class="rounded-full bg-violet-600 px-3 py-1 text-[11px] font-bold text-white transition-all cursor-pointer">
                            Semua Chat
                        </button>
                        <button type="button" id="devFilterUnread" onclick="setDevRoomFilter('unread')"
                            class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-600 hover:bg-slate-200 transition-all cursor-pointer">
                            Belum Dibaca
                        </button>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Realtime Aktif
                    </span>
                </div>
            </div>

            {{-- Active Rooms List --}}
            <div id="devRoomsContainer" class="flex-1 min-h-0 max-h-full overflow-y-auto overscroll-contain dev-chat-scrollbar divide-y divide-slate-100">
                <div class="p-8 text-center text-slate-400">
                    <div class="mx-auto mb-3 h-10 w-10 animate-spin rounded-full border-2 border-violet-600 border-t-transparent"></div>
                    <p class="text-xs font-semibold">Memuat daftar pesan masuk...</p>
                </div>
            </div>

            {{-- Mitras Directory List --}}
            <div id="devMitrasContainer" class="hidden flex-1 min-h-0 max-h-full overflow-y-auto overscroll-contain dev-chat-scrollbar">
                <div id="devMitrasList" class="divide-y divide-slate-100">
                    @foreach ($mitras as $mitra)
                        <div data-mitra-search="{{ strtolower($mitra['name'] . ' ' . ($mitra['name_store'] ?? '') . ' ' . $mitra['email'] . ' ' . $mitra['nomor_telephone']) }}"
                            onclick="selectPartner({{ $mitra['id'] }})"
                            class="dev-mitra-item flex items-center justify-between gap-3 p-3.5 hover:bg-violet-50/50 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="relative shrink-0">
                                    @if ($mitra['has_photo'] && $mitra['avatar_url'])
                                        <img src="{{ $mitra['avatar_url'] }}" alt="{{ $mitra['name'] }}"
                                            class="h-12 w-12 rounded-2xl object-cover border border-slate-200 shadow-2xs"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="display:none;"
                                            class="h-12 w-12 rounded-2xl bg-gradient-to-br {{ $mitra['gradient'] }} text-white font-extrabold text-sm items-center justify-center">
                                            {{ $mitra['initials'] }}
                                        </div>
                                    @else
                                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br {{ $mitra['gradient'] }} text-white font-extrabold text-sm flex items-center justify-center">
                                            {{ $mitra['initials'] }}
                                        </div>
                                    @endif
                                    <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500"></span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $mitra['name'] }}</h4>
                                    @if (!empty($mitra['name_store']))
                                        <p class="text-[11px] font-semibold text-violet-600 truncate">
                                            <i class="bi bi-shop mr-1"></i>{{ $mitra['name_store'] }}
                                        </p>
                                    @else
                                        <p class="text-[11px] text-slate-400 truncate">Customer KampSewa</p>
                                    @endif
                                    <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ $mitra['email'] }}</p>
                                </div>
                            </div>
                            <span class="shrink-0 rounded-xl bg-violet-50 px-3 py-1.5 text-xs font-bold text-violet-600 hover:bg-violet-600 hover:text-white transition-colors">
                                Chat
                            </span>
                        </div>
                    @endforeach
                </div>
                <div id="devMitrasLoadMoreContainer" class="p-3.5 text-center border-t border-slate-100 bg-slate-50/60">
                    <button type="button" id="btnDevLoadMoreMitras" onclick="loadMoreDevMitras()"
                        @if(empty($has_more_mitras)) disabled @endif
                        class="w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 {{ empty($has_more_mitras) ? 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200/60' : 'bg-white hover:bg-violet-50 text-violet-700 border border-violet-200/80 shadow-2xs hover:border-violet-300 cursor-pointer' }}">
                        <span id="btnDevLoadMoreText">{{ empty($has_more_mitras) ? 'Tidak ada lagi data' : 'Lihat Sebagian Lagi' }}</span>
                        <i id="btnDevLoadMoreSpinner" class="bi bi-arrow-repeat hidden animate-spin"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Right Column: Active Conversation Window --}}
        <div class="lg:col-span-8 flex flex-col h-full min-h-0 max-h-full overflow-hidden bg-slate-50/50 relative">

            {{-- Empty State --}}
            <div id="devEmptyState" class="flex-1 min-h-0 max-h-full flex flex-col items-center justify-start p-6 sm:p-10 text-center overflow-y-auto overscroll-contain dev-chat-scrollbar">
                <div class="my-auto w-full flex flex-col items-center">
                    <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-violet-500/15 to-indigo-500/20 text-violet-600 ring-1 ring-violet-200/70">
                        <i class="bi bi-chat-left-quote-fill text-4xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-black text-slate-900">
                        Pilih Percakapan Customer / Mitra
                    </h3>
                    <p class="mt-1 max-w-md text-xs sm:text-sm text-slate-500">
                        Pilih salah satu chat dari daftar di sebelah kiri untuk membaca dan membalas pesan secara real-time. Status centang akan otomatis berubah menjadi <strong>Dibaca</strong>.
                    </p>
                    <button type="button" onclick="openMitraModal()"
                        class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-violet-600 hover:bg-violet-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition-all cursor-pointer">
                        <i class="bi bi-chat-dots-fill"></i>
                        <span>Pilih Mitra Untuk Mulai Chat</span>
                    </button>
                </div>
            </div>

            {{-- Active Chat View --}}
            <div id="devActiveChat" class="hidden flex-col h-full min-h-0 max-h-full overflow-hidden">
                {{-- Header --}}
                <div class="flex items-center justify-between gap-3 border-b border-slate-200/80 bg-white px-4 sm:px-6 py-3.5 shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <button type="button" onclick="closeDevMobileChat()"
                            class="lg:hidden flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200">
                            <i class="bi bi-arrow-left"></i>
                        </button>

                        <div id="devHeaderAvatar" class="relative shrink-0"></div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 id="devHeaderName" class="text-sm sm:text-base font-black text-slate-900 truncate">-</h3>
                                <span id="devHeaderRoleBadge" class="rounded-full bg-violet-100 px-2.5 py-0.5 text-[10px] font-extrabold text-violet-700 shrink-0">
                                    Mitra Toko
                                </span>
                            </div>
                            <div class="mt-0.5 flex flex-wrap items-center gap-x-3 gap-y-0.5 text-[11px] text-slate-500">
                                <span id="devHeaderEmail" class="truncate"></span>
                                <span id="devHeaderPhone" class="hidden sm:inline truncate"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a id="devHeaderProfileLink" href="#"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 transition-all">
                            <i class="bi bi-person-vcard"></i>
                            <span class="hidden sm:inline">Detail Pengguna</span>
                        </a>
                    </div>
                </div>

                {{-- Messages Viewport --}}
                <div id="devMessagesViewport" class="flex-1 min-h-0 max-h-full overflow-y-auto overscroll-contain dev-chat-scrollbar dev-chat-pattern p-4 sm:p-6 space-y-4">
                </div>

                {{-- Quick Reply Chips --}}
                <div class="flex items-center gap-2 overflow-x-auto dev-chat-scrollbar border-t border-slate-100 bg-white/95 px-4 py-2 shrink-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 shrink-0">Balasan Cepat:</span>
                    <button type="button" onclick="fillDevQuickReply('Halo Kak, terima kasih telah menghubungi Admin KampSewa. Ada yang bisa kami bantu?')"
                        class="rounded-lg bg-slate-100 hover:bg-violet-50 hover:text-violet-700 px-2.5 py-1 text-[11px] font-medium text-slate-600 whitespace-nowrap transition-colors cursor-pointer">
                        👋 Salam Pembuka
                    </button>
                    <button type="button" onclick="fillDevQuickReply('Baik Kak, laporan atau pengajuan Anda sedang kami periksa. Mohon ditunggu sebentar ya.')"
                        class="rounded-lg bg-slate-100 hover:bg-violet-50 hover:text-violet-700 px-2.5 py-1 text-[11px] font-medium text-slate-600 whitespace-nowrap transition-colors cursor-pointer">
                        🔍 Sedang Dicek
                    </button>
                    <button type="button" onclick="fillDevQuickReply('Pengajuan promosi/iklan toko Anda telah kami tindak lanjuti. Silakan cek kembali di menu Kelola Iklan.')"
                        class="rounded-lg bg-slate-100 hover:bg-violet-50 hover:text-violet-700 px-2.5 py-1 text-[11px] font-medium text-slate-600 whitespace-nowrap transition-colors cursor-pointer">
                        ✅ Info Iklan Selesai
                    </button>
                </div>

                {{-- Message Composer --}}
                <form id="devSendForm" onsubmit="submitDevMessage(event)" class="border-t border-slate-200/80 bg-white p-3.5 sm:p-4 shrink-0">
                    <div class="flex items-end gap-2.5">
                        <div class="flex-1 relative">
                            <textarea id="devMessageInput" rows="1"
                                placeholder="Ketik balasan untuk Customer / Mitra..."
                                onkeydown="handleDevInputKeydown(event)"
                                oninput="autoResizeDevTextarea(this)"
                                spellcheck="false"
                                autocomplete="off"
                                autocorrect="off"
                                autocapitalize="off"
                                data-gramm="false"
                                data-enable-grammarly="false"
                                writingsuggestions="false"
                                style="overflow-y: hidden; resize: none;"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs sm:text-sm text-slate-800 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 resize-none max-h-32 transition-all no-corner-indicator overflow-hidden"></textarea>
                        </div>
                        <button type="submit" id="devSendBtn"
                            class="flex h-11 shrink-0 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#B381F4] to-[#5038ED] px-5 text-xs sm:text-sm font-bold text-white shadow-[0_10px_20px_-8px_rgba(80,56,237,0.55)] transition-all hover:-translate-y-0.5 disabled:opacity-50 cursor-pointer">
                            <span>Kirim</span>
                            <i id="devSendIcon" class="bi bi-send-fill text-xs"></i>
                        </button>
                    </div>
                    <div class="mt-1.5 flex items-center justify-between px-1 text-[10px] text-slate-400">
                        <span>Tekan <strong>Enter</strong> untuk kirim, <strong>Shift + Enter</strong> untuk baris baru</span>
                        <span class="inline-flex items-center gap-1">
                            <i class="bi bi-check2-all text-sky-500 text-xs"></i> Status baca &amp; centang diperbarui otomatis secara real-time
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Pilih Mitra --}}
<div id="mitraSelectorModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="w-full max-w-2xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 bg-gradient-to-r from-violet-50/70 via-white to-white px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-xs">
                    <i class="bi bi-shop text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">Mulai Chat dengan Customer / Mitra</h3>
                    <p class="text-xs text-slate-500">Pilih pengguna atau mitra toko yang ingin Anda hubungi</p>
                </div>
            </div>
            <button type="button" onclick="closeMitraModal()"
                class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 cursor-pointer">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <div class="border-b border-slate-100 bg-slate-50/50 p-4">
            <div class="relative">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" oninput="filterModalMitras(this.value)"
                    placeholder="Cari nama mitra, nama toko, email, atau nomor telepon..."
                    class="w-full rounded-xl border border-slate-200 bg-white pl-9 pr-4 py-2.5 text-xs font-medium focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
            </div>
        </div>

        <div id="modalMitrasScrollArea" class="max-h-[60vh] sm:max-h-[460px] overflow-y-auto overscroll-contain dev-chat-scrollbar">
            <div id="modalMitrasGrid" class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($mitras as $mitra)
                    <div data-modal-mitra="{{ strtolower($mitra['name'] . ' ' . ($mitra['name_store'] ?? '') . ' ' . $mitra['email'] . ' ' . $mitra['nomor_telephone']) }}"
                        onclick="selectPartner({{ $mitra['id'] }}); closeMitraModal();"
                        class="modal-mitra-card group flex items-start gap-3.5 rounded-2xl border border-slate-200/80 p-3.5 hover:border-violet-400 hover:bg-violet-50/30 transition-all cursor-pointer">
                        <div class="relative shrink-0">
                            @if ($mitra['has_photo'] && $mitra['avatar_url'])
                                <img src="{{ $mitra['avatar_url'] }}" alt="{{ $mitra['name'] }}"
                                    class="h-12 w-12 rounded-2xl object-cover border border-slate-200"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="display:none;"
                                    class="h-12 w-12 rounded-2xl bg-gradient-to-br {{ $mitra['gradient'] }} text-white font-extrabold text-sm items-center justify-center">
                                    {{ $mitra['initials'] }}
                                </div>
                            @else
                                <div class="h-12 w-12 rounded-2xl bg-gradient-to-br {{ $mitra['gradient'] }} text-white font-extrabold text-sm flex items-center justify-center">
                                    {{ $mitra['initials'] }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-violet-700 truncate">{{ $mitra['name'] }}</h4>
                            <span class="mt-0.5 inline-block rounded-md bg-violet-100/80 px-2 py-0.5 text-[10px] font-bold text-violet-700 truncate max-w-full">
                                {{ $mitra['role_label'] }}
                            </span>
                            <p class="mt-1 text-[11px] text-slate-500 truncate">{{ $mitra['email'] }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ $mitra['nomor_telephone'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="modalMitrasLoadMoreContainer" class="p-4 pt-1 text-center bg-slate-50/50 border-t border-slate-100">
                <button type="button" id="btnModalLoadMoreMitras" onclick="loadMoreModalMitras()"
                    @if(empty($has_more_mitras)) disabled @endif
                    class="w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 {{ empty($has_more_mitras) ? 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200/60' : 'bg-white hover:bg-violet-50 text-violet-700 border border-violet-200/80 shadow-2xs hover:border-violet-300 cursor-pointer' }}">
                    <span id="btnModalLoadMoreText">{{ empty($has_more_mitras) ? 'Tidak ada lagi data' : 'Lihat Sebagian Lagi' }}</span>
                    <i id="btnModalLoadMoreSpinner" class="bi bi-arrow-repeat hidden animate-spin"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const DEV_CHAT_CONFIG = {
        roomsUrl: @json(route('developer.chat.rooms')),
        mitrasUrl: @json(route('developer.chat.mitras')),
        messagesBaseUrl: @json(url('/developer/dashboard/chat/messages')),
        sendUrl: @json(route('developer.chat.send')),
        detailUserBaseUrl: @json(url('/developer/dashboard/kelola-pengguna/detail-pengguna')),
        csrfToken: @json(csrf_token()),
        mitras: @json($mitras->values()),
        initialPartnerId: @json($selected_partner_id ? (int) $selected_partner_id : null),
        totalMitras: {{ (int) ($total_mitras_count ?? count($mitras)) }},
        initialLimit: {{ (int) ($initial_limit ?? 50) }},
        hasMoreMitras: @json(!empty($has_more_mitras)),
    };

    let devState = {
        activePartnerId: null,
        activeTab: 'rooms',
        roomFilter: 'all',
        searchQuery: '',
        rooms: [],
        messagesSignature: '',
        isSending: false,
        pollTimer: null,
        searchTimer: null,
        mitraOffset: {{ count($mitras) }},
        mitraHasMore: @json(!empty($has_more_mitras)),
        mitraLoading: false,
        modalMitraQuery: '',
        modalMitraOffset: {{ count($mitras) }},
        modalMitraHasMore: @json(!empty($has_more_mitras)),
        modalMitraLoading: false,
    };

    function escapeDevHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function renderDevAvatar(user, sizeClass = 'h-12 w-12', roundedClass = 'rounded-2xl', textClass = 'text-sm') {
        const gradient = user.gradient || 'from-violet-600 to-indigo-600';
        const initials = escapeDevHtml(user.initials || 'US');
        const name = escapeDevHtml(user.name || 'User');

        if (user.has_photo && user.avatar_url) {
            return `
                <div class="relative shrink-0">
                    <img src="${escapeDevHtml(user.avatar_url)}" alt="${name}"
                        class="${sizeClass} ${roundedClass} object-cover border border-slate-200 shadow-2xs"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none;"
                        class="${sizeClass} ${roundedClass} bg-gradient-to-br ${gradient} text-white font-extrabold ${textClass} items-center justify-center shadow-2xs">
                        ${initials}
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500"></span>
                </div>
            `;
        }

        return `
            <div class="relative shrink-0">
                <div class="${sizeClass} ${roundedClass} bg-gradient-to-br ${gradient} text-white font-extrabold ${textClass} flex items-center justify-center shadow-2xs">
                    ${initials}
                </div>
                <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500"></span>
            </div>
        `;
    }

    function renderDevMitraItem(m) {
        const searchKeywords = escapeDevHtml((m.name + ' ' + (m.name_store || '') + ' ' + m.email + ' ' + m.nomor_telephone).toLowerCase());
        return `
            <div data-mitra-search="${searchKeywords}"
                onclick="selectPartner(${m.id})"
                class="dev-mitra-item flex items-center justify-between gap-3 p-3.5 hover:bg-violet-50/50 transition-colors cursor-pointer">
                <div class="flex items-center gap-3 min-w-0">
                    ${renderDevAvatar(m, 'h-12 w-12', 'rounded-2xl', 'text-sm')}
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">${escapeDevHtml(m.name)}</h4>
                        ${m.name_store
                            ? `<p class="text-[11px] font-semibold text-violet-600 truncate"><i class="bi bi-shop mr-1"></i>${escapeDevHtml(m.name_store)}</p>`
                            : `<p class="text-[11px] text-slate-400 truncate">Customer KampSewa</p>`}
                        <p class="text-[10px] text-slate-400 truncate mt-0.5">${escapeDevHtml(m.email)}</p>
                    </div>
                </div>
                <span class="shrink-0 rounded-xl bg-violet-50 px-3 py-1.5 text-xs font-bold text-violet-600 hover:bg-violet-600 hover:text-white transition-colors">
                    Chat
                </span>
            </div>
        `;
    }

    function renderModalMitraCard(m) {
        const searchKeywords = escapeDevHtml((m.name + ' ' + (m.name_store || '') + ' ' + m.email + ' ' + m.nomor_telephone).toLowerCase());
        return `
            <div data-modal-mitra="${searchKeywords}"
                onclick="selectPartner(${m.id}); closeMitraModal();"
                class="modal-mitra-card group flex items-start gap-3.5 rounded-2xl border border-slate-200/80 p-3.5 hover:border-violet-400 hover:bg-violet-50/30 transition-all cursor-pointer">
                ${renderDevAvatar(m, 'h-12 w-12', 'rounded-2xl', 'text-sm')}
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-violet-700 truncate">${escapeDevHtml(m.name)}</h4>
                    <span class="mt-0.5 inline-block rounded-md bg-violet-100/80 px-2 py-0.5 text-[10px] font-bold text-violet-700 truncate max-w-full">
                        ${escapeDevHtml(m.role_label || 'Mitra Toko')}
                    </span>
                    <p class="mt-1 text-[11px] text-slate-500 truncate">${escapeDevHtml(m.email)}</p>
                    <p class="text-[11px] text-slate-400 truncate">${escapeDevHtml(m.nomor_telephone)}</p>
                </div>
            </div>
        `;
    }

    function updateDevLoadMoreButtonState(status) {
        const btn = document.getElementById('btnDevLoadMoreMitras');
        const textEl = document.getElementById('btnDevLoadMoreText');
        const spinner = document.getElementById('btnDevLoadMoreSpinner');
        if (!btn || !textEl) return;

        if (status === 'loading') {
            btn.disabled = true;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-slate-100 text-slate-500 border border-slate-200 cursor-wait';
            textEl.textContent = 'Memuat mitra...';
            if (spinner) spinner.classList.remove('hidden');
        } else if (status === 'empty' || !devState.mitraHasMore) {
            btn.disabled = true;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200/60';
            textEl.textContent = 'Tidak ada lagi data';
            if (spinner) spinner.classList.add('hidden');
        } else {
            btn.disabled = false;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-white hover:bg-violet-50 text-violet-700 border border-violet-200/80 shadow-2xs hover:border-violet-300 cursor-pointer';
            textEl.textContent = 'Lihat Sebagian Lagi';
            if (spinner) spinner.classList.add('hidden');
        }
    }

    async function loadMoreDevMitras() {
        if (devState.mitraLoading || !devState.mitraHasMore) return;
        devState.mitraLoading = true;
        updateDevLoadMoreButtonState('loading');

        try {
            const query = devState.searchQuery || '';
            const offset = devState.mitraOffset;
            const res = await fetch(`${DEV_CHAT_CONFIG.mitrasUrl}?q=${encodeURIComponent(query)}&offset=${offset}&limit=50`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();
            if (data.success && data.mitras) {
                const listCont = document.getElementById('devMitrasList');
                if (listCont) {
                    const html = data.mitras.map(renderDevMitraItem).join('');
                    listCont.insertAdjacentHTML('beforeend', html);
                }
                devState.mitraOffset = data.offset;
                devState.mitraHasMore = !!data.has_more;
            }
        } catch (e) {
            console.error('Error loading more mitras:', e);
        } finally {
            devState.mitraLoading = false;
            updateDevLoadMoreButtonState(devState.mitraHasMore ? 'ready' : 'empty');
        }
    }

    function updateModalLoadMoreButtonState(status) {
        const btn = document.getElementById('btnModalLoadMoreMitras');
        const textEl = document.getElementById('btnModalLoadMoreText');
        const spinner = document.getElementById('btnModalLoadMoreSpinner');
        if (!btn || !textEl) return;

        if (status === 'loading') {
            btn.disabled = true;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-slate-100 text-slate-500 border border-slate-200 cursor-wait';
            textEl.textContent = 'Memuat mitra...';
            if (spinner) spinner.classList.remove('hidden');
        } else if (status === 'empty' || !devState.modalMitraHasMore) {
            btn.disabled = true;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200/60';
            textEl.textContent = 'Tidak ada lagi data';
            if (spinner) spinner.classList.add('hidden');
        } else {
            btn.disabled = false;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-white hover:bg-violet-50 text-violet-700 border border-violet-200/80 shadow-2xs hover:border-violet-300 cursor-pointer';
            textEl.textContent = 'Lihat Sebagian Lagi';
            if (spinner) spinner.classList.add('hidden');
        }
    }

    async function loadMoreModalMitras() {
        if (devState.modalMitraLoading || !devState.modalMitraHasMore) return;
        devState.modalMitraLoading = true;
        updateModalLoadMoreButtonState('loading');

        try {
            const query = devState.modalMitraQuery || '';
            const offset = devState.modalMitraOffset;
            const res = await fetch(`${DEV_CHAT_CONFIG.mitrasUrl}?q=${encodeURIComponent(query)}&offset=${offset}&limit=50`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();
            if (data.success && data.mitras) {
                const grid = document.getElementById('modalMitrasGrid');
                if (grid) {
                    const html = data.mitras.map(renderModalMitraCard).join('');
                    grid.insertAdjacentHTML('beforeend', html);
                }
                devState.modalMitraOffset = data.offset;
                devState.modalMitraHasMore = !!data.has_more;
            }
        } catch (e) {
            console.error('Error loading more modal mitras:', e);
        } finally {
            devState.modalMitraLoading = false;
            updateModalLoadMoreButtonState(devState.modalMitraHasMore ? 'ready' : 'empty');
        }
    }

    function switchDevTab(tab) {
        devState.activeTab = tab;
        const tabRooms = document.getElementById('devTabRooms');
        const tabMitras = document.getElementById('devTabMitras');
        const roomsCont = document.getElementById('devRoomsContainer');
        const mitrasCont = document.getElementById('devMitrasContainer');
        const filterBar = document.getElementById('devFilterBar');

        if (tab === 'rooms') {
            tabRooms.className = 'flex items-center justify-center gap-2 rounded-xl py-2 px-3 text-xs font-extrabold transition-all bg-white text-violet-700 shadow-xs cursor-pointer';
            tabMitras.className = 'flex items-center justify-center gap-2 rounded-xl py-2 px-3 text-xs font-extrabold transition-all text-slate-600 hover:text-slate-900 cursor-pointer';
            roomsCont.classList.remove('hidden');
            mitrasCont.classList.add('hidden');
            filterBar.classList.remove('hidden');
            renderDevRooms();
        } else {
            tabMitras.className = 'flex items-center justify-center gap-2 rounded-xl py-2 px-3 text-xs font-extrabold transition-all bg-white text-violet-700 shadow-xs cursor-pointer';
            tabRooms.className = 'flex items-center justify-center gap-2 rounded-xl py-2 px-3 text-xs font-extrabold transition-all text-slate-600 hover:text-slate-900 cursor-pointer';
            mitrasCont.classList.remove('hidden');
            roomsCont.classList.add('hidden');
            filterBar.classList.add('hidden');
            if (devState.searchQuery) {
                filterDevMitraDirectory(devState.searchQuery);
            }
        }
    }

    function setDevRoomFilter(filter) {
        devState.roomFilter = filter;
        const allBtn = document.getElementById('devFilterAll');
        const unreadBtn = document.getElementById('devFilterUnread');

        if (filter === 'all') {
            allBtn.className = 'rounded-full bg-violet-600 px-3 py-1 text-[11px] font-bold text-white transition-all cursor-pointer';
            unreadBtn.className = 'rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-600 hover:bg-slate-200 transition-all cursor-pointer';
        } else {
            unreadBtn.className = 'rounded-full bg-violet-600 px-3 py-1 text-[11px] font-bold text-white transition-all cursor-pointer';
            allBtn.className = 'rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-600 hover:bg-slate-200 transition-all cursor-pointer';
        }
        renderDevRooms();
    }

    function handleDevSearch(val) {
        devState.searchQuery = (val || '').trim().toLowerCase();
        if (devState.activeTab === 'rooms') {
            renderDevRooms();
        } else {
            filterDevMitraDirectory(devState.searchQuery);
        }
    }

    function filterDevMitraDirectory(query) {
        clearTimeout(devState.searchTimer);
        devState.searchTimer = setTimeout(async () => {
            try {
                updateDevLoadMoreButtonState('loading');
                const res = await fetch(`${DEV_CHAT_CONFIG.mitrasUrl}?q=${encodeURIComponent(query || '')}&offset=0&limit=50`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const data = await res.json();
                if (!data.success) return;
                const list = data.mitras || [];
                const cont = document.getElementById('devMitrasList');
                if (!cont) return;

                devState.mitraOffset = data.offset || list.length;
                devState.mitraHasMore = !!data.has_more;

                if (list.length === 0) {
                    cont.innerHTML = `<div class="p-8 text-center text-xs text-slate-400">Tidak ada mitra ditemukan.</div>`;
                    updateDevLoadMoreButtonState('empty');
                    return;
                }
                cont.innerHTML = list.map(renderDevMitraItem).join('');
                updateDevLoadMoreButtonState(devState.mitraHasMore ? 'ready' : 'empty');
            } catch (e) {
                console.error(e);
                updateDevLoadMoreButtonState('empty');
            }
        }, 250);
    }

    function openMitraModal() {
        const modal = document.getElementById('mitraSelectorModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeMitraModal() {
        const modal = document.getElementById('mitraSelectorModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function filterModalMitras(query) {
        devState.modalMitraQuery = (query || '').trim();
        clearTimeout(devState.searchTimer);
        devState.searchTimer = setTimeout(async () => {
            try {
                updateModalLoadMoreButtonState('loading');
                const res = await fetch(`${DEV_CHAT_CONFIG.mitrasUrl}?q=${encodeURIComponent(devState.modalMitraQuery)}&offset=0&limit=50`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const data = await res.json();
                if (!data.success) return;
                const list = data.mitras || [];
                const grid = document.getElementById('modalMitrasGrid');
                if (!grid) return;

                devState.modalMitraOffset = data.offset || list.length;
                devState.modalMitraHasMore = !!data.has_more;

                if (list.length === 0) {
                    grid.innerHTML = `<div class="col-span-2 p-8 text-center text-xs text-slate-400">Tidak ada pengguna atau mitra ditemukan.</div>`;
                    updateModalLoadMoreButtonState('empty');
                    return;
                }
                grid.innerHTML = list.map(renderModalMitraCard).join('');
                updateModalLoadMoreButtonState(devState.modalMitraHasMore ? 'ready' : 'empty');
            } catch (e) {
                console.error(e);
                updateModalLoadMoreButtonState('empty');
            }
        }, 250);
    }

    async function fetchDevRooms() {
        try {
            const res = await fetch(DEV_CHAT_CONFIG.roomsUrl, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const data = await res.json();
            if (!data.success) return;

            devState.rooms = data.rooms || [];
            updateDevBadges(data.total_unread || 0, devState.rooms.length);
            renderDevRooms();
        } catch (err) {
            console.error('Error loading developer chat rooms:', err);
        }
    }

    function updateDevBadges(totalUnread, totalRooms) {
        const statRooms = document.getElementById('statTotalRooms');
        const statUnread = document.getElementById('statTotalUnread');
        if (statRooms) statRooms.textContent = totalRooms;
        if (statUnread) statUnread.textContent = totalUnread;

        const tabBadge = document.getElementById('devSidebarUnreadBadge');
        if (tabBadge) {
            if (totalUnread > 0) {
                tabBadge.textContent = totalUnread > 99 ? '99+' : totalUnread;
                tabBadge.classList.remove('hidden');
            } else {
                tabBadge.classList.add('hidden');
            }
        }

        const sidebarMenuBadge = document.getElementById('sidebar-dev-chat-badge');
        if (sidebarMenuBadge) {
            if (totalUnread > 0) {
                sidebarMenuBadge.textContent = totalUnread > 99 ? '99+' : totalUnread;
                sidebarMenuBadge.classList.remove('hidden');
                sidebarMenuBadge.classList.add('inline-flex');
            } else {
                sidebarMenuBadge.classList.add('hidden');
                sidebarMenuBadge.classList.remove('inline-flex');
            }
        }

        const navbarChatBadge = document.getElementById('navbar-dev-chat-badge');
        if (navbarChatBadge) {
            if (totalUnread > 0) {
                navbarChatBadge.textContent = totalUnread > 99 ? '99+' : totalUnread;
                navbarChatBadge.classList.remove('hidden');
                navbarChatBadge.classList.add('flex');
            } else {
                navbarChatBadge.classList.add('hidden');
                navbarChatBadge.classList.remove('flex');
            }
        }
    }

    function renderDevRooms() {
        const container = document.getElementById('devRoomsContainer');
        if (!container) return;

        let filtered = devState.rooms.filter(room => {
            if (devState.roomFilter === 'unread' && room.unread_count <= 0) return false;
            if (devState.searchQuery) {
                const name = (room.user?.name || '').toLowerCase();
                const store = (room.user?.name_store || '').toLowerCase();
                const last = (room.last_message || '').toLowerCase();
                return name.includes(devState.searchQuery) || store.includes(devState.searchQuery) || last.includes(devState.searchQuery);
            }
            return true;
        });

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="p-8 text-center">
                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <i class="bi bi-chat-square-text text-2xl"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-700">
                        ${devState.roomFilter === 'unread' ? 'Tidak ada pesan yang belum dibaca' : 'Belum ada pesan masuk untuk Anda'}
                    </p>
                    <p class="mt-1 mb-4 text-[11px] text-slate-400">
                        Ketika Customer / Mitra memilih Anda sebagai Admin tujuan dan mengirim pesan, percakapan akan langsung muncul di sini.
                    </p>
                    <button type="button" onclick="switchDevTab('mitras')"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-violet-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-violet-700 transition-colors cursor-pointer">
                        <i class="bi bi-shop"></i> Lihat Daftar Mitra
                    </button>
                </div>
            `;
            return;
        }

        container.innerHTML = filtered.map(room => {
            const isActive = Number(devState.activePartnerId) === Number(room.partner_id);
            const activeClasses = isActive
                ? 'bg-violet-50/90 border-l-4 border-l-violet-600'
                : 'hover:bg-slate-50 border-l-4 border-l-transparent';

            let checkIcon = '';
            if (room.is_last_mine) {
                checkIcon = room.is_read_last
                    ? '<i class="bi bi-check2-all text-sky-500 text-sm shrink-0" title="Dibaca oleh Mitra"></i>'
                    : '<i class="bi bi-check2 text-slate-400 text-sm shrink-0" title="Terkirim"></i>';
            }

            const unreadBadge = room.unread_count > 0
                ? `<span class="rounded-full bg-rose-500 px-2 py-0.5 text-[10px] font-extrabold text-white shrink-0 shadow-2xs">${room.unread_count}</span>`
                : '';

            const storeLabel = room.user?.name_store
                ? `<i class="bi bi-shop mr-1"></i>${escapeDevHtml(room.user.name_store)}`
                : 'Customer / Mitra KampSewa';

            return `
                <div onclick="selectPartner(${room.partner_id})"
                    class="flex items-center gap-3 p-3.5 transition-all cursor-pointer ${activeClasses}">
                    ${renderDevAvatar(room.user, 'h-12 w-12', 'rounded-2xl', 'text-sm')}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <h4 class="text-xs sm:text-sm font-extrabold ${isActive ? 'text-violet-700' : 'text-slate-900'} truncate">
                                ${escapeDevHtml(room.user.name)}
                            </h4>
                            <span class="text-[10px] font-semibold ${room.unread_count > 0 ? 'text-violet-600 font-bold' : 'text-slate-400'} shrink-0" title="${escapeDevHtml(room.last_message_full_time)}">
                                ${escapeDevHtml(room.last_message_time)}
                            </span>
                        </div>
                        <p class="text-[10px] font-bold text-violet-600/85 truncate">
                            ${storeLabel}
                        </p>
                        <div class="mt-1 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1 min-w-0">
                                ${checkIcon}
                                <p class="text-xs ${room.unread_count > 0 ? 'font-bold text-slate-900' : 'text-slate-500'} truncate">
                                    ${escapeDevHtml(room.last_message)}
                                </p>
                            </div>
                            ${unreadBadge}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    async function selectPartner(partnerId) {
        devState.activePartnerId = Number(partnerId);
        devState.messagesSignature = '';

        document.getElementById('devEmptyState').classList.add('hidden');
        const activeChat = document.getElementById('devActiveChat');
        activeChat.classList.remove('hidden');
        activeChat.classList.add('flex');

        const localMitra = DEV_CHAT_CONFIG.mitras.find(m => Number(m.id) === devState.activePartnerId);
        if (localMitra) {
            updateDevHeader(localMitra);
        }

        renderDevRooms();
        await fetchDevMessages(true);
        await fetchDevRooms();
    }

    function closeDevMobileChat() {
        devState.activePartnerId = null;
        document.getElementById('devActiveChat').classList.add('hidden');
        document.getElementById('devActiveChat').classList.remove('flex');
        document.getElementById('devEmptyState').classList.remove('hidden');
        renderDevRooms();
    }

    function updateDevHeader(partner) {
        document.getElementById('devHeaderAvatar').innerHTML = renderDevAvatar(partner, 'h-11 w-11', 'rounded-2xl', 'text-xs');
        document.getElementById('devHeaderName').textContent = partner.name || 'Mitra';
        document.getElementById('devHeaderRoleBadge').textContent = partner.role_label || 'Customer / Mitra';
        document.getElementById('devHeaderEmail').innerHTML = `<i class="bi bi-envelope mr-1"></i>${escapeDevHtml(partner.email || '-')}`;
        document.getElementById('devHeaderPhone').innerHTML = `<i class="bi bi-telephone mr-1"></i>${escapeDevHtml(partner.nomor_telephone || '-')}`;
        document.getElementById('devHeaderProfileLink').href = `${DEV_CHAT_CONFIG.detailUserBaseUrl}/${partner.id}`;
    }

    async function fetchDevMessages(forceScroll = false) {
        if (!devState.activePartnerId) return;
        try {
            const url = `${DEV_CHAT_CONFIG.messagesBaseUrl}/${devState.activePartnerId}`;
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const data = await res.json();
            if (!data.success) return;

            if (data.partner) {
                updateDevHeader(data.partner);
            }

            renderDevMessagesTimeline(data.messages || [], forceScroll);
        } catch (err) {
            console.error('Error loading messages:', err);
        }
    }

    function renderDevMessagesTimeline(messages, forceScroll = false) {
        const viewport = document.getElementById('devMessagesViewport');
        if (!viewport) return;

        const newSig = JSON.stringify(messages.map(m => `${m.id}:${m.is_read}`));
        if (newSig === devState.messagesSignature && !forceScroll) {
            return;
        }

        const isNearBottom = (viewport.scrollHeight - viewport.scrollTop - viewport.clientHeight) < 120;
        devState.messagesSignature = newSig;

        if (messages.length === 0) {
            viewport.innerHTML = `
                <div class="h-full flex flex-col items-center justify-center text-center py-12 px-4">
                    <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-violet-50 text-violet-600 shadow-2xs">
                        <i class="bi bi-chat-dots text-2xl"></i>
                    </div>
                    <p class="text-sm font-black text-slate-800">Belum Ada Riwayat Pesan</p>
                    <p class="mt-1 max-w-sm text-xs text-slate-500">
                        Kirim pesan di bawah untuk memulai percakapan langsung dengan Mitra / Customer ini.
                    </p>
                </div>
            `;
            return;
        }

        let html = '';
        let lastDateKey = null;

        messages.forEach(msg => {
            if (msg.date_key !== lastDateKey) {
                lastDateKey = msg.date_key;
                html += `
                    <div class="flex justify-center my-3">
                        <span class="rounded-full border border-slate-200/80 bg-white/90 px-3.5 py-1 text-[11px] font-bold text-slate-500 shadow-2xs">
                            ${escapeDevHtml(msg.date_label)}
                        </span>
                    </div>
                `;
            }

            if (msg.is_mine) {
                const readBadge = msg.is_read
                    ? `<span class="inline-flex items-center gap-0.5 text-sky-200 font-bold" title="Dibaca oleh Mitra"><i class="bi bi-check2-all text-sm"></i> Dibaca</span>`
                    : `<span class="inline-flex items-center gap-0.5 text-violet-200" title="Terkirim"><i class="bi bi-check2 text-sm"></i> Terkirim</span>`;

                html += `
                    <div class="flex justify-end">
                        <div class="max-w-[82%] sm:max-w-[70%] rounded-2xl rounded-tr-xs bg-gradient-to-br from-[#7C3AED] to-[#5038ED] text-white px-4 py-2.5 shadow-sm">
                            <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-wrap break-words">${escapeDevHtml(msg.message_text)}</p>
                            <div class="mt-1.5 flex items-center justify-end gap-2 text-[10px] text-violet-100">
                                <span title="${escapeDevHtml(msg.full_datetime)}">${escapeDevHtml(msg.time)}</span>
                                ${readBadge}
                            </div>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="flex justify-start">
                        <div class="max-w-[82%] sm:max-w-[70%] rounded-2xl rounded-tl-xs border border-slate-200/80 bg-white text-slate-800 px-4 py-2.5 shadow-2xs">
                            <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-wrap break-words">${escapeDevHtml(msg.message_text)}</p>
                            <div class="mt-1.5 flex items-center justify-end gap-1.5 text-[10px] text-slate-400">
                                <span title="${escapeDevHtml(msg.full_datetime)}">${escapeDevHtml(msg.time)}</span>
                            </div>
                        </div>
                    </div>
                `;
            }
        });

        viewport.innerHTML = html;

        if (forceScroll || isNearBottom) {
            viewport.scrollTop = viewport.scrollHeight;
        }
    }

    function fillDevQuickReply(text) {
        const input = document.getElementById('devMessageInput');
        if (!input) return;
        input.value = text;
        autoResizeDevTextarea(input);
        input.focus();
    }

    function autoResizeDevTextarea(el) {
        el.style.height = 'auto';
        if (el.scrollHeight > 128) {
            el.style.overflowY = 'auto';
            el.style.height = '128px';
            el.classList.add('dev-chat-scrollbar');
        } else {
            el.style.overflowY = 'hidden';
            el.style.height = Math.max(el.scrollHeight, 46) + 'px';
            el.classList.remove('dev-chat-scrollbar');
        }
    }

    function handleDevInputKeydown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            submitDevMessage(e);
        }
    }

    async function submitDevMessage(e) {
        if (e) e.preventDefault();
        if (devState.isSending || !devState.activePartnerId) return;

        const input = document.getElementById('devMessageInput');
        const text = (input.value || '').trim();
        if (!text) return;

        devState.isSending = true;
        const btn = document.getElementById('devSendBtn');
        const icon = document.getElementById('devSendIcon');
        btn.disabled = true;
        icon.className = 'bi bi-arrow-repeat animate-spin text-xs';

        try {
            const res = await fetch(DEV_CHAT_CONFIG.sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': DEV_CHAT_CONFIG.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    receiver_id: devState.activePartnerId,
                    message: text,
                }),
            });

            if (res.ok) {
                input.value = '';
                input.style.height = 'auto';
                input.style.overflowY = 'hidden';
                input.classList.remove('dev-chat-scrollbar');
                await fetchDevMessages(true);
                await fetchDevRooms();
            }
        } catch (err) {
            console.error('Error sending developer message:', err);
        } finally {
            devState.isSending = false;
            btn.disabled = false;
            icon.className = 'bi bi-send-fill text-xs';
            input.focus();
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        await fetchDevRooms();

        if (DEV_CHAT_CONFIG.initialPartnerId) {
            await selectPartner(DEV_CHAT_CONFIG.initialPartnerId);
        } else if (devState.rooms.length > 0) {
            await selectPartner(devState.rooms[0].partner_id);
        }

        devState.pollTimer = setInterval(async () => {
            await fetchDevRooms();
            if (devState.activePartnerId) {
                await fetchDevMessages(false);
            }
        }, 2500);
    });
</script>
@endpush
@endsection
