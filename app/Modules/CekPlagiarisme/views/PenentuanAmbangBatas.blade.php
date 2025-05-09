@extends('adminlte::page')

@section('title', 'Penentuan Ambang Batas')

@section('content_header')
<h1 class="mb-3">Penentuan Ambang Batas</h1>
<div>
    @php
    $prefix = rtrim(config('adminlte.dashboard_url', 'sipta'), '/');
    @endphp

    @component('KelolaPenilaianTA.views.components.breadcrumb', [
    'links' => [
    ['url' => url('/' . $prefix), 'label' => 'Beranda'],
    ['url' => '', 'label' => 'Penentuan Ambang Batas']
    ]
    ])
    @endcomponent
</div>
@stop

@section('content')
<section class="content">
    <div class="card">
        <div class="d-flex justify-content-end px-3 pt-3">
            <button class="btn btn-primary btn-md" data-toggle="modal" data-target="#addAmbangBatasModal">
                + Tambah
            </button>
        </div>
        <div class="card-body">
            <table id="table" class="table table-striped" width="100%">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th style="width: 50">Nomor</th>
                        <th style="width: 100">Ambang Batas</th>
                        <th style="width: 150">Waktu Ditambahkan</th>
                        <th style="width: 200">Nama Koordinator TA</th>
                        <th style="width: 150">Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Tambah Ambang Batas -->
<x-adminlte-modal id="addAmbangBatasModal" title="Tambah Ambang Batas" theme="blue" size="lg">
    <form id="ambangBatasForm" onsubmit="return false;">
        <div class="mb-3">
            <label for="ambang_batas" class="form-label">Presentase Ambang Batas (%)</label>
            <input type="number" class="form-control" id="ambang_batas" name="ambang_batas" required min="1" max="100">
        </div>
        <div class="d-flex pt-3 justify-content-end">
            <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal" class="mx-1" />
            <x-adminlte-button theme="success" label="Simpan" type="submit" class="mx-1" />
        </div>
        <x-slot name="footerSlot"></x-slot>
    </form>
</x-adminlte-modal>
@stop

@section('css')
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
<link rel="stylesheet"
    href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="prefix-url" content="{{ env('PREFIX_URL', 'sipta-dev') }}">

<script
    src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script
    src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>
    var prefixUrl = $("meta[name='prefix-url']").attr("content");
    $(document).ready(function() {

        var table = $('#table').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data ambang batas tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data ambang batas tersedia",
                infoFiltered: "(difilter dari total _MAX_ data)",
                paginate: {
                    first: "<<",
                    last: ">>",
                    next: ">",
                    previous: "<"
                }
            }
        });

        var originalData = []; // Variabel untuk menyimpan data asli

        // Ambil prefix URL dari meta tag yang ada di halaman
        var url = `${prefixUrl}/api/ambang-batas`;

        /**
         * Fungsi ini digunakan untuk mengambil data ambang batas dari API
         */
        function loadData() {
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                success: function(response) {

                    // Clear dulu sebelum isi
                    table.clear();

                    // Sorting berdasarkan status: "digunakan" paling atas
                    response.sort(function(a, b) {
                        // Ubah status ke angka: digunakan = 0, tidak_digunakan = 1
                        const statusOrder = (status) => status.toLowerCase() === 'digunakan' ? 0 : 1;

                        // Urutkan dulu berdasarkan status, lalu berdasarkan tanggal update terbaru
                        const statusCompare = statusOrder(a.status) - statusOrder(b.status);
                        if (statusCompare !== 0) return statusCompare;

                        // Jika status sama, urutkan berdasarkan tanggal terbaru
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

                    // Tambahkan ke tabel
                    response.forEach(function(item) {
                        table.row.add([
                            item.nomor,
                            item.ambang_batas,
                            item.tanggal,
                            item.koordinator ?? '-',
                            item.status ?? '-'
                        ]);
                    });

                    // Draw ulang tabel
                    table.draw();
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#ambangBatasForm").submit(function(e) {
            e.preventDefault();

            var formData = {
                ambang_batas: $("#ambang_batas").val()
            };

            $.ajax({
                type: "POST",
                url: `${prefixUrl}/api/ambang-batas`,
                data: formData,
                dataType: "json"
            }).done(function(response) {
                Swal.fire({
                    title: "Berhasil!",
                    text: response.message,
                    icon: "success"
                }).then(() => {
                    $("#addAmbangBatasModal").modal('hide');
                    $("#ambangBatasForm")[0].reset();
                    loadData();
                });
            }).fail(function(xhr) {
                Swal.fire({
                    title: "Gagal Menambahkan!",
                    text: xhr.responseJSON?.message ?? "Ambang Batas gagal ditambahkan!",
                    icon: "error"
                });
                $("#addAmbangBatasModal").modal('hide');
                $("#ambangBatasForm")[0].reset();
            });
        });
    });
</script>
@stop