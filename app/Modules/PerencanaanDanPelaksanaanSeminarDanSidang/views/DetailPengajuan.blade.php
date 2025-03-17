@extends('adminlte::page')

@section('title', 'Detail Pengajuan')

@section('content_header')
    <h1>Detail Pengajuan</h1>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Informasi Pengajuan -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Pengajuan</h3>
            </div>
            <div class="card-body">
                <p><strong>Kelompok:</strong> {{ $dataKota->kelompok }}</p>
                <p><strong>Judul:</strong> {{ $dataKota->judul_ta }}</p>
                <p><strong>Status:</strong> {{ $dataKota->jenis_pengajuan }}</p>
                <p><strong>Tanggal:</strong> {{ $dataKota->tanggal }}</p>
            </div>
        </div>

        <!-- Berkas Pengajuan -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Berkas Pengajuan</h3>
            </div>
            <div class="card-body container-scroll">
                @foreach($dataKota->berkas as $berkas)
                    <div class="file-preview">
                        <h5>{{ $berkas->nama }}</h5>
                        <iframe src="{{ asset('storage/berkas/' . $berkas->file) }}" width="100%" height="400px"></iframe>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Form Verifikasi -->
        <form action="{{ route('perencanaan.kelola-pengajuan.verifikasi', ['id' => $dataKota->kelompok]) }}" method="POST">
            @csrf
            <input type="hidden" name="keputusan" id="keputusan" value="">
            <input type="hidden" name="catatan" id="catatan_input" value="">

            <!-- Tombol Tolak & Setuju -->
            <div class="d-flex justify-content-center gap-2 mt-3">
                <button type="button" class="btn btn-danger mx-2" id="btnTolak">Tolak</button>
                <button type="button" class="btn btn-success mx-2" id="btnSetuju">Setuju</button>
            </div>

            <!-- Container Catatan (Tersembunyi Awalnya) -->
            <div class="card mt-3" id="containerCatatan" style="display: none;">
                <div class="card-body">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea class="form-control" id="catatan" rows="3"></textarea>
                </div>
            </div>

            <!-- Tombol Kirim -->
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary" id="btnKirim" disabled>Kirim</button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .container-scroll {
            max-height: 500px;
            overflow-y: auto;
        }
        .file-preview {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .active-btn {
            opacity: 1;
        }
        .disabled-btn {
            opacity: 0.5;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            let keputusan = ""; // Menyimpan keputusan user

            // Event ketika tombol "Tolak" diklik
            $('#btnTolak').on('click', function() {
                keputusan = 'Ditolak';
                $('#keputusan').val(keputusan);
                $('#containerCatatan').slideDown(); // Menampilkan container catatan
                $('#btnSetuju').removeClass('active-btn').addClass('disabled-btn');
                $(this).addClass('active-btn').removeClass('disabled-btn');
                $('#btnKirim').prop('disabled', false);
            });

            // Event ketika tombol "Setuju" diklik
            $('#btnSetuju').on('click', function() {
                keputusan = 'Disetujui';
                $('#keputusan').val(keputusan);
                $('#containerCatatan').slideUp(); // Menyembunyikan container catatan
                $('#btnTolak').removeClass('active-btn').addClass('disabled-btn');
                $(this).addClass('active-btn').removeClass('disabled-btn');
                $('#btnKirim').prop('disabled', false);
            });

            // Saat Submit, masukkan catatan ke input hidden
            $('form').on('submit', function() {
                if (keputusan === 'Ditolak') {
                    $('#catatan_input').val($('#catatan').val());
                }
            });
        });
    </script>
@stop
