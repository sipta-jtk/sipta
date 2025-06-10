@extends('adminlte::page')

@section('title', 'List Bimbingan Kelompok TA')

@section('content_header')
<h1>List Bimbingan Kelompok TA</h1>
@stop

@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop


@section('content')
<div class="container-fluid">
    {{-- Filter Responsif --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="form-row align-items-center flex-wrap">
                <div class="col-md-auto col-sm-12 d-flex align-items-center mb-2">
                    <label for="filter-tahun" class="mr-2 mb-0 text-nowrap">Tahun Masuk:</label>
                    <select id="filter-tahun" class="form-control form-control-sm w-auto" style="min-width: 120px;">
                        <option value="" selected>Semua</option>
                        @php
                        $listTahun = $kelompok->flatMap->mahasiswa->pluck('tahun_masuk')->unique()->sort();
                        @endphp
                        @foreach ($listTahun as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-auto col-sm-12 d-flex align-items-center mb-2">
                    <label for="filter-prodi" class="mr-2 mb-0 text-nowrap">Prodi:</label>
                    <select id="filter-prodi" class="form-control form-control-sm w-auto" style="min-width: 120px;">
                        <option value="" selected>Semua</option>
                        @php
                        $listProdi = $kelompok->flatMap->mahasiswa->pluck('prodi.nama_prodi')->unique()->sort();
                        @endphp
                        @foreach ($listProdi as $prodi)
                        <option value="{{ $prodi }}">{{ $prodi }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
        </div>

    </div>

    {{-- Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-striped w-100">
                            <thead class="sticky-header">
                                <tr class="bg-dark text-white text-center">
                                    <th class="align-middle" style="width: 3%">KoTA</th>
                                    <th class="align-middle" style="width: 10%">Tahun Masuk</th>
                                    <th class="align-middle" style="width: 17%">Prodi</th>
                                    <th class="align-middle" style="width: 35%">Judul TA</th>
                                    <th class="align-middle" style="width: 20%">Mahasiswa</th>
                                    <th class="align-middle" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($kelompok as $k)
                                <tr>
                                    <td class="text-center">{{ $k->nama_kota }}</td>
                                    <td class="text-center">
                                        {{-- Ambil semua tahun masuk dan tampilkan sebagai daftar unik --}}
                                        {{ $k->mahasiswa->pluck('tahun_masuk')->unique()->join(', ') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $k->mahasiswa->pluck('prodi.nama_prodi')->unique()->join(', ') }}
                                    </td>

                                    <td>{{ $k->judul_ta }}</td>
                                    <td>
                                        <ul class="mb-0 pl-3">
                                            @foreach ($k->mahasiswa as $mhs)
                                            <li>
                                                {{ $mhs->nim }}
                                                @if ($mhs->user && $mhs->user->nama)
                                                - {{ $mhs->user->nama }}
                                                @else
                                                - <em class="text-muted">nama tidak ditemukan</em>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('Repository.dashboard.kota.mahasiswa', ['id_kota' => $k->id_kota]) }}"
                                            class="btn btn-sm btn-primary">
                                            Lihat Repository
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> {{-- /.table-responsive --}}
                </div>
            </div>
        </div>
    </div>
</div>
@stop


@section('js')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#table').DataTable({
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

        // Reset ke default
        $('#filter-tahun').val('').trigger('change');
        $('#filter-prodi').val('').trigger('change');

        // Filter berdasarkan tahun dan prodi
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var selectedTahun = $('#filter-tahun').val();
            var selectedProdi = $('#filter-prodi').val();
            var tahunText = $(table.row(dataIndex).node()).find('td:eq(1)').text(); // Tahun Masuk (kolom ke-2)
            var prodiText = $(table.row(dataIndex).node()).find('td:eq(2)').text(); // Prodi (kolom ke-3)

            var matchTahun = !selectedTahun || tahunText.includes(selectedTahun);
            var matchProdi = !selectedProdi || prodiText.includes(selectedProdi);

            return matchTahun && matchProdi;
        });

        $('#filter-tahun, #filter-prodi').on('change', function() {
            table.draw();
        });
    });
</script>
@stop