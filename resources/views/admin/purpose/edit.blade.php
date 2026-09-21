<x-app-layout>

    <div class="w-full px-3 sm:px-4 lg:px-5 py-4 sm:py-5">

        <div class="w-full max-w-[420px] mx-auto
                    border border-gray-400
                    bg-white
                    rounded-sm">

            {{-- Header --}}
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
                    Form Ubah Tujuan
                </h2>

            </div>

            {{-- Body --}}
            <div class="px-4 sm:px-7 py-5">

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

                <form action="{{ route('admin.purpose.update', $purpose->id) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-4">

                        <label for="purpose_type"
                            class="block mb-2
                                   text-[15px] sm:text-[16px]
                                   text-gray-700">

                            Nama Purpose

                        </label>

                        <input type="text"
                            name="purpose_type"
                            id="purpose_type"
                            value="{{ old('purpose_type', $purpose->purpose_type) }}"
                            required
                            autofocus
                            placeholder="Masukkan nama purpose"
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

                    <div class="flex flex-col sm:flex-row gap-2 w-full">

                        <a href="{{ route('admin.purpose.index') }}"
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