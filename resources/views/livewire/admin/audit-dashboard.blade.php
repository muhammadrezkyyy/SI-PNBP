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
                        <iframe src="{{ route('admin.simponi.view', $payment->id) }}#toolbar=0" class="w-full h-[600px] border-0"></iframe>
                    </div>
                </div>
                @endif
            </div>

        {{-- RIGHT: Editable Data & Verifikasi --}}
        <div class="xl:col-span-7 space-y-6 flex flex-col">
            {{-- SIMPONI Editable Data --}}
            @if($payment?->simponi_pdf_path)
        <div class="flex-1 rounded-2xl border border-blue-200 dark:border-blue-800 bg-blue-50/30 dark:bg-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="border-b border-blue-100 dark:border-slate-700/50 bg-blue-50 dark:bg-slate-800 px-5 py-4 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-blue-800 dark:text-slate-100">Tagihan SIMPONI (Editable)</h3>
                    <p class="text-xs text-blue-600 dark:text-slate-400 mt-0.5">Data ini diekstrak dari PDF. Semua perubahan di sini akan dicetak ke PDF akhir.</p>
                </div>
            </div>
            <div class="flex-1 overflow-auto p-4 sm:p-8 relative bg-slate-100 dark:bg-slate-900/50">
                {{-- Toolbar MS Word Style --}}
                <div class="max-w-[794px] mx-auto bg-white border border-slate-300 shadow-sm mb-4 rounded flex items-center p-2 gap-1 sticky top-0 z-50">
                    <button type="button" onclick="document.execCommand('bold', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded font-bold text-black border border-transparent hover:border-slate-300" title="Bold (Ctrl+B)">B</button>
                    <button type="button" onclick="document.execCommand('italic', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded italic text-black border border-transparent hover:border-slate-300" title="Italic (Ctrl+I)">I</button>
                    <button type="button" onclick="document.execCommand('underline', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded underline text-black border border-transparent hover:border-slate-300" title="Underline (Ctrl+U)">U</button>
                    <div class="w-px h-6 bg-slate-300 mx-1"></div>
                    <button type="button" onclick="document.execCommand('justifyLeft', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded text-black border border-transparent hover:border-slate-300" title="Align Left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16" /></svg>
                    </button>
                    <button type="button" onclick="document.execCommand('justifyCenter', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded text-black border border-transparent hover:border-slate-300" title="Align Center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M4 18h16" /></svg>
                    </button>
                    <button type="button" onclick="document.execCommand('justifyRight', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded text-black border border-transparent hover:border-slate-300" title="Align Right">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M4 18h16" /></svg>
                    </button>
                    <div class="w-px h-6 bg-slate-300 mx-1"></div>
                    <button type="button" onclick="document.execCommand('insertUnorderedList', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded text-black border border-transparent hover:border-slate-300" title="Bullet List">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div class="ml-auto text-xs text-slate-500 italic px-2">Blok teks di bawah lalu klik tombol untuk mengedit</div>
                </div>

                {{-- KERTAS BPN WYSIWYG --}}
                <div class="bg-white text-black p-6 sm:p-8 shadow-sm relative group border border-slate-200 mx-auto" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; width: 794px; min-height: 1123px; display: flex; flex-direction: column;">
                    <style>
                        .bpn-paper .label {
                            width: 230px;
                            padding-left: 0;
                            vertical-align: top;
                            padding-bottom: 3px;
                        }
                        .bpn-paper .colon {
                            width: 15px;
                            text-align: center;
                            vertical-align: top;
                            padding-bottom: 3px;
                        }
                        .bpn-paper .value {
                            vertical-align: top;
                            padding-bottom: 3px;
                        }
                        .bpn-editable {
                            outline: none;
                            border: 1px solid transparent;
                            display: block;
                            min-height: 1.2em;
                            cursor: text;
                        }
                        .bpn-editable:hover, .bpn-editable:focus {
                            background-color: #f8fafc;
                            border: 1px dashed #cbd5e1;
                        }
                    </style>
                    <div class="bpn-paper text-[12px] leading-[1.4] relative z-10 flex-1 flex flex-col">
                        {{-- Background Watermark --}}
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-[-1] overflow-hidden pt-32" style="opacity: 0.04;">
                            <div class="w-[650px] h-[650px]">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(650)->margin(0)->generate($simponi_data['ntpn'] ?? 'SIMPONI') !!}
                            </div>
                        </div>

                        {{-- Header --}}
                        <div class="flex justify-between items-start mb-6">
                            <div class="flex items-start gap-4">
                                <img src="/images/kemenkeu_logo.png?v={{ time() }}" class="w-[110px] object-contain" alt="Logo">
                                <div class="flex-1 text-left pt-2 min-w-[300px]">
                                    <div contenteditable="true" class="bpn-editable font-bold text-[12px]" x-data @blur="$wire.set('simponi_data.header_1', $el.innerHTML)">{!! $simponi_data['header_1'] ?? '' !!}</div>
                                    <div contenteditable="true" class="bpn-editable font-bold text-[12px]" x-data @blur="$wire.set('simponi_data.header_2', $el.innerHTML)">{!! $simponi_data['header_2'] ?? '' !!}</div>
                                    <div contenteditable="true" class="bpn-editable font-bold text-[12px]" x-data @blur="$wire.set('simponi_data.header_3', $el.innerHTML)">{!! $simponi_data['header_3'] ?? '' !!}</div>
                                </div>
                            </div>
                            <div class="w-[110px] h-[110px] flex justify-end">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(95)->margin(0)->generate($simponi_data['ntpn'] ?? 'SIMPONI') !!}
                            </div>
                        </div>

                        {{-- Title --}}
                        <div class="text-center mb-6">
                            <div contenteditable="true" class="bpn-editable font-bold text-[14px] inline-block" x-data @blur="$wire.set('simponi_data.title_1', $el.innerHTML)">{!! $simponi_data['title_1'] ?? '' !!}</div><br>
                            <div contenteditable="true" class="bpn-editable font-bold text-[14px] inline-block" x-data @blur="$wire.set('simponi_data.title_2', $el.innerHTML)">{!! $simponi_data['title_2'] ?? '' !!}</div>
                        </div>

                        {{-- Table --}}
                        <div class="mb-2">Data Pembayaran Tagihan :</div>
                        <table class="w-full mb-8">
                            <tbody>
                                <tr><td class="label">Kode Billing</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.kode_billing', $el.innerHTML)">{!! $simponi_data['kode_billing'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Tanggal Billing</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.tanggal_billing', $el.innerHTML)">{!! $simponi_data['tanggal_billing'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Tanggal Kedaluwarsa</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.tanggal_kedaluwarsa', $el.innerHTML)">{!! $simponi_data['tanggal_kedaluwarsa'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Tanggal Bayar</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.tanggal_bayar', $el.innerHTML)">{!! $simponi_data['tanggal_bayar'] ?? '' !!}</div></td></tr>
                                <tr><td class="label italic">Bank/Pos/Fintech Bayar</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable font-bold" x-data @blur="$wire.set('simponi_data.bank_bayar', $el.innerHTML)">{!! $simponi_data['bank_bayar'] ?? '' !!}</div></td></tr>
                                <tr><td class="label italic">Channel Bayar</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable font-bold italic" x-data @blur="$wire.set('simponi_data.channel_bayar', $el.innerHTML)">{!! $simponi_data['channel_bayar'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Nama Wajib Setor/Wajib Bayar</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.nama_wajib_setor', $el.innerHTML)">{!! $simponi_data['nama_wajib_setor'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Kementerian/Lembaga</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable font-bold" x-data @blur="$wire.set('simponi_data.kementerian_lembaga', $el.innerHTML)">{!! $simponi_data['kementerian_lembaga'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Unit Eselon I</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable font-bold" x-data @blur="$wire.set('simponi_data.unit_eselon_i', $el.innerHTML)">{!! $simponi_data['unit_eselon_i'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Satuan Kerja</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.satuan_kerja', $el.innerHTML)">{!! $simponi_data['satuan_kerja'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Total Disetor</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.total_disetor', $el.innerHTML)">{!! $simponi_data['total_disetor'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Terbilang</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable italic" x-data @blur="$wire.set('simponi_data.terbilang', $el.innerHTML)">{!! $simponi_data['terbilang'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Status</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.status', $el.innerHTML)">{!! $simponi_data['status'] ?? '' !!}</div></td></tr>
                                <tr><td class="label font-bold">NTB</td><td class="colon font-bold">:</td><td class="value"><div contenteditable="true" class="bpn-editable font-bold" x-data @blur="$wire.set('simponi_data.ntb', $el.innerHTML)">{!! $simponi_data['ntb'] ?? '' !!}</div></td></tr>
                                <tr><td class="label font-bold">NTPN</td><td class="colon font-bold">:</td><td class="value"><div contenteditable="true" class="bpn-editable font-bold" x-data @blur="$wire.set('simponi_data.ntpn', $el.innerHTML)">{!! $simponi_data['ntpn'] ?? '' !!}</div></td></tr>
                            </tbody>
                        </table>

                        <div class="mb-2">Detail Pembayaran Tagihan :</div>
                        <table class="w-full">
                            <tbody>
                                <tr><td class="label">Jenis Setoran</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.jenis_setoran', $el.innerHTML)">{!! $simponi_data['jenis_setoran'] ?? '' !!}</div></td></tr>
                                <tr><td colspan="3" class="h-4"></td></tr>
                                <tr><td class="label">Kode Akun</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.kode_akun', $el.innerHTML)">{!! $simponi_data['kode_akun'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Jumlah Setoran</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.jumlah_setoran', $el.innerHTML)">{!! $simponi_data['jumlah_setoran'] ?? '' !!}</div></td></tr>
                                <tr><td class="label">Keterangan</td><td class="colon">:</td><td class="value"><div contenteditable="true" class="bpn-editable" x-data @blur="$wire.set('simponi_data.keterangan', $el.innerHTML)">{!! $simponi_data['keterangan'] ?? '' !!}</div></td></tr>
                            </tbody>
                        </table>

                        {{-- Footer --}}
                        <div class="mt-auto text-[10px] font-bold italic text-black flex items-center justify-between pt-2" style="font-family: Arial, sans-serif; border-top: 1px solid #000;">
                            <span>Tanggal Cetak : {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} WIB</span>
                            <span>1/1</span>
                            <span>SIMPONI</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        {{-- Tombol Cetak Ulang PDF jika sudah disetujui --}}
        <div class="mt-6">
            <button wire:click="reprintPdf" wire:loading.attr="disabled"
                    class="w-full rounded-xl bg-blue-600 px-6 py-3.5 text-center text-sm font-bold text-white shadow-xl shadow-blue-500/30 hover:bg-blue-700 hover:shadow-2xl transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span wire:loading.remove wire:target="reprintPdf">Cetak Ulang & Perbarui PDF SIMPONI Asli</span>
                <span wire:loading wire:target="reprintPdf">Memperbarui PDF...</span>
            </button>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 text-center">Jika Anda melakukan perubahan teks di editor atas, klik tombol ini agar perubahan tersebut tersimpan dan tertimpa ke file PDF aslinya.</p>
        </div>
        @endif

    </div> {{-- /END Right Column --}}
</div> {{-- /END Side-by-Side Grid Layout --}}

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
