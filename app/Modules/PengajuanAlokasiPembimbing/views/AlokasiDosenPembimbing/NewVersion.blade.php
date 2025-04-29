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

    <div class="card border mb-2 p-2 m-0 m-100" style="height: 75vh;">
        <div class="row">
            <div class="col" style="max-width:75%; min-width: 75%;">
                <div class=" table-responsive" style="height: 75vh;">
                    <table class="table table-striped m-0 " id="alokasiTable" style="min-width: 850px; max-width: 75vw;">

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
                                        <table class="m-0 table table-striped table-bordered">
                                            <thead class="font-weight-normal">
                                                <tr>
                                                    <th scope="col" class="font-weight-normal">Kota 101
                                                        <br>
                                                        Judul TA ABCDEFG Pelangi ada 12 warna Lorem ipsum dolor sit
                                                        <br>
                                                        Bidang TA
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="p-0">
                                                        <div>
                                                            <div class="row d-flex flex-wrap m-0" style="height: 250px;">
                                                                <div style="flex: 0 0 50%; max-width: 50%; height: 100%;"
                                                                    class="col-sm border-right border-dark p-0">

                                                                    <div class="row d-flex flex-wrap m-0"
                                                                        style="height: 250px;">
                                                                        <div style="flex: 0 0 50%; max-width: 50%; height: 100%;"
                                                                            class="col-sm border-right border-dark p-0">
                                                                            <div
                                                                                class="border-bottom border-dark m-0 p-1 pl-3">
                                                                                Anggota Kelompok
                                                                            </div>
                                                                            <div class="p-1 pl-3">
                                                                                @for ($MH = 1; $MH <= 4; $MH++)
                                                                                    Mahasiswa A
                                                                                    <br>
                                                                                    <span
                                                                                        class="badge font-weight-normal p-0">22150404</span>
                                                                                    <br>
                                                                                @endfor
                                                                            </div>
                                                                        </div>
                                                                        <div style="flex: 0 0 50%; max-width: 50%; height: 100%;"
                                                                            class="col-sm p-0">
                                                                            <div
                                                                                class="border-bottom border-dark m-0 p-1 pl-3">
                                                                                Usulan Pembimbing
                                                                            </div>
                                                                            <div class="p-1 pl-3">
                                                                                @for ($pengujiList = 1; $pengujiList <= 4; $pengujiList++)
                                                                                    <span
                                                                                        class="badge bg-primary">{{ $pengujiList }}</span>
                                                                                    JLM
                                                                                    <br>
                                                                                @endfor
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div style="flex: 0 0 50%; max-width: 50%; height: 100%;"
                                                                    class="col-sm border-right border-dark p-0">
                                                                    <div class="row d-flex m-0 flex-row"
                                                                        style="height: 250px;">
                                                                        <div class="col-4 p-0 bg-warning"
                                                                            style="flex: 0 0 50%; max-width: 50%; height: 50%;">
                                                                            <div class="border border-dark p-0 h-100">
                                                                                <div class="row d-flex flex-wrap m-0 p-2"
                                                                                    style="height: 100%">
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 25%;"
                                                                                        class="col-sm p-0">
                                                                                        <span
                                                                                            class="badge fw-normal">Pembimbing
                                                                                            1</span>
                                                                                    </div>
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 50%;"
                                                                                        class="col-sm p-0">
                                                                                        <input type="text" name=""
                                                                                            id=""
                                                                                            class="w-100 h-100 bg-transparent border-0 font-weight-bold text-center"
                                                                                            style="font-size: xx-large"
                                                                                            value="JO">
                                                                                    </div>
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 25%;"
                                                                                        class="col-sm p-0">

                                                                                        <div
                                                                                            class="d-flex justify-content-between">
                                                                                            <button
                                                                                                class="btn btn-sm btn-primary">
                                                                                                <i
                                                                                                    class="fa fs-fw fa-eye"></i>
                                                                                            </button>
                                                                                            <button
                                                                                                class="btn btn-sm btn-success">
                                                                                                <i
                                                                                                    class="fa fs-fw fa-check"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4 p-0 bg-warning"
                                                                            style="flex: 0 0 50%; max-width: 50%; height: 50%;">
                                                                            <div class="border border-dark p-0 h-100">
                                                                                <div class="row d-flex flex-wrap m-0 p-2"
                                                                                    style="height: 100%">
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 25%;"
                                                                                        class="col-sm p-0">
                                                                                        <span
                                                                                            class="badge fw-normal">Pembimbing
                                                                                            2</span>
                                                                                    </div>
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 50%;"
                                                                                        class="col-sm p-0">
                                                                                        <input type="text" name=""
                                                                                            id=""
                                                                                            class="w-100 h-100 bg-transparent border-0 font-weight-bold text-center"
                                                                                            style="font-size: xx-large"
                                                                                            value="JO">
                                                                                    </div>
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 25%;"
                                                                                        class="col-sm p-0">

                                                                                        <div
                                                                                            class="d-flex justify-content-between">
                                                                                            <button
                                                                                                class="btn btn-sm btn-primary">
                                                                                                <i
                                                                                                    class="fa fs-fw fa-eye"></i>
                                                                                            </button>
                                                                                            <button
                                                                                                class="btn btn-sm btn-success">
                                                                                                <i
                                                                                                    class="fa fs-fw fa-check"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4 p-0 bg-warning"
                                                                            style="flex: 0 0 50%; max-width: 50%; height: 50%;">
                                                                            <div class="border border-dark p-0 h-100">
                                                                                <div class="row d-flex flex-wrap m-0 p-2"
                                                                                    style="height: 100%">
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 25%;"
                                                                                        class="col-sm p-0">
                                                                                        <span
                                                                                            class="badge fw-normal">Penguji
                                                                                            1</span>
                                                                                    </div>
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 50%;"
                                                                                        class="col-sm p-0">
                                                                                        <input type="text"
                                                                                            name="" id=""
                                                                                            class="w-100 h-100 bg-transparent border-0 font-weight-bold text-center"
                                                                                            style="font-size: xx-large"
                                                                                            value="JO">
                                                                                    </div>
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 25%;"
                                                                                        class="col-sm p-0">

                                                                                        <div
                                                                                            class="d-flex justify-content-between">
                                                                                            <button
                                                                                                class="btn btn-sm btn-primary">
                                                                                                <i
                                                                                                    class="fa fs-fw fa-eye"></i>
                                                                                            </button>
                                                                                            <button
                                                                                                class="btn btn-sm btn-success">
                                                                                                <i
                                                                                                    class="fa fs-fw fa-check"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4 p-0 bg-warning"
                                                                            style="flex: 0 0 50%; max-width: 50%; height: 50%;">
                                                                            <div class="border border-dark p-0 h-100">
                                                                                <div class="row d-flex flex-wrap m-0 p-2"
                                                                                    style="height: 100%">
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 25%;"
                                                                                        class="col-sm p-0">
                                                                                        <span
                                                                                            class="badge fw-normal">Penguji
                                                                                            2</span>
                                                                                    </div>
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 50%;"
                                                                                        class="col-sm p-0">
                                                                                        <input type="text"
                                                                                            name="" id=""
                                                                                            class="w-100 h-100 bg-transparent border-0 font-weight-bold text-center"
                                                                                            style="font-size: xx-large"
                                                                                            value="JO">
                                                                                    </div>
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 25%;"
                                                                                        class="col-sm p-0">

                                                                                        <div
                                                                                            class="d-flex justify-content-between">
                                                                                            <button
                                                                                                class="btn btn-sm btn-primary">
                                                                                                <i
                                                                                                    class="fa fs-fw fa-eye"></i>
                                                                                            </button>
                                                                                            <button
                                                                                                class="btn btn-sm btn-success">
                                                                                                <i
                                                                                                    class="fa fs-fw fa-check"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
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
            <div class="col p-0" style="max-width: 25%; min-width: 25%;">
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
                                    <th>Nama Dosen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 1; $i < 100; $i++)
                                    <tr>
                                        <td style="position: relative;">
                                            <div class="d-flex align-items-start justify-content-between"
                                                style="gap: 8px;">
                                                <span class="badge fw-normal" style="flex-shrink: 0;">
                                                    niggalius Rahtam Ziqri
                                                    {{ $i }}
                                                </span>
                                                <div style="flex-grow: 1;">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th class="p-1">
                                                                    <span class="badge fw-normal">D3</span>
                                                                </th>
                                                                <th class="p-1">
                                                                    <span class="badge fw-normal">D4</span>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-center">
                                                            <tr>
                                                                <td class="p-1">
                                                                    <span class="badge fw-normal">3/7</span>
                                                                </td>
                                                                <td class="p-1">
                                                                    <span class="badge fw-normal">2/10</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-1">
                                                                    <span class="badge fw-normal">1</span>
                                                                </td>
                                                                <td class="p-1">
                                                                    <span class="badge fw-normal">2</span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
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
                scrollCollapse: true,
                lengthChange: false
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
