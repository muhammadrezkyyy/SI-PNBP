@section('page-title', 'Manajemen Menu')

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">Manajemen Menu Navigasi</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Atur menu yang tampil pada sidebar admin</p>
        </div>
        <button wire:click="create" class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-500/30 hover:from-blue-700 hover:to-indigo-700 transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Menu
        </button>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 dark:bg-green-900/20 dark:border-green-800/50">
            <p class="text-sm font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700/50">
                        <th class="px-6 py-3 text-left font-semibold text-slate-500 dark:text-slate-400 w-16">Urutan</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-500 dark:text-slate-400">Grup / Header</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-500 dark:text-slate-400">Label Menu</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-500 dark:text-slate-400">Route</th>
                        <th class="px-6 py-3 text-center font-semibold text-slate-500 dark:text-slate-400">Status</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-500 dark:text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/30" id="sortable-menu-list">
                    @forelse($menus as $index => $menu)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors" data-id="{{ $menu->id }}" wire:key="menu-{{ $menu->id }}">
                            <td class="px-6 py-4">
                                <div class="flex flex-col items-center gap-1">
                                    <button wire:click="moveUp({{ $menu->id }})" class="text-slate-400 hover:text-blue-600 disabled:opacity-30 disabled:hover:text-slate-400" @if($loop->first) disabled @endif>
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    </button>
                                    <div class="cursor-move drag-handle my-0.5" title="Drag untuk mengubah urutan">
                                        <svg class="h-5 w-5 text-slate-400 hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                        </svg>
                                    </div>
                                    <button wire:click="moveDown({{ $menu->id }})" class="text-slate-400 hover:text-blue-600 disabled:opacity-30 disabled:hover:text-slate-400" @if($loop->last) disabled @endif>
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($menu->group)
                                    <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10 dark:bg-indigo-400/10 dark:text-indigo-400 dark:ring-indigo-400/30">{{ $menu->group }}</span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-xl bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center text-slate-500 dark:text-slate-400">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu->icon }}"/>
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-800 dark:text-slate-100">{{ $menu->label }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <code class="rounded bg-slate-100 dark:bg-slate-700 px-2 py-1 text-xs text-pink-600 dark:text-pink-400">{{ $menu->route }}</code>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggleActive({{ $menu->id }})" class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $menu->is_active ? 'bg-green-50 text-green-700 ring-1 ring-green-600/20 dark:bg-green-900/30 dark:text-green-400' : 'bg-slate-100 text-slate-600 ring-1 ring-slate-500/20 dark:bg-slate-800 dark:text-slate-400' }}">
                                    @if($menu->is_active)
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-600 dark:bg-green-400"></span> Aktif
                                    @else
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400 dark:bg-slate-500"></span> Nonaktif
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $menu->id }})" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                    <button wire:click="delete({{ $menu->id }})" wire:confirm="Yakin ingin menghapus menu ini?" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">Belum ada menu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Form --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showModal', false)"></div>
        
        <div class="relative w-full max-w-lg transform rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-2xl transition-all">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">
                    {{ $isEditing ? 'Edit Menu' : 'Tambah Menu Baru' }}
                </h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Label Menu <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="label" placeholder="Contoh: Laporan Keuangan" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-500/30 dark:text-white transition-all">
                    @error('label') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Grup / Header Menu</label>
                    <input type="text" wire:model="group" placeholder="Contoh: SISTEM, FASILITAS (Opsional)" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-500/30 dark:text-white transition-all uppercase">
                    <p class="text-xs text-slate-400 mt-1">Kosongkan jika menu tidak masuk grup apapun. Huruf kapital lebih rapi.</p>
                    @error('group') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Route Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="route" placeholder="Contoh: admin.reports.index" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-500/30 dark:text-white transition-all">
                    <p class="text-xs text-slate-400 mt-1">Pastikan route ini valid dan terdaftar di file rute Laravel.</p>
                    @error('route') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">SVG Path Icon <span class="text-red-500">*</span></label>
                    <textarea wire:model="icon" rows="3" placeholder="Masukkan atribut 'd' dari SVG path (contoh: M12 4v16m8-8H4)" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-500/30 dark:text-white transition-all font-mono"></textarea>
                    <p class="text-xs text-slate-400 mt-1">Ambil icon dari Heroicons.com (pilih yang Outline, copy d="..." attributenya saja).</p>
                    @error('icon') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Menu Aktif</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" wire:click="$set('showModal', false)" class="rounded-xl px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors">Batal</button>
                    <button type="submit" class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition-all">
                        Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        let el = document.getElementById('sortable-menu-list');
        if (el) {
            new Sortable(el, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-slate-100',
                onEnd: function (evt) {
                    let itemEls = el.querySelectorAll('tr[data-id]');
                    let orderedIds = Array.from(itemEls).map(itemEl => itemEl.getAttribute('data-id'));
                    
                    fetch('{{ route("admin.menus.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ orderedIds: orderedIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Order saved successfully, no flash needed
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        }
    });
</script>
@endpush
