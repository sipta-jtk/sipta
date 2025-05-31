@extends('adminlte::page')

@section('title', 'Pembatalan Jadwal Sidang')

@section('content_header')
<h1>Persetujuan Pembatalan Jadwal Sidang</h1>
<div>
@component('KelolaPenilaianTA.views.components.breadcrumb', [
'links' => [
['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
['url' => '', 'label' => 'Persetujuan Pembatalan Jadwal Sidang'],
]
])
@endcomponent
</div>
@stop

@section('content')
<div class="card mx-3 mt-3">
<div class="card-body">
<table id="seminarTable" class="table table-striped" width="100%">
    <thead class="sticky-header">
        <tr class="bg-dark text-white">
            <th>Kota No</th>
            <th>Judul</th>
            <th>Tanggal Sidang</th>
            <th>Sesi Sidang</th>
            <th>Ruangan</th>
            <th>Dosen Pengaju</th>
            <th>Alasan</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jadwal as $s)
            <tr>
                <td>{{ $s->nama_kota }}</td>
                <td>{{ $s->judul_ta }}</td>
                <td>{{ $s->tanggal }}</td>
                <td>{{ $s->sesi }}</td>
                <td>{{ $s->nama_ruangan }}</td>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->alasan_pembatalan }}</td>
                <td>
                    <form
                        action="{{ route('persetujuan.pembatalan.sidang', ['pembatalan_id' => $s->id_pembatalan, 'status' => 1]) }}"
                        method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-md w-100 my-1">Setuju</button>
                    </form>
                    <form
                        action="{{ route('persetujuan.pembatalan.sidang', ['pembatalan_id' => $s->id_pembatalan, 'status' => 0]) }}"
                        method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-md w-100 my-1">Tolak</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{--
<link rel="stylesheet" href="/css/admin_custom.css"> --}}
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#seminarTable').DataTable({
            language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari total _MAX_ data)",
                    paginate: {
                        first: "<<",  // Tombol pertama
                        last: ">>",   // Tombol terakhir
                        next: ">",    // Tombol berikutnya
                        previous: "<" // Tombol sebelumnya
                    }
                }
        });
    });
</script>
@stop