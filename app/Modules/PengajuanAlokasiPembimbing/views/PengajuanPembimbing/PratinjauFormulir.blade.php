@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <div class="m-3">
        <h1>Formulir Pengajuan Dosen Pembimbing</h1>
    </div>
@stop

@section('content')

    <div class="container-fluid row w-100 justify-content-start">
        <div class="card p-4 bg-light">
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="4"
                activeColor="primary" inactiveColor="secondary" :hrefs="['#', '#', '#', '#']" />
        </div>

        <div class="col">
            <div class="card p-4 bg-light">
                <h5 class="mb-3">Data Mahasiswa</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Anggota 1</label>
                        <p>Nama Lengkap:</p>
                        <p>NIM:</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Anggota 2</label>
                        <p class="fw-bold">Nama Lengkap:</p>
                        <p>NIM:</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Anggota 3</label>
                        <p class="fw-bold">Nama Lengkap:</p>
                        <p>NIM:</p>
                    </div>
                </div>

                <h5 class="mb-3 fw-bold">Topik Tugas Akhir</h5>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>

                <h5 class="mt-3 mb-3 fw-bold">Prioritas Dosen Pembimbing</h5>
                <ol>
                    <li>Nama Lengkap</li>
                    <li>Nama Lengkap</li>
                    <li>Nama Lengkap</li>
                    <li>Nama Lengkap</li>
                    <li>Nama Lengkap</li>
                </ol>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.prioritas-dosen-pembimbing') }}" class="btn btn-info">Sebelumnya</a>
                    <button type="submit" class="btn btn-sm btn-primary" style="font-size: 15px">Finalisasi Data</button>
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
