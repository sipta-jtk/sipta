@extends('adminlte::page')
{{-- @php
dd($kelompokData['table']);
@endphp --}}

{{-- ... @section('title'), @section('content_header') ... --}}

@section('content')
<div class="card p-3 container">
    <div class=" mb-3">
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
                    $currentPeminatanStatus = $kelompok['status_peminatan_aktual'] ?? 'none';

                    $buttonClass = 'btn-danger';
                    $buttonIcon = 'fa-times';
                    $dataStatusForJs = 'rejected';

                    if ($currentPeminatanStatus === 'accepted') {
                    $buttonClass = 'btn-success';
                    $buttonIcon = 'fa-check';
                    $dataStatusForJs = 'accepted';
                    } elseif ($currentPeminatanStatus === 'rejected') {
                    } elseif ($currentPeminatanStatus === 'none') {
                    $buttonClass = 'btn-danger';
                    $buttonIcon = 'fa-times';
                    $dataStatusForJs = 'rejected';
                    }
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

        // Tambahkan flag untuk mencegah multiple request
        let isProcessing = false;
        let processingRequests = new Set();

        function handleAction(kelompokId, actionType) {
            let routeUrl = "{{ route('pengajuanalokasipembimbing.daftar-pengajuan-dosbing.handlePengajuan', ['id' => ':kelompokId', 'action' => ':actionType']) }}";
            routeUrl = routeUrl.replace(':kelompokId', kelompokId).replace(':actionType', actionType);

            // Buat unique request ID
            const requestId = `${kelompokId}_${actionType}_${Date.now()}`;

            // Cek apakah sudah ada request yang sedang diproses untuk kelompok ini
            if (processingRequests.has(kelompokId)) {
                console.log('Request dibatalkan - sedang diproses:', kelompokId);
                return;
            }

            let confirmationText = actionType === "accept" ?
                "Apakah Anda yakin ingin menerima peminatan untuk kelompok ini?" :
                "Apakah Anda yakin ingin menolak peminatan untuk kelompok ini?";
            let confirmButtonText = actionType === "accept" ? "Ya, Terima" : "Ya, Tolak";

            console.log('=== HANDLE ACTION START ===', {
                requestId: requestId
                , kelompokId: kelompokId
                , actionType: actionType
                , timestamp: new Date().toISOString()
            });

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
                    // Tandai request sedang diproses
                    processingRequests.add(kelompokId);
                    isProcessing = true;

                    console.log('=== AJAX REQUEST START ===', {
                        requestId: requestId
                        , kelompokId: kelompokId
                        , actionType: actionType
                        , url: `/PengajuanAlokasiPembimbing/daftar-pengajuan-dosbing/pengajuan/${kelompokId}/${actionType}`
                        , timestamp: new Date().toISOString()
                    });

                    $.ajax({
                        url: `/PengajuanAlokasiPembimbing/daftar-pengajuan-dosbing/pengajuan/${kelompokId}/${actionType}`
                        , type: 'POST'
                        , headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            , 'X-Request-ID': requestId
                        }
                        , data: {
                            '_token': $('meta[name="csrf-token"]').attr('content')
                        }
                        , timeout: 10000, // 10 detik timeout
                        success: function(response) {
                            console.log('=== AJAX SUCCESS ===', {
                                requestId: requestId
                                , kelompokId: kelompokId
                                , response: response
                                , timestamp: new Date().toISOString()
                            });

                            if (response.status === 'success') {
                                Swal.fire({
                                    title: "Berhasil!"
                                    , text: response.message
                                    , icon: "success"
                                    , timer: 1500
                                    , showConfirmButton: false
                                });

                                // Update HANYA tombol yang spesifik dengan ID yang tepat
                                const $specificBtn = $(`.btn-toggle-status[data-id="${kelompokId}"]`);

                                console.log('=== UPDATE BUTTON ===', {
                                    requestId: requestId
                                    , kelompokId: kelompokId
                                    , actionType: actionType
                                    , buttonsFound: $specificBtn.length
                                    , timestamp: new Date().toISOString()
                                });

                                if ($specificBtn.length === 1) {
                                    if (actionType === "accept") {
                                        $specificBtn.removeClass("btn-secondary btn-danger").addClass("btn-success")
                                            .html('<i class="fas fa-check"></i>')
                                            .data("status", "accepted")
                                            .prop("disabled", false);
                                    } else if (actionType === "reject") {
                                        $specificBtn.removeClass("btn-success btn-secondary").addClass("btn-danger")
                                            .html('<i class="fas fa-times"></i>')
                                            .data("status", "rejected")
                                            .prop("disabled", false);
                                    }
                                } else {
                                    console.error('MASALAH: Jumlah tombol tidak tepat!', {
                                        expected: 1
                                        , found: $specificBtn.length
                                        , kelompokId: kelompokId
                                    });
                                }
                            } else {
                                Swal.fire({
                                    title: "Informasi"
                                    , text: response.message || "Terjadi sesuatu."
                                    , icon: "info"
                                });
                            }
                        }
                        , error: function(xhr, status, error) {
                            console.error("=== AJAX ERROR ===", {
                                requestId: requestId
                                , kelompokId: kelompokId
                                , xhr: xhr
                                , status: status
                                , error: error
                                , timestamp: new Date().toISOString()
                            });

                            Swal.fire({
                                title: "Error!"
                                , text: "Terjadi kesalahan saat memproses permintaan."
                                , icon: "error"
                            });
                        }
                        , complete: function() {
                            // Hapus flag processing setelah selesai
                            processingRequests.delete(kelompokId);
                            isProcessing = false;

                            console.log('=== REQUEST COMPLETE ===', {
                                requestId: requestId
                                , kelompokId: kelompokId
                                , timestamp: new Date().toISOString()
                            });
                        }
                    });
                }
            });
        }

        $(document).on("click", ".btn-toggle-status", function(e) {
            // Prevent default dan stop propagation
            e.preventDefault();
            e.stopPropagation();

            let $btn = $(this);
            let kelompokId = $btn.data("id");
            let currentStatus = $btn.data("status");

            // Cek apakah sedang ada request yang diproses
            if (isProcessing || processingRequests.has(kelompokId)) {
                console.log('Click dibatalkan - sedang diproses:', kelompokId);
                return false;
            }

            console.log("=== BUTTON CLICK ===", {
                kelompokId: kelompokId
                , currentStatus: currentStatus
                , buttonElement: $btn[0]
                , timestamp: new Date().toISOString()
            });

            let buttonsWithSameId = $(`.btn-toggle-status[data-id="${kelompokId}"]`);
            console.log("Buttons with same ID:", buttonsWithSameId.length);

            if (buttonsWithSameId.length > 1) {
                console.error('MASALAH: Multiple tombol dengan ID sama!', {
                    kelompokId: kelompokId
                    , count: buttonsWithSameId.length
                });
                return false;
            }

            // Sederhana: jika rejected -> accept, jika accepted -> reject
            let nextAction;
            if (currentStatus === "accepted") {
                nextAction = "reject";
            } else {
                nextAction = "accept";
            }

            // Panggil fungsi handleAction
            handleAction(kelompokId, nextAction);

            return false;
        });
    });

</script>
@endsection
