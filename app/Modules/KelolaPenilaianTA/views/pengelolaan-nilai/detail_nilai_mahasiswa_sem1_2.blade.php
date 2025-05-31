@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai')

@section('content_header')
@php
    $prefix = env('PREFIX_URL', 'sipta');
@endphp
<div class="container-fluid p-3">
    <!-- Judul Halaman -->
    <h1 class="mb-0">Detail Nilai {{ $detailInformasiFta->nama_fta }} <br /> {{ $detailInformasiFta->prodi->nama_prodi }}</h1>

    {{-- TBD perbaiki breadcrumb --}}
    @component('KelolaPenilaianTA.views.components.breadcrumb', [
    'links' => [
    ['url' => "/$prefix", 'label' => 'Beranda'],
    ['url' => route('kelola.penilaian'), 'label' => 'Kelola Nilai'],
    ['url' => '', 'label' => 'Data' ]
    ]
    ])
    @endcomponent

</div>
@stop

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-end mb-3" style="gap: 0.5rem;">
        <button class="btn btn-primary" data-toggle="modal" data-target="#uploadExcel">
            <i class="fa fa-upload"></i> Unggah Excel
        </button>
        <a href="{{ route('download.template-nilai') }}" class="btn btn-primary">
            <i class="fa fa-download"></i> Unduh Template Excel
        </a>
    </div>
    {{-- Tabel Scrollable --}}
    <div>
        <table id="alokasiTable" class="table text-center table-striped table-hover">
            <thead class="sticky-header">
                <tr class="bg-dark text-white">
                    <th rowspan="2" class="align-middle" style="width: 3%;">No</th>
                    <th rowspan="2" class="align-middle" style="width: 3%;">NIM</th>
                    <th rowspan="2" class="align-middle" style="width: 20%;">Nama</th>
                    <th rowspan="2" class="align-middle" style="width: 4%;">Kelompok</th>
                    <th rowspan="2" class="align-middle">Penguji 1</th>
                    <th rowspan="2" class="align-middle">Penguji 2</th>
                    <th rowspan="2" class="align-middle">Penguji 3</th>
                    <th colspan="3" style="width: 10%;">Nilai</th>
                </tr>
                <tr class="bg-secondary text-white">
                    <th style="width: 2%;">P1</th>
                    <th style="width: 2%;">P2</th>
                    <th style="width: 2%;">P3</th>
                </tr>
            </thead>
            <tbody>
                @php
                $no = 1;
                $grouped = $detailNilaiMahasiswa->groupBy('id_kota');
                @endphp

                @foreach ($grouped as $idKota => $mahasiswaKelompok)
                @foreach ($mahasiswaKelompok as $mahasiswa)
                <tr>
                    <td class="align-middle text-center">{{ $no++ }}</td>
                    <td class="align-middle text-center">{{ $mahasiswa->nim }}</td>
                    <td class="align-middle text-start">{{ $mahasiswa->user->nama }}</td>
                    <td class="align-middle text-center">{{ $mahasiswa->kota->nama_kota }}</td>

                    {{-- Penguji --}}
                    @php
                    // Ambil daftar id_dosen dari nilai_kategori
                    $pengujiList = $mahasiswa->nilaiKategori->pluck('dosen.id_dosen')->toArray() ?? [];
                    // Pastikan selalu ada 3 elemen, isi dengan '-' jika kurang
                    $pengujiList = array_pad($pengujiList, 3, '-');
                    @endphp

                    <td class="align-middle text-center">{{ $pengujiList[0] }}</td>
                    <td class="align-middle text-center">{{ $pengujiList[1] }}</td>
                    <td class="align-middle text-center">{{ $pengujiList[2] }}</td>

                    {{-- Nilai --}}
                    @php
                    // Ambil daftar nilai dari nilai_kategori
                    $nilaiList = $mahasiswa->nilaiKategori->pluck('nilai')->toArray() ?? [];
                    // Pastikan selalu ada 3 elemen, isi dengan '-' jika kurang
                    $nilaiList = array_pad($nilaiList, 3, 0);
                    @endphp

                    <td class="align-middle text-center">{{ $nilaiList[0] }}</td>
                    <td class="align-middle text-center">{{ $nilaiList[1] }}</td>
                    <td class="align-middle text-center">{{ $nilaiList[2] }}</td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between mt-1">
        <div id="infoControls"></div> <!-- Placeholder untuk info -->
        <div id="paginationControls"></div> <!-- Placeholder untuk pagination -->
    </div>

    <x-adminlte-modal id="uploadExcel" title="Input Nilai Melalui Excel" theme="blue" size='lg'>
        <form action="{{route('import-nilai', ['namaFta' => Str::slug($detailInformasiFta->nama_fta), 'idProdi' => $detailInformasiFta->prodi->id_prodi])}}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-adminlte-input-file name="file" label="Upload Excel" placeholder="Pilih file excel ..." fgroup-class="" disable-feedback required accept=".xls,.xlsx,.csv" />
            <div class="d-flex pt-3 justify-content-end">
                <x-adminlte-button theme="danger" label="Tutup"
                    data-dismiss="modal" class="mx-1" />
                <x-adminlte-button theme="success" label="Pratinjau"
                    class="mx-1" type="submit" />
            </div>
            <x-slot name="footerSlot"></x-slot>
        </form>
    </x-adminlte-modal>
</div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/kelola_penilaian_ta.css') }}" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('KelolaPenilaianTA/js/kelola_penilaian_ta.js') }}"></script>
@stop