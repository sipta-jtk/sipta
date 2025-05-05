@extends('adminlte::page')

@section('title', 'Pengelolaan Formulir Penilaian')

@section('content_header')
<div class="container-fluid p-2">
    <!-- Judul Halaman -->
    <h1 class="mb-0">Pengelolaan Formulir Penilaian</h1>

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
    <div class="p-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Daftar Formulir Penilaian TA</h3>
            <a href="{{ route('formulir-penilaian.tambah-aspek-formulir') }}" class="btn btn-primary btn-md my-1" title="Tambah Formulir Penilaian">
                Tambah <i class="mx-1 fas fa-plus"></i>
            </a>
        </div>

        <div class="table-container">
            <table id="formulirTable" class="table table-striped text-center">
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
                        <tr>
                            <td class="align-middle"></td>
                            <td class="align-middle">{{ $row->kode_fta }}</td>
                            <td class="align-middle">{{ $row->nama_fta }}</td>
                            <td class="align-middle">{{ $row->nama_prodi }}</td>
                            <td class="align-middle">{{ $row->jenis_form }}</td>
                            <td class="align-middle">
                                {{ \Carbon\Carbon::parse($row->tanggal_tenggat_pengisian)->translatedFormat('d F Y') }}
                            </td>
                            <td class="align-middle">
                                @if ($row->nama_fta == 'Dosen Pembimbing')
                                    <a href="{{ route('detail.dosen-pembimbing', ['idFta' => $row->id_fta, 'idProdi' => $row->nama_prodi]) }}" 
                                    class="btn btn-primary btn-md my-1 w-30" title="Lihat Detail">
                                        Lihat Detail
                                    </a>
                                @elseif ($row->jenis_form == 'penilaian')
                                    <a href="{{ route('detail.penilaian', ['idFta' => $row->id_fta, 'idProdi' => $row->nama_prodi]) }}" 
                                    class="btn btn-primary btn-md my-1 w-30" title="Lihat Detail">
                                        Lihat Detail
                                    </a>
                                @elseif ($row->jenis_form == 'feedback')
                                    <a href="{{ route('detail.feedback', ['idFta' => $row->id_fta, 'idProdi' => $row->nama_prodi]) }}" 
                                    class="btn btn-primary btn-md my-1 w-30" title="Lihat Detail">
                                        Lihat Detail
                                    </a>
                                @endif
                                    <a href="{{ route('aspek-penilaian.edit', $row->id_fta) }}" class="btn btn-warning btn-md my-1 w-20" title="Ubah Formulir">
                                        <i class="mx-1 fas fa-edit"></i>
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