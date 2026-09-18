<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Buku Tamu - BPS Kota Jambi</title>
    <style>
        #emp {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        #emp td,
        #emp th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #emp tr:nth-child(even) {
            background-color: #0bfdfd;
        }

        #emp th {
            padding: 12px;
            background-color: #4CAF50;
            color: white;
        }

        h2,
        h3,
        h4 {
            text-align: center;
        }
    </style>
</head>

<body>

    <table>
        <tr>
            <td>
                <img src="logoBps.png" style="width:100px;height:100px;">
            </td>
            <td>
                <h2>Badan Pusat Statistik (BPS)</h2>
                <h3>Kota Jambi</h3>
                <h4><i>Jl. Jambi (isi alamat lengkap)</i></h4>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
    </table>

    <h2>Ringkasan - Data Buku Tamu</h2>
    <p style="text-align: justify">
        Buku tamu <strong>BPS Kota Jambi</strong> merupakan daftar pengunjung dalam rangka
        pemenuhan kebutuhan data statistik. Data ini digunakan sebagai bahan evaluasi pelayanan.
    </p>

    <p style="text-align:center">Total Pengunjung : <strong>100</strong> orang</p>
    <p style="text-align:center">Total Transaksi : <strong>80</strong> kali</p>
    <p style="text-align:center">Belum Dilayani : <strong>20</strong> kali</p>
    <p style="text-align:center">Total Dilayani : <strong>60</strong> kali</p>

    <h4>1. Berdasarkan Pekerjaan</h4>
    <table id="emp">
        <tr>
            <th>ASN</th>
            <th>Pelajar/Mahasiswa</th>
            <th>Dosen/Guru</th>
            <th>Peneliti</th>
            <th>Umum</th>
        </tr>
        <tr>
            <td>20</td>
            <td>30</td>
            <td>10</td>
            <td>5</td>
            <td>35</td>
        </tr>
    </table>

    <h4>2. Berdasarkan Pendidikan</h4>
    <table id="emp">
        <tr>
            <th>SMP</th>
            <th>SMA</th>
            <th>Diploma</th>
            <th>S1</th>
            <th>S2</th>
            <th>S3</th>
        </tr>
        <tr>
            <td>5</td>
            <td>20</td>
            <td>10</td>
            <td>40</td>
            <td>15</td>
            <td>10</td>
        </tr>
    </table>

    <h4>3. Berdasarkan Layanan</h4>
    <table id="emp">
        <tr>
            <th>Permintaan Data</th>
            <th>Konsultasi</th>
            <th>Rekomendasi</th>
            <th>Umum</th>
        </tr>
        <tr>
            <td>30</td>
            <td>20</td>
            <td>10</td>
            <td>20</td>
        </tr>
    </table>

    <h4>4. Berdasarkan Media</h4>
    <table id="emp">
        <tr>
            <th>WhatsApp</th>
            <th>Pelayanan Langsung</th>
        </tr>
        <tr>
            <td>50</td>
            <td>30</td>
        </tr>
    </table>

    <h4>5. Berdasarkan Tujuan</h4>
    <table id="emp">
        <tr>
            <th>Skripsi</th>
            <th>Tesis</th>
            <th>Disertasi</th>
            <th>Penelitian</th>
            <th>Umum</th>
        </tr>
        <tr>
            <td>30</td>
            <td>10</td>
            <td>5</td>
            <td>25</td>
            <td>10</td>
        </tr>
    </table>

</body>

</html>
