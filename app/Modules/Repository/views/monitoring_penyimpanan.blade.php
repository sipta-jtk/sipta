@extends('adminlte::page')

@section('title', 'Monitoring Penyimpanan')

@section('content_header')
    <h1 class="mb-3">Monitoring Penyimpanan</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [['url' => url('/'), 'label' => 'Beranda'], ['url' => '', 'label' => 'Monitoring Penyimpanan']],
        ])
        @endcomponent
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between px-3 pt-3">
                <button class="btn btn-primary btn-md" id="filterButton" type="button" style="height: 40px;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <div class="form-group text-secondary">
                    <label><i class="fas fa-database mr-1"></i> Kategori</label>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari Kategori...">
                </div>
            </div>

            <div class="collapse" id="filterMenu">
                <div class="card mx-3 mt-3">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <h5 class="card-title m-0">
                            <i class="fas fa-filter mr-1"></i>Filter Data
                        </h5>
                        <button id="closeFilter" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group text-secondary mb-2">
                                    <label class="mb-1"><i class="fas fa-folder mr-1"></i> Kategori</label>
                                    <select id="kategoriFilter" class="form-control form-control-sm">
                                        <option value="">Semua Kategori</option>
                                        <option value="seminar1">Seminar 1</option>
                                        <option value="seminar2">Seminar 2</option>
                                        <option value="seminar3">Seminar 3</option>
                                        <option value="sidang">Sidang Akhir</option>
                                        <option value="yudisium">Yudisium</option>
                                        <option value="plagiarisme">Plagiarisme</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group text-secondary mb-2">
                                    <label class="mb-1"><i class="fas fa-file-alt mr-1"></i> Subkategori</label>
                                    <select id="subkategoriFilter" class="form-control form-control-sm">
                                        <option value="">Semua Subkategori</option>
                                        <option value="Laporan">Laporan</option>
                                        <option value="FTA">FTA</option>
                                        <option value="PowerPoint">PowerPoint</option>
                                        <option value="SRS">SRS</option>
                                        <option value="SDD">SDD</option>
                                        <option value="Poster">Poster</option>
                                        <option value="Surat Bebas Masalah">Surat Bebas Masalah</option>
                                        <option value="Hasil TOEIC">Hasil TOEIC</option>
                                        <option value="Surat Keaktifan">Surat Keaktifan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-group text-secondary mb-2 d-flex">
                                    <button id="applyFilter" class="btn btn-primary btn-sm mr-1">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button id="resetFilter" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Chart dan Summary -->
            <div class="row mt-4 mb-4">
                <div class="col-md-6 mx-auto text-center">
                    <h4 class="mb-3">Distribusi Penyimpanan Berdasarkan Subkategori</h4>
                    <div style="position: relative; height: 350px; width: 100%;">
                        <canvas id="storage-pie-chart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mb-4">
                <div class="col-md-8">
                    <div class="progress" style="height: 25px;">
                        @php
                            $usedPercentage = ($totalPenyimpanan / $kapasitasMaksimum) * 100;
                            $usedPercentage = min($usedPercentage, 100); // Cap at 100%
                            $usedGB = $totalPenyimpanan / (1024 * 1024);
                            $totalGB = $kapasitasMaksimum / (1024 * 1024);

                            // Change progress bar color based on usage percentage
                            $progressClass = 'bg-success';
                            if ($usedPercentage > 75) {
                                $progressClass = 'bg-danger';
                            } elseif ($usedPercentage > 50) {
                                $progressClass = 'bg-warning';
                            }
                        @endphp
                        <div class="progress-bar {{ $progressClass }}" role="progressbar"
                            style="width: {{ $usedPercentage }}%;" aria-valuenow="{{ $usedPercentage }}" aria-valuemin="0"
                            aria-valuemax="100">
                            {{ number_format($usedPercentage, 1) }}%
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <strong>{{ number_format($usedGB, 2) }} GB dari {{ number_format($totalGB, 0) }} GB
                            digunakan</strong>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table id="penyimpananTable" class="table table-striped table-bordered" width="100%">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Subkategori</th>
                            <th>Penggunaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penyimpanan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->kategori }}</td>
                                <td>{{ $item->subkategori ? $item->subkategori->nama_subkategori : 'No Subkategori' }}</td>
                                <td>{{ number_format($item->total_ukuran / 1024, 2) }} MB</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <style>
        .chart-container {
            position: relative;
            margin: auto;
            height: 350px;
            width: 100%;
            max-width: 500px;
        }

        canvas#storage-pie-chart {
            max-height: 350px;
        }
    </style>
@stop

@section('js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#penyimpananTable').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari total _MAX_ data)",
                    paginate: {
                        first: "<<",
                        last: ">>",
                        next: ">",
                        previous: "<"
                    }
                }
            });

            // Existing search input functionality
            $('#searchInput').on('keyup', function() {
                table.column(1).search(this.value).draw();
            });

            // Debug console log to check data
            console.log("Subkategori data:", @json($penyimpananBySubkategori));

            // Data untuk pie chart dari controller
            const subkategoriData = @json($penyimpananBySubkategori);

            // Variable to store pie chart instance
            let myPieChart;

            // Function to initialize the chart with filtered data
            function initializeChart(filteredData) {
                // Persiapan data untuk chart
                const labels = [];
                const chartData = [];
                const backgroundColors = [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                    '#5a5c69', '#6f42c1', '#fd7e14', '#20c997', '#6c757d'
                ];

                // Format data untuk chart
                filteredData.forEach(function(item, index) {
                    if (item.subkategori) {
                        const subkategoriName = item.subkategori.nama_subkategori;
                        labels.push(subkategoriName);
                        chartData.push(parseFloat(item.total_ukuran));
                        console.log("Added to chart:", subkategoriName, parseFloat(item.total_ukuran));
                    }
                });

                // Sort data berdasarkan ukuran file (opsional)
                const sortedIndices = chartData.map((value, index) => ({
                        value,
                        index
                    }))
                    .sort((a, b) => b.value - a.value)
                    .map(data => data.index);

                const sortedLabels = sortedIndices.map(index => labels[index]);
                const sortedData = sortedIndices.map(index => chartData[index]);
                const sortedColors = sortedIndices.map(index => backgroundColors[index % backgroundColors.length]);

                console.log("Chart labels:", sortedLabels);
                console.log("Chart data:", sortedData);

                // Destroy existing chart if it exists
                if (myPieChart) {
                    myPieChart.destroy();
                }

                // Buat chart
                const ctx = document.getElementById('storage-pie-chart');
                if (ctx) {
                    console.log("Canvas element found");
                    // Register ChartDataLabels plugin
                    Chart.register(ChartDataLabels);
                    myPieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: sortedLabels,
                            datasets: [{
                                data: sortedData,
                                backgroundColor: sortedColors,
                                hoverBackgroundColor: sortedColors,
                                hoverBorderColor: "rgba(234, 236, 244, 1)",
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    left: 10,
                                    right: 25,
                                    top: 0,
                                    bottom: 0
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'right',
                                    display: true,
                                    labels: {
                                        color: '#333',
                                        usePointStyle: true,
                                        boxWidth: 10,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const mbValue = (value / 1024).toFixed(2);
                                            // Hitung persentase
                                            const total = context.chart.data.datasets[0].data.reduce((a,
                                                b) => a + b, 0);
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return label + ': ' + mbValue + ' MB (' + percentage + '%)';
                                        }
                                    }
                                },
                                datalabels: {
                                    formatter: (value, ctx) => {
                                        const total = ctx.chart.data.datasets[0].data.reduce((a, b) =>
                                            a +
                                            b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return percentage + '%';
                                    },
                                    color: '#fff',
                                    font: {
                                        weight: 'bold',
                                        size: 11
                                    }
                                }
                            }
                        }
                    });
                } else {
                    console.error("Canvas element not found");
                }
            }

            // Function to apply filters
            function applyFilters() {
                const kategoriValue = $('#kategoriFilter').val();
                const subkategoriValue = $('#subkategoriFilter').val();

                console.log("Filter values:", kategoriValue, subkategoriValue);

                // Filter table data
                table
                    .column(1).search(kategoriValue, true, false)
                    .column(2).search(subkategoriValue, true, false)
                    .draw();

                // Filter chart data
                let filteredChartData = [...subkategoriData];

                // Filter by kategori (using original data from server)
                if (kategoriValue) {
                    // For chart data we need to fetch kategori-filtered data since chartData only has subkategori
                    // Use AJAX to get updated data based on kategori
                    $.ajax({
                        url: "{{ url('/repository/koor-ta/get-filtered-data') }}",
                        type: "GET",
                        data: {
                            kategori: kategoriValue,
                            subkategori: subkategoriValue
                        },
                        success: function(response) {
                            console.log("Filtered data from server:", response);
                            initializeChart(response.data);
                        },
                        error: function(error) {
                            console.error("Error fetching filtered data:", error);
                        }
                    });
                }
                // If only subkategori is filtered, we can do it client-side
                else if (subkategoriValue) {
                    filteredChartData = filteredChartData.filter(item =>
                        item.subkategori && item.subkategori.nama_subkategori === subkategoriValue
                    );
                    initializeChart(filteredChartData);
                }
                // No filters, use all data
                else {
                    initializeChart(filteredChartData);
                }
            }

            // Apply Filter button click
            $('#applyFilter').on('click', function() {
                applyFilters();
            });

            // Reset Filter button click
            $('#resetFilter').on('click', function() {
                $('#kategoriFilter').val('');
                $('#subkategoriFilter').val('');

                // Reset DataTable search
                table
                    .column(1).search('')
                    .column(2).search('')
                    .draw();

                // Reset chart to original data
                initializeChart(subkategoriData);
            });

            // Initialize chart with all data on page load
            initializeChart(subkategoriData);

            // Toggle filter menu with the filter button
            $('#filterButton').on('click', function() {
                $('#filterMenu').collapse('toggle');
            });

            // Close filter with close button
            $('#closeFilter').on('click', function() {
                $('#filterMenu').collapse('hide');
            });
        });
    </script>
@stop
