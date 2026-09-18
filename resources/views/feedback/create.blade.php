<x-guest-layout>
    <div class="max-w-xl mx-auto mt-12 bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold text-[#385683] mb-4 text-center">
            Form Penilaian Layanan
        </h1>

        <form action="{{ route('feedback.store') }}" method="POST">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

            {{-- ================== PERTANYAAN 1 ================== --}}
            <div x-data="{ rating: 1 }" class="mb-5">
                <p class="mb-2 font-medium">Pelayanan petugas</p>

                <div class="flex items-center gap-2">
                    <div class="flex gap-1">
                        <template x-for="i in 5">
                            <svg @click="rating = i" :class="i <= rating ? 'text-yellow-400' : 'text-gray-300'"
                                class="w-8 h-8 cursor-pointer" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974
                                         4.178.018c.969.004 1.371 1.24.588 1.81l-3.385
                                         2.46 1.24 3.987c.285.918-.755
                                         1.688-1.54 1.118L10 13.348l-3.318
                                         2.946c-.784.57-1.824-.2-1.54-1.118
                                         l1.24-3.987-3.385-2.46c-.783-.57-.38-1.806.588-1.81
                                         l4.178-.018 1.286-3.974z" />
                            </svg>
                        </template>
                    </div>

                    <span class="text-gray-500" x-text="rating + '/5'"></span>
                </div>

                <input type="hidden" name="rating_pelayanan" :value="rating">
            </div>

            {{-- ================== PERTANYAAN 2 ================== --}}
            <div x-data="{ rating: 1 }" class="mb-5">
                <p class="mb-2 font-medium">Sarana & fasilitas</p>

                <div class="flex items-center gap-2">
                    <div class="flex gap-1">
                        <template x-for="i in 5">
                            <svg @click="rating = i" :class="i <= rating ? 'text-yellow-400' : 'text-gray-300'"
                                class="w-8 h-8 cursor-pointer" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286
                                         3.974 4.178.018c.969.004 1.371
                                         1.24.588 1.81l-3.385 2.46
                                         1.24 3.987c.285.918-.755 1.688-1.54
                                         1.118L10 13.348l-3.318
                                         2.946c-.784.57-1.824-.2-1.54-1.118
                                         l1.24-3.987-3.385-2.46c-.783-.57-.38-1.806.588-1.81
                                         l4.178-.018 1.286-3.974z" />
                            </svg>
                        </template>
                    </div>

                    <span class="text-gray-500" x-text="rating + '/5'"></span>
                </div>

                <input type="hidden" name="rating_fasilitas" :value="rating">
            </div>

            {{-- ================== PERTANYAAN 3 ================== --}}
            <div x-data="{ rating: 1 }" class="mb-6">
                <p class="mb-2 font-medium">Kualitas data</p>

                <div class="flex items-center gap-2">
                    <div class="flex gap-1">
                        <template x-for="i in 5">
                            <svg @click="rating = i" :class="i <= rating ? 'text-yellow-400' : 'text-gray-300'"
                                class="w-8 h-8 cursor-pointer" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286
                                         3.974 4.178.018c.969.004 1.371
                                         1.24.588 1.81l-3.385 2.46
                                         1.24 3.987c.285.918-.755 1.688-1.54
                                         1.118L10 13.348l-3.318
                                         2.946c-.784.57-1.824-.2-1.54-1.118
                                         l1.24-3.987-3.385-2.46c-.783-.57-.38-1.806.588-1.81
                                         l4.178-.018 1.286-3.974z" />
                            </svg>
                        </template>
                    </div>

                    <span class="text-gray-500" x-text="rating + '/5'"></span>
                </div>

                <input type="hidden" name="rating_kualitas" :value="rating">
            </div>

            {{-- ================== SARAN ================== --}}
            <div class="mb-6">
                <label class="font-medium block mb-2">Saran dan Masukan</label>
                <textarea name="saran_kritik" rows="4" class="w-full border rounded-lg p-3 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-full font-semibold">
                Kirim
            </button>
        </form>

    </div>
</x-guest-layout>
