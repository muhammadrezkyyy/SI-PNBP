@extends('layouts.admin')
@section('page-title', 'Kalender Reservasi')

@section('content')
@php
    use App\Models\Reservation;
    use App\Models\Building;

    $totalActiveBuildings = max(1, Building::where('is_active', true)->count());

    $allReservations = Reservation::with(['building.facilityType'])
        ->whereNotIn('status', ['REJECTED', 'EXPIRED'])
        ->get()
        ->map(function ($r) {
            return [
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
            ];
        })
        ->filter(fn($r) => $r['start'] && $r['end'])
        ->values();
@endphp

<div>

{{-- ══ PAGE HEADER + STATS ══ --}}
<div class="flex flex-wrap items-start gap-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="h-12 w-12 rounded-2xl bg-blue-600 flex items-center justify-center shadow-lg" style="box-shadow:0 8px 20px rgba(37,99,235,.35);">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Kalender Reservasi</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Lihat jadwal dan status reservasi fasilitas</p>
        </div>
    </div>

    {{-- Stats Strip --}}
    <div class="ml-auto flex flex-wrap items-stretch gap-0 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-3">
            <div class="h-9 w-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-slate-500">Total Reservasi Bulan Ini</p>
                <p id="stat-total" class="text-2xl font-black text-blue-600 dark:text-blue-400 leading-none">–</p>
                <p class="text-xs text-slate-400">reservasi</p>
            </div>
        </div>
        <div class="w-px bg-slate-100 dark:bg-slate-700"></div>
        <div class="flex items-center gap-3 px-5 py-3">
            <div class="h-9 w-9 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-slate-500">Tanggal Tersedia</p>
                <p id="stat-avail" class="text-2xl font-black text-emerald-600 dark:text-emerald-400 leading-none">–</p>
                <p class="text-xs text-slate-400">hari</p>
            </div>
        </div>
        <div class="w-px bg-slate-100 dark:bg-slate-700"></div>
        <div class="flex items-center gap-3 px-5 py-3">
            <div class="h-9 w-9 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-slate-500">Tanggal Penuh</p>
                <p id="stat-full" class="text-2xl font-black text-red-600 dark:text-red-400 leading-none">–</p>
                <p class="text-xs text-slate-400">hari</p>
            </div>
        </div>
    </div>
</div>

{{-- ══ CALENDAR CARD ══ --}}
<div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">

    {{-- ── Nav Bar ── --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700/50">
        {{-- Prev / Next / Today --}}
        <div class="flex items-center gap-2">
            <button id="cal-prev"
                style="height:36px;width:36px;display:inline-flex;align-items:center;justify-content:center;border-radius:10px;border:1px solid #e2e8f0;background:#fff;color:#64748b;cursor:pointer;transition:all .15s;"
                onmouseover="this.style.background='#f8fafc';this.style.color='#1e293b';"
                onmouseout="this.style.background='#fff';this.style.color='#64748b';">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button id="cal-next"
                style="height:36px;width:36px;display:inline-flex;align-items:center;justify-content:center;border-radius:10px;border:1px solid #e2e8f0;background:#fff;color:#64748b;cursor:pointer;transition:all .15s;"
                onmouseover="this.style.background='#f8fafc';this.style.color='#1e293b';"
                onmouseout="this.style.background='#fff';this.style.color='#64748b';">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <button id="cal-today"
                style="height:36px;padding:0 16px;border-radius:10px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-size:13px;font-weight:600;cursor:pointer;transition:all .15s;"
                onmouseover="this.style.background='#f8fafc';"
                onmouseout="this.style.background='#fff';">
                Hari Ini
            </button>
        </div>

        {{-- Month Title --}}
        <h2 id="cal-title" style="font-size:20px;font-weight:800;color:#1e293b;min-width:200px;text-align:center;"></h2>

        {{-- View Toggle (decorative) --}}
        <div style="display:flex;border-radius:10px;overflow:hidden;border:1px solid #e2e8f0;">
            <button style="padding:8px 16px;font-size:13px;font-weight:700;background:#2563eb;color:#fff;border:none;cursor:pointer;">Bulan</button>
            <button style="padding:8px 16px;font-size:13px;font-weight:500;background:#fff;color:#94a3b8;border:none;cursor:pointer;border-left:1px solid #e2e8f0;"
                onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">Minggu</button>
            <button style="padding:8px 16px;font-size:13px;font-weight:500;background:#fff;color:#94a3b8;border:none;cursor:pointer;border-left:1px solid #e2e8f0;"
                onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">Hari</button>
        </div>
    </div>

    {{-- ── Day-of-Week Headers ── --}}
    <div id="cal-day-headers" style="display:grid;grid-template-columns:repeat(7,1fr);background:#f8fafc;border-bottom:1px solid #e2e8f0;">
        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $i => $d)
        <div style="padding:10px 0;text-align:center;font-size:13px;font-weight:700;color:{{ $i >= 5 ? '#ef4444' : '#64748b' }};{{ $i < 6 ? 'border-right:1px solid #e2e8f0;' : '' }}letter-spacing:.01em;">
            {{ $d }}
        </div>
        @endforeach
    </div>

    {{-- ── Calendar Grid (JS renders here) ── --}}
    <div id="cal-grid" style="display:grid;grid-template-columns:repeat(7,1fr);"></div>

    {{-- ── Legend ── --}}
    <div style="border-top:1px solid #f1f5f9;padding:16px 24px;background:#fafafa;">
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:24px;">
            <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Keterangan:</span>
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:20px;height:20px;border-radius:6px;background:#dcfce7;border:2px solid #86efac;"></div>
                <div>
                    <p style="font-size:12px;font-weight:700;color:#374151;margin:0;">Hijau</p>
                    <p style="font-size:10px;color:#94a3b8;margin:0;">Tanggal memiliki reservasi</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:20px;height:20px;border-radius:6px;background:#fff;border:2px solid #e2e8f0;"></div>
                <div>
                    <p style="font-size:12px;font-weight:700;color:#374151;margin:0;">Putih/Abu</p>
                    <p style="font-size:10px;color:#94a3b8;margin:0;">Belum ada reservasi</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:20px;height:20px;border-radius:6px;background:#fee2e2;border:2px solid #fca5a5;"></div>
                <div>
                    <p style="font-size:12px;font-weight:700;color:#374151;margin:0;">Merah</p>
                    <p style="font-size:10px;color:#94a3b8;margin:0;">Jadwal penuh / tidak tersedia</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:20px;height:20px;border-radius:6px;background:#fff;border:2.5px solid #3b82f6;"></div>
                <div>
                    <p style="font-size:12px;font-weight:700;color:#374151;margin:0;">Border Biru</p>
                    <p style="font-size:10px;color:#94a3b8;margin:0;">Hari ini</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:6px;margin-left:auto;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-size:11px;color:#94a3b8;">Klik tanggal untuk melihat detail reservasi</span>
            </div>
        </div>
    </div>
</div>

{{-- ══ TOOLTIP ══ --}}
<div id="cal-tooltip" style="position:fixed;z-index:9990;min-width:270px;max-width:340px;pointer-events:none;opacity:0;transition:opacity .18s ease,transform .18s ease;transform:translateY(4px);display:none;">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.15);overflow:hidden;">
        <div id="cal-tooltip-body" style="padding:16px;"></div>
    </div>
</div>

{{-- ══ MODAL ══ --}}
<div id="cal-modal-bg" onclick="closeModal()" style="position:fixed;inset:0;z-index:9995;display:none;align-items:center;justify-content:center;padding:16px;background:rgba(15,23,42,.5);backdrop-filter:blur(4px);">
    <div onclick="event.stopPropagation()" style="position:relative;width:100%;max-width:520px;max-height:85vh;border-radius:20px;border:1px solid #e2e8f0;background:#fff;box-shadow:0 32px 80px rgba(0,0,0,.18);display:flex;flex-direction:column;overflow:hidden;">
        {{-- Modal Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid #f1f5f9;flex-shrink:0;">
            <div>
                <h3 id="modal-title" style="font-size:17px;font-weight:800;color:#1e293b;margin:0;"></h3>
                <p id="modal-sub" style="font-size:12px;color:#94a3b8;margin:4px 0 0;"></p>
            </div>
            <button onclick="closeModal()" style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;"
                onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#64748b"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        {{-- Modal Body --}}
        <div id="modal-body" style="overflow-y:auto;flex:1;padding:16px 22px;"></div>
        {{-- Modal Footer --}}
        <div style="border-top:1px solid #f1f5f9;padding:12px 22px;flex-shrink:0;">
            <button onclick="closeModal()" style="width:100%;padding:10px;border-radius:10px;border:1px solid #e2e8f0;background:#fff;font-size:13px;font-weight:600;color:#64748b;cursor:pointer;"
                onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">
                Tutup
            </button>
        </div>
    </div>
</div>

</div>{{-- END root div --}}

@push('scripts')
<script>
(function(){
'use strict';

// ── Data from PHP ─────────────────────────────────────
const RSVS   = @json($allReservations);
const TOT_B  = {{ $totalActiveBuildings }};
const TODAY  = '{{ now()->format('Y-m-d') }}';

// ── Month/Day labels ──────────────────────────────────
const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const MONS   = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

// ── Color palette for facility dots ──────────────────
const PALETTE = ['#6366f1','#f59e0b','#10b981','#ef4444','#3b82f6','#ec4899','#14b8a6','#f97316','#8b5cf6'];
const FAC_COLORS = {};
let pIdx = 0;
function facColor(name){
    if(!FAC_COLORS[name]){ FAC_COLORS[name]=PALETTE[pIdx++%PALETTE.length]; }
    return FAC_COLORS[name];
}
RSVS.forEach(r=>facColor(r.facility));

// ── State ─────────────────────────────────────────────
let CY = new Date().getFullYear();
let CM = new Date().getMonth()+1; // 1-indexed

// ── Helpers ───────────────────────────────────────────
function daysIn(y,m){ return new Date(y,m,0).getDate(); }
function firstWD(y,m){ const d=new Date(y,m-1,1).getDay(); return d===0?6:d-1; } // Mon=0
function ds(y,m,d){ return `${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`; }
function fmtLong(s){ const[y,m,d]=s.split('-'); return `${parseInt(d)} ${MONTHS[m-1]} ${y}`; }

function getBookings(dateStr){ return RSVS.filter(r=>r.start<=dateStr&&r.end>=dateStr); }
function isFull(bookings){ return TOT_B>0 && new Set(bookings.map(b=>b.building_id)).size>=TOT_B; }

// ── Status chip ───────────────────────────────────────
function chip(val,label){
    const m={
        CONFIRMED:     {bg:'#dcfce7',c:'#065f46',br:'#86efac'},
        COMPLETED:     {bg:'#cffafe',c:'#164e63',br:'#67e8f9'},
        VERIFYING:     {bg:'#dbeafe',c:'#1e40af',br:'#93c5fd'},
        WAITING_PAYMENT:{bg:'#ffedd5',c:'#7c2d12',br:'#fdba74'},
        PENDING_BILLING:{bg:'#fef9c3',c:'#713f12',br:'#fde047'},
        REJECTED:      {bg:'#fee2e2',c:'#991b1b',br:'#fca5a5'},
        EXPIRED:       {bg:'#f1f5f9',c:'#475569',br:'#cbd5e1'},
    };
    const s=m[val]||{bg:'#f1f5f9',c:'#475569',br:'#cbd5e1'};
    return `<span style="display:inline-block;font-size:10px;font-weight:700;border-radius:999px;padding:2px 8px;background:${s.bg};color:${s.c};border:1px solid ${s.br};">${label}</span>`;
}

// ── RENDER CALENDAR ───────────────────────────────────
function render(){
    const grid = document.getElementById('cal-grid');
    const title= document.getElementById('cal-title');
    if(!grid||!title) return;

    title.textContent = `${MONTHS[CM-1]} ${CY}`;

    const totalDays = daysIn(CY, CM);
    const offset    = firstWD(CY, CM);
    const today     = TODAY;

    let html = '';
    let statTotal=0, statBooked=0, statFull=0;

    // ── Leading cells (prev month) ──
    const prevDays = daysIn(CY, CM-1);
    for(let i=0;i<offset;i++){
        const prevD = prevDays - offset + 1 + i;
        html += cell({
            num: prevD,
            status: 'other',
            isToday: false,
            isWeekend: i>=5,
            bookings: [],
            dateStr: '',
        });
    }

    // ── Current month cells ──
    for(let d=1;d<=totalDays;d++){
        const dateStr  = ds(CY,CM,d);
        const bookings = getBookings(dateStr);
        const weekPos  = (offset + d - 1) % 7; // 0=Mon,6=Sun
        const full     = isFull(bookings);
        let status = 'empty';
        if(bookings.length>0) status = full ? 'full' : 'booked';

        if(bookings.length>0){ statTotal+=bookings.length; statBooked++; }
        if(full) statFull++;

        html += cell({
            num: d,
            status,
            isToday: dateStr===today,
            isWeekend: weekPos>=5,
            bookings,
            dateStr,
        });
    }

    // ── Trailing cells ──
    const filled = offset + totalDays;
    const trail  = filled % 7 === 0 ? 0 : 7 - (filled % 7);
    for(let i=1;i<=trail;i++){
        html += cell({ num:i, status:'other', isToday:false, isWeekend:((filled+i-1)%7)>=5, bookings:[], dateStr:'' });
    }

    grid.innerHTML = html;

    // Stats update
    const sn=(id,v)=>{const e=document.getElementById(id);if(e)e.textContent=v;};
    sn('stat-total', statTotal);
    sn('stat-avail', totalDays - statBooked);
    sn('stat-full',  statFull);
}

// ── Build cell HTML (all inline styles!) ─────────────
function cell({ num, status, isToday, isWeekend, bookings, dateStr }){
    // Cell background & border
    let bg='#ffffff', border='1px solid #e2e8f0', cursor='default';
    if(status==='other'){  bg='#f8fafc'; }
    if(status==='booked'){ bg='#f0fdf4'; border='1px solid #bbf7d0'; cursor='pointer'; }
    if(status==='full'){   bg='#fff1f2'; border='1px solid #fecaca'; cursor='pointer'; }

    // Today ring override (add blue outline on top)
    let todayStyle = '';
    if(isToday){ todayStyle='outline:2.5px solid #3b82f6;outline-offset:0;z-index:1;position:relative;'; }

    // Number color
    let numColor = status==='other' ? '#cbd5e1' : (isWeekend ? '#ef4444' : '#374151');
    if(isToday) numColor='#2563eb';

    // Hover events
    const hoverIn = (status==='booked'||status==='full')
        ? `onmouseenter="cellHover(event,this,'${dateStr}')" onmouseleave="cellOut(this,'${status}')" onclick="openModal('${dateStr}')"`
        : '';

    // Badge
    let badge='';
    if(status==='booked'){
        badge=`<span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:#166534;background:#bbf7d0;border:1px solid #86efac;border-radius:6px;padding:3px 8px;margin-top:auto;">
            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
            ${bookings.length} Reservasi
        </span>`;
    } else if(status==='full'){
        badge=`<span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:800;color:#991b1b;background:#fecaca;border:1px solid #fca5a5;border-radius:6px;padding:3px 8px;margin-top:auto;letter-spacing:.03em;">
            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
            PENUH
        </span>`;
    }

    // Facility color dots
    let dots='';
    if(bookings.length>0 && status!=='full'){
        const uniqFac={};
        bookings.forEach(b=>{uniqFac[b.facility]=facColor(b.facility);});
        const shown=Object.entries(uniqFac).slice(0,5);
        dots=`<div style="display:flex;gap:3px;flex-wrap:wrap;margin-bottom:2px;">`;
        shown.forEach(([,c])=>{dots+=`<span style="width:7px;height:7px;border-radius:50%;background:${c};flex-shrink:0;"></span>`;});
        dots+=`</div>`;
    }

    return `<div style="min-height:96px;padding:10px 12px;background:${bg};border-right:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;cursor:${cursor};display:flex;flex-direction:column;gap:4px;transition:background .15s;box-sizing:border-box;${todayStyle}" data-bg="${bg}" data-status="${status}" ${hoverIn}>
        <span style="font-size:14px;font-weight:700;color:${numColor};line-height:1;">${num}</span>
        ${dots}
        ${badge}
    </div>`;
}

// ── Cell hover effect ─────────────────────────────────
function cellHover(e, el, dateStr){
    const s = el.dataset.status;
    if(s==='booked') el.style.background='#dcfce7';
    if(s==='full')   el.style.background='#fee2e2';
    showTip(e, dateStr);
}
function cellOut(el, status){
    el.style.background = el.dataset.bg;
    hideTip();
}

// ── Tooltip ───────────────────────────────────────────
function showTip(e, dateStr){
    const bks = getBookings(dateStr);
    if(!bks.length) return;

    const tip  = document.getElementById('cal-tooltip');
    const body = document.getElementById('cal-tooltip-body');
    const full = isFull(bks);
    const statusBg  = full ? '#fecaca' : '#bbf7d0';
    const statusClr = full ? '#991b1b' : '#166534';
    const statusTxt = full ? '🔴 Jadwal Penuh' : `🟢 ${bks.length} Reservasi`;

    // Group by facility
    const byFac={};
    bks.forEach(b=>{ if(!byFac[b.facility]) byFac[b.facility]={c:facColor(b.facility),items:[]}; byFac[b.facility].items.push(b); });

    let html=`<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid #f1f5f9;">
        <span style="font-size:13px;font-weight:800;color:#1e293b;">${fmtLong(dateStr)}</span>
        <span style="font-size:10px;font-weight:700;background:${statusBg};color:${statusClr};border-radius:999px;padding:3px 10px;">${statusTxt}</span>
    </div>`;

    Object.entries(byFac).slice(0,3).forEach(([fac, data])=>{
        data.items.slice(0,2).forEach(b=>{
            html+=`<div style="display:flex;gap:10px;align-items:flex-start;margin-bottom:10px;padding:10px;background:#f8fafc;border-radius:10px;border:1px solid #f1f5f9;">
                <div style="width:36px;height:36px;border-radius:8px;background:${data.c}18;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="${data.c}"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:2px;">
                        <span style="font-size:12px;font-weight:700;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${b.building}</span>
                        ${chip(b.status_val,b.status)}
                    </div>
                    <p style="font-size:11px;color:#64748b;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">👤 ${b.customer}</p>
                    <p style="font-size:10px;color:#94a3b8;margin:2px 0 0;">
                        <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:${data.c};margin-right:4px;vertical-align:middle;"></span>
                        ${fac} · ${b.start_fmt} – ${b.end_fmt}
                    </p>
                </div>
            </div>`;
        });
        if(data.items.length>2){
            html+=`<p style="font-size:10px;color:#94a3b8;text-align:center;margin:0 0 8px;">+${data.items.length-2} reservasi lainnya di ${fac}</p>`;
        }
    });

    if(Object.keys(byFac).length>3){
        html+=`<p style="font-size:10px;color:#94a3b8;text-align:center;margin:0;">...dan fasilitas lain · Klik untuk detail lengkap</p>`;
    }

    html+=`<div style="text-align:center;margin-top:10px;padding-top:10px;border-top:1px solid #f1f5f9;">
        <span style="font-size:11px;color:#3b82f6;font-weight:600;">Klik untuk detail lengkap →</span>
    </div>`;

    body.innerHTML = html;

    // Position
    tip.style.display='block';
    const tw = tip.offsetWidth||280, th=tip.offsetHeight||200;
    const vw=window.innerWidth, vh=window.innerHeight;
    const cellR = e.currentTarget.getBoundingClientRect();
    let left = cellR.left + window.scrollX + cellR.width/2 - tw/2;
    let top  = cellR.top  + window.scrollY - th - 10;
    if(left<8+window.scrollX) left=8+window.scrollX;
    if(left+tw>vw-8+window.scrollX) left=vw-8-tw+window.scrollX;
    if(top<window.scrollY+8) top=cellR.bottom+window.scrollY+8;
    tip.style.left=left+'px'; tip.style.top=top+'px';
    tip.style.opacity='1'; tip.style.transform='translateY(0)';
}
function hideTip(){
    const tip=document.getElementById('cal-tooltip');
    tip.style.opacity='0'; tip.style.transform='translateY(4px)';
}

// ── Modal ─────────────────────────────────────────────
function openModal(dateStr){
    hideTip();
    const bks=getBookings(dateStr);
    if(!bks.length) return;
    const full=isFull(bks);

    document.getElementById('modal-title').textContent=fmtLong(dateStr);
    document.getElementById('modal-sub').textContent=`${bks.length} reservasi · ${full?'🔴 Jadwal Penuh':'🟢 Ada Slot Tersedia'}`;

    const byFac={};
    bks.forEach(b=>{ if(!byFac[b.facility]) byFac[b.facility]={c:facColor(b.facility),items:[]}; byFac[b.facility].items.push(b); });

    let html='';
    Object.entries(byFac).forEach(([fac, data])=>{
        html+=`<div style="margin-bottom:12px;border-radius:12px;border:1px solid #f1f5f9;overflow:hidden;">
            <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:${data.c}10;border-bottom:1px solid ${data.c}20;">
                <span style="width:10px;height:10px;border-radius:50%;background:${data.c};flex-shrink:0;"></span>
                <span style="font-size:13px;font-weight:700;color:#1e293b;flex:1;">${fac}</span>
                <span style="font-size:11px;font-weight:700;background:${data.c};color:#fff;border-radius:999px;padding:2px 10px;">${data.items.length}</span>
            </div>`;
        data.items.forEach((b,i)=>{
            html+=`<div style="padding:12px 14px;${i<data.items.length-1?'border-bottom:1px solid #f8fafc;':''}">
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <div style="width:38px;height:38px;border-radius:9px;background:${data.c}15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="${data.c}"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:3px;">
                            <span style="font-size:13px;font-weight:700;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${b.building}</span>
                            ${chip(b.status_val,b.status)}
                        </div>
                        <p style="font-size:12px;color:#64748b;margin:0;display:flex;align-items:center;gap:4px;">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            ${b.customer}
                        </p>
                        <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;display:flex;align-items:center;gap:4px;">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            ${b.start_fmt} – ${b.end_fmt}
                        </p>
                    </div>
                </div>
            </div>`;
        });
        html+=`</div>`;
    });

    document.getElementById('modal-body').innerHTML=html;
    const bg=document.getElementById('cal-modal-bg');
    bg.style.display='flex'; document.body.style.overflow='hidden';
}
function closeModal(e){
    if(e&&e.target!==document.getElementById('cal-modal-bg')&&e.type==='click') return;
    document.getElementById('cal-modal-bg').style.display='none';
    document.body.style.overflow='';
}
window.openModal=openModal; window.closeModal=closeModal;

// ── Navigation ────────────────────────────────────────
document.getElementById('cal-prev').onclick=()=>{ CM--; if(CM<1){CM=12;CY--;} render(); };
document.getElementById('cal-next').onclick=()=>{ CM++; if(CM>12){CM=1;CY++;} render(); };
document.getElementById('cal-today').onclick=()=>{ CY=new Date().getFullYear(); CM=new Date().getMonth()+1; render(); };

// ── Keyboard ──────────────────────────────────────────
document.addEventListener('keydown',e=>{
    if(e.key==='Escape')      closeModal();
    if(e.key==='ArrowLeft')   document.getElementById('cal-prev').click();
    if(e.key==='ArrowRight')  document.getElementById('cal-next').click();
});
window.addEventListener('scroll', hideTip, {passive:true});

// ── Init ──────────────────────────────────────────────
render();

})();
</script>
@endpush

@endsection
