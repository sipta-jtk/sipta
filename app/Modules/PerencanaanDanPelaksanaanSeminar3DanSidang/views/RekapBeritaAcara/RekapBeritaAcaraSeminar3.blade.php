@extends('adminlte::page')

@section('title', 'Rekapitulasi Berita Acara Seminar 3')

@section('content_header')
    <h1 class="mb-3">Rekapitulasi Berita Acara Seminar 3</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Rekapitulasi Berita Acara Seminar 3']
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
                <table id="RekapBeritaAcaraSeminar3" class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 1%;">NO</th>
                            <th style="width: 8%;">KoTA</th>
                            <th style="width: 8%;">NIM</th>
                            <th style="width: 15%;">Nama Mahasiswa</th>
                            <th style="width: 10%;">Tanggal</th>
                            <th style="width: 8%;">Ruangan</th>
                            <th style="width: 1%;">Sesi</th>
                            <th style="width: 10%;">Status Kehadiran</th>
                            <th style="width: 10%;">Dokumentasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beritaAcaraSeminar3 as $index => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->penjadwalan->kota->nama_kota ?? '-' }}</td>
                                <td>{{ $item->user->mahasiswa->nim ?? '-' }}</td>
                                <td>{{ $item->user->nama ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->translatedFormat ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->nama_ruangan ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->sesi ?? '-' }}</td>
                                <td>
                                    @if($item->status_hadir == 'hadir')
                                    <span class="text-success font-weight-bold">Hadir</span>
                                    @elseif($item->status_hadir == 'tidak_hadir')
                                        <span class="text-danger font-weight-bold">Tidak Hadir</span>
                                    @else
                                        <span class="text-warning font-weight-bold">Belum Absen</span>
                                    @endif
                                </td>
                                <td style="width: 50px;">
                                    @if($item->foto_sidang)
                                        <div class="mb-2">
                                            <strong>File Terupload:</strong>
                                            <a href="{{ asset('storage/' . $item->foto_sidang) }}" target="_blank">
                                                {{ \Illuminate\Support\Str::limit(basename($item->foto_sidang), 13) }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="alert alert-warning p-1" style="font-size: 0.7rem;">
                                            Dokumentasi belum diupload.
                                        </div>
                                    @endif
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
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $('#RekapBeritaAcaraSeminar3').DataTable({
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(difilter dari total _MAX_ data)",
                    "paginate": {
                        "first": "<<",
                        "last": ">>",
                        "next": ">",
                        "previous": "<"
                    }
                }
            });
    </script>
@stop