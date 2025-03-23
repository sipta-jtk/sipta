<head>
    <!-- Meta tags untuk menyimpan data user -->
    <meta name="user-role" content="{{ auth()->user()->role_user }}">
    <meta name="logged-in-dosen-nip" content="{{ auth()->user()->dosen->nip ?? '' }}">
</head>

@forelse($catatan as $index => $item)
    <div class="comment-item mb-3 p-3 border rounded bg-light" data-index="{{ $index }}"
        data-dosen-nip="{{ $item->dosen->nip ?? '' }}">
        <div class="d-flex justify-content-between align-items-center">
            <strong>
                {{ $item->dosen->user->nama ?? 'Nama Dosen Tidak Tersedia' }}
                @php
                    $alokasi = $alokasiDosen->where('nip', $item->dosen->nip)->first();
                @endphp
                <span class="text-muted small">
                    @if($alokasi)
                        (Dosen Pembimbing {{ $alokasi->urutan_prioritas_terpilih }})
                    @else
                        (Dosen Pembimbing)
                    @endif
                </span>
            </strong>

            <!-- Dropdown Aksi hanya untuk Dosen yang Login dan Penulis Komentar -->
            @if(auth()->user()->role_user === 'dosen' && auth()->user()->dosen->nip === $item->dosen->nip)
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button class="dropdown-item edit-btn" data-id="{{ $item->id_review }}" data-review="{{ $item->review }}"
                                data-bs-toggle="modal" data-bs-target="#editCommentModal">
                                <i class="fas fa-edit me-2"></i> Edit
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item copy-btn" data-review="{{ $item->review }}">
                                <i class="fas fa-copy me-2"></i> Salin
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item text-danger fw-bold delete-btn" data-index="{{ $index }}" data-id="{{ $item->id_review }}"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                <i class="fas fa-trash me-2 text-danger"></i> Hapus
                            </button>
                        </li>
                    </ul>
                </div>
            @endif
        </div>

        <!-- Menampilkan teks komentar -->
        <p class="mt-2 mb-1 comment-text">{{ $item->review }}</p>
        <!-- Menampilkan tanggal komentar dengan format tertentu -->
        <small class="text-muted comment-date">
            {{ \Carbon\Carbon::parse($item->update_at)->format('d/m/Y, H:i:s') }}
        </small>
    </div>
@empty
    <div class="d-flex justify-content-center align-items-center" style="height: 80px;">
        <p class="text-muted italic-text">Belum ada catatan</p> <!-- Pesan jika tidak ada komentar -->
    </div>
@endforelse

<!-- ============================================================= -->
<!-- Bagian: Form Input Komentar Baru (Hanya Tampil untuk Dosen)   -->
<!-- ============================================================= -->
@can('dosen')
    <form id="comment-form" method="post" data-dokumen-id="{{ $dokumen->id_dokumen }}">
        @csrf
        <input type="hidden" id="id_dokumen" value="{{ $dokumen->id_dokumen }}">
        <div class="mt-3">
            <textarea name="comment" id="comment-input" class="form-control" placeholder="Masukkan komentar baru..."
                rows="3" required></textarea>
            <small class="text-muted float-end mt-1" id="word-count">0/500 kata</small>
            <button type="submit" class="btn btn-primary mt-2">Kirim Komentar</button>
        </div>
    </form>
@endcan

<!-- ========================================== -->
<!-- Bagian: Modal untuk Konfirmasi Penghapusan -->
<!-- ========================================== -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmLabel">PERINGATAN !!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus catatan ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger me-2" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- ==================================== -->
<!-- Bagian: Modal untuk konfirmasi salin -->
<!-- ==================================== -->
<div class="modal fade" id="copySuccessModal" tabindex="-1" aria-labelledby="copySuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="copySuccessLabel">Sukses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Teks telah berhasil disalin ke clipboard!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- =================================== -->
<!-- Bagian: Modal untuk konfirmasi Edit -->
<!-- =================================== -->
<div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="post" id="edit_comment_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCommentLabel">Edit Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="editCommentText" class="form-control" rows="3" required></textarea>
                </div>
                <input type="text" id="editCommentId" hidden>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveEditComment">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================= -->
<!-- Bagian: Style CSS (Pemuatan Library dan Styling Kustom)      -->
<!-- ============================================================= -->
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="prefix-url" content="{{ env('PREFIX_URL', 'sipta-dev') }}">

    <style>
        .dropdown-toggle::after {
            display: none !important;
        }

        #word-count {
            font-size: 0.8rem;
        }

        .over-limit {
            color: red !important;
        }

        .italic-text {
            font-style: italic;
            margin: 0;
        }

        .toast {
            border-radius: 0.375rem;
            font-size: 0.875rem;
            padding: 6px 12px;
            max-width: 180px;
            margin-bottom: 4px;
            position: absolute;
        }

        .toast-body {
            font-weight: 500;
            color: #ffffff;
            display: flex;
            align-items: center;
            padding: 0;
        }

        .toast-container {
            position: absolute;
            z-index: 9999;
        }

        .text-bg-success {
            background-color: #28a745 !important;
        }

        .fas.fa-check-circle {
            font-size: 1rem;
            color: #ffffff;
        }
    </style>
@endsection

<!-- ============================================================= -->
<!-- Bagian: JavaScript                                          -->
<!-- ============================================================= -->
<!-- JavaScript untuk mengelola form submission dan word counter -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('comment-form');

        // Ambil prefix URL dari meta tag
        const prefixUrl = document.querySelector("meta[name='prefix-url']")
            ? document.querySelector("meta[name='prefix-url']").getAttribute("content")
            : 'sipta-dev';

        // Ambil ID dokumen dari atribut data-dokumen-id
        const dokumenId = form.getAttribute('data-dokumen-id');


        // Event listener untuk submit form
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah pengiriman form normal

            const commentText = document.getElementById('comment-input').value.trim();
            const dokumenId = form.getAttribute('data-dokumen-id'); // ID dokumen dari form

            const url = `/${prefixUrl}/cek-plagiarisme/catatan-store/${dokumenId}`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ comment: commentText })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                        title: 'Berhasil!',
                        text: 'Catatan berhasil ditambahkan!',
                        icon: 'success'
                    }).then(() => {
                        document.getElementById('comment-input').value = ''; // Reset form
                        window.location.reload(); // Reload halaman
                    });                    
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Gagal menambahkan catatan',
                        icon: 'error'
                    });                    
                }
            })
            .catch(error => {
            // Tampilkan SweetAlert error jika terjadi masalah
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengirim data',
                icon: 'error'
            });
        });        
    });

        // Fungsi untuk menambahkan komentar baru ke UI
        function addNewCommentToUI(catatan) {
            // Cek jika container untuk komentar kosong ada
            const emptyContainer = document.querySelector('.d-flex.justify-content-center');
            if (emptyContainer) {
                // Jika ada container "Belum ada catatan", hapus dan buat container baru
                const parentElement = emptyContainer.parentNode;
                parentElement.innerHTML = '';

                // Buat komentar baru
                const newComment = createCommentElement(catatan);
                parentElement.appendChild(newComment);
            } else {
                // Jika sudah ada komentar, tambahkan di atas
                const firstComment = document.querySelector('.comment-item');
                if (firstComment && firstComment.parentNode) {
                    const newComment = createCommentElement(catatan);
                    firstComment.parentNode.insertBefore(newComment, firstComment);
                }
            }
        }

        // Fungsi untuk membuat elemen komentar baru
        function createCommentElement(catatan) {
            const newComment = document.createElement('div');
            newComment.className = 'comment-item mb-3 p-3 border rounded bg-light';

            // Format tanggal
            const date = new Date(catatan.created_at);
            const formattedDate = date.toLocaleDateString('id-ID') + ', ' +
                date.toLocaleTimeString('id-ID');

            // HTML untuk komentar baru
            newComment.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <strong>
                    ${catatan.dosen?.user?.nama || 'Nama Dosen Tidak Tersedia'}
                    <span class="text-muted small">
                        (Dosen Pembimbing)
                    </span>
                </strong>

                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button class="dropdown-item edit-btn" data-id="${catatan.id}" data-review="${catatan.review}">
                                <i class="fas fa-edit me-2"></i> Edit
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item copy-btn" data-id="${catatan.id}">
                                <i class="fas fa-copy me-2"></i> Salin
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item text-danger fw-bold delete-btn" data-index="${catatan.id}"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                <i class="fas fa-trash me-2 text-danger"></i> Hapus
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <p class="mt-2 mb-1 comment-text">${catatan.review}</p>
            <small class="text-muted comment-date">
                ${formattedDate}
            </small>
        `;

            return newComment;
        }

        // Word counter untuk textarea
        const commentInput = document.getElementById('comment-input');
        const wordCount = document.getElementById('word-count');

        if (commentInput && wordCount) {
            commentInput.addEventListener('input', function () {
                const words = this.value.trim().split(/\s+/).length;
                if (this.value.trim() === '') {
                    wordCount.textContent = '0/500 kata';
                } else {
                    wordCount.textContent = `${words}/500 kata`;
                }

                if (words > 500) {
                    wordCount.classList.add('over-limit');
                } else {
                    wordCount.classList.remove('over-limit');
                }
            });
        }
    });


    $(document).ready(function () {

        $(".edit-btn").click(function () {

            let review = $(this).data("review");
            let id = $(this).data("id");

            // Set nilai ke dalam modal
            $("#editCommentText").val(review);
            $("#editCommentId").attr("data-id", id);
        });

        $("#saveEditComment").click(function () {
        const dokumenId = $("#id_dokumen").val();

        const prefixUrl = document.querySelector("meta[name='prefix-url']")
            ? document.querySelector("meta[name='prefix-url']").getAttribute("content")
            : 'sipta-dev';
            let id = $("#editCommentId").attr("data-id");
            let review = $("#editCommentText").val();
            const url = `/${prefixUrl}/cek-plagiarisme/catatan-store/${dokumenId}/${id}`;
            
            console.log(url)
            $.ajax({
                url: url,
                type: "PUT",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    "Content-Type": "application/json",
                },
                data: JSON.stringify({ comment: review }),
                    success: function (response) {
                            Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload(); // Reload halaman setelah update berhasil
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Gagal memperbarui catatan!',
                        icon: 'error'
                    });
                },
            });
        });
    });


    $(document).ready(function () {
    let deleteId = null; // Variabel untuk menyimpan ID yang akan dihapus

    // Saat tombol "Hapus" dalam dropdown diklik
    $(".delete-btn").click(function () {
        deleteId = $(this).data("id"); // Ambil ID dari data-id
    });


    const prefixUrl = document.querySelector("meta[name='prefix-url']")
            ? document.querySelector("meta[name='prefix-url']").getAttribute("content")
            : 'sipta-dev';

        const dokumenId = $("#id_dokumen").val();


        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


    // Saat tombol "Hapus" di modal diklik
    $("#confirmDeleteBtn").click(function () {
        if (deleteId) {
            const url = `/${prefixUrl}/cek-plagiarisme/catatan-store/${dokumenId}/${deleteId}`;

            $.ajax({
                headers: {
                'X-CSRF-TOKEN': csrfToken,
                "Content-Type": "application/json",
            },
                url: url, // Ganti dengan endpoint API yang sesuai
                type: "DELETE",
                data: { id: deleteId }, // Kirim ID ke server
                success: function (response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload(); // Reload halaman setelah penghapusan berhasil
                    });                
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Catatan gagal dihapus.',
                        icon: 'error'
                    });               
                },
            });
        }
    });

    $(document).ready(function () {
        $(".copy-btn").click(function () {
            let commentText = $(this).data("review"); // Mengambil teks komentar dari data-attribute
            
            // Cek apakah Clipboard API tersedia
            if (navigator.clipboard) {
                // Salin teks komentar ke clipboard
                navigator.clipboard.writeText(commentText).then(function () {
                    // Ganti dengan alert sebagai feedback
                    alert('Teks telah berhasil disalin ke clipboard!');
                }).catch(function (error) {
                    alert('Gagal menyalin komentar: ' + error);
                });
            } else {
                alert('Clipboard API tidak didukung di browser ini.');
            }
        });
    });
});

</script>