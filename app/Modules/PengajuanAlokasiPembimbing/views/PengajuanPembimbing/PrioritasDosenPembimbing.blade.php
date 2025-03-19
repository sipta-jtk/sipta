@extends('adminlte::page')

@section('title', 'Formulir Pengajuan Dosen Pembimbing')

@section('content_header')
    <div class="m-3">
        <h1>Formulir Pengajuan Dosen Pembimbing</h1>
    </div>
@stop

@section('content')

    <div class="container-fluid row w-100 justify-content-start">
        <div class="card p-4 bg-light">
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="3"
                activeColor="primary" inactiveColor="secondary" 
                :hrefs="['data-kelompok', 'topik-tugas-akhir', 'prioritas-dosen-pembimbing', 'pratinjau-formulir']" />
        </div>

        <div class="col">
            <div class="card p-4 bg-light">
                <h5 class="mb-3">Prioritas Dosen Pembimbing</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">List Dosen Pembimbing</label>

                        <input type="text" id="searchDosen" class="form-control mb-2" placeholder="Cari nama dosen...">

                        <div class="border p-2" style="max-height: 70vh; overflow-y: auto;">
                            <ul id="dosenList" class="list-group">
                                @foreach ($listDosen as $d)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="dosen-name">{{ $d->nama }}</span>
                                        <div>
                                            <button class="btn btn-sm btn-secondary viewHistory" type="button" data-toggle="modal" data-target="#historyModal{{$d->nip}}">
                                                <i class="fas fa-file-alt"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary addDosen" data-name="{{ $d->nama }}">+</button>
                                        </div>
                                    </li>

                                    <!-- Modal untuk Riwayat Topik -->
                                    <div class="modal fade" id="historyModal{{$d->nip}}" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="historyModalLabel">Riwayat Ketertarikan Bidang</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p id="dosenName">
                                                        {{ $d->nama }}
                                                    </p>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Bidang</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="historyContent">
                                                            @foreach ($d->history as $bidang)
                                                                <tr>
                                                                    <td>{{ $bidang->bidang }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                    <a href={{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.pratinjau-formulir.index') }} class="btn btn-info ml-3">Selanjutnya</a>
                </div>
            </div>
    </div>

    <!-- Modal untuk Riwayat Topik -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Riwayat Ketertarikan Bidang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="dosenName"></p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Bidang</th>
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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@include('PengajuanAlokasiPembimbing.Helper.JS.SweetAlert')

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

        function ValidateDosen(){
            let prioritasDosen = [];
            $("#prioritasList .priority-name").each(function () {
                let name = $(this).text();
                if (name !== "-") {
                    let priority = $(this).siblings(".priority-number").text();
                    prioritasDosen.push({ name: name, priority: priority });
                }
            });

            if (prioritasDosen.length < 1) {
                toast("error", "Prioritas dosen pembimbing tidak boleh kosong!");
                return false;
            }

            return true;
        }

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
            if (ValidateDosen()){
            localStorage.setItem("prioritasDosen", JSON.stringify(prioritasDosen));
            toast("success", "Draft berhasil disimpan!");
        }
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
        // $(".viewHistory").click(function (event) {
        //     event.preventDefault();
            
        //     let nip = $(this).data("nip"); // Ambil NIP dari atribut data
        //     let name = $(this).data("name");
        //     $("#dosenName").text(name);
            
        //     let historyContent = $("#historyContent");
        //     historyContent.html("<tr><td colspan='2' class='text-center'>Loading...</td></tr>");

        //     let form = $('<form>', {
        //         action: `/prioritas-dosen-pembimbing/dosen/history/${nip}`,
        //         method: 'GET',
        //         target: '_blank'
        //     });

        //     $('body').append(form);
        //     form.submit();
        //     form.remove();

        //     $("#historyModal").modal("show");
        // });
    });

    // Mencari dosen berdasarkan nama
    $(document).ready(function () {
        $("#searchDosen").on("input", function () {
            let searchText = $(this).val().toLowerCase().trim();

            $("#dosenList .list-group-item").each(function () {
                let dosenName = $(this).find(".dosen-name").text().trim().toLowerCase();
                if (dosenName.includes(searchText)) {
                    $(this).removeClass('d-none');
                    $(this).addClass('d-flex');
                } else {
                    $(this).removeClass('d-flex');
                    $(this).addClass('d-none');
                }
            });
        });
    });
</script>
@stop