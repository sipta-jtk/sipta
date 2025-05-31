@extends('adminlte::page')

@section('title', 'PengelolaanPeriode')

@section('content_header')
    <h1>Pengelolaan Periode Pengisian FTA01 dan FTA02</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/'), 'label' => 'Beranda'],
                [
                    'url' => '',
                    'label' => 'Pengelolaan Periode',
                ],
            ],
        ])
        @endcomponent
    </div>

    <div class="card p-3">

        <div class="container-fluid d-flex justify-content-end mb-2 p-0">
            <button type="button" class="btn btn-primary" onclick="openModal('add')">
                <i class="fas fa-plus"></i> Tambah</button>
        </div>
        <center>
            <table id="periodeTable" class="table table-striped table-bordered w-100 text-center table-responsive">

                <thead class="bg-dark text-white">

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
                                    class="badge badge-primary">{{ \Carbon\Carbon::parse($periode->periode_mulai)->translatedFormat('d F Y') }}</span>
                                -
                                <span
                                    class="badge badge-primary">{{ \Carbon\Carbon::parse($periode->periode_akhir)->translatedFormat('d F Y') }}</span>
                                ({{ \Carbon\Carbon::parse($periode->periode_mulai)->diffInDays(\Carbon\Carbon::parse($periode->periode_akhir)) + 1 }}
                                hari)
                            </td>
                            <td>
                                @if (\Carbon\Carbon::now()->lt($periode->periode_mulai))
                                    <span class="badge badge-warning">Belum Dimulai</span>
                                @elseif (
                                    \Carbon\Carbon::now()->toDateString() >= \Carbon\Carbon::parse($periode->periode_mulai)->toDateString() &&
                                        \Carbon\Carbon::now()->toDateString() <= \Carbon\Carbon::parse($periode->periode_akhir)->toDateString())
                                    <span class="badge badge-success">Berlangsung</span>
                                @else
                                    <span class="badge badge-secondary">Berakhir</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
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
                                    <button type="button" class="btn btn-warning ml-1"
                                        onclick="openModal('edit', {{ $periode }})">
                                        <i class="fas fa-edit"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </center>
    </div>

    <x-adminlte-modal id="PeriodeModal" title="Edit Periode" theme="blue" size="md" static-backdrop>
        <form action="" id="formPeriode" method="POST">
            @csrf
            <div class="form-group">
                <label for="periode" class="font-weight-normal">Jadwal Periode Pengajuan FTA01 &
                    FTA02</label>
                <div class="col-auto">
                    <x-adminlte-input id="periode" name="periode" required />
                    <input type="hidden" name="periodeId" id="periodeInput">
                </div>
                <small class="text-danger d-none" id="periodeError">Periode tidak valid, periode yang anda masukkan
                    bertabrakan dengan periode lain</small>
            </div>
            <x-slot name="footerSlot">
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" id="saveFormJadwalBtn" form="formPeriode">Simpan
                    <iclass="fas fa-save"></i></button>
            </x-slot>
        </form>
    </x-adminlte-modal>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>
    <script>
        moment.locale('id');

        function openModal(type, data = null) {
            $('#periodeError').addClass('d-none');
            if (type == 'add') {
                $('#periode').val('');
                $('#exampleModalLabel').text('Tambah Periode');
                $('#formPeriode').attr('action',
                    '{{ route('pengajuanalokasipembimbing.pengelolaan-periode.store', ['mode' => 'add']) }}');
                $('#periodeInput').val('');
                populateInvalidDate();
            } else {
                $('#periode').val(
                    moment(data.periode_mulai).format('DD MMMM YYYY') + ' - ' + moment(data.periode_akhir)
                    .format('DD MMMM YYYY'));
                $('#periodeInput').val(data.id_periode_pengajuan);
                $('#formPeriode').attr('action',
                    '{{ route('pengajuanalokasipembimbing.pengelolaan-periode.store', ['mode' => 'update']) }}');
                $('#exampleModalLabel').text('Edit Periode');
                excludeDate(moment(data.periode_mulai), moment(data.periode_akhir));
            }
            $('#PeriodeModal').modal('show');
        }

        $('#periode').on('apply.daterangepicker', function(ev, picker) {
            var periode = picker.startDate.format('DD MMMM YYYY') + ' - ' + picker.endDate.format('DD MMMM YYYY');
            var collided = false;
            periodes.forEach(p => {
                if (p.periode_mulai <= picker.endDate.format('DD MMMM YYYY') && p.periode_akhir >= picker
                    .startDate.format('DD MMMM YYYY') && p.id_periode_pengajuan != $('#periodeInput').val()
                ) {
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
                responsive: true,
                "order": [
                    [0, "asc"]
                ],
                "language": {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data ",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari total _MAX_ data)",
                    "paginate": {
                        "first": "<<",
                        "last": ">>",
                        "next": ">",
                        "previous": "<"
                    }
                },
                "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                }, {
                    "width": "50%",
                    "targets": 1
                }, {
                    "width": "55%",
                    "targets": 2
                }, {
                    "width": "20%",
                    "targets": 3
                }]

            });

            $('#periode').daterangepicker({
                "locale": {
                    "format": "DD MMMM YYYY",
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

        function populateInvalidDate() {
            $('#periode').data('daterangepicker').remove();
            $('#periode').daterangepicker({
                "locale": {
                    "format": "DD MMMM YYYY",
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
                    firstDay: 1
                },
                isInvalidDate: function(date) {
                    return periodes.some(p => {
                        return date.isBetween(moment(p.periode_mulai), moment(p.periode_akhir), null,
                            '[]');
                    });
                }
            });
        }

        function excludeDate(from, to) {
            $('#periode').data('daterangepicker').remove();
            $('#periode').daterangepicker({
                "locale": {
                    "format": "DD MMMM YYYY",
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
                    firstDay: 1
                },
                isInvalidDate: function(date) {
                    return periodes.some(p => {
                        return date.isBetween(moment(p.periode_mulai), moment(p.periode_akhir), null,
                                '[]') &&
                            (date.isBefore(from) || date.isAfter(to));
                    });
                }
            });
        }
    </script>
@endsection
