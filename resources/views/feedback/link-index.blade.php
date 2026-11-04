<x-guest-layout>
    <main class="w-full max-w-2xl space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-lg sm:p-8">
        <header class="space-y-3 border-b border-[#385683]/15 pb-5">
            <p class="text-sm font-semibold uppercase tracking-wide text-[#385683]">BPS Kota Jambi · Penilaian Layanan</p>
            <h1 class="text-3xl font-bold leading-tight text-[#385683] sm:text-4xl">Berikan Penilaian Layanan</h1>
            <p class="max-w-xl text-base leading-relaxed text-gray-600">Masukkan token atau tempel tautan rahasia yang ditampilkan setelah pengajuan layanan berhasil dikirim.</p>
        </header>

        <form method="POST" action="{{ route('feedback.resolve') }}" class="space-y-4">
            @csrf

            <div>
                <label for="feedback_link" class="mb-2 block text-sm font-semibold text-gray-800">
                    Token atau tautan feedback
                </label>
                <input
                    id="feedback_link"
                    name="feedback_link"
                    type="text"
                    value="{{ old('feedback_link') }}"
                    maxlength="2048"
                    required
                    spellcheck="false"
                    autocomplete="off"
                    aria-describedby="feedback-link-help feedback-link-error"
                    class="w-full rounded-md border border-gray-300 px-3 py-3 text-gray-900 shadow-sm focus:border-[#385683] focus:outline-none focus:ring-2 focus:ring-[#385683]/30"
                    placeholder="Tempel tautan atau masukkan token 32 karakter"
                >
                <p id="feedback-link-help" class="mt-2 text-sm leading-relaxed text-gray-600">
                    Buka halaman konfirmasi pengajuan setelah mengirim formulir. Jika tombol WhatsApp tersedia, pilih <span class="font-semibold text-gray-800">Kirim Informasi ke WhatsApp</span> untuk membuka draf pesan yang berisi tautan feedback. Pesan tidak dikirim otomatis. Jika Anda menerima tautan melalui WhatsApp, salin tautan dari pesan lalu tempel di sini. Anda juga dapat memasukkan token baru 32 karakter heksadesimal huruf kecil. Token lama 64 karakter tidak lagi dapat digunakan.
                </p>
                @error('feedback_link')
                    <p id="feedback-link-error" class="mt-2 text-sm font-medium text-red-700" role="alert">{{ $message }}</p>
                @else
                    <span id="feedback-link-error" class="sr-only"></span>
                @enderror
            </div>

            <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center rounded-lg bg-[#385683] px-6 py-3 font-semibold text-white transition hover:bg-[#2f496f] focus:outline-none focus:ring-2 focus:ring-[#385683] focus:ring-offset-2 sm:w-auto">
                Lanjutkan ke formulir penilaian
            </button>
        </form>

        <p class="rounded-lg border border-[#385683]/15 bg-[#385683]/5 p-4 text-sm leading-relaxed text-gray-700">
            Penilaian dapat dikirim saat layanan belum dilayani, sedang dilayani, atau sudah dilayani. Setiap tautan hanya dapat digunakan satu kali. Tautan membuktikan penguasaan tautan rahasia, bukan identitas atau kepemilikan nomor HP. Pencarian feedback berdasarkan nomor HP tidak tersedia.
        </p>
    </main>
</x-guest-layout>
