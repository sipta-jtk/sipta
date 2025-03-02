@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Kesediaan Membimbing</h1>
@stop

@section('content')

    <p>Peminatan Bidang > <a href="www">Kuota Bimbingan</a> > <a href="www">Jadwal Kesediaan</a></p>

    <div class="row w-100 justify-content-center">
        <div class="col-lg-3 col-6 ml-2 mr-2">
            <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="info"
                innerHtml="<h3>150</h3><p>Bidang Diminati</p>" icon="fas fa-graduation-cap" href="#"
                hrefText="More info" />
        </div>
        <div class="col-lg-3 col-6 ml-2 mr-2">
            <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="success"
                innerHtml="<h3 class='mb-2'>5<sup style='font-size: 20px'> mahasiswa D3</sup><br>6<sup style='font-size: 20px'> mahasiswa D4</sup></h3>"
                icon="fas fa-users" href="#" hrefText="More info" />
        </div>
        <div class="col-lg-3 col-6 ml-2 mr-2">
            <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="warning"
                innerHtml="<h3>3 <sup style='font-size: 20px'>Hari</sup><br>5 <sup style='font-size: 20px'>Sesi</sup></h3>"
                icon="fas fa-calendar-alt" href="#" hrefText="More info" />
        </div>
    </div>

    <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.horizontal-progres number="3" active="1"
        activeColor="primary" inactiveColor="secondary" :hrefs="['#', '#', '#']" />

    <div class="container-fluid bg-gradient-info rounded-top">
        <div class="container">
            <div class="row row-cols-2 p-2">
                <div class="col">
                    <p class="m-0">Daftar Bidang</p>
                </div>
                <div class="col text-right">
                    <a class="m-0 text-light"> <i class="fas fa-plus"></i> Tambah Bidang</a>
                </div>
            </div>
        </div>
    </div>
    {{-- ================== --}}
    <div class="container-fluid bg-secondary rounded-bottom bg-opacity-25 pre-scrollable mb-4">

        <div class="container text-center ">
            <div class="row row-cols-3 p-3">
                @for ($i = 0; $i < 500; $i++)
                    <div class="col">
                        <div class="pretty p-default p-fill">
                            <input type="checkbox" />
                            <div class="state p-info text-left" style="min-width: 150px;">
                                <label>
                                    Bidang {{ $i + 1 }}
                                </label>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
    {{-- ================== --}}
    <div class="container-fluid d-flex justify-content-end p-0">
        <button type="button" class="btn btn-primary ml-3">Simpan <i class="fas fa-save pl-1"></i></button>
        <button type="button" class="btn btn-info ml-3">Berikutnya <i class="fas fa-chevron-right pl-1"></i></button>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

{{-- @section('js')
@stop --}}
