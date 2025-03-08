@extends('adminlte::page')

@section('title', 'Monitoring Mahasiswa')

@section('content_header')
    <h1>Monitoring Mahasiswa</h1>
@stop

@section('content')
    <div class="card-KoTA">
        <h4>Informasi KoTA</h4>
        <table class="table table-bordered w-50">
            <tbody>
                <tr>
                    <th class="bg-dark text-white w-25 text-center p-2">Kode KoTA</th>
                    <td class="w-75 p-2">103</td>
                </tr>
                <tr>
                    <th class="bg-dark text-white w-25 text-center p-2">Judul Topik</th>
                    <td class="w-75 p-2">Pengembangan Aplikasi SipTa</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card-Mahasiswa">
        <h4>Data Mahasiswa</h4>
        <table class="table table-bordered w-50">
            <thead>
                <tr class="bg-dark text-white" style="text-align: center;">
                    <th rowspan="2" class="align-middle text-center w-25">NIM</th>
                    <th rowspan="2" class="align-middle text-center w-75">Nama</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>221524049</td>
                    <td>Muhammad Daffa</td>
                </tr>
                <tr>
                    <td>221524048</td>
                    <td>Muhammad Agim</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card-Dosen-Pembimbing">
        <h4>Data Dosen Pembimbing</h4>
        <table class="table table-bordered w-50">
            <thead>
                <tr class="bg-dark text-white"  style="text-align: center;">
                    <th class="align-middle text-center w-25">NIP</th>
                    <th class="align-middle text-center w-75">Nama</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1111111</td>
                    <td>Bu Sri</td>
                </tr>
                <tr>
                    <td>22222222</td>
                    <td>Pak Joe</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card-Progress-Penilaian">
        <h4>Progress Penilaian oleh Dosen</h4>
          <table class="table table-bordered">
            <thead>
              <tr class="bg-dark text-white" style="text-align: center">
                <th>Nama Kategori</th>
                <th>Rubrik</th>
                <th>Status Penilaian</th>
                <th>Detail</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="vertical-align: middle; white-space: nowrap;">Seminar 1</td>
                    <td style="text-align: center;">
                        <button type="button" class="btn btn-primary btn-sm px-3">Lihat Rubrik</button>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <span class="badge bg-success px-3 py-2 rounded-pill">Sudah Dinilai</span>
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="btn btn-primary btn-sm px-3">Lihat Detail</button>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: middle; white-space: nowrap;">Seminar 2</td>
                    <td style="text-align: center;">
                        <button type="button" class="btn btn-primary btn-sm px-3">Lihat Rubrik</button>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <span class="badge px-3 py-2 rounded-pill" style="background-color: #ffc107; color: white;">
                            Sedang Dinilai
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="btn btn-primary btn-sm px-3">Lihat Detail</button>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: middle; white-space: nowrap;">Seminar 3</td>
                    <td style="text-align: center;">
                        <button type="button" class="btn btn-primary btn-sm px-3">Lihat Rubrik</button>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <span class="badge bg-gray px-3 py-2 rounded-pill">Belum Dinilai</span>
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="btn btn-primary btn-sm px-3 disabled">Lihat Detail</button>
                    </td>
                </tr>
            </tbody>            
          </table>
    </div>
@stop