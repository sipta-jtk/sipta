@section('content')
<div class="container">
    @if (isset($kelompokData) && count($kelompokData) > 0)
    <table id="kesediaanTable" class="table table-bordered table-striped">
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
            @php $no = 1; @endphp
            @foreach ($kelompokData as $kelompok)
            @foreach ($kelompok['anggota'] as $index => $anggota)
            <tr>
                @if ($index === 0)
                <td rowspan="{{ count($kelompok['anggota']) }}">{{ $no }}</td>
                <td rowspan="{{ count($kelompok['anggota']) }}">{{ $kelompok['kode'] }}</td>
                @endif
                <td>{{ $anggota['nama'] }}</td>
                <td>{{ $anggota['nim'] }}</td>
                @if ($index === 0)
                <td rowspan="{{ count($kelompok['anggota']) }}">{{ $kelompok['bidang'] ?? '-' }}</td>
                <td rowspan="{{ count($kelompok['anggota']) }}">{{ $kelompok['judul'] ?? '-' }}</td>
                <td rowspan="{{ count($kelompok['anggota']) }}">{{ $kelompok['tanggal'] ?? '-' }}</td>
                <td rowspan="{{ count($kelompok['anggota']) }}">
                    @if ($kelompok['status'] === 'accepted')
                    <button class="btn btn-success w-100" disabled>Diterima</button>
                    @else
                    <button class="btn btn-success mb-3 w-100 btn-accept" data-id="{{ $kelompok['id'] }}" data-action="accept">
                        Terima
                    </button>
                    @endif

                    @if ($kelompok['status'] === 'rejected')
                    <button class="btn btn-danger w-100" disabled>Ditolak</button>
                    @else
                    <button class="btn btn-danger w-100 btn-reject" data-id="{{ $kelompok['id'] }}" data-action="reject">
                        Tolak
                    </button>
                    @endif
                </td>

                @endif
            </tr>
            @endforeach
            @php $no++; @endphp
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
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

</style>
@stop

@section('js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('PengajuanAlokasiPembimbing.Helper.JS.SweetAlert')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
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
                        url: routeUrl
                        , method: "POST"
                        , headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        }
                        , success: function(response) {
                            console.log("Response dari server:", response);

                            // Handle existing city selection scenario
                            if (response.status === "exists") {
                                Swal.fire({
                                    title: "Kota sudah dipilih!"
                                    , text: response.message
                                    , icon: "warning"
                                });
                                return; // Stop further execution
                            }

                            // Success handling after accepting or rejecting the proposal
                            Swal.fire({
                                title: "Berhasil!"
                                , text: response.message
                                , icon: "success"
                            }).then(() => {
                                // Update the button states dynamically
                                if (actionType === "accept") {
                                    // Set the accept button to "Diterima" and disable it
                                    $(`[data-id="${kelompokId}"][data-action="accept"]`).text("Diterima").prop("disabled", true);

                                    // Leave the reject button enabled
                                    $(`[data-id="${kelompokId}"][data-action="reject"]`).prop("disabled", false);
                                } else if (actionType === "reject") {
                                    // Set the reject button to "Ditolak" and disable it
                                    $(`[data-id="${kelompokId}"][data-action="reject"]`).text("Ditolak").prop("disabled", true);

                                    // Enable the accept button again
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
