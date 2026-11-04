<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Detail Layanan</h2>
    </x-slot>

    <div class="bg-white p-6 rounded shadow space-y-4">

        <div>
            <strong>Nama Pengunjung:</strong>
            {{ $transaction->customer?->nama_lengkap ?? '-' }}
        </div>

        <div>
            <strong>Media Pelayanan:</strong>
            {{ $transaction->media?->media_type ?? '-' }}
        </div>

        <div>
            <strong>Jenis Pelayanan:</strong>
            {{ $transaction->service?->service_type ?? '-' }}
        </div>

        <div>
            <strong>Tujuan:</strong>
            {{ $transaction->purpose?->purpose_type ?? '-' }}
        </div>

        <div>
            <strong>Kebutuhan Data:</strong>
            {{ $transaction->subCategorie?->sub_categorie_type ?? '-' }}
            ({{ $transaction->subCategorie?->categorie?->categorie_type ?? '-' }})
        </div>

        <div>
            <strong>Data Diminta:</strong>
            <p class="mt-1 bg-gray-100 p-3 rounded">
                {{ $transaction->data_diminta ?? '-' }}
            </p>
        </div>

        <div>
            <strong>Status Transaksi:</strong>
            <span
                class="px-2 py-1 rounded border
                {{ $transaction->status_transaksi === 'Sudah dilayani' ? 'border-green-300 bg-green-100 text-green-800' : ($transaction->status_transaksi === 'Sedang dilayani' ? 'border-amber-300 bg-amber-100 text-amber-900' : 'border-gray-300 bg-gray-100 text-gray-800') }}">
                {{ $transaction->status_transaksi }}
            </span>
            <p class="mt-2 text-sm text-gray-600">Terakhir diperbarui: {{ $transaction->status_updated_at?->format('d-m-Y H:i') ?? '-' }}</p>
        </div>

        <hr>

        <div>
            <strong>Penilaian:</strong><br>
            @if ($transaction->feedback)
                {{ $transaction->feedback->rating_pelayanan }},
                {{ $transaction->feedback->rating_fasilitas }},
                {{ $transaction->feedback->rating_kualitas }}

                <p class="mt-2">
                    <strong>Saran:</strong>
                    {{ $transaction->feedback->saran_kritik ?? '-' }}
                </p>
            @else
                -
            @endif
        </div>

        <div class="pt-4">
            <a href="{{ route('admin.transaction.index') }}" class="px-4 py-2 bg-gray-300 rounded">
                Kembali
            </a>
        </div>

    </div>
</x-app-layout>
