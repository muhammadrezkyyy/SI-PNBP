

<div class="relative" x-data="{ open: false }" wire:poll.30s="loadNotifications">
    {{-- Bell Button --}}
    <button @click="open = !open" @click.outside="open = false" 
            class="relative rounded-full p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/50">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        {{-- Badge --}}
        @if($unreadCount > 0)
        <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white dark:ring-slate-900">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
         class="absolute right-0 mt-2 w-80 md:w-96 rounded-xl border border-slate-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-800 z-50 overflow-hidden"
         style="display: none;">
        
        <div class="border-b border-slate-100 bg-slate-50/50 px-4 py-3 dark:border-slate-700/50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800 dark:text-slate-100">Notifikasi</h3>
            @if($unreadCount > 0)
                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                    {{ $unreadCount }} Baru
                </span>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notif)
                @php
                    $isVerifying = $notif->status === 'VERIFYING';
                    $url = $isVerifying 
                        ? route('admin.audit.show', $notif->payment->id ?? '') 
                        : route('admin.reservations.show', $notif);
                @endphp
                <a href="{{ $url }}" class="flex items-start gap-4 border-b border-slate-100 p-4 hover:bg-slate-50 dark:border-slate-700/50 dark:hover:bg-slate-700/50 transition-colors">
                    <div class="flex-shrink-0 mt-1">
                        @if($isVerifying)
                            <div class="rounded-full bg-emerald-100 p-2 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        @else
                            <div class="rounded-full bg-amber-100 p-2 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">
                            {{ $isVerifying ? 'Pembayaran Siap Diaudit' : 'Reservasi Baru' }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">
                            @if($isVerifying)
                                Pelanggan <strong>{{ $notif->customer_name }}</strong> telah mengupload bukti bayar.
                            @else
                                Pesanan masuk dari <strong>{{ $notif->customer_name }}</strong> menunggu tagihan.
                            @endif
                        </p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1.5">
                            {{ $notif->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Tidak ada notifikasi baru</p>
                </div>
            @endforelse
        </div>
        
        <div class="border-t border-slate-100 bg-slate-50 p-2 text-center dark:border-slate-700/50 dark:bg-slate-800/80">
            <a href="{{ route('admin.reservations.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                Lihat Semua Reservasi
            </a>
        </div>
    </div>
</div>