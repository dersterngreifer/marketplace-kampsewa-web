<div id="modal-edit-pemasukan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-2xl flex items-center justify-center text-white gradient-1 shadow-sm">
                    <i class="bi bi-pencil-square text-sm"></i>
                </div>
                <h3 class="text-base font-black text-slate-900">Edit Pemasukan Perusahaan</h3>
            </div>
            <button type="button" onclick="modalHandlerEditPenghasilan(false)" class="text-slate-400 hover:text-slate-600 transition">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <form method="POST" id="form-edit-pemasukan" action="" class="p-5 flex flex-col gap-4">
            @csrf
            @method('PUT')
            <div class="flex flex-col gap-1.5">
                <label for="edit_sumber_pemasukan" class="text-xs font-bold text-slate-700">Sumber Pemasukan</label>
                <select class="w-full border border-slate-200 rounded-xl text-xs font-medium p-2.5 focus:ring-2 focus:ring-violet-400 focus:outline-none"
                    id="edit_sumber_pemasukan" name="sumber" required>
                    <option value="Service">Service (Biaya Penanganan / Fee)</option>
                    <option value="Layanan Iklan">Layanan Iklan (Sponsorship / Banner)</option>
                    <option value="Pajak Platform">Pajak Platform (Bagi Hasil Penyewaan)</option>
                    <option value="Kemitraan Mitra">Kemitraan Mitra (Badge Resmi / Verified)</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="edit_deskripsi_pemasukan" class="text-xs font-bold text-slate-700">Deskripsi Transaksi</label>
                <input class="w-full border border-slate-200 rounded-xl text-xs font-medium p-2.5 focus:ring-2 focus:ring-violet-400 focus:outline-none"
                    type="text" id="edit_deskripsi_pemasukan" name="deskripsi" required>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="edit_nominal_pemasukan" class="text-xs font-bold text-slate-700">Nominal (Rp)</label>
                <input class="w-full border border-slate-200 rounded-xl text-xs font-medium p-2.5 focus:ring-2 focus:ring-violet-400 focus:outline-none"
                    type="number" id="edit_nominal_pemasukan" name="nominal" min="1000" required>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="modalHandlerEditPenghasilan(false)"
                    class="px-4 py-2 text-xs font-bold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 text-xs font-bold text-white gradient-1 rounded-xl shadow-md hover:opacity-95 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
