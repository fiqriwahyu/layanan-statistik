<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Admin</h2>
    </x-slot>

    <div class="bg-white p-6 rounded shadow w-full max-w-xl">

        <form action="{{ route('superadmin.admin.update', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama</label>
                <input type="text" name="name" value="{{ $admin->name }}"
                    class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ $admin->email }}"
                    class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">
                    Password (kosongkan jika tidak diubah)
                </label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="flex gap-2">

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Update
                </button>

                <a href="{{ route('superadmin.admin.index') }}"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                    Kembali
                </a>

            </div>

        </form>

    </div>
</x-app-layout>
