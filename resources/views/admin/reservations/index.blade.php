@extends('layouts.admin')
@section('page-title', 'Daftar Reservasi')

@section('content')
<div>
    {{-- Filter & Pencarian --}}
    <div class="mb-6 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="flex flex-col sm:flex-row gap-3">
            {{-- Pencarian --}}
            <div class="flex-1 relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama pelanggan, email, atau gedung..."
                       class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 py-2.5 pl-10 pr-4 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/30 transition-all">
            </div>
            {{-- Filter Status --}}
            <select name="status"
                    class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                <option value="">Semua Status</option>
                @foreach(\App\Enums\ReservationStatus::cases() as $s)
                <option value="{{ $s->value }}" {{ request('status') === $s->value ? 'selected' : '' }}>
                    {{ $s->label() }}
                </option>
                @endforeach
            </select>
            <button type="submit"
                    class="flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                Filter
            </button>
            @if(request('search') || request('status'))
            <a href="{{ route('admin.reservations.index') }}"
               class="flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 px-5 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:bg-slate-900/50 transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Tabel Reservasi --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="border-b border-slate-100 dark:border-slate-700/50 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-slate-800 dark:text-slate-100">Semua Reservasi</h2>
                <p class="text-xs text-slate-400 mt-0.5">Total: {{ $reservations->total() }} data</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500 dark:text-slate-400">Tampilkan:</span>
                <select id="per-page-select" onchange="changePerPage(this.value)"
                        class="rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-2.5 py-1.5 text-xs text-slate-700 dark:text-slate-200 focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-400 dark:focus:ring-blue-500/30 transition-all">
                    @foreach([5, 10, 25, 50] as $pp)
                        <option value="{{ $pp }}" {{ request('per_page', 15) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                    @endforeach
                    <option value="all" {{ request('per_page') === 'all' ? 'selected' : '' }}>Semua</option>
                </select>
            </div>
        </div>

        @if($reservations->isEmpty())
        <div class="py-20 text-center">
            <svg class="mx-auto h-12 w-12 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Tidak ada reservasi ditemukan</p>
            <p class="text-xs text-slate-400 mt-1">Coba ubah filter pencarian Anda</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700/50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Gedung</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Tanggal Reservasi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Kadaluarsa</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                    @foreach($reservations as $reservation)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 flex-shrink-0 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($reservation->customer_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800 dark:text-slate-100">{{ $reservation->customer_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $reservation->customer_phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800 dark:text-slate-100">{{ $reservation->building?->name }}</p>
                            <p class="text-xs text-slate-400">{{ $reservation->building?->facilityType?->daily_rate_formatted ?? 'Rp 0' }}/hari</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300 text-xs">
                            <p class="font-medium">{{ $reservation->start_date?->isoFormat('D MMM YYYY') }}</p>
                            <p class="text-slate-400">s/d {{ $reservation->end_date?->isoFormat('D MMM YYYY') }}</p>
                            <p class="text-slate-400 mt-0.5">{{ $reservation->duration_days }} hari</p>
                        </td>
                        <td class="px-6 py-4">
                            @include('components.status-badge', ['status' => $reservation->status])
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                            @if($reservation->lock_expires_at)
                                @if($reservation->lock_expires_at->isPast())
                                    <span class="text-red-500 font-medium">Sudah lewat</span>
                                @else
                                    <span title="{{ $reservation->lock_expires_at->format('d/m/Y H:i') }}">
                                        {{ $reservation->lock_expires_at->diffForHumans() }}
                                    </span>
                                @endif
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.reservations.show', $reservation) }}"
                                   class="rounded-lg border border-slate-200 dark:border-slate-700 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:bg-slate-800/80 transition-all">
                                    Detail
                                </a>
                                @if($reservation->status->value === 'PENDING_BILLING' && !$reservation->payment)
                                <a href="{{ route('admin.billing.upload', $reservation) }}"
                                   class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 transition-all">
                                    Upload Tagihan
                                </a>
                                @endif
                                @if($reservation->status->value === 'VERIFYING' && $reservation->payment)
                                <a href="{{ route('admin.audit.show', $reservation->payment) }}"
                                   class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700 transition-all">
                                    Audit
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        @if($reservations->hasPages())
        <div class="border-t border-slate-100 dark:border-slate-700/50 px-6 py-4">
            {{ $reservations->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function changePerPage(val) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', val);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
</script>
@endpush

