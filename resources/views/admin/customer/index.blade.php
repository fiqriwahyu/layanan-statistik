<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Data Customer</h2>
    </x-slot>
    <div class="flex gap-2 mb-4">
        <a href="{{ route('admin.customer.export.excel', array_merge(request()->all(), ['format' => 'xlsx'])) }}"
            class="btn bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Export XLSX</a>

        <a href="{{ route('admin.customer.export.excel', array_merge(request()->all(), ['format' => 'csv'])) }}"
            class="btn bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700">Export CSV</a>

        <a href="{{ route('admin.customer.export.pdf', request()->all()) }}"
            class="btn bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Export PDF</a>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="mb-4 flex flex-wrap gap-2 items-end">
        {{-- Search --}}
        <div class="flex items-center gap-2">
            <label for="search" class="font-semibold">Pencarian :</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                placeholder="Cari nama atau telepon..." class="input border border-gray-300 rounded px-3 py-1 w-64">
        </div>

        {{-- Filter tanggal --}}
        <div class="flex items-center gap-2">
            <label for="from" class="font-semibold">Dari :</label>
            <input type="date" id="from" name="from" value="{{ request('from') }}"
                class="input border border-gray-300 rounded px-3 py-1">
            <label for="to" class="font-semibold">Sampai :</label>
            <input type="date" id="to" name="to" value="{{ request('to') }}"
                class="input border border-gray-300 rounded px-3 py-1">
        </div>

        <button class="btn bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Filter</button>
        <a href="{{ route('admin.customer.index') }}"
            class="btn bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">Reset</a>
    </form>

    {{-- TABEL --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full border border-gray-300 text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border w-12 text-center">No</th>
                    <th class="p-2 border text-left">Nama</th>
                    <th class="p-2 border text-left">Telepon</th>
                    <th class="p-2 border text-center">Gender</th>
                    <th class="p-2 border text-left">Pendidikan</th>
                    <th class="p-2 border text-left">Pekerjaan</th>
                    <th class="p-2 border text-center">Tanggal</th>
                    <th class="p-2 border w-28 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $key => $p)
                    <tr class="{{ $key % 2 == 0 ? 'bg-gray-200' : 'bg-white' }}">
                        <td class="p-2 border text-center">{{ $customers->firstItem() + $key }}</td>
                        <td class="p-2 border">{{ $p->nama_lengkap }}</td>
                        <td class="p-2 border">{{ $p->no_hp }}</td>
                        <td class="p-2 border text-center">{{ $p->jenis_kelamin }}</td>
                        <td class="p-2 border">{{ $p->education->education_type ?? '-' }}</td>
                        <td class="p-2 border">{{ $p->work->work_type ?? '-' }}</td>
                        <td class="p-2 border text-center">{{ $p->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="p-2 border text-center flex justify-center gap-2">
                            <form action="{{ route('admin.customer.destroy', $p->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold">Hapus</button>
                            </form>
                            <a href="{{ route('admin.customer.show', $p->id) }}"
                                class="bg-blue-400 hover:bg-blue-500 text-white px-3 py-1 rounded text-xs font-semibold">List</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center p-4 text-gray-500">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</x-app-layout>
