@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai')

@section('content_header')
    <div class="d-flex flex-column pl-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="/KelolaPenilaianTA/pengelolaan-nilai">Kelola Penilaian</a></li>
              <li class="breadcrumb-item active" aria-current="page">Data</li>
            </ol>
        </nav>
        <h1>Detail Nilai {{ $kategori }}</h1>
    </div>
@stop

@section('content')
    <div class="p-4">
        {{-- DataTables Controls (Jumlah data & Search) --}}
        <div class="d-flex justify-content-between mb-2">
            <div id="dataTableControls"></div> <!-- Placeholder untuk jumlah data -->
            <div id="searchBox"></div> <!-- Placeholder untuk pencarian -->
        </div>

        {{-- Tabel Scrollable --}}
        <div class="table-container">
            <table id="alokasiTable" class="table text-center">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th rowspan="2" class="align-middle" style="width: 3%;">No</th>
                        <th rowspan="2" class="align-middle" style="width: 20%;">Nama</th>
                        <th rowspan="2" class="align-middle" style="width: 4%;">Kelompok</th>
                        <th rowspan="2" class="align-middle">Penguji 1</th>
                        <th rowspan="2" class="align-middle">Penguji 2</th>
                        <th rowspan="2" class="align-middle">Penguji 3</th>
                        <th colspan="3" style="width: 10%;">Nilai</th>
                        <th rowspan="2" class="align-middle">Rata-rata</th>
                        <th rowspan="2" class="align-middle">Aksi</th>
                    </tr>
                    <tr class="bg-secondary text-white">
                        <th style="width: 2%;">P1</th>
                        <th style="width: 2%;">P2</th>
                        <th style="width: 2%;">P3</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['nama'] }}</td>
                            <td>{{ $item['kelompok'] }}</td>
                            <td>{{ $item['penguji1'] }}</td>
                            <td>{{ $item['penguji2'] }}</td>
                            <td>{{ $item['penguji3'] }}</td>
                            <td>{{ $item['p1'] }}</td>
                            <td>{{ $item['p2'] }}</td>
                            <td>{{ $item['p3'] }}</td>
                            <td>{{ $item['rata_rata'] }}</td>
                            <td>
                                <button type="button" class="btn btn-primary">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .table-container {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #ddd;
            position: relative;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .table-container::-webkit-scrollbar {
            display: none; /* Untuk Chrome, Safari, dan Opera */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc !important; /* Tambahkan border untuk outline */
            padding: 10px;
            text-align: center;
        }

        thead {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.9);
            color: white;
        }

        thead th {
            position: sticky;
            top: 0;
            z-index: 1001;
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            text-align: center;
            padding: 12px;
            border-bottom: 2px solid #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa; /* Warna striping */
        }

        tbody tr:hover {
            background-color: #e2e6ea;
        }

        .tooltip-box {
            width: 280px;
            padding: 10px;
            border-radius: 6px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 14px;
            line-height: 1.5;
            left: 0;
            transform: translateX(0);
            top: 130%;
            z-index: 10;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#alokasiTable').DataTable({
                "paging": true,
                "lengthMenu": [10, 25, 50, 100],
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });

            $('#dataTableControls').html($('.dataTables_length'));
            $('#searchBox').html($('.dataTables_filter'));
        });
    </script>
@stop