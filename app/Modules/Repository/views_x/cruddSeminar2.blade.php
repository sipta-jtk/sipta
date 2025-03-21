@extends('adminlte::page')

@section('title', 'Laporan TA')

@section('content_header')
    <h1 class="text-center">LAPORAN TA - SEMINAR 2</h1>
@stop

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-box">
            <input type="text" class="form-control" id="searchInput" placeholder="Search here...">
        </div>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addDocumentModal">
            + Add
        </button>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Versi</th>
                <th>Judul</th>
                <th>Tanggal Dibuat</th>
                <th>Terakhir Diedit</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>V1.0</td>
                <td>Sistem Manajemen Tugas Akhir</td>
                <td>2025-03-01</td>
                <td>2025-03-01</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editDocumentModal" data-filename="Sistem_Manajemen_TA.pdf">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-success">
                        <i class="fas fa-download"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>V1.1</td>
                <td>Pengembangan Fitur Notifikasi</td>
                <td>2025-03-10</td>
                <td>2025-03-12</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editDocumentModal" data-filename="Fitur_Notifikasi.pdf">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-success">
                        <i class="fas fa-download"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>V2.0</td>
                <td>Integrasi dengan API</td>
                <td>2025-04-05</td>
                <td>2025-04-05</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editDocumentModal" data-filename="Integrasi_API.pdf">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-success">
                        <i class="fas fa-download"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Dokumen -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">Laporan TA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="file" class="form-label">File:</label>
                        <input type="file" class="form-control" id="file" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi:</label>
                        <textarea class="form-control" id="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Back</button>
                        <button type="submit" class="btn btn-dark">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Dokumen -->
<div class="modal fade" id="editDocumentModal" tabindex="-1" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title w-100 text-center" id="editDocumentModalLabel">Laporan TA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="editFile" class="form-label">File:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="editFileName">
                            <div class="input-group-append">
                                <label for="editFile" class="btn btn-outline-secondary mb-0">Browse</label>
                                <input type="file" id="editFile" class="d-none">
                            </div>
                        </div>
                        <small class="form-text text-muted">Replace file hanya ketika file yang diupload salah, jika ada revisi upload file baru</small>
                    </div>
                    <div class="mb-3">
                        <label for="editVersi" class="form-label">Versi:</label>
                        <input type="text" class="form-control" id="editVersi" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDeskripsi" class="form-label">Deskripsi:</label>
                        <textarea class="form-control" id="editDeskripsi" rows="3" required></textarea>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #444; color: white; border: none; width: 120px; height: 40px; border-radius: 4px;">Back</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #444; color: white; border: none; width: 120px; height: 40px; border-radius: 4px;">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Dokumen -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title w-100 text-center" id="deleteConfirmationModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-circle text-warning mb-3" style="font-size: 3rem;"></i>
                <p class="mb-0">Apakah Anda yakin ingin menghapus dokumen</p>
                <p class="font-weight-bold mb-4" id="deleteDocumentTitle"></p>
                <p class="text-muted small">Dokumen yang sudah dihapus tidak dapat dikembalikan</p>
                <input type="hidden" id="deleteDocumentId">
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #444; color: white; border: none; width: 120px; height: 40px; border-radius: 4px;">Batal</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger" style="background-color: #dc3545; color: white; border: none; width: 120px; height: 40px; border-radius: 4px;">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Download Dokumen -->
<div class="modal fade" id="downloadConfirmationModal" tabindex="-1" aria-labelledby="downloadConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title w-100 text-center" id="downloadConfirmationModalLabel">Konfirmasi Download</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-circle text-warning mb-3" style="font-size: 3rem;"></i>
                <p class="mb-0">Apakah Anda yakin ingin mengunduh dokumen</p>
                <p class="font-weight-bold mb-4" id="downloadDocumentTitle"></p>
                <p class="text-muted small">Dokumen akan diunduh ke perangkat Anda</p>
                <input type="hidden" id="downloadDocumentId">
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #444; color: white; border: none; width: 120px; height: 40px; border-radius: 4px;">Batal</button>
                <button type="button" id="confirmDownloadBtn" class="btn btn-success" style="background-color: #28a745; color: white; border: none; width: 120px; height: 40px; border-radius: 4px;">Download</button>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .search-box input {
            width: 250px;
        }
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-dark {
            background-color: #3d3d3d;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: white;
        }
        .btn-dark:hover {
            background-color: #2c2c2c;
        }
        .modal-header h5 {
            font-weight: bold;
        }
        hr {
            margin: 0;
            border-top: 2px solid #ddd;
        }
        #editDocumentModal .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }
        #editDocumentModal .modal-title {
            font-weight: bold;
        }
        #editDocumentModal .form-label {
            font-weight: bold;
        }
        #deleteConfirmationModal .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }
        #deleteConfirmationModal .modal-title {
            font-weight: bold;
        }
        #deleteConfirmationModal .btn-secondary {
            background-color: #3d3d3d;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: white;
        }
        #deleteConfirmationModal .btn-secondary:hover {
            background-color: #2c2c2c;
        }
        #deleteConfirmationModal .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: white;
        }
        #deleteConfirmationModal .btn-danger:hover {
            background-color: #c82333;
        }
        #downloadConfirmationModal .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }
        #downloadConfirmationModal .modal-title {
            font-weight: bold;
        }
        #downloadConfirmationModal .btn-secondary {
            background-color: #3d3d3d;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: white;
        }
        #downloadConfirmationModal .btn-secondary:hover {
            background-color: #2c2c2c;
        }
        #downloadConfirmationModal .btn-success {
            background-color: #28a745;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            color: white;
        }
        #downloadConfirmationModal .btn-success:hover {
            background-color: #218838;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Laporan TA page loaded.");
        
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });

        // Handle file selection display
        document.getElementById('editFile').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('editFileName').value = this.files[0].name;
            }
        });

        // Set tanggal saat ini untuk Terakhir Diedit
        document.addEventListener('DOMContentLoaded', function() {
            var now = new Date();
            var formattedDate = now.toISOString().split('T')[0]; // Format YYYY-MM-DD
            
            if (document.getElementById('editLastUpdated')) {
                document.getElementById('editLastUpdated').value = formattedDate;
            }
        });

        // Populate edit modal when edit button is clicked
        document.querySelectorAll('.btn-outline-primary').forEach(function(button, index) {
            button.addEventListener('click', function() {
                var row = this.closest('tr');
                var versi = row.cells[1].textContent;
                var judul = row.cells[2].textContent;
                var tanggalDibuat = row.cells[3].textContent;
                var terakhirDiedit = row.cells[4].textContent;
                var filename = this.getAttribute('data-filename');
                
                if (document.getElementById('editFileName')) {
                    document.getElementById('editFileName').value = filename;
                }
                if (document.getElementById('editVersi')) {
                    document.getElementById('editVersi').value = versi;
                }
                if (document.getElementById('editDeskripsi')) {
                    document.getElementById('editDeskripsi').value = judul;
                }
                if (document.getElementById('editLastUpdated')) {
                    document.getElementById('editLastUpdated').value = terakhirDiedit;
                }
            });
        });

        // Handle delete button clicks
        document.querySelectorAll('.btn-outline-danger').forEach(function(button) {
            button.addEventListener('click', function() {
                var row = this.closest('tr');
                var id = row.cells[0].textContent;
                var title = row.cells[2].textContent;
                
                // Set the document title in the confirmation modal
                document.getElementById('deleteDocumentTitle').textContent = title;
                
                // Store the document ID for deletion
                document.getElementById('deleteDocumentId').value = id;
                
                // Show the confirmation modal
                $('#deleteConfirmationModal').modal('show');
            });
        });

        // Handle confirmation of deletion
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            var documentId = document.getElementById('deleteDocumentId').value;
            
            // Here you would add the actual delete logic, for example:
            // deleteDocument(documentId);
            
            // For now, let's just close the modal and provide feedback
            $('#deleteConfirmationModal').modal('hide');
            
            // Optional: Remove the row from the table for immediate visual feedback
            document.querySelectorAll('tbody tr').forEach(function(row) {
                if (row.cells[0].textContent === documentId) {
                    row.remove();
                }
            });
            
            // You might want to show a success message
            // alert('Dokumen berhasil dihapus');
            
            console.log('Document with ID ' + documentId + ' deleted');
        });

        // Handle download button clicks
        document.querySelectorAll('.btn-outline-success').forEach(function(button) {
            button.addEventListener('click', function() {
                var row = this.closest('tr');
                var id = row.cells[0].textContent;
                var title = row.cells[2].textContent;
                
                // Set the document title in the confirmation modal
                document.getElementById('downloadDocumentTitle').textContent = title;
                
                // Store the document ID for download
                document.getElementById('downloadDocumentId').value = id;
                
                // Show the confirmation modal
                $('#downloadConfirmationModal').modal('show');
            });
        });
    </script>
@stop