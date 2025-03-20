@extends('adminlte::page')

@section('title', 'Laporan TA - ' . ucfirst($kategori))

@section('content_header')
<h1 class="text-center">LAPORAN TA - {{ strtoupper($kategori) }}</h1>
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

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>No</th>
                @if ($kategori === 'fta')
                <th>Kode FTA</th>
                @endif
                <th>Versi</th>
                <th>Judul</th>
                <th>Tanggal Dibuat</th>
                <th>Terakhir Diedit</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($dokumen->isEmpty())
            <tr>
                <td colspan="{{ $kategori === 'fta' ? 7 : 6 }}" class="text-center">
                    <p class="text-muted">List Dokumen Kosong, Belum Ada Dokumen Yang Ditambahkan</p>
                </td>
            </tr>
            @else
            @foreach ($dokumen as $index => $doc)
            <tr>
                <td>{{ $index + 1 }}</td>
                @if ($kategori === 'fta')
                <td>{{ $doc->kode_fta }}</td>
                @endif
                <td>{{ $doc->versi }}</td>
                <td>{{ $doc->judul }}</td>
                <td>{{ $doc->created_at }}</td>
                <td>{{ $doc->updated_at }}</td>
                <td>
                    <!-- Edit button -->
                    <button class="btn btn-sm btn-outline-primary edit-btn"
                        data-id="{{ $doc->id_dokumen }}"
                        data-judul="{{ $doc->judul }}"
                        data-versi="{{ $doc->versi }}"
                        data-deskripsi="{{ $doc->deskripsi }}"
                        data-file="{{ $doc->file_path }}"
                        data-toggle="modal" data-target="#editDocumentModal">
                        <i class="fas fa-edit"></i>
                    </button>

                    <!-- Delete button -->
                    <button class="btn btn-sm btn-outline-danger delete-btn"
                        data-id="{{ $doc->id_dokumen }}"
                        data-judul="{{ $doc->judul }}">
                        <i class="fas fa-trash"></i>
                    </button>

                    <!-- Download button -->
                    <a href="#" class="btn btn-sm btn-outline-success download-btn"
                        data-id="{{ $doc->id_dokumen }}"
                        data-judul="{{ $doc->judul }}"
                        data-toggle="modal"
                        data-target="#downloadConfirmationModal">
                        <i class="fas fa-download"></i>
                    </a>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>

<!-- Modal Tambah Dokumen -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">Tambah Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addDocumentForm" action="{{ route('Repository.store', $kategori) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul:</label>
                        <input class="form-control" id="judul" name="judul" required>
                    </div>

                    <!-- Dropdown untuk Subkategori (hanya untuk kategori "artefak") -->
                    @if ($kategori === 'artefak')
                    <div class="mb-3">
                        <label for="subkategori" class="form-label">Subkategori:</label>
                        <div class="input-group">
                            <select class="form-control" id="subkategori" name="id_subkategori" required>
                                <option value="">Pilih Subkategori</option>
                                @foreach ($subkategoris as $subkategori)
                                <option value="{{ $subkategori->id_subkategori }}">{{ $subkategori->nama_subkategori }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#tambahSubkategoriModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="file" class="form-label">File:</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi:</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
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

<!-- Modal Tambah Subkategori -->
<div class="modal fade" id="tambahSubkategoriModal" tabindex="-1" aria-labelledby="tambahSubkategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahSubkategoriModalLabel">Tambah Subkategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tambahSubkategoriForm">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_subkategori" class="form-label">Nama Subkategori:</label>
                        <input type="text" class="form-control" id="nama_subkategori" name="nama_subkategori" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
                <h5 class="modal-title w-100 text-center" id="editDocumentModalLabel">Edit Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id">

                    <div class="mb-3">
                        <label for="editJudul" class="form-label">Judul:</label>
                        <input type="text" class="form-control" id="editJudul" name="judul" required>
                    </div>

                    <div class="mb-3">
                        <p><strong>File Terunggah:</strong></p>
                        <div id="fileDisplay">
                            <a href="#" id="editFileLink" target="_blank" class="btn btn-primary">Lihat File</a>
                            <p id="noFileMessage" style="display: none;">Tidak ada file terunggah</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editDeskripsi" class="form-label">Deskripsi:</label>
                        <textarea class="form-control" id="editDeskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="openSaveConfirmation">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Dokumen -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Simpan Edit -->
<div class="modal fade" id="saveConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="saveConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title w-100 text-center" id="saveConfirmationModalLabel">Konfirmasi Simpan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                <p class="mb-0">Apakah Anda yakin ingin menyimpan perubahan?</p>
                <p class="font-weight-bold mb-4" id="saveDocumentTitle"></p>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmSaveBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Download -->
<div class="modal fade" id="downloadConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="downloadConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title w-100 text-center" id="downloadConfirmationModalLabel">Konfirmasi Download</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-download text-primary mb-3" style="font-size: 3rem;"></i>
                <p class="mb-0">Apakah Anda yakin ingin mengunduh dokumen ini?</p>
                <p class="font-weight-bold mb-4" id="downloadDocumentTitle"></p>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="#" id="confirmDownloadBtn" class="btn btn-primary">Download</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .table th,
    .table td {
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

    document.addEventListener('DOMContentLoaded', function() {
        // Populate edit modal when edit button is clicked
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');
                const deskripsi = this.getAttribute('data-deskripsi');
                const filePath = this.getAttribute('data-file');

                document.getElementById('editJudul').value = judul;
                document.getElementById('editDeskripsi').value = deskripsi;

                // Set form action with dynamic kategori
                document.getElementById('editForm').action = `/repository/{{ $kategori }}/${id}`;

                // Handle file display
                const fileLink = document.getElementById('editFileLink');
                const noFileMessage = document.getElementById('noFileMessage');

                if (filePath && filePath.trim() !== '') {
                    fileLink.href = `/storage/${filePath}`;
                    fileLink.style.display = 'inline';
                    noFileMessage.style.display = 'none';
                } else {
                    fileLink.style.display = 'none';
                    noFileMessage.style.display = 'block';
                }
            });
        });

        // Handle delete button clicks
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-judul');

                document.getElementById('deleteDocumentTitle').textContent = title;
                document.getElementById('deleteForm').action = `/repository/{{ $kategori }}/${id}`;
                $('#deleteConfirmationModal').modal('show');
            });
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            document.getElementById('deleteForm').submit();
        });

        document.getElementById('editForm').addEventListener('submit', function(event) {
            event.preventDefault();
            $('#saveConfirmationModal').modal('show');
        });

        document.getElementById('confirmSaveBtn').addEventListener('click', function() {
            document.getElementById('editForm').submit();
        });

        document.querySelectorAll('.download-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const id = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');

                // Set the document title in the modal
                document.getElementById('downloadDocumentTitle').textContent = judul;

                // Set the download link on the confirm button
                document.getElementById('confirmDownloadBtn').href = "{{ route('Repository.download', [$kategori, 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', id);
            });
        });

        // Handle tambah subkategori
        // Handle tambah subkategori
        document.getElementById('tambahSubkategoriForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Create FormData from the form
            const formData = new FormData(this);

            // Send request to server
            fetch("{{ route('subkategori.store') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        // Remove the 'Content-Type' header to let the browser set it with the correct boundary for FormData
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add new subcategory to dropdown
                        const select = document.getElementById('subkategori');
                        const option = document.createElement('option');
                        option.value = data.subkategori.id_subkategori;
                        option.text = data.subkategori.nama_subkategori;
                        select.appendChild(option);

                        // Select new subcategory
                        select.value = data.subkategori.id_subkategori;

                        // Close modal
                        $('#tambahSubkategoriModal').modal('hide');

                        // Reset form
                        document.getElementById('tambahSubkategoriForm').reset();
                    } else {
                        alert('Gagal menambahkan subkategori');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan subkategori');
                });
        });

        // Auto-close alert messages
        window.setTimeout(function() {
            const alerts = document.querySelectorAll(".alert");
            alerts.forEach(alert => {
                $(alert).fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            });
        }, 4000);
    });
</script>
@stop