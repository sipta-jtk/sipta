@extends('adminlte::page')

@section('title', 'PembatalanJadwalSeminarSidang')

@section('content_header')
    <h1>PembatalanJadwalSeminar</h1>
@stop

@section('content')
    <p>Welcome to PembatalanJadwalSeminarSidang Page.</p>
    <table id="seminarTable" class="table table-striped" width="100%">
        <thead>
            <th>Kota No</th>
            <th>Judul</th>
            <th>Tanggal Siddang</th>
            <th>Sesi Sidang</th>
            <th>Ruangan</th>
            <th>Pembimbing</th>
            <th>Penguji 1</th>
            <th>Penguji 2</th>
            <th>Alasan</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach($jadwal as $s)
            <tr>
                <td>{{ $s->kota_no }}</td>
                <td>{{ $s->judul_ta }}</td>
                <td>{{ $s->tanggal }}</td>
                <td>{{ $s->sesi }}</td>
                <td>{{ $s->ruangan }}</td>
                <td>{{ $s->pembimbing }}</td>
                <td>{{ $s->penguji1 }}</td>
                <td>{{ $s->penguji2 }}</td>
                <td>{{ $s->alasan_pembatalan }}</td>
                <td>
                    <a href="" class="btn btn-primary">Setuju</a>
                    <a href="" class="btn btn-danger">Tolak</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#seminarTable').DataTable();
        });
    </script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop