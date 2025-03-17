{{-- @extends('adminlte::page')

@section('title', 'Pengajuan')

@section('content_header')
    <h1>Pengajuan</h1>
@stop

@section('content')
<div class="row p-4 bg-light">
    <!-- Card Pengajuan Seminar 3 -->
    <div class="col-md-6">
        @php
            $seminar3Enabled = $artifact->seminar1 && $artifact->seminar2 && $artifact->berkas;
        @endphp
        <x-adminlte-card title="Pengajuan Seminar 3" theme="{{ $seminar3Enabled ? 'primary' : 'secondary' }}" icon="fas fa-file-alt"
            style="{{ $seminar3Enabled ? '' : 'opacity: 0.5; pointer-events: none;' }}">
            <p>Seminar 3 dapat diajukan setelah menyelesaikan tahap seminar 1 dan 2 serta telah melengkapi seluruh berkas yang dibutuhkan.</p>
            <div class="text-center">
                @if ($seminar3Enabled)
                    <a href="{{ route('pengajuan-seminar3') }}" class="btn btn-primary">Buat Pengajuan</a>
                @else
                    <button class="btn btn-secondary" disabled>Belum Memenuhi Syarat</button>
                @endif
            </div>
        </x-adminlte-card>
    </div>

    <!-- Card Pengajuan Sidang Akhir -->
    <div class="col-md-6">
        @php
            $sidangEnabled = $artifact->seminar1 && $artifact->seminar2 && $artifact->berkas && $artifact->seminar3;
        @endphp
        <x-adminlte-card title="Pengajuan Sidang Akhir" theme="{{ $sidangEnabled ? 'primary' : 'secondary' }}" icon="fas fa-graduation-cap"
            style="{{ $sidangEnabled ? '' : 'opacity: 0.5; pointer-events: none;' }}">
            <p>Sidang akhir dapat diajukan setelah menyelesaikan tahap seminar 3 serta telah melengkapi seluruh berkas yang dibutuhkan.</p>
            <div class="text-center">
                @if ($sidangEnabled)
                    <a href="{{ route('pengajuan-sidang') }}" class="btn btn-primary">Buat Pengajuan</a>
                @else
                    <button class="btn btn-secondary" disabled>Belum Memenuhi Syarat</button>
                @endif
            </div>
        </x-adminlte-card>
    </div>
</div>
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop --}}

@extends('adminlte::page')

@section('title', 'Pengajuan')

@section('content_header')
    <h1 class="mx-4"><strong>Daftar Pengajuan</strong></h1>
@stop

@section('content')
<div class="row p-4 bg-light text-center">
    <!-- Card Pengajuan Seminar 3 -->
    <div class="col-md-6">
        @php
            $seminar3Approved = collect($verifikasi)->contains(fn($item) => $item->jenis_pangajuan === 'seminar_3' && $item->status_konfirmasi === 'disetujui');
        @endphp
        <x-adminlte-card title="Pengajuan Seminar 3" theme="{{ $seminar3Approved ? 'primary' : 'secondary' }}" icon="fas fa-file-alt"
            style="{{ $seminar3Approved ? '' : 'opacity: 0.5; pointer-events: none;' }}">
            <p>Seminar 3 dapat diajukan setelah mendapatkan persetujuan dari verifikasi berkas.</p>
            <div class="text-center">
                @if ($seminar3Approved)
                    <a href="{{ route('pengajuan-seminar3') }}" class="btn btn-primary">Buat Pengajuan</a>
                @else
                    <button class="btn btn-secondary" disabled>Belum Memenuhi Syarat</button>
                @endif
            </div>
        </x-adminlte-card>
    </div>

    <!-- Card Pengajuan Sidang Akhir -->
    <div class="col-md-6">
        @php
            $sidangApproved = $seminar3Approved && collect($verifikasi)->contains(fn($item) => $item->jenis_pangajuan === 'sidang' && $item->status_konfirmasi === 'disetujui');
        @endphp
        <x-adminlte-card title="Pengajuan Sidang Akhir" theme="{{ $sidangApproved ? 'primary' : 'secondary' }}" icon="fas fa-graduation-cap"
            style="{{ $sidangApproved ? '' : 'opacity: 0.5; pointer-events: none;' }}">
            <p>Sidang akhir dapat diajukan setelah Seminar 3 disetujui dan mendapatkan persetujuan dari verifikasi berkas.</p>
            <div class="text-center">
                @if ($sidangApproved)
                    <a href="{{ route('pengajuan-sidang') }}" class="btn btn-primary">Buat Pengajuan</a>
                @else
                    <button class="btn btn-secondary" disabled>Belum Memenuhi Syarat</button>
                @endif
            </div>
        </x-adminlte-card>
    </div>
</div>
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
