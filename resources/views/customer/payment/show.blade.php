{{-- Tampilan Form Pelaporan Pembayaran untuk Pelanggan --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Reservasi — SI-RESERVASI PNBP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="antialiased">
@php
    $status = $reservation->status->value;
    $isPaid = in_array($status, ['CONFIRMED', 'COMPLETED']);
    $isVerifying = $status === 'VERIFYING';
    $canSubmit = $status === 'WAITING_PAYMENT';
    $appLogo = \App\Models\AppSetting::getVal('app_logo_path');
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 relative overflow-hidden">

    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-[0.03]"
         style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    {{-- Glow Effects --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-blue-500/10 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 right-0 w-[400px] h-[300px] bg-indigo-500/10 rounded-full blur-[100px]"></div>

    <div class="relative z-10 py-8 px-4 sm:px-6">
        <div class="max-w-lg mx-auto">

            {{-- Logo & Header --}}
            <div class="text-center mb-8">
                @if($appLogo)
                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-2xl bg-white shadow-2xl shadow-blue-500/30 mb-4 ring-4 ring-white/10 overflow-hidden">
                        <img src="{{ asset('storage/' . $appLogo) }}" alt="Logo" class="w-full h-full object-contain p-2">
                    </div>
                @else
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-2xl shadow-blue-500/30 mb-4 ring-4 ring-white/10">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                @endif
                <h1 class="text-2xl font-bold text-white tracking-tight">Pembayaran Reservasi</h1>
                <p class="text-sm text-slate-400 mt-1">{{ \App\Models\AppSetting::getVal('app_name', 'SI-RESERVASI PNBP') }}</p>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-500/30 bg-green-500/10 backdrop-blur-sm p-4 flex items-start gap-3">
                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-green-500/20 flex items-center justify-center">
                    <svg class="h-4 w-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-green-300">Berhasil!</p>
                    <p class="text-xs text-green-400/80 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            {{-- Kartu Tagihan --}}
            <div class="mb-6 rounded-2xl overflow-hidden shadow-2xl shadow-blue-900/30">
                {{-- Header Gradient --}}
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-6 relative overflow-hidden">
                    {{-- Decorative circles --}}
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/5 rounded-full"></div>
                    <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-white/5 rounded-full"></div>

                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-5">
                            <p class="text-xs font-semibold text-blue-200 uppercase tracking-widest">Tagihan SIMPONI</p>
                            {{-- Status Badge --}}
                            @if($isPaid)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-200 border border-green-400/30 backdrop-blur-sm">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-400 animate-pulse"></span>
                                    Sudah Dibayar
                                </span>
                            @elseif($isVerifying)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-200 border border-amber-400/30 backdrop-blur-sm">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                    Menunggu Verifikasi
                                </span>
                            @elseif($canSubmit)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-200 border border-red-400/30 backdrop-blur-sm">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-400 animate-pulse"></span>
                                    Belum Dibayar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-500/20 text-slate-300 border border-slate-400/30 backdrop-blur-sm">
                                    Dibatalkan
                                </span>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-blue-300/70 mb-1">Kode Billing</p>
                                <p class="font-mono text-2xl sm:text-3xl font-black text-white tracking-[0.15em] leading-none">{{ $reservation->payment->simponi_billing_code }}</p>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-blue-300/70 mb-1">Nominal Pembayaran</p>
                                    <p class="text-xl sm:text-2xl font-bold text-white">{{ $reservation->payment->nominal_formatted }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] uppercase tracking-widest text-blue-300/70 mb-1">Reservasi</p>
                                    <p class="text-sm font-semibold text-blue-200">{{ $reservation->building?->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bottom Bar --}}
                @if($reservation->payment->simponi_pdf_path)
                <div class="bg-slate-800/80 backdrop-blur-sm px-6 py-3 flex items-center justify-between border-t border-white/5">
                    <a href="{{ route('customer.payment.simponi', $reservation->payment) }}" target="_blank"
                       class="inline-flex items-center gap-2 text-xs font-semibold {{ $isPaid ? 'text-green-400 hover:text-green-300' : 'text-blue-400 hover:text-blue-300' }} transition-colors group">
                        <svg class="h-4 w-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        {{ $isPaid ? 'Lihat Bukti Pembayaran (Lunas)' : 'Lihat Dokumen Tagihan' }}
                    </a>
                    <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
                @endif
            </div>

            {{-- Langkah Pembayaran --}}
            @if($canSubmit)
            <div class="mb-6 rounded-2xl border border-slate-700/50 bg-slate-800/50 backdrop-blur-sm shadow-lg overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-700/50">
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <svg class="h-4 w-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Cara Pembayaran
                    </h2>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        @foreach([
                            'Buka aplikasi bank, ATM, atau e-wallet Anda',
                            'Pilih menu *Pajak / PNBP / MPN G3*',
                            'Masukkan Kode Billing 15 digit di atas',
                            'Konfirmasi nominal dan selesaikan transaksi',
                            'Simpan atau foto bukti pembayaran Anda',
                            'Upload foto bukti pembayaran di form bawah',
                        ] as $i => $step)
                        <div class="flex items-start gap-3 group">
                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-blue-500/10 text-blue-400 text-xs font-bold border border-blue-500/20 group-hover:bg-blue-500/20 transition-colors">
                                {{ $i + 1 }}
                            </div>
                            <p class="text-sm text-slate-300 pt-0.5 leading-relaxed">{!! str_replace(['*'], ['<span class="font-semibold text-white">'], str_replace(['*'], ['</span>'], $step)) !!}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Form Pelaporan Pembayaran --}}
            @if($canSubmit)
            <div class="rounded-2xl border border-slate-700/50 bg-slate-800/50 backdrop-blur-sm shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-700/50 bg-slate-800/80">
                    <h2 class="font-bold text-white flex items-center gap-2">
                        <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Lapor Pembayaran
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Upload bukti pembayaran Anda.</p>
                </div>

                <form method="POST"
                      action="{{ route('customer.payment.store', $reservation) }}"
                      enctype="multipart/form-data"
                      class="p-6 space-y-6">
                    @csrf

                    {{-- Error Validasi --}}
                    @if($errors->any())
                    <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-4">
                        <p class="text-sm font-semibold text-red-300 mb-2">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                            <li class="text-xs text-red-400">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Upload Bukti Pembayaran --}}
                    <div x-data="{ fileName: '', preview: null }">
                        <label class="block text-sm font-semibold text-slate-200 mb-2">
                            Bukti Pembayaran <span class="text-red-400">*</span>
                        </label>
                        <div @click="$refs.fileInput.click()"
                             class="relative rounded-xl border-2 border-dashed border-slate-600 bg-slate-900/50 p-6 text-center cursor-pointer hover:border-blue-500/50 hover:bg-blue-500/5 transition-all duration-300 @error('receipt_image') border-red-500/50 @enderror">

                            <input type="file"
                                   name="receipt_image"
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="sr-only"
                                   x-ref="fileInput"
                                   @change="
                                       fileName = $event.target.files[0]?.name;
                                       const reader = new FileReader();
                                       reader.onload = (e) => preview = e.target.result;
                                       reader.readAsDataURL($event.target.files[0]);
                                   ">

                            <div x-show="!preview">
                                <div class="mx-auto h-14 w-14 rounded-2xl bg-slate-700/50 flex items-center justify-center mb-3 ring-2 ring-slate-600/50">
                                    <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-300">Klik untuk pilih foto bukti pembayaran</p>
                                <p class="text-xs text-slate-500 mt-1">JPEG, PNG, JPG — Maks. 5 MB</p>
                            </div>

                            <div x-show="preview" class="space-y-3" x-cloak>
                                <img :src="preview" alt="Preview" class="mx-auto max-h-52 rounded-xl object-contain ring-2 ring-slate-600/50 shadow-lg">
                                <div>
                                    <p class="text-xs text-slate-400" x-text="fileName"></p>
                                    <p class="text-xs text-blue-400 font-medium mt-1">Klik untuk ganti foto</p>
                                </div>
                            </div>
                        </div>
                        @error('receipt_image')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 px-6 py-4 text-sm font-bold text-white shadow-xl shadow-green-500/20 hover:from-emerald-600 hover:to-green-700 hover:shadow-green-500/30 active:scale-[0.98] transition-all duration-200">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Kirim Bukti Pembayaran
                    </button>
                </form>
            </div>

            {{-- Status: Sudah mengirim bukti / Menunggu Verifikasi --}}
            @elseif($isVerifying)
            <div class="rounded-2xl border border-amber-500/20 bg-slate-800/50 backdrop-blur-sm shadow-lg overflow-hidden">
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-amber-500/10 mb-5 ring-4 ring-amber-500/10">
                        <svg class="h-10 w-10 text-amber-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Menunggu Verifikasi</h3>
                    <p class="text-sm text-slate-400 max-w-xs mx-auto leading-relaxed">Bukti pembayaran Anda telah diterima dan sedang dalam proses pemeriksaan oleh Admin. Anda akan dihubungi jika sudah selesai.</p>

                    @if($reservation->payment->ntpn)
                    <div class="mt-6 p-4 rounded-xl bg-slate-900/50 border border-slate-700/50">
                        <p class="text-[10px] uppercase tracking-widest text-slate-500 mb-1">NTPN yang dilaporkan</p>
                        <p class="font-mono text-lg font-bold text-white tracking-[0.2em]">{{ $reservation->payment->ntpn }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Status: Sudah Dibayar / Confirmed --}}
            @elseif($isPaid)
            <div class="rounded-2xl border border-green-500/20 bg-slate-800/50 backdrop-blur-sm shadow-lg overflow-hidden">
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-green-500/10 mb-5 ring-4 ring-green-500/10">
                        <svg class="h-10 w-10 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Pembayaran Dikonfirmasi</h3>
                    <p class="text-sm text-slate-400 max-w-xs mx-auto leading-relaxed">Pembayaran Anda telah berhasil diverifikasi. Terima kasih telah menyelesaikan pembayaran.</p>

                    @if($reservation->payment->ntpn)
                    <div class="mt-6 p-4 rounded-xl bg-slate-900/50 border border-green-500/20">
                        <p class="text-[10px] uppercase tracking-widest text-green-400/60 mb-1">NTPN Terverifikasi</p>
                        <p class="font-mono text-lg font-bold text-green-300 tracking-[0.2em]">{{ $reservation->payment->ntpn }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Footer --}}
            <p class="text-center text-xs text-slate-600 mt-8 pb-4">
                &copy; {{ date('Y') }} SI-RESERVASI PNBP &mdash; Seluruh hak dilindungi.
            </p>

        </div>
    </div>
</div>
</body>
</html>
