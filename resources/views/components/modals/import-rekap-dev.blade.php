<div id="modal-import-rekap" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-2xl flex items-center justify-center text-white bg-emerald-600 shadow-sm">
                    <i class="fi fi-rr-inbox-in text-sm"></i>
                </div>
                <h3 class="text-base font-black text-slate-900">Import Data Rekap Keuangan</h3>
            </div>
            <button type="button" onclick="modalHandlerImportRekap(false)" class="text-slate-400 hover:text-slate-600 transition">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('rekap-keuangan.import') }}" enctype="multipart/form-data" class="p-5 flex flex-col gap-4">
            @csrf
            <div>
                <p class="text-xs text-slate-500 mb-2">Unggah file CSV berisi data transaksi pemasukan & pengeluaran. Kolom harus berisi Tipe, Sumber_Kategori, Deskripsi, Nominal, dan Tanggal.</p>
                <a href="{{ route('rekap-keuangan.template-csv') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 hover:underline">
                    <i class="fi fi-rr-download text-[11px]"></i>
                    <span>Download Template CSV Rekap Keuangan</span>
                </a>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Pilih File CSV (.csv / .txt)</label>
                <input type="file" name="file" accept=".csv,.txt" required
                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-200 rounded-xl p-1.5">
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="modalHandlerImportRekap(false)"
                    class="px-4 py-2 text-xs font-bold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 rounded-xl shadow-md hover:bg-emerald-700 transition">
                    Import Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
