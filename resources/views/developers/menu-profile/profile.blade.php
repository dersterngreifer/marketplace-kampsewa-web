@extends('layouts.developers.ly-dashboard')

@include('components.modals.edit-profile')

@section('content')
    <div class="--container">
        <div class="--component-awal w-full h-auto">
            <img class="w-full object-cover h-[300px]" src="{{ asset('images/pexels-toulouse-3195757.jpg') }}" alt="">
        </div>
        <div class="---component-kedua py-4 px-8 w-full h-auto grid grid-cols-[0.5fr_1fr_1fr] gap-2 mt-[-50px]">
            <div class="--card-detail-profile-user flex flex-col gap-2 w-full h-full">
                <div class="--foto-name-id flex flex-col gap-4 w-full bg-white shadow-box-shadow-11 rounded-[20px] p-4">
                    <div class="--image w-full flex justify-center">
                        @include('components.ui.avatar', [
                            'nama' => session('nama_lengkap'),
                            'foto' => session('foto'),
                            'type' => 1,
                            'class' => 'w-[80px] h-[80px]',
                            'rounded' => 'rounded-full',
                            'text' => 'text-2xl',
                            'extra' => 'border-2 border-solid border-white outline outline-[#5038ED]',
                        ])
                    </div>
                    <div class="--name-id w-full text-center">
                        <p class="text-[14px] font-bold">{{ session('nama_lengkap') }}</p>
                        <p class="text-[12px] font-medium text-gray-400 whitespace-nowrap">ID : {{ session('id_user') }}</p>
                    </div>
                    <div class="--button w-full flex justify-center"><button type="button"
                            onclick="modalEditProfileHandler(true)"
                            class="px-4 py-2 gradient-1 text-[12px] font-bold cursor-pointer text-white rounded-full">Edit
                            Profile</button></div>
                </div>
                <div class="--number-phone bg-white rounded-[20px] w-full flex gap-2 items-center p-4 shadow-box-shadow-11">
                    <div class="w-[40px] h-[40px] rounded-full flex items-center justify-center bg-[#F8F7F4]"><i
                            class="bi bi-telephone-fill"></i></div>
                    <div>
                        <p class="text-[12px] font-medium text-gray-400">Nomor Telepon:</p>
                        <p class="text-[14px] font-bold">{{ session('nomor_telephone') ?? 'Belum di isi' }}</p>
                    </div>
                </div>
                <div
                    class="--tanggal-lahir bg-white rounded-[20px] w-full flex gap-2 items-center p-4 shadow-box-shadow-11">
                    <div class="w-[40px] h-[40px] rounded-full flex items-center justify-center bg-[#F8F7F4]"><i
                            class="bi bi-balloon-fill"></i></div>
                    <div>
                        <p class="text-[12px] font-medium text-gray-400">Lahir:</p>
                        <p class="text-[14px] font-bold whitespace-nowrap">
                            {{ session('tanggal_lahir') ? \Carbon\Carbon::parse(session('tanggal_lahir'))->translatedFormat('d F Y') : 'Belum di isi' }}
                        </p>
                    </div>
                </div>
                <div class="--gender bg-white rounded-[20px] w-full flex gap-2 items-center p-4 shadow-box-shadow-11">
                    <div class="w-[40px] h-[40px] rounded-full flex items-center justify-center bg-[#F8F7F4]"><i
                            class="bi bi-gender-female"></i></div>
                    <div>
                        <p class="text-[12px] font-medium text-gray-400">Jenis Kelamin:</p>
                        <p class="text-[14px] font-bold">{{ session('jenis_kelamin') ?? 'Belum di isi' }}</p>
                    </div>
                </div>
                <div class="--level bg-white rounded-[20px] w-full flex gap-2 items-center p-4 shadow-box-shadow-11">
                    <div class="w-[40px] h-[40px] rounded-full flex items-center justify-center bg-[#F8F7F4]"><i
                            class="bi bi-person-check"></i></div>
                    <div>
                        <p class="text-[12px] font-medium text-gray-400">Level:</p>
                        <p class="text-[14px] font-bold">Developer</p>
                    </div>
                </div>
                <div class="--email bg-white rounded-[20px] w-full flex gap-2 items-center p-4 shadow-box-shadow-11">
                    <div class="w-[40px] h-[40px] rounded-full flex items-center justify-center bg-[#F8F7F4]"><i
                            class="bi bi-envelope-at-fill"></i></div>
                    <div>
                        <p class="text-[12px] font-medium text-gray-400">Email:</p>
                        <p class="text-[14px] font-bold">{{ session('email') ?? 'Belum di isi' }}</p>
                    </div>
                </div>
                <div class="--status bg-white rounded-[20px] w-full flex gap-2 items-center p-4 shadow-box-shadow-11">
                    <div class="w-[40px] h-[40px] rounded-full flex items-center justify-center bg-[#F8F7F4]"><i
                            class="bi bi-check2"></i></div>
                    <div>
                        <p class="text-[12px] font-medium text-gray-400">Status:</p>
                        <p class="text-[14px] font-bold">{{ session('status') ?? 'Aktif' }}</p>
                    </div>
                </div>
            </div>
            <div class="--card-detail-data-input-pemasukan w-full max-h-[800px]">
                <div class="w-full h-full overflow-y-auto sub-wrapper flex flex-col gap-4 p-4 bg-white rounded-[20px] shadow-box-shadow-11">
                    <div class="--title">
                        <div class="w-[20%] h-[8px] rounded-full bg-blue-400"></div>
                        <p class="text-[16px] font-bold">Input pemasukan anda.</p>
                        <p class="text-[14px] font-normal text-[#2b2b2b]">List dari data pemasukan yang anda inputkan pada
                            pemasukan ini.</p>
                    </div>
                    <div class="-total-data-search">
                        <div class="--total-data text-[14px] font-medium">Total input : <b>143</b> Data.</div>
                    </div>
                    <div class="--wrapper-card-data-list w-full p-1 overflow-y-auto mt-2 flex flex-col gap-2">
                        @for ($i = 0; $i < 6; $i++)
                            <a href="">
                                <div
                                    class="--data-items-list w-full p-4 bg-[#F4F5F7] hover:bg-[#F8F7F4] rounded-[8px] flex items-center gap-2">
                                    <div class="--title-content">
                                        <p class="text-[16px] font-bold">Allysa Safitri <sup class="font-normal">dev</sup>
                                        </p>
                                        <p class="text-[12px] line-clamp-2">Pengeluaran ini digunakan untuk pembayaran media
                                            hosting
                                            database dan media hosting website juga penambahan biaya untuk dinas kantor
                                            provider.</p>
                                        <div class="--data-more w-full flex mt-2 items-center gap-2">
                                            <p class="text-[12px] font-medium py-1 px-2 bg-white rounded-full">Service</p>
                                            <p class="text-[12px] font-medium">20 November 2024</p>
                                            <div class="w-[2px] h-[15px] bg-[#2b2b2b2b]"></div>
                                            <p class="text-[12px] font-bold">Rp. 25.550.000,00</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endfor
                    </div>
                </div>
            </div>
            <div class="--card-detail-data-input-pengeluaran w-full h-full max-h-[800px]">
                <div class="w-full h-full overflow-y-auto sub-wrapper flex flex-col gap-4 p-4 bg-white rounded-[20px] shadow-box-shadow-11">
                    <div class="--title">
                        <div class="w-[20%] h-[8px] rounded-full bg-red-400"></div>
                        <p class="text-[16px] font-bold">Input pengeluaran anda.</p>
                        <p class="text-[14px] font-normal text-[#2b2b2b]">List dari data pengeluaran yang anda inputkan pada
                            pengeluaran ini.</p>
                    </div>
                    <div class="-total-data-search">
                        <div class="--total-data text-[14px] font-medium">Total input : <b>143</b> Data.</div>
                    </div>
                    <div class="--wrapper-card-data-list w-full p-1 overflow-y-auto mt-2 flex flex-col gap-2">
                        @for ($i = 0; $i < 6; $i++)
                            <a href="">
                                <div
                                    class="--data-items-list w-full p-4 bg-[#F4F5F7] hover:bg-[#F8F7F4] rounded-[8px] flex items-center gap-2">
                                    <div class="--title-content">
                                        <p class="text-[16px] font-bold">Allysa Safitri <sup class="font-normal">dev</sup>
                                        </p>
                                        <p class="text-[12px] line-clamp-2">Pengeluaran ini digunakan untuk pembayaran media
                                            hosting
                                            database dan media hosting website juga penambahan biaya untuk dinas kantor
                                            provider.</p>
                                        <div class="--data-more w-full flex mt-2 items-center gap-2">
                                            <p class="text-[12px] font-medium py-1 px-2 bg-white rounded-full">Service</p>
                                            <p class="text-[12px] font-medium">20 November 2024</p>
                                            <div class="w-[2px] h-[15px] bg-[#2b2b2b2b]"></div>
                                            <p class="text-[12px] font-bold">Rp. 25.550.000,00</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
