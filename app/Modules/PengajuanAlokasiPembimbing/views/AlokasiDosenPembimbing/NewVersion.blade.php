@extends('adminlte::page')

@section('title', 'Alokasi Dosen Pembimbing dan Dosen Penguji')

@section('content')
    <form action="{{ route('pengajuanalokasipembimbing.alokasi-pembimbing.submit') }}" method="POST" id="alokasiForm">
        @csrf
        <input type="hidden" id="dataToSend" name="dataToSend">
    </form>

    <h1 class="mb-3">Alokasi Dosen Pembimbing</h1>

    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Alokasi Dosen Pembimbing'],
            ],
        ])
        @endcomponent
    </div>

    <div class="container border mb-2 p-2 m-0 m-100" style="height: 75vh;">
        <div class="row">
            <div class="col border" style="max-width:75%; min-width: 75%;">
                <div class=" table-responsive" style="height: 75vh; overflow-x:hidden;">
                    <table class="table table-striped " id="alokasiTable" {{-- style="width: 1000px" --}}>

                        <thead class="bg-dark sticky-top">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i < 100; $i++)
                                <tr>
                                    <td class="p-0 text-center" style="width: 10px">1</td>
                                    <td class="p-0">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Kota 101
                                                        <br>
                                                        Judul TA ABCDEFG Pelangi ada 12 warna Lorem ipsum dolor sit
                                                        <br>
                                                        Bidang TA
                                                    </th>
                                                    <th scope="col">Alokasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="p-0">
                                                        <div class="container">
                                                            <div class="row" style="height: 250px;">
                                                                <div class="col-sm border-right border-dark">
                                                                    Mahasiswa A
                                                                    <br>
                                                                    Mahasiswa A
                                                                    <br>
                                                                    Mahasiswa A
                                                                    <br>
                                                                    Mahasiswa A
                                                                </div>
                                                                <div class="col-sm">
                                                                    Usulan Pembimbing
                                                                    <hr class="m-0">
                                                                    1. JLM
                                                                    <br>
                                                                    1. JLM
                                                                    <br>
                                                                    1. JLM
                                                                    <br>
                                                                    1. JLM
                                                                    <br>
                                                                    1. JLM
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="p-0">
                                                        <div class="container p-0">
                                                            <div class="row d-flex flex-wrap" style="height: 250px;">
                                                                <div class="col-4 p-0"
                                                                    style="flex: 0 0 33.33%; max-width: 33.33%; height: 50%;">
                                                                    <div class="border p-2 h-100">
                                                                        <span class="badge fw-normal">Pembimbing
                                                                            1</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4 p-0"
                                                                    style="flex: 0 0 33.33%; max-width: 33.33%; height: 50%;">
                                                                    <div class="border p-2 h-100"><span
                                                                            class="badge fw-normal">Pembimbing 2</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4 p-0"
                                                                    style="flex: 0 0 33.33%; max-width: 33.33%; height: 50%;">
                                                                    <div class="border p-2 h-100">NULL</div>
                                                                </div>
                                                                <div class="col-4 p-0"
                                                                    style="flex: 0 0 33.33%; max-width: 33.33%; height: 50%;">
                                                                    <div class="border p-2 h-100"><span
                                                                            class="badge fw-normal">Penguji 1</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4 p-0"
                                                                    style="flex: 0 0 33.33%; max-width: 33.33%; height: 50%;">
                                                                    <div class="border p-2 h-100"><span
                                                                            class="badge fw-normal">Penguji 2</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4 p-0"
                                                                    style="flex: 0 0 33.33%; max-width: 33.33%; height: 50%;">
                                                                    <div class="border p-2 h-100"><span
                                                                            class="badge fw-normal">Penguji 3</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>


                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- punya gwejh -->
            <div class="col border p-0" style="max-width: 25%; min-width: 25%;">
                <div style="height: 75vh; overflow: hidden; display: flex; flex-direction: column;">

                    <!-- HEADER -->
                    <div class="bg-dark text-white p-2">
                        Detail Dosen
                    </div>

                    <!-- DATATABLE -->
                    <div style="flex: 1;">
                        <table id="dosenTable" class="table table-striped table-hover mb-0" style="width: 100%;">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th></th> <!-- Kolom buat tombol expand -->
                                    <th>Nama Dosen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 1; $i < 100; $i++)
                                    <tr>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary toggle-detail">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <span class="badge fw-normal bg-secondary">
                                                Mathar Riqzi {{ $i }}
                                            </span>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('js')
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        // To automatically close the sidebar, yk, we need extra space for this :V
        function adjustSidebar() {
            let toggleNav = $('a.nav-link[data-widget="pushmenu"]');
            if (toggleNav.length) {
                toggleNav.trigger('click');
            }
        }

        $(document).ready(function() {
            adjustSidebar();
            var table = $('#dosenTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            info: true,
            scrollY: 'calc(75vh - 120px)',
            scrollCollapse: true
        });

        $('#dosenTable tbody').on('click', '.toggle-detail', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {

                row.child.hide();
                $(this).find('i').removeClass('fa-minus').addClass('fa-plus');
            } else {
                // Buka child row
                var childContent = `
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th>D3</th>
                                <th>D4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>3/7</td>
                                <td>2/10</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                            </tr>
                        </tbody>
                    </table>
                `;
                row.child(childContent).show();
                $(this).find('i').removeClass('fa-plus').addClass('fa-minus');
            }
        });
        });

        $(window).resize(function() {
            adjustSidebar();
        });

        $('#alokasiTable').DataTable({
            responsive: true,
        });
    </script>
@endsection

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop
