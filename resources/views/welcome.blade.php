@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to sipta.</p>

    <!-- Tombol untuk Membuat Data Baru -->
    <button id="createDataButton" class="btn btn-primary">Buat Data Baru</button>

    <!-- Tombol untuk Mengubah Nilai Data -->
    <button id="updateDataButton" class="btn btn-success" style="margin-left: 10px;">Ubah Nilai Data</button>

    <!-- Input Field untuk Menampilkan/Mengedit Nilai -->
    <div style="margin-top: 20px;">
        <label for="dataField">Nilai:</label>
        <input type="text" id="dataField" class="form-control" readonly>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            let currentId = null; // Untuk menyimpan ID data yang dibuat atau diperbarui

            // Event listener untuk tombol "Buat Data Baru"
            $('#createDataButton').on('click', function () {
                // Kirim POST request ke endpoint untuk membuat data baru
                fetch('/create-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ value: 'Nilai Default' }) // Kirim nilai awal
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data berhasil dibuat:', data);
                        alert('Data berhasil dibuat!');
                        currentId = data.id; // Simpan ID data yang baru dibuat
                        $('#dataField').val(data.value).prop('readonly', false); // Aktifkan input field
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat membuat data.');
                    });
            });

            // Event listener untuk tombol "Ubah Nilai Data"
            $('#updateDataButton').on('click', function () {
                if (!currentId) {
                    alert('Silakan buat data terlebih dahulu!');
                    return;
                }

                const updatedValue = $('#dataField').val(); // Ambil nilai dari input field

                // Kirim PUT/PATCH request ke endpoint untuk memperbarui nilai
                fetch(`/update-data/${currentId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ value: updatedValue }) // Kirim nilai baru
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(result => {
                        console.log('Data berhasil diperbarui:', result);
                        alert('Nilai berhasil diperbarui!');
                        $('#dataField').prop('readonly', true); // Nonaktifkan input field
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memperbarui nilai.');
                    });
            });
        });
    </script>
@stop