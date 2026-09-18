<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Service</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow">
        @if (session('success'))
            <div class="mb-4 text-green-600 font-semibold">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.service.create') }}"
            class="inline-block mb-4 bg-green-400 hover:bg-green-500 text-white px-3 py-1 rounded font-semibold text-sm">
            Tambah Data
        </a>

        <table class="w-full border border-gray-300 rounded text-sm">
            <thead>
                <tr class="bg-gray-400 text-white text-left">
                    <th class="border border-gray-300 px-4 py-2 w-12">No</th>
                    <th class="border border-gray-300 px-4 py-2">Nama Jenis Pelayanan</th>
                    <th class="border border-gray-300 px-4 py-2 w-40">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $key => $item)
                    <tr class="{{ $key % 2 == 0 ? 'bg-gray-300' : 'bg-white' }}">
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            {{ $services->firstItem() + $key }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $item->service_type }}</td>
                        <td class="border border-gray-300 px-4 py-2 flex justify-center space-x-2">
                            <a href="{{ route('admin.service.edit', $item->id) }}"
                                class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs font-semibold">
                                Ubah
                            </a>
                            <form action="{{ route('admin.service.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus jenis pelayanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">Data kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3 text-sm text-gray-600">
            Showing {{ $services->firstItem() ?? 0 }} to {{ $services->lastItem() ?? 0 }} of
            {{ $services->total() ?? 0 }} entries
        </div>

        <div class="mt-2">
            {{ $services->links() }}
        </div>
    </div>
</x-app-layout>
