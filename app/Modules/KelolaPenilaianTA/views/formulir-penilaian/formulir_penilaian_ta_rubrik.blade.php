@extends('adminlte::page')

@section('title', 'Pengelolaan Formulir Penilaian')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => '', 'label' => 'Formulir Penilaian']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Pengelolaan Formulir Penilaian</h1>
    </div>
@stop

@section('content')
    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Daftar Rubrik Penilaian TA</h3>
        </div>

        <div class="table-container">
            <table id="formulirTable" class="table text-center">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th style="width: 3%;">No</th>
                        <th style="width: 12%;">Kode Formulir</th>
                        <th style="width: 19%;">Nama Formulir</th>
                        <th style="width: 12%;">Program Studi</th>
                        <th style="width: 19%;">Jenis Formulir</th>
                        <th style="width: 15%;">Tanggal Tenggat Pengisian</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr class="bg-light">
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $row->kode_fta }}</td>
                            <td class="align-middle">{{ $row->nama_fta }}</td>
                            <td class="align-middle">{{ $row->id_prodi }}</td>
                            <td class="align-middle">{{ $row->jenis_form }}</td>
                            <td class="align-middle">{{ date('d-m-Y', strtotime($row->tanggal_tenggat_pengisian)) }}</td>
                            <td class="align-middle">
                                @if ($row->hasRubrik)
                                    <a href="{{ route('formulir-penilaian.rubrik.edit', $row->id_fta) }}" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                @else
                                    <a href="{{ route('formulir-penilaian.rubrik.tambah', $row->id_fta) }}" class="btn btn-warning btn-sm">
                                        <i class="fa-solid fa-plus"></i>
                                    </a>
                                @endif
                            </td>                            
                        </tr>
                    @endforeach
                </tbody>                
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/formulir_penilaian.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/formulir_penilaian.js') }}"></script>
@stop