<div id="modal-edit-admin" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden my-8">
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-slate-100 bg-gradient-to-r from-[#19191B] via-[#24243A] to-[#12A4ED] text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <i class="fi fi-rr-edit text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-black">Edit Data Admin KampSewa</h3>
                    <p class="text-xs text-white/70">Perbarui informasi profil dan kredensial administrator.</p>
                </div>
            </div>
            <button type="button" onclick="modalEditAdminHandler(false)" class="text-white/70 hover:text-white transition">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        {{-- Form --}}
        <form id="form-edit-admin" action="" method="POST" enctype="multipart/form-data" class="p-6 flex flex-col gap-5">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Nama Lengkap --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Nama Lengkap Admin <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit-admin-fullname" name="fullname" required
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-400"
                        placeholder="Contoh: Admin Dian">
                </div>

                {{-- Email --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Email KampSewa <span class="text-rose-500">*</span></label>
                    <input type="email" id="edit-admin-email" name="email" required
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-400"
                        placeholder="admin@kampsewa.com">
                </div>

                {{-- No Telepon --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit-admin-phone" name="number_phone" required
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-400"
                        placeholder="081234567890">
                </div>

                {{-- Password Baru (Opsional) --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" id="edit-admin-password" name="password" minlength="6"
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-400"
                        placeholder="Minimal 6 karakter">
                </div>

                {{-- Tanggal Lahir --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Tanggal Lahir</label>
                    <input type="date" id="edit-admin-birth" name="date_of_birth"
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-400">
                </div>

                {{-- Gender --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Jenis Kelamin</label>
                    <select id="edit-admin-gender" name="gender"
                        class="h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-400">
                        <option value="">Belum Ditentukan</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            {{-- Foto Profil --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Ganti Foto Profil (Opsional)</label>
                <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp"
                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 border border-slate-200 rounded-xl p-1.5">
                <p class="text-[11px] text-slate-400">Pilih file baru hanya jika ingin mengganti foto profil yang lama.</p>
            </div>

            {{-- Footer Buttons --}}
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                <button type="button" onclick="modalEditAdminHandler(false)"
                    class="px-4 py-2.5 text-xs font-bold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition active:scale-95">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-xs font-bold text-white bg-[#12A4ED] hover:bg-[#0d8ecc] rounded-xl shadow-md transition active:scale-95">
                    <i class="bi bi-check-lg mr-1"></i> Perbarui Data Admin
                </button>
            </div>
        </form>
    </div>
</div>
