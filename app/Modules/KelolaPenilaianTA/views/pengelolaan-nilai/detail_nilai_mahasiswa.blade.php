@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => url('/kelola-penilaian-ta/pengelolaan-nilai'), 'label' => 'Kelola Nilai'],
                ['url' => '', 'label' =>  'Data' ]
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Detail Nilai {{ $namaKategori }}</h1>
    </div>
@stop

@section('content')
    <div class="p-4">
        {{-- Tabel Scrollable --}}
        <div class="table-container">
            <table id="alokasiTable" class="table text-center">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th rowspan="2" class="align-middle" style="width: 3%;">No</th>
                        <th rowspan="2" class="align-middle" style="width: 20%;">Nama</th>
                        <th rowspan="2" class="align-middle" style="width: 4%;">Kelompok</th>
                        <th rowspan="2" class="align-middle">Penguji 1</th>
                        <th rowspan="2" class="align-middle">Penguji 2</th>
                        <th rowspan="2" class="align-middle">Penguji 3</th>
                        <th colspan="3" style="width: 10%;">Nilai</th>
                        <th rowspan="2" class="align-middle">Rata-rata</th>
                        @if (strtolower($namaKategori) == 'seminar iii' || strtolower($namaKategori) == 'seminar ii')
                            <th rowspan="2" class="align-middle">Aksi</th>
                        @endif
                    </tr>
                    <tr class="bg-secondary text-white">
                        <th style="width: 2%;">P1</th>
                        <th style="width: 2%;">P2</th>
                        <th style="width: 2%;">P3</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filteredData as $data)
                        <tr>
                            <td> {{ $data['index']}} </td>
                            <td> {{ $data['nama'] }} </td>
                            <td> {{ $data['kelompok'] }} </td>
                            @foreach ($data['kode_dosen'] as $kode)
                                <td> {{ $kode }} </td>
                            @endforeach
                            @foreach ($data['nilai'] as $nilai)
                                <td> {{ $nilai }} </td>
                            @endforeach
                            <td> {{ $data['rata-rata'] }} </td>
                            @if (strtolower($namaKategori) == 'seminar iii' || strtolower($namaKategori) == 'seminar ii')
                                @if (count(array_filter($data['nilai'], fn($value) => $value !== null && $value !== 0)) == 3)
                                    <div>
                                    <td> 
                                        <a class="btn btn-danger">Nilai</a> 
                                        <a class="btn btn-danger">Feedback</a>
                                    </td> 
                                @else
                                    <td> 
                                        <a href="{{ url('sipta/kelola-penilaian-ta/nilai-seminar/' . $idFta . '/nilai/' . $data['kota']) }}" class="btn btn-primary">Nilai</a>
                                        <form action="{{ url('sipta/kelola-penilaian-ta/nilai-seminar/' . $idFta . '/feedback/' . $data['kota']) }}" method="POST" style="display:inline;" class="finalisasi-form">
                                            @csrf
                                            <button type="submit" class="btn btn-primary finalisasi-button">Finalisasi</button>
                                        </form>
                                    </td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/kelola_penilaian_ta.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/kelola_penilaian_ta.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const finalisasiButtons = document.querySelectorAll('.finalisasi-button');
            finalisasiButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const form = this.closest('form');
                    if (confirm('Apakah Anda yakin ingin memfinalisasi nilai ini?')) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@stop