@extends('adminlte::page')

@section('title', 'Pengelolaan Formulir Penilaian')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => route('beranda.get'), 'label' => 'Home'],
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
            <h3>Daftar Formulir Penilaian TA</h3>
            <a href="{{ route('formulir-penilaian.tambah-aspek-formulir') }}" class="btn btn-dark">
                <i class="fa-solid fa-plus"></i> Tambah
            </a>
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
                                @if ($row->jenis_form == 'penilaian')
                                    <a href="{{ route('detail.penilaian', ['idFta' => $row->id_fta, 'idProdi' => $row->id_prodi]) }}" 
                                       class="btn btn-info btn-sm">
                                        Lihat Detail
                                    </a>
                                @elseif ($row->jenis_form == 'feedback')
                                    <a href="{{ route('detail.feedback', ['idFta' => $row->id_fta, 'idProdi' => $row->id_prodi]) }}" 
                                       class="btn btn-info btn-sm">
                                        Lihat Detail
                                    </a>
                                @endif
                                <a href="{{ route('aspek-penilaian.edit', $row->id_fta) }}" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen"></i>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/formulir_penilaian.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/formulir_penilaian.js') }}"></script>
@stop