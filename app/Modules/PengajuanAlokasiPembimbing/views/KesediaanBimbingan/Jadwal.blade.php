@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Kesediaan Membimbing</h1>
@stop

@section('content')

    <p><a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index') }}">Peminatan Bidang</a> >
        <a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.index') }}">Kuota Bimbingan</a> >
        Jadwal Kesediaan
    </p>

    @include('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.CardBar')

    <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.horizontal-progres number="3" active="3"
        activeColor="primary" inactiveColor="secondary" :hrefs="[
            route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.index'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index'),
        ]" />

    <div class="container-fluid m-0 p-0">

        {{-- ================== --}}
        <div class="border rounded">
            <table class="table table-responsive-sm text-center table-borderless" id="jadwalTable">
                <thead>
                    <tr>
                        @for ($day = 1; $day <= 7; $day++)
                            @php
                                $dayname = \Carbon\Carbon::now()
                                    ->locale('id')
                                    ->startOfWeek()
                                    ->addDays($day - 1)
                                    ->translatedFormat('l');
                            @endphp
                            <th scope="col">
                                <button class="btn bg-transparent" onclick="CallTimeModal('Tambah', '{{ $dayname }}')">
                                    {{ $dayname }}
                                    <i class="fas fa-plus"></i>
                                </button>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @for ($time = 0; $time <= 23; $time++)
                        <tr>
                            @for ($day = 1; $day <= 7; $day++)
                                <td class="p-2 {{ $day != 7 ? 'border-right' : '' }}"
                                    id="{{ $time }}-{{ \Carbon\Carbon::create()->startOfWeek()->addDays($day - 1)->locale('id')->translatedFormat('l') }}">
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        {{-- ================== --}}
        <div class="container-fluid d-flex justify-content-end p-3 p-md-0 mt-2">
            <button type="button" onclick="previousPage()" class="btn btn-info">Sebelumnya <i
                    class="fas fa-chevron-left pl-1"></i></button>
            <button type="button" onclick="saveJadwal()" class="btn btn-primary ml-3">Simpan <i
                    class="fas fa-save pl-1"></i></button>
        </div>
    </div>

    <div class="modal fade" id="TimeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit jadwal hari Senin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                                <input type="hidden" name="day" id="day">
                                <div class="form-group row justify-content-md-end">
                                    <label for="time" class="col-5 col-form-label font-weight-normal"><small>Waktu
                                            mulai</small></label>
                                    <div class="col-auto">
                                        <input type="time" class="form-control" id="timestart">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group row justify-content-start">
                                    <label for="time" class="col-5 col-form-label font-weight-normal"><small>Waktu
                                            selesai</small></label>
                                    <div class="col-auto">
                                        <input type="time" class="form-control" id="timeend">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-sm btn-primary" id="saveFormJadwalBtn">Simpan <i
                            class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.store') }}" id="jadwalForm"
        method="POST">
        @csrf <input type="hidden" name="jadwals">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var dataJadwal = [
            @foreach ($jadwal as $jadwalitem)
                {
                    id: '{{ $jadwalitem->id_jadwal_dosbim }}',
                    day: '{{ ucfirst(strtolower($jadwalitem->hari)) }}',
                    time: ['{{ $jadwalitem->jam_mulai }}', '{{ $jadwalitem->jam_selesai }}']
                },
            @endforeach
        ];


        function is_valid(day, timeA, timeB, excludeId = []) {
            day = day.charAt(0).toUpperCase() + day.slice(1).toLowerCase();
            var timeAHour = parseInt(timeA.split(':')[0]);
            var timeBHour = parseInt(timeB.split(':')[0]);

            var valid = true;
            dataJadwal.forEach((jadwal) => {
                if (excludeId.includes(jadwal.id)) {
                    return;
                }

                if (jadwal.day == day) {
                    var jadwalAHour = parseInt(jadwal.time[0].split(':')[0]);
                    var jadwalBHour = parseInt(jadwal.time[1].split(':')[0]);

                    if ((timeAHour >= jadwalAHour && timeAHour <= jadwalBHour) ||
                        (timeBHour >= jadwalAHour && timeBHour <= jadwalBHour) ||
                        (timeAHour <= jadwalAHour && timeBHour >= jadwalBHour)) {
                        valid = false;
                    }
                }
            });

            return valid;
        }

        function saveJadwal() {
            $('#jadwalForm input[name="jadwals"]').val(JSON.stringify(dataJadwal));
            $('#jadwalForm').submit();
        }

        function addJadwal(day, timeA, timeB, itemId = Math.random().toString(36).replace(/[0-9]/g, '').substring(2, 15) +
            Math.random().toString(36).replace(/[0-9]/g, '').substring(2, 15)) {
            if (is_valid(day, timeA, timeB)) {
                dataJadwal.push({
                    id: itemId,
                    day: day,
                    time: [timeA, timeB]
                });
                renderScedule();

                $('#TimeModal').modal('hide');
            } else {
                SweetAlert('error', 'Gagal Menambah Jadwal',
                    'Jadwal yang anda masukkan bentrok dengan jadwal yang sudah ada', 'OK', '', '#d33', '', false, true);
            }
        }

        function editJadwal(id, day, timeA, timeB) {
            removeJadwal(id);
            addJadwal(day, timeA, timeB, id);
        }

        function removeJadwal(id) {
            console.log(id);

            dataJadwal = dataJadwal.filter((jadwal) => jadwal.id != id);
            renderScedule();
        }

        function clearTable() {
            $('#jadwalTable td').each(function() {
                $(this).removeClass('bg-secondary');
                $(this).removeClass('rounded-top');
                $(this).removeClass('rounded-bottom');
                $(this).empty();
            });
        }

        function convertTo12H(time) {
            var hours = parseInt(time.split(':')[0]);
            var minutes = parseInt(time.split(':')[1]);
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;
            return strTime;
        }

        function renderScedule() {
            clearTable();
            dataJadwal.forEach((jadwal) => {
                let timeA = parseInt(jadwal.time[0].split(':')[0]);
                let timeB = parseInt(jadwal.time[1].split(':')[0]);

                for (let i = timeA; i <= timeB; i++) {
                    let cell = $(`#${i}-${jadwal.day}`);
                    cell.addClass('bg-secondary');

                    if (i == timeA) {
                        cell.addClass('rounded-top').removeClass('rounded-bottom');
                        let container = $('<div>').addClass('container-fluid d-flex justify-content-end p-0');

                        let editButton = $('<button>')
                            .addClass('btn btn-sm btn-success rounded text-white pt-0 pb-0 mr-1')
                            .html('<small><i class="fas fa-edit"></i></small>')
                            .on('click', () => CallTimeModal('Edit', jadwal.day, jadwal.time[0], jadwal.time[1],
                                jadwal.id));

                        let deleteButton = $('<button>')
                            .addClass('btn btn-sm btn-danger rounded text-white pt-0 pb-0')
                            .html('<small><i class="fas fa-times"></i></small>')
                            .on('click', () => {
                                SweetAlert('warning', 'Hapus Jadwal',
                                    'Apakah anda yakin ingin menghapus jadwal ini?', 'Ya', 'Tidak', '#d33',
                                    '#3085d6', true, true, (confirmed) => {
                                        if (confirmed) {
                                            removeJadwal(jadwal.id);
                                            toast('success', 'Berhasil', 'Jadwal berhasil dihapus', 5000);
                                        }
                                    });
                            });

                        container.append(editButton, deleteButton);
                        cell.append(container);
                    }

                    if (i == timeA + 1 || timeA == timeB) {
                        cell.append(
                            `<small class="m-0">${convertTo12H(jadwal.time[0])} - ${convertTo12H(jadwal.time[1])}</small>`
                        );
                    }

                    if (i == timeB && !cell.hasClass('rounded-top')) {
                        cell.addClass('rounded-bottom');
                    }
                }
            });
        }
        renderScedule();
    </script>

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

@section('js')
    @include('pengajuanalokasipembimbing.Helper.JS.SweetAlert')
    @include('pengajuanalokasipembimbing.Helper.JS.AutoFlashReader')
    @include('pengajuanalokasipembimbing.Helper.JS.AutoErrorShower')

    <script>
        function CallTimeModal(type, day, defaultstartvalue = new Date(new Date().setMinutes(0)).toTimeString().slice(0, 5),
            defaultendvalue = new Date(new Date().setHours(new Date().getHours() + 1, 0)).toTimeString().slice(0, 5), id =
            "NULL"
        ) {
            $('#TimeModal').modal('show');
            $('#TimeModal .modal-title').text(type + ' jadwal hari ' + day);
            $('#timestart').val(defaultstartvalue);
            $('#timeend').val(defaultendvalue);
            $('#day').val(day);

            if (type == 'Edit') {
                $('#saveFormJadwalBtn').attr('onclick',
                    `editJadwal('${id}', $("#day").val(), $("#timestart").val(), $("#timeend").val())`);
            } else {
                $('#saveFormJadwalBtn').attr('onclick',
                    `addJadwal($("#day").val(), $("#timestart").val(), $("#timeend").val())`);
            }
        }

        function previousPage() {
            $('#jadwalForm').attr('action',
                "{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.next', ['previous' => '3', 'target' => '2']) }}"
            );
            saveJadwal();
        }
    </script>
@stop
