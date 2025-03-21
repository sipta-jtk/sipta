@extends('adminlte::page')

@section('title', 'Detail Plagiarism Check')

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Judul Halaman -->
    <h1 class="mb-0">Detail Plagiarism Check</h1>
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="me-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/cek-plagiarisme') }}">Plagiarism Checking</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Laporan</li>
        </ol>
    </nav>
</div>
@stop


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- Kolom untuk Dokumen -->
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h3 class="mb-0">{{ $dokumen->judul ?? 'Judul Dokumen Contoh' }}</h3>
                </div>
                <div class="card-body text-center">
                    @if(isset($dokumen->file))
                        <iframe src="{{ asset('storage/' . $dokumen->file) }}" width="100%" height="600px"></iframe>
                    @else
                        <pre class="p-3 bg-light border rounded"
                            style="height: 500px; overflow-y: auto;">{{ $dokumen->isi ?? 'Isi dokumen tidak tersedia' }}</pre>
                    @endif
                </div>
            </div>
        </div>

        <!-- Navigasi Tab -->
        <div class="col-md-4">
            <ul class="nav nav-tabs mb-3" id="detailTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="detail-tab" data-bs-toggle="tab" href="#detail" role="tab">📋
                        Detail</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="sumber-tab" data-bs-toggle="tab" href="#sumber" role="tab">📄 Sumber</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="unduh-tab" data-bs-toggle="tab" href="#unduh" role="tab">⬇️ Unduh</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="catatan-tab" data-bs-toggle="tab" href="#catatan" role="tab">💬 Catatan</a>
                </li>

            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="tabContent">
                <!-- Detail Dokumen -->
                <div class="tab-pane fade show active" id="detail" role="tabpanel">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h5>Detail Dokumen</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Penulis</th>
                                        <td>{{ $dokumen->user->nama ?? 'Nama Tidak Tersedia' }}</td> <!-- Data penulis dari database -->
                                    </tr>
                                    <tr>
                                        <th>Judul Dokumen</th>
                                        <td>{{ $dokumen->judul }}</td> <!-- Data judul dari database -->
                                    </tr>
                                    <tr>
                                        <th>Nama Dokumen</th>
                                        <td>{{ $dokumen->nama_dokumen ?? 'Dokumen Tidak Ditemukan' }}</td> <!-- Data nama dokumen -->
                                    </tr>
                                    <tr>
                                        <th>Tanggal Unggah</th>
                                        <td>{{ \Carbon\Carbon::parse($dokumen->created_at)->format('d-m-Y') }}</td> <!-- Format tanggal -->
                                    </tr>
                                    <tr>
                                        <th>Jumlah Halaman</th>
                                        <td>{{ $dokumen->jumlah_halaman ?? 'Tidak Tersedia' }}</td> <!-- Data halaman -->
                                    </tr>
                                    <tr>
                                        <th>Ukuran Dokumen</th>
                                        <td>{{ $dokumen->ukuran_file }} MB</td> <!-- Ukuran file -->
                                    </tr>
                                    <tr>
                                        <th>Ambang Batas</th>
                                        <td>{{ $dokumen->ambangBatas->ambang_batas ?? 'Tidak Diketahui' }}%</td> <!-- Data ambang batas -->
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sumber Plagiarisme -->
                <div class="tab-pane fade" id="sumber" role="tabpanel">
                    <div class="card shadow-lg">
                        <div class="card-header bg-danger text-white">
                        <h5>Daftar Sumber - 
                            @if(fmod($dokumen->persentase_plagiarisme, 1) == 0)
                                {{ number_format($dokumen->persentase_plagiarisme, 0) }}%
                            @else
                                {{ number_format($dokumen->persentase_plagiarisme, 1) }}%
                            @endif
                            Index Kesamaan
                        </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach($sumberPlagiarisme as $item)
                                        <tr>
                                            <td>{{ $item->listJurnalPlagiarisme->judul ?? 'Judul Jurnal Tidak Tersedia' }}</td>
                                            <td>
                                                @if(fmod($item->listJurnalPlagiarisme->persentase_kemunculan, 1) == 0)
                                                    {{ number_format($item->listJurnalPlagiarisme->persentase_kemunculan, 0) }}%
                                                @else
                                                    {{ number_format($item->listJurnalPlagiarisme->persentase_kemunculan, 1) }}%
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Unduh Dokumen -->
                <div class="tab-pane fade" id="unduh" role="tabpanel">
                    <div class="card shadow-lg">
                        <div class="card-header bg-secondary text-white">
                            <h5>Unduh Dokumen</h5>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-primary w-100">📥 Unduh Dokumen Hasil Pengecekan</button>
                            <button class="btn btn-secondary w-100 mt-2">📥 Unduh Bukti Penerimaan Digital</button>
                            <button class="btn btn-success w-100 mt-2">📥 Unduh Dokumen Asli</button>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="tab-pane fade" id="catatan" role="tabpanel">
                    <div class="card shadow-lg">
                        <div class="card-header bg-info text-white">
                            <h5>Catatan</h5>
                        </div>
                        <div class="card-body">
                            @include('CekPlagiarisme.views.catatan')
                        </div>
                    </div>
                </div>

            </div> <!-- End Tab Content -->
        </div> <!-- End Col -->
    </div> <!-- End Row -->
</div> <!-- End Container -->
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Bootstrap Tab Switching
        var sidebarTabs = document.querySelectorAll("#detailTabs .nav-link");
        sidebarTabs.forEach(function (tab) {
            tab.addEventListener("click", function (event) {
                event.preventDefault(); // Hindari reload halaman

                // Aktifkan tab yang diklik
                var tabTrigger = new bootstrap.Tab(this);
                tabTrigger.show();
            });
        });
    });
</script>
@stop