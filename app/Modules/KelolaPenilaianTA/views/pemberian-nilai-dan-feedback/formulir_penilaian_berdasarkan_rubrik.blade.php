@extends('adminlte::page')

@section('title', 'PENILAIAN ' . strtoupper($namaFta))

@section('content_header')
@php
    $prefix = env('PREFIX_URL', 'sipta');
@endphp
    <div class="container-fluid p-3">
        <h1 class="mb-0">PENILAIAN {{ strtoupper($namaFta) }}</h1>

        @php 
            $namaFtaBreadcrumb = str_replace(' ', '-', $namaFta);
        @endphp
        
        <!-- Breadcrumb -->
        {{-- TBD perbaiki alur breadcumb --}}
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => "/$prefix", 'label' => 'Beranda'],
                ['url' => route('nilai.index', ['kegiatan' => $namaFtaBreadcrumb]), 'label' => 'Tabel Penilaian & Masukan'],
                ['url' => '', 'label' => match ($namaFta) {
                    'sidang akhir' => 'Sidang Akhir',
                    'seminar iii' => 'Seminar III',
                    default => ucfirst($namaFta)
                } . ' - Formulir Penilaian Berdasarkan Rubrik']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
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

            <div class="col-md-2 mt-3">
                <strong>Pada Hari/Tanggal</strong> <br>
                <span>{{ $keteranganUmumPenilaian ? \Carbon\Carbon::parse($keteranganUmumPenilaian->penjadwalan->first()->tanggal)->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>Waktu</strong> <br>
                <span>
                    @if($keteranganUmumPenilaian)
                        {{ \Carbon\Carbon::parse($keteranganUmumPenilaian->penjadwalan->first()->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($keteranganUmumPenilaian->penjadwalan->first()->end)->format('H:i') }}
                    @else
                        -
                    @endif
                </span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>KoTA</strong> <br>
               <span> {{ $keteranganUmumPenilaian->nama_kota }}</span>
            </div>
        </div>

        <!-- Data Mahasiswa dalam Tabel -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table table-bordered">
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
                <strong>
                    {{ match ($namaFta) {
                        'seminar i' => 'Usulan Topik Tugas Akhir',
                        default => 'Topik Tugas Akhir'
                    } }}
                </strong> <br>
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
        </x-adminlte-modal> <br>

        <!-- Form Penilaian -->
        @php
            $isEdit = count($detailInformasiFta->first()?->kriteriaPenilaian->first()?->rubrik->first()?->nilaiRubrik ?? []) > 0;
            $action = $isEdit ? route('pengisian.nilai.edit', ['namaFta' => Str::slug($namaFta), 'idKota' => $idKota]) : route('pengisian.nilai.store', ['namaFta' => Str::slug($namaFta), 'idKota' => $idKota]);
        @endphp
        <form action="{{ $action }}" method="POST">
            @csrf
            @if($isEdit)
                @method('PATCH')
            @endif
            <div class="row mt-4">
                <div class="table-container">
                    <table id="myTable" class="display nowrap table text-center table-bordered" style="width:100%"> 
                        <thead class="sticky-header">
                            <tr class="bg-dark text-white">
                                <th style="min-width: 200px;" rowspan="2">Detail Kriteria</th>
                                <th style="min-width: 200px;" colspan="6">Rentang Penilaian</th>
                                <th style="min-width: 200px;" colspan="{{ count($keteranganUmumPenilaian->mahasiswa ?? []) }}" class="input-nilai-header">Nilai Perorangan</th>
                            </tr>
                            <tr class="bg-dark text-white sticky-row">
                                @foreach($rubrikList as $index => $rubrik)
                                    <th style="min-width: 200px;">
                                        @if($index == count($rubrikList) - 1)
                                            {{ $rubrik['nilai']['batas_atas'] }} - {{ $rubrik['nilai']['batas_bawah'] }} ({{ $rubrik['nilai']['id_nilai'] }})
                                        @else
                                            {{ $rubrik['nilai']['batas_atas'] }} - {{ $rubrik['nilai']['batas_bawah'] }} ({{ $rubrik['nilai']['id_nilai'] }})
                                        @endif
                                    </th>
                                @endforeach

                                @foreach($keteranganUmumPenilaian->mahasiswa ?? [] as $key => $mhs)
                                    <th class="input-nilai-header">{{ $key + 1 }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailInformasiFta->first()?->kriteriaPenilaian ?? [] as $indexKriteria => $kriteria)
                                <tr>
                                    <td colspan="{{ 8 + count($keteranganUmumPenilaian->mahasiswa ?? []) }}" class="bg-light text-left"><strong> {{ $kriteria->nama_kriteria }}</strong></td>
                                </tr>
                                @foreach ($kriteria->rubrik as $index => $rubrik)
                                    <tr>
                                        <td>{{ $rubrik->nama_rubrik }}</td>
                                        @foreach ($rubrik->detailRubrik as $detail)
                                            <td> {{ $detail->detail_rubrik_penilaian }} </td>
                                        @endforeach
                                        @foreach($keteranganUmumPenilaian->mahasiswa ?? [] as $key => $mhs)
                                            @php
                                                $nilai = isset($rubrik->nilaiRubrik[$key]) ? $rubrik->nilaiRubrik[$key]->nilai_rubrik : '';
                                            @endphp

                                            <td class="input-nilai align-middle">
                                                <input type="number" class="form-control" name="nilai{{ $key }}[]" min="0" max="100" value="{{ $nilai }}" {{ $view ? '' : 'readonly' }} required data-toggle="tooltip" data-placement="top" title="{{ $mhs->user->nama }}">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            @if ($view)
                <div class="row mt-3">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary btn-prev btn-md my-1">
                            Simpan <i class="fa-solid fa-floppy-disk"></i>
                        </button>
                    </div>
                </div>
            @endif
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/pemberian_nilai_dan_feedback.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/pemberian_nilai_dan_feedback.js') }}"></script>
    <script>
        window.PREFIX_URL = "{{ env('PREFIX_URL') }}";
    </script>
@stop