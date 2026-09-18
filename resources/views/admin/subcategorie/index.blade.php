<x-app-layout>
    <div class="bg-gray-200 p-3 mb-4 rounded text-center font-semibold text-gray-700">
        Data Sub Categorie
    </div>

    <div class="flex justify-between items-center mb-3">
        <a href="{{ route('admin.subcategorie.create') }}"
            class="bg-green-400 hover:bg-green-500 text-white px-3 py-1 rounded text-sm font-semibold">
            Tambah Data
        </a>

        <div class="text-sm">
            <label for="search" class="mr-2 font-semibold">Pencarian :</label>
            <input type="text" id="search" name="search" placeholder="Cari sub kategori..."
                class="border border-gray-300 rounded px-2 py-1 text-sm">
        </div>
    </div>

    <table class="w-full border-collapse border border-gray-300 text-left text-sm">
        <thead>
            <tr class="bg-gray-400 text-white">
                <th class="border border-gray-300 px-4 py-2 w-12">No</th>
                <th class="border border-gray-300 px-4 py-2">Sub Kategori</th>
                <th class="border border-gray-300 px-4 py-2">Kategori</th>
                <th class="border border-gray-300 px-4 py-2 w-40">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subcategories as $key => $subcategorie)
                <tr class="{{ $key % 2 == 0 ? 'bg-gray-300' : 'bg-white' }}">
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $subcategories->firstItem() + $key }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ $subcategorie->sub_categorie_type }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $subcategorie->categorie->categorie_type }}</td>
                    <td class="border border-gray-300 px-4 py-2 flex justify-center space-x-2">
                        <a href="{{ route('admin.subcategorie.edit', $subcategorie) }}"
                            class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs font-semibold">
                            Ubah
                        </a>
                        <form action="{{ route('admin.subcategorie.destroy', $subcategorie) }}" method="POST"
                            onsubmit="return confirm('Yakin hapus sub kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-2">
        {{ $subcategories->links() }}
    </div>
</x-app-layout>
