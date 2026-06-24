<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Penerimaan Negara (BPN)</title>
    <style>
        @page {
            margin: 35px 45px 50px 45px;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.4;
            position: relative;
        }

        /* ─── HEADER ─────────────────────────────────────────── */
        .header-wrap {
            width: 100%;
            margin-bottom: 12px;
            position: relative;
        }
        .header-logo-text {
            display: table;
            width: 100%;
        }
        .header-logo-text .col-logo {
            display: table-cell;
            width: 72px;
            vertical-align: middle;
        }
        .header-logo-text .col-logo img {
            width: 68px;
            display: block;
        }
        .header-logo-text .col-gap {
            display: table-cell;
            width: 24px; /* Diperlebar agar teks tidak mepet logo */
        }
        .header-logo-text .col-title {
            display: table-cell;
            vertical-align: middle;
        }
        .header-logo-text .col-title p {
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .header-logo-text .col-title .h1 {
            font-size: 13px;
            font-weight: bold;
        }
        .header-logo-text .col-title .h2 {
            font-size: 13px;
            font-weight: bold;
        }
        .header-logo-text .col-title .h3 {
            font-size: 12px;
            font-weight: bold;
        }
        /* QR di pojok kanan atas — sesuai SIMPONI asli */
        .header-qr {
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            text-align: right;
        }
        .header-qr img {
            width: 78px;
            height: 78px;
        }

        /* Garis pemisah bawah header */
        .header-divider {
            border: none;
            border-top: 2px solid #000;
            margin: 10px 85px 8px 0; /* Margin Kanan 85px agar tidak menabrak QR Code */
        }

        /* ─── DOC TITLE ──────────────────────────────────────── */
        .doc-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 28px 0 18px 0; /* Margin atas ditambah agar judul lebih turun */
            line-height: 1.5;
            letter-spacing: 0.3px;
        }

        /* ─── SECTION TITLE ──────────────────────────────────── */
        .section-title {
            font-size: 11px;
            font-weight: normal;
            margin-bottom: 6px;
        }

        /* ─── DATA TABLE ─────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .data-table td {
            padding: 2.5px 0;
            vertical-align: top;
            font-size: 11px;
        }
        .data-table td.label {
            width: 220px;
            padding-left: 12px;
        }
        .data-table td.colon {
            width: 14px;
            text-align: center;
        }
        .data-table td.value {
            font-weight: normal;
        }
        .bold-value   { font-weight: bold !important; }
        .italic-value { font-style: italic !important; }
        .bold-label   { font-weight: bold !important; }

        /* ─── FOOTER ─────────────────────────────────────────── */
        .footer {
            position: absolute;
            bottom: -30px;
            left: 0;
            right: 0;
            font-size: 9px;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-style: italic;
        }
        .footer-line {
            border: none;
            border-top: 1px solid #000;
            margin-bottom: 3px;
        }
        .footer-inner {
            display: table;
            width: 100%;
        }
        .footer-left  { display: table-cell; text-align: left; }
        .footer-center{ display: table-cell; text-align: center; }
        .footer-right { display: table-cell; text-align: right; }

        /* ─── WATERMARK ──────────────────────────────────────── */
        .background-watermark {
            position: absolute;
            top: 160px;
            left: 50%;
            margin-left: -230px;
            width: 460px;
            height: 460px;
            opacity: 0.07;
            z-index: -1;
        }
        .background-watermark img {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>

    {{-- Watermark QR --}}
    @if(isset($data['qr_content']))
        @php
            $wmQrBase64 = 'data:image/svg+xml;base64,' . base64_encode(QrCode::format('svg')->size(460)->margin(0)->generate($data['qr_content']));
        @endphp
        <div class="background-watermark">
            <img src="{{ $wmQrBase64 }}" />
        </div>
    @endif

    {{-- ═══ HEADER ═══ --}}
    <div class="header-wrap">

        {{-- QR Kanan Atas (sesuai posisi SIMPONI asli) --}}
        @if(isset($data['qr_content']))
            @php
                $topQrBase64 = 'data:image/svg+xml;base64,' . base64_encode(QrCode::format('svg')->size(78)->margin(0)->generate($data['qr_content']));
            @endphp
            <div class="header-qr">
                <img src="{{ $topQrBase64 }}" />
            </div>
        @endif

        {{-- Logo + Teks Kiri --}}
        <div class="header-logo-text">
            <div class="col-logo">
                @php
                    $logoPath = public_path('images/kemenkeu_logo.png');
                    $logoBase64 = '';
                    if (file_exists($logoPath)) {
                        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                    }
                @endphp
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" />
                @endif
            </div>
            <div class="col-gap"></div>
            <div class="col-title">
                <p>
                    <span class="h1">{!! $data['header_1'] ?? 'Kementerian Keuangan RI' !!}</span><br>
                    <span class="h2">{!! $data['header_2'] ?? 'Direktorat Jenderal Anggaran' !!}</span><br>
                    <span class="h3">{!! $data['header_3'] ?? 'SISTEM INFORMASI PNBP ONLINE (SIMPONI)' !!}</span>
                </p>
            </div>
        </div>

    </div>

    {{-- ═══ JUDUL DOKUMEN ═══ --}}
    <div class="doc-title">
        {!! $data['title_1'] ?? 'BUKTI PENERIMAAN NEGARA' !!}<br>
        {!! $data['title_2'] ?? 'PENERIMAAN NEGARA BUKAN PAJAK (PNBP)' !!}
    </div>

    {{-- ═══ DATA PEMBAYARAN TAGIHAN ═══ --}}
    <div class="section-title">Data Pembayaran Tagihan :</div>
    <table class="data-table">
        <tr>
            <td class="label">Kode Billing</td><td class="colon">:</td>
            <td class="value">{!! nl2br(e($data['kode_billing'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Billing</td><td class="colon">:</td>
            <td class="value">{!! nl2br(e($data['tanggal_billing'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Kedaluwarsa</td><td class="colon">:</td>
            <td class="value">{!! nl2br(e($data['tanggal_kedaluwarsa'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Bayar</td><td class="colon">:</td>
            <td class="value bold-value">{!! nl2br(e($data['tanggal_bayar'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Bank/Pos/<i>Fintech</i> Bayar</td><td class="colon">:</td>
            <td class="value bold-value">{!! nl2br(e($data['bank_pos_fintech_bayar'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label italic-value">Channel Bayar</td><td class="colon">:</td>
            <td class="value bold-value italic-value">{!! nl2br(e($data['channel_bayar'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Nama Wajib Setor/Wajib Bayar</td><td class="colon">:</td>
            <td class="value">{!! nl2br(e($data['nama_wajib_setor'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Kementerian/Lembaga</td><td class="colon">:</td>
            <td class="value bold-value">{!! nl2br(e($data['kementerian_lembaga'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Unit Eselon I</td><td class="colon">:</td>
            <td class="value bold-value">{!! nl2br(e($data['unit_eselon_i'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Satuan Kerja</td><td class="colon">:</td>
            <td class="value">{!! nl2br(e($data['satuan_kerja'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Total Disetor</td><td class="colon">:</td>
            <td class="value">{!! nl2br(e($data['total_disetor'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Terbilang</td><td class="colon">:</td>
            <td class="value italic-value">{!! nl2br(e($data['terbilang'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Status</td><td class="colon">:</td>
            <td class="value">{!! nl2br(e($data['status'] ?? 'Sudah Dibayar')) !!}</td>
        </tr>
        <tr>
            <td class="label bold-label">NTB</td><td class="colon">:</td>
            <td class="value bold-value">{!! nl2br(e($data['ntb'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label bold-label">NTPN</td><td class="colon">:</td>
            <td class="value bold-value">{!! nl2br(e($data['ntpn'] ?? '')) !!}</td>
        </tr>
    </table>

    {{-- ═══ DETAIL PEMBAYARAN TAGIHAN ═══ --}}
    <div class="section-title">Detail Pembayaran Tagihan :</div>
    <table class="data-table">
        <tr>
            <td class="label">Jenis Setoran</td><td class="colon">:</td>
            <td class="value">{!! nl2br(e($data['jenis_setoran'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Kode Akun</td><td class="colon">:</td>
            <td class="value">{!! nl2br(e($data['kode_akun'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Jumlah Setoran</td><td class="colon">:</td>
            <td class="value">{!! nl2br(e($data['jumlah_setoran'] ?? '')) !!}</td>
        </tr>
        <tr>
            <td class="label">Keterangan</td><td class="colon">:</td>
            <td class="value">{!! nl2br(e($data['keterangan'] ?? '')) !!}</td>
        </tr>
    </table>

    {{-- ═══ FOOTER ═══ --}}
    <div class="footer">
        <hr class="footer-line">
        <div class="footer-inner">
            <div class="footer-left">Tanggal Cetak : {{ \Carbon\Carbon::now()->setTimezone('Asia/Makassar')->format('d/m/Y H:i:s') }} WIB</div>
            <div class="footer-center">1/1</div>
            <div class="footer-right">SIMPONI</div>
        </div>
    </div>

</body>
</html>
