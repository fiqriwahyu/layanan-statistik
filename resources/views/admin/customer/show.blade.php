<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Detail Customer</h2>
    </x-slot>

    <div class="bg-white rounded shadow p-6 space-y-6">

        {{-- DATA DIRI --}}
        <div>
            <h3 class="font-semibold text-lg mb-2 border-b pb-1">Data Diri</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Nama Lengkap</label>
                    <div class="font-medium">{{ $customer->nama_lengkap }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">No HP</label>
                    <div class="font-medium">{{ $customer->no_hp }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Jenis Kelamin</label>
                    <div class="font-medium">{{ $customer->jenis_kelamin }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Usia</label>
                    <div class="font-medium">{{ $customer->usia }} Tahun</div>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm text-gray-500">Alamat</label>
                    <div class="font-medium">{{ $customer->alamat }}</div>
                </div>
            </div>
        </div>

        {{-- PEKERJAAN --}}
        <div>
            <h3 class="font-semibold text-lg mb-2 border-b pb-1">Pekerjaan & Pendidikan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Instansi</label>
                    <div class="font-medium">{{ $customer->nama_instansi ?? '-' }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">NIP / NIM</label>
                    <div class="font-medium">{{ $customer->nip_nim ?? '-' }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Pekerjaan</label>
                    <div class="font-medium">{{ $customer->work->work_type ?? '-' }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Pendidikan</label>
                    <div class="font-medium">{{ $customer->education->education_type ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="font-semibold text-lg mb-2 border-b pb-1">Layanan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Media Pelayanan</label>
                    <div class="font-medium">{{ $customer->transactions->first()->media?->media_type ?? '-' }}</div>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Jenis Pelayanan</label>
                    <div class="font-medium">{{ $customer->transactions->first()->service?->service_type ?? '-' }}</div>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm text-gray-500">Tujuan</label>
                    <div class="font-medium">{{ $customer->transactions->first()->purpose?->purpose_type ?? '-' }}</div>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm text-gray-500">Kebutuhan Data</label>
                    <div class="font-medium">{{ $customer->transactions->first()->subCategorie?->sub_categorie_type ?? '-' }}</div>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm text-gray-500">Data yang Diminta</label>
                    <div class="font-medium whitespace-pre-line">
                        {{ $customer->transactions->first()->data_diminta ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="flex justify-between items-center border-t pt-4">
            <div class="text-sm text-gray-500">
                Tanggal Kunjungan:
                <span class="font-medium">
                    {{ $customer->created_at->format('d-m-Y H:i') }}
                </span>
            </div>

            <a href="{{ route('admin.customer.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Kembali
            </a>
        </div>

    </div>
</x-app-layout>
