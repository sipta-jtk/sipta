@extends('adminlte::page')

@section('title', 'Penilaian Dosen Pembimbing')

@section('content_header')
@php
    $prefix = env('PREFIX_URL', 'sipta');
@endphp
    <div class="container-fluid p-3">
        <!-- Judul Halaman -->
        <h1 class="mb-0">PENILAIAN {{ strtoupper($namaFta) }}</h1>
        
        <!-- Breadcrumb -->
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => "/$prefix", 'label' => 'Beranda'],
                ['url' => route('monitoring.dosen.pembimbing'), 'label' => 'Monitoring Dosen Pembimbing'],
                ['url' => '', 'label' => 'Penilaian Dosen Pembimbing']
                ]
                ])
        @endcomponent
    </div>
@stop

@section('content')
    <div class="card p-4">
        <div class="row">
            <!-- Kode FTA -->
            <div class="col-md-12">
                <strong>Kode FTA</strong> <br>
                <span>{{ ($detailInformasiFta->first()?->kode_fta) }}</span>
            </div>

            <!-- Tanggal, Waktu, ID KoTA -->
            <div class="col-md-2 mt-3">
                <strong>KoTA</strong> <br>
                <span>{{ $keteranganUmumPenilaian->nama_kota }}</span>
            </div>
        </div>

        <!-- Data Mahasiswa dalam Tabel -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($keteranganUmumPenilaian->mahasiswa as $key => $mhs)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $mhs->nim }}</td>
                                    <td>{{ $mhs->user->nama }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Topik Tugas Akhir -->
        <div class="row mt-4">
            <div class="col-md-12">
                <strong>Judul Tugas Akhir</strong> <br>
                <span>{{ $keteranganUmumPenilaian->judul_ta ? $keteranganUmumPenilaian->judul_ta : '-' }}</span>
            </div>
        </div>

        <!-- Tombol Lihat Dokumen -->
        <div class="row mt-4">
            <div class="col-md-12">
                <strong>Dokumen
                    {{ match ($namaFta) {
                        'seminar i' => 'Seminar I',
                        'seminar ii' => 'Seminar II',
                        'seminar iii' => 'Seminar III',
                        'sidang akhir' => 'Sidang Akhir',
                        default => ''
                    } }}
                </strong> <br>

                <!-- Tombol Preview Laporan -->
                <span
                    @if(!$dokumen['laporan'])
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Laporan belum tersedia"
                    @endif
                    style="display: inline-block;">

                    <button type="button" class="btn btn-primary btn-prev"
                        onclick="LihatDokumen(
                            '{{ $dokumen['laporan']->file_path ?? '' }}',
                            '{{ $dokumen['laporan']->id_dokumen ?? '' }}',
                            '{{ $dokumen['laporan']->kategori ?? '' }}'
                        )"
                        data-toggle="modal" data-target="#LihatDokumen"
                        {{ $dokumen['laporan'] ? '' : 'disabled' }}>
                        Laporan <i class="fa-solid fa-file"></i>
                    </button>
                </span>

                <!-- Tombol Preview PowerPoint -->
                <span
                    @if(!$dokumen['powerpoint'])
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="PowerPoint belum tersedia"
                    @endif
                    style="display: inline-block;">

                    <button type="button" class="btn btn-primary btn-prev"
                        onclick="LihatDokumen(
                            '{{ $dokumen['powerpoint']->file_path ?? '' }}',
                            '{{ $dokumen['powerpoint']->id_dokumen ?? '' }}',
                            '{{ $dokumen['powerpoint']->kategori ?? '' }}'
                        )"
                        data-toggle="modal" data-target="#LihatDokumen"
                        {{ $dokumen['powerpoint'] ? '' : 'disabled' }}>
                        PowerPoint <i class="fa-solid fa-file-powerpoint"></i>
                    </button>
                </span>
            </div>
        </div>

        <!-- Modal Lihat Dokumen -->
        <x-adminlte-modal id="LihatDokumen" title="Preview Dokumen" theme="green" size="xl">
            <div class="row px-3">
                <div class="col-md-3 mb-2">
                    <label class="mt-2">Aksi File</label>
                    <div class="d-flex flex-column">
                        <a href="#" target="_blank" class="btn btn-primary mb-2" id="view_file_link">
                            <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                        </a>
                        <a href="#"
                        class="btn btn-success"
                        id="view_file_download"
                        data-url-template="{{ route('dokumen.download', ['kategori' => '__kategori__', 'id' => '__id__']) }}">
                            <i class="fas fa-download"></i> Unduh
                        </a>
                    </div>
                </div>
                <div class="col-md-9 mb-2">
                    <label class="form-label">Pratinjau Dokumen</label>
                    <div class="document-preview-container" style="height: 470px; border: 1px solid #ddd;">
                        <iframe id="viewDocumentPreview" style="width: 100%; height: 100%; border: none;" src=""></iframe>
                        <div id="viewPreviewNotAvailable" class="text-center p-5" style="display: none;">
                            <i class="fas fa-file-alt fa-3x mb-3 text-secondary"></i>
                            <p>Preview tidak tersedia untuk jenis file ini</p>
                        </div>
                    </div>
                </div>
            </div>

            <x-slot name="footerSlot">
                <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal" />
            </x-slot>
        </x-adminlte-modal>

        <form action="{{ route('pengisian.nilai.store', ['namaFta' => Str::slug($namaFta), 'idKota' => $idKota]) }}" method="POST">
            @csrf
            <div class="row mt-4">
                <div class="col-md-12">
                    @php
                        $all   = $detailInformasiFta->first()->kriteriaPenilaian;
                        $secA  = $all->slice(0, 2);
                        $secB  = $all->slice(2, 2);
                        $mhs   = $keteranganUmumPenilaian->mahasiswa;
                    @endphp

                    <table class="table table-bordered table-striped text-center">
                        <thead class="thead-dark align-middle">
                            <tr>
                                <th rowspan="3">No</th>
                                <th rowspan="3">Kriteria Penilaian Penguji</th>
                                <th rowspan="3">Bobot<br>Nilai</th>
                                <th colspan="{{ count($mhs) }}" rowspan="2">Nilai Perorangan</th>
                            </tr>
                            <tr></tr>
                            <tr>
                                @foreach($mhs as $i => $item)
                                    <th>{{ $i + 1 }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Section A --}}
                            <tr class="table-secondary">
                                <td colspan="{{ 3 + count($mhs) }}"><strong>Luaran Tugas Akhir</strong></td>
                            </tr>
                            @foreach($secA as $i => $krit)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="text-left">{{ $krit->nama_kriteria }}</td>
                                    <td>{{ $krit->bobot_kriteria }}%</td>
                                    @foreach($mhs as $key => $item)
                                        <td>
                                            <input type="number"
                                                   name="nilai{{ $key }}[]"
                                                   class="form-control form-control-sm"
                                                   min="0" max="100"
                                                   value="{{ $krit->nilaiKriteria->firstWhere('nim', $item->nim)?->nilai_kriteria ?? '' }}">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            
                            {{-- Section B --}}
                            <tr class="table-secondary">
                                <td colspan="{{ 3 + count($mhs) }}"><strong>Proses Bimbingan</strong></td>
                            </tr>
                            @foreach($secB as $i => $krit)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="text-left">{{ $krit->nama_kriteria }}</td>
                                    <td>{{ $krit->bobot_kriteria }}%</td>
                                    @foreach($mhs as $key => $item)
                                        <td>
                                            <input type="number"
                                                   name="nilai{{ $key }}[]"
                                                   class="form-control form-control-sm"
                                                   min="0" max="100"
                                                   value="{{ $krit->nilaiKriteria->firstWhere('nim', $item->nim)?->nilai_kriteria ?? '' }}">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-prev btn-md my-1">
                    Simpan <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/pemberian_nilai_dan_feedback.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/pemberian_nilai_dan_feedback.js') }}"></script>
    <script>
        window.PREFIX_URL = "{{ env('PREFIX_URL') }}";
    </script>
@stop