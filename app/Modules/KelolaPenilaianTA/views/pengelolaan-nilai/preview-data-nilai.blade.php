@extends('adminlte::page')

@section('title', 'Preview Data Nilai')

@section('content_header')
@php
    $prefix = env('PREFIX_URL', 'sipta');
@endphp
<h1>Data Import Nilai</h1>
<div>
    @component('KelolaPenilaianTA.views.components.breadcrumb', [
    'links' => [
    ['url' => "/$prefix", 'label' => 'Beranda'],
    ['url' => route('kelola.penilaian'), 'label' => 'Kelola Nilai'],
    ['url' => route('kelola.penilaian.detail', ['namaFta' => $namaFta, 'idProdi' => $idProdi]), 'label' => 'Data'],
    ['url' => '', 'label' => 'Preview Data Nilai']
    ]])
    @endcomponent
</div>
@stop
@section('content')
<a href="{{route('kelola.penilaian.detail', ['namaFta' => $namaFta, 'idProdi' => $idProdi])}}" class="btn btn-primary mb-3">
    <i class="fa fa-back"></i> Kembali
</a>
<div class="card">
    <div class="card-body">

        @if (session('importedData'))
        <form action="{{route('inputBulkNilai', ['namaFta' => $namaFta, 'idProdi' => $idProdi])}}" method="POST">
            @csrf
            <table class="table table-hover table-striped text-center align-middle">
                <thead>
                <tr class="bg-dark text-white">
                    <th rowspan="2" style="width: 3%;">No</th>
                    <th rowspan="2" style="width: 10%;">NIM</th>
                    <th rowspan="2" style="width: 20%;">Nama</th>
                    <th rowspan="2" style="width: 10%;">Kelompok</th>
                    <th colspan="3">Penguji</th>
                    <th colspan="3">Nilai</th>
                </tr>
                <tr class="bg-dark text-white">
                    <th style="width: 7%;">P1</th>
                    <th style="width: 7%;">P2</th>
                    <th style="width: 7%;">P3</th>
                    <th style="width: 7%;">P1</th>
                    <th style="width: 7%;">P2</th>
                    <th style="width: 7%;">P3</th>
                </tr>
                </thead>
                <tbody>
                    @foreach (session('importedData') as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <input type="text" name="data[{{ $index }}][nim]" value="{{ $row['nim'] }}" class="form-control text-center" readonly>
                        </td>
                        <td>
                            <input type="text" name="data[{{ $index }}][nama]" value="{{ $row['nama'] }}" class="form-control" readonly>
                        </td>
                        <td>
                            <input type="text" name="data[{{ $index }}][kelompok]" value="{{ $row['kelompok'] }}" class="form-control text-center" readonly>
                        </td>
                        <td>
                            <input type="text" name="data[{{ $index }}][penguji1]" value="{{ $row['penguji1'] }}" class="form-control text-center" readonly>
                        </td>
                        <td>
                            <input type="text" name="data[{{ $index }}][penguji2]" value="{{ $row['penguji2'] }}" class="form-control text-center" readonly>
                        </td>
                        <td>
                            <input type="text" name="data[{{ $index }}][penguji3]" value="{{ $row['penguji3'] }}" class="form-control text-center" readonly>
                        </td>
                        <td>
                            <input type="number" name="data[{{ $index }}][nilaiPenguji1]" value="{{ $row['nilaiPenguji1'] }}" class="form-control text-center" required>
                        </td>
                        <td>
                            <input type="number" name="data[{{ $index }}][nilaiPenguji2]" value="{{ $row['nilaiPenguji2'] }}" class="form-control text-center" required>
                        </td>
                        <td>
                            <input type="number" name="data[{{ $index }}][nilaiPenguji3]" value="{{ $row['nilaiPenguji3'] }}" class="form-control text-center" required>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>


            <button type="submit" class="btn btn-primary">Simpan ke Database</button>
        </form>
        @else
        <p>Tidak ada data untuk ditampilkan.</p>
        @endif

    </div>
</div>
@endsection