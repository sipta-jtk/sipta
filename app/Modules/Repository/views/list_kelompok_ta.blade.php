@extends('adminlte::page')

@section('title', 'List Kelompok TA')

@section('content_header')
    <h1>List Kelompok TA</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kelompok TA</h3>
                </div>
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>KoTA</th>
                                <th>Judul TA</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kelompok as $key => $k)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $k->id_kota }}</td>
                                <td>{{ $k->judul_ta }}</td>
                                <td>
                                    <a href="{{ route('Repository.dashboard.kota.mahasiswa', ['id_kota' => $k->id_kota]) }}" class="btn btn-primary">
                                        Lihat Repository
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
