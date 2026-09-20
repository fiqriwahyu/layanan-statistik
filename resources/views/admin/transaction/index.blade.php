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
                           text-center">
                    Data Transaksi
                </h2>

            </div>

            {{-- Body --}}
            <div class="px-3 sm:px-5 lg:px-7 py-4 sm:py-5">

                {{-- Success Alert --}}
                @if (session('success'))
                    <div class="mb-4
                                px-4 py-3
                                bg-green-100
                                border border-green-300
                                text-green-700
                                text-[14px]
                                rounded-sm">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Filter & Search --}}
                <div class="flex flex-col lg:flex-row
                            lg:items-end
                            lg:justify-between
                            gap-4
                            mb-4">

                    {{-- Filter Tanggal --}}
                    <form method="GET"
                        action="{{ route('admin.transaction.index') }}"
                        class="flex flex-col sm:flex-row
                               sm:items-end
                               gap-2">

                        {{-- Pertahankan search saat filter tanggal --}}
                        @if (request('search'))
                            <input type="hidden"
                                name="search"
                                value="{{ request('search') }}">
                        @endif

                        <div>
                            <label for="from"
                                class="block mb-1
                                       text-[14px]
                                       text-gray-600">
                                Dari
                            </label>

                            <input type="date"
                                id="from"
                                name="from"
                                value="{{ request('from') }}"
                                class="w-full sm:w-[155px]
                                       h-[36px]
                                       border border-gray-500
                                       rounded-sm
                                       px-2
                                       text-[14px]
                                       text-gray-600
                                       focus:outline-none
                                       focus:border-gray-700">
                        </div>

                        <div>
                            <label for="to"
                                class="block mb-1
                                       text-[14px]
                                       text-gray-600">
                                Sampai
                            </label>

                            <input type="date"
                                id="to"
                                name="to"
                                value="{{ request('to') }}"
                                class="w-full sm:w-[155px]
                                       h-[36px]
                                       border border-gray-500
                                       rounded-sm
                                       px-2
                                       text-[14px]
                                       text-gray-600
                                       focus:outline-none
                                       focus:border-gray-700">
                        </div>

                        <button type="submit"
                            class="w-full sm:w-auto
                                   h-[36px]
                                   px-4
                                   bg-blue-600
                                   hover:bg-blue-700
                                   text-white
                                   text-[14px]
                                   rounded-sm">
                            Filter
                        </button>

                    </form>

                    {{-- Search --}}
                    <form method="GET"
                        action="{{ route('admin.transaction.index') }}"
                        class="flex flex-col sm:flex-row
                               sm:items-center
                               gap-2">

                        {{-- Pertahankan filter tanggal saat search --}}
                        @if (request('from'))
                            <input type="hidden"
                                name="from"
                                value="{{ request('from') }}">
                        @endif

                        @if (request('to'))
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
                            placeholder="Nama / No. HP..."
                            class="w-full sm:w-[200px]
                                   h-[36px]
                                   border border-gray-500
                                   rounded-sm
                                   px-3
                                   text-[15px] sm:text-[16px]
                                   text-gray-600
                                   focus:outline-none
                                   focus:border-gray-700">

                    </form>

                </div>

                {{-- Tombol Export --}}
                <div class="flex flex-wrap gap-2 mb-4">

                    <button type="button"
                        class="bg-red-500
                               hover:bg-red-600
                               text-white
                               text-[14px]
                               px-4 py-2
                               rounded-sm">
                        PDF Data
                    </button>

                    <button type="button"
                        class="bg-blue-600
                               hover:bg-blue-700
                               text-white
                               text-[14px]
                               px-4 py-2
                               rounded-sm">
                        Excel Data
                    </button>

                    <button type="button"
                        class="bg-green-500
                               hover:bg-green-600
                               text-white
                               text-[14px]
                               px-4 py-2
                               rounded-sm">
                        CSV Data
                    </button>

                </div>

                {{-- Table --}}
                <div class="w-full overflow-x-auto">

                    <table class="w-full min-w-[1100px]
                                  border-collapse
                                  text-[15px]">

                        <thead>

                            <tr class="bg-slate-600 text-white">

                                <th class="w-[60px]
                                           h-[45px]
                                           border border-gray-300
                                           px-3
                                           text-center
                                           align-middle">
                                    No
                                </th>

                                <th class="min-w-[180px]
                                           h-[45px]
                                           border border-gray-300
                                           px-3
                                           text-center
                                           align-middle">
                                    Nama
                                </th>

                                <th class="min-w-[170px]
                                           h-[45px]
                                           border border-gray-300
                                           px-3
                                           text-center
                                           align-middle">
                                    Status Transaksi
                                </th>

                                <th class="min-w-[180px]
                                           h-[45px]
                                           border border-gray-300
                                           px-3
                                           text-center
                                           align-middle">
                                    Penilaian
                                </th>

                                <th class="min-w-[250px]
                                           h-[45px]
                                           border border-gray-300
                                           px-3
                                           text-center
                                           align-middle">
                                    Saran
                                </th>

                                <th class="min-w-[170px]
                                           h-[45px]
                                           border border-gray-300
                                           px-3
                                           text-center
                                           align-middle">
                                    Tanggal
                                </th>

                                <th class="min-w-[160px]
                                           h-[45px]
                                           border border-gray-300
                                           px-3
                                           text-center
                                           align-middle">
                                    Status Penilaian
                                </th>

                                <th class="w-[130px]
                                           h-[45px]
                                           border border-gray-300
                                           px-3
                                           text-center
                                           align-middle">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse ($transactions as $key => $item)

                                <tr class="{{ $key % 2 == 0 ? 'bg-gray-300' : 'bg-white' }}
                                           hover:bg-gray-200">

                                    {{-- No --}}
                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3
                                               text-center
                                               align-middle">
                                        {{ $transactions->firstItem() + $key }}
                                    </td>

                                    {{-- Nama --}}
                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3
                                               text-center
                                               align-middle
                                               break-words">
                                        {{ $item->customer->nama_lengkap ?? '-' }}
                                    </td>

                                    {{-- Status Transaksi --}}
                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3
                                               text-center
                                               align-middle">

                                        <form action="{{ route('admin.transaction.updateStatus', $item->id) }}"
                                            method="POST">

                                            @csrf
                                            @method('PUT')

                                            <select name="status_transaksi"
                                                onchange="this.form.submit()"
                                                class="h-[34px]
                                                       px-2
                                                       rounded-sm
                                                       text-white
                                                       text-[13px]
                                                       border-0
                                                       focus:outline-none
                                                       {{ $item->status_transaksi == 'Sudah dilayani'
                                                           ? 'bg-green-500'
                                                           : 'bg-red-500' }}">

                                                <option value="Belum dilayani"
                                                    {{ $item->status_transaksi == 'Belum dilayani' ? 'selected' : '' }}>
                                                    Belum dilayani
                                                </option>

                                                <option value="Sudah dilayani"
                                                    {{ $item->status_transaksi == 'Sudah dilayani' ? 'selected' : '' }}>
                                                    Sudah dilayani
                                                </option>

                                            </select>

                                        </form>

                                    </td>

                                    {{-- Penilaian --}}
                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3
                                               text-center
                                               align-middle
                                               break-words">

                                        @if ($item->feedback)

                                            {{ $item->feedback->rating_pelayanan ?? '-' }},
                                            {{ $item->feedback->rating_fasilitas ?? '-' }},
                                            {{ $item->feedback->rating_kualitas ?? '-' }}

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- Saran --}}
                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3
                                               text-center
                                               align-middle
                                               break-words">

                                        {{ $item->feedback->saran_kritik ?? '-' }}

                                    </td>

                                    {{-- Tanggal --}}
                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-3
                                               text-center
                                               align-middle">

                                        {{ $item->created_at->format('Y-m-d H:i:s') }}

                                    </td>

                                    {{-- Status Penilaian --}}
<td class="h-[70px]
           border border-gray-300
           px-3
           text-center
           align-middle">

    @if ($item->feedback)

        <span class="inline-block
                     px-3 py-1
                     rounded-sm
                     text-white
                     text-[13px]
                     bg-green-500">
            Sudah menilai
        </span>

    @else

        <span class="inline-block
                     px-3 py-1
                     rounded-sm
                     text-white
                     text-[13px]
                     bg-red-500">
            Belum menilai
        </span>

    @endif

</td>

                                    {{-- Aksi --}}
                                    <td class="h-[70px]
                                               border border-gray-300
                                               px-2
                                               align-middle">

                                        <div class="flex flex-col
                                                    items-center
                                                    justify-center
                                                    gap-1">

                                            <a href="{{ route('admin.transaction.show', $item->id) }}"
                                                class="w-[52px]
                                                       text-center
                                                       bg-sky-500
                                                       hover:bg-sky-600
                                                       text-white
                                                       text-[14px]
                                                       py-1
                                                       rounded-sm">
                                                Lihat
                                            </a>

                                            {{-- <form action="{{ route('admin.transaction.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin hapus transaksi ini?');">

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

                                            </form> --}}

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8"
                                        class="border border-gray-300
                                               px-4 py-6
                                               text-center
                                               text-gray-500">

                                        Data transaksi tidak ditemukan

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Footer --}}
                <div class="flex flex-col sm:flex-row
                            sm:items-center
                            sm:justify-between
                            gap-3
                            mt-4">

                    <div class="text-[14px] text-gray-600">

                        @if ($transactions->total() > 0)

                            Menampilkan
                            {{ $transactions->firstItem() }}
                            sampai
                            {{ $transactions->lastItem() }}
                            dari
                            {{ $transactions->total() }}
                            data

                        @else

                            Menampilkan 0 data

                        @endif

                    </div>

                    {{-- Pagination --}}
                    @if ($transactions->hasPages())

                        <div class="flex items-center gap-1">

                            @if ($transactions->onFirstPage())

                                <span class="px-2 py-1
                                             text-[14px]
                                             text-gray-400
                                             border border-gray-300
                                             rounded-sm">
                                    Sebelumnya
                                </span>

                            @else

                                <a href="{{ $transactions->previousPageUrl() }}"
                                    class="px-2 py-1
                                           text-[14px]
                                           text-gray-600
                                           border border-gray-400
                                           rounded-sm
                                           hover:bg-gray-200">
                                    Sebelumnya
                                </a>

                            @endif

                            @for ($page = 1; $page <= $transactions->lastPage(); $page++)

                                @if ($page == $transactions->currentPage())

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

                                    <a href="{{ $transactions->url($page) }}"
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

                            @if ($transactions->hasMorePages())

                                <a href="{{ $transactions->nextPageUrl() }}"
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