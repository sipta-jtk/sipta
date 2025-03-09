@extends('adminlte::page')

@section('title', 'Pengajuan Cerai KoTA')

@section('content_header')
    <h1>Pengajuan Cerai KoTA</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengajuan</h3>
    </div>

    <div class="card-body">
        <table id="datatable" class="table table-borderd">
            <thead class="bg-primary text-white">
                <tr>
                    <th style="width: 50%">Nama Mahasiswa</th>
                    <th style="width: 30%">KoTA</th>
                    <th style="width: 20%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengajuan as $item)
                <tr>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->kelompok_ta}}</td>
                    <td>
                    <a href="{{ route('pengajuan.cerai.kota.show', $item->id) }}" class="btn btn-sm btn-info">
                        Tinjau <i class="mx-1 fas fa-arrow-circle-right"></i>
                    </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"> --}}
@stop

@section('js')
    <script> console.log("Ini user management bang!"); </script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        });
    </script>
@stop