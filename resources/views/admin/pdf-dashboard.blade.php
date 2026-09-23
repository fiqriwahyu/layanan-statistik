<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Ringkasan Layanan</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 15px 22px 22px 22px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        .header {
            border-bottom: 2px solid #1f5f9c;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: 0;
            padding: 0;
            vertical-align: middle;
        }

        .logo-cell {
            width: 55px;
        }

        .logo {
            width: 44px;
            height: 44px;
        }

        .identity {
            padding-left: 7px !important;
        }

        .identity-main {
            font-size: 14px;
            font-weight: bold;
            color: #1f5f9c;
        }

        .identity-sub {
            font-size: 8px;
            color: #555;
            margin-top: 2px;
        }

        .title {
            text-align: center;
            margin: 3px 0 6px;
        }

        .title-main {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .title-sub {
            font-size: 8px;
            color: #555;
            margin-top: 2px;
        }

        .period {
            font-size: 8px;
            color: #555;
            margin-top: 2px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .summary td {
            width: 25%;
            border: 1px solid #bfc7cf;
            padding: 5px 8px;
        }

        .summary-label {
            font-size: 8px;
            color: #555;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #1f5f9c;
            margin-top: 1px;
        }

        .data-layout {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .data-layout > tbody > tr > td {
            vertical-align: top;
            width: 50%;
        }

        .left {
            padding-right: 7px !important;
        }

        .right {
            padding-left: 7px !important;
        }

        .section {
            margin-bottom: 7px;
        }

        .section-title {
            font-size: 9px;
            font-weight: bold;
            color: #1f5f9c;
            padding-bottom: 3px;
            border-bottom: 1px solid #1f5f9c;
            margin-bottom: 3px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #edf2f6;
            border: 1px solid #c6cdd4;
            padding: 4px 6px;
            font-size: 8px;
            font-weight: bold;
            text-align: left;
        }

        .data-table td {
            border: 1px solid #d2d7dc;
            padding: 4px 6px;
            font-size: 8.5px;
            line-height: 1.15;
        }

        .data-table tr:nth-child(even) td {
            background: #fafbfc;
        }

        .number {
            width: 70px;
            text-align: center !important;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -11px;
            border-top: 1px solid #cbd1d6;
            padding-top: 3px;
            font-size: 6.5px;
            color: #666;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('images/icons/logo-bps.png') }}" class="logo">
                </td>

                <td class="identity">
                    <div class="identity-main">
                        BADAN PUSAT STATISTIK KOTA JAMBI
                    </div>

                    <div class="identity-sub">
                        Sistem Informasi Layanan Statistik
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="title">
        <div class="title-main">
            LAPORAN RINGKASAN LAYANAN
        </div>

        <div class="title-sub">
            Sistem Informasi Layanan Statistik BPS Kota Jambi
        </div>

        <div class="period">
            Periode: {{ $periode }}
        </div>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-label">Total Pengunjung</div>
                <div class="summary-value">{{ $totalPengunjung }}</div>
            </td>

            <td>
                <div class="summary-label">Belum Dilayani</div>
                <div class="summary-value">{{ $belumDilayani }}</div>
            </td>

            <td>
                <div class="summary-label">Sudah Dilayani</div>
                <div class="summary-value">{{ $sudahDilayani }}</div>
            </td>
        </tr>
    </table>

    <table class="data-layout">
        <tr>

            <td class="left">

                <div class="section">
                    <div class="section-title">
                        1. Data Pengunjung Berdasarkan Pendidikan
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Pendidikan</th>
                                <th class="number">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($pendidikan as $item)
                                <tr>
                                    <td>{{ $item->education_type }}</td>
                                    <td class="number">{{ $item->customers_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="section">
                    <div class="section-title">
                        2. Data Pengunjung Berdasarkan Pekerjaan
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Pekerjaan</th>
                                <th class="number">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($pekerjaan as $item)
                                <tr>
                                    <td>{{ $item->work_type }}</td>
                                    <td class="number">{{ $item->customers_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="section">
                    <div class="section-title">
                        3. Data Transaksi Berdasarkan Media Pelayanan
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Media Pelayanan</th>
                                <th class="number">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($mediaPelayanan as $item)
                                <tr>
                                    <td>{{ $item->media_type }}</td>
                                    <td class="number">{{ $item->transactions_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </td>

            <td class="right">

                <div class="section">
                    <div class="section-title">
                        4. Data Transaksi Berdasarkan Jenis Pelayanan
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Jenis Pelayanan</th>
                                <th class="number">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($jenisPelayanan as $item)
                                <tr>
                                    <td>{{ $item->service_type }}</td>
                                    <td class="number">{{ $item->transactions_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="section">
                    <div class="section-title">
                        5. Data Transaksi Berdasarkan Tujuan
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tujuan</th>
                                <th class="number">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($tujuan as $item)
                                <tr>
                                    <td>{{ $item->purpose_type }}</td>
                                    <td class="number">{{ $item->transactions_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </td>

        </tr>
    </table>

    <div class="footer">
        <div class="footer-left">
            Sistem Informasi Layanan Statistik — BPS Kota Jambi
        </div>

        <div class="footer-right">
            Dicetak: {{ now()->format('d-m-Y H:i') }}
        </div>
    </div>

</body>
</html>