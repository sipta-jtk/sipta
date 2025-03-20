@extends('adminlte::page')

@section('title', 'Form Pisah KoTA')

@section('content_header')
    <h1>Form Pisah KoTA</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @csrf

        <!-- Nama Mahasiswa -->
        <div class="mb-3">
            <label class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control" value="{{ $mahasiswa->user->nama ?? 'Data tidak ditemukan' }}" readonly>
        </div>

        <!-- Info KoTA -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Judul TA</label>
                <input type="text" class="form-control" value="{{ $kota->judul_ta ?? '-' }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tahun TA</label>
                <input type="text" class="form-control" value="{{ $kota->tahun_kota ?? '-' }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nama KoTA</label>
                <input type="text" class="form-control" value="{{ $kota->nama_kota ?? '-' }}" readonly>
            </div>
        </div>

        <!-- Tampilkan File FTA Jika Ada -->
        @if($pengajuan && $pengajuan->fta_20)
            <div class="mb-3">
                <label class="form-label">File FTA 20 (PDF)</label>
                <div>
                    <a href="{{ asset('storage/' . $pengajuan->fta_20) }}" target="_blank" class="btn btn-info">
                        <i class="fas fa-file-pdf"></i> Lihat FTA 20
                    </a>
                </div>
            </div>
        @else
            <span class="text-danger">Belum ada file FTA 20.</span>
        @endif

        <div class="">

            <!-- Tampilkan Tombol sesuai Role User -->
            @if(auth()->user()->role_user === 'mahasiswa')
                <!-- Tombol Ajukan Pisah -->
                @if($pengajuan)
                    <!-- Kalau pengajuan udah ada -->
                    <form action="{{ route('form.pisah.kota.batal') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <a href="{{ route('timeline') }}" class="btn btn-secondary mx-2 my-3">
                                <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Yakin batalkan pengajuan?')">
                            <i class="fas fa-times-circle"></i> Batalkan Pengajuan
                        </button>
                    </form>
                @else
                    <!-- Kalau belum ada pengajuan -->
                    <form action="{{ route('form.pisah.kota.ajukan') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="gap-2">
                            <label class="form-label">Unggah FTA 20 (PDF)</label>
                            <input type="file" class="form-control" name="fta_20" accept=".pdf" required>
                            <a href="{{ route('timeline') }}" class="btn btn-secondary mx-2 my-3">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ajukan pisah?')">
                                <i class="fas fa-paper-plane"></i> Ajukan Pisah
                            </button>
                        </div>
                    </form>
                @endif
            @elseif(auth()->user()->role_user === 'dosen' && auth()->user()->dosen->role_dosen === 'koordinator_ta')
                <!-- Form Terima Pisah -->
                <form action="{{ route('pengajuan.pisah.kota.terima', $pengajuan->id_pengajuan ?? 0) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('PATCH')
                    <a href="{{ route('timeline') }}" class="btn btn-secondary mx-2 my-3">
                                <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success" onclick="return confirm('Yakin terima pisah?')">
                        <i class="fas fa-check"></i> Terima Pisah
                    </button>
                </form>
            @endif
        
        </div>
    </div>
</div>
@stop
