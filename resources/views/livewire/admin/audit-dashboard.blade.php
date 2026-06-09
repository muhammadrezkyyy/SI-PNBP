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
    <div class="rounded-2xl border {{ $audit_decision === 'APPROVE' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }} p-8 text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full {{ $audit_decision === 'APPROVE' ? 'bg-green-100' : 'bg-red-100' }}">
            @if($audit_decision === 'APPROVE')
            <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            @else
            <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            @endif
        </div>
        <h2 class="text-xl font-bold {{ $audit_decision === 'APPROVE' ? 'text-green-800' : 'text-red-800' }}">
            Audit {{ $audit_decision === 'APPROVE' ? 'Disetujui' : 'Ditolak' }}
        </h2>
        <p class="text-sm {{ $audit_decision === 'APPROVE' ? 'text-green-600' : 'text-red-600' }} mt-2">
            Notifikasi WhatsApp telah dikirim ke pelanggan. Log audit telah dicatat.
        </p>
        <a href="{{ route('admin.reservations.index') }}"
           class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-800 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-900 transition-all">
            Kembali ke Daftar Reservasi
        </a>
    </div>

    @else

    {{-- Side-by-Side Layout --}}
    {{-- Mobile: flex-col | Tablet+: grid-cols-2 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- LEFT: Receipt Preview --}}
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

        {{-- RIGHT: Audit Form --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 dark:border-slate-700/50 px-5 py-4">
                <h3 class="font-semibold text-slate-800 dark:text-slate-100">Form Verifikasi</h3>
                <p class="text-xs text-slate-400 mt-0.5">Data terisi otomatis dari parsing SIMPONI. Verifikasi dan putuskan.</p>
            </div>

            <div class="p-5">
                {{-- SIMPONI Data (from parsed PDF) --}}
                <div class="mb-5 rounded-xl border border-blue-100 bg-blue-50 p-4">
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide mb-3">Data Tagihan SIMPONI (Parsed)</p>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-blue-600">Kode Billing</span>
                            <span class="font-mono font-bold text-blue-800">{{ $payment?->simponi_billing_code ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-blue-600">Nominal</span>
                            <span class="font-bold text-blue-800">{{ $payment?->nominal_formatted ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Verification Inputs --}}
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            Kode Billing Terverifikasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="verified_billing_code"
                               wire:model="verified_billing_code"
                               maxlength="15"
                               placeholder="15 digit kode billing"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-4 py-2.5 text-sm font-mono text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-400 focus:bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                        @error('verified_billing_code')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            Nominal Terverifikasi (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               id="verified_amount"
                               wire:model="verified_amount"
                               min="1"
                               placeholder="Nominal dalam Rupiah"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:border-blue-400 focus:bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                        @error('verified_amount')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Decision --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Keputusan Audit <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex cursor-pointer rounded-xl border-2 p-3.5 transition-all
                                      {{ $audit_decision === 'APPROVE' ? 'border-green-500 bg-green-50' : 'border-slate-200 dark:border-slate-700 hover:border-green-200' }}">
                            <input type="radio" wire:model.live="audit_decision" value="APPROVE" class="sr-only">
                            <div class="flex items-center gap-2.5">
                                <div class="h-8 w-8 rounded-full {{ $audit_decision === 'APPROVE' ? 'bg-green-500' : 'bg-slate-100 dark:bg-slate-800/80' }} flex items-center justify-center transition-colors">
                                    <svg class="h-4 w-4 {{ $audit_decision === 'APPROVE' ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold {{ $audit_decision === 'APPROVE' ? 'text-green-800' : 'text-slate-600 dark:text-slate-300' }}">Setujui</p>
                                    <p class="text-xs {{ $audit_decision === 'APPROVE' ? 'text-green-600' : 'text-slate-400' }}">Konfirmasi reservasi</p>
                                </div>
                            </div>
                        </label>

                        <label class="relative flex cursor-pointer rounded-xl border-2 p-3.5 transition-all
                                      {{ $audit_decision === 'REJECT' ? 'border-red-500 bg-red-50' : 'border-slate-200 dark:border-slate-700 hover:border-red-200' }}">
                            <input type="radio" wire:model.live="audit_decision" value="REJECT" class="sr-only">
                            <div class="flex items-center gap-2.5">
                                <div class="h-8 w-8 rounded-full {{ $audit_decision === 'REJECT' ? 'bg-red-500' : 'bg-slate-100 dark:bg-slate-800/80' }} flex items-center justify-center transition-colors">
                                    <svg class="h-4 w-4 {{ $audit_decision === 'REJECT' ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold {{ $audit_decision === 'REJECT' ? 'text-red-800' : 'text-slate-600 dark:text-slate-300' }}">Tolak</p>
                                    <p class="text-xs {{ $audit_decision === 'REJECT' ? 'text-red-600' : 'text-slate-400' }}">Kembalikan ke verifikasi</p>
                                </div>
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
                        Data Pembayaran (Isi dari Bukti BPN)
                    </p>
                    <div>
                        <label class="block text-sm font-semibold text-green-800 mb-1.5">
                            NTB (Nomor Transaksi Bank) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="ntb"
                               wire:model="ntb"
                               placeholder="Contoh: 260505030106"
                               class="w-full rounded-xl border border-green-200 bg-white px-4 py-2.5 text-sm font-mono text-slate-800 placeholder-slate-400 focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100 transition-all">
                        @error('ntb')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-green-800 mb-1.5">
                            NTPN (Nomor Transaksi Penerimaan Negara) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="ntpn"
                               wire:model="ntpn"
                               placeholder="Contoh: A31673CIG6R5K8RH"
                               class="w-full rounded-xl border border-green-200 bg-white px-4 py-2.5 text-sm font-mono text-slate-800 placeholder-slate-400 focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100 transition-all">
                        @error('ntpn')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <p class="text-xs text-green-600 italic">
                        💡 Salin NTB & NTPN dari bukti pembayaran (BPN) pelanggan. Data ini akan otomatis dicetak ke file tagihan SIMPONI.
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

    </div>
    @endif

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
