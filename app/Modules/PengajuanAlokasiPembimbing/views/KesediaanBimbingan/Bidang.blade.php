@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Kesediaan Membimbing</h1>
@stop

@section('content')

    <p>Peminatan Bidang > <a
            href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.index') }}">Kuota
            Bimbingan</a> > <a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index') }}">Jadwal
            Kesediaan</a></p>

    @include('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.CardBar')

    <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.horizontal-progres number="3" active="1"
        activeColor="primary" inactiveColor="secondary" :hrefs="[
            route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.index'),
            route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index'),
        ]" />

    <div class="container-fluid m-0 p-0">

        <div id="errorContainer">
        </div>

        <div class="container-fluid bg-gradient-info rounded-top p-0">
            <div class="container-fluid">
                <div class="row row-cols-2 p-2">
                    <div class="col">
                        <p class="m-0">Daftar Bidang</p>
                    </div>
                    <div class="col text-right">
                        <button type="button" class="btn btn-sm p-0 m-0 bg-transparent text-light" data-toggle="modal"
                            data-target="#AddNewModal">
                            <i class="fas fa-plus"></i> Tambah Bidang
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ================== --}}

        <form action="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.store') }}" method="POST"
            id="MinatForm">
            @csrf
            <div class="container-fluid bg-secondary rounded-bottom bg-opacity-25 pre-scrollable mb-4">

                <div class="container text-left">
                    <div class="row row-cols-lg-2 row-cols-1 p-0 pt-2 pb-2 p-md-3 pt-md-3 pb-md-3">
                        @foreach ($bidang as $bidangitem)
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="bidang{{ $bidangitem->id_bidang }}"
                                        name="bidang[]" value="{{ $bidangitem->id_bidang }}">
                                    <label class="form-check-label" for="bidang{{ $bidangitem->id_bidang }}">
                                        {{ $bidangitem->bidang }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- ================== --}}
            <div class="container-fluid d-flex justify-content-end p-3 p-md-0 mt-2">
                <button type="submit" class="btn btn-primary ml-3">Simpan <i class="fas fa-save pl-1"></i></button>
                <button type="button" onclick="nextPage()" form="nextForm" class="btn btn-info ml-3">Berikutnya <i
                        class="fas fa-chevron-right pl-1"></i></button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="AddNewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.add') }}"
                    method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah bidang baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="form-group m-0">
                                <label for="bidang">Bidang</label>

                                @php
                                    $oldBidangValue = old('bidang');
                                    if (is_array($oldBidangValue)) {
                                        $oldBidangValue = null;
                                    }
                                @endphp

                                <input type="text" id="bidang" name="bidang" class="form-control"
                                    value="{{ $oldBidangValue }}" required>
                            </div>
                            <small class="text-danger d-none" id="addBidangError">* Bidang yang sudah ada tidak bisa
                                ditambahkan</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary" disabled id="modalSaveBtn">Simpan <i
                                class="fas fa-save"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let bidang_list = [];
        @foreach ($bidang as $bidangitem)
            bidang_list.push(`{{ $bidangitem->bidang }}`);
        @endforeach

        $('#bidang').on('input', function() {
            if (bidang_list.includes($(this).val())) {
                $('#modalSaveBtn').prop('disabled', true);
                $('#modalSaveBtn').addClass('btn-danger');
                $('#modalSaveBtn').removeClass('btn-primary');
                $('#addBidangError').removeClass('d-none');
            } else {
                $('#modalSaveBtn').prop('disabled', false);
                $('#modalSaveBtn').addClass('btn-primary');
                $('#modalSaveBtn').removeClass('btn-danger');
                $('#addBidangError').addClass('d-none');
            }
        });
    </script>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />

    @include('PengajuanAlokasiPembimbing.Helper.CSS.BoostrapExtend')
@stop

@section('js')
    @include('pengajuanalokasipembimbing.Helper.JS.AutoFlashReader')
    @include('pengajuanalokasipembimbing.Helper.JS.AutoErrorShower')

    <script>
        $('.form-check-input').on('click', function() {

            if ($('.form-check-input:checked').length > 5) {
                $(this).prop('checked', false);
                toast('warning', 'Peringatan', 'Maksimal 5 bidang yang dapat dipilih', 5000);
                return;
            }

            if ($(this).prop('checked')) {
                $(this).next().addClass('text-success');
                $(this).next().addClass('text-bold');
            } else {
                $(this).next().removeClass('text-success');
                $(this).next().removeClass('text-bold');
            }

            $('#jmlMinatBidangText').text($('.form-check-input:checked').length);
        });
        @foreach ($savedBidang as $bidang)
            $('#bidang{{ $bidang->id_bidang }}').trigger('click');
        @endforeach

        function nextPage() {
            $('#MinatForm').attr('action',
                "{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.next', ['previous' => '1', 'target' => '2']) }}"
            );
            $('#MinatForm').submit();
        }
    </script>

@stop
