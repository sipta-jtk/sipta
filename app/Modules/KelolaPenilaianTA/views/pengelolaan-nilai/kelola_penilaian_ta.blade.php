@extends('adminlte::page')

@section('title', 'KelolaPenilaianTA')

@section('content_header')
    <div class="container-fluid p-3">
        <h1 class="mb-0">{{ $data['header'] ?? 'Kelola Penilaian' }}</h1>
        {{-- TBD perbaiki breadcumb --}}
        
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => route('beranda.get'), 'label' => 'Home'],
                ['url' => '', 'label' =>  $data['header'] ?? 'Kelola Penilaian' ]
            ]
        ])
        @endcomponent

    </div>
@stop

@section('content')
    <div class="shadow p-3 mb-5 bg-body rounded">
        <div class="mb-3 d-flex justify-content-end">
            <select id="kategori-filter" class="form-select w-25">
                @foreach ($prodiList as $index => $prodi)
                    <option value="{{ $prodi->nama_prodi }}" {{ $index == 0 ? 'selected' : '' }}>
                        {{ $prodi->nama_prodi }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <table class="table table-bordered text-center table-striped">
            <thead class="bg-brown text-white">
                <tr>
                    <th class="bg-dark">Kategori</th>
                    <th class="bg-dark">Program Studi</th>
                    <th class="bg-dark" style="width: 20%">Aksi</th>
                </tr>
            </thead>
            <tbody id="kategori-table">
                @foreach ($kategoriPenilaian as $index => $fta)
                    <tr class="kategori-row" data-kategori="{{ $fta->prodi->nama_prodi }}" 
                        @if ($index >= 4) hidden @endif>
                        <td class="text-center align-middle">{{ $fta->nama_fta }}</td>
                        <td class="text-center align-middle">{{ $fta->prodi->nama_prodi }}</td>
                        <td>
                            <div class="action d-flex flex-column align-items-center justify-content-center gap-3">
                                <a href="{{ route('kelola.penilaian.detail', ['namaFta' => Str::slug($fta->nama_fta), 'idProdi' => $fta->prodi->id_prodi]) }}" class="btn btn-primary w-100 buka-detail m-0">Buka Detail</a>

                                @if (!in_array(strtolower($fta->nama_fta), ['seminar i', 'seminar ii']))
                                    @if (($fta->kategoriPenilaian->first()?->kunci_penilaian ?? 0) == 1)
                                        <form action="{{ route('kelola.penilaian.toggle-kunci', ['idKategori' => $fta->kategoriPenilaian->first?->id_kategori, 'action' => 'buka']) }}" method="POST" class="w-100">
                                            @csrf
                                            <button type="submit" class="btn btn-danger w-100 m-0">Buka Kunci Penilaian</button>
                                        </form>
                                    @else
                                        <form action="{{ route('kelola.penilaian.toggle-kunci', ['idKategori' => $fta->kategoriPenilaian->first?->id_kategori, 'action' => 'kunci']) }}" method="POST" class="w-100">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100 m-0">Kunci Penilaian</button>
                                        </form>
                                    @endif
                                @endif
                            </div>                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tombol Tampilkan Semua -->
        @if (count($kategoriPenilaian) > 4)
            <div class="d-flex justify-content-center mt-3">
                <button id="show-more-btn" class="btn btn-secondary" onclick="showAllRows()">
                    Tampilkan Semua
                </button>
            </div>
        @endif
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/kelola_penilaian_ta.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="{{ asset('KelolaPenilaianTA/js/kelola_penilaian_ta.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterKategori() {
            let selectedCategory = document.getElementById('kategori-filter').value.toLowerCase();
            let rows = document.querySelectorAll('.kategori-row');
            let visibleCount = 0;

            rows.forEach(row => {
                let kategori = row.getAttribute('data-kategori').toLowerCase();
                
                if (selectedCategory === kategori || selectedCategory === "") {
                    row.hidden = visibleCount >= 4;
                    visibleCount++;
                } else {
                    row.hidden = true;
                }
            });

            let showMoreButton = document.getElementById('show-more-btn');
            if (showMoreButton) {
                showMoreButton.style.display = (visibleCount > 4) ? "block" : "none";
            }
        }

        document.getElementById('kategori-filter').addEventListener('change', filterKategori);
        document.addEventListener("DOMContentLoaded", filterKategori);
    </script>
@stop
