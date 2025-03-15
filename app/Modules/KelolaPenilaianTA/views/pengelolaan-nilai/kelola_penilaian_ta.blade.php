@extends('adminlte::page')

@section('title', 'KelolaPenilaianTA')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => '', 'label' =>  $data['header'] ?? 'Kelola Penilaian' ]
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">{{ $data['header'] ?? 'Kelola Penilaian' }}</h1>
    </div>
@stop

@section('content')
    <div class="shadow p-3 mb-5 bg-body rounded">
        <table class="table table-striped table-bordered text-center">
            <thead class="bg-brown text-white">
                <tr>
                    <th>Kategori</th>
                    <th class="w-40">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['kategori'] ?? [] as $kategori)
                    <tr>
                        <td>{{ $kategori }}</td>
                        <td>
                            <div class="action d-flex flex-row align-items-center justify-content-center"> 
                                @if (!in_array(strtolower($kategori), ['seminar 1', 'seminar 2']))
                                    <button type="button" class="btn btn-primary me-2">Kunci Penilaian</button>
                                @endif
                                <a href="{{ url('kelola-penilaian-ta/detail/' . Str::slug($kategori)) }}" class="btn btn-primary">Buka Detail</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="action d-flex justify-content-end">
            <button type="button" class="btn btn-primary">Kunci Semua Penilaian</button>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/kelola_penilaian_ta.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop