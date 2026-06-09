<div>
    @if($isModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

                <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 dark:border-slate-700">
                    <div class="bg-white dark:bg-slate-800 px-6 pb-6 pt-8 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100" id="modal-title">
                                Pengaturan Akun
                            </h3>
                            <button wire:click="closeModal" class="text-slate-400 hover:text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/80 hover:bg-slate-200 rounded-full p-2 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <form wire:submit="save" class="space-y-5">
                            <div>
                                <label for="profile_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="profile_name" wire:model="name" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-400">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="profile_email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email <span class="text-red-500">*</span></label>
                                <input type="email" id="profile_email" wire:model="email" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-400">
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-200 mb-4">Ubah Password (Opsional)</h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="current_password" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Password Saat Ini</label>
                                        <div class="relative" x-data="{ showPassword: false }">
                                            <input :type="showPassword ? 'text' : 'password'" id="current_password" wire:model="current_password" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 pl-4 pr-12 py-2.5 text-sm text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-400" placeholder="••••••••">
                                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                            </button>
                                        </div>
                                        @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="new_password" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Password Baru</label>
                                        <div class="relative" x-data="{ showPassword: false }">
                                            <input :type="showPassword ? 'text' : 'password'" id="new_password" wire:model="password" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 pl-4 pr-12 py-2.5 text-sm text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-400" placeholder="Minimal 8 karakter">
                                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                            </button>
                                        </div>
                                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Konfirmasi Password Baru</label>
                                        <div class="relative" x-data="{ showPassword: false }">
                                            <input :type="showPassword ? 'text' : 'password'" id="password_confirmation" wire:model="password_confirmation" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 pl-4 pr-12 py-2.5 text-sm text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-400" placeholder="Ulangi password baru">
                                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                                <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition-colors">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
