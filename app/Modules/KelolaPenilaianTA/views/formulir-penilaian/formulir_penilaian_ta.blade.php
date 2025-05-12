@extends('adminlte::page')

@section('title', 'Pengelolaan Formulir Penilaian')

@section('content_header')
<div class="container-fluid p-2">
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
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body p-3">
            {{-- Filter Toggle Button --}}
            <div class="d-flex justify-content-between mb-3">
                <button id="toggleFilter" class="btn btn-primary btn-md">
                    <i class="fas fa-filter"></i>
                </button>
            </div>

            {{-- Filter Section --}}
            <div id="filterSection" class="mt-3 mb-3" style="display: none;">
                <div class="card mt-3">
                    <div class="card-header">
                        <i class="fas fa-filter"></i> Filter Data
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filterProdi"><i class="fas fa-university"></i> Program Studi</label>
                                <select id="filterProdi" class="form-control">
                                    <option value="">Semua Program Studi</option>
                                    <option value="D3-Teknik Informatika">D3-Teknik Informatika</option>
                                    <option value="D4-Teknik Informatika">D4-Teknik Informatika</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filterNamaForm"><i class="fas fa-file-alt"></i> Nama Formulir</label>
                                <select id="filterNamaForm" class="form-control">
                                    <option value="">Semua Nama Formulir</option>
                                    <option value="Seminar I">Seminar I</option>
                                    <option value="Seminar II">Seminar II</option>
                                    <option value="Seminar III">Seminar III</option>
                                    <option value="Sidang Akhir">Sidang Akhir</option>
                                    <option value="Dosen Pembimbing">Dosen Pembimbing</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filterJenisForm"><i class="fas fa-file-alt"></i> Jenis Formulir</label>
                                <select id="filterJenisForm" class="form-control">
                                    <option value="">Semua Jenis Formulir</option>
                                    <option value="penilaian">Penilaian</option>
                                    <option value="feedback">Feedback</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button id="applyFilter" class="btn btn-primary">Terapkan</button>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center mb-3">
                <a href="{{ route('formulir-penilaian.tambah-aspek-formulir') }}" class="btn btn-primary btn-md my-1" title="Tambah Formulir Penilaian">
                    Tambah <i class="mx-1 fas fa-plus"></i>
                </a>
            </div>

            {{-- DataTables Controls (Jumlah data & Search) --}}
            <div class="d-flex justify-content-between mb-3">
                <div id="dataTableControls"></div>
                <div id="searchBox"></div>
            </div>

            {{-- Tabel Scrollable --}}
            <div class="table-container">
                <table id="formulirTable" class="table table-striped table-bordered text-center" width="100%">
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
                        @if(empty($data) || count($data) == 0)
                        <tr>
                            <td colspan="7" class="text-center">Data tidak tersedia</td>
                        </tr>
                        @else
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
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-1">
                <div id="infoControls"></div>
                <div id="paginationControls"></div>
            </div>
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