

<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Laporan Keuangan & Reservasi</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Filter dan cetak rekapitulasi data sistem.</p>
        </div>
        <div>
            <button wire:click="exportPdf" 
                    class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/50 transition-all">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak PDF
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate" class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-100 dark:focus:border-blue-400">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate" class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-100 dark:focus:border-blue-400">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Fasilitas/Gedung</label>
                <select wire:model.live="buildingId" class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-100 dark:focus:border-blue-400">
                    <option value="">Semua Fasilitas</option>
                    @foreach($this->buildings as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Status Laporan</label>
                <select wire:model.live="status" class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-100 dark:focus:border-blue-400">
                    <option value="">Semua Status</option>
                    <option value="WAITING_PAYMENT">Belum Bayar (Waiting)</option>
                    <option value="VERIFYING">Menunggu Audit (Verifying)</option>
                    <option value="CONFIRMED">Lunas / Dikonfirmasi</option>
                    <option value="COMPLETED">Selesai Digunakan</option>
                    <option value="REJECTED">Ditolak / Batal</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Reservasi (Tampil)</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $this->summary['total_reservations'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm dark:border-emerald-800/50 dark:bg-emerald-900/20">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-200 text-emerald-700 dark:bg-emerald-800 dark:text-emerald-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-emerald-700 dark:text-emerald-400">Pendapatan Lunas (Confirmed/Completed)</p>
                <p class="text-2xl font-bold text-emerald-800 dark:text-emerald-100">Rp {{ number_format($this->summary['total_revenue'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-900/50 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Tgl Transaksi</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan / Instansi</th>
                        <th class="px-6 py-4 font-semibold">Gedung</th>
                        <th class="px-6 py-4 font-semibold">Jadwal</th>
                        <th class="px-6 py-4 font-semibold">NTPN</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @forelse($this->query->latest()->paginate(20) as $r)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4">{{ $r->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800 dark:text-slate-100">{{ $r->customer_name }}</div>
                                <div class="text-xs text-slate-500">{{ $r->customer_data['instansi'] ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $r->building ? $r->building->name : '-' }}</td>
                            <td class="px-6 py-4 text-xs">
                                {{ $r->start_date ? $r->start_date->format('d/m/y') : '-' }} s/d 
                                {{ $r->end_date ? $r->end_date->format('d/m/y') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $r->payment ? $r->payment->ntpn : '-' }}</td>
                            <td class="px-6 py-4">
                                @include('components.status-badge', ['status' => $r->status])
                            </td>
                            <td class="px-6 py-4 text-right font-medium">
                                Rp {{ number_format($r->payment ? $r->payment->nominal : 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                Data tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-700">
            {{ $this->query->latest()->paginate(20)->links() }}
        </div>
    </div>
</div>