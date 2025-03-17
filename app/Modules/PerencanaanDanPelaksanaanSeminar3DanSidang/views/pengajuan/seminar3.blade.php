@extends('adminlte::page')

@section('title', 'Pengajuan Seminar dan Sidang')

@section('content_header')
    <h1><strong>Pengajuan Seminar 3</strong></h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <h4><strong>KoTA {{ $dataKota->kota_no }}</strong></h4>
        <p class="text-uppercase">{{ $dataKota->judul_ta }}</p>
        
        <div class="row">
            <div class="col">
                <h4 class="text-bold px-3">Mahasiswa</h4>
                <ul>
                    @foreach($dataKota->mahasiswa as $mhs)
                        <li><h5>{{ $mhs->nim }} - {{ $mhs->nama }}</h5></li>
                    @endforeach
                </ul>
            </div> 
            <div class="col">
                <h4 class="text-bold px-3">Pembimbing</h4>
                <ul>
                    @foreach($dataKota->pembimbing as $index => $mhs)
                        @if(isset($dataKota->pembimbing[$index]))
                            <li><h5>{{ $dataKota->pembimbing[$index]->nip }} - {{ $dataKota->pembimbing[$index]->nama }}</h5></li>
                        @else
                            <li>Data tidak ditemukan</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h4>Waktu dan Tempat Pelaksanaan</h4>
        <form action="" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="tanggal_seminar3" class="form-label">Tanggal:</label>
                <input type="date" id="tanggal_seminar3" name="tanggal_seminar3" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="sesi_seminar3" class="form-label">Sesi:</label>
                <select id="sesi_seminar3" name="sesi_seminar3" class="form-control" required>
                    <option value="Sesi I">Sesi I</option>
                    <option value="Sesi II">Sesi II</option>
                    <option value="Sesi III">Sesi III</option>
                    <option value="Sesi IV">Sesi IV</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tempat_seminar3" class="form-label">Ruangan:</label>
                <input type="text" id="tempat_seminar3" name="tempat_seminar3" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Ajukan</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop