@extends('layouts.admin')
@section('page-title', 'Dokumen Tagihan SIMPONI')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ printing: false }">

    {{-- Toolbar --}}
    <div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <a href="javascript:history.back()"
               class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:text-slate-100 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100 mt-1">
                Dokumen Tagihan SIMPONI
            </h1>
            @if($payment->simponi_billing_code)
            <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-0.5">
                Kode Billing: {{ $payment->simponi_billing_code }}
            </p>
            @endif
        </div>

        <div class="flex items-center gap-2 shrink-0">
            {{-- Download PDF --}}
            <a href="{{ route('admin.simponi.stream', $payment) }}"
               download="BPN_SIMPONI_{{ $payment->simponi_billing_code }}.pdf"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-600
                      bg-white dark:bg-slate-700 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200
                      hover:bg-slate-50 dark:hover:bg-slate-600 transition-all shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download PDF
            </a>

            {{-- Print Button --}}
            <button @click="printing = true; $refs.pdfFrame.contentWindow.print(); setTimeout(() => printing = false, 2000)"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white
                           shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-xl transition-all">
                <svg :class="printing ? 'animate-spin' : ''" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!printing" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    <circle x-show="printing" class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path x-show="printing" class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                <span x-text="printing ? 'Membuka Print Dialog...' : '🖨️ Cetak Dokumen'"></span>
            </button>
        </div>
    </div>

    {{-- PDF Viewer --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 shadow-xl overflow-hidden bg-white dark:bg-slate-800">
        {{-- Frame header --}}
        <div class="border-b border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-800 px-5 py-3 flex items-center gap-3">
            <div class="flex gap-1.5">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
            </div>
            <span class="text-xs text-slate-400 font-mono flex-1 truncate">
                {{ $pdfUrl }}
            </span>
            <kbd class="text-xs bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded font-mono">
                Ctrl+P untuk cetak
            </kbd>
        </div>

        {{-- Iframe PDF --}}
        <iframe x-ref="pdfFrame"
                src="{{ $pdfUrl }}"
                class="w-full border-0"
                style="height: 80vh; min-height: 600px;"
                title="Dokumen Tagihan SIMPONI">
        </iframe>
    </div>

    {{-- Info --}}
    <p class="text-center text-xs text-slate-400 dark:text-slate-500 mt-4">
        Tidak bisa melihat PDF? &nbsp;
        <a href="{{ route('admin.simponi.stream', $payment) }}" target="_blank"
           class="text-blue-500 hover:text-blue-600 underline">
            Buka di tab baru
        </a>
    </p>
</div>
@endsection
