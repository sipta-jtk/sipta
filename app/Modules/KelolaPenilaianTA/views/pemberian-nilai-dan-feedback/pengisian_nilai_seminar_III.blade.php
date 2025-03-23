@extends('adminlte::page')

@section('title', 'PENILAIAN SEMINAR III')

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Breadcrumb -->
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => '', 'label' => 'Penilaian Seminar III']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">PENILAIAN SEMINAR III</h1>
    </div>
@stop

@section('content')
    <div class="card p-4">
        <div class="row">
            <!-- Kode FTA -->
            <div class="col-md-12">
                <strong>Kode FTA</strong> <br>
                <span>{{ $data['nama_fta'] }}</span>
            </div>

            <!-- Tanggal, Waktu, ID KoTA -->
            <div class="col-md-2 mt-3">
                <strong>Pada hari/tanggal</strong> <br>
                <span>{{ $data['tanggal'] }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>Waktu</strong> <br>
                <span>{{ $data['waktu'] }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>KoTA</strong> <br>
                <span>{{ $data['nama_kota'] }}</span>
            </div>
        </div>

        <!-- Data Mahasiswa dalam Tabel -->
        <div class="row mt-4">
            <div class="col-md-4">
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
                            @foreach($mahasiswa as $key => $mhs)
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
                <strong>Topik Tugas Akhir</strong> <br>
                <span>{{ $data['judul_ta'] }}</span>
            </div>
        </div>

        <!-- Tombol Preview -->
        <div class="row mt-4">
            <div class="col-md-12">
                <strong>Preview File Dokumen Seminar II</strong> <br>
                <button type="button" class="btn btn-primary btn-prev" data-toggle="modal" data-target="#previewModal" onclick="loadPreview('https://drive.google.com/file/d/1csAcC_MeS9YI3BkdW-i747-aG92-8yLf/view?usp=sharing')">
                    Laporan
                </button>
                <button type="button" class="btn btn-primary btn-prev" data-toggle="modal" data-target="#previewModal" onclick="loadPreview('https://drive.google.com/file/d/1csAcC_MeS9YI3BkdW-i747-aG92-8yLf/view?usp=sharing')">
                    Power Point
                </button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="previewModalLabel">Preview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="previewFrame" src="" width="100%" height="500px" frameborder="0"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Penilaian -->
        @php
            $actionUrl = isset($nilaiKriteria) && count($nilaiKriteria) > 0 ? url('sipta/kelola-penilaian-ta/nilai-seminar/'. $idFta . '/nilai/' . $data['id_fta'] . '/edit') : url('sipta/kelola-penilaian-ta/nilai-seminar/'. $idFta . '/nilai/' . $data['id_fta'] . '/tambah');
        @endphp
        <form action="{{ $actionUrl }}" method="POST">
            @csrf
            @if(isset($nilaiKriteria) && count($nilaiKriteria) > 0)
                @method('PATCH')
            @endif
            <div class="row mt-4">
                <div class="table-container">
                    <table id="myTable" class="display nowrap table text-center table-bordered" style="width:100%"> 
                        <thead class="sticky-header">
                            <tr class="bg-dark text-white">
                                <th style="min-width: 200px;" rowspan="2">Detail Kriteria</th>
                                <th style="min-width: 200px;" colspan="6">Rentang Penilaian</th>
                                <th style="min-width: 100px;" rowspan="2">Rentang Nilai</th>
                                <th style="min-width: 100px;" colspan="{{ count($mahasiswa) }}">Nilai Perorangan</th>
                            </tr>
                            <tr class="bg-dark text-white sticky-row">
                                @foreach($nilaiBatas[0] as $nilai)
                                    <th style="min-width: 200px;">
                                        @if($nilai['id_nilai'] == 'CD')
                                            {{ $nilai['batas_atas'] }} ({{ $nilai['id_nilai'] }}
                                        @else
                                            {{ $nilai['batas_bawah'] }} - {{ $nilai['batas_atas'] }} ({{ $nilai['id_nilai'] }})
                                        @endif
                                    </th>
                                @endforeach
                                @foreach($mahasiswa as $key => $mhs)
                                    <th>{{ $key + 1 }}</th>  
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteriaPenilaian as $index => $kriteria)
                                <tr>
                                    <td colspan="{{ 8 + count($mahasiswa) }}" class="bg-light text-left"><strong> {{ $kriteria->nama_kriteria }}</strong></td>
                                </tr>
                                @foreach ($kriteria->rubrik as $key => $rubrik)
                                    <tr>
                                        <td>{{ $rubrik->nama_rubrik }}</td>
                                        @foreach ($rubrik->detailRubrik as $detail)
                                            <td> {{ $detail->detail_rubrik_penilaian }} </td>
                                        @endforeach
                                        <td>0-100</td>
                                        @foreach($mahasiswa as $key => $mhs)
                                            <td><input type="number" class="form-control" name="nilai{{ $key }}[]" min="0" max="100"></td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="row mt-3">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/pemberian_nilai_dan_feedback.css') }}">
    <style>
        /* Hide the increment and decrement buttons */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/pemberian_nilai_dan_feedback.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Menginisialisasi popover untuk elemen yang sudah ada
            $('[data-toggle="popover"]').popover({
                trigger: 'hover',
                placement: 'top',
                html: true
            });

            // Event delegation untuk elemen dinamis
            $(document).on('mouseenter', '[data-toggle="popover"]', function () {
                $(this).popover('show');
            }).on('mouseleave', '[data-toggle="popover"]', function () {
                $(this).popover('hide');
            });
        });

        function loadPreview(url) {
            let fileId = extractDriveFileId(url);
            if (fileId) {
                let embedUrl = `https://drive.google.com/file/d/${fileId}/preview`;
                document.getElementById('previewFrame').src = embedUrl;
            } else {
                alert("Format link tidak valid!");
            }
        }

        function extractDriveFileId(url) {
            let match = url.match(/[-\w]{25,}/);
            return match ? match[0] : null;
        }
    </script>
@stop