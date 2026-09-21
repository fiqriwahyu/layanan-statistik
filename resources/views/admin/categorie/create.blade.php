<x-app-layout>

    <div class="w-full px-3 sm:px-4 lg:px-5 py-4 sm:py-5">

        <div class="w-full max-w-[420px] mx-auto
                    border border-gray-400
                    bg-white
                    rounded-sm">

            {{-- HEADER --}}
            <div class="min-h-[64px]
                        flex items-center
                        px-4 sm:px-7
                        py-3
                        bg-gray-100
                        border-b border-gray-300">

                <h2 class="text-[20px] sm:text-[24px]
                           leading-6
                           font-normal
                           text-gray-600
                           break-words">
                    Form Tambah kategorie
                </h2>

            </div>

            {{-- CONTENT --}}
            <div class="px-4 sm:px-7 py-5">

                {{-- ERROR --}}
                @if ($errors->any())

                    <div class="mb-4
                                text-[14px]
                                text-red-600
                                break-words">

                        @foreach ($errors->all() as $error)

                            <div class="mb-1">
                                {{ $error }}
                            </div>

                        @endforeach

                    </div>

                @endif

                {{-- FORM --}}
                <form action="{{ route('admin.categorie.store') }}"
                    method="POST">

                    @csrf

                    {{-- NAMA KATEGORI --}}
                    <div class="mb-4">

                        <label for="categorie_type"
                            class="block mb-2
                                   text-[15px] sm:text-[16px]
                                   text-gray-700">

                            Nama Kategori

                        </label>

                        <input type="text"
                            id="categorie_type"
                            name="categorie_type"
                            value="{{ old('categorie_type') }}"
                            required
                            autofocus
                            placeholder="Masukkan nama kategori"
                            class="w-full h-[40px]
                                   border border-gray-500
                                   rounded-sm
                                   px-3
                                   text-[15px] sm:text-[16px]
                                   text-gray-700
                                   placeholder-gray-400
                                   focus:outline-none
                                   focus:border-gray-700">

                    </div>

                    {{-- BUTTON --}}
                    <div class="flex flex-col sm:flex-row
                                gap-2
                                w-full">

                        {{-- KEMBALI --}}
                        <a href="{{ route('admin.categorie.index') }}"
                            class="w-full sm:w-auto
                                   text-center
                                   px-4 py-2
                                   bg-blue-600
                                   hover:bg-blue-700
                                   text-white
                                   text-[14px]
                                   rounded-sm">

                            Kembali

                        </a>

                        {{-- SIMPAN --}}
                        <button type="submit"
                            class="w-full sm:w-auto
                                   px-4 py-2
                                   bg-green-500
                                   hover:bg-green-600
                                   text-white
                                   text-[14px]
                                   rounded-sm">

                            Simpan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>