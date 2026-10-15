@extends('layouts.developers.ly-dashboard')
@section('content')
    <div class="_container w-full h-auto p-8 flex flex-col gap-8">
        {{-- todo wrapper data user sewa sedang berlangsung --}}
        <div class="w-full h-auto">
            {{-- todo isi kontent --}}
            <div class="w-full h-auto flex flex-col gap-8">
                {{-- todo title --}}
                <div class="w-full">
                    @php
                        // Array untuk mengubah nama hari dalam bahasa Indonesia
                        $nama_hari = [
                            'Sunday' => 'Minggu',
                            'Monday' => 'Senin',
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis',
                            'Friday' => 'Jumat',
                            'Saturday' => 'Sabtu',
                        ];

                        // Mendapatkan nama hari dalam bahasa Indonesia
                        $hari = $nama_hari[date('l')];

                        // Mendapatkan tanggal dalam format "d F Y"
                        $tanggal = date('j F Y');
                    @endphp
                    <p class="text-[14px] text-gray-500 font-medium">{{ $hari . ', ' . $tanggal }}</p>
                    <h1 class="text-[20px] font-medium">Penyewaan Sedang Berlangsung</h1>
                </div>
                {{-- todo card data --}}
                <div class="w-full flex flex-col gap-8">
                    {{-- todo untuk component aksi --}}
                    <div class="w-full flex justify-between items-center">
                        {{-- todo wrapper btn delete all, btn export data bentuk ke excel --}}
                        <div class="flex items-center gap-4 w-full">
                            {{-- todo btn export --}}
                            <div><button
                                    class="cursor-pointer gap-2 flex items-center px-4 py-2 bg-gradient-to-r from-[#B381F4] to-[#5038ED] rounded-[5px]">
                                    <p class="mt-1"><i class="text-white fi fi-rr-inbox-out"></i></p>
                                    <p class="text-white text-[14px] font-medium">Export</p>
                                </button></div>
                            {{-- todo btn delete all --}}
                            <div>
                                <button class="px-4 py-2 bg-[#F06D6B] rounded-[5px] flex items-center gap-2">
                                    <p class="mt-1"><i class="text-white fi fi-rr-trash"></i></p>
                                    <p class="text-[14px] font-medium text-white">Hapus</p>
                                </button>
                            </div>
                        </div>
                        {{-- todo wrapper search, filter --}}
                        <div class="w-full">
                            {{-- todo search --}}
                            <div class="w-full">
                                <form class="mx-auto">
                                    <div class="flex relative">
                                        <label for="search-dropdown"
                                            class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Your
                                            Email</label>
                                        <button id="dropdown-button" data-dropdown-toggle="dropdown"
                                            class="relative flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-white bg-[#2B3D63] rounded-s-lg focus:ring-4 focus:outline-none focus:ring-gray-100"
                                            type="button">All categories <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="m1 1 4 4 4-4" />
                                            </svg></button>
                                        <div id="dropdown"
                                            class="z-20 absolute top-full hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                                aria-labelledby="dropdown-button">
                                                <li>
                                                    <button type="button"
                                                        class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mockups</button>
                                                </li>
                                                <li>
                                                    <button type="button"
                                                        class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Templates</button>
                                                </li>
                                                <li>
                                                    <button type="button"
                                                        class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Design</button>
                                                </li>
                                                <li>
                                                    <button type="button"
                                                        class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logos</button>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="relative w-full">
                                            <input type="search" id="search-dropdown"
                                                class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-white rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Search Mockups, Logos, Design Templates..." required />
                                            <button type="submit"
                                                class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 20 20">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                                </svg>
                                                <span class="sr-only">Search</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- todo untuk wrapper list data item --}}
                    <div>
                        <p>Total User : 2053</p>
                        <div class="relative w-full h-[500px] overflow-hidden shadow-box-shadow-11 rounded-[20px] bg-white">
                            <div class="w-full h-full overflow-x-auto">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-white dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-3 py-3 max-w-[50px]">
                                                <div class="inline-flex items-center">
                                                    <label
                                                        class="relative flex items-center p-3 rounded-full cursor-pointer"
                                                        htmlFor="check">
                                                        <input type="checkbox"
                                                            class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-md border-2 border-gray-500 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:bg-gray-900 checked:before:bg-gray-900 hover:before:opacity-10"
                                                            id="check" />
                                                        <span
                                                            class="absolute text-white transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                                viewBox="0 0 20 20" fill="currentColor"
                                                                stroke="currentColor" stroke-width="1">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </span>
                                                    </label>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                User
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Status Sewa
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Durasi
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Harga Sewa
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < 20; $i++)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-3">
                                                    <div class="inline-flex items-center">
                                                        <label
                                                            class="relative flex items-center p-3 rounded-full cursor-pointer"
                                                            htmlFor="check">
                                                            <input type="checkbox"
                                                                class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-md border-2 border-gray-500 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:bg-gray-900 checked:before:bg-gray-900 hover:before:opacity-10"
                                                                id="check" />
                                                            <span
                                                                class="absolute text-white transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                                                    fill="currentColor" stroke="currentColor"
                                                                    stroke-width="1">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 flex items-center gap-2 whitespace-nowrap dark:text-white">
                                                    <img class="rounded-full w-[40px] h-[40px] object-cover"
                                                        src="@userPhoto(null, 1)"
                                                        onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>'; this.onerror=null;"
                                                        alt="Agung Kurniawan">
                                                    <p>Agung Kurniawan</p>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <p class="py-2 px-4 w-fit bg-[#F0FDF4] text-[#4ED17E] rounded-full">
                                                        Berlangsung</p>
                                                </td>
                                                <td class="px-6 py-4">
                                                    1 Minggu
                                                </td>
                                                <td class="px-6 py-4">
                                                    Rp. 1.000.000
                                                </td>
                                                <td class="px-6 py-4 flex gap-2 items-center">
                                                    <p><a href=""><i class="text-[16px] bi bi-pen-fill"></i></a>
                                                    </p>
                                                    <p><a href=""><i class="text-[16px] bi bi-trash-fill"></i></a>
                                                    </p>
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- todo wrapper penyewaaan yang terlambat, kehilangan, dan denda --}}
        <div class="w-full h-auto">
            {{-- todo wrapper data user sewa sedang berlangsung --}}
            <div class="w-full h-auto">
                {{-- todo isi kontent --}}
                <div class="w-full h-auto flex flex-col gap-8">
                    {{-- todo title --}}
                    <div class="w-full">
                        @php
                            // Array untuk mengubah nama hari dalam bahasa Indonesia
                            $nama_hari = [
                                'Sunday' => 'Minggu',
                                'Monday' => 'Senin',
                                'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu',
                            ];

                            // Mendapatkan nama hari dalam bahasa Indonesia
                            $hari = $nama_hari[date('l')];

                            // Mendapatkan tanggal dalam format "d F Y"
                            $tanggal = date('j F Y');
                        @endphp
                        <h1 class="text-[20px] font-medium">Pelanggaran Users</h1>
                    </div>
                    {{-- todo card data --}}
                    <div class="w-full flex flex-col gap-8">
                        {{-- todo untuk component aksi --}}
                        <div class="w-full flex justify-between items-center">
                            {{-- todo wrapper btn delete all, btn export data bentuk ke excel --}}
                            <div class="flex items-center gap-4 w-full">
                                {{-- todo btn export --}}
                                <div><button
                                        class="cursor-pointer gap-2 flex items-center px-4 py-2 bg-gradient-to-r from-[#B381F4] to-[#5038ED] rounded-[5px]">
                                        <p class="mt-1"><i class="text-white fi fi-rr-inbox-out"></i></p>
                                        <p class="text-white text-[14px] font-medium">Export</p>
                                    </button></div>
                                {{-- todo btn delete all --}}
                                <div>
                                    <button class="px-4 py-2 bg-[#F06D6B] rounded-[5px] flex items-center gap-2">
                                        <p class="mt-1"><i class="text-white fi fi-rr-trash"></i></p>
                                        <p class="text-[14px] font-medium text-white">Hapus</p>
                                    </button>
                                </div>
                            </div>
                            {{-- todo wrapper search, filter --}}
                            <div class="w-full">
                                {{-- todo search --}}
                                <div class="w-full">
                                    <form class="mx-auto">
                                        <div class="flex relative">
                                            <label for="search-dropdown"
                                                class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Your
                                                Email</label>
                                            <button id="dropdown-button" data-dropdown-toggle="dropdown"
                                                class="relative flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-white bg-[#2B3D63] rounded-s-lg focus:ring-4 focus:outline-none focus:ring-gray-100"
                                                type="button">All categories <svg class="w-2.5 h-2.5 ms-2.5"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                                                </svg></button>
                                            <div id="dropdown"
                                                class="z-20 absolute top-full hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                                    aria-labelledby="dropdown-button">
                                                    <li>
                                                        <button type="button"
                                                            class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mockups</button>
                                                    </li>
                                                    <li>
                                                        <button type="button"
                                                            class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Templates</button>
                                                    </li>
                                                    <li>
                                                        <button type="button"
                                                            class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Design</button>
                                                    </li>
                                                    <li>
                                                        <button type="button"
                                                            class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logos</button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="relative w-full">
                                                <input type="search" id="search-dropdown"
                                                    class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-white rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="Search Mockups, Logos, Design Templates..." required />
                                                <button type="submit"
                                                    class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                    <svg class="w-4 h-4" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 20 20">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                                    </svg>
                                                    <span class="sr-only">Search</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- todo untuk wrapper list data item --}}
                        <div>
                            <p>Total User : 2053</p>
                            <div
                                class="relative w-full h-[500px] overflow-hidden shadow-box-shadow-11 rounded-[20px] bg-white">
                                <div class="w-full h-full overflow-x-auto">
                                    <table
                                        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-white dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-3 py-3 max-w-[50px]">
                                                    <div class="inline-flex items-center">
                                                        <label
                                                            class="relative flex items-center p-3 rounded-full cursor-pointer"
                                                            htmlFor="check">
                                                            <input type="checkbox"
                                                                class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-md border-2 border-gray-500 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:bg-gray-900 checked:before:bg-gray-900 hover:before:opacity-10"
                                                                id="check" />
                                                            <span
                                                                class="absolute text-white transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                                                    fill="currentColor" stroke="currentColor"
                                                                    stroke-width="1">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    User
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Status Sewa
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Jenis Pelanggaran
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Tarif Denda
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 20; $i++)
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                    <td class="px-3">
                                                        <div class="inline-flex items-center">
                                                            <label
                                                                class="relative flex items-center p-3 rounded-full cursor-pointer"
                                                                htmlFor="check">
                                                                <input type="checkbox"
                                                                    class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-md border-2 border-gray-500 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:bg-gray-900 checked:before:bg-gray-900 hover:before:opacity-10"
                                                                    id="check" />
                                                                <span
                                                                    class="absolute text-white transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                                                        fill="currentColor" stroke="currentColor"
                                                                        stroke-width="1">
                                                                        <path fill-rule="evenodd"
                                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                            clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td scope="row"
                                                        class="px-6 py-4 font-medium text-gray-900 flex items-center gap-2 whitespace-nowrap dark:text-white">
                                                        <img class="rounded-full w-[40px] h-[40px] object-cover"
                                                            src="@userPhoto(null, 1)"
                                                            onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>'; this.onerror=null;"
                                                            alt="Agung Kurniawan">
                                                        <p>Agung Kurniawan</p>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <p
                                                            class="py-2 px-4 w-fit bg-[#F0FDF4] text-[#4ED17E] rounded-full">
                                                            Berlangsung</p>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        1 Minggu
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        Rp. 1.000.000
                                                    </td>
                                                    <td class="px-6 py-4 flex gap-2 items-center">
                                                        <p><a href=""><i
                                                                    class="text-[16px] bi bi-pen-fill"></i></a>
                                                        </p>
                                                        <p><a href=""><i
                                                                    class="text-[16px] bi bi-trash-fill"></i></a>
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- todo riwayat penyewaan user --}}
        <div class="w-full h-auto">
            <div class="w-full h-auto">
                {{-- todo isi kontent --}}
                <div class="w-full h-auto flex flex-col gap-8">
                    {{-- todo title --}}
                    <div class="w-full">
                        @php
                            // Array untuk mengubah nama hari dalam bahasa Indonesia
                            $nama_hari = [
                                'Sunday' => 'Minggu',
                                'Monday' => 'Senin',
                                'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu',
                            ];

                            // Mendapatkan nama hari dalam bahasa Indonesia
                            $hari = $nama_hari[date('l')];

                            // Mendapatkan tanggal dalam format "d F Y"
                            $tanggal = date('j F Y');
                        @endphp
                        <h1 class="text-[20px] font-medium">Riwayat Penyewaan</h1>
                    </div>
                    {{-- todo card data --}}
                    <div class="w-full flex flex-col gap-8">
                        {{-- todo untuk component aksi --}}
                        <div class="w-full flex justify-between items-center">
                            {{-- todo wrapper btn delete all, btn export data bentuk ke excel --}}
                            <div class="flex items-center gap-4 w-full">
                                {{-- todo btn export --}}
                                <div><button
                                        class="cursor-pointer gap-2 flex items-center px-4 py-2 bg-gradient-to-r from-[#B381F4] to-[#5038ED] rounded-[5px]">
                                        <p class="mt-1"><i class="text-white fi fi-rr-inbox-out"></i></p>
                                        <p class="text-white text-[14px] font-medium">Export</p>
                                    </button></div>
                                {{-- todo btn delete all --}}
                                <div>
                                    <button class="px-4 py-2 bg-[#F06D6B] rounded-[5px] flex items-center gap-2">
                                        <p class="mt-1"><i class="text-white fi fi-rr-trash"></i></p>
                                        <p class="text-[14px] font-medium text-white">Hapus</p>
                                    </button>
                                </div>
                            </div>
                            {{-- todo wrapper search, filter --}}
                            <div class="w-full">
                                {{-- todo search --}}
                                <div class="w-full">
                                    <form class="mx-auto">
                                        <div class="flex relative">
                                            <label for="search-dropdown"
                                                class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Your
                                                Email</label>
                                            <button id="dropdown-button" data-dropdown-toggle="dropdown"
                                                class="relative flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-white bg-[#2B3D63] rounded-s-lg focus:ring-4 focus:outline-none focus:ring-gray-100"
                                                type="button">All categories <svg class="w-2.5 h-2.5 ms-2.5"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                                                </svg></button>
                                            <div id="dropdown"
                                                class="z-20 absolute top-full hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                                    aria-labelledby="dropdown-button">
                                                    <li>
                                                        <button type="button"
                                                            class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mockups</button>
                                                    </li>
                                                    <li>
                                                        <button type="button"
                                                            class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Templates</button>
                                                    </li>
                                                    <li>
                                                        <button type="button"
                                                            class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Design</button>
                                                    </li>
                                                    <li>
                                                        <button type="button"
                                                            class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logos</button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="relative w-full">
                                                <input type="search" id="search-dropdown"
                                                    class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-white rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="Search Mockups, Logos, Design Templates..." required />
                                                <button type="submit"
                                                    class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                    <svg class="w-4 h-4" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 20 20">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                                    </svg>
                                                    <span class="sr-only">Search</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- todo untuk wrapper list data item --}}
                        <div>
                            <p>Total User : 2053</p>
                            <div
                                class="relative w-full h-[500px] overflow-hidden shadow-box-shadow-11 rounded-[20px] bg-white">
                                <div class="w-full h-full overflow-x-auto">
                                    <table
                                        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-white dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-3 py-3 max-w-[50px]">
                                                    <div class="inline-flex items-center">
                                                        <label
                                                            class="relative flex items-center p-3 rounded-full cursor-pointer"
                                                            htmlFor="check">
                                                            <input type="checkbox"
                                                                class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-md border-2 border-gray-500 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:bg-gray-900 checked:before:bg-gray-900 hover:before:opacity-10"
                                                                id="check" />
                                                            <span
                                                                class="absolute text-white transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                                                    fill="currentColor" stroke="currentColor"
                                                                    stroke-width="1">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    User
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Status Sewa
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Durasi
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Tanggal Selesai
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 20; $i++)
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                    <td class="px-3">
                                                        <div class="inline-flex items-center">
                                                            <label
                                                                class="relative flex items-center p-3 rounded-full cursor-pointer"
                                                                htmlFor="check">
                                                                <input type="checkbox"
                                                                    class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-md border-2 border-gray-500 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:bg-gray-900 checked:before:bg-gray-900 hover:before:opacity-10"
                                                                    id="check" />
                                                                <span
                                                                    class="absolute text-white transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                                                        fill="currentColor" stroke="currentColor"
                                                                        stroke-width="1">
                                                                        <path fill-rule="evenodd"
                                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                            clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td scope="row"
                                                        class="px-6 py-4 font-medium text-gray-900 flex items-center gap-2 whitespace-nowrap dark:text-white">
                                                        <img class="rounded-full w-[40px] h-[40px] object-cover"
                                                            src="@userPhoto(null, 1)"
                                                            onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'%235038ed\'/><circle cx=\'50\' cy=\'40\' r=\'20\' fill=\'white\'/><path d=\'M 20 85 C 20 65, 80 65, 80 85 Z\' fill=\'white\'/></svg>'; this.onerror=null;"
                                                            alt="Agung Kurniawan">
                                                        <p>Agung Kurniawan</p>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <p
                                                            class="py-2 px-4 w-fit bg-[#F0FDF4] text-[#4ED17E] rounded-full">
                                                            Berlangsung</p>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        1 Minggu
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        Rp. 1.000.000
                                                    </td>
                                                    <td class="px-6 py-4 flex gap-2 items-center">
                                                        <p><a href=""><i
                                                                    class="text-[16px] bi bi-pen-fill"></i></a>
                                                        </p>
                                                        <p><a href=""><i
                                                                    class="text-[16px] bi bi-trash-fill"></i></a>
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // todo fungsi dropdown filter search data sewa sedang berlangsung
        document.getElementById('dropdown-button').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('dropdown').classList.toggle('hidden');
        });
    </script>
@endsection
