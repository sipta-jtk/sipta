@extends('adminlte::page')

@php
    $isKoordinator = auth()->user()->dosen->role_dosen === 'koordinator_ta';
@endphp

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        @media (max-width: 768px) {
            .tabel-pengajuan {
                max-width: 100%;
                width: 100%;
            }

            .tabel-detail {
                margin-top: 50px;
                max-width: 100%;
                width: 100%;
            }
        }

        @media (min-width: 769px) {
            .tabel-pengajuan {
                max-width: 75%;
                width: 75%;
            }

            .tabel-detail {
                max-width: 25%;
                width: 25%;
            }
        }

        #dosenTable_wrapper .dataTables_filter {
            display: none;
        }

        .card {
            overflow: hidden;
        }

        .table-responsive {
            overflow-y: auto;
            max-height: calc(80vh - 120px); /* Adjusted to fit within the card wrapper */
        }
    </style>
@stop

@section('title', 'Alokasi Dosen Pembimbing')

@section('content')
    <h1 class="mb-3">Alokasi Dosen Penguji</h1>

    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Alokasi Dosen Penguji'],
            ],
        ])
        @endcomponent
    </div>

    <div class="card border mb-2 p-2 m-0 m-100" style="height: 80vh;">
        <div class="row">
            <div class="col tabel-pengajuan">
                <!-- HEADER -->
                <div class="card-header text-center">
                    <h3 class="card-title w-100">Tabel Alokasi Penguji</h3>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2">
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse"
                            data-target="#filterProdiMenu" aria-expanded="false" aria-controls="filterProdiMenu"
                            title="Tampilkan Filter Prodi">
                            <i class="fas fa-filter"></i>
                        </button>
                        
                        <!-- NOTIF KEL REN REN -->
                        @if ($isKoordinator)
                            <button class="btn btn-sm btn-info ml-1" onclick="confirmSendNotification()"
                                title="Kirim notifikasi ke Mahasiswa dan Dosen">
                                <i class="fas fa-bell"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- FILTER PRODI COLLAPSE -->
                <div class="collapse" id="filterProdiMenu">
                    <div class="card mx-2 mb-2">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-2"></i>Filter Prodi
                            </h3>
                        </div>
                        <div class="card-body">
                            <select id="filterProdi" class="form-control form-control-sm w-50"
                                onchange="filterPengajuanByProdi()">
                                <option value="">Semua</option>
                                <option value="D3">D3</option>
                                <option value="D4">D4</option>
                            </select>
                        </div>
                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-secondary btn-sm"
                                onclick="clearFilterProdi()">Reset</button>
                        </div>
                    </div>
                </div>

                {{-- TABEL --}}
                <div class=" table-responsive" style="height: 75vh;">
                    <table class="table table-striped m-0 " id="alokasiTable" style="min-width: 850px; max-width: 75vw;">

                        <thead class="bg-dark sticky-top">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @for ($i = 1; $i < 100; $i++) --}}
                            @foreach ($list_pengajuan as $index => $pengajuan)
                                <tr>
                                    <td class="p-0 text-center" style="width: 10px">{{ $index + 1 }}</td>
                                    <td class="p-0">
                                        <input type="hidden" name="" class="prodi" value="{{ $pengajuan->prodi }}">
                                        <input type="hidden" name="" class="kodeProdi" value="{{ $pengajuan->kode_prodi }}">
                                        <input type="hidden" name="id_pengajuan" value="{{ $pengajuan->id_pengajuan_pembimbing }}">
                                        <table class="m-0 table table-striped table-bordered">
                                            <thead class="font-weight-normal">
                                                <tr>
                                                    <th scope="col" class="font-weight-normal">
                                                        {{ $pengajuan->judul_ta }}<br>
                                                        {{ $pengajuan->bidang }}
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
                                                                        <div style="flex: 0 0 100%; max-width: 100%; height: 100%;"
                                                                            class="col-sm p-0">
                                                                            <div
                                                                                class="border-bottom border-dark m-0 p-1 pl-3">
                                                                                Dafta Penguji Berminat
                                                                            </div>
                                                                            <div class="p-1 pl-3">
                                                                                @foreach ($pengajuan->usulan_dosen as $usulan)
                                                                                    <span
                                                                                        class="badge bg-primary">{{ $loop->iteration }}</span>
                                                                                    <span
                                                                                        class="text-muted">({{ $usulan->id_dosen }})</span><br>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- ini pembimbing -->
                                                                <div style="flex: 0 0 50%; max-width: 50%; height: 100%;"
                                                                    class="col-sm border-right border-dark p-0">
                                                                    <div class="row d-flex m-0 flex-row"
                                                                        style="height: 500px;">
                                                                        @php
                                                                            $alok1 = $pengajuan->alokasi->firstWhere(
                                                                                'urutan_prioritas_terpilih',
                                                                                1,
                                                                            );
                                                                            $bg1 =
                                                                                $alok1?->status_alokasi === 'fix'
                                                                                    ? 'bg-success'
                                                                                    : 'bg-warning';
                                                                        @endphp
                                                                        <div class="col-4 p-0 {{ $bg1 }}"
                                                                            id="bg-{{ $pengajuan->id_pengajuan_pembimbing }}pembimbing1"
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
                                                                                        {{-- Penguji 1 --}}
                                                                                        <input type="text"
                                                                                            value="{{ $pengajuan->alokasi->firstWhere('urutan_prioritas_terpilih', 1)?->id_dosen ?? '' }}"
                                                                                            id="{{ str_replace(' ', '', $pengajuan->nama_kota) }}Pembimbing1"
                                                                                            class="w-100 h-100 bg-transparent border-0 font-weight-bold text-center alokasiInputText"
                                                                                            style="font-size: xx-large"
                                                                                            @if (!$isKoordinator) readonly @endif
                                                                                            onchange="savePembimbing('{{ $pengajuan->id_pengajuan_pembimbing }}', this.value, 1, 'belum_fix', 'pembimbing')">

                                                                                    </div>
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 25%;"
                                                                                        class="col-sm p-0">

                                                                                        <div
                                                                                            class="d-flex justify-content-between">
                                                                                            <button
                                                                                                class="btn btn-sm btn-primary"
                                                                                                onclick="
                                                                                                goToDetailDosen($('#{{ str_replace(' ', '', $pengajuan->nama_kota) }}Pembimbing1').val());
                                                                                                ">
                                                                                                <i
                                                                                                    class="fa fs-fw fa-eye"></i>
                                                                                            </button>
                                                                                            @if ($isKoordinator)
                                                                                                <button
                                                                                                    {{-- class="btn btn-sm btn-success" --}}
                                                                                                    class="btn btn-sm {{ $alok1?->status_alokasi === 'fix' ? 'btn-warning' : 'btn-success' }}"
                                                                                                    onclick="if ($('#{{ str_replace(' ', '', $pengajuan->nama_kota) }}Pembimbing1').val() !== '') fixAlokasi('{{ $pengajuan->id_pengajuan_pembimbing }}', 1)">
                                                                                                    <i
                                                                                                        class="fa fs-fw {{ $alok1?->status_alokasi === 'fix' ? 'fa-undo' : 'fa-check' }}"></i>
                                                                                                </button>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @php
                                                                            $alok2 = $pengajuan->alokasi->firstWhere(
                                                                                'urutan_prioritas_terpilih',
                                                                                2,
                                                                            );
                                                                            $bg2 =
                                                                                $alok2?->status_alokasi === 'fix'
                                                                                    ? 'bg-success'
                                                                                    : 'bg-warning';
                                                                        @endphp
                                                                        <div class="col-4 p-0 {{ $bg2 }}"
                                                                            id="bg-{{ $pengajuan->id_pengajuan_pembimbing }}pembimbing2"
                                                                            style="flex: 0 0 50%; max-width: 50%; height: 50%;">
                                                                            <div class="border border-dark p-0 h-100">
                                                                                <div class="row d-flex flex-wrap m-0 p-2"
                                                                                    style="height: 100%">
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 25%;"
                                                                                        class="col-sm p-0">
                                                                                        <span
                                                                                            class="badge fw-normal">Peguji
                                                                                            2</span>
                                                                                    </div>
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 50%;"
                                                                                        class="col-sm p-0">
                                                                                        {{-- Penguji 2 --}}
                                                                                        <input type="text"
                                                                                            value="{{ $pengajuan->alokasi->firstWhere('urutan_prioritas_terpilih', 2)?->id_dosen ?? '' }}"
                                                                                            id="{{ str_replace(' ', '', $pengajuan->nama_kota) }}Pembimbing2"
                                                                                            class="w-100 h-100 bg-transparent border-0 font-weight-bold text-center alokasiInputText"
                                                                                            style="font-size: xx-large"
                                                                                            @if (!$isKoordinator) readonly @endif
                                                                                            onchange="savePembimbing('{{ $pengajuan->id_pengajuan_pembimbing }}', this.value, 2, 'belum_fix', 'pembimbing')">
                                                                                    </div>
                                                                                    <div style="flex: 0 0 100%; max-width: 100%; height: 25%;"
                                                                                        class="col-sm p-0">

                                                                                        <div
                                                                                            class="d-flex justify-content-between">
                                                                                            <button
                                                                                                class="btn btn-sm btn-primary"
                                                                                                onclick="
                                                                                                goToDetailDosen($('#{{ str_replace(' ', '', $pengajuan->nama_kota) }}Pembimbing2').val());
                                                                                                ">
                                                                                                <i
                                                                                                    class="fa fs-fw fa-eye"></i>
                                                                                            </button>
                                                                                            @if ($isKoordinator)
                                                                                                <button
                                                                                                    class="btn btn-sm {{ $alok2?->status_alokasi === 'fix' ? 'btn-warning' : 'btn-success' }}"
                                                                                                    onclick="if ($('#{{ str_replace(' ', '', $pengajuan->nama_kota) }}Pembimbing2').val() !== '') fixAlokasi('{{ $pengajuan->id_pengajuan_pembimbing }}', 2)">
                                                                                                    <i
                                                                                                        class="fa fs-fw {{ $alok2?->status_alokasi === 'fix' ? 'fa-undo' : 'fa-check' }}"></i>

                                                                                                </button>
                                                                                            @endif
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
                                {{-- @endfor --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- punya gwejh -->
            <div class="col p-0 tabel-detail">
                <div class="d-flex flex-column" style="height: 75vh; overflow: hidden;">

                    <!-- HEADER -->
                    <div class="card-header text-center">
                        <h3 class="card-title w-100">Tabel Detail Dosen</h3>
                    </div>

                    <!-- WRAPPER UNTUK FILTER DAN SEARCH -->
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse"
                            data-target="#filterMenu" aria-expanded="false" aria-controls="filterMenu"
                            title="Tampilkan Filter">
                            <i class="fas fa-filter"></i>
                        </button>
                        <div id="dosenTable_filter" class="dataTables_filter flex-grow-1 m-0">
                            <input type="search" class="form-control form-control-sm" placeholder="Search"
                                aria-controls="dosenTable">
                        </div>
                    </div>

                    <!-- FILTER COLLAPSE -->
                    <div class="collapse" id="filterMenu">
                        <div class="card mx-2 mb-2">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-filter mr-2"></i>Filter Dosen
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="filterOption" value="kuota"
                                        id="filterQuota" onchange="filter()">
                                    <label class="form-check-label" for="filterQuota">
                                        <i class="fas fa-exclamation-triangle mr-1 text-warning"></i> Melebihi Kuota
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="filterOption" value="belum"
                                        id="filterUnassigned" onchange="filter()">
                                    <label class="form-check-label" for="filterUnassigned">
                                        <i class="fas fa-user-times mr-1 text-secondary"></i> Belum Terpilih
                                    </label>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" id="clearFilter" class="btn btn-secondary btn-sm"
                                    onclick="clearFilter()">Hapus Filter</button>
                                <button type="button" id="applyFilter" class="btn btn-primary btn-sm">Terapkan</button>
                            </div>

                            <script>
                                function clearFilter() {
                                    document.querySelectorAll('input[name="filterOption"]').forEach(input => input.checked = false);
                                    filter();
                                }
                            </script>
                        </div>
                    </div>

                    <!-- DATATABLE -->
                    <div class="flex-grow-1 overflow-auto">
                        <table id="dosenTable" class="table table-striped table-hover mb-0 p-3" style="width: 100%;">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Nama Dosen</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Isi tabel ada di JS --}}
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
    @include('PengajuanAlokasiPembimbing.Helper.JS.SweetAlert')

    <script>
        const prodiList = @json(collect($list_prodi)->mapWithKeys(function ($item) {
                return [$item->id_prodi => substr($item->nama_prodi, 0, 2)];
            }));
    </script>

    <script>
        var DetailDosenTable;
        window.mahasiswaNotified = [];
        window.dosenNotified = [];

        function updateDataTable() {
            if (DetailDosenTable) {
                DetailDosenTable.clear();
                DetailDosenTable.rows.add(kuotaDosen);
                DetailDosenTable.draw();
            }
        }

        Object.defineProperty(window, 'kuotaDosen', {
            set: function(value) {
                this._kuotaDosen = value;
                updateDataTable();
            },
            get: function() {
                return this._kuotaDosen;
            }
        });

        // To automatically close the sidebar, yk, we need extra space for this :V
        function adjustSidebar() {
            let toggleNav = $('a.nav-link[data-widget="pushmenu"]');
            if (toggleNav.length) {
                toggleNav.trigger('click');
            }
        }

        function clearFilterProdi() {
            $('#filterProdi').val('');
            filterPengajuanByProdi();
        }

        $(document).ready(function() {
            adjustSidebar();

            $.get("{{ route('pengajuanalokasipembimbing.alokasi-pembimbing.getDetailDosen') }}", function(data) {
                window.kuotaDosen = data;
                updateKuotaDosen();
            });

            // Init dosenTable
            DetailDosenTable = $('#dosenTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                info: true,
                scrollY: 'calc(75vh - 120px)',
                scrollCollapse: true,
                lengthChange: false,
                data: kuotaDosen || [],
                columns: [{
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-between align-items-start" style="gap: 8px;" id="detailDosen-${row.id}">
                                <!-- Kolom Nama Dosen -->
                                <div class="text-break" style="flex: 1; min-width: 0; word-break: break-word; white-space: normal;">
                                    <span class="fw-bold">${row.dosenName}</span>
                                </div>

                                <!-- Kolom Tabel Kuota -->
                                <div style="flex-shrink: 0;">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="text-center">
                                            <tr>
                                                ${Object.entries(prodiList).map(([id, kode]) => `
                                                                                                                                                        <th class="p-1"><span class="badge fw-normal">${kode}</span></th>
                                                                                                                                                    `).join('')}
                                                <th class="p-1"><span class="badge fw-normal"></span></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                ${Object.entries(prodiList).map(([_, kode]) => `
                                                                                                                                                        <td class="p-1">
                                                                                                                                                            <span class="badge fw-normal ${kode} ${(row.mhs?.[kode] > row.kuota?.[kode]) ? 'bg-danger' : ''}">
                                                                                                                                                                ${row.mhs?.[kode] || 0}/${row.kuota?.[kode] || 0}
                                                                                                                                                            </span>
                                                                                                                                                        </td>
                                                                                                                                                    `).join('')}
                                                <td class="p-1 align-middle"><span class="badge fw-normal">MHS</span></td>
                                            </tr>
                                            <tr>
                                                ${Object.entries(prodiList).map(([_, kode]) => `
                                                                                                                                                        <td class="p-1"><span class="badge fw-normal">${row.kelompok?.[kode] || 0}</span></td>
                                                                                                                                                    `).join('')}
                                                <td class="p-1 align-middle"><span class="badge fw-normal">KOTA</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    }
                }, ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari total _MAX_ data)",
                    paginate: {
                        first: "<<",
                        last: ">>",
                        next: ">",
                        previous: "<"
                    }
                },
            });

            setTimeout(() => {
                DetailDosenTable.columns.adjust().responsive.recalc();
            }, 400);

            $('#dosenTable_filter input').on('keyup', function() {
                DetailDosenTable.search(this.value).draw();
            });

            $('#dosenTable_filter').addClass('flex-grow-1 m-0');
            $('#dosenTable_filter input').addClass('form-control form-control-sm');

            $('#applyFilter').on('click', function() {
                let searchTerms = [];

                if ($('#filterQuota').is(':checked')) {
                    searchTerms.push('kuota');
                }
                if ($('#filterUnassigned').is(':checked')) {
                    searchTerms.push('belum');
                }

                const keyword = searchTerms.join(' ');
                table.search(keyword).draw();
            });

            $('#alokasiTable').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
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
        });

        function filterPengajuanByProdi() {
            const selected = $('#filterProdi').val();

            $('#alokasiTable > tbody > tr').each(function() {
                const kodeProdi = $(this).find('input.kodeProdi').val(); // pastikan cari langsung input

                if (selected === '' || kodeProdi === selected) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $(window).resize(function() {
            setTimeout(() => {
                adjustSidebar();
            }, 1000);
        });

        function updateKuotaDosen() {
            for (let i = 0; i < kuotaDosen.length; i++) {
                kuotaDosen[i].mhs.D3 = 0;
                kuotaDosen[i].mhs.D4 = 0;
                kuotaDosen[i].kelompok.D3 = 0;
                kuotaDosen[i].kelompok.D4 = 0;
            }

            $('.alokasiInputText').each(function() {
                let id_dosen = $(this).val();

                let index = kuotaDosen.findIndex(dosen => dosen.id === id_dosen);

                let prodi = $(this).closest('tr').find('.prodi').val();
                let prodiSplit = prodi.split('-');
                let prodiCode = prodiSplit[0];

                if (index !== -1) {
                    let closestInput = $(this).closest('tr').find('.alokasiInputText').not(this);
                    let closestValue = closestInput.val();
                    if (closestValue == id_dosen) {
                        $(closestInput).val('');
                    }
                    if (id_dosen !== '') {
                        let totalMhs = $(this).closest('tr').find('.totalMhs').val();
                        totalMhs = parseInt(totalMhs);

                        kuotaDosen[index].mhs[prodiCode] += totalMhs;
                        kuotaDosen[index].kelompok[prodiCode]++;
                    }
                }

                updateDataTable();
            });
        }

        function goToDetailDosen(id) {
            let dosen = kuotaDosen.find(d => d.id === id);
            if (dosen) {
                let searchInput = $('#dosenTable_filter input');
                searchInput.val(dosen.dosenName);
                DetailDosenTable.search(dosen.dosenName).draw();
            }
        }

        function filter() {
            if ($('#filterQuota').is(':checked')) {
                for (let i = 0; i < kuotaDosen.length; i++) {
                    var save = false;
                    for (const key in kuotaDosen[i].mhs) {
                        if (kuotaDosen[i].mhs[key] > kuotaDosen[i].kuota[key]) {
                            save = true;
                            break;
                        }
                    }
                    if (save) {
                        $(`#detailDosen-${kuotaDosen[i].id}`).removeClass('d-none');
                        $(`#detailDosen-${kuotaDosen[i].id}`).addClass('d-flex');
                    } else {
                        $(`#detailDosen-${kuotaDosen[i].id}`).addClass('d-none');
                        $(`#detailDosen-${kuotaDosen[i].id}`).removeClass('d-flex');
                    }
                }
            } else if ($('#filterUnassigned').is(':checked')) {
                for (let i = 0; i < kuotaDosen.length; i++) {
                    if (Object.values(kuotaDosen[i].mhs).some(value => value > 0)) {
                        $(`#detailDosen-${kuotaDosen[i].id}`).addClass('d-none');
                        $(`#detailDosen-${kuotaDosen[i].id}`).removeClass('d-flex');

                    } else {
                        $(`#detailDosen-${kuotaDosen[i].id}`).removeClass('d-none');
                        $(`#detailDosen-${kuotaDosen[i].id}`).addClass('d-flex');
                    }
                }
            } else {
                for (let i = 0; i < kuotaDosen.length; i++) {
                    $(`#detailDosen-${kuotaDosen[i].id}`).removeClass('d-none');
                    $(`#detailDosen-${kuotaDosen[i].id}`).addClass('d-flex');
                }
            }
        }

        function savePembimbing(id_pengajuan, kode_dosen, urutan, status, tipe) {
            let dosen = kuotaDosen.find(d => d.id === kode_dosen);
            if (dosen) {
                $.ajax({
                    url: "{{ route('pengajuanalokasipembimbing.alokasi-pembimbing.updateAlokasi') }}",
                    type: "POST",
                    data: {
                        id_pengajuan_pembimbing: id_pengajuan,
                        nip: dosen.nip,
                        urutan_prioritas_terpilih: urutan,
                        status_alokasi: status,
                        tipe_alokasi: tipe,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response);
                        toast('success', 'Berhasil', 'Alokasi berhasil diperbarui');
                        let pembimbing = $("#bg-" + id_pengajuan + "pembimbing" + urutan);
                        pembimbing.removeClass("bg-success");
                        pembimbing.addClass("bg-warning");
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            } else if (kode_dosen == '') {
                $.ajax({
                    url: "{{ route('pengajuanalokasipembimbing.alokasi-pembimbing.deleteAlokasi') }}",
                    type: "POST",
                    data: {
                        id_pengajuan_pembimbing: id_pengajuan,
                        urutan_prioritas_terpilih: urutan,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response);
                        toast('success', 'Berhasil', 'Alokasi berhasil diperbarui');
                        let pembimbing = $("#bg-" + id_pengajuan + "pembimbing" + urutan);
                        pembimbing.removeClass("bg-success");
                        pembimbing.addClass("bg-warning");
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }
        }

        function fixAlokasi(id_pengajuan, urutan) {

            var caller = event.target.closest('button');

            $.ajax({
                url: "{{ route('pengajuanalokasipembimbing.alokasi-pembimbing.fixAlokasi') }}",
                type: "POST",
                data: {
                    id_pengajuan_pembimbing: id_pengajuan,
                    urutan_prioritas_terpilih: urutan,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                    var pembimbing = $("#bg-" + id_pengajuan + "pembimbing" + urutan);
                    if (pembimbing.hasClass("bg-warning")) {
                        pembimbing.removeClass("bg-warning");
                        pembimbing.addClass("bg-success");
                        $(caller).removeClass("btn-success");
                        $(caller).addClass("btn-warning");
                        $(caller).find('i').removeClass('fa-check').addClass('fa-undo');
                    } else {
                        pembimbing.removeClass("bg-success");
                        pembimbing.addClass("bg-warning");

                        $(caller).removeClass("btn-warning");
                        $(caller).addClass("btn-success");
                        $(caller).find('i').removeClass('fa-undo');
                        $(caller).find('i').addClass('fa-check');
                    }


                    toast('success', 'Berhasil', 'Alokasi berhasil diperbarui');
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        // onchange on .alokasiInputText
        $('.alokasiInputText').on('change', function() {
            updateKuotaDosen();
        });

        // REN REN
        function confirmSendNotification() {
            FireSweetAlert(
                'question',
                'Kirim Notifikasi?',
                'Apakah Anda yakin ingin mengirim notifikasi ke mahasiswa dan dosen?',
                'Ya, Kirim',
                'Batal',
                '#28a745',
                '#6c757d',
                true,
                true,
                function(confirmed) {
                    if (confirmed) {
                        window.mahasiswaNotified = [];
                        window.dosenNotified = [];

                        $('#alokasiTable > tbody > tr').each(function () {
                            const pengajuanRow = $(this);
                            const idPengajuan = pengajuanRow.find('input[name="id_pengajuan"]').val();

                            ['1', '2'].forEach(function(urutan) {
                                const pembimbingInput = pengajuanRow.find(`#bg-${idPengajuan}pembimbing${urutan}`);
                                if (pembimbingInput.hasClass('bg-success')) {
                                    const inputPembimbing = pengajuanRow.find('td input.alokasiInputText').eq(urutan - 1);
                                    const idDosen = inputPembimbing.val();

                                    if (idDosen && !window.dosenNotified.includes(idDosen)) {
                                        window.dosenNotified.push(idDosen);
                                    }

                                    pengajuanRow.find('.badge.font-weight-normal.p-0').each(function () {
                                        const nim = $(this).text().trim();
                                        const nama = $(this).prev().text().trim();
                                        if (nim && !window.mahasiswaNotified.some(m => m.nim === nim)) {
                                            window.mahasiswaNotified.push({ nama, nim });
                                        }
                                    });
                                }
                            });
                        });

                        console.table(window.mahasiswaNotified);
                        console.table(window.dosenNotified);

                        toast('success', 'Terkirim', `Notifikasi akan dikirim ke ${window.mahasiswaNotified.length} mahasiswa dan ${window.dosenNotified.length} dosen`);
                    }
                }
            );
        }
    </script>
@endsection
