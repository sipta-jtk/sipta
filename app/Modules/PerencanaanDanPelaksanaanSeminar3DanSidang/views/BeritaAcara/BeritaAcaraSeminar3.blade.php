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
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 8%;">KoTA</th>
                            <th style="width: 8%;">NIM</th>
                            <th style="width: 15%;">Nama Mahasiswa</th>
                            <th style="width: 10%;">Tanggal</th>
                            <th style="width: 8%;">Ruangan</th>
                            <th style="width: 1%;">Sesi</th>
                            <th style="width: 12%;">Status Kehadiran</th>
                            <th style="width: 8%;">Dokumentasi</th>
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
                                        <button class="btn btn-success btn-md my-1 w-100" disabled style="opacity: 1">Hadir</button>
                                    @elseif($item->status_hadir == 'tidak_hadir')
                                        <button class="btn btn-danger btn-md my-1 w-100" disabled style="opacity: 1">Absen</button>
                                    @elseif($item->status_hadir == 'belum_absen' && $sekarang->isSameDay($item->penjadwalan->tanggal))
                                        <form action="{{ route('presensi.hadir') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id_kehadiran" value="{{ $item->id_kehadiran }}">
                                            <input type="hidden" name="status_hadir" value="hadir">
                                            <button type="submit" class="btn btn-info btn-md my-1 w-100">Absensi</button>
                                        </form>
                                    @elseif($item->status_hadir == 'belum_absen' && $sekarang->gt(($item->penjadwalan->tanggal)))
                                        <button class="btn btn-danger btn-md my-1 w-100" disabled style="opacity: 1">Absen</button>
                                    @else
                                        <button class="btn btn-warning btn-md my-1 w-100" disabled style="opacity: 1">Belum Absen</button>
                                    @endif
                                </td>
                                <td style="width: 250px;">
                                    @if($item->foto_sidang)
                                        <div class="mb-2">
                                            <strong>File Terupload:</strong>
                                            <a href="{{ asset('storage/' . $item->foto_sidang) }}" target="_blank">
                                                {{ \Illuminate\Support\Str::limit(basename($item->foto_sidang),13) }}
                                            </a>
                                        </div>
                                    <button class="btn btn-primary btn-md my-1" 
                                        data-toggle="modal" 
                                        data-target="#modalDokumentasi{{ $item->id_kehadiran }}">
                                        <i class=""></i> Unggah File
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            <!-- Modal untuk upload dokumentasi -->
                            <x-adminlte-modal id="modalDokumentasi{{ $item->id_kehadiran }}" 
                                title="Unggah Dokumentasi Sidang" 
                                theme="blue"
                                icon="fas fa-camera"
                                size="md">
                                <form action="{{ route('presensi.dokumentasi') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id_kehadiran" value="{{ $item->id_kehadiran }}">
                                    <div class="mb-2 input-group">
                                        <div class="custom-file">
                                            <input type="file" class="form-control-file form-control-sm" name="dokumentasi" id="dokumentasi" accept=".jpg,.jpeg,.png,.pdf">
                                            <label class="custom-file-label" for="dokumentasi" data-browse="Cari" >Pilih file</label>
                                        </div>
                                    </div>
                                    <div class="d-flex pt-3 justify-content-end">
                                        <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal"
                                        class="mx-1"/>
                                        <x-adminlte-button type="submit" theme="success" label="Simpan"
                                        class="mx-1"/>
                                    </div>
                                    <x-slot name="footerSlot"></x-slot>
                                </form>
                            </x-adminlte-modal>
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