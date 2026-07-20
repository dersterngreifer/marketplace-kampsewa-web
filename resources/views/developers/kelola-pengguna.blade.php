@extends('layouts.developers.ly-dashboard')

@section('content')
    {{-- Modal tambah customer --}}
    @include('components.modals.add-customer')

    <div class="min-h-screen w-full bg-[#F6F7FB] px-4 py-5 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div
            class="mb-6 overflow-hidden rounded-[28px] bg-gradient-to-br from-[#19191B] via-[#24243A] to-[#12A4ED] p-5 text-white shadow-lg sm:p-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <p class="mb-2 w-fit rounded-full bg-white/15 px-3 py-1 text-xs font-semibold backdrop-blur">
                        Kelola Pengguna
                    </p>

                    <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">
                        Data List Pengguna
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-white/75">
                        Pantau data customer, jumlah produk yang disewakan, informasi kontak, dan detail akun pengguna.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:flex sm:items-center">
                    <div class="rounded-2xl bg-white/15 px-4 py-3 backdrop-blur">
                        <p class="text-xs text-white/70">Total Customer</p>
                        <p class="mt-1 text-xl font-bold">{{ $get_total_user }}</p>
                    </div>

                    <button type="button" onclick="modalHandler(true)"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-4 py-3 text-sm font-bold text-[#19191B] shadow-md transition hover:-translate-y-0.5 hover:bg-slate-100 active:scale-[0.98]">
                        <i class="fi fi-rr-plus-small text-base"></i>
                        <span>Tambah</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Search, Filter, Bulk Delete --}}
        <div class="mb-5 rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <form id="customerFilterForm" method="GET" action="{{ route('kelola-pengguna.index') }}"
                class="grid grid-cols-1 gap-3 lg:grid-cols-[1fr_220px_auto_auto_auto] lg:items-center">
                {{-- Search --}}
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                        <i class="fi fi-rr-search text-sm"></i>
                    </div>

                    <input type="text" name="cari_customer" id="search" value="{{ $cari_customer }}"
                        placeholder="Cari nama, email, atau nomor telepon..." autocomplete="off"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-medium text-[#19191B] outline-none transition placeholder:text-slate-400 focus:border-[#12A4ED] focus:bg-white focus:ring-4 focus:ring-[#12A4ED]/10">
                </div>

                {{-- Filter --}}
                <div class="relative">
                    <select id="filter" name="filter"
                        class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50 px-4 pr-10 text-sm font-semibold text-[#19191B] outline-none transition focus:border-[#12A4ED] focus:bg-white focus:ring-4 focus:ring-[#12A4ED]/10">
                        <option value="terbaru" {{ request('filter') == 'terbaru' ? 'selected' : '' }}>
                            Terbaru
                        </option>
                        <option value="terlama" {{ request('filter') == 'terlama' ? 'selected' : '' }}>
                            Terlama
                        </option>
                        <option value="punya_produk" {{ request('filter') == 'punya_produk' ? 'selected' : '' }}>
                            Punya Produk
                        </option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                        <i class="fi fi-rr-angle-small-down"></i>
                    </div>
                </div>

                {{-- Pilih Semua --}}
                <label
                    class="flex h-12 cursor-pointer items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                    <span class="relative flex items-center">
                        <input type="checkbox" id="checkAllUsers"
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

                    <span>Pilih Semua</span>
                </label>

                {{-- Delete Selected --}}
                <button type="submit" form="bulkDeleteForm" id="bulkDeleteButton" disabled
                    class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-red-500 px-5 text-sm font-bold text-white shadow-md shadow-red-500/20 transition hover:-translate-y-0.5 hover:bg-red-600 active:scale-[0.98] disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none disabled:hover:translate-y-0">
                    <i class="fi fi-rr-trash text-sm"></i>
                    <span>Delete Selected</span>
                    <span id="selectedCount" class="hidden rounded-full bg-white/20 px-2 py-0.5 text-xs">0</span>
                </button>

                {{-- Reset --}}
                <a href="{{ route('kelola-pengguna.index') }}"
                    class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 active:scale-[0.98]">
                    <i class="fi fi-rr-refresh text-sm"></i>
                    <span>Reset</span>
                </a>
            </form>

            <form id="bulkDeleteForm" action="{{ route('kelola-pengguna.bulk-destroy') }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
        </div>

        {{-- Info kecil --}}
        <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm font-semibold text-slate-600">
                Menampilkan data customer
            </p>

            <p class="text-sm font-bold text-[#19191B]">
                {{ $get_total_user }} Customer
            </p>
        </div>

        {{-- List Card --}}
        <div class="space-y-3">
            @forelse ($data as $item)
                <article
                    class="user-card group relative z-0 overflow-visible rounded-[28px] border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:z-10 hover:-translate-y-0.5 hover:border-[#12A4ED]/30 hover:shadow-xl hover:shadow-slate-200/70 sm:p-5">

                    <div
                        class="grid grid-cols-1 gap-4 lg:grid-cols-2 xl:grid-cols-[minmax(390px,1.75fr)_minmax(180px,0.85fr)_minmax(230px,1fr)_minmax(210px,0.85fr)] xl:items-center xl:gap-5">

                        {{-- Profile --}}
                        <div class="flex min-w-0 items-center gap-4 lg:col-span-2 xl:col-span-1">

                            {{-- Custom Checkbox --}}
                            <label class="flex h-16 w-10 shrink-0 cursor-pointer items-center justify-center">
                                <span class="relative flex items-center">
                                    <input type="checkbox" name="user_ids[]" value="{{ $item->user_id }}"
                                        form="bulkDeleteForm"
                                        class="user-checkbox peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-slate-300 bg-white transition-all checked:border-[#5038ED] checked:bg-[#5038ED]">

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
                            </label>

                            {{-- Avatar --}}
                            <div
                                class="relative h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-slate-100 ring-4 ring-slate-50">
                                <img src="@userPhoto($item->foto)" alt="Foto {{ $item->name }}"
                                    class="h-full w-full object-cover">
                            </div>

                            {{-- Identity --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex min-w-0 items-center gap-2">
                                    <h2 class="min-w-0 truncate text-base font-bold text-[#19191B]">
                                        {{ $item->name }}
                                    </h2>

                                    <span
                                        class="shrink-0 rounded-full bg-[#FDEAEE] px-2.5 py-1 text-[11px] font-bold text-[#F5325C]">
                                        Customer
                                    </span>
                                </div>

                                <p class="mt-1 truncate text-sm font-medium text-slate-400">
                                    {{ $item->email ?: 'Email belum di isi.' }}
                                </p>

                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span
                                        class="inline-flex min-w-[76px] shrink-0 items-center justify-center whitespace-nowrap rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500">
                                        ID #{{ $item->user_id }}
                                    </span>

                                    <span
                                        class="inline-flex min-w-0 items-center gap-1.5 text-xs font-semibold text-slate-500">
                                        <i class="fi fi-rr-clock-three shrink-0 text-slate-400"></i>
                                        <span class="truncate">
                                            Bergabung {{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Contact --}}
                        <div class="h-full rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-400">
                                Kontak
                            </p>

                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[#12A4ED] shadow-sm">
                                    <i class="fi fi-rr-phone-call text-sm"></i>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-[#19191B]">
                                        Nomor Telepon
                                    </p>

                                    <p class="truncate text-sm font-medium text-slate-500">
                                        {{ $item->nomor_telephone ?: 'Belum di isi.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Product --}}
                        <div class="h-full rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-400">
                                Produk
                            </p>

                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[#12A4ED] shadow-sm">
                                    <i class="fi fi-rr-box-open text-sm"></i>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-[#19191B]">
                                        {{ $item->total_product ? $item->total_product . ' Total produk disewakan' : 'Belum ada produk.' }}
                                    </p>

                                    @if ($item->total_product)
                                        <a href="{{ route('detail-pengguna.index', ['user' => $item->user_id]) }}"
                                            class="mt-1 inline-flex text-xs font-bold text-[#12A4ED] hover:underline">
                                            Lihat semua produk
                                        </a>
                                    @else
                                        <p class="mt-1 text-xs font-medium text-slate-400">
                                            Pengguna belum menyewakan produk.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Gender & Action --}}
                        <div
                            class="flex h-full items-center justify-between gap-3 rounded-2xl border border-slate-100 bg-white xl:justify-end xl:border-0 xl:bg-transparent">

                            <div
                                class="inline-flex min-w-[130px] items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-[#12A4ED]"></span>
                                <span class="truncate text-sm font-bold text-[#19191B]">
                                    {{ $item->jenis_kelamin ?: 'Belum di isi.' }}
                                </span>
                            </div>

                            <div class="relative shrink-0">
                                <button type="button"
                                    class="btn-more inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 transition hover:border-[#12A4ED]/40 hover:bg-[#12A4ED]/5 hover:text-[#12A4ED]"
                                    aria-label="Menu pengguna">
                                    <i class="fi fi-rr-menu-dots-vertical text-lg"></i>
                                </button>

                                <div
                                    class="dropdown-menu invisible absolute right-0 top-12 z-[9999] w-44 translate-y-2 rounded-2xl border border-slate-100 bg-white p-2 opacity-0 shadow-2xl shadow-slate-900/15 transition">
                                    <a href="{{ route('detail-pengguna.index', ['user' => $item->user_id]) }}"
                                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-[#12A4ED]/10 hover:text-[#12A4ED]">
                                        <i class="fi fi-rr-folder-open text-sm"></i>
                                        <span>Detail</span>
                                    </a>

                                    <form action="{{ route('kelola-pengguna.destroy', $item->user_id) }}" method="POST"
                                        class="singleDeleteForm" data-name="{{ $item->name }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-bold text-red-500 transition hover:bg-red-50">
                                            <i class="fi fi-rr-trash text-sm"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                {{-- Empty State --}}
                <div class="rounded-[28px] border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm">
                    <div
                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">
                        <i class="fi fi-rr-users-alt text-2xl"></i>
                    </div>

                    <h3 class="text-lg font-bold text-[#19191B]">
                        Data pengguna tidak ditemukan
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Coba gunakan kata kunci lain atau reset filter pencarian untuk menampilkan semua customer.
                    </p>

                    <a href="{{ route('kelola-pengguna.index') }}"
                        class="mt-5 inline-flex h-11 items-center justify-center rounded-2xl bg-[#12A4ED] px-5 text-sm font-bold text-white transition hover:bg-[#0E8FD0]">
                        Reset Pencarian
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $data->onEachSide(1)->links('components.paginate.custom-pagination') }}
        </div>
    </div>

    <style>
        .swal2-popup.kampsewa-swal {
            border-radius: 28px !important;
            padding: 28px !important;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.22) !important;
        }

        .swal2-title.kampsewa-title {
            color: #19191B !important;
            font-size: 22px !important;
            font-weight: 800 !important;
        }

        .swal2-html-container.kampsewa-text {
            color: #64748B !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            line-height: 1.6 !important;
        }

        .kampsewa-confirm-button {
            border-radius: 16px !important;
            background: #5038ED !important;
            color: white !important;
            padding: 12px 22px !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            box-shadow: 0 12px 24px rgba(80, 56, 237, 0.25) !important;
        }

        .kampsewa-confirm-button:hover {
            background: #412CCB !important;
        }

        .kampsewa-cancel-button {
            border-radius: 16px !important;
            background: #F1F5F9 !important;
            color: #475569 !important;
            padding: 12px 22px !important;
            font-size: 14px !important;
            font-weight: 800 !important;
        }

        .kampsewa-cancel-button:hover {
            background: #E2E8F0 !important;
        }

        .swal2-icon.swal2-warning {
            border-color: #5038ED !important;
            color: #5038ED !important;
        }

        .swal2-icon.swal2-success {
            border-color: #5038ED !important;
            color: #5038ED !important;
        }

        .swal2-success-line-tip,
        .swal2-success-line-long {
            background-color: #5038ED !important;
        }

        .swal2-success-ring {
            border-color: rgba(80, 56, 237, 0.25) !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const KampSewaSwal = Swal.mixin({
            customClass: {
                popup: 'kampsewa-swal',
                title: 'kampsewa-title',
                htmlContainer: 'kampsewa-text',
                confirmButton: 'kampsewa-confirm-button',
                cancelButton: 'kampsewa-cancel-button'
            },
            buttonsStyling: false
        });
    </script>
    @if (session('success'))
        <script>
            KampSewaSwal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                confirmButtonText: 'Oke'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            KampSewaSwal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error')),
                confirmButtonText: 'Oke'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            KampSewaSwal.fire({
                icon: 'error',
                title: 'Validasi gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Oke'
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn-more');

            function closeAllDropdowns() {
                document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                    menu.classList.add('invisible', 'opacity-0', 'translate-y-2');
                    menu.classList.remove('visible', 'opacity-100', 'translate-y-0');
                });
            }

            buttons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.stopPropagation();

                    const menu = this.parentElement.querySelector('.dropdown-menu');
                    const isOpen = menu.classList.contains('visible');

                    closeAllDropdowns();

                    if (!isOpen) {
                        menu.classList.remove('invisible', 'opacity-0', 'translate-y-2');
                        menu.classList.add('visible', 'opacity-100', 'translate-y-0');
                    }
                });
            });

            document.addEventListener('click', function() {
                closeAllDropdowns();
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeAllDropdowns();
                }
            });

            const filterForm = document.getElementById('customerFilterForm');
            const searchInput = document.getElementById('search');
            const filterSelect = document.getElementById('filter');

            let searchTimer = null;

            if (searchInput && filterForm) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);

                    searchTimer = setTimeout(function() {
                        filterForm.submit();
                    }, 500);
                });
            }

            if (filterSelect && filterForm) {
                filterSelect.addEventListener('change', function() {
                    filterForm.submit();
                });
            }

            const checkAllUsers = document.getElementById('checkAllUsers');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const bulkDeleteButton = document.getElementById('bulkDeleteButton');
            const selectedCount = document.getElementById('selectedCount');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');

            function updateBulkDeleteButton() {
                const checkedUsers = document.querySelectorAll('.user-checkbox:checked');
                const checkedCount = checkedUsers.length;

                if (bulkDeleteButton) {
                    bulkDeleteButton.disabled = checkedCount === 0;
                }

                if (selectedCount) {
                    selectedCount.textContent = checkedCount;

                    if (checkedCount > 0) {
                        selectedCount.classList.remove('hidden');
                    } else {
                        selectedCount.classList.add('hidden');
                    }
                }

                if (checkAllUsers) {
                    checkAllUsers.checked = checkedCount > 0 && checkedCount === userCheckboxes.length;
                    checkAllUsers.indeterminate = checkedCount > 0 && checkedCount < userCheckboxes.length;
                }
            }

            if (checkAllUsers) {
                checkAllUsers.addEventListener('change', function() {
                    userCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = checkAllUsers.checked;
                    });

                    updateBulkDeleteButton();
                });
            }

            userCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateBulkDeleteButton);
            });

            updateBulkDeleteButton();

            if (bulkDeleteForm) {
                bulkDeleteForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const checkedUsers = document.querySelectorAll('.user-checkbox:checked');
                    const total = checkedUsers.length;

                    if (total === 0) {
                        KampSewaSwal.fire({
                            icon: 'warning',
                            title: 'Belum ada data dipilih',
                            text: 'Pilih minimal satu customer terlebih dahulu.',
                            confirmButtonText: 'Oke'
                        });

                        return;
                    }

                    KampSewaSwal.fire({
                        icon: 'warning',
                        title: 'Hapus data terpilih?',
                        text: 'Yakin ingin menghapus ' + total + ' customer terpilih?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            bulkDeleteForm.submit();
                        }
                    });
                });
            }

            document.querySelectorAll('.singleDeleteForm').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const name = form.dataset.name || 'customer ini';

                    KampSewaSwal.fire({
                        icon: 'warning',
                        title: 'Hapus customer?',
                        text: 'Yakin ingin menghapus ' + name + '?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });

        let modal = document.getElementById("modal");

        function modalHandler(val) {
            if (!modal) return;

            if (val) {
                fadeIn(modal);
            } else {
                fadeOut(modal);
            }
        }

        function fadeOut(el) {
            el.style.opacity = 1;

            (function fade() {
                if ((el.style.opacity -= 0.1) < 0) {
                    el.style.display = "none";
                } else {
                    requestAnimationFrame(fade);
                }
            })();
        }

        function fadeIn(el, display) {
            el.style.opacity = 0;
            el.style.display = display || "flex";

            (function fade() {
                let val = parseFloat(el.style.opacity);

                if (!((val += 0.2) > 1)) {
                    el.style.opacity = val;
                    requestAnimationFrame(fade);
                }
            })();
        }
    </script>
@endsection
