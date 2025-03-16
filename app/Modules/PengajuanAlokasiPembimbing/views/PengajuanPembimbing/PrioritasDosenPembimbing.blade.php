@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <div class="m-3">
        <h1>Formulir Pengajuan Dosen Pembimbing</h1>
    </div>
@stop

@section('content')

    <div class="container-fluid row w-100 justify-content-start">
        <div class="card p-4 bg-light">
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="3"
                activeColor="primary" inactiveColor="secondary" :hrefs="['#', '#', '#', '#']" />
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
                                            <button class="btn btn-sm btn-secondary viewHistory" data-name="{{ $d->nama }}">
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
                    <a href={{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.pratinjau-formulir') }} class="btn btn-info ml-3">Selanjutnya</a>
                </div>
            </div>
    </div>

    <!-- Modal untuk Riwayat Topik -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Riwayat Topik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="dosenName"></p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Riwayat Topik</th>
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
            $("#historyModal").modal("show");
        });
    });
</script>
@stop