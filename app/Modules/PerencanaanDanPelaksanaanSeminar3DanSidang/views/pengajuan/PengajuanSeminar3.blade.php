@extends('adminlte::page')

@section('title', 'Pengajuan Seminar dan Sidang')

@section('content_header')
    <h1 class="ml-2"><strong>Pengajuan Seminar 3</strong></h1>
@stop

@section('content')
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label=""></button>
    </div>
@endif
<div class="container-fluid row w-100 d-flex align-items-stretch">
    <!-- Kolom Identitas KoTA -->
    <div class="col-md-6 d-flex">
        <div class="card p-4 bg-light w-100">
            <div>
                <h4><strong>{{ $dataKota->nama_kota }}</strong></h4>
                <p class="text-uppercase">{{ $dataKota->judul_ta }}</p>
                
                <div>
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
                            @foreach($pembimbing as $dosen)
                                <li><h5>{{ $dosen->username }} - {{ $dosen->nama }}</h5></li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-bold px-3">Penguji</h4>
                        <ul>
                            @foreach($penguji as $dosen)
                                <li><h5>{{ $dosen->username }} - {{ $dosen->nama }}</h5></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Waktu dan Tempat Pelaksanaan -->
    <div class="col-md-6 d-flex">
        <div class="card p-4 bg-light w-100">
            <div>
                <h4>Waktu dan Tempat Pelaksanaan</h4>
                <!-- <form action="" method="POST"> -->
                <form action="{{ route('pengajuan-tambah', ['id_kota' => $dataKota->id_kota]) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="tanggal_pengajuan" class="form-label">Tanggal:</label>
                        <input type="date" id="tanggal_pengajuan" name="tanggal_pengajuan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="sesi_pengajuan" class="form-label">Sesi:</label>
                        <select id="sesi_pengajuan" name="sesi_pengajuan" class="form-control" required>
                            <option value="" disabled selected>Pilih Sesi</option>
                            <option value="1">Sesi I</option>
                            <option value="2">Sesi II</option>
                            <option value="3">Sesi III</option>
                            <option value="4">Sesi IV</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label for="ruangan_pengajuan" class="form-label">Ruangan:</label>
                        <select id="ruangan_pengajuan" name="ruangan_pengajuan" class="form-control" required>
                            <option value="" disabled selected>Pilih Ruangan</option>
                            @foreach($ruanganTersedia as $ruangan)
                                <option value="{{ $ruangan['id_ruangan'] }}">
                                    {{ $ruangan['kode_ruangan'] }} - {{ $ruangan['nama_ruangan'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input hidden untuk mengirim agenda -->
                    <input type="hidden" name="agenda" value="seminar_3">

                    <!-- Checkbox untuk menyetujui -->
                    <div class="mb-3 form-check text-center">
                        <input type="checkbox" id="setuju" class="form-check-input">
                        <label for="setuju" class="form-check-label">Saya menyetujui data yang diajukan sudah benar</label>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="ajukan-btn" disabled>Ajukan</button>
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
</script>
<script>
    setTimeout(function() {
        let alert = document.querySelector(".alert");
        if (alert) {
            alert.style.transition = "opacity 0.5s";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }
    }, 7000);
</script>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop