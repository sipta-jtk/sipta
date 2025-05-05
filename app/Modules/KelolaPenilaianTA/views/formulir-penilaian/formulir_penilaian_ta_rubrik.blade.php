@extends('adminlte::page')

@section('title', 'Pengelolaan Rubrik Penilaian')

@section('content_header')
<div class="container-fluid p-2">
    <!-- Judul Halaman -->
    <h1 class="mb-0">Pengelolaan Rubrik Penilaian</h1>

    @component('KelolaPenilaianTA.views.components.breadcrumb', [
        'links' => [
            ['url' => route('beranda.get'), 'label' => 'Beranda'],
            ['url' => '', 'label' => 'Formulir Penilaian']
        ]
    ])
    @endcomponent
</div>
@stop

@section('content')
<div class="card p-4">
    <div class="p-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Daftar Rubrik Penilaian TA</h3>
        </div>

        <div class="table-container">
            <table id="formulirTable" class="table table-striped text-center">
                <thead class="sticky-header bg-dark text-white">
                    <tr>
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
                        <tr>
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $row->kode_fta }}</td>
                            <td class="align-middle">{{ $row->nama_fta }}</td>
                            <td class="align-middle">{{ $row->nama_prodi }}</td>
                            <td class="align-middle">{{ $row->jenis_form }}</td>
                            <td class="align-middle">
                                {{ \Carbon\Carbon::parse($row->tanggal_tenggat_pengisian)->translatedFormat('d F Y') }}
                            </td>
                            <td class="align-middle">
                                @if ($row->hasRubrik)
                                    <a href="{{ route('formulir-penilaian.rubrik.edit', $row->id_fta) }}" 
                                    class="btn btn-warning btn-md my-1 w-30" title="Ubah Rubrik">
                                        Ubah <i class="mx-1 fas fa-edit"></i>
                                    </a>
                                @else
                                    <a href="{{ route('formulir-penilaian.rubrik.tambah', $row->id_fta) }}" 
                                    class="btn btn-primary btn-md my-1 w-30" title="Tambah Rubrik">
                                        Tambah <i class="mx-1 fas fa-plus"></i>
                                    </a>
                                @endif
                            </td>                            
                        </tr>
                    @endforeach
                </tbody>                
            </table>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/formulir_penilaian.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/formulir_penilaian.js') }}"></script>
@stop
