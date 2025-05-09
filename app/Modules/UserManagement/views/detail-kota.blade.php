@extends('adminlte::page')

@section('title', 'Detail Kelompok TA')

@section('content_header')
    <h1 class="mb-3">Detail Kelompok TA</h1>

    <div>
        @php
            $links = [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda']
            ];
            
            if(url()->previous() && strpos(url()->previous(), 'management-kota') !== false) {
                $links[] = ['url' => route('management-kota'), 'label' => 'Daftar Kelompok Tugas Akhir'];
            }
            
            $links[] = ['url' => '', 'label' => 'Detail Kelompok TA'];
        @endphp

        @component('KelolaPenilaianTA.views.components.breadcrumb', ['links' => $links])
        @endcomponent
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">{{ $kota->nama_kota }}</h3>
    </div>
    <div class="card-body">
        <div class="row mb-4 px-3">
            <p class="text-secondary text-md border-bottom col-md-12">Anggota</p>
            @foreach($anggota as $key => $mhs)
                <x-adminlte-input name="anggota{{ $key + 1 }}" 
                    label="Anggota {{ $key + 1 }}" 
                    value="{{ $mhs->nama }} - {{ $mhs->nim }}"
                    fgroup-class="col-md-6" readonly/>
            @endforeach

            @for($i = count($anggota); $i < $maksimalAnggota; $i++)
                <x-adminlte-input name="anggota{{ $i + 1 }}" 
                    label="Anggota {{ $i + 1 }}" 
                    value="Tidak Ada"
                    fgroup-class="col-md-6" readonly/>
            @endfor
        </div>

        <div class="row mb-4 px-3">
            <p class="text-secondary text-md border-bottom col-md-12">Detail Informasi</p>
            <x-adminlte-input name="topik" label="Topik" value="{{ $judulTA }}"
                fgroup-class="col-md-12" readonly/>

            <x-adminlte-input name="bidang_ta" label="Bidang TA" value="{{ $bidangTA }}"
                fgroup-class="col-md-6" readonly/>

            <x-adminlte-input name="tahun_ta" label="Tahun TA" value="{{ $kota->tahun_kota }}"
                fgroup-class="col-md-6" readonly/>
        </div>

        <div class="row mb-4 px-3">
            <p class="text-secondary text-md border-bottom col-md-12">Dosen Pembimbing</p>
            @php $pembimbing1 = $pembimbing[0] ?? (object)['nama' => 'Belum ditentukan']; @endphp
            <x-adminlte-input name="pembimbing1" label="Dosen Pembimbing 1"
                value="{{ $pembimbing1->nama }}"
                fgroup-class="col-md-6" readonly/>

            @php $pembimbing2 = $pembimbing[1] ?? (object)['nama' => 'Belum ditentukan']; @endphp
            <x-adminlte-input name="pembimbing2" label="Dosen Pembimbing 2"
                value="{{ $pembimbing2->nama }}"
                fgroup-class="col-md-6" readonly/>
        </div>

        <div class="d-flex pt-3 px-3 justify-content-end">
            @if(url()->previous() && strpos(url()->previous(), 'management-kota') !== false)
                <a href="{{ route('management-kota') }}" class="btn btn-secondary">Kembali</a>
            @endif

            @can('mahasiswa_kota', $kota->id_kota)
                <a href="{{route('form.pisah.kota')}}" class="btn btn-danger">Ajukan Pisah</a>
            @endcan
        </div>
    </div>
</div>
@stop