@extends('adminlte::page')

@section('title', 'Rekap Presensi Seminar 3')

@section('content_header')
    <h1>Rekap Berita Acara Seminar 3</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table id="presensiSeminar" class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="w-10">KoTA</th>
                            <th class="w-20">Nim</th>
                            <th class="w-20">Nama Mahasiswa</th>
                            <th class="w-15">Tanggal</th>
                            <th class="w-15">Ruangan</th>
                            <th class="w-10">Sesi</th>
                            <th class="w-10">Status Kehadiran</th>
                            <th class="w-15">Dokumentasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beritaAcaraSeminar3 as $index => $item)
                            <tr>
                                <td>{{ $item->penjadwalan->kota->nama_kota ?? '-' }}</td>
                                <td>{{ $item->user->mahasiswa->nim ?? '-' }}</td>
                                <td>{{ $item->user->nama ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->tanggal ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->id_ruangan ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->sesi ?? '-' }}</td>
                                <td>
                                    @if($item->status_hadir == 'hadir')
                                        <button class="btn btn-success btn-sm" disabled>Hadir</button>
                                    @elseif($item->status_hadir == 'tidak_hadir')
                                        <button class="btn btn-danger btn-sm" disabled>Tidak Hadir</button>
                                    @else
                                        <button class="btn btn-warning btn-sm" disabled>Belum Absen</button>
                                    @endif
                                </td>
                                <td style="width: 50px;">
                                    @if($item->foto_sidang)
                                        <div class="mb-2">
                                            <strong>File Terupload:</strong>
                                            <a href="{{ asset('storage/' . $item->foto_sidang) }}" target="_blank">
                                                {{ \Illuminate\Support\Str::limit(basename($item->foto_sidang), 15) }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="alert alert-warning p-1" style="font-size: 0.7rem;">
                                            Dokumentasi belum diupload.
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function() {
            $('#presensiSeminar').DataTable();
    });
    </script>
@stop