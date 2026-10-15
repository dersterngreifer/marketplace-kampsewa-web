<div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-extrabold text-[#19191B]">
                Produk Yang Disewakan
            </h2>
            <p class="mt-1 text-sm font-medium text-slate-500">
                Daftar produk terbaru yang diposting oleh customer.
            </p>
        </div>

        <a
            href="{{ route('detail-pengguna.produk-disewakan', ['user' => $user_id]) }}"
            class="inline-flex w-fit items-center gap-2 rounded-2xl bg-[#5038ED] px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-[#5038ED]/20 transition hover:-translate-y-0.5 hover:bg-[#412CCB]"
        >
            <span>Lihat Semua</span>
            <i class="fi fi-rr-arrow-small-right text-lg"></i>
        </a>
    </div>

    <div class="space-y-3">
        @forelse ($produk_disewakan_limit2 as $item)
            <a
                href="{{ route('detail-pengguna.detail-produk-disewakan', ['user' => $name, 'namaproduk' => $item->nama]) }}"
                class="flex flex-col gap-4 rounded-2xl border border-slate-100 bg-slate-50 p-3 transition hover:-translate-y-0.5 hover:bg-white hover:shadow-lg sm:flex-row sm:items-center sm:justify-between"
            >
                <div class="flex min-w-0 items-center gap-3">
                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-white shadow-sm">
                        <img
                            class="h-full w-full object-cover"
                            src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($item) }}"
                            alt="{{ $item->nama }}"
                            onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22400%22%20height%3D%22300%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20300%22%3E%3Crect%20fill%3D%22%23f1f5f9%22%20width%3D%22400%22%20height%3D%22300%22%2F%3E%3Ctext%20fill%3D%22%2394a3b8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%3EKampSewa%20Produk%3C%2Ftext%3E%3C%2Fsvg%3E';"
                        >
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="line-clamp-1 text-base font-extrabold text-[#19191B]">
                                {{ $item->nama }}
                            </h3>

                            <span class="rounded-full bg-[#5038ED]/10 px-2.5 py-1 text-xs font-bold text-[#5038ED]">
                                {{ $item->status }}
                            </span>
                        </div>

                        <p class="mt-1 text-sm font-medium text-slate-500">
                            {{ $item->stok_produk ?? 0 }} Total Stok Produk
                        </p>
                    </div>
                </div>

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white text-[#5038ED] shadow-sm">
                    <i class="fi fi-rr-angle-small-right text-xl"></i>
                </div>
            </a>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400">
                    <i class="fi fi-rr-box-open text-xl"></i>
                </div>

                <p class="text-sm font-bold text-[#19191B]">
                    Belum ada produk disewakan
                </p>

                <p class="mt-1 text-sm font-medium text-slate-500">
                    Customer ini belum memposting produk sewa.
                </p>
            </div>
        @endforelse
    </div>
</div>