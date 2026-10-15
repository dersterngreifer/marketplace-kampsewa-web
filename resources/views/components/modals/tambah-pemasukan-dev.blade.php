<div id="modal-tambah-pemasukan-customer" class="hidden">
    <div
        class="_container w-full h-screen flex justify-center items-center z-20 fixed top-0 left-0 bg-black/40 backdrop-blur-sm">
        <div
            class="_card w-[500px] flex flex-col h-[500px] overflow-clip bg-white p-[20px] rounded-[20px] shadow-box-shadow-11">
            <div class="_header w-full flex items-center justify-between pb-2 border-b">
                <div class="_title flex items-center gap-2">
                    <div class="w-[35px] h-[35px] rounded-full flex justify-center items-center text-white gradient-1">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <span class="text-[16px] font-bold">Tambah Pemasukan Perusahaan</span>
                </div>
                <button type="button" id="close-modal-pemasukan" class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg text-[18px]"></i>
                </button>
            </div>
            <div class="_body w-full mt-4 flex-grow p-2 overflow-y-auto">
                <form method="POST" id="form-tambah-pemasukan" action="{{ route('penghasilan.tambah-penghasilan-dev', ['id_user' => Crypt::encrypt(session('id_user') ?? 1)]) }}"
                    class="w-full flex flex-col gap-3">
                    @csrf
                    <div class="_input w-full flex flex-col gap-1">
                        <label for="sumber_pemasukan_customer" class="text-[13px] font-semibold text-gray-700">Sumber Pemasukan</label>
                        <select class="border w-full border-gray-300 rounded-[10px] text-[14px] p-2.5 focus:ring-2 focus:ring-[#7CA9CF] focus:outline-none"
                            id="sumber_pemasukan_customer" name="sumber" required>
                            <option value="Service">Service (Biaya Penanganan / Fee)</option>
                            <option value="Layanan Iklan">Layanan Iklan (Sponsorship / Banner)</option>
                            <option value="Pajak Platform">Pajak Platform (Bagi Hasil Penyewaan)</option>
                            <option value="Kemitraan Mitra">Kemitraan Mitra (Badge Resmi / Verified)</option>
                        </select>
                    </div>
                    <div class="_input w-full flex flex-col gap-1">
                        <label for="deskripsi_pemasukan_customer" class="text-[13px] font-semibold text-gray-700">Deskripsi Transaksi</label>
                        <input class="border w-full border-gray-300 rounded-[10px] text-[14px] p-2.5 focus:ring-2 focus:ring-[#7CA9CF] focus:outline-none"
                            placeholder="Contoh: Biaya admin transaksi sewa alat camping" type="text" id="deskripsi_pemasukan_customer" name="deskripsi"
                            required>
                    </div>
                    <div class="_input w-full flex flex-col gap-1">
                        <label for="nominal_pemasukan_customer" class="text-[13px] font-semibold text-gray-700">Nominal (Rp)</label>
                        <input class="border w-full border-gray-300 rounded-[10px] text-[14px] p-2.5 focus:ring-2 focus:ring-[#7CA9CF] focus:outline-none"
                            placeholder="Masukkan nominal pemasukan (contoh: 2500000)" type="number" id="nominal_pemasukan_customer" name="nominal"
                            min="1000" required>
                    </div>
                </form>
            </div>
            <div class="_footer w-full p-2 flex justify-end gap-2 border-t pt-3">
                <button type="button" class="text-[14px] border border-gray-300 py-2 px-5 rounded-full hover:bg-gray-50"
                 id="cancel-tambah-pemasukan-web-customer">Batal</button>
                <button type="button" id="tambah-pemasukan"
                    class="gradient-1 text-white text-[14px] font-medium py-2 px-6 rounded-full hover:opacity-90 transition">Simpan Pemasukan</button>
            </div>
        </div>
    </div>
</div>
