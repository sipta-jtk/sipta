@extends('adminlte::page')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Base styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            background-color: #343a40;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        
        .content {
            padding: 30px;
            background-color: #f8f9fa;
            background: linear-gradient(to bottom right, #f8f9fa, #ffffff);
            min-height: 100vh;
        }
        
        /* Page header section */
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .page-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 20px;
        }
        
        /* Action buttons at top */
        .action-top-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .btn-action {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 10px rgba(0,0,0,0.15);
        }
        
        /* Search box section */
        .search-box {
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .search-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
        }
        
        .search-col-select {
            flex: 1;
            min-width: 250px;
        }
        
        .search-col-input {
            flex: 2;
            min-width: 300px;
        }
        
        /* Form elements */
        .form-select {
            padding: 12px 15px;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ced4da;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fff;
            cursor: pointer;
        }
        
        .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .input-group {
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .input-group .form-control {
            padding: 12px 15px;
            border: 1px solid #ced4da;
            border-right: none;
            height: auto;
        }
        
        .input-group .btn {
            padding: 0 20px;
            font-weight: 500;
        }
        
        /* Table card */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: 0 12px 25px rgba(0,0,0,0.12);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 1.5rem 2rem;
        }
        
        .card-header h5 {
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        /* Table styles */
        .table-responsive {
            padding: 0;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        
        .table td {
            padding: 15px 20px;
            vertical-align: middle;
        }
        
        .table tbody tr {
            border-bottom: 1px solid #f2f2f2;
        }
        
        .table tbody tr:hover {
            background-color: rgba(0,123,255,0.05);
            transition: all 0.2s ease;
        }
        
        /* Status badge */
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .status-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        
        /* Action buttons in table */
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .btn-sm {
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .btn-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        
        /* Empty state */
        .text-center {
            padding: 25px;
            font-style: italic;
            color: #6c757d;
        }
        
        /* Alert messages */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .search-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-col-select, 
            .search-col-input {
                width: 100%;
                min-width: 100%;
            }
            
            .action-top-buttons {
                flex-direction: column;
                align-items: stretch;
            }
            
            .table th, 
            .table td {
                padding: 12px 15px;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-12">
            <div class="mb-4">
                <h2 class="page-title">Ruangan</h2>
                <div class="d-flex justify-content-end align-items-center mb-4">
                    <div class="d-flex gap-3">
                        <a href="{{ route('ruangan.create') }}" class="btn btn-primary btn-action">
                            <i class="fas fa-plus me-2"></i> Ruangan
                        </a>
                    </div>
                </div>
            </div>
            <!-- Page Header with Buttons -->

            <!-- Search Box -->
            <div class="search-box mb-4">
                <form action="{{ route('ruangan.index') }}" method="GET">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select class="form-select shadow-sm" id="searchType" name="search_type">
                                <option value="">Pilih Tipe Pencarian</option>
                                <option value="nama_ruangan" {{ request('search_type') == 'nama_ruangan' ? 'selected' : '' }}>Search by Name</option>
                                <option value="kode_ruangan" {{ request('search_type') == 'kode_ruangan' ? 'selected' : '' }}>Search by Code</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}" placeholder="Masukkan kata kunci pencarian...">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Table Card -->
            <div class="card">
                <div class="card-header py-3">
                    <h5 class="mb-0">Daftar Ruangan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Kode Ruangan</th>
                                    <th class="px-4 py-3">Nama Ruangan</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Kode Gedung</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ruangan as $key => $room)
                                <tr>
                                    <td class="px-4 py-3">{{ $key + 1 }}</td>
                                    <td class="px-4 py-3">{{ $room->kode_ruangan }}</td>
                                    <td class="px-4 py-3">{{ $room->nama_ruangan }}</td>
                                    <td class="px-4 py-3">
                                        @if ($room->status_ruangan == 'tersedia')
                                            <span class="status-badge bg-success text-white rounded p-1">Tersedia</span>
                                        @else
                                            <span class="status-badge bg-danger text-white rounded p-1">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $room->kode_gedung }}</td>
                                    <td class="px-4 py-3">
                                        <div class="action-buttons justify-content-center">
                                            <a href="{{ route('ruangan.edit', $room->id_ruangan) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('ruangan.destroy', $room->id_ruangan) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">Tidak ada data ruangan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection 