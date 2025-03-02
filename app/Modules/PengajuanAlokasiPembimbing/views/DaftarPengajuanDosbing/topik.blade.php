@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

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
                <x-pengajuan-alokasi-pembimbing.components.daftar-pengajuan-dosbing.table-pengajuan title="Daftar Pengajuan TA" tableClass="table table-striped table-hover" theadClass="table-primary" />
            </div>
        </div>
</div>


@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop
