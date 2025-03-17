@extends('adminlte::page')

@section('title', 'Rekap Presensi Seminar 3')

@section('content_header')
    <h1>Rekap Presensi Seminar 3</h1>
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
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="w-20">NIM</th>
                            <th class="w-20">MAHASISWA</th>
                            <th class="w-10">KOTA</th>
                            <th class="w-15">TANGGAL</th>
                            <th class="w-15">RUANGAN</th>
                            <th class="w-10">SESI</th>
                            <th class="w-10">STATUS</th>
                            <th class="w-20">DOKUMENTASI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($presensiSeminar3 as $index => $item)
                        @php
                            $statusKehadiran = session("status_hadir_{$item['id_kehadiran']}", $item['status_hadir']);
                            $dokumentasi = session("dok_{$item['id_kehadiran']}", $item['dokumentasi']);
                        @endphp

                            <tr>
                                <td>{{ $item['nim'] }}</td>
                                <td>{{ $item['mahasiswa'] }}</td>
                                <td>{{ $item['id_kota'] }}</td>
                                <td>{{ $item['tanggal'] }}</td>
                                <td>{{ $item['ruangan'] }}</td>
                                <td>{{ $item['sesi'] }}</td>
                                <td>
                                    @if($statusKehadiran == 'hadir')
                                        <button class="btn btn-success btn-sm" disabled>Hadir</button>
                                    @elseif($statusKehadiran == 'absen')
                                        <button class="btn btn-danger btn-sm" disabled>Absen</button>
                                    @else
                                        <button class="btn btn-warning btn-sm" disabled>Belum Absen</button>
                                    @endif
                                </td>
                                <td style="width: 250px;">
                                    @if($dokumentasi)
                                        <div class="mb-2">
                                            <strong>File Terupload:</strong>
                                            <a href="{{ asset('storage/' . $dokumentasi) }}" target="_blank">
                                                {{ basename($dokumentasi) }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
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
    <style>
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop