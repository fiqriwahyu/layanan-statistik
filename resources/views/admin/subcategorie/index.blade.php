<x-app-layout>

    <div class="w-full px-3 sm:px-4 lg:px-5 py-4 sm:py-5">

        <div class="w-full border border-gray-400 bg-white rounded-sm">

            {{-- Header --}}
            <div class="min-h-[64px]
                        flex items-center justify-center
                        px-4 sm:px-6
                        py-3
                        bg-gray-100
                        border-b border-gray-300">

                <h2 class="text-[20px] sm:text-[24px]
                           leading-6
                           font-normal
                           text-gray-600
                           text-center
                           break-words">
                    Data Sub Kategorie
                </h2>

            </div>

            {{-- Body --}}
            <div class="px-3 sm:px-5 lg:px-7 py-4 sm:py-5">

                {{-- Toolbar --}}
                <div class="flex flex-col lg:flex-row
                            lg:items-center
                            lg:justify-between
                            gap-4
                            mb-4">

                    <a href="{{ route('admin.subcategorie.create') }}"
                        class="w-full sm:w-auto
                               self-start
                               text-center
                               bg-green-500
                               hover:bg-green-600
                               text-white
                               text-[14px]
                               px-4 py-2
                               rounded-sm">
                        Tambah Data
                    </a>

                    <div class="flex flex-col sm:flex-row
                                sm:items-center
                                gap-2">

                        <label for="search"
                            class="text-[15px] sm:text-[16px]
                                   text-gray-600
                                   whitespace-nowrap">
                            Pencarian :
                        </label>

                        <input type="text"
                            id="search"
                            name="search"
                            placeholder="Cari sub kategori..."
                            class="w-full sm:w-[180px]
                                   h-[36px]
                                   border border-gray-500
                                   rounded-sm
                                   px-3
                                   text-[15px] sm:text-[16px]
                                   text-gray-600
                                   focus:outline-none
                                   focus:border-gray-700">

                    </div>

                </div>

                {{-- Table --}}
                <div class="w-full overflow-x-auto">

                    <table class="w-full min-w-[700px]
                                  border-collapse
                                  text-[16px]">

                        <thead>
                            <tr class="bg-slate-600 text-white">

                                <th class="w-[70px]
                                           h-[45px]
                                           border border-gray-300
                                           px-3 sm:px-4
                                           text-center
                                           align-middle">
                                    No
                                </th>

                                <th class="h-[45px]
                                           border border-gray-300
                                           px-3 sm:px-4
                                           text-center
                                           align-middle">
                                    Sub Kategori
                                </th>

                                <th class="h-[45px]
                                           border border-gray-300
                                           px-3 sm:px-4
                                           text-center
                                           align-middle">
                                    Kategori
                                </th>

                                <th class="w-[110px]
                                           h-[45px]
                                           border border-gray-300
                                           px-3 sm:px-4
                                           text-center
                                           align-middle">
                                    Aksi
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($subcategories as $key => $subcategorie)

                                <tr class="{{ $key % 2 == 0 ? 'bg-gray-300' : 'bg-white' }}
                                           hover:bg-gray-200">

                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3 sm:px-4
                                               text-center
                                               align-middle
                                               break-words">
                                        {{ $subcategories->firstItem() + $key }}
                                    </td>

                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3 sm:px-4
                                               text-center
                                               align-middle
                                               break-words">
                                        {{ $subcategorie->sub_categorie_type }}
                                    </td>

                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3 sm:px-4
                                               text-center
                                               align-middle
                                               break-words">
                                        {{ $subcategorie->categorie->categorie_type }}
                                    </td>

                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-2 sm:px-3
                                               align-middle">

                                        <div class="flex flex-col
                                                    items-center
                                                    justify-center
                                                    gap-1">

                                            <a href="{{ route('admin.subcategorie.edit', $subcategorie) }}"
                                                class="w-[52px]
                                                       text-center
                                                       bg-yellow-400
                                                       hover:bg-yellow-500
                                                       text-white
                                                       text-[14px]
                                                       py-1
                                                       rounded-sm">
                                                Ubah
                                            </a>

                                            <form action="{{ route('admin.subcategorie.destroy', $subcategorie) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin hapus sub kategori ini?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="w-[52px]
                                                           bg-red-500
                                                           hover:bg-red-600
                                                           text-white
                                                           text-[14px]
                                                           py-1
                                                           rounded-sm">
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4"
                                        class="border border-gray-300
                                               px-4 py-6
                                               text-center
                                               text-gray-500">
                                        Data sub kategori tidak ditemukan
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Footer / Pagination --}}
                <div class="flex flex-col sm:flex-row
                            sm:items-center
                            sm:justify-between
                            gap-3
                            mt-4">

                    <div class="text-[14px] text-gray-600">
                        @if ($subcategories->total() > 0)
                            Menampilkan
                            {{ $subcategories->firstItem() }}
                            sampai
                            {{ $subcategories->lastItem() }}
                            dari
                            {{ $subcategories->total() }}
                            data
                        @else
                            Menampilkan 0 data
                        @endif
                    </div>

                    @if ($subcategories->hasPages())

                        <div class="flex items-center gap-1">

                            @if ($subcategories->onFirstPage())
                                <span class="px-2 py-1
                                             text-[14px]
                                             text-gray-400
                                             border border-gray-300
                                             rounded-sm">
                                    Sebelumnya
                                </span>
                            @else
                                <a href="{{ $subcategories->previousPageUrl() }}"
                                    class="px-2 py-1
                                           text-[14px]
                                           text-gray-600
                                           border border-gray-400
                                           rounded-sm
                                           hover:bg-gray-200">
                                    Sebelumnya
                                </a>
                            @endif

                            @for ($page = 1; $page <= $subcategories->lastPage(); $page++)

                                @if ($page == $subcategories->currentPage())

                                    <span class="min-w-[30px]
                                                 text-center
                                                 px-2 py-1
                                                 text-[14px]
                                                 bg-gray-300
                                                 border border-gray-400
                                                 rounded-sm">
                                        {{ $page }}
                                    </span>

                                @else

                                    <a href="{{ $subcategories->url($page) }}"
                                        class="min-w-[30px]
                                               text-center
                                               px-2 py-1
                                               text-[14px]
                                               text-gray-600
                                               border border-gray-400
                                               rounded-sm
                                               hover:bg-gray-200">
                                        {{ $page }}
                                    </a>

                                @endif

                            @endfor

                            @if ($subcategories->hasMorePages())

                                <a href="{{ $subcategories->nextPageUrl() }}"
                                    class="px-2 py-1
                                           text-[14px]
                                           text-gray-600
                                           border border-gray-400
                                           rounded-sm
                                           hover:bg-gray-200">
                                    Berikutnya
                                </a>

                            @else

                                <span class="px-2 py-1
                                             text-[14px]
                                             text-gray-400
                                             border border-gray-300
                                             rounded-sm">
                                    Berikutnya
                                </span>

                            @endif

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>