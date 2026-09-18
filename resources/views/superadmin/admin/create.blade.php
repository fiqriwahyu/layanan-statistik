<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Tambah Admin</h2>
    </x-slot>

    <div class="bg-white p-6 rounded shadow w-full max-w-xl">

        <form action="{{ route('superadmin.admin.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="flex gap-2">

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Simpan
                </button>

                <a href="{{ route('superadmin.admin.index') }}"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                    Kembali
                </a>

            </div>

        </form>

    </div>
</x-app-layout>
