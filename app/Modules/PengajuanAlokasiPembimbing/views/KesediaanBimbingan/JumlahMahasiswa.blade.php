@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Kesediaan Membimbing</h1>
@stop

@section('content')

    <p><a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index') }}">Peminatan Bidang</a> >
        Kuota
        Bimbingan > <a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal') }}">Jadwal
            Kesediaan</a></p>

    <center>
        <div class="row w-100 justify-content-center">
            <div class="col-lg-3 col ml-2 mr-2">
                <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="info"
                    innerHtml="<h3>150</h3><p>Bidang Diminati</p>" icon="fas fa-graduation-cap"
                    href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index') }}"
                    hrefText="More info" />
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

    <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.horizontal-progres number="3" active="2"
        activeColor="primary" inactiveColor="secondary" :hrefs="[
            route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal'),
        ]" />

    <div class="container-fluid m-0 p-0">

        {{-- ================== --}}

        <div class="container-fluid d-flex justify-content-center">
            <div class="row w-100">
                <div class="col d-flex justify-content-end">
                    <div class="bg-gradient-success rounded w-100 w-md-100 w-lg-50">
                        <div class="row">
                            <div class="col-sm">
                                <div class="m-3">
                                    <div class="form-group m-0">
                                        <input type="number"
                                            class="form-control bg-transparent border-0 text-light p-0 fs-1" value="0"
                                            min="0" id="valD3">
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
                                            class="form-control bg-transparent border-0 text-light p-0 fs-1" value="0"
                                            min="0" id="valD4">
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
        <div class="container-fluid mt-3 d-none" id="warning">
            <center>
                <span class="btn rounded-pill badge-danger p-1">
                    <small>
                        Dengan mengisi kuota 0, anda menyatakan bahwa tidak bersedia membimbing!
                    </small>
                </span>
            </center>
        </div>
        {{-- ================== --}}
        <div class="container-fluid d-flex justify-content-end p-3 p-md-0 d-none">
            <button type="button" class="btn btn-primary ml-3">Simpan <i class="fas fa-save pl-1"></i></button>
            <button type="button" class="btn btn-info ml-3">Berikutnya <i class="fas fa-chevron-right pl-1"></i></button>
        </div>
    </div>

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />

    @include('PengajuanAlokasiPembimbing.Helper.CSS.BoostrapExtend')
@stop

@section('js')
    @include('pengajuanalokasipembimbing.Helper.JS.SweetAlert')

    <script>
        $(document).ready(function() {
            checkWarning();
        });

        $("#valD3").bind('keyup mouseup', function() {
            checkWarning();
        });
        $("#valD4").bind('keyup mouseup', function() {
            checkWarning();
        });

        function checkWarning() {
            var D3 = $('#valD3').val();
            var D4 = $('#valD4').val();

            if (D3 == 0 && D4 == 0) {
                $('#warning').removeClass('d-none');
                toast('warning', 'Peringatan', 'Dengan mengisi kuota 0, anda menyatakan bahwa tidak bersedia membimbing!',
                    5000);

            } else {
                $('#warning').addClass('d-none');
            }
        }
    </script>
@stop
