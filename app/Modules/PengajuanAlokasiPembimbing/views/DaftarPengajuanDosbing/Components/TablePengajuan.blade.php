@section('content')
<div class="card p-3 container">
    @if (isset($kelompokData) && count($kelompokData) > 0)
    <table id="kesediaanTable" class="table table-bordered table-stripped table-responsive">
        {{-- <table id="myTable" class="table"> --}}
        <thead class="text-center bg-dark text-white">
            <tr>
                <th>No</th>
                <th>Kelompok</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Bidang</th>
                <th>Judul TA</th>
                <th>Pengajuan</th>
                <th>Aksi</th>
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
                <td>{{$kelompok['kode'] }}</td>
                {{-- <td>{{ $anggota['nama'] }}</td>
                <td>{{ $anggota['nim'] }}</td> --}}
                <td class="p-0">
                    @foreach ($kelompok['anggota'] as $indexAnggota=>$anggota)
                    <div class="mb-1 p-2">
                        <span class="text-truncate" style="max-width: 150px;">{{ $anggota['nama'] }}</span>
                    </div>
                    @if ($indexAnggota < count($kelompok['anggota']) - 1) <hr class="m-0">
                        @endif
                        @endforeach
                </td>
                <td class="p-0">
                    @foreach ($kelompok['anggota'] as $indexAnggota=>$anggota)
                    <div class="mb-1 p-2">
                        <span class="text-truncate" style="max-width: 150px;">{{ $anggota['nim'] }}</span>
                    </div>
                    @if ($indexAnggota < count($kelompok['anggota']) - 1) <hr class="m-0">
                        @endif
                        @endforeach
                </td>
                <td>{{($kelompok['bidang'] ?? '-') }}</td>
                <td>{{($kelompok['judul'] ?? '-') }}</td>
                <td>{{($kelompok['tanggal'] ?? '-') }}</td>
                <td>
                    @if ($kelompok['status'] === 'accepted')
                    <button class="btn btn-success w-100" disabled>Diterima</button>
                    @elseif ($kelompok['status'] === 'rejected')
                    <button class="btn btn-danger w-100" disabled>Ditolak</button>
                    @else
                    <button class="btn btn-success mb-3 w-100 btn-accept" data-id="{{ $kelompok['id'] }}" data-action="accept">Terima</button>
                    <button class="btn btn-danger w-100 btn-reject" data-id="{{ $kelompok['id'] }}" data-action="reject">Tolak</button>
                    @endif
                </td>
            </tr>
            {{-- @endforeach --}}

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
    }

    .btn-accept:hover {
        background-color: #218838;
    }

    .btn-reject {
        background-color: #dc3545 !important;
        color: white;
        margin-top: 5px;
    }

    .btn-reject:hover {
        background-color: #c82333;
    }

    .kelompok-abu td {
        background-color: #f2f2f2 !important;
        /* pakai !important */
    }

    .kelompok-putih td {
        background-color: #ffffff !important;
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
        // $('#kesediaanTable').DataTable({
        //     paging: true
        //     , ordering: true
        //     , searching: true
        //     language: {
        //         "search": "Cari:"
        //         , "lengthMenu": "Tampilkan _MENU_ data per halaman"
        //         , "zeroRecords": "Tidak ada data yang ditemukan"
        //         , "info": "Menampilkan _PAGE_ dari _PAGES_ halaman"
        //         , "infoEmpty": "Data tidak tersedia"
        //         , "infoFiltered": "(difilter dari total _MAX_ data)"
        //     }
        //     , columnDefs: [{
        //         orderable: false
        //         , targets: [7]
        //     }]
        // });

        function handleAction(kelompokId, actionType) {
            let routeUrl = "{{ route('pengajuanalokasipembimbing.daftar-pengajuan-dosbing.handlePengajuan', ['id' => ':kelompokId', 'action' => ':actionType']) }}";
            routeUrl = routeUrl.replace(':kelompokId', kelompokId).replace(':actionType', actionType);

            Swal.fire({
                title: "Konfirmasi"
                , text: actionType === "accept" ? "Apakah Anda yakin ingin menerima pengajuan ini?" : "Apakah Anda yakin ingin menolak pengajuan ini?"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: "#3085d6"
                , cancelButtonColor: "#6c757d"
                , confirmButtonText: actionType === "accept" ? "Ya, Terima" : "Ya, Tolak"
                , cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/PengajuanAlokasiPembimbing/daftar-pengajuan-dosbing/pengajuan/" + kelompokId + "/" + actionType
                        , method: "POST"
                        , headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        }
                        , success: function(response) {
                            console.log("Response dari server:", response);


                            if (response.status === "exists") {
                                Swal.fire({
                                    title: "Kota sudah dipilih!"
                                    , text: response.message
                                    , icon: "warning"
                                });
                                return;
                            }


                            Swal.fire({
                                title: "Berhasil!"
                                , text: response.message
                                , icon: "success"
                            }).then(() => {

                                if (actionType === "accept") {

                                    $(`[data-id="${kelompokId}"][data-action="accept"]`).text("Diterima").prop("disabled", true);
                                    $(`[data-id="${kelompokId}"][data-action="reject"]`).prop("disabled", false);
                                } else if (actionType === "reject") {
                                    $(`[data-id="${kelompokId}"][data-action="reject"]`).text("Ditolak").prop("disabled", true);
                                    $(`[data-id="${kelompokId}"][data-action="accept"]`).prop("disabled", false).text("Terima");
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
    });

</script>

@stop
