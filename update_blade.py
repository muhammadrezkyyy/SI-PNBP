import sys

file_path = r'd:\laragon\www\SI-PNBP\resources\views\livewire\admin\audit-dashboard.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    lines = f.readlines()

new_content = []
for i, line in enumerate(lines):
    if '{{-- RIGHT: Editable Data & Verifikasi --}}' in line:
        new_content.append(line)
        break
    new_content.append(line)

form_html = """        <div class="xl:col-span-7 space-y-6 flex flex-col">
            {{-- Form Edit Data SIMPONI --}}
            @if($payment?->simponi_pdf_path && !$submitted)
            <div class="rounded-2xl border border-blue-200 dark:border-blue-800 bg-white dark:bg-slate-800 shadow-sm overflow-hidden flex flex-col mb-6">
                <div class="border-b border-blue-100 dark:border-slate-700/50 bg-blue-50/50 dark:bg-slate-800 px-5 py-4">
                    <h3 class="font-semibold text-blue-800 dark:text-slate-100 flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Data Tagihan SIMPONI
                    </h3>
                    <p class="text-xs text-blue-600 dark:text-slate-400 mt-1">Ubah teks isian SIMPONI jika diperlukan. Format logo, barcode, dan tata letak dokumen sudah otomatis terkunci oleh sistem.</p>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Kode Billing</label>
                        <input type="text" wire:model="simponi_data.kode_billing" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Tanggal Billing</label>
                        <input type="text" wire:model="simponi_data.tanggal_billing" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Tgl Kedaluwarsa</label>
                        <input type="text" wire:model="simponi_data.tanggal_kedaluwarsa" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Tanggal Bayar</label>
                        <input type="text" wire:model="simponi_data.tanggal_bayar" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Bank/Fintech Bayar</label>
                        <input type="text" wire:model="simponi_data.bank_pos_fintech_bayar" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Channel Bayar</label>
                        <input type="text" wire:model="simponi_data.channel_bayar" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Nama Wajib Setor</label>
                        <input type="text" wire:model="simponi_data.nama_wajib_setor" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Total Disetor</label>
                        <input type="text" wire:model="simponi_data.total_disetor" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Status</label>
                        <input type="text" wire:model="simponi_data.status" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Terbilang</label>
                        <input type="text" wire:model="simponi_data.terbilang" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    
                    {{-- Detail Pembayaran --}}
                    <div class="col-span-1 md:col-span-2 mt-4 pt-5 border-t border-slate-100 dark:border-slate-700">
                        <h4 class="font-bold text-slate-800 dark:text-slate-200 mb-4 text-sm flex items-center gap-2">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Detail Pembayaran Tagihan
                        </h4>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Jenis Setoran</label>
                        <input type="text" wire:model="simponi_data.jenis_setoran" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Kode Akun</label>
                        <input type="text" wire:model="simponi_data.kode_akun" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Jumlah Setoran</label>
                        <input type="text" wire:model="simponi_data.jumlah_setoran" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Keterangan</label>
                        <textarea wire:model="simponi_data.keterangan" rows="2" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></textarea>
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
                               class="w-full rounded-xl border border-green-200 bg-white px-4 py-2.5 text-sm font-mono text-slate-800 placeholder-slate-400 focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100 transition-all">
                        @error('simponi_data.ntb')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-green-800 mb-1.5 uppercase tracking-wide">
                            NTPN <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="simponi_data.ntpn"
                               placeholder="Contoh: A31673CIG6R5K8RH"
                               class="w-full rounded-xl border border-green-200 bg-white px-4 py-2.5 text-sm font-mono text-slate-800 placeholder-slate-400 focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100 transition-all">
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
"""

new_content.append(form_html)

with open(file_path, 'w', encoding='utf-8') as f:
    f.writelines(new_content)

print('Success')
