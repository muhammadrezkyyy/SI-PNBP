<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Upload Tagihan SIMPONI</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Reservasi: {{ $reservation->building->name }} - {{ $reservation->user->name ?? 'Tamu' }}</p>
        </div>
        <a href="{{ route('admin.reservations.show', $reservation) }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ dragging: false, localPreviewUrl: null }">
        
        {{-- Kolom Kiri: Form & Hasil Ekstraksi --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 dark:border-slate-700/50 px-6 py-4">
                    <h2 class="font-semibold text-slate-800 dark:text-slate-100">File Dokumen SIMPONI</h2>
                </div>
                
                <div class="p-6">
                    {{-- Area Upload PDF --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">
                            Pilih File PDF
                        </label>
                        <div @dragover.prevent="dragging = true"
                             @dragleave.prevent="dragging = false"
                             @drop.prevent="
                                dragging = false;
                                const file = $event.dataTransfer.files[0];
                                if (file && file.type === 'application/pdf') {
                                    localPreviewUrl = URL.createObjectURL(file);
                                } else {
                                    localPreviewUrl = null;
                                }
                             "
                             :class="dragging ? 'border-blue-400 bg-blue-50' : 'border-slate-300 bg-slate-50 dark:bg-slate-900/50 hover:border-blue-300 hover:bg-slate-100 dark:bg-slate-800/80'"
                             class="relative rounded-2xl border-2 border-dashed p-8 text-center transition-all cursor-pointer"
                             @click="$refs.fileInput.click()">

                            <input type="file"
                                   wire:model="simponi_pdf"
                                   accept=".pdf"
                                   class="sr-only"
                                   x-ref="fileInput"
                                   @change="
                                        const file = $event.target.files[0];
                                        if (file && file.type === 'application/pdf') {
                                            localPreviewUrl = URL.createObjectURL(file);
                                        } else {
                                            localPreviewUrl = null;
                                        }
                                   ">

                            <div wire:loading.remove wire:target="simponi_pdf">
                                @if(!$simponi_pdf)
                                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100">
                                        <svg class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Klik atau seret file PDF ke sini</p>
                                    <p class="text-xs text-slate-400 mt-1">Format: PDF A Maksimal 10 MB</p>
                                @else
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-green-700">{{ $simponi_pdf->getClientOriginalName() }}</p>
                                        <p class="text-xs text-slate-400">Klik untuk ganti file</p>
                                    </div>
                                @endif
                            </div>

                            <div wire:loading wire:target="simponi_pdf" class="py-4">
                                <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm text-slate-500 mt-3">Sedang mengupload dan membaca PDF...</p>
                            </div>
                        </div>
                        @error('simponi_pdf')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hasil Ekstraksi --}}
                    @if($extracted_billing_code || $extracted_nominal)
                        <div class="mb-6 p-4 rounded-xl border border-green-200 bg-green-50 dark:bg-green-900/20">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm font-bold text-green-800 dark:text-green-300">Data Berhasil Diekstrak!</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-green-600/80 mb-1">Kode Billing</p>
                                    <p class="font-mono text-lg font-bold text-green-800">{{ $extracted_billing_code }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-green-600/80 mb-1">Nominal</p>
                                    <p class="font-bold text-lg text-green-800">Rp {{ number_format($extracted_nominal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($extracted_error)
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                            <p class="text-sm font-semibold text-red-800 mb-2">Peringatan Pembacaan Dokumen:</p>
                            <p class="text-sm text-red-700">{{ $extracted_error }}</p>
                        </div>
                    @endif

                    {{-- Mode Manual --}}
                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="is_manual" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Gunakan Input Manual (Ekstraksi Gagal/Ubah Data)</span>
                        </label>
                        
                        @if($is_manual)
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Kode Billing (15 Digit)</label>
                                    <input type="text" wire:model="manual_billing_code" placeholder="Misal: 820230000012345" class="w-full rounded-lg border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:text-white">
                                    @error('manual_billing_code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Nominal Pembayaran (Rp)</label>
                                    <input type="number" wire:model="manual_nominal" placeholder="Misal: 1500000" class="w-full rounded-lg border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:text-white">
                                    @error('manual_nominal') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 flex items-center justify-between border-t border-slate-100 dark:border-slate-700/50">
                    <p class="text-xs text-slate-500">
                        Pesan otomatis berisi tagihan akan dikirim ke WhatsApp pelanggan.
                    </p>
                    <button wire:click="submit" wire:loading.attr="disabled"
                            class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-500/30 hover:from-blue-700 hover:to-indigo-700 transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="submit">
                            <svg class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </span>
                        <span wire:loading wire:target="submit">
                            <svg class="animate-spin h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        Simpan & Kirim WA
                    </button>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Live Preview PDF --}}
        <div class="h-[600px] lg:h-auto rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900 shadow-sm overflow-hidden flex flex-col relative">
            <div class="border-b border-slate-200 dark:border-slate-700/50 px-4 py-3 bg-white dark:bg-slate-800 flex items-center justify-between z-10">
                <h2 class="font-semibold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Preview Dokumen
                </h2>
                <span x-show="localPreviewUrl" style="display: none;" class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-lg font-medium">Ready</span>
            </div>

            <div class="flex-1 bg-slate-100 dark:bg-slate-900 relative min-h-[500px]">
                <template x-if="localPreviewUrl">
                    <object :data="localPreviewUrl + '#view=FitH'" type="application/pdf" class="absolute inset-0 w-full h-full z-0">
                        <embed :src="localPreviewUrl + '#view=FitH'" type="application/pdf" class="w-full h-full" />
                    </object>
                </template>
                <template x-if="!localPreviewUrl">
                    <div class="flex flex-col items-center justify-center h-full text-slate-400 absolute inset-0">
                        <svg class="h-16 w-16 mb-4 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-medium text-slate-500 dark:text-slate-400">Belum ada file yang diupload</p>
                        <p class="text-sm mt-1">Preview PDF akan muncul di sini</p>
                    </div>
                </template>
            </div>
        </div>

    </div>
</div>
