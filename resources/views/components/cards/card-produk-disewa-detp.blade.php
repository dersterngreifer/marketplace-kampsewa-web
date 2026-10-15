<div class="_card-design flex flex-col gap-2 cursor-pointer">
    <a
        href="{{ route('detail-pengguna.detail-produk-sedang-disewa', ['fullname' => $name, 'namaproduk' => 'Icikiwir Produk Kebanggaan']) }}">
        <div class="_header w-full">
            <div class="_img relative group">
                <div
                    class="_hover-detail flex items-center justify-between w-full h-fit bottom-0 py-4 px-4 bg-gradient-to-t from-[#000000] to-[rgba(0, 0, 0, 0)] rounded-br-[10px] rounded-bl-[10px] absolute opacity-0 group-hover:opacity-100 transition duration-300">
                    <p class="text-[14px] font-medium text-white">Lihat Detail</p>
                    <p
                        class="text-[18px] text-black w-[30px] h-[30px] rounded-full flex justify-center items-center bg-white">
                        <i class="mt-1 fi fi-rr-angle-small-right"></i>
                    </p>
                </div>
                <img class="w-full h-[180px] rounded-[10px] object-cover"
                    src="{{ asset('assets/image/customers/produk/gerber.png') }}" 
                    onerror="this.src='{{ \App\Helpers\PhotoHelper::getThumbnailUrl(null) }}'; this.onerror=null;"
                    alt="">
            </div>
        </div>
    </a>
    <div class="_body">
        <div class="_pemilik-barang-nama-produk flex flex-col gap-2">
            <div class="_nama-produk">
                <p class="text-[14px] font-medium">Gerber Multi-Purpose Nippers With Screw</p>
            </div>
            <div class="_pemilik-barang-waktu-sewa flex justify-between items-center">
                <div class="_pemilik-barang flex items-center gap-2">
                    <div class="_img"><img class="w-[30px] h-[30px] object-cover rounded-full"
                            src="{{ asset('assets/image/customers/profile/pexels-pixabay-164763.jpg') }}"
                            onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>'; this.onerror=null;"
                            alt="">
                    </div>
                    <p class="text-[14px] line-clamp-1 max-w-[75%] font-medium">Mas Rudi Camping Jaya
                    </p>
                </div>
                <div class="_waktu-sewa flex gap-1 items-center">
                    <p class="text-[12px] mt-1"><i class="fi fi-rr-clock-three"></i></p>
                    <p class="text-[12px] font-medium whitespace-nowrap">3 Hari</p>
                </div>
            </div>
        </div>
    </div>
    <div class="_footer mt-2">
        <div class="_waktu-penyewaan items-center flex justify-between">
            <div class="_awal-sewa">
                <p class="text-[12px] p-2 bg-[#F0FDF4] text-[#39CB6F] rounded-full">17 Nov 2024</p>
            </div>
            <p class="mt-1"><i class="fi fi-rr-arrow-right"></i></p>
            <div class="_akhir-sewa">
                <p class="text-[12px] p-2 bg-[#FEF2F2] text-[#F26565] rounded-full">20 Nov 2024</p>
            </div>
        </div>
    </div>
</div>
