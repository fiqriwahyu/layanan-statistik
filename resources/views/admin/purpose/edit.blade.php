<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Purpose</h2>
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

        <form action="{{ route('admin.purpose.update', $purpose->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="purpose_type" class="block font-semibold mb-1">Nama Purpose</label>
                <input type="text" name="purpose_type" id="purpose_type" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-yellow-300"
                    value="{{ old('purpose_type', $purpose->purpose_type) }}">
            </div>
            <div class="flex justify-between">
                <a href="{{ route('admin.purpose.index') }}"
                    class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded font-semibold">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded font-semibold">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
