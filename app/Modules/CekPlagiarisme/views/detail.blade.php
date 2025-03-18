@extends('adminlte::page')

@section('title', 'Detail Plagiarism Check')

@section('content_header')
    <h1 class="text-center">Detail Plagiarism Check</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- Kolom untuk Dokumen -->
        <div class="col-md-8 me-3">
            <div class="card shadow-lg">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">{{ $dokumen->judul ?? 'Judul Dokumen Contoh' }}</h3>
                    <button id="toggleSidebar" class="btn btn-dark btn-sm position-absolute" style="top: 10px; right: 10px;">
                        <i id="toggleIcon" class="fas fa-chevron-left"></i>
                    </button>
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
                            <a class="nav-link" id="catatan-tab" data-bs-toggle="tab" href="#catatan" role="tab" aria-controls="catatan" aria-selected="false">💬</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="sidebarContent">
                        <!-- Daftar Sumber -->
                        <div class="tab-pane fade show active" id="sumber" role="tabpanel">
                            <h5 class="text-danger">15% Index Kesamaan</h5>
                            <ul class="list-group">
                                <li class="list-group-item">arxiv.org - 4%</li>
                                <li class="list-group-item">jurnal.itscience.org - 12%</li>
                                <li class="list-group-item">ejurnal.umri.ac.id - 2%</li>
                                <li class="list-group-item">Fei Li et al. - <1%</li>
                            </ul>
                        </div>
                        <!-- Detail Dokumen -->
                        <div class="tab-pane fade" id="detail" role="tabpanel">
                            <p><strong>Penulis:</strong> Mumun Sumumun</p>
                            <p><strong>Judul Dokumen:</strong> Implementasi Algoritma Naive</p>
                            <p><strong>Nama Dokumen:</strong> CONTOH FIX.PDF</p>
                            <p><strong>Tanggal Unggah:</strong> 28-02-2025</p>
                            <p><strong>Jumlah Halaman:</strong> 30</p>
                            <p><strong>Ukuran Dokumen:</strong> 2MB</p>
                            <p><strong>Ambang Batas Yang Digunakan:</strong> 50%</p>
                        </div>
                        <!-- Unduh Dokumen -->
                        <div class="tab-pane fade" id="unduh" role="tabpanel">
                            <button class="btn btn-primary w-100">📥 Unduh Dokumen Hasil Pengecekan</button>
                            <button class="btn btn-secondary w-100 mt-2">📥 Unduh Bukti Penerimaan Digital</button>
                            <button class="btn btn-success w-100 mt-2">📥 Unduh Dokumen Asli</button>
                        </div>
                        <!-- Catatan -->
                        <div class="tab-pane fade" id="catatan" role="tabpanel">
                            <div class="list-group">
                                <div class="list-group-item">
                                    <strong>Nana Mardiana</strong> - 01 Maret 2025, 20:20:20
                                    <p>Gunakan sumber referensi yang sahih, minimal Sinta 3</p>
                                </div>
                                <div class="list-group-item">
                                    <strong>Cinta Laura</strong> - 02 Maret 2025, 10:12:09
                                    <p>Di Parafrase yaa!!</p>
                                </div>
                                <div class="list-group-item">
                                    <strong>Zayn Malik</strong> - 02 Maret 2025, 13:09:01
                                    <p>Di Parafrase yaa!!</p>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End Tab Content -->

                </div> <!-- End Offcanvas Body -->
            </div> <!-- End Sidebar -->
        </div> <!-- End Col -->
    </div> <!-- End Row -->
</div> <!-- End Container -->
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var sidebar = document.getElementById('sidebar');

        // Pastikan sidebar tetap tersembunyi saat awal
        sidebar.classList.add("d-none");

        document.getElementById('toggleSidebar').addEventListener('click', function () {
            sidebar.classList.toggle("d-none");
        });

        document.getElementById('closeSidebar').addEventListener('click', function () {
            sidebar.classList.add("d-none");
        });

        // Bootstrap Tab Switching
        var sidebarTabs = document.querySelectorAll("#sidebarTabs .nav-link");
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
