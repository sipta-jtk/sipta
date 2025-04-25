@extends('adminlte::page')

@section('title', 'Ubah Aspek Penilaian')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => route('beranda.get'), 'label' => 'Home'],
                ['url' => route('formulir-penilaian.index'), 'label' => 'Formulir Penilaian'],
                ['url' => '', 'label' => 'Ubah Aspek Penilaian']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Ubah Aspek Penilaian</h1>
    </div>
@stop

@section('content')
    <div class="p-4">
        <div id="notification" class="alert alert-danger d-none" role="alert">
            <span id="notificationMessage"></span>
        </div>

        <form action="{{ route('aspek-penilaian.update', $aspek->id) }}" method="POST" id="aspekForm">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Kode FTA -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kodeFTA">Kode FTA</label>
                        <input type="text" class="form-control" id="kodeFTA" name="kodeFTA" value="{{ $aspek->kodeFTA }}" readonly>
                    </div>
                </div>

                <!-- Nama FTA -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="namaFTA">Nama FTA</label>
                        <input type="text" class="form-control" id="namaFTA" name="namaFTA" value="{{ $aspek->namaFTA }}" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jenisForm">Jenis Formulir</label>
                        <input type="text" class="form-control" id="jenisForm" name="jenisForm" value="{{ $aspek->jenisForm }}" readonly>
                    </div>
                </div>

                <!-- Nama Prodi -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="namaProdi">Nama Prodi</label>
                        <input type="text" class="form-control" id="namaProdi" name="namaProdi" value="{{ $aspek->namaProdi }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Form Penilaian Section -->
            @if($aspek->jenisForm == 'penilaian')
                <!-- Aspek Penilaian -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6><strong>Aspek Penilaian</strong></h6>
                    <button type="button" class="btn btn-dark btn-sm" id="addRow">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>

                <div class="table-container mb-3">
                    <table class="table text-center" id="tablePenilaian">
                        <thead class="sticky-header">
                            <tr class="bg-dark text-white">
                                <th style="min-width: 200px;">Kriteria Penilaian Penguji</th>
                                <th style="min-width: 100px;">Bobot (%)</th>
                                <th style="min-width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="aspekPenilaianTable">
                            @forelse($aspek->penilaian as $penilaian)
                                <tr>
                                    <td><input type="text" class="form-control" name="nama_kriteria[]" value="{{ $penilaian->nama_kriteria }}" required></td>
                                    <td><input type="number" class="form-control" name="bobot_kriteria[]" value="{{ $penilaian->bobot_kriteria }}" required></td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                            
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3" class="text-right">
                                <div class="bobot-summary alert alert-info">Total bobot harus 100%. Saat ini: 0%</div>
                            </td>
                        </tr>
                    </tfoot>
                    </table>

                    @if(count($aspek->penilaian) == 0)
                        <div class="text-center">Tidak ada data kriteria penilaian.</div>
                    @endif
                </div>
            @else
                <!-- Aspek Feedback -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6><strong>Aspek Feedback</strong></h6>
                    <button type="button" class="btn btn-dark btn-sm" id="addFeedbackRow">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>

                <div class="table-container mb-3">
                    <table class="table text-center" id="tableFeedback">
                        <thead class="sticky-header">
                            <tr class="bg-dark text-white">
                                <th style="min-width: 300px;">Kriteria Feedback</th>
                                <th style="min-width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="aspekFeedbackTable">
                            @forelse($aspek->feedback as $feedback)
                                <tr>
                                    <td><input type="text" class="form-control" name="nama_aspek_feedback[]" value="{{ $feedback->nama_aspek_feedback }}" required></td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-feedback-row">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Tidak ada data pertanyaan feedback.</td>
                                </tr>
                            @endforelse
                            
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="row">
                <!-- Tanggal Tenggat -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggalTenggat">Tanggal Tenggat Pengisian</label>
                        <input type="date" class="form-control" id="tanggalTenggat" name="tanggalTenggat" value="{{ $aspek->tanggalTenggat }}" required>
                    </div>
                </div>

                <!-- Waktu Tenggat -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="waktuTenggat">Tanggal Tenggat Pengisian</label>
                        <input type="time" class="form-control" id="waktuTenggat" name="waktuTenggat" value="{{ $aspek->waktuTenggat }}" required>
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/ubah_aspek_formulir.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/ubah_aspek_formulir.js') }}"></script>
@stop