@extends('adminlte::page')

@section('title', 'Detail Artefak')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col">
                        <h1 class="m-0">Detail Kelompok Tugas Akhir</h1>
                    </div>
                    <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                        <span class="badge rounded-pill bg-success" style="font-size: 1.0em;">
                            <i class="nav-icon fas fa-check"></i>
                            Sudah Mengumpulkan
                        </span>
                        <span class="badge rounded-pill bg-secondary" style="font-size: 1.0em;">
                            <i class="nav-icon fas fa-times"></i>
                            Belum Mengumpulkan
                        </span>
                    </div>
                </div><!-- /.row -->
            <hr/>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $artefak->nama_artefak }}</h5>
                <p class="card-text">{{ $artefak->deskripsi }}</p>
                <p class="card-text"><small class="text-muted">{{ $artefak->kategori_artefak }}</small></p>
                <p class="card-text"><small class="text-muted">Tenggat Waktu: {{ $artefak->tenggat_waktu }}</small></p>
            </div>
        </div>
        <a href="{{ route('artefaks.index') }}" class="btn btn-secondary mt-3">Kembali</a>

        <br>
        <br>
        <br>
@endsection      