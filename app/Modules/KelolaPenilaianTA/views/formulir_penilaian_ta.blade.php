@extends('adminlte::page')

@section('title', 'Pengelolaan Formulir Penilaian')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Formulir Penilaian & Feedback</a></li>
            <li class="breadcrumb-item active" aria-current="page">Formulir Penilaian</li>
        </ol>
    </nav>
    <h1>Pengelolaan Formulir Penilaian</h1>
@stop

@section('content')
    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Daftar Formulir Penilaian TA</h3>
            <a href="{{ route('formulir-penilaian.create') }}" class="btn btn-dark">
                <i class="fa-solid fa-plus"></i> Tambah
            </a>
        </div>

        <div class="table-container">
            <table id="formulirTable" class="table text-center">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th style="width: 3%;">No</th>
                        <th style="width: 32%;">Kode Formulir</th>
                        <th style="width: 35%;">Nama Formulir</th>
                        <th style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr class="bg-light">
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $row['kode'] }}</td>
                            <td class="align-middle">{{ $row['nama'] }}</td>
                            <td class="align-middle">
                                <a href="{{ url('KelolaPenilaianTA/formulir-penilaian/detail') }}" class="btn btn-info btn-sm">Lihat Detail</a>
                                <a href="{{ url('KelolaPenilaianTA/formulir-penilaian/edit') }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i></a>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
            display: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc !important;
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
            background-color: #f8f9fa;
        }
        tbody tr:hover {
            background-color: #e2e6ea;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#formulirTable').DataTable({
                "paging": true,
                "lengthMenu": [10, 25, 50, 100],
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });
    </script>
@stop