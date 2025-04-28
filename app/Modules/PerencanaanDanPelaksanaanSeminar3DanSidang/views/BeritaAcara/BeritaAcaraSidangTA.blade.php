@extends('adminlte::page')

@section('title', 'PerencanaanDanPelaksanaanSeminarDanSidang')

@section('content_header')
    <h1>Berita Acara Pelaksanaan Sidang TA</h1>
@stop

@section('content')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="w-10">KoTA</th>
                            <th class="w-20">Nim</th>
                            <th class="w-20">Nama Mahasiswa</th>
                            <th class="w-15">Tanggal</th>
                            <th class="w-15">Ruangan</th>
                            <th class="w-10">Sesi</th>
                            <th class="w-10">Status Kehadiran</th>
                            <th class="w-20">Dokumentasi</th>
                            <th class="w-10">Batas Revisi</th>
                            <th class="w-15">Status Kelulusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beritaAcaraSidangTA as $index => $item)
                            <tr>
                                <td>{{ $item->penjadwalan->kota->nama_kota ?? '-' }}</td>
                                <td>{{ $item->user->mahasiswa->nim ?? '-' }}</td>
                                <td>{{ $item->user->nama ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->tanggal ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->id_ruangan ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->sesi ?? '-' }}</td>
                                <td>
                                    @if($item->status_hadir == 'hadir')
                                        <button class="btn btn-success btn-sm" disabled>Hadir</button>
                                    @elseif($item->status_hadir == 'tidak_hadir')
                                        <button class="btn btn-danger btn-sm" disabled>Absen</button>
                                    @elseif($item->status_hadir == 'belum_absen' && $sekarang->isSameDay($item->penjadwalan->tanggal))
                                        <form action="{{ route('presensi.hadir') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id_kehadiran" value="{{ $item->id_kehadiran }}">
                                            <input type="hidden" name="status_hadir" value="hadir">
                                            <button type="submit" class="btn btn-info btn-sm">Absensi</button>
                                        </form>
                                    @elseif($item->status_hadir == 'belum_absen' && $sekarang->gt(($item->penjadwalan->tanggal)))
                                        <button class="btn btn-danger btn-sm" disabled>Absen</button>
                                    @else
                                        <button class="btn btn-warning btn-sm" disabled>Belum Absen</button>
                                    @endif
                                </td>
                                <td style="width: 250px;">
                                    @if($item->foto_sidang)
                                        <div class="mb-2">
                                            <strong>File Terupload:</strong>
                                            <a href="{{ asset('storage/' . $item->foto_sidang) }}" target="_blank">
                                                {{ \Illuminate\Support\Str::limit(basename($item->foto_sidang),15) }}
                                            </a>
                                        </div>
                                    @endif
                                    <form action="{{ route('presensi.dokumentasi') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id_kehadiran" value="{{ $item->id_kehadiran }}">
                                        <div class="form-group">
                                            <input type="file" name="dokumentasi" class="form-control-file form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                            <small class="text-muted">Maksimal 5 MB (JPG, JPEG, PNG, PDF)</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Unggah</button>
                                    </form>
                                </td>
                                <td>
                                    @if($item->batas_revisi)
                                        {{ $item->batas_revisi }}
                                    @else
                                        <form action="{{ route('simpan.batas.revisi') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_kehadiran" value="{{ $item->id_kehadiran }}">
                                            <input type="date" name="batas_revisi" class="form-control form-control-sm" value="{{ $item->batas_revisi }}">
                                            <button type="submit" class="btn btn-primary btn-sm mt-2">Simpan</button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status_kelulusan == 'pending')
                                        <!-- Tombol untuk membuka modal -->
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalStatusKelulusan{{ $item->id_kehadiran }}">
                                            Isi Status
                                        </button>
                                    @else
                                        {{ $item->status_kelulusan }}  
                                    @endif
                                </td>
                            </tr>
                            <!-- Modal untuk mengisi status kelulusan -->
                            <div class="modal fade" id="modalStatusKelulusan{{ $item->id_kehadiran }}" tabindex="-1" role="dialog" aria-labelledby="modalStatusKelulusanLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalStatusKelulusanLabel">Isi Status Kelulusan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('simpan.status.kelulusan') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_kehadiran" value="{{ $item->id_kehadiran }}">
                                                <div class="form-group">
                                                    <label for="status_kelulusan">Pilih Status Kelulusan:</label>
                                                    <select name="status_kelulusan" class="form-control" required>
                                                        <option value="lulus_tanpa_perbaikan_laporan">Lulus Tanpa Perbaikan</option>
                                                        <option value="lulus_dengan_perbaikan_laporan">Lulus Dengan Perbaikan</option>
                                                        <option value="mengulang_sidang_tugas_akhir">Mengulang Sidang</option>
                                                        <option value="tidak_lulus">Tidak Lulus</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
@stop