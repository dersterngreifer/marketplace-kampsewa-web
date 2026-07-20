@php
    $fotoUser = $data->foto ? trim($data->foto) : null;

    $fotoKosong =
        !$fotoUser ||
        in_array(strtolower($fotoUser), ['belum di isi', 'belum di isi.', 'belum diisi', 'belum diisi.', 'null', '-']);

    if ($fotoKosong) {
        $fotoUrl = asset('assets/image/default/profile.png');
    } elseif (str_starts_with($fotoUser, 'http://') || str_starts_with($fotoUser, 'https://')) {
        $fotoUrl = $fotoUser;
    } elseif (str_starts_with($fotoUser, 'customers/profile/')) {
        $fotoUrl = asset('storage/' . $fotoUser) . '?v=' . strtotime($data->updated_at ?? now());
    } elseif (str_starts_with($fotoUser, 'storage/')) {
        $fotoUrl = asset($fotoUser) . '?v=' . strtotime($data->updated_at ?? now());
    } else {
        $fotoUrl = asset('assets/image/customers/profile/' . $fotoUser);
    }
@endphp
<div id="profileUserCard{{ $data->user_id }}"
    class="relative overflow-visible rounded-[28px] border border-slate-200 bg-white shadow-sm">

    {{-- Cover --}}
    <div class="relative z-0 h-[180px] rounded-t-[28px]">

        {{-- Background cover tetap clipped, tapi action menu tidak ikut terpotong --}}
        <div
            class="absolute inset-0 overflow-hidden rounded-t-[28px] bg-gradient-to-br from-[#19191B] via-[#24243A] to-[#5038ED]">
            <img class="h-full w-full object-cover opacity-50"
                src="{{ asset('assets/image/customers/background/pexels-juan-mendez-1082316.jpg') }}"
                alt="Background pengguna">

            <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-black/10 to-transparent"></div>
        </div>

        <span
            class="absolute left-4 top-4 z-10 rounded-full bg-white/20 px-3 py-1 text-xs font-bold text-white backdrop-blur">
            Customer
        </span>

        {{-- Action Button --}}
        <div class="absolute right-4 top-4 z-[100]">
            <button type="button" id="profileActionBtn{{ $data->user_id }}" aria-label="Menu aksi pengguna"
                class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/25 bg-white/20 text-white shadow-lg backdrop-blur transition hover:bg-white/30 focus:outline-none focus:ring-4 focus:ring-white/25">

                {{-- Pakai titik manual agar presisi, tidak miring seperti icon font --}}
                <span class="flex flex-col items-center justify-center gap-[3px]">
                    <span class="block h-1 w-1 rounded-full bg-current"></span>
                    <span class="block h-1 w-1 rounded-full bg-current"></span>
                    <span class="block h-1 w-1 rounded-full bg-current"></span>
                </span>
            </button>

            {{-- Dropdown --}}
            <div id="profileActionMenu{{ $data->user_id }}"
                class="fixed hidden w-56 overflow-hidden rounded-2xl border border-slate-100 bg-white p-2 shadow-2xl ring-1 ring-black/5"
                style="z-index: 999999;">

                <button type="button" id="openEditUserModal{{ $data->user_id }}"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-bold text-[#19191B] transition hover:bg-[#5038ED]/10 hover:text-[#5038ED]">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#5038ED]/10 text-[#5038ED]">
                        <i class="fi fi-rr-edit"></i>
                    </span>
                    Edit this user
                </button>

                <button type="button" id="openDeleteUserModal{{ $data->user_id }}"
                    class="mt-1 flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-bold text-[#F5325C] transition hover:bg-[#FDEAEE]">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#FDEAEE] text-[#F5325C]">
                        <i class="fi fi-rr-trash"></i>
                    </span>
                    Delete this user
                </button>
            </div>
        </div>
    </div>

    {{-- Profile --}}
    <div class="relative z-10 px-5 pb-5">
        <div class="-mt-12 flex items-end justify-between gap-4">
            <div
                class="relative z-20 h-24 w-24 shrink-0 overflow-hidden rounded-[28px] border-4 border-white bg-slate-100 shadow-xl">
                <img class="h-full w-full object-cover" src="{{ $fotoUrl }}" alt="Foto {{ $data->name }}">
                >
            </div>

            <span
                class="mb-2 inline-flex items-center gap-2 rounded-full bg-[#5038ED]/10 px-3 py-1.5 text-xs font-bold text-[#5038ED]">
                <span class="h-2 w-2 rounded-full bg-[#5038ED]"></span>
                Aktif
            </span>
        </div>

        <div class="mt-4">
            <h2 class="line-clamp-1 text-xl font-extrabold text-[#19191B]">
                {{ $data->name }}
            </h2>

            <p class="mt-1 line-clamp-1 text-sm font-medium text-slate-500">
                {{ $data->email ?? 'Email belum di isi.' }}
            </p>

            <div class="mt-3 flex flex-wrap items-center gap-2">
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                    ID #{{ $data->user_id }}
                </span>

                <span class="rounded-full bg-[#FDEAEE] px-3 py-1 text-xs font-bold text-[#F5325C]">
                    Customer
                </span>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-3">
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white text-[#5038ED] shadow-sm">
                        <i class="fi fi-rr-calendar"></i>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                            Bergabung
                        </p>
                        <p class="mt-1 text-sm font-bold text-[#19191B]">
                            {{ Carbon\Carbon::parse($data->created_at)->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white text-[#5038ED] shadow-sm">
                        <i class="fi fi-rr-party-horn"></i>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                            Tanggal Lahir
                        </p>
                        <p class="mt-1 text-sm font-bold text-[#19191B]">
                            {{ $data->tanggal_lahir ? Carbon\Carbon::parse($data->tanggal_lahir)->translatedFormat('d F Y') : 'Belum di isi.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-1">
            <div class="rounded-2xl bg-[#5038ED]/10 p-4">
                <div
                    class="mb-2 flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-[#5038ED] shadow-sm">
                    <i class="fi fi-rr-mobile-notch"></i>
                </div>

                <p class="text-sm font-extrabold text-[#19191B]">
                    Nomor Telepon
                </p>
                <p class="mt-1 break-words text-sm font-medium text-slate-500">
                    {{ $data->nomor_telephone ?: 'Belum di isi.' }}
                </p>
            </div>

            <div class="rounded-2xl bg-[#FDEAEE] p-4">
                <div
                    class="mb-2 flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-[#F5325C] shadow-sm">
                    <i class="fi fi-rr-venus-mars"></i>
                </div>

                <p class="text-sm font-extrabold text-[#19191B]">
                    Jenis Kelamin
                </p>
                <p class="mt-1 text-sm font-medium text-slate-500">
                    {{ $data->jenis_kelamin ?: 'Belum di isi.' }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Edit User Modal --}}
<div id="editUserModal{{ $data->user_id }}"
    class="fixed inset-0 z-[9998] hidden items-center justify-center bg-[#19191B]/60 px-4 py-6 backdrop-blur-sm">
    <div class="relative max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-[32px] bg-white shadow-2xl">

        <div class="sticky top-0 z-10 border-b border-slate-100 bg-white/90 px-6 py-5 backdrop-blur-xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div
                        class="mb-3 inline-flex items-center gap-2 rounded-full bg-[#5038ED]/10 px-3 py-1.5 text-xs font-extrabold text-[#5038ED]">
                        <i class="fi fi-rr-edit"></i>
                        Edit Pengguna
                    </div>

                    <h3 class="text-xl font-extrabold text-[#19191B]">
                        Perbarui Data Customer
                    </h3>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Ubah informasi pengguna dengan form yang tersedia di bawah ini.
                    </p>
                </div>

                <button type="button" id="closeEditUserModal{{ $data->user_id }}"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-[#FDEAEE] hover:text-[#F5325C]">
                    <i class="fi fi-rr-cross-small text-xl"></i>
                </button>
            </div>
        </div>

        <form action="{{ route('kelola-pengguna.update', $data->user_id) }}" method="POST"
            enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-extrabold text-[#19191B]">
                        Foto Profile
                    </label>

                    <div class="rounded-[28px] border border-slate-200 bg-slate-50 p-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-[112px_1fr] sm:items-stretch">

                            {{-- Preview Foto Lama / Baru --}}
                            <div
                                class="relative mx-auto h-28 w-28 overflow-hidden rounded-[28px] border-4 border-white bg-white shadow-md sm:mx-0">
                                <img id="previewFotoUser{{ $data->user_id }}" src="{{ $fotoUrl }}"
                                    alt="Foto {{ $data->name }}" class="h-full w-full object-cover">

                                <div
                                    class="absolute inset-x-0 bottom-0 bg-black/45 px-2 py-1 text-center text-[10px] font-extrabold text-white">
                                    Foto Saat Ini
                                </div>
                            </div>

                            {{-- Area Upload --}}
                            <div class="min-w-0">
                                <label for="fotoUserInput{{ $data->user_id }}"
                                    class="group flex min-h-[140px] w-full cursor-pointer flex-col items-center justify-center rounded-[24px] border-2 border-dashed border-[#5038ED]/30 bg-white px-5 py-6 text-center transition hover:border-[#5038ED] hover:bg-[#5038ED]/5 sm:min-h-full">

                                    <div
                                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#5038ED]/10 text-[#5038ED] transition group-hover:scale-105">
                                        <i class="fi fi-rr-cloud-upload-alt text-xl"></i>
                                    </div>

                                    <p class="text-sm font-extrabold text-[#19191B]">
                                        Klik untuk upload foto baru
                                    </p>

                                    <p id="fotoFileName{{ $data->user_id }}"
                                        class=" max-w-full truncate text-xs font-semibold text-slate-500">
                                        JPG, JPEG, PNG, WEBP maksimal 2MB
                                    </p>

                                    <p class="text-[11px] font-bold text-[#F5325C]">
                                        Jika disimpan, foto lama akan diganti.
                                    </p>
                                </label>

                                <input id="fotoUserInput{{ $data->user_id }}" type="file" name="foto"
                                    accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden">
                            </div>
                        </div>
                    </div>

                    @error('foto')
                        <p class="mt-2 text-xs font-bold text-[#F5325C]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-extrabold text-[#19191B]">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <i class="fi fi-rr-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="name" value="{{ old('name', $data->name) }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-bold text-[#19191B] outline-none transition focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10"
                            placeholder="Masukkan nama pengguna">
                    </div>
                    @error('name')
                        <p class="mt-2 text-xs font-bold text-[#F5325C]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-extrabold text-[#19191B]">
                        Email
                    </label>
                    <div class="relative">
                        <i class="fi fi-rr-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" name="email" value="{{ old('email', $data->email) }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-bold text-[#19191B] outline-none transition focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10"
                            placeholder="Masukkan email pengguna">
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs font-bold text-[#F5325C]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-extrabold text-[#19191B]">
                        Nomor Telepon
                    </label>
                    <div class="relative">
                        <i class="fi fi-rr-mobile-notch absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="nomor_telephone"
                            value="{{ old('nomor_telephone', $data->nomor_telephone) }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-bold text-[#19191B] outline-none transition focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10"
                            placeholder="Contoh: 081234567890">
                    </div>
                    @error('nomor_telephone')
                        <p class="mt-2 text-xs font-bold text-[#F5325C]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-extrabold text-[#19191B]">
                        Tanggal Lahir
                    </label>
                    <div class="relative">
                        <i class="fi fi-rr-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="date" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $data->tanggal_lahir ? Carbon\Carbon::parse($data->tanggal_lahir)->format('Y-m-d') : '') }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-bold text-[#19191B] outline-none transition focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10">
                    </div>
                    @error('tanggal_lahir')
                        <p class="mt-2 text-xs font-bold text-[#F5325C]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-extrabold text-[#19191B]">
                        Jenis Kelamin
                    </label>
                    <div class="relative">
                        <i class="fi fi-rr-venus-mars absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select name="jenis_kelamin"
                            class="w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-10 text-sm font-bold text-[#19191B] outline-none transition focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Laki-laki" @selected(old('jenis_kelamin', $data->jenis_kelamin) == 'Laki-laki')>
                                Laki-laki
                            </option>
                            <option value="Perempuan" @selected(old('jenis_kelamin', $data->jenis_kelamin) == 'Perempuan')>
                                Perempuan
                            </option>
                        </select>
                        <i
                            class="fi fi-rr-angle-small-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                    @error('jenis_kelamin')
                        <p class="mt-2 text-xs font-bold text-[#F5325C]">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancelEditUserModal{{ $data->user_id }}"
                    class="rounded-2xl bg-slate-100 px-5 py-3 text-sm font-extrabold text-slate-600 transition hover:bg-slate-200">
                    Batal
                </button>

                <button type="submit"
                    class="rounded-2xl bg-[#5038ED] px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-[#5038ED]/25 transition hover:-translate-y-0.5 hover:bg-[#432FD1]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Delete User Modal --}}
<div id="deleteUserModal{{ $data->user_id }}"
    class="fixed inset-0 z-[9999] hidden items-center justify-center bg-[#19191B]/60 px-4 py-6 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-[32px] bg-white p-6 text-center shadow-2xl">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[28px] bg-[#FDEAEE] text-[#F5325C]">
            <i class="fi fi-rr-trash text-3xl"></i>
        </div>

        <h3 class="mt-5 text-2xl font-extrabold text-[#19191B]">
            Hapus pengguna ini?
        </h3>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            Data pengguna <span class="font-extrabold text-[#19191B]">{{ $data->name }}</span>
            akan dihapus dari sistem. Aksi ini tidak bisa dibatalkan.
        </p>

        <div class="mt-4 rounded-2xl bg-slate-50 p-4 text-left">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Detail Pengguna
            </p>
            <p class="mt-1 text-sm font-extrabold text-[#19191B]">
                {{ $data->name }}
            </p>
            <p class="mt-1 break-words text-sm font-medium text-slate-500">
                {{ $data->email ?? 'Email belum di isi.' }}
            </p>
        </div>

        <form action="{{ route('kelola-pengguna.detail-destroy', $data->user_id) }}" method="POST" class="mt-6">
            @csrf
            @method('DELETE')

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <button type="button" id="cancelDeleteUserModal{{ $data->user_id }}"
                    class="rounded-2xl bg-slate-100 px-5 py-3 text-sm font-extrabold text-slate-600 transition hover:bg-slate-200">
                    Batal
                </button>

                <button type="submit"
                    class="rounded-2xl bg-[#F5325C] px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-[#F5325C]/25 transition hover:-translate-y-0.5 hover:bg-[#df244d]">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userId = @json($data->user_id);

        const actionBtn = document.getElementById(`profileActionBtn${userId}`);
        const actionMenu = document.getElementById(`profileActionMenu${userId}`);

        const editModal = document.getElementById(`editUserModal${userId}`);
        const deleteModal = document.getElementById(`deleteUserModal${userId}`);

        const openEditBtn = document.getElementById(`openEditUserModal${userId}`);
        const closeEditBtn = document.getElementById(`closeEditUserModal${userId}`);
        const cancelEditBtn = document.getElementById(`cancelEditUserModal${userId}`);

        const openDeleteBtn = document.getElementById(`openDeleteUserModal${userId}`);
        const cancelDeleteBtn = document.getElementById(`cancelDeleteUserModal${userId}`);

        const fotoInput = document.getElementById(`fotoUserInput${userId}`);
        const fotoPreview = document.getElementById(`previewFotoUser${userId}`);

        const fotoFileName = document.getElementById(`fotoFileName${userId}`);

        // Pindahkan modal ke body agar tidak kalah z-index / tidak terpotong parent overflow
        if (editModal && editModal.parentElement !== document.body) {
            document.body.appendChild(editModal);
        }

        if (deleteModal && deleteModal.parentElement !== document.body) {
            document.body.appendChild(deleteModal);
        }

        function openModal(modal) {
            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(modal) {
            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function closeActionMenu() {
            actionMenu?.classList.add('hidden');
        }

        actionBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            actionMenu?.classList.toggle('hidden');
        });

        actionMenu?.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        openEditBtn?.addEventListener('click', function() {
            closeActionMenu();
            openModal(editModal);
        });

        closeEditBtn?.addEventListener('click', function() {
            closeModal(editModal);
        });

        cancelEditBtn?.addEventListener('click', function() {
            closeModal(editModal);
        });

        openDeleteBtn?.addEventListener('click', function() {
            closeActionMenu();
            openModal(deleteModal);
        });

        cancelDeleteBtn?.addEventListener('click', function() {
            closeModal(deleteModal);
        });

        editModal?.addEventListener('click', function(e) {
            if (e.target === editModal) {
                closeModal(editModal);
            }
        });

        deleteModal?.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                closeModal(deleteModal);
            }
        });

        document.addEventListener('click', function(e) {
            if (!actionBtn?.contains(e.target) && !actionMenu?.contains(e.target)) {
                closeActionMenu();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeActionMenu();

                if (!editModal?.classList.contains('hidden')) {
                    closeModal(editModal);
                }

                if (!deleteModal?.classList.contains('hidden')) {
                    closeModal(deleteModal);
                }
            }
        });

        // Preview foto baru sebelum submit
        fotoInput?.addEventListener('change', function() {
            const file = this.files?.[0];

            if (!file || !fotoPreview) return;

            const reader = new FileReader();

            reader.onload = function(e) {
                fotoPreview.src = e.target.result;
            };

            reader.readAsDataURL(file);
        });

        fotoInput?.addEventListener('change', function() {
            const file = this.files?.[0];

            if (!file) return;

            if (fotoPreview) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    fotoPreview.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }

            if (fotoFileName) {
                fotoFileName.textContent = file.name;
            }
        });

        @if ($errors->any())
            openModal(editModal);
        @endif
    });
</script>
