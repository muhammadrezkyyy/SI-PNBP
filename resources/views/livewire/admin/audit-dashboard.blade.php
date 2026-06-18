<div class="max-w-7xl mx-auto" x-data="{ zoomOpen: @entangle('zoom_open') }">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Audit Pembayaran</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Verifikasi bukti pembayaran dan tagihan SIMPONI secara side-by-side.</p>
        </div>
        <a href="{{ route('admin.reservations.index') }}"
           class="flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:bg-slate-900/50 transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- Submitted State --}}
    @if($submitted)
    <div class="rounded-2xl border {{ $audit_decision === 'APPROVE' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }} p-6 text-center mb-6">
        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full {{ $audit_decision === 'APPROVE' ? 'bg-green-100' : 'bg-red-100' }}">
            @if($audit_decision === 'APPROVE')
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @else
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @endif
        </div>
        <h2 class="text-lg font-bold {{ $audit_decision === 'APPROVE' ? 'text-green-800' : 'text-red-800' }}">
            Audit {{ $audit_decision === 'APPROVE' ? 'Disetujui' : 'Ditolak' }}
        </h2>
        <p class="text-sm {{ $audit_decision === 'APPROVE' ? 'text-green-600' : 'text-red-600' }} mt-1">
            Pembayaran telah diverifikasi.
        </p>
    </div>
    @endif

    {{-- Side-by-Side Layout --}}
    {{-- Mobile: flex-col | Tablet+: grid-cols-2 --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        {{-- LEFT: Previews --}}
        <div class="xl:col-span-5 space-y-6 flex flex-col h-full">
            {{-- Receipt Preview --}}
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 dark:border-slate-700/50 px-5 py-4 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-slate-800 dark:text-slate-100">Bukti Pembayaran</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Diunggah oleh pelanggan</p>
                </div>
                @if($payment?->receipt_path)
                <button wire:click="toggleZoom"
                        class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-700 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:bg-slate-900/50 transition-all">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                    </svg>
                    Perbesar
                </button>
                @endif
            </div>

            <div class="p-5">
                @if($payment?->receipt_path)
                <div class="rounded-xl overflow-hidden border border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-900/50">
                    <img src="{{ route('admin.receipt.view', $payment->id) }}"
                         alt="Bukti Pembayaran"
                         class="w-full object-contain max-h-96 cursor-zoom-in"
                         @click="zoomOpen = true">
                </div>
                @else
                <div class="rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 py-16 text-center">
                    <svg class="mx-auto h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-slate-400">Belum ada bukti pembayaran</p>
                </div>
                @endif

                {{-- NTPN Info --}}
                @if($payment?->ntpn)
                <div class="mt-4 rounded-xl border border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-900/50 p-4">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">NTPN (diklaim pelanggan)</p>
                    <p class="font-mono text-lg font-bold text-slate-800 dark:text-slate-100 tracking-widest">{{ $payment->ntpn }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ strlen($payment->ntpn) }}/16 digit</p>
                </div>
                @endif

                {{-- Reservation Summary --}}
                @if($payment?->reservation)
                <div class="mt-4 rounded-xl border border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-900/50 p-4 space-y-2">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Detail Reservasi</p>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Pelanggan</span>
                        <span class="font-medium text-slate-800 dark:text-slate-100">{{ $payment->reservation->user?->name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Gedung</span>
                        <span class="font-medium text-slate-800 dark:text-slate-100">{{ $payment->reservation->building?->name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Tanggal</span>
                        <span class="font-medium text-slate-800 dark:text-slate-100">
                            {{ $payment->reservation->start_date?->isoFormat('D MMM') }} –
                            {{ $payment->reservation->end_date?->isoFormat('D MMM YYYY') }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

                {{-- SIMPONI Asli Preview --}}
                @if($payment?->simponi_pdf_path)
                <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden flex flex-col mt-4">
                    <div class="border-b border-slate-100 dark:border-slate-700/50 px-5 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-slate-800 dark:text-slate-100">SIMPONI Asli (Preview PDF)</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Dokumen PDF yang diunggah admin</p>
                        </div>
                        <a href="{{ route('admin.simponi.view', $payment->id) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Buka Tab Baru</a>
                    </div>
                    <div class="p-0 bg-slate-100 dark:bg-slate-900 flex-1" wire:ignore>
                        <iframe src="{{ route('admin.simponi.stream', $payment->id) }}#toolbar=0" class="w-full h-[600px] border-0"></iframe>
                    </div>
                </div>
                @endif
            </div>

        {{-- RIGHT: Editable Data & Verifikasi --}}
        <div class="xl:col-span-7 space-y-6 flex flex-col">
            {{-- SIMPONI Word-like Document Editor --}}
            @if($payment?->simponi_pdf_path)
            <div class="flex-1 rounded-2xl border border-blue-200 dark:border-blue-800 bg-blue-50/30 dark:bg-slate-800 shadow-sm overflow-hidden flex flex-col"
                 x-data="bpnWordEditor($wire)"
                 x-init="init()">

                {{-- Header Panel --}}
                <div class="border-b border-blue-100 dark:border-slate-700/50 bg-blue-50 dark:bg-slate-800 px-5 py-3 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-blue-800 dark:text-slate-100 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Editor Dokumen SIMPONI
                        </h3>
                        <p class="text-xs text-blue-600 dark:text-slate-400 mt-0.5">Edit bebas seperti Microsoft Word — klik teks untuk mengedit, geser elemen via handle ⠿</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span x-show="!saved" class="text-xs text-amber-600 flex items-center gap-1 animate-pulse">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="30 70"/></svg> Menyimpan…
                        </span>
                        <span x-show="saved" class="text-xs text-emerald-600 flex items-center gap-1">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Tersimpan
                        </span>
                    </div>
                </div>

                {{-- MS Word Ribbon Toolbar --}}
                <div class="bg-white dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600 px-2 py-1.5 flex flex-wrap items-center gap-1 sticky top-0 z-50 shadow-sm">
                    <select onchange="document.getElementById('bpn-editor').focus(); document.execCommand('fontName', false, this.value);"
                            class="h-7 text-xs border border-slate-300 rounded px-1 text-slate-700 bg-white focus:outline-none" title="Font Keluarga">
                        <option value="Arial, Helvetica, sans-serif" selected>Arial</option>
                        <option value="Times New Roman, serif">Times New Roman</option>
                        <option value="Courier New, monospace">Courier New</option>
                        <option value="Georgia, serif">Georgia</option>
                        <option value="Verdana, sans-serif">Verdana</option>
                        <option value="Tahoma, sans-serif">Tahoma</option>
                    </select>
                    <select @change="setFontSize($event.target.value)"
                            class="h-7 w-14 text-xs border border-slate-300 rounded px-1 text-slate-700 bg-white focus:outline-none" title="Ukuran">
                        <option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
                        <option value="13" selected>13</option><option value="14">14</option><option value="16">16</option><option value="18">18</option>
                        <option value="20">20</option><option value="24">24</option><option value="28">28</option><option value="36">36</option>
                    </select>
                    <span class="w-px h-5 bg-slate-300 mx-0.5"></span>
                    <button type="button" @click="execCmd('bold')" :class="{'bg-blue-100': isBold}" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 font-bold text-sm text-slate-800" title="Bold">B</button>
                    <button type="button" @click="execCmd('italic')" :class="{'bg-blue-100': isItalic}" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 italic text-sm text-slate-800" title="Italic">I</button>
                    <button type="button" @click="execCmd('underline')" :class="{'bg-blue-100': isUnderline}" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 underline text-sm text-slate-800" title="Underline">U</button>
                    <button type="button" @click="execCmd('strikeThrough')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 line-through text-sm text-slate-700" title="Strikethrough">S</button>
                    <span class="w-px h-5 bg-slate-300 mx-0.5"></span>
                    <label title="Warna Teks" class="w-7 h-7 flex items-center justify-center rounded border border-slate-300 hover:bg-slate-100 cursor-pointer relative overflow-hidden">
                        <svg class="h-3.5 w-3.5 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 01.894.553l7 14A1 1 0 0117 18H3a1 1 0 01-.894-1.447l7-14A1 1 0 0110 2z"/></svg>
                        <input type="color" class="absolute opacity-0 inset-0 w-full h-full cursor-pointer" @change="execCmd('foreColor', $event.target.value)" value="#000000">
                    </label>
                    <label title="Highlight" class="w-7 h-7 flex items-center justify-center rounded border border-slate-300 hover:bg-slate-100 cursor-pointer relative overflow-hidden bg-yellow-50">
                        <span class="text-xs font-bold text-yellow-600">A</span>
                        <input type="color" class="absolute opacity-0 inset-0 w-full h-full cursor-pointer" @change="execCmd('hiliteColor', $event.target.value)" value="#ffff00">
                    </label>
                    <button type="button" @click="execCmd('removeFormat')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-50 hover:text-red-500 text-slate-500 text-xs" title="Hapus Format">✕</button>
                    <span class="w-px h-5 bg-slate-300 mx-0.5"></span>
                    <button type="button" @click="execCmd('justifyLeft')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 text-slate-700" title="Kiri">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16"/></svg></button>
                    <button type="button" @click="execCmd('justifyCenter')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 text-slate-700" title="Tengah">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M4 18h16"/></svg></button>
                    <button type="button" @click="execCmd('justifyRight')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 text-slate-700" title="Kanan">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M4 18h16"/></svg></button>
                    <button type="button" @click="execCmd('justifyFull')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 text-slate-700" title="Justify">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                    <span class="w-px h-5 bg-slate-300 mx-0.5"></span>
                    <button type="button" @click="execCmd('insertUnorderedList')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 text-slate-700 text-sm" title="Bullet">•≡</button>
                    <button type="button" @click="execCmd('insertOrderedList')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 text-slate-700 text-xs" title="Nomor">1≡</button>
                    <button type="button" @click="execCmd('indent')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 text-slate-700 text-xs" title="Indent">→|</button>
                    <button type="button" @click="execCmd('outdent')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 text-slate-700 text-xs" title="Outdent">|←</button>
                    <span class="w-px h-5 bg-slate-300 mx-0.5"></span>
                    <button type="button" @click="execCmd('undo')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 text-slate-700" title="Undo (Ctrl+Z)">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg></button>
                    <button type="button" @click="execCmd('redo')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-slate-100 text-slate-700" title="Redo (Ctrl+Y)">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"/></svg></button>
                    <span class="ml-auto text-xs text-slate-400 italic hidden lg:flex items-center gap-1">⠿ = geser drag &nbsp;|&nbsp; Ctrl+Z undo</span>
                </div>

                {{-- Document Canvas --}}
                <div class="flex-1 overflow-auto bg-slate-300 dark:bg-slate-900 p-8">

                    {{-- CSS for drag handles --}}
                    <style>
                        .bpn-abs-el { cursor: default; }
                        .bpn-drag-handle {
                            position: absolute;
                            top: -18px; left: 0;
                            background: #2563eb;
                            color: #fff;
                            font-size: 10px;
                            padding: 1px 5px;
                            border-radius: 3px;
                            cursor: grab;
                            white-space: nowrap;
                            opacity: 0;
                            transition: opacity .15s;
                            user-select: none;
                            z-index: 100;
                        }
                        /* Handle logo & QR header: tampil saat parent hover */
                        .bpn-abs-el:hover .bpn-drag-handle { opacity: 1; }
                        /* Handle watermark: parent pointer-events:none sehingga :hover parent tidak jalan.
                           Tampilkan handle selalu sedikit (0.35) dan penuh saat handle-nya sendiri di-hover */
                        #bpn-el-qr-watermark .bpn-drag-handle {
                            opacity: 0.35;
                            background: #7c3aed;
                        }
                        #bpn-el-qr-watermark .bpn-drag-handle:hover { opacity: 1; }
                        .bpn-drag-handle:active { cursor: grabbing; }
                        #bpn-editor:focus { outline: none; }
                        #bpn-editor a { color: #2563eb; }
                    </style>

                    {{-- A4 Paper --}}
                    <div id="bpn-paper"
                         class="bg-white shadow-2xl mx-auto"
                         style="position:relative; width:794px; min-height:1123px; padding:40px 50px; box-sizing:border-box; font-family:Arial,Helvetica,sans-serif; font-size:13px; line-height:1.5; color:#000;">

                        {{-- ════ DRAGGABLE: Logo ════
                             wire:ignore = Livewire tidak reset posisi setelah drag --}}
                        <div id="bpn-el-logo" class="bpn-abs-el" wire:ignore
                             style="position:absolute; top:0px; left:0px; z-index:30; user-select:none;">
                            <div class="bpn-drag-handle" @mousedown.prevent="startDrag($event)">⠿ Logo</div>
                            <img src="/images/kemenkeu_logo.png?v={{ time() }}" style="width:80px; display:block; pointer-events:none;" draggable="false" alt="Logo">
                        </div>

                        {{-- ════ DRAGGABLE: Header QR Code ════ --}}
                        <div id="bpn-el-qr-header" class="bpn-abs-el" wire:ignore
                             style="position:absolute; top:0px; left:609px; z-index:30; user-select:none;">
                            <div class="bpn-drag-handle" @mousedown.prevent="startDrag($event)">⠿ QR Header</div>
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(85)->margin(0)->generate($simponi_data['ntpn'] ?? 'SIMPONI') !!}
                        </div>

                        {{-- ════ DRAGGABLE: Watermark QR ════
                             FIX: z-index:20 (di atas editor z-index:5) + pointer-events:none pada container
                             agar klik menembus ke editor. Handle punya pointer-events:all sendiri. --}}
                        <div id="bpn-el-qr-watermark" class="bpn-abs-el" wire:ignore
                             style="position:absolute; top:210px; left:147px; width:500px; z-index:1; user-select:none; pointer-events:none;">
                            {{-- Handle: pointer-events:all agar bisa di-klik, meski parent pointer-events:none
                                 PENTING: tidak pakai opacity:0 inline — biarkan CSS atur ke 0.35 --}}
                            <div class="bpn-drag-handle"
                                 style="pointer-events:all;"
                                 @mousedown.prevent="startDrag($event)">⠿ Geser Watermark</div>
                            {{-- QR SVG dengan warna abu-abu terang (tanpa opacity CSS karena DOMPDF tidak support) --}}
                            <div style="pointer-events:none;">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(500)->margin(0)->color(240, 240, 240)->generate($simponi_data['ntpn'] ?? 'SIMPONI') !!}
                            </div>
                        </div>

                        {{-- ════ MAIN EDITABLE CONTENT ════
                             min-height dikurangi 60px untuk footer absolute di bawah --}}
                        <div id="bpn-editor"
                             contenteditable="true"
                             spellcheck="false"
                             wire:ignore
                             style="position:relative; z-index:5; min-height:980px; outline:none; caret-color:#1d4ed8; padding-bottom:60px;"
                             @input.debounce.600ms="onEdit()"
                             @keydown.ctrl.z.prevent="execCmd('undo')"
                             @keydown.ctrl.y.prevent="execCmd('redo')"
                             @keydown.ctrl.b.prevent="execCmd('bold')"
                             @keydown.ctrl.i.prevent="execCmd('italic')"
                             @keydown.ctrl.u.prevent="execCmd('underline')"
                             @paste.prevent="handlePaste($event)"
                             @mouseup="updateToolbarState()"
                             @keyup="updateToolbarState()">

                            {{-- Header spacer untuk logo & QR (absolute) --}}
                            <table style="width:100%; border-collapse:collapse; margin-bottom:20px; table-layout:fixed;">
                                <tr>
                                    <td style="width:90px; min-height:90px; vertical-align:top;">&nbsp;</td>
                                    <td style="vertical-align:top; padding-top:10px; font-size:13px; line-height:1.6;">
                                        {!! $simponi_data['header_1'] ?? 'Kementerian Keuangan RI' !!}<br>
                                        {!! $simponi_data['header_2'] ?? 'Direktorat Jenderal Anggaran' !!}<br>
                                        {!! $simponi_data['header_3'] ?? 'SISTEM INFORMASI PNBP ONLINE (SIMPONI)' !!}
                                    </td>
                                    <td style="width:95px;">&nbsp;</td>
                                </tr>
                            </table>

                            {{-- Title --}}
                            <div style="text-align:center; font-size:16px; font-weight:bold; margin:30px 0 40px 0; line-height:1.3;">
                                {!! $simponi_data['title_1'] ?? 'BUKTI PENERIMAAN NEGARA' !!}<br>
                                {!! $simponi_data['title_2'] ?? 'PENERIMAAN NEGARA BUKAN PAJAK (PNBP)' !!}
                            </div>

                            {{-- Data Pembayaran --}}
                            <div style="font-size:13px; font-weight:normal; margin-bottom:10px;">Data Pembayaran Tagihan :</div>
                            <table style="width:100%; border-collapse:collapse; margin-bottom:30px;">
                                <tbody>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Kode Billing</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;">{!! $simponi_data['kode_billing'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Tanggal Billing</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;">{!! $simponi_data['tanggal_billing'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Tanggal Kedaluwarsa</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;">{!! $simponi_data['tanggal_kedaluwarsa'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Tanggal Bayar</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;font-weight:bold;">{!! $simponi_data['tanggal_bayar'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Bank/Pos/<i>Fintech</i> Bayar</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;font-weight:bold;">{!! $simponi_data['bank_pos_fintech_bayar'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;font-style:italic;"><i>Channel Bayar</i></td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;font-weight:bold;font-style:italic;">{!! $simponi_data['channel_bayar'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Nama Wajib Setor/Wajib Bayar</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;">{!! $simponi_data['nama_wajib_setor'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Kementerian/Lembaga</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;font-weight:bold;">{!! $simponi_data['kementerian_lembaga'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Unit Eselon I</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;font-weight:bold;">{!! $simponi_data['unit_eselon_i'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Satuan Kerja</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;">{!! $simponi_data['satuan_kerja'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Total Disetor</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;">{!! $simponi_data['total_disetor'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Terbilang</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;font-style:italic;">{!! $simponi_data['terbilang'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Status</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;">{!! $simponi_data['status'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;font-weight:bold;">NTB</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;font-weight:bold;">{!! $simponi_data['ntb'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;font-weight:bold;">NTPN</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;font-weight:bold;">{!! $simponi_data['ntpn'] ?? '' !!}</td></tr>
                                </tbody>
                            </table>

                            {{-- Detail Pembayaran --}}
                            <div style="font-size:13px; font-weight:normal; margin-bottom:10px;">Detail Pembayaran Tagihan :</div>
                            <table style="width:100%; border-collapse:collapse; margin-bottom:30px;">
                                <tbody>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Jenis Setoran</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;">{!! $simponi_data['jenis_setoran'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Kode Akun</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;">{!! $simponi_data['kode_akun'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Jumlah Setoran</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;">{!! $simponi_data['jumlah_setoran'] ?? '' !!}</td></tr>
                                    <tr><td style="width:220px;padding:3px 0 3px 15px;vertical-align:top;">Keterangan</td><td style="width:15px;text-align:center;vertical-align:top;padding:3px 0;">:</td><td style="vertical-align:top;padding:3px 0;">{!! $simponi_data['keterangan'] ?? '' !!}</td></tr>
                                </tbody>
                            </table>

                        </div>{{-- /#bpn-editor --}}

                        {{-- ════ FOOTER: absolute di bawah kertas (di luar contenteditable) ════ --}}
                        <div wire:ignore
                             style="position:absolute; bottom:40px; left:50px; right:50px;
                                    font-size:11px; color:#000; font-family:Arial,Helvetica,sans-serif;
                                    border-top:2px solid #000; padding-top:8px; z-index:25;
                                    display:flex; justify-content:space-between; align-items:center;">
                            <div style="font-weight:bold; font-style:italic;">Tanggal Cetak : {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} WIB</div>
                            <div style="font-weight:bold; font-style:italic;">1/1</div>
                            <div style="font-weight:bold; font-style:italic;">SIMPONI</div>
                        </div>
                    </div>{{-- /#bpn-paper --}}
                </div>{{-- /.canvas --}}
            </div>{{-- /x-data bpnWordEditor --}}
            @endif
        @if(!$submitted)
        {{-- Audit Form --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden mt-6">
            <div class="border-b border-slate-100 dark:border-slate-700/50 px-5 py-4">
                <h3 class="font-semibold text-slate-800 dark:text-slate-100">Form Verifikasi</h3>
                <p class="text-xs text-slate-400 mt-0.5">Data terisi otomatis dari parsing SIMPONI. Verifikasi dan putuskan.</p>
            </div>

            <div class="p-5">
                {{-- Decision --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Keputusan Verifikasi <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="audit_decision" value="APPROVE" class="peer sr-only">
                            <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-3 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 peer-checked:text-green-700 dark:peer-checked:text-green-400 hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <span class="block text-sm font-bold">Setujui</span>
                                <span class="block text-xs font-medium opacity-70 mt-0.5">Sesuai Tagihan</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="audit_decision" value="REJECT" class="peer sr-only">
                            <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-3 text-center transition-all peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 peer-checked:text-red-700 dark:peer-checked:text-red-400 hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <span class="block text-sm font-bold">Tolak</span>
                                <span class="block text-xs font-medium opacity-70 mt-0.5">Tidak Sesuai / Kurang</span>
                            </div>
                        </label>
                    </div>
                    @error('audit_decision')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                
                {{-- Approval Fields: NTB & NTPN (conditional) --}}
                @if($audit_decision === 'APPROVE')
                <div class="mb-5 space-y-4 rounded-xl border border-green-200 bg-green-50 p-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <p class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-3">
                        <svg class="inline h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        NTB & NTPN
                    </p>
                    <div>
                        <label class="block text-sm font-semibold text-green-800 mb-1.5">
                            NTB (Nomor Transaksi Bank) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="simponi_data.ntb"
                               placeholder="Contoh: 260505030106"
                               class="w-full rounded-xl border border-green-200 bg-white px-4 py-2.5 text-sm font-mono text-slate-800 placeholder-slate-400 focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100 transition-all">
                        @error('simponi_data.ntb')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-green-800 mb-1.5">
                            NTPN (Nomor Transaksi Penerimaan Negara) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="simponi_data.ntpn"
                               placeholder="Contoh: A31673CIG6R5K8RH"
                               class="w-full rounded-xl border border-green-200 bg-white px-4 py-2.5 text-sm font-mono text-slate-800 placeholder-slate-400 focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100 transition-all">
                        @error('simponi_data.ntpn')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <p class="text-xs text-green-600 italic">
                        💡 Data NTB, NTPN, dan semua data pada form di sebelah kiri akan dicetak menjadi PDF SIMPONI yang baru.
                    </p>
                </div>
                @endif

                {{-- Rejection reason (conditional) --}}
                @if($audit_decision === 'REJECT')
                <div class="mb-5" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="rejection_reason"
                              id="rejection_reason"
                              rows="3"
                              placeholder="Jelaskan alasan penolakan kepada pelanggan..."
                              class="w-full rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-red-400 focus:bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-red-100 transition-all resize-none"></textarea>
                    @error('rejection_reason')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                @endif

                {{-- Submit --}}
                <button wire:click="submitAudit"
                        wire:loading.attr="disabled"
                        wire:target="submitAudit"
                        class="w-full flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-bold text-white shadow-md transition-all disabled:opacity-60
                               {{ $audit_decision === 'REJECT'
                                   ? 'bg-gradient-to-r from-red-600 to-rose-600 shadow-red-500/30 hover:from-red-700 hover:to-rose-700'
                                   : 'bg-gradient-to-r from-blue-600 to-indigo-600 shadow-blue-500/30 hover:from-blue-700 hover:to-indigo-700' }}">
                    <span wire:loading.remove wire:target="submitAudit">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    <span wire:loading wire:target="submitAudit">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    <span wire:loading.remove wire:target="submitAudit">Kirim Keputusan Audit</span>
                    <span wire:loading wire:target="submitAudit">Memproses...</span>
                </button>
            </div>
        </div>
        @else
        {{-- ══════════════════════════════════════
             PANEL SETELAH AUDIT SELESAI
             ══════════════════════════════════════ --}}
        <div class="mt-6 space-y-4"
             x-data="{ pdfReady: false, pdfUrl: '' }"
             @simponi-pdf-updated.window="pdfReady = true; pdfUrl = '{{ route('customer.payment.simponi', $payment) }}?' + Date.now()">

            {{-- Status Audit --}}
            <div class="rounded-xl px-5 py-4 flex items-center gap-4
                        {{ $audit_decision === 'APPROVE'
                            ? 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800'
                            : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' }}">
                <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center
                            {{ $audit_decision === 'APPROVE' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                    @if($audit_decision === 'APPROVE')
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    @else
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    @endif
                </div>
                <div>
                    <p class="font-bold text-sm {{ $audit_decision === 'APPROVE' ? 'text-emerald-800 dark:text-emerald-200' : 'text-red-800 dark:text-red-200' }}">
                        Audit {{ $audit_decision === 'APPROVE' ? 'Disetujui ✓' : 'Ditolak ✗' }}
                    </p>
                    <p class="text-xs {{ $audit_decision === 'APPROVE' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} mt-0.5">
                        {{ $audit_decision === 'APPROVE' ? 'PDF BPN telah digenerate dan bisa dicetak.' : 'Reservasi telah ditolak.' }}
                    </p>
                </div>
            </div>

            @if($audit_decision === 'APPROVE')
            {{-- STEP 1: Perbarui PDF dari Editan --}}
            <div class="rounded-xl border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 p-4 space-y-3">
                <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wide">
                    Langkah 1 — Simpan Editan ke PDF
                </p>
                <button onclick="savePaperAndReprint()"
                        wire:loading.attr="disabled"
                        class="w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/30
                               hover:bg-blue-700 hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed
                               flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" wire:loading.remove wire:target="reprintPdf" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <svg class="w-4 h-4 animate-spin" wire:loading wire:target="reprintPdf" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span wire:loading.remove wire:target="reprintPdf">⟳ Simpan Editan & Perbarui PDF</span>
                    <span wire:loading wire:target="reprintPdf">Memperbarui PDF...</span>
                </button>
                <p class="text-xs text-blue-500 dark:text-blue-400 text-center">
                    Klik tombol ini setiap kali selesai mengedit dokumen di atas
                </p>
            </div>

            {{-- STEP 2: Buka & Cetak PDF --}}
            <div class="rounded-xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 p-4 space-y-3">
                <p class="text-xs font-semibold text-green-700 dark:text-green-300 uppercase tracking-wide">
                    Langkah 2 — Buka & Cetak PDF
                </p>
                {{-- Link selalu ada untuk PDF awal, diperbarui saat reprintPdf berhasil --}}
                <a :href="pdfUrl || '{{ route('customer.payment.simponi', $payment) }}'"
                   target="_blank"
                   style="background-color: #16a34a; color: white;"
                   class="w-full rounded-xl bg-green-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-green-500/30
                          hover:bg-green-700 hover:shadow-xl transition-all
                          flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span x-text="pdfReady ? '✓ Buka PDF Terbaru (Cetak)' : 'Buka PDF & Cetak'"></span>
                </a>
                <p class="text-xs text-green-600 dark:text-green-400 text-center">
                    PDF terbuka di tab baru → tekan <kbd class="bg-green-100 dark:bg-green-800 px-1.5 rounded font-mono text-green-700 dark:text-green-300">Ctrl+P</kbd> untuk mencetak
                </p>
            </div>
            @endif

            {{-- Tombol Selesai / Kembali --}}
            <a href="{{ route('admin.reservations.index') }}"
               class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700
                      px-5 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200
                      hover:bg-slate-50 dark:hover:bg-slate-600 transition-all
                      flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Selesai &amp; Kembali ke Daftar Reservasi
            </a>
        </div>
        @endif

    </div> {{-- /END Right Column --}}

    {{-- Zoom Modal (Alpine.js) --}}
    @if($payment?->receipt_path)
    <div x-show="zoomOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="zoomOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/90 backdrop-blur-sm p-4"
         @click.self="zoomOpen = false">
        <div class="relative max-w-4xl w-full">
            <button @click="zoomOpen = false"
                    class="absolute -top-10 right-0 text-white/70 hover:text-white flex items-center gap-2 text-sm font-medium">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Tutup (Esc)
            </button>
            <img src="{{ route('admin.receipt.view', $payment->id) }}"
                 alt="Bukti Pembayaran (Zoom)"
                 class="w-full rounded-2xl shadow-2xl object-contain max-h-[80vh]">
        </div>
    </div>
    @endif
</div>

<script>
// ── Global: Simpan paper HTML dan generate PDF ───────────────────
function savePaperAndReprint() {
    const paper = document.getElementById('bpn-paper');
    if (!paper) { alert('Editor tidak ditemukan. Pastikan halaman sudah dimuat sempurna.'); return; }

    const clone = paper.cloneNode(true);

    // 1. Hapus semua drag handle (label ⠿)
    clone.querySelectorAll('.bpn-drag-handle').forEach(el => el.remove());

    // 2. Hilangkan atribut interaktif
    clone.querySelectorAll('[contenteditable]').forEach(el => el.removeAttribute('contenteditable'));
    clone.querySelectorAll('[spellcheck]').forEach(el => el.removeAttribute('spellcheck'));
    clone.querySelectorAll('[draggable]').forEach(el => el.removeAttribute('draggable'));

    // 3. Bersihkan inline style yang tidak perlu untuk PDF
    clone.querySelectorAll('[style]').forEach(el => {
        el.style.removeProperty('pointer-events');
        el.style.removeProperty('user-select');
        el.style.removeProperty('cursor');
    });

    // 4. FIX DOMPDF KEKURANGAN (box-sizing, flexbox, svg opacity)
    // a) Fix padding & box-sizing: hapus padding paper, pindahkan ke editor agar tidak overflow 794px
    clone.style.padding = '0';
    clone.style.boxSizing = 'content-box';
    const editorNode = clone.querySelector('#bpn-editor');
    if (editorNode) {
        // top:40px, right:50px, bottom:60px, left:50px
        editorNode.style.padding = '40px 50px 60px 50px';
    }

    // b) Fix Footer flexbox: DOMPDF tidak support display:flex, ganti ke float
    const footer = clone.querySelector('div[style*="display:flex"]');
    if (footer) {
        footer.style.display = 'block';
        if (footer.children.length >= 3) {
            const leftHtml = footer.children[0].innerHTML;
            const centerHtml = footer.children[1].innerHTML;
            const rightHtml = footer.children[2].innerHTML;
            footer.innerHTML = `
                <div style="float: left;">${leftHtml}</div>
                <div style="float: right;">${rightHtml}</div>
                <div style="text-align: center; margin: 0 auto; width: 100px;">${centerHtml}</div>
                <div style="clear: both;"></div>
            `;
        }
    }

    const paperHtml = clone.outerHTML;

    // 4. Kirim ke Livewire via component yang aktif
    const livewireEl = document.getElementById('bpn-paper').closest('[wire\\:id]');
    if (!livewireEl) { alert('Livewire component tidak ditemukan.'); return; }

    const wireId = livewireEl.getAttribute('wire:id');
    const component = Livewire.find(wireId);
    if (!component) { alert('Tidak dapat menemukan Livewire component.'); return; }

    component.call('saveBpnContent', paperHtml).then(() => {
        setTimeout(() => component.call('reprintPdf'), 400);
    });
}

document.addEventListener('alpine:init', () => {
    Alpine.data('bpnWordEditor', ($wire) => ({
        // ── State ──────────────────────────────────────────
        saved: true,
        isBold: false,
        isItalic: false,
        isUnderline: false,
        dragging: null,
        dragOffX: 0,
        dragOffY: 0,
        _onMove: null,
        _onStop: null,
        saveTimer: null,

        // ── Init ───────────────────────────────────────────
        init() {
            this._onMove = this.onDrag.bind(this);
            this._onStop = this.stopDrag.bind(this);
        },

        // ── Drag & Drop ────────────────────────────────────
        startDrag(event) {
            // Temukan .bpn-abs-el terdekat dari handle yang diklik
            const el = event.target.closest('.bpn-abs-el');
            if (!el) return;

            this.dragging = el;
            const paper  = document.getElementById('bpn-paper');
            const pRect  = paper.getBoundingClientRect();
            const eRect  = el.getBoundingClientRect();

            // Hitung posisi sekarang relatif terhadap paper
            let curLeft = eRect.left - pRect.left;
            let curTop  = eRect.top  - pRect.top;

            // Set absolute position (override right/center jika ada)
            el.style.right      = 'auto';
            el.style.marginLeft = '0';
            el.style.left       = curLeft + 'px';
            el.style.top        = curTop  + 'px';

            this.dragOffX = event.clientX - curLeft;
            this.dragOffY = event.clientY - curTop;

            el.style.cursor = 'grabbing';
            document.body.style.userSelect = 'none';

            document.addEventListener('mousemove', this._onMove);
            document.addEventListener('mouseup',   this._onStop);
        },

        onDrag(event) {
            if (!this.dragging) return;
            event.preventDefault();
            const paper  = document.getElementById('bpn-paper');
            const pRect  = paper.getBoundingClientRect();
            let newLeft  = event.clientX - this.dragOffX;
            let newTop   = event.clientY - this.dragOffY;

            // Clamp inside paper boundaries (loose)
            newLeft = Math.max(-50, Math.min(newLeft, pRect.width - 20));
            newTop  = Math.max(-20, Math.min(newTop,  1123 - 20));

            this.dragging.style.left = newLeft + 'px';
            this.dragging.style.top  = newTop  + 'px';
        },

        stopDrag() {
            if (!this.dragging) return;
            this.dragging.style.cursor = 'default';
            document.body.style.userSelect = '';

            // Simpan posisi di Alpine (tidak kirim ke server setiap drag
            // untuk hindari Livewire re-render yang mereset posisi).
            // Posisi akan dikirim bersama bpn_html saat onEdit() dipanggil.
            const elId = this.dragging.id;
            const top  = Math.round(parseFloat(this.dragging.style.top)  || 0);
            const left = Math.round(parseFloat(this.dragging.style.left) || 0);

            // Kirim posisi tanpa menunggu respons (fire-and-forget)
            $wire.saveElementPosition(elId, top, left);

            this.dragging = null;
            document.removeEventListener('mousemove', this._onMove);
            document.removeEventListener('mouseup',   this._onStop);
        },

        // ── Content Sync ───────────────────────────────────
        onEdit() {
            this.saved = false;
            clearTimeout(this.saveTimer);
            this.saveTimer = setTimeout(() => {
                const html = document.getElementById('bpn-editor')?.innerHTML || '';
                $wire.saveBpnContent(html).then(() => { this.saved = true; });
            }, 800);
        },

        handlePaste(event) {
            // Smart paste: try plain text first, fallback to sanitised HTML
            const cd   = event.clipboardData || window.clipboardData;
            const text = cd.getData('text/plain');
            if (text) {
                document.execCommand('insertText', false, text);
            } else {
                const html = cd.getData('text/html');
                // Strip dangerous tags, keep basic formatting
                const div  = document.createElement('div');
                div.innerHTML = html;
                ['script','style','head','meta','link','object','iframe'].forEach(tag => {
                    div.querySelectorAll(tag).forEach(el => el.remove());
                });
                document.execCommand('insertHTML', false, div.innerHTML);
            }
        },

        // ── Formatting ─────────────────────────────────────
        execCmd(cmd, value = null) {
            document.getElementById('bpn-editor')?.focus();
            document.execCommand(cmd, false, value);
            this.updateToolbarState();
        },

        setFontSize(sizePx) {
            const editor = document.getElementById('bpn-editor');
            if (!editor) return;
            editor.focus();

            const sel = window.getSelection();
            if (!sel || sel.rangeCount === 0) return;

            if (sel.isCollapsed) {
                // No selection — just set current font size marker
                document.execCommand('fontSize', false, '7');
                editor.querySelectorAll('font[size="7"]').forEach(el => {
                    el.removeAttribute('size');
                    el.style.fontSize = sizePx + 'px';
                });
                return;
            }

            // Wrap selection in a span with the desired font size
            const range = sel.getRangeAt(0);
            const span  = document.createElement('span');
            span.style.fontSize = sizePx + 'px';
            try {
                range.surroundContents(span);
            } catch (e) {
                // Range crosses node boundaries — use execCommand fallback
                document.execCommand('fontSize', false, '7');
                editor.querySelectorAll('font[size="7"]').forEach(el => {
                    el.removeAttribute('size');
                    el.style.fontSize = sizePx + 'px';
                });
            }
            this.updateToolbarState();
        },

        updateToolbarState() {
            try {
                this.isBold      = document.queryCommandState('bold');
                this.isItalic    = document.queryCommandState('italic');
                this.isUnderline = document.queryCommandState('underline');
            } catch(e) {}
        },
    }));
});
</script>
