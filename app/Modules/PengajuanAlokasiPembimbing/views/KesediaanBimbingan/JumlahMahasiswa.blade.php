@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Kesediaan Membimbing</h1>
@stop

@section('content')
<<<<<<< HEAD

    <p><a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index') }}">Peminatan Bidang</a> >
        Kuota
        Bimbingan > <a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index') }}">Jadwal
            Kesediaan</a></p>

    @include('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.C_CardBar')


    <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.horizontal-progres number="3" active="2"
        activeColor="primary" inactiveColor="secondary" :hrefs="[
            route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.index'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index'),
        ]" />

    <div class="container-fluid m-0 p-0">
        @include('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.C_ErrorPeriode')

        {{-- ================== --}}
        <form action="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.store') }}" method="post"
            id="JmlMahasiswaForm">
            @csrf
            <div class="container-fluid d-flex justify-content-center">
                <div class="row w-100">
                    <div class="col d-flex justify-content-end">
                        <div class="bg-gradient-success rounded w-100 w-md-100 w-lg-50">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="m-3">
                                        <div class="form-group m-0">
                                            <input type="number"
                                                class="form-control bg-transparent border-0 text-light p-0 fs-1"
                                                min="0" id="valD3" name="valD3"
                                                {{ $savedInformation['Periode'] ? '' : 'disabled' }}
                                                value="{{ old('valD3') ? old('valD3') : $savedInformation['MaxBimbingan']['MaxD3'] }}">
                                        </div>
                                        <hr class="text-light m-0 border-light">
                                        <small>Jumlah maksimal mahasiswa</small>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <strong>
                                        <h1 class="m-0 p-2 display-4"><strong>D3</strong></h1>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col d-flex justify-content-start">
                        <div class="bg-gradient-info rounded w-100 w-md-100 w-lg-50">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="m-3">
                                        <div class="form-group m-0">
                                            <input type="number"
                                                class="form-control bg-transparent border-0 text-light p-0 fs-1"
                                                min="0" id="valD4" name="valD4"
                                                {{ $savedInformation['Periode'] ? '' : 'disabled' }}
                                                value="{{ old('valD4') ? old('valD4') : $savedInformation['MaxBimbingan']['MaxD4'] }}">
                                        </div>
                                        <hr class="text-light m-0 border-light">
                                        <small>Jumlah maksimal mahasiswa</small>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <strong>
                                        <h1 class="m-0 p-2 display-4"><strong>D4</strong></h1>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-3 d-none w-100 w-lg-50" id="warning">
                <center>
                    <div class="alert alert-danger alert-dismissible d-none">
                        <small>
                            Dengan mengisi kuota 0, anda menyatakan bahwa tidak bersedia membimbing!
                        </small>
                    </div>
                    <div class="alert alert-danger alert-dismissible d-none">
                        <small>
                            Maksimal jumlah mahasiswa adalah 10 D3 dan 8 D4,<br>lebih dari itu honor hanya dihitung untuk 18
                            mahasiswa
                        </small>
                    </div>
                </center>
            </div>
            {{-- ================== --}}
            <div class="container-fluid d-flex justify-content-center justify-content-md-end p-3 p-md-0 mt-2">
                <button type="button" onclick="previousPage()" class="btn btn-info ml-3"><i
                        class="fas fa-chevron-left pl-1"></i></button>
                <button type="submit" class="btn btn-primary ml-3" {{ $savedInformation['Periode'] ? '' : 'disabled' }}><i
                        class="fas fa-save pl-1"></i></button>
                <button type="button" onclick="nextPage()" class="btn btn-info ml-3"><i
                        class="fas fa-chevron-right pl-1"></i></button>
            </div>
        </form>
    </div>
=======
    @php
        if ($savedInformation['StatusBersediaMembimbing'] == 'tidak_bersedia') {
            $savedInformation['Periode'] = false;
        }
    @endphp

    @if ($savedInformation['StatusBersediaMembimbing'] == 'belum_konfirmasi')
        @include('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.V_KonfirmasiBersedia')
    @else
        <p><a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index') }}">Peminatan Bidang</a> >
            Kuota
            Bimbingan > <a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index') }}">Jadwal
                Kesediaan</a></p>

        @include('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.C_CardBar')

        <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.horizontal-progres number="3" active="2"
            activeColor="primary" inactiveColor="secondary" :hrefs="[
                route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index'),
                route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.index'),
                route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index'),
            ]" />

        <div class="container-fluid m-0 p-0">
            @include('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.C_ErrorPeriode')

            {{-- ================== --}}
            <form action="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.store') }}"
                method="post" id="JmlMahasiswaForm">
                @csrf
                <div class="container-fluid d-flex justify-content-center">
                    <div class="row justify-content-center">
                        @php
                            $no = 0;
                        @endphp
                        @foreach ($savedInformation['MaxBimbingan'] as $key => $value)
                            <div class="col-12 col-md-5 p-0 m-2">
                                @php
                                    $no++;
                                    $colors = [
                                        'bg-gradient-success',
                                        'bg-gradient-primary',
                                        'bg-gradient-warning',
                                        'bg-gradient-danger',
                                    ];

                                    $prefix = '';
                                    if (preg_match('/D1|D2|D3|D4|S1|S2|S3/', $value['nama_prodi'])) {
                                        preg_match('/^(D[1-4]|S[1-3])/', $value['nama_prodi'], $matches);
                                        $prefix = $matches[0];
                                        $prefix .= ' ';
                                    }

                                    $words = explode(' ', $value['nama_prodi']);
                                    foreach ($words as $index => $word) {
                                        if ($index === 0 && $prefix !== '') {
                                            continue;
                                        }
                                        $prefix .= $word[0];
                                    }
                                @endphp
                                <div class="{{ $colors[$no % count($colors)] }} rounded w-100">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="m-3">
                                                <div class="form-group m-0">
                                                    <input type="number"
                                                        class="form-control bg-transparent border-0 text-light p-0 fs-1"
                                                        min="0" id="val{!! str_replace(' ', '', $value['id_prodi']) !!}"
                                                        name="val{!! str_replace(' ', '', $value['id_prodi']) !!}"
                                                        {{ $savedInformation['Periode'] ? '' : 'disabled' }}
                                                        value="{{ old('val' . $value['id_prodi']) ? old('val' . $value['id_prodi']) : $value['jumlah'] }}">
                                                </div>
                                                <hr class="text-light m-0 border-light">
                                                <small>Mahasiswa {{ $value['nama_prodi'] }}</small>
                                            </div>
                                        </div>
                                        <div class="col-auto text-break">
                                            <strong>
                                                <h2 class="m-0 p-2 display-5 h-100 align-content-center">
                                                    <strong>{!! str_replace(' ', '<br>', $prefix) !!}</strong>
                                                </h2>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="container-fluid mt-3 d-none w-100 w-lg-50 text-center" id="warning">
                    <center>
                        <div class="alert alert-warning alert-dismissible d-none p-3">
                            <small>
                                <strong>Peringatan:</strong> Dengan mengisi kuota 0, anda menyatakan bahwa tidak bersedia
                                membimbing!
                            </small>
                        </div>
                        <div class="alert alert-warning alert-dismissible d-none p-3">
                            <small>
                                <strong>Peringatan:</strong> Maksimal jumlah mahasiswa adalah
                                @php
                                    $maxTotal = 0;
                                @endphp
                                @foreach ($batas_bimbingan as $key => $value)
                                    @php
                                        $maxTotal += $value['maksimal_mahasiswa_bimbingan'];
                                    @endphp

                                    {{ $value['maksimal_mahasiswa_bimbingan'] }} {{ $value['nama_prodi'] }}
                                    @if (!$loop->last)
                                        dan
                                    @else
                                        ,
                                    @endif
                                @endforeach
                                lebih dari itu honor hanya dihitung untuk {{ $maxTotal }} mahasiswa
                            </small>
                        </div>
                    </center>
                </div>
                {{-- ================== --}}
                <div class="container-fluid d-flex justify-content-center justify-content-md-end p-3 p-md-0 mt-2">
                    <button type="button" onclick="previousPage()" class="btn btn-info ml-3"><i
                            class="fas fa-chevron-left pl-1"></i></button>
                    <button type="submit" class="btn btn-primary ml-3"
                        {{ $savedInformation['Periode'] ? '' : 'disabled' }}><i class="fas fa-save pl-1"></i></button>
                    <button type="button" onclick="nextPage()" class="btn btn-info ml-3"><i
                            class="fas fa-chevron-right pl-1"></i></button>
                </div>
            </form>
        </div>

        <script>
            function checkWarning() {
                var maxTotal = {{ $maxTotal }};
                var maxRule = [];
                @foreach ($batas_bimbingan as $key => $value)
                    maxRule['val' + `{!! str_replace(' ', '', $value['id_prodi']) !!}`] = {{ $value['maksimal_mahasiswa_bimbingan'] }};
                @endforeach
                var total = 0
                var form = $('#JmlMahasiswaForm');

                for (const key in maxRule) {
                    if (Object.hasOwnProperty.call(maxRule, key)) {
                        const element = maxRule[key];
                        total += parseInt($('#' + key).val());
                        if (parseInt($('#' + key).val()) > element) {
                            $('#warning').removeClass('d-none');
                            $('#warning').find('div').first().addClass('d-none');
                            $('#warning').find('div').last().removeClass('d-none');
                            if (!toastShowed) {
                                toast('warning', 'Peringatan',
                                    'Maksimal jumlah mahasiswa adalah ' + maxTotal +
                                    ' mahasiswa, lebih dari itu honor hanya dihitung untuk ' + maxTotal + ' mahasiswa',
                                    5000);
                                toastShowed = true;
                            }
                            return;
                        }
                    }
                }
                if (total === 0) {
                    $('#warning').removeClass('d-none');
                    $('#warning').find('div').first().removeClass('d-none');
                    $('#warning').find('div').last().addClass('d-none');
                    if (!toastShowed) {
                        toast('warning', 'Peringatan',
                            'Dengan mengisi kuota 0, anda menyatakan bahwa tidak bersedia membimbing!',
                            5000);
                        toastShowed = true;
                    }
                    return;
                }


                $('#warning').addClass('d-none');
                $('#warning').find('div').addClass('d-none');
                toastShowed = false;
            }
        </script>
    @endif
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />

    @include('PengajuanAlokasiPembimbing.Helper.CSS.BoostrapExtend')
@stop

@section('js')
<<<<<<< HEAD
    @include('pengajuanalokasipembimbing.Helper.JS.SweetAlert')
    @include('pengajuanalokasipembimbing.Helper.JS.AutoFlashReader')
    @include('pengajuanalokasipembimbing.Helper.JS.AutoErrorShower')
=======
    @include('PengajuanAlokasiPembimbing.Helper.JS.SweetAlert')
    @include('PengajuanAlokasiPembimbing.Helper.JS.AutoFlashReader')
    @include('PengajuanAlokasiPembimbing.Helper.JS.AutoErrorShower')
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b

    <script>
        var toastShowed = false;
        $(document).ready(function() {
            checkWarning();
        });

<<<<<<< HEAD
        $("#valD3").bind('keyup mouseup', function() {
            $(this).val(parseInt($(this).val(), 10));
            checkWarning();
        });
        $("#valD4").bind('keyup mouseup', function() {
            $(this).val(parseInt($(this).val(), 10));
            checkWarning();
        });

        function checkWarning() {
            var D3 = $('#valD3').val();
            var D4 = $('#valD4').val();

            if (D3 == 0 && D4 == 0) {
                $('#warning').removeClass('d-none');
                $('#warning').find('div').first().removeClass('d-none');
                $('#warning').find('div').last().addClass('d-none');
                @if ($savedInformation['Periode'])
                    if (!toastShowed) {
                        toast('warning', 'Peringatan',
                            'Dengan mengisi kuota 0, anda menyatakan bahwa tidak bersedia membimbing!',
                            5000);
                        toastShowed = true;
                    }
                @endif
            } else if (D3 > 10 || D4 > 8) {
                $('#warning').removeClass('d-none');
                $('#warning').find('div').first().addClass('d-none');
                $('#warning').find('div').last().removeClass('d-none');
                if (!toastShowed) {
                    toast('warning', 'Peringatan',
                        'Maksimal jumlah mahasiswa adalah 10 D3 dan 8 D4, lebih dari itu honor hanya dihitung untuk 18 mahasiswa',
                        5000);
                    toastShowed = true;
                }
            } else {
                $('#warning').addClass('d-none');
                $('#warning').find('div').addClass('d-none');
            }
        }
=======
        $('#JmlMahasiswaForm input').each(function() {
            $(this).bind('keyup mouseup', function() {
                var value = parseInt($(this).val(), 10);
                if (!isNaN(value) && value >= 0) {
                    $(this).val(value);
                } else {
                    $(this).val(0);
                }
                checkWarning();
            });
        });
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b

        function previousPage() {
            $('#JmlMahasiswaForm').attr('action',
                "{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.next', ['previous' => '2', 'target' => '1']) }}"
            );
            $('#JmlMahasiswaForm').submit();
        }

        function nextPage() {
            $('#JmlMahasiswaForm').attr('action',
                "{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.next', ['previous' => '2', 'target' => '3']) }}"
            );
            $('#JmlMahasiswaForm').submit();
        }
    </script>
@stop
