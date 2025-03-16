@extends('adminlte::page')

@section('title', 'KelolaPenilaianTA')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => '', 'label' =>  $data['header'] ]
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">{{ $data['header'] }}</h1>
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
                @foreach ($kategori as $data)
                    <tr>
                        <td>{{ $data['nama_kategori'] }}</td>
                        <td>
                            <div class="action d-flex flex-row align-items-center justify-content-center"> 
                                @if (!in_array(strtolower($data['nama_kategori']), ['seminar 1', 'seminar 2']))
                                    <button type="button" class="btn btn-primary me-2">Kunci Penilaian</button>
                                @endif
                                <a href="{{ url('kelola-penilaian-ta/detail/' . Str::slug($data['nama_kategori'])) }}" class="btn btn-primary buka-detail" data-id="{{ $data['id_kategori'] }}">Buka Detail</a>
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
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/kelola_penilaian_ta.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="{{ asset('KelolaPenilaianTA/js/kelola_penilaian_ta.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
@stop