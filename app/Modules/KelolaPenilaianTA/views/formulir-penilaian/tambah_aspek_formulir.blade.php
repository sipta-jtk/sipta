@extends('adminlte::page')

@section('title', 'Penambahan Aspek Penilaian')

@section('content_header')
<div class="container-fluid p-3">
    <!-- Judul Halaman -->
    <h1 class="mb-0">Penambahan Aspek Penilaian</h1>

    @component('KelolaPenilaianTA.views.components.breadcrumb', [
        'links' => [
            ['url' => route('beranda.get'), 'label' => 'Beranda'],
            ['url' => route('formulir-penilaian.index'), 'label' => 'Formulir Penilaian'],
            ['url' => '', 'label' => 'Tambah Aspek Penilaian']
        ]
    ])
    @endcomponent
</div>
@stop

@section('content')
    <div class="p-2">
        <div id="notification" class="alert alert-danger d-none" role="alert">
            <span id="notificationMessage"></span>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="aspekFormulirForm" action="{{ route('formulir-penilaian.simpan') }}" method="POST">
                    @csrf

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

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="namaFTA">Nama FTA</label>
                                    <x-adminlte-select name="namaFTA" id="namaFTA" required fgroup-class="mb-0">
                                        <option value="" disabled {{ old('namaFTA') ? '' : 'selected' }}>Pilih Nama FTA</option>
                                        <option value="Seminar I" {{ old('namaFTA') == 'Seminar I' ? 'selected' : '' }}>Seminar I</option>
                                        <option value="Seminar II" {{ old('namaFTA') == 'Seminar II' ? 'selected' : '' }}>Seminar II</option>
                                        <option value="Seminar III" {{ old('namaFTA') == 'Seminar III' ? 'selected' : '' }}>Seminar III</option>
                                        <option value="Sidang Akhir" {{ old('namaFTA') == 'Sidang Akhir' ? 'selected' : '' }}>Sidang Akhir</option>
                                        <option value="Dosen Pembimbing" {{ old('namaFTA') == 'Dosen Pembimbing' ? 'selected' : '' }}>Dosen Pembimbing</option>
                                    </x-adminlte-select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenisForm">Jenis Formulir</label>
                                    <x-adminlte-select name="jenisForm" id="jenisForm" required>
                                        <option value="" disabled {{ old('jenisForm') ? '' : 'selected' }}>-- Pilih Jenis Formulir --</option>
                                        <option value="Penilaian" {{ old('jenisForm') == 'Penilaian' ? 'selected' : '' }}>Penilaian</option>
                                        <option value="Feedback" {{ old('jenisForm') == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                    </x-adminlte-select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="namaProdi">Nama Prodi</label>
                                    <x-adminlte-select name="namaProdi" id="namaProdi" required>
                                        <option value="" disabled {{ old('namaProdi') ? '' : 'selected' }}>-- Pilih Nama Prodi --</option>
                                        @foreach ($prodiList as $prodi)
                                            <option value="{{ $prodi->id_prodi }}" {{ old('namaProdi') == $prodi->id_prodi ? 'selected' : '' }}>
                                                {{ $prodi->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </x-adminlte-select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6" id="jenisTAContainer" style="display: none;">
                                <div class="form-group">
                                    <label for="jenisTA">Jenis TA</label>
                                    <x-adminlte-select name="jenisTA" id="jenisTA" required>
                                        <option value="" disabled {{ old('jenisTA') ? '' : 'selected' }}>-- Pilih Jenis TA --</option>
                                        <option value="Penelitian" {{ old('jenisTA') == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                                        <option value="Pengembangan" {{ old('jenisTA') == 'Pengembangan' ? 'selected' : '' }}>Pengembangan</option>
                                    </x-adminlte-select>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                    @if(old('nama_kriteria'))
                                        @foreach(old('nama_kriteria') as $index => $namaKriteria)
                                            <tr>
                                                <td><input type="text" class="form-control" name="nama_kriteria[]" value="{{ $namaKriteria }}" required></td>
                                                <td><input type="number" class="form-control" name="bobot_kriteria[]" value="{{ old('bobot_kriteria')[$index] ?? '' }}" required></td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td><input type="text" class="form-control" name="nama_kriteria[]" required></td>
                                            <td><input type="number" class="form-control" name="bobot_kriteria[]" required></td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            <div class="bobot-summary alert alert-warning">Total bobot harus 100%. Saat ini: 0%</div>
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
                                    @if(old('nama_aspek_feedback'))
                                        @foreach(old('nama_aspek_feedback') as $namaAspekFeedback)
                                            <tr>
                                                <td><input type="text" class="form-control" name="nama_aspek_feedback[]" value="{{ $namaAspekFeedback }}" required></td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td><input type="text" class="form-control" name="nama_aspek_feedback[]" required></td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <br>

                    <div class="mb-2 px-3">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-secondary text-md border-bottom">Batas Waktu Pengisian</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tanggalTenggat">Tanggal Tenggat Pengisian</label>
                                <x-adminlte-input 
                                    type="date" 
                                    name="tanggalTenggat" 
                                    id="tanggalTenggat" 
                                    value="{{ old('tanggalTenggat', isset($formPenilaian) ? \Carbon\Carbon::parse($formPenilaian->tanggal_tenggat_pengisian)->format('Y-m-d') : '') }}" 
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                                <label for="waktuTenggat">Waktu Tenggat</label>
                                <x-adminlte-input 
                                    type="time" 
                                    name="waktuTenggat" 
                                    id="waktuTenggat" 
                                    value="{{ old('waktuTenggat', isset($formPenilaian) ? \Carbon\Carbon::parse($formPenilaian->waktu_tenggat_pengisian)->format('H:i') : '') }}" 
                                    required 
                                />
                            </div>
                        </div>
                    </div>

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
