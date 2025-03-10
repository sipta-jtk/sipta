@extends('adminlte::page')

@section('title', 'Konfirmasi Pengajuan KoTA')

@section('content_header')
    <h1>Konfirmasi Pengajuan KoTA</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Kelompok TA</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <x-adminlte-input name="anggota1" label="Anggota 1 (Akun Anda)" value="{{ session('anggota1', 'Nama Anda') }}"
                fgroup-class="col-md-6" readonly/>
        </div>

        <div class="row">
            <x-adminlte-input name="anggota2" label="Anggota 2" value="{{ session('anggota2', 'Tidak Dipilih') }}"
                fgroup-class="col-md-6" readonly/>
        </div>

        <div class="row">
            <x-adminlte-input name="anggota3" label="Anggota 3" value="{{ session('anggota3', 'Tidak Dipilih') }}"
                fgroup-class="col-md-6" readonly/>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('pengajuan-kota') }}" class="btn btn-secondary">Kembali</a>
            <a href="#" class="btn btn-danger">Tinggalkan Pra-KoTA</a>
        </div>
    </div>
</div>
@stop