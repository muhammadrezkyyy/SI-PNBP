<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Penerimaan Negara (BPN)</title>
    <style>
        @page {
            margin: 40px 50px;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #000;
            line-height: 1.5;
            position: relative;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .header td.logo {
            width: 90px;
            text-align: left;
            vertical-align: top;
        }
        .header td.logo img {
            width: 80px;
        }
        .header td.title {
            text-align: left;
            vertical-align: top;
            padding-top: 10px;
        }
        .header td.title h3 {
            margin: 0;
            font-size: 15px;
            font-weight: normal;
        }
        .header td.title p {
            margin: 0;
            font-size: 15px;
            font-weight: normal;
        }
        .header td.qr {
            width: 100px;
            text-align: right;
            vertical-align: top;
        }
        .header td.qr img {
            width: 90px;
        }
        .doc-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0 40px 0;
            line-height: 1.3;
        }
        .footer {
            margin-top: 50px;
            font-size: 11px;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            border-top: 2px solid #000;
            padding-top: 10px;
            overflow: hidden;
        }
        .section-title {
            font-size: 13px;
            font-weight: normal;
            margin-bottom: 10px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 13px;
        }
        .data-table td.label {
            width: 220px;
            padding-left: 15px;
        }
        .data-table td.colon {
            width: 15px;
            text-align: center;
        }
        .data-table td.value {
            font-weight: normal;
        }
        .bold-value {
            font-weight: bold !important;
        }
        .italic-value {
            font-style: italic !important;
        }
        .bold-label {
            font-weight: bold !important;
        }
        /* Watermark */
        .background-watermark {
            position: absolute;
            top: 250px;
            left: 50%;
            margin-left: -250px; /* half of width */
            width: 500px;
            height: 500px;
            opacity: 0.08;
            z-index: -1;
        }
    </style>
</head>
<body>

    <!-- Watermark QR -->
    @if(isset($data['qr_content']))
        <div class="background-watermark">
            {!! QrCode::size(500)->margin(0)->generate($data['qr_content']) !!}
        </div>
    @endif

    <div class="header">
        <table>
            <tr>
                <td class="logo">
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
                </td>
                <td class="title">
                    {!! $data['header_1'] ?? 'Kementerian Keuangan RI' !!}<br>
                    {!! $data['header_2'] ?? 'Direktorat Jenderal Anggaran' !!}<br>
                    {!! $data['header_3'] ?? 'SISTEM INFORMASI PNBP ONLINE (SIMPONI)' !!}
                </td>
                <td style="width: 85px; text-align: right;">
                    @if(isset($data['qr_content']))
                        {!! QrCode::size(85)->margin(0)->generate($data['qr_content']) !!}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="doc-title">
        {!! $data['title_1'] ?? 'BUKTI PENERIMAAN NEGARA' !!}<br>
        {!! $data['title_2'] ?? 'PENERIMAAN NEGARA BUKAN PAJAK (PNBP)' !!}
    </div>

    <div class="section-title">Data Pembayaran Tagihan :</div>
    <table class="data-table">
        <tr>
            <td class="label">Kode Billing</td><td class="colon">:</td>
            <td class="value">{!! $data['kode_billing'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Billing</td><td class="colon">:</td>
            <td class="value">{!! $data['tanggal_billing'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Kedaluwarsa</td><td class="colon">:</td>
            <td class="value">{!! $data['tanggal_kedaluwarsa'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Bayar</td><td class="colon">:</td>
            <td class="value bold-value">{!! $data['tanggal_bayar'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Bank/Pos/<i>Fintech</i> Bayar</td><td class="colon">:</td>
            <td class="value bold-value">{!! $data['bank_pos_fintech_bayar'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label italic-value">Channel Bayar</td><td class="colon">:</td>
            <td class="value bold-value italic-value">{!! $data['channel_bayar'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Nama Wajib Setor/Wajib Bayar</td><td class="colon">:</td>
            <td class="value">{!! $data['nama_wajib_setor'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Kementerian/Lembaga</td><td class="colon">:</td>
            <td class="value bold-value">{!! $data['kementerian_lembaga'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Unit Eselon I</td><td class="colon">:</td>
            <td class="value bold-value">{!! $data['unit_eselon_i'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Satuan Kerja</td><td class="colon">:</td>
            <td class="value">{!! $data['satuan_kerja'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Total Disetor</td><td class="colon">:</td>
            <td class="value">{!! $data['total_disetor'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Terbilang</td><td class="colon">:</td>
            <td class="value italic-value">{!! $data['terbilang'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Status</td><td class="colon">:</td>
            <td class="value">{!! $data['status'] ?? 'Sudah Dibayar' !!}</td>
        </tr>
        <tr>
            <td class="label bold-label">NTB</td><td class="colon">:</td>
            <td class="value bold-value">{!! $data['ntb'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label bold-label">NTPN</td><td class="colon">:</td>
            <td class="value bold-value">{!! $data['ntpn'] ?? '' !!}</td>
        </tr>
    </table>

    <div class="section-title">Detail Pembayaran Tagihan :</div>
    <table class="data-table">
        <tr>
            <td class="label">Jenis Setoran</td><td class="colon">:</td>
            <td class="value">{!! $data['jenis_setoran'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Kode Akun</td><td class="colon">:</td>
            <td class="value">{!! $data['kode_akun'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Jumlah Setoran</td><td class="colon">:</td>
            <td class="value">{!! $data['jumlah_setoran'] ?? '' !!}</td>
        </tr>
        <tr>
            <td class="label">Keterangan</td><td class="colon">:</td>
            <td class="value">{!! $data['keterangan'] ?? '' !!}</td>
        </tr>
    </table>

    <div class="footer">
        <div style="float: left;">Tanggal Cetak : {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} WIB</div>
        <div style="float: right;">SIMPONI</div>
        <div style="text-align: center; margin: 0 auto; width: 50px;">1/1</div>
    </div>
</body>
</html>
