<x-app-layout>

    <div class="w-full px-3 sm:px-4 lg:px-5 py-4 sm:py-5">

        <div class="w-full border border-gray-400 bg-white rounded-sm">

            {{-- HEADER --}}
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
                           text-center">

                    Data kategorie

                </h2>

            </div>


            {{-- CONTENT --}}
            <div class="px-3 sm:px-5 lg:px-7 py-4 sm:py-5">

                {{-- TOOLBAR --}}
                <div class="flex flex-col
                            lg:flex-row
                            lg:items-center
                            lg:justify-between
                            gap-4
                            mb-4">

                    {{-- TAMBAH DATA --}}
                    <a href="{{ route('admin.categorie.create') }}"
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


                    {{-- SEARCH --}}
                    <div class="flex flex-col
                                sm:flex-row
                                sm:items-center
                                gap-2 sm:gap-3
                                w-full lg:w-auto">

                        <label for="search"
                            class="text-[15px] sm:text-[16px]
                                   text-gray-600
                                   whitespace-nowrap">

                            Pencarian :

                        </label>

                        <input type="text"
                            id="search"
                            name="search"
                            placeholder="Cari kategori..."
                            class="w-full sm:w-[180px]
                                   h-[36px]
                                   border border-gray-500
                                   rounded-sm
                                   px-3
                                   text-[15px] sm:text-[16px]
                                   text-gray-600
                                   placeholder-gray-400
                                   focus:outline-none
                                   focus:border-gray-700"
                            autocomplete="off">

                    </div>

                </div>


                {{-- TABLE --}}
                <div class="w-full overflow-x-auto">

                    <table class="w-full
                                  min-w-[600px]
                                  border-collapse
                                  text-[16px]">

                        <thead>

                            <tr class="bg-slate-600 text-white">

                                {{-- NO --}}
                                <th class="h-[43px]
                                           border border-gray-400
                                           px-3 sm:px-4
                                           text-center
                                           font-normal
                                           w-[70px]
                                           whitespace-nowrap">

                                    No

                                </th>


                                {{-- KATEGORI --}}
                                <th class="border border-gray-400
                                           px-3 sm:px-4
                                           text-center
                                           font-normal
                                           whitespace-nowrap">

                                    Kategori

                                </th>


                                {{-- AKSI --}}
                                <th class="border border-gray-400
                                           px-3 sm:px-4
                                           text-center
                                           font-normal
                                           w-[110px]
                                           whitespace-nowrap">

                                    Aksi

                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($categories as $key => $categorie)

                                <tr class="{{ $key % 2 == 0
                                    ? 'bg-gray-300'
                                    : 'bg-white' }}
                                    hover:bg-gray-200">


                                    {{-- NO --}}
                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3 sm:px-4
                                               text-center
                                               align-middle">

                                        {{ $categories->firstItem() + $key }}

                                    </td>


                                    {{-- KATEGORI --}}
                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3 sm:px-4
                                               text-center
                                               align-middle
                                               break-words">

                                        {{ $categorie->categorie_type }}

                                    </td>


                                    {{-- AKSI --}}
                                    <td class="border border-gray-300
                                               px-2 sm:px-3
                                               align-middle">

                                        <div class="flex flex-col
                                                    items-center
                                                    justify-center
                                                    gap-1">

                                            {{-- UBAH --}}
                                            <a href="{{ route('admin.categorie.edit', $categorie) }}"
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


                                            {{-- HAPUS --}}
                                            <form action="{{ route('admin.categorie.destroy', $categorie) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin hapus kategori ini?');">

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

                                    <td colspan="3"
                                        class="border border-gray-300
                                               text-center
                                               py-8
                                               px-3
                                               text-gray-500
                                               text-[16px]">

                                        Data kategori tidak ditemukan

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- FOOTER --}}
                <div class="flex flex-col
                            sm:flex-row
                            sm:items-center
                            sm:justify-between
                            gap-3
                            mt-4">

                    {{-- SHOWING --}}
                    <div class="text-[14px]
                                text-gray-600">

                        Showing
                        {{ $categories->firstItem() ?? 0 }}
                        to
                        {{ $categories->lastItem() ?? 0 }}
                        of
                        {{ $categories->total() ?? 0 }}
                        entries

                    </div>


                    {{-- PAGINATION --}}
                    @if ($categories->hasPages())

                        <div class="flex flex-wrap
                                    items-center
                                    gap-1
                                    text-[14px]">

                            {{-- PREVIOUS --}}
                            @if ($categories->onFirstPage())

                                <span class="px-2 py-1
                                             text-gray-400
                                             whitespace-nowrap">

                                    Previous

                                </span>

                            @else

                                <a href="{{ $categories->previousPageUrl() }}"
                                    class="px-2 py-1
                                           text-gray-600
                                           hover:text-gray-900
                                           whitespace-nowrap">

                                    Previous

                                </a>

                            @endif


                            {{-- NUMBER --}}
                            @foreach ($categories->getUrlRange(
                                max(1, $categories->currentPage() - 1),
                                min($categories->lastPage(), $categories->currentPage() + 1)
                            ) as $page => $url)

                                @if ($page == $categories->currentPage())

                                    <span class="min-w-[30px]
                                                 text-center
                                                 px-2 py-1
                                                 bg-gray-300
                                                 border border-gray-400
                                                 rounded-sm">

                                        {{ $page }}

                                    </span>

                                @else

                                    <a href="{{ $url }}"
                                        class="min-w-[30px]
                                               text-center
                                               px-2 py-1
                                               text-gray-600
                                               hover:bg-gray-200
                                               rounded-sm">

                                        {{ $page }}

                                    </a>

                                @endif

                            @endforeach


                            {{-- NEXT --}}
                            @if ($categories->hasMorePages())

                                <a href="{{ $categories->nextPageUrl() }}"
                                    class="px-2 py-1
                                           text-gray-600
                                           hover:text-gray-900
                                           whitespace-nowrap">

                                    Next

                                </a>

                            @else

                                <span class="px-2 py-1
                                             text-gray-400
                                             whitespace-nowrap">

                                    Next

                                </span>

                            @endif

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>