<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none
        }
    </style>

</head>

<body class="text-[#1b1b18]">

    @if (session('success'))
        <div class="max-w-7xl mx-auto px-6 mt-6">
            <div class="bg-green-100 text-green-700 p-4 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif


    {{-- NAVBAR --}}
    <nav class="bg-[#385683] border-b border-white/20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between h-16 items-center">

                {{-- KIRI: Logo + Nama --}}
                <a href="#beranda" class="flex items-center gap-3 text-white">
                    <div class="w-8 h-8 bg-white text-[#385683] rounded-lg flex items-center justify-center font-bold">
                        B
                    </div>
                    <span class="text-sm md:text-base font-semibold leading-tight">
                        Badan Pusat Statistik <br>
                        Kota Jambi
                    </span>
                </a>

                {{-- KANAN: Menu Guest --}}
                <div class="flex items-center gap-8 text-sm font-medium text-white">
                    <a href="#beranda" class="hover:text-indigo-300 transition">Beranda</a>
                    <a href="#tentang" class="hover:text-indigo-300 transition">Tentang Kami</a>
                    <a href="#layanan" class="hover:text-indigo-300 transition">Layanan Kami</a>
                    <a href="#penilaian" class="hover:text-indigo-300 transition">Penilaian</a>
                    <a href="#kontak" class="hover:text-indigo-300 transition">Kontak</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section id="beranda" class="relative bg-cover bg-center"
        style="background-image: url('{{ asset('images/hero-bps.png') }}');">

        {{-- Overlay gelap --}}
        <div class="absolute inset-0 bg-[#6D7F99]/80"></div>

        {{-- Konten --}}
        <div class="relative max-w-7xl mx-auto px-6 py-32 text-center text-white">

            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                Layanan Statistik <br class="hidden md:block">
                BPS Kota Jambi
            </h1>

            <p class="text-lg md:text-xl text-white/90 mb-12">
                Melayani dengan profesional
            </p>

            <a href="{{ route('form-tamu') }}" class="inline-flex px-8 py-3 bg-blue-600 text-white rounded-lg">
                Form Tamu
            </a>


        </div>
    </section>

    {{-- TENTANG KAMI --}}
    <section id="tentang" class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                {{-- KIRI: FOTO --}}
                <div>
                    <img src="{{ asset('images/tentang-bps.png') }}" alt="BPS Kota Jambi"
                        class="rounded-xl shadow-lg w-full object-cover">
                </div>

                {{-- KANAN: KONTEN --}}
                <div>

                    <h2 class="text-3xl font-bold text-[#385683] mb-4">
                        Tentang Kami
                    </h2>

                    {{-- VISI --}}
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-[#385683] mb-2">
                            Visi
                        </h3>
                        <p class="text-gray-700">
                            Penyedia Data Statistik Berkualitas Untuk Indonesia Maju
                        </p>
                    </div>

                    {{-- MISI --}}
                    <div>
                        <h3 class="text-xl font-semibold text-[#385683] mb-3">
                            Misi
                        </h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Menyediakan statistik berkualitas yang berstandar nasional dan internasional.</li>
                            <li>Membina Organisasi Perangkat Daerah (OPD) melalui Sistem Statistik Nasional yang
                                berkesinambung</li>
                            <li>Mewujudkan pelayanan prima di bidang statistilk untuk terwujudnya Sistem Statistik
                                Nasional.</li>
                            <li>Membangun SDM yang unggul dan adaptif berlandaskan nilai profesionalisme, integritas dan
                                amanah.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

                {{-- KIRI --}}
                <div>

                    <h3 class="text-3xl font-bold text-[#385683] mb-4">
                        Ringkasan Data Pengunjung
                    </h3>

                    <p class="text-lg text-gray-600 mb-8">
                        Berikut ini merupakan ringkasan jumlah pengguna buku tamu
                        BPS Kota Jambi berdasarkan kriteria pekerjaan.
                    </p>

                    {{-- TOTAL --}}


                    {{-- LIST PEKERJAAN --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-white border rounded-lg p-6 shadow text-center">
                            <h4 class="text-sm text-gray-500 uppercase">
                                Total Pengunjung
                            </h4>

                            <p class="text-xl font-bold text-[#385683]">
                                {{ $totalCustomer }} orang
                            </p>
                        </div>
                        @foreach ($works as $work)
                            <div class="bg-white border rounded-lg p-4 shadow text-center">

                                <p class="text-sm text-gray-500">
                                    {{ $work->work_type }}
                                </p>

                                <p class="text-xl font-bold text-[#385683]">
                                    {{ $work->customers_count }}
                                </p>

                                <p class="text-xs text-gray-400">
                                    orang
                                </p>

                            </div>
                        @endforeach

                    </div>

                </div>

                {{-- KANAN --}}
                <div>
                    <img src="{{ asset('images/statistik.png') }}" class="w-full rounded-xl shadow" alt="Statistik">
                </div>

            </div>

        </div>
    </section>

    {{-- LAYANAN KAMI --}}
    <section id="layanan" class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Judul --}}
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-[#385683] mb-4">
                    Layanan Kami
                </h2>
                <p class="text-gray-600">
                    Layanan statistik di BPS Kota Jambi mencakup berbagai jenis layanan. Di halaman ini Anda dapat
                    menemukan berbagai layanan yang kami sediakan. #MelayaniDenganHati❤️
                </p>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10">

                {{-- Card Layanan --}}
                @php
                    $layanan = [
                        [
                            'title' => 'Perpustakaan',
                            'image' => asset('template/assets/img/publikasi.png'),
                            'description' =>
                                'Publikasi adalah media penyebarluasan hasil kegiatan statistik yang dilakukan oleh Badan Pusat Statistik (BPS) kepada publik. Publikasi ini memuat berbagai data dan informasi statistik.',
                            'icon' => asset('images/icons/perpustakaan.png'),
                            'standards' => asset('template/assets/img/ModalPerpustakaan.png'),
                            'button_text' => 'Kunjungi Web ->',
                            'url' => 'https://jambikota.bps.go.id/publication.html',
                        ],
                        [
                            'title' => 'Konsultasi Statistik',
                            'image' => asset('images/icons/konsultasi.png'),
                            'description' =>
                                'Konsultasi terkait data, metadata, klasifikasi, dan produk statistik BPS lainnya.',
                            'icon' => asset('images/icons/konsultasi.png'),
                            'standards' => asset('template/assets/img/ModalKonsultasi.png'),
                            'button_text' => 'Kunjungi Web ->',
                            'url' => '#',
                        ],
                        [
                            'title' => 'Rekomendasi Statistik',
                            'image' => asset('images/icons/rekomendasi.png'),
                            'description' => 'Saran yang diberikan oleh BPS terhadap pelaksanaan kegiatan statistik.',
                            'icon' => asset('images/icons/rekomendasi.png'),
                            'standards' => asset('template/assets/img/ModalRekomendasi.png'),
                            'button_text' => 'Kunjungi Web ->',
                            'url' => '#',
                        ],
                        [
                            'title' => 'Layanan Produk',
                            'image' => asset('images/icons/layanan.png'),
                            'description' =>
                                'Informasi layanan produk statistik dan layanan lainnya di BPS Kota Jambi.',
                            'icon' => asset('images/icons/layanan.png'),
                            'standards' => asset('template/assets/img/ModalLayanan.png'),
                            'button_text' => 'Kunjungi Web ->',
                            'url' => '#',
                        ],
                    ];
                @endphp

                @foreach ($layanan as $item)
                    <div
                        class="bg-white border rounded-xl p-8 text-center shadow-sm hover:shadow-lg transition hover:-translate-y-1">
                        <img src="{{ $item['icon'] }}" alt="{{ $item['title'] }}" class="w-20 h-20 mx-auto mb-6">
                        <h3 class="text-lg font-semibold text-[#385683] mb-3">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#serviceModal"
                                data-title="{{ $item['title'] }}" data-image="{{ $item['image'] }}"
                                data-description="{{ $item['description'] }}"
                                data-standards-link="{{ $item['standards'] }}"
                                data-button-text="{{ $item['button_text'] }}" data-url="{{ $item['url'] }}">
                                {{ $item['title'] }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ $item['description'] }}
                        </p>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    {{-- Modal Layanan (satu saja, di luar grid) --}}
     <div class="modal fade"
        id="serviceModal"
        tabindex="-1"
        aria-hidden="true">


        <div class="modal-dialog
                    modal-dialog-centered
                    w-[calc(100%-24px)]
                    max-w-[500px]
                    mx-auto">


            <div class="modal-content
                        border-0
                        rounded-4
                        overflow-hidden
                        bg-[#edf5fc]">


                <div class="modal-body
                            text-center
                            p-4
                            sm:p-5
                            relative">


                    {{-- CLOSE --}}
                    <button type="button"
                        class="btn-close
                               position-absolute
                               top-0
                               end-0
                               m-3"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>


                    {{-- TITLE --}}
                    <h5 id="serviceModalLabel"
                        class="text-[#385683]
                               text-[18px]
                               sm:text-[21px]
                               font-bold
                               leading-tight
                               mt-2
                               mb-5
                               px-6">
                    </h5>


                    {{-- IMAGE --}}
                    <div class="flex
                                items-center
                                justify-center
                                w-[140px]
                                sm:w-[160px]
                                h-[120px]
                                sm:h-[140px]
                                mx-auto
                                mb-4">

                        <img id="modalImage"
                            src=""
                            alt=""
                            class="max-w-full
                                   max-h-full
                                   object-contain">

                    </div>


                    {{-- STANDAR --}}
                    <a href="#"
                        id="standardsLink"

                        data-bs-toggle="modal"
                        data-bs-target="#imageModal"

                        class="block
                               text-[#385683]
                               hover:text-[#29456f]
                               no-underline
                               text-[12px]
                               sm:text-[13px]
                               font-bold
                               mb-3">

                        BACA STANDAR LAYANAN!

                    </a>


                    {{-- DESKRIPSI --}}
                    <p id="modalDescription"
                        class="text-gray-600
                               text-[13px]
                               sm:text-[14px]
                               leading-relaxed
                               text-justify
                               mb-5
                               max-w-[430px]
                               mx-auto">
                    </p>


                    {{-- BUTTON --}}
                    <a href="#"
                        id="modalButton"
                        target="_blank"

                        class="inline-block
                               bg-white
                               border
                               border-[#7fa2c7]
                               hover:bg-[#385683]
                               hover:text-white
                               text-[#385683]
                               no-underline
                               rounded-full
                               px-5
                               py-2
                               text-[12px]
                               sm:text-[13px]
                               font-semibold
                               transition">

                        Kunjungi Web →

                    </a>

                </div>

            </div>

        </div>

    </div>
    </div>

    {{-- Modal Standar Layanan --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImageContent" src="" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>




    {{-- Penilaian --}}
    <section id="penilaian" class="relative bg-cover bg-center"
        style="background-image: url('{{ asset('images/penilaian-bps.png') }}');">

        <div class="absolute inset-0 bg-[#6D7F99]/80"></div>

        {{-- ALPINE WRAPPER --}}
        <div x-data="penilaianModal()" x-cloak class="relative max-w-7xl mx-auto px-6 py-32 text-center text-white">

            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                Berikan Penilaian
            </h1>

            <p class="text-lg md:text-xl text-white/90 mb-12">
                Anda dapat memberikan Masukan dan Saran melalui tombol penilaian dibawah ini. Masukan dan Saran yang
                diberikan akan dilindungi dan kerahasiaan identitas Anda terjaga dengan baik. Berikan penilaian secara
                bebas tanpa paksaan.
            </p>

            {{-- TOMBOL --}}
            <a href="#" @click.prevent="open = true"
                class="inline-flex items-center px-8 py-3 bg-white text-[#385683] font-semibold rounded-lg shadow hover:bg-gray-100 transition">
                Beri Nilai
            </a>

            {{-- MODAL --}}
            <div x-show="open" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

                <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 text-left">
                    <h2 class="text-lg font-semibold mb-4 text-[#385683]">
                        Masukkan No HP
                    </h2>

                    <input x-model="no_hp" type="text" class="w-full border rounded px-3 py-2 mb-3 text-black"
                        placeholder="08123456789">

                    <p x-show="error" class="text-red-600 text-sm mb-3" x-text="error"></p>

                    <div class="flex justify-end gap-2">
                        <button @click="open=false" class="px-4 py-2 bg-gray-300 rounded">
                            Batal
                        </button>

                        <button @click="cek" class="px-4 py-2 bg-blue-600 text-white rounded">
                            <span x-show="!loading">Lanjut</span>
                            <span x-show="loading">Mengecek...</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Hubungi Kami --}}
    <section id="kontak" class="bg-[#3F5D87] text-white pt-20 pb-8">
        <div class="max-w-7xl mx-auto px-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

                {{-- KIRI: Informasi Kontak --}}
                <div>
                    <h2 class="text-2xl font-bold mb-6 uppercase">
                        Hubungi Kami
                    </h2>

                    <p class="mb-6 text-white/90">
                        Badan Pusat Statistik Kota Jambi <br>
                        (BPS–Statistics of Jambi Municipality)
                    </p>

                    <ul class="space-y-4 text-white/90 text-sm">
                        <li class="flex items-start gap-3">
                            <span>📍</span>
                            <span>
                                Jl. Basuki Rachmat, Kota Jambi, Jambi 38128
                            </span>
                        </li>

                        <li class="flex items-center gap-3">
                            <span>📞</span>
                            <span>(0741) 40539</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <span>✉️</span>
                            <span>bps1571@bps.go.id</span>
                        </li>

                        <li class="flex items-start gap-3">
                            <span>🕒</span>
                            <span>
                                Senin–Kamis, 08.00–16.00 WIB <br>
                                Jumat, 08.00–15.00 WIB
                            </span>
                        </li>
                    </ul>

                    {{-- Logo --}}
                    <div class="flex items-center gap-4 mt-8">
                        <img src="{{ asset('images/logo-berakhlak.png') }}" alt="BerAKHLAK" class="h-10">

                        <img src="{{ asset('images/logo-bangga.png') }}" alt="Bangga Melayani Bangsa"
                            class="h-10">
                    </div>
                </div>

            </div>

            {{-- Garis --}}
            <div
                class="border-t border-white/30 mt-12 pt-6 flex flex-col md:flex-row items-center justify-between text-sm">

                <p class="text-white/80">
                    © Badan Pusat Statistik – 2024
                </p>

                {{-- Sosial Media --}}
                <div class="flex items-center gap-4 mt-4 md:mt-0">
                    <a href="#" class="hover:opacity-80">
                        <img src="{{ asset('images/icons/instagram.png') }}" class="w-6 h-6" alt="Instagram">
                    </a>
                    <a href="#" class="hover:opacity-80">
                        <img src="{{ asset('images/icons/facebook.png') }}" class="w-6 h-6" alt="Facebook">
                    </a>
                    <a href="#" class="hover:opacity-80">
                        <img src="{{ asset('images/icons/youtube.png') }}" class="w-6 h-6" alt="Youtube">
                    </a>
                </div>

            </div>
        </div>
    </section>

</body>
{{-- Script Modal Dinamis --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceModal = document.getElementById('serviceModal');
        const modalTitle = document.getElementById('serviceModalLabel');
        const modalImage = document.getElementById('modalImage');
        const modalDescription = document.getElementById('modalDescription');
        const standardsLink = document.getElementById('standardsLink');
        const modalButton = document.getElementById('modalButton');
        const imageModalContent = document.getElementById('modalImageContent');

        serviceModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            modalTitle.textContent = button.getAttribute('data-title');
            modalImage.src = button.getAttribute('data-image');
            modalDescription.textContent = button.getAttribute('data-description');
            standardsLink.onclick = () => {
                imageModalContent.src = button.getAttribute('data-standards-link');
            };
            modalButton.textContent = button.getAttribute('data-button-text');
            modalButton.href = button.getAttribute('data-url');
        });
    });
</script>

<script>
    function penilaianModal() {
        return {
            open: false,
            loading: false,
            error: '',
            no_hp: '',

            cek() {
                this.loading = true;
                this.error = '';

                fetch("{{ route('feedback.cek-nohp') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            no_hp: this.no_hp
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.loading = false;

                        if (data.status === 'ok') {
                            window.location.href = `/feedback/${data.transaction_id}`;
                        } else {
                            this.error = 'No HP tidak ditemukan';
                        }
                    })
                    .catch(() => {
                        this.loading = false;
                        this.error = 'Terjadi kesalahan';
                    });
            }
        }
    }
</script>

</html>
