@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Form Pengajuan Dosen Pembimbing</h1>
@stop

@section('content')

    {{-- <p>Data Kelompok > <a href="www">Topik Tugas Akhir</a> > <a href="www">Prioritas Dosen Pembimbing</a> > <a href="www">Pratinjau</a></p> --}}

    <div class="container-fluid row w-100 justify-content-start">
        <div class="card p-4 bg-light">
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="2"
                activeColor="primary" inactiveColor="secondary" :hrefs="['#', '#', '#', '#']" />
        </div>
            <!-- Form Pengajuan -->
        <div class="col-md-9">
            <div class="card p-4 bg-light">
                <h5 class="mb-3">Topik Tugas Akhir</h5>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Topik/Judul Tugas Akhir</label>
                        <input type="text" class="form-control" placeholder="Masukkan topik/judul">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Bidang Tugas Akhir</label>
                        <div class="container-fluid bg-gradient-info rounded-top">
                            <div class="container">
                                <div class="row row-cols-2 p-2">
                                    <div class="col">
                                        <p class="m-0">Daftar Bidang</p>
                                    </div>
                                    <div class="col text-right">
                                        <a class="m-0 text-light"> <i class="fas fa-plus"></i> Tambah Bidang</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ================== --}}
                        <div class="container-fluid bg-secondary rounded-bottom bg-opacity-25 pre-scrollable mb-4">
                    
                            <div class="container text-center ">
                                <div class="row row-cols-3 p-3">
                                    @for ($i = 0; $i < 50; $i++)
                                        <div class="col">
                                            <div class="pretty p-default p-fill">
                                                <input type="checkbox" />
                                                <div class="state p-info text-left" style="min-width: 150px;">
                                                    <label>
                                                        Bidang {{ $i + 1 }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan & Selanjutnya -->
                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-info ml-3">Sebelumnya</button>
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
