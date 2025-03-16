@extends('adminlte::page')

@section('title', 'Pengaturan Nilai Akhir')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => '', 'label' => 'Pengaturan Nilai Akhir']
            ]
        ])
        @endcomponent
        <h1 class="mb-0">Pengaturan Nilai Akhir</h1>
    </div>
@stop

@section('content')
    <div class="p-4">
        {{-- Form untuk Simpan Data --}}
        <form id="nilaiAkhirForm" action="{{ route('pengaturan-bobot.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="table-container">
                <table id="nilaiAkhirTable" class="table text-center">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white">
                            <th>No</th>
                            <th>Komponen Nilai Akhir</th>
                            <th>Bobot (%)</th>
                            <th>Sumber Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $row)
                            <tr class="bg-light">
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">{{ $row['komponen'] }}</td>
                                <td class="align-middle">
                                    <input type="number" name="bobot[{{ $row['komponen'] }}]" class="form-control bobot-input"value="{{ $row['bobot'] }}" min="0" max="100" required>
                                </td>
                                <td class="align-middle">
                                    <select name="sumber_nilai[{{ $row['komponen'] }}]" class="form-control sumber-nilai">
                                        <option value="Seminar 2" {{ $row['sumber_nilai'] == 'Seminar 2' ? 'selected' : '' }}>Seminar 2</option>
                                        <option value="Seminar 3" {{ $row['sumber_nilai'] == 'Seminar 3' ? 'selected' : '' }}>Seminar 3</option>
                                        <option value="Sidang Akhir" {{ $row['sumber_nilai'] == 'Sidang Akhir' ? 'selected' : '' }}>Sidang Akhir</option>
                                        <option value="Dosen Pembimbing" {{ $row['sumber_nilai'] == 'Dosen Pembimbing' ? 'selected' : '' }}>Dosen Pembimbing</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pesan Error --}}
            <div id="error-messages" class="alert alert-danger d-none mt-3">
                <ul id="error-list" class="mb-0"></ul>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-danger me-2" onclick="window.history.back();">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" id="submit-button" class="btn btn-success ml-2">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
        @if(session('error'))
            <div id="backend-error" class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/rekapitulasi_nilai.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function () {
            function validateBobot() {
                let totalBobot = 0;
                let allValid = true;

                $('.bobot-input').each(function () {
                    let val = $(this).val().trim();
                    if (val === '' || isNaN(val) || val < 0 || val > 100) {
                        allValid = false;
                    }
                    totalBobot += parseFloat(val) || 0;
                });

                if (!allValid) return 'Bobot harus angka antara 0 - 100.';
                if (totalBobot !== 100) return 'Total bobot harus 100%. Saat ini: ' + totalBobot + '%';
                return null;
            }

            function validateSumberNilai() {
                let sumberSet = new Set();
                let isValid = true;

                $('.sumber-nilai').each(function () {
                    let val = $(this).val();
                    if (sumberSet.has(val)) {
                        isValid = false;
                    } else {
                        sumberSet.add(val);
                    }
                });

                if (!isValid) {
                    return 'Setiap sumber nilai harus unik. Tidak boleh ada duplikat.';
                }
                return null;
            }

            function validateForm() {
                let errors = [];
                let errorBobot = validateBobot();
                let errorSumber = validateSumberNilai();

                if (errorBobot) errors.push(errorBobot);
                if (errorSumber) errors.push(errorSumber);

                if (errors.length > 0) {
                    let errorListHtml = errors.map(error => `<li>${error}</li>`).join('');
                    $('#error-list').html(errorListHtml);
                    $('#error-messages').removeClass('d-none');
                    $('#submit-button').prop('disabled', true);
                    return false;
                } else {
                    $('#error-messages').addClass('d-none');
                    $('#submit-button').prop('disabled', false);
                    return true;
                }
            }

            // Validasi saat form disubmit
            $('#nilaiAkhirForm').submit(function (e) {
                if (!validateForm()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal menyimpan!',
                        html: $('#error-list').html(),
                    });
                }
            });

            // Live validation
            $('.bobot-input, .sumber-nilai').on('input change', function () {
                let isValid = validateForm();
                if (isValid) {
                    $('#error-messages').addClass('d-none');
                    $('#submit-button').prop('disabled', false);
                }
            });

            // Tampilkan error dari backend jika ada
            if ($('#backend-error').length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menyimpan!',
                    text: $('#backend-error').text(),
                });
            }
        });
    </script>

@stop
