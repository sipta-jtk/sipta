@extends('adminlte::page')

@section('title', 'Rekap Presensi Sidang TA')

@section('content_header')
    <h1>Rekap Presensi Sidang TA</h1>
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
                <table id="presensiSidangTA" class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="w-10">KoTA</th>
                            <th class="w-20">Nim</th>
                            <th class="w-20">Nama Mahasiswa</th>
                            <th class="w-15">Tanggal</th>
                            <th class="w-15">Ruangan</th>
                            <th class="w-10">Sesi</th>
                            <th class="w-10">Status Kehadiran</th>
                            <th class="w-20">Dokumentasi</th>
                            <th class="w-15">Batas Revisi</th>
                            <th class="w-15">Status Kelulusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beritaAcaraSidangTA as $index => $item)
                            @php
                                $statusKehadiran = session("status_hadir_{$item['id_kehadiran']}", $item['status_hadir']);
                                $dokumentasi = session("dok_{$item['id_kehadiran']}", $item['dokumentasi']);
                                $batasRevisi = session("batas_revisi_{$item['id_kehadiran']}", $item['batas_revisi'] ?? '');
                                $statusKelulusan = session("status_kelulusan_{$item['id_kehadiran']}", $item['status_kelulusan'] ?? '');
                            @endphp

                            <tr>
                                <td>{{ $item['id_kota'] }}</td>
                                <td>{{ $item['nim'] }}</td>
                                <td>{{ $item['mahasiswa'] }}</td>
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
                                <td style="width: 50px;">
                                    @if($dokumentasi)
                                        <div class="mb-2">
                                            <a href="{{ asset('storage/' . $dokumentasi) }}" target="_blank">
                                                {{ \Illuminate\Support\Str::limit(basename($dokumentasi), 15) }}
                                            </a>
                                        </div>
                                    @else
                                        <span style="font-size: 1 rem;"> Belum diupload</span>
                                    @endif
                                </td>
                                <td>
                                    @if($batasRevisi)
                                        {{ $batasRevisi }} <!-- Tampilkan batas revisi jika sudah diisi -->
                                    @else
                                        <span class="text-muted">Belum diisi</span> <!-- Tampilkan pesan jika belum diisi -->
                                    @endif
                                </td>
                                <td>
                                    @if($statusKelulusan)
                                        {{ $statusKelulusan }} <!-- Tampilkan status kelulusan jika sudah diisi -->
                                    @else
                                        <span class="text-muted">Belum diisi</span> <!-- Tampilkan pesan jika belum diisi -->
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
            $('#presensiSidangTA').DataTable();
        });
    </script>
@stop