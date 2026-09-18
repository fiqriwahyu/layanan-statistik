<x-app-layout>
    <div class="bg-gray-200 p-3 rounded mb-4 font-semibold text-gray-700 text-center">
        Tambah Sub Categorie
    </div>

    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <form action="{{ route('admin.subcategorie.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="sub_categorie_type" class="block mb-1 font-semibold">Nama Sub Kategori</label>
                <input type="text" id="sub_categorie_type" name="sub_categorie_type" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-green-300"
                    placeholder="Masukkan nama sub kategori">
                @error('sub_categorie_type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="categorie_id" class="block mb-1 font-semibold">Kategori</label>
                <select name="categorie_id" id="categorie_id" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-green-300">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}"
                            {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->categorie_type }}
                        </option>
                    @endforeach
                </select>
                @error('categorie_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between">
                <a href="{{ route('admin.subcategorie.index') }}"
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
