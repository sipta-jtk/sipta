@extends('adminlte::page')

@section('title', 'Detail Plagiarism Check')

@section('content_header')
    <h1 class="text-center">Detail Plagiarism Check</h1>
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
                        <pre class="p-3 bg-light border rounded" style="height: 500px; overflow-y: auto;">{{ $dokumen->isi ?? 'Isi dokumen tidak tersedia' }}</pre>
                    @endif
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <!-- Sidebar dengan Tab -->
        <div class="col-md-3">
            <div class="offcanvas offcanvas-end d-none" tabindex="-1" id="sidebar">
                <div class="offcanvas-header">
                    <button id="closeSidebar" class="btn btn-dark btn-sm">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="offcanvas-body">
                    
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-3" id="sidebarTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="sumber-tab" data-bs-toggle="tab" href="#sumber" role="tab" aria-controls="sumber" aria-selected="true">📄</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="detail-tab" data-bs-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="false">📋</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="unduh-tab" data-bs-toggle="tab" href="#unduh" role="tab" aria-controls="unduh" aria-selected="false">⬇️</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('povMahasiswa') }}" aria-controls="catatan" aria-selected="false">💬</a>
                        </li>

                    </ul>
=======
        <!-- Navigasi Tab -->
        <div class="col-md-4">
            <ul class="nav nav-tabs mb-3" id="detailTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="detail-tab" data-bs-toggle="tab" href="#detail" role="tab">📋 Detail</a>
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
>>>>>>> ffefe625c6f86e1c7b94969ab5f602046fd845c7

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
                                    <tr><th>Penulis</th><td>Mumun Sumumun</td></tr>
                                    <tr><th>Judul Dokumen</th><td>Implementasi Algoritma Naive</td></tr>
                                    <tr><th>Nama Dokumen</th><td>CONTOH FIX.PDF</td></tr>
                                    <tr><th>Tanggal Unggah</th><td>28-02-2025</td></tr>
                                    <tr><th>Jumlah Halaman</th><td>30</td></tr>
                                    <tr><th>Ukuran Dokumen</th><td>2MB</td></tr>
                                    <tr><th>Ambang Batas</th><td>50%</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Daftar Sumber -->
                <div class="tab-pane fade" id="sumber" role="tabpanel">
                    <div class="card shadow-lg">
                        <div class="card-header bg-danger text-white">
                            <h5>Daftar Sumber - 15% Index Kesamaan</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr><td>arxiv.org</td><td>4%</td></tr>
                                    <tr><td>jurnal.itscience.org</td><td>12%</td></tr>
                                    <tr><td>ejurnal.umri.ac.id</td><td>2%</td></tr>
                                    <tr><td>Fei Li et al.</td><td><1%</td></tr>
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
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>Nana Mardiana</strong> - 01 Maret 2025, 20:20:20
                                    <p>Gunakan sumber referensi yang sahih, minimal Sinta 3</p>
                                </li>
                                <li class="list-group-item">
                                    <strong>Cinta Laura</strong> - 02 Maret 2025, 10:12:09
                                    <p>Di Parafrase yaa!!</p>
                                </li>
                                <li class="list-group-item">
                                    <strong>Zayn Malik</strong> - 02 Maret 2025, 13:09:01
                                    <p>Di Parafrase yaa!!</p>
                                </li>
                            </ul>
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
