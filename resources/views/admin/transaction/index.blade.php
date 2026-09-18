<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Data Transaksi</h2>
    </x-slot>

    <div class="bg-white p-6 rounded shadow">

        {{-- FILTER --}}
        <div class="flex justify-between mb-4">
            <form method="GET" class="flex gap-2">
                <input type="date" name="from" value="{{ request('from') }}" class="border px-3 py-2 rounded">
                <input type="date" name="to" value="{{ request('to') }}" class="border px-3 py-2 rounded">
                <button class="bg-blue-500 text-white px-4 rounded">Filter</button>
            </form>

            <div class="space-x-2">
                <button class="bg-red-500 text-white px-4 py-2 rounded">PDF Data</button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Excel Data</button>
                <button class="bg-green-500 text-white px-4 py-2 rounded">CSV Data</button>
            </div>
        </div>

        {{-- SEARCH --}}
        <form method="GET" class="mb-4 text-right">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Pencarian..."
                class="border px-3 py-2 rounded">
        </form>

        <table class="w-full border">
            <thead class="bg-gray-600 text-white">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Status Transaksi</th>
                    <th>Penilaian</th>
                    <th>Saran</th>
                    <th>Tanggal</th>
                    <th>Status Penilaian</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($transactions as $item)
                    <tr class="border-t text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->customer->nama_lengkap }}</td>

                        <td>
                            <form action="{{ route('admin.transaction.updateStatus', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <select name="status_transaksi" onchange="this.form.submit()"
                                    class="px-2 py-1 rounded text-white
                                    {{ $item->status_transaksi == 'Sudah dilayani' ? 'bg-green-500' : 'bg-red-500' }}">

                                    <option value="Belum dilayani"
                                        {{ $item->status_transaksi == 'Belum dilayani' ? 'selected' : '' }}>
                                        Belum dilayani
                                    </option>

                                    <option value="Sudah dilayani"
                                        {{ $item->status_transaksi == 'Sudah dilayani' ? 'selected' : '' }}>
                                        Sudah dilayani
                                    </option>
                                </select>
                            </form>
                        </td>

                        {{-- <td>{{ $item->penilaian->penilaian_layanan ?? '-' }}</td> --}}
                        <td>
                            @if ($item->feedback)
                                {{ $item->feedback->rating_pelayanan }},
                                {{ $item->feedback->rating_fasilitas }},
                                {{ $item->feedback->rating_kualitas }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->feedback->saran_kritik ?? '-' }}</td>
                        <td>{{ $item->created_at->format('Y-m-d H:i:s') }}</td>

                        <td>
                            <span
                                class="px-3 py-1 rounded text-white
                                {{ $item->status_penilaian == 'Sudah menilai' ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $item->status_penilaian }}
                            </span>
                        </td>

                        <td class="space-x-1">
                            <form action="{{ route('admin.transaction.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded inline-block">
                                    Hapus
                                </button>
                                <a href="{{ route('admin.transaction.show', $item->id) }}"
                                    class="bg-sky-500 text-white px-3 py-1 rounded inline-block">
                                    Lihat
                                </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
