@extends('adminlte::page')

@section('title', 'Formulir Pengajuan Dosen Pembimbing')

@section('content_header')
    {{-- <div class="m-3">
        <h1>Formulir Pengajuan Dosen Pembimbing</h1>
    </div> --}}
    <h1 class="mb-3">Formulir Pengajuan Dosen Pembimbing</h1> 
 
    <div> 
        @component('KelolaPenilaianTA.views.components.breadcrumb', [ 
            'links' => [ 
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'], 
                ['url' => '', 'label' => 'Formulir Pengajuan Dosen Pembimbing']
            ] 
        ]) 
        @endcomponent   
    </div>
@stop

@section('content')

    <div class="container-fluid row w-100 justify-content-start">
        <div class="card p-4 bg-light">
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="3"
                activeColor="primary" inactiveColor="secondary" 
                :hrefs="[
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.data-kelompok'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.topik-tugas-akhir'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.prioritas-dosen-pembimbing.index'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.pratinjau-formulir.index')]"/>
        </div> 

        <div class="col">
            <div class="card p-4 bg-light">
                <p class="text-secondary text-md border-bottom">Prioritas Dosen Pembimbing</p> 
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">List Dosen Pembimbing</label>

                        <input type="text" id="searchDosen" class="form-control mb-2" placeholder="Cari nama dosen...">

                        <div class="border p-2" style="max-height: 70vh; overflow-y: auto;">
                            <ul id="dosenList" class="list-group">
                                @foreach ($listDosen as $d)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="dosen-name">{{ $d->nama }}</span>
                                        <div class="button-container d-flex justify-content-end">
                                            <button class="btn btn-sm btn-secondary viewHistory" type="button" data-toggle="modal" data-target="#historyModal{{$d->nip}}">
                                                <i class="fas fa-file-alt"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary addDosen" data-name="{{ $d->nama }}">+</button>
                                        </div>
                                    </li>

                                    {{-- Modal Riwayat Ketertarikan Bidang --}}
                                    <x-adminlte-modal id="historyModal{{ $d->nip }}" title="Riwayat Ketertarikan Bidang" theme="blue" size="lg" static-backdrop scrollable>

                                        <div class="px-3"> 
                                            <p id="dosenName" class="mb-3">
                                                {{ $d->nama }}
                                            </p>
                                    
                                            <table id="table-history-{{ $d->nip }}" class="table table-striped" width="100%">
                                                <thead class="sticky-header">
                                                    <tr class="bg-dark text-white">
                                                        <th>List Ketertarikan Bidang</th>
                                                    </tr>
                                                </thead>
                                    
                                                <tbody>
                                                    @forelse ($d->history as $bidang)
                                                        <tr>
                                                            <td>{{ $bidang->bidang }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="1" class="text-center">Tidak ada data tersedia</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                    
                                            <x-slot name="footerSlot">
                                                <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal"/>
                                            </x-slot>
                                        </div>
                                    
                                    </x-adminlte-modal>                                    
                                    
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
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />

    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}} 
    <link rel="stylesheet" 
    href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css"> 

    <style>
        .button-container {
            display: flex;
            justify-content: flex-end;
            gap: 5px; /* Space between the buttons */
        }
    </style>
@stop

@section ('js')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> 
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> 
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