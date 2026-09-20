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
                    Form Ubah Sub Categorie
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

                <form action="{{ route('admin.subcategorie.update', $subcategorie) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    {{-- Nama Sub Kategori --}}
                    <div class="mb-4">

                        <label for="sub_categorie_type"
                            class="block mb-2
                                   text-[15px] sm:text-[16px]
                                   text-gray-700">

                            Nama Sub Kategori

                        </label>

                        <input type="text"
                            id="sub_categorie_type"
                            name="sub_categorie_type"
                            value="{{ old('sub_categorie_type', $subcategorie->sub_categorie_type) }}"
                            required
                            autofocus
                            placeholder="Masukkan nama sub kategori"
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

                    {{-- Kategori --}}
                    <div class="mb-4">

                        <label for="categorie_id"
                            class="block mb-2
                                   text-[15px] sm:text-[16px]
                                   text-gray-700">

                            Kategori

                        </label>

                        <select name="categorie_id"
                            id="categorie_id"
                            required
                            class="w-full h-[40px]
                                   border border-gray-500
                                   rounded-sm
                                   px-3
                                   text-[15px] sm:text-[16px]
                                   text-gray-700
                                   focus:outline-none
                                   focus:border-gray-700">

                            <option value="">-- Pilih Kategori --</option>

                            @foreach ($categories as $categorie)

                                <option value="{{ $categorie->id }}"
                                    {{ old('categorie_id', $subcategorie->categorie_id) == $categorie->id ? 'selected' : '' }}>

                                    {{ $categorie->categorie_type }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Button --}}
                    <div class="flex flex-col sm:flex-row gap-2 w-full">

                        <a href="{{ route('admin.subcategorie.index') }}"
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