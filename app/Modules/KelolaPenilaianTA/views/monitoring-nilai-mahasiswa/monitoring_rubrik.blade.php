@extends('adminlte::page')

@section('title', 'Monitoring Rubrik')

@section('content_header')
    <h1 class="mb-0">Rubrik Penilaian {{ $kategori->nama_fta }}</h1>
    <div>
        @php
            $routeName = Route::currentRouteName();
        @endphp

        @if($routeName === 'monitoring.rubrik.mahasiswa')
            {{-- Jalur dari Mahasiswa --}}
            @component('KelolaPenilaianTA.views.components.breadcrumb', [
                'links' => [
                    ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                    ['url' => route('monitoring.mahasiswa'), 'label' => 'Informasi Penilaian Mahasiswa'],
                    ['url' => '', 'label' => 'Detail Rubrik']
                ]
            ])
            @endcomponent

        @elseif($routeName === 'monitoring.rubrik.dosen')
            {{-- Jalur dari Dosen Pembimbing --}}
            @component('KelolaPenilaianTA.views.components.breadcrumb', [
                'links' => [
                    ['url' => route('beranda.get'), 'label' => 'Beranda'],
                    ['url' => route('monitoring.dosen.pembimbing'), 'label' => 'Monitoring Dosen Pembimbing'],
                    ['url' => '', 'label' => 'Detail Rubrik']
                ]
            ])
            @endcomponent
        @endif
    </div>
@stop


@section('content')
<div class="card mb-4">
    <!-- /.card-header -->
    <div class="card-body p-4">
        <div class="table-container">
            <table class="table text-center">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th style="min-width: 200px;" rowspan="2">Detail Kriteria</th>
                        <th style="min-width: 200px;" colspan="{{ count($rentangNilai) }}">Rentang Penilaian</th>
                    </tr>
                    <tr class="bg-dark text-white">
                        @foreach ($rentangNilai as $nilai)
                            <th style="min-width: 200px;">
                                ≥ {{ $nilai->batas_bawah }} - {{ $nilai->batas_atas }} ({{ $nilai->id_nilai }})
                            </th>
                        @endforeach
                    </tr>
                </thead>                
                <tbody>
                    @foreach ($namaKriteria as $index => $kriteria)
                        <!-- Tampilkan Nama Kriteria -->
                        <tr>
                            <td colspan="{{ count($rentangNilai) + 1 }}" class="bg-light text-left">
                                <strong>{{ $index + 1 }}. {{ $kriteria->nama_kriteria }} ({{ $kriteria->bobot_kriteria }}%)</strong>
                            </td>
                        </tr>
                
                        <!-- Tampilkan Nama Rubrik -->
                        @foreach ($kriteria->rubrik as $rubrik)
                            <tr>
                                <td class="text-left">{{ $rubrik->nama_rubrik }}</td>
                                @foreach ($rentangNilai as $nilai)
                                    <td class="text-left">{{ $rubrik->detail[$nilai->id_nilai] }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>                         
            </table>
        </div>
    </div>
    <!-- /.card-body -->
  </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/monitoring_nilai_mahasiswa.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@stop