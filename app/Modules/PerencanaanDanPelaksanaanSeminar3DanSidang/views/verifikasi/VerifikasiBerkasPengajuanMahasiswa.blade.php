@extends('adminlte::page')

@section('title', 'Verifikasi Berkas Pengajuan Mahasiswa')

@section('content_header')
    <h1 class="mx-4"><strong>Verifikasi Berkas Pengajuan Mahasiswa</strong></h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($pengajuan)
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">Status Pengajuan</h3>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4 font-weight-bold">Status</div>
                    <div class="col-sm-8">
                        @if($pengajuan->status_konfirmasi === 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($pengajuan->status_konfirmasi === 'tidak_disetujui')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </div>
                </div>

                @if($pengajuan->catatan)
                <div class="row mb-2">
                    <div class="col-sm-4 font-weight-bold">Catatan dari Koordinator TA</div>
                    <div class="col-sm-8">
                        {{ $pengajuan->catatan }}
                    </div>
                </div>
                @endif

                <div class="row mb-2">
                    <div class="col-sm-4 font-weight-bold">Tanggal Pengajuan</div>
                    <div class="col-sm-8">
                        {{ $pengajuan->formatted_tanggal_pengajuan }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4 font-weight-bold">Jenis Pengajuan</div>
                    <div class="col-sm-8">
                        Seminar 3
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Daftar Artefak Yang Perlu Dikumpulkan</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Artefak</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr>
                            <td>{{ $item['nama_artefak'] }}</td>
                            <td>
                                @if ($item['status'] === 'Sudah diunggah')
                                    <span class="badge bg-success">{{ $item['status'] }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $item['status'] }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">Tidak ada data artefak.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <form action="{{ route('verifikasi3.store') }}" method="POST" class="text-center mt-4">
                @csrf
                <button type="submit" class="btn btn-primary btn-md" 
                    @if ($tidakBisaAjukan) disabled @endif>
                    Ajukan Verifikasi Berkas
                </button>
            </form>

            @if ($pengajuan && in_array($pengajuan->status_konfirmasi, ['disetujui', 'pending']))
                <div class="alert alert-warning mt-3">
                    Pengajuan tidak dapat dilakukan karena status konfirmasi adalah <strong>{{ $pengajuan->status_konfirmasi }}</strong>.
                </div>
            @endif

            @if ($adaBelumUpload)
                <div class="alert alert-danger mt-3">
                    Pengajuan tidak dapat dilakukan karena ada artefak yang belum di-upload.
                </div>
            @endif
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
