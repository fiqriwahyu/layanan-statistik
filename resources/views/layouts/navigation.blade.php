<nav class="bg-white border-b border-gray-200">

    <div class="px-6 py-4 flex justify-between items-center">

        <div>

            {{-- <h1 class="text-lg font-semibold text-gray-800">
                Layanan Statistik BPS Kota Jambi
            </h1> --}}

        </div>


        @auth

            <div class="flex items-center gap-4">

                <div class="text-right">

                    <p class="text-sm font-semibold text-gray-700">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-gray-500">
                        {{ auth()->user()->email }}
                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-100"
                    >
                        Logout
                    </button>

                </form>

            </div>

        @endauth

    </div>

</nav>