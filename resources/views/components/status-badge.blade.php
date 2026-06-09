@php
    $colors = [
        'PENDING_BILLING'  => 'bg-amber-100 text-amber-800 ring-amber-200',
        'WAITING_PAYMENT'  => 'bg-orange-100 text-orange-800 ring-orange-200',
        'VERIFYING'        => 'bg-blue-100 text-blue-800 ring-blue-200',
        'CONFIRMED'        => 'bg-green-100 text-green-800 ring-green-200',
        'REJECTED'         => 'bg-red-100 text-red-800 ring-red-200',
        'EXPIRED'          => 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 ring-slate-200',
    ];
    $statusValue = $status instanceof \App\Enums\ReservationStatus ? $status->value : (string) $status;
    $cls = $colors[$statusValue] ?? 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 ring-slate-200';
    $label = $status instanceof \App\Enums\ReservationStatus ? $status->label() : $statusValue;
@endphp
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 {{ $cls }}">
    {{ $label }}
</span>
