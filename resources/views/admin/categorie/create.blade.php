<x-app-layout>
    <div class="bg-gray-200 p-3 rounded mb-4 font-semibold text-gray-700 text-center">
        Tambah categorie
    </div>

    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <form action="{{ route('admin.categorie.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="categorie_type" class="block mb-1 font-semibold">Nama Kategori</label>
                <input type="text" id="categorie_type" name="categorie_type" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-green-300"
                    placeholder="Masukkan nama kategori">
                @error('categorie_type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between">
                <a href="{{ route('admin.categorie.index') }}"
                    class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded font-semibold">
                    Kembali
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded font-semibold">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
