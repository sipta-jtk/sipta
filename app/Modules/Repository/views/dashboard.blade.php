@extends('adminlte::page')

@section('title', 'Dashboard Repository')

@section('content_header')
    <h1 class="text-center">Daftar Kategori Repository</h1>
@stop

@section('content')
    <div class="container">
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategoriList as $index => $kategori)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $kategori)) }}</td>
                        <td>
                            <a href="{{ route('Repository.index', $kategori) }}" class="btn btn-primary">
                                Lihat Dokumen
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('css')
    <style>
        .table th,
        .table td {
            vertical-align: middle;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Dashboard Repository page loaded.");
    </script>
@stop