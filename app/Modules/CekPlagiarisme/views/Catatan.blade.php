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

        <!-- Dropdown Aksi hanya untuk Dosen yang Login dan Penulis catatan -->
        @if(auth()->user()->role_user === 'dosen' && auth()->user()->dosen->nip === $item->dosen->nip)
        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <button class="dropdown-item edit-btn" data-id="{{ $item->id_review }}"
                        data-review="{{ $item->review }}" data-bs-toggle="modal" data-bs-target="#editCommentModal">
                        <i class="fas fa-edit me-2"></i> Ubah
                    </button>
                </li>
                <li>
                    <button class="dropdown-item copy-btn" data-review="{{ $item->review }}">
                        <i class="fas fa-copy me-2"></i> Salin
                    </button>
                </li>
                <li>
                    <button class="dropdown-item text-danger fw-bold delete-btn" data-index="{{ $index }}"
                        data-id="{{ $item->id_review }}" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                        <i class="fas fa-trash me-2 text-danger"></i> Hapus
                    </button>
                </li>
            </ul>
        </div>
        @endif
    </div>

    <!-- Menampilkan teks catatan -->
    <p class="mt-2 mb-1 comment-text">{{ $item->review }}</p>
    <!-- Menampilkan tanggal catatan dengan format tertentu -->
    <small class="text-muted comment-date">
        {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('H:i d F Y') }}
    </small>
</div>
@empty
<div class="d-flex justify-content-center align-items-center" style="height: 80px;">
    <p class="text-muted italic-text">Belum ada catatan</p> <!-- Pesan jika tidak ada catatan -->
</div>
@endforelse

<!-- ============================================================= -->
<!-- Bagian: Form Input catatan Baru (Hanya Tampil untuk Dosen)   -->
<!-- ============================================================= -->
@can('dosen')
<form id="comment-form" method="post" data-dokumen-id="{{ $dokumen->id_dokumen }}">
    @csrf
    <input type="hidden" id="id_dokumen" value="{{ $dokumen->id_dokumen }}">
    <div class="mt-3">
        <textarea name="comment" id="comment-input" class="form-control" placeholder="Masukkan catatan baru..." rows="3"
            required></textarea>
        <div class="d-flex pt-2 justify-content-end">
            <small class="text-muted me-auto my-auto" id="word-count">0/500 karakter</small>
            <x-adminlte-button id="submit-button" theme="success" class="bg-gradient-success border-0 mx-1" label="Kirim" type="submit" />
        </div>
    </div>
</form>
@endcan

<!-- ========================================== -->
<!-- Bagian: Modal untuk Konfirmasi Penghapusan -->
<!-- ========================================== -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="deleteConfirmLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus catatan ini?</p>
            </div>
            <div class="modal-footer">
                <x-adminlte-button label="Batal" theme="secondary" class="bg-gradient-secondary mx-1 border-0"
                    data-bs-dismiss="modal" type="button" />
                <x-adminlte-button label="Hapus" theme="danger" class="bg-gradient-danger mx-1 border-0"
                    id="confirmDeleteBtn" type="button" />
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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editCommentLabel">Ubah Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="editCommentText" class="form-control" rows="3" required></textarea>
                    <small class="text-muted mt-1" id="edit-word-count">0/500 karakter</small>
                </div>
                <input type="text" id="editCommentId" hidden>
                <div class="modal-footer">
                    <x-adminlte-button label="Batal" theme="secondary" class="bg-gradient-secondary mx-1 border-0"
                        data-bs-dismiss="modal" type="button" />
                    <x-adminlte-button label="Simpan" theme="success" class="bg-gradient-success mx-1 border-0"
                        id="saveEditComment" type="button" />
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
    // Ambil prefix URL dari meta tag
    const prefixUrl = document.querySelector("meta[name='prefix-url']") ?
        document.querySelector("meta[name='prefix-url']").getAttribute("content") :
        'sipta-dev';

    // Fungsi untuk membuat URL API yang benar (tanpa duplikasi prefix)
    function createApiUrl(endpoint) {
        // Periksa apakah url sudah berisi domain name atau dimulai dengan http
        if (endpoint.includes('://') || endpoint.startsWith('http')) {
            return endpoint;
        }

        // Hapus slash di awal endpoint jika ada
        if (endpoint.startsWith('/')) {
            endpoint = endpoint.substring(1);
        }

        // Hapus slash di akhir prefixUrl jika ada
        let prefix = prefixUrl;
        if (prefix.endsWith('/')) {
            prefix = prefix.substring(0, prefix.length - 1);
        }

        // Gabungkan dengan slash di tengah
        return '/' + prefix + '/' + endpoint;
    }

    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('comment-form');

        // Ambil ID dokumen dari atribut data-dokumen-id
        const dokumenId = form.getAttribute('data-dokumen-id');

        // Event listener untuk submit form
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah pengiriman form normal

            const commentText = document.getElementById('comment-input').value.trim();
            const dokumenId = form.getAttribute('data-dokumen-id'); // ID dokumen dari form

            const url = createApiUrl(`cek-plagiarisme/catatan-store/${dokumenId}`);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            console.log("URL for POST request:", url); // Debugging URL

            // Show loading
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        comment: commentText
                    })
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

        // Fungsi untuk menambahkan catatan baru ke UI
        function addNewCommentToUI(catatan) {
            // Cek jika container untuk catatan kosong ada
            const emptyContainer = document.querySelector('.d-flex.justify-content-center');
            if (emptyContainer) {
                // Jika ada container "Belum ada catatan", hapus dan buat container baru
                const parentElement = emptyContainer.parentNode;
                parentElement.innerHTML = '';

                // Buat catatan baru
                const newComment = createCommentElement(catatan);
                parentElement.appendChild(newComment);
            } else {
                // Jika sudah ada catatan, tambahkan di atas
                const firstComment = document.querySelector('.comment-item');
                if (firstComment && firstComment.parentNode) {
                    const newComment = createCommentElement(catatan);
                    firstComment.parentNode.insertBefore(newComment, firstComment);
                }
            }
        }

        // Fungsi untuk membuat elemen catatan baru
        function createCommentElement(catatan) {
            const newComment = document.createElement('div');
            newComment.className = 'comment-item mb-3 p-3 border rounded bg-light';

            // Format tanggal
            const date = new Date(catatan.created_at);
            const formattedDate = date.toLocaleDateString('id-ID') + ', ' +
                date.toLocaleTimeString('id-ID');

            // HTML untuk catatan baru
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

        // Character counter untuk textarea
        const commentInput = document.getElementById('comment-input');
        const charCount = document.getElementById('word-count');
        const submitButton = document.getElementById('submit-button');

        if (commentInput && charCount && submitButton) {
            commentInput.addEventListener('input', function() {
                const charCountValue = this.value.length;

                if (this.value.trim() === '') {
                    charCount.textContent = '0/500 karakter';
                } else {
                    charCount.textContent = `${charCountValue}/500 karakter`;
                }

                if (charCountValue > 500) {
                    charCount.classList.add('over-limit');
                    submitButton.disabled = true;
                    this.classList.add('is-invalid');
                } else {
                    charCount.classList.remove('over-limit');
                    submitButton.disabled = false;
                    this.classList.remove('is-invalid');
                }
            });
        }
    });

    $(document).ready(function() {
        const editCommentInput = document.getElementById('editCommentText');
        const editCharCount = document.getElementById('edit-word-count');
        const saveEditButton = document.getElementById('saveEditComment');

        $(".edit-btn").click(function() {
            let review = $(this).data("review");
            let id = $(this).data("id");

            // Set nilai ke dalam modal
            $("#editCommentText").val(review);
            $("#editCommentId").attr("data-id", id);

            if (editCharCount) {
                editCharCount.textContent = `${review.length}/500 karakter`;
                if (review.length > 500) {
                    editCharCount.classList.add('over-limit');
                    saveEditButton.disabled = true;
                } else {
                    editCharCount.classList.remove('over-limit');
                    saveEditButton.disabled = false;
                }
            }
        });

        if (editCommentInput && editCharCount && saveEditButton) {
            editCommentInput.addEventListener('input', function() {
                const charCountValue = this.value.length;

                if (this.value.trim() === '') {
                    editCharCount.textContent = '0/500 karakter';
                } else {
                    editCharCount.textContent = `${charCountValue}/500 karakter`;
                }

                // validasi jumlah karakter
                if (charCountValue > 500) {
                    editCharCount.classList.add('over-limit');
                    saveEditButton.disabled = true;
                    this.classList.add('is-invalid');
                } else {
                    editCharCount.classList.remove('over-limit');
                    saveEditButton.disabled = false;
                    this.classList.remove('is-invalid');
                }
            });
        }

        $("#saveEditComment").click(function() {
            const dokumenId = $("#id_dokumen").val();
            const prefixUrl = document.querySelector("meta[name='prefix-url']") ?
                document.querySelector("meta[name='prefix-url']").getAttribute("content") :
                'sipta-dev';
            let id = $("#editCommentId").attr("data-id");
            let review = $("#editCommentText").val();
            const url = createApiUrl(`cek-plagiarisme/catatan-store/${dokumenId}/${id}`);

            // Show loading
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            console.log("Edit URL:", url) // Debugging URL
            $.ajax({
                url: url,
                type: "PUT",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    "Content-Type": "application/json",
                },
                data: JSON.stringify({
                    comment: review
                }),
                success: function(response) {
                    $("#editCommentModal").modal('hide');
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload(); // Reload halaman setelah update berhasil
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Gagal memperbarui catatan!',
                        icon: 'error'
                    });
                },
            });
        });
    });


    $(document).ready(function() {

        let deleteId = null; // Variabel untuk menyimpan ID yang akan dihapus

        // Saat tombol "Hapus" dalam dropdown diklik
        $(".delete-btn").click(function() {
            deleteId = $(this).data("id"); // Ambil ID dari data-id
        });


        const prefixUrl = document.querySelector("meta[name='prefix-url']") ?
            document.querySelector("meta[name='prefix-url']").getAttribute("content") :
            'sipta-dev';

        const dokumenId = $("#id_dokumen").val();

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Saat tombol "Hapus" di modal diklik
        $("#confirmDeleteBtn").click(function() {
            if (deleteId) {
                const url = createApiUrl(`cek-plagiarisme/catatan-store/${dokumenId}/${deleteId}`);
                console.log("Delete URL:", url); // Debugging URL

                // Show loading
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        "Content-Type": "application/json",
                    },
                    url: url, // Ganti dengan endpoint API yang sesuai
                    type: "DELETE",
                    data: {
                        id: deleteId
                    }, // Kirim ID ke server
                    success: function(response) {
                        $("#deleteConfirmModal").modal('hide'); // Tutup modal konfirmasi
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            location.reload(); // Reload halaman setelah penghapusan berhasil
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Catatan gagal dihapus.',
                            icon: 'error'
                        });
                    },
                });
            }
        });

        $(document).ready(function() {
            $(".copy-btn").click(function() {
                let commentText = $(this).data("review"); // Mengambil teks catatan dari data-attribute

                // Cek apakah Clipboard API tersedia
                if (navigator.clipboard) {
                    // Salin teks catatan ke clipboard
                    navigator.clipboard.writeText(commentText).then(function() {
                        // Ganti dengan alert sebagai feedback
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Teks telah berhasil disalin ke clipboard!',
                            icon: 'success'
                        });
                    }).catch(function(error) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Gagal menyalin catatan: ' + error,
                            icon: 'success'
                        });
                    });
                } else {
                    alert('Clipboard API tidak didukung di browser ini.');
                }
            });
        });
    });
</script>