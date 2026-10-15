@extends('layouts.developers.ly-dashboard')
@section('content')
    <div class="_container p-8 flex gap-8 flex-col">
        <div class="_header flex gap-4 items-center">
            <div class="_btn-back w-fit"><a href="{{ route('detail-pengguna.index', ['fullname' => $name]) }}"
                    class="flex items-center gap-2 px-4 py-2 gradient-1 text-white rounded-[10px]">
                    <p class="mt-1"><i class="text-[14px] fi fi-rr-arrow-small-left"></i></p>
                    <p class="text-[12px] font-medium">Kembali</p>
                </a></div>
            <div class="_title">
                <h1 class="text-[20px]">Detail Barang Yang Disewa oleh {{ $name }}</h1>
                <p class="text-[14px]">Menampilkan detail data dari barang yang disewa oleh user
                    <b>{{ $name }}</b>.
                </p>
            </div>
        </div>
        <div class="_container-content relative w-full grid grid-cols-2 gap-10">
            <div class="_component-image-produk relative w-full">
                <div class="_image-detail flex flex-col gap-2 sticky top-6 z-50">
                    <img class="rounded-[10px] w-full h-[450px] object-cover"
                        src="{{ \App\Helpers\PhotoHelper::getThumbnailUrl($produk) }}" alt="{{ $produk->nama }}"
                        onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22400%22%20height%3D%22300%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20300%22%3E%3Crect%20fill%3D%22%23f1f5f9%22%20width%3D%22400%22%20height%3D%22300%22%2F%3E%3Ctext%20fill%3D%22%2394a3b8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%3EKampSewa%20Produk%3C%2Ftext%3E%3C%2Fsvg%3E';">
                    <div class="_image-all grid grid-cols-4 gap-2 w-full">
                        @foreach($produk->foto->take(4) as $p)
                            <img class="rounded-[10px] object-cover w-full h-[120px]"
                                src="{{ \App\Helpers\PhotoHelper::getPhotoUrl($p->url_foto, $p->tipe_sumber) }}" alt=""
                                onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22400%22%20height%3D%22300%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20300%22%3E%3Crect%20fill%3D%22%23f1f5f9%22%20width%3D%22400%22%20height%3D%22300%22%2F%3E%3Ctext%20fill%3D%22%2394a3b8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%3EKampSewa%20Produk%3C%2Ftext%3E%3C%2Fsvg%3E';">
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="_component-detail-data-produk flex flex-col gap-8">
                <div class="_detail">
                    <div class="_title text-[16px] font-bold mb-4">Detail Produk</div>
                    <div class="_field flex flex-col gap-1">
                        <div class="_nama-produk flex gap-2">
                            <p class="text-[14px] text-[#847588] text-nowrap">Nama Produk :</p>
                            <p class="text-[14px] font-medium">{{ $produk->nama }}</p>
                        </div>
                        <div class="_stok-produk flex gap-2 mt-2">
                            <p class="text-[14px] text-[#847588] text-nowrap">Stok Produk :</p>
                            <p class="text-[14px] font-medium">{{ $stok_produk }} Stok</p>
                        </div>
                        <div class="_status-produk flex gap-2 mt-2">
                            <p class="text-[14px] text-[#847588] text-nowrap">Status Produk :</p>
                            <p class="text-[14px] font-medium capitalize">{{ $produk->status }}</p>
                        </div>
                        <div class="_kategori-produk flex gap-2 mt-2">
                            <p class="text-[14px] text-[#847588] text-nowrap">Kategori Produk :</p>
                            <p class="text-[14px] font-medium">{{ $produk->kategori }}</p>
                        </div>
                        <div class="_deskripsi-produk flex flex-col gap-2 mt-2">
                            <p class="text-[14px] text-[#847588] text-nowrap">Deskripsi Produk :</p>
                            <p class="text-[14px] font-medium p-2 shadow-box-shadow-36 rounded-[10px]">
                                {{ $produk->deskripsi }}
                            </p>
                        </div>
                        <div class="_tanggal-diposting flex gap-2">
                            <p class="text-[14px] text-[#847588] text-nowrap">Tanggal Diposting :</p>
                            <p class="text-[14px] font-medium">20 November 2024</p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="_transaksi">
                    <div class="_title text-[16px] font-bold mb-4">Transaksi Produk</div>
                    <div class="_tanggal-transaksi-dilakukan flex gap-2">
                        <p class="text-[14px] text-[#847588] text-nowrap">Transaksi Dilakukan :</p>
                        <p class="text-[14px] font-medium">20 November 2024</p>
                    </div>
                    <div class="_tanggal-mulai flex gap-2">
                        <p class="text-[14px] text-[#847588] text-nowrap">Tanggal Mulai :</p>
                        <p class="text-[14px] font-medium">20 November 2024</p>
                    </div>
                    <div class="_tanggal-selesai flex gap-2">
                        <p class="text-[14px] text-[#847588] text-nowrap">Tanggal Selesai :</p>
                        <p class="text-[14px] font-medium">22 November 2024</p>
                    </div>
                    <div class="_total-harga-penyewaan flex gap-2">
                        <p class="text-[14px] text-[#847588] text-nowrap">Total Harga Penyewaan :</p>
                        <p class="text-[14px] font-medium">Rp. 140.000,00</p>
                    </div>
                    <div class="_status-pembayaran flex gap-2">
                        <p class="text-[14px] text-[#847588] text-nowrap">Status Pembayaran :</p>
                        <p class="text-[14px] font-bold">Lunas</p>
                    </div>
                    <div class="_status-penyewaan flex gap-2">
                        <p class="text-[14px] text-[#847588] text-nowrap">Status Penyewaan :</p>
                        <p class="text-[14px] font-bold">Sedang Disewa</p>
                    </div>
                    <div class="_jumlah-barang-disewa flex gap-2">
                        <p class="text-[14px] text-[#847588] text-nowrap">Jumlah Barang Disewa :</p>
                        <p class="text-[14px] font-medium">2 Barang</p>
                    </div>
                </div>
                <hr>
                <div class="_user-pemilik">
                    <div class="_title text-[16px] font-bold mb-4">Pemilik Barang Yang Disewa</div>
                    <div class="_user flex gap-2 items-center">
                        <div class="_img">
                            <img class="rounded-full w-[40px] h-[40px] object-cover" 
                                src="@userPhoto($user->foto ?? null, 0)" 
                                onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>'; this.onerror=null;"
                                alt="{{ $user->name ?? $name }}">
                        </div>
                        <div class="_nama-visit">
                            <p><a href="{{ isset($user) ? route('detail-pengguna.index', Crypt::encrypt($user->id)) : '#' }}" class="text-[14px] font-medium">{{ $user->name ?? $name }}</a></p>
                            <p><a href="{{ isset($user) ? route('detail-pengguna.index', Crypt::encrypt($user->id)) : '#' }}" class="hover:underline text-[14px] text-[#847588]">View Profile</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
