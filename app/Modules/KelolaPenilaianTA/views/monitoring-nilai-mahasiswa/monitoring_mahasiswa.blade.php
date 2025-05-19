@extends('adminlte::page')

@section('title', 'Monitoring Mahasiswa')

@section('content_header')
    <h1 class="mb-3">Informasi Penilaian Mahasiswa</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Informasi Penilaian Mahasiswa']
            ]
        ])c
        @endcomponent
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header text-center">
            <h3 class="card-title w-100">Informasi KoTA</h3>
        </div>
        <div class="card-body px-3 py-3">
            <table class="table table-bordered w-50">
                <tbody>
                    <tr>
                        <th class="bg-dark text-white w-25 text-center p-2">Kode KoTA</th>
                        <td class="w-75 p-2">{{ $kotaInfo->nama_kota }}</td>
                    </tr>
                    <tr>
                        <th class="bg-dark text-white w-25 text-center p-2">Judul Topik</th>
                        <td class="w-75 p-2">{{ $kotaInfo->judul_ta }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-center">
            <h3 class="card-title w-100">Data Mahasiswa</h3>
        </div>
        <div class="card-body px-3 py-3">
            <table class="table table-striped table-bordered w-50">
                <thead>
                    <tr class="bg-dark text-white" style="text-align: center;">
                        <th class="bg-dark align-middle text-center w-25">NIM</th>
                        <th class="bg-dark align-middle text-center w-75">Nama</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mahasiswaList as $mahasiswa)
                        <tr>
                            <td>{{ $mahasiswa->nim }}</td>
                            <td>{{ $mahasiswa->user->nama }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-center">
            <h3 class="card-title w-100">Data Dosen Pembimbing</h3>
        </div>
        <div class="card-body px-3 py-3">
            <table class="table table-striped table-bordered w-50">
                <thead>
                    <tr class="bg-dark text-white" style="text-align: center;">
                        <th class="bg-dark align-middle text-center w-25">NIP</th>
                        <th class="bg-dark align-middle text-center w-75">Nama</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dosenPembimbing as $dosen)
                        <tr>
                            <td class="text-center">{{ $dosen->nip }}</td>
                            <td>{{ $dosen->nama_dosen }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header text-center">
            <h3 class="card-title w-100">Daftar Evaluasi</h3>
        </div>
        <div class="card-body px-3 py-3">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="bg-dark">Nama FTA</th>
                        <th class="bg-dark text-center">Rubrik Penilaian</th>
                        <th class="bg-dark text-center">Masukkan Dosen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ftaList as $ftaNama)
                        @php
                            $ftaPenilaian = $ftaPenilaianList[$ftaNama] ?? null;
                            $ftaFeedback = $ftaFeedbackList[$ftaNama] ?? null;
                            $isFeedbackAvailable = $ftaFeedback && in_array($ftaFeedback->id_fta, $ftaWithFeedback);
                        @endphp
                        <tr>
                            <td style="vertical-align: middle; white-space: nowrap;">{{ $ftaNama }}</td>
                            <td class="text-center">
                                @if ($ftaPenilaian)
                                    <a href="{{ route('monitoring.rubrik.mahasiswa', ['kodeFta' => $ftaPenilaian->id_fta, 'idProdi' => $idProdi]) }}" 
                                        class="btn btn-primary btn-md my-1 w-100 px-3">
                                        Lihat Rubrik
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($ftaFeedback)
                                    <div class="tooltip-wrapper position-relative">
                                        <a href="{{ $isFeedbackAvailable ? route('monitoring.feedback', ['id_fta' => $ftaFeedback->id_fta, 'id_kota' => $kotaInfo->id_kota]) : '#' }}"
                                            class="btn btn-primary btn-md my-1 w-100 {{ $isFeedbackAvailable ? '' : 'disabled-link' }}">
                                            Lihat Masukkan
                                        </a>
                                        @if (!$isFeedbackAvailable)
                                            <div class="tooltip-box position-absolute bg-white border p-2 shadow rounded text-left">
                                                <strong>Masukkan belum tersedia!</strong>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>                                                                           
                        </tr>
                    @endforeach
                </tbody>                
            </table>
        </div>
    </div>      
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/monitoring_nilai_mahasiswa.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/monitoring_mahasiswa.js') }}"></script>
@stop


