<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengunjung BPS Kota Jambi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #edf5fc, #ffffff, #fff9ed);
        }

        .form-card {
            width: 100%;
            max-width: 410px;
        }

        .form-control,
        .form-select {
            min-height: 41px;
            font-size: 14px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-control::placeholder {
            color: #999;
        }

        .step-title {
            font-size: 15px;
            font-weight: 700;
        }

        .step-indicator {
            width: 28px;
            height: 28px;
            min-width: 28px;
            font-size: 12px;
        }

        .step-line {
            width: 50px;
            height: 1px;
            background: #dee2e6;
        }

        .custom-dropdown {
            position: relative;
        }

        .custom-dropdown-trigger {
            min-height: 41px;
            cursor: pointer;
        }

        .custom-dropdown-menu {
            position: absolute;
            z-index: 9999;
            top: calc(100% + 4px);
            left: 0;
            width: 100%;
            max-height: 220px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,.12);
        }

        .custom-dropdown-category {
            padding: 8px 12px;
            background: #f5f6f7;
            color: #333;
            font-size: 12px;
            font-weight: 700;
            border-bottom: 1px solid #eee;
        }

        .custom-dropdown-item {
            padding: 9px 14px;
            color: #212529;
            background: #fff;
            font-size: 13px;
            cursor: pointer;
        }

        .custom-dropdown-item:hover {
            background: #edf5fc;
        }

        .custom-dropdown-empty {
            padding: 12px;
            color: #777;
            font-size: 13px;
        }

        .info-text {
            font-size: 11px;
        }

        @media (max-width: 575px) {
            .form-card {
                max-width: 100%;
            }

            .form-card-inner {
                padding: 20px !important;
            }
        }
    </style>
</head>

<body>

<div x-data="{ step: 1 }" class="min-vh-100 d-flex align-items-center justify-content-center p-3">

    <div class="form-card">

        <div class="form-card-inner bg-white border border-dark rounded-4 shadow-sm p-4">

            {{-- LOGO --}}
            <div class="text-center mb-2">
                <img src="{{ asset('images/icons/logo-bps.png') }}" alt="Logo BPS" class="mx-auto" style="width:58px;height:58px;object-fit:contain;">
            </div>

            {{-- HEADER --}}
            <div class="text-center mb-4">
                <h1 class="fw-bold mb-1" style="font-size:20px;">Form Pengunjung BPS Kota Jambi</h1>
                <p class="text-secondary mb-0" style="font-size:13px;">Yuk lengkapi dulu form pendaftaran di bawah ini!</p>
            </div>

            {{-- STEP --}}
            <div class="d-flex justify-content-center align-items-center mb-4">

                <div class="step-indicator rounded-circle d-flex align-items-center justify-content-center" :class="step >= 1 ? 'bg-primary text-white' : 'bg-light text-secondary'">1</div>

                <div class="step-line"></div>

                <div class="step-indicator rounded-circle d-flex align-items-center justify-content-center" :class="step >= 2 ? 'bg-primary text-white' : 'bg-light text-secondary'">2</div>

                <div class="step-line"></div>

                <div class="step-indicator rounded-circle d-flex align-items-center justify-content-center" :class="step >= 3 ? 'bg-primary text-white' : 'bg-light text-secondary'">3</div>

            </div>

            <form action="{{ route('form-tamu.store') }}" method="POST">
                @csrf

                {{-- =====================================================
                     STEP 1 - DATA DIRI
                ====================================================== --}}
                <div x-show="step === 1">

                    <div class="step-title mb-3">Data Diri</div>

                    {{-- NAMA --}}
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input id="nama_lengkap" name="nama_lengkap" type="text" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap" class="form-control rounded-2" required>
                    </div>

                    {{-- NO HP --}}
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">Nomor HP</label>
                        <input id="no_hp" name="no_hp" type="text" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" class="form-control rounded-2" required>
                        <div id="cek-nohp-message" class="info-text mt-1 text-secondary"></div>
                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="form-select rounded-2" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" class="form-control rounded-2">
                    </div>

                    {{-- ALAMAT --}}
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat" class="form-control rounded-2">{{ old('alamat') }}</textarea>
                    </div>

                    {{-- USIA --}}
                    <div class="mb-4">
                        <label for="usia" class="form-label">Usia</label>
                        <input id="usia" name="usia" type="number" value="{{ old('usia') }}" placeholder="Masukkan usia" min="1" class="form-control rounded-2">
                    </div>

                    <button type="button" @click="step = 2" class="btn btn-primary rounded-pill w-100">
                        Lanjut
                    </button>

                </div>


                {{-- =====================================================
                     STEP 2 - PEKERJAAN
                ====================================================== --}}
                <div x-show="step === 2" x-cloak>

                    <div class="step-title mb-3">Pekerjaan</div>

                    {{-- INSTANSI --}}
                    <div class="mb-3">
                        <label for="nama_instansi" class="form-label">Nama Instansi</label>
                        <input id="nama_instansi" name="nama_instansi" type="text" value="{{ old('nama_instansi') }}" placeholder="Masukkan nama instansi" class="form-control rounded-2">
                    </div>

                    {{-- NIP / NIM --}}
                    <div class="mb-3">
                        <label for="nip_nim" class="form-label">NIP / NIM</label>
                        <input id="nip_nim" name="nip_nim" type="text" value="{{ old('nip_nim') }}" placeholder="Masukkan NIP / NIM" class="form-control rounded-2">
                    </div>

                    {{-- PEKERJAAN --}}
                    <div class="mb-3">
                        <label for="work_id" class="form-label">Pekerjaan</label>
                        <select id="work_id" name="work_id" class="form-select rounded-2">
                            <option value="">Pilih pekerjaan</option>

                            @foreach ($works as $work)
                                <option value="{{ $work->id }}" {{ old('work_id') == $work->id ? 'selected' : '' }}>
                                    {{ $work->work_type }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- PENDIDIKAN --}}
                    <div class="mb-4">
                        <label for="education_id" class="form-label">Pendidikan</label>
                        <select id="education_id" name="education_id" class="form-select rounded-2">
                            <option value="">Pilih pendidikan</option>

                            @foreach ($educations as $education)
                                <option value="{{ $education->id }}" {{ old('education_id') == $education->id ? 'selected' : '' }}>
                                    {{ $education->education_type }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" @click="step = 1" class="btn rounded-pill w-50 text-dark" style="background:#ffbd43;">
                            Kembali
                        </button>

                        <button type="button" @click="step = 3" class="btn rounded-pill w-50 text-white" style="background:#385683;">
                            Lanjut
                        </button>
                    </div>

                </div>


                {{-- =====================================================
                     STEP 3 - LAYANAN
                ====================================================== --}}
                <div x-show="step === 3" x-cloak>

                    <div class="step-title mb-3">Layanan</div>

                    {{-- MEDIA --}}
                    <div class="mb-3">
                        <label for="media_id" class="form-label">Media Layanan</label>

                        <select id="media_id" name="media_id" class="form-select rounded-2" required>
                            <option value="">Pilih media layanan</option>

                            @foreach ($medias as $media)
                                <option value="{{ $media->id }}" {{ old('media_id') == $media->id ? 'selected' : '' }}>
                                    {{ $media->media_type }}
                                </option>
                            @endforeach

                        </select>
                    </div>


                    {{-- =================================================
                         KEBUTUHAN DATA / SUB KATEGORI
                    ================================================== --}}
                    <div class="mb-3" x-data="{ open: false, selected: '{{ old('sub_categorie_id') }}', text: '{{ old('sub_categorie_id') ? 'Kebutuhan data dipilih' : '' }}' }">

                        <label class="form-label">Kebutuhan Data</label>

                        <input type="hidden" id="sub_categorie_id" name="sub_categorie_id" :value="selected" required>

                        <div class="custom-dropdown">

                            <button type="button" @click="open = !open" class="custom-dropdown-trigger form-control bg-white text-start d-flex align-items-center justify-content-between">
                                <span x-text="text || 'Pilih kebutuhan data'" class="text-truncate"></span>
                                <span class="ms-2">⌄</span>
                            </button>

                            <div x-show="open" x-cloak @click.outside="open = false" class="custom-dropdown-menu">

                                @forelse ($categories as $categorie)

                                    <div class="custom-dropdown-category">
                                        {{ $categorie->categorie_type }}
                                    </div>

                                    @forelse ($categorie->subCategories as $sub)

                                        <div class="custom-dropdown-item" @click="selected = '{{ $sub->id }}'; text = '{{ $sub->sub_categorie_type }}'; open = false">
                                            {{ $sub->sub_categorie_type }}
                                        </div>

                                    @empty

                                        <div class="custom-dropdown-empty">
                                            Tidak ada sub kategori.
                                        </div>

                                    @endforelse

                                @empty

                                    <div class="custom-dropdown-empty">
                                        Tidak ada kategori data.
                                    </div>

                                @endforelse

                            </div>

                        </div>

                    </div>


                    {{-- LAYANAN --}}
                    <div class="mb-3">
                        <label for="service_id" class="form-label">Layanan</label>

                        <select id="service_id" name="service_id" class="form-select rounded-2" required>
                            <option value="">Pilih layanan</option>

                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->service_type }}
                                </option>
                            @endforeach

                        </select>
                    </div>


                    {{-- TUJUAN --}}
                    <div class="mb-3">
                        <label for="purpose_id" class="form-label">Tujuan</label>

                        <select id="purpose_id" name="purpose_id" class="form-select rounded-2" required>
                            <option value="">Pilih tujuan</option>

                            @foreach ($purposes as $purpose)
                                <option value="{{ $purpose->id }}" {{ old('purpose_id') == $purpose->id ? 'selected' : '' }}>
                                    {{ $purpose->purpose_type }}
                                </option>
                            @endforeach

                        </select>
                    </div>


                    {{-- DATA DIMINTA --}}
                    <div class="mb-4">
                        <label for="data_diminta" class="form-label">Data yang Diminta</label>

                        <textarea id="data_diminta" name="data_diminta" rows="3" placeholder="Tuliskan data yang dibutuhkan" class="form-control rounded-2">{{ old('data_diminta') }}</textarea>
                    </div>


                    {{-- BUTTON --}}
                    <div class="d-flex gap-2">

                        <button type="button" @click="step = 2" class="btn rounded-pill w-50 text-dark" style="background:#ffbd43;">
                            Kembali
                        </button>

                        <button type="submit" class="btn rounded-pill w-50 text-white" style="background:#385683;">
                            Kirim
                        </button>

                    </div>

                </div>

            </form>

        </div>

        <div class="text-center mt-3">
            <small class="text-secondary" style="font-size:11px;">
                BPS Kota Jambi
            </small>
        </div>

    </div>

</div>


{{-- =========================================================
     AUTO FILL BERDASARKAN NOMOR HP
========================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const noHp = document.getElementById('no_hp');
    const message = document.getElementById('cek-nohp-message');

    if (!noHp) return;

    noHp.addEventListener('blur', function () {

        const value = this.value.trim();

        if (!value) {
            message.textContent = '';
            return;
        }

        message.textContent = 'Memeriksa data...';
        message.className = 'info-text mt-1 text-secondary';

        fetch(`/cek-nohp?no_hp=${encodeURIComponent(value)}`)
            .then(response => response.json())
            .then(data => {

                if (!data || !data.found) {
                    message.textContent = '';
                    return;
                }

                if (data.nama_lengkap) {
                    document.getElementById('nama_lengkap').value = data.nama_lengkap;
                }

                if (data.alamat) {
                    document.getElementById('alamat').value = data.alamat;
                }

                if (data.usia) {
                    document.getElementById('usia').value = data.usia;
                }

                if (data.email) {
                    document.getElementById('email').value = data.email;
                }

                if (data.nama_instansi) {
                    document.getElementById('nama_instansi').value = data.nama_instansi;
                }

                if (data.nip_nim) {
                    document.getElementById('nip_nim').value = data.nip_nim;
                }

                if (data.jenis_kelamin) {
                    document.getElementById('jenis_kelamin').value = data.jenis_kelamin;
                }

                if (data.work_id) {
                    document.getElementById('work_id').value = data.work_id;
                }

                if (data.education_id) {
                    document.getElementById('education_id').value = data.education_id;
                }

                message.textContent = 'Data otomatis diisi berdasarkan nomor HP. Anda bisa mengubahnya.';
                message.className = 'info-text mt-1 text-success';

            })
            .catch(error => {
                console.error(error);
                message.textContent = '';
            });

    });

});
</script>

</body>
</html>