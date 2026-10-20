@extends('layouts.customers.layouts-customer')

@section('customer-content')
<style>
    .chat-scrollbar {
        overflow-y: auto !important;
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, 0.45) transparent;
        -webkit-overflow-scrolling: touch;
        touch-action: pan-y;
    }
    .chat-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .chat-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .chat-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.35);
        border-radius: 9999px;
    }
    .chat-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(100, 116, 139, 0.55);
    }
    .chat-bg-pattern {
        background-color: #f8fafc;
        background-image: radial-gradient(#cbd5e1 0.75px, transparent 0.75px);
        background-size: 20px 20px;
    }
    /* Hilangkan resizer & garis pojok kanan pada input */
    #chatMessageInput,
    .no-corner-indicator {
        resize: none !important;
        outline: none !important;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }
    #chatMessageInput::-webkit-resizer,
    #chatMessageInput::-webkit-scrollbar,
    #chatMessageInput::-webkit-scrollbar-corner,
    #chatMessageInput::-webkit-scrollbar-thumb,
    #chatMessageInput::-webkit-scrollbar-track,
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

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-5">
    {{-- Top Page Banner & Admin Quick Selector --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-4 sm:p-5 mb-5">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-4 border-b border-slate-100">
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/25 shrink-0">
                    <i class="bi bi-chat-dots-fill text-2xl"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            Live Support Realtime
                        </span>
                        <span class="text-xs font-semibold text-slate-400">Mitra Toko &harr; Admin Developer</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">
                        Pusat Bantuan &amp; Chat Admin KampSewa
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Pilih Admin yang ingin Anda hubungi. Pesan Anda akan langsung tampil secara real-time di dashboard Admin tujuan.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 self-start lg:self-center">
                <div class="px-3.5 py-2 rounded-2xl bg-slate-50 border border-slate-200/70 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                        {{ $total_admins_count ?? count($admins) }}
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-700 leading-tight">Admin Tersedia</p>
                        <p class="text-[10px] text-slate-400">Siap membantu Anda</p>
                    </div>
                </div>

                <button type="button" onclick="openAdminModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5 cursor-pointer">
                    <i class="bi bi-person-plus-fill text-base"></i>
                    <span>Pilih Admin Tujuan</span>
                </button>
            </div>
        </div>

        {{-- Horizontal Quick Admin Cards Strip --}}
        <div class="pt-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <i class="bi bi-people-fill text-blue-600 text-sm"></i>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-600">
                        Daftar Admin Support (Klik untuk Mulai Chat)
                    </h2>
                </div>
                <span class="text-[11px] text-slate-400 hidden sm:inline">Geser untuk melihat semua admin &rarr;</span>
            </div>

            <div class="flex items-center gap-3.5 overflow-x-auto chat-scrollbar py-3.5 px-1.5">
                @foreach ($admins as $admin)
                    <button type="button"
                        onclick="selectAdminPartner({{ $admin['id'] }})"
                        data-admin-strip-id="{{ $admin['id'] }}"
                        class="admin-strip-card group relative flex items-center gap-3.5 py-3.5 px-4 rounded-2xl border border-slate-200/90 bg-white hover:bg-blue-50/50 hover:border-blue-300 min-h-[90px] transition-all duration-200 shrink-0 text-left shadow-2xs cursor-pointer">
                        <div class="relative shrink-0">
                            @if ($admin['has_photo'] && $admin['avatar_url'])
                                <img src="{{ $admin['avatar_url'] }}" alt="{{ $admin['name'] }}"
                                    class="w-12 h-12 rounded-2xl object-cover ring-2 ring-slate-100 shadow-xs"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="display:none;"
                                    class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $admin['gradient'] }} text-white font-extrabold text-sm items-center justify-center ring-2 ring-slate-100 shadow-xs">
                                    {{ $admin['initials'] }}
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $admin['gradient'] }} text-white font-extrabold text-sm flex items-center justify-center ring-2 ring-slate-100 shadow-xs">
                                    {{ $admin['initials'] }}
                                </div>
                            @endif
                            <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white shadow-xs" title="Aktif"></span>
                        </div>
                        <div class="min-w-[130px] max-w-[175px]">
                            <div class="flex items-center gap-1.5">
                                <p class="admin-strip-name text-xs sm:text-sm font-bold text-slate-800 group-hover:text-blue-600 truncate transition-colors leading-tight">
                                    {{ $admin['name'] }}
                                </p>
                            </div>
                            <p class="text-[11px] font-medium text-slate-400 truncate mt-0.5 leading-tight">
                                {{ $admin['email'] }}
                            </p>
                            <div class="mt-2">
                                <span class="admin-strip-pill inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200/60 transition-all">
                                    <i class="bi bi-chat-text-fill text-[9px]"></i>
                                    <span class="admin-strip-pill-text">Chat Sekarang</span>
                                </span>
                            </div>
                        </div>
                        <span id="strip-unread-{{ $admin['id'] }}"
                            class="{{ ($admin['unread_count'] ?? 0) > 0 ? 'flex' : 'hidden' }} absolute -top-2 -right-1.5 h-5 min-w-[20px] px-1.5 rounded-full bg-rose-500 text-white text-[10px] font-extrabold items-center justify-center border-2 border-white shadow-md">
                            {{ $admin['unread_count'] ?? 0 }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Main Split-Pane Messenger Container --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[580px] h-[calc(100vh-270px)] max-h-[780px]">

        {{-- Left Column: Room List & Admin Selector --}}
        <div id="chatSidebarPane" class="lg:col-span-4 border-r border-slate-200/80 flex flex-col h-full min-h-0 max-h-full overflow-hidden bg-white">
            {{-- Sidebar Header --}}
            <div class="p-4 border-b border-slate-100 space-y-3 shrink-0">
                {{-- Mode Switcher Tabs --}}
                <div class="grid grid-cols-2 gap-1.5 p-1 bg-slate-100 rounded-2xl">
                    <button type="button" id="tabBtnRooms" onclick="switchSidebarTab('rooms')"
                        class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-extrabold transition-all bg-white text-blue-600 shadow-xs cursor-pointer">
                        <i class="bi bi-chat-left-text-fill"></i>
                        <span>Percakapan</span>
                        <span id="sidebarTotalUnreadBadge" class="hidden px-1.5 py-0.2 rounded-full text-[10px] bg-rose-500 text-white font-black">0</span>
                    </button>
                    <button type="button" id="tabBtnAdmins" onclick="switchSidebarTab('admins')"
                        class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-extrabold transition-all text-slate-600 hover:text-slate-900 cursor-pointer">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>Pilih Admin ({{ $total_admins_count ?? count($admins) }})</span>
                    </button>
                </div>

                {{-- Search Bar --}}
                <div class="relative">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="sidebarSearchInput" oninput="handleSidebarSearch(this.value)"
                        placeholder="Cari nama admin atau isi pesan..."
                        spellcheck="false" autocomplete="off" autocorrect="off"
                        class="w-full pl-9 pr-8 py-2.5 text-xs font-medium bg-slate-50 border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all">
                </div>

                {{-- Filter Pills (Only visible on Rooms tab) --}}
                <div id="roomFilterBar" class="flex items-center justify-between gap-2 pt-0.5">
                    <div class="flex items-center gap-1.5">
                        <button type="button" id="filterAllBtn" onclick="setRoomFilter('all')"
                            class="px-3 py-1 rounded-full text-[11px] font-bold bg-blue-600 text-white transition-all cursor-pointer">
                            Semua Chat
                        </button>
                        <button type="button" id="filterUnreadBtn" onclick="setRoomFilter('unread')"
                            class="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all cursor-pointer">
                            Belum Dibaca
                        </button>
                    </div>
                    <span id="pollingStatusIndicator" class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terhubung
                    </span>
                </div>
            </div>

            {{-- Rooms List Container --}}
            <div id="roomsListContainer" class="flex-1 min-h-0 max-h-full overflow-y-auto overscroll-contain chat-scrollbar divide-y divide-slate-100">
                <div class="p-8 text-center text-slate-400">
                    <div class="w-10 h-10 border-2 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="text-xs font-semibold">Memuat daftar percakapan...</p>
                </div>
            </div>

            {{-- Admins Directory List Container (Hidden by default) --}}
            <div id="adminsListContainer" class="hidden flex-1 min-h-0 max-h-full overflow-y-auto overscroll-contain chat-scrollbar">
                <div id="adminsDirectoryList" class="divide-y divide-slate-100">
                    @foreach ($admins as $admin)
                        <div data-admin-search="{{ strtolower($admin['name'] . ' ' . $admin['email'] . ' ' . $admin['nomor_telephone']) }}"
                            data-admin-id="{{ $admin['id'] }}"
                            onclick="selectAdminPartner({{ $admin['id'] }})"
                            class="admin-directory-item p-3.5 hover:bg-blue-50/50 transition-colors cursor-pointer flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="relative shrink-0">
                                    @if ($admin['has_photo'] && $admin['avatar_url'])
                                        <img src="{{ $admin['avatar_url'] }}" alt="{{ $admin['name'] }}"
                                            class="w-12 h-12 rounded-2xl object-cover border border-slate-200 shadow-xs"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="display:none;"
                                            class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $admin['gradient'] }} text-white font-extrabold text-sm items-center justify-center shadow-xs">
                                            {{ $admin['initials'] }}
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $admin['gradient'] }} text-white font-extrabold text-sm flex items-center justify-center shadow-xs">
                                            {{ $admin['initials'] }}
                                        </div>
                                    @endif
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white"></span>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="text-sm font-bold text-slate-900 truncate">{{ $admin['name'] }}</h4>
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold bg-blue-100 text-blue-700">ADMIN</span>
                                    </div>
                                    <p class="text-xs text-slate-500 truncate mt-0.5">
                                        <i class="bi bi-envelope text-[10px] mr-1"></i>{{ $admin['email'] }}
                                    </p>
                                    <p class="text-[11px] text-slate-400 truncate mt-0.5">
                                        <i class="bi bi-telephone text-[10px] mr-1"></i>{{ $admin['nomor_telephone'] }}
                                    </p>
                                </div>
                            </div>
                            <button type="button"
                                class="shrink-0 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white text-xs font-bold transition-colors">
                                Chat
                            </button>
                        </div>
                    @endforeach
                </div>
                <div id="adminsLoadMoreContainer" class="p-3.5 text-center border-t border-slate-100 bg-slate-50/60">
                    <button type="button" id="btnLoadMoreAdmins" onclick="loadMoreAdmins()"
                        @if(empty($has_more_admins)) disabled @endif
                        class="w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 {{ empty($has_more_admins) ? 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200/60' : 'bg-white hover:bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs hover:border-blue-300 cursor-pointer' }}">
                        <span id="btnLoadMoreAdminsText">{{ empty($has_more_admins) ? 'Tidak ada lagi data' : 'Lihat Sebagian Lagi' }}</span>
                        <i id="btnLoadMoreAdminsSpinner" class="bi bi-arrow-repeat hidden animate-spin"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Right Column: Active Conversation Window --}}
        <div id="chatConversationPane" class="lg:col-span-8 flex flex-col h-full min-h-0 max-h-full overflow-hidden bg-slate-50/50 relative">

            {{-- Empty State (When No Admin Selected) --}}
            <div id="emptyChatState" class="flex-1 min-h-0 max-h-full flex flex-col items-center justify-start p-6 sm:p-10 text-center overflow-y-auto overscroll-contain chat-scrollbar">
                <div class="my-auto w-full flex flex-col items-center">
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-blue-500/10 to-indigo-500/20 border border-blue-200/60 flex items-center justify-center text-blue-600 mb-4 shadow-inner">
                        <i class="bi bi-chat-heart-fill text-4xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-extrabold text-slate-900">
                        Pilih Admin Untuk Memulai Konsultasi / Chat
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-md mt-1 mb-6">
                        Silakan pilih salah satu Admin KampSewa di bawah ini. Pesan Anda akan langsung diterima oleh Admin yang bersangkutan secara real-time.
                    </p>

                    <div class="w-full max-w-2xl grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                        @foreach ($admins->take(6) as $admin)
                            <div onclick="selectAdminPartner({{ $admin['id'] }})"
                                class="group p-3.5 rounded-2xl bg-white border border-slate-200/80 hover:border-blue-400 hover:shadow-md transition-all flex items-center justify-between gap-3 cursor-pointer">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="relative shrink-0">
                                        @if ($admin['has_photo'] && $admin['avatar_url'])
                                            <img src="{{ $admin['avatar_url'] }}" alt="{{ $admin['name'] }}"
                                                class="w-12 h-12 rounded-2xl object-cover border border-slate-100"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div style="display:none;"
                                                class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $admin['gradient'] }} text-white font-extrabold text-sm items-center justify-center">
                                                {{ $admin['initials'] }}
                                            </div>
                                        @else
                                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $admin['gradient'] }} text-white font-extrabold text-sm flex items-center justify-center">
                                                {{ $admin['initials'] }}
                                            </div>
                                        @endif
                                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-blue-600 truncate transition-colors">
                                            {{ $admin['name'] }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $admin['role_label'] }}</p>
                                        <p class="text-[10px] text-slate-400 truncate">{{ $admin['email'] }}</p>
                                    </div>
                                </div>
                                <span class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center transition-colors shrink-0">
                                    <i class="bi bi-send-fill text-xs"></i>
                                </span>
                            </div>
                        @endforeach
                    </div>

                    @if (count($admins) > 6)
                        <button type="button" onclick="openAdminModal()"
                            class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-blue-600 hover:bg-blue-50 transition-colors cursor-pointer">
                            <span>Lihat Semua {{ count($admins) }} Admin</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Active Conversation Content (Hidden until an Admin is selected) --}}
            <div id="activeChatWrapper" class="hidden flex-col h-full min-h-0 max-h-full overflow-hidden">
                {{-- Active Chat Header --}}
                <div class="px-4 sm:px-6 py-3.5 bg-white border-b border-slate-200/80 flex items-center justify-between gap-3 shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <button type="button" onclick="closeMobileChatPane()"
                            class="lg:hidden w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-slate-200">
                            <i class="bi bi-arrow-left"></i>
                        </button>

                        <div id="headerAdminAvatar" class="relative shrink-0"></div>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <h3 id="headerAdminName" class="text-sm sm:text-base font-extrabold text-slate-900 truncate">-</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-700 shrink-0">
                                    Admin Developer
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-0.5 text-[11px] text-slate-500 mt-0.5">
                                <span class="inline-flex items-center gap-1 text-emerald-600 font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Online &bull; Siap Membantu
                                </span>
                                <span id="headerAdminEmail" class="hidden sm:inline truncate"></span>
                                <span id="headerAdminPhone" class="hidden md:inline truncate"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button" onclick="openAdminModal()"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 hover:border-blue-300 hover:bg-blue-50 text-xs font-bold text-slate-700 hover:text-blue-600 transition-all cursor-pointer">
                            <i class="bi bi-arrow-left-right text-xs"></i>
                            <span class="hidden sm:inline">Ganti Admin</span>
                        </button>
                    </div>
                </div>

                {{-- Messages Timeline --}}
                <div id="messagesViewport" class="flex-1 min-h-0 max-h-full overflow-y-auto overscroll-contain chat-scrollbar chat-bg-pattern p-4 sm:p-6 space-y-4">
                </div>

                {{-- Quick Template Chips --}}
                <div class="px-4 py-2 bg-white/95 border-t border-slate-100 flex items-center gap-2 overflow-x-auto chat-scrollbar shrink-0">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider shrink-0">Pesan Cepat:</span>
                    <button type="button" onclick="fillQuickMessage('Halo Admin, saya butuh bantuan terkait pengelolaan toko saya.')"
                        class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-[11px] font-medium text-slate-600 whitespace-nowrap transition-colors cursor-pointer">
                        👋 Bantuan Toko Mitra
                    </button>
                    <button type="button" onclick="fillQuickMessage('Halo Admin, mohon informasi terkait status verifikasi promosi/iklan saya.')"
                        class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-[11px] font-medium text-slate-600 whitespace-nowrap transition-colors cursor-pointer">
                        📢 Status Iklan Promosi
                    </button>
                    <button type="button" onclick="fillQuickMessage('Halo Admin, saya ingin berkonsultasi mengenai transaksi penyewaan pelanggan.')"
                        class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-[11px] font-medium text-slate-600 whitespace-nowrap transition-colors cursor-pointer">
                        📦 Kendala Penyewaan
                    </button>
                </div>

                {{-- Message Input Composer --}}
                <form id="sendMessageForm" onsubmit="submitChatMessage(event)" class="p-3.5 sm:p-4 bg-white border-t border-slate-200/80 shrink-0">
                    <div class="flex items-end gap-2.5">
                        <div class="flex-1 relative">
                            <textarea id="chatMessageInput" rows="1"
                                placeholder="Ketik pesan untuk Admin..."
                                onkeydown="handleInputKeydown(event)"
                                oninput="autoResizeTextarea(this)"
                                spellcheck="false"
                                autocomplete="off"
                                autocorrect="off"
                                autocapitalize="off"
                                data-gramm="false"
                                data-enable-grammarly="false"
                                writingsuggestions="false"
                                style="overflow-y: hidden; resize: none;"
                                class="w-full px-4 py-3 text-xs sm:text-sm text-slate-800 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white resize-none max-h-32 transition-all no-corner-indicator overflow-hidden"></textarea>
                        </div>
                        <button type="submit" id="sendMsgBtn"
                            class="h-11 px-5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md shadow-blue-500/25 transition-all hover:-translate-y-0.5 disabled:opacity-50 cursor-pointer shrink-0">
                            <span>Kirim</span>
                            <i id="sendMsgIcon" class="bi bi-send-fill text-xs"></i>
                        </button>
                    </div>
                    <div class="flex items-center justify-between mt-1.5 px-1 text-[10px] text-slate-400">
                        <span>Tekan <strong>Enter</strong> untuk mengirim, <strong>Shift + Enter</strong> untuk baris baru</span>
                        <span class="inline-flex items-center gap-1">
                            <i class="bi bi-check2-all text-sky-500 text-xs"></i> Centang biru menandakan pesan telah dibaca Admin
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Pilih Admin Support --}}
<div id="adminSelectorModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-2xl overflow-hidden animate-fadeIn">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-blue-50/70 via-white to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-sm">
                    <i class="bi bi-people-fill text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Pilih Admin KampSewa</h3>
                    <p class="text-xs text-slate-500">Pilih admin yang ingin Anda ajak berdiskusi secara langsung</p>
                </div>
            </div>
            <button type="button" onclick="closeAdminModal()"
                class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors cursor-pointer">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <div class="relative">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="modalAdminSearch" oninput="filterModalAdmins(this.value)"
                    placeholder="Cari nama admin, email, atau nomor telepon..."
                    spellcheck="false" autocomplete="off" autocorrect="off"
                    class="w-full pl-9 pr-4 py-2.5 text-xs font-medium bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
        </div>

        <div id="modalAdminsScrollArea" class="max-h-[460px] overflow-y-auto chat-scrollbar">
            <div id="modalAdminsGrid" class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($admins as $admin)
                    <div data-modal-admin="{{ strtolower($admin['name'] . ' ' . $admin['email'] . ' ' . $admin['nomor_telephone']) }}"
                        onclick="selectAdminPartner({{ $admin['id'] }}); closeAdminModal();"
                        class="modal-admin-card group p-4 rounded-2xl border border-slate-200/80 hover:border-blue-400 hover:bg-blue-50/30 transition-all flex items-start gap-3.5 cursor-pointer">
                        <div class="relative shrink-0">
                            @if ($admin['has_photo'] && $admin['avatar_url'])
                                <img src="{{ $admin['avatar_url'] }}" alt="{{ $admin['name'] }}"
                                    class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-xs"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="display:none;"
                                    class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $admin['gradient'] }} text-white font-extrabold text-base items-center justify-center shadow-xs">
                                    {{ $admin['initials'] }}
                                </div>
                            @else
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $admin['gradient'] }} text-white font-extrabold text-base flex items-center justify-center shadow-xs">
                                    {{ $admin['initials'] }}
                                </div>
                            @endif
                            <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white"></span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 truncate transition-colors">
                                    {{ $admin['name'] }}
                                </h4>
                            </div>
                            <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-100/80 text-blue-700">
                                {{ $admin['role_label'] }}
                            </span>
                            <p class="text-[11px] text-slate-500 truncate mt-1.5">
                                <i class="bi bi-envelope mr-1 text-slate-400"></i>{{ $admin['email'] }}
                            </p>
                            <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                <i class="bi bi-whatsapp mr-1 text-emerald-500"></i>{{ $admin['nomor_telephone'] }}
                            </p>
                            <div class="mt-2.5 flex items-center justify-end">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-600 text-white text-[11px] font-bold group-hover:bg-indigo-600 transition-colors">
                                    <i class="bi bi-chat-dots-fill"></i> Mulai Chat
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="modalAdminsLoadMoreContainer" class="p-4 pt-1 text-center bg-slate-50/50 border-t border-slate-100">
                <button type="button" id="btnModalLoadMoreAdmins" onclick="loadMoreModalAdmins()"
                    @if(empty($has_more_admins)) disabled @endif
                    class="w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 {{ empty($has_more_admins) ? 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200/60' : 'bg-white hover:bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs hover:border-blue-300 cursor-pointer' }}">
                    <span id="btnModalLoadMoreAdminsText">{{ empty($has_more_admins) ? 'Tidak ada lagi data' : 'Lihat Sebagian Lagi' }}</span>
                    <i id="btnModalLoadMoreAdminsSpinner" class="bi bi-arrow-repeat hidden animate-spin"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const CHAT_CONFIG = {
        roomsUrl: @json(route('customer.chat-admin.rooms', ['id_user' => $encrypted_user_id])),
        adminsUrl: @json(route('customer.chat-admin.admins', ['id_user' => $encrypted_user_id])),
        messagesBaseUrl: @json(url('/customer/dashboard/chat-admin/' . $encrypted_user_id . '/messages')),
        sendUrl: @json(route('customer.chat-admin.send', ['id_user' => $encrypted_user_id])),
        csrfToken: @json(csrf_token()),
        admins: @json($admins->values()),
        initialAdminId: @json($selected_admin_id ? (int) $selected_admin_id : null),
        totalAdmins: {{ (int) ($total_admins_count ?? count($admins)) }},
        initialLimit: {{ (int) ($initial_limit ?? 50) }},
        hasMoreAdmins: @json(!empty($has_more_admins)),
    };

    let state = {
        activeAdminId: null,
        activeTab: 'rooms',
        roomFilter: 'all',
        searchQuery: '',
        rooms: [],
        messagesSignature: '',
        isSending: false,
        pollTimer: null,
        searchTimer: null,
        adminOffset: {{ count($admins) }},
        adminHasMore: @json(!empty($has_more_admins)),
        adminLoading: false,
        modalAdminQuery: '',
        modalAdminOffset: {{ count($admins) }},
        modalAdminHasMore: @json(!empty($has_more_admins)),
        modalAdminLoading: false,
    };

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function renderAvatarHtml(user, sizeClass = 'w-12 h-12', roundedClass = 'rounded-2xl', textClass = 'text-sm') {
        const gradient = user.gradient || 'from-blue-600 to-indigo-600';
        const initials = escapeHtml(user.initials || 'AD');
        const name = escapeHtml(user.name || 'Admin');

        if (user.has_photo && user.avatar_url) {
            return `
                <div class="relative shrink-0">
                    <img src="${escapeHtml(user.avatar_url)}" alt="${name}"
                        class="${sizeClass} ${roundedClass} object-cover border border-slate-200 shadow-xs"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none;"
                        class="${sizeClass} ${roundedClass} bg-gradient-to-br ${gradient} text-white font-extrabold ${textClass} items-center justify-center shadow-xs">
                        ${initials}
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white"></span>
                </div>
            `;
        }

        return `
            <div class="relative shrink-0">
                <div class="${sizeClass} ${roundedClass} bg-gradient-to-br ${gradient} text-white font-extrabold ${textClass} flex items-center justify-center shadow-xs">
                    ${initials}
                </div>
                <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white"></span>
            </div>
        `;
    }

    function renderAdminDirectoryItem(admin) {
        const searchKeywords = escapeHtml((admin.name + ' ' + admin.email + ' ' + admin.nomor_telephone).toLowerCase());
        return `
            <div data-admin-search="${searchKeywords}"
                data-admin-id="${admin.id}"
                onclick="selectAdminPartner(${admin.id})"
                class="admin-directory-item p-3.5 hover:bg-blue-50/50 transition-colors cursor-pointer flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    ${renderAvatarHtml(admin, 'w-12 h-12', 'rounded-2xl', 'text-sm')}
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <h4 class="text-sm font-bold text-slate-900 truncate">${escapeHtml(admin.name)}</h4>
                            <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold bg-blue-100 text-blue-700">ADMIN</span>
                        </div>
                        <p class="text-xs text-slate-500 truncate mt-0.5">
                            <i class="bi bi-envelope text-[10px] mr-1"></i>${escapeHtml(admin.email)}
                        </p>
                        <p class="text-[11px] text-slate-400 truncate mt-0.5">
                            <i class="bi bi-telephone text-[10px] mr-1"></i>${escapeHtml(admin.nomor_telephone)}
                        </p>
                    </div>
                </div>
                <button type="button"
                    class="shrink-0 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white text-xs font-bold transition-colors">
                    Chat
                </button>
            </div>
        `;
    }

    function renderModalAdminCard(admin) {
        const searchKeywords = escapeHtml((admin.name + ' ' + admin.email + ' ' + admin.nomor_telephone).toLowerCase());
        return `
            <div data-modal-admin="${searchKeywords}"
                onclick="selectAdminPartner(${admin.id}); closeAdminModal();"
                class="modal-admin-card group p-4 rounded-2xl border border-slate-200/80 hover:border-blue-400 hover:bg-blue-50/30 transition-all flex items-start gap-3.5 cursor-pointer">
                ${renderAvatarHtml(admin, 'w-14 h-14', 'rounded-2xl', 'text-base')}
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 truncate transition-colors">
                            ${escapeHtml(admin.name)}
                        </h4>
                    </div>
                    <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-100/80 text-blue-700">
                        ${escapeHtml(admin.role_label || 'Admin Support')}
                    </span>
                    <p class="text-[11px] text-slate-500 truncate mt-1.5">
                        <i class="bi bi-envelope mr-1 text-slate-400"></i>${escapeHtml(admin.email)}
                    </p>
                    <p class="text-[11px] text-slate-500 truncate mt-0.5">
                        <i class="bi bi-whatsapp mr-1 text-emerald-500"></i>${escapeHtml(admin.nomor_telephone)}
                    </p>
                    <div class="mt-2.5 flex items-center justify-end">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-600 text-white text-[11px] font-bold group-hover:bg-indigo-600 transition-colors">
                            <i class="bi bi-chat-dots-fill"></i> Mulai Chat
                        </span>
                    </div>
                </div>
            </div>
        `;
    }

    function updateAdminLoadMoreButtonState(status) {
        const btn = document.getElementById('btnLoadMoreAdmins');
        const textEl = document.getElementById('btnLoadMoreAdminsText');
        const spinner = document.getElementById('btnLoadMoreAdminsSpinner');
        if (!btn || !textEl) return;

        if (status === 'loading') {
            btn.disabled = true;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-slate-100 text-slate-500 border border-slate-200 cursor-wait';
            textEl.textContent = 'Memuat admin...';
            if (spinner) spinner.classList.remove('hidden');
        } else if (status === 'empty' || !state.adminHasMore) {
            btn.disabled = true;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200/60';
            textEl.textContent = 'Tidak ada lagi data';
            if (spinner) spinner.classList.add('hidden');
        } else {
            btn.disabled = false;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-white hover:bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs hover:border-blue-300 cursor-pointer';
            textEl.textContent = 'Lihat Sebagian Lagi';
            if (spinner) spinner.classList.add('hidden');
        }
    }

    async function loadMoreAdmins() {
        if (state.adminLoading || !state.adminHasMore) return;
        state.adminLoading = true;
        updateAdminLoadMoreButtonState('loading');

        try {
            const query = state.searchQuery || '';
            const offset = state.adminOffset;
            const res = await fetch(`${CHAT_CONFIG.adminsUrl}?q=${encodeURIComponent(query)}&offset=${offset}&limit=50`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();
            if (data.success && data.admins) {
                const listCont = document.getElementById('adminsDirectoryList');
                if (listCont) {
                    const html = data.admins.map(renderAdminDirectoryItem).join('');
                    listCont.insertAdjacentHTML('beforeend', html);
                }
                state.adminOffset = data.offset;
                state.adminHasMore = !!data.has_more;
            }
        } catch (e) {
            console.error('Error loading more admins:', e);
        } finally {
            state.adminLoading = false;
            updateAdminLoadMoreButtonState(state.adminHasMore ? 'ready' : 'empty');
        }
    }

    function updateModalAdminLoadMoreButtonState(status) {
        const btn = document.getElementById('btnModalLoadMoreAdmins');
        const textEl = document.getElementById('btnModalLoadMoreAdminsText');
        const spinner = document.getElementById('btnModalLoadMoreAdminsSpinner');
        if (!btn || !textEl) return;

        if (status === 'loading') {
            btn.disabled = true;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-slate-100 text-slate-500 border border-slate-200 cursor-wait';
            textEl.textContent = 'Memuat admin...';
            if (spinner) spinner.classList.remove('hidden');
        } else if (status === 'empty' || !state.modalAdminHasMore) {
            btn.disabled = true;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200/60';
            textEl.textContent = 'Tidak ada lagi data';
            if (spinner) spinner.classList.add('hidden');
        } else {
            btn.disabled = false;
            btn.className = 'w-full py-2.5 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 bg-white hover:bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs hover:border-blue-300 cursor-pointer';
            textEl.textContent = 'Lihat Sebagian Lagi';
            if (spinner) spinner.classList.add('hidden');
        }
    }

    async function loadMoreModalAdmins() {
        if (state.modalAdminLoading || !state.modalAdminHasMore) return;
        state.modalAdminLoading = true;
        updateModalAdminLoadMoreButtonState('loading');

        try {
            const query = state.modalAdminQuery || '';
            const offset = state.modalAdminOffset;
            const res = await fetch(`${CHAT_CONFIG.adminsUrl}?q=${encodeURIComponent(query)}&offset=${offset}&limit=50`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();
            if (data.success && data.admins) {
                const grid = document.getElementById('modalAdminsGrid');
                if (grid) {
                    const html = data.admins.map(renderModalAdminCard).join('');
                    grid.insertAdjacentHTML('beforeend', html);
                }
                state.modalAdminOffset = data.offset;
                state.modalAdminHasMore = !!data.has_more;
            }
        } catch (e) {
            console.error('Error loading more modal admins:', e);
        } finally {
            state.modalAdminLoading = false;
            updateModalAdminLoadMoreButtonState(state.modalAdminHasMore ? 'ready' : 'empty');
        }
    }

    function switchSidebarTab(tab) {
        state.activeTab = tab;
        const tabBtnRooms = document.getElementById('tabBtnRooms');
        const tabBtnAdmins = document.getElementById('tabBtnAdmins');
        const roomsList = document.getElementById('roomsListContainer');
        const adminsList = document.getElementById('adminsListContainer');
        const filterBar = document.getElementById('roomFilterBar');

        if (tab === 'rooms') {
            tabBtnRooms.className = 'flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-extrabold transition-all bg-white text-blue-600 shadow-xs cursor-pointer';
            tabBtnAdmins.className = 'flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-extrabold transition-all text-slate-600 hover:text-slate-900 cursor-pointer';
            roomsList.classList.remove('hidden');
            adminsList.classList.add('hidden');
            filterBar.classList.remove('hidden');
            renderRoomsList();
        } else {
            tabBtnAdmins.className = 'flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-extrabold transition-all bg-white text-blue-600 shadow-xs cursor-pointer';
            tabBtnRooms.className = 'flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-extrabold transition-all text-slate-600 hover:text-slate-900 cursor-pointer';
            adminsList.classList.remove('hidden');
            roomsList.classList.add('hidden');
            filterBar.classList.add('hidden');
            if (state.searchQuery) {
                filterDirectoryAdmins(state.searchQuery);
            }
        }
    }

    function setRoomFilter(filter) {
        state.roomFilter = filter;
        const allBtn = document.getElementById('filterAllBtn');
        const unreadBtn = document.getElementById('filterUnreadBtn');

        if (filter === 'all') {
            allBtn.className = 'px-3 py-1 rounded-full text-[11px] font-bold bg-blue-600 text-white transition-all cursor-pointer';
            unreadBtn.className = 'px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all cursor-pointer';
        } else {
            unreadBtn.className = 'px-3 py-1 rounded-full text-[11px] font-bold bg-blue-600 text-white transition-all cursor-pointer';
            allBtn.className = 'px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all cursor-pointer';
        }
        renderRoomsList();
    }

    function handleSidebarSearch(val) {
        state.searchQuery = (val || '').trim().toLowerCase();
        if (state.activeTab === 'rooms') {
            renderRoomsList();
        } else {
            filterDirectoryAdmins(state.searchQuery);
        }
    }

    function filterDirectoryAdmins(query) {
        clearTimeout(state.searchTimer);
        state.searchTimer = setTimeout(async () => {
            try {
                updateAdminLoadMoreButtonState('loading');
                const res = await fetch(`${CHAT_CONFIG.adminsUrl}?q=${encodeURIComponent(query || '')}&offset=0&limit=50`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const data = await res.json();
                if (!data.success) return;
                const list = data.admins || [];
                const listCont = document.getElementById('adminsDirectoryList');
                if (!listCont) return;

                state.adminOffset = data.offset || list.length;
                state.adminHasMore = !!data.has_more;

                if (list.length === 0) {
                    listCont.innerHTML = `<div class="p-8 text-center text-xs text-slate-400">Tidak ada admin ditemukan.</div>`;
                    updateAdminLoadMoreButtonState('empty');
                    return;
                }
                listCont.innerHTML = list.map(renderAdminDirectoryItem).join('');
                updateAdminLoadMoreButtonState(state.adminHasMore ? 'ready' : 'empty');
            } catch (e) {
                console.error(e);
                updateAdminLoadMoreButtonState('empty');
            }
        }, 250);
    }

    function openAdminModal() {
        const modal = document.getElementById('adminSelectorModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeAdminModal() {
        const modal = document.getElementById('adminSelectorModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function filterModalAdmins(query) {
        state.modalAdminQuery = (query || '').trim();
        clearTimeout(state.searchTimer);
        state.searchTimer = setTimeout(async () => {
            try {
                updateModalAdminLoadMoreButtonState('loading');
                const res = await fetch(`${CHAT_CONFIG.adminsUrl}?q=${encodeURIComponent(state.modalAdminQuery)}&offset=0&limit=50`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const data = await res.json();
                if (!data.success) return;
                const list = data.admins || [];
                const grid = document.getElementById('modalAdminsGrid');
                if (!grid) return;

                state.modalAdminOffset = data.offset || list.length;
                state.modalAdminHasMore = !!data.has_more;

                if (list.length === 0) {
                    grid.innerHTML = `<div class="col-span-2 p-8 text-center text-xs text-slate-400">Tidak ada admin ditemukan.</div>`;
                    updateModalAdminLoadMoreButtonState('empty');
                    return;
                }
                grid.innerHTML = list.map(renderModalAdminCard).join('');
                updateModalAdminLoadMoreButtonState(state.modalAdminHasMore ? 'ready' : 'empty');
            } catch (e) {
                console.error(e);
                updateModalAdminLoadMoreButtonState('empty');
            }
        }, 250);
    }

    async function fetchRooms(silent = false) {
        try {
            const res = await fetch(CHAT_CONFIG.roomsUrl, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const data = await res.json();
            if (!data.success) return;

            state.rooms = data.rooms || [];
            updateUnreadBadges(data.total_unread || 0, state.rooms);
            renderRoomsList();
        } catch (err) {
            console.error('Failed fetching rooms:', err);
        }
    }

    function updateUnreadBadges(totalUnread, rooms) {
        const sidebarBadge = document.getElementById('sidebarTotalUnreadBadge');
        if (sidebarBadge) {
            if (totalUnread > 0) {
                sidebarBadge.textContent = totalUnread > 99 ? '99+' : totalUnread;
                sidebarBadge.classList.remove('hidden');
            } else {
                sidebarBadge.classList.add('hidden');
            }
        }

        const navBadge = document.getElementById('nav-chat-admin-badge');
        if (navBadge) {
            if (totalUnread > 0) {
                navBadge.textContent = totalUnread > 99 ? '99+' : totalUnread;
                navBadge.classList.remove('hidden');
                navBadge.classList.add('flex');
            } else {
                navBadge.classList.add('hidden');
                navBadge.classList.remove('flex');
            }
        }

        document.querySelectorAll('[id^="strip-unread-"]').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('flex');
        });

        rooms.forEach(r => {
            const stripBadge = document.getElementById(`strip-unread-${r.partner_id}`);
            if (stripBadge && r.unread_count > 0) {
                stripBadge.textContent = r.unread_count;
                stripBadge.classList.remove('hidden');
                stripBadge.classList.add('flex');
            }
        });
    }

    function renderRoomsList() {
        const container = document.getElementById('roomsListContainer');
        if (!container) return;

        let filtered = state.rooms.filter(room => {
            if (state.roomFilter === 'unread' && room.unread_count <= 0) return false;
            if (state.searchQuery) {
                const name = (room.user?.name || '').toLowerCase();
                const email = (room.user?.email || '').toLowerCase();
                const last = (room.last_message || '').toLowerCase();
                return name.includes(state.searchQuery) || email.includes(state.searchQuery) || last.includes(state.searchQuery);
            }
            return true;
        });

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="p-8 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <i class="bi bi-chat-square-dots text-2xl"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-700">
                        ${state.roomFilter === 'unread' ? 'Tidak ada pesan belum dibaca' : 'Belum ada riwayat percakapan'}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1 mb-4">
                        Pilih salah satu Admin KampSewa untuk memulai chat bantuan.
                    </p>
                    <button type="button" onclick="switchSidebarTab('admins')"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold shadow-xs hover:bg-blue-700 transition-colors cursor-pointer">
                        <i class="bi bi-person-plus-fill"></i> Pilih Admin Sekarang
                    </button>
                </div>
            `;
            return;
        }

        container.innerHTML = filtered.map(room => {
            const isActive = Number(state.activeAdminId) === Number(room.partner_id);
            const activeClasses = isActive
                ? 'bg-blue-50/90 border-l-4 border-l-blue-600'
                : 'hover:bg-slate-50 border-l-4 border-l-transparent';

            let checkIcon = '';
            if (room.is_last_mine) {
                checkIcon = room.is_read_last
                    ? '<i class="bi bi-check2-all text-sky-500 text-sm shrink-0" title="Dibaca"></i>'
                    : '<i class="bi bi-check2 text-slate-400 text-sm shrink-0" title="Terkirim"></i>';
            }

            const unreadBadge = room.unread_count > 0
                ? `<span class="px-2 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-extrabold shrink-0 shadow-xs">${room.unread_count}</span>`
                : '';

            return `
                <div onclick="selectAdminPartner(${room.partner_id})"
                    class="p-3.5 transition-all cursor-pointer flex items-center gap-3 ${activeClasses}">
                    ${renderAvatarHtml(room.user, 'w-12 h-12', 'rounded-2xl', 'text-sm')}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <h4 class="text-xs sm:text-sm font-extrabold ${isActive ? 'text-blue-700' : 'text-slate-900'} truncate">
                                ${escapeHtml(room.user.name)}
                            </h4>
                            <span class="text-[10px] font-semibold ${room.unread_count > 0 ? 'text-blue-600 font-bold' : 'text-slate-400'} shrink-0" title="${escapeHtml(room.last_message_full_time)}">
                                ${escapeHtml(room.last_message_time)}
                            </span>
                        </div>
                        <p class="text-[10px] font-semibold text-blue-600/80 truncate">
                            Admin Support KampSewa
                        </p>
                        <div class="flex items-center justify-between gap-2 mt-1">
                            <div class="flex items-center gap-1 min-w-0">
                                ${checkIcon}
                                <p class="text-xs ${room.unread_count > 0 ? 'font-bold text-slate-900' : 'text-slate-500'} truncate">
                                    ${escapeHtml(room.last_message)}
                                </p>
                            </div>
                            ${unreadBadge}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    async function selectAdminPartner(adminId) {
        state.activeAdminId = Number(adminId);
        state.messagesSignature = '';

        // Highlight strip card
        document.querySelectorAll('.admin-strip-card').forEach(card => {
            const id = Number(card.getAttribute('data-admin-strip-id'));
            const nameEl = card.querySelector('.admin-strip-name');
            const pill = card.querySelector('.admin-strip-pill');
            const pillText = card.querySelector('.admin-strip-pill-text');

            if (id === state.activeAdminId) {
                card.classList.add('ring-2', 'ring-blue-600', 'bg-blue-50/90', 'border-blue-500', 'shadow-md');
                card.classList.remove('border-slate-200/90', 'bg-white');
                if (nameEl) nameEl.classList.add('text-blue-700');
                if (pill) {
                    pill.className = 'admin-strip-pill inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-600 text-white shadow-2xs border border-blue-600 transition-all';
                }
                if (pillText) pillText.textContent = 'Sedang Dipilih';
                try {
                    card.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
                } catch (e) {}
            } else {
                card.classList.remove('ring-2', 'ring-blue-600', 'bg-blue-50/90', 'border-blue-500', 'shadow-md');
                card.classList.add('border-slate-200/90', 'bg-white');
                if (nameEl) nameEl.classList.remove('text-blue-700');
                if (pill) {
                    pill.className = 'admin-strip-pill inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200/60 transition-all';
                }
                if (pillText) pillText.textContent = 'Chat Sekarang';
            }
        });

        // Highlight directory item in sidebar
        document.querySelectorAll('.admin-directory-item').forEach(item => {
            const id = Number(item.getAttribute('data-admin-id'));
            if (id === state.activeAdminId) {
                item.classList.add('bg-blue-50/90', 'border-l-4', 'border-l-blue-600');
            } else {
                item.classList.remove('bg-blue-50/90', 'border-l-4', 'border-l-blue-600');
            }
        });

        // Show conversation pane
        document.getElementById('emptyChatState').classList.add('hidden');
        const activeWrapper = document.getElementById('activeChatWrapper');
        activeWrapper.classList.remove('hidden');
        activeWrapper.classList.add('flex');

        // Find admin info immediately from local list for instant header update
        const localAdmin = CHAT_CONFIG.admins.find(a => Number(a.id) === state.activeAdminId);
        if (localAdmin) {
            updateConversationHeader(localAdmin);
        }

        renderRoomsList();
        await fetchMessagesForActiveAdmin(true);
        await fetchRooms(true);
    }

    function closeMobileChatPane() {
        state.activeAdminId = null;
        document.getElementById('activeChatWrapper').classList.add('hidden');
        document.getElementById('activeChatWrapper').classList.remove('flex');
        document.getElementById('emptyChatState').classList.remove('hidden');
        renderRoomsList();
    }

    function updateConversationHeader(partner) {
        document.getElementById('headerAdminAvatar').innerHTML = renderAvatarHtml(partner, 'w-11 h-11', 'rounded-2xl', 'text-xs');
        document.getElementById('headerAdminName').textContent = partner.name || 'Admin';
        document.getElementById('headerAdminEmail').innerHTML = `<i class="bi bi-envelope mr-1"></i>${escapeHtml(partner.email || '-')}`;
        document.getElementById('headerAdminPhone').innerHTML = `<i class="bi bi-telephone mr-1"></i>${escapeHtml(partner.nomor_telephone || '-')}`;
    }

    async function fetchMessagesForActiveAdmin(forceScroll = false) {
        if (!state.activeAdminId) return;
        try {
            const url = `${CHAT_CONFIG.messagesBaseUrl}/${state.activeAdminId}`;
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const data = await res.json();
            if (!data.success) return;

            if (data.partner) {
                updateConversationHeader(data.partner);
            }

            renderMessagesTimeline(data.messages || [], forceScroll);
        } catch (err) {
            console.error('Failed fetching messages:', err);
        }
    }

    function renderMessagesTimeline(messages, forceScroll = false) {
        const viewport = document.getElementById('messagesViewport');
        if (!viewport) return;

        const newSignature = JSON.stringify(messages.map(m => `${m.id}:${m.is_read}`));
        if (newSignature === state.messagesSignature && !forceScroll) {
            return;
        }

        const isNearBottom = (viewport.scrollHeight - viewport.scrollTop - viewport.clientHeight) < 120;
        state.messagesSignature = newSignature;

        if (messages.length === 0) {
            viewport.innerHTML = `
                <div class="h-full flex flex-col items-center justify-center text-center py-12 px-4">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3 shadow-xs">
                        <i class="bi bi-chat-dots text-2xl"></i>
                    </div>
                    <p class="text-sm font-extrabold text-slate-800">Mulai Percakapan dengan Admin Ini</p>
                    <p class="text-xs text-slate-500 max-w-sm mt-1">
                        Kirim pesan pertama Anda di bawah. Pesan akan langsung masuk ke ruang chat Admin yang Anda pilih.
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
                        <span class="px-3.5 py-1 rounded-full bg-white/90 border border-slate-200/80 text-[11px] font-bold text-slate-500 shadow-2xs">
                            ${escapeHtml(msg.date_label)}
                        </span>
                    </div>
                `;
            }

            if (msg.is_mine) {
                const readStatusHtml = msg.is_read
                    ? `<span class="inline-flex items-center gap-0.5 text-sky-200 font-bold" title="Dibaca oleh Admin"><i class="bi bi-check2-all text-sm"></i> Dibaca</span>`
                    : `<span class="inline-flex items-center gap-0.5 text-blue-200" title="Terkirim"><i class="bi bi-check2 text-sm"></i> Terkirim</span>`;

                html += `
                    <div class="flex justify-end">
                        <div class="max-w-[82%] sm:max-w-[70%] rounded-2xl rounded-tr-xs bg-gradient-to-br from-blue-600 to-indigo-600 text-white px-4 py-2.5 shadow-sm">
                            <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-wrap break-words">${escapeHtml(msg.message_text)}</p>
                            <div class="flex items-center justify-end gap-2 mt-1.5 text-[10px] text-blue-100">
                                <span title="${escapeHtml(msg.full_datetime)}">${escapeHtml(msg.time)}</span>
                                ${readStatusHtml}
                            </div>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="flex justify-start">
                        <div class="max-w-[82%] sm:max-w-[70%] rounded-2xl rounded-tl-xs bg-white border border-slate-200/80 text-slate-800 px-4 py-2.5 shadow-xs">
                            <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-wrap break-words">${escapeHtml(msg.message_text)}</p>
                            <div class="flex items-center justify-end gap-1.5 mt-1.5 text-[10px] text-slate-400">
                                <span title="${escapeHtml(msg.full_datetime)}">${escapeHtml(msg.time)}</span>
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

    function fillQuickMessage(text) {
        const input = document.getElementById('chatMessageInput');
        if (!input) return;
        input.value = text;
        autoResizeTextarea(input);
        input.focus();
    }

    function autoResizeTextarea(el) {
        el.style.height = 'auto';
        if (el.scrollHeight > 128) {
            el.style.overflowY = 'auto';
            el.style.height = '128px';
            el.classList.add('chat-scrollbar');
        } else {
            el.style.overflowY = 'hidden';
            el.style.height = Math.max(el.scrollHeight, 46) + 'px';
            el.classList.remove('chat-scrollbar');
        }
    }

    function handleInputKeydown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            submitChatMessage(e);
        }
    }

    async function submitChatMessage(e) {
        if (e) e.preventDefault();
        if (state.isSending || !state.activeAdminId) return;

        const input = document.getElementById('chatMessageInput');
        const message = (input.value || '').trim();
        if (!message) return;

        state.isSending = true;
        const btn = document.getElementById('sendMsgBtn');
        const icon = document.getElementById('sendMsgIcon');
        btn.disabled = true;
        icon.className = 'bi bi-arrow-repeat animate-spin text-xs';

        try {
            const res = await fetch(CHAT_CONFIG.sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CHAT_CONFIG.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    receiver_id: state.activeAdminId,
                    message: message,
                }),
            });

            if (res.ok) {
                input.value = '';
                input.style.height = 'auto';
                input.style.overflowY = 'hidden';
                input.classList.remove('chat-scrollbar');
                await fetchMessagesForActiveAdmin(true);
                await fetchRooms(true);
            }
        } catch (err) {
            console.error('Error sending message:', err);
        } finally {
            state.isSending = false;
            btn.disabled = false;
            icon.className = 'bi bi-send-fill text-xs';
            input.focus();
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        await fetchRooms();

        if (CHAT_CONFIG.initialAdminId) {
            await selectAdminPartner(CHAT_CONFIG.initialAdminId);
        }

        // Real-time polling every 2.5 seconds
        state.pollTimer = setInterval(async () => {
            await fetchRooms(true);
            if (state.activeAdminId) {
                await fetchMessagesForActiveAdmin(false);
            }
        }, 2500);
    });
</script>
@endsection
