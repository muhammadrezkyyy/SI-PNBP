<div class="max-w-4xl mx-auto" x-data="{ dragIndex: null }">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Form Builder</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Atur kolom yang akan diisi oleh pelanggan saat melakukan reservasi.</p>
    </div>

    {{-- Success Banner --}}
    @if($saved)
    <div class="mb-6 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800"
         x-data x-init="setTimeout(() => { $el.style.display='none' }, 3000)">
        <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Form berhasil disimpan! Perubahan akan langsung terlihat di halaman booking pelanggan.</span>
    </div>
    @endif

    {{-- Fields List --}}
    <div class="space-y-3" id="form-fields-list">

        @forelse($fields as $index => $field)
        <div class="group relative rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm transition-all duration-200 hover:shadow-md hover:border-blue-200"
             wire:key="field-{{ $index }}">

            {{-- Drag handle + index badge --}}
            <div class="flex items-start gap-4">
                <div class="flex flex-col items-center gap-1 pt-1">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800/80 text-xs font-bold text-slate-500 dark:text-slate-400">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex flex-col gap-0.5 mt-2">
                        <button wire:click="moveUp({{ $index }})"
                                class="p-0.5 text-slate-300 hover:text-blue-500 transition-colors"
                                title="Geser ke atas">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                        </button>
                        <button wire:click="moveDown({{ $index }})"
                                class="p-0.5 text-slate-300 hover:text-blue-500 transition-colors"
                                title="Geser ke bawah">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Field config grid --}}
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Field Name --}}
                    <div class="lg:col-span-1">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Nama Field <span class="text-red-400">*</span></label>
                        <input type="text"
                               wire:model="fields.{{ $index }}.field_name"
                               placeholder="e.g., institution"
                               class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/30 transition-all font-mono">
                        @error("fields.{$index}.field_name")
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Field Label --}}
                    <div class="lg:col-span-1">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Label <span class="text-red-400">*</span></label>
                        <input type="text"
                               wire:model="fields.{{ $index }}.field_label"
                               placeholder="e.g., Nama Instansi"
                               class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/30 transition-all">
                        @error("fields.{$index}.field_label")
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Field Type --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Tipe</label>
                        <select wire:model="fields.{{ $index }}.field_type"
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:border-blue-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/30 transition-all">
                            <option value="text">Teks</option>
                            <option value="number">Angka</option>
                            <option value="date">Tanggal</option>
                            <option value="email">Email</option>
                            <option value="textarea">Paragraf</option>
                        </select>
                    </div>

                    {{-- Placeholder --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Placeholder</label>
                        <input type="text"
                               wire:model="fields.{{ $index }}.placeholder"
                               placeholder="Teks petunjuk..."
                               class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-3 py-2 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/30 transition-all">
                    </div>
                </div>

                {{-- Required toggle + Delete --}}
                <div class="flex flex-col items-end gap-3">
                    <button wire:click="removeField({{ $index }})"
                            wire:confirm="Hapus field '{{ $field['field_label'] ?: 'ini' }}'?"
                            class="opacity-0 group-hover:opacity-100 rounded-lg p-1.5 text-slate-300 hover:bg-red-50 hover:text-red-500 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                    <label class="flex items-center gap-2 cursor-pointer mt-auto">
                        <input type="checkbox"
                               wire:model="fields.{{ $index }}.is_required"
                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 h-4 w-4">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-300">Wajib</span>
                    </label>
                </div>
            </div>

            {{-- Live preview badge --}}
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Preview: <span class="font-medium text-slate-600 dark:text-slate-300">{{ $field['field_label'] ?: '(label)' }}</span>
                    @if($field['is_required'])
                        <span class="text-red-400">*</span>
                    @endif
                    <span class="ml-auto rounded-full bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 text-slate-500 dark:text-slate-400">{{ $field['field_type'] }}</span>
                </div>
            </div>
        </div>

        @empty
        <div class="rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 py-16 text-center">
            <div class="flex justify-center mb-4">
                <div class="h-14 w-14 rounded-full bg-blue-50 flex items-center justify-center">
                    <svg class="h-7 w-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Belum ada field</p>
            <p class="text-xs text-slate-400 mt-1">Klik "Tambah Field" untuk mulai membangun form reservasi.</p>
        </div>
        @endforelse
    </div>

    {{-- Action Bar --}}
    <div class="mt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm">
        <button wire:click="addField"
                class="flex items-center gap-2 rounded-xl border-2 border-dashed border-blue-300 bg-blue-50 px-5 py-2.5 text-sm font-semibold text-blue-700 hover:border-blue-400 hover:bg-blue-100 transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Field
        </button>

        <button wire:click="saveForm"
                wire:loading.attr="disabled"
                wire:target="saveForm"
                class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-500/30 hover:from-blue-700 hover:to-indigo-700 transition-all disabled:opacity-60">
            <span wire:loading.remove wire:target="saveForm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </span>
            <span wire:loading wire:target="saveForm">
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </span>
            <span wire:loading.remove wire:target="saveForm">Simpan Form</span>
            <span wire:loading wire:target="saveForm">Menyimpan...</span>
        </button>
    </div>
</div>
