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
            <div class="card shadow-lg mb-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="documentTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="dokumen-tab" data-toggle="tab" href="#dokumen-content" role="tab" aria-controls="dokumen-content" aria-selected="true">
                                <i class="fas fa-file-alt mr-1"></i> Dokumen Plagiarisme
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="receipt-tab" data-toggle="tab" href="#receipt-content" role="tab" aria-controls="receipt-content" aria-selected="false">
                                <i class="fas fa-receipt mr-1"></i> Bukti Penerimaan Digital
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="documentTabsContent">
                    <!-- Tab Dokumen Plagiarisme -->
                    <div class="tab-pane fade show active" id="dokumen-content" role="tabpanel" aria-labelledby="dokumen-tab">
                        <div class="card-body text-center">
                            <h4 class="mb-3">{{ $dokumen->judul ?? 'Dokumen Plagiarisme' }}</h4>

                            @if(isset($dokumen->file_path))
                            <iframe src="{{ asset('storage/' . $dokumen->file_path) }}" width="100%" height="580px" class="mb-3"></iframe>
                            @else
                            <pre class="p-3 bg-light border rounded" style="height: 500px; overflow-y: auto;">{{ $dokumen->isi ?? 'Isi dokumen tidak tersedia' }}</pre>
                            @endif
                        </div>
                    </div>

                    <!-- Tab Bukti Penerimaan Digital -->
                    <div class="tab-pane fade" id="receipt-content" role="tabpanel" aria-labelledby="receipt-tab">
                        <div class="card-body text-center">
                            <h4 class="mb-3">Bukti Penerimaan Digital</h4>

                            @if(isset($digital_receipt) && isset($digital_receipt->file_path))
                            <iframe src="{{ asset('storage/' . $digital_receipt->file_path) }}" width="100%" height="580px" class="mb-3"></iframe>
                            @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i> Bukti penerimaan digital tidak tersedia
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigasi Tab -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="detailTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="sumber-tab" data-toggle="tab" href="#sumber" role="tab">
                                <i class="fas fa-file-alt mr-1"></i> Sumber
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="detail-tab" data-toggle="tab" href="#detail" role="tab">
                                <i class="fas fa-clipboard-list mr-1"></i> Detail
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="unduh-tab" data-toggle="tab" href="#unduh" role="tab">
                                <i class="fas fa-download mr-1"></i> Unduh
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="catatan-tab" data-toggle="tab" href="#catatan" role="tab">
                                <i class="fas fa-comments mr-1"></i> Catatan
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="tabContent">
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
                                            <th>Keywords</th>
                                            <td>
                                                @if($dokumen->keywords && $dokumen->keywords->count() > 0)
                                                    @foreach($dokumen->keywords as $keyword)
                                                        <span class="badge badge-primary mr-1">{{ $keyword->nama_keyword }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Tidak ada keyword</span>
                                                @endif
                                            </td>
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
                                            <td>{{ $dokumen->jumlah_kata ?? 'Tidak Tersedia' }}</td> <!-- Data kata -->
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
                                <h5>
                                    Daftar Sumber - 
                                    @if(fmod($dokumen->persentase_plagiarisme, 1) == 0)
                                        {{ number_format($dokumen->persentase_plagiarisme, 0) }}%
                                    @else
                                        {{ number_format($dokumen->persentase_plagiarisme, 1) }}%
                                    @endif
                                    Index Kesamaan
                                </h5>
                            </div>
                            <div class="card-body" style="max-height: 595px; overflow-y: auto;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sumber</th>
                                            <th>Persentase Kemunculan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($jurnalPlagiarisme as $jurnal)
                                            @php
                                                $url = $jurnal->link_jurnal;
                                                // Tambahkan protokol http:// jika belum ada
                                                if (!preg_match('/^https?:\/\//i', $url)) {
                                                    $url = 'http://' . $url;
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">
                                                        {{ $jurnal->link_jurnal }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($jurnal->persentase_kemunculan > 0 && $jurnal->persentase_kemunculan < 1)
                                                        &lt;1%
                                                    @else
                                                        {{ number_format($jurnal->persentase_kemunculan, 0) }}%
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center">Belum ada sumber plagiarisme.</td>
                                            </tr>
                                        @endforelse
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
                                @if(isset($dokumen->file_path))
                                <a href="{{ asset('storage/' . $dokumen->file_path) }}" download class="btn btn-primary w-100">
                                    <i class="fas fa-file-download mr-1"></i> Unduh Dokumen Plagiarisme
                                </a>
                                @else
                                <button class="btn btn-primary w-100 disabled" disabled>
                                    <i class="fas fa-file-download mr-1"></i> Unduh Dokumen Plagiarisme (Tidak Tersedia)
                                </button>
                                @endif
                                
                                @if(isset($digital_receipt) && isset($digital_receipt->file_path))
                                <a href="{{ asset('storage/' . $digital_receipt->file_path) }}" download class="btn btn-primary w-100 mt-2">
                                    <i class="fas fa-receipt mr-1"></i> Unduh Bukti Penerimaan Digital
                                </a>
                                @else
                                <button class="btn btn-primary w-100 mt-2 disabled" disabled>
                                    <i class="fas fa-receipt mr-1"></i> Unduh Bukti Penerimaan Digital (Tidak Tersedia)
                                </button>
                                @endif
                                
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle mr-2"></i> Klik tombol di atas untuk mengunduh dokumen langsung ke perangkat Anda.
                                </div>
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
                </div>
            </div> <!-- End Tab Content -->
        </div> <!-- End Col -->
    </div> <!-- End Row -->
</div> <!-- End Container -->
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Monitor PDF loading for all iframes
        const iframes = document.querySelectorAll('iframe');
        iframes.forEach(function(iframe) {
            // Event listener untuk error iframe
            iframe.addEventListener('error', function() {
                showErrorMessage(iframe);
            });

            // Check if loaded correctly
            iframe.addEventListener('load', function() {
                console.log('Iframe loaded:', iframe.src);
                try {
                    // If we can access the iframe content, check for errors
                    setTimeout(function() {
                        if (iframe.contentDocument &&
                            (iframe.contentDocument.body.innerHTML === '' ||
                                iframe.contentDocument.title.includes('404'))) {
                            showErrorMessage(iframe);
                        }
                    }, 1000);
                } catch (e) {
                    // Cross-origin restrictions prevent accessing the iframe content
                    console.log('Cannot access iframe content - cross-origin restrictions');
                }
            });
        });

        function showErrorMessage(iframe) {
            const container = iframe.parentNode;
            const errorMsg = document.createElement('div');
            errorMsg.className = 'alert alert-warning mt-3';
            errorMsg.innerHTML = '<strong>Dokumen tidak dapat ditampilkan.</strong><br>';

            // Insert error message before the iframe
            iframe.style.display = 'none';
            container.insertBefore(errorMsg, iframe);
        }
        
        // Handle download buttons in Unduh tab
        const downloadButtons = document.querySelectorAll('#unduh a[download]');
        downloadButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                console.log('Download initiated:', this.href);
                
                // Create a notification about the download
                const notification = document.createElement('div');
                notification.className = 'alert alert-success mt-2';
                notification.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Mengunduh dokumen...';
                
                // Add notification after the button
                this.parentNode.appendChild(notification);
                
                // Remove notification after 3 seconds
                setTimeout(function() {
                    notification.remove();
                }, 3000);
            });
        });
        
        // Enable jQuery tab functionality for both tab groups
        $('#documentTabs a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('#detailTabs a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@stop