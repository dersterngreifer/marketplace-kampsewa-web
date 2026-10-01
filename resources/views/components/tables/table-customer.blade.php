<div class="w-full overflow-hidden rounded-[28px] border border-slate-200/70 bg-white shadow-sm">

    <div class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-black tracking-tight text-slate-900">
                Daftar Pengguna Online
            </h1>
            <p class="mt-1 text-sm font-medium text-slate-400">
                Total user online :
                <span class="font-black text-slate-700">
                    {{ $customer_online->count() == 0 ? 0 : $customer_online->count() }} User.
                </span>
            </p>
        </div>

        <div class="flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-sm font-bold text-emerald-600">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            Online
        </div>
    </div>

    <div class="max-h-[560px] overflow-auto">
        @if (count($customer_online) == 0)
            <div class="flex min-h-[420px] flex-col items-center justify-center gap-5 px-6 text-center md:flex-row md:text-left">
                <img class="w-[220px] max-w-full object-contain sm:w-[280px]"
                    src="{{ asset('images/illustration/222社交206气泡水肌理矢量创意插画气泡水-01 1.png') }}" alt="">

                <div>
                    <p class="text-4xl font-black tracking-tight text-slate-900">
                        OOPS!
                    </p>
                    <p class="mt-2 text-base font-medium text-slate-500">
                        Tidak ada user online saat ini
                    </p>
                </div>
            </div>
        @else
            <table class="min-w-[900px] w-full border-collapse bg-white text-left text-sm">
                <thead class="sticky top-0 z-10 border-b border-slate-100 bg-slate-50/95 backdrop-blur">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">No</th>
                        <th scope="col" class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">Nama</th>
                        <th scope="col" class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">Status</th>
                        <th scope="col" class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">Role</th>
                        <th scope="col" class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">Waktu Aktif</th>
                        <th scope="col" class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach ($customer_online as $item)
                        <tr class="transition-all duration-300 hover:bg-slate-50">
                            <td class="px-6 py-4 font-semibold text-slate-500">
                                {{ $loop->iteration }}
                            </td>

                            <th class="px-6 py-4 font-normal">
                                <div class="flex items-center gap-3">
                                    <div class="relative h-11 w-11 shrink-0">
                                        @include('components.ui.avatar', [
                                            'nama' => $item->name,
                                            'foto' => $item->foto,
                                            'class' => 'h-full w-full',
                                            'rounded' => 'rounded-2xl',
                                            'text' => 'text-xs',
                                            'extra' => 'shadow-sm',
                                        ])

                                        <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"></span>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="max-w-[220px] truncate text-sm font-black text-slate-800">
                                            {{ $item->name }}
                                        </div>
                                        <div class="max-w-[240px] truncate text-xs font-medium text-slate-400">
                                            {{ $item->email }}
                                        </div>
                                    </div>
                                </div>
                            </th>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-black text-emerald-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                    {{ $item->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 font-semibold text-slate-600">
                                Customer
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-500">
                                @if ($item->time_login)
                                    {{ \Carbon\Carbon::parse($item->time_login)->diffForHumans() }}
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <a href="{{ route('detail-pengguna.index', [$item->name]) }}"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-violet-50 text-violet-600 transition-all duration-300 hover:bg-violet-600 hover:text-white">
                                    <i class="bi bi-pen-fill text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>