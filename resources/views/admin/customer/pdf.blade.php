<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pengunjung</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 18px 25px 25px 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8.5px;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #1f5f9c;
            padding-bottom: 6px;
            margin-bottom: 9px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .logo-cell {
            width: 58px;
        }

        .logo {
            width: 48px;
            height: auto;
        }

        .identity {
            padding-left: 5px !important;
        }

        .identity-main {
            font-size: 14px;
            font-weight: bold;
            color: #1f5f9c;
            line-height: 1.2;
        }

        .identity-sub {
            font-size: 10px;
            font-weight: bold;
            color: #333;
            margin-top: 1px;
        }

        .identity-info {
            font-size: 7.5px;
            color: #666;
            margin-top: 2px;
        }

        .title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #1f5f9c;
            text-transform: uppercase;
            margin: 5px 0 2px;
        }

        .subtitle {
            text-align: center;
            font-size: 8px;
            color: #666;
            margin-bottom: 8px;
        }

        .information {
            width: 100%;
            border: 0.6px solid #aaa;
            border-collapse: collapse;
            margin-bottom: 8px;
            background: #f7f9fb;
        }

        .information td {
            border: none;
            padding: 3.5px 5px;
            font-size: 8px;
            vertical-align: middle;
        }

        .information .label {
            font-weight: bold;
            color: #444;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .data-table thead {
            display: table-header-group;
        }

        .data-table tr {
            page-break-inside: avoid;
        }

        .data-table th {
            background: #1f5f9c;
            color: #fff;
            border: 0.5px solid #174c7e;
            padding: 5px 4px;
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            line-height: 1.2;
            vertical-align: middle;
        }

        .data-table td {
            border: 0.5px solid #999;
            padding: 4px 5px;
            font-size: 8.5px;
            line-height: 1.25;
            vertical-align: middle;
        }

        .data-table tr:nth-child(even) td {
            background: #f5f8fb;
        }

        .data-table tr:nth-child(odd) td {
            background: #fff;
        }

        .center {
            text-align: center;
        }

        .no-data {
            text-align: center;
            padding: 12px !important;
            color: #666;
        }

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -15px;
            border-top: 0.5px solid #999;
            padding-top: 3px;
            font-size: 6.8px;
            color: #666;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if (file_exists(public_path('images/icons/logo-bps.png')))
                        <img src="{{ public_path('images/icons/logo-bps.png') }}" class="logo">
                    @endif
                </td>

                <td class="identity">
                    <div class="identity-main">BADAN PUSAT STATISTIK</div>
                    <div class="identity-sub">KOTA JAMBI</div>
                    <div class="identity-info">Sistem Informasi Layanan Statistik</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="title">LAPORAN DATA PENGUNJUNG</div>

    <div class="subtitle">
        Sistem Informasi Layanan Statistik BPS Kota Jambi
    </div>

    <table class="information">
        <tr>
            <td class="label" width="9%">Periode Data</td>
            <td width="25%">: {{ $periode }}</td>

            <td class="label" width="13%">Jumlah Pengunjung</td>
            <td width="15%">: {{ $pengunjungs->count() }} orang</td>

            <td class="label" width="12%">Tanggal Cetak</td>
            <td>: {{ now()->format('d-m-Y H:i:s') }} WIB</td>
        </tr>

        @if ($search)
            <tr>
                <td class="label">Pencarian</td>
                <td colspan="5">: {{ $search }}</td>
            </tr>
        @endif
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="23%">Nama Lengkap</th>
                <th width="14%">No. HP</th>
                <th width="10%">Jenis Kelamin</th>
                <th width="16%">Pendidikan</th>
                <th width="17%">Pekerjaan</th>
                <th width="15%">Tanggal</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pengunjungs as $key => $p)
                <tr>
                    <td class="center">{{ $key + 1 }}</td>
                    <td>{{ $p->nama_lengkap }}</td>
                    <td>{{ $p->no_hp }}</td>
                    <td class="center">{{ $p->jenis_kelamin }}</td>
                    <td>{{ $p->education?->education_type ?? '-' }}</td>
                    <td>{{ $p->work?->work_type ?? '-' }}</td>
                    <td class="center">{{ $p->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="no-data">Data pengunjung tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-left">
            Sistem Informasi Layanan Statistik - BPS Kota Jambi
        </div>

        <div class="footer-right">
            Halaman <span class="page-number"></span>
        </div>
    </div>

</body>
</html>