@extends('adminlte::page')

@section('title', 'PerencanaanDanPelaksanaanSeminar3DanSidang')

@section('content_header')
    <h1>Alokasi Penguji</h1>
@stop

@section('content')
    <p>Welcome to Alokasi Penguji Page.</p>
    <table id="seminarTable" class="table table-striped" width="100%">
        <thead>
            <th>Kota No</th>
            <th>Judul</th>
            <th>Pembimbing</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach($KoTA as $s)
            <tr>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->judul }}</td>
                <td>{{ $s->pembimbing }}</td>
                <td>
                    <x-adminlte-button theme="primary" label="Alokasi Penguji" data-toggle="modal" data-target="#modalMin"/>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <x-adminlte-modal id="modalMin" title="Alokasi Penguji">
        <x-adminlte-select name="selBasic">
            <option>Option 1</option>
            <option disabled>Option 2</option>
            <option selected>Option 3</option>
        </x-adminlte-select>
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