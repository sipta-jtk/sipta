@extends('adminlte::page')

@section('title', 'Kelola Artefak')

@section('content')
<div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col">
                        <h1 class="m-0">Daftar Artefak</h1>
                    </div>
                    @if (auth()->user()->role_user=="dosen")
                    <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                        <!-- <form class="me-m   d-2" action="{{ route('artefak.search') }}" method="GET">
                            <input type="text" name="keyword" placeholder="Cari Artefak...">
                            <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                        </form> -->
                        <!-- <a href="{{ url('/artefak/create') }} "> -->
                            <button type="button" class="btn btn-success"  data-toggle="modal" data-target="#tambahArtefakModal">
                                Tambah
                                <i class="nav-icon fas fa-plus"></i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="tambahArtefakModal" tabindex="-1" role="dialog" aria-labelledby="tambahArtefakModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('artefak.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="tambahArtefakModalLabel">Tambah Artefak</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="nama_artefak">Nama Artefak</label>
                                                    <select name="nama_artefak" id="nama_artefak" class="form-control" required>
                                                        <option value="" disabled selected>Pilih Nama Artefak</option>
                                                        @foreach($masterArtefaks as $masterArtefak)
                                                            <option value="{{ $masterArtefak }}" {{ old('nama_artefak') == $masterArtefak ? 'selected' : '' }}>{{ $masterArtefak }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="deskripsi">Deskripsi</label>
                                                    <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="kategori_artefak">Kategori Artefak</label>
                                                    <select name="kategori_artefak" id="kategori_artefak" class="form-control" required>
                                                        <option value="" disabled selected>Pilih Kategori Artefak</option>
                                                        <option value="FTA" {{ old('kategori_artefak') == 'FTA' ? 'selected' : '' }}>FTA</option>
                                                        <option value="Dokumen" {{ old('kategori_artefak') == 'Dokumen' ? 'selected' : '' }}>Dokumen</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tanggal_tenggat">Tanggal Tenggat</label>
                                                    <input type="date" class="form-control" id="tanggal_tenggat" name="tanggal_tenggat" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="waktu_tenggat">Waktu Tenggat</label>
                                                    <input type="time" class="form-control" id="waktu_tenggat" name="waktu_tenggat" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <!-- </a> -->
                    </div>
                    @endif
                </div><!-- /.row -->
            <hr/>
        </div><!-- /.container-fluid -->

        <!-- Main content -->
        <div class="content">
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- DataTables Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row mt-4">
                            @foreach($artefaks as $artefak)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div class="col"><h4>{{ $artefak->nama_artefak }}</h4></div>
                                        @if (auth()->user()->role_user=="dosen")
                                        <div class="col d-flex justify-content-end">
                                            <div class="mr-2">
                                                <!-- Button edit -->
                                                <a href="#" class="edit-artefak" data-placement="top" title="Edit Artefak" data-toggle="modal" data-target="#editArtefakModal{{ $artefak->id_artefak }}">
                                                    <i class="nav-icon fas fa-pen" style="color: blue;"></i>
                                                </a>
                                                <!-- Modal Edit Artefak -->
                                                <div class="modal fade" id="editArtefakModal{{ $artefak->id_artefak }}" tabindex="-1" aria-labelledby="editArtefakModalLabel{{ $artefak->id_artefak }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('artefak.update', $artefak->id_artefak) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editArtefakModalLabel{{ $artefak->id_artefak }}">Edit Artefak</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="nama_artefak">Nama Artefak</label>
                                                                        <input type="text" class="form-control" id="nama_artefak" name="nama_artefak" value="{{ old('nama_artefak', $artefak->nama_artefak) }}" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="deskripsi">Deskripsi</label>
                                                                        <textarea class="form-control" id="deskripsi" name="deskripsi" required>{{ old('deskripsi', $artefak->deskripsi) }}</textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="kategori_artefak">Kategori Artefak</label>
                                                                        <select name="kategori_artefak" id="kategori_artefak" class="form-control" required>
                                                                            <option value="" disabled>Pilih Kategori Artefak</option>
                                                                            <option value="FTA" {{ old('kategori_artefak', $artefak->kategori_artefak) == 'FTA' ? 'selected' : '' }}>FTA</option>
                                                                            <option value="Dokumen" {{ old('kategori_artefak', $artefak->kategori_artefak) == 'Dokumen' ? 'selected' : '' }}>Dokumen</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="tenggat_waktu">Tenggat Waktu</label>
                                                                        <input type="datetime-local" class="form-control" id="tenggat_waktu" name="tenggat_waktu" value="{{ old('tenggat_waktu', $artefak->tenggat_waktu) }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="#" data-placement="top" title="Delete Artefak" data-toggle="modal" data-target="#deleteArtefakModal-{{ $artefak->id_artefak }}">
                                                    <i class="nav-icon fas fa-trash" style="color: red;"></i>
                                                </a>
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteArtefakModal-{{ $artefak->id_artefak }}" tabindex="-1" role="dialog" aria-labelledby="deleteArtefakModalLabel-{{ $artefak->id_artefak }}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteArtefakModalLabel-{{ $artefak->id_artefak }}">Konfirmasi Hapus</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Apakah Anda yakin ingin menghapus artefak "<strong>{{ $artefak->nama_artefak }}</strong>"?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('artefak.destroy', $artefak->id_artefak) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{{ $artefak->deskripsi }}</p>
                                        <p class="card-text"><small class="text-muted">Tenggat: <strong>{{ $artefak->formatted_tenggat_waktu }}</strong></small></p>
                                        @if (auth()->user()->role_user == "mahasiswa")
                                            @if ($artefak->kumpul)
                                                <div>
                                                    <a href="{{ Storage::url($artefak->kumpul->file_pengumpulan) }}" target="_blank">
                                                        <i class="fas fa-file-pdf"></i> {{ basename($artefak->kumpul->file_pengumpulan) }}
                                                    </a>                                                
                                                    <form action="{{ route('submissions.destroy', $artefak->kumpul->id_artefak) }}" method="POST" class="mt-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                            <button class="btn btn-danger" type="submit">Hapus File</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="input-group mb-3">
                                                    <form action="{{ route('submissions.store', $artefak->id_artefak) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="file" class="form-control" name="file_pengumpulan" required>
                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2">
                                                            <button class="btn btn-primary" type="submit">Kumpulkan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- @include('sweetalert::alert') -->
@endsection
