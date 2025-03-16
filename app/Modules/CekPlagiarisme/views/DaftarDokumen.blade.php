@extends('adminlte::page')

@section('title', 'Cek Plagiarisme')

@section('content_header')
<h1 class="text-center mb-3">Cek Plagiarisme</h1>
@stop

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-box d-flex align-items-center">
                <input type="text" class="form-control" id="searchInput" placeholder="Cari...">
            </div>
        </div>
        <div class="card-body">
            <div id="jsGridPlagiarism"></div>
        </div>
    </div>
</section>
@stop

@section('js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "/api/cek-plagiarisme",
            dataType: "json",
            success: function(response) {
                console.log("Data dari API:", response);

                response.sort((a, b) => new Date(b.waktu) - new Date(a.waktu));

                response = response.map((item, index) => ({
                    id_dokumen: item.id_dokumen,
                    nomor: index + 1,
                    judul: item.judul,
                    waktu: item.waktu,
                    penulis: item.penulis,
                    presentase: item.persentase_plagiarisme + "%",
                    status: item.status,
                    komentar: getKomentarLink(item.review, item.id_dokumen)
                }));

                $("#jsGridPlagiarism").jsGrid({
                    width: "100%",
                    height: "450px",
                    sorting: true,
                    paging: true,
                    rowClick: function(args) {
                        window.location.href = "/cek-plagiarisme/" + args.item.id_dokumen;
                    },
                    data: response,
                    fields: [{
                            name: "nomor",
                            type: "number",
                            title: "No",
                            width: 50,
                            align: "center"
                        },
                        {
                            name: "judul",
                            type: "text",
                            title: "Judul",
                            width: 200
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

                $("#searchInput").on("keyup", function() {
                    var searchValue = $(this).val().toLowerCase();
                    var filteredData = response.filter(item =>
                        Object.values(item).some(value => String(value).toLowerCase().includes(searchValue))
                    );
                    $("#jsGridPlagiarism").jsGrid("option", "data", filteredData);
                });
            },
            error: function(xhr, status, error) {
                console.error("Gagal mengambil data dari API:", status, error);
            }
        });
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

    function getKomentarLink(komentar, id) {
        if (komentar) {
            return '<span class="text-primary">Komentar diberikan</span>';
        } else {
            return '<span class="text-muted">Belum ada komentar</span>';
        }
    }
</script>
@stop