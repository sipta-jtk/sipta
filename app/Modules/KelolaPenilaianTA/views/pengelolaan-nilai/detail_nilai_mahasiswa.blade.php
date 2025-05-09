@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai')

@section('content_header')
    <div class="container-fluid p-3">
        {{-- TBD perbaiki breadcrumb --}}   
        
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => route('beranda.get'), 'label' => 'Beranda'],
                ['url' => route('kelola.penilaian'), 'label' => 'Kelola Nilai'],
                ['url' => '', 'label' =>  'Data' ]
                ]
                ])
        @endcomponent
        
        <!-- Judul Halaman -->
        <h1 class="mb-0">Detail Nilai {{ $detailInformasiFta->nama_fta }} <br /> {{ $detailInformasiFta->prodi->nama_prodi }}</h1>
    </div>
@stop

@section('content')
    <div class="card p-4">
        <!-- Button Import From Excel -->
        @if (in_array(strtolower($detailInformasiFta->nama_fta), ['seminar ii']))
            <div class="mb-3">
                <form action="{{ route('import.nilai') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control d-inline-block w-auto" required>
                    <button type="submit" class="btn btn-success">Impor dari Excel</button>
                </form>
            </div>
        @endif
        {{-- Tabel Scrollable --}}
        <div>
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
                        @if (in_array(strtolower($detailInformasiFta->nama_fta), ['seminar iii', 'sidang akhir']))    
                            <th colspan="3" style="width: 10%;">Feedback</th>
                        @endif
                        @if (in_array(strtolower($detailInformasiFta->nama_fta), ['seminar ii']))    
                            <th rowspan="2" class="align-middle">Aksi</th>
                        @endif
                    </tr>
                    <tr class="bg-secondary text-white">
                        <th style="width: 2%;">P1</th>
                        <th style="width: 2%;">P2</th>
                        <th style="width: 2%;">P3</th>
                        @if (in_array(strtolower($detailInformasiFta->nama_fta), ['seminar iii', 'sidang akhir']))  
                            <th style="width: 2%;">P1</th>
                            <th style="width: 2%;">P2</th>
                            <th style="width: 2%;">P3</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                        $grouped = $detailNilaiMahasiswa->groupBy('id_kota');
                        // Log::info('Grouped Data: '. json_encode($grouped, JSON_PRETTY_PRINT));
                    @endphp
                
                    @foreach ($grouped as $idKota => $mahasiswaKelompok)
                        {{ Log::info('Kelompok Mahasiswa: '. json_encode($mahasiswaKelompok, JSON_PRETTY_PRINT)); }}
                        <tr>
                            <td class="text-center align-middle">{{ $no++ }}</td>
                            
                            <td class="text-center align-middle d-flex flex-column p-0" style="height: 100%;">
                                @foreach ($mahasiswaKelompok as $index => $data)
                                    <div class="border-bottom w-100 flex-fill d-flex align-items-center justify-content-center">
                                        {{ $data->user->nama }}
                                    </div>
                                @endforeach
                            
                                {{-- Tambahkan div kosong jika jumlah elemen kurang dari 3 --}}
                                @for ($i = count($mahasiswaKelompok); $i < 3; $i++)
                                    <div class="w-100 flex-fill d-flex align-items-center justify-content-center">
                                        &nbsp; <!-- Kosong -->
                                    </div>
                                @endfor
                            </td>
                
                            <td class="text-center align-middle">{{ $data->kota->nama_kota }}</td>
                                                
                            @php
                                $pengujiList = $data->nilaiKategori->pluck('dosen.id_dosen')->toArray();
                                while (count($pengujiList) < 3) {
                                    $pengujiList[] = '-';
                                }
                            @endphp

                            {{-- Penguji --}}
                            {{-- @foreach ($pengujiList as $penguji)
                                    <td rowspan="{{ count($mahasiswaKelompok) }}" class="text-center align-middle"> {{ $penguji }}</td>
                                @endif
                            @endforeach --}}
                               {{-- Penguji --}}
                            @foreach ($pengujiList as $penguji)
                                <td class="text-center align-middle"> 
                                    {{ $penguji }}
                                </td>
                            @endforeach
                
                            {{-- Nilai --}}
                            @php
                                $nilaiList = $data->nilaiKategori->pluck('nilai')->toArray();
                                while (count($nilaiList) < 3) {
                                    $nilaiList[] = 0;
                                }
                            @endphp
                            
                            @foreach ($nilaiList as $nilai)
                                <td class="text-center align-middle">
                                    {{ $nilai }}
                                </td>
                            @endforeach

                            {{-- Feedback --}}
                            @php
                                $feedbackList = $data->kota->detailFeedback->pluck('feedback')->toArray();
                                while (count($feedbackList) < 3) {
                                    $feedbackList[] = '-';
                                }
                            @endphp
                            @foreach ($feedbackList as $feedback)
                                <td class="text-center align-middle">
                                    <i class="far fa-check-square text-success"></i>
                                    {{-- <i class="far fa-square text-muted"></i> Kotak kosong --}}
                                    {{-- <i class="far fa-check-square text-success"></i> Centang hijau --}}
                                </td>
                            @endforeach

                            @if ($index == 0 && in_array(strtolower($detailInformasiFta->nama_fta), ['seminar ii']))
                                <td class="text-center align-middle">
                                    @if ($data->kota->detailFeedback->first()?->status_penilaian_dosen ?? 'draft' == 'draft')
                                        <a href="{{ route('pengisian.nilai', ['namaFta' => $namaFta, 'idKota' => $data->id_kota, 'idProdi' => $data->id_prodi]) }}" class="btn btn-primary w-100">Nilai</a>
                                    @else
                                        <a href="{{ route('pengisian.nilai', ['namaFta' => $namaFta, 'idKota' => $data->id_kota, 'idProdi' => $data->id_prodi]) }}" class="btn btn-primary w-100">Nilai</a>
                                    @endif
                                </td>
                            @endif
                            
                            <td>
                                <a href="{{ route('pengisian.nilai', ['namaFta' => $namaFta, 'idKota' => $data->id_kota, 'idProdi' => $data->id_prodi]) }}" class="btn btn-primary w-100">Nilai</a>
                            </td>

                            <!-- <td class="d-flex flex-column gap-1">
                                <a href="{{ route('pengisian.nilai', ['namaFta' => $namaFta, 'idKota' => $data->id_kota, 'idProdi' => $data->id_prodi]) }}" class="btn btn-primary w-100">Nilai</a>
                                <a href="{{ route('pengisian.masukan', ['namaFta' => $namaFta, 'idKota' => $data->id_kota, 'idProdi' => $data->id_prodi]) }}" class="btn btn-primary w-100">Masukan</a>
        
                                @if (($data->nilaiKategori->first()?->status_penilaian_dosen ?? 'draft') == 'dipublikasikan')
                                    <form action="{{ route('kelola.penilaian.toggle-publish', ['namaFta' => $namaFta, 'idKota' => $data->id_kota, 'action' => 'unpublish']) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger w-100">Batal Publikasi</button>
                                    </form>
                                @else
                                    <form action="{{ route('kelola.penilaian.toggle-publish', ['namaFta' => $namaFta, 'idKota' => $data->id_kota, 'action' => 'publish']) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">Publikasi</button>
                                    </form>
                                @endif
                            </td> -->
                        </tr>
                    @endforeach
                </tbody>                
            </table>
        </div>

        <div class="d-flex justify-content-between mt-1">
            <div id="infoControls"></div> <!-- Placeholder untuk info -->
            <div id="paginationControls"></div> <!-- Placeholder untuk pagination -->
        </div>
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