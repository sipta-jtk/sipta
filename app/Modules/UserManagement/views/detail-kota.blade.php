@extends('adminlte::page')

@section('title', 'Detail Kelompok TA')

@section('content_header')
    <h1>Detail Kelompok TA</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">{{ $kota->nama_kota }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($anggota as $key => $mhs)
                <x-adminlte-input name="anggota{{ $key + 1 }}" 
                    label="Anggota {{ $key + 1 }}" 
                    value="{{ $mhs->nama }} - {{ $mhs->nim }}"
                    fgroup-class="col-md-6" readonly/>
            @endforeach

            @for($i = count($anggota); $i < 3; $i++)
                <x-adminlte-input name="anggota{{ $i + 1 }}" 
                    label="Anggota {{ $i + 1 }}" 
                    value="Tidak Ada"
                    fgroup-class="col-md-6" readonly/>
            @endfor

            <x-adminlte-input name="topik" label="Topik" value="{{ $judulTA }}"
                fgroup-class="col-md-12" readonly/>

            <x-adminlte-input name="bidang_ta" label="Bidang TA" value="{{ $bidangTA }}"
                fgroup-class="col-md-6" readonly/>

            <x-adminlte-input name="tahun_ta" label="Tahun TA" value="{{ $kota->tahun_kota }}"
                fgroup-class="col-md-6" readonly/>
            
            @php $pembimbing1 = $pembimbing[0] ?? (object)['nama' => 'Belum ditentukan']; @endphp
            <x-adminlte-input name="pembimbing1" label="Dosen Pembimbing 1"
                value="{{ $pembimbing1->nama }}"
                fgroup-class="col-md-6" readonly/>

            @php $pembimbing2 = $pembimbing[1] ?? (object)['nama' => 'Belum ditentukan']; @endphp
            <x-adminlte-input name="pembimbing2" label="Dosen Pembimbing 2"
                value="{{ $pembimbing2->nama }}"
                fgroup-class="col-md-6" readonly/>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('management-kota') }}" class="btn btn-secondary">Kembali</a>
            <a href="#" class="btn btn-danger">Ajukan Cerai</a>
        </div>
    </div>
</div>
@stop