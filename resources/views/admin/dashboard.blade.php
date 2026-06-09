@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')
@php
    $user = auth()->user();
    
    // JS will handle the dynamic greeting
    $greeting = ''; 

    $totalReservasi = \App\Models\Reservation::count();
    $menungguTagihan = \App\Models\Reservation::where('status', 'PENDING_BILLING')->count();
    $perluDiaudit = \App\Models\Reservation::where('status', 'VERIFYING')->count();
    $dikonfirmasi = \App\Models\Reservation::where('status', 'CONFIRMED')->count();
    $ditolak = \App\Models\Reservation::where('status', 'REJECTED')->count();

    $todayReservasi = \App\Models\Reservation::whereDate('created_at', today())->count();

    $recentReservations = \App\Models\Reservation::with(['user','building'])->latest()->take(8)->get();
@endphp

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
            <span id="dynamic-greeting">Halo</span>, {{ $user->name }}! 👋
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Berikut adalah ringkasan aktivitas reservasi hari ini.</p>
    </div>
</div>

@push('scripts')
<script>
    function updateGreeting() {
        const hour = new Date().getHours();
        let greeting = 'Selamat Pagi';
        
        if (hour >= 18 || hour < 4) {
            greeting = 'Selamat Malam';
        } else if (hour >= 15) {
            greeting = 'Selamat Sore';
        } else if (hour >= 10) {
            greeting = 'Selamat Siang';
        }
        const greetingText = greeting;
        document.getElementById('dynamic-greeting').textContent = greetingText;
        document.querySelectorAll('.dynamic-greeting-text').forEach(el => el.textContent = greetingText);
    }
    
    // Run immediately and then check every minute
    updateGreeting();
    setInterval(updateGreeting, 60000);
</script>
@endpush

<div class="space-y-6">

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 p-6 text-white shadow-lg">
        <div class="absolute inset-0 opacity-10">
            <svg class="absolute -right-10 -top-10 h-64 w-64 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div class="relative">
            <p class="text-blue-100 text-sm font-medium mb-1"><span class="dynamic-greeting-text">Halo</span>, 👋</p>
            <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
            <p class="text-blue-200 text-sm mt-2">Berikut ringkasan aktivitas sistem reservasi Anda hari ini.</p>
        </div>
        {{-- Quick Info --}}
        <div class="relative mt-4 flex flex-wrap gap-3">
            <div class="flex items-center gap-2 rounded-lg bg-white/10 backdrop-blur-sm px-3 py-1.5">
                <svg class="h-4 w-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-xs font-medium text-blue-100">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
            </div>
            <div class="flex items-center gap-2 rounded-lg bg-white/10 backdrop-blur-sm px-3 py-1.5">
                <svg class="h-4 w-4 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                <span class="text-xs font-medium text-blue-100">{{ $todayReservasi }} reservasi baru hari ini</span>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @php
            $stats = [
                ['label' => 'Total Reservasi', 'value' => $totalReservasi, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'blue', 'trend' => null],
                ['label' => 'Menunggu Tagihan', 'value' => $menungguTagihan, 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'yellow', 'trend' => $menungguTagihan > 0 ? 'Perlu tindakan' : null],
                ['label' => 'Perlu Diaudit', 'value' => $perluDiaudit, 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'color' => 'indigo', 'trend' => $perluDiaudit > 0 ? 'Perlu tindakan' : null],
                ['label' => 'Dikonfirmasi', 'value' => $dikonfirmasi, 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'green', 'trend' => null],
                ['label' => 'Ditolak', 'value' => $ditolak, 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'red', 'trend' => null],
            ];
            $colorMap = [
                'blue'   => ['bg' => 'bg-blue-50 dark:bg-blue-900/20',   'icon' => 'text-blue-600 dark:text-blue-400',   'ring' => 'ring-blue-200 dark:ring-blue-800'],
                'yellow' => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'icon' => 'text-amber-600 dark:text-amber-400', 'ring' => 'ring-amber-200 dark:ring-amber-800'],
                'indigo' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20', 'icon' => 'text-indigo-600 dark:text-indigo-400', 'ring' => 'ring-indigo-200 dark:ring-indigo-800'],
                'green'  => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/20',  'icon' => 'text-emerald-600 dark:text-emerald-400',  'ring' => 'ring-emerald-200 dark:ring-emerald-800'],
                'red'    => ['bg' => 'bg-red-50 dark:bg-red-900/20',    'icon' => 'text-red-600 dark:text-red-400',    'ring' => 'ring-red-200 dark:ring-red-800'],
            ];
        @endphp

        @foreach($stats as $stat)
        @php $c = $colorMap[$stat['color']]; @endphp
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm hover:shadow-md hover:border-blue-200 dark:hover:border-blue-800 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="h-10 w-10 rounded-xl {{ $c['bg'] }} ring-1 {{ $c['ring'] }} flex items-center justify-center">
                    <svg class="h-5 w-5 {{ $c['icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $stat['value'] }}</p>
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">{{ $stat['label'] }}</p>
            @if($stat['trend'])
                <p class="text-xs text-amber-600 dark:text-amber-400 font-medium mt-1.5 flex items-center gap-1">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span></span>
                    {{ $stat['trend'] }}
                </p>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Recent Reservations --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="border-b border-slate-100 dark:border-slate-700/50 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-slate-800 dark:text-slate-100">Reservasi Terbaru</h2>
                <p class="text-xs text-slate-400 mt-0.5">Menampilkan 8 reservasi terakhir</p>
            </div>
            <a href="{{ route('admin.reservations.index') }}"
               class="flex items-center gap-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                Lihat semua
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($recentReservations->isEmpty())
        <div class="py-16 text-center">
            <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada data reservasi</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700/50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Gedung</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/30">
                    @foreach($recentReservations as $r)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 flex-shrink-0 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    {{ strtoupper(substr($r->customer_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800 dark:text-slate-100">{{ $r->customer_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $r->customer_phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-700 dark:text-slate-200 font-medium">{{ $r->building?->name }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300 text-xs">
                            {{ $r->start_date?->isoFormat('D MMM') }} – {{ $r->end_date?->isoFormat('D MMM YYYY') }}
                        </td>
                        <td class="px-6 py-4">
                            @include('components.status-badge', ['status' => $r->status])
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.reservations.show', $r) }}"
                               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-700 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
