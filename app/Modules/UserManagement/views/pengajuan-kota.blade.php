@extends('adminlte::page')

@section('title', 'Pengajuan KoTA')

@section('content_header')
    <h1>Pengajuan KoTA</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Pengajuan Kelompok KoTA</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('pengajuan-kota.submit') }}" method="POST" id="pengajuan-form">
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
        
        document.getElementById('add-member').addEventListener('click', function () 
        {
            if (anggotaCount < 3) 
            {
                anggotaCount++;
                let anggotaDiv = document.createElement('div');
                anggotaDiv.classList.add('row', 'anggota-group', 'mt-2', 'mb-2');
                anggotaDiv.setAttribute('id', 'anggota' + anggotaCount);
                
                anggotaDiv.innerHTML = `
                    <div class="col-md-5">
                        <x-adminlte-select name="anggota${anggotaCount}" label="Anggota ${anggotaCount}">
                            <option value="">Pilih anggota</option>
                            @foreach ($mahasiswa as $mhs)
                                <option value="{{ $mhs->nim }}">{{ $mhs->nama }} - {{ $mhs->nim }}</option>
                            @endforeach
                        </x-adminlte-select>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <x-adminlte-button label="Hapus" theme="danger" icon="fas fa-trash" class="remove-member" data-id="anggota${anggotaCount}"/>
                    </div>
                `;
                
                anggotaContainer.appendChild(anggotaDiv);
                updateAnggotaNames();
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
            }
        });

        function updateAnggotaNames()
        {
            let anggotaGroups = anggotaContainer.getElementsByClassName('anggota-group');
            for (let i = 0; i < anggotaGroups.length; i++)
            {
                let selectElement = anggotaGroups[i].querySelector('select');
                let labelElement = anggotaGroups[i].querySelector('label');
                
                let newIndex = i + 2;

                selectElement.setAttribute('name', 'anggota' + newIndex);
                labelElement.textContent = 'Anggota ' + newIndex;
            }
        }

        {{-- Event submit form untuk pop-up konfirmasi --}}
        document.getElementById('pengajuan-form').addEventListener('submit', function (event) 
        {
            event.preventDefault();

            let anggotaElements = anggotaContainer.querySelectorAll('select');
            let selectedValues = new Set();
            let totalAnggota = 1;
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

                // Tampilkan Peringatan
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

            if (totalAnggota < 1 || totalAnggota > 3)
            {
                isValid = false;
                errorMessage = 'Jumlah anggota harus minimal 1 dan maksimal 3.';

                // Tampilkan pesan error di bawah anggota terakhir
                if (anggotaElements.length > 0) 
                {
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
                title: "Konfirmasi Pengajuan",
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
