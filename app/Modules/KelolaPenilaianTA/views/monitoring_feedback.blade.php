@extends('adminlte::page')

@section('title', 'Monitoring Feedback')

@section('content_header')
    <h1>Monitoring Mahasiswa</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-blue p-0">
            <h3 class="card-title p-3">Detail Usulan Tugas Akhir</h3>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th class="w-25 p-2">Kode KoTA</th>
                        <td class="w-75 p-2">103</td>
                    </tr>
                    <tr>
                        <th class="w-25 p-2">Judul Topik</th>
                        <td class="w-75 p-2">Pengembangan Aplikasi SipTa</td>
                    </tr>
                    <tr>
                        <th class="w-25 p-2">Kategori</th>
                        <td class="w-75 p-2">Seminar 1</td>
                    </tr>
                    <tr>
                        <th class="w-25 p-2">Hari / Tanggal</th>
                        <td class="w-75 p-2">Selasa / 28 Oktober 2026</td>
                    </tr>
                    <tr>
                        <th class="w-25 p-2">Data Mahasiswa</th>
                    </tr>
                    <tr>
                        <th class="w-25 p-2">Anggota 1</th>
                        <td class="w-75 p-2">221524049 - Muhammad Daffa</td>
                    </tr>
                    <tr>
                        <th class="w-25 p-2">Anggota 2</th>
                        <td class="w-75 p-2">221524048 - Muhammad Agim</td>
                    </tr>
                    <tr>
                        <th class="w-25 p-2">Data Dosen Pembimbing</th>
                    </tr>
                    <tr>
                        <th class="w-25 p-2">Dosen 1</th>
                        <td class="w-75 p-2">11111111 - Bu Sri</td>
                    </tr>
                    <tr>
                        <th class="w-25 p-2">Dosen 2</th>
                        <td class="w-75 p-2">22222222 - Pak Joe</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex p-0">
          <h3 class="card-title text-bold p-3">Isi Masukkan</h3>
          <ul class="nav nav-pills ml-auto p-2">
            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">RA</a></li>
            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">JL</a></li>
            <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">IS</a></li>
          </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <a class="text-bold text-dark">Deskripsi Topik </a>
                <ol>
                  <li>Lorem ipsum dolor sit amet</li>
                  <li>Consectetur adipiscing elit</li>
                  <li>Eget porttitor lorem</li>
                </ol>
                <a class="text-bold text-dark">Porblem Definition </a>
                <ol>
                  <li>Lorem ipsum dolor sit amet</li>
                  <li>Consectetur adipiscing elit</li>
                  <li>Eget porttitor lorem</li>
                </ol>
                <a class="text-bold text-dark">Metodologoi Penyelesaian TA</a>
                <ol>
                  <li>Lorem ipsum dolor sit amet</li>
                  <li>Consectetur adipiscing elit</li>
                  <li>Eget porttitor lorem</li>
                </ol>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
                <a class="text-bold text-dark">Deskripsi Topik </a>
                <ol>
                  <li>Lorem ipsum dolor sit amet</li>
                  <li>Consectetur adipiscing elit</li>
                  <li>Eget porttitor lorem</li>
                </ol>
                <a class="text-bold text-dark">Porblem Definition </a>
                <ol>
                  <li>Lorem ipsum dolor sit amet</li>
                  <li>Consectetur adipiscing elit</li>
                  <li>Eget porttitor lorem</li>
                </ol>
                <a class="text-bold text-dark">Metodologoi Penyelesaian TA</a>
                <ol>
                  <li>Lorem ipsum dolor sit amet</li>
                  <li>Consectetur adipiscing elit</li>
                  <li>Eget porttitor lorem</li>
                </ol>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_3">
                <a class="text-bold text-dark">Deskripsi Topik </a>
                <ol>
                  <li>Lorem ipsum dolor sit amet</li>
                  <li>Consectetur adipiscing elit</li>
                  <li>Eget porttitor lorem</li>
                </ol>
                <a class="text-bold text-dark">Porblem Definition </a>
                <ol>
                  <li>Lorem ipsum dolor sit amet</li>
                  <li>Consectetur adipiscing elit</li>
                  <li>Eget porttitor lorem</li>
                </ol>
                <a class="text-bold text-dark">Metodologoi Penyelesaian TA</a>
                <ol>
                  <li>Lorem ipsum dolor sit amet</li>
                  <li>Consectetur adipiscing elit</li>
                  <li>Eget porttitor lorem</li>
                </ol>
            </div>
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div><!-- /.card-body -->
      </div>
@stop