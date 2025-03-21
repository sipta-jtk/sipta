@extends('adminlte::page')

@section('title', 'Penentuan Ambang Batas')

@section('content_header')
<h1 class="text-center">PENENTUAN AMBANG BATAS</h1>
@stop

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-box">
                <input type="text" class="form-control" id="searchInput" placeholder="Cari disini...">
            </div>
            <div class="ml-auto d-flex align-items-center">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addAmbangBatasModal">
                    <i class="fas fa-plus mr-2"></i>
                    TAMBAH AMBANG BATAS
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="jsGrid1"></div>
        </div>
    </div>
</section>

<!-- Modal Tambah Ambang Batas -->
<div class="modal fade" id="addAmbangBatasModal" tabindex="-1" aria-labelledby="addAmbangBatasModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Ambang Batas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ambangBatasForm">
                    <div class="mb-3">
                        <label for="ambang_batas" class="form-label">Presentase Ambang Batas (%):</label>
                        <input type="number" class="form-control" id="ambang_batas" name="ambang_batas" required min="1" max="100">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    #jsGrid1 .jsgrid-row,
    #jsGrid1 .jsgrid-alt-row {
        pointer-events: none;
        cursor: default !important;
        user-select: none;
    }
</style>

<script>
    $(document).ready(function() {
        console.log("DOM siap, inisialisasi jsGrid..."); // Debugging

        var originalData = []; // Variabel untuk menyimpan data asli

        /**
         * Fungsi ini digunakan untuk mengambil data ambang batas dari API
         */
        function loadData() {
            $.ajax({
                type: "GET",
                url: "/api/ambang-batas",
                dataType: "json",
                success: function(response) {
                    console.log("Data dari API:", response);

                    // Sorting berdasarkan tanggal terbaru (descending)
                    response.sort(function(a, b) {
                        return new Date(b.tanggal) - new Date(a.tanggal);
                    });

                    // Menambahkan nomor urut secara dinamis berdasarkan index setelah sorting
                    response = response.map((item, index) => ({
                        nomor: index + 1, // Nomor urut
                        ambang_batas: item.ambang_batas + "%",
                        tanggal: item.tanggal,
                        koordinator: item.koordinator,
                        status: item.status
                    }));

                    // Simpan data asli ke dalam variabel global
                    originalData = response;

                    // Inisialisasi jsGrid
                    $("#jsGrid1").jsGrid({
                        width: "100%",
                        height: "450px",
                        data: originalData, // Set data awal
                        autoload: true, // Pastikan data dimuat otomatis
                        fields: [{
                                name: "nomor",
                                type: "number",
                                title: "Nomor",
                                width: 50,
                                align: "center"
                            },
                            {
                                name: "ambang_batas",
                                type: "text",
                                title: "Ambang Batas",
                                width: 100,
                                align: "center"
                            },
                            {
                                name: "tanggal",
                                type: "text",
                                width: 150,
                                align: "center"
                            },
                            {
                                name: "koordinator",
                                type: "text",
                                title: "Nama Koordinator TA",
                                width: 200,
                                align: "center"
                            },
                            {
                                name: "status",
                                type: "text",
                                title: "Status",
                                width: 150,
                                align: "center"
                            }
                        ]
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Gagal mengambil data dari API:", status, error);
                }
            });
        }

        // Panggil fungsi untuk pertama kali
        loadData();

        /**
         * Fungsi untuk melakukan pencarian data ambang batas
         */
        $("#searchInput").on("keyup", function() {
            var searchValue = $(this).val().toLowerCase();

            // Jika searchValue kosong, kembalikan data asli
            if (searchValue === "") {
                $("#jsGrid1").jsGrid("option", "data", originalData); // Gunakan data asli
            } else {
                // Jika ada teks dalam search, lakukan filter
                var filteredData = originalData.filter(function(item) {
                    return Object.values(item).some(value =>
                        String(value).toLowerCase().includes(searchValue)
                    );
                });

                // Menambahkan nomor urut setelah filter
                filteredData = filteredData.map((item, index) => ({
                    ...item,
                    nomor: index + 1 // Mengatur nomor urut ulang
                }));

                $("#jsGrid1").jsGrid("option", "data", filteredData); // Perbarui data grid dengan hasil filter
            }
        });

        /**
         * Fungsi untuk menambah ambang batas baru
         */
        $("#ambangBatasForm").submit(function(e) {
            e.preventDefault();

            var formData = {
                ambang_batas: $("#ambang_batas").val()
            };

            $.ajax({
                type: "POST",
                url: "/api/ambang-batas",
                data: formData,
                dataType: "json"
            }).done(function(response) {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Ambang Batas berhasil ditambahkan!",
                    icon: "success"
                }).then(() => {
                    $("#addAmbangBatasModal").modal('hide'); // Tutup modal
                    $("#ambangBatasForm")[0].reset(); // Reset form
                    loadData(); // Refresh tabel otomatis
                });
            }).fail(function(xhr) {
                Swal.fire({
                    title: "Gagal Menambahkan!",
                    text: "Ambang Batas gagal ditambahkan!",
                    icon: "error"
                });
            });
        });
    });
</script>
@stop