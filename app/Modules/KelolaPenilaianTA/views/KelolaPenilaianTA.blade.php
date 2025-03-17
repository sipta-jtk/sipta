@extends('adminlte::page')

@section('title', 'KelolaPenilaianTA')

@section('content_header')
    <div class="d-flex flex-column pl-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Kelola Penilaian</li>
            </ol>
        </nav>
        <h1>{{ $data['header'] }}</h1>
    </div>
@stop

@section('content')
    <div class="shadow p-3 mb-5 bg-body rounded">
        <table class="table table-striped table-bordered">
            <thead class="bg-brown text-white">
                <tr>
                    <th>Kategori</th>
                    <th class="w-40">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['kategori'] as $kategori)
                    <tr>
                        <td> {{ $kategori }} </td>
                        <td>
                            <div class="action d-flex flex-row align-items-center"> 
                                <button type="button" class="btn btn-primary">Kunci Penilaian</button>
                                <a href="{{ url('KelolaPenilaianTA/detail/' . Str::slug($kategori)) }}" class="btn btn-primary">Buka Detail</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="action d-flex justify-content-end">
            <button type="button" class="btn btn-primary">Kunci Semua Penilaian</button>
            <a href=" {{ url('KelolaPenilaianTA/rekapitulasi-nilai') }}" class="btn btn-primary">Rekap Nilai</a>
        </div>
    </div>
@stop

@section('css')
    <style>
        .bg-brown {
            background-color: #3E3E3E;
        }

        .w-40 {
            width: 40%;
        }

        .action {
            gap: 1rem;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop