<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @include('components.alert')

    <!-- PAGE CONTENT WRAPPER -->
    <div class="py-6">
        <!-- GREETING FOR EVERYONE -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded">
                <div class="p-4 text-primary-70">
                    <p>
                        Selamat datang
                        <span class="font-extrabold text-primary-50">
                            {{ Auth::user()->name ? Auth::user()->name : Auth::user()->username }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-white overflow-hidden shadow-sm rounded">
                <p class="p-4 border-b border-primary-20 text-center uppercase tracking-tighter text-lg font-extrabold text-primary">Nilai Tanggapan Responden Teratas</p>
                <div class="p-4 lg:p-6 border-t border-primary-20">
                    <div class="p-2 lg:p-4 text-primary-70 grid grid-cols-1 rounded-md shadow-inner gap-4 bg-primary-10/25">
                        @if ($highest_score_responses->where('is_done_scoring',1)->count() > 0)
                            @foreach ($highest_score_responses as $response)
                                @if ($response->is_done_scoring)
                                    <div class="w-full bg-white rounded-md shadow p-2 lg:p-4 flex justify-between">
                                        <!-- RANK -->
                                        <div class="rounded-l-md bg-primary text-warning shadow-inner w-10 md:w-16 lg:w-20 flex items-center justify-center">
                                            <p class="text-2xl md:text-3xl lg:text-4xl font-serif font-black">{{ $loop->index + 1 }}</p>
                                        </div>
                                        <!-- DETAILS -->
                                        <div class="w-[calc(100%-2.5rem-4px)] md:w-[calc(100%-4rem-4px)] lg:w-[calc(100%-5rem-4px)] rounded-r-md bg-primary-10/25 shadow-inner text-primary p-2 md:p-3">
                                            <!-- WORK UNIT NAME -->
                                            <p class="text-xs md:text-sm lg:text-base tracking-tighter font-bold uppercase">{{ $response->respondent->work_unit->name }}</p>
                                            <!-- RESPONDENT NAME -->
                                            <p class="text-[.65rem] md:text-xs lg:text-sm tracking-tight my-1">Responden : {{ $response->respondent->name }}</p>
                                            <!-- SCORE -->
                                            <span class="flex justify-start gap-2 items">
                                                <p class="text-xs md:text-sm lg:text-base w-16 md:w-20 lg:w-24 text-center font-extrabold bg-primary text-white py-1 md:py-1.5 lg:py-2 px-2 md:px-2.5 lg:px-3 rounded-sm md:rounded lg:rounded-md">
                                                    {{ round($response->total_score,1) }}
                                                </p>
                                                <span class="w-[calc(100%-4.5rem)] md:w-[calc(100%-5.5rem)] lg:w-[calc(100%-6.5rem)]">
                                                    @if ($response->total_score >= 90)
                                                        <p class="text-xs md:text-sm lg:text-base font-medium py-1 md:py-1.5 lg:py-2 px-2 md:px-2.5 lg:px-3 text-center rounded-sm md:rounded lg:rounded-md text-green-200 bg-green-600">
                                                            INFORMATIF
                                                        </p>
                                                    @elseif ($response->total_score >= 80)
                                                        <p class="text-xs md:text-sm lg:text-base font-medium py-1 md:py-1.5 lg:py-2 px-2 md:px-2.5 lg:px-3 text-center rounded-sm md:rounded lg:rounded-md text-lime-200 bg-lime-600">
                                                            MENUJU INFORMATIF
                                                        </p>
                                                    @elseif ($response->total_score >= 60)
                                                        <p class="text-xs md:text-sm lg:text-base font-medium py-1 md:py-1.5 lg:py-2 px-2 md:px-2.5 lg:px-3 text-center rounded-sm md:rounded lg:rounded-md text-yellow-50 bg-yellow-300">
                                                            CUKUP INFORMATIF
                                                        </p>
                                                    @elseif ($response->total_score >= 40)
                                                        <p class="text-xs md:text-sm lg:text-base font-medium py-1 md:py-1.5 lg:py-2 px-2 md:px-2.5 lg:px-3 text-center rounded-sm md:rounded lg:rounded-md text-orange-100 bg-orange-500">
                                                            KURANG INFORMATIF
                                                        </p>
                                                    @else
                                                        <p class="text-xs md:text-sm lg:text-base font-medium py-1 md:py-1.5 lg:py-2 px-2 md:px-2.5 lg:px-3 text-center rounded-sm md:rounded lg:rounded-md text-red-200 bg-red-600">
                                                            TIDAK INFORMATIF
                                                        </p>
                                                    @endif
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full bg-white rounded-md shadow p-2 lg:p-4 text-xs md:text-sm lg:text-base font-bold">
                                        <div class="bg-gray-100 text-gray-400 shadow-inner p-2 rounded-md">
                                            DATA SELENGKAPNYA AKAN DITAMPILKAN SETELAH LEBIH BANYAK TANGGAPAN PENILAIAN RESPONDEN UNIT KERJA YANG TELAH DIEVALUASI
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="w-full bg-white rounded-md shadow p-2 lg:p-4 text-xs md:text-sm lg:text-base font-bold">
                                <div class="bg-gray-100 text-gray-400 shadow-inner p-2 rounded-md">
                                    DATA SELENGKAPNYA AKAN DITAMPILKAN SETELAH LEBIH BANYAK TANGGAPAN PENILAIAN RESPONDEN UNIT KERJA YANG TELAH DIEVALUASI
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- REGISTRATION CHARTS -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-white overflow-hidden shadow-sm rounded">
                <p class="p-4 border-b border-primary-20 text-center uppercase tracking-tighter text-lg font-extrabold text-primary">Pendaftaran Responden Unit Kerja</p>
                <div class="p-4 lg:p-6 text-primary-70 grid grid-cols-1 lg:grid-cols-2 gap-4 border-t border-primary-20">
                    <div id="registrationDonutChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                    <div id="registrationBarChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                </div>
            </div>
        </div>

        <!-- SUBMISSION CHARTS -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-white overflow-hidden shadow-sm rounded">
                <p class="p-4 border-b border-primary-20 text-center uppercase tracking-tighter text-lg font-extrabold text-primary">Pengisian Penilaian Unit Kerja</p>
                <div class="p-4 lg:p-6 text-primary-70 grid grid-cols-1 lg:grid-cols-2 gap-4 border-t border-primary-20">
                    <div id="submissionDonutChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                    <div id="submissionBarChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                </div>
            </div>
        </div>

        <!-- EVALUATION CHARTS -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-white overflow-hidden shadow-sm rounded">
                <p class="p-4 border-b border-primary-20 text-center uppercase tracking-tighter text-lg font-extrabold text-primary">Evaluasi Penilaian Unit Kerja</p>
                <div class="p-4 lg:p-6 text-primary-70 grid grid-cols-1 lg:grid-cols-2 gap-4 border-t border-primary-20">
                    <div id="evaluationDonutChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                    <div id="evaluationBarChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            function renderApexChart(expected, actual, barChartSelector, donutChartSelector, label, description) {
                let expectedCounts = [ 
                    expected["PELAKSANA"]  ?? 0,
                    expected["DARAT"]      ?? 0,
                    expected["LAUT"]       ?? 0,
                    expected["UDARA"]      ?? 0,
                    expected["KERETA"]     ?? 0,
                    expected["BPSDMP(UP)"] ?? 0,
                ];

                let actualCounts = [ 
                    actual["PELAKSANA"]    ?? 0,
                    actual["DARAT"]        ?? 0,
                    actual["LAUT"]         ?? 0,
                    actual["UDARA"]        ?? 0,
                    actual["KERETA"]       ?? 0,
                    actual["BPSDMP(UP)"]   ?? 0,
                ];

                // Map percentages for Bar Chart
                let actualPercentages = actualCounts.map((value, index) => ((value / expectedCounts[index]) * 100).toFixed(1));

                // Calculate the sums for Donut Chart
                let sumExpectedCounts   = expectedCounts.reduce((a, b) => a + b, 0);
                let sumActualCounts     = actualCounts.reduce((a, b) => a + b, 0);
                let percentage          = (sumActualCounts / sumExpectedCounts) * 100;

                var barChartOptions = {
                    chart: {
                        type    : 'bar',
                        height  : 300,
                        toolbar: {
                            show: false // Disable the action button
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            colors: {
                                ranges: [
                                    { from: 0,   to: 19.99, color: '#dc2626' },
                                    { from: 20,  to: 39.99, color: '#fb923c' },
                                    { from: 40,  to: 59.99, color: '#fddf47' },
                                    { from: 60,  to: 79.99, color: '#acc712' },
                                    { from: 80,  to: 99.99, color: '#16a34a' },
                                    { from: 100, to: 100, color: '#06785f' },
                                ]
                            }
                        }
                    },
                    series: [{
                        name: 'Persentase',
                        data: actualPercentages
                    }],
                    xaxis: {
                        categories: ['PELAKSANA', 'DARAT', 'LAUT', 'UDARA', 'KERETA', 'BPSDMP(UP)'],
                        labels: {
                            formatter: function(value) {
                                return value + "%";
                            }
                        }
                    },
                    yaxis: {
                        min         : 0,
                        max         : 100,
                        tickAmount  : 10,
                    },
                    dataLabels: {
                        enabled: false
                    },
                    tooltip: {
                        custom: function({ series, seriesIndex, dataPointIndex, w }) {
                            var firstValue  = actualCounts[dataPointIndex];
                            var secondValue = expectedCounts[dataPointIndex];
                            return `
                                <div class="p-2 bg-white w-fit shadow shadow-primary-20 rounded-md">
                                    <table class="text-xs text-left text-primary-50">
                                        <tbody>
                                            <tr class="bg-white border-b">
                                                <th class="px-2 py-1">Kategori Unit Kerja</th>
                                                <td class="px-2 py-1">:</td>
                                                <td class="px-2 py-1 font-bold text-primary uppercase">${w.globals.labels[dataPointIndex]}</td>
                                            </tr>
                                            <tr class="bg-white">
                                                <th class="px-2 py-1">${description}</th>
                                                <td class="px-2 py-1">:</td>
                                                <td class="px-2 py-1">${firstValue}/${secondValue} (<span class="font-bold text-primary">${series[seriesIndex][dataPointIndex]}%</span>)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            `;
                        }
                    }
                }

                var barChart = new ApexCharts(document.querySelector(barChartSelector), barChartOptions);
                barChart.render();

                var donutChartOptions = {
                    chart: {
                        type: 'donut',
                        height: 300
                    },
                    series: [percentage, 100 - percentage],
                    labels: [`Sudah ${label}`, `Belum ${label}`],
                    colors: ['#06785f','#A8A8A8'],
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        y: {
                            formatter: function (value) {
                                return value.toFixed(2) + "%";
                            }
                        },
                        custom: function({ series, seriesIndex, dataPointIndex, w }) {
                            if (seriesIndex === 0) {
                                return `
                                    <div class="p-2 bg-white w-fit shadow shadow-primary-20 rounded overflow-hidden">
                                        <table class="text-xs text-left text-primary-50">
                                            <tbody>
                                                <tr class="bg-white">
                                                    <th class="px-2 py-1">Sudah ${label}</th>
                                                    <td class="px-2 py-1">:</td>
                                                    <td class="px-2 py-1">${sumActualCounts}/${sumExpectedCounts} (<span class="font-bold text-primary">${percentage.toFixed(2)}%</span>)</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                `;
                            } else {
                                return `
                                    <div class="p-2 bg-white w-fit shadow shadow-primary-20 rounded overflow-hidden">
                                        <table class="text-xs text-left text-primary-50">
                                            <tbody>
                                                <tr class="bg-white">
                                                    <th class="px-2 py-1">Belum ${label}</th>
                                                    <td class="px-2 py-1">:</td>
                                                    <td class="px-2 py-1">${sumExpectedCounts - sumActualCounts}/${sumExpectedCounts} (<span class="font-bold text-primary">${(100 - percentage).toFixed(2)}%</span>)</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                `;
                            }
                        }
                    }
                };
                var donutChart = new ApexCharts(document.querySelector(donutChartSelector), donutChartOptions);
                donutChart.render();
            }
            $(document).ready(function () {
                let data = @json($processed_data);

                let empty_arr = {
                    "PELAKSANA"  : 0,
                    "DARAT"      : 0,
                    "LAUT"       : 0,
                    "UDARA"      : 0,
                    "KERETA"     : 0,
                    "BPSDMP(UP)" : 0,
                };

                let original_arr    = data.units_count_by_category;
                let registered_arr  = data.registered_count > 0 ? data.registered_count_by_category : empty_arr;
                let submitted_arr   = data.submitted_count  > 0 ? data.submitted_count_by_category : empty_arr;
                let scored_arr      = data.scored_count     > 0 ? data.scored_count_by_category : empty_arr;

                renderApexChart(original_arr,registered_arr,"#registrationBarChart","#registrationDonutChart", "Mendaftar", "Responden Mendaftar");
                renderApexChart(registered_arr,submitted_arr,"#submissionBarChart","#submissionDonutChart", "Mengisi", "Responden Mengisi");
                renderApexChart(submitted_arr,scored_arr,"#evaluationBarChart","#evaluationDonutChart", "Dievaluasi", "Tanggapan Dievaluasi");
            });
        </script>
    </x-slot>

</x-app-layout>