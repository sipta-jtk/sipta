@extends('adminlte::page')

@section('title', 'Dokumen Sidang')

@section('content_header')
<h1 class="mb-3 text-left">Dokumen Sidang</h1>
@stop


@section('content')

{{-- Navigasi Tab --}}
<ul class="nav nav-tabs mb-3" id="tabsidang" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-laporan-tab" data-toggle="tab" data-target="#tab-laporan" type="button" role="tab" aria-controls="tab-laporan" aria-selected="true">Laporan</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-fta-tab" data-toggle="tab" data-target="#tab-fta" type="button" role="tab" aria-controls="tab-fta" aria-selected="false">FTA</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-srs-tab" data-toggle="tab" data-target="#tab-srs" type="button" role="tab" aria-controls="tab-srs" aria-selected="false">Dokumen Teknis</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-pp-tab" data-toggle="tab" data-target="#tab-pp" type="button" role="tab" aria-controls="tab-pp" aria-selected="false">Dokumen Lainnya</button>
    </li>
</ul>

<div class="tab-content" id="tabsidangContent">
    {{-- Tab: Laporan --}}
    <div class="tab-pane fade show active" id="tab-laporan" role="tabpanel" aria-labelledby="tab-laporan-tab">
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <h3 class="card-title m-0 text-center text-bold">Laporan Sidang AKhir</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap px-2 mb-3">
                    <div class="d-flex align-items-center">
                        <!-- Tombol Filter -->
                        <button class="btn btn-primary btn-md mr-2" type="button" data-toggle="collapse" data-target="#filterMenuLaporan">
                            <i class="fas fa-filter"></i>
                        </button>

                        <!-- Length Change (Akan diisi otomatis oleh DataTables) -->
                        <div id="custom-length"></div>
                    </div>

                    <!-- Tombol Tambah -->
                    @if ($status_ta === 'mahasiswa_ta')
                    <button id="btn-tambah-laporan" onclick="TambahDokumen('btn-tambah-laporan', '{{ $subkategoriLaporan->id_subkategori }}')" class="btn btn-primary btn-md" data-toggle="modal" data-target="#TambahDokumen" data-id-subkategori="{{ $subkategoriLaporan->id_subkategori }}">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                    @endif
                </div>


                <div class="collapse filter-menu" id="filterMenuLaporan" data-target="laporan">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-2"></i>Filter Data
                            </h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Versi</label>
                                    <select class="form-control version-filter">
                                        <option value="">Semua</option>
                                        @for ($i = 1; $i <= 15; $i++)
                                            <option value="{{ $i }}">V{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Tanggal Dibuat</label>
                                    <input type="date" class="form-control date-filter">
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary apply-filter-btn">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>


                <table class="table table-bordered text-center datatable" data-table="laporan">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="no-sort">No</th>
                            <th>Versi</th>
                            <th>Judul</th>
                            <th>Tanggal Dibuat</th>
                            <th>Terakhir Diedit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($laporan->isEmpty())
                        <tr>
                            <td colspan="{{ $kategori === 'fta' ? 7 : 6 }}" class="text-center">
                                <p class="text-muted">List Dokumen Kosong, Belum Ada Dokumen Yang Ditambahkan</p>
                            </td>
                        </tr>
                        @else
                        @foreach ($laporan as $index => $doc)
                        <tr>
                            <td></td>
                            <td>{{ $doc->versi }}</td>
                            <td>{{ $doc->judul }}</td>

                            <td>{{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($doc->updated_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>
                                @if ($status_ta === 'mahasiswa_ta')
                                <!-- Delete button -->
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="HapusDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-toggle="modal" data-target="#HapusDokumen">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Edit button -->
                                <button class="btn btn-sm btn-outline-primary"
                                    onclick="EditDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}','')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-versi="{{ $doc->versi }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-toggle="modal" data-target="#UbahDokumen">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- View button -->
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-notes="{{ $doc->notes }}"
                                    data-notes_koor="{{ $doc->notes_koordinator }}"
                                    data-toggle="modal"
                                    data-target="#LihatDokumen">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @endif

                                @if ($isPembimbing || $isKoordinator)
                                <!-- View button -->
                                <div class="d-flex flex-row justify-content-center">
                                    <button class="btn btn-sm btn-outline-success"
                                        onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                        data-id="{{ $doc->id_dokumen }}"
                                        data-judul="{{ $doc->judul }}"
                                        data-file="{{ $doc->file_path }}"
                                        data-deskripsi="{{ $doc->deskripsi }}"
                                        data-notes="{{ $doc->notes }}"
                                        data-notes_koor="{{ $doc->notes_koordinator }}"
                                        data-toggle="modal"
                                        data-target="#LihatDokumen">
                                        <i class="fas fa-edit mr-1"></i> Detail
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- Tab: FTA --}}
    <div class="tab-pane fade" id="tab-fta" role="tabpanel" aria-labelledby="tab-fta-tab">
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <h3 class="card-title m-0 text-center text-bold">Formulir Tugas Akhir (FTA)</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap px-2 mb-3">
                    <div class="d-flex align-items-center">
                        <!-- Tombol Filter -->
                        <button class="btn btn-primary btn-md mr-2" type="button" data-toggle="collapse" data-target="#filterMenuFta">
                            <i class="fas fa-filter"></i>
                        </button>

                        <!-- Length Change untuk FTA -->
                        <div id="custom-length-fta"></div>
                    </div>

                    <!-- Tombol Tambah -->
                    @if ($status_ta === 'mahasiswa_ta')
                    <button id="btn-tambah-fta" onclick="TambahDokumen('btn-tambah-fta', '{{ $subkategoriFta->id_subkategori }}')" class="btn btn-primary btn-md" data-toggle="modal" data-target="#TambahDokumen" data-id-subkategori="{{ $subkategoriFta->id_subkategori }}">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                    @endif
                </div>

                <div class="collapse filter-menu" id="filterMenuFta" data-target="fta">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-2"></i>Filter Data
                            </h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Versi</label>
                                    <select class="form-control version-filter">
                                        <option value="">Semua</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">V{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Tanggal Dibuat</label>
                                    <input type="date" class="form-control date-filter">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group text-secondary">
                                        <label>
                                            <i class="fas fa-project-diagram mr-1"></i> Kode FTA
                                        </label>
                                        <select class="form-control select2bs4" name="kode_fta" style="width: 100%;">
                                            <option value="FTA-13">FTA-13 - Persetujuan pelaksanaan sidan tugas akhir</option>
                                            <option value="FTA-14">FTA 14 - Bukti Bimbingan untuk Sidang TA</option>
                                            <option value="FTA-14a">FTA-14a - Resume bimbingan</option>
                                            <option value="FTA-15">FTA-15 - Penilaian sidang</option>
                                            <option value="FTA-16">FTA-16 - Berita acara pelaksanaan sidang</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary apply-filter-btn">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered text-center datatable" data-table="fta">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="no-sort">No</th>
                            <th>Kode-FTA</th>
                            <th>Versi</th>
                            <th>Judul</th>
                            <th>Tanggal Dibuat</th>
                            <th>Terakhir Diedit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($fta->isEmpty())
                        <tr>
                            <td colspan="{{ $kategori === 'fta' ? 7 : 6 }}" class="text-center">
                                <p class="text-muted">List Dokumen Kosong, Belum Ada Dokumen Yang Ditambahkan</p>
                            </td>
                        </tr>
                        @else
                        @foreach ($fta as $index => $doc)
                        <tr>
                            <td></td>
                            <td>{{ $doc->kode_fta }}</td>
                            <td>{{ $doc->versi }}</td>
                            <td>{{ $doc->judul }}</td>

                            <td>{{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($doc->updated_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>
                                @if ($status_ta === 'mahasiswa_ta')
                                <!-- Delete button -->
                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                    onclick="HapusDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-toggle="modal" data-target="#HapusDokumen">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Edit button -->
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    onclick="EditDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}','')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-versi="{{ $doc->versi }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-toggle="modal" data-target="#UbahDokumen">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- View button -->
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-notes="{{ $doc->notes }}"
                                    data-notes_koor="{{ $doc->notes_koordinator }}"
                                    data-toggle="modal"
                                    data-target="#LihatDokumen">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @endif

                                @if ($isPembimbing || $isKoordinator)
                                <!-- View button -->
                                <div class="d-flex flex-row justify-content-center">
                                    <button class="btn btn-sm btn-outline-success"
                                        onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                        data-id="{{ $doc->id_dokumen }}"
                                        data-judul="{{ $doc->judul }}"
                                        data-file="{{ $doc->file_path }}"
                                        data-deskripsi="{{ $doc->deskripsi }}"
                                        data-notes="{{ $doc->notes }}"
                                        data-notes_koor="{{ $doc->notes_koordinator }}"
                                        data-toggle="modal"
                                        data-target="#LihatDokumen">
                                        <i class="fas fa-edit mr-1"></i> Detail
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tab: Power Point --}}
    <div class="tab-pane fade" id="tab-pp" role="tabpanel" aria-labelledby="tab-pp-tab">
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <h3 class="card-title m-0 text-center text-bold">Dokumen Lainnya</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap px-2 mb-3">
                    <div class="d-flex align-items-center">
                        <!-- Tombol Filter -->
                        <button class="btn btn-primary btn-md mr-2" type="button" data-toggle="collapse" data-target="#filterMenuPpt">
                            <i class="fas fa-filter"></i>
                        </button>

                        <!-- Length Change untuk PowerPoint -->
                        <div id="custom-length-ppt"></div>
                    </div>

                    <!-- Tombol Tambah -->
                    @if ($status_ta === 'mahasiswa_ta')
                    <button id="btn-tambah-lainnya" onclick="TambahDokumen('btn-tambah-lainnya', '{{ $subkategoriPpt->id_subkategori }}')" class="btn btn-primary btn-md" data-toggle="modal" data-target="#TambahDokumen" data-id-subkategori="{{ $subkategoriPpt->id_subkategori }}">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                    @endif
                </div>

                <div class="collapse filter-menu" id="filterMenuPpt" data-target="ppt">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-2"></i>Filter Data
                            </h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Versi</label>
                                    <select class="form-control version-filter">
                                        <option value="">Semua</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">V{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Tanggal Dibuat</label>
                                    <input type="date" class="form-control date-filter">
                                </div>
                                <div class="col-md-4">
                                    <label>Jenis Dokumen</label>
                                    <select class="form-control doctype-filter">
                                        <option value="">Semua</option>
                                        <option value="PowerPoint">PowerPoint</option>
                                        <option value="Poster">Poster</option>
                                        <option value="Template">Template</option>
                                        <option value="Resume">Resume</option>
                                        <option value="Lampiran">Lampiran</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary apply-filter-btn">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered text-center datatable" data-table="ppt">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="no-sort">No</th>
                            <th>Versi</th>
                            <th>Judul</th>
                            <th>Jenis Dokumen</th>
                            <th>Tanggal Dibuat</th>
                            <th>Terakhir Diedit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($powerpoint->isEmpty())
                        <tr>
                            <td colspan="{{ $kategori === 'fta' ? 7 : 6 }}" class="text-center">
                                <p class="text-muted">List Dokumen Kosong, Belum Ada Dokumen Yang Ditambahkan</p>
                            </td>
                        </tr>
                        @else
                        @foreach ($powerpoint as $index => $doc)
                        <tr>
                            <td></td>
                            <td>{{ $doc->versi }}</td>
                            <td>{{ $doc->judul }}</td>
                            <td>{{ $doc->subkategori->nama_subkategori }}</td>
                            <td>{{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($doc->updated_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>
                                @if ($status_ta === 'mahasiswa_ta')
                                <!-- Delete button -->
                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                    onclick="HapusDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-toggle="modal" data-target="#HapusDokumen">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Edit button -->
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    onclick="EditDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}','')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-versi="{{ $doc->versi }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-toggle="modal" data-target="#UbahDokumen">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- View button -->
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-notes="{{ $doc->notes }}"
                                    data-notes_koor="{{ $doc->notes_koordinator }}"
                                    data-toggle="modal"
                                    data-target="#LihatDokumen">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @endif

                                @if ($isPembimbing || $isKoordinator)
                                <!-- View button -->
                                <div class="d-flex flex-row justify-content-center">
                                    <button class="btn btn-sm btn-outline-success"
                                        onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                        data-id="{{ $doc->id_dokumen }}"
                                        data-judul="{{ $doc->judul }}"
                                        data-file="{{ $doc->file_path }}"
                                        data-deskripsi="{{ $doc->deskripsi }}"
                                        data-notes="{{ $doc->notes }}"
                                        data-notes_koor="{{ $doc->notes_koordinator }}"
                                        data-toggle="modal"
                                        data-target="#LihatDokumen">
                                        <i class="fas fa-edit mr-1"></i> Detail
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- Tab: Dokumen Teknis --}}
    <div class="tab-pane fade" id="tab-srs" role="tabpanel" aria-labelledby="tab-srs-tab">
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <h3 class="card-title m-0 text-center text-bold">Dokumen Teknis Sidang</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap px-2 mb-3">
                    <div class="d-flex align-items-center">
                        <!-- Tombol Filter -->
                        <button class="btn btn-primary btn-md mr-2" type="button" data-toggle="collapse" data-target="#filterMenuSrs">
                            <i class="fas fa-filter"></i>
                        </button>

                        <!-- Length Change (Akan diisi otomatis oleh DataTables) -->
                        <div id="custom-length-srs"></div>
                    </div>

                    <!-- Tombol Tambah -->
                    @if ($status_ta === 'mahasiswa_ta')
                    <button id="btn-tambah-teknis" onclick="TambahDokumen('btn-tambah-teknis', '{{ $subkategoriSrs->id_subkategori }}')" class="btn btn-primary btn-md" data-toggle="modal" data-target="#TambahDokumen" data-id-subkategori="{{ $subkategoriSrs->id_subkategori }}">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                    @endif
                </div>


                <div class="collapse filter-menu" id="filterMenuSrs" data-target="srs">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-2"></i>Filter Data
                            </h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Versi</label>
                                    <select class="form-control version-filter">
                                        <option value="">Semua</option>
                                        @for ($i = 1; $i <= 15; $i++)
                                            <option value="{{ $i }}">V{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Jenis Dokumen</label>
                                    <select class="form-control doctype-filter">
                                        <option value="">Semua</option>
                                        <option value="SRS">Software Requirements Specification (SRS)</option>
                                        <option value="SDD">Software Design Document (SDD)</option>
                                        <option value="SAD">Software Architecture Document (SAD)</option>
                                        <option value="STD">Software Test Document (STD)</option>
                                        <option value="SOP">Standard Operating Procedure (SOP)</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Tanggal Dibuat</label>
                                    <input type="date" class="form-control date-filter">
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary apply-filter-btn">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>


                <table class="table table-bordered text-center datatable" data-table="srs">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="no-sort">No</th>
                            <th>Versi</th>
                            <th>Judul</th>
                            <th>Jenis Dokumen</th>
                            <th>Tanggal Dibuat</th>
                            <th>Terakhir Diedit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($srs->isEmpty())
                        <tr>
                            <td colspan="{{ $kategori === 'fta' ? 8 : 7 }}" class="text-center">
                                <p class="text-muted">List Dokumen Kosong, Belum Ada Dokumen Yang Ditambahkan</p>
                            </td>
                        </tr>
                        @else
                        @foreach ($srs as $index => $doc)
                        <tr>
                            <td></td>
                            <td>{{ $doc->versi }}</td>
                            <td>{{ $doc->judul }}</td>
                            <td>{{ $doc->subkategori->nama_subkategori }}</td>
                            <td>{{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($doc->updated_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>
                                @if ($status_ta === 'mahasiswa_ta')
                                <!-- Delete button -->
                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                    onclick="HapusDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-toggle="modal" data-target="#HapusDokumen">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Edit button -->
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    onclick="EditDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}','')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-versi="{{ $doc->versi }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-toggle="modal" data-target="#UbahDokumen">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- View button -->
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-notes="{{ $doc->notes }}"
                                    data-notes_koor="{{ $doc->notes_koordinator }}"
                                    data-toggle="modal"
                                    data-target="#LihatDokumen">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @endif

                                @if ($isPembimbing || $isKoordinator)
                                <!-- View button -->
                                <div class="d-flex flex-row justify-content-center">
                                    <button class="btn btn-sm btn-outline-success"
                                        onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                        data-id="{{ $doc->id_dokumen }}"
                                        data-judul="{{ $doc->judul }}"
                                        data-file="{{ $doc->file_path }}"
                                        data-deskripsi="{{ $doc->deskripsi }}"
                                        data-notes="{{ $doc->notes }}"
                                        data-notes_koor="{{ $doc->notes_koordinator }}"
                                        data-toggle="modal"
                                        data-target="#LihatDokumen">
                                        <i class="fas fa-edit mr-1"></i> Detail
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tab: SDD --}}
    <div class="tab-pane fade" id="tab-sdd" role="tabpanel" aria-labelledby="tab-sdd-tab">
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <h3 class="card-title m-0 text-center text-bold">Dokumen SDD Sidang</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap px-2 mb-3">
                    <div class="d-flex align-items-center">
                        <!-- Tombol Filter -->
                        <button class="btn btn-primary btn-md mr-2" type="button" data-toggle="collapse" data-target="#filterMenuSdd">
                            <i class="fas fa-filter"></i>
                        </button>

                        <!-- Length Change (Akan diisi otomatis oleh DataTables) -->
                        <div id="custom-length-sdd"></div>
                    </div>

                    <!-- Tombol Tambah -->
                    @if ($status_ta === 'mahasiswa_ta')
                    <button id="btn-tambah-sdd" onclick="TambahDokumen('btn-tambah-sdd', '{{ $subkategoriSdd->id_subkategori }}')" class="btn btn-primary btn-md" data-toggle="modal" data-target="#TambahDokumen" data-id-subkategori="{{ $subkategoriSdd->id_subkategori }}">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                    @endif
                </div>


                <div class="collapse filter-menu" id="filterMenuSdd" data-target="sdd">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-2"></i>Filter Data
                            </h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Versi</label>
                                    <select class="form-control version-filter">
                                        <option value="">Semua</option>
                                        @for ($i = 1; $i <= 15; $i++)
                                            <option value="{{ $i }}">V{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Tanggal Dibuat</label>
                                    <input type="date" class="form-control date-filter">
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary apply-filter-btn">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>


                <table class="table table-bordered text-center datatable" data-table="sdd">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="no-sort">No</th>
                            <th>Versi</th>
                            <th>Judul</th>
                            <th>Tanggal Dibuat</th>
                            <th>Terakhir Diedit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($sdd->isEmpty())
                        <tr>
                            <td colspan="{{ $kategori === 'fta' ? 7 : 6 }}" class="text-center">
                                <p class="text-muted">List Dokumen Kosong, Belum Ada Dokumen Yang Ditambahkan</p>
                            </td>
                        </tr>
                        @else
                        @foreach ($sdd as $index => $doc)
                        <tr>
                            <td></td>
                            <td>{{ $doc->versi }}</td>
                            <td>{{ $doc->judul }}</td>

                            <td>{{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($doc->updated_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>
                                @if ($status_ta === 'mahasiswa_ta')
                                <!-- Delete button -->
                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                    onclick="HapusDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-toggle="modal" data-target="#HapusDokumen">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Edit button -->
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    onclick="EditDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}','')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-versi="{{ $doc->versi }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-toggle="modal" data-target="#UbahDokumen">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- View button -->
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-notes="{{ $doc->notes }}"
                                    data-notes_koor="{{ $doc->notes_koordinator }}"
                                    data-toggle="modal"
                                    data-target="#LihatDokumen">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @endif

                                @if ($isPembimbing || $isKoordinator)
                                <!-- View button -->
                                <div class="d-flex flex-row justify-content-center">
                                    <button class="btn btn-sm btn-outline-success"
                                        onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                        data-id="{{ $doc->id_dokumen }}"
                                        data-judul="{{ $doc->judul }}"
                                        data-file="{{ $doc->file_path }}"
                                        data-deskripsi="{{ $doc->deskripsi }}"
                                        data-notes="{{ $doc->notes }}"
                                        data-notes_koor="{{ $doc->notes_koordinator }}"
                                        data-toggle="modal"
                                        data-target="#LihatDokumen">
                                        <i class="fas fa-edit mr-1"></i> Detail
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tab: Poster --}}
    <div class="tab-pane fade" id="tab-poster" role="tabpanel" aria-labelledby="tab-poster-tab">
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <h3 class="card-title m-0 text-center text-bold">Poster</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap px-2 mb-3">
                    <div class="d-flex align-items-center">
                        <!-- Tombol Filter -->
                        <button class="btn btn-primary btn-md mr-2" type="button" data-toggle="collapse" data-target="#filterMenuPoster">
                            <i class="fas fa-filter"></i>
                        </button>

                        <!-- Length Change (Akan diisi otomatis oleh DataTables) -->
                        <div id="custom-length-poster"></div>
                    </div>

                    <!-- Tombol Tambah -->
                    @if ($status_ta === 'mahasiswa_ta')
                    <button id="btn-tambah-poster" onclick="TambahDokumen('btn-tambah-poster', '{{ $subkategoriPoster->id_subkategori }}')" class="btn btn-primary btn-md" data-toggle="modal" data-target="#TambahDokumen" data-id-subkategori="{{ $subkategoriPoster->id_subkategori }}">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                    @endif
                </div>


                <div class="collapse filter-menu" id="filterMenuPoster" data-target="poster">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-2"></i>Filter Data
                            </h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Versi</label>
                                    <select class="form-control version-filter">
                                        <option value="">Semua</option>
                                        @for ($i = 1; $i <= 15; $i++)
                                            <option value="{{ $i }}">V{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Tanggal Dibuat</label>
                                    <input type="date" class="form-control date-filter">
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary apply-filter-btn">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>


                <table class="table table-bordered text-center datatable" data-table="poster">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="no-sort">No</th>
                            <th>Versi</th>
                            <th>Judul</th>
                            <th>Tanggal Dibuat</th>
                            <th>Terakhir Diedit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($poster->isEmpty())
                        <tr>
                            <td colspan="{{ $kategori === 'fta' ? 7 : 6 }}" class="text-center">
                                <p class="text-muted">List Dokumen Kosong, Belum Ada Dokumen Yang Ditambahkan</p>
                            </td>
                        </tr>
                        @else
                        @foreach ($poster as $index => $doc)
                        <tr>
                            <td></td>
                            <td>{{ $doc->versi }}</td>
                            <td>{{ $doc->judul }}</td>

                            <td>{{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($doc->updated_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>
                                @if ($status_ta === 'mahasiswa_ta')
                                <!-- Delete button -->
                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                    onclick="HapusDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-toggle="modal" data-target="#HapusDokumen">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Edit button -->
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    onclick="EditDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}','')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-versi="{{ $doc->versi }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-toggle="modal" data-target="#UbahDokumen">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- View button -->
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-notes="{{ $doc->notes }}"
                                    data-notes_koor="{{ $doc->notes_koordinator }}"
                                    data-toggle="modal"
                                    data-target="#LihatDokumen">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @endif

                                @if ($isPembimbing || $isKoordinator)
                                <!-- View button -->
                                <div class="d-flex flex-row justify-content-center">
                                    <button class="btn btn-sm btn-outline-success"
                                        onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}', '{{ $doc->notes_koordinator }}')"
                                        data-id="{{ $doc->id_dokumen }}"
                                        data-judul="{{ $doc->judul }}"
                                        data-file="{{ $doc->file_path }}"
                                        data-deskripsi="{{ $doc->deskripsi }}"
                                        data-notes="{{ $doc->notes }}"
                                        data-notes_koor="{{ $doc->notes_koordinator }}"
                                        data-toggle="modal"
                                        data-target="#LihatDokumen">
                                        <i class="fas fa-edit mr-1"></i> Detail
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- BAGIAN MODAL -->

<!-- TAMBAH DOKUMEN -->
<x-adminlte-modal id="TambahDokumen" title="Tambah Dokumen Sidang" theme="blue" size="lg">
    <form id="addDocumentForm" action="{{ route('Repository.store', $kategori) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_subkategori" id="id_subkategori_hidden" value="">
        <input type="hidden" name="dokumen_teknis_type" id="dokumen_teknis_type" value="">
        <input type="hidden" name="dokumen_lainnya_type" id="dokumen_lainnya_type" value="">
        <div class="row px-3">
            <div class="col-md-10 mb-2">
                <p class="text-secondary text-md border-bottom">Informasi Dokumen</p>

                <!-- Dropdown subkategori untuk dokumen teknis -->
                <div id="field-subkategori-teknis" style="display:none;">
                    <label for="subkategori_teknis">Jenis Dokumen Teknis</label>
                    <x-adminlte-select name="subkategori_teknis" id="subkategori_teknis" class="select2bs4">
                        <option selected disabled>Pilih Jenis Dokumen Teknis</option>
                        <option value="SRS">Software Requirements Specification (SRS)</option>
                        <option value="SDD">Software Design Document (SDD)</option>
                        <option value="SAD">Software Architecture Document (SAD)</option>
                        <option value="STD">Software Test Document (STD)</option>
                        <option value="SOP">Standard Operating Procedure (SOP)</option>
                    </x-adminlte-select>
                </div>

                <!-- Dropdown subkategori untuk dokumen lainnya -->
                <div id="field-subkategori-lainnya" style="display:none;">
                    <label for="subkategori_lainnya">Jenis Dokumen</label>
                    <x-adminlte-select name="subkategori_lainnya" id="subkategori_lainnya" class="select2bs4">
                        <option selected disabled>Pilih Jenis Dokumen</option>
                        <option value="PowerPoint">PowerPoint</option>
                        <option value="Poster">Poster</option>
                        <option value="Template">Template</option>
                        <option value="Resume">Resume</option>
                        <option value="Lampiran">Lampiran</option>
                    </x-adminlte-select>
                </div>

                <label for="judul">Judul Dokumen</label>
                <x-adminlte-input name="judul" id="judul" required />

                <div id="field-kode-fta" style="display:none;">
                    <label for="kode_fta">Kode FTA</label>
                    <x-adminlte-select name="kode_fta" id="kode_fta" class="select2bs4">
                        <option selected disabled>Pilih Kode FTA</option>

                        @php
                        $ftaOptions = [
                        ['value' => 'FTA-13', 'text' => 'FTA-13 - Persetujuan pelaksanaan sidan tugas akhir'],
                        ['value' => 'FTA-14', 'text' => 'FTA 14 - Bukti Bimbingan untuk Sidang TA'],
                        ['value' => 'FTA-14a', 'text' => 'FTA-14a - Resume bimbingan'],
                        ['value' => 'FTA-15', 'text' => 'FTA-15 - Penilaian & Masukan Sidang'],
                        ['value' => 'FTA-16', 'text' => 'FTA-16 - Berita acara pelaksanaan sidang'],
                        ];
                        @endphp

                        @foreach ($ftaOptions as $fta)
                        <option value="{{ $fta['value'] }}">{{ $fta['text'] }}</option>
                        @endforeach
                    </x-adminlte-select>
                </div>

                <label for="deskripsi">Deskripsi</label>
                <x-adminlte-textarea name="deskripsi" id="deskripsi" required placeholder="Berikan penjelasan terkait dokumen" rows="4" />

                <div class="form-group w-100">
                    <label for="file">File</label>
                    <x-adminlte-input-file onchange="JudulDokumen()" name="file" id="file" igroup-size="md" required />
                </div>

                <label for="username">Pengunggah</label>
                <x-adminlte-input name="username" id="username"
                    value="{{ auth()->user()->username }}" readonly required />
            </div>
        </div>

        <div class="d-flex pt-3 justify-content-end">
            <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal" class="mx-1" />
            <button type="submit" class="btn btn-success mx-1">Simpan</button>
        </div>
    </form>

    <x-slot name="footerSlot">
        {{-- Footer kosong, supaya tidak muncul tombol Close lagi --}}
    </x-slot>
</x-adminlte-modal>

<!-- EDIT DOKUMEN -->
<x-adminlte-modal id="UbahDokumen" title="Ubah Dokumen Sidang" theme="yellow" size="xl">
    <form id="editDocumentForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_dokumen" id="edit_id_dokumen">

        <div class="row px-3">
            <div class="col-md-4 mb-2">
                <p class="text-secondary text-md border-bottom">Informasi Dokumen</p>

                <label for="edit_judul">Judul Dokumen</label>
                <x-adminlte-input name="judul" id="edit_judul" required />

                <label for="edit_deskripsi" class="mt-2">Deskripsi</label>
                <x-adminlte-textarea name="deskripsi" id="edit_deskripsi" required placeholder="Berikan penjelasan terkait dokumen" rows="4" />

                <label for="edit_username" class="mt-2">Pengunggah</label>
                <x-adminlte-input name="username" id="edit_username" readonly required />
            </div>

            <div class="col-md-8 mb-2">
                <p class="text-secondary text-md border-bottom">Dokumen Terunggah</p>

                <label class="form-label">Pratinjau Dokumen</label>
                <div class="document-preview-container" style="height: 400px; border: 1px solid #ddd;">
                    <iframe id="documentPreview" style="width: 100%; height: 100%; border: none;" src=""></iframe>
                    <div id="previewNotAvailable" class="text-center p-5" style="display: none;">
                        <i class="fas fa-file-alt fa-3x mb-3 text-secondary"></i>
                        <p>Preview tidak tersedia untuk jenis file ini</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex pt-3 justify-content-end">
            <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal" class="mx-1" />
            <button type="submit" class="btn btn-success mx-1">Simpan</button>
        </div>
    </form>

    <x-slot name="footerSlot"></x-slot>
</x-adminlte-modal>

<!-- DELETE DOKUMEN -->
<x-adminlte-modal id="HapusDokumen" title="Konfirmasi Hapus Dokumen" theme="danger" size="md" centered>
    <div class="text-center">
        <i class="fas fa-exclamation-circle text-warning mb-3" style="font-size: 3rem;"></i>
        <p class="mb-0">Apakah Anda yakin ingin menghapus dokumen</p>
        <p id="hapusDocumentTitle" class="font-weight-bold mb-3"></p>
        <p class="text-muted small">Dokumen yang sudah dihapus tidak dapat dikembalikan.</p>
    </div>

    <form id="deleteDocumentForm" method="POST">
        @csrf
        @method('DELETE')
    </form>

    <x-slot name="footerSlot">
        <div class="d-flex justify-content-end">
            <x-adminlte-button label="Batal" theme="secondary" data-dismiss="modal" class="mx-1" />
            <button type="submit" class="btn btn-danger mx-1" form="deleteDocumentForm">Hapus</button>
        </div>
    </x-slot>
</x-adminlte-modal>

<!-- LIHAT DOKUMEN -->
<x-adminlte-modal id="LihatDokumen" title="Detail Dokumen" theme="green" size="xl">
    @if ($isPembimbing)
    <form id="saveNotesForm" method="POST" action="{{ route('Repository.saveNotes', ['id' => 0]) }}">
        @csrf
        <input type="hidden" name="id_dokumen" id="view_id_dokumen">
        @endif
        <div class="row px-3">
            <div class="col-md-4 mb-2">
                <p class="text-secondary text-md border-bottom">Informasi Dokumen</p>

                <label for="view_judul">Judul Dokumen</label>
                <x-adminlte-input name="judul" id="view_judul" readonly />

                <!-- Tambahan field Kode FTA, default hidden -->
                <div id="field_kode_fta_view" style="display: none;">
                    <label for="view_kode_fta" class="mt-1">Kode FTA</label>
                    <x-adminlte-input name="kode_fta" id="view_kode_fta" readonly />
                </div>

                <label for="view_deskripsi" class="mt-1">Deskripsi</label>
                <x-adminlte-textarea name="deskripsi" id="view_deskripsi" readonly rows="4" />

                <label for="view_username">Pengunggah</label>
                <x-adminlte-input name="username" id="view_username" readonly required />

                <label class="mt-2">Aksi File</label>
                <div class="d-flex flex-column">
                    <a href="#" target="_blank" class="btn btn-primary mb-2" id="view_file_link">
                        <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                    </a>
                    <a href="#" class="btn btn-success" id="view_file_download">
                        <i class="fas fa-download"></i> Unduh
                    </a>
                </div>
            </div>

            <div class="col-md-8 mb-2">
                <p class="text-secondary text-md border-bottom">Pratinjau Dokumen</p>
                <div class="document-preview-container" style="height: 470px; border: 1px solid #ddd;">
                    <iframe id="viewDocumentPreview" style="width: 100%; height: 100%; border: none;" src=""></iframe>
                    <div id="viewPreviewNotAvailable" class="text-center p-5" style="display: none;">
                        <i class="fas fa-file-alt fa-3x mb-3 text-secondary"></i>
                        <p>Preview tidak tersedia untuk jenis file ini</p>
                    </div>
                </div>
            </div>

            @if ($status_ta === 'mahasiswa_ta')
            <div class="col-md-12 mb-2">
                <label for="view_notes" class="mt-1">Catatan Pembimbing</label>
                <x-adminlte-textarea name="notes" id="view_notes" readonly rows="4" />
            </div>
            <div class="col-md-12 mb-2">
                <label for="view_notes_koordinator" class="mt-1">Catatan Koordinator</label>
                <x-adminlte-textarea name="notes_koordinator" id="view_notes_koordinator" readonly rows="4" />
            </div>
            @endif

            @if ($isPembimbing)
            <div class="col-md-12 mb-2">
                <label for="give_notes" class="mt-1">Catatan Sebagai Pembimbing</label>
                <x-adminlte-textarea name="input_notes" id="give_notes" placeholder="Berikan catatan terkait dokumen kepada Mahasiswa" rows="4" />
            </div>
            @endif

            @if ($isKoordinator)
            <div class="col-md-12 mb-2">
                <label for="give_notes_koordinator" class="mt-1">Catatan Sebagai Koordinator</label>
                <x-adminlte-textarea name="input_notes_koordinator" id="give_notes_koordinator" placeholder="Berikan catatan terkait dokumen kepada Mahasiswa" rows="4" />
            </div>
            @endif

        </div>

        @if ($status_ta === 'mahasiswa_ta')
        <div class="d-flex pt-3 justify-content-end">
            <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal" class="mx-1" />
        </div>
        @endif

        @if ($isPembimbing)
        <div class="d-flex pt-3 justify-content-end">
            <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal" class="mx-1" />
            <button type="submit" class="btn btn-success mx-1">Simpan</button>
        </div>
    </form>
    @endif
    <x-slot name="footerSlot"></x-slot>
</x-adminlte-modal>

@stop

@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<style>
    /* Button styling */
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .input-group {
        width: 100%;
    }

    .custom-wrapper .dataTables_length {
        margin-left: auto;
    }

    #custom-length label {
        margin-bottom: 0;
    }

    .gap-2>*+* {
        margin-left: 0.5rem;
    }

    /* Tambahan penting ini! */
    .gap-2 {
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .document-preview-container {
            height: 400px;
            /* Lebih pendek di HP */
        }
    }
</style>
@stop

@section('js')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        // Handler untuk perubahan subkategori dokumen teknis
        $('#subkategori_teknis').change(function() {
            var selectedType = $(this).val();
            var srsId = '{{ $subkategoriSrs ? $subkategoriSrs->id_subkategori : "" }}';

            // Set dokumen type untuk tahu jenis dokumen yang dipilih
            if (selectedType) {
                // Simpan jenis dokumen yang dipilih (SRS, SDD, dll)
                $('#dokumen_teknis_type').val(selectedType);

                // Sementara gunakan id_subkategori SRS, nanti akan diganti di controller
                $('#id_subkategori_hidden').val(srsId);

                // Update judul dokumen dengan jenis dokumen yang dipilih sebagai informasi tambahan
                var currentJudul = $('#judul').val();
                if (!currentJudul || currentJudul.trim() === '') {
                    $('#judul').val(selectedType + ' - ');
                }
            }
        });

        // Handler untuk perubahan subkategori dokumen lainnya
        $('#subkategori_lainnya').change(function() {
            var selectedType = $(this).val();
            var pptId = '{{ $subkategoriPpt ? $subkategoriPpt->id_subkategori : "" }}';

            // Set dokumen type untuk tahu jenis dokumen yang dipilih
            if (selectedType) {
                console.log("Selected document type (lainnya):", selectedType);

                // Simpan jenis dokumen yang dipilih (PowerPoint, Poster, dll)
                $('#dokumen_lainnya_type').val(selectedType);

                // Sementara gunakan id_subkategori PowerPoint, nanti akan diganti di controller
                $('#id_subkategori_hidden').val(pptId);

                // Update judul dokumen dengan jenis dokumen yang dipilih sebagai informasi tambahan
                var currentJudul = $('#judul').val();
                if (!currentJudul || currentJudul.trim() === '') {
                    $('#judul').val(selectedType + ' - ');
                }
            }
        });

        // Validasi form sebelum submit untuk memastikan subkategori telah dipilih
        $('#addDocumentForm').submit(function(e) {
            // Validasi untuk dokumen teknis
            if ($('#field-subkategori-teknis').is(':visible') && !$('#subkategori_teknis').val()) {
                e.preventDefault();
                alert('Pilih jenis dokumen teknis terlebih dahulu');
                return false;
            }

            // Validasi untuk dokumen lainnya
            if ($('#field-subkategori-lainnya').is(':visible') && !$('#subkategori_lainnya').val()) {
                e.preventDefault();
                alert('Pilih jenis dokumen lainnya terlebih dahulu');
                return false;
            }
        });
        // $('#addDocumentForm, #editDocumentForm, #deleteDocumentForm').on('submit', function() {
        //     saveActiveTab();
        // });
        // // Restore tab setelah reload
        // var activeTabId = localStorage.getItem('seminar1_active_tab');
        // if (activeTabId) {
        //     // Nonaktifkan semua tab
        //     $('.nav-tabs .nav-link').removeClass('active');
        //     $('.tab-pane').removeClass('show active');

        //     // Aktifkan tab dan tab-pane sesuai yang terakhir
        //     $('#' + activeTabId).addClass('active');
        //     var targetPaneId = $('#' + activeTabId).attr('data-target');
        //     $(targetPaneId).addClass('show active');

        //     // Optional: hapus localStorage supaya tidak terus-terusan
        //     // localStorage.removeItem('seminar1_active_tab');
        // }


        const dataTables = {};

        $('.datatable').each(function(index) {
            let tableId = $(this).closest('.tab-pane').attr('id');
            let customLengthId = 'custom-length';
            var tableKey = $(this).data('table');

            if (!tableKey) {
                tableKey = tableId || 'table-' + index;
            }

            if (tableId === 'tab-fta') {
                customLengthId = 'custom-length-fta';
            } else if (tableId === 'tab-pp') {
                customLengthId = 'custom-length-ppt';
            } else if (tableId === 'tab-srs') {
                customLengthId = 'custom-length-srs';
            } else if (tableId === 'tab-sdd') {
                customLengthId = 'custom-length-sdd';
            } else if (tableId === 'tab-poster') {
                customLengthId = 'custom-length-poster';
            }

            dataTables[tableKey] = $(this).DataTable({
                searching: false,
                info: false,
                lengthChange: true,
                dom: '<"d-none"l>t<"d-flex justify-content-end"ip>',
                language: {
                    zeroRecords: "Data tidak ditemukan",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    paginate: {
                        first: "<<",
                        last: ">>",
                        next: ">",
                        previous: "<"
                    }
                },
                initComplete: function() {
                    var lengthMenu = $(this.api().table().container()).find('.dataTables_length').first();
                    var customLengthHtml = `
                    <div class="d-flex align-items-center gap-2" style="white-space: nowrap;">
                        <label class="mb-0" for="customLengthSelect">Tampilkan</label>
                        ${lengthMenu.find('select').prop('outerHTML')}
                        <span>data per halaman</span>
                    </div>`;
                    $(`#${customLengthId}`).empty().append(customLengthHtml);

                    // ⬇⬇⬇ Tambahkan isi No saat init pertama
                    var api = this.api();
                    api.on('order.dt search.dt', function() {
                        api.column(0, {
                                search: 'applied',
                                order: 'applied'
                            })
                            .nodes()
                            .each(function(cell, i) {
                                cell.innerHTML = i + 1;
                            });
                    });

                    // ⬇⬇⬇ Jalankan manual untuk pertama kali
                    api.draw();
                }
            });
        });
    });

    const prefix = "/{{ env('PREFIX_URL') }}";
    const kategori = '{{ $kategori }}';

    // $('#file').on('change', function() {
    function JudulDokumen() {
        let fileName = $('#file').val().split('\\').pop(); // Ambil nama file saja
        $('#file').next('.custom-file-label').addClass("selected").html(fileName);
    };

    // $('.edit-btn').on('click', function() {
    function EditDokumen(id, judul, deskripsi, filePath) {
        saveActiveTab();
        console.log("Tes2");
        // const button = event.target;
        // var id = button.dataset.id;
        // var judul = button.dataset.judul;
        // var deskripsi = button.dataset.deskripsi;
        // var filePath = button.dataset.file;
        var kategori = '{{ $kategori }}'; // Kategori juga tetap di-blade-kan

        // Set form action update dinamis dengan prefix
        $('#editDocumentForm').attr('action', `${prefix}/repository/mahasiswa/${kategori}/${id}`);

        // Set field edit input
        $('#edit_id_dokumen').val(id);
        $('#edit_judul').val(judul);
        $('#edit_deskripsi').val(deskripsi);
        $('#edit_username').val('{{ auth()->user()->username }}'); // Handle file preview
        if (filePath && filePath.trim() !== '') {
            var fullUrl = `${prefix}/storage/${filePath}`;

            // Deteksi file extension
            var fileExtension = filePath.split('.').pop().toLowerCase();

            if (['pdf', 'png', 'jpg', 'jpeg'].includes(fileExtension)) {
                // File gambar dan PDF langsung tampil
                $('#documentPreview').attr('src', fullUrl).show();
                $('#previewNotAvailable').hide();
            } else if (['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'].includes(fileExtension)) {
                // File Word atau PowerPoint pakai Google Docs Viewer
                var viewerUrl = `https://docs.google.com/gview?url=${location.origin}${fullUrl}&embedded=true`;
                $('#documentPreview').attr('src', viewerUrl).show();
                $('#previewNotAvailable').hide();
            } else {
                // File tipe lain tidak bisa di-preview
                $('#documentPreview').hide();
                $('#previewNotAvailable').show();
            }
        } else {
            $('#documentPreview').hide();
            $('#previewNotAvailable').show();
        }
    };

    // $('.delete-btn').on('click', function() {
    function HapusDokumen(id, judul) {
        saveActiveTab();
        // var id = $(this).data('id');
        // var judul = $(this).data('judul');

        // Set judul yang akan ditampilkan
        $('#hapusDocumentTitle').text(judul);

        // Set action form delete
        $('#deleteDocumentForm').attr('action', `${prefix}/repository/mahasiswa/${kategori}/${id}`);
    };

    function saveActiveTab() {
        const activeTabId = $('.nav-tabs .nav-link.active').attr('id'); // Contoh hasil: "tab-fta-tab"
        if (activeTabId) {
            localStorage.setItem('seminar1_active_tab', activeTabId);
        }
    }


    // $('.view-btn').on('click', function() {
    function LihatDokumen(judul, deskripsi, filePath, kodeFta, catatan, username, catatanKoordinator) {
        console.log("Tes");
        // Get document ID from the button data attribute
        var docId = $(event.currentTarget).data('id');
        var kategori = '{{ $kategori }}'; // ini dari blade kamu

        // Update hidden form field
        $('#view_id_dokumen').val(docId);
        $('#saveNotesForm').attr('action', `${prefix}/repository/mahasiswa/save-notes/${docId}`);

        // Set other form fields
        $('#view_judul').val(judul);
        $('#view_deskripsi').val(deskripsi);
        $('#view_username').val(username);
        $('#view_notes').val(catatan);
        $('#give_notes').val(catatan);
        $('#view_notes_koordinator').val(catatanKoordinator);
        $('#give_notes_koordinator').val(catatanKoordinator);

        // Handle Kode FTA
        if (kodeFta) {
            $('#field_kode_fta_view').show();
            $('#view_kode_fta').val(kodeFta);
        } else {
            $('#field_kode_fta_view').hide();
            $('#view_kode_fta').val('');
        }

        if (filePath && filePath.trim() !== '') {
            var fullUrl = `${prefix}/storage/${filePath}`;
            var fileExtension = filePath.split('.').pop().toLowerCase();

            // Determine the appropriate URL for "Buka di halaman baru" based on file type
            var openInNewTabUrl = fullUrl;
            if (['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'].includes(fileExtension)) {
                // For office documents, use Google Docs viewer (without embedded=true for new tab)
                openInNewTabUrl = `https://docs.google.com/gview?url=${location.origin}${fullUrl}`;
            }

            // Buka di tab baru
            $('#view_file_link').attr('href', openInNewTabUrl);

            // Link download
            $('#view_file_download').attr('href', `${prefix}/repository/mahasiswa/${kategori}/${docId}/download`);

            // Preview file
            if (['pdf', 'png', 'jpg', 'jpeg'].includes(fileExtension)) {
                $('#viewDocumentPreview').attr('src', fullUrl).show();
                $('#viewPreviewNotAvailable').hide();
            } else if (['doc', 'docx', 'ppt', 'pptx'].includes(fileExtension)) {
                var viewerUrl = `https://docs.google.com/gview?url=${location.origin}${fullUrl}&embedded=true`;
                $('#viewDocumentPreview').attr('src', viewerUrl).show();
                $('#viewPreviewNotAvailable').hide();
            } else {
                $('#viewDocumentPreview').hide();
                $('#viewPreviewNotAvailable').show();
            }
        } else {
            $('#viewDocumentPreview').hide();
            $('#viewPreviewNotAvailable').show();
        }
    };

    // Apply Filter
    // $('.apply-filter-btn').on('click', function() {
    function filter() {
        var $filterMenu = $(this).closest('.filter-menu');
        var targetTable = $filterMenu.data('target');
        var table = dataTables[targetTable];

        // Ambil input filter
        var versionValue = $filterMenu.find('.version-filter').val();
        var dateValue = $filterMenu.find('.date-filter').val();
        var doctypeValue = $filterMenu.find('.doctype-filter').val(); // Tambah filter jenis dokumen

        // Reset semua search dulu
        table.columns().search('');

        // Apply filter kolom
        if (versionValue) {
            table.column(1).search('^' + versionValue + '$', true, false); // Kolom Versi di index 1
        }

        if (doctypeValue) {
            table.column(3).search(doctypeValue); // Kolom Jenis Dokumen di index 3
        }

        if (dateValue) {
            // Kolom Tanggal - perlu menyesuaikan index karena ada kolom jenis dokumen
            table.column(targetTable === 'ppt' || targetTable === 'srs' ? 4 : 3).search(dateValue);
        }

        table.draw();
    };


    function TambahDokumen(type, id) {
        saveActiveTab();
        var title = '';
        var showKodeFTA = false;
        var showSubkategoriTeknis = false;
        var showSubkategoriLainnya = false;

        if (type === 'btn-tambah-laporan') {
            title = 'Tambah Laporan Sidang';
        } else if (type === 'btn-tambah-fta') {
            title = 'Tambah FTA Sidang';
            showKodeFTA = true;
        } else if (type === 'btn-tambah-ppt') {
            title = 'Tambah PowerPoint Sidang';
        } else if (type === 'btn-tambah-srs' || type === 'btn-tambah-teknis') {
            title = 'Tambah Dokumen Teknis Sidang';
            showSubkategoriTeknis = true;
        } else if (type === 'btn-tambah-lainnya') {
            title = 'Tambah Dokumen Lainnya Sidang';
            showSubkategoriLainnya = true;
        }

        $('#TambahDokumen .modal-title').text(title);
        $('#field-kode-fta').toggle(showKodeFTA);
        $('#field-subkategori-teknis').toggle(showSubkategoriTeknis);
        $('#field-subkategori-lainnya').toggle(showSubkategoriLainnya);

        if (showKodeFTA) {
            $('#kode_fta').prop('required', true); // Tambahkan required kalau FTA
        } else {
            $('#kode_fta').prop('required', false); // Hilangkan required kalau bukan FTA
            $('#kode_fta').val(''); // Reset value juga supaya kosong
        }

        if (showSubkategoriTeknis) {
            $('#subkategori_teknis').prop('required', true);
        } else {
            $('#subkategori_teknis').prop('required', false);
            $('#subkategori_teknis').val('');
        }

        if (showSubkategoriLainnya) {
            $('#subkategori_lainnya').prop('required', true);
        } else {
            $('#subkategori_lainnya').prop('required', false);
            $('#subkategori_lainnya').val('');
        }

        $('#field-subkategori').hide();
        $('#field-repo-url').hide();

        console.log("Tes");
        // Set id_subkategori dari tombol
        // var idSubkategori = $(this).data('id-subkategori');
        $('#id_subkategori_hidden').val(id);
        console.log("Tis");
    };
</script>
@stop