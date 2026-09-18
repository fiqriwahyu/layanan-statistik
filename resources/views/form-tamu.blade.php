<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Tamu - {{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 p-6" x-data="{ step: 1 }">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow">

        <h1 class="text-2xl font-bold mb-6 text-center">Form Tamu</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('form-tamu.store') }}">
            @csrf

            <!-- STEP 1 -->
            <div x-show="step === 1" x-cloak>
                <h2 class="font-semibold mb-4">Data Diri</h2>
                <label class="block mb-1 font-semibold">Nama Lengkap</label>
                <input id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" class="input mb-2 w-full"
                    required>

                <label class="block mb-1 font-semibold">No HP</label>
                <input id="no_hp" name="no_hp" placeholder="No HP" class="input mb-2 w-full" required>

                <label class="block mb-1 font-semibold">Jenis Kelamin</label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="input mb-2 w-full" required>
                    <option value="">Jenis Kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>

                <label class="block mb-1 font-semibold">Email</label>
                <input id="email" name="email" placeholder="Email" class="input mb-2 w-full">

                <label class="block mb-1 font-semibold">Alamat</label>
                <textarea id="alamat" name="alamat" placeholder="Alamat" class="input mb-2 w-full" required></textarea>

                <label class="block mb-1 font-semibold">Usia</label>
                <input id="usia" type="number" name="usia" placeholder="Usia" class="input mb-2 w-full"
                    required>
            </div>

            <!-- STEP 2 -->
            <div x-show="step === 2" x-cloak>
                <h2 class="font-semibold mb-4">Pekerjaan</h2>
                <label class="block mb-1 font-semibold">Nama Instansi</label>
                <input name="nama_instansi" placeholder="Instansi" class="input mb-2 w-full">

                <label class="block mb-1 font-semibold">NIP / NIM</label>
                <input name="nip_nim" placeholder="NIP / NIM" class="input mb-2 w-full">

                <label class="block mb-1 font-semibold">Pekerjaan</label>
                <select name="work_id" class="input mb-2 w-full">
                    <option value="">-- Pilih Pekerjaan --</option>
                    @foreach ($works as $work)
                        <option value="{{ $work->id }}">{{ $work->work_type }}</option>
                    @endforeach
                </select>

                <label class="block mb-1 font-semibold">Pendidikan</label>
                <select name="education_id" class="input mb-2 w-full">
                    <option value="">-- Pilih Pendidikan --</option>
                    @foreach ($educations as $education)
                        <option value="{{ $education->id }}">{{ $education->education_type }}</option>
                    @endforeach
                </select>
            </div>

            <!-- STEP 3 -->
            <div x-show="step === 3" x-cloak>
                <h2 class="font-semibold mb-4">Layanan</h2>
                {{-- <label class="block mb-1 font-semibold">Media Pelayanan</label>
                <select name="media_pelayanan" class="input mb-2 w-full" required>
                    <option value="">Media Pelayanan</option>
                    <option value="Datang Langsung">Datang Langsung</option>
                    <option value="Online">Online</option>
                </select> --}}
                <label class="block mb-1 font-semibold">media Pelayanan</label>
                <select name="media_id" class="input mb-2 w-full" required>
                    <option value="">media Pelayanan</option>
                    @foreach ($medias as $media)
                        <option value="{{ $media->id }}">
                            {{ $media->media_type }}
                        </option>
                    @endforeach
                </select>

                <div class="mb-4 relative">
                    <label class="block mb-1 font-semibold">Kebutuhan Data</label>
                    <input type="hidden" name="sub_categorie_id" id="sub_categorie_id" required>

                    <div id="dropdownTrigger" onclick="toggleDropdown()"
                        class="input cursor-pointer flex justify-between items-center">
                        <span id="dropdownLabel" class="text-gray-400">-- Pilih Kebutuhan Data --</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <div id="dropdownMenu"
                        class="absolute z-10 mt-1 w-full bg-white border rounded shadow hidden max-h-64 overflow-y-auto">

                        @foreach ($categories as $categorie)
                            <div class="px-4 py-2 bg-gray-100 font-semibold text-gray-700">
                                {{ $categorie->categorie_type }}
                            </div>

                            @foreach ($categorie->subCategories as $sub)
                                <div onclick="pilihSubKategori({{ $sub->id }}, '{{ $sub->sub_categorie_type }}')"
                                    class="px-6 py-2 cursor-pointer hover:bg-blue-100">
                                    {{ $sub->sub_categorie_type }}
                                </div>
                            @endforeach
                        @endforeach

                    </div>
                </div>

                <label class="block mb-1 font-semibold">Jenis Pelayanan</label>
                <select name="service_id" class="input mb-2 w-full" required>
                    <option value="">Jenis Pelayanan</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">
                            {{ $service->service_type }}
                        </option>
                    @endforeach
                </select>

                <label class="block mb-1 font-semibold">Tujuan</label>
                <select name="purpose_id" class="input mb-2 w-full" required>
                    <option value="">-- Pilih tujuan --</option>
                    @foreach ($purposes as $purpose)
                        <option value="{{ $purpose->id }}">
                            {{ $purpose->purpose_type }}
                        </option>
                    @endforeach
                </select>

                <label class="block mb-1 font-semibold">Data Spesifik yang Dibutuhkan <span
                        class="text-red-500">*</span></label>
                <textarea name="data_diminta" rows="4" required class="input w-full"
                    placeholder="Contoh: Data jumlah penduduk Kota Jambi tahun 2020–2024 berdasarkan kecamatan"></textarea>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-between mt-6">
                <button type="button" x-show="step > 1" @click="step--"
                    class="px-4 py-2 bg-gray-300 rounded">Kembali</button>
                <button type="button" x-show="step < 3" @click="step++"
                    class="px-4 py-2 bg-blue-600 text-white rounded">Lanjut</button>
                <button type="submit" x-show="step === 3"
                    class="px-4 py-2 bg-green-600 text-white rounded">Kirim</button>
            </div>

        </form>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        }

        function pilihSubKategori(id, nama) {
            document.getElementById('sub_categorie_id').value = id;
            const label = document.getElementById('dropdownLabel');
            label.innerText = nama;
            label.classList.remove('text-gray-400');
            document.getElementById('dropdownMenu').classList.add('hidden');
        }

        document.addEventListener('click', function(e) {
            const trigger = document.getElementById('dropdownTrigger');
            const menu = document.getElementById('dropdownMenu');
            if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        document.getElementById('no_hp').addEventListener('blur', function() {
            let nohp = this.value.trim();
            if (nohp.length < 8) return;

            fetch(`/cek-nohp?no_hp=${nohp}`)
                .then(res => res.json())
                .then(res => {
                    if (res.exists) {
                        const data = res.data;
                        ['nama_lengkap', 'alamat', 'usia', 'email', 'nama_instansi', 'nip_nim'].forEach(id => {
                            const el = document.getElementById(id) || document.querySelector(
                                `[name="${id}"]`);
                            if (el) el.value = data[id] ?? '';
                        });

                        [{
                                selector: '#jenis_kelamin',
                                value: data.jenis_kelamin
                            },
                            {
                                selector: '[name="work_id"]',
                                value: data.work_id
                            },
                            {
                                selector: '[name="education_id"]',
                                value: data.education_id
                            }
                        ].forEach(item => {
                            const sel = document.querySelector(item.selector);
                            if (sel) sel.value = item.value ?? '';
                        });

                        if (!document.getElementById('autoFilledNote')) {
                            const note = document.createElement('p');
                            note.id = 'autoFilledNote';
                            note.className = 'text-sm text-blue-600 mb-2';
                            note.innerText = 'Data otomatis diisi berdasarkan nomor HP. Anda bisa mengubahnya.';
                            const step1 = document.querySelector('div[x-show="step === 1"]');
                            step1.insertBefore(note, step1.firstChild.nextSibling);
                        }
                    }
                }).catch(err => console.error(err));
        });
    </script>

</body>

</html>
