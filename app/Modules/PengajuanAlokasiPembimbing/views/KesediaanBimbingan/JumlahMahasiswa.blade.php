@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Kesediaan Membimbing</h1>
@stop

@section('content')

    <p><a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index') }}">Peminatan Bidang</a> >
        Kuota
        Bimbingan > <a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index') }}">Jadwal
            Kesediaan</a></p>

    @include('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.CardBar')


    <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.horizontal-progres number="3" active="2"
        activeColor="primary" inactiveColor="secondary" :hrefs="[
            route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.index'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index'),
        ]" />

    <div class="container-fluid m-0 p-0">

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
            <div class="container-fluid d-flex justify-content-end p-3 p-md-0 d-none mt-2">
                <button type="button" onclick="previousPage()" class="btn btn-info ml-3">Sebelumnya <i
                        class="fas fa-chevron-left pl-1"></i></button>
                <button type="submit" class="btn btn-primary ml-3">Simpan <i class="fas fa-save pl-1"></i></button>
                <button type="button" onclick="nextPage()" class="btn btn-info ml-3">Berikutnya <i
                        class="fas fa-chevron-right pl-1"></i></button>
            </div>
        </form>
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
    @include('pengajuanalokasipembimbing.Helper.JS.AutoFlashReader')
    @include('pengajuanalokasipembimbing.Helper.JS.AutoErrorShower')

    <script>
        var toastShowed = false;
        $(document).ready(function() {
            checkWarning();
        });

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
                if (!toastShowed) {
                    toast('warning', 'Peringatan',
                        'Dengan mengisi kuota 0, anda menyatakan bahwa tidak bersedia membimbing!',
                        5000);
                    toastShowed = true;
                }
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
