@extends('adminlte::page')

@section('title', 'PengelolaanPeriode')

@section('content_header')
    <h1>Pengelolaan Periode Pengisian FTA01 dan FTA02</h1>
    <br>

    <div class="container-fluid d-flex justify-content-end mb-2 p-0">
        <button type="button" class="btn btn-primary" onclick="openModal('add')">
            Tambah Periode <i class="fas fa-plus"></i></button>
    </div>
    <div class="table-responsive">
        <table id="periodeTable" class="table table-striped table-bordered w-100 text-center">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($periodes as $index => $periode)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span
                                class="badge badge-primary">{{ \Carbon\Carbon::parse($periode->periode_mulai)->format('d-M-Y') }}</span>
                            -
                            <span
                                class="badge badge-primary">{{ \Carbon\Carbon::parse($periode->periode_akhir)->format('d-M-Y') }}</span>
                            ({{ \Carbon\Carbon::parse($periode->periode_mulai)->diffInDays(\Carbon\Carbon::parse($periode->periode_akhir)) }}
                            hari)
                        </td>
                        <td>
                            @if (\Carbon\Carbon::now()->lt($periode->periode_mulai))
                                <span class="badge badge-warning">Belum Dimulai</span>
                            @elseif (\Carbon\Carbon::now()->between($periode->periode_mulai, $periode->periode_akhir))
                                <span class="badge badge-success">Berlangsung</span>
                            @else
                                <span class="badge badge-secondary">Berakhir</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary" onclick="openModal('edit', {{ $periode }})">
                                <i class="fas fa-edit"></i></button>
                            <form
                                action="{{ route('pengajuanalokasipembimbing.pengelolaan-periode.delete', ['id' => $periode->id_periode_pengajuan]) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger"
                                    onclick="FireSweetAlert('warning', 'Apakah anda yakin?', 'Data yang dihapus tidak dapat dikembalikan', 'Ya', 'Tidak', '#d33', 'gray', true, true, (result) => {if(result) {
                                    this.closest('form').submit();
                                }})"><i
                                        class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="PeriodeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" id="formPeriode" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Periode</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="time" class="font-weight-normal"><small>Jadwal
                                Periode Pengajuan FTA01 & FTA02</small></label>
                        <div class="col-auto">
                            <input type="text" class="form-control" id="periode" name="periode" required>
                            <input type="hidden" name="periodeId" id="periodeInput">
                        </div>
                        <small class="text-danger d-none" id="periodeError">Periode tidak valid, periode yang anda masukkan
                            bertabrakan dengan periode lain</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary" id="saveFormJadwalBtn">Simpan <i
                                class="fas fa-save"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var periodes = @json($periodes);
    </script>
@stop

@section('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">

@endsection

@section('js')
    @include('PengajuanAlokasiPembimbing.Helper.JS.SweetAlert')
    @include('PengajuanAlokasiPembimbing.Helper.JS.AutoFlashReader')
    @include('PengajuanAlokasiPembimbing.Helper.JS.AutoErrorShower')

    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        function openModal(type, data = null) {
            $('#periodeError').addClass('d-none');
            if (type == 'add') {
                $('#periode').val('');
                $('#exampleModalLabel').text('Tambah Periode');
                $('#formPeriode').attr('action',
                    '{{ route('pengajuanalokasipembimbing.pengelolaan-periode.store', ['mode' => 'add']) }}');
                $('#periodeInput').val('');
            } else {
                $('#periode').val(data.periode_mulai + ' - ' + data.periode_akhir);
                $('#periodeInput').val(data.id_periode_pengajuan);
                $('#formPeriode').attr('action',
                    '{{ route('pengajuanalokasipembimbing.pengelolaan-periode.store', ['mode' => 'update']) }}');
                $('#exampleModalLabel').text('Edit Periode');
            }
            $('#PeriodeModal').modal('show');
        }

        $('#periode').on('apply.daterangepicker', function(ev, picker) {
            var periode = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD');
            var collided = false;
            periodes.forEach(p => {
                if (p.periode_mulai <= picker.endDate.format('YYYY-MM-DD') && p.periode_akhir >= picker
                    .startDate.format('YYYY-MM-DD') && p.id_periode_pengajuan != $('#periodeInput').val()) {
                    collided = true;
                }
            });
            if (collided) {
                $('#periode').val('');
                $('#periodeError').removeClass('d-none');
            } else {
                $('#periodeError').addClass('d-none');
            }
        });

        $(document).ready(function() {
            $('#periodeTable').DataTable({
                "pagingType": "full_numbers",
                "searching": true,
                "ordering": true,
                "order": [
                    [0, "asc"]
                ],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _PAGE_ dari _PAGES_ halaman",
                    "infoEmpty": "Data tidak ditemukan",
                    "infoFiltered": "(filter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                }, {
                    "width": "60%",
                    "targets": 1
                }, {
                    "width": "15%",
                    "targets": 2
                }, {
                    "width": "10%",
                    "targets": 3
                }]

            });

            $('#periode').daterangepicker({
                "locale": {
                    "format": "YYYY-MM-DD",
                    "separator": " - ",
                    "applyLabel": "Pilih",
                    "cancelLabel": "Batal",
                    "fromLabel": "Dari",
                    "toLabel": "Ke",
                    "customRangeLabel": "Custom",
                    "weekLabel": "W",
                    "daysOfWeek": [
                        "Mg",
                        "Sn",
                        "Sl",
                        "Rb",
                        "Km",
                        "Jm",
                        "Sb"
                    ],
                    "monthNames": [
                        "Januari",
                        "Februari",
                        "Maret",
                        "April",
                        "Mei",
                        "Juni",
                        "Juli",
                        "Agustus",
                        "September",
                        "Oktober",
                        "November",
                        "Desember"
                    ],
                    "firstDay": 1
                },
                "isInvalidDate": function(date) {
                    var invalidDates = periodes.map(p => {
                        return {
                            start: moment(p.periode_mulai),
                            end: moment(p.periode_akhir)
                        };
                    });

                    return invalidDates.some(d => date.isBetween(d.start, d.end, null, '[]'));
                }
            });
        });
    </script>
@endsection
