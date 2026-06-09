<div>
    @section('page-title', 'Pengaturan Aplikasi')

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 dark:text-white">Pengaturan Aplikasi</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-400 mt-1">Kelola nama aplikasi, logo, dan tema default.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 rounded-xl bg-green-50 p-4 border border-green-200 flex items-center gap-3">
            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:bg-slate-800 dark:border-slate-700 shadow-sm overflow-hidden p-6 max-w-3xl">
        <form wire:submit="save" class="space-y-6">
            {{-- App Name --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="app_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 dark:text-slate-300 mb-1.5">Nama Aplikasi <span class="text-red-500">*</span></label>
                    <input type="text" id="app_name" wire:model="app_name" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 dark:text-white bg-white dark:bg-slate-800 dark:bg-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-400">
                    @error('app_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="copyright_text" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 dark:text-slate-300 mb-1.5">Teks Copyright <span class="text-red-500">*</span></label>
                    <input type="text" id="copyright_text" wire:model="copyright_text" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 dark:text-white bg-white dark:bg-slate-800 dark:bg-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-400">
                    @error('copyright_text') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="footer_text" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 dark:text-slate-300 mb-1.5">Teks Sub-Footer <span class="text-red-500">*</span></label>
                    <input type="text" id="footer_text" wire:model="footer_text" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 dark:text-white bg-white dark:bg-slate-800 dark:bg-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-400">
                    @error('footer_text') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Theme Default & Color --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="default_theme" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 dark:text-slate-300 mb-1.5">Tema Default <span class="text-red-500">*</span></label>
                    <select id="default_theme" wire:model="default_theme" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 dark:text-white bg-white dark:bg-slate-800 dark:bg-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                        <option value="light">Terang (Light)</option>
                        <option value="dark">Gelap (Dark)</option>
                        <option value="system">Ikuti Sistem (System)</option>
                    </select>
                    @error('default_theme') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="primary_color" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 dark:text-slate-300 mb-1.5">Warna Utama Aplikasi <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="primary_color" wire:model="primary_color" class="h-10 w-14 cursor-pointer rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:bg-slate-900 p-1 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                        <input type="text" wire:model="primary_color" class="flex-1 rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2 text-sm text-slate-800 dark:text-slate-100 dark:text-white bg-white dark:bg-slate-800 dark:bg-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all font-mono" placeholder="#0e1f40">
                    </div>
                    @error('primary_color') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Logo --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 dark:text-slate-300 mb-1.5">Logo Aplikasi</label>
                
                <div class="flex items-start gap-6 mt-2">
                    {{-- Current Logo Preview --}}
                    <div class="flex-shrink-0">
                        @if ($app_logo)
                            <img src="{{ $app_logo->temporaryUrl() }}" class="h-24 w-24 object-cover rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                        @elseif ($current_logo_path)
                            <img src="{{ asset('storage/' . $current_logo_path) }}" class="h-24 w-24 object-cover rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                        @else
                            <div class="h-24 w-24 rounded-xl border border-slate-200 dark:border-slate-700 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 dark:bg-slate-900 p-2 flex items-center justify-center text-slate-400">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Upload Input --}}
                    <div class="flex-1">
                        <label for="app_logo" class="cursor-pointer inline-flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 dark:border-slate-600 bg-white dark:bg-slate-800 dark:bg-slate-800 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 dark:text-slate-200 hover:bg-slate-50 dark:bg-slate-900/50 dark:hover:bg-slate-700 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Pilih File Logo
                        </label>
                        <input type="file" id="app_logo" wire:model="app_logo" class="hidden" accept="image/*">
                        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-400 mt-2">Format: JPG, PNG. Maks: 2MB.</p>
                        @error('app_logo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-700/50 dark:border-slate-700 flex justify-end">
                <button type="submit" class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition-colors">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
