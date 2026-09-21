<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        Login - Layanan Statistik BPS Kota Jambi
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="min-h-screen bg-gradient-to-br from-[#eef4fb] via-white to-[#e8f0f9]">


    <div class="min-h-screen flex items-center justify-center px-4 py-8">


        <div class="w-full max-w-[430px]">


            {{-- CARD LOGIN --}}

            <div class="bg-white rounded-2xl shadow-[0_15px_45px_rgba(30,58,95,0.12)] border border-gray-100 overflow-hidden">


                {{-- TOP ACCENT --}}

                <div class="h-1.5 bg-[#3E5C8A]"></div>


                <div class="px-7 sm:px-9 py-9">


                    {{-- LOGO & TITLE --}}

                    <div class="text-center mb-8">


                        <div class="flex justify-center mb-5">

                            <div
                                class="w-[92px] h-[92px] rounded-2xl bg-[#f4f7fb] border border-[#e1e8f2] flex items-center justify-center shadow-sm"
                            >

                                <img
                                    src="{{ asset('images/icons/logo-bps.png') }}"
                                    alt="Logo BPS"
                                    class="h-[68px] w-auto object-contain"
                                >

                            </div>

                        </div>


                        <h1 class="text-[25px] font-bold text-gray-800 tracking-tight">
                            Layanan Statistik
                        </h1>


                        <p class="text-gray-500 text-sm mt-1.5">
                            BPS Kota Jambi
                        </p>


                        <div class="flex items-center justify-center gap-2 mt-4">

                            <span class="w-8 h-px bg-gray-200"></span>

                            <span class="text-[11px] uppercase tracking-wider text-gray-400">
                                Sistem Informasi
                            </span>

                            <span class="w-8 h-px bg-gray-200"></span>

                        </div>

                    </div>



                    {{-- ERROR VALIDATION --}}

                    @if ($errors->any())

                        <div class="mb-5 rounded-xl bg-red-50 border border-red-100 px-4 py-3">

                            <div class="flex gap-3">

                                <div class="flex-shrink-0 mt-0.5">

                                    <svg
                                        class="w-5 h-5 text-red-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />

                                    </svg>

                                </div>


                                <div>

                                    @foreach ($errors->all() as $error)

                                        <p class="text-sm text-red-600 leading-relaxed">
                                            {{ $error }}
                                        </p>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    @endif



                    {{-- SESSION ERROR --}}

                    @if (session('error'))

                        <div class="mb-5 rounded-xl bg-red-50 border border-red-100 px-4 py-3">

                            <div class="flex items-center gap-3">

                                <svg
                                    class="w-5 h-5 text-red-500 flex-shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />

                                </svg>


                                <p class="text-sm text-red-600">
                                    {{ session('error') }}
                                </p>

                            </div>

                        </div>

                    @endif



                    {{-- LOGIN FORM --}}

                    <form
                        method="POST"
                        action="{{ route('login') }}"
                    >

                        @csrf



                        {{-- EMAIL --}}

                        <div class="mb-5">

                            <label
                                for="email"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Email
                            </label>


                            <div class="relative">


                                {{-- EMAIL ICON --}}

                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />

                                    </svg>

                                </div>


                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Masukkan email"
                                    class="w-full h-12 rounded-xl border border-gray-300 bg-gray-50 pl-12 pr-4 text-sm text-gray-800 placeholder-gray-400 outline-none transition duration-200 focus:bg-white focus:border-[#3E5C8A] focus:ring-4 focus:ring-[#3E5C8A]/10"
                                >

                            </div>

                        </div>



                        {{-- PASSWORD --}}

                        <div class="mb-4">

                            <label
                                for="password"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Password
                            </label>


                            <div class="relative">


                                {{-- PASSWORD ICON --}}

                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2h8z"
                                        />

                                    </svg>

                                </div>


                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                    class="w-full h-12 rounded-xl border border-gray-300 bg-gray-50 pl-12 pr-4 text-sm text-gray-800 placeholder-gray-400 outline-none transition duration-200 focus:bg-white focus:border-[#3E5C8A] focus:ring-4 focus:ring-[#3E5C8A]/10"
                                >

                            </div>

                        </div>



                        {{-- REMEMBER ME --}}

                        <div class="flex items-center justify-between mb-7">


                            <label
                                for="remember"
                                class="flex items-center cursor-pointer select-none"
                            >

                                <input
                                    id="remember"
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    class="w-4 h-4 rounded border-gray-300 text-[#3E5C8A] focus:ring-[#3E5C8A]/30"
                                >


                                <span class="ml-2.5 text-sm text-gray-600">
                                    Ingat saya
                                </span>

                            </label>


                        </div>



                        {{-- LOGIN BUTTON --}}

                        <button
                            type="submit"
                            class="group w-full h-12 bg-[#3E5C8A] hover:bg-[#30486e] active:bg-[#273c5d] text-white font-semibold rounded-xl shadow-md shadow-[#3E5C8A]/20 hover:shadow-lg hover:shadow-[#3E5C8A]/25 transition-all duration-200 flex items-center justify-center gap-2"
                        >

                            <span>
                                Login
                            </span>


                            <svg
                                class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                                />

                            </svg>

                        </button>


                    </form>


                </div>


                {{-- CARD FOOTER --}}

                <div class="border-t border-gray-100 bg-gray-50/70 px-7 py-4 text-center">

                    <p class="text-xs text-gray-400">
                        © {{ date('Y') }} BPS Kota Jambi
                    </p>

                </div>


            </div>


            {{-- SMALL FOOTER --}}

            <p class="text-center text-xs text-gray-400 mt-5">
                Akses layanan statistik secara mudah dan terintegrasi
            </p>


        </div>

    </div>


</body>

</html>