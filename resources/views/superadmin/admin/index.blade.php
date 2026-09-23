<x-app-layout>

    <div class="w-full px-3 sm:px-4 lg:px-5 py-4 sm:py-5">

        <div class="w-full border border-gray-400 bg-white rounded-sm">

            <div class="min-h-[64px] flex items-center justify-center px-4 sm:px-6 py-3 bg-gray-100 border-b border-gray-300">
                <h2 class="text-[20px] sm:text-[24px] leading-6 font-normal text-gray-600 text-center">
                    Data Admin
                </h2>
            </div>

            <div class="px-3 sm:px-5 lg:px-7 py-4 sm:py-5">

                @if (session('success'))
                    <div class="mb-4 px-3 sm:px-4 py-2 bg-green-100 border border-green-300 text-green-700 text-[14px] rounded-sm break-words">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 px-3 sm:px-4 py-2 bg-red-100 border border-red-300 text-red-700 text-[14px] rounded-sm break-words">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">

                    <a href="{{ route('superadmin.admin.create') }}"
                        class="w-full sm:w-auto self-start text-center bg-green-500 hover:bg-green-600 text-white text-[14px] px-4 py-2 rounded-sm">
                        Tambah Data
                    </a>

                </div>

                <div class="w-full overflow-x-auto">

                    <table class="w-full min-w-[600px] border-collapse text-[16px]">

                        <thead>

                            <tr class="bg-slate-600 text-white">

                                <th class="h-[43px] border border-gray-400 px-3 sm:px-4 text-center font-normal w-[70px] whitespace-nowrap">
                                    No
                                </th>

                                <th class="border border-gray-400 px-3 sm:px-4 text-center font-normal whitespace-nowrap">
                                    Nama Admin
                                </th>

                                <th class="border border-gray-400 px-3 sm:px-4 text-center font-normal whitespace-nowrap">
                                    Email
                                </th>

                                <th class="border border-gray-400 px-3 sm:px-4 text-center font-normal w-[110px] whitespace-nowrap">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse ($admins as $key => $admin)

                                <tr class="{{ $key % 2 == 0 ? 'bg-gray-300' : 'bg-white' }} hover:bg-gray-200">

                                    <td class="h-[70px] border border-gray-300 px-3 sm:px-4 text-center align-middle">
                                        {{ $key + 1 }}
                                    </td>

                                    <td class="h-[70px] border border-gray-300 px-3 sm:px-4 text-center align-middle break-words">
                                        {{ $admin->name }}
                                    </td>

                                    <td class="h-[70px] border border-gray-300 px-3 sm:px-4 text-center align-middle break-words">
                                        {{ $admin->email }}
                                    </td>

                                    <td class="border border-gray-300 px-2 sm:px-3 align-middle">

                                        <div class="flex flex-col items-center justify-center gap-1">

                                            <a href="{{ route('superadmin.admin.edit', $admin->id) }}"
                                                class="w-[52px] text-center bg-yellow-400 hover:bg-yellow-500 text-white text-[14px] py-1 rounded-sm">
                                                Ubah
                                            </a>

                                            <form action="{{ route('superadmin.admin.destroy', $admin->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin hapus admin ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="w-[52px] bg-red-500 hover:bg-red-600 text-white text-[14px] py-1 rounded-sm">
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4"
                                        class="border border-gray-300 text-center py-8 px-3 text-gray-500 text-[16px]">
                                        Data admin tidak ditemukan
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>