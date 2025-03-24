@extends('adminlte::page')

@section('title', 'Alokasi Dosen Pembimbing')

@section('content_header')
<h1>Alokasi Dosen Pembimbing</h1>
@stop

@section('content')
<form action="{{ route('pengajuanalokasipembimbing.alokasi-pembimbing.submit') }}" method="POST" id="alokasiForm">
    @csrf
    <input type="hidden" id="dataToSend" name="dataToSend">
</form>

<div class="p-4">

    <datalist id="dosenList">
        @foreach ($dosenList as $dosen)
        <option value="{{ $dosen['id_dosen'] }}">{{ $dosen['nama'] }}</option>
        @endforeach
    </datalist>

    <div class="d-flex justify-content-between mb-2">
        <div id="dataTableControls"></div>
        <div id="searchBox"></div>
    </div>

    <div class="table-container">
        <table id="alokasiTable" class="table text-center" style="min-width: 1400px;">
            <thead class="sticky-header">
                <tr class="bg-dark text-white">
                    <th rowspan="2" class="sticky-column no-sort" style="width: 5%; position: sticky; left: 0; background: rgba(0, 0, 0, 0.9);">No</th>
                    <th rowspan="2" class="sticky-column no-sort" style="width: 15%; position: sticky; left: 5%; background: rgba(0, 0, 0, 0.9);">Kelompok
                    </th>
                    <th rowspan="2" class="no-sort" style="width: 20%;">Anggota</th>
                    <th rowspan="2" class="no-sort" style="width: 15%;">Bidang</th>
                    <th rowspan="2" class="no-sort" style="width: 20%;">Judul/Topik</th>
                    <th colspan="5" class="no-sort" style="width: 20%">Usulan Pembimbing</th>
                    <th colspan="4" class="no-sort" style="width: 30%">Pembimbing</th>
                    <th colspan="3" class="no-sort" style="width: 20%">Penguji</th>
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
                        {{ $index + 1 }}</td>
                    <td class="align-middle sticky-column" style="position: sticky; left: 5%; background: white;">
                        {{ $row['nama_kota'] }}</td>
                    <td class="align-middle">
                        <ul class="m-0 p-0" style="list-style-type: none;">
                            @foreach ($row['mahasiswa'] as $anggota)
                            <li>{{ $anggota['nama'] }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="align-middle">{{ $row['bidang'] }}</td>
                    <td class="align-middle">{{ $row['judul_ta'] }}</td>
                    @for ($i = 1; $i <= 5; $i++) <td class="align-middle">
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
                            <input type="text" class="form-control text-center pembimbing mb-2" data-index="{{ $index }}" name="pembimbing1{{ $row['nama_kota'] }}" list="dosenList" value="{{ $row['preferensi_dosen'][0]['id_dosen'] ?? '' }}" onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'pembimbing1', this.value)">
                            <label>Status:</label>
                            <select class="form-control status-dropdown w-100" data-detail-container="#detailPembimbing1" onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'status_pembimbing1', this.value)">
                                <option value="belum_fix" {{ isset($row['preferensi_dosen'][0]) ? '' : 'selected' }}>Belum Fix</option>
                                <option value="fix" {{ isset($row['preferensi_dosen'][0]) ? 'selected' : '' }}>Fix</option>
                            </select>
                        </td>
                        <td class="align-middle text-left">
                            <div class="detail-content" id="detailPembimbing1">
                                <div><strong>Nama:</strong> {{ $row['detailPembimbing1']['nama'] ?? '-' }}</div>
                                <div><strong>KoTA:</strong> P1: {{ $row['detailPembimbing1']['pembimbing1_KoTA'] ?? '0' }} |
                                    P2: {{ $row['detailPembimbing1']['pembimbing2_KoTA'] ?? '0' }} |
                                    Total: {{ $row['detailPembimbing1']['jumlah_KoTA'] ?? '0' }}</div>
                                <div><strong>Mhs:</strong> P1: {{ $row['detailPembimbing1']['pembimbing1_Mhs'] ?? '0' }} |
                                    P2: {{ $row['detailPembimbing1']['pembimbing2_Mhs'] ?? '0' }} |
                                    Total: {{ $row['detailPembimbing1']['jumlahMahasiswa'] ?? '0' }}</div>
                                <div><strong>Kuota:</strong> {{ $row['detailPembimbing1']['kuota'] ?? '0' }}</div>
                                <div><strong>Kelebihan:</strong>
                                    @if (($row['detailPembimbing1']['jumlahMahasiswa'] ?? 0) > ($row['detailPembimbing1']['kuota'] ?? 0))
                                    <span style="color: red;">
                                        {{ ($row['detailPembimbing1']['jumlahMahasiswa'] ?? 0) - ($row['detailPembimbing1']['kuota'] ?? 0) }} (Overload)
                                    </span>
                                    @else
                                    <span style="color: green;">Aman</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="align-middle status-cell" data-status="belum_fix">
                            <input type="text" class="form-control text-center pembimbing mb-2" data-index="{{ $index }}" name="pembimbing2{{ $row['nama_kota'] }}" list="dosenList" value="{{ $row['preferensi_dosen'][1]['id_dosen'] ?? '' }}" onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'pembimbing2', this.value)">
                            <label>Status:</label>
                            <select class="form-control status-dropdown w-100" data-detail-container="#detailPembimbing2" onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'status_pembimbing2', this.value)">
                                <option value="belum_fix" {{ isset($row['preferensi_dosen'][1]) ? '' : 'selected' }}>Belum Fix</option>
                                <option value="fix" {{ isset($row['preferensi_dosen'][1]) ? 'selected' : '' }}>Fix</option>
                            </select>
                        </td>
                        <td class="align-middle text-left">
                            <div class="detail-content" id="detailPembimbing2">
                                <div><strong>Nama:</strong> {{ $row['detailPembimbing2']['nama'] ?? '-' }}</div>
                                <div><strong>KoTA:</strong> P1:
                                    {{ $row['detailPembimbing2']['pembimbing1_KoTA'] ?? '0' }} |
                                    P2: {{ $row['detailPembimbing2']['pembimbing2_KoTA'] ?? '0' }} |
                                    Total: {{ $row['detailPembimbing2']['jumlah_KoTA'] ?? '0' }}</div>
                                <div><strong>Mhs:</strong> P1:
                                    {{ $row['detailPembimbing2']['pembimbing1_Mhs'] ?? '0' }} |
                                    P2: {{ $row['detailPembimbing2']['pembimbing2_Mhs'] ?? '0' }} |
                                    Total: {{ $row['detailPembimbing2']['jumlahMahasiswa'] ?? '0' }}</div>
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
                            <input type="text" class="form-control text-center penguji" data-index="{{ $index }}" name="penguji1{{ $row['nama_kota'] }}" list="dosenList" onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'penguji1', this.value)">
                        </td>
                        <td class="align-middle">
                            <input type="text" class="form-control text-center penguji" data-index="{{ $index }}" name="penguji2{{ $row['nama_kota'] }}" list="dosenList" onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'penguji2', this.value)">
                        </td>
                        <td class="align-middle">
                            <input type="text" class="form-control text-center penguji" data-index="{{ $index }}" name="penguji3{{ $row['nama_kota'] }}" list="dosenList" onchange="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'penguji3', this.value)">
                        </td>
                        <td class="align-middle" style="min-width: 150px;">
                            <textarea class="form-control text-left auto-expand catatan-input" data-id="{{ $row['id_pengajuan_pembimbing'] }}" name="catatan_{{ $index }}" rows="1" style="overflow: hidden; resize: none;" oninput="saveData('{{ $row['id_pengajuan_pembimbing'] }}', 'catatan', this.value)">{{ trim($row['catatan'] ?? '') }}</textarea>
                        </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary mr-2" id="saveDraftBtn">Simpan</button>
        <button type="button" class="btn btn-primary" id="openConfirmModal" onclick="submitForm()">Finalisasi</button>
    </div>
</div>

<div class="p-4 mt-5">
    <h3>List Dosen Pembimbing</h3>
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
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .table-container {
        max-height: 500px;
        overflow: auto;
        border: 1px solid #ddd;
        position: relative;
        width: 100%;
    }

    table {
        width: 100%;
        min-width: 1400px;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ccc !important;
        padding: 10px;
        text-align: center;
        white-space: nowrap;
    }

    thead {
        position: sticky;
        top: 0;
        z-index: 1030;
        background: rgba(0, 0, 0, 0.9);
        color: white;
    }

    thead th {
        position: sticky;
        top: 0;
        z-index: 1031;
        background-color: rgba(0, 0, 0, 0.9);
        color: white !important;
        text-align: center;
        padding: 12px;
        border-bottom: 2px solid #fff;
    }

    th.sticky-column {
        background: rgba(0, 0, 0, 0.9) !important;
        color: white !important;
        z-index: 1032 !important;
    }

    td.sticky-column {
        color: black !important;
        background: white !important;
        z-index: 1025;
    }

    .sticky-column:first-child {
        background: rgba(0, 0, 0, 0.9);
        color: white;
    }

    th:first-child {
        white-space: nowrap;
        text-align: center;
    }

    tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    tbody tr:hover {
        background-color: #e2e6ea;
    }

    table.dataTable thead th.no-sort.sorting::before,
    table.dataTable thead th.no-sort.sorting::after,
    table.dataTable thead th.no-sort.sorting_asc::before,
    table.dataTable thead th.no-sort.sorting_asc::after,
    table.dataTable thead th.no-sort.sorting_desc::before,
    table.dataTable thead th.no-sort.sorting_desc::after {
        display: none !important;
        background-image: none !important;
    }

    table.dataTable thead th.no-sort {
        background-image: none !important;
    }

    .status-cell[data-status="fix"] {
        background-color: green !important;
        color: white !important;
    }

    .status-cell[data-status="belum_fix"] {
        background-color: yellow !important;
        color: black !important;
    }

    .status-cell {
        min-width: 120px;
        padding: 5px;
        transition: background-color 0.3s ease-in-out;
    }

    .status-dropdown {
        width: 100%;
        text-align: center;
        padding: 5px;
        font-weight: bold;
        min-width: 120px;
        border-radius: 5px;
    }

    .auto-expand {
        width: 100%;
        min-height: 35px;
        max-height: 150px;
        resize: none;
        overflow-y: hidden;
        border: 1px solid #ccc;
        padding: 5px;
        font-size: 14px;
        transition: height 0.2s ease-in-out;
    }

    button:hover {
        opacity: 0.8;
        transition: 0.2s;
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

    th:nth-child(15),
    th:nth-child(16),
    th:nth-child(17),
    td:nth-child(15),
    td:nth-child(16),
    td:nth-child(17) {
        min-width: 70px !important;
    }

</style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    var DataToSend = [];
    $(document).ready(function() {
        $(".pembimbing, .penguji, .catatan-input").each(function() {
            var row = $(this).closest("tr");
            var id_pengajuan_pembimbing = row.data("id");

            if (!id_pengajuan_pembimbing) {
                console.warn("Skipping row: Missing or invalid id_pengajuan_pembimbing", row);
                return;
            }

            var fieldName = $(this).attr("name")
                .replace(/\d+$/, "") // Hapus angka di akhir
                .replace(/Kota.*/, ""); // Hapus "Kota" dan teks setelahnya

            var value = $(this).val().trim();

            if (value) {
                saveData(id_pengajuan_pembimbing, fieldName, value);
            }
        });

        function saveData(id_pengajuan_pembimbing, key, value) {
            let found = DataToSend.find(item => item.id_pengajuan_pembimbing == id_pengajuan_pembimbing);

            if (!found) {
                found = {
                    id_pengajuan_pembimbing: id_pengajuan_pembimbing
                };
                DataToSend.push(found);
            }

            found[key] = value;
            console.log("Updated DataToSend:", DataToSend);
        }

        $(".catatan-input").on("input", function() {
            var id_pengajuan_pembimbing = $(this).data("id");
            var catatan = $(this).val();
            saveData(id_pengajuan_pembimbing, "catatan", catatan);
        });

        $(".penguji").on("input", function() {
            var id_pengajuan_pembimbing = $(this).closest("tr").find("td:first").text().trim();
            var role = $(this).attr("name").split(id_pengajuan_pembimbing)[0];
            var value = $(this).val();
            saveData(id_pengajuan_pembimbing, role, value);
        });

        let table = $('#alokasiTable').DataTable({
            responsive: true
            , paging: true
            , lengthMenu: [10, 25, 50, 100]
            , pageLength: 10
            , searching: true
            , ordering: true
            , info: true
            , autoWidth: false
            , drawCallback: function() {
                initializeScripts();
            }
        });

        function autoExpandTextarea() {
            $(".auto-expand").each(function() {
                this.style.height = "auto";
                this.style.height = (this.scrollHeight) + "px";
            });
        }

        function initializeScripts() {
            $(".status-dropdown").off("change").on("change", function() {
                let cell = $(this).closest('.status-cell');
                let status = $(this).val();
                cell.attr('data-status', status);
                cell.removeClass("belum_fix fix").addClass(status);
            });

            $(".auto-expand").off("input").on("input", function() {
                this.style.height = "auto";
                this.style.height = (this.scrollHeight) + "px";
            });
        }

        $('#saveDraftBtn').click(function() {
            Swal.fire({
                icon: 'success'
                , title: 'Draft Disimpan'
                , text: 'Data alokasi pembimbing berhasil disimpan sebagai draft.'
                , timer: 2000
                , showConfirmButton: false
            });
        });

        autoExpandTextarea();
        initializeScripts();
        const localStorageKey = "alokasiPembimbingDraft";

        // Fungsi untuk menyimpan data ke Local Storage
        function saveDraft() {
            let draftData = {};

            // Simpan nilai setiap input dalam form
            $("input, select, textarea").each(function() {
                draftData[$(this).attr("name")] = $(this).val();
            });

            // Simpan ke Local Storage
            localStorage.setItem(localStorageKey, JSON.stringify(draftData));

            Swal.fire({
                icon: 'success'
                , title: 'Draft Disimpan'
                , text: 'Data alokasi pembimbing berhasil disimpan sebagai draft.'
                , timer: 2000
                , showConfirmButton: false
            });
        }

        // Fungsi untuk memuat data dari Local Storage saat halaman dimuat
        function loadDraft() {
            let savedData = localStorage.getItem(localStorageKey);

            if (savedData) {
                savedData = JSON.parse(savedData);

                // Isi kembali form dengan data yang disimpan
                $("input, select, textarea").each(function() {
                    if (savedData[$(this).attr("name")]) {
                        $(this).val(savedData[$(this).attr("name")]);
                    }
                });
            }
        }

        // Fungsi untuk menghapus draft saat "Finalisasi" ditekan
        function clearDraft() {
            localStorage.removeItem(localStorageKey);
        }

        // Event listener untuk tombol "Simpan" -> Simpan ke Local Storage
        $("#saveDraftBtn").click(function() {
            saveDraft();
        });

        // Event listener untuk tombol "Finalisasi" -> Hapus draft dari Local Storage
        $("#openConfirmModal").click(function() {
            clearDraft();
        });

        // Saat halaman pertama kali dimuat, langsung ambil detail dosen jika pembimbing sudah terisi
        $(".pembimbing").each(function() {
            var nip = $(this).val();
            var detailContainer = $(this).closest("td").next().find(".detail-content");

            if (nip) {
                fetchDosenDetail(nip, detailContainer);
            }
        });

        // Saat halaman pertama kali dimuat, langsung ubah warna dropdown status
        $(".status-dropdown").each(function() {
            let selectedStatus = $(this).val();
            let cell = $(this).closest('.status-cell');

            if (selectedStatus === "fix") {
                cell.attr("data-status", "fix").css("background-color", "green").css("color", "white");
            } else {
                cell.attr("data-status", "belum_fix").css("background-color", "yellow").css("color", "black");
            }
        });

        // Muat data draft saat halaman pertama kali dibuka
        loadDraft();
    });

    function saveData(id_pengajuan_pembimbing, key, value) {
        let found = DataToSend.find(item => item.id_pengajuan_pembimbing == id_pengajuan_pembimbing);

        if (!found) {
            found = {
                id_pengajuan_pembimbing: id_pengajuan_pembimbing
            };
            DataToSend.push(found);
        }

        found[key] = value;
        console.log("Updated DataToSend:", DataToSend);
    }

    function submitForm() {
        let pembimbingCount = 0;
        let pengujiCount = 0;
        let hasPendingStatus = false;

        $(".pembimbing").each(function() {
            if ($(this).val().trim() !== "") {
                pembimbingCount++;
            }
        });

        $(".penguji").each(function() {
            if ($(this).val().trim() !== "") {
                pengujiCount++;
            }
        });

        $(".status-dropdown").each(function() {
            if ($(this).val() !== "fix") {
                hasPendingStatus = true;
            }
        });

        let isValidRow = true;

        $("#alokasiTable tbody tr").each(function() {
            let pembimbing1 = $(this).find(".pembimbing").eq(0).val().trim();
            let pembimbing2 = $(this).find(".pembimbing").eq(1).val().trim();
            let penguji1 = $(this).find(".penguji").eq(0).val().trim();
            let penguji2 = $(this).find(".penguji").eq(1).val().trim();
            let penguji3 = $(this).find(".penguji").eq(2).val().trim();

            if (
                pembimbing1 === "" &&
                pembimbing2 === "" &&
                penguji1 === "" &&
                penguji2 === "" &&
                penguji3 === ""
            ) {
                isValidRow = false;
                return false; // break .each()
            }
        });

        if (!isValidRow) {
            Swal.fire({
                icon: 'error'
                , title: 'Validasi Gagal!'
                , text: 'Setiap kelompok minimal harus memiliki satu pembimbing atau satu penguji.'
                , timer: 3000
                , showConfirmButton: true
            });
            return;
        }

        if (pembimbingCount < 1) {
            Swal.fire({
                icon: 'error'
                , title: 'Validasi Gagal!'
                , text: 'Minimal 1 pembimbing harus diisi sebelum melakukan finalisasi.'
                , timer: 3000
                , showConfirmButton: true
            });
            return;
        }

        if (pengujiCount < 1) {
            Swal.fire({
                icon: 'error'
                , title: 'Validasi Gagal!'
                , text: 'Minimal 1 penguji harus diisi sebelum melakukan finalisasi.'
                , timer: 3000
                , showConfirmButton: true
            });
            return;
        }

        if (DataToSend.length === 0) {
            Swal.fire({
                icon: 'warning'
                , title: 'Tidak ada perubahan!'
                , text: 'Silakan isi atau ubah alokasi pembimbing sebelum menyimpan.'
                , timer: 2000
                , showConfirmButton: false
            });
            return;
        }

        var ParseddataToSend = JSON.stringify(DataToSend);
        console.log("Final Data to Send:", ParseddataToSend);
        $('#dataToSend').val(ParseddataToSend);

        if (hasPendingStatus) {
            Swal.fire({
                icon: 'warning'
                , title: 'Status Dosen Belum Fix'
                , text: 'Ada dosen yang statusnya belum fix. Apakah Anda yakin ingin melanjutkan finalisasi?'
                , showCancelButton: true
                , confirmButtonText: 'Ya, Lanjutkan'
                , cancelButtonText: 'Batal'
                , preConfirm: () => {
                    return new Promise((resolve, reject) => {
                        $(".status-dropdown").each(function() {
                            $(this).val("fix").trigger("change");

                            let id_pengajuan_pembimbing = $(this).closest("tr").find(".pembimbing").first().data("index");
                            let rowId = @json($list_pengajuan)[id_pengajuan_pembimbing]['id_pengajuan_pembimbing'];
                            let statusKey = $(this).closest("td").find(".pembimbing").length > 0 ? "status_pembimbing1" : "status_pembimbing2";
                            saveData(rowId, statusKey, "fix");
                        });

                        ParseddataToSend = JSON.stringify(DataToSend);
                        $('#dataToSend').val(ParseddataToSend);

                        $.ajax({
                            url: $("#alokasiForm").attr('action')
                            , type: "POST"
                            , data: {
                                _token: "{{ csrf_token() }}"
                                , dataToSend: ParseddataToSend
                            }
                            , success: function(response) {
                                resolve(response);
                            }
                            , error: function() {
                                reject("Terjadi kesalahan saat mengirim data!");
                            }
                        });
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success'
                        , title: 'Sukses'
                        , text: 'Alokasi pembimbing berhasil diajukan!'
                        , timer: 2000
                        , showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            }).catch((error) => {
                Swal.fire({
                    icon: 'error'
                    , title: 'Gagal'
                    , text: error
                    , timer: 2000
                    , showConfirmButton: false
                });
            });
        } else {
            $('#alokasiForm').submit();
        }
    }

    const dosenCache = {};

    var prefixUrl = "{{ env('PREFIX_URL') }}";

    function fetchDosenDetail(nip, detailContainer) {
        if (!nip) {
            detailContainer.html(`
            <div><strong>Nama:</strong> -</div>
            <div><strong>KoTA:</strong> P1: 0 | P2: 0 | Total: 0</div>
            <div><strong>Mhs:</strong> P1: 0 | P2: 0 | Total: 0</div>
            <div><strong>Kuota:</strong> 0</div>
            <div><strong>Kelebihan:</strong> Aman</div>
        `);
            return;
        }

        console.log(`Fetching data for NIP: ` + nip);

        $.ajax({
            url: prefixUrl + `/PengajuanAlokasiPembimbing/alokasi-pembimbing/getDetailDosen/` + nip
            , type: "GET"
            , dataType: "json"
            , success: function(response) {
                console.log("Response Data:", response);

                if (response.error) {
                    detailContainer.html(`<div style="color: red;">${response.error}</div>`);
                    return;
                }

                const data = response;

                if (dosenCache[nip]) {
                    dosenCache[nip].pembimbing1_KoTA += data.pembimbing1_KoTA;
                    dosenCache[nip].pembimbing2_KoTA += data.pembimbing2_KoTA;
                    dosenCache[nip].jumlah_KoTA += data.jumlah_KoTA;
                    dosenCache[nip].pembimbing1_Mhs += data.pembimbing1_Mhs;
                    dosenCache[nip].pembimbing2_Mhs += data.pembimbing2_Mhs;
                    dosenCache[nip].jumlahMahasiswa += data.jumlahMahasiswa;
                    dosenCache[nip].kuota += data.kuota;

                    // **Mengolah kelebihan (Overload)**
                    const prevKelebihan = parseInt(dosenCache[nip].kelebihan.match(/\d+/)[0]) || 0;
                    const newKelebihan = parseInt(data.kelebihan.match(/\d+/)[0]) || 0;
                    dosenCache[nip].kelebihan = `${prevKelebihan + newKelebihan} (Overload)`;
                } else {
                    // Simpan data baru jika belum ada
                    dosenCache[nip] = {
                        ...data
                    };
                }

                const dosen = dosenCache[nip];

                detailContainer.html(`
                <div><strong>Nama:</strong> ${dosen.nama}</div>
                <div><strong>KoTA:</strong> P1: ${dosen.pembimbing1_KoTA} |
                    P2: ${dosen.pembimbing2_KoTA} |
                    Total: ${dosen.jumlah_KoTA}</div>
                <div><strong>Mhs:</strong> P1: ${dosen.pembimbing1_Mhs} |
                    P2: ${dosen.pembimbing2_Mhs} |
                    Total: ${dosen.jumlahMahasiswa}</div>
                <div><strong>Kuota:</strong> ${dosen.kuota}</div>
                <div><strong>Kelebihan:</strong>
                    <span style="color: ${dosen.kelebihan.includes('Overload') ? 'red' : 'green'};">
                        ${dosen.kelebihan}
                    </span>
                </div>
            `);
            }
            , error: function(xhr) {
                console.log("AJAX Error:", xhr.responseText);
                detailContainer.html(`<div style="color: red;">Gagal mengambil data dosen.</div>`);
            }
        });
    }

    $(document).on("change", ".pembimbing", function() {
        var nip = $(this).val();
        var detailContainer = $(this).closest("td").next().find(".detail-content");
        fetchDosenDetail(nip, detailContainer);
    });

    $(document).ready(function() {
        $(".catatan-input").on("input", function() {
            var id_pengajuan_pembimbing = $(this).data("id");
            var catatan = $(this).val();
            saveData(id_pengajuan_pembimbing, "catatan", catatan);
        });
    });

    function updateDosenList() {
        let allocatedDosen = new Set();

        $(".pembimbing, .penguji").each(function() {
            let selectedDosen = $(this).val();
            if (selectedDosen) {
                allocatedDosen.add(selectedDosen);
            }
        });

        $("#dosenTable tbody tr").each(function() {
            let dosenId = $(this).find("td:nth-child(2)").text().trim();
            if (allocatedDosen.has(dosenId)) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    }

    updateDosenList();

    $(document).on("change", ".pembimbing, .penguji", function() {
        updateDosenList();
    });

    $(document).on("input", ".pembimbing, .penguji", function() {
        updateDosenList();
    });
    $(document).ready(function() {
        function validateSelection() {
            $(".pembimbing, .penguji").each(function() {
                let row = $(this).closest("tr");
                let pembimbing1 = row.find(".pembimbing").eq(0).val();
                let pembimbing2 = row.find(".pembimbing").eq(1).val();
                let penguji1 = row.find(".penguji").eq(0).val();
                let penguji2 = row.find(".penguji").eq(1).val();
                let penguji3 = row.find(".penguji").eq(2).val();

                // **Validasi Pembimbing Tidak Boleh Sama dalam Satu KoTA**
                if (pembimbing1 && pembimbing2 && pembimbing1 === pembimbing2) {
                    Swal.fire({
                        icon: 'error'
                        , title: 'Kesalahan!'
                        , text: 'Pembimbing 1 dan Pembimbing 2 tidak boleh sama dalam satu kelompok.'
                        , confirmButtonText: 'OK'
                    });
                    row.find(".pembimbing").eq(1).val(""); // Kosongkan Pembimbing 2
                    return;
                }

                // **Validasi Penguji Tidak Boleh Sama dalam Satu KoTA**
                let pengujiSet = new Set([penguji1, penguji2, penguji3].filter(Boolean)); // Buang yang kosong/null
                if (pengujiSet.size < ([penguji1, penguji2, penguji3].filter(Boolean).length)) {
                    Swal.fire({
                        icon: 'error'
                        , title: 'Kesalahan!'
                        , text: 'Penguji dalam satu kelompok tidak boleh sama.'
                        , confirmButtonText: 'OK'
                    });

                    $(this).val(""); // Kosongkan input yang diubah terakhir
                    return;
                }
            });
        }

        $(".pembimbing, .penguji").on("change", validateSelection);
    });

</script>
@stop
