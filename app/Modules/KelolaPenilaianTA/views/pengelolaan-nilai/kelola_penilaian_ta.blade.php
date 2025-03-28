@extends('adminlte::page')

@section('title', 'KelolaPenilaianTA')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/'), 'label' => 'Home'],
                ['url' => '', 'label' =>  $data['header'] ?? 'Kelola Penilaian' ]
            ]
        ])
        @endcomponent

        <h1 class="mb-0">{{ $data['header'] ?? 'Kelola Penilaian' }}</h1>
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
        
        <table class="table table-bordered text-center">
            <thead class="bg-brown text-white">
                <tr>
                    <th class="bg-dark">Kategori</th>
                    <th class="bg-dark">Program Studi</th>
                    <th class="bg-dark">Aksi</th>
                </tr>
            </thead>
            <tbody id="kategori-table">
                @foreach ($kategoriPenilaian as $index => $fta)
                    <tr class="kategori-row" data-kategori="{{ $fta->prodi->nama_prodi }}" 
                        @if ($index >= 4) hidden @endif>
                        <td>{{ $fta->nama_fta }}</td>
                        <td>{{ $fta->prodi->nama_prodi }}</td>
                        <td>
                            <div class="action d-flex flex-row align-items-center justify-content-center"> 
                                @if (!in_array(strtolower($fta->nama_fta), ['seminar i', 'seminar ii']))
                                    <button type="button" class="btn btn-primary me-2">Kunci Penilaian</button>
                                @endif
                                <a href="{{ route('kelola.penilaian.detail', ['namaFta' => Str::slug($fta->nama_fta), 'idProdi' => $fta->prodi->id_prodi]) }}" class="btn btn-primary buka-detail" data-id="{{ $index }}">Buka Detail</a>
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
