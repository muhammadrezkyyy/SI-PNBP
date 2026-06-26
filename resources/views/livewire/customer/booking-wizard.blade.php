<div class="min-h-screen transition-colors" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #0f172a 100%);">

    {{-- ===== NAVBAR STICKY ===== --}}
    <nav id="sticky-nav" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" style="background: transparent;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                @php
                    $appName = \App\Models\AppSetting::getVal('app_name', 'SI-RESERVASI PNBP');
                    $appLogo = \App\Models\AppSetting::getVal('app_logo_path');
                @endphp
                <div class="flex items-center gap-3">
                    @if($appLogo)
                        <div class="h-8 w-8 rounded-lg overflow-hidden border border-white/20 flex-shrink-0">
                            <img src="{{ asset('storage/' . $appLogo) }}" class="h-full w-full object-cover">
                        </div>
                    @else
                        <div class="h-8 w-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                    <span class="font-bold text-white text-sm hidden sm:block nav-title" style="opacity:0; transition: opacity 0.3s;">{{ $appName }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <button id="theme-toggle" type="button" class="text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2 transition-all">
                        <svg id="theme-toggle-dark-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    </button>
                    <a href="#booking-form" class="hidden sm:inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-bold text-white transition-all" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ===== HERO SECTION ===== --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden px-4 pb-16" style="padding-top: 80px;">

        {{-- Animated blobs --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="blob blob-1" style="position:absolute; width:600px; height:600px; border-radius:9999px; opacity:0.15; animation: blob1 12s ease-in-out infinite; top:-200px; left:-200px; background: radial-gradient(circle, #6366f1, #8b5cf6);"></div>
            <div class="blob blob-2" style="position:absolute; width:500px; height:500px; border-radius:9999px; opacity:0.12; animation: blob2 15s ease-in-out infinite; bottom:-150px; right:-150px; background: radial-gradient(circle, #3b82f6, #06b6d4);"></div>
            <div class="blob blob-3" style="position:absolute; width:350px; height:350px; border-radius:9999px; opacity:0.1; animation: blob3 10s ease-in-out infinite; top:40%; left:60%; background: radial-gradient(circle, #a855f7, #ec4899);"></div>
            {{-- Grid pattern --}}
            <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(99,102,241,0.06) 1px, transparent 1px),linear-gradient(90deg, rgba(99,102,241,0.06) 1px, transparent 1px);background-size:40px 40px;"></div>
        </div>

        <div class="relative z-10 max-w-5xl w-full mx-auto text-center">

            {{-- Logo --}}
            <div class="hero-animate" style="animation: fadeSlideUp 0.7s ease forwards; opacity:0; animation-delay:0.1s;">
                @if($appLogo)
                    <div class="mx-auto mb-6 h-20 w-20 rounded-2xl overflow-hidden border-2 border-white/20 shadow-2xl" style="box-shadow: 0 0 40px rgba(99,102,241,0.4);">
                        <img src="{{ asset('storage/' . $appLogo) }}" class="h-full w-full object-cover">
                    </div>
                @else
                    <div class="mx-auto mb-6 h-20 w-20 rounded-2xl flex items-center justify-center shadow-2xl border border-white/20" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); box-shadow: 0 0 40px rgba(99,102,241,0.4);">
                        <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Badge --}}
            <div class="hero-animate" style="animation: fadeSlideUp 0.7s ease forwards; opacity:0; animation-delay:0.2s;">
                <span class="inline-flex items-center gap-2 rounded-full border border-indigo-400/30 px-4 py-1.5 text-xs font-semibold text-indigo-300 mb-6" style="background: rgba(99,102,241,0.15); backdrop-filter: blur(8px);">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-400"></span></span>
                    Sistem Reservasi Online · PNBP
                </span>
            </div>

            {{-- Heading --}}
            <div class="hero-animate" style="animation: fadeSlideUp 0.7s ease forwards; opacity:0; animation-delay:0.3s;">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                    {{ $appName }}
                    <span class="block mt-2 text-transparent bg-clip-text" style="background: linear-gradient(135deg, #818cf8, #c084fc, #38bdf8);">
                        Reservasi Mudah & Cepat
                    </span>
                </h1>
                <p class="max-w-2xl mx-auto text-lg text-slate-300 leading-relaxed mb-10">
                    Pesan fasilitas gedung dan ruangan secara online. Proses cepat, transparan, dan terpercaya untuk kebutuhan kegiatan Anda.
                </p>
            </div>

            {{-- CTA Buttons --}}
            <div class="hero-animate flex flex-col sm:flex-row gap-4 justify-center items-center mb-16" style="animation: fadeSlideUp 0.7s ease forwards; opacity:0; animation-delay:0.4s;">
                <a href="#booking-form" class="inline-flex items-center gap-3 rounded-2xl px-8 py-4 text-base font-bold text-white shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-indigo-500/40 group" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); box-shadow: 0 4px 30px rgba(99,102,241,0.4);">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Mulai Pesan Sekarang
                    <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="#how-it-works" class="inline-flex items-center gap-2 rounded-2xl px-8 py-4 text-base font-semibold text-white/80 hover:text-white transition-all duration-300 border border-white/20 hover:border-white/40 hover:bg-white/10" style="backdrop-filter:blur(8px);">
                    Cara Reservasi
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
            </div>

            {{-- Stats --}}
            @php
                $totalFacilityTypes = \App\Models\FacilityType::count();
                $totalBuildings = \App\Models\Building::where('is_active', true)->count();
                $totalConfirmed = \App\Models\Reservation::whereIn('status', ['CONFIRMED', 'COMPLETED'])->count();
            @endphp
            <div class="hero-animate grid grid-cols-3 gap-4 max-w-lg mx-auto" style="animation: fadeSlideUp 0.7s ease forwards; opacity:0; animation-delay:0.55s;">
                <div class="rounded-2xl p-4 text-center border border-white/10" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                    <p class="text-2xl font-black text-white">{{ $totalFacilityTypes }}</p>
                    <p class="text-xs text-slate-400 mt-1">Kategori</p>
                </div>
                <div class="rounded-2xl p-4 text-center border border-white/10" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                    <p class="text-2xl font-black text-white">{{ $totalBuildings }}</p>
                    <p class="text-xs text-slate-400 mt-1">Unit Tersedia</p>
                </div>
                <div class="rounded-2xl p-4 text-center border border-white/10" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                    <p class="text-2xl font-black text-white">{{ $totalConfirmed }}</p>
                    <p class="text-xs text-slate-400 mt-1">Terkonfirmasi</p>
                </div>
            </div>

            {{-- Scroll indicator --}}
            <div class="mt-12 flex justify-center" style="animation: bounce-slow 2s ease-in-out infinite;">
                <div class="flex flex-col items-center gap-2 text-white/40">
                    <span class="text-xs">Scroll ke bawah</span>
                    <svg class="h-5 w-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== HOW IT WORKS SECTION ===== --}}
    <section id="how-it-works" class="relative py-20 px-4" style="background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <span class="inline-block text-xs font-bold uppercase tracking-widest text-indigo-400 mb-3">Panduan Reservasi</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">3 Langkah Mudah</h2>
                <p class="text-slate-400 max-w-xl mx-auto">Proses reservasi fasilitas kami dirancang sesederhana mungkin agar Anda dapat memesan dengan cepat dan nyaman.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                {{-- Connector line --}}
                <div class="hidden md:block absolute top-12 left-1/3 right-1/3 h-0.5 z-0" style="background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), transparent);"></div>

                @php $steps = [
                    ['num'=>'01','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','title'=>'Pilih Fasilitas & Jadwal','desc'=>'Pilih kategori fasilitas, tentukan tanggal mulai dan selesai, lalu pilih unit yang tersedia.','color'=>'from-indigo-500 to-blue-500'],
                    ['num'=>'02','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','title'=>'Isi Data Pemohon','desc'=>'Lengkapi formulir data diri dan keperluan penggunaan fasilitas yang dibutuhkan.','color'=>'from-purple-500 to-pink-500'],
                    ['num'=>'03','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','title'=>'Konfirmasi & Pembayaran','desc'=>'Admin akan memproses dan mengirim tagihan SIMPONI. Selesaikan pembayaran untuk konfirmasi.','color'=>'from-emerald-500 to-teal-500'],
                ]; @endphp

                @foreach($steps as $step)
                <div class="relative z-10 group rounded-2xl p-7 border border-white/10 transition-all duration-300 hover:border-white/20 hover:-translate-y-1" style="background: rgba(255,255,255,0.04); backdrop-filter:blur(12px);">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="h-12 w-12 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, {{ explode(' ', $step['color'])[0] === 'from-indigo-500' ? '#6366f1,#3b82f6' : (explode(' ', $step['color'])[0] === 'from-purple-500' ? '#a855f7,#ec4899' : '#10b981,#14b8a6') }});">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-4xl font-black text-white/10 leading-none">{{ $step['num'] }}</span>
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">{{ $step['title'] }}</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="#booking-form" class="inline-flex items-center gap-2 rounded-2xl px-7 py-3.5 text-sm font-bold text-white transition-all duration-300 hover:scale-105" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); box-shadow: 0 4px 20px rgba(99,102,241,0.35);">
                    Mulai Reservasi
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ===== BOOKING FORM SECTION ===== --}}
    <section id="booking-form" class="relative py-20 px-4" style="background: linear-gradient(180deg, #1e1b4b 0%, #0f172a 100%);">
        <div class="max-w-5xl xl:max-w-7xl w-full mx-auto">

            {{-- Section Header --}}
            <div class="text-center mb-10">
                <span class="inline-block text-xs font-bold uppercase tracking-widest text-indigo-400 mb-3">Formulir Reservasi</span>
                <h2 class="text-2xl sm:text-3xl font-black text-white mb-2">Buat Reservasi Baru</h2>
                <p class="text-slate-400 text-sm">Pilih fasilitas, tanggal, dan isi data pemohon Anda.</p>
            </div>

            {{-- Success State --}}
            @if($booking_success)
            <div class="max-w-xl mx-auto rounded-3xl border border-emerald-500/30 p-10 text-center" style="background: rgba(16,185,129,0.08); backdrop-filter:blur(16px);">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full" style="background: rgba(16,185,129,0.2);">
                    <svg class="h-10 w-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Reservasi Berhasil! 🎉</h2>
                <p class="text-slate-300 mb-8">Permintaan reservasi Anda telah kami terima. Admin akan segera menyiapkan tagihan SIMPONI dan menghubungi Anda via WhatsApp.</p>
                <div class="rounded-xl border border-white/10 p-4 mb-8" style="background: rgba(255,255,255,0.05);">
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-2">ID Reservasi</p>
                    <p class="font-mono text-sm font-bold text-indigo-300 break-all">{{ $reservation_id }}</p>
                </div>
                <a href="{{ route('booking') }}" class="inline-flex items-center gap-2 rounded-2xl px-7 py-3.5 text-sm font-bold text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Reservasi Baru
                </a>
            </div>
            @else

            {{-- Booking Form Card --}}
            <div class="rounded-3xl border border-white/10 overflow-hidden shadow-2xl" style="background: rgba(255,255,255,0.04); backdrop-filter:blur(20px); box-shadow: 0 25px 50px rgba(0,0,0,0.4);">

                {{-- Conflict Error --}}
                @if($conflict_error)
                <div class="border-b border-red-500/20 px-6 py-4 flex items-start gap-3" style="background: rgba(239,68,68,0.1);">
                    <svg class="h-5 w-5 flex-shrink-0 text-red-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-red-300">Jadwal Tidak Tersedia</p>
                        <p class="text-sm text-red-400/80 mt-0.5">{{ $conflict_error }}</p>
                    </div>
                </div>
                @endif

                <div class="p-5 sm:p-7 lg:p-10">

                    @if($current_step === 1)
                    {{-- STEP 1 --}}
                    <div class="w-full max-w-4xl mx-auto space-y-8"
                         x-data="{}"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-y-6"
                         x-transition:enter-end="opacity-100 translate-y-0">

                        {{-- Step indicator --}}
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">1</div>
                                <span class="text-sm font-semibold text-white">Pilih Fasilitas</span>
                            </div>
                            <div class="flex-1 h-px" style="background: rgba(255,255,255,0.1);"></div>
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold text-slate-500 border border-white/10">2</div>
                                <span class="text-sm font-medium text-slate-500 hidden sm:block">Data Pemohon</span>
                            </div>
                        </div>

                        {{-- Date Range --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 rounded-2xl border border-white/10" style="background: rgba(255,255,255,0.04);">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tanggal Mulai <span class="text-red-400">*</span></label>
                                <input type="date" wire:model.live="start_date"
                                       min="{{ now()->addDay()->format('Y-m-d') }}"
                                       class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:outline-none focus:ring-2 transition-all"
                                       style="background: rgba(255,255,255,0.06); color-scheme: dark;">
                                @error('start_date')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tanggal Selesai <span class="text-red-400">*</span></label>
                                <input type="date" wire:model.live="end_date"
                                       min="{{ $start_date ?: now()->addDay()->format('Y-m-d') }}"
                                       class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:outline-none focus:ring-2 transition-all"
                                       style="background: rgba(255,255,255,0.06); color-scheme: dark;">
                                @error('end_date')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Facility Type Selection --}}
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">
                                Kategori Fasilitas <span class="text-red-400">*</span>
                                <span class="font-normal text-slate-400 text-xs ml-1">({{ count($facilityTypes) }} tersedia)</span>
                            </label>

                            <style>
                                .custom-scrollbar::-webkit-scrollbar { height: 6px; }
                                .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius:4px; }
                                .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.5); border-radius: 4px; }
                                .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(99,102,241,0.8); }
                                input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1) opacity(0.5); cursor:pointer; }
                            </style>

                            <div class="flex overflow-x-auto pb-4 gap-4 snap-x custom-scrollbar scroll-smooth">
                                @foreach($facilityTypes as $type)
                                @php $typeImages = $type->all_image_paths; @endphp
                                <label class="flex-shrink-0 snap-start relative flex flex-col cursor-pointer rounded-2xl border-2 overflow-hidden transition-all duration-200 group"
                                       style="width: 260px; min-width: 260px; max-width: 85vw;
                                              {{ $facility_type_id === (string)$type->id ? 'border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.2);' : 'border-color: rgba(255,255,255,0.1);' }}">
                                    <input type="radio" wire:model.live="facility_type_id" value="{{ $type->id }}" class="sr-only">

                                    {{-- Image Carousel --}}
                                    <div class="relative w-full flex-shrink-0 overflow-hidden" style="height: 160px; background: rgba(255,255,255,0.05);" id="carousel-{{ $type->id }}" data-images="{{ json_encode(array_map(function($p){return asset('storage/'.$p);}, $typeImages)) }}">
                                        @if(count($typeImages) > 0)
                                            <div class="flex h-full transition-transform duration-300 ease-in-out carousel-track" data-current="0">
                                                @foreach($typeImages as $index => $imgPath)
                                                <div class="flex-shrink-0 w-full h-full">
                                                    <img src="{{ asset('storage/' . $imgPath) }}"
                                                         alt="{{ $type->name }}"
                                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 cursor-zoom-in"
                                                         onclick="openLightbox(event, '{{ $type->id }}', {{ $index }}, '{{ addslashes($type->name) }}')"
                                                         title="Klik untuk perbesar foto">
                                                </div>
                                                @endforeach
                                            </div>

                                            @if(count($typeImages) > 1)
                                            <button type="button"
                                                    class="carousel-prev absolute left-1.5 top-1/2 -translate-y-1/2 h-7 w-7 rounded-full text-white flex items-center justify-center transition-all opacity-0 group-hover:opacity-100"
                                                    style="background:rgba(0,0,0,0.5);"
                                                    onclick="carouselSlide(event, '{{ $type->id }}', -1)">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                            </button>
                                            <button type="button"
                                                    class="carousel-next absolute right-1.5 top-1/2 -translate-y-1/2 h-7 w-7 rounded-full text-white flex items-center justify-center transition-all opacity-0 group-hover:opacity-100"
                                                    style="background:rgba(0,0,0,0.5);"
                                                    onclick="carouselSlide(event, '{{ $type->id }}', 1)">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                            </button>
                                            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1 carousel-dots">
                                                @foreach($typeImages as $i => $imgPath)
                                                <button type="button" onclick="carouselGoTo(event, '{{ $type->id }}', {{ $i }})"
                                                        class="carousel-dot h-1.5 rounded-full transition-all {{ $i === 0 ? 'w-4 bg-white' : 'w-1.5 bg-white/50' }}"></button>
                                                @endforeach
                                            </div>
                                            @endif
                                            <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to top, rgba(0,0,0,0.4), transparent);"></div>
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                                                <svg class="h-8 w-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <p class="text-xs text-slate-500">Foto belum tersedia</p>
                                            </div>
                                        @endif

                                        @if($facility_type_id === (string)$type->id)
                                        <div class="absolute top-2 left-2 h-6 w-6 rounded-full border-2 border-white flex items-center justify-center shadow" style="background:#6366f1;">
                                            <svg class="h-3.5 w-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Card Body --}}
                                    <div class="p-3.5 flex-1 flex flex-col" style="{{ $facility_type_id === (string)$type->id ? 'background: rgba(99,102,241,0.15);' : 'background: rgba(255,255,255,0.03);' }}">
                                        <div class="flex flex-col gap-1 mb-1.5">
                                            <p class="font-bold text-white text-sm leading-snug line-clamp-2" title="{{ $type->name }}">{{ $type->name }}</p>
                                            <span class="text-xs font-bold text-emerald-400">
                                                {{ $type->daily_rate_formatted }}<span class="font-normal text-slate-400 text-[10px]">/hari</span>
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-400 mt-1 line-clamp-2 flex-1">{{ $type->description }}</p>
                                        <p class="text-[10px] text-slate-400 mt-2 flex flex-wrap gap-1 items-center">
                                            @if($type->dynamic_available_count > 0)
                                                <span class="text-slate-300">{{ $type->dynamic_available_count }} unit tersedia</span>
                                            @else
                                                <span class="text-red-400 font-semibold">Tidak tersedia (Penuh)</span>
                                            @endif
                                            @if(count($typeImages) > 0)
                                            <span class="text-slate-600">·</span>
                                            <span class="text-indigo-400 hover:text-indigo-300 cursor-pointer relative z-10" onclick="openLightbox(event, '{{ $type->id }}', 0, '{{ addslashes($type->name) }}')">Lihat foto ↗</span>
                                            @endif
                                        </p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('facility_type_id')<p class="mt-2 text-xs font-medium text-red-400">{{ $message }}</p>@enderror
                        </div>

                        {{-- Unit Selection --}}
                        <div x-data="{ show: @entangle('facility_type_id') }"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 -translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">

                            @if($facility_type_id)
                            <div class="pt-2">
                                <label class="block text-sm font-bold text-white mb-4">
                                    Pilih Unit / Ruangan <span class="text-red-400">*</span>
                                </label>

                                @if(count($buildings) > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($buildings as $building)
                                    @if($building->is_booked)
                                        <div class="flex items-center justify-between gap-3 rounded-xl border-2 border-red-500/20 p-3 opacity-60 cursor-not-allowed" style="background:rgba(239,68,68,0.05);">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="h-4 w-4 flex-shrink-0 rounded-full border-2 border-red-500/50 bg-red-500/20"></div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-slate-500 text-sm line-through decoration-red-400 truncate">{{ $building->name }}</p>
                                                    <p class="text-[10px] sm:text-xs text-red-400 mt-0.5 flex items-center gap-1">
                                                        <svg class="h-3 w-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                        <span class="truncate">{{ $building->status_message }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="flex-shrink-0 inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold text-red-400 whitespace-nowrap" style="background:rgba(239,68,68,0.15);">
                                                {{ $building->status_badge }}
                                            </span>
                                        </div>
                                    @else
                                        <label class="flex items-center justify-between gap-3 rounded-xl border-2 p-3 cursor-pointer transition-all duration-200"
                                               style="{{ $building_id === (string)$building->id ? 'border-color:#6366f1; background:rgba(99,102,241,0.15); box-shadow:0 0 0 3px rgba(99,102,241,0.15);' : 'border-color:rgba(255,255,255,0.1); background:rgba(255,255,255,0.03);' }}">
                                            <input type="radio" wire:model.live="building_id" value="{{ $building->id }}" class="sr-only">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="h-4 w-4 flex-shrink-0 rounded-full border-2 transition-all flex items-center justify-center"
                                                     style="{{ $building_id === (string)$building->id ? 'border-color:#6366f1; background:#6366f1;' : 'border-color:rgba(255,255,255,0.3);' }}">
                                                    @if($building_id === (string)$building->id)
                                                    <div class="h-1.5 w-1.5 rounded-full bg-white"></div>
                                                    @endif
                                                </div>
                                                <p class="font-bold text-white text-sm truncate">{{ $building->name }}</p>
                                            </div>
                                            @if($building_id === (string)$building->id)
                                            <span class="flex-shrink-0 inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold text-indigo-300 whitespace-nowrap" style="background:rgba(99,102,241,0.2);">
                                                ✓ Dipilih
                                            </span>
                                            @else
                                            <span class="flex-shrink-0 inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold text-emerald-400 whitespace-nowrap" style="background:rgba(16,185,129,0.1);">
                                                Tersedia
                                            </span>
                                            @endif
                                        </label>
                                    @endif
                                    @endforeach
                                </div>
                                @else
                                <p class="text-sm text-slate-400 p-6 rounded-xl border border-white/10 text-center" style="background:rgba(255,255,255,0.04);">
                                    Maaf, tidak ada unit yang tersedia untuk kategori ini pada tanggal tersebut.
                                </p>
                                @endif
                                @error('building_id')<p class="mt-3 text-xs font-bold text-red-400">{{ $message }}</p>@enderror
                            </div>
                            @endif
                        </div>

                        {{-- Next Button --}}
                        @if($building_id && $facility_type_id && $start_date && $end_date)
                        <div class="flex justify-end pt-4 border-t border-white/10">
                            <button wire:click="nextStep" class="inline-flex items-center justify-center gap-2 rounded-2xl px-8 py-4 text-sm font-bold text-white shadow-xl transition-all hover:scale-105"
                                    style="background: linear-gradient(135deg,#6366f1,#8b5cf6); box-shadow:0 4px 20px rgba(99,102,241,0.35);">
                                <span>Lanjut Isi Data Pemohon</span>
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                        @endif

                    </div>
                    @endif

                    @if($current_step === 2)
                    {{-- STEP 2 --}}
                    <div class="w-full max-w-3xl mx-auto"
                         x-data="{}"
                         x-init="window.scrollTo({top: document.getElementById('booking-form').offsetTop - 80, behavior: 'smooth'})"
                         x-transition:enter="transition ease-out duration-500 delay-100"
                         x-transition:enter-start="opacity-0 translate-y-6"
                         x-transition:enter-end="opacity-100 translate-y-0">

                        @if($selectedType && $building_id && $start_date && $end_date)

                        {{-- Step indicator --}}
                        <div class="flex items-center gap-3 mb-8">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background:rgba(99,102,241,0.3); border:2px solid rgba(99,102,241,0.5);">✓</div>
                                <span class="text-sm font-medium text-slate-400 hidden sm:block">Pilih Fasilitas</span>
                            </div>
                            <div class="flex-1 h-px" style="background: linear-gradient(90deg, rgba(99,102,241,0.5), rgba(99,102,241,0.2));"></div>
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">2</div>
                                <span class="text-sm font-semibold text-white hidden sm:block">Data Pemohon</span>
                            </div>
                        </div>

                        {{-- Header --}}
                        <div class="flex items-center gap-4 mb-8">
                            <button type="button" wire:click="previousStep" class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-xl border border-white/10 text-slate-300 hover:text-white hover:border-white/30 transition-all" style="background:rgba(255,255,255,0.05);">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <div>
                                <h2 class="text-xl font-bold text-white mb-1">Data Pemohon</h2>
                                <p class="text-sm text-slate-400">Lengkapi formulir berikut untuk menyelesaikan reservasi.</p>
                            </div>
                        </div>

                        {{-- Summary Card --}}
                        <div class="rounded-2xl p-5 mb-8 border border-indigo-500/20" style="background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(139,92,246,0.1));">
                            <div class="mb-4 pb-4 border-b border-white/10">
                                <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-1">Fasilitas Dipesan</p>
                                <p class="text-base font-bold text-white">{{ $selectedType->name }}</p>
                                @php $selectedBuilding = $buildings->firstWhere('id', $building_id); @endphp
                                @if($selectedBuilding)
                                    <p class="text-sm font-medium text-slate-300 mt-0.5">Unit: <span class="font-bold text-white">{{ $selectedBuilding->name }}</span></p>
                                @endif
                                <p class="text-xs text-slate-400 mt-1">
                                    <span class="font-semibold text-slate-300">{{ \Carbon\Carbon::parse($start_date)->isoFormat('D MMMM Y') }}</span>
                                    s/d
                                    <span class="font-semibold text-slate-300">{{ \Carbon\Carbon::parse($end_date)->isoFormat('D MMMM Y') }}</span>
                                </p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-1">Estimasi Biaya</p>
                                    <p class="text-sm text-slate-300">{{ $selectedType->daily_rate_formatted }} × {{ $durationDays }} hari</p>
                                </div>
                                <p class="text-2xl font-black text-white">Rp {{ number_format($estimatedTotal, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Form Fields --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                            @foreach($fields as $field)
                            <div class="{{ $field->field_type === 'textarea' ? 'sm:col-span-2' : '' }}">
                                <label for="field_{{ $field->field_name }}" class="block text-sm font-semibold text-slate-300 mb-2">
                                    {{ $field->field_label }}
                                    @if($field->is_required)<span class="text-red-400">*</span>@endif
                                </label>

                                @if($field->field_type === 'textarea')
                                <textarea id="field_{{ $field->field_name }}"
                                          wire:model="customer_data.{{ $field->field_name }}"
                                          placeholder="{{ $field->placeholder }}"
                                          rows="3"
                                          class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-indigo-400 focus:outline-none focus:ring-2 transition-all resize-none"
                                          style="background:rgba(255,255,255,0.06);"></textarea>
                                @else
                                <input type="{{ $field->field_type }}"
                                       id="field_{{ $field->field_name }}"
                                       wire:model="customer_data.{{ $field->field_name }}"
                                       placeholder="{{ $field->placeholder }}"
                                       class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-indigo-400 focus:outline-none focus:ring-2 transition-all"
                                       style="background:rgba(255,255,255,0.06);">
                                @endif
                                @error("customer_data.{$field->field_name}")
                                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            @endforeach

                            @if($fields->isEmpty())
                            <div class="sm:col-span-2 rounded-xl border border-amber-400/20 p-4 text-sm text-amber-300" style="background:rgba(251,191,36,0.08);">
                                Admin belum mengkonfigurasi kolom formulir. Silakan hubungi admin.
                            </div>
                            @endif
                        </div>

                        {{-- Notice --}}
                        <div class="rounded-xl border border-amber-400/20 p-4 text-sm text-amber-300 mb-8" style="background:rgba(251,191,36,0.06);">
                            <p class="font-semibold mb-2 flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Perhatikan
                            </p>
                            <ul class="space-y-1.5 text-xs text-amber-300/80">
                                <li class="flex items-start gap-2"><span class="mt-1 h-1 w-1 rounded-full bg-amber-400 flex-shrink-0"></span>Reservasi dikonfirmasi admin maksimal 1×24 jam kerja (Sabtu, Minggu & libur nasional tidak dihitung).</li>
                                <li class="flex items-start gap-2"><span class="mt-1 h-1 w-1 rounded-full bg-amber-400 flex-shrink-0"></span>Setelah tagihan SIMPONI diterbitkan, Anda memiliki 24 jam untuk menyelesaikan pembayaran.</li>
                                <li class="flex items-start gap-2"><span class="mt-1 h-1 w-1 rounded-full bg-amber-400 flex-shrink-0"></span>Reservasi yang melewati batas waktu pembayaran akan otomatis dibatalkan.</li>
                            </ul>
                        </div>

                        {{-- Submit Button --}}
                        <button wire:click="confirmBooking"
                                wire:loading.attr="disabled"
                                wire:target="confirmBooking"
                                class="w-full flex items-center justify-center gap-3 rounded-2xl px-6 py-4 text-base font-bold text-white transition-all hover:scale-[1.01] disabled:opacity-60 disabled:cursor-not-allowed"
                                style="background:linear-gradient(135deg,#6366f1,#8b5cf6); box-shadow:0 4px 30px rgba(99,102,241,0.4);">
                            <svg wire:loading.remove wire:target="confirmBooking" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <svg wire:loading wire:target="confirmBooking" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            <span wire:loading.remove wire:target="confirmBooking">Selesaikan Reservasi</span>
                            <span wire:loading wire:target="confirmBooking">Memproses Reservasi...</span>
                        </button>

                        @endif
                    </div>
                    @endif

                </div>
            </div>
            @endif

            {{-- Footer --}}
            <p class="text-center text-xs text-slate-500 mt-10">
                @php
                    $copyrightText = \App\Models\AppSetting::getVal('copyright_text', '© ' . date('Y') . ' ' . $appName);
                    $copyrightWithHiddenLink = str_replace('©', '<a href="' . route('login') . '" class="hover:text-slate-400" style="text-decoration:none;color:inherit;cursor:text;">©</a>', $copyrightText);
                @endphp
                {!! $copyrightWithHiddenLink !!}
            </p>
        </div>
    </section>

    {{-- ===== LIGHTBOX MODAL ===== --}}
    <div id="lightbox-overlay" class="fixed inset-0 z-[9999] hidden items-center justify-center backdrop-blur-sm" style="background:rgba(0,0,0,0.92);" onclick="closeLightbox()">
    <div class="relative max-w-5xl max-h-screen w-full h-full flex items-center justify-center p-4" onclick="event.stopPropagation()">
        <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full rounded-2xl object-contain shadow-2xl transition-opacity duration-300">
        <p id="lightbox-caption" class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white text-sm font-medium rounded-full px-4 py-1.5" style="background:rgba(0,0,0,0.6);"></p>
        <button id="lightbox-prev" onclick="lightboxSlide(event, -1)" class="absolute left-4 top-1/2 -translate-y-1/2 h-12 w-12 rounded-full text-white flex items-center justify-center transition-all hidden" style="background:rgba(255,255,255,0.1);"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg></button>
        <button id="lightbox-next" onclick="lightboxSlide(event, 1)" class="absolute right-4 top-1/2 -translate-y-1/2 h-12 w-12 rounded-full text-white flex items-center justify-center transition-all hidden" style="background:rgba(255,255,255,0.1);"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg></button>
        <button onclick="closeLightbox()" class="absolute top-4 right-4 h-10 w-10 rounded-full text-white flex items-center justify-center transition-all" style="background:rgba(255,255,255,0.1);"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
</div>

<style>
@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes blob1 {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33%  { transform: translate(60px, 40px) scale(1.1); }
    66%  { transform: translate(-30px, 80px) scale(0.95); }
}
@keyframes blob2 {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33%  { transform: translate(-80px, -40px) scale(1.1); }
    66%  { transform: translate(40px, -60px) scale(0.95); }
}
@keyframes blob3 {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50%  { transform: translate(-60px, 30px) scale(1.15); }
}
html { scroll-behavior: smooth; }
#sticky-nav.scrolled {
    background: rgba(15, 23, 42, 0.95) !important;
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255,255,255,0.06);
    box-shadow: 0 4px 30px rgba(0,0,0,0.3);
}
#sticky-nav.scrolled .nav-title { opacity: 1 !important; }
</style>

<script>
// ---- Sticky Navbar ----
window.addEventListener('scroll', function() {
    const nav = document.getElementById('sticky-nav');
    if (window.scrollY > 80) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});

// ---- Theme Toggle ----
(function() {
    const toggle = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');

    if (localStorage.getItem('color-theme') === 'dark' || (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        lightIcon && lightIcon.classList.remove('hidden');
    } else {
        document.documentElement.classList.remove('dark');
        darkIcon && darkIcon.classList.remove('hidden');
    }

    if (toggle) {
        toggle.addEventListener('click', function() {
            darkIcon && darkIcon.classList.toggle('hidden');
            lightIcon && lightIcon.classList.toggle('hidden');
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });
    }
})();

// ---- Smooth scroll for anchor links ----
document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            const navH = document.getElementById('sticky-nav').offsetHeight;
            const top = target.getBoundingClientRect().top + window.scrollY - navH - 16;
            window.scrollTo({ top, behavior: 'smooth' });
        }
    });
});

// ---- Carousel Logic ----
function carouselSlide(e, typeId, dir) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    const container = document.getElementById('carousel-' + typeId);
    if (!container) return;
    const track = container.querySelector('.carousel-track');
    const dots = container.querySelectorAll('.carousel-dot');
    const total = track.children.length;
    let current = parseInt(track.dataset.current || '0');
    current = (current + dir + total) % total;
    track.dataset.current = current;
    track.style.transform = 'translateX(-' + (current * 100) + '%)';
    dots.forEach((d, i) => {
        d.classList.toggle('w-4', i === current);
        d.classList.toggle('bg-white', i === current);
        d.classList.toggle('w-1.5', i !== current);
        d.classList.toggle('bg-white/50', i !== current);
    });
}
function carouselGoTo(e, typeId, index) {
    e.preventDefault(); e.stopPropagation();
    const container = document.getElementById('carousel-' + typeId);
    if (!container) return;
    const track = container.querySelector('.carousel-track');
    const dots = container.querySelectorAll('.carousel-dot');
    track.dataset.current = index;
    track.style.transform = 'translateX(-' + (index * 100) + '%)';
    dots.forEach((d, i) => {
        d.classList.toggle('w-4', i === index);
        d.classList.toggle('bg-white', i === index);
        d.classList.toggle('w-1.5', i !== index);
        d.classList.toggle('bg-white/50', i !== index);
    });
}

// ---- Lightbox Logic ----
let lightboxImages = [];
let lightboxCurrentIndex = 0;
let lightboxCaptionText = '';

function openLightbox(e, typeId, index, caption) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    const container = document.getElementById('carousel-' + typeId);
    if (!container) return;
    lightboxImages = JSON.parse(container.getAttribute('data-images') || '[]');
    lightboxCurrentIndex = index;
    lightboxCaptionText = caption;
    updateLightboxImage();
    const overlay = document.getElementById('lightbox-overlay');
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    document.body.style.overflow = 'hidden';
    document.getElementById('lightbox-prev').classList.toggle('hidden', lightboxImages.length <= 1);
    document.getElementById('lightbox-next').classList.toggle('hidden', lightboxImages.length <= 1);
}
function updateLightboxImage() {
    if (lightboxImages.length === 0) return;
    document.getElementById('lightbox-img').src = lightboxImages[lightboxCurrentIndex];
    const counter = lightboxImages.length > 1 ? ` (${lightboxCurrentIndex + 1}/${lightboxImages.length})` : '';
    document.getElementById('lightbox-caption').textContent = lightboxCaptionText + counter;
}
function lightboxSlide(e, dir) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    if (lightboxImages.length <= 1) return;
    lightboxCurrentIndex = (lightboxCurrentIndex + dir + lightboxImages.length) % lightboxImages.length;
    updateLightboxImage();
}
function closeLightbox() {
    const overlay = document.getElementById('lightbox-overlay');
    overlay.classList.add('hidden');
    overlay.classList.remove('flex');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') lightboxSlide(null, 1);
    if (e.key === 'ArrowLeft') lightboxSlide(null, -1);
});

// ---- Touch/Swipe ----
function initSwipeGestures() {
    const lightboxImg = document.getElementById('lightbox-img');
    let touchstartX = 0;
    lightboxImg.addEventListener('touchstart', e => { touchstartX = e.changedTouches[0].screenX; }, {passive: true});
    lightboxImg.addEventListener('touchend', e => {
        const dx = e.changedTouches[0].screenX - touchstartX;
        if (Math.abs(dx) > 50) lightboxSlide(null, dx < 0 ? 1 : -1);
    }, {passive: true});

    document.querySelectorAll('.carousel-track').forEach(track => {
        let cStartX = 0;
        const parentId = track.closest('[id^="carousel-"]')?.id.replace('carousel-', '');
        track.addEventListener('touchstart', e => { cStartX = e.changedTouches[0].screenX; }, {passive: true});
        track.addEventListener('touchend', e => {
            const dx = e.changedTouches[0].screenX - cStartX;
            if (Math.abs(dx) > 50) carouselSlide(null, parentId, dx < 0 ? 1 : -1);
        }, {passive: true});
    });
}
document.addEventListener('DOMContentLoaded', initSwipeGestures);
</script>

</div>{{-- END single Livewire root --}}
