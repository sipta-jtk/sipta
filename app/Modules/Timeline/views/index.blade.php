@extends('adminlte::page')

@section('title', 'Timeline')

@section('content')
 <!-- Content Header (Page header) -->
 <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Timeline</h1>
        </div><!-- /.col -->
        <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
            @if (auth()->user()->role_user== "dosen")
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addTimelineModal">
                Tambah
                <i class="nav-icon fas fa-plus"></i>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="addTimelineModal" tabindex="-1" role="dialog" aria-labelledby="addTimelineModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTimelineModalLabel">Tambah Timeline</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('timeline.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nama_kegiatan">Nama Kegiatan</label>
                                    <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_selesai">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="id_kategori_artefak">Pilih Artefak</label>
                                    <div class="form-check">
                                        @foreach ($masterArtefaks as $artefak)
                                            <input class="form-check-input" type="checkbox" id="artefak_{{ $artefak->id_kategori_artefak }}" name="id_kategori_artefak[]" value="{{ $artefak->id_kategori_artefak }}">
                                            <label class="form-check-label" for="artefak_{{ $artefak->id_kategori_artefak }}">
                                                {{ $artefak->jenis_artefak }}
                                            </label>
                                            <br>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div><!-- /.col -->
    </div><!-- /.row -->
    <hr>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid" style="background-color: B8C6E2;">
        <div class="row">
            <div class="col-md-12">
                <div class="timeline">
                    @foreach($timelines as $timeline)
                    <div class="time-label">
                        <span class="bg-secondary">{{ $timeline->tanggal_mulai }}</span>
                    </div>
                    <div>
                        <i class="fas fa-calendar bg-blue"></i>
                        <div class="timeline-item">
                            <div class="timeline-header d-flex justify-content-between align-items-center">
                                <strong>{{ $timeline->nama_kegiatan }}</strong>
                                @if (auth()->user()->role_user== "dosen")
                                <div class="mr-2"> 
                                    <!-- Edit Button trigger modal -->
                                    <a href="#" data-toggle="modal"  data-toggle="tooltip" data-placement="top" title="Edit Timeline" data-target="#editTimelineModal-{{ $timeline->id_timeline }}">
                                        <i class="nav-icon fas fa-pen" style="color: blue;"></i>
                                    </a>
                                    <!-- Delete Button trigger modal -->
                                    <a href="#" data-toggle="modal" data-placement="top" title="Delete Timeline" data-target="#deleteTimelineModal-{{ $timeline->id_timeline }}">
                                        <i class="nav-icon fas fa-trash" style="color: red;"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                            <div class="timeline-body">
                                <div>
                                    {{ $timeline->deskripsi }}
                                </div>
                                @if ($timeline->artefak->count())
                                    @foreach ($timeline->artefak as $artefak)
                                        <span class="badge badge-secondary">
                                            {{ $artefak->kategoriArtefak->jenis_artefak }}
                                        </span>
                                    @endforeach
                                @else
                                    Tidak ada artefak terkait.
                                @endif
                            </div>
                            <div class="timeline-footer">
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editTimelineModal-{{ $timeline->id_timeline }}" tabindex="-1" role="dialog" aria-labelledby="editTimelineModalLabel-{{ $timeline->id_timeline }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editTimelineModalLabel-{{ $timeline->id_timeline }}">Edit Timeline</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('timeline.update', $timeline->id_timeline) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="edit_nama_kegiatan_{{ $timeline->id_timeline }}">Nama Kegiatan</label>
                                                        <input type="text" class="form-control" id="edit_nama_kegiatan_{{ $timeline->id_timeline }}" name="nama_kegiatan" value="{{ $timeline->nama_kegiatan }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_tanggal_mulai_{{ $timeline->id_timeline }}">Tanggal Mulai</label>
                                                        <input type="date" class="form-control" id="edit_tanggal_mulai_{{ $timeline->id_timeline }}" name="tanggal_mulai" value="{{ $timeline->tanggal_mulai }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_tanggal_selesai_{{ $timeline->id_timeline }}">Tanggal Selesai</label>
                                                        <input type="date" class="form-control" id="edit_tanggal_selesai_{{ $timeline->id_timeline }}" name="tanggal_selesai" value="{{ $timeline->tanggal_selesai }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_deskripsi_{{ $timeline->id_timeline }}">Deskripsi</label>
                                                        <textarea class="form-control" id="edit_deskripsi_{{ $timeline->id_timeline }}" name="deskripsi" rows="3">{{ $timeline->deskripsi }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="id_kategori_artefak">Pilih Artefak</label>
                                                        <div class="form-check">
                                                            @foreach ($masterArtefaks as $artefak)
                                                                <input class="form-check-input" type="checkbox" id="edit_artefak_{{ $artefak->id_kategori_artefak }}" name="id_kategori_artefak[]" value="{{ $artefak->id_kategori_artefak }}" 
                                                                @if(in_array($artefak->id_kategori_artefak, $timeline->artefak->pluck('id_kategori_artefak')->toArray())) checked @endif>
                                                                <label class="form-check-label" for="edit_artefak_{{ $artefak->id_kategori_artefak }}">
                                                                    {{ $artefak->jenis_artefak }}
                                                                </label>
                                                                <br>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteTimelineModal-{{ $timeline->id_timeline }}" tabindex="-1" role="dialog" aria-labelledby="deleteTimelineModalLabel-{{ $timeline->id_timeline }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteTimelineModalLabel-{{ $timeline->id_timeline }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus timeline dengan kegiatan "<strong>{{ $timeline->nama_kegiatan }}</strong>"?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('timeline.destroy', $timeline->id_timeline) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /.timeline-footer -->
                        </div> <!-- /.timeline-item -->
                    </div>
                    @endforeach
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div> <!-- /.timeline -->
            </div>
        </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
</div>
<!-- /.content -->
<br>
<br>
<br>
@endsection
