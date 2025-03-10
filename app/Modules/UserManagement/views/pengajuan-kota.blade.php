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
                <x-adminlte-input name="anggota1" label="Anggota 1 (Akun Anda)" value="John Doe"
                    fgroup-class="col-md-6" readonly/>
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
                            <option value="User A">User A</option>
                            <option value="User B">User B</option>
                            <option value="User C">User C</option>
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

        document.getElementById('pengajuan-form').addEventListener('submit', function (event) 
        {
            let anggotaElements = anggotaContainer.querySelectorAll('select');
            let selectedValues = new Set();
            let totalAnggota = 1;

            for (let select of anggotaElements) 
            {
                let value = select.value.trim();
                if (value === "") 
                {
                    if (selectedValues.has(value)) 
                    {
                        event.preventDefault();
                        alert('Anggota tidak boleh duplikat.');
                        return;
                    }
                    selectedValues.add(value);
                    totalAnggota++;
                }
            }

            if (totalAnggota < 1 || totalAnggota > 3)
            {
                event.preventDefault();
                alert('Jumlah anggota harus minimal 1 dan maksimal 3.');
            }
        });
    });
</script>
@stop
