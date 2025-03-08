@extends('adminlte::page')

@section('title', 'Pengajuan Sidang')

@section('content_header')
    <h1>Pengajuan Sidang</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <h4><strong>KoTA 101</strong></h4>
        <p>PENGEMBANGAN APLIKASI MONITORING TUGAS AKHIR DI JURUSAN TEKNIK KOMPUTER DAN INFORMATIKA</p>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Dosen Pembimbing</th>
                    <th>NIP</th>
                    <th>Penguji</th>
                    <th>NIP</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataKota->mahasiswa as $index => $mahasiswa)
                    <tr>
                        <td>{{ $mahasiswa->nama }}</td>
                        <td>{{ $mahasiswa->nim }}</td>

                        @if ($index == 0)
                            <td>{{ $dataKota->pembimbing->nama }}</td>
                            <td>{{ $dataKota->pembimbing->nip }}</td>
                        @else
                            <td></td>
                            <td></td>
                        @endif

                        @if (isset($dataKota->penguji[$index]))
                            <td>{{ $dataKota->penguji[$index]->nama }}</td>
                            <td>{{ $dataKota->penguji[$index]->nip }}</td>
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
        <h4>Pengajuan Sidang</h4>
        <form action="" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="tanggal_sidang" class="form-label">Tanggal:</label>
                <input type="date" id="tanggal_sidang" name="tanggal_sidang" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="sesi_sidang" class="form-label">Sesi:</label>
                <select id="sesi_sidang" name="sesi_sidang" class="form-control" required>
                    <option value="Sesi I">Sesi I</option>
                    <option value="Sesi II">Sesi II</option>
                    <option value="Sesi III">Sesi III</option>
                    <option value="Sesi IV">Sesi IV</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tempat_sidang" class="form-label">Tempat Sidang:</label>
                <input type="text" id="tempat_sidang" name="tempat_sidang" class="form-control" required>
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