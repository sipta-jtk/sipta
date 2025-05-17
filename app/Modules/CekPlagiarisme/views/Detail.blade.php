@extends('adminlte::page')

@section('title', 'Detail Plagiarism Check')

@section('content_header')
@php
$prefix = env('PREFIX_URL', '');
@endphp
<div class="mb-4">
    <h1 class="mb-3">Detail Pengecekan Plagiarisme</h1>

    @component('KelolaPenilaianTA.views.components.breadcrumb', [
        'links' => [
            ['url' => url($prefix . '/'), 'label' => 'Beranda'],
            ['url' => url($prefix . '/cek-plagiarisme'), 'label' => 'Pengecekan Plagiarisme'],
            ['url' => '', 'label' => 'Detail Laporan']
        ]
    ])
    @endcomponent
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
                    @if(isset($dokumen->file_path))
                    <iframe src="{{ asset('storage/' . $dokumen->file_path) }}" width="100%" height="600px"></iframe>
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
                    <a class="nav-link" id="sumber-tab" data-bs-toggle="tab" href="#sumber" role="tab">
                        <i class="fas fa-file-alt me-1"></i> Sumber
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="detail-tab" data-bs-toggle="tab" href="#detail" role="tab">
                        <i class="fas fa-clipboard-list me-1"></i> Detail
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="unduh-tab" data-bs-toggle="tab" href="#unduh" role="tab">
                        <i class="fas fa-download me-1"></i> Unduh
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="catatan-tab" data-bs-toggle="tab" href="#catatan" role="tab">
                        <i class="fas fa-comments me-1"></i> Catatan
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="tabContent">
                <!-- Detail Dokumen -->
                <div class="tab-pane fade show active" id="detail" role="tabpanel">
                    <div class="card shadow-lg">
                        <div class="card-header bg-dark text-white">
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
                                        <td>{{ \Carbon\Carbon::parse($dokumen->created_at)->format('d F Y') }}</td> <!-- Format tanggal -->
                                    </tr>
                                    <tr>
                                        <th>Jumlah Halaman</th>
                                        <td>{{ $dokumen->jumlah_halaman ?? 'Tidak Tersedia' }}</td> <!-- Data halaman -->
                                    </tr>
                                    <tr>
                                        <th>Jumlah Kata</th>
                                        <td>{{ $dokumen->jumlah_halaman ?? 'Tidak Tersedia' }}</td> <!-- Data halaman -->
                                    </tr>
                                    <tr>
                                        <th>Ukuran Dokumen</th>
                                        <td>{{ number_format($dokumen->ukuran_file / 1024, 2) }} MB</td> <!-- Mengonversi ukuran ke MB dan menampilkan dua angka desimal -->
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
                        <div class="card-header bg-dark text-white">
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
                        <div class="card-header bg-dark text-white">
                            <h5>Unduh Dokumen</h5>
                        </div>
                        <div class="card-body">

                            {{-- Tombol aktif: Unduh Dokumen Hasil Pengecekan --}}
                            <a 
                                href="{{ route('dokumen.download', ['id' => $dokumen->id_dokumen]) }}" 
                                class="btn btn-primary w-100"
                                title="Unduh dokumen yang telah dicek plagiarisme">
                                <i class="fas fa-file-download me-1"></i> Unduh Dokumen Hasil Pengecekan
                            </a>

                            {{-- Tombol nonaktif: Unduh Bukti Penerimaan Digital --}}
                            <button 
                                type="button" 
                                class="btn btn-secondary w-100 mt-2" 
                                title="Fitur belum tersedia" 
                                disabled>
                                <i class="fas fa-receipt me-1"></i> Unduh Bukti Penerimaan Digital
                            </button>

                            {{-- Tombol nonaktif: Unduh Dokumen Asli --}}
                            <button 
                                type="button" 
                                class="btn btn-secondary w-100 mt-2" 
                                title="Fitur belum tersedia" 
                                disabled>
                                <i class="fas fa-file-alt me-1"></i> Unduh Dokumen Asli
                            </button>

                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="tab-pane fade" id="catatan" role="tabpanel">
                    <div class="card shadow-lg">
                        <div class="card-header bg-dark text-white">
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
    document.addEventListener("DOMContentLoaded", function() {
        // Bootstrap Tab Switching
        var sidebarTabs = document.querySelectorAll("#detailTabs .nav-link");
        sidebarTabs.forEach(function(tab) {
            tab.addEventListener("click", function(event) {
                event.preventDefault(); // Hindari reload halaman

                // Aktifkan tab yang diklik
                var tabTrigger = new bootstrap.Tab(this);
                tabTrigger.show();
            });
        });
    });
</script>
@stop