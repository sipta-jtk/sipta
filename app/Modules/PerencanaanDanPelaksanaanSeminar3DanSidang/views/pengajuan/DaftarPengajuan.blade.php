@extends('adminlte::page')

@section('title', 'Pengajuan')

@section('content_header')
    <h1 class="mx-4"><strong>Daftar Pengajuan</strong></h1>
@stop

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
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
<div class="row p-4 bg-light text-center">
    <!-- Card Pengajuan Seminar 3 -->
    <div class="col-md-6">
        @php
            $seminar3Approved = collect($verifikasi)->contains(fn($item) => $item->jenis_pengajuan === 'seminar_3' && $item->status_konfirmasi === 'disetujui');
        @endphp
        <x-adminlte-card title="Pengajuan Seminar 3" theme="{{ $seminar3Approved ? 'primary' : 'secondary' }}" icon="fas fa-file-alt"
            class="d-flex flex-column h-100"
            style="{{ $seminar3Approved ? '' : 'opacity: 0.5; pointer-events: none;' }}">
            <div class="d-flex flex-column flex-grow-1">
                <p class="flex-grow-1">Seminar 3 dapat diajukan setelah pengajuan berkas persyaratan Seminar 3 telah mendapat persetujuan.</p>
                <div class="mt-auto">
                    @if ($seminar3Approved)
                        <a href="{{ route('pengajuan-seminar3', ['id_kota']) }}" class="btn btn-primary">Buat Pengajuan</a>
                    @else
                        <button class="btn btn-secondary" disabled>Belum Memenuhi Syarat</button>
                    @endif
                </div>
            </div>
        </x-adminlte-card>
    </div>

    <!-- Card Pengajuan Sidang Akhir -->
    <div class="col-md-6">
        @php
            $sidangApproved = $seminar3Approved && collect($verifikasi)->contains(fn($item) => $item->jenis_pengajuan === 'sidang' && $item->status_konfirmasi === 'disetujui');
        @endphp
        <x-adminlte-card title="Pengajuan Sidang Akhir" theme="{{ $sidangApproved ? 'primary' : 'secondary' }}" icon="fas fa-graduation-cap"
            class="d-flex flex-column h-100"
            style="{{ $sidangApproved ? '' : 'opacity: 0.5; pointer-events: none;' }}">
            <div class="d-flex flex-column flex-grow-1">
                <p class="flex-grow-1">Sidang Akhir dapat diajukan setelah Seminar 3 selesai dan pengajuan berkas persyaratan Sidang Akhir telah mendapat persetujuan.</p>
                <div class="mt-auto">
                    @if ($sidangApproved)
                        <a href="{{ route('pengajuan-sidang') }}" class="btn btn-primary">Buat Pengajuan</a>
                    @else
                        <button class="btn btn-secondary" disabled>Belum Memenuhi Syarat</button>
                    @endif
                </div>
            </div>
        </x-adminlte-card>
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
