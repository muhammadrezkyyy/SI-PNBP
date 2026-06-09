<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SI-RESERVASI PNBP — Sistem Reservasi Gedung Internal Berbasis PNBP Kementerian">
    <title>@yield('title', 'SI-RESERVASI PNBP')</title>
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
    @livewireStyles
    <script>
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
<body class="h-full font-inter antialiased bg-slate-50 dark:bg-slate-900/50 dark:bg-slate-900 transition-colors">
    {{ $slot }}
    @livewireScripts
    @stack('scripts')
    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        if(themeToggleDarkIcon && themeToggleLightIcon) {
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            var themeToggleBtn = document.getElementById('theme-toggle');
            if(themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    themeToggleDarkIcon.classList.toggle('hidden');
                    themeToggleLightIcon.classList.toggle('hidden');
                    if (localStorage.getItem('theme')) {
                        if (localStorage.getItem('theme') === 'light') {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('theme', 'dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('theme', 'light');
                        }
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
            }
        }
    </script>
</body>
</html>
