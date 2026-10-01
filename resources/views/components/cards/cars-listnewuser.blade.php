<div class="h-full w-full rounded-[28px] border border-slate-200/70 bg-white p-5 shadow-sm">

    <div class="mb-5 flex items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black tracking-tight text-slate-900">
                Customer Baru
            </h3>
            <p class="mt-1 text-sm font-medium text-slate-400">
                Pengguna baru bulan ini
            </p>
        </div>

        <a href="{{ route('kelola-pengguna.index') }}"
            class="shrink-0 rounded-full bg-violet-50 px-3 py-1.5 text-xs font-bold text-violet-600 transition-all duration-300 hover:bg-violet-600 hover:text-white">
            View all
        </a>
    </div>

    <div class="max-h-[520px] overflow-y-auto pr-1">
        <ul role="list" class="space-y-2">
            @forelse ($customer_baru_bulan_ini as $item)
                <li>
                    <a href="{{ route('detail-pengguna.index', [$item->name]) }}"
                        class="group flex items-center gap-3 rounded-2xl border border-transparent p-3 transition-all duration-300 hover:border-slate-200 hover:bg-slate-50">

                        @include('components.ui.avatar', [
                            'nama' => $item->name,
                            'foto' => $item->foto,
                            'class' => 'h-11 w-11',
                            'rounded' => 'rounded-2xl',
                            'text' => 'text-xs',
                            'extra' => 'shadow-sm',
                        ])

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-slate-900">
                                {{ $item->name }}
                            </p>
                            <p class="truncate text-xs font-medium text-slate-400">
                                {{ $item->email }}
                            </p>
                        </div>

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-400 transition-all duration-300 group-hover:bg-violet-100 group-hover:text-violet-600">
                            <i class="fi fi-rr-angle-small-right mt-1 text-[22px]"></i>
                        </div>
                    </a>
                </li>
            @empty
                <li class="flex min-h-[220px] flex-col items-center justify-center text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100">
                        <i class="fi fi-rr-user-add mt-1 text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-800">
                        Belum ada customer baru
                    </p>
                    <p class="mt-1 text-xs text-slate-400">
                        Data customer baru bulan ini akan tampil di sini.
                    </p>
                </li>
            @endforelse
        </ul>
    </div>
</div>