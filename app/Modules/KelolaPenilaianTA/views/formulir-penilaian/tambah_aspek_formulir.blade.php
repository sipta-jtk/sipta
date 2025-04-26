@extends('adminlte::page')

@section('title', 'Penambahan Aspek Penilaian')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => route('beranda.get'), 'label' => 'Home'],
                ['url' => route('formulir-penilaian.index'), 'label' => 'Formulir Penilaian'],
                ['url' => '', 'label' => 'Tambah Aspek Penilaian']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Penambahan Aspek Penilaian</h1>
    </div>
@stop

@section('content')
    <div class="p-4">
        <div id="notification" class="alert alert-danger d-none" role="alert">
            <span id="notificationMessage"></span>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="aspekFormulirForm" action="{{ route('formulir-penilaian.simpan') }}" method="POST">
                    @csrf

                    <div class="mb-2 px-3">
                        <!-- Informasi FTA -->
                        <div class="row">
                            <div class="col-12">
                                <p class="text-secondary text-md border-bottom">Informasi FTA</p>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Kode FTA -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kodeFTA">Kode FTA</label>
                                    <x-adminlte-input 
                                        name="kodeFTA" 
                                        id="kodeFTA" 
                                        value="{{ old('kodeFTA') }}" 
                                        placeholder="Format FTA: FTA.XX, FTA.01" 
                                        required
                                        :class="$errors->has('kodeFTA') ? 'is-invalid' : ''"
                                    />
                                    @error('kodeFTA')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Nama FTA -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="namaFTA">Nama FTA</label>
                                    <x-adminlte-select name="namaFTA" id="namaFTA" required fgroup-class="mb-0">
                                        <option value="" disabled selected>Pilih Nama FTA</option>
                                        <option value="Seminar I">Seminar I</option>
                                        <option value="Seminar II">Seminar II</option>
                                        <option value="Seminar III">Seminar III</option>
                                        <option value="Sidang Akhir">Sidang Akhir</option>
                                        <option value="Dosen Pembimbing">Dosen Pembimbing</option>
                                    </x-adminlte-select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Jenis Formulir -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenisForm">Jenis Formulir</label>
                                    <x-adminlte-select name="jenisForm" id="jenisForm" required>
                                        <option value="" disabled selected>-- Pilih Jenis Formulir --</option>
                                        <option value="Penilaian">Penilaian</option>
                                        <option value="Feedback">Feedback</option>
                                    </x-adminlte-select>
                                </div>
                            </div>

                            <!-- Nama Prodi -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="namaProdi">Nama Prodi</label>
                                    <x-adminlte-select name="namaProdi" id="namaProdi" required>
                                        <option value="" disabled selected>-- Pilih Nama Prodi --</option>
                                        @foreach ($prodiList as $prodi)
                                            <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                        @endforeach
                                    </x-adminlte-select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aspek Penilaian -->
                    <div class="mb-2 px-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong>Aspek Penilaian</strong>
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
                                    <tr>
                                        <td><input type="text" class="form-control" name="nama_kriteria[]" required></td>
                                        <td><input type="number" class="form-control" name="bobot_kriteria[]" required></td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            <div class="bobot-summary alert alert-info">Total bobot harus 100%. Saat ini: 0%</div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>

                            <table class="table text-center" id="tableFeedback" style="display: none;">
                                <thead class="sticky-header">
                                    <tr class="bg-dark text-white">
                                        <th style="min-width: 200px;">Kriteria Penilaian Penguji</th>
                                        <th style="min-width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="aspekFeedbackTable">
                                    <tr>
                                        <td><input type="text" class="form-control" name="nama_aspek_feedback[]" required></td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <br>


                    <!-- Tanggal Tenggat Pengisian -->
                    <div class="mb-2 px-3">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-secondary text-md border-bottom">Batas Waktu Pengisian</p>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Tanggal Tenggat -->
                            <div class="col-md-6">
                                <label for="tanggalTenggat">Tanggal Tenggat Pengisian</label>
                                <x-adminlte-input type="date" name="tanggalTenggat" id="tanggalTenggat" required />
                            </div>

                            <!-- Waktu Tenggat -->
                            <div class="col-md-6">
                                <label for="waktuTenggat">Waktu Tenggat</label>
                                <x-adminlte-input type="time" name="waktuTenggat" id="waktuTenggat" required />
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end px-3">
                        <button type="submit" class="btn btn-primary btn-md my-1">
                            Simpan <i class="fa-solid fa-floppy-disk"></i>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/tambah_aspek_formulir.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/tambah_aspek_formulir.js') }}"></script>
@stop