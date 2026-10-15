<div class="w-full overflow-hidden rounded-[28px] border border-slate-200/70 bg-white shadow-sm">

    <div class="border-b border-slate-100 p-5 sm:p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <div
                    class="mb-2 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-emerald-600">
                    <i class="fi fi-rr-check flex text-[10px]"></i>
                    Sudah Dibalas
                </div>
                <h2 class="text-xl font-black tracking-tight text-slate-900 sm:text-2xl">
                    Feedback Reply
                </h2>
                <p class="mt-1 text-sm font-medium text-slate-400">
                    Feedback customer yang sudah dibalas.
                </p>
            </div>

            <button type="button" id="deleteReplyButton" aria-label="Hapus reply terpilih"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-red-50 px-4 py-3 text-sm font-bold text-red-500 transition-all duration-300 hover:bg-red-500 hover:text-white active:scale-95">
                <i class="fi fi-rr-trash flex text-[15px]"></i>
                Hapus Terpilih
            </button>
        </div>

        <div class="mt-5 flex items-center justify-between gap-3 rounded-2xl bg-slate-50 p-3">
            <label class="flex cursor-pointer items-center gap-3">
                <span class="relative flex items-center">
                    <input type="checkbox" id="checkAllReply"
                        class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-slate-300 bg-white transition-all checked:border-[#5038ED] checked:bg-[#5038ED]">
                    <span
                        class="pointer-events-none absolute left-1/2 top-1/2 hidden -translate-x-1/2 -translate-y-1/2 text-white peer-checked:block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>
                <span class="text-sm font-bold text-slate-600">Pilih semua reply</span>
            </label>

            <span class="shrink-0 rounded-full bg-white px-3 py-1 text-xs font-bold text-slate-500 shadow-sm">
                {{ $reply->total() }} item
            </span>
        </div>
    </div>

    @php
    $isReplyFiltered =
        request('reply_sort', 'date_latest') !== 'date_latest' ||
        request('reply_kriteria', 'all') !== 'all';
@endphp

<form method="GET" action="{{ route('notification.index') }}"
    class="mx-5 mt-5 overflow-hidden rounded-[26px] border border-slate-200/70 bg-gradient-to-br from-slate-50 via-white to-emerald-50/40 p-4 shadow-sm sm:mx-6">

    <input type="hidden" name="feedback_sort" value="{{ request('feedback_sort', 'date_latest') }}">
    <input type="hidden" name="feedback_kriteria" value="{{ request('feedback_kriteria', 'all') }}">
    <input type="hidden" name="customer_reply_sort" value="{{ request('customer_reply_sort', 'date_latest') }}">
    <input type="hidden" name="customer_reply_kriteria" value="{{ request('customer_reply_kriteria', 'all') }}">

    <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white text-emerald-600 shadow-sm ring-1 ring-slate-200/70">
                <i class="fi fi-rr-filter flex text-[17px]"></i>
            </div>

            <div>
                <h3 class="text-sm font-black text-slate-900">
                    Filter & Sorting
                </h3>
                <p class="mt-0.5 text-xs font-medium text-slate-400">
                    Atur urutan feedback yang sudah dibalas.
                </p>
            </div>
        </div>

        @if ($isReplyFiltered)
            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-black text-emerald-700">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                Filter aktif
            </span>
        @else
            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-black text-slate-500">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                Default view
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-3 lg:grid-cols-[1fr_1fr_auto]">
        <div class="group">
            <label class="mb-2 flex items-center gap-2 text-[11px] font-black uppercase tracking-[0.18em] text-slate-400">
                <i class="fi fi-rr-sort-alt flex text-[12px] text-emerald-400"></i>
                Sort Feedback Reply
            </label>

            <div class="relative">
                <div class="pointer-events-none absolute left-4 top-1/2 z-10 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500">
                    <i class="fi fi-rr-calendar-clock flex text-[14px]"></i>
                </div>

                <select name="reply_sort" onchange="this.form.submit()"
                    class="h-[52px] w-full appearance-none rounded-[20px] border border-slate-200 bg-white py-3 pl-14 pr-12 text-sm font-bold text-slate-700 shadow-sm outline-none transition-all duration-300 hover:border-emerald-200 hover:shadow-md focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                    <option value="date_latest" @selected(request('reply_sort', 'date_latest') == 'date_latest')>Tanggal terbaru</option>
                    <option value="date_oldest" @selected(request('reply_sort') == 'date_oldest')>Tanggal terlama</option>
                    <option value="kriteria_best" @selected(request('reply_sort') == 'kriteria_best')>Kriteria terbaik</option>
                    <option value="kriteria_worst" @selected(request('reply_sort') == 'kriteria_worst')>Kriteria terburuk</option>
                </select>

                <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="fi fi-rr-angle-small-down flex text-[18px]"></i>
                </div>
            </div>
        </div>

        <div class="group">
            <label class="mb-2 flex items-center gap-2 text-[11px] font-black uppercase tracking-[0.18em] text-slate-400">
                <i class="fi fi-rr-star flex text-[12px] text-amber-400"></i>
                Filter Kriteria
            </label>

            <div class="relative">
                <div class="pointer-events-none absolute left-4 top-1/2 z-10 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-xl bg-amber-50 text-amber-500">
                    <i class="fi fi-rr-chart-histogram flex text-[14px]"></i>
                </div>

                <select name="reply_kriteria" onchange="this.form.submit()"
                    class="h-[52px] w-full appearance-none rounded-[20px] border border-slate-200 bg-white py-3 pl-14 pr-12 text-sm font-bold text-slate-700 shadow-sm outline-none transition-all duration-300 hover:border-emerald-200 hover:shadow-md focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                    <option value="all" @selected(request('reply_kriteria', 'all') == 'all')>Semua kriteria</option>
                    <option value="baik" @selected(request('reply_kriteria') == 'baik')>Baik / Sangat Baik</option>
                    <option value="cukup" @selected(request('reply_kriteria') == 'cukup')>Cukup</option>
                    <option value="buruk" @selected(request('reply_kriteria') == 'buruk')>Kurang / Sangat Kurang</option>
                    <option value="sangat_baik" @selected(request('reply_kriteria') == 'sangat_baik')>Sangat Baik saja</option>
                    <option value="baik_only" @selected(request('reply_kriteria') == 'baik_only')>Baik saja</option>
                    <option value="kurang" @selected(request('reply_kriteria') == 'kurang')>Kurang saja</option>
                    <option value="sangat_kurang" @selected(request('reply_kriteria') == 'sangat_kurang')>Sangat Kurang saja</option>
                </select>

                <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="fi fi-rr-angle-small-down flex text-[18px]"></i>
                </div>
            </div>
        </div>

        <div class="flex items-end">
            <a href="{{ route('notification.index') }}"
                class="inline-flex h-[52px] w-full items-center justify-center gap-2 rounded-[20px] border border-slate-200 bg-white px-5 text-sm font-black text-slate-500 shadow-sm transition-all duration-300 hover:border-red-100 hover:bg-red-50 hover:text-red-500 hover:shadow-md lg:w-auto">
                <i class="fi fi-rr-refresh flex text-[14px]"></i>
                Reset
            </a>
        </div>
    </div>
</form>

    <form id="deleteReplyForm" method="POST" action="{{ route('notification.delete-selected-reply') }}">
        @csrf
        @method('DELETE')

        <div class="max-h-[640px] overflow-y-auto p-4 sm:p-5">
            @if ($reply->count() == 0)
                <div class="flex min-h-[280px] flex-col items-center justify-center px-6 text-center">
                    <div
                        class="mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">
                        <i class="fi fi-rr-comment-alt flex text-2xl"></i>
                    </div>
                    <p class="text-base font-black text-slate-900">
                        Belum ada feedback reply
                    </p>
                    <p class="mt-1 text-sm text-slate-400">
                        Feedback yang sudah dibalas akan tampil di sini.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($reply as $item)
                        <article
                            class="group flex flex-col rounded-[22px] border border-slate-200/70 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-violet-200 hover:shadow-lg hover:shadow-slate-200/70">

                            <div class="flex items-start justify-between gap-3">
                                <label class="flex cursor-pointer items-center gap-2">
                                    <span class="relative flex items-center">
                                        <input type="checkbox" name="feedback_ids[]" value="{{ $item->id }}"
                                            class="reply-checkbox peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-slate-300 bg-white transition-all checked:border-[#5038ED] checked:bg-[#5038ED]">
                                        <span
                                            class="pointer-events-none absolute left-1/2 top-1/2 hidden -translate-x-1/2 -translate-y-1/2 text-white peer-checked:block">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </span>

                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-black text-emerald-500">
                                        <i class="fi fi-rr-check flex text-[10px]"></i>
                                        {{ $item->status }}
                                    </span>
                                </label>

                                <div class="flex shrink-0 items-center gap-2">
                                    <button type="button" title="Lihat balasan / lihat chat" data-chat-button
                                        data-feedback-id="{{ $item->id }}"
                                        data-customer-name="{{ $item->name }}"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-violet-50 text-violet-600 transition-all duration-300 hover:bg-violet-600 hover:text-white active:scale-95">
                                        <i class="fi fi-rr-comment-dots flex text-[15px]"></i>
                                    </button>

                                    <span class="text-xs font-semibold text-slate-400">
                                        {{ Carbon\Carbon::parse($item->created_at)->format('j M Y') }}
                                    </span>
                                </div>
                            </div>

                            <p class="mt-3 line-clamp-4 flex-1 text-sm leading-6 text-slate-600">
                                {{ $item->deskripsi }}
                            </p>

                            <div class="mt-4 flex items-center gap-3 border-t border-slate-100 pt-3">
                                <img class="h-10 w-10 shrink-0 rounded-2xl object-cover shadow-sm ring-1 ring-slate-100"
                                    src="@userPhoto($item->foto)" alt="Foto {{ $item->name }}"
                                    onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>'; this.onerror=null;">

                                <div class="min-w-0">
                                    <p class="truncate text-sm font-black text-slate-900">
                                        {{ $item->name }}
                                    </p>
                                    <p class="truncate text-xs font-medium text-slate-400">
                                        Pelanggan
                                    </p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </form>

    <div class="border-t border-slate-100 p-4">
        {{ $reply->onEachSide(1)->links('components.paginate.custom-pagination') }}
    </div>
</div>
