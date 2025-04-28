@extends('adminlte::page')

@section('title', 'PENILAIAN SEMINAR II')

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Breadcrumb -->
        {{-- TBD perbaiki alur breadcumb --}}
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => route('beranda.get'), 'label' => 'Home'],
                ['url' => '', 'label' => 'Penilaian Seminar II']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">PENILAIAN {{ strtoupper($namaFta) }}</h1>
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
                <strong>Topik Tugas Akhir</strong> <br>
                <span>{{ $keteranganUmumPenilaian->judul_ta }}</span>
            </div>
        </div>

        <!-- Tombol Preview -->
        <div class="row mt-4">
            <div class="col-md-12">
                <strong>Preview File Dokumen Seminar II</strong> <br>
                {{-- TBD get file secara dinamis --}}
                <button type="button" class="btn btn-primary btn-prev" data-toggle="modal" data-target="#previewModal" onclick="loadPreview('https://drive.google.com/file/d/1csAcC_MeS9YI3BkdW-i747-aG92-8yLf/view?usp=sharing')">
                    Laporan
                </button>
                {{-- TBD get file secara dinamis --}}
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
            $kriteriaPenilaian = $detailInformasiFta->first()?->kriteriaPenilaian;

            if (isset($kriteriaPenilaian) && $kriteriaPenilaian->first()?->nilaiKriteria->isNotEmpty() ?? false) {
                $actionUrl = route('pengisian.nilai.edit', ['namaFta' => Str::slug($namaFta), 'idKota' => $idKota]);
            } else {
                $actionUrl = route('pengisian.nilai.store', ['namaFta' => Str::slug($namaFta), 'idKota' => $idKota]);
            }
        @endphp
        <form action="{{ $actionUrl }}" method="POST">
            @csrf
            @if(isset($kriteriaPenilaian) && $kriteriaPenilaian->first()?->nilaiKriteria->isNotEmpty() ?? false)
                @method('PATCH')
            @else
                @method('POST')
            @endif
            <div class="row mt-4">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th rowspan="2" class="align-content-center">No</th>
                                <th rowspan="2" class="align-content-center">Kriteria Penilaian Penguji</th>
                                <th rowspan="2" class="align-content-center">Bobot Nilai</th>
                                <th colspan="{{ count($keteranganUmumPenilaian->mahasiswa) }}">Nilai Perorangan</th>
                            </tr>
                            <tr>
                                @foreach($keteranganUmumPenilaian->mahasiswa as $key => $mhs)
                                    <th data-toggle="popover" data-content="{{ $mhs->user->nama }}">{{ $key + 1 }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailInformasiFta->first()?->kriteriaPenilaian ?? [] as $index => $kriteria)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $kriteria->nama_kriteria }}
                                        @if ($kriteria->rubrik->count() > 0)
                                            <ul>
                                                @foreach ($kriteria->rubrik as $rubrik)
                                                    <li>{{ $rubrik->nama_rubrik }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>{{ $kriteria->bobot_kriteria }} %</td>
                                    @foreach($keteranganUmumPenilaian->mahasiswa as $key => $mhs)
                                        @php
                                            $nilai = isset($kriteria->nilaiKriteria[$key]) ? $kriteria->nilaiKriteria[$key]->nilai_kriteria : '';
                                        @endphp
                                        <td>
                                            <input type="number" class="form-control" name="nilai{{ $key }}[]" min="0" max="100" value="{{ $nilai }}">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <button type="submit" class="btn btn-warning">
                Simpan
            </button>
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