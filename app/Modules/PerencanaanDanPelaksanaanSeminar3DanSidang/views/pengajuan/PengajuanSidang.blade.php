@extends('adminlte::page')

@section('title', 'Pengajuan Sidang')

@section('content_header')
    <h1 class="mx-2">Pengajuan Sidang Akhir</h1>
    <div class="ml-2">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => url('/pengajuan/'), 'label' => 'Daftar Pengajuan'],
                ['url' => '', 'label' => 'Pengajuan Sidang Akhir']
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show ml-2" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label=""></button>
    </div>
@endif
<div class="container-fluid">
    <div class="row w-100 justify-content-start d-flex align-items-stretch">
        <!-- Kolom Identitas KoTA -->
        <div class="col-md-6">
            <div class="card p-4 bg-light h-100">
                <h4><strong>{{ $dataKota->nama_kota }}</strong></h4>
                <p class="text-uppercase">{{ $dataKota->judul_ta }}</p>
                
                <div>
                    <h4 class="text-bold px-3">Mahasiswa</h4>
                    <ul>
                        @foreach($mahasiswa as $mhs)
                            <li><h5>{{ $mhs->nim }} - {{ $mhs->nama }}</h5></li>
                        @endforeach
                    </ul>
                </div> 
                
                <div>
                    <h4 class="text-bold px-3">Pembimbing</h4>
                    <ul>
                        @foreach($pembimbing as $pb)
                            <li><h5>{{ $pb->username }} - {{ $pb->nama }}</h5></li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-bold px-3">Penguji</h4>
                    <ul>
                        @foreach($penguji as $pj)
                            <li><h5>{{ $pj->username }} - {{ $pj->nama }}</h5></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Kolom Waktu dan Tempat Pelaksanaan -->
        <div class="col-md-6">
            <div class="card p-4 bg-light h-100">
                <h4>Waktu dan Tempat Pelaksanaan</h4>
                <form action="{{ route('pengajuan-tambah', ['id_kota' => $dataKota->id_kota]) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="tanggal_pengajuan" class="form-label">Tanggal:</label>
                        <input type="date" id="tanggal_pengajuan" name="tanggal_pengajuan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="sesi_pengajuan" class="form-label">Sesi:</label>
                        <select id="sesi_pengajuan" name="sesi_pengajuan" class="form-control" required>
                            <option value="" disabled selected>-- Pilih Sesi --</option>
                            <option value="1">Sesi I</option>
                            <option value="2">Sesi II</option>
                            <option value="3">Sesi III</option>
                            <option value="4">Sesi IV</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label for="ruangan_pengajuan" class="form-label">Ruangan:</label>
                        <select id="ruangan_pengajuan" name="ruangan_pengajuan" class="form-control" required>
                            <option value="" disabled selected>-- Pilih Ruangan --</option>
                            @foreach($ruanganTersedia as $ruangan)
                                <option value='@json(["id" => $ruangan["id_ruangan"], "nama" => $ruangan["nama_ruangan"]])'>
                                    {{ $ruangan['nama_ruangan'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input hidden untuk mengirim agenda -->
                    <input type="hidden" name="agenda" value="sidang">

                    <!-- Checkbox untuk menyetujui -->
                    <div class="mb-3 form-check text-center">
                        <input type="checkbox" id="setuju" class="form-check-input">
                        <label for="setuju" class="form-check-label">Saya menyetujui data yang diajukan sudah benar</label>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-md my-1" id="ajukan-btn" disabled>Ajukan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkbox = document.getElementById("setuju");
        const submitButton = document.getElementById("ajukan-btn");

        checkbox.addEventListener("change", function() {
            submitButton.disabled = !checkbox.checked;
        });
    });

    document.getElementById('tanggal_pengajuan').addEventListener('focus', function() {
        this.showPicker();
    });
</script>
<script>
    setTimeout(function() {
        let alert = document.querySelector(".alert");
        if (alert) {
            alert.style.transition = "opacity 0.5s";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000);
</script>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop