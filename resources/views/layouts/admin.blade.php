<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SI-RESERVASI PNBP — Panel Admin">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — SI-RESERVASI PNBP')</title>
    @php
        $primaryColor = \App\Models\AppSetting::getVal('primary_color', '#0e1f40');
        $appLogo = \App\Models\AppSetting::getVal('app_logo_path');
    @endphp
    @if($appLogo && file_exists(storage_path('app/public/logos/favicon.png')))
        <link rel="icon" type="image/png" href="{{ asset('storage/logos/favicon.png') . '?v=' . filemtime(storage_path('app/public/logos/favicon.png')) }}">
    @elseif($appLogo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $appLogo) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireStyles
    <script>
        // Prevent FOUC
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @php
        $primaryColor = \App\Models\AppSetting::getVal('primary_color', '#0e1f40');
    @endphp
    <style>
        :root {
            --color-blue-50: color-mix(in srgb, {{ $primaryColor }} 10%, white);
            --color-blue-100: color-mix(in srgb, {{ $primaryColor }} 20%, white);
            --color-blue-200: color-mix(in srgb, {{ $primaryColor }} 40%, white);
            --color-blue-300: color-mix(in srgb, {{ $primaryColor }} 60%, white);
            --color-blue-400: color-mix(in srgb, {{ $primaryColor }} 80%, white);
            --color-blue-500: color-mix(in srgb, {{ $primaryColor }} 90%, white);
            --color-blue-600: {{ $primaryColor }};
            --color-blue-700: color-mix(in srgb, {{ $primaryColor }} 85%, black);
            --color-blue-800: color-mix(in srgb, {{ $primaryColor }} 70%, black);
            --color-blue-900: color-mix(in srgb, {{ $primaryColor }} 50%, black);
            
            --color-indigo-50: color-mix(in srgb, {{ $primaryColor }} 5%, white);
            --color-indigo-100: color-mix(in srgb, {{ $primaryColor }} 15%, white);
            --color-indigo-500: color-mix(in srgb, {{ $primaryColor }} 85%, white);
            --color-indigo-600: {{ $primaryColor }};
            --color-indigo-700: color-mix(in srgb, {{ $primaryColor }} 80%, black);
        }
    </style>
</head>
<body class="h-full bg-slate-100 dark:bg-slate-800/80 dark:bg-slate-900 font-inter antialiased transition-colors" x-data="{ sidebarOpen: false }">

{{-- Mobile sidebar overlay --}}
<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-slate-900/70 backdrop-blur-sm lg:hidden"></div>

{{-- Sidebar --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 w-72 transform bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transition-transform duration-300 ease-in-out lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex h-16 items-center justify-between px-6 border-b border-slate-200 dark:border-slate-700/50">
        @php
            $appName = \App\Models\AppSetting::getVal('app_name', 'SI-RESERVASI');
            $appLogo = \App\Models\AppSetting::getVal('app_logo_path');
        @endphp
        <div class="flex items-center gap-3 overflow-hidden">
            @if($appLogo)
                <img src="{{ asset('storage/' . $appLogo) }}" class="h-9 w-9 object-cover rounded-xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700">
            @else
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            @endif
            <div class="truncate">
                <p class="text-sm font-bold text-slate-800 dark:text-white leading-none truncate">{{ $appName }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">PNBP Admin</p>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="mt-4 px-4 space-y-1 pb-24 overflow-y-auto max-h-[calc(100vh-4rem)]">
        @php
            $navItems = \App\Models\AdminMenu::where('is_active', true)->orderBy('order')->get();
            $currentGroup = null;
        @endphp

        @foreach($navItems as $item)
            @if(\Route::has($item->route))
                @if($item->group && $item->group !== $currentGroup)
                    <div class="px-3 pt-5 pb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ $item->group }}</span>
                    </div>
                    @php $currentGroup = $item->group; @endphp
                @elseif(!$item->group && $currentGroup !== null && $currentGroup !== '')
                    @php $currentGroup = ''; @endphp
                    <div class="my-2 border-t border-slate-200 dark:border-slate-700/50"></div>
                @endif
                <a href="{{ route($item->route) }}"
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs($item->route . '*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-800 dark:hover:text-white' }}">
                    <svg class="h-5 w-5 flex-shrink-0 {{ request()->routeIs($item->route . '*') ? 'text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item->icon }}"/>
                    </svg>
                    {{ $item->label }}
                </a>
            @endif
        @endforeach
    </nav>

    {{-- User footer --}}
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-200 dark:border-slate-700/50">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-sm font-bold cursor-pointer" onclick="Livewire.dispatch('openProfileModal')" title="Pengaturan Akun">
                {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0 cursor-pointer rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors p-1" onclick="Livewire.dispatch('openProfileModal')" title="Pengaturan Akun">
                <p class="text-sm font-medium text-slate-800 dark:text-white truncate">{{ auth()->user()?->name }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()?->email }}</p>
            </div>
            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                @csrf
            </form>
            <button type="button" onclick="confirmLogout()" class="text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors" title="Logout">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </button>
        </div>
    </div>
</aside>

{{-- Main content --}}
<div class="flex flex-col lg:flex-row min-h-screen">
    {{-- Sidebar spacer for desktop --}}
    <div class="hidden lg:block w-72 flex-shrink-0"></div>

    <div class="flex-1 flex flex-col">
        {{-- Top bar --}}
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between bg-white dark:bg-slate-800/80 dark:bg-slate-900/80 backdrop-blur border-b border-slate-200 dark:border-slate-700 dark:border-slate-800 px-4 lg:px-8">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:bg-slate-800/80 dark:hover:bg-slate-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex-1 lg:flex-none">
                <h1 class="text-base font-semibold text-slate-800 dark:text-slate-100 dark:text-slate-100">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                <livewire:admin.notification-bell />
                <button id="theme-toggle" type="button" class="text-slate-500 dark:text-amber-400 hover:bg-slate-100 dark:bg-slate-800/80 dark:hover:bg-slate-700 border border-transparent dark:border-slate-700/50 focus:outline-none rounded-lg text-sm p-2.5 transition-all">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>
                {{-- Live Clock --}}
                <div id="live-clock" class="hidden sm:flex items-center gap-2 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/50 px-3 py-1.5">
                    <svg class="h-4 w-4 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-xs">
                        <span id="clock-date" class="font-medium text-slate-600 dark:text-slate-300"></span>
                        <span class="text-slate-300 dark:text-slate-600 mx-0.5">|</span>
                        <span id="clock-time" class="font-bold text-slate-800 dark:text-slate-100 tabular-nums"></span>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-4 lg:p-8">
            @if(session('success'))
                <div class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800" x-data x-init="setTimeout(() => $el.remove(), 5000)">
                    <svg class="h-5 w-5 flex-shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    <svg class="h-5 w-5 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>
    </div>
</div>

@livewireScripts
@stack('scripts')
<script>
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Change the icons inside the button based on previous settings
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleLightIcon.classList.remove('hidden');
    } else {
        themeToggleDarkIcon.classList.remove('hidden');
    }

    var themeToggleBtn = document.getElementById('theme-toggle');

    themeToggleBtn.addEventListener('click', function() {
        // toggle icons inside button
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        // if set via local storage previously
        if (localStorage.getItem('theme')) {
            if (localStorage.getItem('theme') === 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        // if NOT set via local storage previously
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    });

    // Live Clock
    function updateClock() {
        const now = new Date();
        const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        const dateStr = hari[now.getDay()] + ', ' + now.getDate() + ' ' + bulan[now.getMonth()] + ' ' + now.getFullYear();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
        document.getElementById('clock-date').textContent = dateStr;
        document.getElementById('clock-time').textContent = timeStr;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // SweetAlert Logout
    function confirmLogout() {
        Swal.fire({
            title: 'Keluar dari Sistem?',
            text: 'Sesi Anda akan berakhir dan Anda perlu login kembali.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa fa-sign-out-alt"></i> Ya, Logout',
            cancelButtonText: 'Batal',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-6 py-2.5 text-sm font-semibold',
                cancelButton: 'rounded-xl px-6 py-2.5 text-sm font-semibold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>

<livewire:admin.profile-manager />
</body>
</html>
