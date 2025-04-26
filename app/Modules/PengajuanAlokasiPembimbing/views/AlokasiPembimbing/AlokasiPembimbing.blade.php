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
            ['url' => '', 'label' => 'Alokasi Dosen Pembimbing']
        ]
    ])
    @endcomponent
</div>

<datalist id="dosenList">
    @foreach ($dosenList as $dosen)
        <option value="{{ $dosen['id_dosen'] }}">{{ $dosen['nama'] }}</option>
    @endforeach
</datalist>

<div class="d-flex justify-content-between mb-2">
    <div id="dataTableControls"></div>
    <div id="searchBox"></div>
</div>
<div class="card mb-4">
    <div class="card-header text-center">
        <h3 class="card-title w-100 mb-0">Tabel Alokasi Pembimbing dan Penguji</h3>
    </div>
    <div class="table-container">
        <table id="alokasiTable" class="table text-center" style="min-width: 1400px;">
            <thead class="sticky-header">
                <tr class="bg-dark text-white">
                    <th rowspan="2" class="sticky-column no-sort" style="width: 5%; position: sticky; left: 0;">No</th>
                    <th rowspan="2" class="sticky-column no-sort" style="width: 15%; position: sticky; left: 5%;">
                        Kelompok</th>
                    <th rowspan="2" class="no-sort" style="width: 20%;">Anggota</th>
                    <th rowspan="2" class="no-sort" style="width: 15%;">Bidang</th>
                    <th rowspan="2" class="no-sort" style="width: 20%;">Judul/Topik</th>
                    <th colspan="5" class="no-sort" style="width: 20%;">Usulan Pembimbing</th>
                    <th colspan="4" class="no-sort" style="width: 30%;">Pembimbing</th>
                    <th colspan="3" class="no-sort" style="width: 20%;">Penguji</th>
                    <th rowspan="2" class="no-sort" style="width: 15%;">Catatan</th>
                </tr>
                <tr class="bg-secondary text-white">
                    <th class="no-sort" style="width: 4%">1</th>
                    <th class="no-sort" style="width: 4%">2</th>
                    <th class="no-sort" style="width: 4%">3</th>
                    <th class="no-sort" style="width: 4%">4</th>
                    <th class="no-sort" style="width: 4%">5</th>
                    <th class="no-sort" style="width: 10%">1</th>
                    <th class="no-sort" style="width: 15%">Detail</th>
                    <th class="no-sort" style="width: 10%">2</th>
                    <th class="no-sort" style="width: 15%">Detail</th>
                    <th class="no-sort" style="width: 10%">1</th>
                    <th class="no-sort" style="width: 10%">2</th>
                    <th class="no-sort" style="width: 10%">3</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_pengajuan as $index => $row)
                            <tr class="bg-light" data-id="{{ $row['id_pengajuan_pembimbing'] }}">
                                <td class="align-middle sticky-column" style="position: sticky; left: 0; background: white;">
                                    {{ $index + 1 }}
                                </td>
                                <td class="align-middle sticky-column" style="position: sticky; left: 5%; background: white;">
                                    {{ $row['nama_kota'] }}
                                </td>
                                <td class="align-middle">
                                    <ul class="m-0 p-0" style="list-style-type: none;">
                                        @foreach ($row['mahasiswa'] as $anggota)
                                            <li>{{ $anggota['nama'] }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="align-middle">{{ $row['bidang'] }}</td>
                                <td class="align-middle">{{ $row['judul_ta'] }}</td>
                                @for ($i = 1; $i <= 5; $i++)
                                                <td class="align-middle">
                                                    @php
                                                        $found = false;
                                                    @endphp
                                                    @foreach ($row['usulan_dosen'] as $dosen)
                                                                @if ($dosen['urutan_prioritas'] == $i)
                                                                            @php
                                                                                $found = true;
                                                                            @endphp
                                                                            {{ $dosen['id_dosen'] }}
                                                                @endif
                                                    @endforeach
                                                    @if (!$found)
                                                        -
                                                    @endif
                                                </td>
                                @endfor

                                <td class="align-middle status-cell" data-status="belum_fix">
                                    <input type="text" class="form-control text-center pembimbing mb-2" data-index="{{ $index }}"
                                        name="pembimbing1{{ $row['nama_kota'] }}" list="dosenList"
                                        value="{{ $row['preferensi_dosen'][0]['id_dosen'] ?? '' }}"
                                        onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'pembimbing1', this.value)">
                                    <label>Status:</label>
                                    <select class="form-control status-dropdown w-100" data-detail-container="#detailPembimbing1"
                                        onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'status_pembimbing1', this.value)">
                                        <option value="belum_fix" {{ isset($row['preferensi_dosen'][0]) ? '' : 'selected' }}>
                                            Belum Fix</option>
                                        <option value="fix" {{ isset($row['preferensi_dosen'][0]) ? 'selected' : '' }}>Fix
                                        </option>
                                    </select>
                                </td>
                                <td class="align-middle text-left">
                                    <div class="detail-content" id="detailPembimbing1">
                                        <div><strong>Nama:</strong> {{ $row['detailPembimbing1']['nama'] ?? '-' }}</div>
                                        <div><strong>KoTA:</strong> P1:
                                            {{ $row['detailPembimbing1']['pembimbing1_KoTA'] ?? '0' }} |
                                            P2: {{ $row['detailPembimbing1']['pembimbing2_KoTA'] ?? '0' }} |
                                            Total: {{ $row['detailPembimbing1']['jumlah_KoTA'] ?? '0' }}
                                        </div>
                                        <div><strong>Mhs:</strong> P1: {{ $row['detailPembimbing1']['pembimbing1_Mhs'] ?? '0' }}
                                            |
                                            P2: {{ $row['detailPembimbing1']['pembimbing2_Mhs'] ?? '0' }} |
                                            Total: {{ $row['detailPembimbing1']['jumlahMahasiswa'] ?? '0' }}</div>
                                        <div><strong>Kuota:</strong> {{ $row['detailPembimbing1']['kuota'] ?? '0' }}</div>
                                        <div><strong>Kelebihan:</strong>
                                            @if (($row['detailPembimbing1']['jumlahMahasiswa'] ?? 0) > ($row['detailPembimbing1']['kuota'] ?? 0))
                                                <span style="color: red;">
                                                    {{ ($row['detailPembimbing1']['jumlahMahasiswa'] ?? 0) - ($row['detailPembimbing1']['kuota'] ?? 0) }}
                                                    (Overload)
                                                </span>
                                            @else
                                                <span style="color: green;">Aman</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle status-cell" data-status="belum_fix">
                                    <input type="text" class="form-control text-center pembimbing mb-2" data-index="{{ $index }}"
                                        name="pembimbing2{{ $row['nama_kota'] }}" list="dosenList"
                                        value="{{ $row['preferensi_dosen'][1]['id_dosen'] ?? '' }}"
                                        onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'pembimbing2', this.value)">
                                    <label>Status:</label>
                                    <select class="form-control status-dropdown w-100" data-detail-container="#detailPembimbing2"
                                        onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'status_pembimbing2', this.value)">
                                        <option value="belum_fix" {{ isset($row['preferensi_dosen'][1]) ? '' : 'selected' }}>
                                            Belum Fix</option>
                                        <option value="fix" {{ isset($row['preferensi_dosen'][1]) ? 'selected' : '' }}>Fix
                                        </option>
                                    </select>
                                </td>
                                <td class="align-middle text-left">
                                    <div class="detail-content" id="detailPembimbing2">
                                        <div><strong>Nama:</strong> {{ $row['detailPembimbing2']['nama'] ?? '-' }}</div>
                                        <div><strong>KoTA:</strong> P1:
                                            {{ $row['detailPembimbing2']['pembimbing1_KoTA'] ?? '0' }} |
                                            P2: {{ $row['detailPembimbing2']['pembimbing2_KoTA'] ?? '0' }} |
                                            Total: {{ $row['detailPembimbing2']['jumlah_KoTA'] ?? '0' }}
                                        </div>
                                        <div><strong>Mhs:</strong> P1:
                                            {{ $row['detailPembimbing2']['pembimbing1_Mhs'] ?? '0' }} |
                                            P2: {{ $row['detailPembimbing2']['pembimbing2_Mhs'] ?? '0' }} |
                                            Total: {{ $row['detailPembimbing2']['jumlahMahasiswa'] ?? '0' }}
                                        </div>
                                        <div><strong>Kuota:</strong> {{ $row['detailPembimbing2']['kuota'] ?? '0' }}</div>
                                        <div><strong>Kelebihan:</strong>
                                            @if (($row['detailPembimbing2']['jumlahMahasiswa'] ?? 0) > ($row['detailPembimbing2']['kuota'] ?? 0))
                                                <span style="color: red;">
                                                    {{ ($row['detailPembimbing2']['jumlahMahasiswa'] ?? 0) - ($row['detailPembimbing2']['kuota'] ?? 0) }}
                                                    (Overload)
                                                </span>
                                            @else
                                                <span style="color: green;">Aman</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <input type="text" class="form-control text-center penguji" data-index="{{ $index }}"
                                        name="penguji1{{ $row['nama_kota'] }}" list="dosenList"
                                        onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'penguji1', this.value)">
                                </td>
                                <td class="align-middle">
                                    <input type="text" class="form-control text-center penguji" data-index="{{ $index }}"
                                        name="penguji2{{ $row['nama_kota'] }}" list="dosenList"
                                        onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'penguji2', this.value)">
                                </td>
                                <td class="align-middle">
                                    <input type="text" class="form-control text-center penguji" data-index="{{ $index }}"
                                        name="penguji3{{ $row['nama_kota'] }}" list="dosenList"
                                        onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'penguji3', this.value)">
                                </td>
                                <td class="align-middle" style="min-width: 150px;">
                                    <textarea class="form-control text-left auto-expand catatan-input"
                                        data-id="{{ $row['id_pengajuan_pembimbing'] }}" name="catatan_{{ $index }}" rows="1"
                                        style="overflow: hidden; resize: none;"
                                        oninput="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'catatan', this.value)">{{ trim($row['catatan'] ?? '') }}</textarea>
                                </td>
                            </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-end mt-3">
    <button type="button" class="btn btn-secondary mr-2" id="saveDraftBtn">Simpan</button>
    <button type="button" class="btn btn-primary" id="openConfirmModal" onclick="submitForm()">Finalisasi</button>
</div>

<div class="card mt-5">
    <div class="card-header text-center">
        <h3 class="card-title w-100 mb-0">Daftar Dosen Pembimbing</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table id="dosenTable" class="table text-center" style="min-width: 600px;">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">ID Dosen</th>
                        <th style="width: 40%;">Nama</th>
                        <th style="width: 40%;">KBK</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dosenList as $index => $dosen)
                        <tr class="bg-light" data-id="{{ $row['id_pengajuan_pembimbing'] }}">
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $dosen['id_dosen'] }}</td>
                            <td class="align-middle">{{ $dosen['nama'] }}</td>
                            <td class="align-middle text-left">
                                @foreach ($dosen['ketertarikan_bidang'] as $kbk)
                                    {{ $kbk['bidang'] }}
                                    <br>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* --- Table Container --- */
    .table-container {
        max-height: 500px;
        overflow: auto;
        border: 1px solid #ddd;
        width: 100%;
        position: relative;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* --- Table Structure --- */
    table {
        width: 100%;
        min-width: 1400px;
        border-collapse: collapse;
        font-size: 16px;
    }

    th,
    td {
        border: 1px solid #ccc !important;
        padding: 10px;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* --- Sticky Header & Columns --- */
    thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #343a40;
        color: white;
        text-align: center;
        vertical-align: middle;
        font-weight: bold;
    }

    th.sticky-column {
        position: sticky;
        background: #343a40;
        color: white;
        z-index: 11;
    }

    th.sticky-column:first-child {
        left: 0;
    }

    th.sticky-column:nth-child(2) {
        left: 5%;
    }

    /* --- Table Body --- */
    tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    tbody tr:hover {
        background-color: #e2e6ea;
        transition: background-color 0.2s ease;
    }

    /* --- Sorting Indicators --- */
    table.dataTable thead th.no-sort {
        background-image: none !important;
    }

    table.dataTable thead th.no-sort.sorting::before,
    table.dataTable thead th.no-sort.sorting::after,
    table.dataTable thead th.no-sort.sorting_asc::before,
    table.dataTable thead th.no-sort.sorting_asc::after,
    table.dataTable thead th.no-sort.sorting_desc::before,
    table.dataTable thead th.no-sort.sorting_desc::after {
        display: none !important;
    }

    /* --- Status Cells --- */
    .status-cell {
        min-width: 120px;
        padding: 5px;
        transition: all 0.3s ease;
        font-weight: 500;
        border-radius: 3px;
    }

    .status-cell[data-status="fix"] {
        background-color: #28a745 !important;
        color: white !important;
    }

    .status-cell[data-status="belum_fix"] {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }

    .status-dropdown {
        width: 100%;
        text-align: center;
        padding: 5px;
        font-weight: bold;
        min-width: 120px;
        border-radius: 5px;
        border: 1px solid #ccc;
        cursor: pointer;
    }

    /* --- Form Elements --- */
    .auto-expand {
        width: 100%;
        min-height: 35px;
        max-height: 150px;
        resize: none;
        overflow-y: hidden;
        border: 1px solid #ccc;
        padding: 8px;
        font-size: 16px;
        transition: height 0.2s ease;
        border-radius: 4px;
    }

    /* --- Buttons --- */
    button {
        transition: all 0.2s ease;
    }

    button:hover {
        opacity: 0.85;
        transform: translateY(-1px);
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-dark {
        background-color: #343a40;
        border-color: #343a40;
    }

    /* --- Specific Column Widths --- */
    th:nth-child(15),
    th:nth-child(16),
    th:nth-child(17),
    td:nth-child(15),
    td:nth-child(16),
    td:nth-child(17) {
        min-width: 70px !important;
        max-width: 100px;
    }

    /* --- Responsive Considerations --- */
    @media (max-width: 1600px) {
        .table-container {
            max-height: 400px;
        }
    }
</style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    var DataToSend = [];
    const dosenCache = {};
    const localStorageKey = "alokasiPembimbingDraft";
    const prefixUrl = "{{ '/' . env('PREFIX_URL') }}";

    // ======================= Utility Functions ========================

    function saveData(id_pengajuan_pembimbing, key, value) {
        let found = DataToSend.find(item => item.id_pengajuan_pembimbing == id_pengajuan_pembimbing);
        if (!found) {
            found = { id_pengajuan_pembimbing };
            DataToSend.push(found);
        }
        found[key] = value;
    }

    function fetchDosenDetailDebounced(nip, detailContainer) {
        clearTimeout(fetchDosenDetailDebounced.timer);
        fetchDosenDetailDebounced.timer = setTimeout(() => {
            fetchDosenDetail(nip, detailContainer);
        }, 300);
    }

    function fetchDosenDetail(nip, detailContainer) {
        if (!nip) {
            detailContainer.html(`<div><strong>Nama:</strong> -</div>...`);
            return;
        }

        if (dosenCache[nip]) {
            renderDosenDetail(dosenCache[nip], detailContainer);
            return;
        }

        $.ajax({
            url: `${prefixUrl}/PengajuanAlokasiPembimbing/alokasi-pembimbing/getDetailDosen/${nip}`,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    detailContainer.html(`<div style="color: red;">${response.error}</div>`);
                    return;
                }
                dosenCache[nip] = response;
                renderDosenDetail(response, detailContainer);
            },
            error: function () {
                detailContainer.html(`<div style="color: red;">Gagal mengambil data dosen.</div>`);
            }
        });
    }

    function renderDosenDetail(data, container) {
        container.html(`
        <div><strong>Nama:</strong> ${data.nama}</div>
        <div><strong>KoTA:</strong> P1: ${data.pembimbing1_KoTA} | P2: ${data.pembimbing2_KoTA} | Total: ${data.jumlah_KoTA}</div>
        <div><strong>Mhs:</strong> P1: ${data.pembimbing1_Mhs} | P2: ${data.pembimbing2_Mhs} | Total: ${data.jumlahMahasiswa}</div>
        <div><strong>Kuota:</strong> ${data.kuota}</div>
        <div><strong>Kelebihan:</strong> 
            <span style="color: ${data.kelebihan.includes('Overload') ? 'red' : 'green'};">${data.kelebihan}</span>
        </div>
    `);
    }

    function saveDraft() {
        let draftData = {};
        $("input, select, textarea").each(function () {
            draftData[$(this).attr("name")] = $(this).val();
        });
        localStorage.setItem(localStorageKey, JSON.stringify(draftData));
    }

    function loadDraft() {
        let savedData = JSON.parse(localStorage.getItem(localStorageKey) || "{}");
        for (const name in savedData) {
            $(`[name="${name}"]`).val(savedData[name]);
        }
    }

    function clearDraft() {
        localStorage.removeItem(localStorageKey);
    }

    function validateUniquePembimbingPenguji() {
        $(".pembimbing, .penguji").each(function () {
            let row = $(this).closest("tr");
            let pembimbing1 = row.find(".pembimbing").eq(0).val();
            let pembimbing2 = row.find(".pembimbing").eq(1).val();
            let penguji1 = row.find(".penguji").eq(0).val();
            let penguji2 = row.find(".penguji").eq(1).val();
            let penguji3 = row.find(".penguji").eq(2).val();

            if (pembimbing1 && pembimbing2 && pembimbing1 === pembimbing2) {
                Swal.fire('Kesalahan!', 'Pembimbing 1 dan Pembimbing 2 tidak boleh sama.', 'error');
                row.find(".pembimbing").eq(1).val("");
            }

            let pengujiSet = new Set([penguji1, penguji2, penguji3].filter(Boolean));
            if (pengujiSet.size < [penguji1, penguji2, penguji3].filter(Boolean).length) {
                Swal.fire('Kesalahan!', 'Penguji tidak boleh duplikat.', 'error');
                $(this).val("");
            }
        });
    }

    function updateDosenList() {
        let allocatedDosen = new Set();
        $(".pembimbing, .penguji").each(function () {
            if (this.value) allocatedDosen.add(this.value);
        });
        $("#dosenTable tbody tr").each(function () {
            let dosenId = $(this).find("td:nth-child(2)").text().trim();
            $(this).toggle(!allocatedDosen.has(dosenId));
        });
    }

    function autoExpandTextarea() {
        $(".auto-expand").each(function () {
            this.style.height = "auto";
            this.style.height = (this.scrollHeight) + "px";
        });
    }

    // ======================= Main Logic ========================

    $(document).ready(function () {
        loadDraft();
        autoExpandTextarea();

        let table = $('#alokasiTable').DataTable({
            scrollX: true,
            autoWidth: false,
            responsive: false,
            paging: true,
            fixedHeader: true,
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ada data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: { first: "<<", last: ">>", next: ">", previous: "<" }
            }
        });

        $(document).on("input change", ".pembimbing, .penguji, .catatan-input", function () {
            const row = $(this).closest("tr");
            const id = row.data("id");
            const field = $(this).attr("name");
            saveData(id, field, this.value);
            updateDosenList();
            autoExpandTextarea();
        });

        $(document).on("change", ".pembimbing", function () {
            const detailContainer = $(this).closest("td").next().find(".detail-content");
            fetchDosenDetailDebounced(this.value, detailContainer);
        });

        $(document).on("change", ".status-dropdown", function () {
            const status = this.value;
            $(this).closest('.status-cell').attr('data-status', status)
                .toggleClass('fix', status === 'fix')
                .toggleClass('belum_fix', status === 'belum_fix');
        });

        $("#saveDraftBtn").click(function () {
            saveDraft();
            Swal.fire('Draft Disimpan', '', 'success');
        });

        $("#openConfirmModal").click(function () {
            clearDraft();
            submitForm();
        });

        $(".status-dropdown").each(function () {
            const status = $(this).val();
            $(this).closest('.status-cell')
                .attr('data-status', status)
                .toggleClass('fix', status === 'fix')
                .toggleClass('belum_fix', status === 'belum_fix');
        });

        $(".pembimbing").each(function () {
            const nip = $(this).val();
            if (!nip) return;

            const detailContainer = $(this).closest("td").next().find(".detail-content");
            fetchDosenDetail(nip, detailContainer); 
        });

        $(".pembimbing, .penguji, .status-dropdown").each(function () {
            $(this).trigger("change");
        });

        $("#alokasiTable tbody tr").each(function () {
            const id = $(this).data("id");
            if (!id) return;

            // Pembimbing
            const pemb1 = $(this).find(".pembimbing").eq(0).val();
            const pemb2 = $(this).find(".pembimbing").eq(1).val();
            const stat1 = $(this).find(".status-dropdown").eq(0).val();
            const stat2 = $(this).find(".status-dropdown").eq(1).val();

            if (pemb1) saveData(id, "pembimbing1", pemb1);
            if (pemb2) saveData(id, "pembimbing2", pemb2);
            if (stat1) saveData(id, "status_pembimbing1", stat1);
            if (stat2) saveData(id, "status_pembimbing2", stat2);

            // Penguji
            const penguji1 = $(this).find(".penguji").eq(0).val();
            const penguji2 = $(this).find(".penguji").eq(1).val();
            const penguji3 = $(this).find(".penguji").eq(2).val();

            if (penguji1) saveData(id, "penguji1", penguji1);
            if (penguji2) saveData(id, "penguji2", penguji2);
            if (penguji3) saveData(id, "penguji3", penguji3);

            // Catatan
            const catatan = $(this).find(".catatan-input").val();
            if (catatan) saveData(id, "catatan", catatan);
        });

        $(".pembimbing, .penguji").on("change", validateUniquePembimbingPenguji);
    });

    // ======================= Submit Form ========================

    function submitForm() {
        let pembimbingFilled = $(".pembimbing").filter(function () { return this.value.trim(); }).length;
        let pengujiFilled = $(".penguji").filter(function () { return this.value.trim(); }).length;
        let hasPending = $(".status-dropdown").is(function () { return this.value !== 'fix'; });

        if (pembimbingFilled < 1 || pengujiFilled < 1) {
            Swal.fire('Validasi Gagal!', 'Minimal 1 pembimbing dan 1 penguji harus diisi.', 'error');
            return;
        }

        if (DataToSend.length === 0) {
            Swal.fire('Tidak ada perubahan!', '', 'warning');
            return;
        }

        const parsedData = JSON.stringify(DataToSend);
        $('#dataToSend').val(parsedData);

        if (hasPending) {
            Swal.fire({
                title: 'Status Belum Fix',
                text: 'Ada dosen belum fix. Lanjutkan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batalkan',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $(".status-dropdown").val("fix").trigger("change");
                    $('#alokasiForm').submit();
                }
            });
        } else {
            $('#alokasiForm').submit();
        }
    }
</script>
@stop