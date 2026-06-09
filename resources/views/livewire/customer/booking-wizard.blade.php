<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 px-4 transition-colors">
    {{-- Theme Toggle --}}
    <div class="absolute top-4 right-4 z-[999]">
        <button id="theme-toggle" type="button" class="text-slate-500 dark:text-amber-400 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md shadow-sm border border-slate-200/50 dark:border-slate-700/50 hover:bg-white dark:hover:bg-slate-700 focus:outline-none rounded-full text-sm p-2.5 transition-all">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
        </button>
    </div>

    <div class="max-w-2xl mx-auto mt-4">

        @php
            $appName = \App\Models\AppSetting::getVal('app_name', 'SI-RESERVASI PNBP');
            $appLogo = \App\Models\AppSetting::getVal('app_logo_path');
        @endphp

        {{-- Logo Header --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-3 mb-4">
                @if($appLogo)
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-xl shadow-blue-500/30 overflow-hidden border border-slate-100 dark:border-slate-700">
                        <img src="{{ asset('storage/' . $appLogo) }}" class="h-full w-full object-cover">
                    </div>
                @else
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 shadow-xl shadow-blue-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif
                <div class="text-left">
                    <p class="font-bold text-slate-800 dark:text-white text-lg leading-none">{{ $appName }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Sistem Reservasi Internal</p>
                </div>
            </div>
        </div>

        {{-- Success State --}}
        @if($booking_success)
        <div class="rounded-3xl border border-green-200 bg-white dark:bg-slate-800 shadow-2xl shadow-green-500/10 p-8 text-center">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-green-100">
                <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-2">Reservasi Berhasil!</h2>
            <p class="text-slate-500 dark:text-slate-400 mb-6">Permintaan reservasi Anda telah kami terima. Admin akan segera menyiapkan tagihan SIMPONI dan menghubungi Anda via WhatsApp.</p>
            <div class="rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 p-4 mb-6">
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">ID Reservasi</p>
                <p class="font-mono text-sm font-bold text-blue-700 break-all">{{ $reservation_id }}</p>
            </div>
            <a href="{{ route('booking') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all">
                Buat Reservasi Baru
            </a>
        </div>
        @else

        {{-- Step Progress Bar --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                @foreach([1 => 'Jadwal', 2 => 'Data Diri', 3 => 'Konfirmasi'] as $step => $label)
                <div class="flex flex-col items-center gap-2 flex-1">
                    <div class="relative flex items-center w-full">
                        @if($step > 1)
                        <div class="flex-1 h-0.5 {{ $current_step >= $step ? 'bg-blue-600' : 'bg-slate-200' }} transition-colors duration-300"></div>
                        @endif
                        <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 transition-all duration-300 flex-shrink-0 mx-auto
                                    {{ $current_step > $step ? 'border-blue-600 bg-blue-600' :
                                       ($current_step == $step ? 'border-blue-600 bg-white dark:bg-slate-800' : 'border-slate-300 bg-white dark:bg-slate-800') }}">
                            @if($current_step > $step)
                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <span class="text-sm font-bold {{ $current_step == $step ? 'text-blue-600' : 'text-slate-400' }}">{{ $step }}</span>
                            @endif
                        </div>
                        @if($step < 3)
                        <div class="flex-1 h-0.5 {{ $current_step > $step ? 'bg-blue-600' : 'bg-slate-200' }} transition-colors duration-300"></div>
                        @endif
                    </div>
                    <span class="text-[10px] sm:text-xs font-medium {{ $current_step == $step ? 'text-blue-700' : 'text-slate-400' }}">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Card --}}
        <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-2xl shadow-slate-900/5 dark:shadow-none overflow-hidden">

            {{-- Conflict Error --}}
            @if($conflict_error)
            <div class="border-b border-red-100 bg-red-50 px-6 py-4 flex items-start gap-3">
                <svg class="h-5 w-5 flex-shrink-0 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-sm font-semibold text-red-800">Jadwal Tidak Tersedia</p>
                    <p class="text-sm text-red-600 mt-0.5">{{ $conflict_error }}</p>
                </div>
            </div>
            @endif

            {{-- ===== STEP 1: SCHEDULE ===== --}}
            @if($current_step === 1)
            <div class="p-4 sm:p-8">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-1">Pilih Fasilitas & Jadwal</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-7">Pilih kategori fasilitas lalu tentukan tanggal reservasi.</p>

                {{-- Date Range (dipindah ke atas agar user tahu dulu ketersediaan) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700/50">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" wire:model.live="start_date"
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-800 dark:text-slate-100 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/30 transition-all">
                        @error('start_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" wire:model.live="end_date"
                               min="{{ $start_date ?: now()->addDay()->format('Y-m-d') }}"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-800 dark:text-slate-100 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/30 transition-all">
                        @error('end_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Facility Type Selection (Category Cards with Carousel) --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">
                        Kategori Fasilitas <span class="text-red-500">*</span>
                        <span class="font-normal text-slate-400 text-xs ml-1">({{ count($facilityTypes) }} tersedia)</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($facilityTypes as $type)
                        @php $typeImages = $type->all_image_paths; @endphp
                        <label class="relative flex flex-col cursor-pointer rounded-2xl border-2 overflow-hidden transition-all duration-200 group
                                      {{ $facility_type_id === (string)$type->id ? 'border-blue-500 ring-2 ring-blue-300/50 dark:ring-blue-500/30' : 'border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-500/50' }}">
                            <input type="radio" wire:model.live="facility_type_id" value="{{ $type->id }}" class="sr-only">

                            {{-- Image Carousel --}}
                            <div class="relative h-40 w-full bg-slate-100 dark:bg-slate-700 overflow-hidden" id="carousel-{{ $type->id }}" data-images="{{ json_encode(array_map(function($p){return asset('storage/'.$p);}, $typeImages)) }}">
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

                                    {{-- Prev/Next Arrows (only if multiple images) --}}
                                    @if(count($typeImages) > 1)
                                    <button type="button"
                                            class="carousel-prev absolute left-1.5 top-1/2 -translate-y-1/2 h-7 w-7 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition-all opacity-0 group-hover:opacity-100"
                                            onclick="carouselSlide(event, '{{ $type->id }}', -1)">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <button type="button"
                                            class="carousel-next absolute right-1.5 top-1/2 -translate-y-1/2 h-7 w-7 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition-all opacity-0 group-hover:opacity-100"
                                            onclick="carouselSlide(event, '{{ $type->id }}', 1)">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                    {{-- Dots --}}
                                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1 carousel-dots">
                                        @foreach($typeImages as $i => $imgPath)
                                        <button type="button" onclick="carouselGoTo(event, '{{ $type->id }}', {{ $i }})"
                                                class="carousel-dot h-1.5 rounded-full transition-all {{ $i === 0 ? 'w-4 bg-white' : 'w-1.5 bg-white/50' }}"></button>
                                        @endforeach
                                    </div>
                                    {{-- Photo count --}}
                                    <div class="absolute top-2 right-2">
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-black/50 backdrop-blur-sm px-1.5 py-0.5 text-[10px] font-bold text-white">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ count($typeImages) }}
                                        </span>
                                    </div>
                                    @endif

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent pointer-events-none"></div>
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                                        <svg class="h-8 w-8 text-slate-300 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-xs text-slate-400">Foto belum tersedia</p>
                                    </div>
                                @endif

                                {{-- Selected checkmark --}}
                                @if($facility_type_id === (string)$type->id)
                                <div class="absolute top-2 left-2 h-6 w-6 rounded-full bg-blue-600 border-2 border-white flex items-center justify-center shadow">
                                    <svg class="h-3.5 w-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </div>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div class="p-3.5 flex-1 flex flex-col {{ $facility_type_id === (string)$type->id ? 'bg-blue-50 dark:bg-blue-900/20' : 'bg-white dark:bg-slate-800/50' }}">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="font-bold text-slate-800 dark:text-slate-100 text-sm leading-snug">{{ $type->name }}</p>
                                    <span class="flex-shrink-0 text-xs font-bold text-blue-700 dark:text-blue-400">
                                        {{ $type->daily_rate_formatted }}<span class="font-normal text-slate-400 text-[10px]">/hari</span>
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2 flex-1">{{ $type->description }}</p>
                                <p class="text-[10px] text-slate-400 mt-1.5">
                                    {{ $type->active_buildings_count }} unit tersedia
                                    @if(count($typeImages) > 0)
                                    · <span class="text-blue-500 cursor-pointer" onclick="openLightbox('{{ $type->id }}', 0, '{{ addslashes($type->name) }}')">Lihat foto ↗</span>
                                    @endif
                                </p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('facility_type_id')<p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Unit Selection (shows after category picked) --}}
                @if($facility_type_id)
                <div class="mb-6 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-700/50">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">
                        Pilih Unit / Ruangan <span class="text-red-500">*</span>
                    </label>

                    @if(count($buildings) > 0)
                    <div class="space-y-2">
                        @foreach($buildings as $building)
                        @if($building->is_booked)
                            {{-- BOOKED: Disabled card --}}
                            <div class="flex items-center justify-between rounded-xl border-2 border-red-100 dark:border-red-900/40 bg-red-50/60 dark:bg-red-900/10 p-3 opacity-75 cursor-not-allowed">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="h-4 w-4 flex-shrink-0 rounded-full border-2 border-red-300 bg-red-100 dark:border-red-700 dark:bg-red-900/40"></div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-600 dark:text-slate-400 text-sm line-through decoration-red-400">{{ $building->name }}</p>
                                        <p class="text-xs text-red-500 dark:text-red-400 mt-0.5 flex items-center gap-1">
                                            <svg class="h-3 w-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            {{ $building->status_message }}
                                        </p>
                                    </div>
                                </div>
                                <span class="flex-shrink-0 ml-2 inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/50 px-2 py-0.5 text-[10px] font-bold text-red-700 dark:text-red-400 whitespace-nowrap">
                                    {{ $building->status_badge }}
                                </span>
                            </div>
                        @else
                            {{-- AVAILABLE: Selectable card --}}
                            <label class="flex items-center justify-between rounded-xl border-2 p-3 cursor-pointer transition-all duration-150
                                          {{ $building_id === (string)$building->id
                                             ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/30 shadow-sm'
                                             : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-blue-300 dark:hover:border-blue-600 hover:bg-blue-50/30' }}">
                                <input type="radio" wire:model.live="building_id" value="{{ $building->id }}" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div class="h-4 w-4 flex-shrink-0 rounded-full border-2 transition-all {{ $building_id === (string)$building->id ? 'border-blue-600 bg-blue-600' : 'border-slate-300 dark:border-slate-600' }} flex items-center justify-center">
                                        @if($building_id === (string)$building->id)
                                        <div class="h-1.5 w-1.5 rounded-full bg-white"></div>
                                        @endif
                                    </div>
                                    <p class="font-medium text-slate-800 dark:text-slate-100 text-sm">{{ $building->name }}</p>
                                </div>
                                @if($building_id === (string)$building->id)
                                <span class="flex-shrink-0 inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/50 px-2 py-0.5 text-[10px] font-bold text-blue-700 dark:text-blue-400">
                                    ✓ Dipilih
                                </span>
                                @else
                                <span class="flex-shrink-0 inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-2 py-0.5 text-[10px] font-bold text-green-700 dark:text-green-400">
                                    Tersedia
                                </span>
                                @endif
                            </label>
                        @endif
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 text-center">
                        Tidak ada unit yang tersedia untuk kategori ini.
                    </p>
                    @endif
                    @error('building_id')<p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
                </div>
                @endif

                {{-- Cost Estimate --}}
                @if($selectedType && $building_id && $start_date && $end_date)
                <div class="rounded-2xl border border-blue-200 dark:border-blue-900/50 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-1">Estimasi Biaya</p>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ $selectedType->daily_rate_formatted }} × {{ $durationDays }} hari
                            </p>
                        </div>
                        <p class="text-2xl font-black text-blue-700 dark:text-blue-400">Rp {{ number_format($estimatedTotal, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- ===== STEP 2: DYNAMIC FORM ===== --}}
            @elseif($current_step === 2)
            <div class="p-4 sm:p-8">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-1">Data Pemohon</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-7">Lengkapi formulir berikut sesuai data diri Anda.</p>

                <div class="space-y-5">
                    @foreach($fields as $field)
                    <div>
                        <label for="field_{{ $field->field_name }}" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                            {{ $field->field_label }}
                            @if($field->is_required)<span class="text-red-500">*</span>@endif
                        </label>

                        @if($field->field_type === 'textarea')
                        <textarea id="field_{{ $field->field_name }}"
                                  wire:model="customer_data.{{ $field->field_name }}"
                                  placeholder="{{ $field->placeholder }}"
                                  rows="3"
                                  class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/30 transition-all resize-none"></textarea>
                        @else
                        <input type="{{ $field->field_type }}"
                               id="field_{{ $field->field_name }}"
                               wire:model="customer_data.{{ $field->field_name }}"
                               placeholder="{{ $field->placeholder }}"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/30 transition-all">
                        @endif
                        @error("customer_data.{$field->field_name}")
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    @endforeach

                    @if($fields->isEmpty())
                    <div class="rounded-xl border border-orange-100 bg-orange-50 p-4 text-sm text-orange-700">
                        Admin belum mengkonfigurasi kolom formulir. Silakan hubungi admin.
                    </div>
                    @endif
                </div>
            </div>

            {{-- ===== STEP 3: CONFIRMATION ===== --}}
            @elseif($current_step === 3)
            <div class="p-4 sm:p-8">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-1">Konfirmasi Reservasi</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-7">Periksa kembali data reservasi Anda sebelum mengirimkan.</p>

                {{-- Summary Card --}}
                <div class="rounded-2xl border border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-900/50 divide-y divide-slate-100 dark:divide-slate-700/50 mb-6 overflow-hidden">
                    <div class="p-4">
                        <p class="text-xs text-slate-400 uppercase font-semibold tracking-wide mb-1">Kategori Fasilitas</p>
                        <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $selectedType?->name ?? '—' }}</p>
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-slate-400 uppercase font-semibold tracking-wide mb-1">Unit / Ruangan</p>
                        <p class="font-semibold text-slate-800 dark:text-slate-100">
                            {{ collect($buildings)->firstWhere('id', $building_id)?->name ?? '—' }}
                        </p>
                    </div>
                    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-semibold tracking-wide mb-1">Tanggal Mulai</p>
                            <p class="font-semibold text-slate-800 dark:text-slate-100">{{ \Carbon\Carbon::parse($start_date)->isoFormat('D MMM YYYY') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-semibold tracking-wide mb-1">Tanggal Selesai</p>
                            <p class="font-semibold text-slate-800 dark:text-slate-100">{{ \Carbon\Carbon::parse($end_date)->isoFormat('D MMM YYYY') }}</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-slate-400 uppercase font-semibold tracking-wide mb-1">Durasi</p>
                        <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $durationDays }} hari</p>
                    </div>
                    @foreach($fields as $field)
                    @if(!empty($customer_data[$field->field_name]))
                    <div class="p-4">
                        <p class="text-xs text-slate-400 uppercase font-semibold tracking-wide mb-1">{{ $field->field_label }}</p>
                        <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $customer_data[$field->field_name] }}</p>
                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- Total --}}
                <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 p-5 text-white mb-6">
                    <p class="text-sm font-medium text-blue-200">Estimasi Total PNBP</p>
                    <p class="text-3xl font-bold mt-1">Rp {{ number_format($estimatedTotal, 0, ',', '.') }}</p>
                    <p class="text-xs text-blue-300 mt-1">*Nominal resmi sesuai tagihan SIMPONI dari Admin</p>
                </div>

                <div class="rounded-xl border border-amber-100 bg-amber-50 p-4 text-sm text-amber-800 mb-6">
                    <p class="font-semibold mb-1">⚠️ Perhatikan</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Reservasi akan terkunci selama 24 jam menunggu tagihan dari Admin.</li>
                        <li>Setelah tagihan diterima, Anda memiliki 72 jam untuk melakukan pembayaran.</li>
                        <li>Reservasi yang melewati batas waktu akan otomatis dibatalkan.</li>
                    </ul>
                </div>
            </div>
            @endif

            {{-- Navigation Buttons --}}
            <div class="border-t border-slate-100 dark:border-slate-700/50 px-4 sm:px-8 py-4 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
                <button @if($current_step > 1) wire:click="prevStep" @endif
                        class="{{ $current_step === 1 ? 'invisible' : '' }} flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </button>

                @if($current_step < $total_steps)
                <button wire:click="nextStep"
                        wire:loading.attr="disabled"
                        wire:target="nextStep"
                        class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-500/30 hover:from-blue-700 hover:to-indigo-700 transition-all disabled:opacity-60">
                    <span wire:loading.remove wire:target="nextStep">Lanjut</span>
                    <span wire:loading wire:target="nextStep">Memproses...</span>
                    <svg wire:loading.remove wire:target="nextStep" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <svg wire:loading wire:target="nextStep" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                </button>
                @else
                <button wire:click="confirmBooking"
                        wire:loading.attr="disabled"
                        wire:target="confirmBooking"
                        class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-green-500/30 hover:from-green-700 hover:to-emerald-700 transition-all disabled:opacity-60">
                    <svg wire:loading.remove wire:target="confirmBooking" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <svg wire:loading wire:target="confirmBooking" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <span wire:loading.remove wire:target="confirmBooking">Kirim Reservasi</span>
                    <span wire:loading wire:target="confirmBooking">Mengunci jadwal...</span>
                </button>
                @endif
            </div>

        </div>
        @endif

        {{-- Footer --}}
        <p class="text-center text-xs text-slate-400 dark:text-slate-500 mt-8">
            @php
                $copyrightText = \App\Models\AppSetting::getVal('copyright_text', '© ' . date('Y') . ' ' . $appName);
                $footerText = \App\Models\AppSetting::getVal('footer_text', 'Sistem Reservasi Gedung Internal.');
                $copyrightWithHiddenLink = str_replace('©', '<a href="' . route('login') . '" class="hover:text-slate-400 dark:hover:text-slate-500" style="text-decoration:none;color:inherit;cursor:text;">©</a>', $copyrightText);
            @endphp
            {!! $copyrightWithHiddenLink !!}. {{ $footerText }}
        </p>

    </div>


{{-- ===== LIGHTBOX MODAL ===== --}}
<div id="lightbox-overlay" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/90 backdrop-blur-sm" onclick="closeLightbox()">
    <div class="relative max-w-5xl max-h-screen w-full h-full flex items-center justify-center p-4" onclick="event.stopPropagation()">
        <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full rounded-2xl object-contain shadow-2xl transition-opacity duration-300">
        <p id="lightbox-caption" class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white text-sm font-medium bg-black/50 rounded-full px-4 py-1.5"></p>
        
        <button id="lightbox-prev" onclick="lightboxSlide(event, -1)" class="absolute left-4 top-1/2 -translate-y-1/2 h-12 w-12 rounded-full bg-black/50 hover:bg-black/70 text-white flex items-center justify-center transition-all hidden">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button id="lightbox-next" onclick="lightboxSlide(event, 1)" class="absolute right-4 top-1/2 -translate-y-1/2 h-12 w-12 rounded-full bg-black/50 hover:bg-black/70 text-white flex items-center justify-center transition-all hidden">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </button>

        <button onclick="closeLightbox()" class="absolute top-4 right-4 h-10 w-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>

<script>
// ---- Carousel Logic ----
function carouselSlide(e, typeId, dir) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
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
    e.preventDefault();
    e.stopPropagation();
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
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
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
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
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

// ---- Swipe Gestures (Touch Support) ----
function initSwipeGestures() {
    // Lightbox swipe
    const lightboxImg = document.getElementById('lightbox-img');
    let touchstartX = 0;
    let touchendX = 0;
    
    lightboxImg.addEventListener('touchstart', e => {
        touchstartX = e.changedTouches[0].screenX;
    }, {passive: true});

    lightboxImg.addEventListener('touchend', e => {
        touchendX = e.changedTouches[0].screenX;
        handleSwipe(lightboxSlide, touchstartX, touchendX);
    }, {passive: true});

    // Carousel swipe
    const carousels = document.querySelectorAll('.carousel-track');
    carousels.forEach(track => {
        let cStartX = 0;
        let cEndX = 0;
        // Find the parent carousel ID to pass to slide function
        const parentId = track.closest('[id^="carousel-"]')?.id.replace('carousel-', '');
        
        track.addEventListener('touchstart', e => {
            cStartX = e.changedTouches[0].screenX;
        }, {passive: true});

        track.addEventListener('touchend', e => {
            cEndX = e.changedTouches[0].screenX;
            handleSwipe((e, dir) => carouselSlide(e, parentId, dir), cStartX, cEndX);
        }, {passive: true});
    });
}

function handleSwipe(actionCallback, startX, endX) {
    const SWIPE_THRESHOLD = 50;
    if (endX < startX - SWIPE_THRESHOLD) {
        actionCallback(null, 1); // Swipe left -> Next
    }
    if (endX > startX + SWIPE_THRESHOLD) {
        actionCallback(null, -1); // Swipe right -> Prev
    }
}

document.addEventListener('DOMContentLoaded', initSwipeGestures);
</script>
</div>
