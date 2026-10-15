@if ($customerReplies->count() == 0)
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
@else
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($customerReplies as $item)
            <button type="button"
                data-chat-button
                data-feedback-id="{{ $item->id }}"
                data-customer-name="{{ $item->user?->name ?? 'Customer' }}"
                class="group flex w-full items-start gap-3 rounded-[22px] border border-slate-200/70 bg-white p-4 text-left shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-violet-200 hover:shadow-lg hover:shadow-slate-200/70">

                <img class="h-11 w-11 shrink-0 rounded-2xl object-cover shadow-sm ring-1 ring-slate-100"
                    src="@userPhoto($item->user?->foto)"
                    alt="Foto {{ $item->user?->name ?? 'Customer' }}"
                    onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>'; this.onerror=null;">

                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <p class="truncate text-sm font-black text-slate-900">
                            {{ $item->user?->name ?? 'Customer' }}
                        </p>

                        @if (($item->unread_customer_messages_count ?? 0) > 0)
                            <span class="shrink-0 rounded-full bg-red-50 px-2 py-0.5 text-[11px] font-black text-red-500">
                                {{ $item->unread_customer_messages_count }}
                            </span>
                        @endif
                    </div>

                    <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                        {{ $item->latestMessage?->message }}
                    </p>

                    <p class="mt-2 text-[11px] font-bold text-violet-500">
                        Lihat chat
                    </p>
                </div>
            </button>
        @endforeach
    </div>
@endif