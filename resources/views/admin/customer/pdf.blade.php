<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Data Pengunjung</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
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
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
        }

        td {
            text-align: left;
        }

        td.center {
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

    </style>

</head>

<body>

    <h2>DATA PENGUNJUNG</h2>

    <table>

        <thead>

            <tr>

                <th width="5%">
                    No
                </th>

                <th>
                    Nama Lengkap
                </th>

                <th>
                    No HP
                </th>

                <th>
                    Gender
                </th>

                <th>
                    Pendidikan
                </th>

                <th>
                    Pekerjaan
                </th>

                <th>
                    Tanggal
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse ($pengunjungs as $key => $p)

                <tr>

                    <td class="center">
                        {{ $key + 1 }}
                    </td>

                    <td>
                        {{ $p->nama_lengkap }}
                    </td>

                    <td>
                        {{ $p->no_hp }}
                    </td>

                    <td class="center">
                        {{ $p->jenis_kelamin }}
                    </td>

                    <td>
                        {{ $p->education?->education_type ?? '-' }}
                    </td>

                    <td>
                        {{ $p->work?->work_type ?? '-' }}
                    </td>

                    <td class="center">
                        {{ $p->created_at?->format('Y-m-d H:i') }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="center">

                        Data pengunjung tidak ditemukan

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</body>

</html>