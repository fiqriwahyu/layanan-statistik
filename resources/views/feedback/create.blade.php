<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penilaian Layanan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#cfddf4] flex items-center justify-center px-4 py-6 sm:px-6">

    <div class="w-full max-w-[375px] sm:max-w-[390px] bg-white border border-gray-700 rounded-lg shadow-md px-6 py-6 sm:px-7 sm:py-7">

        {{-- HEADER --}}
        <div class="mb-5">
            <h1 class="text-xl sm:text-[21px] font-bold text-gray-900 mb-4">
                Beri Penilaian
            </h1>
        </div>

        <form action="{{ route('feedback.store') }}" method="POST">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

            {{-- PERTANYAAN 1 --}}
            <div x-data="{ rating: 0 }" class="mb-4">
                <p class="text-[11px] sm:text-xs text-gray-800 leading-5 mb-2">
                    Apakah anda puas dengan pelayanan yang kami berikan?
                </p>

                <div class="flex items-center gap-4">
                    <div class="flex gap-1">
                        <template x-for="i in 5" :key="i">
                            <svg @click="rating = i" :class="i <= rating ? 'text-gray-700' : 'text-gray-300'" class="w-7 h-7 sm:w-8 sm:h-8 cursor-pointer transition duration-150 hover:scale-105" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974 4.178.018c.969.004 1.371 1.24.588 1.81l-3.385 2.46 1.24 3.987c.285.918-.755 1.688-1.54 1.118L10 13.348l-3.318 2.946c-.784.57-1.824-.2-1.54-1.118l1.24-3.987-3.385-2.46c-.783-.57-.38-1.806.588-1.81l4.178-.018 1.286-3.974z" />
                            </svg>
                        </template>
                    </div>

                    <span class="min-w-[35px] text-[20px] sm:text-[21px] text-gray-700" x-text="rating + '/5'"></span>
                </div>

                <input type="hidden" name="rating_pelayanan" :value="rating || 1">
            </div>

            {{-- PERTANYAAN 2 --}}
            <div x-data="{ rating: 0 }" class="mb-4">
                <p class="text-[11px] sm:text-xs text-gray-800 leading-5 mb-2">
                    Apakah anda puas dengan sarana dan fasilitas yang kami sediakan?
                </p>

                <div class="flex items-center gap-4">
                    <div class="flex gap-1">
                        <template x-for="i in 5" :key="i">
                            <svg @click="rating = i" :class="i <= rating ? 'text-gray-700' : 'text-gray-300'" class="w-7 h-7 sm:w-8 sm:h-8 cursor-pointer transition duration-150 hover:scale-105" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974 4.178.018c.969.004 1.371 1.24.588 1.81l-3.385 2.46 1.24 3.987c.285.918-.755 1.688-1.54 1.118L10 13.348l-3.318 2.946c-.784.57-1.824-.2-1.54-1.118l1.24-3.987-3.385-2.46c-.783-.57-.38-1.806.588-1.81l4.178-.018 1.286-3.974z" />
                            </svg>
                        </template>
                    </div>

                    <span class="min-w-[35px] text-[20px] sm:text-[21px] text-gray-700" x-text="rating + '/5'"></span>
                </div>

                <input type="hidden" name="rating_fasilitas" :value="rating || 1">
            </div>

            {{-- PERTANYAAN 3 --}}
            <div x-data="{ rating: 0 }" class="mb-5">
                <p class="text-[11px] sm:text-xs text-gray-800 leading-5 mb-2">
                    Apakah anda puas dengan kualitas data yang kami berikan?
                </p>

                <div class="flex items-center gap-4">
                    <div class="flex gap-1">
                        <template x-for="i in 5" :key="i">
                            <svg @click="rating = i" :class="i <= rating ? 'text-gray-700' : 'text-gray-300'" class="w-7 h-7 sm:w-8 sm:h-8 cursor-pointer transition duration-150 hover:scale-105" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974 4.178.018c.969.004 1.371 1.24.588 1.81l-3.385 2.46 1.24 3.987c.285.918-.755 1.688-1.54 1.118L10 13.348l-3.318 2.946c-.784.57-1.824-.2-1.54-1.118l1.24-3.987-3.385-2.46c-.783-.57-.38-1.806.588-1.81l4.178-.018 1.286-3.974z" />
                            </svg>
                        </template>
                    </div>

                    <span class="min-w-[35px] text-[20px] sm:text-[21px] text-gray-700" x-text="rating + '/5'"></span>
                </div>

                <input type="hidden" name="rating_kualitas" :value="rating || 1">
            </div>

            {{-- SARAN --}}
            <div class="mb-4">
                <label class="block text-[11px] sm:text-xs text-gray-800 mb-2">
                    Saran dan Masukkan
                </label>

                <textarea name="saran_kritik" rows="4" class="w-full resize-none border border-gray-700 rounded-lg px-3 py-2 text-sm outline-none focus:border-[#385683] focus:ring-1 focus:ring-[#385683]"></textarea>
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-center">
                <button type="submit" class="bg-[#32e02d] hover:bg-[#28cc24] text-white text-xs sm:text-sm px-7 py-2 rounded-full font-medium transition duration-200">
                    Kirim
                </button>
            </div>

        </form>

    </div>

</body>
</html>