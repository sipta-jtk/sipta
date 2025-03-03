@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Kesediaan Membimbing</h1>
@stop

@section('content')

    <p><a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang') }}">Peminatan Bidang</a> >
        <a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa') }}">Kuota Bimbingan</a> >
        Jadwal Kesediaan
    </p>

    <center>
        <div class="row w-100 justify-content-center">
            <div class="col-lg-3 col ml-2 mr-2">
                <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="info"
                    innerHtml="<h3>150</h3><p>Bidang Diminati</p>" icon="fas fa-graduation-cap"
                    href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang') }}" hrefText="More info" />
            </div>
            <div class="col-lg-3 col ml-2 mr-2">
                <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="success"
                    innerHtml="<h3 class='mb-2'>5<sup style='font-size: 20px'> mahasiswa D3</sup><br>6<sup style='font-size: 20px'> mahasiswa D4</sup></h3>"
                    icon="fas fa-users"
                    href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa') }}"
                    hrefText="More info" />
            </div>
            <div class="col-lg-3 col ml-2 mr-2">
                <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="warning"
                    innerHtml="<h3>3 <sup style='font-size: 20px'>Hari</sup><br>5 <sup style='font-size: 20px'>Sesi</sup></h3>"
                    icon="fas fa-calendar-alt" href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal') }}"
                    hrefText="More info" />
            </div>
        </div>
    </center>

    <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.horizontal-progres number="3" active="3"
        activeColor="primary" inactiveColor="secondary" :hrefs="[
            route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal'),
        ]" />

    <div class="container-fluid m-0 p-0">

        {{-- ================== --}}
        <div class="border rounded">
            <table class="table table-responsive-sm text-center table-borderless">
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
        <div class="container-fluid d-flex justify-content-end p-3 p-md-0">
            <button type="button" class="btn btn-primary ml-3">Simpan <i class="fas fa-save pl-1"></i></button>
            <button type="button" class="btn btn-info ml-3">Berikutnya <i class="fas fa-chevron-right pl-1"></i></button>
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
                    <button type="button" class="btn btn-sm btn-primary">Simpan <i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

@section('js')
    @include('pengajuanalokasipembimbing.Helper.JS.SweetAlert')

    <script>
        var dataJadwal = [{
                day: 'Senin',
                time: ['10:00', '14:00']
            },
            {
                day: 'Selasa',
                time: ['10:00', '18:00']
            },
            {
                day: 'Rabu',
                time: ['00:00', '23:00']
            },
            {
                day: 'Kamis',
                time: ['09:00', '12:00']
            },
            {
                day: 'Kamis',
                time: ['14:00', '16:00']
            },
            {
                day: "Jumat",
                time: ['13:00', '23:00']
            },
            {
                day: "Sabtu",
                time: ['13:00', '14:00']
            },
            {
                day: "Senin",
                time: ['07:00', '07:00']
            }
        ];

        dataJadwal.forEach((jadwal) => {
            let timeA = parseInt(jadwal.time[0].split(':')[0]);
            let timeB = parseInt(jadwal.time[1].split(':')[0]);
            for (let i = timeA; i <= timeB; i++) {
                $(`#${i}-${jadwal.day}`).addClass('bg-secondary');
                if (i == timeA) {
                    $(`#${i}-${jadwal.day}`).addClass('rounded-top');

                    var container = document.createElement('div');
                    container.classList.add('container-fluid', 'd-flex', 'justify-content-end', 'p-0');

                    var editButton = document.createElement('button');
                    container.appendChild(editButton);
                    editButton.classList.add('btn', 'btn-sm', 'btn-success', 'rounded', 'text-white', 'pt-0',
                        'pb-0',
                        'mr-1');
                    editButton.innerHTML = '<small><i class="fas fa-edit"></i></small>';
                    // editButton.setAttribute('data-toggle', 'modal');
                    // editButton.setAttribute('data-target', '#TimeModal');
                    editButton.addEventListener('click', function() {
                        CallTimeModal('Edit', jadwal.day, jadwal.time[0], jadwal.time[1]);
                    });

                    var button = document.createElement('button');
                    container.appendChild(button);
                    button.classList.add('btn', 'btn-sm', 'btn-danger', 'rounded', 'text-white', 'pt-0', 'pb-0');
                    button.innerHTML = '<small><i class="fas fa-times"></i></small>';
                    // onclick
                    button.addEventListener('click', function() {
                        SweetAlert('warning', 'Hapus Jadwal',
                            'Apakah anda yakin ingin menghapus jadwal ini?', 'Ya',
                            'Tidak', '#d33', '#3085d6', true, true);
                    });



                    $(`#${i}-${jadwal.day}`).append(container);
                }
                if ((i == timeA + 1) || (timeA == timeB)) {
                    $(`#${i}-${jadwal.day}`).append(
                        `<small class="m-0">${jadwal.time[0]} - ${jadwal.time[1]}</small>`
                    );
                }

                if (i == timeB) {
                    $(`#${i}-${jadwal.day}`).addClass('rounded-bottom');
                }
            }
        });
    </script>

    <script>
        function CallTimeModal(type, day, defaultstartvalue = '00:00', defaultendvalue = '00:00') {
            $('#TimeModal').modal('show');
            $('#TimeModal .modal-title').text(type + ' jadwal hari ' + day);
            $('#timestart').val(defaultstartvalue);
            $('#timeend').val(defaultendvalue);
        }
    </script>
@stop
