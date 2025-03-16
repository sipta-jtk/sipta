@extends('adminlte::page')

@section('title', 'Monitoring Mahasiswa')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
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
    

    <div class="card-Progress-Penilaian">
        <h4>Progress Penilaian oleh Dosen</h4>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-dark text-white text-center">
                    <th>Nama Kategori</th>
                    <th>Rubrik</th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="vertical-align: middle; white-space: nowrap;">Seminar 1</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary btn-sm px-3">Lihat Rubrik</button>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary btn-sm px-3 disabled">Lihat Detail</button>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: middle; white-space: nowrap;">Seminar 2</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary btn-sm px-3">Lihat Rubrik</button>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary btn-sm px-3">Lihat Detail</button>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: middle; white-space: nowrap;">Seminar 3</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary btn-sm px-3">Lihat Rubrik</button>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary btn-sm px-3 disabled">Lihat Detail</button>
                    </td>
                </tr>                
            </tbody>            
        </table>
    </div>    
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/monitoring_mahasiswa.js') }}"></script>
@stop


