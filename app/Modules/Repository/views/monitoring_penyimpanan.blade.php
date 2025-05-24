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
                <button class="btn btn-primary btn-md" type="button" data-toggle="collapse" data-target="#filterMenu">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <div>
                    <a href="{{ url('/log-aktivitas') }}" class="btn btn-secondary">Log Aktivitas</a>
                    <a href="{{ url('/repository') }}" class="btn btn-secondary">Akses Dokumen</a>
                </div>
            </div>

            <div class="collapse" id="filterMenu">
                <div class="card mx-3 mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-filter mr-2"></i>Filter Data
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group text-secondary">
                                    <label><i class="fas fa-database mr-1"></i> Kategori</label>
                                    <input type="text" id="searchInput" class="form-control"
                                        placeholder="Cari Kategori...">
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

                <div class="mt-4">
                    <p>
                        Penyimpanan Tersedia:
                        {{ number_format($kapasitasMaksimum / 1024 / 1024 - $totalPenyimpanan / 1024 / 1024, 2) }} GB
                    </p>
                    <p>
                        Penyimpanan Terpakai: {{ number_format($totalPenyimpanan / 1024, 2) }} MB
                    </p>
                </div>
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
    <script>
        $(document).ready(function() {
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

            $('#searchInput').on('keyup', function() {
                table.column(1).search(this.value).draw();
            });

            // Debug console log to check data
            console.log("Subkategori data:", @json($penyimpananBySubkategori));

            // Data untuk pie chart dari controller
            const subkategoriData = @json($penyimpananBySubkategori);

            // Persiapan data untuk chart
            const labels = [];
            const chartData = [];
            const backgroundColors = [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                '#5a5c69', '#6f42c1', '#fd7e14', '#20c997', '#6c757d'
            ];

            // Format data untuk chart
            subkategoriData.forEach(function(item, index) {
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

            // Buat chart
            const ctx = document.getElementById('storage-pie-chart');
            if (ctx) {
                console.log("Canvas element found");
                const myPieChart = new Chart(ctx, {
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
                                        return label + ': ' + mbValue + ' MB';
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                console.error("Canvas element not found");
            }
        });
    </script>
@stop
