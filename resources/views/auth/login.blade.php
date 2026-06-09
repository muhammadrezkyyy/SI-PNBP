<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login Admin — SI-RESERVASI PNBP">
    <title>Login Admin — {{ \App\Models\AppSetting::getVal('app_name', 'SI-RESERVASI PNBP') }}</title>
    @php $faviconLogo = \App\Models\AppSetting::getVal('app_logo_path'); @endphp
    @if($faviconLogo && file_exists(storage_path('app/public/logos/favicon.png')))
        <link rel="icon" type="image/png" href="{{ asset('storage/logos/favicon.png') . '?v=' . filemtime(storage_path('app/public/logos/favicon.png')) }}">
    @elseif($faviconLogo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $faviconLogo) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js for interactive UI elements -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @php
        $primaryColor = \App\Models\AppSetting::getVal('primary_color', '#3b82f6');
        $appLogo = \App\Models\AppSetting::getVal('app_logo_path');
        $appName = \App\Models\AppSetting::getVal('app_name', 'SI-RESERVASI PNBP');
        $copyrightText = \App\Models\AppSetting::getVal('copyright_text', '© ' . date('Y') . ' ' . $appName);
        
        $hex = str_replace('#', '', $primaryColor);
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $primaryRgb = "$r, $g, $b";
    @endphp

    <style>
        [x-cloak] { display: none !important; }
        :root {
            --primary: {{ $primaryColor }};
            --primary-rgb: {{ $primaryRgb }};
        }
        /* ---------- Animasi & Efek ---------- */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-12px) rotate(1deg); }
            66% { transform: translateY(6px) rotate(-1deg); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 0.7; }
            50% { transform: scale(1.05); opacity: 0.3; }
            100% { transform: scale(0.9); opacity: 0.7; }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #0f172a 50%, rgba(var(--primary-rgb), 0.15) 75%, #0f172a 100%);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.05),
                0 20px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 80px rgba(59, 130, 246, 0.05);
        }

        .float-orb {
            animation: float 8s ease-in-out infinite;
            filter: blur(60px);
        }
        .float-orb-2 {
            animation: float 10s ease-in-out infinite reverse;
            filter: blur(70px);
        }
        .float-orb-3 {
            animation: float 12s ease-in-out 2s infinite;
            filter: blur(50px);
        }

        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .fade-in-up-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .fade-in-up-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .fade-in-up-delay-3 { animation-delay: 0.3s; opacity: 0; }
        .fade-in-up-delay-4 { animation-delay: 0.4s; opacity: 0; }

        .login-input {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f1f5f9;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .login-input::placeholder {
            color: rgba(148, 163, 184, 0.6);
        }
        .login-input:focus {
            outline: none;
            border-color: rgba(var(--primary-rgb), 0.6);
            background: rgba(255, 255, 255, 0.07);
            box-shadow:
                0 0 0 3px rgba(var(--primary-rgb), 0.15),
                0 0 20px rgba(var(--primary-rgb), 0.1);
        }
        .login-input:hover:not(:focus) {
            border-color: rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.06);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, rgba(var(--primary-rgb), 0.7) 100%);
            background-size: 200% 200%;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            background-size: 200% 100%;
            animation: shimmer 3s infinite;
        }
        .btn-login:hover {
            background-position: 100% 0;
            transform: translateY(-1px);
            box-shadow:
                0 10px 30px -5px rgba(var(--primary-rgb), 0.4),
                0 0 20px rgba(var(--primary-rgb), 0.2);
        }
        .btn-login:active {
            transform: translateY(0);
        }

        .logo-glow {
            animation: pulse-ring 3s ease-in-out infinite;
        }

        .input-icon {
            color: rgba(148, 163, 184, 0.5);
            transition: color 0.3s ease;
        }
        .input-group:focus-within .input-icon {
            color: rgba(var(--primary-rgb), 0.8);
        }

        /* Grid pattern overlay */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
        }
    </style>
</head>

<body class="h-full font-[Inter] antialiased login-bg overflow-hidden relative">
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 grid-pattern"></div>

    <!-- Floating Orbs Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="float-orb absolute -top-32 -left-32 w-96 h-96 rounded-full" style="background-color: rgba(var(--primary-rgb), 0.15)"></div>
        <div class="float-orb-2 absolute top-1/3 -right-20 w-80 h-80 rounded-full" style="background-color: rgba(var(--primary-rgb), 0.1)"></div>
        <div class="float-orb-3 absolute -bottom-20 left-1/3 w-72 h-72 rounded-full" style="background-color: rgba(var(--primary-rgb), 0.12)"></div>
        <div class="float-orb absolute top-1/2 left-1/4 w-40 h-40 rounded-full bg-slate-500/10" style="animation-delay: 3s;"></div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 flex min-h-full items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">

            <!-- Logo & Branding -->
            <div class="text-center mb-8 fade-in-up fade-in-up-delay-1">
                <div class="inline-flex items-center justify-center relative mb-5">
                    @if($appLogo)
                        <div class="absolute inset-0 w-24 h-24 rounded-3xl logo-glow" style="background: rgba(var(--primary-rgb), 0.2)"></div>
                        <div class="relative flex h-24 w-24 items-center justify-center rounded-3xl bg-white shadow-2xl overflow-hidden" style="box-shadow: 0 10px 25px -5px rgba(var(--primary-rgb), 0.3)">
                            <img src="{{ asset('storage/' . $appLogo) . '?v=' . (file_exists(storage_path('app/public/' . $appLogo)) ? filemtime(storage_path('app/public/' . $appLogo)) : time()) }}" alt="Logo" class="w-full h-full object-contain p-2">
                        </div>
                    @else
                        <div class="absolute inset-0 w-20 h-20 rounded-2xl logo-glow" style="background: rgba(var(--primary-rgb), 0.3)"></div>
                        <div class="relative flex h-20 w-20 items-center justify-center rounded-2xl shadow-2xl" style="background: linear-gradient(135deg, var(--primary), rgba(var(--primary-rgb), 0.8)); box-shadow: 0 10px 25px -5px rgba(var(--primary-rgb), 0.4)">
                            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">{{ $appName }}</h1>
                <p class="mt-1.5 text-sm text-slate-400">Panel Administrasi</p>
            </div>

            <!-- Login Card -->
            <div class="glass-card rounded-3xl p-8 fade-in-up fade-in-up-delay-2">

                <!-- Header -->
                <div class="mb-7">
                    <h2 class="text-xl font-semibold text-white">Masuk ke Akun Admin</h2>
                    <p class="mt-1 text-sm text-slate-400">Silakan masukkan kredensial Anda untuk melanjutkan.</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-500/20 bg-red-500/10 p-4 fade-in-up">
                        <svg class="h-5 w-5 flex-shrink-0 text-red-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-300">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5" id="admin-login-form">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-slate-300">
                            Alamat Email
                        </label>
                        <div class="input-group relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="h-5 w-5 input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="admin@sipnbp.go.id"
                                class="login-input block w-full rounded-xl py-3.5 pl-12 pr-4 text-sm font-medium"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-slate-300">
                            Kata Sandi
                        </label>
                        <div class="input-group relative" x-data="{ showPassword: false }">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="h-5 w-5 input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                id="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="login-input block w-full rounded-xl py-3.5 pl-12 pr-12 text-sm font-medium"
                            >
                            <!-- Toggle visibility -->
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 dark:text-slate-400 hover:text-slate-300 transition-colors">
                                <!-- Eye open -->
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <!-- Eye closed -->
                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2.5 cursor-pointer group" for="remember">
                            <input type="checkbox" name="remember" id="remember"
                                   class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-blue-500 focus:ring-blue-500/30 focus:ring-offset-0 transition-colors cursor-pointer">
                            <span class="text-sm text-slate-400 group-hover:text-slate-300 transition-colors select-none">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="btn-login"
                            class="btn-login relative flex w-full items-center justify-center gap-2 rounded-xl py-3.5 text-sm font-semibold text-white shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-300">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Masuk ke Dashboard</span>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center fade-in-up fade-in-up-delay-4">
                <div class="flex items-center justify-center gap-2 text-slate-500 dark:text-slate-400 text-xs">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Dilindungi enkripsi SSL &bull; Akses khusus Administrator</span>
                </div>
                <p class="mt-3 text-slate-600 dark:text-slate-300 text-xs">{{ $copyrightText }}</p>
            </div>

        </div>
    </div>
</body>
</html>
