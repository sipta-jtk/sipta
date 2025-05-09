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

        @php
            // Ambil maksimal anggota dari session
            $maksimalAnggota = session('maksimal_anggota', 3);
        @endphp

        @for($i = 1; $i <= $maksimalAnggota; $i++)
            <div class="row">
                <x-adminlte-input 
                    name="anggota{{ $i }}" 
                    label="{{ $i == 1 ? 'Anggota 1 (Akun Anda)' : 'Anggota ' . $i }}" 
                    value="{{ session('anggota' . $i, $i == 1 ? 'Nama Anda' : 'Tidak Dipilih') }}"
                    fgroup-class="col-md-6" 
                    readonly/>
            </div>
        @endfor

        <div class="d-flex pt-3 justify-content-end">
            <a href="{{ url(env('PREFIX_URL', 'sipta') . '/') }}" class="btn btn-secondary mx-1">Kembali ke Dashboard</a>
            <a href="#" class="btn btn-danger mx-1">Tinggalkan Pra-KoTA</a>
        </div>
    </div>
</div>
@stop