@section('content')
<div class="card p-3 container">
    @if (isset($kelompokData) && count($kelompokData) > 0)
    <table id="kesediaanTable" class="table table-bordered table-stripped table-responsive">
        {{-- <table id="myTable" class="table"> --}}
        <thead class="text-center bg-dark text-white">
            <div class="mb-3">
                <label for="filterProdi">Filter Prodi:</label>
                <select id="filterProdi" class="form-control w-25">
                    <option value="">Semua</option>
                    <option value="1">D3</option>
                    <option value="2">D4</option>
                </select>
            </div>

            <tr>
                <th>No</th>
                {{-- <th>Kelompok</th>
                <th>Nama</th>
                <th>NIM</th> --}}
                <th class="bidang-column" style="width: 30%;">Bidang</th>
                <th class="judul-column" style="width: 70%;">Judul TA</th>
                <th style="width: 10%;">Prodi</th>
                {{-- <th>Pengajuan</th> --}}
                <th>Peminatan</th>
            </tr>
        </thead>
        <tbody>
            @php $groupCounter = 0; @endphp

            @foreach ($kelompokData as $index => $kelompok)

            {{-- @foreach ($kelompok['anggota'] as $index => $anggota) --}}
            <tr>
                <td>
                    <center>{{$index+1}}</center>
                </td>
                <td class="bidang-column">{{ $kelompok['bidang'] ?? '-' }}</td>
                <td class="judul-column">{{ $kelompok['judul'] ?? '-' }}</td>
                <td data-search="{{ $kelompok['id_prodi'] }}">
                    {{ $kelompok['id_prodi'] == 1 ? 'D3' : ($kelompok['id_prodi'] == 2 ? 'D4' : '-') }}
                </td>
                {{-- <pre>{{ print_r($kelompok, true) }}</pre> --}}

                <td>
                    @php
                    $isAccepted = $kelompok['status'] === 'accepted';
                    $isRejected = $kelompok['status'] === 'rejected';
                    @endphp

                    @if ($isAccepted)
                    <button class="btn btn-success w-100 btn-toggle-status" data-id="{{ $kelompok['id'] }}" data-status="accepted" disabled>
                        <i class="fas fa-check"></i>
                    </button>
                    @elseif ($isRejected)
                    <button class="btn btn-danger w-100 btn-toggle-status" data-id="{{ $kelompok['id'] }}" data-status="rejected" disabled>
                        <i class="fas fa-times"></i>
                    </button>
                    @else
                    <button class="btn btn-secondary w-100 btn-toggle-status" data-id="{{ $kelompok['id'] }}" data-status="none">
                        <i class="fas fa-toggle-off"></i>
                    </button>
                    @endif
                </td>

            </tr>

            @php $groupCounter++; @endphp
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
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> --}}
<link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
<style>
    .btn-accept {
        background-color: #28a745 !important;
        color: white;
        margin: 5px 0;
    }

    .btn-accept:hover {
        background-color: #218838;
    }

    .btn-reject {
        background-color: #dc3545 !important;
        color: white;
        margin: 5px 0;
    }

    .btn-accept,
    .btn-reject {
        display: inline-block;
        width: 48%;
        margin: 0 1% !important;
    }

    .btn-success {
        margin: 5px;
    }

    .btn-reject:hover {
        background-color: #c82333;
    }

    .btn-toggle-status {
        transition: background-color 0.3s ease;
    }

</style>
@stop

@section('js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('PengajuanAlokasiPembimbing.Helper.JS.SweetAlert')



{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
{{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {

        $('#kesediaanTable').DataTable({
            "pagingType": "full_numbers"
            , "searching": true
            , "ordering": true
            , responsive: true
            , "order": [
                [0, "asc"]
            ]
            , "language": {
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
        , });

        var table = $('#kesediaanTable').DataTable();
        $('#filterProdi').on('change', function() {
            var val = $(this).val();
            table.column(3).search(val).draw();
        });

        function handleAction(kelompokId, actionType) {
            let routeUrl = "{{ route('pengajuanalokasipembimbing.daftar-pengajuan-dosbing.handlePengajuan', ['id' => ':kelompokId', 'action' => ':actionType']) }}";
            routeUrl = routeUrl.replace(':kelompokId', kelompokId).replace(':actionType', actionType);

            Swal.fire({
                title: "Konfirmasi"
                , text: actionType === "accept" ? "Apakah Anda benar-benar berminat untuk menguji judul TA ini?" : "Apakah Anda yakin tidak jadi berminat di judul TA ini?"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: "#3085d6"
                , cancelButtonColor: "#6c757d"
                , confirmButtonText: actionType === "accept" ? "Ya, minat" : "Ya, tidak diminati."
                , cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: routeUrl
                        , method: "POST"
                        , headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        }
                        , success: function(response) {
                            console.log("Response dari server:", response);


                            if (response.status === "exists") {
                                Swal.fire({
                                    title: "Minat sudah dipilih!"
                                    , text: response.message
                                    , icon: "warning"
                                });

                                $(`[data-id="${kelompokId}"]`)
                                    .removeClass("btn-secondary btn-success")
                                    .addClass("btn-danger")
                                    .html('<i class="fas fa-times"></i>')
                                    .data("status", "rejected")
                                    .prop("enable", true);


                                return;
                            }


                            Swal.fire({
                                title: "Berhasil!"
                                , text: response.message
                                , icon: "success"
                            }).then(() => {

                                if (actionType === "accept") {
                                    $(`[data-id="${kelompokId}"]`)
                                        .removeClass("btn-secondary btn-danger")
                                        .addClass("btn-success")
                                        .html('<i class="fas fa-check"></i>')
                                        .data("status", "accepted")
                                        .prop("disabled", true);
                                } else if (actionType === "reject") {
                                    $(`[data-id="${kelompokId}"]`)
                                        .removeClass("btn-secondary btn-success")
                                        .addClass("btn-danger")
                                        .html('<i class="fas fa-times"></i>')
                                        .data("status", "rejected")
                                        .prop("disabled", true);
                                }


                            });
                        }
                        , error: function(xhr) {
                            if (xhr.status === 422) {
                                Swal.fire({
                                    title: "Kuota Terpenuhi!"
                                    , text: xhr.responseJSON.message
                                    , icon: "warning"
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!"
                                    , text: "Terjadi kesalahan saat memperbarui data."
                                    , icon: "error"
                                });
                            }
                            console.error(xhr.responseText); // Debugging
                        }
                    });
                }
            });
        }

        $(document).on("click", ".btn", function() {
            let kelompokId = $(this).data("id");
            let actionType = $(this).data("action");
            handleAction(kelompokId, actionType);
        });

        $(document).on("click", ".btn-toggle-status", function() {
            let $btn = $(this);
            let kelompokId = $btn.data("id");
            let currentStatus = $btn.data("status");

            // Toggle logika: jika status sekarang none/rejected -> accept, jika accepted -> reject
            let nextAction = currentStatus === "accepted" ? "reject" : "accept";

            handleAction(kelompokId, nextAction);
        });

    });

</script>

@stop
