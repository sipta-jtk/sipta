@extends('adminlte::page')

@section('title', 'PENILAIAN ' . strtoupper($namaFta))

@section('content_header')
    <div class="container-fluid p-3">
        <h1 class="mb-0">PENILAIAN {{ strtoupper($namaFta) }}</h1>
        
        <!-- Breadcrumb -->
        {{-- TBD perbaiki alur breadcumb --}}
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => route('beranda.get'), 'label' => 'Beranda'],
                ['url' => route('nilai.index'), 'label' => 'Tabel Penilaian & Masukan'],
                ['url' => '', 'label' => 'Penilaian Seminar III']
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
                <span>{{ ($detailInformasiFta->first()?->nama_fta) }}</span>
            </div>

            <div class="col-md-2 mt-3">
                <strong>Pada Hari/Tanggal</strong> <br>
                <span>{{ $keteranganUmumPenilaian ? \Carbon\Carbon::parse($keteranganUmumPenilaian->tanggal)->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>Waktu</strong> <br>
                <span>
                    @if($keteranganUmumPenilaian)
                        {{ \Carbon\Carbon::parse($keteranganUmumPenilaian->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($keteranganUmumPenilaian->end)->format('H:i') }}
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
                <strong>Topik Tugas Akhir</strong> <br>
                <span>{{ $keteranganUmumPenilaian->judul_ta ?? "Belum terdapat judul" }}</span>
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
                                <th style="min-width: 200px;" colspan="{{ count($keteranganUmumPenilaian->mahasiswa ?? []) }}">Nilai Perorangan</th>
                            </tr>
                            <tr class="bg-dark text-white sticky-row">
                                @foreach($rubrikList as $index => $rubrik)
                                    <th style="min-width: 200px;">
                                        @if($index == count($rubrikList) - 1)
                                            {{ $rubrik['nilai']['batas_bawah'] }} - ??? ({{ $rubrik['nilai']['id_nilai'] }})
                                        @else
                                            {{ $rubrik['nilai']['batas_bawah'] }} - {{ $rubrik['nilai']['batas_atas'] }} ({{ $rubrik['nilai']['id_nilai'] }})
                                        @endif
                                    </th>
                                @endforeach

                                @foreach($keteranganUmumPenilaian->mahasiswa ?? [] as $key => $mhs)
                                    <th>{{ $key + 1 }}</th>  
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

                                            <td>
                                                <input type="number" class="form-control" name="nilai{{ $key }}[]" min="0" max="100" value="{{ $nilai }}" {{ $view ? '' : 'readonly' }} required>
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


        $(document).ready(function() {
            // Menginisialisasi DataTable dengan FixedColumns
            var table = $('#myTable').DataTable({
                scrollX: true,
                fixedColumns: {
                    rightColumns: 1 // Jumlah kolom yang ingin dibekukan di sebelah kanan
                }
            });
    </script>
@stop