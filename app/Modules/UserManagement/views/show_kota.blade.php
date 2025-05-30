{{-- bismillahirahmanirahim, semoga dijauhkan dari bug bug yang terkutuk --}}
@extends('adminlte::page')

@section('title', 'Data KoTA')

@section('content_header')
    <h1>Data Kota</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
        'links' => [
        ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
        ['url' => url(), 'label' => 'Data Kelompok KoTA']
        ]])
        @endcomponent
        </div>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        
        <table id="datatable" class="table table-striped">
            <thead class="bg-dark text-white">
                <tr>
                    <th>No</th>
                    <th>Kota</th>
                    <th>Tahun Kota</th>
                    <th>Judul TA</th>
                    <th>Status KoTA</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                @php
                    @endphp
                @foreach ($kota as $kt)
                <tr>
                    <td></td>
                    <td>{{ $kt->nama_kota }}</td>
                    <td>{{$kt->tahun_kota}}</td>
                    <td>
                        @if ($kt->judul_ta)
                            {{ $kt->judul_ta }}
                        @else
                            <span class="text-danger italic">Belum ada judul </span>
                        @endif
                    </td>
                    <td>
                        @if ($kt->status_kota == 'aktif')
                            <span class="badge badge-primary">{{ $kt->status_kota }}</span>
                        @elseif ($kt->status_kota == 'lulus')
                            <span class="badge badge-success">{{ $kt->status_kota }}</span>
                        @elseif ($kt->status_kota == 'bubar')
                            <span class="badge badge-danger">{{ $kt->status_kota }}</span>
                        @else 
                            <span class="badge badge-secondary">{{ $kt->status_kota }}</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-primary btn-xs shadow btn-detail-kota" title="Detail KoTA" data-toggle="modal" data-target="#detailKoTA" data-id="{{ $kt->id_kota }}">                            <i class="fas fa-eye"></i></button>                        
                    </td> 
                    @php
                    @endphp
                </tr>
                @endforeach 
            </tbody>
        </table>                       
            </div>
            </div>

                     <x-adminlte-modal id="detailKoTA" title="Detail KoTA" theme="blue" size='lg' >
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                    <div class="modal-body" id="modal-detail-kota">
                                        <div class="text-center">Memuat...</div>
                                    </div>
                                    </div>
                                </div>
                     </x-adminlte-modal>

@stop

@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            responsive: true,
            columnDefs: [
                { targets: [0, 5], orderable: false } // Kolom No & Aksi tidak bisa disort
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "→",
                    previous: "←"
                },
                zeroRecords: "Data tidak ditemukan",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                infoFiltered: "(disaring dari _MAX_ total entri)"
            },
            drawCallback: function (settings) {
                let api = this.api();
                api.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });
    });

    $('#datatable tbody').on('click', '.btn-detail-kota', function () {
        var id = $(this).data('id');
        console.log('ID Kota:', id);
        $('#modal-detail-kota').html('<div class="text-center">Loading...</div>');

        $.ajax({
            url: '/detail-kota-prodi/' + id,
            method: 'GET',
            success: function(response){
                $('#modal-detail-kota').html(response);
            },
            error: function(){
                $('#modal-detail-kota').html('<div class="text-danger text-center">Gagal mengambil data.</div>');
            }
        });
    });

</script>
@stop

