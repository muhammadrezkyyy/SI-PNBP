<div class="max-w-7xl mx-auto" x-data="{ zoomOpen: @entangle('zoom_open') }">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Audit Pembayaran</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Verifikasi bukti pembayaran dan tagihan SIMPONI secara side-by-side.</p>
        </div>
        <a href="{{ route('admin.reservations.index') }}"
           class="flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:bg-slate-900/50 transition-all resize-y"></textarea>
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
                        class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-700 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:bg-slate-900/50 transition-all resize-y"></textarea>
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
            {{-- ╔══ FORM EDIT DATA SIMPONI ══╗ --}}
            @if($payment?->simponi_pdf_path && !$submitted)
            <div class="rounded-2xl border border-blue-200 dark:border-blue-800/60 bg-white dark:bg-slate-800 shadow-sm overflow-hidden flex flex-col mb-6">

                {{-- Header Card --}}
                <div class="border-b border-blue-100 dark:border-slate-700/50 bg-gradient-to-r from-blue-50 to-indigo-50/50 dark:from-slate-800 dark:to-slate-800 px-5 py-3.5 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/40">
                            <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-blue-900 dark:text-slate-100 text-sm">Edit Data Tagihan SIMPONI</h3>
                            <p class="text-[11px] text-blue-500 dark:text-slate-400 leading-tight mt-0.5">Font & posisi sama persis dengan output PDF yang akan dicetak</p>
                        </div>
                    </div>
                    <span class="hidden sm:inline-flex items-center gap-1 rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 text-[10px] font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wider">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                        Live Preview
                    </span>
                </div>

                <div class="p-4 space-y-5">

                    {{-- ═══ LABEL STYLE ═══
                         CSS inline agar font Arial 11px persis sama dengan PDF --}}
                    <style>
                        .simponi-edit-table { width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 11px; }
                        .simponi-edit-table tr { border-bottom: 1px solid #e2e8f0; }
                        .simponi-edit-table tr:last-child { border-bottom: none; }
                        .simponi-edit-table tr:hover { background: #f8faff; }
                        .dark .simponi-edit-table tr:hover { background: rgba(99,102,241,0.05); }
                        .simponi-edit-label {
                            width: 200px; min-width: 160px; max-width: 200px;
                            padding: 6px 8px 6px 10px;
                            font-size: 11px; font-family: Arial, Helvetica, sans-serif;
                            color: #475569; font-weight: 600;
                            vertical-align: top;
                            background: #f8fafc;
                            white-space: nowrap;
                            border-right: 1px solid #e2e8f0;
                        }
                        .dark .simponi-edit-label { background: rgba(30,41,59,0.5); color: #94a3b8; border-color: rgba(71,85,105,0.3); }
                        .simponi-edit-colon {
                            width: 16px; text-align: center; vertical-align: top;
                            padding: 6px 2px; font-size: 11px; font-family: Arial, Helvetica, sans-serif;
                            color: #64748b;
                        }
                        .simponi-edit-value { padding: 4px 8px; vertical-align: top; }
                        .simponi-edit-ta {
                            width: 100%; border: none; outline: none; resize: none; background: transparent;
                            font-family: Arial, Helvetica, sans-serif; font-size: 11px;
                            color: #1e293b; line-height: 1.5;
                            padding: 2px 4px; border-radius: 4px;
                            transition: background 0.15s;
                        }
                        .simponi-edit-ta:focus { background: #eff6ff; box-shadow: 0 0 0 2px #3b82f6; border-radius: 4px; }
                        .dark .simponi-edit-ta { color: #e2e8f0; }
                        .dark .simponi-edit-ta:focus { background: rgba(59,130,246,0.1); }
                        .simponi-edit-ta.is-bold { font-weight: bold; }
                        .simponi-edit-ta.is-italic { font-style: italic; }
                        .simponi-section-badge {
                            display: inline-flex; align-items: center; gap: 6px;
                            font-size: 10px; font-weight: 700; letter-spacing: 0.08em;
                            text-transform: uppercase; padding: 3px 10px 3px 8px;
                            border-radius: 999px; margin-bottom: 6px;
                        }
                        .simponi-section-badge .dot { width: 6px; height: 6px; border-radius: 50%; }
                    </style>

                    {{-- ── SEKSI 1: KOP SURAT ── --}}
                    <div>
                        <div class="simponi-section-badge bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                            <span class="dot bg-blue-500"></span> Kop Surat &amp; Judul
                        </div>
                        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs">
                            <table class="simponi-edit-table">
                                <tbody>
                                    @foreach([
                                        ['key'=>'header_1','label'=>'Kementerian/Lembaga','rows'=>1,'bold'=>true],
                                        ['key'=>'header_2','label'=>'Direktorat Jenderal','rows'=>1,'bold'=>true],
                                        ['key'=>'header_3','label'=>'Nama Sistem','rows'=>1,'bold'=>true],
                                        ['key'=>'title_1', 'label'=>'Judul Dokumen 1','rows'=>1,'bold'=>true],
                                        ['key'=>'title_2', 'label'=>'Judul Dokumen 2','rows'=>1,'bold'=>true],
                                    ] as $f)
                                    <tr>
                                        <td class="simponi-edit-label">{{ $f['label'] }}</td>
                                        <td class="simponi-edit-colon">:</td>
                                        <td class="simponi-edit-value">
                                            <textarea wire:model="simponi_data.{{ $f['key'] }}"
                                                rows="{{ $f['rows'] }}"
                                                class="simponi-edit-ta {{ isset($f['bold'])&&$f['bold'] ? 'is-bold':'' }} {{ isset($f['italic'])&&$f['italic'] ? 'is-italic':'' }}"
                                                x-data x-init="$el.style.height=$el.scrollHeight+'px'"
                                                @input="$el.style.height='auto';$el.style.height=$el.scrollHeight+'px'"></textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ── SEKSI 2: DATA PEMBAYARAN TAGIHAN ── --}}
                    <div>
                        <div class="simponi-section-badge bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300">
                            <span class="dot bg-indigo-500"></span> Data Pembayaran Tagihan
                        </div>
                        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs">
                            <table class="simponi-edit-table">
                                <tbody>
                                    @foreach([
                                        ['key'=>'kode_billing',          'label'=>'Kode Billing',               'rows'=>1, 'bold'=>true],
                                        ['key'=>'tanggal_billing',       'label'=>'Tanggal Billing',            'rows'=>1],
                                        ['key'=>'tanggal_kedaluwarsa',   'label'=>'Tanggal Kedaluwarsa',        'rows'=>1],
                                        ['key'=>'tanggal_bayar',         'label'=>'Tanggal Bayar',              'rows'=>1, 'bold'=>true],
                                        ['key'=>'bank_pos_fintech_bayar','label'=>'Bank/Pos/Fintech Bayar',     'rows'=>1, 'bold'=>true],
                                        ['key'=>'channel_bayar',         'label'=>'Channel Bayar',              'rows'=>1, 'bold'=>true, 'italic'=>true],
                                        ['key'=>'nama_wajib_setor',      'label'=>'Nama Wajib Setor/Bayar',     'rows'=>2],
                                        ['key'=>'kementerian_lembaga',   'label'=>'Kementerian/Lembaga',        'rows'=>2, 'bold'=>true],
                                        ['key'=>'unit_eselon_i',         'label'=>'Unit Eselon I',              'rows'=>2, 'bold'=>true],
                                        ['key'=>'satuan_kerja',          'label'=>'Satuan Kerja',               'rows'=>2],
                                        ['key'=>'total_disetor',         'label'=>'Total Disetor',              'rows'=>1],
                                        ['key'=>'terbilang',             'label'=>'Terbilang',                  'rows'=>2, 'italic'=>true],
                                        ['key'=>'status',                'label'=>'Status',                     'rows'=>1],
                                        ['key'=>'ntb',                   'label'=>'NTB',                        'rows'=>1, 'bold'=>true],
                                        ['key'=>'ntpn',                  'label'=>'NTPN',                       'rows'=>1, 'bold'=>true],
                                    ] as $f)
                                    <tr>
                                        <td class="simponi-edit-label {{ isset($f['bold'])&&$f['bold'] ? 'font-bold':'' }}">{{ $f['label'] }}</td>
                                        <td class="simponi-edit-colon">:</td>
                                        <td class="simponi-edit-value">
                                            <textarea wire:model="simponi_data.{{ $f['key'] }}"
                                                rows="{{ $f['rows'] }}"
                                                class="simponi-edit-ta {{ isset($f['bold'])&&$f['bold'] ? 'is-bold':'' }} {{ isset($f['italic'])&&$f['italic'] ? 'is-italic':'' }}"
                                                x-data x-init="$el.style.height=$el.scrollHeight+'px'"
                                                @input="$el.style.height='auto';$el.style.height=$el.scrollHeight+'px'"></textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ── SEKSI 3: DETAIL PEMBAYARAN ── --}}
                    <div>
                        <div class="simponi-section-badge bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300">
                            <span class="dot bg-violet-500"></span> Detail Pembayaran Tagihan
                        </div>
                        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs">
                            <table class="simponi-edit-table">
                                <tbody>
                                    @foreach([
                                        ['key'=>'jenis_setoran', 'label'=>'Jenis Setoran',  'rows'=>2],
                                        ['key'=>'kode_akun',     'label'=>'Kode Akun',       'rows'=>1],
                                        ['key'=>'jumlah_setoran','label'=>'Jumlah Setoran',  'rows'=>1],
                                        ['key'=>'keterangan',    'label'=>'Keterangan',      'rows'=>3],
                                    ] as $f)
                                    <tr>
                                        <td class="simponi-edit-label">{{ $f['label'] }}</td>
                                        <td class="simponi-edit-colon">:</td>
                                        <td class="simponi-edit-value">
                                            <textarea wire:model="simponi_data.{{ $f['key'] }}"
                                                rows="{{ $f['rows'] }}"
                                                class="simponi-edit-ta {{ isset($f['italic'])&&$f['italic'] ? 'is-italic':'' }}"
                                                x-data x-init="$el.style.height=$el.scrollHeight+'px'"
                                                @input="$el.style.height='auto';$el.style.height=$el.scrollHeight+'px'"></textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            @endif





        @if(!$submitted)
        {{-- Audit Form --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden mt-6">
            <div class="border-b border-slate-100 dark:border-slate-700/50 px-5 py-4">
                <h3 class="font-semibold text-slate-800 dark:text-slate-100">Keputusan Audit</h3>
                <p class="text-xs text-slate-400 mt-0.5">Verifikasi dan putuskan. Data form di atas akan dicetak permanen ke dalam PDF baru jika disetujui.</p>
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
                                <span class="block text-xs font-medium opacity-70 mt-0.5">Data Valid</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="audit_decision" value="REJECT" class="peer sr-only">
                            <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-3 text-center transition-all peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 peer-checked:text-red-700 dark:peer-checked:text-red-400 hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <span class="block text-sm font-bold">Tolak</span>
                                <span class="block text-xs font-medium opacity-70 mt-0.5">Tidak Valid</span>
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
                        Masukkan NTB & NTPN
                    </p>
                    <div>
                        <label class="block text-sm font-bold text-green-800 mb-1.5 uppercase tracking-wide">
                            NTB (Nomor Transaksi Bank) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="simponi_data.ntb"
                               placeholder="Contoh: 260505030106"
                               class="w-full rounded-xl border border-green-200 bg-white px-4 py-2.5 text-sm font-mono text-slate-800 placeholder-slate-400 focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100 transition-all resize-y"></textarea>
                        @error('simponi_data.ntb')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-green-800 mb-1.5 uppercase tracking-wide">
                            NTPN <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="simponi_data.ntpn"
                               placeholder="Contoh: A31673CIG6R5K8RH"
                               class="w-full rounded-xl border border-green-200 bg-white px-4 py-2.5 text-sm font-mono text-slate-800 placeholder-slate-400 focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100 transition-all resize-y"></textarea>
                        @error('simponi_data.ntpn')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
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
                    <span wire:loading.remove wire:target="submitAudit">Kirim Keputusan & Generate PDF Terkunci</span>
                    <span wire:loading wire:target="submitAudit">Memproses...</span>
                </button>
            </div>
        </div>
        @else
        {{-- ══════════════════════════════════════
             PANEL SETELAH AUDIT SELESAI
             ══════════════════════════════════════ --}}
        <div class="mt-6 space-y-4">
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
                        {{ $audit_decision === 'APPROVE' ? 'PDF BPN dengan format terkunci berhasil digenerate dan bisa dicetak.' : 'Reservasi telah ditolak.' }}
                    </p>
                </div>
            </div>

            @if($audit_decision === 'APPROVE')
            {{-- STEP 2: Buka & Cetak PDF --}}
            <div class="rounded-xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 p-4 space-y-3">
                <a href="{{ route('customer.payment.simponi', $payment) }}"
                   target="_blank"
                   style="background-color: #16a34a; color: white;"
                   class="w-full rounded-xl bg-green-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-green-500/30
                          hover:bg-green-700 hover:shadow-xl transition-all
                          flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Buka PDF & Cetak
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
