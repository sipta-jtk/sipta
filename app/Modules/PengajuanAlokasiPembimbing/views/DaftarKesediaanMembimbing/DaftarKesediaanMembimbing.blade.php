@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Daftar Kesediaan Membimbing</h1>
@stop

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#kesediaanTable').DataTable();
        });
    </script>
@stop

@section('content')

    <p>Beranda > <a href="www">Daftar Kesediaan Menjadi Dosen Pembimbing</a></p>
    <div class="table-responsive">
    <table id="kesediaanTable" class="table table-bordered">
    <thead>
        <tr>
            <th colspan="5" class="text-center">Dosen Eligible Sebagai Pembimbing 1</th>
            <th colspan="2" class="text-center">Jumlah TA</th>
            <th colspan="3" class="text-center">Kesediaan Membimbing</th>
            <th rowspan="2" class="text-center align-middle">Topik</th>
        </tr>
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">KD DOSEN</th>
            <th class="text-center" style="min-width: 25vh;">NAMA</th>
            <th class="text-center">ID DOSEN</th>
            <th class="text-center" style="min-width: 25vh;">NIP</th>
            <th class="text-center" style="min-width: 10vh;" >KBK</th>
            <th class="text-center" style="min-width: 5vh;">Status Pengumpulan</th>
            <th class="text-center">Jumlah Mhs</th>
            <th class="text-center">Mhs D3</th>
            <th class="text-center">Mhs D4</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td class="text-center">KO009</td>
            <td class="text-center">Lorem ipsum</td>
            <td class="text-center">LI</td>
            <td class="text-center">19601226 199243 1 101</td>
            <td class="text-center">Multimedia</td>
            <td class="text-center">2025-03-10</td>
            <td class="text-center">20</td>
            <td class="text-center">2</td>
            <td class="text-center">12</td>
            <td class="text-left" style="white-space: nowrap; max-width: 200px; overflow:auto">Business intelligent, Enterprise Architecture, ERP, Organizational Performance Measurement, Business intelligent, Enterprise Architecture, ERP, Organizational Performance Measurement, Business intelligent, Enterprise Architecture, ERP, Organizational Performance Measurement</td>
        </tr>
        <tr>
            <td class="text-center">1</td>
            <td class="text-center">KO009</td>
            <td class="text-center">Mirom ipsum</td>
            <td class="text-center">LI</td>
            <td class="text-center">19601226 199243 1 101</td>
            <td class="text-center">Multimedia</td>
            <td class="text-center">2025-03-10</td>
            <td class="text-center">20</td>
            <td class="text-center">2</td>
            <td class="text-center">12</td>
            <td class="text-left" style="white-space: nowrap; max-width: 200px; overflow:auto">Business intelligent, Enterprise Architecture, ERP, Organizational Performance Measurement, Business intelligent, Enterprise Architecture, ERP, Organizational Performance Measurement, Business intelligent, Enterprise Architecture, ERP, Organizational Performance Measurement</td>
        </tr>
    </tbody>
</table>
</div>

@stop