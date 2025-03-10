@extends('adminlte::page')

@section('title', 'Detail Kelompok TA')

@section('content_header')
    <h1>Detail Kelompok TA</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">KoTA 404</h3>
    </div>
    <div class="card-body">
        <div class="row">
            
            <x-adminlte-input name="anggota1" label="Anggota 1" value="Banteng Harisantoso"
                fgroup-class="col-md-6" readonly/>
            
            <x-adminlte-input name="anggota2" label="Anggota 2" value="Keanu Rayhan H"
                fgroup-class="col-md-6" readonly/>

            <x-adminlte-input name="anggota3" label="Anggota 3" value="Muhammad Hanif"
                fgroup-class="col-md-6" readonly/>

            <x-adminlte-input name="topik" label="Topik" value="Pengembangan Aplikasi Pendeteksi Capybara"
                fgroup-class="col-md-12" readonly/>

            <x-adminlte-input name="bidang_ta" label="Bidang TA" value="Computer Vision"
                fgroup-class="col-md-6" readonly/>

            <x-adminlte-input name="tahun_ta" label="Tahun TA" value="2026"
                fgroup-class="col-md-6" readonly/>
            
            <x-adminlte-input name="pembimbing1" label="Dosen Pembimbing 1"
                value="Dr. Mohammad Fathur Rabbani, S.Si., M.T"
                fgroup-class="col-md-6" readonly/>

            <x-adminlte-input name="pembimbing2" label="Dosen Pembimbing 2"
                value="Muhammad Rizki Nurmuttaqin, M.T"
                fgroup-class="col-md-6" readonly/>
        </div>

        <div class="d-flex justify-content-between">
            <a href="#" class="btn btn-secondary">Kembali</a>
            <a href="#" class="btn btn-danger">Ajukan Cerai</a>
        </div>
    </div>
</div>
@stop