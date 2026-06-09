@extends('layouts.admin')
@section('page-title', 'Upload Tagihan SIMPONI')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Navigasi Kembali --}}
    <div class="mb-6">
        <a href="{{ route('admin.reservations.show', $reservation) }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:text-slate-100 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Detail Reservasi
        </a>
    </div>

    {{-- Info Reservasi --}}
    <div class="mb-6 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-5">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 flex-shrink-0 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-slate-800 dark:text-slate-100">{{ $reservation->building?->name }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $reservation->start_date?->isoFormat('D MMM') }} – {{ $reservation->end_date?->isoFormat('D MMM YYYY') }}
                    · {{ $reservation->user?->name }}
                </p>
            </div>
        </div>
    </div>

    {{-- Form Upload PDF --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="border-b border-slate-100 dark:border-slate-700/50 px-6 py-4">
            <h2 class="font-semibold text-slate-800 dark:text-slate-100">Upload Tagihan SIMPONI (PDF)</h2>
            <p class="text-xs text-slate-400 mt-0.5">Sistem akan otomatis mengekstrak Kode Billing dan Nominal dari PDF.</p>
        </div>

        <form method="POST"
              action="{{ route('admin.billing.store', $reservation) }}"
              enctype="multipart/form-data"
              class="p-6">
            @csrf

            {{-- Error Validation --}}
            @if($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">
                <p class="text-sm font-semibold text-red-800 mb-2">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-sm text-red-700">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Area Upload PDF --}}
            <div class="mb-6" x-data="{ fileName: '', dragging: false, isManual: false }">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">
                    File PDF SIMPONI <span class="text-red-500" x-show="!isManual">*</span> <span class="text-xs text-slate-400 font-normal ml-2" x-show="isManual">(Opsional)</span>
                </label>
                <div @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="dragging = false; fileName = $event.dataTransfer.files[0]?.name; $refs.fileInput.files = $event.dataTransfer.files"
                     :class="dragging ? 'border-blue-400 bg-blue-50' : 'border-slate-300 bg-slate-50 dark:bg-slate-900/50 hover:border-blue-300 hover:bg-slate-100 dark:bg-slate-800/80'"
                     class="relative rounded-2xl border-2 border-dashed p-10 text-center transition-all cursor-pointer"
                     @click="$refs.fileInput.click()">

                    <input type="file"
                           name="simponi_pdf"
                           accept=".pdf"
                           class="sr-only"
                           x-ref="fileInput"
                           @change="fileName = $event.target.files[0]?.name">

                    <div x-show="!fileName">
                        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100">
                            <svg class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Klik atau seret file PDF ke sini</p>
                        <p class="text-xs text-slate-400 mt-1">Format: PDF · Maksimal 10 MB</p>
                    </div>

                    <div x-show="fileName" class="flex flex-col items-center gap-2">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-green-700" x-text="fileName"></p>
                        <p class="text-xs text-slate-400">Klik untuk ganti file</p>
                    </div>
                </div>
                @error('simponi_pdf')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror

                {{-- Mode Uji Coba / Manual Input --}}
                <div class="mt-4 p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_manual" value="1" x-model="isManual" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Gunakan Input Manual (Mode Uji Coba / Ekstraksi Gagal)</span>
                    </label>
                    
                    <div x-show="isManual" x-collapse class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Kode Billing (15 Digit)</label>
                            <input type="text" name="manual_billing_code" placeholder="Misal: 820230000012345" class="w-full rounded-lg border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Nominal Pembayaran (Rp)</label>
                            <input type="number" name="manual_nominal" placeholder="Misal: 1500000" class="w-full rounded-lg border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Keterangan Proses --}}
            <div class="mb-6 rounded-xl border border-amber-100 bg-amber-50 p-4">
                <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide mb-2">Yang akan terjadi setelah upload:</p>
                <ol class="list-decimal list-inside space-y-1.5 text-xs text-amber-800">
                    <li>Sistem membaca teks dari PDF menggunakan parser lokal</li>
                    <li>Kode Billing (15 digit) dan Nominal diekstrak via Regex</li>
                    <li>Data tagihan disimpan dan status reservasi diperbarui ke <strong>Menunggu Pembayaran</strong></li>
                    <li>Instruksi pembayaran otomatis dikirim ke WhatsApp pelanggan</li>
                </ol>
            </div>

            {{-- Tombol Submit --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.reservations.show', $reservation) }}"
                   class="rounded-xl border border-slate-200 dark:border-slate-700 px-5 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:bg-slate-900/50 transition-all">
                    Batal
                </a>
                <button type="submit"
                        class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-500/30 hover:from-blue-700 hover:to-indigo-700 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload & Proses Tagihan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

