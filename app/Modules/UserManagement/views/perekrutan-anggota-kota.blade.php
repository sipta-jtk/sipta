@extends('adminlte::page')

@section('title', 'Rekrut Anggota KoTA')

@section('content_header')
    <h1>Rekrut Anggota Kelompok TA</h1>
@stop
<!-- up -->
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Rekrut Anggota</h3>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('perekrutan-anggota-kota.submit') }}" method="POST" id="form-perekrutan">
            @csrf
            <div class="row">
                <x-adminlte-input name="anggota1" label="Anggota 1 (Akun Anda)" 
                    value="{{ $mahasiswaAnggota1->nama }} - {{ $mahasiswaAnggota1->nim }}"
                    fgroup-class="col-md-6" readonly/>
                <input type="hidden" name="anggota1" value="{{ $mahasiswaAnggota1->nim }}">
            </div>

            <div class="mb-4">
                <div id="anggota-container"></div>
                <x-adminlte-button id="add-member" label="Tambah Anggota" theme="success" icon="fas fa-plus"/>
            
                <!-- Menampilkan info maksimal anggota -->
                <div class="mt-2">
                    <small class="text-muted">
                        *Maksimal anggota untuk program studi Anda adalah {{ $maksimalAnggota }} orang
                    </small>
                </div>
            </div>
             
            <div class="d-flex justify-content-end">
                <x-adminlte-button label="Simpan" theme="primary" type="submit"/>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () 
    {
        let anggotaCount = 1;
        const anggotaContainer = document.getElementById('anggota-container');
        
        // Ambil maksimal anggota dari server
        const maksimalAnggota = {{ $maksimalAnggota }};
        
        // Buat array untuk menyimpan semua data mahasiswa
        const allMahasiswa = [
            @foreach ($mahasiswa as $mhs)
                { nim: "{{ $mhs->nim }}", nama: "{{ $mhs->nama }}" },
            @endforeach
        ];
        
        document.getElementById('add-member').addEventListener('click', function () 
        {
            // Ubah kondisi untuk memeriksa jumlah maksimal anggota
            if (anggotaCount < maksimalAnggota)
            {
                anggotaCount++;
                let anggotaDiv = document.createElement('div');
                anggotaDiv.classList.add('row', 'anggota-group', 'mt-2', 'mb-2');
                anggotaDiv.setAttribute('id', 'anggota' + anggotaCount);
                
                anggotaDiv.innerHTML = `
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="anggota${anggotaCount}">Anggota ${anggotaCount}</label>
                            <select name="anggota${anggotaCount}" id="anggota${anggotaCount}" class="form-control">
                                <option value="">Pilih anggota</option>
                                ${generateMahasiswaOptions()}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger remove-member" data-id="anggota${anggotaCount}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                `;
                
                anggotaContainer.appendChild(anggotaDiv);
                attachChangeListener(document.getElementById('anggota' + anggotaCount));
                updateAnggotaNames();
                
                // Sembunyikan tombol "Tambah Anggota" jika sudah mencapai batas maksimal
                if (anggotaCount >= maksimalAnggota) {
                    document.getElementById('add-member').style.display = 'none';
                }
            }
        });
        
        anggotaContainer.addEventListener('click', function (event) 
        {
            if (event.target.classList.contains('remove-member') || event.target.closest('.remove-member')) 
            {
                let parentDiv = event.target.closest('.anggota-group');
                parentDiv.remove();
                anggotaCount--;
                updateAnggotaNames();
                updateAllDropdowns();
                
                // Tampilkan lagi tombol "Tambah Anggota" jika belum mencapai batas maksimal
                if (anggotaCount < maksimalAnggota) {
                    document.getElementById('add-member').style.display = 'inline-flex';
                }
            }
        });

        // Fungsi untuk mendapatkan semua nilai yang sudah dipilih
        function getSelectedValues() {
            const values = ['{{ $mahasiswaAnggota1->nim }}'];
            const selects = document.querySelectorAll('#anggota-container select');
            selects.forEach(select => {
                if (select.value) {
                    values.push(select.value);
                }
            });
            return values;
        }
        
        // Fungsi untuk menghasilkan opsi dropdown
        function generateMahasiswaOptions() {
            const selectedValues = getSelectedValues();
            
            return allMahasiswa
                .filter(mhs => !selectedValues.includes(mhs.nim))
                .map(mhs => `<option value="${mhs.nim}">${mhs.nama} - ${mhs.nim}</option>`)
                .join('');
        }

        // Fungsi untuk menambahkan event listener pada dropdown
        function attachChangeListener(selectElement) {
            if (selectElement) {
                selectElement.addEventListener('change', updateAllDropdowns);
            }
        }

        // Update semua dropdown berdasarkan nilai yang sudah dipilih
        function updateAllDropdowns() {
            const selects = document.querySelectorAll('#anggota-container select');
            const selectedValues = getSelectedValues();
            
            selects.forEach(select => {
                const currentValue = select.value;
                
                // Hapus semua opsi kecuali "Pilih anggota"
                while (select.options.length > 1) {
                    select.remove(1);
                }
                
                // Tambahkan opsi untuk mahasiswa yang belum dipilih atau merupakan nilai saat ini
                for (const mhs of allMahasiswa) {
                    if (!selectedValues.includes(mhs.nim) || mhs.nim === currentValue) {
                        const option = new Option(`${mhs.nama} - ${mhs.nim}`, mhs.nim);
                        select.add(option);
                        
                        // Set selected jika ini nilai saat ini
                        if (mhs.nim === currentValue) {
                            option.selected = true;
                        }
                    }
                }
            });
        }

        function updateAnggotaNames()
        {
            let anggotaGroups = anggotaContainer.getElementsByClassName('anggota-group');
            for (let i = 0; i < anggotaGroups.length; i++)
            {
                let selectElement = anggotaGroups[i].querySelector('select');
                let labelElement = anggotaGroups[i].querySelector('label');
                
                let newIndex = i + 2;

                selectElement.setAttribute('name', 'anggota' + newIndex);
                selectElement.setAttribute('id', 'anggota' + newIndex);
                labelElement.textContent = 'Anggota ' + newIndex;
                labelElement.setAttribute('for', 'anggota' + newIndex);
            }
            
            // Update dropdowns setelah nama diperbarui
            updateAllDropdowns();
        }

        //  Event submit form
        document.getElementById('form-perekrutan').addEventListener('submit', function (event) 
        {
            event.preventDefault();

            let anggotaElements = anggotaContainer.querySelectorAll('select');
            let selectedValues = new Set();
            let totalAnggota = 1; // Mulai dengan 1 untuk anggota 1 (akun Anda)
            let isValid = true;
            let errorMessage = '';

            // Hapus pesan error sebelumnya
            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            // Cek apakah Anggota 1 sudah masuk kelompok TA
            let isAnggota1HasKelompokTA = '{{ $mahasiswaAnggota1->id_kota }}'.trim();

            if (isAnggota1HasKelompokTA)
            {
                isValid = false;
                errorMessage = 'Anda sudah tergabung dalam kelompok TA dan tidak bisa mengajukan form lagi.';

                Swal.fire({
                    title: "Peringatan!",
                    text: errorMessage,
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return;
            }

            selectedValues.add('{{ $mahasiswaAnggota1->nim }}');

            for (let select of anggotaElements) 
            {
                let value = select.value.trim();
                if (value !== "") 
                {
                    if (selectedValues.has(value)) 
                    {
                        isValid = false;
                        select.classList.add('is-invalid');

                        let errorDiv = document.createElement('div');
                        errorDiv.classList.add('invalid-feedback');
                        errorDiv.textContent = 'Anggota tidak boleh duplikat';
                        select.parentNode.appendChild(errorDiv);

                        errorMessage = 'Anggota tidak boleh duplikat';
                    } else 
                    {
                        selectedValues.add(value);
                        totalAnggota++;
                    }
                }
            }

            // Ubah validasi untuk memeriksa maksimal anggota
            if (totalAnggota < 1 || totalAnggota > maksimalAnggota)
            {
                isValid = false;
                errorMessage = `Jumlah anggota harus minimal 1 dan maksimal ${maksimalAnggota}.`;

                // Tampilkan pesan error
                if (anggotaElements.length > 0) {
                    let lastElement = anggotaElements[anggotaElements.length - 1];
                    lastElement.classList.add('is-invalid');
                    let errorDiv = document.createElement('div');
                    errorDiv.classList.add('invalid-feedback');
                    errorDiv.textContent = errorMessage;
                    lastElement.parentNode.appendChild(errorDiv);
                }
            }

            if (!isValid) 
            {
                Swal.fire({
                    title: "Kesalahan!",
                    text: errorMessage,
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }

            // Konfirmasi sebelum menyimpan
            Swal.fire({
                title: "Konfirmasi Perekrutan",
                text: "Apakah Anda yakin ingin mengajukan kelompok TA ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Simpan!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        });
    });
</script>
@stop
