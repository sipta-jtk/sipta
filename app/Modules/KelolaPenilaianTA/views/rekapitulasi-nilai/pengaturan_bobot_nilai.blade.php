@extends('adminlte::page')

@section('title', 'Pengaturan Bobot Nilai Akhir')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-2">Pengaturan Bobot Nilai Akhir</h1>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Pengaturan Nilai Akhir']
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body p-3">
                {{-- Filter Toggle Button --}}
                <div class="d-flex justify-content-between mb-3">
                    <div id="dataTableControls"></div>
                    <div id="searchBox"></div>          
                </div>

                {{-- Form untuk Simpan Data --}}
                <form id="nilaiAkhirForm" action="{{ route('pengaturan-bobot.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="table-container mb-3">
                        <table id="nilaiAkhirTable" class="table table-striped table-bordered" width="100%">
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div id="infoControls"></div>
                        <div id="paginationControls"></div>
                    </div>

                    <!-- Pesan Warning -->
                    <div id="warning-message" class="alert alert-warning d-none mb-3">
                        <ul id="warning-list" class="mb-0"></ul>
                    </div>

                    {{-- Pesan Error --}}
                    <div id="error-messages" class="alert alert-danger d-none mb-3">
                        <ul id="error-list" class="mb-0"></ul>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-danger me-2" onclick="window.history.back();">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="button" id="edit-button" class="btn btn-warning ml-2">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="submit" id="submit-button" class="btn btn-success ml-2">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
                @if(session('error'))
                    <div id="backend-error" class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
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