@extends('adminlte::page')

@section('title', 'Monitoring Feedback')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/'), 'label' => 'Home'],
                ['url' => url('kelola-penilaian-ta/monitoring/mahasiswa'), 'label' => 'Informasi Penilaian Mahasiswa'],
                ['url' => '', 'label' => 'Detail Feedback']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Detail Feedback</h1>
    </div>
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
                      <td class="w-75 p-2">{{ $kotaInfo->nama_kota ?? '-' }}</td>
                  </tr>
                  <tr>
                      <th class="w-25 p-2">Judul Topik</th>
                      <td class="w-75 p-2">{{ $kotaInfo->judul_ta ?? '-' }}</td>
                  </tr>
                  <tr>
                    <th class="w-25 p-2">Kategori</th>
                    <td class="w-75 p-2">{{ $ftaPenilaian->nama_fta }}</td>
                </tr>
                  <tr>
                      <th class="w-25 p-2">Tanggal</th>
                      <td class="w-75 p-2">{{ $hariTanggal }}</td>
                  </tr>
                  <tr>
                      <th class="w-25 p-2">Data Mahasiswa</th>
                  </tr>
                  @foreach($mahasiswaList as $index => $mhs)
                  <tr>
                      <th class="w-25 p-2">Anggota {{ $index + 1 }}</th>
                      <td class="w-75 p-2">{{ $mhs->nim }} - {{ $mhs->nama }}</td>
                  </tr>
                  @endforeach
                  <tr>
                      <th class="w-25 p-2">Data Dosen Pembimbing</th>
                  </tr>
                  @foreach($dosenPembimbing as $index => $dosen)
                  <tr>
                      <th class="w-25 p-2">Pembimbing {{ $index + 1 }}</th>
                      <td class="w-75 p-2">{{ $dosen->nip }} - {{ $dosen->nama_dosen }}</td>
                  </tr>
                  @endforeach
                  <tr>
                      <th class="w-25 p-2">Data Dosen Penguji</th>
                  </tr>
                  @foreach($dosenPenguji as $index => $dosen)
                  <tr>
                      <th class="w-25 p-2">Penguji {{ $index + 1 }}</th>
                      <td class="w-75 p-2">{{ $dosen->nip }} - {{ $dosen->nama_dosen }}</td>
                  </tr>
                  @endforeach
              </tbody>
          </table>
      </div>      
    </div>

    <div class="card">
      <div class="card-header d-flex p-0">
          <h3 class="card-title text-bold p-3">Isi Masukkan</h3>
          <ul class="nav nav-pills ml-auto p-2">
            @foreach ($dosenPenguji as $key => $dosen)
                <li class="nav-item">
                    <a class="nav-link {{ $key == 0 ? 'active' : '' }}" href="#tab_{{ $key }}" data-toggle="tab">
                        P{{ $key + 1 }}
                    </a>
                </li>
            @endforeach
        </ul>
      </div><!-- /.card-header -->
  
      <div class="card-body">
          <div class="tab-content">
              @foreach ($dosenPenguji as $key => $dosen)
                  <div class="tab-pane {{ $key == 0 ? 'active' : '' }}" id="tab_{{ $key }}">
                      @foreach ($aspekFeedback as $aspek)
                          <a class="text-bold text-dark">{{ $aspek->nama_aspek_feedback }}</a>
                          <ol>
                              @php
                                  $feedbacks = $detailFeedback->where('id_feedback', $aspek->id_feedback)->where('nip', $dosen->nip);
                              @endphp
                              @if ($feedbacks->isEmpty())
                                  <li>Feedback belum tersedia.</li>
                              @else
                                  @foreach ($feedbacks as $feedback)
                                    {!! $feedback->isi_feedback !!}
                                  @endforeach
                              @endif
                          </ol>
                      @endforeach
                  </div>
                  <!-- /.tab-pane -->
              @endforeach
          </div>
          <!-- /.tab-content -->
      </div><!-- /.card-body -->
  </div>  
@stop