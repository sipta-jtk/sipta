@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Kesediaan Membimbing</h1>
@stop

@section('content')
    @php
        $AllowEdit =
            $savedInformation['StatusBersediaMembimbing'] == 'tidak_bersedia' || !$savedInformation['Periode']
                ? false
                : true;
    @endphp

    @if ($savedInformation['StatusBersediaMembimbing'] == 'belum_konfirmasi')
        <p> <a href="/">Beranda</a> / Formulir Kesediaan Membimbing</p>
        @include('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.V_KonfirmasiBersedia')
    @else
        <p> <a href="/">Beranda</a> / Peminatan Bidang / <a
                href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.index') }}">Kuota
                Bimbingan</a> / <a href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index') }}">Jadwal
                Kesediaan</a></p>

        <div class="card p-3">
            @include('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.C_CardBar')


            <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.horizontal-progres number="3" active="1"
                activeColor="primary" inactiveColor="secondary" :hrefs="[
                    route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index'),
                    route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.index'),
                    route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index'),
                ]" />

            <div class="container-fluid m-0 p-0">

                <div id="errorContainer">
                </div>

                @include('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.C_ErrorPeriode')

                {{-- ================== --}}

                <div class="container-fluid bg-gradient-info rounded-top p-0">
                    <div class="container-fluid">
                        <div class="row row-cols-2 p-2">
                            <div class="col">
                                <p class="m-0">Daftar Bidang</p>
                            </div>
                            <div class="col text-right">
                                <button type="button" class="btn btn-sm p-0 m-0 bg-transparent text-light"
                                    data-toggle="modal" data-target="#AddNewModal" {{ $AllowEdit ? '' : 'disabled' }}>
                                    <i class="fas fa-plus"></i> Tambah Bidang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ================== --}}

                <form action="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.store') }}"
                    method="POST" id="MinatForm">
                    @csrf
                    <div class="container-fluid bg-light rounded-bottom bg-opacity-25 pre-scrollable mb-4 border">

                        <div class="container text-left">
                            <div class="row row-cols-lg-2 row-cols-1 p-0 pt-2 pb-2 p-md-3 pt-md-3 pb-md-3">
                                @foreach ($bidang as $bidangitem)
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="bidang{{ $bidangitem->id_bidang }}" name="bidang[]"
                                                value="{{ $bidangitem->id_bidang }}" {{ $AllowEdit ? '' : 'disabled' }}>
                                            <label class="form-check-label text-dark"
                                                for="bidang{{ $bidangitem->id_bidang }}">
                                                {{ $bidangitem->bidang }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid form-check">
                        <input type="checkbox" id="checkAll" class="form-check-input" disabled
                            {{ $AllowEdit ? '' : 'disabled' }}>
                        <label class="form-check-label text-dark" for="checkAll">Pilih Semua</label>
                    </div>


                    {{-- ================== --}}
                    <div class="container-fluid d-flex justify-content-end mt-2 pl-0 pt-3 pb-3 pr-0">
                        <button type="submit" class="btn btn-primary ml-3" {{ $AllowEdit ? '' : 'disabled' }}><i
                                class="fas fa-save pl-1"></i></button>
                        <button type="button" onclick="nextPage()" form="nextForm" class="btn btn-info ml-3"><i
                                class="fas fa-chevron-right pl-1"></i></button>
                    </div>
                </form>
            </div>

        </div>

        @if ($AllowEdit)
            <x-adminlte-modal id="AddNewModal" title="Tambah bidang baru" theme="primary" size="md" centered
                theme="blue">
                <form action="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.add') }}"
                    method="POST" id="addNewForm">
                    @csrf
                    <div class="form-group">
                        <label for="bidang">Bidang</label>

                        @php
                            $oldBidangValue = old('bidang');
                            if (is_array($oldBidangValue)) {
                                $oldBidangValue = null;
                            }
                        @endphp

                        <x-adminlte-input type="text" id="bidang" name="bidang" class="form-control"
                            value="{{ $oldBidangValue }}" required />
                        <small class="text-danger d-none" id="addBidangError">* Bidang yang sudah ada tidak bisa
                            ditambahkan</small>
                    </div>
                    <x-slot name="footerSlot">
                        <x-adminlte-button label="Tutup" data-dismiss="modal" theme="danger" class="btn-sm" />
                        <x-adminlte-button label="Simpan" type="submit" theme="success" class="btn-sm" id="modalSaveBtn"
                            form="addNewForm" disabled />
                    </x-slot>
                </form>
            </x-adminlte-modal>
        @endif
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            let bidang_list = [];
            @foreach ($bidang as $bidangitem)
                bidang_list.push(`{{ $bidangitem->bidang }}`);
            @endforeach

            $('#bidang').on('input', function() {
                if (bidang_list.map(b => b.toLowerCase()).includes($(this).val().toLowerCase())) {
                    $('#modalSaveBtn').prop('disabled', true);
                    $('#modalSaveBtn').addClass('btn-secondary');
                    $('#modalSaveBtn').removeClass('btn-success');
                    $('#addBidangError').removeClass('d-none');
                } else {
                    $('#modalSaveBtn').prop('disabled', false);
                    $('#modalSaveBtn').addClass('btn-success');
                    $('#modalSaveBtn').removeClass('btn-secondary');
                    $('#addBidangError').addClass('d-none');
                }
            });
        </script>
    @endif
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />

    @include('PengajuanAlokasiPembimbing.Helper.CSS.BoostrapExtend')
@stop

@section('js')
    @include('PengajuanAlokasiPembimbing.Helper.JS.AutoFlashReader')
    @include('PengajuanAlokasiPembimbing.Helper.JS.AutoErrorShower')

    <script>
        $('.form-check-input').on('click', function() {
            if ($(this).attr('id') == 'checkAll') {
                return;
            }

            if ($(this).prop('checked')) {
                $(this).next().removeClass('text-dark');
                $(this).next().addClass('text-success');
                $(this).next().addClass('text-bold');
            } else {
                $(this).next().addClass('text-dark');
                $(this).next().removeClass('text-success');
                $(this).next().removeClass('text-bold');
            }

            $('#jmlMinatBidangText').text($('.form-check-input:checked').length);

            if ($('.form-check-input:checked').length != $('.form-check-input').length - 2) {
                $('#checkAll').prop('checked', false);
            }
        });

        $('#checkAll').on('click', function() {
            for (let i = 0; i < $('.form-check-input').length; i++) {
                const element = $('.form-check-input')[i];
                if ($(element).attr('id') == 'checkAll') {
                    continue;
                }

                if ($(this).prop('checked')) {
                    $(element).prop('checked', true);
                    $(element).next().removeClass('text-dark');
                    $(element).next().addClass('text-success');
                    $(element).next().addClass('text-bold');
                } else {
                    $(element).prop('checked', false);
                    $(element).next().addClass('text-dark');
                    $(element).next().removeClass('text-success');
                    $(element).next().removeClass('text-bold');
                }
            }
        });

        @foreach ($savedBidang as $bidang)
            var disabled = $('#bidang{{ $bidang->id_bidang }}').prop('disabled');
            if (disabled) {
                $('#bidang{{ $bidang->id_bidang }}').prop('disabled', false);
                $('#bidang{{ $bidang->id_bidang }}').trigger('click');
                $('#bidang{{ $bidang->id_bidang }}').prop('disabled', true);
            } else {
                $('#bidang{{ $bidang->id_bidang }}').trigger('click');
            }
        @endforeach

        function nextPage() {
            $('#MinatForm').attr('action',
                "{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.next', ['previous' => '1', 'target' => '2']) }}"
            );
            $('#MinatForm').submit();
        }
    </script>

@stop
