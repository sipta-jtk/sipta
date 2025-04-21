@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai')

@section('content_header')
    <h1>Rekapitulasi Nilai Sidang</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tabel Nilai Sidang</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dummy.seminar-2.simpan-nilai') }}" method="POST">
                @csrf
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kriteria Penilaian Penguji</th>
                            <th>Bobot Nilai</th>
                            <th>Rentang Nilai</th>
                            <th colspan="3">Nilai Perorangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                Kejelasan isi dokumen : 
                                <ul>
                                    <li>kejelasan kaitan antar bab/sub kajian (hubungan sebab akibat/reasoning, rasionalitas),</li>
                                    <li>kesesuaian dan ketepatan penggunaan metodologi dan modelling tools,</li>
                                    <li>kejelasan dan kesesuaian studi pustaka beserta daftar pustakanya,</li>
                                    <li>tata tulis laporan.</li>
                                </ul>
                            </td>
                            <td>40 %</td>
                            <td>0 - 100</td>
                            <td><input type="number" class="form-control" name="nilai1[]" min="0" max="100"></td>
                            <td><input type="number" class="form-control" name="nilai2[]" min="0" max="100"></td>
                            <td><input type="number" class="form-control" name="nilai3[]" min="0" max="100"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                Presentasi : 
                                <ul>
                                    <li>materi presentasi,</li>
                                    <li>kejelasan presentasi & kemampuan membangkitkan minat pemirsa,</li>
                                    <li>penggunaan alat bantu,</li>
                                    <li>ketepatan waktu,</li>
                                    <li>kebebasan dari catatan.</li>
                                </ul>
                            </td>
                            <td>20 %</td>
                            <td>0 - 100</td>
                            <td><input type="number" class="form-control" name="nilai1[]" min="0" max="100"></td>
                            <td><input type="number" class="form-control" name="nilai2[]" min="0" max="100"></td>
                            <td><input type="number" class="form-control" name="nilai3[]" min="0" max="100"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Tanya Jawab (penguasaan materi terkait tugas yang dikerjakan)</td>
                            <td>40 %</td>
                            <td>0 - 100</td>
                            <td><input type="number" class="form-control" name="nilai1[]" min="0" max="100"></td>
                            <td><input type="number" class="form-control" name="nilai2[]" min="0" max="100"></td>
                            <td><input type="number" class="form-control" name="nilai3[]" min="0" max="100"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script> console.log('Hi!'); </script>
@stop