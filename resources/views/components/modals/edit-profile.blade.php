<div id="modalEditProfile" class="hidden">
    <div class="fixed inset-0 z-50 flex min-h-screen w-full items-center justify-center bg-slate-900/40 px-4 py-6 backdrop-blur-sm"
        onclick="modalEditProfileHandler(false)">
        <div
            class="relative flex max-h-[92vh] w-full max-w-[620px] flex-col overflow-hidden rounded-[28px] bg-white shadow-2xl shadow-slate-900/20"
            onclick="event.stopPropagation()">

            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-[#19191B] via-[#25253A] to-[#5038ED] px-5 py-5 text-white sm:px-6">
                <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-14 left-8 h-28 w-28 rounded-full bg-white/10"></div>

                <div class="relative flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/15 backdrop-blur">
                            <i class="fi fi-rr-user-edit text-lg"></i>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold">
                                Edit Profile
                            </h2>
                            <p class="mt-1 text-sm leading-5 text-white/70">
                                Perbarui data dan foto profil developer anda.
                            </p>
                        </div>
                    </div>

                    <button type="button" onclick="modalEditProfileHandler(false)"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-white transition hover:bg-white/25 active:scale-95">
                        <i class="fi fi-rr-cross-small text-2xl"></i>
                    </button>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                class="flex min-h-0 flex-1 flex-col">
                @csrf
                @method('PUT')

                {{-- Body --}}
                <div class="flex-1 overflow-y-auto px-5 py-5 sm:px-6">
                    <div class="grid grid-cols-1 gap-4">

                        {{-- Upload Photo --}}
                        <div>
                            <p class="mb-2 text-sm font-bold text-[#19191B]">
                                Foto Profil
                            </p>

                            <label for="foto"
                                class="group relative flex min-h-[210px] w-full cursor-pointer items-center justify-center overflow-hidden rounded-[24px] border-2 border-dashed border-slate-300 bg-slate-50 transition hover:border-[#5038ED] hover:bg-[#5038ED]/5">
                                <img id="fotoPreview"
                                    class="hidden h-full min-h-[210px] w-full object-cover"
                                    src="" alt="Preview foto developer">

                                <div id="fotoPlaceholder" class="flex flex-col items-center justify-center px-5 text-center">
                                    <div
                                        class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[#5038ED] shadow-sm">
                                        <i class="fi fi-rr-picture text-[28px]"></i>
                                    </div>

                                    <p class="text-sm font-bold text-[#19191B]">
                                        Upload foto klik <u>disini!</u>
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-slate-500">
                                        Format foto didukung JPG, PNG dan WEBP. Ukuran maksimal 2 MB.
                                    </p>
                                </div>

                                <div
                                    class="absolute bottom-3 right-3 hidden rounded-full bg-white px-3 py-1.5 text-xs font-bold text-[#5038ED] shadow-md group-hover:block">
                                    Ganti Foto
                                </div>
                            </label>

                            <input type="file" name="foto" id="foto" accept="image/png, image/jpeg, image/jpg, image/webp"
                                class="hidden" onchange="previewDeveloperPhoto(event)">

                            @error('foto')
                                <p class="mt-2 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-bold text-[#19191B]">
                                Nama Lengkap
                            </label>

                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                                    <i class="fi fi-rr-user text-sm"></i>
                                </div>

                                <input
                                    class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-semibold text-[#19191B] outline-none transition placeholder:text-slate-400 focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10 @error('name') border-red-400 focus:border-red-400 focus:ring-red-100 @enderror"
                                    placeholder="Masukkan nama lengkap" type="text" id="name" name="name"
                                    value="{{ old('name', session('nama_lengkap')) }}" required>
                            </div>

                            @error('name')
                                <p class="mt-2 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="mb-2 block text-sm font-bold text-[#19191B]">
                                Email
                            </label>

                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                                    <i class="fi fi-rr-envelope text-sm"></i>
                                </div>

                                <input
                                    class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-semibold text-[#19191B] outline-none transition placeholder:text-slate-400 focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10 @error('email') border-red-400 focus:border-red-400 focus:ring-red-100 @enderror"
                                    placeholder="Masukkan email" type="email" id="email" name="email"
                                    value="{{ old('email', session('email')) }}" required>
                            </div>

                            @error('email')
                                <p class="mt-2 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <label for="nomor_telephone" class="mb-2 block text-sm font-bold text-[#19191B]">
                                Nomor Telepon
                            </label>

                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                                    <i class="fi fi-rr-phone-call text-sm"></i>
                                </div>

                                <input
                                    class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-semibold text-[#19191B] outline-none transition placeholder:text-slate-400 focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10 @error('nomor_telephone') border-red-400 focus:border-red-400 focus:ring-red-100 @enderror"
                                    placeholder="Masukkan nomor telepon" type="text" id="nomor_telephone"
                                    name="nomor_telephone" value="{{ old('nomor_telephone', session('nomor_telephone')) }}"
                                    required>
                            </div>

                            @error('nomor_telephone')
                                <p class="mt-2 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label for="tanggal_lahir" class="mb-2 block text-sm font-bold text-[#19191B]">
                                Tanggal Lahir
                            </label>

                            <div class="relative">
                                <input
                                    class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-[#19191B] outline-none transition focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10 @error('tanggal_lahir') border-red-400 focus:border-red-400 focus:ring-red-100 @enderror"
                                    type="date" id="tanggal_lahir" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', session('tanggal_lahir')) }}">

                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                                    <i class="fi fi-rr-calendar text-sm"></i>
                                </div>
                            </div>

                            @error('tanggal_lahir')
                                <p class="mt-2 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label for="jenis_kelamin" class="mb-2 block text-sm font-bold text-[#19191B]">
                                Jenis Kelamin
                            </label>

                            <div class="relative">
                                <select
                                    class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50 px-4 pr-11 text-sm font-semibold text-[#19191B] outline-none transition focus:border-[#5038ED] focus:bg-white focus:ring-4 focus:ring-[#5038ED]/10"
                                    id="jenis_kelamin" name="jenis_kelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" @selected(old('jenis_kelamin', session('jenis_kelamin')) === 'Laki-laki')>
                                        Laki-laki
                                    </option>
                                    <option value="Perempuan" @selected(old('jenis_kelamin', session('jenis_kelamin')) === 'Perempuan')>
                                        Perempuan
                                    </option>
                                </select>

                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                                    <i class="fi fi-rr-angle-small-down"></i>
                                </div>
                            </div>

                            @error('jenis_kelamin')
                                <p class="mt-2 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="border-t border-slate-100 bg-white px-5 py-4 sm:px-6">
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button type="button"
                            class="h-11 rounded-2xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 active:scale-[0.98]"
                            onclick="modalEditProfileHandler(false)">
                            Cancel
                        </button>

                        <button type="submit"
                            class="h-11 rounded-2xl bg-[#5038ED] px-6 text-sm font-bold text-white shadow-md shadow-[#5038ED]/20 transition hover:-translate-y-0.5 hover:bg-[#4527D4] active:scale-[0.98]">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modalEditProfile = document.getElementById("modalEditProfile");

    function modalEditProfileHandler(val) {
        if (!modalEditProfile) return;

        if (val) {
            modalEditProfile.style.display = "flex";
            modalEditProfile.style.opacity = 1;
        } else {
            modalEditProfile.style.opacity = 0;
            modalEditProfile.style.display = "none";
        }
    }

    function previewDeveloperPhoto(event) {
        const file = event.target.files[0];
        if (!file) return;

        const preview = document.getElementById("fotoPreview");
        const placeholder = document.getElementById("fotoPlaceholder");

        preview.src = URL.createObjectURL(file);
        preview.classList.remove("hidden");
        placeholder.classList.add("hidden");
    }

    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            modalEditProfileHandler(true);
        });
    @endif
</script>
