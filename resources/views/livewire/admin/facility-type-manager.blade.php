@section('page-title', 'Kategori Fasilitas')

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">Kategori Fasilitas</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola kategori beserta tarif dan galeri foto masing-masing fasilitas.</p>
        </div>
        <button wire:click="create" class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-500/30 hover:from-blue-700 hover:to-indigo-700 transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </button>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800/50 p-4 flex items-center gap-3">
            <svg class="h-5 w-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Card Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($types as $type)
            <div class="group rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col">

                {{-- Image Carousel --}}
                @php $allImages = $type->all_image_paths; @endphp
                <div x-data="{ activeSlide: 0, slides: {{ count($allImages) }}, startX: 0 }" 
                     @touchstart.stop="startX = $event.touches[0].clientX"
                     @touchend.stop="if (startX - $event.changedTouches[0].clientX > 40 && slides > 1) { activeSlide = activeSlide < slides - 1 ? activeSlide + 1 : 0 } else if ($event.changedTouches[0].clientX - startX > 40 && slides > 1) { activeSlide = activeSlide > 0 ? activeSlide - 1 : slides - 1 }"
                     class="relative h-44 w-full bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 overflow-hidden group/slider">
                    @if(count($allImages) > 0)
                        <div class="flex h-full transition-transform duration-500 ease-out" :style="`transform: translateX(-${activeSlide * 100}%)`">
                            @foreach($allImages as $imgPath)
                            <div class="flex-shrink-0 w-full h-full">
                                <img src="{{ asset('storage/' . $imgPath) }}" alt="{{ $type->name }}" class="w-full h-full object-cover">
                            </div>
                            @endforeach
                        </div>
                        @if(count($allImages) > 1)
                            <!-- Arrows -->
                            <button @click.stop="activeSlide = activeSlide > 0 ? activeSlide - 1 : slides - 1" class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-black/40 p-1.5 text-white opacity-0 group-hover/slider:opacity-100 hover:bg-black/70 focus:outline-none backdrop-blur-sm transition-all duration-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button @click.stop="activeSlide = activeSlide < slides - 1 ? activeSlide + 1 : 0" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-black/40 p-1.5 text-white opacity-0 group-hover/slider:opacity-100 hover:bg-black/70 focus:outline-none backdrop-blur-sm transition-all duration-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </button>
                            <!-- Dots -->
                            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1.5">
                                @foreach($allImages as $i => $imgPath)
                                <button @click.stop="activeSlide = {{ $i }}" class="h-1.5 rounded-full transition-all duration-300 shadow-sm" :class="activeSlide === {{ $i }} ? 'w-4 bg-white' : 'w-1.5 bg-white/50 hover:bg-white/80'"></button>
                                @endforeach
                            </div>
                        @endif
                        <div class="absolute inset-x-0 top-0 h-12 bg-gradient-to-b from-black/50 to-transparent pointer-events-none"></div>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <svg class="h-10 w-10 text-slate-300 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-slate-400">Belum ada foto</p>
                        </div>
                    @endif

                    {{-- Photo count badge --}}
                    @if(count($allImages) > 0)
                    <div class="absolute top-2 left-2">
                        <span class="inline-flex items-center gap-1 rounded-lg bg-black/50 backdrop-blur-sm px-2 py-1 text-[10px] font-bold text-white">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ count($allImages) }} foto
                        </span>
                    </div>
                    @endif

                    {{-- Tarif badge --}}
                    <div class="absolute bottom-2 left-2">
                        <span class="inline-flex items-center rounded-lg bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm px-2.5 py-1 text-xs font-bold text-blue-700 dark:text-blue-400 shadow-sm">
                            {{ $type->daily_rate_formatted }}<span class="font-normal text-slate-500 ml-0.5">/hari</span>
                        </span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">{{ $type->name }}</h3>
                        <span class="flex-shrink-0 inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 text-[10px] font-bold text-blue-700 dark:text-blue-400">
                            {{ $type->active_buildings_count }} unit
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 flex-1">{{ $type->description ?: 'Tidak ada deskripsi.' }}</p>
                </div>

                {{-- Actions --}}
                <div class="border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/30 px-5 py-3 flex items-center justify-end gap-1">
                    <button wire:click="edit({{ $type->id }})" class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Edit
                    </button>
                    <button wire:click="delete({{ $type->id }})" wire:confirm="Yakin ingin menghapus kategori '{{ $type->name }}'? Semua foto dan unit terkait akan terpengaruh." class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 p-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700 mb-4">
                    <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-1">Belum Ada Kategori</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Tambahkan kategori fasilitas pertama Anda.</p>
                <button wire:click="create" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition-all">Tambah Kategori Sekarang</button>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $types->links() }}
    </div>

    {{-- Modal Form --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>

        <div class="relative w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white dark:bg-slate-800 shadow-2xl border border-slate-200 dark:border-slate-700">

            <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-700 sticky top-0 bg-white dark:bg-slate-800 z-10">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">
                    {{ $isEditing ? 'Edit Kategori Fasilitas' : 'Tambah Kategori Baru' }}
                </h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="px-6 py-5 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" placeholder="Contoh: Asrama, Ruang Rapat, Kelas" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 outline-none transition-all">
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tarif per Hari (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="daily_rate" placeholder="1500000" min="0" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 outline-none transition-all">
                        @error('daily_rate') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Foto Utama (Cover)</label>
                        <input type="file" wire:model="coverImage" accept="image/*" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 file:mr-2 file:rounded-full file:border-0 file:bg-blue-50 file:px-3 file:py-1 file:text-[10px] file:font-bold file:text-blue-700 dark:file:bg-blue-900/30 dark:file:text-blue-400 cursor-pointer">
                    </div>
                </div>

                {{-- Cover preview --}}
                @if($coverImage)
                    <div class="rounded-xl overflow-hidden border border-blue-200 dark:border-blue-800 relative h-32 group">
                        <img src="{{ $coverImage->temporaryUrl() }}" class="w-full h-full object-cover">
                        <span class="absolute top-2 right-2 rounded-full bg-blue-600 px-2 py-0.5 text-[10px] font-bold text-white shadow-sm">Cover Baru</span>
                        <button type="button" wire:click="$set('coverImage', null)"
                                class="absolute top-2 left-2 h-7 w-7 rounded-full bg-red-600 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md hover:bg-red-700" title="Hapus foto ini">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @elseif($oldCoverImage)
                    <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 relative h-32">
                        <img src="{{ asset('storage/' . $oldCoverImage) }}" class="w-full h-full object-cover">
                        <span class="absolute top-2 right-2 rounded-full bg-slate-600 px-2 py-0.5 text-[10px] font-bold text-white">Cover Saat Ini</span>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Keterangan <span class="text-red-500">*</span></label>
                    <textarea wire:model="description" rows="2" placeholder="Fasilitas: AC, WiFi, Kapasitas 30 orang..." class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 outline-none transition-all resize-none"></textarea>
                    @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Gallery Upload --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Galeri Foto
                        <span class="font-normal text-slate-400 text-xs ml-1">(Upload banyak foto sekaligus, tanpa batas)</span>
                    </label>

                    {{-- Existing gallery images --}}
                    @if(count($existingGallery) > 0)
                    <div class="grid grid-cols-4 gap-2 mb-3">
                        @foreach($existingGallery as $img)
                        <div class="relative group rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 aspect-square">
                            <img src="{{ asset('storage/' . $img['path']) }}" class="w-full h-full object-cover">
                            <button type="button" wire:click="removeGalleryImage({{ $img['id'] }})" wire:confirm="Hapus foto ini?"
                                    class="absolute inset-0 flex items-center justify-center bg-red-600/70 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- New gallery preview --}}
                    @if(count($galleryImages) > 0)
                    <div class="grid grid-cols-4 gap-2 mb-3">
                        @foreach($galleryImages as $index => $img)
                        <div class="rounded-lg overflow-hidden border-2 border-blue-300 dark:border-blue-700 aspect-square relative group">
                            <img src="{{ $img->temporaryUrl() }}" class="w-full h-full object-cover">
                            <span class="absolute bottom-0 inset-x-0 bg-blue-600 text-center text-[9px] font-bold text-white py-0.5">Baru</span>
                            <button type="button" wire:click="removeNewGalleryImage({{ $index }})"
                                    class="absolute top-1 right-1 h-6 w-6 rounded-full bg-red-600 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md hover:bg-red-700" title="Hapus foto ini">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <input type="file" wire:model="galleryImages" accept="image/*" multiple
                           class="w-full rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 px-4 py-4 text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-blue-700 dark:file:bg-blue-900/30 dark:file:text-blue-400 cursor-pointer text-center">
                    <p class="text-[11px] text-slate-400 mt-1.5">Format: JPG, PNG, WebP. Maks. 2MB per foto. Pilih beberapa file sekaligus.</p>
                    @error('galleryImages.*') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" wire:click="$set('showModal', false)" class="rounded-xl px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors">Batal</button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="save, coverImage, galleryImages"
                            class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-blue-700 hover:to-indigo-700 transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="save, coverImage, galleryImages">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah Kategori' }}</span>
                        <span wire:loading wire:target="save, coverImage, galleryImages">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
