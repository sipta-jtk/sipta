@extends('adminlte::page')

@section('title', 'Jadwal Sidang')

@section('content_header')
<h1>Jadwal Sidang</h1>
@stop

@section('content')
<h4>Jadwal Sidang Bimbingan</h4>
<table id="sidangBimTable" class="table table-striped" width="100%">
    <thead class="sticky-header">
        <tr class="bg-dark text-white">
            <th>Kota No</th>
            <th>Judul</th>
            <th>Tanggal Sidang</th>
            <th>Sesi Sidang</th>
            <th>Ruangan</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jadwal_pembimbing as $s)
            <tr>
                <td>{{ $s->kota->nama_kota }}</td>
                <td>{{ $s->kota->judul_ta }}</td>
                <td>{{ $s->tanggal }}</td>
                <td>{{ $s->sesi }}</td>
                <td>{{ $s->id_ruangan }}</td>
                <td>
                    @if ($s->id_pembatalan == null && $s->status_pembatalan == 0)
                        <span>Terjadwal</span>
                    @elseif ($s->id_pembatalan != null && $s->status_pembatalan == 1)
                        <span>Dibatalkan</span>
                    @elseif ($s->id_pembatalan != null && $s->status_pembatalan == null)
                        <span>Menunggu Persetujuan Pembatalan</span>
                    @endif
                </td>
                <td>
                    @if ($s->id_pembatalan == null && $s->status_pembatalan == 0)
                    <x-adminlte-button theme="danger" label="Ajukan pembatalan" data-toggle="modal"
                        data-target="#modalMin{{ $s->id }}" />
                    @else
                    <x-adminlte-button theme="danger" label="Ajukan pembatalan" data-toggle="modal"
                        data-target="#modalMin{{ $s->id }}" disabled/>
                    @endif
                </td>
            </tr>

            <x-adminlte-modal id="modalMin{{ $s->id }}" title="Alasan Pembatalan">
                <form action="{{ route('pembatalan.sidang') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $s->id_penjadwalan }}">
                    <x-adminlte-textarea name="alasan" placeholder="Masukkan alasan..." required />
                    <button type="submit" class="btn btn-primary">Kirimkan</button>
                    <x-adminlte-button theme="danger" label="Batalkan" data-dismiss="modal" />
                    <x-slot name="footerSlot">
                    </x-slot>
                </form>
            </x-adminlte-modal>
        @endforeach
    </tbody>
</table>

<br>
<br>

<h4>Jadwal Menguji Sidang</h4>
<table id="sidangUjiTable" class="table table-striped" width="100%">
    <thead class="sticky-header">
        <tr class="bg-dark text-white">
            <th>Kota No</th>
            <th>Judul</th>
            <th>Tanggal Sidang</th>
            <th>Sesi Sidang</th>
            <th>Ruangan</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jadwal_penguji as $s)
            <tr>
                <td>{{ $s->kota->nama_kota }}</td>
                <td>{{ $s->kota->judul_ta }}</td>
                <td>{{ $s->tanggal }}</td>
                <td>{{ $s->sesi }}</td>
                <td>{{ $s->id_ruangan }}</td>
                <td>
                    @if ($s->id_pembatalan == null && $s->status_pembatalan == 0)
                        <span>Terjadwal</span>
                    @elseif ($s->id_pembatalan != null && $s->status_pembatalan == 1)
                        <span>Dibatalkan</span>
                    @elseif ($s->id_pembatalan != null && $s->status_pembatalan == null)
                        <span>Menunggu Persetujuan Pembatalan</span>
                    @endif
                </td>
                <td>
                    @if ($s->id_pembatalan == null && $s->status_pembatalan == 0)
                    <x-adminlte-button theme="danger" label="Ajukan pembatalan" data-toggle="modal"
                        data-target="#modalMin{{ $s->id }}" />
                    @else
                    <x-adminlte-button theme="danger" label="Ajukan pembatalan" data-toggle="modal"
                        data-target="#modalMin{{ $s->id }}" disabled/>
                    @endif
                </td>
            </tr>

            <x-adminlte-modal id="modalMin{{ $s->id }}" title="Alasan Pembatalan">
                <form action="{{ route('pembatalan.Sidang') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $s->id_penjadwalan }}">
                    <x-adminlte-textarea name="alasan" placeholder="Masukkan alasan..." required />
                    <button type="submit" class="btn btn-primary">Kirimkan</button>
                    <x-adminlte-button theme="danger" label="Batalkan" data-dismiss="modal" />
                    <x-slot name="footerSlot">
                    </x-slot>
                </form>
            </x-adminlte-modal>
        @endforeach
    </tbody>
</table>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{--
<link rel="stylesheet" href="/css/admin_custom.css"> --}}
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#sidangBimTable').DataTable();
    });
    $(document).ready(function () {
        $('#sidangUjiTable').DataTable();
    });
</script>
<script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop