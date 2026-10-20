<div id="modal-tambah-admin" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden my-8">
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-slate-100 bg-gradient-to-r from-[#080E2E] via-[#1E2756] to-[#5038ED] text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <i class="fi fi-rr-user-add text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-black">Tambah Admin KampSewa Baru</h3>
                    <p class="text-xs text-white/70">Tambahkan akun administrator baru dengan akses penuh ke Developer Panel.</p>
                </div>
            </div>
            <button type="button" onclick="modalTambahAdminHandler(false)" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        {{-- Form --}}
        <form action="{{ route('kelola-pengguna.store-admin') }}" method="POST" enctype="multipart/form-data" class="p-6 flex flex-col gap-5">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Nama Lengkap --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Nama Lengkap Admin <span class="text-rose-500">*</span></label>
                    <input type="text" name="fullname" value="{{ old('fullname') }}" required
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-400"
                        placeholder="Contoh: Admin Dian">
                </div>

                {{-- Email --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Email KampSewa <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-400"
                        placeholder="admin@kampsewa.com">
                </div>

                {{-- No Telepon --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                    <input type="text" name="number_phone" value="{{ old('number_phone') }}" required
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-400"
                        placeholder="081234567890">
                </div>

                {{-- Password --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required minlength="6"
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-400"
                        placeholder="Minimal 6 karakter">
                </div>

                {{-- Tanggal Lahir --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Tanggal Lahir <span class="text-rose-500">*</span></label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-400">
                </div>

                {{-- Gender --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select name="gender" required
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-400">
                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            {{-- Foto Profil --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Foto Profil Admin (Opsional)</label>
                <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp"
                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 border border-slate-200 rounded-xl p-1.5">
                <p class="text-[11px] text-slate-400">Format JPG, JPEG, PNG, atau WEBP. Maks 10 MB.</p>
            </div>

            {{-- Footer Buttons --}}
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                <button type="button" onclick="modalTambahAdminHandler(false)"
                    class="px-4 py-2.5 text-xs font-bold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition active:scale-95">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-xs font-bold text-white bg-[#5038ED] hover:bg-[#412CCB] rounded-xl shadow-md transition active:scale-95">
                    <i class="bi bi-check-lg mr-1"></i> Simpan Admin Baru
                </button>
            </div>
        </form>
    </div>
</div>
