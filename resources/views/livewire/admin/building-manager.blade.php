<div>
    @section('page-title', 'Unit Ruangan')

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">Manajemen Unit Ruangan</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola data unit spesifik setiap fasilitas (Asrama, Kelas, Ruang Meeting, dll).</p>
        </div>
        <button wire:click="create"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-500/30 hover:from-blue-700 hover:to-indigo-700 transition-all">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Unit
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800/50 p-4 flex items-center gap-3">
            <svg class="h-5 w-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Card Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($buildings as $building)
            <div class="group rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col">

                {{-- Header card dengan warna kategori --}}
                <div class="h-2 w-full {{ $building->is_active ? 'bg-gradient-to-r from-blue-500 to-indigo-500' : 'bg-slate-300 dark:bg-slate-600' }}"></div>

                <div class="p-5 flex-1">
                    {{-- Title & Status --}}
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 leading-tight line-clamp-2">{{ $building->name }}</h3>
                            <span class="inline-flex items-center gap-1 mt-1.5 rounded-full bg-blue-50 dark:bg-blue-900/40 px-2.5 py-0.5 text-xs font-semibold text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10">
                                {{ $building->facilityType?->name ?? 'Tanpa Kategori' }}
                            </span>
                        </div>
                        <span class="flex-shrink-0 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                            {{ $building->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                            {{ $building->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </div>

                    {{-- Tarif dari Kategori --}}
                    <div class="rounded-xl bg-slate-50 dark:bg-slate-900/50 p-3.5 border border-slate-100 dark:border-slate-700/50">
                        <p class="text-[11px] text-slate-400 uppercase font-bold tracking-wider mb-0.5">Tarif Harian (via Kategori)</p>
                        <p class="font-bold text-blue-700 dark:text-blue-400 text-lg">
                            {{ $building->facilityType?->daily_rate_formatted ?? 'Rp 0' }}
                        </p>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/30 px-5 py-3 flex items-center justify-between gap-2">
                    <button wire:click="toggleStatus('{{ $building->id }}')"
                            class="text-xs font-semibold transition-colors {{ $building->is_active ? 'text-amber-600 hover:text-amber-700' : 'text-green-600 hover:text-green-700' }}">
                        {{ $building->is_active ? '⏸ Non-Aktifkan' : '▶ Aktifkan' }}
                    </button>
                    <div class="flex items-center gap-1">
                        <button wire:click="edit('{{ $building->id }}')"
                                class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Edit
                        </button>
                        <button wire:click="delete('{{ $building->id }}')"
                                wire:confirm="Yakin ingin menghapus unit '{{ $building->name }}'?"
                                class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 p-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700 mb-4">
                    <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-1">Belum Ada Unit</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Tambahkan unit pertama untuk mulai menerima reservasi.</p>
                <button wire:click="create" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition-all">
                    Tambah Unit Sekarang
                </button>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $buildings->links() }}
    </div>

    {{-- Modal Tambah/Edit --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal"></div>

        <div class="relative w-full max-w-md transform rounded-2xl bg-white dark:bg-slate-800 shadow-2xl transition-all border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">
                    {{ $isEditing ? 'Edit Unit Ruangan' : 'Tambah Unit Baru' }}
                </h3>
                <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="px-6 py-5 space-y-4">
                {{-- Nama Unit --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Nama Unit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="name"
                           placeholder="Contoh: Asrama Putra Lantai 1, Ruang Rapat VIP"
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 outline-none transition-all">
                    @error('name')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Kategori / Tipe --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Kategori / Tipe <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="facility_type_id"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 outline-none transition-all">
                        <option value="">Pilih Kategori...</option>
                        @foreach($facilityTypes as $ft)
                            <option value="{{ $ft->id }}">{{ $ft->name }} — {{ $ft->daily_rate_formatted }}/hari</option>
                        @endforeach
                    </select>
                    @error('facility_type_id')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Status Toggle --}}
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700/50">
                    <button type="button" wire:click="$toggle('is_active')"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none
                                   {{ $is_active ? 'bg-blue-600' : 'bg-slate-300 dark:bg-slate-600' }}">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                     {{ $is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                    <div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Status: {{ $is_active ? 'Aktif' : 'Non-Aktif' }}</span>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Jika nonaktif, unit ini tidak tampil di form pemesanan.</p>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" wire:click="closeModal"
                            class="rounded-xl px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-blue-700 hover:to-indigo-700 transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah Unit' }}</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
