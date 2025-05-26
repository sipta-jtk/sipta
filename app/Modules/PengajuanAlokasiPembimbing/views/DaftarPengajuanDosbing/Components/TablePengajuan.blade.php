@extends('adminlte::page')
{{-- @php
dd($kelompokData['table']);
@endphp --}}

{{-- ... @section('title'), @section('content_header') ... --}}

@section('content')
<div class="card p-3 container">
    <div class="mb-3">
        <label for="filterProdi">Filter Prodi:</label>
        <select id="filterProdi" class="form-control w-25">
            <option value="">Semua Prodi</option>
            @isset($kelompokData['prodiList'])
            @foreach($kelompokData['prodiList'] as $prodi)
            <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
            @endforeach
            @endisset
        </select>
    </div>

    @if (isset($kelompokData['table']) && count($kelompokData['table']) > 0)
    <table id="kesediaanTable" class="table table-bordered table-stripped table-responsive">
        <thead class="text-center bg-dark text-white">
            <tr>
                <th>No</th>
                <th class="bidang-column" style="width: 30%;">Bidang</th>
                <th class="judul-column" style="width: 70%;">Judul TA</th>
                <th style="width: 10%;">Prodi</th>
                <th>Peminatan</th>
            </tr>
        </thead>
        <tbody>
            @php
            $kelompokData['table'] = collect($kelompokData['table']);
            @endphp
            @foreach ($kelompokData['table'] as $kelompok)
            <tr>
                <td>
                    {{-- <center>{{ $loop->iteration }}</center> --}}
                    <center>{{ $kelompok['loop_no'] }}</center>
                </td>
                <td class="bidang-column">{{ $kelompok['bidang'] ?? '-' }}</td>
                <td class="judul-column">{{ $kelompok['judul'] ?? '-' }}</td>
                <td data-search="{{ $kelompok['id_prodi'] }}">
                    @php
                    $namaProdi = '-';
                    if(isset($kelompokData['prodiList']) && $kelompok['id_prodi'] !== null) {
                    $foundProdi = $kelompokData['prodiList']->firstWhere('id_prodi', $kelompok['id_prodi']);
                    if ($foundProdi) {
                    $namaProdi = $foundProdi->nama_prodi;
                    }
                    }
                    @endphp
                    {{ $namaProdi }}
                </td>
                <td>
                    @php
                    // status_peminatan_aktual dari controller: 'accepted' atau 'none'
                    $currentPeminatanStatus = $kelompok['status_peminatan_aktual'] ?? 'none';

                    $buttonClass = 'btn-danger';
                    $buttonIcon = 'fa-times';
                    $dataStatusForJs = 'rejected';

                    if ($currentPeminatanStatus === 'accepted') {
                    $buttonClass = 'btn-success';
                    $buttonIcon = 'fa-check';
                    $dataStatusForJs = 'accepted';
                    } elseif ($currentPeminatanStatus === 'rejected') {
                    // Sudah default merah
                    } elseif ($currentPeminatanStatus === 'none') {
                    // Tetap gunakan style rejected untuk status 'none'
                    $buttonClass = 'btn-danger';
                    $buttonIcon = 'fa-times';
                    $dataStatusForJs = 'rejected';
                    }
                    // Tombol tidak pernah 'disabled' di awal, selalu bisa diklik untuk accept/reject
                    @endphp
                    <button class="btn {{ $buttonClass }} w-100 btn-toggle-status" data-id="{{ $kelompok['id_kota_real'] }}" data-status="{{ $dataStatusForJs }}">
                        <i class="fas {{ $buttonIcon }}"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="text-center">Tidak ada data tersedia.</p>
    @endif
</div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
<style>
    .btn-toggle-status {
        transition: background-color 0.3s ease;
        margin: 5px 0;
    }

</style>
@stop

@section('js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        var table = $('#kesediaanTable').DataTable({
            "pagingType": "full_numbers"
            , "searching": true
            , "ordering": true
            , responsive: true
            , "order": [
                [0, "asc"] // Urutkan berdasarkan kolom No (index 0)
            ]
            , "language": {
                // ... Bahasa DataTables ...
                search: "Cari:"
                , lengthMenu: "Tampilkan _MENU_ data per halaman"
                , zeroRecords: "Data tidak ditemukan"
                , info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data "
                , infoEmpty: "Tidak ada data tersedia"
                , infoFiltered: "(difilter dari total _MAX_ data)"
                , "paginate": {
                    "first": "<<"
                    , "last": ">>"
                    , "next": ">"
                    , "previous": "<"
                }
            }
            , "columnDefs": [{
                "orderable": false
                , "searchable": false
                , "targets": 4
            }]
        });

        // Filter Prodi Handler
        $('#filterProdi').on('change', function() {
            var val = $(this).val();
            table.column(3).search(val ? '^' + $.fn.dataTable.util.escapeRegex(val) + '$' : '', true, false).draw();
        });

        // Fungsi Handle Aksi (Terima/Batalkan Peminatan)
        function handleAction(kelompokId, actionType) {
            let routeUrl = "{{ route('pengajuanalokasipembimbing.daftar-pengajuan-dosbing.handlePengajuan', ['id' => ':kelompokId', 'action' => ':actionType']) }}";
            routeUrl = routeUrl.replace(':kelompokId', kelompokId).replace(':actionType', actionType);

            let confirmationText = actionType === "accept" ?
                "Apakah Anda benar-benar berminat untuk membimbing kelompok ini?" :
                "Apakah Anda yakin ingin membatalkan peminatan untuk kelompok ini?";
            let confirmButtonText = actionType === "accept" ? "Ya, Minat" : "Ya, Batalkan";

            Swal.fire({
                title: "Konfirmasi"
                , text: confirmationText
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: "#3085d6"
                , cancelButtonColor: "#6c757d"
                , confirmButtonText: confirmButtonText
                , cancelButtonText: "Tutup"
                , reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: routeUrl
                        , method: "POST"
                        , headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        }
                        , dataType: "json"
                        , success: function(response) {
                            console.log("Response dari server:", response);

                            if (response.status === "exists") {
                                Swal.fire({
                                    title: "Sudah Diminati!"
                                    , text: response.message
                                    , icon: "warning"
                                });
                                return;
                            }

                            if (response.status === 'success') {
                                Swal.fire({
                                    title: "Berhasil!"
                                    , text: response.message
                                    , icon: "success"
                                    , timer: 1500, // Tutup otomatis setelah 1.5 detik
                                    showConfirmButton: false
                                });

                                // Update Tombol setelah sukses
                                const $btn = $(`.btn-toggle-status[data-id="${kelompokId}"]`);
                                if (actionType === "accept") {
                                    $btn.removeClass("btn-secondary btn-danger").addClass("btn-success")
                                        .html('<i class="fas fa-check"></i>')
                                        .data("status", "accepted")
                                        .prop("disabled", false);
                                } else if (actionType === "reject") {
                                    $btn.removeClass("btn-success btn-danger").addClass("btn-danger")
                                        .html('<i class="fas fa-times"></i>')
                                        .data("status", "rejected") // Kembali ke status 'none'
                                        .prop("disabled", false); // Aktifkan kembali
                                }
                            } else {
                                // Handle jika ada status lain dari backend
                                Swal.fire({
                                    title: "Informasi"
                                    , text: response.message || "Terjadi sesuatu."
                                    , icon: "info"
                                });
                            }

                        }
                        , error: function(xhr, status, error) {
                            console.error("AJAX Error:", {
                                xhr: xhr
                                , status: status
                                , error: error
                            });

                            let errorTitle = "Error!";
                            let errorMessage = "Gagal memproses permintaan. Silakan coba lagi.";

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                                if (xhr.status === 422) {
                                    errorTitle = "Gagal!";
                                } else if (xhr.status === 404) {
                                    errorTitle = "Tidak Ditemukan!";
                                } else if (xhr.status === 403) {
                                    errorTitle = "Akses Ditolak!";
                                }
                            } else if (xhr.status === 500) {
                                errorMessage = "Terjadi kesalahan pada server.";
                            }

                            Swal.fire({
                                title: errorTitle
                                , text: errorMessage
                                , icon: "error"
                            });
                        }
                    });
                }
            });
        }

        $(document).on("click", ".btn-toggle-status", function() {
            let $btn = $(this);
            let kelompokId = $btn.data("id");
            let currentStatus = $btn.data("status");

            // Sederhana: jika rejected -> accept, jika accepted -> reject
            let nextAction;
            if (currentStatus === "accepted") {
                nextAction = "reject";
            } else {
                nextAction = "accept";
            }

            // Panggil fungsi handleAction
            handleAction(kelompokId, nextAction);
        });

    });

</script>
@endsection
