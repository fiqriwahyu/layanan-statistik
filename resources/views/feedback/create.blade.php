<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Penilaian Layanan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-4 py-10">

        <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-6 md:p-8">

            <div class="text-center mb-8">

                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Penilaian Layanan
                </h1>

                <p class="text-gray-500 mt-2">
                    Berikan penilaian terhadap layanan yang telah Anda terima.
                </p>

            </div>

            <div class="mb-6 p-4 rounded-lg bg-blue-50 border border-blue-200">

                <div class="font-semibold text-gray-800">
                    Transaksi #{{ $transaction->id }}
                </div>

                <div class="text-sm text-gray-600 mt-1">
                    Tanggal:
                    {{ $transaction->created_at ? $transaction->created_at->format('d-m-Y H:i') : '-' }}
                </div>

                <div class="text-sm text-gray-600 mt-1">
                    Data diminta:
                    {{ $transaction->data_diminta ?? '-' }}
                </div>

            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('feedback.store') }}" method="POST" class="space-y-7">

                @csrf

                <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

                <div x-data="{ rating: {{ old('rating_pelayanan', 0) }} }">

                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        1. Bagaimana penilaian Anda terhadap pelayanan yang diberikan?
                    </label>

                    <div class="flex gap-2">

                        @for ($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                @click="rating = {{ $i }}"
                                class="focus:outline-none"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    class="w-9 h-9 transition"
                                    :class="rating >= {{ $i }} ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 fill-gray-300'"
                                >
                                    <path d="M12 2.5l2.94 5.95 6.56.95-4.75 4.63 1.12 6.54L12 17.48l-5.87 3.09 1.12-6.54L2.5 9.4l6.56-.95L12 2.5z"/>
                                </svg>
                            </button>
                        @endfor

                    </div>

                    <input type="hidden" name="rating_pelayanan" :value="rating">

                </div>

                <div x-data="{ rating: {{ old('rating_fasilitas', 0) }} }">

                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        2. Bagaimana penilaian Anda terhadap fasilitas pelayanan?
                    </label>

                    <div class="flex gap-2">

                        @for ($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                @click="rating = {{ $i }}"
                                class="focus:outline-none"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    class="w-9 h-9 transition"
                                    :class="rating >= {{ $i }} ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 fill-gray-300'"
                                >
                                    <path d="M12 2.5l2.94 5.95 6.56.95-4.75 4.63 1.12 6.54L12 17.48l-5.87 3.09 1.12-6.54L2.5 9.4l6.56-.95L12 2.5z"/>
                                </svg>
                            </button>
                        @endfor

                    </div>

                    <input type="hidden" name="rating_fasilitas" :value="rating">

                </div>

                <div x-data="{ rating: {{ old('rating_kualitas', 0) }} }">

                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        3. Bagaimana penilaian Anda terhadap kualitas layanan?
                    </label>

                    <div class="flex gap-2">

                        @for ($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                @click="rating = {{ $i }}"
                                class="focus:outline-none"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    class="w-9 h-9 transition"
                                    :class="rating >= {{ $i }} ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 fill-gray-300'"
                                >
                                    <path d="M12 2.5l2.94 5.95 6.56.95-4.75 4.63 1.12 6.54L12 17.48l-5.87 3.09 1.12-6.54L2.5 9.4l6.56-.95L12 2.5z"/>
                                </svg>
                            </button>
                        @endfor

                    </div>

                    <input type="hidden" name="rating_kualitas" :value="rating">

                </div>

                <div>

                    <label
                        for="saran_kritik"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Saran dan Kritik
                    </label>

                    <textarea
                        id="saran_kritik"
                        name="saran_kritik"
                        rows="5"
                        maxlength="2000"
                        placeholder="Tuliskan saran atau kritik Anda..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >{{ old('saran_kritik') }}</textarea>

                </div>

                <div class="flex flex-col md:flex-row gap-3">

                    <a
                        href="{{ route('feedback.index') }}"
                        class="w-full md:w-1/2 h-12 flex items-center justify-center rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100"
                    >
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="w-full md:w-1/2 h-12 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold"
                    >
                        Kirim Penilaian
                    </button>

                </div>

            </form>

        </div>

    </div>

</body>

</html>