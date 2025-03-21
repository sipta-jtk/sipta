<!-- ============================================================= -->
<!-- Bagian: Menampilkan Daftar Komentar (Comments List)          -->
<!-- ============================================================= -->
<div id="comments-list">
    @foreach($catatan as $index => $item)
        <div class="comment-item mb-3 p-3 border rounded bg-light" data-index="{{ $index }}">
            <div class="d-flex justify-content-between align-items-center">
                <strong>
                    <!-- Menampilkan Id Dosen atau pesan fallback jika tidak tersedia -->
                    {{ optional($item->dosen->user)->nama ?? 'Nama Dosen Tidak Tersedia' }}
                    
                    <!-- Menampilkan role dosen jika tersedia -->
                    <span class="text-muted small">
                        @if($item->dosen && $item->dosen->role_dosen)
                            ({{ $item->dosen->role_dosen }})
                        @else
                            (Role Tidak Tersedia)
                        @endif
                    </span>
                </strong>

                <!-- ========================================================= -->
                <!-- Bagian: Dropdown Menu Aksi (Edit, Salin, Hapus)         -->
                <!-- ========================================================= -->
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
                            <button class="dropdown-item text-danger fw-bold delete-btn" data-index="{{ $index }}"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                <i class="fas fa-trash me-2 text-danger"></i> Hapus
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Menampilkan teks komentar -->
            <p class="mt-2 mb-1 comment-text">{{ $item->review }}</p>
            <!-- Menampilkan tanggal komentar dengan format tertentu -->
            <small class="text-muted comment-date">
                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y, H:i:s') }}
            </small>
        </div>
    @endforeach
</div>

<!-- ============================================================= -->
<!-- Bagian: Form Input Komentar Baru                            -->
<!-- ============================================================= -->
<form id="comment-form">
    <div class="mt-3">
        <textarea name="comment" id="comment-input" class="form-control" placeholder="Masukkan komentar baru..."
            rows="3" required></textarea>
        <!-- Menampilkan hitungan kata dengan batas maksimum 500 kata -->
        <small class="text-muted float-end mt-1" id="word-count">0/500 kata</small>
        <button type="submit" class="btn btn-primary mt-2">Kirim Komentar</button>
    </div>
</form>

<!-- ============================================================= -->
<!-- Bagian: Modal Konfirmasi Penghapusan                       -->
<!-- ============================================================= -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmLabel">PERINGATAN !!</h5>
                <!-- Gunakan tombol close tanpa span tambahan -->
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

<!-- ============================================================= -->
<!-- Bagian: Style CSS (Pemuatan Library dan Styling Kustom)      -->
<!-- ============================================================= -->
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        /* Menghilangkan ikon dropdown default dari Bootstrap */
        .dropdown-toggle::after {
            display: none !important;
        }

        /* Styling khusus untuk hitungan kata */
        #word-count {
            font-size: 0.8rem;
        }

        /* Styling untuk memberi peringatan jika jumlah kata melebihi batas */
        .over-limit {
            color: red !important;
        }
    </style>
@endsection

<!-- ============================================================= -->
<!-- Bagian: JavaScript                                          -->
<!-- ============================================================= -->
<script>
    // Variabel global untuk menyimpan index komentar yang akan dihapus
    let deleteIndex = null;

    // Inisialisasi komponen Bootstrap dropdown dan event listener setelah DOM siap
    document.addEventListener("DOMContentLoaded", function () {
        // Inisialisasi Dropdown
        let dropdownElements = document.querySelectorAll('.dropdown-toggle');
        dropdownElements.forEach(function (dropdown) {
            new bootstrap.Dropdown(dropdown);
        });

        // Pasang event listener untuk semua aksi
        setupEventListeners();
    });

    // Fungsi untuk memasang event listener pada elemen-elemen interaktif
    function setupEventListeners() {
        // =========================================================
        // Event Listener untuk tombol Hapus
        // =========================================================
        document.querySelectorAll(".delete-btn").forEach((btn) => {
            btn.addEventListener("click", function () {
                deleteIndex = this.getAttribute("data-index");
            });
        });

        // Konfirmasi penghapusan melalui tombol di modal
        document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
            if (deleteIndex !== null) {
                let commentContainers = document.querySelectorAll(".comment-item");
                if (commentContainers[deleteIndex]) {
                    commentContainers[deleteIndex].remove();
                }
                // Menutup modal setelah komentar dihapus
                let deleteModal = bootstrap.Modal.getInstance(document.getElementById("deleteConfirmModal"));
                deleteModal.hide();
            }
        });

        // =========================================================
        // Event Listener untuk tombol Salin (Copy)
        // =========================================================
        document.querySelectorAll(".copy-btn").forEach((btn) => {
            btn.addEventListener("click", function () {
                let commentText = this.closest(".comment-item").querySelector(".comment-text").innerText;
                navigator.clipboard.writeText(commentText).then(() => {
                    alert("Komentar disalin!");
                });
            });
        });

        // =========================================================
        // Event Listener untuk tombol Edit
        // =========================================================
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

        // =========================================================
        // Event Listener untuk Form Komentar Baru
        // =========================================================
        document.getElementById("comment-form").addEventListener("submit", function (e) {
            e.preventDefault();
            let commentInput = document.getElementById("comment-input");
            let commentText = commentInput.value.trim();
            if (commentText === "") return;

            // Menghitung jumlah komentar yang sudah ada untuk menentukan indeks komentar baru
            let commentIndex = document.querySelectorAll(".comment-item").length;

            // Menggunakan Template Literal untuk membuat elemen HTML komentar baru
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

            // Menambahkan komentar baru ke bagian atas daftar
            document.getElementById("comments-list").insertAdjacentHTML("afterbegin", newComment);
            // Mengosongkan textarea setelah komentar dikirim
            commentInput.value = "";

            // Pemasangan kembali event listener untuk elemen-elemen baru
            setupEventListeners();
        });
    }

    // =========================================================
    // Event Listener: Hitung Jumlah Kata di Textarea Komentar Baru
    // =========================================================
    document.addEventListener("DOMContentLoaded", function () {
        const commentInput = document.getElementById("comment-input");
        const wordCountDisplay = document.getElementById("word-count");
        const maxWords = 500;

        commentInput.addEventListener("input", function () {
            let words = commentInput.value.trim().match(/\S+/g);
            let numWords = words ? words.length : 0;

            if (numWords > maxWords) {
                wordCountDisplay.classList.add('over-limit');
            } else {
                wordCountDisplay.classList.remove('over-limit');
            }

            wordCountDisplay.textContent = `${numWords}/${maxWords} kata`;
        });
    });
</script>

<!-- ============================================================= -->
<!-- Pemuatan Library JavaScript Bootstrap Terbaru                -->
<!-- ============================================================= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
