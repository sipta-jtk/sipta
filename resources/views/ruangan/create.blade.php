@extends('adminlte::page')

@section('title', 'Create/Update Ruangan')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .content-wrapper {
        background-color: #ffffff !important;
    }

    .card {
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 25px;
        border: none;
        background: #fff;
    }

    .section-title {
        color: #0d6efd;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }

    .form-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .form-label {
        color: #212529;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 15px;
        padding: 3rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #ffffff;
        min-height: 250px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
    }

    .upload-area:hover {
        border-color: #0d6efd;
        background-color: #f8f9fa;
    }

    .facility-input-container {
        margin-bottom: 1rem;
    }

    .facility-input-group {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .facility-input-group .form-select {
        flex: 2;
    }

    .facility-input-group .form-control {
        flex: 1;
        max-width: 120px;
    }

    .btn-add {
        background: #0d6efd;
        color: white;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }

    .btn-submit {
        background: #198754;
        color: white;
        border-radius: 8px;
        padding: 0.75rem 2rem;
    }

    .btn-cancel {
        background: #dc3545;
        color: white;
        border-radius: 8px;
        padding: 0.75rem 2rem;
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    /* Override AdminLTE styles */
    .content-wrapper > .content {
        padding: 2rem;
    }

    #imageContainer {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .btn-close {
        z-index: 10;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-close:hover {
        transform: scale(1.1);
        background-color: #dc3545 !important;
        color: white;
    }

    .facility-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .facility-item {
        background-color: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }

    .facility-item:hover {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transform: translateY(-1px);
    }

    .facility-info {
        flex-grow: 1;
    }

    .facility-info strong {
        display: block;
        color: #2c3e50;
        margin-bottom: 2px;
    }

    .facility-info .text-muted {
        font-size: 0.875rem;
    }

    .facility-controls {
        display: flex;
        gap: 8px;
    }

    .facility-controls button {
        padding: 4px 8px;
        border-radius: 4px;
    }

    .invalid-feedback {
        display: block;
        margin-top: 0.5rem;
    }

    .facility-error-container {
        min-height: 24px; /* Tinggi minimum untuk menghindari lompatan layout */
    }

    .facility-error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }

    .facility-error.removing {
        animation: fadeOut 0.3s ease-in-out;
    }
</style>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body p-4">
            <h2 class="text-center mb-4">Create Ruangan</h2>
            
            <form action="{{ route('ruangan.store') }}" 
                method="POST" 
                enctype="multipart/form-data">
                @csrf
                @if(isset($ruangan))
                    @method('PUT')
                @endif

                <!-- Data Ruangan Section -->
                <div class="form-section">
                    <h5 class="section-title">Data Ruangan</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Code Ruangan</label>
                                <input type="text" 
                                    class="form-control @error('kode_ruangan') is-invalid @enderror" 
                                    name="kode_ruangan"
                                    value="{{ old('kode_ruangan', $ruangan->kode_ruangan ?? '') }}"
                                    placeholder="Masukan Code Ruangan Anda"
                                    maxlength="6"
                                    required>
                                @error('kode_ruangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Ruangan</label>
                                <input type="text" 
                                    class="form-control @error('nama_ruangan') is-invalid @enderror" 
                                    name="nama_ruangan"
                                    value="{{ old('nama_ruangan', $ruangan->nama_ruangan ?? '') }}"
                                    placeholder="Masukan Nama Ruangan Anda"
                                    maxlength="127"
                                    required>
                                @error('nama_ruangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Code Gedung</label>
                                <select class="form-select @error('kode_gedung') is-invalid @enderror" 
                                        name="kode_gedung" 
                                        required>
                                    <option value="">Pilih Code Gedung</option>
                                    @foreach($gedung as $g)
                                        <option value="{{ $g->kode_gedung }}" 
                                            {{ old('kode_gedung', $ruangan->kode_gedung ?? '') == $g->kode_gedung ? 'selected' : '' }}>
                                            {{ $g->nama_gedung }} ({{ $g->kode_gedung }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kode_gedung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select @error('status_ruangan') is-invalid @enderror" 
                                        name="status_ruangan" 
                                        required>
                                    <option value="">Pilih Status Ruangan</option>
                                    <option value="tersedia" {{ old('status_ruangan', $ruangan->status_ruangan ?? '') == 'tersedia' ? 'selected' : '' }}>
                                        Tersedia
                                    </option>
                                    <option value="tidak_tersedia" {{ old('status_ruangan', $ruangan->status_ruangan ?? '') == 'tidak_tersedia' ? 'selected' : '' }}>
                                        Tidak Tersedia
                                    </option>
                                </select>
                                @error('status_ruangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Foto Ruangan Section -->
                    <div class="col-md-6">
                        <div class="form-section h-100">
                            <h5 class="section-title">Foto Ruangan</h5>
                            <div class="upload-area" id="uploadArea">
                                <div id="uploadPrompt" class="{{ isset($ruangan) && $ruangan->foto ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                    <p class="mb-0">Klik untuk upload foto</p>
                                    <small class="text-muted">atau drag and drop file di sini</small>
                                </div>
                                <div id="imageContainer" class="position-relative {{ isset($ruangan) && $ruangan->foto ? '' : 'd-none' }}">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" id="removeImage" style="background-color: #fff; padding: 8px; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <img id="imagePreview" 
                                        class="img-fluid" 
                                        src="{{ isset($ruangan) && $ruangan->foto ? asset('storage/'.$ruangan->foto) : '' }}"
                                        style="max-height: 200px; width: auto; border-radius: 8px;">
                                </div>
                                <input type="file" 
                                    class="d-none" 
                                    id="photoInput" 
                                    name="foto" 
                                    accept="image/*">
                            </div>
                            @error('foto')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Fasilitas Ruangan Section -->
                    <div class="col-md-6">
                        <div class="form-section h-100">
                            <h5 class="section-title">Fasilitas Ruangan</h5>
                            <div class="facility-input-container">
                                <div class="facility-input-group">
                                    <select class="form-select" id="facilityInput">
                                        <option value="" selected disabled>Pilih Fasilitas</option>
                                        @foreach($fasilitas as $f)
                                            <option value="{{ $f->id_fasililtas }}" 
                                                    data-nama="{{ $f->nama_fasilitas }}">
                                                {{ $f->nama_fasilitas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" 
                                           class="form-control" 
                                           id="facilityQuantity" 
                                           placeholder="Jumlah" 
                                           min="1">
                                    <button class="btn btn-add" type="button" id="addFacility">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div id="facilityError" class="facility-error-container"></div>
                            </div>
                            
                            <div id="facilityList" class="facility-list mt-3">
                                @if(isset($ruangan))
                                    @foreach($ruangan->fasilitas as $f)
                                        <div class="facility-item">
                                            <div class="facility-info">
                                                <strong>{{ $f->nama_fasilitas }}</strong>
                                                <div class="text-muted">Jumlah: {{ $f->pivot->jumlah_fasilitas }}</div>
                                            </div>
                                            <input type="hidden" name="fasilitas[{{ $f->id_fasililtas }}]" value="{{ $f->pivot->jumlah_fasilitas }}">
                                            <div class="facility-controls">
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-facility">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-facility">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('ruangan.index') }}" class="btn btn-cancel">Cancel</a>
                    <button type="submit" class="btn btn-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script untuk upload foto dan preview -->
    <script>
    document.getElementById('uploadArea').addEventListener('click', function(e) {
        if (e.target.id !== 'removeImage' && !e.target.closest('#removeImage')) {
            document.getElementById('photoInput').click();
        }
    });

    document.getElementById('photoInput').addEventListener('change', handleFileSelect);

    // Tambahkan event listener untuk tombol close
    document.getElementById('removeImage')?.addEventListener('click', function(e) {
        e.stopPropagation(); // Mencegah event click uploadArea
        removeImage();
    });

    function handleFileSelect(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const uploadPrompt = document.getElementById('uploadPrompt');
                const imageContainer = document.getElementById('imageContainer');
                const imagePreview = document.getElementById('imagePreview');
                
                uploadPrompt.classList.add('d-none');
                imageContainer.classList.remove('d-none');
                imagePreview.src = e.target.result;
                document.getElementById('removeFoto').value = '0';
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        const uploadPrompt = document.getElementById('uploadPrompt');
        const imageContainer = document.getElementById('imageContainer');
        const imagePreview = document.getElementById('imagePreview');
        const photoInput = document.getElementById('photoInput');
        
        uploadPrompt.classList.remove('d-none');
        imageContainer.classList.add('d-none');
        imagePreview.src = '';
        photoInput.value = '';
        document.getElementById('removeFoto').value = '1';
    }

    // Update script drag and drop
    uploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const file = dt.files[0];
        
        if (file && file.type.startsWith('image/')) {
            document.getElementById('photoInput').files = dt.files;
            const reader = new FileReader();
            reader.onload = function(e) {
                const uploadPrompt = document.getElementById('uploadPrompt');
                const imageContainer = document.getElementById('imageContainer');
                const imagePreview = document.getElementById('imagePreview');
                
                uploadPrompt.classList.add('d-none');
                imageContainer.classList.remove('d-none');
                imagePreview.src = e.target.result;
                document.getElementById('removeFoto').value = '0';
            }
            reader.readAsDataURL(file);
        }
    }

    // Script untuk mengelola fasilitas
    document.addEventListener('DOMContentLoaded', function() {
        const facilityInput = document.getElementById('facilityInput');
        const quantityInput = document.getElementById('facilityQuantity');
        const addButton = document.getElementById('addFacility');
        const facilityList = document.getElementById('facilityList');
        
        // Simpan semua opsi original untuk digunakan nanti
        const originalOptions = Array.from(facilityInput.options);
        
        // Fungsi untuk memperbarui dropdown options
        function updateDropdownOptions() {
            const usedFacilities = Array.from(document.querySelectorAll('input[name^="fasilitas["]'))
                .map(input => input.name.match(/\[(\d+)\]/)[1]);

            facilityInput.innerHTML = ''; // Kosongkan dropdown

            originalOptions.forEach(option => {
                const optionClone = option.cloneNode(true);
                if (option.value === '' || !usedFacilities.includes(option.value)) {
                    facilityInput.appendChild(optionClone);
                }
            });
        }

        // Fungsi untuk mengecek duplikasi
        function isFacilityDuplicate(facilityId) {
            // Tambahkan logging untuk debug
            console.log('Checking duplicate for facility ID:', facilityId);
            console.log('Existing facilities:', document.querySelectorAll('input[name^="fasilitas["]'));
            
            const selector = `input[name="fasilitas[${facilityId}]"]`;
            console.log('Looking for:', selector);
            
            const existingFacility = document.querySelector(selector);
            console.log('Found:', existingFacility);
            
            return existingFacility !== null;
        }
        
        // Fungsi untuk menampilkan error
        function showError(message) {
            const errorContainer = document.getElementById('facilityError');
            
            // Hapus error yang ada dengan animasi
            const existingError = errorContainer.querySelector('.facility-error');
            if (existingError) {
                existingError.classList.add('removing');
                setTimeout(() => {
                    existingError.remove();
                }, 300);
            }
            
            // Buat element error baru
            const errorDiv = document.createElement('div');
            errorDiv.className = 'facility-error';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>${message}`;
            
            // Tunggu animasi penghapusan selesai sebelum menambahkan pesan baru
            setTimeout(() => {
                errorContainer.appendChild(errorDiv);
                
                // Hapus pesan error setelah 3 detik
                setTimeout(() => {
                    errorDiv.classList.add('removing');
                    setTimeout(() => {
                        errorDiv.remove();
                    }, 300);
                }, 3000);
            }, existingError ? 300 : 0);
        }
        
        // Event listener untuk tombol tambah
        addButton.addEventListener('click', function() {
            const selectedOption = facilityInput.options[facilityInput.selectedIndex];
            
            // Validasi pilihan fasilitas
            if (facilityInput.selectedIndex === 0) {
                showError('Silakan pilih fasilitas terlebih dahulu');
                return;
            }

            // Validasi jumlah
            const quantity = parseInt(quantityInput.value);
            if (!quantity || quantity < 1) {
                showError('Jumlah fasilitas minimal 1');
                return;
            }

            const facilityId = selectedOption.value;
            const facilityName = selectedOption.text;

            // Debug
            console.log('Adding facility:', {
                id: facilityId,
                name: facilityName,
                quantity: quantity
            });
            
            // Cek duplikasi dengan logging
            const isDuplicate = isFacilityDuplicate(facilityId);
            console.log('Is duplicate:', isDuplicate);
            
            if (isDuplicate) {
                showError('Fasilitas ini sudah ditambahkan');
                return;
            }

            // Buat dan tambahkan item fasilitas
            const facilityItem = document.createElement('div');
            facilityItem.className = 'facility-item';
            facilityItem.innerHTML = `
                <div class="facility-info">
                    <strong>${facilityName}</strong>
                    <div class="text-muted">Jumlah: ${quantity}</div>
                </div>
                <input type="hidden" name="fasilitas[${facilityId}]" value="${quantity}">
                <div class="facility-controls">
                    <button type="button" class="btn btn-sm btn-outline-primary edit-facility">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-facility">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

            facilityList.appendChild(facilityItem);
            
            // Update dropdown options
            updateDropdownOptions();
            
            // Reset input
            facilityInput.selectedIndex = 0;
            quantityInput.value = '';
        });
        
        // Event delegation untuk tombol edit dan hapus
        facilityList.addEventListener('click', function(e) {
            const button = e.target.closest('button');
            if (!button) return;
            
            const facilityItem = button.closest('.facility-item');
            
            if (button.classList.contains('remove-facility')) {
                facilityItem.remove();
                updateDropdownOptions(); // Perbarui dropdown setelah fasilitas dihapus
            }
            
            if (button.classList.contains('edit-facility')) {
                const facilityId = facilityItem.querySelector('input[type="hidden"]').name.match(/\[(\d+)\]/)[1];
                const quantity = facilityItem.querySelector('input[type="hidden"]').value;
                
                facilityItem.remove();
                updateDropdownOptions(); // Perbarui dropdown setelah fasilitas dihapus
                
                facilityInput.value = facilityId;
                quantityInput.value = quantity;
            }
        });

        
        // Event listener untuk menghapus class invalid saat input berubah
        facilityInput.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });

        quantityInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });

        // Jalankan updateDropdownOptions saat halaman dimuat
        updateDropdownOptions();
    });
    </script>
@stop