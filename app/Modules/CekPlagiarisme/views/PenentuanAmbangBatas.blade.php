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
                <input type="text" class="form-control" id="searchInput" placeholder="Search here...">
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

<script>
    $(document).ready(function() {
        console.log("DOM siap, inisialisasi jsGrid..."); // Debugging


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

                console.log("Data setelah sorting:", response);
                // Menambahkan nomor urut secara dinamis berdasarkan index setelah sorting
                response = response.map((item, index) => ({
                    nomor: index + 1, // Menggunakan nomor urut mulai dari 1
                    ambang_batas: item.ambang_batas,
                    tanggal: item.tanggal,
                    koordinator: item.koordinator,
                    status: item.status
                }));

                $("#jsGrid1").jsGrid({
                    width: "100%",
                    height: "450px",
                    data: response,
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
                            title: "Ambang Batas (%)",
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
                            align: "center",
                        }
                    ]
                });

                // Fungsi Pencarian
                $("#searchInput").on("keyup", function() {
                    var searchValue = $(this).val().toLowerCase();
                    var filteredData = response.filter(function(item) {
                        var statusText = item.Status ? "Sedang Digunakan" : "Tidak Digunakan";
                        return Object.values(item).some(value =>
                            String(value).toLowerCase().includes(searchValue) || statusText.toLowerCase().includes(searchValue)
                        );
                    }); 
                    $("#jsGrid1").jsGrid("option", "data", filteredData);
                });
            },
            error: function(xhr, status, error) {
                console.error("Gagal mengambil data dari API:", status, error);
            }
        });
    });

    $(document).ready(function() {
        $("#ambangBatasForm").submit(function(e) {
            e.preventDefault();

            var formData = {
                ambang_batas: $("#ambang_batas").val()
            };

            $.ajax({
                type: "POST",
                url: "/api/ambang-batas",
                data: formData,
                dataType: "json",
                success: function(response) {
                    alert(response.message); // Tampilkan pesan sukses
                    $("#addAmbangBatasModal").modal('hide'); // Tutup modal
                    $("#ambangBatasForm")[0].reset(); // Reset form
                    $("#jsGrid1").jsGrid("loadData"); // Refresh tabel
                },
                error: function(xhr) {
                    console.error("Error response:", xhr.responseText);
                    alert("Terjadi kesalahan! Cek console untuk detail.");
                }
            });
        });
    });
</script>
@stop