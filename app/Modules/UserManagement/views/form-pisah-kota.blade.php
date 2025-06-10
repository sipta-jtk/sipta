@extends('adminlte::page')

@section('title', 'Form Pisah KoTA')

@section('content_header')
    <h1>Form Pisah KoTA</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @csrf
        <a href="{{ url()->previous() }}" class="btn btn-primary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <!-- Nama Mahasiswa -->
        <div class="mb-3">
            <label class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control" value="{{ $mahasiswa->user->nama ?? 'Data tidak ditemukan' }}" readonly>
        </div>

        @if(!empty($anggotaKelompok) && count($anggotaKelompok) > 0)
        <div class="mb-3">
            <label class="form-label">Nama Anggota</label>
            @foreach ($anggotaKelompok as $anggota)
                <input type="text" class="form-control mb-2" value="{{ $anggota->user->nama ?? 'Data tidak ditemukan' }}" readonly>
            @endforeach
        </div>
        @endif

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
        @elseif($kota->status_kota === 'aktif')
            <span class="text-danger">Belum ada file FTA 20.</span>
        @endif

        <div class="d-flex">
            <!-- Tampilkan Tombol sesuai Role User -->
            @if(auth()->user()->role_user === 'mahasiswa')
                <!-- Tombol Ajukan Pisah -->
                @if($pengajuan)
                    <!-- Kalau pengajuan udah ada -->
                    <form id="batal-pengajuan" action="{{ route('form.pisah.kota.batal') }}" method="POST" enctype="multipart/form-data" class="w-100">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Alasan</label>
                            <textarea name="alasan" class="form-control" id="alasan" rows="5" readonly>{{ old('alasan', $pengajuan?->alasan ?? '') }}</textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger">
                                 Batalkan Pengajuan <i class="fas fa-times-circle"></i>
                            </button>
                        </div>
                    </form>
                @elseif($kota->status_kota === 'pra_kota')
                    <!-- Pra KoTA pisah -->
                    <form id="pisah-pra-kota" action="{{ route('form.pisah.kota.prakota') }}" method="POST" enctype="multipart/form-data" class="w-100">
                        @csrf
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Pisah KoTA
                            </button>
                        </div>
                    </form>
                @elseif($kota->status_kota === 'aktif')
                    <!-- Kalau belum ada pengajuan -->
                    <form id="ajukan-pisah" action="{{ route('form.pisah.kota.ajukan') }}" method="POST" enctype="multipart/form-data" class="w-100">
                        @csrf
                        <div>
                            <div class="w-25 my-2">
                                <input type="file" class="form-control" name="fta_20" accept=".pdf" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alasan</label>
                                <textarea name="alasan" class="form-control" id="alasan" rows="5" >{{ old('alasan', $pengajuan?->alasan ?? '') }}</textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    Ajukan Pisah <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            @elseif(auth()->user()->role_user === 'dosen' && auth()->user()->dosen->role_dosen === 'koordinator_ta')
                <!-- Form Verifikasi Pisah -->
                <div class="w-100">
                    <div class="mb-3">
                        <label class="form-label">Alasan</label>
                        <textarea name="alasan" class="form-control" id="alasan" rows="5" readonly>{{ old('alasan', $pengajuan?->alasan ?? '') }}</textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <form id="tolak-pengajuan" action="{{ route('pengajuan.pisah.kota.tolak', $pengajuan->id_pengajuan ?? 0) }}" method="POST" class="mx-1" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                                <button type="submit" class="btn btn-danger">
                                    Tolak Pengajuan <i class="fas fa-times-circle"></i>
                                </button>               
                        </form>
                        <form id="terima-pengajuan" action="{{ route('pengajuan.pisah.kota.terima', $pengajuan->id_pengajuan ?? 0) }}" class="mx-1" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                                <button type="submit" class="btn btn-success">
                                    Terima Pengajuan <i class="fas fa-check"></i>
                                </button>               
                        </form>
                    </div>
                </div>
            @endif
        
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fungsi konfirmasi Swal
    function handleSwalConfirm(formId, message) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

    // Binding tombol - sesuaikan ID-nya
    document.getElementById('batal-pengajuan')?.addEventListener('submit', function(e) {
        e.preventDefault();
        handleSwalConfirm('batal-pengajuan', 'Yakin batalkan pengajuan pisah KoTA?');
    });

    document.getElementById('pisah-pra-kota')?.addEventListener('submit', function(e) {
        e.preventDefault();
        handleSwalConfirm('pisah-pra-kota', 'Yakin pisah KoTA? (Hal ini tidak dapat diurungkan)');
    });

    document.getElementById('ajukan-pisah')?.addEventListener('submit', function(e) {
        e.preventDefault();
        handleSwalConfirm('ajukan-pisah', 'Yakin mengajukan pisah KoTA?');
    });

    document.getElementById('terima-pengajuan')?.addEventListener('submit', function(e) {
        e.preventDefault();
        handleSwalConfirm('terima-pengajuan', 'Yakin menerima pengajuan pisah KoTA?');
    });

    document.getElementById('tolak-pengajuan')?.addEventListener('submit', function(e) {
        e.preventDefault();
        handleSwalConfirm('tolak-pengajuan', 'Yakin menolak pengajuan pisah KoTA?');
    });
</script>
@endsection