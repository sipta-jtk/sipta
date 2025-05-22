@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="mb-3">Dashboard</h1>
@stop

@section('content')
<div class="row">
    <!-- Profil Mahasiswa -->
    <div class="col-md-6 d-flex">
        <div class="card card-primary flex-fill">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user mr-2"></i>Profil Mahasiswa</h3>
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="flex-grow-1"></div>

                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/avatar.png') }}" class="img-circle shadow" width="80" height="80" alt="User Image">
                    <div class="ml-3">
                        <div class="md-4 text-bold">Selamat Datang,</div>
                        <div class="md-4">Username</div>
                        <div class="md-4">NIM - Kelas</div>
                    </div>
                </div>

                <div class="flex-grow-1"></div>

                <div class="mt-auto text-right">
                    <a href="#" class="btn btn-primary mx-1">Edit Profil</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi KoTA -->
    <div class="col-md-6 d-flex">
        <div class="card card-info flex-fill">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-graduation-cap mr-2"></i>Informasi KoTA</h3>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">No KoTA</div>
                    <div class="col-md-8">{{ $kotaData['kode'] ?? $kota->nama_kota ?? '-' }}</div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Judul</div>
                    <div class="col-md-8">{{ $kotaData['judul'] ?? $kota->judul_ta ?? 'Belum Ada' }}</div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Pembimbing 1</div>
                    <div class="col-md-8">{{ $pembimbing[0]->nama ?? '-' }}</div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Pembimbing 2</div>
                    <div class="col-md-8">{{ isset($pembimbing[1]) ? $pembimbing[1]->nama : '-' }}</div>
                </div>
                
                <div class="text-right mt-3">
                    <!-- CATATAN: KONDISI JIKA MAHASISWA SUDAH ATAU BELUM MASUK KOTA -->
                    <!-- @if(isset($kotaData) && isset($kotaData['id']))
                        <a href="{{ route('detail.kota', ['id' => $kotaData['id']]) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye mr-1"></i> Lihat Detail KoTA
                        </a>
                    @else
                        <a href="{{ route('perekrutan-anggota-kota') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Rekrut Anggota KoTA
                        </a>
                    @endif -->

                    <a href="{{ route('kota.saya') }}" class="btn btn-info">
                        <i class="fas fa-eye mr-1"></i> Lihat Detail KoTA
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Berkas Pengajuan Seminar 3 -->
<div class="card card-warning mt-2">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-upload mr-2"></i>Berkas Pengajuan Seminar 3</h3>
    </div>
    <div class="card-body">
        @if(1)
            <ul class="list-group">
                <li class="list-group-item">FTA 10 - Sudah diunggah</li>
                <li class="list-group-item">FTA 10a - Belum diunggah</li>
                <li class="list-group-item">Proposal Tugas Akhir - Sudah diunggah</li>
                <li class="list-group-item">Presentasi - Belum diunggah</li>
            </ul>
        @else
            <div class="alert alert-info m-3">
                Belum ada data berkas seminar 3
            </div>
        @endif
    </div>
</div>

<!-- Berkas Pengajuan Sidang Akhir -->
<div class="card card-danger mt-4 mb-4">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-alt mr-2"></i>Berkas Pengajuan Sidang Akhir</h3>
    </div>
    <div class="card-body">
        @if(1)
            <ul class="list-group">
                <li class="list-group-item">FTA 14 - Belum diunggah</li>
                <li class="list-group-item">FTA 14a - Belum diunggah</li>
                <li class="list-group-item">Laporan Tugas Akhir - Belum diunggah</li>
                <li class="list-group-item">Presentasi - Belum diunggah</li>
            </ul>
        @else
            <div class="alert alert-info m-3">
                Belum ada data berkas sidang
            </div>
        @endif
    </div>
</div>
@stop