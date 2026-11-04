<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-center gap-3">
            <h2 class="text-[16px] font-normal text-gray-600">Data Pengunjung</h2>
        </div>
    </x-slot>

    <div class="w-full bg-slate-50 px-4 py-5 sm:px-6 lg:px-8">

        {{-- TOOLBAR --}}
        <div class="mb-5 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

            {{-- FILTER --}}
            <div class="w-full lg:w-auto">
                <button type="button"
                    onclick="toggleFilter()"
                    class="inline-flex min-h-10 w-full items-center justify-center rounded-md bg-[#31579c] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#284a86] focus:outline-none focus:ring-2 focus:ring-[#31579c] focus:ring-offset-2 sm:w-auto">
                    <span class="mr-1">▼</span>
                    Filter Tanggal
                </button>
            </div>

            {{-- DOWNLOAD --}}
            <div class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">

                <a href="{{ route('admin.dashboard.export.pdf', request()->only(['from', 'to'])) }}"
                    class="inline-flex min-h-10 w-full items-center justify-center gap-2 rounded-md bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                    </svg>
                    PDF
                </a>

                <a href="{{ route('admin.dashboard.export.excel', array_merge(request()->only(['from', 'to']), ['format' => 'xlsx'])) }}"
                    class="inline-flex min-h-10 w-full items-center justify-center gap-2 rounded-md bg-[#31579c] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#284a86] focus:outline-none focus:ring-2 focus:ring-[#31579c] focus:ring-offset-2 sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                    </svg>
                    Excel
                </a>

                <a href="{{ route('admin.dashboard.export.excel', array_merge(request()->only(['from', 'to']), ['format' => 'csv'])) }}"
                    class="inline-flex min-h-10 w-full items-center justify-center gap-2 rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 sm:w-auto">
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
            class="mb-5 hidden rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:p-5">

            <div class="flex flex-col sm:flex-row sm:items-end gap-3 sm:gap-4">

                <div class="w-full sm:w-auto">
                    <label for="from"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Dari
                    </label>

                    <input type="date"
                        id="from"
                        name="from"
                        value="{{ request('from') }}"
                        class="h-10 w-full rounded-md border border-slate-300 px-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 sm:w-[180px]">
                </div>

                <div class="w-full sm:w-auto">
                    <label for="to"
                        class="mb-1.5 block text-sm font-medium text-slate-700">
                        Sampai
                    </label>

                    <input type="date"
                        id="to"
                        name="to"
                        value="{{ request('to') }}"
                        class="h-10 w-full rounded-md border border-slate-300 px-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 sm:w-[180px]">
                </div>

                <div class="flex gap-2 w-full sm:w-auto">

                    <button type="submit"
                        class="min-h-10 flex-1 rounded-md bg-[#31579c] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#284a86] focus:outline-none focus:ring-2 focus:ring-[#31579c] focus:ring-offset-2 sm:flex-none">
                        Filter
                    </button>

                    <a href="{{ route('admin.dashboard') }}"
                        class="min-h-10 flex-1 rounded-md bg-slate-100 px-4 py-2 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 sm:flex-none">
                        Reset
                    </a>

                </div>

            </div>
        </form>

        {{-- STATISTIK --}}
        <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="min-h-[92px] rounded-lg border border-slate-200 border-l-4 border-l-[#31579c] bg-white px-4 py-3 shadow-sm sm:px-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-[#31579c]">Total Pengunjung</p>
                <p class="mt-2 text-2xl font-bold leading-none text-slate-800">
                    {{ $totalCustomer }} <span class="text-sm font-medium text-slate-500">orang</span>
                </p>
            </div>

            <div class="min-h-[92px] rounded-lg border border-slate-200 border-l-4 border-l-sky-500 bg-white px-4 py-3 shadow-sm sm:px-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-sky-700">Belum Dilayani</p>
                <p class="mt-2 text-2xl font-bold leading-none text-slate-800">
                    {{ $belumDilayani }} <span class="text-sm font-medium text-slate-500">transaksi</span>
                </p>
            </div>

            <div class="min-h-[92px] rounded-lg border border-slate-200 border-l-4 border-l-amber-500 bg-white px-4 py-3 shadow-sm sm:px-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Sedang Dilayani</p>
                <p class="mt-2 text-2xl font-bold leading-none text-slate-800">{{ $sedangDilayani }} <span class="text-sm font-medium text-slate-500">transaksi</span></p>
            </div>

            <div class="min-h-[92px] rounded-lg border border-slate-200 border-l-4 border-l-emerald-600 bg-white px-4 py-3 shadow-sm sm:px-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Sudah Dilayani</p>
                <p class="mt-2 text-2xl font-bold leading-none text-slate-800">
                    {{ $sudahDilayani }} <span class="text-sm font-medium text-slate-500">transaksi</span>
                </p>
            </div>

        </div>

        {{-- GRAFIK UTAMA --}}
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-[2.12fr_1fr]">

            <div class="min-w-0 space-y-4">

                {{-- PENDIDIKAN --}}
                <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

                    <div class="flex min-h-[68px] flex-col items-center justify-center border-b border-slate-200 bg-[#e1e7f0] px-3 py-3 text-center">
                        <span class="text-xs font-medium text-[#31579c]">Data Pengunjung</span>
                        <span class="mt-0.5 text-sm font-semibold text-slate-800">Berdasarkan Pendidikan</span>
                    </div>

                    <div class="h-[230px] px-3 py-3 sm:h-[250px]">
                        <canvas id="educationChart"></canvas>
                    </div>

                </div>

                {{-- PEKERJAAN --}}
                <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

                    <div class="flex min-h-[68px] flex-col items-center justify-center border-b border-slate-200 bg-[#e1e7f0] px-3 py-3 text-center">
                        <span class="text-xs font-medium text-[#31579c]">Data Pengunjung</span>
                        <span class="mt-0.5 text-sm font-semibold text-slate-800">Berdasarkan Pekerjaan</span>
                    </div>

                    <div class="h-[230px] px-3 py-3 sm:h-[250px]">
                        <canvas id="workChart"></canvas>
                    </div>

                </div>

            </div>

            {{-- MEDIA PELAYANAN --}}
            <div class="min-w-0">

                <div class="h-full overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

                    <div class="flex min-h-[68px] flex-col items-center justify-center border-b border-slate-200 bg-[#e1e7f0] px-3 py-3 text-center">
                        <span class="text-xs font-medium text-[#31579c]">Data Transaksi</span>
                        <span class="mt-0.5 text-sm font-semibold text-slate-800">Berdasarkan Media Pelayanan</span>
                    </div>

                    <div class="h-[485px] px-3 py-3 sm:h-[520px]">
                        <canvas id="mediaChart"></canvas>
                    </div>

                </div>

            </div>

        </div>

        {{-- GRAFIK BAWAH --}}
        <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">

            {{-- JENIS PELAYANAN --}}
            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

                <div class="flex min-h-[68px] flex-col items-center justify-center border-b border-slate-200 bg-[#e1e7f0] px-3 py-3 text-center">
                    <span class="text-xs font-medium text-[#31579c]">Data Transaksi</span>
                    <span class="mt-0.5 text-sm font-semibold text-slate-800">Berdasarkan Jenis Pelayanan</span>
                </div>

                <div class="h-[285px] px-3 py-3 sm:h-[310px]">
                    <canvas id="serviceChart"></canvas>
                </div>

            </div>

            {{-- TUJUAN --}}
            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

                <div class="flex min-h-[68px] flex-col items-center justify-center border-b border-slate-200 bg-[#e1e7f0] px-3 py-3 text-center">
                    <span class="text-xs font-medium text-[#31579c]">Data Transaksi</span>
                    <span class="mt-0.5 text-sm font-semibold text-slate-800">Berdasarkan Tujuan</span>
                </div>

                <div class="h-[285px] px-3 py-3 sm:h-[310px]">
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
                ctx.font = '11px Arial';
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
                            size: 10
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
                            size: 10
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
                                size: 10
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
                                size: 10
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
