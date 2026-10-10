<x-app-layout>

    <div class="w-full px-3 sm:px-4 lg:px-5 py-4 sm:py-5">

        <div class="w-full border border-gray-400 bg-white rounded-sm">

            <div class="min-h-[64px] flex items-center justify-center px-4 sm:px-6 py-3 bg-gray-100 border-b border-gray-300">
                <h2 class="text-[20px] sm:text-[24px] leading-6 font-normal text-gray-600 text-center">
                    Data Transaksi
                </h2>
            </div>

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

                @if ($errors->any())
                    <div class="mb-4 px-4 py-3 bg-red-100 border border-red-300 text-red-700 text-[14px] rounded-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- TOOLBAR --}}
<div class="mb-4">

    {{-- FILTER --}}
    <div class="mb-3">
        <button type="button"
            onclick="toggleFilter()"
            class="inline-flex items-center gap-2 bg-blue-400 hover:bg-blue-500 text-white text-[14px] px-5 py-2.5 rounded-sm transition">
            <span class="text-[13px]">▼</span>
            Filter Tanggal
        </button>
    </div>

    {{-- DOWNLOAD --}}
    <div class="flex flex-wrap gap-2">

        <a href="{{ route('admin.transaction.export.pdf', request()->all()) }}"
            class="inline-flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white text-[14px] px-5 py-2.5 rounded-sm transition">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
            </svg>
            PDF Data
        </a>

        <a href="{{ route('admin.transaction.export.excel', array_merge(request()->all(), ['format' => 'xlsx'])) }}"
            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-[14px] px-5 py-2.5 rounded-sm transition">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
            </svg>
            Excel Data
        </a>

        <a href="{{ route('admin.transaction.export.excel', array_merge(request()->all(), ['format' => 'csv'])) }}"
            class="inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white text-[14px] px-5 py-2.5 rounded-sm transition">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
            </svg>
            CSV Data
        </a>

    </div>

</div>

{{-- FILTER TANGGAL --}}
<form id="filterTanggal"
    method="GET"
    action="{{ route('admin.transaction.index') }}"
    class="hidden bg-white border border-gray-300 rounded-sm p-3 sm:p-4 mb-4">

    @if (request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif

    <div class="flex flex-col sm:flex-row sm:items-end gap-3 sm:gap-4">

        <div class="w-full sm:w-auto">
            <label for="from"
                class="block text-[14px] text-gray-600 mb-1">
                Dari
            </label>

            <input type="date"
                id="from"
                name="from"
                value="{{ request('from') }}"
                class="w-full sm:w-[170px] h-[36px] border border-gray-400 px-3 py-1 text-[14px] rounded-sm focus:outline-none focus:border-blue-500">
        </div>

        <div class="w-full sm:w-auto">
            <label for="to"
                class="block text-[14px] text-gray-600 mb-1">
                Sampai
            </label>

            <input type="date"
                id="to"
                name="to"
                value="{{ request('to') }}"
                class="w-full sm:w-[170px] h-[36px] border border-gray-400 px-3 py-1 text-[14px] rounded-sm focus:outline-none focus:border-blue-500">
        </div>

        <div class="flex gap-2 w-full sm:w-auto">

            <button type="submit"
                class="flex-1 sm:flex-none bg-blue-500 hover:bg-blue-600 text-white text-[14px] px-4 py-2 rounded-sm">
                Filter
            </button>

            <a href="{{ route('admin.transaction.index') }}"
                class="flex-1 sm:flex-none text-center bg-gray-300 hover:bg-gray-400 text-gray-700 text-[14px] px-4 py-2 rounded-sm">
                Reset
            </a>

        </div>

    </div>
</form>

                {{-- TABEL --}}
                <div class="w-full overflow-x-auto">

                    <table class="w-full min-w-[1250px] border-collapse text-[15px]">

                        <thead>
                            <tr class="bg-slate-600 text-white">

                                <th class="w-[60px] h-[45px] border border-gray-300 px-3 text-center align-middle">
                                    No
                                </th>

                                <th class="min-w-[180px] h-[45px] border border-gray-300 px-3 text-center align-middle">
                                    Nama
                                </th>

                                <th class="min-w-[170px] h-[45px] border border-gray-300 px-3 text-center align-middle">
                                    Status Transaksi
                                </th>

                                <th class="min-w-[180px] h-[45px] border border-gray-300 px-3 text-center align-middle">
                                    Penilaian
                                </th>

                                <th class="min-w-[250px] h-[45px] border border-gray-300 px-3 text-center align-middle">
                                    Saran
                                </th>

                                <th class="min-w-[170px] h-[45px] border border-gray-300 px-3 text-center align-middle">
                                    Tanggal
                                </th>

                                <th class="min-w-[160px] h-[45px] border border-gray-300 px-3 text-center align-middle">
                                    Status Penilaian
                                </th>

                                <th class="w-[130px] h-[45px] border border-gray-300 px-3 text-center align-middle">
                                    Aksi
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($transactions as $key => $item)

                                <tr class="{{ $key % 2 == 0 ? 'bg-gray-300' : 'bg-white' }} hover:bg-gray-200">

                                    <td class="h-[70px] border border-gray-300 px-3 text-center align-middle">
                                        {{ $transactions->firstItem() + $key }}
                                    </td>

                                    <td class="h-[70px] border border-gray-300 px-3 text-center align-middle break-words">
                                        {{ $item->customer->nama_lengkap ?? '-' }}
                                    </td>

                                    <td class="h-[70px] border border-gray-300 px-3 text-center align-middle">

                                        @if ($item->status_transaksi === 'Sudah dilayani')

                                            <button type="button"
                                                onclick="openProofModal(this)"
                                                data-date="{{ $item->dilayani_pada ? $item->dilayani_pada->format('d-m-Y H:i:s') : '-' }}"
                                                data-admin="{{ $item->dilayaniOleh->name ?? '-' }}"
                                                data-description="{{ $item->keterangan_pelayanan ?? '-' }}"
                                                data-file="{{ $item->bukti_pelayanan ? asset('storage/' . $item->bukti_pelayanan) : '#' }}"
                                                class="h-[34px] px-3 rounded-sm text-white text-[13px] bg-green-500 hover:bg-green-600">
                                                Sudah dilayani
                                            </button>

                                        @else

                                            <button type="button"
                                                onclick="openServiceModal({{ $item->id }})"
                                                class="h-[34px] px-3 rounded-sm text-white text-[13px] bg-red-500 hover:bg-red-600">
                                                Belum dilayani
                                            </button>

                                        @endif

                                    </td>

                                    <td class="h-[70px] border border-gray-300 px-3 text-center align-middle break-words">

                                        @if ($item->feedback)

                                            {{ $item->feedback->rating_pelayanan ?? '-' }},
                                            {{ $item->feedback->rating_fasilitas ?? '-' }},
                                            {{ $item->feedback->rating_kualitas ?? '-' }}

                                        @else

                                            -

                                        @endif

                                    </td>

                                    <td class="h-[70px] border border-gray-300 px-3 text-center align-middle break-words">
                                        {{ $item->feedback->saran_kritik ?? '-' }}
                                    </td>

                                    <td class="h-[70px] border border-gray-300 px-3 text-center align-middle">
                                        {{ $item->created_at->format('Y-m-d') }}
                                    </td>

                                    <td class="h-[70px] border border-gray-300 px-3 text-center align-middle">

                                        @if ($item->feedback)

                                            <span class="inline-block px-3 py-1 rounded-sm text-white text-[13px] bg-green-500">
                                                Sudah menilai
                                            </span>

                                        @else

                                            <span class="inline-block px-3 py-1 rounded-sm text-white text-[13px] bg-red-500">
                                                Belum menilai
                                            </span>

                                        @endif

                                    </td>

                                    <td class="h-[70px] border border-gray-300 px-2 align-middle">

                                        <div class="flex flex-col items-center justify-center gap-1">

                                            <a href="{{ route('admin.transaction.show', $item->id) }}"
                                                class="w-[52px] text-center bg-sky-500 hover:bg-sky-600 text-white text-[14px] py-1 rounded-sm">
                                                Lihat
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8"
                                        class="border border-gray-300 px-4 py-6 text-center text-gray-500">
                                        Data transaksi tidak ditemukan
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- PAGINATION --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4">

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

                    @if ($transactions->hasPages())

                        <div class="flex items-center gap-1">

                            @if ($transactions->onFirstPage())

                                <span class="px-2 py-1 text-[14px] text-gray-400 border border-gray-300 rounded-sm">
                                    Sebelumnya
                                </span>

                            @else

                                <a href="{{ $transactions->previousPageUrl() }}"
                                    class="px-2 py-1 text-[14px] text-gray-600 border border-gray-400 rounded-sm hover:bg-gray-200">
                                    Sebelumnya
                                </a>

                            @endif

                            @for ($page = 1; $page <= $transactions->lastPage(); $page++)

                                @if ($page == $transactions->currentPage())

                                    <span class="min-w-[30px] text-center px-2 py-1 text-[14px] bg-gray-300 border border-gray-400 rounded-sm">
                                        {{ $page }}
                                    </span>

                                @else

                                    <a href="{{ $transactions->url($page) }}"
                                        class="min-w-[30px] text-center px-2 py-1 text-[14px] text-gray-600 border border-gray-400 rounded-sm hover:bg-gray-200">
                                        {{ $page }}
                                    </a>

                                @endif

                            @endfor

                            @if ($transactions->hasMorePages())

                                <a href="{{ $transactions->nextPageUrl() }}"
                                    class="px-2 py-1 text-[14px] text-gray-600 border border-gray-400 rounded-sm hover:bg-gray-200">
                                    Berikutnya
                                </a>

                            @else

                                <span class="px-2 py-1 text-[14px] text-gray-400 border border-gray-300 rounded-sm">
                                    Berikutnya
                                </span>

                            @endif

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- MODAL SELESAIKAN LAYANAN --}}
    <div id="serviceModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">

        <div class="w-full max-w-[650px] bg-white rounded-sm shadow-lg">

            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-300 bg-gray-100">

                <h3 class="text-[18px] text-gray-700 font-medium">
                    Selesaikan Layanan
                </h3>

                <button type="button"
                    onclick="closeServiceModal()"
                    class="text-gray-500 hover:text-gray-700 text-[24px] leading-none">
                    &times;
                </button>

            </div>

            <form id="serviceForm" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="px-5 py-5">

                    <div class="grid grid-cols-[170px_1fr] gap-x-4 gap-y-4 items-center">

                        <label class="text-[14px] text-gray-600">
                            Tanggal Dilayani
                        </label>

                        <div class="text-[14px] text-gray-500">
                            Dicatat otomatis saat layanan diselesaikan
                        </div>

                        <label class="text-[14px] text-gray-600">
                            Bukti Pelayanan <span class="text-red-500">*</span>
                        </label>

                        <div>

                            <input type="file"
                                name="bukti_pelayanan"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required
                                class="w-full border border-gray-400 rounded-sm px-3 py-2 text-[14px] text-gray-600">

                            <p class="mt-1 text-[12px] text-gray-500">
                                PDF, JPG, JPEG, PNG. Maksimal 5 MB.
                            </p>

                        </div>

                        <label class="text-[14px] text-gray-600 self-start pt-2">
                            Keterangan
                        </label>

                        <textarea name="keterangan_pelayanan"
                            rows="4"
                            placeholder="Masukkan keterangan pelayanan..."
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-[14px] text-gray-600 resize-none focus:outline-none focus:border-gray-700"></textarea>

                    </div>

                </div>

                <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-300">

                    <button type="button"
                        onclick="closeServiceModal()"
                        class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white text-[14px] rounded-sm">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-[14px] rounded-sm">
                        Simpan & Selesaikan
                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- MODAL LIHAT BUKTI --}}
    <div id="proofModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">

        <div class="w-full max-w-[650px] bg-white rounded-sm shadow-lg">

            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-300 bg-gray-100">

                <h3 class="text-[18px] text-gray-700 font-medium">
                    Bukti Penyelesaian Layanan
                </h3>

                <button type="button"
                    onclick="closeProofModal()"
                    class="text-gray-500 hover:text-gray-700 text-[24px] leading-none">
                    &times;
                </button>

            </div>

            <div class="px-5 py-5">

                <div class="grid grid-cols-[170px_1fr] gap-x-4 gap-y-4 text-[14px]">

                    <div class="text-gray-600">
                        Status
                    </div>

                    <div>
                        <span class="inline-block px-3 py-1 rounded-sm bg-green-500 text-white text-[13px]">
                            Sudah dilayani
                        </span>
                    </div>

                    <div class="text-gray-600">
                        Tanggal Dilayani
                    </div>

                    <div id="proofDate" class="text-gray-700">
                        -
                    </div>

                    <div class="text-gray-600">
                        Dilayani Oleh
                    </div>

                    <div id="proofAdmin" class="text-gray-700">
                        -
                    </div>

                    <div class="text-gray-600">
                        Keterangan
                    </div>

                    <div id="proofDescription" class="text-gray-700 whitespace-pre-wrap">
                        -
                    </div>

                    <div class="text-gray-600">
                        Bukti Pelayanan
                    </div>

                    <div>

                        <a id="proofFile"
                            href="#"
                            target="_blank"
                            class="inline-block px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-[14px] rounded-sm">
                            sudah dilayani
                        </a>

                    </div>

                </div>

            </div>

            <div class="flex justify-end px-5 py-4 border-t border-gray-300">

                <button type="button"
                    onclick="closeProofModal()"
                    class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white text-[14px] rounded-sm">
                    Tutup
                </button>

            </div>

        </div>

    </div>

    <script>
        function toggleFilter() {
            document.getElementById('filterTanggal').classList.toggle('hidden');
        }

        function openServiceModal(id) {
            const modal = document.getElementById('serviceModal');
            const form = document.getElementById('serviceForm');

            form.action = "{{ url('/admin/transaction') }}/" + id + "/status";

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeServiceModal() {
            const modal = document.getElementById('serviceModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.getElementById('serviceForm').reset();
        }

        function openProofModal(button) {
            document.getElementById('proofDate').textContent = button.dataset.date;
            document.getElementById('proofAdmin').textContent = button.dataset.admin;
            document.getElementById('proofDescription').textContent = button.dataset.description;

            const file = document.getElementById('proofFile');

            if (button.dataset.file && button.dataset.file !== '#') {
                file.href = button.dataset.file;
                file.classList.remove('hidden');
            } else {
                file.href = '#';
                file.classList.add('hidden');
            }

            const modal = document.getElementById('proofModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeProofModal() {
            const modal = document.getElementById('proofModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeProofModal();
                closeServiceModal();
            }
        });
    </script>

</x-app-layout>