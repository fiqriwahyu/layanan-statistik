<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Transaksi</title>

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
        }

        .data-table thead {
            display: table-header-group;
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

        .status {
            text-align: center;
            font-weight: bold;
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

    {{-- HEADER --}}
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

    {{-- JUDUL --}}
    <div class="title">
        LAPORAN DATA TRANSAKSI
    </div>

    <div class="subtitle">
        Sistem Informasi Layanan Statistik BPS Kota Jambi
    </div>

    {{-- INFORMASI --}}
    <table class="information">
        <tr>
            <td class="label" width="9%">Periode Data</td>
            <td width="24%">: {{ $periode }}</td>

            <td class="label" width="13%">Jumlah Transaksi</td>
            <td width="14%">: {{ $transactions->count() }} transaksi</td>

            <td class="label" width="12%">Tanggal Cetak</td>
            <td>: {{ now()->format('d-m-Y H:i:s') }} WIB</td>
        </tr>
    </table>

    {{-- TABEL TRANSAKSI --}}
    <table class="data-table">
        <colgroup>
            <col style="width: 5%;">
            <col style="width: 18%;">
            <col style="width: 13%;">
            <col style="width: 16%;">
            <col style="width: 13%;">
            <col style="width: 20%;">
            <col style="width: 8%;">
            <col style="width: 7%;">
        </colgroup>

        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>No. HP</th>
                <th>Jenis Pelayanan</th>
                <th>Tujuan</th>
                <th>Data Diminta</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($transactions as $key => $item)
                <tr>
                    <td class="center">{{ $key + 1 }}</td>
                    <td>{{ $item->customer->nama_lengkap ?? '-' }}</td>
                    <td>{{ $item->customer->no_hp ?? '-' }}</td>
                    <td>{{ $item->service->service_type ?? '-' }}</td>
                    <td>{{ $item->purpose->purpose_type ?? '-' }}</td>
                    <td>{{ $item->data_diminta ?? '-' }}</td>
                    <td class="status">{{ $item->status_transaksi ?? '-' }}</td>
                    <td class="center">{{ $item->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="no-data">
                        Data transaksi tidak ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
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