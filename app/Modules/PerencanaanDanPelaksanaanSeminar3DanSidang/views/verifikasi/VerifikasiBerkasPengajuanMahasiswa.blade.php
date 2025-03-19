@extends('adminlte::page')

@section('title', 'Verifikasi Berkas Pengajuan Mahasiswa')

@section('content_header')
    <h1 class="mx-4"><strong>Verifikasi Berkas Pengajuan Mahasiswa</strong></h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($pengajuan)
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Status Pengajuan</h3>
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong> {{ ucfirst($pengajuan->status_konfirmasi) }}</p>
                    @if($pengajuan->catatan)
                        <p><strong>Catatan dari Dosen:</strong> {{ $pengajuan->catatan }}</p>
                    @endif
                    <p><strong>Tanggal Pengajuan:</strong> {{ $pengajuan->tanggal_pengajuan }}</p>
                    <p><strong>Jenis Pengajuan:</strong> {{ ucfirst($pengajuan->jenis_pangajuan) }}</p>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="card-title">Form Pengajuan</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('verifikasi.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="jenis_pengajuan">Jenis Pengajuan</label>
                        <select name="jenis_pengajuan" id="jenis_pengajuan" class="form-control" required>
                            <option value="seminar3">Seminar 3</option>
                            <option value="sidang">Sidang</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajukan</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
