<head>
    <!-- Meta tags untuk menyimpan data user -->
    <meta name="user-role" content="dosen">
    <meta name="logged-in-dosen-nip" content="1961011419920201001">
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
                            <button class="dropdown-item edit-btn" data-id="{{ $item->id }}" data-review="{{ $item->review }}">
                                <i class="fas fa-edit me-2"></i> Edit
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item copy-btn" data-id="{{ $item->id }}">
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
            @endif
        </div>

        <!-- Menampilkan teks komentar -->
        <p class="mt-2 mb-1 comment-text">{{ $item->review }}</p>
        <!-- Menampilkan tanggal komentar dengan format tertentu -->
        <small class="text-muted comment-date">
            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y, H:i:s') }}
        </small>
    </div>
@empty
    <!-- Jika tidak ada catatan, tampilkan pesan ini -->
    <div class="d-flex justify-content-center align-items-center" style="height: 80px;">
        <p class="text-muted italic-text">Belum ada catatan</p>
    </div>
@endforelse

<!-- ============================================================= -->
<!-- Bagian: Form Input Komentar Baru (Hanya Tampil untuk Dosen)   -->
<!-- ============================================================= -->
<!-- Form Input Komentar Baru -->
@can('dosen')
    <form id="comment-form" action="{{ env('PREFIX_URL', 'sipta-dev') . '/catatan-store/' . $dokumen->id }}" method="POST">
        @csrf
        <!-- Menyertakan ID Dokumen sebagai hidden field -->
        <input type="hidden" id="id_dokumen" value="{{ $dokumen->id }}">
        <div class="mt-3">
            <textarea name="comment" id="comment-input" class="form-control" placeholder="Masukkan komentar baru..."
                rows="3" required></textarea>
            <small class="text-muted float-end mt-1" id="word-count">0/500 kata</small>
            <button type="submit" class="btn btn-primary mt-2">Kirim Komentar</button>
        </div>
    </form>
@endcan

<!-- ============================================================= -->
<!-- Bagian: Modal Konfirmasi Penghapusan                       -->
<!-- ============================================================= -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmLabel">PERINGATAN !!</h5>
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
<!-- Bagian: Toast Notification untuk Menampilkan Pesan Salin   -->
<!-- ============================================================= -->
<div class="toast-container position-relative">
    <div id="copyToast" class="toast align-items-center text-bg-success border-0 position-absolute" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i> Copied to clipboard
            </div>
        </div>
    </div>
</div>

<!-- ============================================================= -->
<!-- Bagian: Toast Notification untuk Menampilkan Pesan Edit   -->
<!-- ============================================================= -->
<div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommentLabel">Edit Catatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea id="editCommentText" class="form-control" rows="3" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveEditComment">Simpan</button>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let deleteIndex = null;

    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("comment-form").addEventListener("submit", function (e) {
            e.preventDefault();

            // Ambil nilai komentar dan ID dokumen
            let commentText = document.getElementById("comment-input").value.trim();
            let dokumenId = document.getElementById('id_dokumen').value;

            // Ambil prefix URL dari meta tag yang ada di halaman
            var prefixUrl = $("meta[name='prefix-url']").attr("content");

            // Menyusun URL dengan prefix dan ID dokumen
            let url = `${prefixUrl}/catatan-store/${dokumenId}`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ comment: commentText })
            })
                .then(response => response.json())  // Parse sebagai JSON
                .then(data => {
                    if (data.success) {
                        // Jika berhasil, tambahkan komentar baru ke container
                        const catatanContainer = document.getElementById("catatan-container");
                        const newComment = document.createElement("div");
                        newComment.classList.add("comment-item", "mb-3", "p-3", "border", "rounded", "bg-light");
                        newComment.innerHTML = `
                    <strong>${data.catatan.dosen.user.nama ?? 'Nama Dosen Tidak Tersedia'}</strong>
                    <p>${data.catatan.review}</p>
                    <small class="text-muted">${new Date(data.catatan.created_at).toLocaleString()}</small>
                `;
                        catatanContainer.prepend(newComment);  // Menambahkan di atas
                        document.getElementById("comment-input").value = '';  // Reset input setelah berhasil
                    } else {
                        alert(data.message);  // Tampilkan pesan error jika gagal
                    }
                })
                .catch(err => {
                    console.error('Error:', err);  // Tangani jika ada error pada request
                });
        });
    });

</script>