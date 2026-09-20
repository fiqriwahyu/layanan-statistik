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
                    Data Pengunjung
                </h2>

            </div>


            {{-- CONTENT --}}
            <div class="px-3 sm:px-5 lg:px-7 py-4 sm:py-5">
@if (session('success'))
    <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-700 text-[14px] rounded-sm">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 px-4 py-3 bg-red-100 border border-red-300 text-red-700 text-[14px] rounded-sm">
        {{ session('error') }}
    </div>
@endif

                {{-- FILTER TANGGAL --}}
                <div class="mb-4">

                    <button type="button"
                        onclick="toggleFilter()"
                        class="w-full sm:w-auto
                               bg-blue-400
                               hover:bg-blue-500
                               text-white
                               text-[14px]
                               px-4 py-2
                               rounded-sm">

                        <span class="mr-1">▼</span>
                        Filter Tanggal

                    </button>

                </div>


                {{-- FORM FILTER --}}
                <form id="filterTanggal"
                    method="GET"
                    class="hidden
                           bg-white
                           border border-gray-300
                           p-3 sm:p-4
                           mb-4
                           rounded-sm">

                    <div class="flex flex-col
                                sm:flex-row
                                sm:flex-wrap
                                sm:items-end
                                gap-3 sm:gap-4">

                        {{-- DARI --}}
                        <div class="w-full sm:w-auto">

                            <label for="from"
                                class="block
                                       text-[14px]
                                       text-gray-600
                                       mb-1">
                                Dari
                            </label>

                            <input type="date"
                                id="from"
                                name="from"
                                value="{{ request('from') }}"
                                class="w-full sm:w-auto
                                       h-[36px]
                                       border border-gray-400
                                       px-3 py-1
                                       text-[14px]
                                       rounded-sm
                                       focus:outline-none">

                        </div>


                        {{-- SAMPAI --}}
                        <div class="w-full sm:w-auto">

                            <label for="to"
                                class="block
                                       text-[14px]
                                       text-gray-600
                                       mb-1">
                                Sampai
                            </label>

                            <input type="date"
                                id="to"
                                name="to"
                                value="{{ request('to') }}"
                                class="w-full sm:w-auto
                                       h-[36px]
                                       border border-gray-400
                                       px-3 py-1
                                       text-[14px]
                                       rounded-sm
                                       focus:outline-none">

                        </div>


                        @if(request('search'))

                            <input type="hidden"
                                name="search"
                                value="{{ request('search') }}">

                        @endif


                        {{-- BUTTON --}}
                        <div class="flex flex-col
                                    sm:flex-row
                                    gap-2
                                    w-full sm:w-auto">

                            <button type="submit"
                                class="w-full sm:w-auto
                                       bg-blue-500
                                       hover:bg-blue-600
                                       text-white
                                       text-[14px]
                                       px-4 py-2
                                       rounded-sm">
                                Filter
                            </button>

                            <a href="{{ route('admin.customer.index') }}"
                                class="w-full sm:w-auto
                                       text-center
                                       bg-gray-300
                                       hover:bg-gray-400
                                       text-gray-700
                                       text-[14px]
                                       px-4 py-2
                                       rounded-sm">
                                Reset
                            </a>

                        </div>

                    </div>

                </form>


                {{-- TOOLBAR --}}
                <div class="flex flex-col
                            lg:flex-row
                            lg:items-center
                            lg:justify-between
                            gap-4
                            mb-4">


                    {{-- EXPORT --}}
                    <div class="flex flex-col
                                sm:flex-row
                                flex-wrap
                                gap-2
                                w-full lg:w-auto">

                        <a href="{{ route('admin.customer.export.pdf', request()->all()) }}"
                            class="w-full sm:w-auto
                                   text-center
                                   bg-red-500
                                   hover:bg-red-600
                                   text-white
                                   text-[14px]
                                   px-4 py-2
                                   rounded-sm">
                            PDF Data
                        </a>

                        <a href="{{ route('admin.customer.export.excel',
                            array_merge(request()->all(), ['format' => 'xlsx'])) }}"
                            class="w-full sm:w-auto
                                   text-center
                                   bg-blue-600
                                   hover:bg-blue-700
                                   text-white
                                   text-[14px]
                                   px-4 py-2
                                   rounded-sm">
                            Excel Data
                        </a>

                        <a href="{{ route('admin.customer.export.excel',
                            array_merge(request()->all(), ['format' => 'csv'])) }}"
                            class="w-full sm:w-auto
                                   text-center
                                   bg-green-500
                                   hover:bg-green-600
                                   text-white
                                   text-[14px]
                                   px-4 py-2
                                   rounded-sm">
                            CSV Data
                        </a>

                    </div>


                    {{-- SEARCH --}}
                    <form method="GET"
                        action="{{ route('admin.customer.index') }}"
                        class="flex flex-col
                               sm:flex-row
                               sm:items-center
                               gap-2 sm:gap-3
                               w-full lg:w-auto">

                        @if(request('from'))

                            <input type="hidden"
                                name="from"
                                value="{{ request('from') }}">

                        @endif

                        @if(request('to'))

                            <input type="hidden"
                                name="to"
                                value="{{ request('to') }}">

                        @endif


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
                                  min-w-[900px]
                                  border-collapse
                                  text-[16px]">

                        <thead>

                            <tr class="bg-slate-600 text-white">

                                <th class="h-[43px]
                                           border border-gray-400
                                           px-3
                                           text-center
                                           font-normal
                                           w-[70px]
                                           whitespace-nowrap">
                                    No
                                    <span class="text-gray-300 text-sm">
                                        ↕
                                    </span>
                                </th>


                                <th class="border border-gray-400
                                           px-3
                                           text-left
                                           font-normal
                                           whitespace-nowrap">
                                    Nama
                                    <span class="float-right text-gray-300 text-sm">
                                        ↕
                                    </span>
                                </th>


                                <th class="border border-gray-400
                                           px-3
                                           text-left
                                           font-normal
                                           whitespace-nowrap">
                                    Telepon
                                    <span class="float-right text-gray-300 text-sm">
                                        ↕
                                    </span>
                                </th>


                                <th class="border border-gray-400
                                           px-3
                                           text-center
                                           font-normal
                                           whitespace-nowrap">
                                    Gender
                                    <span class="text-gray-300 text-sm">
                                        ↕
                                    </span>
                                </th>


                                <th class="border border-gray-400
                                           px-3
                                           text-left
                                           font-normal
                                           whitespace-nowrap">
                                    Pendidikan
                                    <span class="float-right text-gray-300 text-sm">
                                        ↕
                                    </span>
                                </th>


                                <th class="border border-gray-400
                                           px-3
                                           text-left
                                           font-normal
                                           whitespace-nowrap">
                                    Pekerjaan
                                    <span class="float-right text-gray-300 text-sm">
                                        ↕
                                    </span>
                                </th>


                                <th class="border border-gray-400
                                           px-3
                                           text-center
                                           font-normal
                                           whitespace-nowrap">
                                    Tanggal
                                    <span class="text-gray-300 text-sm">
                                        ↕
                                    </span>
                                </th>


                                <th class="border border-gray-400
                                           px-3
                                           text-center
                                           font-normal
                                           w-[100px]
                                           whitespace-nowrap">
                                    Aksi
                                    <span class="text-gray-300 text-sm">
                                        ↕
                                    </span>
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($customers as $key => $p)

                                <tr class="{{ $key % 2 == 0
                                    ? 'bg-gray-300'
                                    : 'bg-white' }}
                                    hover:bg-gray-200">


                                    {{-- NO --}}
                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3
                                               text-center
                                               align-middle">

                                        {{ $customers->firstItem() + $key }}

                                    </td>


                                    {{-- NAMA --}}
                                    <td class="border border-gray-300
                                               px-3
                                               align-middle">

                                        {{ $p->nama_lengkap }}

                                    </td>


                                    {{-- TELEPON --}}
                                    <td class="border border-gray-300
                                               px-3
                                               align-middle
                                               whitespace-nowrap">

                                        {{ $p->no_hp }}

                                    </td>


                                    {{-- GENDER --}}
                                    <td class="border border-gray-300
                                               px-3
                                               text-center
                                               align-middle
                                               whitespace-nowrap">

                                        {{ $p->jenis_kelamin }}

                                    </td>


                                    {{-- PENDIDIKAN --}}
                                    <td class="border border-gray-300
                                               px-3
                                               align-middle">

                                        {{ $p->education->education_type ?? '-' }}

                                    </td>


                                    {{-- PEKERJAAN --}}
                                    <td class="border border-gray-300
                                               px-3
                                               align-middle">

                                        {{ $p->work->work_type ?? '-' }}

                                    </td>


                                    {{-- TANGGAL --}}
                                    <td class="border border-gray-300
                                               px-3
                                               text-center
                                               align-middle
                                               whitespace-nowrap">

                                        {{ $p->created_at->format('Y-m-d') }}

                                        <br>

                                        {{ $p->created_at->format('H:i:s') }}

                                    </td>


                                    {{-- AKSI --}}
                                    <td class="border border-gray-300
                                               px-2
                                               align-middle">

                                        <div class="flex flex-col
                                                    items-center
                                                    justify-center
                                                    gap-1">

                                            {{-- <form action="{{ route('admin.customer.destroy', $p->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin hapus data ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="w-[52px]
                                                           bg-red-400
                                                           hover:bg-red-500
                                                           text-white
                                                           text-[14px]
                                                           px-2 py-1
                                                           rounded-sm">
                                                    Hapus
                                                </button>

                                            </form> --}}


                                            <a href="{{ route('admin.customer.show', $p->id) }}"
                                                class="w-[52px]
                                                       text-center
                                                       bg-sky-400
                                                       hover:bg-sky-500
                                                       text-white
                                                       text-[14px]
                                                       px-2 py-1
                                                       rounded-sm">
                                                Lihat
                                            </a>

                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="8"
                                        class="border border-gray-300
                                               text-center
                                               py-8
                                               text-gray-500
                                               text-[16px]">

                                        Data tidak ditemukan

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
                    <div class="text-[14px] text-gray-600">

                        @if($customers->total() > 0)

                            Showing
                            {{ $customers->firstItem() }}
                            to
                            {{ $customers->lastItem() }}
                            of
                            {{ $customers->total() }}
                            entries

                        @else

                            Showing 0 to 0 of 0 entries

                        @endif

                    </div>


                    {{-- PAGINATION --}}
                    @if($customers->total() > 15)

                        <div class="flex items-center
                                    gap-2
                                    text-[14px]">

                            @if($customers->onFirstPage())

                                <span class="text-gray-400">
                                    Previous
                                </span>

                            @else

                                <a href="{{ $customers->appends(request()->query())->previousPageUrl() }}"
                                    class="text-gray-600
                                           hover:text-blue-500">
                                    Previous
                                </a>

                            @endif


                            @if($customers->hasMorePages())

                                <a href="{{ $customers->appends(request()->query())->nextPageUrl() }}"
                                    class="text-gray-600
                                           hover:text-blue-500">
                                    Next
                                </a>

                            @else

                                <span class="text-gray-400">
                                    Next
                                </span>

                            @endif

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    <script>

        function toggleFilter() {
            document
                .getElementById('filterTanggal')
                .classList
                .toggle('hidden');
        }

    </script>

</x-app-layout>