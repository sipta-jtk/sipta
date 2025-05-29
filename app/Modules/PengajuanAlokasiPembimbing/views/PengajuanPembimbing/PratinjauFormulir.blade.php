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
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="4"
                activeColor="primary" inactiveColor="secondary" 
                :hrefs="[
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.data-kelompok'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.topik-tugas-akhir'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.prioritas-dosen-pembimbing.index'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.pratinjau-formulir.index')]"/>
        </div>

        <div class="col">
            <div class="card p-4 bg-light">
                <p class="text-secondary text-md border-bottom">Data Mahasiswa</p> 
                <div class="row mb-2">
                    <div class="col-md-4">
                        <p class="text-secondary text-md">Anggota 1</p>
                        <table class="table table-borderless">
                            <tr>
                                <th class="p-0" style="font-weight: bold;">Nama</th>
                                <td class="p-0" >:</td>
                                <td class="pl-2 pt-0 pr-0 pb-0" style="font-weight: bold;">{{ $sessionUser->nama }}</td>
                            </tr>
                                <th  class="p-0" style="font-weight: bold;">NIM</th>
                                <td class="p-0" >:</td>
                                <td class="pl-2 pt-0 pr-0 pb-0" style="font-weight: bold;">{{ $sessionUser->nim }}</td>
                            </tr>
                        </table>                        
                    </div>

                    @foreach ($dataAnggota as $index => $anggota)
                        <div class="col-md-4">
                            <p class="text-secondary text-md">Anggota {{ $index + 2 }}</p>
                            <table class="table table-borderless">
                                <tr>
                                    <th class="p-0" style="font-weight: bold;">Nama</th>
                                    <td class="p-0" >:</td>
                                    <td class="pl-2 pt-0 pr-0 pb-0" style="font-weight: bold;">{{ $anggota->nama }}</td>
                                </tr>
                                    <th  class="p-0" style="font-weight: bold;">NIM</th>
                                    <td class="p-0" >:</td>
                                    <td class="pl-2 pt-0 pr-0 pb-0" style="font-weight: bold;">{{ $anggota->nim }}</td>
                                </tr>
                            </table>
                        </div>
                    @endforeach
                </div>

                <p class="text-secondary text-md border-bottom">Topik Tugas Akhir</p> 
                <p id="preview-topik" style="font-weight: bold;">
                    <span style='color: red;'>Topik tugas akhir belum diisi</span>
                </p>

                <p class="text-secondary text-md border-bottom">Jenis Tugas Akhir</p> 
                <p id="preview-jenis" style="font-weight: bold;">
                    <span style='color: red;'>Jenis tugas akhir belum dipilih</span>
                </p>

                <p class="text-secondary text-md border-bottom">Bidang Tugas Akhir</p> 
                <p id="preview-bidang" style="font-weight: bold;">
                    <span style='color: red;'>Bidang tugas akhir belum dipilih</span>
                </p>

                <p class="text-secondary text-md border-bottom">Prioritas Dosen Pembimbing</p> 
                <p id="preview-prioritas" style="font-weight: bold;">
                    <span style='color: red;'>Prioritas dosen belum dipilih</span>
                </p>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.prioritas-dosen-pembimbing.index') }}"
                        class="btn btn-info mr-3">Sebelumnya</a>
                    <button type="submit" id="finalisasiData" class="btn btn-sm btn-primary" style="font-size: 16px">Finalisasi Data</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
    <style>
        #preview-bidang {
            list-style-position: inside;
            padding-left: 0;
        }

        #preview-prioritas {
            list-style-type: none;
            padding-left: 0;
        }
    </style>

@stop

@section('js')
    @include('PengajuanAlokasiPembimbing.Helper.JS.SweetAlert')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mengecek apakah mahasiswa sudah memiliki pengajuan
            $.get("{{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.checkExistingData') }}", function(response) {
                if (response.hasExistingData || response.Periode === false) {
                    // Jika sudah ada data, nonaktifkan tombol dan tampilkan pesan peringatan
                    $("#ubahData, .btn-primary[type='submit']").prop("disabled", true); // Menonaktifkan tombol
                    
                    if (response.Periode === false) {
                        $("#warningMessage").show(); 
                        $("#warningMessage").html("Periode pengajuan dosen pembimbing belum dibuka.");
                    }
                    else {
                        document.getElementById("preview-topik").textContent = response.topikTugasAkhir;
                        document.getElementById("preview-bidang").textContent = response.bidangTugasAkhir;
                        let jenisTugasAkhir = response.jenisTugasAkhir;
                        if (jenisTugasAkhir === "penelitian") {
                            document.getElementById("preview-jenis").textContent = "Penelitian";
                        } else if (jenisTugasAkhir === "pengembangan") {
                            document.getElementById("preview-jenis").textContent = "Pengembangan";
                        }

                        let prioritasList = document.getElementById("preview-prioritas");
                        prioritasList.innerHTML = ""; // Kosongkan daftar sebelum ditambahkan

                        if (response.prioritasDosen && response.prioritasDosen.length > 0) {
                            response.prioritasDosen.forEach(function(dosen, index) {
                                if (index < 5) { // Maksimal 5 prioritas dosen
                                    let listItem = `
                                        <p>
                                            ${dosen.priority}. ${dosen.name}
                                        </p>
                                    `;
                                    prioritasList.innerHTML += listItem;
                                }
                            });
                        } else {
                            prioritasList.innerHTML = "<span style='color: red;'>Prioritas dosen belum dipilih</span>";
                        }

                        $("#successMessage").show(); 
                        $("#successMessage").html("Anda telah melakukan finalisasi formulir. Pengajuan tidak dapat dilakukan dua kali. Terima kasih.");
                        return;
                    }
                    if (response.hasExistingData && response.Periode === false) {
                        $("#successMessage").show(); 
                        $("#successMessage").html("Anda telah melakukan finalisasi formulir dan periode pengisian telah berakhir. Terima kasih.");
                    }
                }
            });

            // Menampilkan topik tugas akhir dan bidang yang sudah disimpan di localStorage
            let savedData = JSON.parse(localStorage.getItem("pengajuanTopikDraft"));

            if (savedData) {
                document.getElementById("preview-topik").textContent = savedData.topik || "<span style='color: red;'>Topik belum diisi</span>";
                document.getElementById("preview-jenis").textContent = savedData.jenisTA || "<span style='color: red;'>Jenis tugas akhir belum dipilih</span>";

                let namaBidang = document.getElementById("preview-bidang");
                namaBidang.textContent = ""; // Kosongkan sebelumnya

                if (savedData.bidang) {
                    // Tampilkan bidang yang dipilih sebagai teks biasa
                    namaBidang.textContent = savedData.bidang;
                } else {
                    namaBidang.innerHTML = "<span style='color: red;'>Tidak ada bidang yang dipilih</span>";
                }
            }

            // Menampilkan prioritas dosen yang sudah disimpan di localStorage
            let prioritasDosen = JSON.parse(localStorage.getItem("prioritasDosen"));
            if (prioritasDosen) {
                let prioritasList = document.getElementById("preview-prioritas");
                prioritasList.innerHTML = ""; // Kosongkan daftar sebelum ditambahkan

                // Menampilkan prioritas dosen yang disimpan
                prioritasDosen.forEach(function(dosen, index) {
                    if (index < 5) { // Maksimal 5 prioritas dosen
                        // Memastikan hanya satu angka urutan yang ditambahkan
                        let listItem = `
                            <p>
                                ${dosen.priority}. ${dosen.name}
                            </p>
                        `;
                        // Tambahkan item baru ke dalam daftar prioritas
                        prioritasList.innerHTML += listItem;
                    }
                });
            } else {
                document.getElementById("preview-prioritas").innerHTML =
                    "<span style='color: red;'>Prioritas dosen belum dipilih</span>";
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Ketika tombol Finalisasi Data diklik
            document.querySelector(".btn-primary[type='submit']").addEventListener('click', function() {
                // Ambil data dari localStorage
                let savedData = JSON.parse(localStorage.getItem("pengajuanTopikDraft"));
                let prioritasDosen = JSON.parse(localStorage.getItem("prioritasDosen"));

                // Pastikan data ada sebelum mengirim
                if (savedData && prioritasDosen) {
                    let dataToSend = {
                        topik: savedData.topik,
                        bidang: savedData.bidang,
                        jenisTA: savedData.jenisTA,
                        prioritas: JSON.stringify(prioritasDosen)
                    };
                    console.log(dataToSend);

                    FireSweetAlert('warning', 'Lakukan Finalisasi Data?', 'Pengajuan hanya dapat dilakukan satu kali. Pastikan data yang Anda isi sudah benar!', 'Submit', 'Kembali', '#3085d6', '#d33', true, true, (confirmed) => {
                    if (confirmed) { 

                    // Kirim data ke backend dengan fetch
                    // Buat form secara dinamis
                    let form = document.createElement('form');
                    form.action = '{{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.pratinjau-formulir.finalisasi') }}';
                    form.method = 'POST';

                    // Tambahkan CSRF token ke dalam form
                    let csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrfToken);

                    // Tambahkan data topik ke dalam form
                    let topikInput = document.createElement('input');
                    topikInput.type = 'hidden';
                    topikInput.name = 'topik';
                    topikInput.value = savedData.topik;
                    form.appendChild(topikInput);

                    // Tambahkan data jenis tugas akhir ke dalam form
                    let jenisTAInput = document.createElement('input');
                    jenisTAInput.type = 'hidden';
                    jenisTAInput.name = 'jenisTA';
                    jenisTAInput.value = savedData.jenisTA;
                    form.appendChild(jenisTAInput);
                    
                    // Tambahkan data bidang ke dalam form
                    let bidangInput = document.createElement('input');
                    bidangInput.type = 'hidden';
                    bidangInput.name = 'bidang';
                    bidangInput.value = savedData.bidang;
                    form.appendChild(bidangInput);

                    // Tambahkan data prioritas ke dalam form
                    let prioritasInput = document.createElement('input');
                    prioritasInput.type = 'hidden';
                    prioritasInput.name = 'prioritas';
                    prioritasInput.value = JSON.stringify(prioritasDosen);
                    form.appendChild(prioritasInput);

                    // Tambahkan form ke dalam body dan submit
                    document.body.appendChild(form);
                    form.submit();
                    }
                });
                } else {
                    toast("error", "Data tidak lengkap.");
                }
            });
        });

    </script>

@stop
