@extends('adminlte::page')

@section('title', 'Pengajuan Pisah KoTA')

@section('content_header')
    <h1>Pengajuan Pisah KoTA</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body rounded-top-start">
        <table id="pengajuanPisah" class="table table-striped" width="100%">
            <thead class="sticky-header">
                <tr class="bg-dark text-white">
                    <th style="width: 65%">Nama Mahasiswa</th>
                    <th style="width: 20%">KoTA</th>
                    <th style="width: 15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengajuan as $item)
                <tr>
                    <td>{{ $item->mahasiswa->user->nama }}</td>
                    <td>{{ $item->kota->nama_kota }}</td>
                    <td>
                        <div>
                            <a class="btn btn-primary btn-md my-1 w-100" href="{{ route('pengajuan.pisah.kota.show', $item->id_pengajuan) }}" class="btn btn-sm btn-info">
                                Tinjau<i class="mx-1 my-1 fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
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
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script>
    $(document).ready(function () {
        const config = {
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
            },
            columnDefs: [
              {
                targets: [2],
                orderable: false
              }
            ]
        };

        $('#pengajuanPisah').DataTable(config);
        $('#pengajuanPisah2').DataTable(config);
    });
    </script>
@stop