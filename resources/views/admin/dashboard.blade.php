<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-center gap-3">
            <h2 class="text-[16px] font-normal text-gray-600">Data Pengunjung</h2>
        </div>
    </x-slot>

    <div class="w-full bg-[#f3f3f3] px-4 sm:px-5 py-3">

        {{-- TOOLBAR --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-4">

            {{-- FILTER --}}
            <div class="w-full lg:w-auto">
                <button type="button"
                    onclick="toggleFilter()"
                    class="w-full sm:w-auto bg-blue-400 hover:bg-blue-500 text-white text-[14px] px-4 py-2 rounded-sm transition">
                    <span class="mr-1">▼</span>
                    Filter Tanggal
                </button>
            </div>

            {{-- DOWNLOAD --}}
            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">

                <a href="{{ route('admin.dashboard.export.pdf', request()->only(['from', 'to'])) }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white text-[14px] px-4 py-2 rounded-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                    </svg>
                    PDF
                </a>

                <a href="{{ route('admin.dashboard.export.excel', array_merge(request()->only(['from', 'to']), ['format' => 'xlsx'])) }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-[14px] px-4 py-2 rounded-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                    </svg>
                    Excel
                </a>

                <a href="{{ route('admin.dashboard.export.excel', array_merge(request()->only(['from', 'to']), ['format' => 'csv'])) }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white text-[14px] px-4 py-2 rounded-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                    </svg>
                    CSV
                </a>

            </div>
        </div>

        {{-- FORM FILTER --}}
        <form id="filterTanggal"
            method="GET"
            action="{{ route('admin.dashboard') }}"
            class="hidden bg-white border border-gray-300 rounded-sm p-3 sm:p-4 mb-4">

            <div class="flex flex-col sm:flex-row sm:items-end gap-3 sm:gap-4">

                <div class="w-full sm:w-auto">
                    <label for="from"
                        class="block text-[14px] text-gray-600 mb-1">
                        Dari
                    </label>

                    <input type="date"
                        id="from"
                        name="from"
                        value="{{ request('from') }}"
                        class="w-full sm:w-[170px] h-[36px] border border-gray-400 px-3 py-1 text-[14px] rounded-sm focus:outline-none focus:border-blue-500">
                </div>

                <div class="w-full sm:w-auto">
                    <label for="to"
                        class="block text-[14px] text-gray-600 mb-1">
                        Sampai
                    </label>

                    <input type="date"
                        id="to"
                        name="to"
                        value="{{ request('to') }}"
                        class="w-full sm:w-[170px] h-[36px] border border-gray-400 px-3 py-1 text-[14px] rounded-sm focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex gap-2 w-full sm:w-auto">

                    <button type="submit"
                        class="flex-1 sm:flex-none bg-blue-500 hover:bg-blue-600 text-white text-[14px] px-4 py-2 rounded-sm">
                        Filter
                    </button>

                    <a href="{{ route('admin.dashboard') }}"
                        class="flex-1 sm:flex-none text-center bg-gray-300 hover:bg-gray-400 text-gray-700 text-[14px] px-4 py-2 rounded-sm">
                        Reset
                    </a>

                </div>

            </div>
        </form>

        {{-- STATISTIK --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

            <div class="h-[68px] bg-white border border-gray-400 border-l-[4px] border-l-blue-600 rounded-sm px-4 py-2">
                <p class="text-[14px] text-blue-600">Total Pengunjung</p>
                <p class="mt-1 text-[15px] text-gray-800">
                    <strong>{{ $totalCustomer }}</strong> orang
                </p>
            </div>

            <div class="h-[68px] bg-white border border-gray-400 border-l-[4px] border-l-blue-600 rounded-sm px-4 py-2">
                <p class="text-[14px] text-blue-600">Belum Dilayani</p>
                <p class="mt-1 text-[15px] text-gray-800">
                    <strong>{{ $belumDilayani }}</strong> transaksi
                </p>
            </div>

            <div class="h-[68px] bg-white border border-gray-400 border-l-[4px] border-l-blue-600 rounded-sm px-4 py-2">
                <p class="text-[14px] text-blue-600">Sudah Dilayani</p>
                <p class="mt-1 text-[15px] text-gray-800">
                    <strong>{{ $sudahDilayani }}</strong> transaksi
                </p>
            </div>

        </div>

        {{-- GRAFIK UTAMA --}}
        <div class="grid grid-cols-1 lg:grid-cols-[2.12fr_1fr] gap-4">

            <div class="min-w-0 space-y-4">

                {{-- PENDIDIKAN --}}
                <div class="bg-white border border-gray-300 rounded-sm shadow-sm overflow-hidden">

                    <div class="h-[56px] flex flex-col items-center justify-center bg-[#e1e7f0] border-b border-gray-300">
                        <span class="text-[13px] text-[#31579c]">Data Pengunjung :</span>
                        <span class="text-[13px] text-[#31579c]">Berdasarkan Pendidikan</span>
                    </div>

                    <div class="h-[180px] px-2 py-2">
                        <canvas id="educationChart"></canvas>
                    </div>

                </div>

                {{-- PEKERJAAN --}}
                <div class="bg-white border border-gray-300 rounded-sm shadow-sm overflow-hidden">

                    <div class="h-[56px] flex flex-col items-center justify-center bg-[#e1e7f0] border-b border-gray-300">
                        <span class="text-[13px] text-[#31579c]">Data Pengunjung :</span>
                        <span class="text-[13px] text-[#31579c]">Berdasarkan Pekerjaan</span>
                    </div>

                    <div class="h-[180px] px-2 py-2">
                        <canvas id="workChart"></canvas>
                    </div>

                </div>

            </div>

            {{-- MEDIA PELAYANAN --}}
            <div class="min-w-0">

                <div class="h-full bg-white border border-gray-300 rounded-sm shadow-sm overflow-hidden">

                    <div class="h-[56px] flex flex-col items-center justify-center bg-[#e1e7f0] border-b border-gray-300">
                        <span class="text-[13px] text-[#31579c]">Data Transaksi :</span>
                        <span class="text-[13px] text-[#31579c]">Berdasarkan Media Pelayanan</span>
                    </div>

                    <div class="h-[380px] px-2 py-2">
                        <canvas id="mediaChart"></canvas>
                    </div>

                </div>

            </div>

        </div>

        {{-- GRAFIK BAWAH --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">

            {{-- JENIS PELAYANAN --}}
            <div class="bg-white border border-gray-300 rounded-sm shadow-sm overflow-hidden">

                <div class="h-[56px] flex flex-col items-center justify-center bg-[#e1e7f0] border-b border-gray-300">
                    <span class="text-[13px] text-[#31579c]">Data Transaksi :</span>
                    <span class="text-[13px] text-[#31579c]">Berdasarkan Jenis Pelayanan</span>
                </div>

                <div class="h-[250px] px-3 py-2">
                    <canvas id="serviceChart"></canvas>
                </div>

            </div>

            {{-- TUJUAN --}}
            <div class="bg-white border border-gray-300 rounded-sm shadow-sm overflow-hidden">

                <div class="h-[56px] flex flex-col items-center justify-center bg-[#e1e7f0] border-b border-gray-300">
                    <span class="text-[13px] text-[#31579c]">Data Transaksi :</span>
                    <span class="text-[13px] text-[#31579c]">Berdasarkan Tujuan</span>
                </div>

                <div class="h-[250px] px-3 py-2">
                    <canvas id="purposeChart"></canvas>
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function toggleFilter() {
            document.getElementById('filterTanggal').classList.toggle('hidden');
        }

        const chartColors = [
            '#4F81BD',
            '#C0504D',
            '#9BBB59',
            '#26B9A9',
            '#8064A2',
            '#4BACC6'
        ];

        const doughnutLabelPlugin = {
            id: 'doughnutLabelPlugin',

            afterDraw(chart) {
                const ctx = chart.ctx;
                const dataset = chart.data.datasets[0];
                const meta = chart.getDatasetMeta(0);

                if (!dataset || !meta.data.length) return;

                const total = dataset.data.reduce(
                    (sum, value) => sum + Number(value || 0),
                    0
                );

                if (!total) return;

                ctx.save();
                ctx.font = '10px Arial';
                ctx.textBaseline = 'middle';

                meta.data.forEach((arc, index) => {
                    const value = Number(dataset.data[index] || 0);
                    const label = chart.data.labels[index] || '';

                    if (!value) return;

                    const percentage = ((value / total) * 100).toFixed(2);
                    const angle = (arc.startAngle + arc.endAngle) / 2;
                    const cos = Math.cos(angle);
                    const sin = Math.sin(angle);

                    const startX = arc.x + cos * arc.outerRadius;
                    const startY = arc.y + sin * arc.outerRadius;
                    const lineX = arc.x + cos * (arc.outerRadius + 15);
                    const lineY = arc.y + sin * (arc.outerRadius + 15);

                    const isRight = cos >= 0;
                    const endX = lineX + (isRight ? 38 : -38);

                    ctx.strokeStyle = chartColors[index % chartColors.length];
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(startX, startY);
                    ctx.lineTo(lineX, lineY);
                    ctx.lineTo(endX, lineY);
                    ctx.stroke();

                    ctx.fillStyle = '#555';
                    ctx.textAlign = isRight ? 'left' : 'right';
                    ctx.fillText(
                        `${label} - ${percentage}%`,
                        endX + (isRight ? 4 : -4),
                        lineY
                    );
                });

                ctx.restore();
            }
        };

        const barOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#444',
                        font: {
                            size: 9
                        },
                        maxRotation: 0,
                        minRotation: 0
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#444',
                        font: {
                            size: 9
                        },
                        precision: 0
                    },
                    grid: {
                        color: '#b8b8b8'
                    }
                }
            }
        };

        new Chart(document.getElementById('educationChart'), {
            type: 'bar',
            data: {
                labels: @json($chartLabelsEducation),
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: @json($chartDataEducation),
                    backgroundColor: chartColors,
                    borderWidth: 0,
                    barPercentage: 0.72,
                    categoryPercentage: 0.8
                }]
            },
            options: barOptions
        });

        new Chart(document.getElementById('workChart'), {
            type: 'bar',
            data: {
                labels: @json($chartLabelsWork),
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: @json($chartDataWork),
                    backgroundColor: chartColors,
                    borderWidth: 0,
                    barPercentage: 0.72,
                    categoryPercentage: 0.8
                }]
            },
            options: barOptions
        });

        new Chart(document.getElementById('mediaChart'), {
            type: 'bar',
            data: {
                labels: @json($chartLabelsMedia),
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: @json($chartDataMedia),
                    backgroundColor: chartColors,
                    borderWidth: 0,
                    barPercentage: 0.55,
                    categoryPercentage: 0.65
                }]
            },
            options: {
                ...barOptions,
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#444',
                            font: {
                                size: 9
                            },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#444',
                            font: {
                                size: 9
                            },
                            precision: 0
                        },
                        grid: {
                            color: '#b8b8b8'
                        }
                    }
                }
            }
        });

        const doughnutOptions = cutout => ({
            responsive: true,
            maintainAspectRatio: false,
            cutout,
            layout: {
                padding: {
                    top: 30,
                    right: 80,
                    bottom: 25,
                    left: 80
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label(context) {
                            const data = context.dataset.data;

                            const total = data.reduce(
                                (sum, value) => sum + Number(value || 0),
                                0
                            );

                            const value = Number(context.raw || 0);

                            const percentage = total
                                ? ((value / total) * 100).toFixed(2)
                                : 0;

                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('serviceChart'), {
            type: 'doughnut',
            data: {
                labels: @json($chartLabelsService),
                datasets: [{
                    data: @json($chartDataService),
                    backgroundColor: chartColors,
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: doughnutOptions('48%'),
            plugins: [doughnutLabelPlugin]
        });

        new Chart(document.getElementById('purposeChart'), {
            type: 'doughnut',
            data: {
                labels: @json($chartLabelsPurpose),
                datasets: [{
                    data: @json($chartDataPurpose),
                    backgroundColor: chartColors,
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: doughnutOptions('0%'),
            plugins: [doughnutLabelPlugin]
        });
    </script>

</x-app-layout>