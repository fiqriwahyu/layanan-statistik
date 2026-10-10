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

                    Data pekerjaan

                </h2>

            </div>


            {{-- CONTENT --}}
            <div class="px-3 sm:px-5 lg:px-7 py-4 sm:py-5">

                {{-- SUCCESS --}}
                @if (session('success'))

                    <div class="mb-4
                                px-3 sm:px-4
                                py-2
                                bg-green-100
                                border border-green-300
                                text-green-700
                                text-[14px]
                                rounded-sm
                                break-words">

                        {{ session('success') }}

                    </div>

                @endif


                {{-- TOOLBAR --}}
                <div class="flex flex-col
                            lg:flex-row
                            lg:items-center
                            lg:justify-between
                            gap-4
                            mb-4">


                    {{-- TAMBAH DATA --}}
                    <a href="{{ route('admin.work.create') }}"
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
                    <form method="GET"
                        action="{{ route('admin.work.index') }}"
                        class="flex flex-col
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
                            value="{{ request('search') }}"
                            class="w-full sm:w-[180px]
                                   h-[36px]
                                   border border-gray-500
                                   rounded-sm
                                   px-3
                                   text-[15px] sm:text-[16px]
                                   text-gray-600
                                   focus:outline-none
                                   focus:border-gray-700"
                            autocomplete="off">

                    </form>

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


                                {{-- WORK --}}
                                <th class="border border-gray-400
                                           px-3 sm:px-4
                                           text-center
                                           font-normal
                                           whitespace-nowrap">

                                    Nama Pekerjaan

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

                            @forelse($works as $key => $item)

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

                                        {{ $works->firstItem() + $key }}

                                    </td>


                                    {{-- WORK --}}
                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3 sm:px-4
                                               text-center
                                               align-middle
                                               break-words">

                                        {{ $item->work_type }}

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
                                            <a href="{{ route('admin.work.edit', $item->id) }}"
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
                                            <form action="{{ route('admin.work.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin hapus pekerjaan ini?')">

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

                                        Data pekerjaan tidak ditemukan

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
                        {{ $works->firstItem() ?? 0 }}
                        to
                        {{ $works->lastItem() ?? 0 }}
                        of
                        {{ $works->total() }}
                        entries

                    </div>


                    {{-- PAGINATION --}}
                    @if ($works->hasPages())

                        <div class="flex flex-wrap
                                    items-center
                                    gap-1
                                    text-[14px]">


                            {{-- PREVIOUS --}}
                            @if ($works->onFirstPage())

                                <span class="px-2 py-1
                                             text-gray-400
                                             whitespace-nowrap">

                                    Previous

                                </span>

                            @else

                                <a href="{{ $works->previousPageUrl() }}"
                                    class="px-2 py-1
                                           text-gray-600
                                           hover:text-gray-900
                                           whitespace-nowrap">

                                    Previous

                                </a>

                            @endif


                            {{-- NUMBER --}}
                            @foreach ($works->getUrlRange(
                                max(1, $works->currentPage() - 1),
                                min($works->lastPage(), $works->currentPage() + 1)
                            ) as $page => $url)

                                @if ($page == $works->currentPage())

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
                            @if ($works->hasMorePages())

                                <a href="{{ $works->nextPageUrl() }}"
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