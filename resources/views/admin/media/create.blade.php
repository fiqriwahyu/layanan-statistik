<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah media</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow max-w-md mx-auto">
        @if ($errors->any())
            <div class="mb-4 text-red-600 font-semibold">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.media.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="media_type" class="block font-semibold mb-1">Nama Media Pelayanan</label>
                <input type="text" name="media_type" id="media_type" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-green-300"
                    placeholder="Masukkan nama media pelayanan" value="{{ old('media_type') }}">
            </div>
            <div class="flex justify-between">
                <a href="{{ route('admin.media.index') }}"
                    class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded font-semibold">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded font-semibold">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
