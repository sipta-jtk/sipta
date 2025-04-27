@extends('adminlte::page')

@section('title')
    @if ($kategori === 'seminar1')
        Dokumen Seminar 1
    @elseif ($kategori === 'seminar2')
        Dokumen Seminar 2
    @elseif ($kategori === 'seminar3')
        Dokumen Seminar 3
    @else
        Daftar Dokumen
    @endif
@stop

@section('content_header')
    <h1 class="mb-3 text-left">
        @if ($kategori === 'seminar1')
            Dokumen Seminar 1
        @elseif ($kategori === 'seminar2')
            Dokumen Seminar 2
        @elseif ($kategori === 'seminar3')
            Dokumen Seminar 3
        @else
            Daftar Dokumen
        @endif
    </h1>
@stop


@section('content')
<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
    @if ($kategori === 'seminar1')
        @include('Repository.views.cardSeminar1')
    @elseif ($kategori === 'seminar2')
        @include('Repository.views.seminar2')
    @elseif ($kategori === 'seminar3')
        @include('Repository.views.seminar3')
    @endif


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
                    <input type="hidden" id="editId" name="id">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul:</label>
                        <input class="form-control" id="judul" name="judul" required>
                    </div>

                    <!-- Dropdown untuk FTA -->
                    @if ($kategori === 'fta')
                    <div class="mb-3">
                        <label for="kode_fta" class="form-label">Kode FTA:</label>
                        <select class="form-control" id="kode_fta" name="kode_fta" required>
                            @for ($i = 1; $i <= 9; $i++)
                                <option value="FTA-{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">FTA-{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                @endfor
                                <option value="FTA-10">FTA-10</option>
                                <option value="FTA-10a">FTA-10a</option>
                                @for ($i = 11; $i <= 13; $i++)
                                    <option value="FTA-{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">FTA-{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                    <option value="FTA-10">FTA-14</option>
                                    <option value="FTA-10a">FTA-14a</option>
                                    @for ($i = 15; $i <= 23; $i++)
                                        <option value="FTA-{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">FTA-{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                        </select>
                    </div>
                    @endif

                    <!-- Dropdown untuk Subkategori (hanya untuk kategori "artefak") -->
                    @if ($kategori === 'artefak')
                    <div class="mb-3">
                        <label for="subkategori" class="form-label">Subkategori:</label>
                        <select class="form-control" id="subkategori" name="id_subkategori" required>
                            <option value="">Pilih Subkategori</option>
                            @foreach ($subkategoris as $subkategori)
                            <option value="{{ $subkategori->id_subkategori }}">{{ $subkategori->nama_subkategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Conditional input based on kategori -->
                    @if ($kategori === 'link_source_code')
                    <div class="mb-3">
                        <label for="repository_url" class="form-label">URL Repository:</label>
                        <input type="url" class="form-control" id="repository_url" name="repository_url"
                            placeholder="https://github.com/username/repository" required>
                        <small class="form-text text-muted">Masukkan URL repository Git (GitHub, GitLab, Bitbucket, dll)</small>
                    </div>
                    @else
                    <div class="mb-3">
                        <label for="file" class="form-label">File:</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi:</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Dokumen -->
<div class="modal fade" id="detailDocumentModal" tabindex="-1" aria-labelledby="detailDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Changed to modal-lg to give more space for the document -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailDocumentModalLabel">Detail Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Judul -->
                <div class="mb-3">
                    <label for="detailJudul" class="form-label">Judul:</label>
                    <input type="text" class="form-control" id="detailJudul" readonly>
                </div>

                <!-- Document Preview -->
                <div class="mb-3">
                    <label class="form-label">Pratinjau Dokumen:</label>
                    <div class="document-preview-container" style="height: 400px; border: 1px solid #ddd;">
                        <iframe id="documentPreview" style="width: 100%; height: 100%; border: none;" src=""></iframe>
                        <div id="previewNotAvailable" class="text-center p-5" style="display: none;">
                            <i class="fas fa-file-alt fa-3x mb-3 text-secondary"></i>
                            <p>Preview tidak tersedia untuk jenis file ini</p>
                        </div>
                    </div>
                </div>

                <!-- File Terunggah -->
                <div class="mb-3">
                    <label class="form-label">Aksi File:</label>
                    <div id="detailFileLink">
                        <a href="#" target="_blank" class="btn btn-primary" id="detailFileLinkBtn">
                            <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                        </a>
                        <a href="#" class="btn btn-success" id="detailFileDownloadBtn"
                            data-kategori="{{ $kategori }}"
                            <i class="fas fa-download"></i> Unduh
                        </a>
                    </div>
                    <p id="noFileMessage" style="display: none;">Tidak ada file terunggah</p>
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label for="detailDeskripsi" class="form-label">Deskripsi:</label>
                    <textarea class="form-control" id="detailDeskripsi" rows="3" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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

    .btn-filter {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        color: white;
        transition: background-color 0.3s ease;
    }

    .btn-filter:hover {
        background-color: #0056b3;
    }

    .btn-reset {
        background-color: #6c757d;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        color: white;
        transition: background-color 0.3s ease;
    }

    .btn-reset:hover {
        background-color: #5a6268;
    }

    /* Filter styling */
    #filterOptions {
        border-radius: 8px;
    }

    #toggleFilter {
        height: 38px;
    }

    /* Add these styles to your existing CSS section */
    .document-preview-container {
        background-color: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .document-preview-container:hover {
        box-shadow: inset 0 0 8px rgba(0, 0, 0, 0.2);
    }

    #previewNotAvailable {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        color: #6c757d;
    }

    #detailFileLink {
        display: flex;
        gap: 10px;
    }

    #detailDocumentModal .modal-dialog {
        max-width: 800px;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .document-preview-container {
            height: 300px !important;
        }
    }

    @media (max-width: 576px) {
        .document-preview-container {
            height: 200px !important;
        }

        #detailFileLink {
            flex-direction: column;
        }

        #detailFileLink a {
            width: 100%;
            margin-bottom: 5px;
        }
    }

    @media (max-width: 768px) {
        .col-md-3 {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>
@stop



@section('js')
<script>
    console.log("Laporan TA page loaded.");
    const prefix = "/{{ env('PREFIX_URL') }}";

    // Toggle filter button functionality
    const statusTa = "{{ $status_ta }}";

    // Cek kondisi
    if (statusTa === 'mahasiswa_ta') {
        document.getElementById("toggleFilter").addEventListener("click", function() {
            var filterDiv = document.getElementById("filterOptions");
            var searchInput = document.getElementById("searchInput");
            if (filterDiv.style.display === "none") {
                filterDiv.style.display = "block";
                searchInput.style.display = "block";
            } else {
                filterDiv.style.display = "none";
                searchInput.style.display = "none";
            }
        });
    }


    // Search input functionality
    // document.getElementById('searchInput').addEventListener('keyup', function() {
    // filterTable();
    // });

    function filterTable() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }

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
                document.getElementById('editForm').action = `${prefix}/repository/mahasiswa/{{ $kategori }}/${id}`;

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

        document.querySelectorAll('.document-title').forEach(link => {
            link.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');
                const filePath = this.getAttribute('data-file');
                const deskripsi = this.getAttribute('data-deskripsi');

                // Set modal content
                document.getElementById('detailJudul').value = judul;
                document.getElementById('detailDeskripsi').value = deskripsi;

                // Handle file display
                const fileLink = document.getElementById('detailFileLink');
                const fileLinkBtn = document.getElementById('detailFileLinkBtn');
                const fileDownloadBtn = document.getElementById('detailFileDownloadBtn');

                // FIXED: Update the data attributes for the download button
                fileDownloadBtn.setAttribute('data-id', id);
                fileDownloadBtn.setAttribute('data-judul', judul);
                const noFileMessage = document.getElementById('noFileMessage');
                const documentPreview = document.getElementById('documentPreview');
                const previewNotAvailable = document.getElementById('previewNotAvailable');

                if (filePath && filePath.trim() !== '') {
                    const fileUrl = `/storage/${filePath}`;

                    // Set link and download buttons
                    fileLinkBtn.href = fileUrl;
                    fileDownloadBtn.href = "{{ route('Repository.download', [$kategori, '']) }}/" + id;
                    fileLink.style.display = 'block';
                    noFileMessage.style.display = 'none';

                    // Check if this is a URL repository (for link_source_code)
                    if (filePath.startsWith('http')) {
                        // This is a repository URL, not a file
                        documentPreview.style.display = 'none';
                        previewNotAvailable.style.display = 'block';
                        previewNotAvailable.innerHTML = `
                    <i class="fas fa-link fa-3x mb-3 text-secondary"></i>
                    <p>Link repository: <a href="${filePath}" target="_blank">${filePath}</a></p>
                `;
                    } else {
                        // Check file type for preview compatibility
                        const fileExtension = filePath.split('.').pop().toLowerCase();
                        const previewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'txt'];

                        if (previewableTypes.includes(fileExtension)) {
                            // File can be previewed
                            documentPreview.src = fileUrl;
                            documentPreview.style.display = 'block';
                            previewNotAvailable.style.display = 'none';
                        } else {
                            // File cannot be previewed
                            documentPreview.style.display = 'none';
                            previewNotAvailable.style.display = 'block';
                            previewNotAvailable.innerHTML = `
                        <i class="fas fa-file-alt fa-3x mb-3 text-secondary"></i>
                        <p>Preview tidak tersedia untuk jenis file ini</p>
                    `;
                        }
                    }
                } else {
                    // No file uploaded
                    fileLink.style.display = 'none';
                    noFileMessage.style.display = 'block';
                    documentPreview.style.display = 'none';
                    previewNotAvailable.style.display = 'block';
                }
            });
        });

        // Handle delete button clicks
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-judul');
                const kategori = "{{ $kategori }}";
                const idKota = "{{ auth()->user()->mahasiswa->id_kota ?? auth()->user()->dosen->id_kota ?? 1 }}";

                // Set judul dokumen ke dalam modal
                document.getElementById('deleteDocumentTitle').textContent = title;

                // Set form action ke route yang sesuai
                document.getElementById('deleteForm').action = `${prefix}/repository/mahasiswa/{{ $kategori }}/${id}`;

                // Tampilkan modal konfirmasi
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

        document.getElementById('detailFileDownloadBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const kategori = "{{ $kategori }}"; // This can be hardcoded since it's the same for all docs in this view
            const judul = this.getAttribute('data-judul');

            // Tambahkan timestamp untuk menghindari cache
            const timestamp = new Date().getTime();
            const downloadUrl = `${prefix}/repository/mahasiswa/${kategori}/${id}/download?t=${timestamp}`;

            console.log('Download URL:', downloadUrl);
            window.location.href = downloadUrl;
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