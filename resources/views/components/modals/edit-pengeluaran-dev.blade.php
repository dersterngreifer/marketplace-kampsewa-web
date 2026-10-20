<div id="modal-edit-pengeluaran" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-2xl flex items-center justify-center text-white bg-rose-500 shadow-sm">
                    <i class="bi bi-pencil-square text-sm"></i>
                </div>
                <h3 class="text-base font-black text-slate-900">Edit Pengeluaran Perusahaan</h3>
            </div>
            <button type="button" onclick="modalHandlerEditPengeluaran(false)" class="text-slate-400 hover:text-slate-600 transition">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <form method="POST" id="form-edit-pengeluaran" action="" class="p-5 flex flex-col gap-4">
            @csrf
            @method('PUT')
            <div class="flex flex-col gap-1.5">
                <label for="edit_kategori_pengeluaran" class="text-xs font-bold text-slate-700">Kategori Pengeluaran</label>
                <select class="w-full border border-slate-200 rounded-xl text-xs font-medium p-2.5 focus:ring-2 focus:ring-rose-400 focus:outline-none"
                    id="edit_kategori_pengeluaran" name="kategori_pengeluaran" required>
                    <option value="Server & Cloud">Server & Cloud (AWS, Cloudflare, Database)</option>
                    <option value="Operasional">Operasional (Listrik, Internet, Perlengkapan)</option>
                    <option value="Maintenance">Maintenance (Tool, Audit Keamanan, Pemeliharaan)</option>
                    <option value="Gaji & SDM">Gaji & SDM (Honorarium, Developer, Support)</option>
                    <option value="Pemasaran & Promosi">Pemasaran & Promosi (Iklan Ads, Event, Banner)</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="edit_sumber_pengeluaran" class="text-xs font-bold text-slate-700">Sumber / Vendor / Penerima</label>
                <input class="w-full border border-slate-200 rounded-xl text-xs font-medium p-2.5 focus:ring-2 focus:ring-rose-400 focus:outline-none"
                    type="text" id="edit_sumber_pengeluaran" name="sumber" required>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="edit_deskripsi_pengeluaran" class="text-xs font-bold text-slate-700">Deskripsi Pengeluaran</label>
                <input class="w-full border border-slate-200 rounded-xl text-xs font-medium p-2.5 focus:ring-2 focus:ring-rose-400 focus:outline-none"
                    type="text" id="edit_deskripsi_pengeluaran" name="deskripsi" required>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="edit_nominal_pengeluaran" class="text-xs font-bold text-slate-700">Nominal (Rp)</label>
                <input class="w-full border border-slate-200 rounded-xl text-xs font-medium p-2.5 focus:ring-2 focus:ring-rose-400 focus:outline-none"
                    type="number" id="edit_nominal_pengeluaran" name="nominal" min="1000" required>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="modalHandlerEditPengeluaran(false)"
                    class="px-4 py-2 text-xs font-bold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 text-xs font-bold text-white bg-rose-500 rounded-xl shadow-md hover:bg-rose-600 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
