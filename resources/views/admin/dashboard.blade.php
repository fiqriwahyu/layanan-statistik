<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- SELAMAT DATANG --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <h1 class="text-2xl font-bold text-gray-800 mb-2">
                        Dashboard Layanan Statistik
                    </h1>

                    <p class="text-gray-700">
                        Selamat datang,
                        <strong>
                            {{ auth()->user()->name }}
                        </strong>.
                    </p>

                    <p class="mt-2 text-gray-600">

                        Role:

                        <strong>
                            {{ ucfirst(auth()->user()->role) }}
                        </strong>

                    </p>

                </div>

            </div>


            {{-- STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">


                {{-- TOTAL PENGUNJUNG --}}
                <div class="bg-white rounded-lg shadow p-6">

                    <p class="text-sm text-gray-500">
                        Total Pengunjung
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $totalCustomer }}
                    </p>

                </div>


                {{-- TOTAL TRANSAKSI --}}
                <div class="bg-white rounded-lg shadow p-6">

                    <p class="text-sm text-gray-500">
                        Total Transaksi
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $totalTransactions }}
                    </p>

                </div>


                {{-- BELUM DILAYANI --}}
                <div class="bg-white rounded-lg shadow p-6">

                    <p class="text-sm text-gray-500">
                        Belum Dilayani
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $belumDilayani }}
                    </p>

                </div>


                {{-- SUDAH DILAYANI --}}
                <div class="bg-white rounded-lg shadow p-6">

                    <p class="text-sm text-gray-500">
                        Sudah Dilayani
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $sudahDilayani }}
                    </p>

                </div>


            </div>


            {{-- DATA CHART --}}
            <div class="mt-6 bg-white rounded-lg shadow p-6">

                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Data Pendidikan
                </h2>

                <canvas id="educationChart"></canvas>

            </div>


            <div class="mt-6 bg-white rounded-lg shadow p-6">

                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Data Pekerjaan
                </h2>

                <canvas id="workChart"></canvas>

            </div>


            <div class="mt-6 bg-white rounded-lg shadow p-6">

                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Media Pelayanan
                </h2>

                <canvas id="mediaChart"></canvas>

            </div>


            <div class="mt-6 bg-white rounded-lg shadow p-6">

                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Jenis Pelayanan
                </h2>

                <canvas id="serviceChart"></canvas>

            </div>


            <div class="mt-6 bg-white rounded-lg shadow p-6">

                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Tujuan
                </h2>

                <canvas id="purposeChart"></canvas>

            </div>


        </div>

    </div>


    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>

        /*
        |--------------------------------------------------------------------------
        | PENDIDIKAN
        |--------------------------------------------------------------------------
        */

        new Chart(
            document.getElementById('educationChart'),
            {
                type: 'bar',

                data: {
                    labels: @json($chartLabelsEducation),

                    datasets: [{
                        label: 'Jumlah Pengunjung',

                        data: @json($chartDataEducation)
                    }]
                },

                options: {
                    responsive: true,

                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | PEKERJAAN
        |--------------------------------------------------------------------------
        */

        new Chart(
            document.getElementById('workChart'),
            {
                type: 'bar',

                data: {
                    labels: @json($chartLabelsWork),

                    datasets: [{
                        label: 'Jumlah Pengunjung',

                        data: @json($chartDataWork)
                    }]
                },

                options: {
                    responsive: true,

                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | MEDIA
        |--------------------------------------------------------------------------
        */

        new Chart(
            document.getElementById('mediaChart'),
            {
                type: 'bar',

                data: {
                    labels: @json($chartLabelsMedia),

                    datasets: [{
                        label: 'Jumlah Transaksi',

                        data: @json($chartDataMedia)
                    }]
                },

                options: {
                    responsive: true,

                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | JENIS PELAYANAN
        |--------------------------------------------------------------------------
        */

        new Chart(
            document.getElementById('serviceChart'),
            {
                type: 'bar',

                data: {
                    labels: @json($chartLabelsService),

                    datasets: [{
                        label: 'Jumlah Transaksi',

                        data: @json($chartDataService)
                    }]
                },

                options: {
                    responsive: true,

                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | TUJUAN
        |--------------------------------------------------------------------------
        */

        new Chart(
            document.getElementById('purposeChart'),
            {
                type: 'bar',

                data: {
                    labels: @json($chartLabelsPurpose),

                    datasets: [{
                        label: 'Jumlah Transaksi',

                        data: @json($chartDataPurpose)
                    }]
                },

                options: {
                    responsive: true,

                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );

    </script>

</x-app-layout>