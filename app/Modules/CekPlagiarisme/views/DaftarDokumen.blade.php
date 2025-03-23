@extends('adminlte::page')

@section('title', 'Cek Plagiarisme')

@section('content_header')
<h1 class="text-center mb-3">Cek Plagiarisme</h1>
@stop

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-box">
                <input type="text" class="form-control" id="searchInput" placeholder="Cari disini...">
            </div>
            @if(auth()->user()->role_user === 'dosen')
            <div class="form-group ml-auto align-items-right mt-3">
                <select id="kelompokSelect" class="form-control">
                </select>
            </div>
            @else(auth()->user()->role_user === 'mahasiswa')
            <!-- Button Unggah Dokumen -->
            <button class="btn btn-primary ml-3" id="uploadButton">Unggah Dokumen</button>
            @endif
        </div>
        <div class="card-body">
            <div id="jsGridPlagiarism"></div>
        </div>
    </div>
</section>
<!-- Modal untuk Upload Dokumen -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Unggah Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="uploadForm">
                    @csrf
                    <div class="form-group">
                        <label for="judul">Judul Dokumen</label>
                        <input type="text" class="form-control" id="judulDokumen" name="judul" placeholder="Masukkan judul dokumen" required>
                        <small class="text-danger d-none" id="judulError">Judul dokumen tidak boleh lebih dari 20 kata.</small>
                    </div>
                    <div class="form-group">
                        <label>Pilih Dokumen</label>
                        <div class="d-flex">
                            <input type="file" class="form-control-file" id="dokumenFile" name="dokumen" accept=".pdf, .docx" required>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-success btn-sm" id="uploadGoogleDrive">Pilih dokumen dari Google Drive</button>
                        </div>
                        <small class="text-muted">
                            Batasan Unggah <br>
                            Ukuran dokumen maksimal: 15 MB <br>
                            Jenis dokumen yang valid: PDF, DOCX
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="previewBtn">Unggah</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="role_user" content="{{ auth()->user()->role_user }}">
<meta name="prefix-url" content="{{ env('PREFIX_URL', 'sipta-dev') }}">

<script>
    var prefixUrl = $("meta[name='prefix-url']").attr("content");
    $(document).ready(function() {
        // Ambil role_user dari meta tag yang ada di halaman
        var roleUser = $("meta[name='role_user']").attr("content");

        // Ambil prefix URL dari meta tag yang ada di halaman
        var urlKota = `${prefixUrl}/api/kotas`;

        if (roleUser === 'dosen') {
            // Mengambil data kota dari API
            $.ajax({
                type: "GET",
                url: urlKota,
                dataType: "json",
                success: function(response) {
                    console.log("Data Kota:", response); // Periksa data yang diterima

                    var kelompokOptions;

                    if (response.length === 0) {
                        kelompokOptions = '<option value="">Tidak ada kelompok</option>';
                    } else {
                        kelompokOptions = '<option value="">Semua Kelompok</option>'; // Opsi default
                        // Menambahkan opsi ke dropdown
                        kelompokOptions += response.map(function(item) {
                            return `<option value="${item.id_kota}">${item.nama_kota}</option>`;
                        }).join('');
                    }

                    // Menambahkan opsi ke dropdown kelompokSelect
                    $("#kelompokSelect").append(kelompokOptions);
                },
                error: function(xhr, status, error) {
                    console.error("Gagal mengambil data kota:", status, error);
                }
            });
        } else {
            // Jika yang login bukan dosen, bisa tampilkan pesan atau tidak menjalankan AJAX sama sekali
            console.log("Akses dibatasi hanya untuk dosen");
            $("#kelompokSelect").append('<option value="">Akses tidak diizinkan</option>');
        }
    });


    $(document).ready(function() {
        // Ambil prefix URL dari meta tag yang ada di halaman
        var urlDokumen = `${prefixUrl}/api/cek-plagiarisme`;

        $.ajax({
            type: "GET",
            url: urlDokumen,
            dataType: "json",
            success: function(response) {
                console.log("Data dari API:", response);

                // Sorting berdasarkan waktu secara descending
                response.sort((a, b) => new Date(b.waktu) - new Date(a.waktu));

                response = response.map((item, index) => ({
                    id_dokumen: item.id_dokumen,
                    nomor: index + 1,
                    judul: item.judul,
                    waktu: item.waktu,
                    penulis: item.penulis,
                    presentase: item.persentase_plagiarisme + "%",
                    status: item.status,
                    komentar: getKomentar(item.review, item.id_dokumen),
                    id_kota: item.id_kota
                }));

                $("#jsGridPlagiarism").jsGrid({
                    width: "100%",
                    height: "600px",
                    sorting: true,
                    paging: true,
                    noDataContent: "Dokumen tidak ditemukan",
                    rowClick: function(args) {
                        window.location.href = prefixUrl + "/cek-plagiarisme/" + args.item.id_dokumen + "/detail-dokumen";
                    },
                    data: response,
                    fields: [{
                            name: "nomor",
                            type: "number",
                            title: "Nomor",
                            width: 50,
                            align: "center",
                            sorting: false
                        },
                        {
                            name: "judul",
                            type: "text",
                            title: "Judul",
                            width: 200,
                            align: "center"
                        },
                        {
                            name: "waktu",
                            type: "text",
                            title: "Waktu Pengecekan",
                            width: 150,
                            align: "center"
                        },
                        {
                            name: "penulis",
                            type: "text",
                            title: "Penulis",
                            width: 150,
                            align: "center"
                        },
                        {
                            name: "presentase",
                            type: "text",
                            title: "Presentase",
                            width: 100,
                            align: "center"
                        },
                        {
                            name: "status",
                            type: "html",
                            title: "Status",
                            width: 150,
                            align: "center"
                        },
                        {
                            name: "komentar",
                            type: "html",
                            title: "Komentar",
                            width: 150,
                            align: "center"
                        }
                    ]
                });

                // Filter berdasarkan kelompok (id_kota) yang dipilih
                $("#kelompokSelect").on("change", function() {
                    var selectedKota = $(this).val(); // Ambil id_kota yang dipilih
                    console.log("Kota yang dipilih:", selectedKota); // Cek nilai yang dipilih

                    var filteredData = response.filter(item => {
                        if (selectedKota) {
                            return item.id_kota == selectedKota; // Filter berdasarkan id_kota
                        }
                        return true; // Tampilkan semua data jika tidak ada pilihan
                    });

                    // Update nomor urut setelah filter
                    filteredData = filteredData.map((item, index) => ({
                        ...item,
                        nomor: index + 1
                    }));

                    // Update data di jsGrid
                    $("#jsGridPlagiarism").jsGrid("option", "data", filteredData);
                });

                $("#searchInput").on("keyup", function() {
                    var searchValue = $(this).val().toLowerCase();
                    var filteredData = response.filter(item =>
                        Object.values(item).some(value => String(value).toLowerCase().includes(searchValue))
                    );

                    // Menambahkan nomor urut setelah filter
                    filteredData = filteredData.map((item, index) => ({
                        ...item,
                        nomor: index + 1 // Reset nomor urut
                    }));

                    $("#jsGridPlagiarism").jsGrid("option", "data", filteredData);
                });
            },
            error: function(xhr, status, error) {
                console.error("Gagal mengambil data dari API:", status, error);
            }
        });
    });

    // Fungsi untuk menampilkan modal upload
    $('#uploadButton').click(function() {
        $('#uploadModal').modal('show');
    });

    // Validasi sebelum mengunggah dokumen
    $('#uploadForm').submit(function(event) {
        event.preventDefault(); // Mencegah form dikirimkan secara default

        var judul = $('#judulDokumen').val(); // Ambil judul dokumen
        var file = $('#dokumenFile')[0].files[0]; // Ambil file yang diunggah

        // Validasi input
        if (!judul || !file) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Silakan isi semua data dan pilih file sebelum mengunggah!',
            });
            return;
        }

        let jumlahKata = judul.trim().split(/\s+/).length;
        if (jumlahKata > 20) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Judul dokumen tidak boleh lebih dari 20 kata!',
            });
            return;
        }

        // Validasi ukuran file (maksimal 15 MB)
        var maxFileSize = 15 * 1024 * 1024; // 15 MB
        if (file.size > maxFileSize) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal mengunggah!',
                text: 'Ukuran file melebihi batas maksimal 15MB.',
            });
            return;
        }

        // Validasi ekstensi file (hanya PDF dan DOCX)
        var validExtensions = ['pdf', 'docx'];
        var fileExtension = file.name.split('.').pop().toLowerCase();
        if (!validExtensions.includes(fileExtension)) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal mengunggah!',
                text: 'Hanya dokumen dengan format PDF atau DOCX yang diperbolehkan.',
            });
            return;
        }

        // Menampilkan pop-up tanda berhasil upload
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Dokumen berhasil diunggah, silahkan tunggu hasil pengecekan.',
            confirmButtonText: 'Tutup',
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload(); // Reload halaman setelah klik "Tutup"
            }
        });

        // Tutup modal upload dokumen setelah unggah berhasil
        $('#uploadModal').modal('hide');
    });

    function getStatusBadge(persentase, ambangBatas) {
        if (persentase === null) {
            return '<span class="badge badge-warning">Processing</span>';
        } else if (persentase < ambangBatas) {
            return '<span class="badge badge-success">Tidak Plagiat</span>';
        } else {
            return '<span class="badge badge-danger">Plagiat</span>';
        }
    }

    function getKomentar(komentar, id) {
        if (komentar) {
            return '<span class="text-dark">Komentar diberikan</span>';
        } else {
            return '<span class="text-muted">Belum ada komentar</span>';
        }
    }
</script>
@stop