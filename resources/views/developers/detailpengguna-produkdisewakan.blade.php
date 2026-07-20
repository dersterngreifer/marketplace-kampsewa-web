@extends('layouts.developers.ly-dashboard')

@section('content')
    <style>
        .produk-masonry-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            grid-auto-rows: 8px;
            gap: 18px;
            align-items: start;
            width: 100%;
        }

        .produk-masonry-item {
            grid-row-end: span 1;
            min-width: 0;
        }

        .produk-masonry-content {
            width: 100%;
        }

        .produk-card-image {
            display: block;
            width: 100%;
            height: auto;
        }

        @media (min-width: 1280px) {
            .produk-masonry-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
        }

        @media (min-width: 1536px) {
            .produk-masonry-grid {
                grid-template-columns: repeat(auto-fit, minmax(265px, 1fr));
            }
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #F1F5F9;
            border-radius: 999px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 999px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #5038ED;
        }

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
            color: #ffffff !important;
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

        .swal2-icon.swal2-warning,
        .swal2-icon.swal2-error,
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

    <div class="min-h-screen w-full bg-[#F6F7FB] px-4 py-5 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div
            class="mb-6 overflow-hidden rounded-[30px] bg-gradient-to-br from-[#19191B] via-[#24243A] to-[#5038ED] p-5 text-white shadow-lg sm:p-7"
        >
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <a
                        href="{{ route('detail-pengguna.index', ['user' => $user_id]) }}"
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-bold text-white backdrop-blur transition hover:bg-white/25"
                    >
                        <i class="fi fi-rr-arrow-small-left text-base"></i>
                        <span>Kembali</span>
                    </a>

                    <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">
                        Barang Disewakan Oleh {{ $name }}
                    </h1>

                    <p class="mt-2 max-w-3xl text-sm leading-6 text-white/75">
                        Menampilkan semua barang yang disediakan atau disewakan oleh customer
                        <b class="text-white">{{ $name }}</b>.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:flex sm:items-center">
                    <div class="rounded-2xl bg-white/15 px-4 py-3 backdrop-blur">
                        <p class="text-xs text-white/70">Total Produk</p>
                        <p class="mt-1 text-xl font-extrabold">{{ $get_data_produk->count() }}</p>
                    </div>

                    <div class="rounded-2xl bg-white/15 px-4 py-3 backdrop-blur">
                        <p class="text-xs text-white/70">Kategori</p>
                        <p class="mt-1 text-xl font-extrabold">{{ count($get_kategori) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter, Search, Action --}}
        <div class="mb-6 rounded-[28px] border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <form
                id="productFilterForm"
                method="GET"
                action="{{ route('detail-pengguna.produk-disewakan', $user_id) }}"
                class="grid grid-cols-1 gap-3 xl:grid-cols-[auto_230px_1fr_auto_auto] xl:items-center"
            >
                {{-- Check all --}}
                <label
                    class="flex h-12 cursor-pointer items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                >
                    <span class="relative flex items-center">
                        <input
                            type="checkbox"
                            id="check-all"
                            class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-slate-300 bg-white transition-all checked:border-[#5038ED] checked:bg-[#5038ED]"
                        >

                        <span
                            class="pointer-events-none absolute left-1/2 top-1/2 hidden -translate-x-1/2 -translate-y-1/2 text-white peer-checked:block"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </span>
                    </span>

                    <span>Pilih Semua</span>
                </label>

                {{-- Filter Category --}}
                <div class="relative">
                    <select
                        name="filter_category"
                        id="filter-category"
                        class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50 px-4 pr-10 text-sm font-bold text-[#19191B] outline-none transition focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10"
                    >
                        <option value="Semua Barang" {{ request('filter_category') == 'Semua Barang' ? 'selected' : '' }}>
                            Semua Barang
                        </option>

                        @foreach ($get_kategori as $kategori)
                            <option value="{{ $kategori }}" {{ request('filter_category') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                        <i class="fi fi-rr-angle-small-down"></i>
                    </div>
                </div>

                {{-- Search --}}
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                        <i class="fi fi-rr-search text-sm"></i>
                    </div>

                    <input
                        type="text"
                        name="cari_barang"
                        id="searchProduct"
                        value="{{ $cari_barang }}"
                        placeholder="Cari nama barang..."
                        autocomplete="off"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-medium text-[#19191B] outline-none transition placeholder:text-slate-400 focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10"
                    >
                </div>

                {{-- Delete Selected --}}
                <button
                    type="button"
                    id="deleteSelectedButton"
                    disabled
                    class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-red-500 px-5 text-sm font-bold text-white shadow-md shadow-red-500/20 transition hover:-translate-y-0.5 hover:bg-red-600 active:scale-[0.98] disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none disabled:hover:translate-y-0"
                >
                    <i class="fi fi-rr-trash text-sm"></i>
                    <span>Hapus</span>
                    <span id="selectedCount" class="hidden rounded-full bg-white/20 px-2 py-0.5 text-xs">0</span>
                </button>

                {{-- Reset --}}
                <a
                    href="{{ route('detail-pengguna.produk-disewakan', $user_id) }}"
                    class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 active:scale-[0.98]"
                >
                    <i class="fi fi-rr-refresh text-sm"></i>
                    <span>Reset</span>
                </a>
            </form>
        </div>

        {{-- Product List --}}
        @if ($get_data_produk->count() == 0)
            <div class="rounded-[30px] border border-dashed border-slate-300 bg-white p-8 shadow-sm">
                <div class="flex flex-col items-center justify-center gap-5 text-center lg:flex-row lg:text-left">
                    <img
                        class="w-[240px] max-w-full object-contain"
                        src="{{ asset('images/illustration/filling-survey.png') }}"
                        alt="Tidak ada produk"
                    >

                    <div>
                        <p class="text-4xl font-black text-[#19191B]">
                            OOPS!
                        </p>

                        <p class="mt-2 text-base font-semibold leading-7 text-slate-500">
                            Sepertinya {{ $name }} tidak memiliki produk yang sedang disewakan.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div id="produkMasonryGrid" class="produk-masonry-grid">
                @foreach ($get_data_produk as $item)
                    <article class="produk-masonry-item">
                        <div
                            class="produk-masonry-content group overflow-hidden rounded-[26px] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#5038ED]/30 hover:shadow-xl hover:shadow-slate-200/80"
                        >
                            {{-- Image --}}
                            <a
                                href="{{ route('detail-pengguna.detail-produk-disewakan', ['user' => $user_id, 'namaproduk' => $item->nama]) }}"
                                class="relative block overflow-hidden bg-slate-100"
                            >
                                <img
                                    class="produk-masonry-image produk-card-image transition duration-500 group-hover:scale-[1.03]"
                                    src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($item) }}"
                                    alt="{{ $item->nama }}"
                                    loading="lazy"
                                >

                                <div
                                    class="absolute inset-x-0 bottom-0 flex translate-y-full items-center justify-between bg-gradient-to-t from-black/85 via-black/40 to-transparent px-4 pb-4 pt-12 transition duration-300 group-hover:translate-y-0"
                                >
                                    <span class="text-sm font-bold text-white">
                                        Lihat Detail
                                    </span>

                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-[#5038ED]">
                                        <i class="fi fi-rr-angle-small-right text-xl"></i>
                                    </span>
                                </div>
                            </a>

                            {{-- Body --}}
                            <div class="p-4">
                                <div class="mb-3 flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <h2 class="line-clamp-2 text-base font-extrabold capitalize leading-6 text-[#19191B]">
                                            {{ $item->nama ?: 'Nama produk belum tersedia' }}
                                        </h2>

                                        <p class="mt-1 line-clamp-2 text-sm font-medium leading-6 text-slate-500">
                                            {{ $item->deskripsi ?: 'Produk ini belum memiliki deskripsi.' }}
                                        </p>
                                    </div>

                                    <span class="shrink-0 rounded-full bg-[#5038ED]/10 px-3 py-1 text-xs font-bold text-[#5038ED]">
                                        Produk
                                    </span>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-3">
                                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                        Mulai Dari
                                    </p>

                                    <p class="mt-1 text-lg font-extrabold text-[#19191B]">
                                        Rp {{ number_format($item->harga_sewa_terkecil ?? 0, 0, ',', '.') }}
                                        <span class="text-xs font-bold text-slate-400">/Hari</span>
                                    </p>
                                </div>

                                {{-- Footer Action --}}
                                <div class="mt-4 flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
                                    <label
                                        class="inline-flex cursor-pointer items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2 transition hover:bg-slate-50"
                                    >
                                        <span class="relative flex items-center">
                                            <input
                                                type="checkbox"
                                                data-id="{{ $item->id_produk }}"
                                                class="product-checkbox peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-slate-300 bg-white transition-all checked:border-[#5038ED] checked:bg-[#5038ED]"
                                            >

                                            <span
                                                class="pointer-events-none absolute left-1/2 top-1/2 hidden -translate-x-1/2 -translate-y-1/2 text-white peer-checked:block"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </span>
                                        </span>

                                        <span class="text-xs font-bold text-slate-600">
                                            Pilih
                                        </span>
                                    </label>

                                    <a
                                        href="{{ route('detail-pengguna.detail-produk-disewakan', ['user' => $user_id, 'namaproduk' => $item->nama]) }}"
                                        class="inline-flex h-10 items-center justify-center gap-2 rounded-2xl bg-[#5038ED] px-4 text-sm font-bold text-white shadow-md shadow-[#5038ED]/20 transition hover:bg-[#412CCB]"
                                    >
                                        <span>Detail</span>
                                        <i class="fi fi-rr-angle-small-right text-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>

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

        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('check-all');
            const productCheckboxes = document.querySelectorAll('.product-checkbox');
            const deleteSelectedButton = document.getElementById('deleteSelectedButton');
            const selectedCount = document.getElementById('selectedCount');
            const filterForm = document.getElementById('productFilterForm');
            const searchProduct = document.getElementById('searchProduct');
            const filterCategory = document.getElementById('filter-category');
            const masonryGrid = document.getElementById('produkMasonryGrid');

            function resizeMasonryItem(item) {
                if (!masonryGrid || !item) return;

                const rowHeight = parseInt(window.getComputedStyle(masonryGrid).getPropertyValue('grid-auto-rows'));
                const rowGap = parseInt(window.getComputedStyle(masonryGrid).getPropertyValue('row-gap'));
                const content = item.querySelector('.produk-masonry-content');

                if (!content || !rowHeight) return;

                const contentHeight = content.getBoundingClientRect().height;
                const rowSpan = Math.ceil((contentHeight + rowGap) / (rowHeight + rowGap));

                item.style.gridRowEnd = 'span ' + rowSpan;
            }

            function resizeAllMasonryItems() {
                document.querySelectorAll('.produk-masonry-item').forEach(function (item) {
                    resizeMasonryItem(item);
                });
            }

            document.querySelectorAll('.produk-masonry-image').forEach(function (image) {
                image.addEventListener('load', function () {
                    resizeAllMasonryItems();
                });

                image.addEventListener('error', function () {
                    resizeAllMasonryItems();
                });
            });

            window.addEventListener('load', function () {
                resizeAllMasonryItems();
            });

            window.addEventListener('resize', function () {
                resizeAllMasonryItems();
            });

            setTimeout(resizeAllMasonryItems, 300);
            setTimeout(resizeAllMasonryItems, 800);

            function updateDeleteButton() {
                const checkedItems = document.querySelectorAll('.product-checkbox:checked');
                const count = checkedItems.length;

                if (deleteSelectedButton) {
                    deleteSelectedButton.disabled = count === 0;
                }

                if (selectedCount) {
                    selectedCount.textContent = count;

                    if (count > 0) {
                        selectedCount.classList.remove('hidden');
                    } else {
                        selectedCount.classList.add('hidden');
                    }
                }

                if (checkAll) {
                    checkAll.checked = count > 0 && count === productCheckboxes.length;
                    checkAll.indeterminate = count > 0 && count < productCheckboxes.length;
                }
            }

            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    productCheckboxes.forEach(function (checkbox) {
                        checkbox.checked = checkAll.checked;
                    });

                    updateDeleteButton();
                });
            }

            productCheckboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', updateDeleteButton);
            });

            updateDeleteButton();

            let searchTimer = null;

            if (searchProduct && filterForm) {
                searchProduct.addEventListener('input', function () {
                    clearTimeout(searchTimer);

                    searchTimer = setTimeout(function () {
                        filterForm.submit();
                    }, 500);
                });
            }

            if (filterCategory && filterForm) {
                filterCategory.addEventListener('change', function () {
                    filterForm.submit();
                });
            }

            if (deleteSelectedButton) {
                deleteSelectedButton.addEventListener('click', function () {
                    const selectedIds = [];

                    document.querySelectorAll('.product-checkbox:checked').forEach(function (checkbox) {
                        selectedIds.push(checkbox.dataset.id);
                    });

                    if (selectedIds.length === 0) {
                        KampSewaSwal.fire({
                            icon: 'warning',
                            title: 'Belum ada produk dipilih',
                            text: 'Pilih minimal satu produk terlebih dahulu.',
                            confirmButtonText: 'Oke'
                        });

                        return;
                    }

                    KampSewaSwal.fire({
                        icon: 'warning',
                        title: 'Hapus produk terpilih?',
                        text: 'Yakin ingin menghapus ' + selectedIds.length + ' produk? Data yang dihapus tidak dapat dikembalikan.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (!result.isConfirmed) return;

                        fetch("{{ route('delete_selected_products') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                ids: selectedIds
                            })
                        })
                        .then(async function (response) {
                            if (!response.ok) {
                                throw new Error(await response.text());
                            }

                            KampSewaSwal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Produk terpilih berhasil dihapus.',
                                confirmButtonText: 'Oke'
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(function () {
                            KampSewaSwal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Produk gagal dihapus. Periksa kembali route atau controller delete selected products.',
                                confirmButtonText: 'Oke'
                            });
                        });
                    });
                });
            }
        });
    </script>
@endsection