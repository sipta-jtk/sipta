@extends('adminlte::page')

@section('title', 'Pengaturan Nilai Akhir')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => route('beranda.get'), 'label' => 'Home'],
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
                <table id="nilaiAkhirTable" class="table table-striped table-bordered text-center" width="100%">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white text-center">
                            <th>No</th>
                            <th>Komponen Nilai Akhir</th>
                            <th>Bobot (%)</th>
                            <th>Sumber Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $row)
                            <tr>
                                <td class="align-middle"></td>
                                <td class="align-middle">{{ $row['komponen'] }}</td>
                                <td class="align-middle">
                                    <input type="number" name="bobot[{{ $row['komponen'] }}]" class="form-control bobot-input" value="{{ $row['bobot'] }}" min="0" max="100" required disabled>
                                </td>
                                <td class="align-middle">
                                    <select name="sumber_nilai[{{ $row['komponen'] }}]" class="form-control sumber-nilai" disabled>
                                        <option value=2 {{ $row['sumber_nilai'] == 2 ? 'selected' : '' }}>Seminar 2</option>
                                        <option value=3 {{ $row['sumber_nilai'] == 3 ? 'selected' : '' }}>Seminar 3</option>
                                        <option value=4 {{ $row['sumber_nilai'] == 4 ? 'selected' : '' }}>Sidang Akhir</option>
                                        <option value=5 {{ $row['sumber_nilai'] == 5 ? 'selected' : '' }}>Dosen Pembimbing</option>
                                    </select>
                                </td>
                                <td class="align-middle">
                                    <button type="button" class="btn btn-primary btn-edit"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pesan Warning -->
            <div id="warning-message" class="alert alert-warning d-none mt-3">
                <ul id="warning-list" class="mb-0"></ul>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/pengaturan_bobot.js') }}"></script>

@stop
