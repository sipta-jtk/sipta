@extends('adminlte::page')

@section('title', 'Daftar Program Studi')

@section('content_header')
    <h1>Program Studi</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Program Studi</h3>
        <div class="card-tools">
            <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
    </div> 
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="col-1 text-center">No</th>
                    <th class="col-3">Nama Prodi</th>
                    <th class="col-3">Ketua Prodi</th>
                    <th class="col-1 text-center">Maksimal Anggota Kota</th>
                    <th class="col-1 text-center">Maksimal Mahasiswa Bimbingan</th>
                    <th class="col-1 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($programStudi as $index => $prodi)
    <tr>
        <td class="text-center">{{ $index + 1 }}</td>
        <td>{{ $prodi->nama_prodi }}</td>
        <td>{{ $prodi->ketua_prodi ?? '-' }}</td>
        <td class="text-center">{{ $prodi->maksimal_anggota_kota }}</td>
        <td class="text-center">{{ $prodi->maksimal_mahasiswa_bimbingan }}</td>
        <td class="text-center">
            <button class="btn btn-warning btn-xs me-2" data-toggle="modal" data-target="#editModal{{ $prodi->id_prodi }}">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal" data-id="{{ $prodi->id_prodi }}">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal{{ $prodi->id_prodi }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $prodi->id_prodi }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $prodi->id_prodi }}">Edit Program Studi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('program-studi.update', $prodi->id_prodi) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama_prodi">Nama Prodi</label>
                            <input type="text" name="nama_prodi" class="form-control" value="{{ $prodi->nama_prodi }}" required>
                        </div>
                        <div class="form-group">
                            <label for="id_kaprodi">Ketua Prodi</label>
                            <select name="id_kaprodi" class="form-control" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosen as $d)
                                    <option value="{{ $d->nip }}" {{ ($prodi->kaprodi_nip ?? '') == $d->nip ? 'selected' : '' }}>
                                        {{ $d->nama_dosen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="maksimal_anggota_kota">Maksimal Anggota Kota</label>
                            <input type="number" name="maksimal_anggota_kota" class="form-control" value="{{ $prodi->maksimal_anggota_kota }}" required min="1">
                        </div>
                        <div class="form-group">
                            <label for="maksimal_mahasiswa_bimbingan">Maksimal Mahasiswa Bimbingan</label>
                            <input type="number" name="maksimal_mahasiswa_bimbingan" class="form-control" value="{{ $prodi->maksimal_mahasiswa_bimbingan }}" required min="1">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Program Studi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('program-studi.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_prodi">Nama Prodi</label>
                        <input type="text" name="nama_prodi" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="id_kaprodi">Ketua Prodi</label>
                        <select name="id_kaprodi" class="form-control" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosen as $d)
                              <option value="{{ $d->nip }}">{{ $d->nama_dosen }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="maksimal_anggota_kota">Maksimal Anggota Kota</label>
                        <input type="number" name="maksimal_anggota_kota" class="form-control" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="maksimal_mahasiswa_bimbingan">Maksimal Mahasiswa Bimbingan</label>
                        <input type="number" name="maksimal_mahasiswa_bimbingan" class="form-control" required min="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>  
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus prodi ini?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var action = '{{ route('program-studi.destroy', ':id') }}';
        action = action.replace(':id', id);
        $('#deleteForm').attr('action', action);
    });
</script>
@stop