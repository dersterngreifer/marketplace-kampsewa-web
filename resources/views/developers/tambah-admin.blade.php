@extends('layouts.developers.ly-dashboard')

@section('content')
<div class="min-h-screen w-full bg-[#F6F7FB] px-4 py-5 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6 overflow-hidden rounded-[28px] bg-gradient-to-br from-[#19191B] via-[#24243A] to-[#12A4ED] p-5 text-white shadow-lg sm:p-7">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="mb-2 w-fit rounded-full bg-white/15 px-3 py-1 text-xs font-semibold backdrop-blur">
                    Manajemen Admin
                </p>
                <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">
                    Tambah Admin Baru
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-300">
                    Tambahkan pengguna admin baru dengan mengisi form secara lengkap. Admin memiliki hak akses penuh ke Developer Panel.
                </p>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="mb-6 rounded-2xl bg-green-50 p-4 border border-green-200">
            <div class="flex items-center gap-3">
                <i class="fi fi-rr-check-circle text-xl text-green-500"></i>
                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    
    @if (session('error'))
        <div class="mb-6 rounded-2xl bg-red-50 p-4 border border-red-200">
            <div class="flex items-center gap-3">
                <i class="fi fi-rr-cross-circle text-xl text-red-500"></i>
                <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-2xl bg-red-50 p-4 border border-red-200">
            <div class="flex items-center gap-3 mb-2">
                <i class="fi fi-rr-exclamation text-xl text-red-500"></i>
                <p class="text-sm font-semibold text-red-800">Terdapat kesalahan pengisian form:</p>
            </div>
            <ul class="list-disc pl-9 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Content --}}
    <div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <form action="{{ route('kelola-pengguna.store-admin') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Nama Lengkap --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Nama Lengkap Admin <span class="text-red-500">*</span></label>
                    <input type="text" name="fullname" value="{{ old('fullname') }}" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10"
                        placeholder="Contoh: Budi Santoso">
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Email Admin <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10"
                        placeholder="Contoh: budi.admin@kampsewa.com">
                </div>

                {{-- No Telepon --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">No. Handphone <span class="text-red-500">*</span></label>
                    <input type="text" name="number_phone" value="{{ old('number_phone') }}" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10"
                        placeholder="Contoh: 081234567890">
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10"
                        placeholder="Minimal 6 karakter">
                </div>

                {{-- Tanggal Lahir --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                </div>

                {{-- Jenis Kelamin --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="gender" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            {{-- Foto Profile --}}
            <div class="mt-6 space-y-2">
                <label class="text-sm font-bold text-slate-700">Foto Profil (Opsional)</label>
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                            <i class="fi fi-rr-cloud-upload text-3xl text-slate-400 mb-2"></i>
                            <p class="mb-1 text-sm text-slate-500 font-semibold"><span class="text-violet-600">Klik untuk upload</span> atau drag and drop</p>
                            <p class="text-xs text-slate-400">PNG, JPG, JPEG (Maks. 10MB)</p>
                        </div>
                        <input id="dropzone-file" type="file" name="photo" class="hidden" accept=".jpg,.jpeg,.png" onchange="previewImage(event)"/>
                    </label>
                </div>
                <div id="image-preview" class="hidden mt-4">
                    <img id="preview-img" src="" alt="Preview" class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg mx-auto">
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                <a href="{{ route('home.index') }}" 
                    class="rounded-xl bg-slate-100 px-6 py-3 text-sm font-bold text-slate-600 transition-all hover:bg-slate-200">
                    Batal
                </a>
                <button type="submit" 
                    class="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-violet-500/30 transition-all hover:-translate-y-0.5 hover:shadow-xl hover:shadow-violet-500/40">
                    Simpan Admin
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    const previewContainer = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-img');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.classList.add('hidden');
        previewImage.src = '';
    }
}
</script>
@endsection
