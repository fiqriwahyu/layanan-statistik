<x-guest-layout>
    <main class="w-full space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-lg sm:p-8">
        <header class="space-y-2 border-b border-[#385683]/15 pb-5">
            <p class="text-sm font-semibold uppercase tracking-wide text-[#385683]">BPS Kota Jambi</p>
            <h1 class="text-2xl font-bold text-[#385683] sm:text-3xl">Cek Status Layanan</h1>
            <p class="text-sm leading-relaxed text-gray-600">Masukkan kode pelacakan yang ditampilkan pada konfirmasi pengajuan untuk melihat perkembangan layanan.</p>
        </header>

        <form method="POST" action="{{ route('service-status.lookup') }}" class="space-y-4">
            @csrf
            <div>
                <label for="tracking_code" class="mb-2 block text-sm font-semibold text-gray-800">Kode pelacakan</label>
                <input id="tracking_code" name="tracking_code" type="text" value="{{ old('tracking_code') }}" maxlength="32" minlength="32" pattern="[a-f0-9]{32}" autocomplete="off" spellcheck="false" required aria-describedby="tracking-help tracking-error" class="w-full rounded-lg border border-gray-300 px-3 py-3 font-mono text-gray-900 shadow-sm focus:border-[#385683] focus:outline-none focus:ring-2 focus:ring-[#385683]/30" placeholder="32 karakter heksadesimal">
                <p id="tracking-help" class="mt-2 text-sm text-gray-600">Kode tidak sama dengan token feedback. Kode tidak akan ditampilkan pada alamat halaman.</p>
                @error('tracking_code')
                    <p id="tracking-error" class="mt-2 text-sm font-medium text-red-700" role="alert">{{ $message }}</p>
                @else
                    <span id="tracking-error" class="sr-only"></span>
                @enderror
            </div>
            <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center rounded-lg bg-[#385683] px-6 py-3 font-semibold text-white transition hover:bg-[#2f496f] focus:outline-none focus:ring-2 focus:ring-[#385683] focus:ring-offset-2 sm:w-auto">Cek Status</button>
        </form>

        @if (session('tracking_result'))
            @php($result = session('tracking_result'))
            @php($badge = match ($result['status']) {
                'Sudah dilayani' => 'bg-green-100 text-green-800 border-green-300',
                'Sedang dilayani' => 'bg-amber-100 text-amber-900 border-amber-300',
                default => 'bg-gray-100 text-gray-800 border-gray-300',
            })
            <section class="rounded-lg border border-[#385683]/20 bg-[#385683]/5 p-5" aria-live="polite" aria-labelledby="tracking-result-title">
                <h2 id="tracking-result-title" class="mb-4 text-lg font-semibold text-[#385683]">Informasi Status Layanan</h2>
                <dl class="grid gap-3 text-sm sm:grid-cols-[10rem_1fr]">
                    <dt class="font-medium text-gray-600">Kode pelacakan</dt><dd class="font-mono text-gray-900">{{ $result['code'] }}</dd>
                    <dt class="font-medium text-gray-600">Jenis layanan</dt><dd class="text-gray-900">{{ $result['service'] ?: '-' }}</dd>
                    <dt class="font-medium text-gray-600">Tanggal pengajuan</dt><dd class="text-gray-900">{{ $result['submitted_at'] ?: '-' }}</dd>
                    <dt class="font-medium text-gray-600">Status layanan</dt><dd><span class="inline-flex rounded-full border px-3 py-1 font-semibold {{ $badge }}">{{ $result['status'] }}</span></dd>
                    <dt class="font-medium text-gray-600">Pembaruan status</dt><dd class="text-gray-900">{{ $result['updated_at'] ?: 'Belum tersedia' }}</dd>
                </dl>
            </section>
        @endif
    </main>
</x-guest-layout>
