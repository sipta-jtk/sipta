@extends('adminlte::page')

@section('title', 'Subkategori Artefak')

@section('content_header')
<h1 class="text-center">SUBKATEGORI ARTEFAK</h1>
@stop

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-box">
            <input type="text" class="form-control" id="searchInput" placeholder="Search here...">
        </div>
        <div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addSubkategoriModal">
                + Add Subkategori
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
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
                <th style="width: 5%;">No</th>
                <th style="width: 70%;">Subkategori</th>
                <th style="width: 25%;">Action</th>
            </tr>
    </thead>
        <tbody>
            @if ($subkategoris->isEmpty())
            <tr>
                <td colspan="3" class="text-center">
                    <p class="text-muted">List Subkategori Kosong, Belum Ada Subkategori Yang Ditambahkan</p>
                </td>
            </tr>
            @else
            @foreach ($subkategoris as $index => $subkategori)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $subkategori->nama_subkategori }}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-btn"
                        data-id="{{ $subkategori->id_subkategori }}"
                        data-nama="{{ $subkategori->nama_subkategori }}"
                        data-toggle="modal" data-target="#editSubkategoriModal">
                        <i class="fas fa-edit"></i> Edit
                    </button>

                    <form action="{{ route('Subkategori.destroy', ['kategori' => $kategori, 'id' => $subkategori->id_subkategori]) }}"
                        method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Yakin ingin menghapus subkategori ini?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>


    <div class="mt-3">
        <a href="{{ route('Repository.index.kota', ['id_kota' => auth()->user()->mahasiswa->id_kota ?? 1, 'kategori' => $kategori]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- Modal Tambah Subkategori -->
<div class="modal fade" id="addSubkategoriModal" tabindex="-1" aria-labelledby="addSubkategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubkategoriModalLabel">Tambah Subkategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Subkategori.store', $kategori) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_subkategori" class="form-label">Nama Subkategori:</label>
                        <input type="text" class="form-control" id="nama_subkategori" name="nama_subkategori" required>
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

<!-- Modal Edit Subkategori -->
<div class="modal fade" id="editSubkategoriModal" tabindex="-1" aria-labelledby="editSubkategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editSubkategoriForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubkategoriModalLabel">Edit Subkategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editSubkategoriId" name="id">
                    <div class="mb-3">
                        <label for="editNamaSubkategori" class="form-label">Nama Subkategori:</label>
                        <input type="text" class="form-control" id="editNamaSubkategori" name="nama_subkategori" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script>
    // Auto-close success alert (hilang dan hapus DOM-nya biar layout responsif lagi)
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500); // Hapus total elemen dari DOM
        }
    }, 3000);

    // Modal edit logic
    document.addEventListener('DOMContentLoaded', () => {
        const editButtons = document.querySelectorAll('.edit-btn');
        const editForm = document.getElementById('editSubkategoriForm');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const nama = this.dataset.nama;

                document.getElementById('editSubkategoriId').value = id;
                document.getElementById('editNamaSubkategori').value = nama;

                editForm.action = `/repository/mahasiswa/{{ $kategori }}/subkategori/${id}`;
            });
        });
    });
     // Search functionality
     document.getElementById("searchInput").addEventListener("keyup", function () {
        let input = this.value.toLowerCase();
        let rows = document.querySelectorAll("table tbody tr");

        rows.forEach(row => {
            let namaSubkategori = row.cells[1]?.innerText.toLowerCase();
            if (namaSubkategori && namaSubkategori.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endsection
