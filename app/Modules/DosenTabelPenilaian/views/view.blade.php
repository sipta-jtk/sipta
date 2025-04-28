@extends('adminlte::page')

@section('title', 'DosenTabelPenilaian')

@section('content_header')
    <h1>Tabel Penilaian & Masukan</h1>
    <div>
    @component('KelolaPenilaianTA.views.components.breadcrumb', [
    'links' => [
    ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
    ['url' => '', 'label' => 'Tabel Penilaian & Masukan']
    ]
    ])
    @endcomponent
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
        <table id="penjadwalanTable" class="table table-striped w-100">
    <thead class="sticky-header">
        <tr class="bg-dark text-white">
            <th style="width: 5%">No</th>
            <th style="width: 10%">Sesi</th>
            <th style="width: 15%">Agenda</th>
            <th style="width: 15%">Tanggal</th>
            <th style="width: 20%">Judul</th>
            <th style="width: 15%">Kota</th>
            <th style="width: 20%">Penilaian</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($penjadwalan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['sesi'] }}</td>
                <td>{{ $item['agenda'] }}</td>
                <td>{{ $item['tanggal'] }}</td>
                <td>{{ $item['judul'] }}</td>
                <td>{{ $item['kota'] }}</td>
                <td>
                    @if ($item['status'] === 'Sudah dinilai')
                        @if ($item['status_penilaian'] === 'dipublikasikan')
                            <a href="{{ route('pengisian.nilai', ['id' => $item['id'], 'kota' => $item['id_kota']]) }}"
                                class="btn btn-primary btn-md">Lihat Nilai</a>
                        @elseif ($item['status_penilaian'] === 'draf')
                            <a href="{{ route('pengisian.nilai', ['id' => $item['id'], 'kota' => $item['id_kota']]) }}"
                                class="btn btn-warning btn-md">Edit Nilai</a>
                            <a href="#" 
                                class="btn btn-warning btn-md"
                                onclick="event.preventDefault(); document.getElementById('publikasikan-form-{{ $item['id_penjadwalan'] }}').submit();">
                                Publikasikan
                            </a>
                            <form id="publikasikan-form-{{ $item['id_penjadwalan'] }}" 
                                  action="{{ route('nilai.publikasikan', ['id_penjadwalan' => $item['id_penjadwalan']]) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endif
                    @else
                        <a href="{{ route('pengisian.nilai', ['id' => $item['id'], 'kota' => $item['id_kota']]) }}"
                            class="btn btn-warning btn-md">Isi Nilai</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
        </div>
    </div>
@stop

@section('css')
    <!-- CDN untuk DataTables CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <!-- CDN untuk jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- CDN untuk DataTables JS -->
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('#penjadwalanTable').DataTable({
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