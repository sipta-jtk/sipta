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
                        <div class="col-md-12">
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
                    <button type="button" id="terapkanFilterBtn" class="btn btn-primary">
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
                    
                    <!-- Abstrak -->
                    <div class="form-group">
                        <label for="abstrakDokumen">Abstrak</label>
                        <textarea class="form-control" id="abstrakDokumen" name="abstrak" rows="5" placeholder="Masukkan abstrak dokumen (150-250 kata)" required></textarea>
                        <small id="abstrakCounter" class="form-text text-muted mt-1">0/250 kata</small>
                        <small class="text-danger d-none" id="abstrakError">Abstrak harus memiliki minimal 150 kata dan maksimal 250 kata.</small>
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

                    <!-- Info Batasan Unggah -->
                    <div class="form-group">
                        <small class="text-muted">
                            Batasan Unggah <br>
                            Ukuran dokumen maksimal: 15 MB <br>
                            Jenis dokumen yang valid: PDF
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
                        <p><strong>Abstrak:</strong> <span id="abstrakPreview" style="display: block; max-height: 100px; overflow-y: auto; font-size: 0.9em; margin-bottom: 10px;"></span></p>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="role_user" content="{{ auth()->user()->role_user }}">
<meta name="prefix-url" content="{{ env('PREFIX_URL', 'sipta-dev') }}">

<script>
    var prefixUrl = $("meta[name='prefix-url']").attr("content");
    
    // Fungsi untuk membuat URL API yang benar (tanpa duplikasi prefix)
    function createApiUrl(endpoint) {
        // Periksa apakah url sudah berisi domain name atau dimulai dengan http
        if (endpoint.includes('://') || endpoint.startsWith('http')) {
            return endpoint;
        }
        
        // Hapus slash di awal endpoint jika ada
        if (endpoint.startsWith('/')) {
            endpoint = endpoint.substring(1);
        }
        
        // Hapus slash di akhir prefixUrl jika ada
        let prefix = prefixUrl;
        if (prefix.endsWith('/')) {
            prefix = prefix.substring(0, prefix.length - 1);
        }
        
        // Gabungkan dengan slash di tengah
        return '/' + prefix + '/' + endpoint;
    }
    
    console.log("Prefix URL:", prefixUrl); // Debugging prefix URL
    
    $(document).ready(function() {
        // Ambil role_user dari meta tag yang ada di halaman
        var roleUser = $("meta[name='role_user']").attr("content");

        // Membuat URL untuk API kota
        var urlKota = '/api/kotas';

        if (roleUser === 'dosen') {
            // Mengambil data kota dari API
            $.ajax({
                type: "GET",
                url: createApiUrl(urlKota),
                dataType: "json",
                success: function(response) {
                    console.log("URL Kota:", createApiUrl(urlKota)); // Log URL yang digunakan
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
        // Buat URL untuk API dokumen
        var urlDokumen = '/api/cek-plagiarisme';

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
            columnDefs: [
                {
                    targets: [0],
                    visible: false,
                    searchable: false
                },
                {
                    targets: [8], // id_kota column (hidden)
                    visible: false,
                    searchable: true
                }
            ],
            responsive: true,
            autoWidth: false
        });

        $('#table tbody').on('click', 'tr', function() {
            var data = table.row(this).data();
            if (data) {
                var idDokumen = data[0]; // ambil id_dokumen dari kolom tersembunyi
                window.location.href = createApiUrl("/cek-plagiarisme/" + idDokumen + "/detail-dokumen");
            }
        });


        $.ajax({
            type: "GET",
            url: createApiUrl(urlDokumen),
            dataType: "json",
            success: function(response) {
                console.log("URL Dokumen:", createApiUrl(urlDokumen)); // Log URL yang digunakan

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

                // Fungsi untuk filter DataTable
                function applyFilters() {
                    var selectedKota = $("#kelompokSelect").val();
                    
                    // Clear table filter first
                    table.search('').columns().search('').draw();
                    
                    // Custom filtering function for DataTables
                    $.fn.dataTable.ext.search.push(
                        function(settings, data, dataIndex) {
                            var rowData = response[dataIndex];
                            
                            // Filter by kota if selected
                            var kotaMatch = !selectedKota || 
                                (rowData && rowData.id_kota != null && rowData.id_kota.toString() == selectedKota);
                            
                            return kotaMatch;
                        }
                    );
                    
                    // Log filter details for debugging
                    console.log("Applying filters:");
                    console.log("- Selected KoTa ID:", selectedKota);
                    console.log("- Total rows before filter:", response.length);
                    console.log("- Available KoTa values:", response.map(item => item.id_kota).filter((value, index, self) => self.indexOf(value) === index));
                    
                    // Redraw the table to apply filters
                    table.draw();
                    
                    // Remove the custom filter function after drawing
                    $.fn.dataTable.ext.search.pop();
                    
                    // Count visible rows after filtering
                    var visibleRowCount = table.rows({search:'applied'}).count();
                    console.log("- Total rows after filter:", visibleRowCount);
                }
                
                // Handle the "Terapkan" button click
                $("#terapkanFilterBtn").on("click", function() {
                    applyFilters();
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
        $('#abstrakDokumen').val('');
        $('#dokumenFile').val('');
        $('#namaFileTerpilih').text('Tidak ada file');
        $('#judulCounter').text('0/20 kata');
        $('#abstrakCounter').text('0/250 kata');
        $('#abstrakError').addClass('d-none');
        $('#judulError').addClass('d-none');
        $('#judulDokumen').removeClass('is-invalid');
        $('#abstrakDokumen').removeClass('is-invalid');
    }


    // Fungsi untuk validasi form
    function validateForm() {
        var judulValid = validateJudul();
        var abstrakValid = validateAbstrak();
        
        // Tombol lanjutkan hanya aktif jika judul dan abstrak valid
        $('#openConfirmBtn').prop('disabled', !(judulValid && abstrakValid));
        
        return judulValid && abstrakValid;
    }
    
    // Validasi real-time untuk judul dokumen
    function validateJudul() {
        var judul = $('#judulDokumen').val().trim();
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
            return false;
        } else {
            $('#judulError').addClass('d-none'); // Sembunyikan pesan error
            $('#judulCounter').removeClass('text-danger').addClass('text-muted'); // Counter normal
            $('#judulDokumen').removeClass('is-invalid'); // Input normal
            return true;
        }
    }
    
    // Validasi real-time untuk abstrak
    function validateAbstrak() {
        var abstrak = $('#abstrakDokumen').val().trim();
        var jumlahKata = abstrak.length > 0 ? abstrak.split(/\s+/).length : 0;
        
        // Update counter
        $('#abstrakCounter').text(jumlahKata + '/250 kata');
        
        // Validasi jumlah kata
        if (jumlahKata < 150 || jumlahKata > 250) {
            $('#abstrakError').removeClass('d-none');
            $('#abstrakCounter').addClass('text-danger').removeClass('text-muted');
            $('#abstrakDokumen').addClass('is-invalid');
            return false;
        } else {
            $('#abstrakError').addClass('d-none');
            $('#abstrakCounter').removeClass('text-danger').addClass('text-muted');
            $('#abstrakDokumen').removeClass('is-invalid');
            return true;
        }
    }
    
    // Event handler untuk input judul
    $('#judulDokumen').on('input', function() {
        validateForm();
    });
    
    // Event handler untuk input abstrak
    $('#abstrakDokumen').on('input', function() {
        validateForm();
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
        const abstrakInput = $('#abstrakDokumen').val().trim();
        
        // Validasi sekali lagi sebelum melanjutkan
        const isValid = validateForm();
        
        if (!fileInput || !judulInput || !abstrakInput || !isValid) {
            Swal.fire('Peringatan', 'Silakan isi judul dan abstrak sesuai ketentuan, serta pilih dokumen.', 'warning');
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
            $('#abstrakPreview').text(abstrakInput);
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
        formData.append('deskripsi', $('#abstrakDokumen').val()); // Mengubah 'abstrak' menjadi 'deskripsi' sesuai dengan field di database
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
            url: createApiUrl('/cekplagiarisme/process'),
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire('Sukses!', 'Dokumen berhasil diunggah.', 'success').then(() => {
                    location.reload();
                });
                $('#confirmModal').modal('hide'); // Tutup modal konfirmasi
                $('#uploadModal').modal('hide'); // Tutup modal upload
                resetUploadForm(); // Reset form upload
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