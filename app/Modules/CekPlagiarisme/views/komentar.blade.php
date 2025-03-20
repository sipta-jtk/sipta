@extends('adminlte::page')

@section('title', 'Komentar CekPlagiarisme')

@section('content_header')
    <h1>Pengecekan Plagiarisme</h1>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stop

@section('content')
<div class="container-fluid">
    <div class="row g-3">
        <!-- Kolom Kiri - Preview Dokumen -->
        <div class="col-lg-8 col-md-5 col-sm-12">
            <div class="card">
                <div class="card-body text-center">
                    <h3>Preview Dokumen</h3>
                    <p><em>Ini preview dari dokumen tugas akhir.</em></p>
                    <img src="https://via.placeholder.com/700x600?text=Preview+Dokumen" alt="Preview Dokumen" class="img-fluid" />
                </div>
            </div>
        </div>

        <!-- Kolom Kanan - Komentar -->
        <div class="col-lg-4 col-md-7 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4>CATATAN</h4>

                    <div id="comments-list">
                        @foreach($comments as $index => $comment)
                            <div class="comment-item mb-3 p-3 border rounded bg-light" data-index="{{ $index }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>
                                        {{ $comment['user'] }} <span class="text-muted small">({{ $comment['role'] }})</span>
                                    </strong>

                                    <!-- Dropdown Menu -->
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item edit-btn">
                                                    <i class="fas fa-edit me-2"></i> Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item copy-btn">
                                                    <i class="fas fa-copy me-2"></i> Salin
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger fw-bold delete-btn" data-index="{{ $index }}" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                                    <i class="fas fa-trash me-2 text-danger"></i> Hapus
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <p class="mt-2 mb-1 comment-text">{{ $comment['content'] }}</p>
                                <small class="text-muted comment-date">{{ \Carbon\Carbon::parse($comment['date'])->format('d/m/Y, H:i:s') }}</small>
                            </div>
                        @endforeach
                    </div>

                    <!-- Form Input Komentar Baru -->
                    <form id="comment-form">
                        <div class="mt-3">
                            <textarea name="comment" id="comment-input" class="form-control" placeholder="Masukkan komentar baru..." rows="3" required></textarea>
                            <button type="submit" class="btn btn-primary mt-2">Kirim Komentar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal konfirmasi penghapusan -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmLabel">PERINGATAN !!</h5>
                <!-- Hapus span dan gunakan hanya btn-close -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus komentar ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger me-2" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    let deleteIndex = null;

    document.addEventListener("DOMContentLoaded", function () {
        let dropdownElements = document.querySelectorAll('.dropdown-toggle');
        dropdownElements.forEach(function (dropdown) {
            new bootstrap.Dropdown(dropdown);
        });

        setupEventListeners();
    });

    function setupEventListeners() {
        document.querySelectorAll(".delete-btn").forEach((btn) => {
            btn.addEventListener("click", function () {
                deleteIndex = this.getAttribute("data-index");
            });
        });

        document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
            if (deleteIndex !== null) {
                let commentContainers = document.querySelectorAll(".comment-item");
                if (commentContainers[deleteIndex]) {
                    commentContainers[deleteIndex].remove();
                }
                // Tutup modal setelah menghapus
                let deleteModal = bootstrap.Modal.getInstance(document.getElementById("deleteConfirmModal"));
                deleteModal.hide();
            }
        });

        document.querySelectorAll(".copy-btn").forEach((btn) => {
            btn.addEventListener("click", function () {
                let commentText = this.closest(".comment-item").querySelector(".comment-text").innerText;
                navigator.clipboard.writeText(commentText).then(() => {
                    alert("Komentar disalin!");
                });
            });
        });

        document.querySelectorAll(".edit-btn").forEach((btn) => {
            btn.addEventListener("click", function () {
                let commentTextElement = this.closest(".comment-item").querySelector(".comment-text");
                let oldText = commentTextElement.innerText;
                let newText = prompt("Edit komentar:", oldText);
                if (newText !== null && newText.trim() !== "") {
                    commentTextElement.innerText = newText;
                }
            });
        });

        document.getElementById("comment-form").addEventListener("submit", function (e) {
            e.preventDefault();
            let commentInput = document.getElementById("comment-input");
            let commentText = commentInput.value.trim();
            if (commentText === "") return;

            let commentIndex = document.querySelectorAll(".comment-item").length;

            let newComment = `
                <div class="comment-item mb-3 p-3 border rounded bg-light" data-index="${commentIndex}">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>
                            Zayn Malik <span class="text-muted small">(Dosen Pembimbing 3)</span>
                        </strong>
                        
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button class="dropdown-item edit-btn">
                                        <i class="fas fa-edit me-2"></i> Edit
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item copy-btn">
                                        <i class="fas fa-copy me-2"></i> Salin
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item text-danger fw-bold delete-btn" data-index="${commentIndex}" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                        <i class="fas fa-trash me-2 text-danger"></i> Hapus
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <p class="mt-2 mb-1 comment-text">${commentText}</p>
                    <small class="text-muted">${new Date().toLocaleString("id-ID")}</small>
                </div>
            `;

            document.getElementById("comments-list").insertAdjacentHTML("afterbegin", newComment);
            commentInput.value = "";

            setupEventListeners();
        });
    }
</script>
@stop
