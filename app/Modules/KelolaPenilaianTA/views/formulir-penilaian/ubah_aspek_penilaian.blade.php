@extends('adminlte::page')

@section('title', 'Pengubahan Aspek Penilaian')

@section('content_header')
<div class="container-fluid p-3">
    <!-- Judul Halaman -->
    <h1 class="mb-0">Pengubahan Aspek Penilaian</h1>

    @component('KelolaPenilaianTA.views.components.breadcrumb', [
        'links' => [
            ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
            ['url' => route('formulir-penilaian.index'), 'label' => 'Formulir Penilaian'],
            ['url' => '', 'label' => 'Ubah Aspek Penilaian']
        ]
    ])
    @endcomponent
</div>
@stop

@section('content')
<div class="card p-4">
    <div class="p-2">
        <div id="notification" class="alert alert-danger d-none" role="alert">
            <span id="notificationMessage"></span>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('aspek-penilaian.update', $aspek->id) }}" method="POST" id="aspekForm">
                    @csrf
                    @method('PUT')

                    <!-- Informasi FTA -->
                    <div class="mb-2 px-3">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-secondary text-md border-bottom">Informasi FTA</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kodeFTA">Kode FTA</label>
                                    <input type="text" class="form-control" id="kodeFTA" name="kodeFTA" value="{{ $aspek->kodeFTA }}" readonly>
                                </div>
                            </div>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="namaProdi">Program Studi</label>
                                    <input type="text" class="form-control" id="namaProdi" name="namaProdi" value="{{ $aspek->namaProdi }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenisTA">Jenis TA</label>
                                    <input type="text" class="form-control" id="jenisTA" name="jenisTA" 
                                        value="{{ $aspek->jenisTA ?? '' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aspek Penilaian -->
                    @if($aspek->jenisForm == 'penilaian')
                        <!-- Aspek Penilaian -->
                        <div class="mb-4 px-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6><strong>Aspek Penilaian</strong></h6>
                                <button type="button" class="btn btn-primary btn-md my-1" id="addRow" title="Tambah Aspek Penilaian">
                                    <i class="fas fa-plus text-white"></i>
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
                                                <div class="bobot-summary alert alert-warning">Total bobot harus 100%. Saat ini: 0%</div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                @if(count($aspek->penilaian) == 0)
                                    <div class="text-center">Tidak ada data kriteria penilaian.</div>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Aspek Feedback -->
                        <div class="mb-4 px-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6><strong>Aspek Feedback</strong></h6>
                                <button type="button" class="btn btn-primary btn-md my-1" id="addFeedbackRow" title="Tambah Aspek Feedback">
                                    <i class="fas fa-plus text-white"></i>
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
                        </div>
                    @endif

                    <!-- Batas Waktu Pengisian -->
                    <div class="mb-2 px-3">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-secondary text-md border-bottom">Batas Waktu Pengisian</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggalTenggat">Tanggal Tenggat Pengisian</label>
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        id="tanggalTenggat" 
                                        name="tanggalTenggat" 
                                        value="{{ old('tanggalTenggat', isset($aspek->tanggalTenggat) ? \Carbon\Carbon::parse($aspek->tanggalTenggat)->format('Y-m-d') : '') }}" 
                                        required
                                    >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="waktuTenggat">Waktu Tenggat</label>
                                    <input 
                                        type="time" 
                                        class="form-control" 
                                        id="waktuTenggat" 
                                        name="waktuTenggat" 
                                        value="{{ old('waktuTenggat', isset($aspek->waktuTenggat) ? \Carbon\Carbon::parse($aspek->waktuTenggat)->format('H:i') : '') }}" 
                                        required
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="d-flex justify-content-end px-3">
                        <button type="submit" class="btn btn-primary btn-md my-1">
                            Simpan <i class="fas fa-floppy-disk"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/ubah_aspek_formulir.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/ubah_aspek_formulir.js') }}"></script>
@stop