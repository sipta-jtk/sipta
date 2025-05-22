@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai')

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Judul Halaman -->
        <h1 class="mb-0">Detail Nilai {{ $detailInformasiFta->nama_fta }} <br /> {{ $detailInformasiFta->prodi->nama_prodi }}</h1>
        
        {{-- TBD perbaiki breadcrumb --}}   
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => route('beranda.get'), 'label' => 'Beranda'],
                ['url' => route('kelola.penilaian'), 'label' => 'Kelola Nilai'],
                ['url' => '', 'label' =>  'Data' ]
                ]
                ])
        @endcomponent
        
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
            <table id="alokasiTable" class="table text-center table-stripped table-hover">
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
                    @endphp
                
                    @foreach ($grouped as $idKota => $mahasiswaKelompok)
                        @foreach ($mahasiswaKelompok as $mahasiswa)
                            <tr>
                                <td class="align-middle text-center">{{ $no++ }}</td>
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

                                {{-- Feedback --}}
                                @php
                                    // Ambil daftar feedback dari mahasiswa dan kelompokkan berdasarkan nip
                                    $feedbackGroupedByNip = collect($mahasiswa->kota->detailFeedback ?? [])->groupBy('nip');
                                @endphp
                                
                                {{-- Iterasi untuk setiap nip --}}
                                @foreach ($feedbackGroupedByNip as $nip => $feedbacks)
                                    <td class="align-middle text-center">
                                        <i class="fas fa-check-square text-success"></i> {{-- Centang hijau jika ada feedback untuk nip ini --}}
                                    </td>
                                @endforeach
                                
                                {{-- Jika jumlah nip kurang dari 3, tambahkan kolom kosong --}}
                                @for ($i = $feedbackGroupedByNip->count(); $i < 3; $i++)
                                    <td class="align-middle text-center">
                                        <i class="far fa-square text-muted"></i> {{-- Centang kosong jika tidak ada feedback --}}
                                    </td>
                                @endfor

                                {{-- @php
                                    // Ambil data nilaiKategori untuk user yang sedang login
                                    $nilaiKategoriUser = $mahasiswa->nilaiKategori
                                        ->where('nip', auth()->user()->username)
                                        ->first();
                                @endphp
                                
                                @if (
                                    !$nilaiKategoriUser || $nilaiKategoriUser->status_penilaian_dosen === 'draf'
                                )
                                    <td class="align-middle text-center d-flex flex-column gap-1">
                                        <a href="{{  route('pengisian.nilai', ['namaFta' => $namaFta, 'idKota' => $mahasiswa->id_kota, 'idProdi' => $mahasiswa->id_prodi]) }}" class="btn btn-primary btn-sm">
                                            Nilai
                                        </a>
                                        <a href="{{  route('pengisian.masukan', ['namaFta' => $namaFta, 'idKota' => $mahasiswa->id_kota, 'idProdi' => $mahasiswa->id_prodi]) }}" class="btn btn-primary btn-sm">
                                            Masukan
                                        </a>
                                        <form action="{{  route('kelola.penilaian.toggle-publish', ['namaFta' => $namaFta, 'idKota' => $mahasiswa->id_kota, 'action' => 'publish']) }}" method="POST" class="w-100 mt-1">
                                            @csrf
                                            <input type="hidden" name="id_kota" value="{{ $mahasiswa->id_kota }}">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                Publikasikan
                                            </button>
                                        </form>
                                    </td>
                                @else
                                    <td class="align-middle text-center d-flex flex-column gap-1">
                                        <a href="{{  route('pengisian.nilai', ['namaFta' => $namaFta, 'idKota' => $mahasiswa->id_kota, 'idProdi' => $mahasiswa->id_prodi]) }}" class="btn btn-primary btn-sm">
                                            Lihat Nilai
                                        </a>
                                        <a href="{{  route('pengisian.masukan', ['namaFta' => $namaFta, 'idKota' => $mahasiswa->id_kota, 'idProdi' => $mahasiswa->id_prodi]) }}" class="btn btn-primary btn-sm">
                                            Lihat Masukan
                                        </a>
                                        <form action="{{  route('kelola.penilaian.toggle-publish', ['namaFta' => $namaFta, 'idKota' => $mahasiswa->id_kota, 'action' => 'unpublish']) }}" method="POST" class="w-100 mt-1">
                                            @csrf
                                            <input type="hidden" name="id_kota" value="{{ $mahasiswa->id_kota }}">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                Batalkan Publikasi
                                            </button>
                                        </form>
                                    </td>
                                @endif --}}
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