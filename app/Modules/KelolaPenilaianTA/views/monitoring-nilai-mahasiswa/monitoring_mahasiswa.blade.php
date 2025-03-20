@extends('adminlte::page')

@section('title', 'Monitoring Mahasiswa')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/'), 'label' => 'Home'],
                ['url' => '', 'label' => 'Informasi Penilaian Mahasiswa']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Informasi Penilaian Mahasiswa</h1>
    </div>
@stop

@section('content')
    <div class="card-KoTA">
        <h4>Informasi KoTA</h4>
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

    <div class="card-Mahasiswa">
        <h4>Data Mahasiswa</h4>
        <table class="table table-bordered w-50">
            <thead>
                <tr class="bg-dark text-white" style="text-align: center;">
                    <th class="align-middle text-center w-25">NIM</th>
                    <th class="align-middle text-center w-75">Nama</th>
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

    <div class="card-Dosen-Pembimbing">
        <h4>Data Dosen Pembimbing</h4>
        <table class="table table-bordered w-50">
            <thead>
                <tr class="bg-dark text-white" style="text-align: center;">
                    <th class="align-middle text-center w-25">NIP</th>
                    <th class="align-middle text-center w-75">Nama</th>
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
    
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h3 class="card-title">Daftar Evaluasi</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama FTA</th>
                        <th class="text-center">Rubrik Penilaian</th>
                        <th class="text-center">Feedback Dosen</th>
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
                                    <a href="{{ route('monitoring.rubrik', ['kodeFta' => $ftaPenilaian->id_fta, 'idProdi' => $idProdi]) }}" 
                                        class="btn btn-primary btn-sm px-3">
                                        Lihat Rubrik
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($ftaFeedback)
                                    <div class="tooltip-wrapper position-relative">
                                        <a href="{{ $isFeedbackAvailable ? route('monitoring.feedback', ['id_fta' => $ftaFeedback->id_fta, 'id_kota' => $kotaInfo->id_kota]) : '#' }}"
                                            class="btn btn-primary btn-sm px-3 {{ $isFeedbackAvailable ? '' : 'disabled-link' }}">
                                            Lihat Feedback
                                        </a>
                                        @if (!$isFeedbackAvailable)
                                            <div class="tooltip-box position-absolute bg-white border p-2 shadow rounded text-left">
                                                <strong>Feedback belum tersedia!</strong>
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
    
    <style>
        /* Nonaktifkan link jika feedback belum tersedia */
        .disabled-link {
            pointer-events: none;
            opacity: 0.5;
        }

        .tooltip-box {
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            position: absolute;
            width: 280px;
            padding: 10px;
            border-radius: 6px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 14px;
            line-height: 1.5;
            left: 50%;
            transform: translateX(-50%);
            top: 130%;
            z-index: 10;
            text-align: center;
        }
        .tooltip-wrapper:hover .tooltip-box {
            visibility: visible;
            opacity: 1;
        }

    </style>
    
      
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/monitoring_mahasiswa.js') }}"></script>
@stop


