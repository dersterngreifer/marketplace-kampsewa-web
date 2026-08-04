@extends('layouts.customers.layouts-customer')
@section('customer-content')
    <div class="--container w-full h-auto px-10 py-8 flex flex-col gap-8 max-w-[1400px] mx-auto">
        <div>
            <h1 class="font-black text-[34px] text-gray-900 leading-tight">Pilih Durasi & Harga Iklan</h1>
            <p class="text-gray-500 mt-2 font-medium">Pilih paket layanan iklan yang paling sesuai dengan kebutuhan promosi Anda.</p>
        </div>
        
        <div class="--wrapper-card grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full">
            <!-- Paling Murah -->
            <a href="{{ route('layanan-iklan.index', ['id_user' => $id_user, 'harga_iklan' => 'paling-murah']) }}"
                class="block group relative transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-[28px] outline-none focus:outline-none">
                <div class="--card w-full h-full bg-[#CCFFF6] p-6 flex items-start justify-between rounded-[28px] border-2 border-transparent group-hover:border-cyan-300 transition-colors">
                    <div class="flex flex-col h-full justify-between items-start gap-3">
                        <p class="text-[13px] font-bold text-teal-800 bg-teal-800/10 px-3 py-1 rounded-full flex items-center gap-1"><i class="bi bi-tag-fill"></i> Paling Murah</p>
                        <h1 class="text-[26px] font-black text-gray-900 leading-none mt-2">Durasi 1 Hari<br>Iklan</h1>
                        <p class="text-[13px] text-gray-700 font-medium leading-relaxed mb-4">Layanan ini adalah layanan yang paling murah dan mungkin tidak direkomendasikan. Tetapi perlu dicoba!</p>
                        <div class="mt-auto flex items-center gap-2 w-full">
                            <p class="text-[15px] font-bold bg-[#101010] text-white px-4 py-2.5 rounded-xl shadow-md group-hover:bg-teal-900 transition-colors">Rp. 50.000<sub>,00</sub></p>
                        </div>
                    </div>
                    <div class="h-full flex justify-center items-center ml-2 relative">
                        <div class="absolute inset-0 bg-white/20 rounded-full blur-xl group-hover:scale-110 transition-transform"></div>
                        <img class="w-[140px] min-w-[140px] object-contain drop-shadow-xl group-hover:scale-105 transition-transform duration-300 relative z-10"
                            src="{{ asset('images/illustration/Businessman-Giving-A-Keynote-2--Streamline-Manila.png') }}"
                            alt="Paling Murah">
                    </div>
                </div>
            </a>

            <!-- Murah -->
            <a href="{{ route('layanan-iklan.index', ['id_user' => $id_user, 'harga_iklan' => 'murah']) }}"
                class="block group relative transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-[28px] outline-none focus:outline-none">
                <div class="--card w-full h-full bg-[#FFF7CC] p-6 flex items-start justify-between rounded-[28px] border-2 border-transparent group-hover:border-yellow-400 transition-colors">
                    <div class="flex flex-col h-full justify-between items-start gap-3">
                        <p class="text-[13px] font-bold text-yellow-800 bg-yellow-800/10 px-3 py-1 rounded-full flex items-center gap-1"><i class="bi bi-tag-fill"></i> Murah</p>
                        <h1 class="text-[26px] font-black text-gray-900 leading-none mt-2">Durasi 2 Hari<br>Iklan</h1>
                        <p class="text-[13px] text-gray-700 font-medium leading-relaxed mb-4">Layanan ini adalah layanan murah dengan durasi iklan lebih lama.</p>
                        <div class="mt-auto flex items-center gap-2 w-full">
                            <p class="text-[15px] font-bold bg-[#101010] text-white px-4 py-2.5 rounded-xl shadow-md group-hover:bg-yellow-900 transition-colors">Rp. 90.000<sub>,00</sub></p>
                        </div>
                    </div>
                    <div class="h-full flex justify-center items-center ml-2 relative">
                        <div class="absolute inset-0 bg-white/20 rounded-full blur-xl group-hover:scale-110 transition-transform"></div>
                        <img class="w-[140px] min-w-[140px] object-contain drop-shadow-xl group-hover:scale-105 transition-transform duration-300 relative z-10"
                            src="{{ asset('images/illustration/Digital-Ads-1--Streamline-Manila.png') }}" alt="Murah">
                    </div>
                </div>
            </a>

            <!-- Sedang -->
            <a href="{{ route('layanan-iklan.index', ['id_user' => $id_user, 'harga_iklan' => 'sedang']) }}"
                class="block group relative transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-[28px] outline-none focus:outline-none">
                <div class="--card w-full h-full bg-[#CCCDFF] p-6 flex items-start justify-between rounded-[28px] border-2 border-transparent group-hover:border-indigo-400 transition-colors">
                    <div class="flex flex-col h-full justify-between items-start gap-3">
                        <p class="text-[13px] font-bold text-indigo-800 bg-indigo-800/10 px-3 py-1 rounded-full flex items-center gap-1"><i class="bi bi-tag-fill"></i> Sedang</p>
                        <h1 class="text-[26px] font-black text-gray-900 leading-none mt-2">Durasi 3 Hari<br>Iklan</h1>
                        <p class="text-[13px] text-gray-700 font-medium leading-relaxed mb-4">Layanan ini menawarkan durasi iklan yang ideal untuk menarik perhatian audiens.</p>
                        <div class="mt-auto flex items-center gap-2 w-full">
                            <p class="text-[15px] font-bold bg-[#101010] text-white px-4 py-2.5 rounded-xl shadow-md group-hover:bg-indigo-900 transition-colors">Rp. 120.000<sub>,00</sub></p>
                        </div>
                    </div>
                    <div class="h-full flex justify-center items-center ml-2 relative">
                        <div class="absolute inset-0 bg-white/20 rounded-full blur-xl group-hover:scale-110 transition-transform"></div>
                        <img class="w-[140px] min-w-[140px] object-contain drop-shadow-xl group-hover:scale-105 transition-transform duration-300 relative z-10"
                            src="{{ asset('images/illustration/A-B-Testing--Streamline-Manila.png') }}" alt="Sedang">
                    </div>
                </div>
            </a>

            <!-- Ideal (White background replaced with Pastel Pink) -->
            <a href="{{ route('layanan-iklan.index', ['id_user' => $id_user, 'harga_iklan' => 'ideal']) }}"
                class="block group relative transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-[28px] outline-none focus:outline-none">
                <div class="--card w-full h-full bg-[#FFE4E8] p-6 flex items-start justify-between rounded-[28px] border-2 border-transparent group-hover:border-rose-400 transition-colors">
                    <div class="flex flex-col h-full justify-between items-start gap-3">
                        <p class="text-[13px] font-bold text-rose-800 bg-rose-800/10 px-3 py-1 rounded-full flex items-center gap-1"><i class="bi bi-tag-fill"></i> Ideal</p>
                        <h1 class="text-[26px] font-black text-gray-900 leading-none mt-2">Durasi 5 Hari<br>Iklan</h1>
                        <p class="text-[13px] text-gray-700 font-medium leading-relaxed mb-4">Layanan ini memberikan keseimbangan antara durasi dan biaya iklan yang optimal.</p>
                        <div class="mt-auto flex items-center gap-2 w-full">
                            <p class="text-[15px] font-bold bg-[#101010] text-white px-4 py-2.5 rounded-xl shadow-md group-hover:bg-rose-900 transition-colors">Rp. 200.000<sub>,00</sub></p>
                        </div>
                    </div>
                    <div class="h-full flex justify-center items-center ml-2 relative">
                        <div class="absolute inset-0 bg-white/20 rounded-full blur-xl group-hover:scale-110 transition-transform"></div>
                        <img class="w-[140px] min-w-[140px] object-contain drop-shadow-xl group-hover:scale-105 transition-transform duration-300 relative z-10"
                            src="{{ asset('images/illustration/Bar-Graph--Streamline-Manila.png') }}" alt="Ideal">
                    </div>
                </div>
            </a>

            <!-- Populer -->
            <a href="{{ route('layanan-iklan.index', ['id_user' => $id_user, 'harga_iklan' => 'populer']) }}"
                class="block group relative transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-[28px] outline-none focus:outline-none">
                <div class="--card w-full h-full bg-[#E0F7FA] p-6 flex items-start justify-between rounded-[28px] border-2 border-transparent group-hover:border-cyan-500 transition-colors">
                    <div class="flex flex-col h-full justify-between items-start gap-3">
                        <p class="text-[13px] font-bold text-cyan-800 bg-cyan-800/10 px-3 py-1 rounded-full flex items-center gap-1"><i class="bi bi-tag-fill"></i> Populer</p>
                        <h1 class="text-[26px] font-black text-gray-900 leading-none mt-2">Durasi 7 Hari<br>Iklan</h1>
                        <p class="text-[13px] text-gray-700 font-medium leading-relaxed mb-4">Layanan ini sangat populer untuk kampanye iklan jangka menengah.</p>
                        <div class="mt-auto flex items-center gap-2 w-full">
                            <p class="text-[15px] font-bold bg-[#101010] text-white px-4 py-2.5 rounded-xl shadow-md group-hover:bg-cyan-900 transition-colors">Rp. 250.000<sub>,00</sub></p>
                        </div>
                    </div>
                    <div class="h-full flex justify-center items-center ml-2 relative">
                        <div class="absolute inset-0 bg-white/20 rounded-full blur-xl group-hover:scale-110 transition-transform"></div>
                        <img class="w-[140px] min-w-[140px] object-contain drop-shadow-xl group-hover:scale-105 transition-transform duration-300 relative z-10"
                            src="{{ asset('images/illustration/Competitor-Analysis--Streamline-Manila.png') }}"
                            alt="Populer">
                    </div>
                </div>
            </a>

            <!-- Premium -->
            <a href="{{ route('layanan-iklan.index', ['id_user' => $id_user, 'harga_iklan' => 'premium']) }}"
                class="block group relative transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-[28px] outline-none focus:outline-none">
                <div class="--card w-full h-full bg-[#FFF3E0] p-6 flex items-start justify-between rounded-[28px] border-2 border-transparent group-hover:border-orange-400 transition-colors">
                    <div class="flex flex-col h-full justify-between items-start gap-3">
                        <p class="text-[13px] font-bold text-orange-800 bg-orange-800/10 px-3 py-1 rounded-full flex items-center gap-1"><i class="bi bi-tag-fill"></i> Premium</p>
                        <h1 class="text-[26px] font-black text-gray-900 leading-none mt-2">Durasi 10 Hari<br>Iklan</h1>
                        <p class="text-[13px] text-gray-700 font-medium leading-relaxed mb-4">Layanan ini menawarkan visibilitas tinggi dengan durasi iklan yang panjang.</p>
                        <div class="mt-auto flex items-center gap-2 w-full">
                            <p class="text-[15px] font-bold bg-[#101010] text-white px-4 py-2.5 rounded-xl shadow-md group-hover:bg-orange-900 transition-colors">Rp. 300.000<sub>,00</sub></p>
                        </div>
                    </div>
                    <div class="h-full flex justify-center items-center ml-2 relative">
                        <div class="absolute inset-0 bg-white/20 rounded-full blur-xl group-hover:scale-110 transition-transform"></div>
                        <img class="w-[140px] min-w-[140px] object-contain drop-shadow-xl group-hover:scale-105 transition-transform duration-300 relative z-10"
                            src="{{ asset('images/illustration/Customize-Product--Streamline-Manila.png') }}"
                            alt="Premium">
                    </div>
                </div>
            </a>

            <!-- Ultimate -->
            <a href="{{ route('layanan-iklan.index', ['id_user' => $id_user, 'harga_iklan' => 'ultimate']) }}"
                class="block group relative transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-[28px] outline-none focus:outline-none md:col-span-2 lg:col-span-3">
                <div class="--card w-full h-full bg-gradient-to-r from-[#CCCDFF] to-[#E2C2FF] p-8 flex flex-col md:flex-row items-center justify-between rounded-[28px] border-2 border-transparent group-hover:border-purple-400 transition-colors">
                    <div class="flex flex-col h-full justify-center items-start gap-3 w-full md:w-2/3">
                        <p class="text-[13px] font-bold text-purple-900 bg-purple-900/10 px-4 py-1.5 rounded-full flex items-center gap-1 uppercase tracking-wider"><i class="bi bi-stars"></i> Ultimate Package</p>
                        <h1 class="text-[32px] font-black text-gray-900 leading-tight mt-2">Durasi 14 Hari Iklan</h1>
                        <p class="text-[15px] text-gray-800 font-medium leading-relaxed mb-4 max-w-lg">Layanan ini sangat cocok untuk kampanye iklan jangka panjang dengan hasil penyewaan yang sangat maksimal dan paling direkomendasikan.</p>
                        <div class="mt-auto flex items-center gap-2">
                            <p class="text-[16px] font-black bg-[#101010] text-white px-6 py-3 rounded-xl shadow-lg group-hover:bg-purple-900 transition-colors">Rp. 400.000<sub>,00</sub></p>
                        </div>
                    </div>
                    <div class="h-full flex justify-center items-center relative mt-6 md:mt-0">
                        <div class="absolute inset-0 bg-white/30 rounded-full blur-2xl group-hover:scale-125 transition-transform"></div>
                        <img class="w-[200px] object-contain drop-shadow-2xl group-hover:scale-110 group-hover:-rotate-2 transition-all duration-500 relative z-10"
                            src="{{ asset('images/illustration/Online-Exams-Tests-1--Streamline-Manila.png') }}"
                            alt="Ultimate">
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
