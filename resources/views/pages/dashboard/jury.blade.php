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

        @if (count($work_units) > 0)
            <!-- REGISTRATION CHARTS -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                <div class="bg-white overflow-hidden shadow-sm rounded">
                    <p class="p-4 border-b border-primary-20 text-center uppercase tracking-tighter text-lg font-extrabold text-primary">
                        Pendaftaran Responden Unit Kerja<br>
                        <span class="text-xs text-gray-500">(YANG DITUGASKAN PADA ANDA UNTUK DINILAI)</span>
                    </p>
                    <div class="p-4 lg:p-6 text-primary-70 grid grid-cols-1 lg:grid-cols-2 gap-4 border-t border-primary-20">
                        <div id="registrationDonutChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                        <div id="registrationBarChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                    </div>
                </div>
            </div>

            <!-- SUBMISSION CHARTS -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                <div class="bg-white overflow-hidden shadow-sm rounded">
                    <p class="p-4 border-b border-primary-20 text-center uppercase tracking-tighter text-lg font-extrabold text-primary">
                        Pengisian Penilaian Unit Kerja<br>
                        <span class="text-xs text-gray-500">(YANG DITUGASKAN PADA ANDA UNTUK DINILAI)</span>
                    </p>
                    <div class="p-4 lg:p-6 text-primary-70 grid grid-cols-1 lg:grid-cols-2 gap-4 border-t border-primary-20">
                        <div id="submissionDonutChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                        <div id="submissionBarChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                    </div>
                </div>
            </div>

            <!-- EVALUATION CHARTS -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                <div class="bg-white overflow-hidden shadow-sm rounded">
                    <p class="p-4 border-b border-primary-20 text-center uppercase tracking-tighter text-lg font-extrabold text-primary">
                        Evaluasi Penilaian Unit Kerja<br>
                        <span class="text-xs text-gray-500">(YANG DITUGASKAN PADA ANDA UNTUK DINILAI)</span>
                    </p>
                    <div class="p-4 lg:p-6 text-primary-70 grid grid-cols-1 lg:grid-cols-2 gap-4 border-t border-primary-20">
                        <div id="evaluationDonutChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                        <div id="evaluationBarChart" class="bg-primary-10/25 shadow-inner rounded p-2"></div>
                    </div>
                </div>
            </div>
        @else
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                <div class="bg-white overflow-hidden shadow-sm rounded-sm">
                    <div class="p-4 text-primary-70">
                        <div class="w-full bg-primary-10 text-primary-100 p-10 py-14 rounded-sm shadow-inner">
                            <p class="text-lg lg:text-xl ">
                                Saat ini Anda belum ditugaskan untuk mengevaluasi penilaian yang diisi oleh responden Unit Kerja manapun.<br>
                                Informasi akan muncul di halaman ini ketika Administrator telah menugaskan Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if (count($work_units) > 0)
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
                    renderApexChart(submitted_arr,scored_arr,"#evaluationBarChart","#evaluationDonutChart", "Dinilai", "Tanggapan Dinilai");
                });
            </script>
        </x-slot>
    @endif

</x-app-layout>