<x-app-layout>
    <div class="bg-gray-200 p-3 rounded mb-4 font-semibold text-gray-700 text-center">
        Edit Sub Categorie
    </div>

    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <form action="{{ route('admin.subcategorie.update', $subcategorie) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="sub_categorie_type" class="block mb-1 font-semibold">Nama Sub Kategori</label>
                <input type="text" id="sub_categorie_type" name="sub_categorie_type"
                    value="{{ old('sub_categorie_type', $subcategorie->sub_categorie_type) }}" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-yellow-300"
                    placeholder="Masukkan nama sub kategori">
                @error('sub_categorie_type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="categorie_id" class="block mb-1 font-semibold">Kategori</label>
                <select name="categorie_id" id="categorie_id" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-yellow-300">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}"
                            {{ old('categorie_id', $subcategorie->categorie_id) == $categorie->id ? 'selected' : '' }}>
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
                    class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded font-semibold">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
