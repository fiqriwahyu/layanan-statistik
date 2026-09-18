<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Pengunjung</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <h2>Data Pengunjung</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>No HP</th>
                <th>Gender</th>
                <th>Pendidikan</th>
                <th>Pekerjaan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengunjungs as $key => $p)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $p->nama_lengkap }}</td>
                    <td>{{ $p->no_hp }}</td>
                    <td>{{ $p->jenis_kelamin }}</td>
                    <td>{{ $p->education->education_type ?? '-' }}</td>
                    <td>{{ $p->work->work_type ?? '-' }}</td>
                    <td>{{ $p->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
