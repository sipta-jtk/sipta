@extends('adminlte::page')

@section('title', 'Cek Plagiarisme')

@section('content_header')
<h1 class="mb-3">Cek Plagiarisme</h1>
<div>
    @php
    $prefix = rtrim(config('adminlte.dashboard_url', 'sipta'), '/');
    @endphp

    @component('KelolaPenilaianTA.views.components.breadcrumb', [
    'links' => [
    ['url' => url('/' . $prefix), 'label' => 'Beranda'],
    ['url' => '', 'label' => 'Cek Plagiarisme']
    ]
    ])
    @endcomponent
</div>
@stop

@section('content')
<section class="content">
    <div class="card">
        <div class="d-flex justify-content-between px-3 pt-3">
            @if(auth()->user()->role_user === 'dosen')
            <button class="btn btn-primary btn-md" type="button"
                data-toggle="collapse" data-target="#filterMenu">
                <i class="fas fa-filter"></i>
            </button>
            @else(auth()->user()->role_user === 'mahasiswa')
            <div class="form-group ml-auto align-items-right mt-3">
                <!-- Button Unggah Dokumen -->
                <button class="btn btn-primary ml-3 btn-md" id="uploadButton"> + Unggah Dokumen</button>
            </div>
            @endif
        </div>
        <div class="collapse" id="filterMenu">
            <div class="card mx-3 mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter mr-2"></i>Filter Data
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group text-secondary">
                                <label>
                                    <i class="fas fa-user mr-1"> KoTa</i> 
                                </label>

                                <select id="kelompokSelect" class="form-control select2bs4"
                                    style="width: 100%;">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-primary">
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="table" class="table table-striped" width="100%">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th style="width:5%;">Id_dokumen</th>
                        <th style="width:5%;">Nomor</th>
                        <th style="width:30%;">Judul</th>
                        <th style="width:15%;">Waktu Pengecekan</th>
                        <th style="width:20%;">Penulis</th>
                        <th style="width:10%;">Presentase</th>
                        <th style="width:10%;">Status</th>
                        <th style="width:10%;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal untuk Upload Dokumen -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadModalLabel">Unggah Dokumen</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <form id="uploadForm">
                    @csrf

                    <!-- Judul Dokumen -->
                    <div class="form-group">
                        <label for="judulDokumen">Judul Dokumen</label>
                        <input type="text" class="form-control" id="judulDokumen" name="judul" placeholder="Masukkan judul dokumen" required>
                        <small class="text-danger d-none" id="judulError">Judul dokumen tidak boleh lebih dari 20 kata.</small>
                    </div>

                    <!-- Pilih Dokumen -->
                    <div class="form-group">
                        <label>Pilih Dokumen</label>
                        <div class="d-flex align-items-center">
                            <label class="btn btn-outline-secondary btn-sm mb-0">
                                Pilih dokumen dari komputer ini
                                <input type="file" id="dokumenFile" name="dokumen" accept=".pdf, .docx" style="display: none;" required>
                            </label>
                            <span id="namaFileTerpilih" class="ml-2">Tidak ada file</span>
                        </div>
                    </div>

                    <!-- Pilih dari Google Drive -->
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary btn-sm" id="uploadGoogleDrive">Pilih dokumen dari Google Drive</button>
                    </div>

                    <!-- Info Batasan Unggah -->
                    <div class="form-group">
                        <small class="text-muted">
                            Batasan Unggah <br>
                            Ukuran dokumen maksimal: 15 MB <br>
                            Jenis dokumen yang valid: PDF, DOCX
                        </small>
                    </div>

                </form> <!-- Penutup form di dalam modal-body -->
            </div> <!-- Penutup modal-body -->

            <!-- Footer -->
            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn btn-danger" id="batalUploadBtn" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="openConfirmBtn">Lanjutkan</button>
            </div>

        </div> <!-- Penutup modal-content -->
    </div> <!-- Penutup modal-dialog -->
</div> <!-- Penutup modal -->

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Dokumen</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <p><strong>Penulis:</strong> <span>{{ auth()->user()->nama }}</span></p>
                        <p><strong>Judul Dokumen:</strong> <span id="judulPreview"></span></p>
                        <p><strong>Nama File:</strong> <span id="namaFilePreview"></span></p>
                        <p><strong>Ukuran File:</strong> <span id="ukuranFilePreview"></span></p>
                        <p><strong>Jumlah Halaman:</strong> <span id="jumlahHalamanPreview"></span></p>
                        <p><strong>Jumlah Kata:</strong> <span id="jumlahKataPreview"></span></p>
                    </div>
                    <div class="col-md-5 text-center">
                        <canvas id="thumbnailCanvas" style="max-width:100%; border:1px solid #ddd;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn btn-danger" id="backToUploadBtn">Kembali</button>
                <button type="button" class="btn btn-success" id="finalUploadBtn">Unggah</button>
            </div>

        </div>
    </div>
</div>


@stop

@section('css')
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
<link rel="stylesheet"
    href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">

<style>
    #table tbody tr {
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #table tbody tr:hover {
        background-color:rgb(200, 200, 200);
    }
</style>
@stop

@section('js')
<style>
    .modal-body {
        overflow-y: auto;
        max-height: calc(100vh - 200px);
    }
</style>

<script
    src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script
    src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script src="https://apis.google.com/js/api.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="role_user" content="{{ auth()->user()->role_user }}">
<meta name="prefix-url" content="{{ env('PREFIX_URL', 'sipta-dev') }}">

<script>
    // Google Picker Setup
    var clientId = '22453178479-2lelvpruokcgqqat527ce5sbk9glld4f.apps.googleusercontent.com';
    var developerKey = 'AIzaSyCfPOTwZ8_N2Nzpfagl-JszIBkrN0xfYuk';
    var scope = ['https://www.googleapis.com/auth/drive.file'];
    var pickerApiLoaded = false;
    var oauthToken = '';

    function onApiLoad() {
        gapi.load('auth', {
            'callback': onAuthApiLoad
        });
        gapi.load('picker', {
            'callback': onPickerApiLoad
        });
    }

    function onAuthApiLoad() {
        gapi.auth.authorize({
            client_id: clientId,
            scope: scope,
            immediate: false
        }, handleAuthResult);
    }

    function onPickerApiLoad() {
        pickerApiLoaded = true;
    }

    function handleAuthResult(authResult) {
        if (authResult && !authResult.error) {
            oauthToken = authResult.access_token;
            createPicker();
        }
    }

    function createPicker() {
        if (pickerApiLoaded && oauthToken) {
            var picker = new google.picker.PickerBuilder()
                .enableFeature(google.picker.Feature.NAV_HIDDEN)
                .setOAuthToken(oauthToken)
                .addView(google.picker.ViewId.DOCS)
                .setDeveloperKey(developerKey)
                .setCallback(pickerCallback)
                .build();
            picker.setVisible(true);
        }
    }

    function pickerCallback(data) {
        if (data.action === google.picker.Action.PICKED) {
            var fileName = data.docs[0].name;
            $('#namaFileTerpilih').text(fileName);
        }
    }


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
                        kelompokOptions = '<option value="">Tidak ada KoTa</option>';
                    } else {
                        kelompokOptions = '<option value="">Semua KoTa</option>'; // Opsi default
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
            $("#kelompokSelect").append('<option value="">Akses tidak diizinkan</option>');
        }
    });


    $(document).ready(function() {
        // Ambil prefix URL dari meta tag yang ada di halaman
        var urlDokumen = `${prefixUrl}/api/cek-plagiarisme`;

        var table = $('#table').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Dokumen tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada dokumen tersedia",
                infoFiltered: "(difilter dari total _MAX_ data)",
                paginate: {
                    first: "<<",
                    last: ">>",
                    next: ">",
                    previous: "<"
                }
            },
            columnDefs: [{
                targets: [0],
                visible: false,
                searchable: false
            }],
            responsive: true,
            autoWidth: false
        });

        $('#table tbody').on('click', 'tr', function() {
            var data = table.row(this).data();
            if (data) {
                var idDokumen = data[0]; // ambil id_dokumen dari kolom tersembunyi
                window.location.href = prefixUrl + "/cek-plagiarisme/" + idDokumen + "/detail-dokumen";
            }
        });


        $.ajax({
            type: "GET",
            url: urlDokumen,
            dataType: "json",
            success: function(response) {

                // Clear dulu sebelum isi
                table.clear();

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
                    catatan: getCatatan(item.review, item.id_dokumen),
                    id_kota: item.id_kota
                }));

                // Tambahkan ke tabel
                response.forEach(function(item) {
                    table.row.add([
                        item.id_dokumen,
                        item.nomor,
                        item.judul,
                        item.waktu,
                        item.penulis,
                        item.presentase,
                        item.status,
                        item.catatan,
                        item.id_kota
                    ]);
                });

                // Draw ulang tabel
                table.draw();

                // Fungsi untuk memperbarui jsGrid setelah filter
                function updateJsGrid(filteredData) {
                    // Update nomor urut setelah filter
                    filteredData = filteredData.map((item, index) => ({
                        ...item,
                        nomor: index + 1 // Reset nomor urut
                    }));

                    // Update data di jsGrid
                    $("#jsGridPlagiarism").jsGrid("option", "data", filteredData);
                }

                // Filter berdasarkan kelompok (id_kota) yang dipilih
                $("#kelompokSelect").on("change", function() {
                    var selectedKota = $(this).val(); // Ambil id_kota yang dipilih

                    // Filter berdasarkan id_kota dan search input
                    filterData(selectedKota, $("#searchInput").val());
                });

                // Filter berdasarkan pencarian (search input)
                $("#searchInput").on("keyup", function() {
                    var searchValue = $(this).val().toLowerCase();

                    // Filter berdasarkan id_kota yang dipilih dan search input
                    filterData($("#kelompokSelect").val(), searchValue);
                });

                // Fungsi untuk melakukan filter berdasarkan id_kota dan search
                function filterData(selectedKota, searchValue) {
                    var filteredData = response.filter(item => {
                        // Filter berdasarkan id_kota
                        var kotaFilter = selectedKota ? item.id_kota == selectedKota : true;

                        // Filter berdasarkan pencarian (search)
                        var searchFilter = Object.values(item).some(value =>
                            String(value).toLowerCase().includes(searchValue)
                        );

                        // Kembalikan true jika data memenuhi kedua kondisi (id_kota dan search)
                        return kotaFilter && searchFilter;
                    });

                    // Update data di jsGrid
                    updateJsGrid(filteredData);
                }
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

    // Google Drive Picker
    $('#uploadGoogleDrive').click(function() {
        onApiLoad();
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

    function getCatatan(catatan, id) {
        if (catatan) {
            return '<span class="text-dark">Catatan diberikan</span>';
        } else {
            return '<span class="text-muted">Belum ada Catatan</span>';
        }
    }

    function resetUploadForm() {
        $('#judulDokumen').val('');
        $('#dokumenFile').val('');
        $('#namaFileTerpilih').text('Tidak ada file');
        $('#judulCounter').text('0/20 kata');
    }


    // Validasi real-time + counter + enable/disable tombol Unggah
    $('#judulDokumen').on('input', function() {
        var judul = $(this).val().trim();
        var jumlahKata = judul.length > 0 ? judul.split(/\s+/).length : 0;

        // Update counter
        if ($('#judulCounter').length === 0) {
            $('#judulDokumen').after('<small id="judulCounter" class="form-text text-muted mt-1"></small>');
        }
        $('#judulCounter').text(jumlahKata + '/20 kata');

        // Validasi jumlah kata
        if (jumlahKata > 20) {
            $('#judulError').removeClass('d-none'); // Tampilkan pesan error
            $('#judulCounter').addClass('text-danger').removeClass('text-muted'); // Counter jadi merah
            $('#judulDokumen').addClass('is-invalid'); // Input merah
            $('#openConfirmBtn').prop('disabled', true);
        } else {
            $('#judulError').addClass('d-none'); // Sembunyikan pesan error
            $('#judulCounter').removeClass('text-danger').addClass('text-muted'); // Counter normal
            $('#judulDokumen').removeClass('is-invalid'); // Input normal
            $('#openConfirmBtn').prop('disabled', false);
        }
    });

    $('#dokumenFile').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $('#namaFileTerpilih').text(fileName ? fileName : 'Tidak ada file');
    });

    let pdfFile, pdfDoc;

    // Saat klik "Unggah" pertama
    $('#openConfirmBtn').click(function() {
        const fileInput = $('#dokumenFile')[0].files[0];
        const judulInput = $('#judulDokumen').val().trim();

        if (!fileInput || !judulInput) {
            Swal.fire('Peringatan', 'Silakan isi judul, dan pilih dokumen.', 'warning');
            return;
        }

        // validasi ukuran file
        var maxFileSize = 15 * 1024 * 1024; // 15 MB
        if (fileInput.size > maxFileSize) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal mengunggah!',
                text: 'Ukuran dokumen melebihi batas maksimal 15MB.',
            });
            return;
        }

        if (fileInput.type !== 'application/pdf') {
            Swal.fire('Peringatan', 'Hanya file PDF yang diperbolehkan.', 'warning');
            return;
        }

        pdfFile = fileInput;

        const reader = new FileReader();
        reader.onload = async function(e) {
            const typedarray = new Uint8Array(e.target.result);
            pdfDoc = await pdfjsLib.getDocument(typedarray).promise;




            let kataTotal = await countWords(pdfDoc);
            $('#jumlahKataPreview').val(kataTotal);

            // Isi field preview
            $('#judulPreview').text(judulInput);
            $('#namaFilePreview').text(fileInput.name);
            $('#ukuranFilePreview').text((fileInput.size / (1024 * 1024)).toFixed(2) + ' MB');
            $('#jumlahHalamanPreview').text(pdfDoc.numPages);
            $('#jumlahKataPreview').text(kataTotal);

            renderThumbnail(pdfDoc);

            // Munculkan modal konfirmasi
            $('#uploadModal').modal('hide'); // Sembunyikan modal upload
            $('#confirmModal').modal('show'); // Tampilkan modal konfirmasi
        };
        reader.readAsArrayBuffer(fileInput);
    });


    // Render thumbnail
    function renderThumbnail(pdfDoc) {
        pdfDoc.getPage(1).then(function(page) {
            const scale = 1.5;
            const viewport = page.getViewport({
                scale: scale
            });
            const canvas = document.getElementById('thumbnailCanvas');
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext);
        });
    }

    // Hitung jumlah kata
    async function countWords(pdfDoc) {
        let totalText = '';
        for (let i = 1; i <= pdfDoc.numPages; i++) {
            let page = await pdfDoc.getPage(i);
            let textContent = await page.getTextContent();
            let strings = textContent.items.map(item => item.str);
            totalText += strings.join(' ');
        }
        return totalText.trim().split(/\s+/).length;
    }

    // Final unggah ke server
    $('#finalUploadBtn').click(function() {
        let formData = new FormData();
        formData.append('judul', $('#judulDokumen').val());
        formData.append('dokumen', pdfFile);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        Swal.fire({
            title: 'Mengunggah...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `${prefixUrl}/cekplagiarisme/process`,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire('Sukses!', 'Dokumen berhasil diunggah.', 'success').then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire('Gagal', 'Gagal mengunggah dokumen.', 'error');
            }
        });
    });

    $('#backToUploadBtn').click(function() {
        $('#confirmModal').modal('hide'); // Tutup modal konfirmasi
        $('#uploadModal').modal('show'); // Balik ke modal upload
    });

    // Button Batal di Modal Upload
    $('#batalUploadBtn').click(function() {
        resetUploadForm();
    });

    // Button Close Modal Upload (ikon X)
    $('#uploadModal .close').click(function() {
        resetUploadForm();
    });
</script>
@stop