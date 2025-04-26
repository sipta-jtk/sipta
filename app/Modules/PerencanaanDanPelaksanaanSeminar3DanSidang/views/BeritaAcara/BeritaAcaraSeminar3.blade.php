@extends('adminlte::page')

@section('title', 'Berita Acara Pelaksanaan Seminar 3')

@section('content_header')
    <h1 class="mb-3">Berita Acara Pelaksanaan Seminar 3</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Berita Acara Pelaksanaan Seminar 3']
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th class="w-10">KoTA</th>
                            <th class="w-15">NIM</th>
                            <th class="w-20">Nama Mahasiswa</th>
                            <th class="w-15">Tanggal</th>
                            <th class="w-15">Ruangan</th>
                            <th class="w-10">Sesi</th>
                            <th class="w-10">Status Kehadiran</th>
                            <th class="w-15">Dokumentasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beritaAcaraSeminar3 as $index => $item)
                            <tr>
                                <td>{{ $item->penjadwalan->kota->nama_kota ?? '-' }}</td>
                                <td>{{ $item->user->mahasiswa->nim ?? '-' }}</td>
                                <td>{{ $item->user->nama ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->translatedFormat ?? '-' }}</td>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
@stop