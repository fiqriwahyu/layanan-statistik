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


<body class="bg-gray-100 min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-4">

        <div class="w-full max-w-md">

            <div class="bg-white rounded-xl shadow-lg p-8">


                {{-- LOGO --}}

                <div class="text-center mb-8">

                    <img
                        src="{{ asset('logo-bps.png') }}"
                        alt="Logo BPS"
                        class="h-20 mx-auto mb-4"
                    >

                    <h1 class="text-2xl font-bold text-gray-800">
                        Layanan Statistik
                    </h1>

                    <p class="text-gray-500 mt-1">
                        BPS Kota Jambi
                    </p>

                </div>


                {{-- ERROR --}}

                @if ($errors->any())

                    <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4">

                        @foreach ($errors->all() as $error)

                            <p class="text-sm text-red-600">
                                {{ $error }}
                            </p>

                        @endforeach

                    </div>

                @endif


                {{-- SESSION ERROR --}}

                @if (session('error'))

                    <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4">

                        <p class="text-sm text-red-600">
                            {{ session('error') }}
                        </p>

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
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-[#3E5C8A] focus:ring-[#3E5C8A]"
                            placeholder="Masukkan email"
                        >

                    </div>


                    {{-- PASSWORD --}}

                    <div class="mb-5">

                        <label
                            for="password"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Password
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-[#3E5C8A] focus:ring-[#3E5C8A]"
                            placeholder="Masukkan password"
                        >

                    </div>


                    {{-- REMEMBER ME --}}

                    <div class="flex items-center mb-6">

                        <input
                            id="remember"
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="rounded border-gray-300 text-[#3E5C8A] focus:ring-[#3E5C8A]"
                        >

                        <label
                            for="remember"
                            class="ml-2 text-sm text-gray-600"
                        >
                            Ingat saya
                        </label>

                    </div>


                    {{-- LOGIN BUTTON --}}

                    <button
                        type="submit"
                        class="w-full bg-[#3E5C8A] hover:bg-[#30486e] text-white font-semibold py-3 rounded-lg transition"
                    >
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>