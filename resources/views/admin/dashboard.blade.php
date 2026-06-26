@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')
@php
    $user            = auth()->user();
    $totalReservasi  = \App\Models\Reservation::count();
    $menunggu        = \App\Models\Reservation::whereIn('status', ['PENDING_BILLING','WAITING_PAYMENT'])->count();
    $perluDiaudit    = \App\Models\Reservation::where('status', 'VERIFYING')->count();
    $dikonfirmasi    = \App\Models\Reservation::where('status', 'CONFIRMED')->count();
    $ditolak         = \App\Models\Reservation::where('status', 'REJECTED')->count();
    $todayReservasi  = \App\Models\Reservation::whereDate('created_at', today())->count();

    // ── Recent Activity ───────────────────────────────────────
    $recentActivity  = \App\Models\Reservation::with(['building'])
        ->latest()->take(8)->get()
        ->map(fn($r) => [
            'status_val'   => $r->status->value,
            'status_label' => $r->status->label(),
            'building'     => $r->building?->name ?? '-',
            'date'         => $r->created_at?->isoFormat('D MMM Y') ?? '',
            'diff'         => $r->created_at ? $r->created_at->diffForHumans(null, true) . ' yang lalu' : '',
        ]);

    // ── Calendar data (semua reservasi aktif) ─────────────────
    $totalActiveBuildings = max(1, \App\Models\Building::where('is_active', true)->count());

    $calRsvs = \App\Models\Reservation::with(['building.facilityType'])
        ->whereNotIn('status', ['REJECTED','EXPIRED'])
        ->get()
        ->map(fn($r) => [
            'id'          => $r->id,
            'facility'    => $r->building?->facilityType?->name ?? 'Fasilitas',
            'building'    => $r->building?->name ?? '-',
            'building_id' => $r->building_id ?? '',
            'customer'    => $r->customer_name,
            'start'       => $r->start_date?->format('Y-m-d') ?? '',
            'end'         => $r->end_date?->format('Y-m-d') ?? '',
            'start_fmt'   => $r->start_date?->isoFormat('D MMM Y') ?? '',
            'end_fmt'     => $r->end_date?->isoFormat('D MMM Y') ?? '',
            'status'      => $r->status->label(),
            'status_val'  => $r->status->value,
            'is_pending'  => in_array($r->status->value, ['PENDING_BILLING','WAITING_PAYMENT']),
        ])
        ->filter(fn($r) => $r['start'] && $r['end'])
        ->values();

    // Greeting
    $hour = now()->hour;
    $greet = $hour < 10 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
@endphp

<div style="display:flex;flex-direction:column;gap:20px;">

{{-- ══ WELCOME BANNER ══ --}}
<div style="position:relative;overflow:hidden;border-radius:16px;background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);padding:28px 32px;border:1px solid #bbf7d0;display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h2 style="font-size:22px;font-weight:800;color:#14532d;margin:0 0 6px;">{{ $greet }}, {{ $user->name }}! 👋</h2>
        <p style="font-size:13px;color:#166534;margin:0;">Berikut adalah ringkasan aktivitas reservasi hari ini.</p>
    </div>
    {{-- Decorative calendar illustration --}}
    <div style="opacity:.85;flex-shrink:0;">
        <svg width="100" height="90" viewBox="0 0 120 110" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="20" width="90" height="80" rx="8" fill="#16a34a" opacity=".15"/>
            <rect x="14" y="24" width="82" height="72" rx="6" fill="#16a34a" opacity=".1"/>
            <rect x="10" y="20" width="90" height="24" rx="8" fill="#16a34a"/>
            <rect x="10" y="36" width="90" height="8" rx="0" fill="#16a34a"/>
            <circle cx="30" cy="20" r="5" fill="#fff" opacity=".7"/>
            <circle cx="80" cy="20" r="5" fill="#fff" opacity=".7"/>
            <rect x="18" y="52" width="12" height="10" rx="2" fill="#16a34a" opacity=".3"/>
            <rect x="36" y="52" width="12" height="10" rx="2" fill="#16a34a" opacity=".5"/>
            <rect x="54" y="52" width="12" height="10" rx="2" fill="#16a34a" opacity=".5"/>
            <rect x="72" y="52" width="12" height="10" rx="2" fill="#16a34a" opacity=".3"/>
            <rect x="18" y="68" width="12" height="10" rx="2" fill="#16a34a" opacity=".5"/>
            <rect x="36" y="68" width="12" height="10" rx="2" fill="#16a34a" opacity=".6"/>
            <rect x="54" y="68" width="12" height="10" rx="2" fill="#16a34a"/>
            <rect x="72" y="68" width="12" height="10" rx="2" fill="#16a34a" opacity=".3"/>
            <rect x="18" y="84" width="12" height="10" rx="2" fill="#16a34a" opacity=".3"/>
            <rect x="36" y="84" width="12" height="10" rx="2" fill="#16a34a" opacity=".4"/>
            <rect x="54" y="84" width="12" height="10" rx="2" fill="#16a34a" opacity=".3"/>
            {{-- Leaf decoration --}}
            <ellipse cx="105" cy="30" rx="12" ry="20" fill="#4ade80" opacity=".5" transform="rotate(-30 105 30)"/>
            <ellipse cx="112" cy="50" rx="8" ry="14" fill="#22c55e" opacity=".4" transform="rotate(20 112 50)"/>
        </svg>
    </div>
</div>

{{-- ══ STATS ROW ══ --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:14px;">

@php
$statsConf = [
    ['key'=>'total',  'label'=>'Total Reservasi',   'val'=>$totalReservasi,'sub'=>'Semua reservasi',         'iclr'=>'#16a34a','bgclr'=>'#f0fdf4','bdr'=>'#bbf7d0',
     'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ['key'=>'tunggu', 'label'=>'Menunggu Tindakan', 'val'=>$menunggu,      'sub'=>'Perlu ditindaklanjuti',   'iclr'=>'#d97706','bgclr'=>'#fffbeb','bdr'=>'#fde68a','pulse'=>true,
     'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
    ['key'=>'audit',  'label'=>'Perlu Diaudit',     'val'=>$perluDiaudit,  'sub'=>'Reservasi perlu diaudit', 'iclr'=>'#7c3aed','bgclr'=>'#f5f3ff','bdr'=>'#ddd6fe',
     'icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
    ['key'=>'konfirm','label'=>'Dikonfirmasi',      'val'=>$dikonfirmasi,  'sub'=>'Reservasi dikonfirmasi',  'iclr'=>'#0891b2','bgclr'=>'#ecfeff','bdr'=>'#a5f3fc',
     'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ['key'=>'tolak',  'label'=>'Ditolak',           'val'=>$ditolak,       'sub'=>'Reservasi ditolak',       'iclr'=>'#dc2626','bgclr'=>'#fef2f2','bdr'=>'#fecaca',
     'icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
];
@endphp

@foreach($statsConf as $s)
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.04);transition:box-shadow .15s;"
     onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.08)';"
     onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,.04)';">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
        <div style="width:36px;height:36px;border-radius:9px;background:{{ $s['bgclr'] }};border:1px solid {{ $s['bdr'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="{{ $s['iclr'] }}">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
            </svg>
        </div>
        <span style="font-size:12px;font-weight:700;color:{{ $s['iclr'] }};">{{ $s['label'] }}</span>
    </div>
    <p style="font-size:30px;font-weight:900;color:#1e293b;margin:0;line-height:1;">{{ $s['val'] }}</p>
    <div style="display:flex;align-items:center;gap:5px;margin-top:5px;">
        @if(!empty($s['pulse']) && $s['val'] > 0)
        <span style="position:relative;display:inline-flex;width:8px;height:8px;flex-shrink:0;">
            <span style="position:absolute;width:100%;height:100%;border-radius:50%;background:#f59e0b;opacity:.75;animation:ping 1s cubic-bezier(0,0,0.2,1) infinite;"></span>
            <span style="position:relative;width:8px;height:8px;border-radius:50%;background:#f59e0b;"></span>
        </span>
        @endif
        <span style="font-size:11px;color:#94a3b8;">{{ $s['sub'] }}</span>
    </div>
</div>
@endforeach
</div>

{{-- ══ MAIN CONTENT: CALENDAR + ACTIVITY ══ --}}
<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

    {{-- ── CALENDAR ────────────────────────── --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04);">

        {{-- Calendar header --}}
        <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:32px;height:32px;border-radius:8px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h2 style="font-size:15px;font-weight:800;color:#1e293b;margin:0;">Kalender Reservasi</h2>
                    <p style="font-size:11px;color:#94a3b8;margin:0;">Tampilan jadwal booking fasilitas per bulan.</p>
                </div>
            </div>
            {{-- Navigation --}}
            <div style="display:flex;align-items:center;gap:6px;">
                <button id="cal-prev" style="width:28px;height:28px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;"
                    onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#475569"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <select id="cal-month-sel" style="height:28px;padding:0 8px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;font-size:12px;font-weight:600;color:#374151;cursor:pointer;"></select>
                <select id="cal-year-sel" style="height:28px;padding:0 8px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;font-size:12px;font-weight:600;color:#374151;cursor:pointer;"></select>
                <button id="cal-next" style="width:28px;height:28px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;"
                    onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#475569"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
                <button id="cal-today" style="height:28px;padding:0 12px;border-radius:7px;border:1px solid #16a34a;background:#f0fdf4;font-size:11px;font-weight:700;color:#16a34a;cursor:pointer;"
                    onmouseover="this.style.background='#dcfce7';" onmouseout="this.style.background='#f0fdf4';">
                    Hari Ini
                </button>
            </div>
        </div>

        {{-- Day headers (Sunday first, green bg) --}}
        <div style="display:grid;grid-template-columns:repeat(7,1fr);">
            @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $i => $dn)
            <div style="padding:10px 4px;text-align:center;font-size:12px;font-weight:700;background:#16a34a;color:{{ $i===0||$i===6 ? '#fde68a' : '#fff' }};{{ $i<6 ? 'border-right:1px solid rgba(255,255,255,.15);' : '' }}letter-spacing:.02em;">
                {{ $dn }}
            </div>
            @endforeach
        </div>

        {{-- Calendar grid (JS renders here) --}}
        <div id="cal-grid" style="display:grid;grid-template-columns:repeat(7,1fr);"></div>

        {{-- Legend --}}
        <div style="border-top:1px solid #f1f5f9;padding:12px 20px;background:#fafafa;">
            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:16px;">
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:16px;height:16px;border-radius:4px;background:#dcfce7;border:1.5px solid #86efac;"></div>
                    <span style="font-size:11px;color:#374151;font-weight:500;">Ada Reservasi</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:16px;height:16px;border-radius:4px;background:#fff;border:1.5px solid #e2e8f0;"></div>
                    <span style="font-size:11px;color:#374151;font-weight:500;">Tidak Ada Reservasi</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:16px;height:16px;border-radius:4px;background:#fee2e2;border:1.5px solid #fca5a5;"></div>
                    <span style="font-size:11px;color:#374151;font-weight:500;">Penuh</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:16px;height:16px;border-radius:4px;background:#fef3c7;border:1.5px solid #fcd34d;"></div>
                    <span style="font-size:11px;color:#374151;font-weight:500;">Menunggu Tindakan</span>
                </div>
                <div style="display:flex;align-items:center;gap:5px;margin-left:auto;">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="font-size:10px;color:#94a3b8;">Klik tanggal untuk melihat detail reservasi</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── AKTIVITAS TERBARU ──────────────── --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04);">
        <div style="padding:16px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:7px;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#64748b"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-size:14px;font-weight:800;color:#1e293b;">Aktivitas Terbaru</span>
            </div>
            <a href="{{ route('admin.reservations.index') }}" style="font-size:12px;font-weight:600;color:#16a34a;text-decoration:none;"
               onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">Lihat Semua</a>
        </div>
        <div style="padding:8px 0;max-height:520px;overflow-y:auto;">
@php
$actColors = [
    'CONFIRMED'       => '#22c55e',
    'COMPLETED'       => '#06b6d4',
    'VERIFYING'       => '#8b5cf6',
    'WAITING_PAYMENT' => '#f59e0b',
    'PENDING_BILLING' => '#f97316',
    'REJECTED'        => '#ef4444',
    'EXPIRED'         => '#94a3b8',
];
$actLabels = [
    'CONFIRMED'       => 'Reservasi Dikonfirmasi',
    'COMPLETED'       => 'Reservasi Selesai',
    'VERIFYING'       => 'Perlu Diaudit',
    'WAITING_PAYMENT' => 'Menunggu Pembayaran',
    'PENDING_BILLING' => 'Menunggu Tindakan',
    'REJECTED'        => 'Reservasi Ditolak',
    'EXPIRED'         => 'Reservasi Kedaluwarsa',
];
@endphp
@forelse($recentActivity as $act)
@php
$dotClr = $actColors[$act['status_val']] ?? '#94a3b8';
$actLbl = $actLabels[$act['status_val']] ?? $act['status_label'];
@endphp
<div style="display:flex;align-items:flex-start;gap:10px;padding:10px 18px;border-bottom:1px solid #f8fafc;transition:background .1s;"
     onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">
    <div style="width:10px;height:10px;border-radius:50%;background:{{ $dotClr }};flex-shrink:0;margin-top:3px;"></div>
    <div style="flex:1;min-width:0;">
        <p style="font-size:12px;font-weight:700;color:#1e293b;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $actLbl }}</p>
        <p style="font-size:11px;color:#64748b;margin:2px 0 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $act['building'] }} &mdash; {{ $act['date'] }}</p>
    </div>
    <span style="font-size:10px;color:#94a3b8;flex-shrink:0;margin-top:1px;">{{ $act['diff'] }}</span>
</div>
@empty
<div style="padding:40px 18px;text-align:center;">
    <p style="font-size:13px;color:#94a3b8;">Belum ada aktivitas</p>
</div>
@endforelse
        </div>
    </div>

</div>{{-- END 2-col grid --}}

</div>{{-- END outer flex --}}

{{-- ══ TOOLTIP ══ --}}
<div id="cal-tooltip" style="position:fixed;z-index:9990;min-width:250px;max-width:310px;pointer-events:none;opacity:0;transition:opacity .16s,transform .16s;transform:translateY(6px);display:none;">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 16px 48px rgba(0,0,0,.14);overflow:hidden;">
        <div id="cal-tip-body" style="padding:14px;"></div>
    </div>
</div>

{{-- ══ MODAL ══ --}}
<div id="cal-modal-bg" onclick="calCloseModal(event)" style="position:fixed;inset:0;z-index:9995;display:none;align-items:center;justify-content:center;padding:16px;background:rgba(15,23,42,.45);backdrop-filter:blur(4px);">
    <div onclick="event.stopPropagation()" style="position:relative;width:100%;max-width:480px;max-height:85vh;border-radius:16px;border:1px solid #e2e8f0;background:#fff;box-shadow:0 24px 64px rgba(0,0,0,.16);display:flex;flex-direction:column;overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:15px 20px;border-bottom:1px solid #f1f5f9;flex-shrink:0;">
            <div>
                <h3 id="cal-modal-title" style="font-size:15px;font-weight:800;color:#1e293b;margin:0;"></h3>
                <p id="cal-modal-sub" style="font-size:11px;color:#94a3b8;margin:3px 0 0;"></p>
            </div>
            <button onclick="calCloseModal()" style="width:28px;height:28px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;"
                onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#64748b"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="cal-modal-body" style="overflow-y:auto;flex:1;padding:14px 20px;"></div>
        <div style="border-top:1px solid #f1f5f9;padding:10px 20px;flex-shrink:0;">
            <button onclick="calCloseModal()" style="width:100%;padding:8px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;font-size:12px;font-weight:600;color:#64748b;cursor:pointer;"
                onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">Tutup</button>
        </div>
    </div>
</div>

<style>
@keyframes ping { 75%,100% { transform:scale(2); opacity:0; } }
</style>

@push('scripts')
<script>
(function(){
'use strict';

// ── Data ──────────────────────────────────────────────
const RSVS  = @json($calRsvs);
const TOT_B = {{ $totalActiveBuildings }};
const TODAY = '{{ now()->format('Y-m-d') }}';

const MONTHS_ID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const MONTHS_SH = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
const PALETTE   = ['#6366f1','#f59e0b','#10b981','#ef4444','#3b82f6','#ec4899','#14b8a6','#f97316','#8b5cf6','#84cc16'];
const FC={};let pi=0;
function fc(n){if(!FC[n])FC[n]=PALETTE[pi++%PALETTE.length];return FC[n];}
RSVS.forEach(r=>fc(r.facility));

let CY=new Date().getFullYear(), CM=new Date().getMonth()+1;

// ── Helpers ───────────────────────────────────────────
function daysIn(y,m){return new Date(y,m,0).getDate();}
// Sunday-first: new Date().getDay() returns 0=Sun directly
function firstWD(y,m){return new Date(y,m-1,1).getDay();}
function ds(y,m,d){return`${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`;}
function fmtL(s){const[y,m,d]=s.split('-');return`${parseInt(d)} ${MONTHS_ID[m-1]} ${y}`;}
function getBks(date){return RSVS.filter(r=>r.start<=date&&r.end>=date);}
function isFull(bks){return TOT_B>0&&new Set(bks.map(b=>b.building_id)).size>=TOT_B;}

function chip(val,label){
    const m={
        CONFIRMED:{bg:'#dcfce7',c:'#065f46',br:'#86efac'},
        COMPLETED:{bg:'#cffafe',c:'#164e63',br:'#67e8f9'},
        VERIFYING:{bg:'#ede9fe',c:'#5b21b6',br:'#c4b5fd'},
        WAITING_PAYMENT:{bg:'#fef3c7',c:'#78350f',br:'#fcd34d'},
        PENDING_BILLING:{bg:'#ffedd5',c:'#7c2d12',br:'#fdba74'},
        REJECTED:{bg:'#fee2e2',c:'#991b1b',br:'#fca5a5'},
        EXPIRED:{bg:'#f1f5f9',c:'#475569',br:'#cbd5e1'},
    };
    const s=m[val]||{bg:'#f1f5f9',c:'#475569',br:'#cbd5e1'};
    return`<span style="display:inline-block;font-size:10px;font-weight:700;border-radius:999px;padding:1px 7px;background:${s.bg};color:${s.c};border:1px solid ${s.br};">${label}</span>`;
}

// ── Populate month/year selects ───────────────────────
function populateSelects(){
    const mSel=document.getElementById('cal-month-sel');
    const ySel=document.getElementById('cal-year-sel');
    if(!mSel||!ySel)return;
    mSel.innerHTML=''; ySel.innerHTML='';
    MONTHS_ID.forEach((m,i)=>{
        const o=document.createElement('option');
        o.value=i+1; o.textContent=m;
        if(i+1===CM)o.selected=true;
        mSel.appendChild(o);
    });
    for(let y=new Date().getFullYear()-3;y<=new Date().getFullYear()+3;y++){
        const o=document.createElement('option');
        o.value=y; o.textContent=y;
        if(y===CY)o.selected=true;
        ySel.appendChild(o);
    }
    mSel.onchange=()=>{CM=parseInt(mSel.value);render(false);};
    ySel.onchange=()=>{CY=parseInt(ySel.value);render(false);};
}

// ── Render ────────────────────────────────────────────
function render(updateSels=true){
    const grid=document.getElementById('cal-grid');
    if(!grid)return;
    if(updateSels)populateSelects();

    // sync selects
    const mSel=document.getElementById('cal-month-sel');
    const ySel=document.getElementById('cal-year-sel');
    if(mSel)mSel.value=CM;
    if(ySel)ySel.value=CY;

    const total=daysIn(CY,CM);
    const off=firstWD(CY,CM); // 0=Sun
    let html='';

    // Leading (prev month)
    const pDays=daysIn(CY,CM<=1?12:CM-1);
    for(let i=0;i<off;i++){
        const pn=pDays-off+1+i;
        const wp=i; // column 0=Sun,6=Sat
        html+=buildCell({num:pn,status:'other',isToday:false,isWE:wp===0||wp===6,bks:[],date:'',isFirst:i===0});
    }

    // Current month
    for(let d=1;d<=total;d++){
        const date=ds(CY,CM,d);
        const bks=getBks(date);
        const colIdx=(off+d-1)%7; // 0=Sun,6=Sat
        const full=isFull(bks);
        const pending=bks.filter(b=>b.is_pending);
        const confirmed=bks.filter(b=>!b.is_pending);

        let status='empty';
        if(full) status='full';
        else if(bks.length>0) status='booked';

        html+=buildCell({
            num:d, status, isToday:date===TODAY,
            isWE:colIdx===0||colIdx===6,
            bks, date,
            pendingCount:pending.length,
            confirmedCount:confirmed.length,
            isFirst:colIdx===0,
        });
    }

    // Trailing
    const filled=off+total;
    const trail=filled%7===0?0:7-(filled%7);
    for(let i=1;i<=trail;i++){
        const colIdx=(filled+i-1)%7;
        html+=buildCell({num:i,status:'other',isToday:false,isWE:colIdx===0||colIdx===6,bks:[],date:'',isFirst:colIdx===0});
    }

    grid.innerHTML=html;
}

function buildCell({num,status,isToday,isWE,bks,date,pendingCount=0,confirmedCount=0,isFirst=false}){
    let bg='#fff',cur='default';
    if(status==='other') bg='#f8fafc';
    else if(status==='full'){ bg='#fff1f2'; cur='pointer'; }
    else if(status==='booked'){ bg='#f0fdf4'; cur='pointer'; }

    const todayRing=isToday?'outline:2px solid #16a34a;outline-offset:-1px;':'';
    let numClr=status==='other'?'#cbd5e1':(isWE?'#ef4444':'#374151');
    if(isToday)numClr='#16a34a';

    const ev=(status==='booked'||status==='full')
        ?`onmouseenter="calShowTip(event,this,'${date}')" onmouseleave="calHideTip(this,'${status}')" onclick="calOpenModal('${date}')"`:'';

    // Badges
    let badges='<div style="display:flex;flex-direction:column;gap:2px;margin-top:auto;">';
    if(status==='full'){
        badges+=`<span style="display:inline-flex;align-items:center;gap:2px;font-size:10px;font-weight:800;color:#b91c1c;background:#fee2e2;border:1px solid #fca5a5;border-radius:4px;padding:1px 5px;">
            <svg width="8" height="8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
            PENUH</span>`;
    } else {
        if(confirmedCount>0){
            badges+=`<span style="display:inline-flex;align-items:center;gap:2px;font-size:10px;font-weight:700;color:#166534;background:#bbf7d0;border:1px solid #86efac;border-radius:4px;padding:1px 5px;">
                <svg width="8" height="8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                ${confirmedCount} Reservasi</span>`;
        }
        if(pendingCount>0){
            badges+=`<span style="display:inline-flex;align-items:center;gap:2px;font-size:10px;font-weight:700;color:#92400e;background:#fef3c7;border:1px solid #fcd34d;border-radius:4px;padding:1px 5px;">
                <svg width="8" height="8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3"/></svg>
                ${pendingCount} Menunggu</span>`;
        }
    }
    badges+='</div>';

    // Hover effect stored
    const hoverBg=status==='full'?'#fee2e2':(status==='booked'?'#dcfce7':'#f8fafc');

    return`<div style="min-height:80px;padding:7px 8px;background:${bg};border-right:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;cursor:${cur};display:flex;flex-direction:column;box-sizing:border-box;transition:background .1s;${todayRing}" data-bg="${bg}" data-hbg="${hoverBg}" data-st="${status}" ${ev}>
        <span style="font-size:13px;font-weight:700;color:${numClr};line-height:1;margin-bottom:3px;">${num}</span>
        ${badges}
    </div>`;
}

// ── Tooltip ───────────────────────────────────────────
function calShowTip(e,el,date){
    el.style.background=el.dataset.hbg;
    const bks=getBks(date);if(!bks.length)return;
    const tip=document.getElementById('cal-tooltip');
    const body=document.getElementById('cal-tip-body');
    const full=isFull(bks);
    const sBg=full?'#fee2e2':'#dcfce7',sClr=full?'#991b1b':'#166534';
    const sTxt=full?`🔴 Jadwal Penuh`:`🟢 ${bks.length} Reservasi`;
    const byF={};
    bks.forEach(b=>{if(!byF[b.facility])byF[b.facility]={c:fc(b.facility),items:[]};byF[b.facility].items.push(b);});
    let html=`<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;padding-bottom:8px;border-bottom:1px solid #f1f5f9;">
        <span style="font-size:12px;font-weight:800;color:#1e293b;">${fmtL(date)}</span>
        <span style="font-size:10px;font-weight:700;background:${sBg};color:${sClr};border-radius:999px;padding:2px 8px;">${sTxt}</span>
    </div>`;
    Object.entries(byF).slice(0,3).forEach(([fac,data])=>{
        data.items.slice(0,2).forEach(b=>{
            html+=`<div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:6px;padding:7px;background:#f8fafc;border-radius:7px;">
                <div style="width:28px;height:28px;border-radius:6px;background:${data.c}18;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="${data.c}"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:4px;margin-bottom:2px;">
                        <span style="font-size:11px;font-weight:700;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${b.building}</span>
                        ${chip(b.status_val,b.status)}
                    </div>
                    <p style="font-size:10px;color:#64748b;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">👤 ${b.customer}</p>
                    <p style="font-size:10px;color:#94a3b8;margin:2px 0 0;"><span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:${data.c};margin-right:3px;vertical-align:middle;"></span>${fac}</p>
                </div>
            </div>`;
        });
        if(data.items.length>2)html+=`<p style="font-size:10px;color:#94a3b8;text-align:center;margin:0 0 5px;">+${data.items.length-2} lainnya</p>`;
    });
    if(Object.keys(byF).length>3)html+=`<p style="font-size:10px;color:#94a3b8;text-align:center;margin:0;">...dan fasilitas lain</p>`;
    html+=`<div style="text-align:center;margin-top:8px;padding-top:8px;border-top:1px solid #f1f5f9;"><span style="font-size:10px;color:#16a34a;font-weight:600;">Klik untuk detail lengkap →</span></div>`;
    body.innerHTML=html;
    tip.style.display='block';
    const tw=tip.offsetWidth||260,th2=tip.offsetHeight||180;
    const vw=window.innerWidth;
    const cr=e.currentTarget.getBoundingClientRect();
    let lft=cr.left+window.scrollX+cr.width/2-tw/2;
    let top=cr.top+window.scrollY-th2-10;
    if(lft<8)lft=8; if(lft+tw>vw-8)lft=vw-8-tw;
    if(top<window.scrollY+8)top=cr.bottom+window.scrollY+8;
    tip.style.left=lft+'px';tip.style.top=top+'px';
    tip.style.opacity='1';tip.style.transform='translateY(0)';
}
function calHideTip(el,st){
    if(el&&el.dataset)el.style.background=el.dataset.bg;
    const t=document.getElementById('cal-tooltip');
    t.style.opacity='0';t.style.transform='translateY(6px)';
}
window.calShowTip=calShowTip;window.calHideTip=calHideTip;

// ── Modal ──────────────────────────────────────────────
function calOpenModal(date){
    const bks=getBks(date);if(!bks.length)return;
    const full=isFull(bks);
    document.getElementById('cal-modal-title').textContent=fmtL(date);
    document.getElementById('cal-modal-sub').textContent=`${bks.length} reservasi · ${full?'🔴 Jadwal Penuh':'🟢 Ada Slot Tersedia'}`;
    const byF={};
    bks.forEach(b=>{if(!byF[b.facility])byF[b.facility]={c:fc(b.facility),items:[]};byF[b.facility].items.push(b);});
    let html='';
    Object.entries(byF).forEach(([fac,data])=>{
        html+=`<div style="margin-bottom:10px;border-radius:10px;border:1px solid #f1f5f9;overflow:hidden;">
            <div style="display:flex;align-items:center;gap:7px;padding:8px 12px;background:${data.c}0f;border-bottom:1px solid ${data.c}20;">
                <span style="width:8px;height:8px;border-radius:50%;background:${data.c};flex-shrink:0;"></span>
                <span style="font-size:12px;font-weight:700;color:#1e293b;flex:1;">${fac}</span>
                <span style="font-size:10px;font-weight:700;background:${data.c};color:#fff;border-radius:999px;padding:2px 8px;">${data.items.length}</span>
            </div>`;
        data.items.forEach((b,i)=>{
            html+=`<div style="padding:10px 12px;${i<data.items.length-1?'border-bottom:1px solid #f8fafc;':''}">
                <div style="display:flex;align-items:flex-start;gap:8px;">
                    <div style="width:32px;height:32px;border-radius:7px;background:${data.c}15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="${data.c}"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:6px;margin-bottom:2px;">
                            <span style="font-size:12px;font-weight:700;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${b.building}</span>
                            ${chip(b.status_val,b.status)}
                        </div>
                        <p style="font-size:11px;color:#64748b;margin:0;">👤 ${b.customer}</p>
                        <p style="font-size:10px;color:#94a3b8;margin:3px 0 0;display:flex;align-items:center;gap:3px;">
                            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            ${b.start_fmt} – ${b.end_fmt}
                        </p>
                    </div>
                </div>
            </div>`;
        });
        html+=`</div>`;
    });
    document.getElementById('cal-modal-body').innerHTML=html;
    const bg=document.getElementById('cal-modal-bg');
    bg.style.display='flex';document.body.style.overflow='hidden';
}
function calCloseModal(e){
    if(e&&e.target!==document.getElementById('cal-modal-bg')&&e.type==='click')return;
    document.getElementById('cal-modal-bg').style.display='none';
    document.body.style.overflow='';
}
window.calOpenModal=calOpenModal;window.calCloseModal=calCloseModal;

// ── Navigation ──────────────────────────────────────────
document.getElementById('cal-prev').onclick=()=>{CM--;if(CM<1){CM=12;CY--;}render();};
document.getElementById('cal-next').onclick=()=>{CM++;if(CM>12){CM=1;CY++;}render();};
document.getElementById('cal-today').onclick=()=>{CY=new Date().getFullYear();CM=new Date().getMonth()+1;render();};
document.addEventListener('keydown',e=>{
    if(e.key==='Escape')calCloseModal();
    if(e.key==='ArrowLeft')document.getElementById('cal-prev').click();
    if(e.key==='ArrowRight')document.getElementById('cal-next').click();
});
window.addEventListener('scroll',()=>{
    const t=document.getElementById('cal-tooltip');
    if(t){t.style.opacity='0';t.style.transform='translateY(6px)';}
},{passive:true});

// ── Init ────────────────────────────────────────────────
populateSelects();
render(false);

})();
</script>
@endpush

@endsection
