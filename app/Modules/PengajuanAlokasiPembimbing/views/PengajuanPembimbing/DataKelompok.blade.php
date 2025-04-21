@extends('adminlte::page')

@section('title', 'Formulir Pengajuan Dosen Pembimbing')

@section('content_header')
    <div class="m-3">
        <h1>Formulir Pengajuan Dosen Pembimbing</h1>
    </div>
@stop

@section('content')

    {{-- <p>Data Kelompok > <a href="www">Topik Tugas Akhir</a> > <a href="www">Prioritas Dosen Pembimbing</a> > <a href="www">Pratinjau</a></p> --}}

    <div id="warningMessage" class="alert alert-danger" role="alert" style="display: none;"> </div>

    <div class="container-fluid row w-100 justify-content-start">
        <div class="card p-4 bg-light">
            <x-pengajuan-alokasi-pembimbing.components.pengajuan-pembimbing.form-stepper step="4" currentStep="1"
                activeColor="primary" inactiveColor="secondary" 
                :hrefs="[
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.data-kelompok'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.topik-tugas-akhir'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.prioritas-dosen-pembimbing.index'),
                    route('pengajuanalokasipembimbing.pengajuan-pembimbing.pratinjau-formulir.index')]"/>
        </div>

        <!-- Form Pengajuan -->
        <div class="col">
            <div class="card p-4 bg-light">
                <h5 class="mb-3">Data Mahasiswa</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" value="{{ $sessionUser->nama }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control" value="{{ $sessionUser->kelas }}" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" value="{{ $sessionUser->nim }}" readonly>
                    </div>
                </div>

                <h5 class="mb-3">Data Kelompok</h5>
                @php
                    $anggota1 = $dataAnggota[0] ?? (object) ['nama' => '-', 'nim' => '-'];
                    $anggota2 = $dataAnggota[1] ?? (object) ['nama' => '-', 'nim' => '-'];
                @endphp
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Anggota 1</label>
                        <input type="text" class="form-control" value="{{ $anggota1->nama }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" value="{{ $anggota1->nim }}" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Anggota 2</label>
                        <input type="text" class="form-control" value="{{ $anggota2->nama }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" value="{{ $anggota2->nim }}" readonly>
                    </div>
                </div>

                <!-- Tombol Ubah Data & Selanjutnya -->
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" id="ubahData" class="btn btn-primary" onclick="window.location.href='{{ route('kota.saya') }}'">Ubah Data</button>
                    <a href={{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.topik-tugas-akhir') }}
                        class="btn btn-info ml-3">Selanjutnya</a>
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
    @include('PengajuanAlokasiPembimbing.Helper.JS.SweetAlert')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mengecek apakah mahasiswa sudah memiliki pengajuan
            $.get("{{ route('pengajuanalokasipembimbing.pengajuan-pembimbing.checkExistingData') }}", function(response) {
                if (response.hasExistingData || response.Periode === false) {
                    // Jika sudah ada data, nonaktifkan tombol dan tampilkan pesan peringatan
                    $("#ubahData, .btn-primary[type='submit']").prop("disabled", true); // Menonaktifkan tombol
                    $("#warningMessage").show(); // Menampilkan pesan peringatan

                    if (response.Periode === false) {
                        $("#warningMessage").html("Periode pengajuan dosen pembimbing belum dibuka.");
                    }
                    else {
                        $("#warningMessage").html("Anda telah mengirim dan finalisasi formulir ini. Pengajuan tidak dapat dilakukan lagi.");
                    }
                    if (response.hasExistingData && response.Periode === false) {
                        $("#warningMessage").html("Anda telah melakukan finalisasi formulir dan periode pengisian telah berakhir. Terima kasih.");
                    }
                }
            });

            Swal.fire({
                icon: 'info',
                title: 'Periksa kembali data anggota kelompok',
                text: 'Jika diperlukan perubahan, harap lakukan penyesuaian pada pengaturan data KoTA sebelum mengajukan dosen pembimbing.',
                cancelButtonText: 'Ubah Data',
                confirmButtonText: 'OK',
                showCancelButton: true,
                cancelButtonColor: '#3085d6',
                confirmButtonColor: '#3085d6',
                reverseButtons: true

            }).then((result) => {
                if (result.isConfirmed) {
                    // Handle OK Button Click (optional)
                    console.log('User clicked OK');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Redirect user to the route 'kota.saya' when clicking "Ubah Data"
                window.location.href = '{{ route('kota.saya') }}'; // This uses the Laravel route helper
                }
            });
        });
    </script>
@stop
