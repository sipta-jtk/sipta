@extends('adminlte::page')

@section('title', 'PembatalanJadwalSeminarSidang')

@section('content_header')
    <h1>JadwalSidang</h1>
@stop

@section('content')
    <p>Welcome to JadwalSeminarSidang Page.</p>
    <table id="seminarTable" class="table table-striped" width="100%">
        <thead>
            <th>Kota No</th>
            <th>Judul</th>
            <th>Tanggal Seminar</th>
            <th>Sesi Seminar</th>
            <th>Ruangan</th>
            <th>Penguji 1</th>
            <th>Penguji 2</th>
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
                <td>{{ $s->penguji1 }}</td>
                <td>{{ $s->penguji2 }}</td>
                <td>
                    <x-adminlte-button theme="danger" label="Batalkan" data-toggle="modal" data-target="#modalMin"/>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <x-adminlte-modal id="modalMin" title="Alasan Pembatalan">
        <x-adminlte-textarea name="Alasan" placeholder="Masukkan alasan..."/>
        <x-slot name="footerSlot">
            <x-adminlte-button class="d-flex ml-auto" theme="primary" label="submit"
                icon="fas fa-sign-in"/>
            <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>
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