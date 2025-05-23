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

    <div id="warningMessage" class="alert alert-danger" role="alert" style="display: none;"> </div>
    <div id="successMessage" class="alert alert-success" role="alert" style="display: none;"> </div>
    
    <div class="container-fluid row w-100 justify-content-start">
        <div class="card p-4 bg-light">
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="2"
                activeColor="primary" inactiveColor="secondary" 
                :hrefs="[
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.data-kelompok'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.topik-tugas-akhir'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.prioritas-dosen-pembimbing.index'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.pratinjau-formulir.index')]"/>
        </div>
            <!-- Form Pengajuan -->
        <div class="col">
            <div class="card p-4 bg-light">
                <p class="text-secondary text-md border-bottom">Topik dan Bidang Tugas Akhir</p> 
                {{-- Form Topik TA--}}
                @php
                    $id_prodi_user = $sessionUser->id_prodi;
                @endphp
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Jenis Tugas Akhir</label>
                        @if ($id_prodi_user == 1)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_tugas_akhir" id="pengembangan" value="Pengembangan" style="accent-color: #17a2b8;">
                                <label class="form-check-label" for="pengembangan">
                                    Pengembangan
                                </label>
                            </div>
                        @elseif ($id_prodi_user == 2)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_tugas_akhir" id="penelitian" value="Penelitian" style="accent-color: #17a2b8;">
                                <label class="form-check-label" for="penelitian">
                                    Penelitian
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_tugas_akhir" id="pengembangan" value="Pengembangan" style="accent-color: #17a2b8;">
                                <label class="form-check-label" for="pengembangan">
                                    Pengembangan
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- Form Bidang TA --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Bidang Tugas Akhir</label>
                        <div class="container-fluid bg-gradient-info rounded-top">
                            <div class="container">
                                <div class="row row-cols-2 p-2">
                                    <div class="col">
                                        <p class="m-0">Daftar Bidang</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Tabel Bidang --}}
                        <div class="container-fluid bg-white border rounded-bottom bg-opacity-25 pre-scrollable mb-4">
                            <div class="container">
                                <div class="row d-flex flex-wrap pl-4 py-3">
                                    @foreach ($namaBidang as $bidang)
                                        <div class="col-md-6 d-flex align-items-center mb-2">
                                            <input type="radio" id="bidang-{{$loop->index}}" name="bidang" class="form-check-input" style="accent-color: #17a2b8;">
                                            <label for="bidang-{{$loop->index}}" class="m-0 flex-grow-1" style="word-break: break-word; white-space: normal; font-weight: normal;">
                                                {{$bidang->bidang}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>                                
                            </div>
                        </div>                                            
                    </div>
                </div>

                <!-- Tombol Simpan & Selanjutnya -->
                <div class="d-flex justify-content-between mt-3">
                    <a href={{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.data-kelompok') }} class="btn btn-info mr-3">Sebelumnya</a>
                    <button type="submit" id="saveDraft" class="btn btn-sm btn-primary" style="font-size: 15px">Simpan Draft</button>
                    <a href={{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.prioritas-dosen-pembimbing.index') }} class="btn btn-info ml-3">Selanjutnya</a>
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
@include('PengajuanAlokasiPembimbing.Helper.JS.SweetAlert')

<script>
    $(document).ready(function () {
        // Mengecek apakah mahasiswa sudah memiliki pengajuan
        $.get("{{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.checkExistingData') }}", function(response) {
                if (response.hasExistingData || response.Periode === false) {
                    // Jika sudah ada data, nonaktifkan tombol dan tampilkan pesan peringatan
                    $("#ubahData, .btn-primary[type='submit']").prop("disabled", true); // Menonaktifkan tombol
                    $("textarea[name='topik']").prop("disabled", true); // Menonaktifkan input topik tugas akhir
                    $("input[type='radio']").prop("disabled", true); // Menonaktifkan semua radio button
                    
                    if (response.Periode === false) {
                        $("#warningMessage").show(); 
                        $("#warningMessage").html("Periode pengajuan dosen pembimbing belum dibuka.");
                    }
                    else {
                        $("textarea[name='topik']").val(response.topikTugasAkhir);
                        $("input[type='radio'][name='bidang']").each(function () {
                            let label = $(this).next("label").text().trim();
                            if (label === response.bidangTugasAkhir) {
                                $(this).prop("checked", true); 
                            }
                        });
                        if (response.jenisTugasAkhir === "penelitian") {
                            $("#penelitian").prop("checked", true); 
                        } else if (response.jenisTugasAkhir === "pengembangan") {
                            $("#pengembangan").prop("checked", true); 
                        }

                        $("#successMessage").show(); 
                        $("#successMessage").html("Anda telah melakukan finalisasi formulir. Pengajuan tidak dapat dilakukan dua kali. Terima kasih.");
                    }
                    if (response.hasExistingData && response.Periode === false) {
                        $("#successMessage").show(); 
                        $("#successMessage").html("Anda telah melakukan finalisasi formulir dan periode pengisian telah berakhir. Terima kasih.");
                    }

                    
                }
            });

        // Load data dari localStorage jika ada
        loadDraftData();

        // Fungsi untuk menyimpan draft saat tombol "Simpan Draft" diklik
        $("#saveDraft").click(function () {
            if (saveDraftData()) {
                toast("success", "Draft berhasil disimpan!");
            }
        });

        // // Simpan data secara otomatis saat input berubah
        // $("textarea, input[type='radio']").on("input change", function () {
        //     saveDraftData();
        // });

        // Fungsi untuk menyimpan data ke localStorage
        function saveDraftData() {
            let topikValue = $("textarea[name='topik']").val().trim();
            let bidangChecked = $("input[type='radio'][name='bidang']:checked");
            let jenisTAChecked = $("input[type='radio'][name='jenis_tugas_akhir']:checked");

            // Validasi: topik harus diisi
            if (topikValue === "") {
                toast("error", "Topik/judul tugas akhir harus diisi.");
                return false; 
            }

            // Validasi: bidang harus dipilih
            if (bidangChecked.length === 0) {
                toast("error", "Bidang tugas akhir harus dipilih.");
                return false;
            }

            if (jenisTAChecked.length === 0) {
                toast("error", "Jenis tugas akhir harus dipilih.");
                return false;
            }

            let draftData = {
                topik: topikValue,
                bidang: bidangChecked.next("label").text().trim(),
                jenisTA: jenisTAChecked.val()
            };

            localStorage.setItem("pengajuanTopikDraft", JSON.stringify(draftData));
            return true;
        }

        // Fungsi untuk memuat draft dari localStorage
        function loadDraftData() {
            let savedData = JSON.parse(localStorage.getItem("pengajuanTopikDraft"));

            if (savedData) {
                $("textarea[name='topik']").val(savedData.topik);

                $("input[type='radio'][name='bidang']").each(function () {
                    let label = $(this).next("label").text().trim();
                    if (savedData.bidang === label) {
                        $(this).prop("checked", true);
                    }
                });

                if (savedData.jenisTA === "Penelitian") {
                    $("#penelitian").prop("checked", true);
                } else if (savedData.jenisTA === "Pengembangan") {
                    $("#pengembangan").prop("checked", true);
                }
            }
        }
    });
    
    // var id_prodi_user = {{ $id_prodi_user ?? 'null' }};
    // $(document).ready(function () {
    //     if(id_prodi_user === 1){
    //         $("#pengembangan").prop("checked", true);
    //     }
    // })
</script>
@stop
