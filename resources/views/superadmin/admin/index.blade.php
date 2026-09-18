<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Data Admin</h2>
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('superadmin.admin.create') }}"
            class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
            Tambah Admin
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full border border-gray-300 text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border w-12 text-center">No</th>
                    <th class="p-2 border text-left">Nama</th>
                    <th class="p-2 border text-left">Email</th>
                    <th class="p-2 border w-32 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($admins as $key => $admin)
                    <tr class="{{ $key % 2 == 0 ? 'bg-gray-200' : 'bg-white' }}">
                        <td class="p-2 border text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td class="p-2 border">
                            {{ $admin->name }}
                        </td>

                        <td class="p-2 border">
                            {{ $admin->email }}
                        </td>

                        <td class="p-2 border text-center flex justify-center gap-2">

                            <a href="{{ route('superadmin.admin.edit', $admin->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs font-semibold">
                                Edit
                            </a>

                            <form action="{{ route('superadmin.admin.destroy', $admin->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus admin ini?')">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold">
                                    Hapus
                                </button>

                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4 text-gray-500">
                            Data admin belum tersedia
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</x-app-layout>
