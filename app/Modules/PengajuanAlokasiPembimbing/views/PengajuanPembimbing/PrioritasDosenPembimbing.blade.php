@extends('adminlte::page')

<<<<<<< HEAD
@section('title', 'PengajuanAlokasiPembimbing')
=======
@section('title', 'Formulir Pengajuan Dosen Pembimbing')
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b

@section('content_header')
    <div class="m-3">
        <h1>Formulir Pengajuan Dosen Pembimbing</h1>
    </div>
@stop

@section('content')

    <div class="container-fluid row w-100 justify-content-start">
        <div class="card p-4 bg-light">
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="3"
<<<<<<< HEAD
                activeColor="primary" inactiveColor="secondary" :hrefs="['#', '#', '#', '#']" />
=======
                activeColor="primary" inactiveColor="secondary" 
                :hrefs="['data-kelompok', 'topik-tugas-akhir', 'prioritas-dosen-pembimbing', 'pratinjau-formulir']" />
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
        </div>

        <div class="col">
            <div class="card p-4 bg-light">
                <h5 class="mb-3">Prioritas Dosen Pembimbing</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">List Dosen Pembimbing</label>
                        <div class="border p-2" style="max-height: 70vh; overflow-y: auto;">
                            <ul id="dosenList" class="list-group">
                                @foreach ($listDosen as $d)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $d->nama }}</span>
                                        <div>
<<<<<<< HEAD
                                            <button class="btn btn-sm btn-secondary viewHistory" data-name="{{ $d->nama }}">
=======
                                            <button class="btn btn-sm btn-secondary viewHistory" data-name="{{ $d->nama }}" data-nip="{{ $d->nip }}">
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
                                                <i class="fas fa-file-alt"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary addDosen" data-name="{{ $d->nama }}">+</button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Urutan Prioritas Dosen Pembimbing (Maks. 5)</label>
                        <ul id="prioritasList" class="list-group mt-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="priority-number">{{ $i }}</span>
                                    <span class="priority-name">-</span>
                                    <button class="btn btn-sm btn-danger removeDosen" style="display: none;">X</button>
                                </li>
                            @endfor
                        </ul>
                    </div>
                    
                </div>

                <!-- Tombol Simpan & Selanjutnya -->
                <div class="d-flex justify-content-between mt-3">
                    <a href={{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.topik-tugas-akhir') }} class="btn btn-info ml-3">Sebelumnya</a>
                    <button type="submit" class="btn btn-sm btn-primary" style="font-size: 15px">Simpan Draft</button>
<<<<<<< HEAD
                    <a href={{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.pratinjau-formulir') }} class="btn btn-info ml-3">Selanjutnya</a>
=======
                    <a href={{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.pratinjau-formulir.index') }} class="btn btn-info ml-3">Selanjutnya</a>
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
                </div>
            </div>
    </div>

    <!-- Modal untuk Riwayat Topik -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
<<<<<<< HEAD
                    <h5 class="modal-title" id="historyModalLabel">Riwayat Topik</h5>
=======
                    <h5 class="modal-title" id="historyModalLabel">Riwayat Ketertarikan Bidang</h5>
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="dosenName"></p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tahun</th>
<<<<<<< HEAD
                                <th>Riwayat Topik</th>
=======
                                <th>Bidang</th>
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
                            </tr>
                        </thead>
                        <tbody id="historyContent">
                            {{-- Data akan diisi lewat JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

@section ('js')
<<<<<<< HEAD
=======

>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function () {
        // Menambahkan dosen ke daftar prioritas
        $(".addDosen").click(function () {
            let name = $(this).data("name");

            // Cari slot kosong pertama dalam daftar prioritas
            let emptySlot = $("#prioritasList .priority-name").filter(function () {
                return $(this).text() === "-";
            }).first();

            if (emptySlot.length > 0) {
                emptySlot.text(name);
                emptySlot.siblings(".removeDosen").show();
                $(this).prop("disabled", true); // Disable tombol tambah agar tidak bisa dipilih dua kali
            }
        });

        // Menghapus dosen dari daftar prioritas
        $(document).on("click", ".removeDosen", function () {
            let parent = $(this).closest("li");
            let removedName = parent.find(".priority-name").text();

            parent.find(".priority-name").text("-");
            $(this).hide();

            // Aktifkan kembali tombol tambah untuk dosen yang dihapus
            $(".addDosen").each(function () {
                if ($(this).data("name") === removedName) {
                    $(this).prop("disabled", false);
                }
            });
        });

<<<<<<< HEAD
        // // Mengaktifkan fitur drag-and-drop untuk mengurutkan dosen tanpa mengubah nomor urut
        // $("#prioritasList").sortable({
        //     axis: "y",
        //     opacity: 0.8,
        //     cursor: "move",
        //     items: "> li",
        //     update: function () {
        //         console.log("Urutan dosen diperbarui");
        //     }
        // }).disableSelection();

        // Fungsi untuk menampilkan riwayat topik dosen pembimbing
        $(".viewHistory").click(function (event) {
            event.preventDefault(); // Mencegah event default agar modal muncul dengan benar

            let name = $(this).data("name");
            $("#dosenName").text(name);
            let historyContent = $("#historyContent");
            historyContent.html(`
                <tr>
                    <td>2024</td>
                    <td>
                        <ul>
                            <li>Data and Information Management</li>
                            <li>Machine Learning</li>
                            <li>Computer Vision</li>
                            <li>Pengembangan Perangkat Lunak</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>2025</td>
                    <td>
                        <ul>
                            <li>Pengembangan Perangkat Lunak</li>
                            <li>Data and Information Management</li>
                        </ul>
                    </td>
                </tr>
            `);

            // Panggil modal agar muncul
=======
        // Menyimpan Draft
        $(".btn-primary[type='submit']").click(function () {
            let prioritasDosen = [];
            
            $("#prioritasList .priority-name").each(function () {
                let name = $(this).text();
                if (name !== "-") {
                    let priority = $(this).siblings(".priority-number").text();
                    prioritasDosen.push({ name: name, priority: priority });
                }
            });

            // Simpan data prioritas dosen ke localStorage
            localStorage.setItem("prioritasDosen", JSON.stringify(prioritasDosen));
            alert("Draft berhasil disimpan!");
        });

        // Menampilkan prioritas dosen yang sudah disimpan di localStorage
        let prioritasDosen = JSON.parse(localStorage.getItem("prioritasDosen"));
        if (prioritasDosen) {
            // Menampilkan nama dosen dan urutan prioritas yang sudah disimpan
            prioritasDosen.forEach(function (dosen, index) {
                if (index < 5) {  // Hanya tampilkan maksimal 5 prioritas dosen
                    let priorityNumber = index + 1;
                    let listItem = $("#prioritasList li").eq(index);
                    listItem.find(".priority-name").text(dosen.name);
                    listItem.find(".removeDosen").show(); // Tampilkan tombol hapus
                }
            });
        }

        // Menonaktifkan tombol "Tambah Dosen" untuk dosen yang sudah ada di prioritas
        let existingPrioritas = JSON.parse(localStorage.getItem("prioritasDosen")) || [];
        $(".addDosen").each(function () {
            let dosenName = $(this).data("name");

            // Jika dosen sudah ada di prioritas, nonaktifkan tombol "Tambah Dosen"
            let isAlreadyAdded = existingPrioritas.some(function (prioritas) {
                return prioritas.name === dosenName;
            });

            if (isAlreadyAdded) {
                $(this).prop("disabled", true); // Nonaktifkan tombol
            }
        });

        // Fungsi untuk menampilkan riwayat topik dosen pembimbing
        $(".viewHistory").click(function (event) {
            event.preventDefault();
            
            let nip = $(this).data("nip"); // Ambil NIP dari atribut data
            let name = $(this).data("name");
            $("#dosenName").text(name);
            
            let historyContent = $("#historyContent");
            historyContent.html("<tr><td colspan='2' class='text-center'>Loading...</td></tr>");

            $.ajax({
                url: `/prioritas-dosen-pembimbing/dosen/history/${nip}`,
                type: "GET",
                success: function (response) {
                    historyContent.empty(); // Kosongkan tabel sebelum memasukkan data

                    if (Object.keys(response).length === 0) {
                        historyContent.html("<tr><td colspan='2' class='text-center'>Tidak ada data</td></tr>");
                    } else {
                        $.each(response, function (tahun, bidangList) {
                            let bidangHtml = "<ul>";
                            bidangList.forEach(function (bidang) {
                                bidangHtml += `<li>${bidang.bidang}</li>`;
                            });
                            bidangHtml += "</ul>";

                            historyContent.append(`
                                <tr>
                                    <td>${tahun}</td>
                                    <td>${bidangHtml}</td>
                                </tr>
                            `);
                        });
                    }
                },
                error: function () {
                    historyContent.html("<tr><td colspan='2' class='text-center text-danger'>Gagal mengambil data</td></tr>");
                }
            });

>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
            $("#historyModal").modal("show");
        });
    });
</script>
@stop