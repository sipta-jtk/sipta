@extends('adminlte::page')

@section('title', 'Tambah Anggota KoTA')

@section('content_header')
    <h1 class="mb-3">Tambah Anggota Kelompok TA</h1>

    <div>
        @php
            $links = [
                ['url' => url(env('PREFIX_URL', 'sipta') . '/'), 'label' => 'Beranda'],
                ['url' => route('kota.saya'), 'label' => 'Detail KoTA'],
                ['url' => '', 'label' => 'Tambah Anggota']
            ];
        @endphp

        @component('KelolaPenilaianTA.views.components.breadcrumb', ['links' => $links])
        @endcomponent
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">{{ $kota->nama_kota }}</h3>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="alert alert-info">
            <p><strong>Informasi:</strong></p>
            <p>Saat ini Kelompok {{ $kota->nama_kota }} memiliki {{ count($anggotaExisting) }} anggota dari {{ $maksimalAnggota }} yang diperbolehkan.</p>
            <p>Anda dapat menambahkan maksimal {{ $slotTersedia }} anggota lagi.</p>
        </div>

        <form action="{{ route('tambah-anggota-kota.submit', ['id' => $kota->id_kota]) }}" method="POST" id="form-tambah-anggota">
            @csrf
            <div class="row mb-4">
                <div class="col-md-12">
                    <p class="text-secondary text-md border-bottom col-md-12">Anggota yang Sudah Ada</p>
                    @foreach($anggotaExisting as $key => $mhs)
                        <!-- <li class="list-group-item">{{ $mhs->nama }} ({{ $mhs->nim }})</li> -->
                        <x-adminlte-input name="anggota{{ $key + 1 }}" 
                            label="Anggota {{ $key + 1 }}" 
                            value="{{ $mhs->nama }} - {{ $mhs->nim }}"
                            fgroup-class="col-md-6" readonly/>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p class="text-secondary text-md border-bottom col-md-12">Tambah Anggota Baru</p>
                </div>
            </div>

            <div id="anggota-container">
                @for($i = 0; $i < $slotTersedia; $i++)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="anggota{{ count($anggotaExisting) + $i + 1 }}">
                                    Anggota {{ count($anggotaExisting) + $i + 1 }}
                                </label>
                                <select name="anggota{{ count($anggotaExisting) + $i + 1 }}" id="anggota{{ count($anggotaExisting) + $i + 1 }}" class="form-control anggota-select">
                                    <option value="">Pilih anggota</option>
                                    @foreach($mahasiswa as $mhs)
                                        <option value="{{ $mhs->nim }}">{{ $mhs->nama }} - {{ $mhs->nim }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('kota.saya') }}" class="btn btn-secondary mx-1">Batal</a>
                <button type="submit" class="btn btn-success mx-1">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Update dropdown options based on selected values
        const anggotaSelects = document.querySelectorAll('.anggota-select');
        
        anggotaSelects.forEach(select => {
            select.addEventListener('change', updateAvailableOptions);
        });
        
        function getSelectedValues() {
            const values = [];
            anggotaSelects.forEach(select => {
                if (select.value) {
                    values.push(select.value);
                }
            });
            return values;
        }
        
        function updateAvailableOptions() {
            const selectedValues = getSelectedValues();
            
            anggotaSelects.forEach(select => {
                const currentValue = select.value;
                const options = Array.from(select.options);
                
                // Skip the first option (placeholder)
                for (let i = 1; i < options.length; i++) {
                    const option = options[i];
                    
                    // If this option is selected in another dropdown and it's not the current dropdown's value, hide it
                    if (selectedValues.includes(option.value) && option.value !== currentValue) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                }
            });
        }
        
        // Handler submit Form
        document.getElementById('form-tambah-anggota').addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Cek setidaknya satu anggota yang dipilih
            let hasNewMember = false;
            anggotaSelects.forEach(select => {
                if (select.value) {
                    hasNewMember = true;
                }
            });
            
            if (!hasNewMember) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak ada anggota baru',
                    text: 'Silakan pilih minimal satu anggota baru untuk ditambahkan.'
                });
                return;
            }
            
            // Menampilkan konfirmasi
            Swal.fire({
                title: 'Konfirmasi Tambah Anggota',
                text: 'Apakah Anda yakin ingin menambahkan anggota baru ke kelompok ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tambahkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@stop