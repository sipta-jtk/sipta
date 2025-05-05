@extends('adminlte::page')

@section('title', 'Monitoring Penyimpanan')

@section('content_header')
    <h1 class="mb-3">Monitoring Penyimpanan</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Monitoring Penyimpanan']
            ]
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
                                    <input type="text" id="searchInput" class="form-control" placeholder="Cari Kategori...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <!-- <div class="my-4">
                <canvas id="storageChart" style="width: 5px; height: 5px;"></canvas> 
            </div> -->

            <div class="mt-4">
                <p>
                    Penyimpanan Tersedia: {{ number_format(1 - $penyimpanan->sum('total_ukuran') / (1024 * 1024), 2) }} GB
                </p>
                <p>
                    Penyimpanan Terpakai: {{ number_format($penyimpanan->sum('total_ukuran') / 1024, 2) }} MB
                </p>
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
                        @foreach($penyimpanan as $index => $item)
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
@stop

@section('js')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    $('#searchInput').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });

    // Data for Pie Chart
    var totalUsed = {{ number_format($penyimpanan->sum('total_ukuran') / 1024, 2) }};
    var totalAvailable = {{ number_format(1 - $penyimpanan->sum('total_ukuran') / (1024 * 1024), 2) }} * 1024; // In MB

    var ctx = document.getElementById('storageChart').getContext('2d');
    var storageChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Penyimpanan Terpakai', 'Penyimpanan Tersedia'],
            datasets: [{
                label: 'Penyimpanan',
                data: [totalUsed, totalAvailable],
                backgroundColor: ['#FF6384', '#36A2EB'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + ' MB';
                        }
                    }
                }
            }
        }
    });
});
</script>
@stop
