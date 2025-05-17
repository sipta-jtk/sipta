@extends('adminlte::page')

@section('title', 'Konfirmasi Pengajuan KoTA')

@section('content_header')
    <h1 class="mb-3">Konfirmasi Pengajuan KoTA</h1>

    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => route('perekrutan-anggota-kota'), 'label' => 'Rekrut Anggota KoTA'],
                ['url' => '', 'label' => 'Konfirmasi KoTA']
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="alert alert-success">
            <h5><i class="icon fas fa-check"></i> Berhasil</h5>
            Kelompok TA berhasil dibuat dengan nama <strong>{{ session('nama_kota') }}</strong> untuk tahun ajaran <strong>{{ session('tahun_kota') }}</strong>.
        </div>

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

        <div class="d-flex pt-3 justify-content-end">
            <a href="{{ url(env('PREFIX_URL', 'sipta') . '/') }}" class="btn btn-secondary mx-1">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@stop