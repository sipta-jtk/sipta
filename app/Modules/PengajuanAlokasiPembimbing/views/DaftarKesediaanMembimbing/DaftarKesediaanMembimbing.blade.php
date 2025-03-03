@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Daftar Kesediaan Membimbing</h1>
@stop

@section('content')

    <p>Beranda > <a href="www">Daftar Kesediaan Menjadi Dosen Pembimbing</a></p>

  
    {{-- ================== --}}

    <div class="container">
        <x-pengajuan-alokasi-pembimbing.components.daftar-kesediaan-membimbing.daftar-kesediaan-membimbing />
    </div>

    {{-- ================== --}}
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

{{-- @section('js')
@stop --}}
