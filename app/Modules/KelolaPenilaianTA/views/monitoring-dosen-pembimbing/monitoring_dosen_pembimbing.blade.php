@extends('adminlte::page')

@section('title', 'Monitoring Dosen Pembimbing')

@section('content_header')
<div class="container-fluid p-2">
    <h1 class="mb-0">Monitoring Dosen Pembimbing</h1>

    @component('KelolaPenilaianTA.views.components.breadcrumb', [
        'links' => [
            ['url' => route('beranda.get'), 'label' => 'Beranda'],
            ['url' => '', 'label' => 'Monitoring Dosen Pembimbing']
        ]
    ])
    @endcomponent
</div>
@stop

@section('content')
@foreach ($formulirByProdi as $idProdi => $dataProdi)
<div class="card mb-4">
    <div class="p-2">
        <div class="card-header text-center">
            <h3 class="card-title w-100">Daftar Formulir Penilaian TA {{ $dataProdi['nama_prodi'] }}</h3>
        </div>

        <div class="table-container">
            <table id="table" class="table table-striped text-center" width="100%">
                <thead class="sticky-header bg-dark text-white">
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 12%;">Kode Formulir</th>
                        <th style="width: 19%;">Nama Formulir</th>
                        <th style="width: 19%;">Jenis TA</th>
                        <th style="width: 15%;">Tanggal Tenggat Pengisian</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataProdi['formulir'] as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->kode_fta }}</td>
                            <td>{{ $row->nama_fta }}</td>
                            <td class="align-middle">{{ $row->jenis_ta ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_tenggat_pengisian)->translatedFormat('d F Y') }}</td>
                            <td>
                                <a href="{{ route('monitoring.rubrik.dosen', ['kodeFta' => $row->id_fta, 'idProdi' => $row->id_prodi]) }}"
                                class="btn btn-primary btn-md m-1 w-100">
                                    Tinjau
                                    <i class="mx-1 my-1 fas fa-arrow-circle-right"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @if (count($dataProdi['formulir']) === 0)
                        <tr><td colspan="7">Tidak ada data formulir untuk prodi ini.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach

<div class="card mb-4">
    <div class="p-2">
        <div class="card-header text-center">
            <h3 class="card-title w-100">Daftar KoTA Bimbingan</h3>
        </div>

        <div class="table-container">
            <table class="table table-striped text-center">
                <thead class="sticky-header bg-dark text-white">
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 10%;">Nama KoTA</th>
                        <th style="width: 20%;">Judul Topik</th>
                        <th style="width: 15%;">Jenis TA</th>
                        <th style="width: 10%;">Seminar I</th>
                        <th style="width: 10%;">Seminar II</th>
                        <th style="width: 10%;">Seminar III</th>
                        <th style="width: 10%;">Sidang Akhir</th>
                        <th style="width: 10%;">Penilaian Pembimbing</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($kotaRows as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row['nama_kota'] }}</td>
                        <td>{{ $row['judul_ta'] }}</td>
                        <td>{{ $row['jenis_ta'] }}</td>

                        @foreach (['Seminar I', 'Seminar II', 'Seminar III', 'Sidang Akhir'] as $namaFta)
                            <td>
                                @php
                                    $fta = $row['fta'][$namaFta];
                                    // dump($fta);
                                @endphp
                                @if ($fta)
                                    <div class="tooltip-wrapper position-relative">
                                        <div class="position-relative d-inline-block">
                                            <a href="{{ $fta['available'] ? route('monitoring.feedback.dosen', [
                                                'id_fta' => $fta['id_fta'],
                                                'id_kota' => $fta['id_kota']
                                            ]) : '#' }}"
                                            class="{{ $fta['available'] ? 'btn btn-md btn-primary m-1' : 'btn btn-md btn-primary m-1 disabled-link' }}">
                                                <i class="fas fa-eye m-1"></i>
                                            </a>
                                            @if (!$fta['available'])
                                                <div class="tooltip-box">
                                                    <strong>Masukkan belum tersedia!</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endforeach
{{-- CODE PUSH PERTAMA
                        @foreach (['Seminar I', 'Seminar II', 'Seminar III', 'Sidang Akhir'] as $namaFta)
                            <td>
                                @php
                                    $fta = $row['fta'][$namaFta];
                                @endphp
                                @if ($fta)
                                    <div class="tooltip-wrapper position-relative">
                                        <a href="{{ $fta['available'] ? route('monitoring.feedback.dosen', [
                                            'id_fta' => $fta['id_fta'],
                                            'id_kota' => $fta['id_kota']
                                        ]) : '#' }}"
                                        class="btn btn-sm btn-primary {{ $fta['available'] ? '' : 'disabled-link' }}">
                                        Lihat
                                        </a>
                                        @if (!$fta['available'])
                                            <div class="tooltip-box position-absolute bg-white border p-2 shadow rounded text-left">
                                                <strong>Masukkan belum tersedia!</strong>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endforeach --}}

                        <td>
                            @php
                                $sidangAkhir = $row['fta']['Sidang Akhir'] ?? null;
                                $isSidangAkhirAvailable = $sidangAkhir && $sidangAkhir['available'];
                            @endphp

                            <a title="Ubah" 
                            class="btn btn-warning btn-md m-1 {{ $isSidangAkhirAvailable ? '' : 'disabled-link' }}"
                            href="{{ $isSidangAkhirAvailable ? route('pengisian.nilai.dosbing', ['namaFta' => 'dosen-pembimbing', $sidangAkhir['id_kota']]) : '#' }}">
                                <i class="fas fa-edit text-dark m-1"></i>
                            </a>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/formulir_penilaian.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/monitoring_nilai_mahasiswa.css') }}">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/formulir_penilaian.js') }}"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/monitoring_mahasiswa.js') }}"></script>
@stop

{{-- @extends('adminlte::page')

@section('title', 'Monitoring Dosen Pembimbing')

@section('content_header')
<div class="container-fluid p-2">
    <h1 class="mb-0">Monitoring Dosen Pembimbing</h1>

    @component('KelolaPenilaianTA.views.components.breadcrumb', [
        'links' => [
            ['url' => route('beranda.get'), 'label' => 'Beranda'],
            ['url' => '', 'label' => 'Formulir Penilaian']
        ]
    ])
    @endcomponent
</div>
@stop

@section('content')
@foreach ($formulirByProdi as $idProdi => $dataProdi)
<div class="card p-4 mb-4">
    <div class="p-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Daftar Formulir Penilaian TA {{ $dataProdi['nama_prodi'] }}</h3>
        </div>

        <div class="table-container">
            <table class="table table-striped text-center">
                <thead class="sticky-header bg-dark text-white">
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 12%;">Kode Formulir</th>
                        <th style="width: 19%;">Nama Formulir</th>
                        <th style="width: 19%;">Jenis TA</th>
                        <th style="width: 15%;">Tanggal Tenggat Pengisian</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataProdi['formulir'] as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->kode_fta }}</td>
                            <td>{{ $row->nama_fta }}</td>
                            <td class="align-middle">{{ $row->jenis_ta ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_tenggat_pengisian)->translatedFormat('d F Y') }}</td>
                            {{-- <td>
                                <button class="btn btn-info btn-md" title="Lihat Rubrik">
                                    Lihat <i class="fas fa-eye mx-1"></i>
                                </button>
                            </td> --}}
                            {{-- <td>
                                <a href="{{ route('monitoring.rubrik.dosen', ['kodeFta' => $row->id_fta, 'idProdi' => $row->id_prodi]) }}"
                                class="btn btn-primary btn-md">
                                    Lihat Rubrik
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @if (count($dataProdi['formulir']) === 0)
                        <tr><td colspan="7">Tidak ada data formulir untuk prodi ini.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach

<div class="card p-4 mb-4">
    <div class="p-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Daftar KoTA Bimbingan</h3>
        </div>

        <div class="table-container">
            <table class="table table-striped text-center">
                <thead class="sticky-header bg-dark text-white">
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 12%;">Nama KoTA</th>
                        <th style="width: 20%;">Judul Topik</th>
                        <th style="width: 15%;">Jenis TA</th>
                        <th style="width: 12%;">Seminar I</th>
                        <th style="width: 12%;">Seminar II</th>
                        <th style="width: 12%;">Seminar III</th>
                        <th style="width: 12%;">Sidang Akhir</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($kotaRows as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row['nama_kota'] }}</td>
                        <td>{{ $row['judul_ta'] }}</td>
                        <td>{{ $row['jenis_ta'] }}</td>

                        @foreach (['Seminar I', 'Seminar II', 'Seminar III', 'Sidang Akhir'] as $namaFta)
                            <td>
                                @php
                                    $fta = $row['fta'][$namaFta];
                                @endphp
                                @if ($fta)
                                    <div class="tooltip-wrapper position-relative">
                                        <a href="{{ $fta['available'] ? route('monitoring.feedback.dosen', [
                                            'id_fta' => $fta['id_fta'],
                                            'id_kota' => $fta['id_kota']
                                        ]) : '#' }}"
                                        class="btn btn-sm btn-primary {{ $fta['available'] ? '' : 'disabled-link' }}">
                                        Lihat
                                        </a>
                                        @if (!$fta['available'])
                                            <div class="tooltip-box position-absolute bg-white border p-2 shadow rounded text-left">
                                                <strong>Masukkan belum tersedia!</strong>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endforeach

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/formulir_penilaian.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/monitoring_nilai_mahasiswa.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/formulir_penilaian.js') }}"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/monitoring_mahasiswa.js') }}"></script>
@stop --}} --}}