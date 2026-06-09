@extends('layouts.admin')
@section('page-title', 'Detail Reservasi')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Navigasi Kembali --}}
    <div class="mb-6">
        <a href="{{ route('admin.reservations.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:text-slate-100 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Reservasi
        </a>
    </div>

    {{-- Header Kartu --}}
    <div class="mb-6 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    @include('components.status-badge', ['status' => $reservation->status])
                </div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $reservation->building?->name }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-mono">ID: {{ $reservation->id }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                {{-- Tombol Upload Tagihan --}}
                @if($reservation->status->value === 'PENDING_BILLING' && !$reservation->payment)
                <a href="{{ route('admin.billing.upload', $reservation) }}"
                   class="flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload Tagihan SIMPONI
                </a>
                @endif
                {{-- Tombol Audit --}}
                @if($reservation->status->value === 'VERIFYING' && $reservation->payment)
                <a href="{{ route('admin.audit.show', $reservation->payment) }}"
                   class="flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Mulai Audit Pembayaran
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Info Reservasi + Data Pelanggan --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Detail Reservasi --}}
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 dark:border-slate-700/50 px-5 py-4">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-100">Informasi Reservasi</h3>
                </div>
                <div class="divide-y divide-slate-200 dark:divide-slate-700/50">
                    <div class="flex justify-between px-5 py-3.5">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Gedung</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $reservation->building?->name }}</span>
                    </div>
                    <div class="flex justify-between px-5 py-3.5">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Tanggal Mulai</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $reservation->start_date?->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    <div class="flex justify-between px-5 py-3.5">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Tanggal Selesai</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $reservation->end_date?->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    <div class="flex justify-between px-5 py-3.5">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Durasi</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $reservation->duration_days }} hari</span>
                    </div>
                    <div class="flex justify-between px-5 py-3.5">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Estimasi Total</span>
                        <span class="text-sm font-bold text-blue-700">Rp {{ number_format($reservation->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between px-5 py-3.5">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Kunci Kedaluwarsa</span>
                        <span class="text-sm font-medium {{ $reservation->lock_expires_at?->isPast() ? 'text-red-600' : 'text-slate-800 dark:text-slate-100' }}">
                            {{ $reservation->lock_expires_at?->isoFormat('D MMM YYYY HH:mm') ?? '—' }}
                        </span>
                    </div>
                    <div class="flex justify-between px-5 py-3.5">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Dibuat</span>
                        <span class="text-sm text-slate-600 dark:text-slate-300">{{ $reservation->created_at?->isoFormat('D MMM YYYY HH:mm') }}</span>
                    </div>
                </div>
            </div>

            {{-- Data Dinamis Pelanggan --}}
            @if(!empty($reservation->customer_data))
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 dark:border-slate-700/50 px-5 py-4">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-100">Data Formulir Pelanggan</h3>
                </div>
                <div class="divide-y divide-slate-200 dark:divide-slate-700/50">
                    @foreach($reservation->customer_data as $key => $value)
                    <div class="flex justify-between gap-4 px-5 py-3.5">
                        <span class="text-sm text-slate-500 dark:text-slate-400 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                        <span class="text-sm font-medium text-slate-800 dark:text-slate-100 text-right">{{ $value ?: '—' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Kolom Kanan: Info Pelanggan + Pembayaran + Audit --}}
        <div class="space-y-6">

            {{-- Profil Pelanggan --}}
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-5">
                <h3 class="font-semibold text-slate-800 dark:text-slate-100 mb-4">Pelanggan</h3>
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 flex-shrink-0 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr($reservation->customer_name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $reservation->customer_name }}</p>
                        @if($reservation->customer_phone)
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $reservation->customer_phone }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                    @if($reservation->user)
                        <span class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-400 ring-1 ring-inset ring-blue-700/10">Akun Terdaftar</span>
                    @else
                        <span class="inline-flex items-center rounded-md bg-slate-100 dark:bg-slate-700 px-2 py-1 text-xs font-medium text-slate-600 dark:text-slate-300 ring-1 ring-inset ring-slate-500/20">Tamu (Guest)</span>
                    @endif
                </div>
            </div>

            {{-- Info Pembayaran SIMPONI --}}
            @if($reservation->payment)
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-5">
                <h3 class="font-semibold text-slate-800 dark:text-slate-100 mb-4">Data Pembayaran</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-1">Kode Billing SIMPONI</p>
                        <p class="font-mono font-bold text-slate-800 dark:text-slate-100">{{ $reservation->payment->simponi_billing_code ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-1">Nominal Tagihan</p>
                        <p class="font-bold text-blue-700">{{ $reservation->payment->nominal_formatted ?? '—' }}</p>
                    </div>
                    @if($reservation->payment->simponi_pdf_path)
                    <div>
                        <a href="{{ route('customer.payment.simponi', $reservation->payment) }}" target="_blank" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Lihat File Dokumen Tagihan
                        </a>
                    </div>
                    @endif
                    @if($reservation->payment->ntpn)
                    <div class="pt-3 mt-3 border-t border-slate-100 dark:border-slate-700/50">
                        <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-1">NTPN (diklaim)</p>
                        <p class="font-mono font-bold text-slate-800 dark:text-slate-100 tracking-widest">{{ $reservation->payment->ntpn }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Log Audit --}}
            @if($reservation->payment?->auditLog)
            @php $audit = $reservation->payment->auditLog; @endphp
            <div class="rounded-2xl border {{ $audit->action === 'APPROVE' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }} p-5">
                <h3 class="font-semibold {{ $audit->action === 'APPROVE' ? 'text-green-800' : 'text-red-800' }} mb-3">
                    Hasil Audit: {{ $audit->action === 'APPROVE' ? 'Disetujui ✓' : 'Ditolak ✗' }}
                </h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="{{ $audit->action === 'APPROVE' ? 'text-green-600' : 'text-red-600' }}">Auditor</span>
                        <span class="font-medium {{ $audit->action === 'APPROVE' ? 'text-green-800' : 'text-red-800' }}">{{ $audit->admin?->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="{{ $audit->action === 'APPROVE' ? 'text-green-600' : 'text-red-600' }}">Waktu</span>
                        <span class="font-medium {{ $audit->action === 'APPROVE' ? 'text-green-800' : 'text-red-800' }}">{{ $audit->created_at?->isoFormat('D MMM YYYY HH:mm') }}</span>
                    </div>
                    @if($audit->payload['rejection_reason'] ?? null)
                    <div class="mt-2 pt-2 border-t border-red-200">
                        <p class="text-red-600 font-semibold mb-1">Alasan Penolakan:</p>
                        <p class="text-red-800">{{ $audit->payload['rejection_reason'] }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

