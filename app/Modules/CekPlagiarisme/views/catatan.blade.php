<div class="card">
    <div class="card-body">
        <div id="comments-list">
            @foreach($catatan as $index => $item)
                <div class="comment-item mb-3 p-3 border rounded bg-light" data-index="{{ $index }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>
                            {{ $item['user'] }} <span class="text-muted small">({{ $item['role'] }})</span>
                        </strong>

                        <!-- Dropdown Menu -->
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
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

                    <p class="mt-2 mb-1 comment-text">{{ $item['content'] }}</p>
                    <small class="text-muted comment-date">{{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y, H:i:s') }}</small>
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

<!-- Modal konfirmasi penghapusan -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PERINGATAN !!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus komentar ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteIndex = null;

    document.addEventListener("DOMContentLoaded", function () {
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
                document.querySelector(`.comment-item[data-index='${deleteIndex}']`).remove();
                bootstrap.Modal.getInstance(document.getElementById("deleteConfirmModal")).hide();
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
                        <strong>Zayn Malik <span class="text-muted small">(Dosen Pembimbing 3)</span></strong>
                        
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item edit-btn"><i class="fas fa-edit me-2"></i> Edit</button></li>
                                <li><button class="dropdown-item copy-btn"><i class="fas fa-copy me-2"></i> Salin</button></li>
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
