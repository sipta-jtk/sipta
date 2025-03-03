@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Form Pengajuan Dosen Pembimbing</h1>
@stop

@section('content')

    {{-- <p>Data Kelompok > <a href="www">Topik Tugas Akhir</a> > <a href="www">Prioritas Dosen Pembimbing</a> > <a href="www">Pratinjau</a></p> --}}

    <div class="container-fluid row w-100 justify-content-start">
        <div class="card p-4 bg-light">
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="1"
                activeColor="primary" inactiveColor="secondary" :hrefs="['#', '#', '#', '#']" />
        </div>

            <!-- Form Pengajuan -->
        <div class="col-md-9">
            <div class="card p-4 bg-light">
                <h5 class="mb-3">Data Mahasiswa</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control" placeholder="Masukkan kelas">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" placeholder="Masukkan NIM">
                    </div>
                </div>

                <h5 class="mb-3">Data Kelompok</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Anggota 1</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" placeholder="Masukkan NIM">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Anggota 2</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" placeholder="Masukkan NIM">
                    </div>
                </div>

                <!-- Tombol Simpan & Selanjutnya -->
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary ml-3">Simpan</button>
                    <button class="btn btn-info ml-3">Selanjutnya</button>
                </div>
            </div>
        </div>
    </div>

    
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

{{-- @section('js')
@stop --}}
