@extends('adminlte::page')

@section('title', 'Laporan TA')

@section('content_header')
    <h1 class="text-center">Laporan TA</h1>
@stop

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-box">
            <input type="text" class="form-control" id="searchInput" placeholder="Search here...">
        </div>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Versi</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Catatan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>1.0</td>
                <td>Sistem Manajemen TA</td>
                <td>2024-03-10</td>
                <td class="catatan-cell"></td>
                <td>                    
                    <button class="btn btn-sm btn-outline-primary edit-btn" data-toggle="modal" data-target="#editDocumentModal"
                        data-versi="1.0" data-judul="Sistem Manajemen TA" data-tanggal="2024-03-10" data-catatan="">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal Edit Dokumen -->
<div class="modal fade" id="editDocumentModal" tabindex="-1" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDocumentModalLabel">Edit Laporan TA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editRowIndex">
                    
                    <div class="mb-3">
                        <label for="editVersi" class="form-label">Versi:</label>
                        <input type="text" class="form-control" id="editVersi" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editJudul" class="form-label">Judul:</label>
                        <input type="text" class="form-control" id="editJudul" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editTanggal" class="form-label">Tanggal:</label>
                        <input type="text" class="form-control" id="editTanggal" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editCatatan" class="form-label">Catatan:</label>
                        <textarea class="form-control" id="editCatatan" rows="3"></textarea>
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

@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log("Laporan TA page loaded.");

        // Event listener untuk tombol edit
        document.querySelectorAll('.edit-btn').forEach((button, index) => {
            button.addEventListener('click', function() {
                let versi = this.getAttribute('data-versi');
                let judul = this.getAttribute('data-judul');
                let tanggal = this.getAttribute('data-tanggal');
                let catatan = this.getAttribute('data-catatan');

                document.getElementById('editVersi').value = versi;
                document.getElementById('editJudul').value = judul;
                document.getElementById('editTanggal').value = tanggal;
                document.getElementById('editCatatan').value = catatan;
                document.getElementById('editRowIndex').value = index;
            });
        });

        // Event listener untuk submit form edit
        document.getElementById('editForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            let rowIndex = document.getElementById('editRowIndex').value;
            let newCatatan = document.getElementById('editCatatan').value;
            let tableRow = document.querySelectorAll("tbody tr")[rowIndex];

            if (tableRow) {
                tableRow.querySelector(".catatan-cell").textContent = newCatatan;
            }

            $('#editDocumentModal').modal('hide');
        });

        // Fitur pencarian pada tabel
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    });
</script>
@stop