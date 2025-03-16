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
                                    <td rowspan="{{ count($kelompok['anggota']) }}">{{ $kelompok['bidang'] }}</td>
                                    <td rowspan="{{ count($kelompok['anggota']) }}">{{ $kelompok['judul'] }}</td>
                                    <td rowspan="{{ count($kelompok['anggota']) }}">{{ $kelompok['tanggal'] }}</td>
                                    <td rowspan="{{ count($kelompok['anggota']) }}">
                                        <button class="btn-action btn-accept" data-id="{{ $kelompok['id'] }}"
                                            data-action="accept">
                                            Terima
                                        </button>
                                        <button class="btn-action btn-reject" data-id="{{ $kelompok['id'] }}"
                                            data-action="reject">
                                            Tolak
                                        </button>
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
        .btn-action {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
        }

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
                Swal.fire({
                    title: "Konfirmasi",
                    text: actionType === "accept" ? "Apakah Anda yakin ingin menerima pengajuan ini?" :
                        "Apakah Anda yakin ingin menolak pengajuan ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: actionType === "accept" ? "Ya, Terima" : "Ya, Tolak",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/PengajuanAlokasiPembimbing/DaftarPengajuanDosbing/pengajuan/" +
                                kelompokId + "/" + actionType,
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                    console.log("Response dari server:", response);
                                    console.log("Memanggil Swal", response.status);

                                    // Jika kota sudah dipilih sebelumnya, hanya tampilkan pesan "exists" dan hentikan eksekusi berikutnya
                                    if (response.status === "exists") {
                                        Swal.fire({
                                            title: "Kota sudah dipilih!",
                                            text: response.message,
                                            icon: "warning"
                                        });
                                        return; // Hentikan eksekusi agar Swal success tidak muncul
                                    }

                                    // Jika tidak masuk ke kondisi "exists", jalankan Swal sukses
                                    Swal.fire({
                                        title: "Berhasil!",
                                        text: response.message,
                                        icon: "success"
                                    }).then(() => {
                                        location.reload();
                                    });
                                }


                                ,
                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    Swal.fire({
                                        title: "Kuota Terpenuhi!",
                                        text: xhr.responseJSON.message,
                                        icon: "warning"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: "Terjadi kesalahan saat memperbarui data.",
                                        icon: "error"
                                    });
                                }
                                console.error(xhr.responseText); // Debugging
                            }
                        });
                    }
                });
            }

            $(document).on("click", ".btn-action", function() {
                let kelompokId = $(this).data("id");
                let actionType = $(this).data("action");
                handleAction(kelompokId, actionType);
            });
        });
    </script>

@stop
