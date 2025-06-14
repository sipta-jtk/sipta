@extends('adminlte::page')

@section('title', 'Tabel Penilaian & Masukan')

@section('content_header')
    <h1 class="m-0 text-dark">Tabel Penilaian & Masukan</h1>
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
                    <span data-toggle="tooltip" data-placement="top"
                        title="@if(!$item['sudah_dibuka'])
                                Penilaian Belum Dimulai
                            @elseif($item['kunci_penilaian'])
                                Penilaian Terkunci
                            @endif
                        "
                    >
                    <div class="mb-2">
                        @if ($item['sudah_penilaian'] === 'Sudah dinilai')
                            @if($item['status_penilaian'] === 'draf')
                                <button onclick="location.href='{{ route('pengisian.nilai', ['namaFta' => $item['namaFta'], 'idKota' => $item['id_kota']]) }}'"
                                    class="btn btn-warning w-100" {{ !$item['sudah_dibuka'] || $item['kunci_penilaian'] ? 'disabled' : ''  }}>
                                    Edit Nilai
                                </button>
                            @elseif($item['status_penilaian'] === 'dipublikasikan')
                                <button onclick="location.href='{{ route('pengisian.nilai', ['namaFta' => $item['namaFta'], 'idKota' => $item['id_kota']]) }}'"
                                    class="btn btn-primary w-100">
                                    Lihat Nilai
                                </button>
                            @else
                                <button onclick="location.href='{{ route('pengisian.nilai', ['namaFta' => $item['namaFta'], 'idKota' => $item['id_kota']]) }}'"
                                    class="btn btn-primary w-100" {{ !$item['sudah_dibuka'] || $item['kunci_penilaian'] ? 'disabled' : '' }}>
                                    Isi Nilai
                                </button>
                            @endif
                        @else
                            <button onclick="location.href='{{ route('pengisian.nilai', ['namaFta' => $item['namaFta'], 'idKota' => $item['id_kota']]) }}'"
                                class="btn btn-primary w-100" {{ !$item['sudah_dibuka'] || $item['kunci_penilaian'] ? 'disabled' : ''  }}>
                                Isi Nilai
                            </button>
                        @endif
                    </div>
                    <div class="mb-2">
                        @if ($item['sudah_feedback'] === "Sudah diisi")
                            @if($item['status_feedback'] === "draf")
                                <button onclick="location.href='{{ route('pengisian.masukan', ['namaFta' => $item['namaFta'], 'idKota' => $item['id_kota'] ]) }}'"
                                    class="btn btn-warning w-100" {{ !$item['sudah_dibuka'] || $item['kunci_penilaian'] ? 'disabled' : ''  }}>
                                    Edit Masukan
                                </button>
                            @elseif($item['status_feedback'] === "dipublikasikan")
                                <button onclick="location.href='{{ route('pengisian.masukan', ['namaFta' => $item['namaFta'], 'idKota' => $item['id_kota'] ]) }}'"
                                    class="btn btn-primary w-100">
                                    Lihat Masukan
                                </button>
                            @else
                                <button onclick="location.href='{{ route('pengisian.masukan', ['namaFta' => $item['namaFta'], 'idKota' => $item['id_kota'] ]) }}'"
                                    class="btn btn-primary w-100" {{ !$item['sudah_dibuka'] || $item['kunci_penilaian'] ? 'disabled' : ''  }}>
                                    Isi Masukan
                                </button>
                            @endif
                        @else
                            <button onclick="location.href='{{ route('pengisian.masukan', ['namaFta' => $item['namaFta'], 'idKota' => $item['id_kota'] ]) }}'"
                                class="btn btn-primary w-100" {{ !$item['sudah_dibuka'] || $item['kunci_penilaian'] ? 'disabled' : ''  }}>
                                Isi Masukan
                            </button>
                        @endif
                    </div>

                        @if ($item['status_penilaian'] === 'draf' || $item['sudah_penilaian'] === 'Belum dinilai')
                            <form action="{{ route('kelola.penilaian.toggle-publish', ['namaFta' => $item['namaFta'], 'idKota' => $item['id_kota'], 'action' => 'publish']) }}"
                                method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100" {{ !$item['sudah_dibuka'] || $item['kunci_penilaian'] ? 'disabled' : ''  }}>
                                    Publikasikan
                                </button>
                            </form>
                        @elseif ($item['status_penilaian'] === 'dipublikasikan')
                            <form action="{{ route('kelola.penilaian.toggle-publish', ['namaFta' => $item['namaFta'], 'idKota' => $item['id_kota'], 'action' => 'unpublish']) }}"
                                method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" {{ !$item['sudah_dibuka'] || $item['kunci_penilaian'] ? 'disabled' : ''  }}>
                                    Batalkan Publikasi
                                </button>
                            </form>
                        @endif
                    </span>
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
 <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
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

        $(function () {
            $('[data-toggle="tooltip"]').tooltip({ trigger: 'hover focus', delay: { "show": 0, "hide": 100 } });
        });
    </script>
@stop