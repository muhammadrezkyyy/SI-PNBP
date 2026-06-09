<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Reservasi PNBP</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            background: #fff;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #334155;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header h1 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e40af;
            letter-spacing: 1px;
        }
        .header h2 {
            font-size: 13px;
            font-weight: bold;
            margin-top: 4px;
            color: #334155;
        }
        .header p {
            font-size: 9px;
            color: #64748b;
            margin-top: 4px;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 16px;
            font-size: 10px;
        }
        .meta-table td {
            padding: 2px 6px;
            vertical-align: top;
        }
        .meta-table .label {
            font-weight: bold;
            color: #475569;
            width: 140px;
        }

        .summary-box {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 16px;
            display: inline-block;
            width: 100%;
        }
        .summary-box .row {
            display: flex;
            justify-content: space-between;
        }
        .summary-box .item {
            display: inline-block;
            margin-right: 30px;
        }
        .summary-box .item .val {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
        }
        .summary-box .item .lbl {
            font-size: 9px;
            color: #64748b;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.data thead th {
            background: #1e3a5f;
            color: #fff;
            font-size: 9px;
            font-weight: 600;
            padding: 6px 8px;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.data tbody td {
            padding: 5px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9px;
        }
        table.data tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .status-lunas {
            color: #15803d;
            font-weight: bold;
        }
        .status-tolak {
            color: #dc2626;
            font-weight: bold;
        }
        .status-proses {
            color: #d97706;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
        }

        .signature-area {
            margin-top: 30px;
            width: 100%;
        }
        .signature-area td {
            text-align: center;
            padding: 8px;
            vertical-align: top;
        }
        .signature-line {
            margin-top: 50px;
            border-bottom: 1px solid #334155;
            width: 160px;
            display: inline-block;
        }
        .signature-title {
            font-size: 9px;
            font-weight: bold;
            color: #475569;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: 'DejaVu Sans Mono', monospace; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h2>Rekapitulasi Laporan Reservasi & Penerimaan Negara Bukan Pajak (PNBP)</h2>
    </div>

    {{-- Filter Metadata --}}
    <table class="meta-table">
        <tr>
            <td class="label">Periode Laporan:</td>
            <td>{{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '-' }} s/d {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '-' }}</td>
            <td class="label">Fasilitas:</td>
            <td>{{ $buildingName }}</td>
        </tr>
        <tr>
            <td class="label">Filter Status:</td>
            <td>{{ $statusFilter }}</td>
            <td class="label">Total Data:</td>
            <td>{{ $reservations->count() }} reservasi</td>
        </tr>
    </table>



    {{-- Data Table --}}
    <table class="data">
        <thead>
            <tr>
                <th style="width:25px">No</th>
                <th>Tgl Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Instansi</th>
                <th>Gedung/Fasilitas</th>
                <th>Jadwal Reservasi</th>
                <th>Kode Billing</th>
                <th>NTPN</th>
                <th>Status</th>
                <th class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $index => $r)
                @php
                    $statusVal = $r->status->value ?? $r->status;
                    $statusClass = match($statusVal) {
                        'CONFIRMED', 'COMPLETED' => 'status-lunas',
                        'REJECTED' => 'status-tolak',
                        default => 'status-proses',
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $r->created_at->format('d/m/Y') }}</td>
                    <td>{{ $r->customer_name }}</td>
                    <td>{{ $r->customer_data['instansi'] ?? '-' }}</td>
                    <td>{{ $r->building ? $r->building->name : '-' }}</td>
                    <td>{{ $r->start_date ? $r->start_date->format('d/m/y') : '-' }} - {{ $r->end_date ? $r->end_date->format('d/m/y') : '-' }}</td>
                    <td class="font-mono">{{ $r->payment->simponi_billing_code ?? '-' }}</td>
                    <td class="font-mono">{{ $r->payment->ntpn ?? '-' }}</td>
                    <td class="{{ $statusClass }}">{{ $statusLabels[$r->status->value ?? $r->status] ?? $r->status }}</td>
                    <td class="text-right">Rp {{ number_format($r->payment ? $r->payment->nominal : 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Signature Area --}}
    <table class="signature-area">
        <tr>
            <td style="width:50%">
                <p class="signature-title">Mengetahui,</p>
                <p class="signature-title">Kepala BBPP Makassar</p>
                <div class="signature-line"></div>
                <p style="font-size:9px; color: #475569;">NIP. ........................</p>
            </td>
            <td style="width:50%">
                <p class="signature-title">Makassar, {{ now()->isoFormat('D MMMM YYYY') }}</p>
                <p class="signature-title">Admin / Petugas</p>
                <div class="signature-line"></div>
                <p style="font-size:9px; color: #475569;">NIP. ........................</p>
            </td>
        </tr>
    </table>


</body>
</html>
