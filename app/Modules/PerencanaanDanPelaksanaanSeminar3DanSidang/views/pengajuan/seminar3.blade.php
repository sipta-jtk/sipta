@extends('adminlte::page')

@section('title', 'Pengajuan Seminar dan Sidang')

@section('content_header')
    <h1>Pengajuan Seminar 3</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <h4><strong>{{ $dataKota->kota_no }}</strong></h4>
        <p>{{ $dataKota->judul_ta }}</p>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Pembimbing</th>
                    <th>NIP Pembimbing</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataKota->mahasiswa as $index => $mahasiswa)
                    <tr>
                        <td>{{ $mahasiswa->nama }}</td>
                        <td>{{ $mahasiswa->nim }}</td>
                        @if (isset($dataKota->pembimbing[$index]))
                            <td>{{ $dataKota->pembimbing[$index]->nama }}</td>
                            <td>{{ $dataKota->pembimbing[$index]->nip }}</td>
                        @else
                            <td></td>
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h4>Pengajuan Seminar 3</h4>
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
                <label for="tempat_seminar3" class="form-label">Tempat Sidang:</label>
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