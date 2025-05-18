@extends('adminlte::page')

@php
    $title = match ($data['namaFta']) {
        'seminar i' => 'MASUKAN SEMINAR I',
        'seminar ii' => 'MASUKAN SEMINAR II',
        'seminar iii' => 'MASUKAN SEMINAR III',
        default => 'MASUKAN SIDANG AKHIR',
    };
@endphp

@section('title', $title)

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Judul Halaman -->
        <h1 class="mb-0">MASUKAN {{ strtoupper($data['namaFta']) }}</h1>

        <!-- Breadcrumb -->
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => route('nilai.index'), 'label' => 'Tabel Penilaian & Masukan'],
                ['url' => '', 'label' => match ($data['namaFta']) {
                        'seminar i' => 'Masukan Seminar I',
                        'seminar ii' => 'Masukan Seminar II',
                        'seminar iii' => 'Masukan Seminar III',
                        default => 'Masukan Sidang Akhir'
                    }
                ],
            ]
        ])
        @endcomponent
    </div>
@stop

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="card p-4">
        <div class="row">
            <!-- Kode FTA -->
            <div class="col-md-12">
                <strong>Kode FTA</strong> <br>
                <span>{{ $data['kode_fta'] }}</span>
            </div>

            <!-- Tanggal, Waktu, KoTA -->
            <div class="col-md-2 mt-3">
                <strong>Pada Hari/Tanggal</strong> <br>
                <span>{{ $jadwal ? \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>Waktu</strong> <br>
                <span>
                    @if($jadwal)
                        {{ \Carbon\Carbon::parse($jadwal->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end)->format('H:i') }}
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
                    <table class="table table-striped">
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
                    {{ match ($data['namaFta']) {
                        'seminar i' => 'Usulan Topik Tugas Akhir',
                        default => 'Topik Tugas Akhir'
                    } }}
                </strong> <br>
                <span>{{ $keteranganUmumPenilaian->judul_ta ? $keteranganUmumPenilaian->judul_ta : '-' }}</span>
            </div>
        </div>

        <!-- Tombol Lihat Dokumen -->
        <!-- <div class="row mt-4">
            <div class="col-md-12">
                <strong>Dokumen
                    {{ match ($data['namaFta']) {
                        'seminar i' => 'Seminar I',
                        'seminar ii' => 'Seminar II',
                        'seminar iii' => 'Seminar III',
                        'sidang akhir' => 'Sidang Akhir',
                        default => ''
                    } }}
                </strong> <br> -->

                <!-- Tombol Preview Laporan -->
                <!-- <button type="button" class="btn btn-primary btn-prev"
                    onclick="LihatDokumen('{{ $dokumen['laporan']->file_path ?? '' }}')"
                    data-toggle="modal" data-target="#LihatDokumen"
                    {{ $dokumen['laporan'] ? '' : 'disabled' }}>
                    Laporan <i class="fa-solid fa-file"></i>
                </button> -->

                <!-- Tombol Preview PowerPoint -->
                <!-- <button type="button" class="btn btn-primary btn-prev"
                    onclick="LihatDokumen('{{ $dokumen['powerpoint']->file_path ?? '' }}')"
                    data-toggle="modal" data-target="#LihatDokumen"
                    {{ $dokumen['powerpoint'] ? '' : 'disabled' }}>
                    PowerPoint <i class="fa-solid fa-file-powerpoint"></i>
                </button>
            </div>
        </div> -->

        <!-- Modal Lihat Dokumen -->
        <!-- <x-adminlte-modal id="LihatDokumen" title="Preview Dokumen" theme="green" size="xl">
            <div class="row px-3">
                <div class="col-md-12">
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
        </x-adminlte-modal> -->

        <!-- Judul Form -->      
        <h3 class="heading-spacing text-center">
            {{ match ($data['namaFta']) {
                'seminar i' => 'EVALUASI',
                'seminar ii', 'seminar iii' => 'ISI MASUKAN',
                'sidang akhir' => 'CATATAN PERBAIKAN LAPORAN',
                default => ''
            } }}
        </h3>

        <!-- Form Masukan -->
        @php
            // Cek apakah data masukan sudah ada
            $isEdit = isset($seminar) && $seminar->kategoriPenilaian->isNotEmpty();
            $actionUrl = $isEdit
                ? route('pengisian.masukan.edit', ['namaFta' => Str::slug($data['namaFta']), 'idKota' => $data['id_kota']])
                : route('pengisian.masukan.store', ['namaFta' => Str::slug($data['namaFta']), 'idKota' => $data['id_kota']]);
        @endphp
        
        <form action="{{ $actionUrl }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PATCH')
            @else
                @method('POST')
            @endif

            @if($aspekFeedback->isEmpty())
                <div class="alert alert-warning">Belum ada aspek feedback untuk {{ strtoupper($data['namaFta']) }}.</div>
            @else
            @foreach ($aspekFeedback as $index => $feedback)
                <div class="form-group mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="{{ Str::slug($feedback->nama_aspek_feedback) }}" class="mb-1">
                            Masukan untuk {{ $feedback->nama_aspek_feedback }}
                        </label>
                        <div class="text-muted char-counter" id="char-count-{{ $index }}">0 kata</div>
                    </div>

                    <input type="hidden" name="feedback[{{ $index }}][id_fta]" value="{{ $data['kode_fta'] }}">
                    <input type="hidden" name="feedback[{{ $index }}][nama_aspek_feedback]" value="{{ $feedback->nama_aspek_feedback }}">
                    <input type="hidden" name="feedback[{{ $index }}][id_feedback]" value="{{ $feedback->id_feedback }}">

                    @php
                        $existingFeedback = $data['detailFeedback']->get($feedback->id_feedback);
                        $oldValue = old("feedback.{$index}.masukan", $existingFeedback->isi_feedback ?? '');
                    @endphp

                    <input id="feedback-{{ $index }}" type="hidden" name="feedback[{{ $index }}][masukan]" value="{{ $oldValue }}">
                    <trix-editor input="feedback-{{ $index }}"></trix-editor>
                    <span>Masukan minimal 30 kata.</span>
                </div>
            @endforeach
            @endif
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-prev btn-md my-1 {{ $aspekFeedback->isEmpty() ? 'disabled' : '' }}">
                    Simpan <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <!-- Bootstrap dan Library lainnya -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">  
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/pemberian_nilai_dan_feedback.css') }}">
@stop

@section('js')
    <!-- Trix Editor Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/pemberian_nilai_dan_feedback.js') }}"></script>
@stop
