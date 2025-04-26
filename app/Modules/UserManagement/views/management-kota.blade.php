@extends('adminlte::page')

@section('title', 'Kelompok Tugas Akhir')

@section('content_header')
    <h1 class="mb-3">Daftar Kelompok Tugas Akhir</h1>

    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Daftar Kelompok Tugas Akhir']
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-striped" id="tabel-kota">
            <thead class="sticky-header">
                <tr class="bg-dark text-white">
                    <th>No</th>
                    <th>Nama Kelompok TA</th>
                    <th>Tahun</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelompokList as $index => $kelompok)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $kelompok->nama_kota }}</td>
                    <td>{{ $kelompok->tahun_kota }}</td>
                    <td>
                    @switch($kelompok->status_kota)
                            @case('pra_kota')
                                <span class="badge badge-warning">Pra-KoTA</span>
                                @break
                            @case('aktif')
                                <span class="badge badge-success">Aktif</span>
                                @break
                            @case('lulus')
                                <span class="badge badge-primary">Lulus</span>
                                @break
                            @case('bubar')
                                <span class="badge badge-danger">Bubar</span>
                                @break
                            @default
                                <span class="badge badge-secondary">{{ $kelompok->status_kota }}</span>
                        @endswitch
                    </td>
                    <td class="text-center">
                        <a class="btn btn-primary btn-sm my-1" href="{{ route('detail.kota', ['id' => $kelompok->id_kota]) }}">
                            <i class="mx-1 my-1 fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
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
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $('#tabel-kota').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(difilter dari total _MAX_ data)",
            paginate: {
                first: "<<",
                last: ">>",
                next: ">",
                previous: "<"
            }
        }
    });
</script>
@stop