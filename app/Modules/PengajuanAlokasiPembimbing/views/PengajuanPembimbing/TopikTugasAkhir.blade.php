@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <div class="m-3">
        <h1>Formulir Pengajuan Dosen Pembimbing</h1>
    </div>
@stop

@section('content')

    {{-- <p>Data Kelompok > <a href="www">Topik Tugas Akhir</a> > <a href="www">Prioritas Dosen Pembimbing</a> > <a href="www">Pratinjau</a></p> --}}

    <div class="container-fluid row w-100 justify-content-start">
        <div class="card p-4 bg-light">
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="2"
                activeColor="primary" inactiveColor="secondary" :hrefs="['#', '#', '#', '#']" />
        </div>
            <!-- Form Pengajuan -->
        <div class="col">
            <div class="card p-4 bg-light">
                <h5 class="mb-3">Topik Tugas Akhir</h5>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Topik/Judul Tugas Akhir</label>
                        <textarea class="form-control" name="topik" rows="3" placeholder="Masukkan topik/judul"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Bidang Tugas Akhir</label>
                        <div class="container-fluid bg-gradient-info rounded-top">
                            <div class="container">
                                <div class="row row-cols-2 p-2">
                                    <div class="col">
                                        <p class="m-0">Daftar Bidang</p>
                                    </div>
                                    <div class="col text-right">
                                        <a class="m-0 text-light"> <i class="fas fa-plus"></i> Tambah Bidang</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ================== --}}
                        <div class="container-fluid bg-white border rounded-bottom bg-opacity-25 pre-scrollable mb-4">
                    
                            <div class="container text-center ">
                                <div class="row row-cols-2 p-3">
                                    @for ($i = 0; $i < 50; $i++)
                                        <div class="col">
                                            <div class="pretty p-default p-fill">
                                                <input type="checkbox" />
                                                <div class="state p-info text-left" style="min-width: 150px;">
                                                    <label>
                                                        Bidang {{ $i + 1 }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan & Selanjutnya -->
                <div class="d-flex justify-content-between mt-3">
                    <a href={{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.data-kelompok') }} class="btn btn-info ml-3">Sebelumnya</a>
                    <button type="button" id="saveDraft" class="btn btn-sm btn-primary" style="font-size: 15px">Simpan Draft</button>
                    <a href={{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.prioritas-dosen-pembimbing') }} class="btn btn-info ml-3">Selanjutnya</a>
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
<script>
    $(document).ready(function () {
        // Load data dari localStorage jika ada
        loadDraftData();

        // Fungsi untuk menyimpan draft saat tombol "Simpan Draft" diklik
        $("#saveDraft").click(function () {
            saveDraftData();
            alert("Draft berhasil disimpan!");
        });

        // Simpan data secara otomatis saat input berubah
        $("textarea, input[type='checkbox']").on("input change", function () {
            saveDraftData();
        });

        // Fungsi untuk menyimpan data ke localStorage
        function saveDraftData() {
            let draftData = {
                topik: $("textarea[name='topik']").val(),
                bidang: []
            };

            // Simpan checkbox yang dicentang
            $("input[type='checkbox']:checked").each(function () {
                draftData.bidang.push($(this).next("div").text().trim());
            });

            localStorage.setItem("pengajuanTopikDraft", JSON.stringify(draftData));
        }

        // Fungsi untuk memuat draft dari localStorage
        function loadDraftData() {
            let savedData = JSON.parse(localStorage.getItem("pengajuanTopikDraft"));

            if (savedData) {
                $("textarea[name='topik']").val(savedData.topik);

                $("input[type='checkbox']").each(function () {
                    let label = $(this).next("div").text().trim();
                    if (savedData.bidang.includes(label)) {
                        $(this).prop("checked", true);
                    }
                });
            }
        }
    });
</script>
@stop
