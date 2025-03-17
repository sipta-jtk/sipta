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
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="4"
                activeColor="primary" inactiveColor="secondary" :hrefs="['data-kelompok', 'topik-tugas-akhir', 'prioritas-dosen-pembimbing', 'pratinjau-formulir']" />
        </div>

        <div class="col">
            <div class="card p-4 bg-light">
                <h5 class="mb-3 fw-bold">Data Mahasiswa</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" style="font-weight: normal;">Anggota 1</label>
                        <p style="font-weight: bold;">Nama : {{ $sessionUser['nama'] }}</p>
                        <p style="font-weight: bold;">NIM : {{ $sessionUser['nim'] }}</p>
                    </div>

                    @foreach ($dataAnggota as $index => $anggota)
                        <div class="col-md-4">
                            <label class="form-label" style="font-weight: normal;">Anggota {{ $index + 2 }}</label>
                            <p style="font-weight: bold;">Nama : {{ $anggota->nama }}</p>
                            <p style="font-weight: bold;">NIM : {{ $anggota->nim }}</p>
                        </div>
                    @endforeach
                </div>

                <h5 class="mb-3">Topik Tugas Akhir</h5>
                <p id="preview-topik" style="font-weight: bold;">Memuat...</p>

                <h5 class="mt-3 mb-3">Bidang Tugas Akhir</h5>
                <ol id="preview-bidang" style="font-weight: bold;">
                    <li>Memuat...</li>
                </ol>

                <h5 class="mt-3 mb-3">Prioritas Dosen Pembimbing</h5>
                <ul id="preview-prioritas" style="font-weight: bold;">
                    <li>Memuat...</li>
                </ul>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.prioritas-dosen-pembimbing.index') }}"
                        class="btn btn-info">Sebelumnya</a>
                    <button type="submit" class="btn btn-sm btn-primary" style="font-size: 16px">Finalisasi Data</button>
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
            // Menampilkan data topik tugas akhir yang sudah disimpan di localStorage
            let savedData = JSON.parse(localStorage.getItem("pengajuanTopikDraft"));

            if (savedData) {
                document.getElementById("preview-topik").textContent = savedData.topik || "Tidak ada data";

                let bidangList = document.getElementById("preview-bidang");
                bidangList.innerHTML = ""; // Kosongkan daftar sebelum ditambahkan

                if (savedData.bidang.length > 0) {
                    savedData.bidang.forEach(function(bidang) {
                        let li = document.createElement("li");
                        li.textContent = bidang;
                        bidangList.appendChild(li);
                    });
                } else {
                    bidangList.innerHTML = "<li>Tidak ada bidang yang dipilih</li>";
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
                            <li>
                                ${dosen.priority}. ${dosen.name}
                            </li>
                        `;
                        // Tambahkan item baru ke dalam daftar prioritas
                        prioritasList.innerHTML += listItem;
                    }
                });
            } else {
                document.getElementById("preview-prioritas").innerHTML =
                    "<li>Tidak ada prioritas dosen yang dipilih.</li>";
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
                        prioritas: prioritasDosen
                    };

                    // Kirim data ke backend dengan fetch
                    fetch('{{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.pratinjau-formulir.finalisasi') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify(dataToSend)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message === 'Data berhasil disimpan') {
                                alert("Data berhasil disimpan!");
                                // Redirect atau lakukan sesuatu setelah berhasil
                            } else {
                                alert("Terjadi kesalahan saat menyimpan data.");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert("Terjadi kesalahan saat menyimpan data.");
                        });
                } else {
                    alert("Data tidak lengkap.");
                }
            });
        });


        // document.addEventListener("DOMContentLoaded", function () {
        //     // Load data from localStorage if available
        //     let savedData = JSON.parse(localStorage.getItem("pengajuanTopikDraft"));

        //     if (savedData) {
        //         // Populate preview for topik
        //         document.getElementById("preview-topik").textContent = savedData.topik || "Tidak ada data";

        //         // Populate bidang preview
        //         let bidangList = document.getElementById("preview-bidang");
        //         bidangList.innerHTML = ""; // Clear the list before adding data

        //         if (savedData.bidang.length > 0) {
        //             savedData.bidang.forEach(function (bidang) {
        //                 let li = document.createElement("li");
        //                 li.textContent = bidang;
        //                 bidangList.appendChild(li);
        //             });
        //         } else {
        //             bidangList.innerHTML = "<li>Tidak ada bidang yang dipilih</li>";
        //         }

        //         // Populate hidden bidang input with the selected bidang data
        //         document.getElementById("bidang").value = JSON.stringify(savedData.bidang);
        //     }

        //     // Load prioritasDosen from localStorage
        //     let prioritasDosen = JSON.parse(localStorage.getItem("prioritasDosen"));
        //     if (prioritasDosen) {
        //         // Populate prioritas dosen preview
        //         let prioritasList = document.getElementById("preview-prioritas");
        //         prioritasList.innerHTML = ""; // Clear the list before adding data

        //         prioritasDosen.forEach(function (dosen, index) {
        //             if (index < 5) { // Only show a maximum of 5 dosen
        //                 let listItem = `
    //                     <li>
    //                         ${dosen.priority}. ${dosen.name}
    //                     </li>
    //                 `;
        //                 prioritasList.innerHTML += listItem;
        //             }
        //         });

        //         // Populate hidden prioritas_dosen input with the selected dosen data
        //         document.getElementById("prioritas_dosen").value = JSON.stringify(prioritasDosen);
        //     } else {
        //         document.getElementById("preview-prioritas").innerHTML = "<li>Tidak ada prioritas dosen yang dipilih.</li>";
        //     }

        //     // Handle form submission (finalization)
        //     const submitButton = document.querySelector('button[type="submit"]');
        //     submitButton.addEventListener('click', function(event) {
        //         event.preventDefault();

        //         // Ensure that all hidden inputs are updated before form submission
        //         let topik = document.getElementById("topik");
        //         let bidang = document.getElementById("bidang");
        //         let prioritas_dosen = document.getElementById("prioritas_dosen");

        //         // Ensure values are taken from localStorage before form submission
        //         let savedData = JSON.parse(localStorage.getItem("pengajuanTopikDraft"));
        //         let prioritasDosen = JSON.parse(localStorage.getItem("prioritasDosen"));

        //         topik.value = savedData ? savedData.topik : '';
        //         bidang.value = savedData ? JSON.stringify(savedData.bidang) : '';
        //         prioritas_dosen.value = prioritasDosen ? JSON.stringify(prioritasDosen) : '';

        //         FireSweetAlert('question', 'Lakukan Finalisasi Data?', 'Pastikan data yang ada isi sudah benar!', 'Submit', 'Kembali', '#3085d6', '#d33', true, true).then((result) => {
        //             if (result.isConfirmed) {
        //                 event.target.closest('form').submit(); // Submit the form after confirmation
        //             }
        //         });
        //     });
        // });
    </script>

@stop
