@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
<link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

@section('content_header')
<div class="container">
    <h1>Daftar Pengajuan Dosen Pembimbing</h1>
</div>
@stop

@section('content')

{{-- <x-pengajuan-alokasi-pembimbing.table-pengajuan :pengajuan="$pengajuan" /> --}}
<div class="container">
    <p>Beranda > <a href="www">Daftar Pengajuan Dosen Pembimbing</a>
        <div class="justify-content-center">
            <div class="mr-2">
                <x-pengajuan-alokasi-pembimbing.components.daftar-pengajuan-dosbing.table-pengajuan :kelompokData="$kelompokData" tableId="myTable" tableClass="table table-striped" />
            </div>
        </div>
</div>


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
