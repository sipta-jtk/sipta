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
  
    {{-- ================== --}}

    <!-- <div class="container">
        <x-pengajuan-alokasi-pembimbing.components.daftar-kesediaan-membimbing.daftar-kesediaan-membimbing />
    </div> -->

    <div class="table-responsive">
<table id="kesediaanTable" class="table table-bordered">
    <thead>
        <tr>
            <th colspan="5" class="text-center">Dosen Eligible Sebagai Pembimbing 1</th>
            <th colspan="2" class="text-center">Jumlah TA</th>
            <th colspan="3" class="text-center">Kesediaan Membimbing</th>
        </tr>
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">KD DOSEN</th>
            <th class="text-center">NAMA</th>
            <th class="text-center">ID DOSEN</th>
            <th class="text-center" style="width: 5%">NIP</th>
            <th class="text-center" style="width: 25%">KBK</th>

            <th class="text-center">Status Pengumpulan</th>
            <th class="text-center">Jumlah Mhs</th>

            <th class="text-center">Mhs D3</th>
            <th class="text-center">Mhs D4</th>
            <th class="text-center" >Topik</th>
        </tr>
    </thead>
    <tbody>

        <tr>
            <td class="text-center">1</td>
            <td class="text-center">KO009N</td>
            <td class="text-center">Lorem ipsum</td>
            <td class="text-center">LI</td>
            <td class="text-center">19191919100019201</td>

            <td class="text-center">SI & DB</td>
            <td class="text-center">2025-03-10</td>

            <td class="text-center">A</td>
            <td class="text-center">B</td>
            <td class="text-center">B</td>
            <td class="text-align-left" style="width: 30%; white-space: wrap; overflow: hidden; text-overflow: ellipsis;"> Business intelligent, Enterprise Architecture, ERP, Organizational Performance Measurement</td>

        </tr>
      
    </tbody>
</table>
</div>

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#kesediaanTable').DataTable();
        });
    </script>
@stop

    {{-- ================== --}}
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

{{-- @section('js')
@stop --}}
