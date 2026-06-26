<div>
    {{-- Navbar --}}
    <nav x-data="{ scrolled: false, mobileMenuOpen: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="scrolled ? 'bg-white shadow-md py-3' : 'bg-transparent py-5'"
         class="fixed top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    @php $appLogo = \App\Models\AppSetting::getVal('app_logo_path'); @endphp
                    @if($appLogo)
                        <img src="{{ asset('storage/' . $appLogo) }}" alt="Logo" class="h-10 w-auto">
                    @else
                        <div class="h-10 w-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                    @endif
                    <span :class="scrolled ? 'text-slate-900' : 'text-white'" class="font-bold text-xl tracking-tight transition-colors">SI-RESERVASI PNBP</span>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" :class="scrolled ? 'text-slate-600 hover:text-green-600' : 'text-white/90 hover:text-white'" class="text-sm font-medium transition-colors">Beranda</a>
                    <a href="#fasilitas" :class="scrolled ? 'text-slate-600 hover:text-green-600' : 'text-white/90 hover:text-white'" class="text-sm font-medium transition-colors">Fasilitas</a>
                    <a href="#jadwal" :class="scrolled ? 'text-slate-600 hover:text-green-600' : 'text-white/90 hover:text-white'" class="text-sm font-medium transition-colors">Jadwal</a>
                    <a href="#cara-reservasi" :class="scrolled ? 'text-slate-600 hover:text-green-600' : 'text-white/90 hover:text-white'" class="text-sm font-medium transition-colors">Cara Reservasi</a>
                    <a href="#faq" :class="scrolled ? 'text-slate-600 hover:text-green-600' : 'text-white/90 hover:text-white'" class="text-sm font-medium transition-colors">FAQ</a>
                </div>

                {{-- Login Button --}}
                <div class="hidden md:flex items-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full text-sm font-semibold transition-all"
                       :class="scrolled ? '!bg-green-600 !text-white !border-transparent hover:!bg-green-700' : ''">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Login Admin
                    </a>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" :class="scrolled ? 'text-slate-900' : 'text-white'">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu Dropdown --}}
        <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-collapse class="md:hidden bg-white shadow-xl absolute w-full left-0 top-full">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-green-600 hover:bg-green-50">Beranda</a>
                <a href="#fasilitas" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-green-600 hover:bg-green-50">Fasilitas</a>
                <a href="#jadwal" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-green-600 hover:bg-green-50">Jadwal</a>
                <a href="#cara-reservasi" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-green-600 hover:bg-green-50">Cara Reservasi</a>
                <a href="#faq" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-green-600 hover:bg-green-50">FAQ</a>
                <div class="mt-4 pt-4 border-t border-slate-100 px-3">
                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 bg-green-600 text-white px-5 py-3 rounded-xl font-semibold">
                        Login Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <div class="relative bg-slate-900 pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        {{-- Modern background shapes --}}
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03]"></div>
        <div class="absolute top-0 right-0 -mr-40 -mt-40 w-[600px] h-[600px] bg-green-500/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 -ml-40 -mb-40 w-[500px] h-[500px] bg-emerald-500/20 rounded-full blur-[100px]"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                {{-- Text Content --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-500/10 border border-green-500/20 text-green-400 text-sm font-semibold mb-6">
                        <span class="flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                        Sistem Reservasi Online
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] mb-6 tracking-tight">
                        Reservasi Gedung & Fasilitas <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-300">Lebih Mudah</span>
                    </h1>
                    <p class="text-lg text-slate-300 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        Lakukan reservasi gedung, aula, ruang rapat, dan fasilitas PNBP secara online tanpa perlu login. Proses cepat, transparan, dan terpercaya.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-10">
                        <a href="{{ route('booking') }}" class="inline-flex justify-center items-center gap-2 bg-green-600 hover:bg-green-500 text-white px-8 py-4 rounded-full font-bold text-lg transition-all shadow-lg shadow-green-500/30 hover:shadow-green-500/50 hover:-translate-y-0.5">
                            Reservasi Sekarang
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="#jadwal" class="inline-flex justify-center items-center gap-2 bg-white/5 hover:bg-white/10 text-white border border-white/10 px-8 py-4 rounded-full font-bold text-lg transition-all backdrop-blur-sm">
                            <svg class="h-5 w-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Lihat Jadwal
                        </a>
                    </div>
                    
                    <div class="flex flex-wrap justify-center lg:justify-start gap-x-6 gap-y-3">
                        <div class="flex items-center gap-2 text-sm font-medium text-slate-300">
                            <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Tanpa Login
                        </div>
                        <div class="flex items-center gap-2 text-sm font-medium text-slate-300">
                            <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Reservasi Cepat
                        </div>
                        <div class="flex items-center gap-2 text-sm font-medium text-slate-300">
                            <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Transparan
                        </div>
                        <div class="flex items-center gap-2 text-sm font-medium text-slate-300">
                            <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Online 24 Jam
                        </div>
                    </div>
                </div>
                
                {{-- Illustration (Glassmorphism card + Calendar UI) --}}
                <div class="relative hidden lg:block">
                    <div class="relative z-10 bg-slate-800/60 backdrop-blur-xl border border-white/10 p-6 rounded-3xl shadow-2xl">
                        {{-- Fake Header --}}
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-2">
                                <div class="h-3 w-3 rounded-full bg-red-400"></div>
                                <div class="h-3 w-3 rounded-full bg-amber-400"></div>
                                <div class="h-3 w-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="h-6 w-24 bg-white/5 rounded-full"></div>
                        </div>
                        {{-- Fake Calendar Grid --}}
                        <div class="grid grid-cols-7 gap-2 mb-4 text-center">
                            @foreach(['S','S','R','K','J','S','M'] as $d)
                                <div class="text-xs font-bold text-slate-400">{{ $d }}</div>
                            @endforeach
                            @for($i=1; $i<=28; $i++)
                                <div class="aspect-square flex items-center justify-center rounded-lg text-sm font-medium {{ $i == 15 ? 'bg-green-500 text-white shadow-lg shadow-green-500/40' : ($i == 18 || $i == 20 ? 'bg-red-500/20 text-red-300' : 'text-slate-300 bg-white/5') }}">
                                    {{ $i }}
                                </div>
                            @endfor
                        </div>
                        {{-- Fake floating notification --}}
                        <div class="absolute -right-8 -bottom-8 bg-white p-4 rounded-2xl shadow-xl shadow-black/20 flex items-center gap-4 animate-[bounce_3s_infinite]">
                            <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Reservasi Disetujui</p>
                                <p class="text-xs text-slate-500">Aula Utama, 15 Jan</p>
                            </div>
                        </div>
                    </div>
                    {{-- Decorative rings --}}
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] border border-white/5 rounded-full border-dashed animate-[spin_60s_linear_infinite]"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[100%] h-[100%] border border-white/10 rounded-full animate-[spin_40s_linear_infinite_reverse]"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="relative z-20 -mt-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 p-6 sm:p-8 grid grid-cols-2 lg:grid-cols-4 gap-8 divide-x divide-slate-100">
            <div class="text-center px-4">
                <div class="h-12 w-12 mx-auto bg-green-50 rounded-xl flex items-center justify-center mb-4 text-green-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalActiveBuildings }}+</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Gedung & Fasilitas</p>
            </div>
            <div class="text-center px-4">
                <div class="h-12 w-12 mx-auto bg-blue-50 rounded-xl flex items-center justify-center mb-4 text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalCompleted }}+</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Reservasi Selesai</p>
            </div>
            <div class="text-center px-4">
                <div class="h-12 w-12 mx-auto bg-amber-50 rounded-xl flex items-center justify-center mb-4 text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514M11 4v.01M12 4v.01"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-800">98%</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Kepuasan Pengguna</p>
            </div>
            <div class="text-center px-4">
                <div class="h-12 w-12 mx-auto bg-purple-50 rounded-xl flex items-center justify-center mb-4 text-purple-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-800">24/7</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Layanan Online</p>
            </div>
        </div>
    </div>

    {{-- Fasilitas Unggulan --}}
    <section id="fasilitas" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Fasilitas Unggulan</h2>
                    <p class="text-slate-500 mt-2">Pilih berbagai gedung dan fasilitas terbaik sesuai kebutuhan acara Anda.</p>
                </div>
                <a href="{{ route('booking') }}" class="mt-4 md:mt-0 text-green-600 font-semibold hover:text-green-700 inline-flex items-center gap-1 group">
                    Lihat Semua Fasilitas
                    <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($facilityTypes->take(6) as $type)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden group border border-slate-100 flex flex-col h-full">
                    <div class="relative h-56 overflow-hidden">
                        @if($type->image_path)
                            <img src="{{ asset('storage/'.$type->image_path) }}" alt="{{ $type->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-slate-800 shadow">
                            {{ $type->activeBuildings->count() }} Tersedia
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $type->name }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ $type->description }}</p>
                        
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tarif PNBP</p>
                                <p class="text-lg font-black text-green-600">{{ $type->daily_rate_formatted }}<span class="text-xs text-slate-400 font-medium">/hari</span></p>
                            </div>
                            <a href="{{ route('booking') }}" class="bg-slate-100 hover:bg-green-600 hover:text-white text-slate-700 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors">
                                Pesan
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Jadwal Ketersediaan --}}
    <section id="jadwal" class="py-24 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Jadwal Ketersediaan</h2>
                <p class="text-slate-500 mt-2 max-w-2xl mx-auto">Cek kalender ketersediaan fasilitas secara realtime sebelum melakukan reservasi.</p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/40 overflow-hidden">
                {{-- Calendar Header --}}
                <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                    <div class="flex items-center gap-4 mb-4 sm:mb-0">
                        <h3 class="text-xl font-bold text-slate-800 w-48">
                            {{ Carbon\Carbon::create($currentYear, $currentMonth)->translatedFormat('F Y') }}
                        </h3>
                        <div class="flex items-center rounded-lg shadow-sm border border-slate-200 bg-white">
                            <button wire:click="prevMonth" class="p-2 text-slate-600 hover:bg-slate-50 hover:text-green-600 transition-colors border-r border-slate-200">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button wire:click="setToday" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Hari Ini</button>
                            <button wire:click="nextMonth" class="p-2 text-slate-600 hover:bg-slate-50 hover:text-green-600 transition-colors border-l border-slate-200">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <div class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-green-500 border border-green-600"></span><span class="text-slate-600">Ada Reservasi</span></div>
                        <div class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-red-500 border border-red-600"></span><span class="text-slate-600">Penuh</span></div>
                        <div class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-amber-400 border border-amber-500"></span><span class="text-slate-600">Menunggu</span></div>
                    </div>
                </div>

                {{-- Calendar Grid --}}
                <div class="overflow-x-auto">
                    <div class="min-w-[700px]">
                        <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-100/50">
                            @foreach(['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                                <div class="px-2 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $day }}</div>
                            @endforeach
                        </div>
                        
                        <div class="grid grid-cols-7 bg-slate-200 gap-px border-b border-slate-200">
                            @php
                                $startOfMonth = Carbon\Carbon::create($currentYear, $currentMonth, 1);
                                $endOfMonth = clone $startOfMonth;
                                $endOfMonth->endOfMonth();
                                
                                $startDayOfWeek = $startOfMonth->dayOfWeek;
                                $daysInMonth = $endOfMonth->daysInMonth;
                                $totalCells = ceil(($startDayOfWeek + $daysInMonth) / 7) * 7;
                                
                                $today = Carbon\Carbon::today();
                            @endphp

                            @for($i = 0; $i < $totalCells; $i++)
                                @php
                                    $isCurrentMonth = $i >= $startDayOfWeek && $i < ($startDayOfWeek + $daysInMonth);
                                    $day = $i - $startDayOfWeek + 1;
                                    $dateStr = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, max(1, min($day, $daysInMonth)));
                                    $isToday = $isCurrentMonth && $dateStr === $today->format('Y-m-d');
                                    
                                    // Calculate events for this day
                                    $dayEvents = collect();
                                    if ($isCurrentMonth) {
                                        $dayEvents = $events->filter(function($e) use ($dateStr) {
                                            return $e['start'] <= $dateStr && $e['end'] >= $dateStr;
                                        })->values();
                                    }
                                    
                                    $isFull = $dayEvents->unique('building_id')->count() >= $totalActiveBuildings;
                                    $pendingCount = $dayEvents->where('is_pending', true)->count();
                                    $confirmedCount = $dayEvents->where('is_pending', false)->count();
                                @endphp

                                <div class="min-h-[100px] bg-white p-2 flex flex-col {{ !$isCurrentMonth ? 'bg-slate-50 text-slate-300' : 'text-slate-700 hover:bg-slate-50' }} {{ $isToday ? 'ring-2 ring-inset ring-green-500 bg-green-50/30' : '' }} relative group transition-colors">
                                    @if($isCurrentMonth)
                                        <div class="flex justify-between items-start mb-1">
                                            <span class="text-sm font-bold {{ $isToday ? 'text-green-600' : '' }} {{ ($i%7==0 || $i%7==6) ? 'text-red-500' : '' }}">{{ $day }}</span>
                                        </div>
                                        
                                        <div class="flex-grow flex flex-col gap-1 mt-1">
                                            @if($isFull)
                                                <div class="text-[10px] font-bold bg-red-100 text-red-700 px-1.5 py-0.5 rounded border border-red-200">PENUH</div>
                                            @else
                                                @if($confirmedCount > 0)
                                                    <div class="text-[10px] font-bold bg-green-100 text-green-700 px-1.5 py-0.5 rounded border border-green-200">{{ $confirmedCount }} Reservasi</div>
                                                @endif
                                                @if($pendingCount > 0)
                                                    <div class="text-[10px] font-bold bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded border border-amber-200">{{ $pendingCount }} Menunggu</div>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        {{-- Hover Tooltip --}}
                                        @if(count($dayEvents) > 0)
                                        <div class="hidden group-hover:block absolute z-10 bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-slate-800 text-white text-xs rounded-lg shadow-xl p-3">
                                            <p class="font-bold border-b border-slate-600 pb-1 mb-2">{{ Carbon\Carbon::parse($dateStr)->translatedFormat('d F Y') }}</p>
                                            <div class="space-y-1.5 max-h-32 overflow-y-auto">
                                                @foreach($dayEvents->take(3) as $ev)
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="w-2 h-2 rounded-full {{ $ev['is_pending'] ? 'bg-amber-400' : 'bg-green-400' }}"></span>
                                                        <span class="truncate">{{ $ev['building_name'] }}</span>
                                                    </div>
                                                @endforeach
                                                @if(count($dayEvents) > 3)
                                                    <div class="text-slate-400 italic pt-1">+ {{ count($dayEvents) - 3 }} lainnya</div>
                                                @endif
                                            </div>
                                            <div class="absolute w-3 h-3 bg-slate-800 transform rotate-45 -bottom-1.5 left-1/2 -translate-x-1/2"></div>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('booking') }}" class="inline-flex items-center gap-2 text-green-600 font-bold hover:text-green-700">
                    Lanjutkan ke Form Reservasi
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Cara Reservasi (Timeline) --}}
    <section id="cara-reservasi" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Cara Melakukan Reservasi</h2>
                <p class="text-slate-500 mt-2">5 langkah mudah untuk memesan fasilitas di SI-RESERVASI PNBP.</p>
            </div>

            <div class="relative">
                {{-- Line connector --}}
                <div class="hidden lg:block absolute top-12 left-0 w-full h-0.5 bg-slate-200"></div>

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                    {{-- Step 1 --}}
                    <div class="relative text-center">
                        <div class="h-24 w-24 mx-auto bg-white border border-slate-200 rounded-full flex items-center justify-center shadow-lg relative z-10 mb-6">
                            <span class="absolute -top-2 -right-2 h-8 w-8 bg-green-600 text-white font-bold rounded-full flex items-center justify-center border-2 border-white shadow">1</span>
                            <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-2">Pilih Fasilitas</h3>
                        <p class="text-sm text-slate-500">Cari dan pilih gedung atau fasilitas yang sesuai dengan kebutuhan Anda.</p>
                    </div>

                    {{-- Step 2 --}}
                    <div class="relative text-center">
                        <div class="h-24 w-24 mx-auto bg-white border border-slate-200 rounded-full flex items-center justify-center shadow-lg relative z-10 mb-6">
                            <span class="absolute -top-2 -right-2 h-8 w-8 bg-green-600 text-white font-bold rounded-full flex items-center justify-center border-2 border-white shadow">2</span>
                            <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-2">Pilih Tanggal</h3>
                        <p class="text-sm text-slate-500">Tentukan tanggal dan durasi penggunaan dengan mengecek kalender.</p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="relative text-center">
                        <div class="h-24 w-24 mx-auto bg-white border border-slate-200 rounded-full flex items-center justify-center shadow-lg relative z-10 mb-6">
                            <span class="absolute -top-2 -right-2 h-8 w-8 bg-green-600 text-white font-bold rounded-full flex items-center justify-center border-2 border-white shadow">3</span>
                            <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-2">Isi Data</h3>
                        <p class="text-sm text-slate-500">Lengkapi form biodata dan detail kegiatan tanpa perlu membuat akun.</p>
                    </div>

                    {{-- Step 4 --}}
                    <div class="relative text-center">
                        <div class="h-24 w-24 mx-auto bg-white border border-slate-200 rounded-full flex items-center justify-center shadow-lg relative z-10 mb-6">
                            <span class="absolute -top-2 -right-2 h-8 w-8 bg-green-600 text-white font-bold rounded-full flex items-center justify-center border-2 border-white shadow">4</span>
                            <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-2">Bayar & Upload</h3>
                        <p class="text-sm text-slate-500">Lakukan pembayaran sesuai tagihan SIMPONI dan upload buktinya.</p>
                    </div>

                    {{-- Step 5 --}}
                    <div class="relative text-center">
                        <div class="h-24 w-24 mx-auto bg-green-50 border border-green-200 rounded-full flex items-center justify-center shadow-lg shadow-green-500/20 relative z-10 mb-6">
                            <span class="absolute -top-2 -right-2 h-8 w-8 bg-green-600 text-white font-bold rounded-full flex items-center justify-center border-2 border-white shadow">5</span>
                            <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-bold text-green-700 mb-2">Disetujui</h3>
                        <p class="text-sm text-green-600/80">Admin memverifikasi pembayaran dan reservasi Anda resmi disetujui!</p>
                    </div>
                </div>
            </div>
            
            {{-- Quick Form Start --}}
            <div class="mt-20 max-w-3xl mx-auto bg-white rounded-3xl p-8 sm:p-12 shadow-2xl shadow-slate-200/50 border border-slate-100 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[300px] h-[300px] bg-green-50 rounded-full blur-[80px]"></div>
                
                <h3 class="text-2xl font-bold text-slate-900 mb-4 relative z-10">Mulai Reservasi Anda Sekarang</h3>
                <p class="text-slate-500 mb-8 relative z-10">Tidak perlu mendaftar akun. Langsung pilih fasilitas dan isi form untuk memulai proses reservasi.</p>
                
                <a href="{{ route('booking') }}" class="relative z-10 inline-flex justify-center items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-10 py-5 rounded-2xl font-bold text-lg transition-transform hover:-translate-y-1 shadow-xl shadow-slate-900/20 w-full sm:w-auto">
                    Buka Form Reservasi
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Pertanyaan Umum (FAQ)</h2>
                <p class="text-slate-500 mt-2">Jawaban cepat untuk pertanyaan yang sering diajukan.</p>
            </div>

            <div class="space-y-4" x-data="{ active: null }">
                @php
                    $faqs = [
                        ['q' => 'Apakah harus login untuk reservasi?', 'a' => 'Tidak. Sistem ini dirancang untuk kemudahan publik. Anda dapat langsung melakukan reservasi tanpa perlu membuat akun atau login. Cukup lengkapi form biodata yang disediakan.'],
                        ['q' => 'Bagaimana cara pembayaran?', 'a' => 'Setelah reservasi Anda disetujui awal oleh admin, Anda akan menerima Kode Billing SIMPONI. Pembayaran dilakukan melalui Bank/Pos/Lembaga Persepsi dengan menyebutkan kode billing tersebut.'],
                        ['q' => 'Berapa lama proses verifikasi?', 'a' => 'Proses verifikasi maksimal 1x24 jam kerja setelah Anda mengupload bukti pembayaran.'],
                        ['q' => 'Bagaimana jika ingin membatalkan reservasi?', 'a' => 'Pembatalan dapat dilakukan dengan menghubungi admin melalui nomor kontak atau WhatsApp yang tertera sebelum tagihan dibayarkan.'],
                        ['q' => 'Siapa yang dapat menggunakan fasilitas?', 'a' => 'Fasilitas terbuka untuk umum (Instansi Pemerintah, Swasta, Organisasi, maupun Perorangan) dengan mematuhi tarif PNBP yang berlaku.'],
                    ];
                @endphp

                @foreach($faqs as $idx => $faq)
                <div class="border border-slate-200 rounded-2xl bg-white overflow-hidden transition-all duration-300"
                     :class="active === {{ $idx }} ? 'ring-2 ring-green-500 shadow-md' : 'hover:border-slate-300'">
                    <button @click="active = active === {{ $idx }} ? null : {{ $idx }}" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-semibold text-slate-900" :class="active === {{ $idx }} ? 'text-green-600' : ''">{{ $faq['q'] }}</span>
                        <svg class="h-5 w-5 text-slate-400 transition-transform duration-300" :class="active === {{ $idx }} ? 'rotate-180 text-green-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === {{ $idx }}" x-collapse>
                        <div class="px-6 pb-5 text-slate-500 text-sm leading-relaxed border-t border-slate-100 pt-3">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-[#0b1b10] border-t border-green-900/30 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                {{-- Col 1 --}}
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        @if($appLogo)
                            <img src="{{ asset('storage/' . $appLogo) }}" alt="Logo" class="h-10 w-auto filter brightness-0 invert">
                        @else
                            <div class="h-10 w-10 bg-green-500 rounded flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                        @endif
                        <span class="font-bold text-xl text-white">SI-RESERVASI PNBP</span>
                    </div>
                    <p class="text-green-900/60 text-slate-400 max-w-md leading-relaxed">
                        Sistem Informasi Reservasi Gedung dan Fasilitas Berbasis Penerimaan Negara Bukan Pajak (PNBP) yang memberikan kemudahan, kecepatan, dan transparansi layanan.
                    </p>
                </div>

                {{-- Col 2 --}}
                <div>
                    <h4 class="font-bold text-white mb-6 tracking-wider text-sm uppercase">Navigasi</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-slate-400 hover:text-green-400 transition-colors">Beranda</a></li>
                        <li><a href="#fasilitas" class="text-slate-400 hover:text-green-400 transition-colors">Fasilitas</a></li>
                        <li><a href="#jadwal" class="text-slate-400 hover:text-green-400 transition-colors">Jadwal Ketersediaan</a></li>
                        <li><a href="#faq" class="text-slate-400 hover:text-green-400 transition-colors">FAQ</a></li>
                    </ul>
                </div>

                {{-- Col 3 --}}
                <div>
                    <h4 class="font-bold text-white mb-6 tracking-wider text-sm uppercase">Kontak Kami</h4>
                    <ul class="space-y-4 text-slate-400 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-green-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Jl. Merdeka No. 123, Jakarta Pusat<br>DKI Jakarta 10110</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            reservasi@pnbp.go.id
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            (021) 1234 5678
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-green-900/50 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-slate-500">
                    &copy; {{ date('Y') }} SI-RESERVASI PNBP. Hak Cipta Dilindungi.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-slate-500 hover:text-white transition-colors">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-white transition-colors">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</div>
