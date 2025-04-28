@extends('adminlte::page')

@section('title', 'Pengajuan')

@section('content_header')
    <h1 class="mx-4 mb-3">Daftar Pengajuan</h1>
    <div class="mx-4">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Daftar Pengajuan']
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (is_null($verifikasi->kota ?? null))
    <div class="p-4 bg-light text-center">
        <h3 class="flex-grow-1 p-5">Anda belum memiliki KoTA.</h3>
    </div>
@elseif (is_null($verifikasi->dosen ?? null))
    <div class="p-4 bg-light text-center">
        <h3 class="flex-grow-1 p-5">Anda belum memiliki pembimbing atau penguji.</h3>
    </div>
@else
<div class="mx-4">
    <div class="row bg-light text-center mb-3">
        <!-- Card Pengajuan Seminar 3 -->
        <div class="col-md-6">
            @php
                $seminar3Approved = collect($verifikasi)->contains(fn($item) => $item->jenis_pengajuan === 'seminar_3' && $item->status_konfirmasi === 'disetujui');
                $seminar3Active = isset($verifikasi->pengajuan) && ($verifikasi->pengajuanSeminar3['status'] !== 'Ditolak' || $verifikasi->pengajuanSeminar3['status'] !== 'Diterima');
                $seminar3InProgress = $verifikasi->pengajuanSeminar3['status'] === 'Diajukan' || $verifikasi->pengajuanSeminar3['status'] === 'Pembimbing' || $verifikasi->pengajuanSeminar3['status'] === 'Penguji' || $verifikasi->pengajuanSeminar3['status'] === 'Koordinator';
                $Seminar3Finished = $verifikasi->pengajuanSeminar3['status'] === 'Diterima'
            @endphp
            <x-adminlte-card title="Pengajuan Seminar 3" 
                theme="{{ $Seminar3Finished ? 'success' : ($seminar3Approved ? ($seminar3InProgress ? 'warning' : ($seminar3Active ? 'primary' : 'secondary')) : 'secondary') }}" 
                icon="fas fa-file-alt"
                class="d-flex flex-column h-100"
                style="{{ $Seminar3Finished ? 'opacity: 0.5; pointer-events: none;' : ($seminar3Approved && !$seminar3InProgress ? '' : 'opacity: 0.5; pointer-events: none;') }}">
                <div class="d-flex flex-column flex-grow-1">
                    <p class="flex-grow-1">Seminar 3 dapat diajukan setelah pengajuan berkas persyaratan Seminar 3 telah mendapat persetujuan.</p>
                    <div class="mt-auto">
                        @if (!$Seminar3Finished && $seminar3Approved && !$seminar3InProgress)
                            <a href="{{ route('pengajuan-seminar3', ['id_kota']) }}" class="btn btn-primary btn-md my-1">Buat Pengajuan</a>
                        @elseif (!$Seminar3Finished && $seminar3Approved && $seminar3InProgress)
                            <button class="btn btn-warning btn-md my-1" disabled>Sedang dalam Pengajuan</button>
                        @elseif ($Seminar3Finished)
                            <button class="btn btn-success btn-md my-1" disabled>Sudah Diterima</button>
                        @else
                            <button class="btn btn-secondary btn-md my-1" disabled>Belum Memenuhi Syarat</button>
                        @endif
                    </div>
                </div>
            </x-adminlte-card>
        </div>

        <!-- Card Pengajuan Sidang Akhir -->
        <div class="col-md-6">
            @php
                $sidangApproved = $Seminar3Finished && $seminar3Approved && collect($verifikasi)->contains(fn($item) => $item->jenis_pengajuan === 'sidang_akhir' && $item->status_konfirmasi === 'disetujui');
                $sidangStatus = $verifikasi->pengajuanSidang['status'] ?? null;
                $sidangActive = isset($verifikasi->pengajuanSidang);
                $sidangInProgress = in_array($sidangStatus, ['Diajukan', 'Pembimbing', 'Penguji', 'Koordinator']);
                $sidangFinished = $sidangStatus === 'Diterima';
            @endphp
            <x-adminlte-card title="Pengajuan Sidang Akhir"
                theme="{{ $sidangFinished ? 'success' : ($sidangApproved ? ($sidangInProgress ? 'warning' : ($sidangActive ? 'primary' : 'secondary')) : 'secondary') }}"
                icon="fas fa-file-alt"
                class="d-flex flex-column h-100"
                style="{{ $sidangFinished ? 'opacity: 0.5; pointer-events: none;' : ($sidangApproved && !$sidangInProgress ? '' : 'opacity: 0.5; pointer-events: none;') }}">
                <div class="d-flex flex-column flex-grow-1">
                    <p class="flex-grow-1">Sidang Akhir dapat diajukan setelah Seminar 3 selesai dan pengajuan berkas persyaratan Sidang Akhir telah mendapat persetujuan.</p>
                    <div class="mt-auto">
                        @if (!$sidangFinished && $sidangApproved && !$sidangInProgress)
                            <a href="{{ route('pengajuan-sidang') }}" class="btn btn-primary btn-md my-1">Buat Pengajuan</a>
                        @elseif (!$sidangFinished && $sidangApproved && $sidangInProgress)
                            <button class="btn btn-warning btn-md my-1" disabled>Sedang dalam Pengajuan</button>
                        @elseif ($sidangFinished)
                            <button class="btn btn-success btn-md my-1" disabled>Sudah Diterima</button>
                        @else
                            <button class="btn btn-secondary btn-md my-1" disabled>Belum Memenuhi Syarat</button>
                        @endif
                    </div>
                </div>
            </x-adminlte-card>
        </div>
    </div>

    <!-- Panggil Komponen Progress Step -->
    <div class="card row mx-1 p-4 bg-light text-center">
        <h2 class="mb-5"><strong>Status Pengajuan Seminar 3</strong></h2>
        <x-perencanaan-dan-pelaksanaan-seminar3-dan-sidang.components.pengajuan.status-pengajuan-jadwal-kota 
            :status="$verifikasi->pengajuanSeminar3['status'] ?? 'Belum Ada Pengajuan'" 
            :rejectedStep="$verifikasi->pengajuanSeminar3['rejected_step'] ?? null"
            activeColor="success"
            inactiveColor="secondary" />
    </div>
    <div class="card row mx-1 p-4 bg-light text-center">
        <h2 class="mb-5"><strong>Status Pengajuan Sidang</strong></h2>
        <x-perencanaan-dan-pelaksanaan-seminar3-dan-sidang.components.pengajuan.status-pengajuan-jadwal-kota 
            :status="$verifikasi->pengajuanSidang['status'] ?? 'Belum Ada Pengajuan'" 
            :rejectedStep="$verifikasi->pengajuanSidang['rejected_step'] ?? null"
            activeColor="success"
            inactiveColor="secondary" />
    </div>
</div>
@endif
@stop

@section('js')
<script>
    setTimeout(function() {
        let alert = document.querySelector(".alert");
        if (alert) {
            alert.style.transition = "opacity 0.5s";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }
    }, 10000); 
</script>
@stop
