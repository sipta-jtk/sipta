@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/'), 'label' => 'Home'],
                ['url' => url('/kelola-penilaian-ta/pengelolaan-nilai'), 'label' => 'Kelola Nilai'],
                ['url' => '', 'label' =>  'Data' ]
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Detail Nilai {{ $detailInformasiFta->first()->nama_fta }}</h1>
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
                        @if (strtolower($detailInformasiFta->first()->nama_fta) == 'seminar i' || strtolower($detailInformasiFta->first()->nama_fta) == 'seminar ii')
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
                    @foreach ($detailNilaiMahasiswa as $index => $data)
                        <tr>
                            <td> {{ $index + 1 }} </td>
                            <td> {{ $data->user->nama }} </td>
                            <td> {{ $data->kota->nama_kota }} </td>
                            @php
                                $pengujiList = $data->nilaiKategori->pluck('dosen.id_dosen')->toArray();
                                while (count($pengujiList) < 3) {
                                    $pengujiList[] = '-';
                                }
                            @endphp
                        
                            @foreach ($pengujiList as $penguji)
                                <td> {{ $penguji }} </td>
                            @endforeach
                        
                            @php
                                $nilaiList = $data->nilaiKategori->pluck('nilai')->toArray();
                                while (count($nilaiList) < 3) {
                                    $nilaiList[] = 0; // Menggunakan 0 agar tetap valid dalam perhitungan
                                }
                        
                                $rataRata = count($data->nilaiKategori) > 0 
                                    ? array_sum($nilaiList) / count($data->nilaiKategori) 
                                    : 0;
                            @endphp
                        
                            @foreach ($nilaiList as $nilai)
                                <td> {{ $nilai }} </td>
                            @endforeach
                        
                            <td> {{ number_format($rataRata, 2) }} </td>
                        
                            @if (in_array(strtolower($detailInformasiFta->first()->nama_fta), ['seminar i', 'seminar ii', 'seminar iii']))
                                    {{-- TBD jika dosen sudah mengisi nilai maka tombol nilai akan merah --}}
                                    {{-- TBD jika pengisi nilai sudah oleh 3 dosen maka tombol nilai akan merah --}}
                                    {{-- TBD jika dosen sudah menyimpan sebagai draf --}}
                                    <td> 
                                        <a href="{{ route('pengisian.nilai', ['namaFta' => $namaFta,'idKota' => $data->id_kota, 'idProdi' => $data->id_prodi]) }}" class="btn btn-primary">Nilai</a>

                                        @if (($data->kota->detailFeedback->first()?->status_penilaian_dosen ?? 'draft') == 'dipublikasikan')
                                            <form action="{{ route('kelola.penilaian.toggle-publish', ['namaFta' => $namaFta, 'idKota' => $data->id_kota, 'action' => 'unpublish']) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Unpublish</button>
                                            </form>
                                        @else
                                            <form action="{{ route('kelola.penilaian.toggle-publish', ['namaFta' => $namaFta, 'idKota' => $data->id_kota, 'action' => 'publish']) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Publish</button>
                                            </form>
                                        @endif
                                    </td>
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
@stop