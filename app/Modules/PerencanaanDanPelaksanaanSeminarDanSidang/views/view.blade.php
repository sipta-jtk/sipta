@extends('adminlte::page')

@section('title', 'PerencanaanDanPelaksanaanSeminarDanSidang')

@section('content_header')
    <h1>Presensi Pelaksanaan Seminar 3 dan Sidang TA</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <h3>Presensi Seminar 3</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="w-20">NIM</th>
                            <th class="w-20">MAHASISWA</th>
                            <th class="w-10">KOTA</th>
                            <th class="w-15">TANGGAL</th>
                            <th class="w-15">RUANGAN</th>
                            <th class="w-10">SESI</th>
                            <th class="w-10">STATUS</th>
                            <th class="w-20">DOKUMENTASI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($presensiSeminar3 as $index => $item)
                            @php
                                $waktuSidang = new DateTime($item['waktu_sidang']);
                                $setengahJamSebelumSidang = (clone $waktuSidang)->modify('-30 minutes');
                                $empatJamSetelahSidang = (clone $waktuSidang)->modify('+4 hours');
                                $statusKehadiran = session("status_hadir_{$item['id_kehadiran']}", $item['status_hadir']);
                                $dokumentasi = session("dok_{$item['id_kehadiran']}", $item['dokumentasi']);
                            @endphp

                            <tr>
                                <td>{{ $item['nim'] }}</td>
                                <td>{{ $item['mahasiswa'] }}</td>
                                <td>{{ $item['id_kota'] }}</td>
                                <td>{{ $item['tanggal'] }}</td>
                                <td>{{ $item['ruangan'] }}</td>
                                <td>{{ $item['sesi'] }}</td>
                                <td>
                                    @if($statusKehadiran == 'hadir')
                                        <button class="btn btn-success btn-sm" disabled>Hadir</button>
                                    @elseif($statusKehadiran == 'absen')
                                        <button class="btn btn-danger btn-sm" disabled>Absen</button>
                                    @elseif($sekarang < $setengahJamSebelumSidang)
                                        <button class="btn btn-warning btn-sm" disabled>Absensi</button>
                                    @elseif($sekarang >= $setengahJamSebelumSidang && $sekarang <= $empatJamSetelahSidang)
                                        <form action="{{ route('presensi.hadir') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id_kehadiran" value="{{ $item['id_kehadiran'] }}">
                                            <button type="submit" class="btn btn-info btn-sm">Absensi</button>
                                        </form>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled>Absen</button>
                                    @endif
                                </td>
                                <td style="width: 250px;">
                                    @if($dokumentasi)
                                        <div class="mb-2">
                                            <strong>File Terupload:</strong>
                                            <a href="{{ asset('storage/' . $dokumentasi) }}" target="_blank">
                                                {{ basename($dokumentasi) }}
                                            </a>
                                        </div>
                                    @endif
                                    @if($errors->has('dokumentasi'))
                                        <div class="alert alert-danger">
                                        {{ $errors->first('dokumentasi') }}
                                         </div>
                                    @endif
                                    <form action="{{ route('presensi.dokumentasi') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id_kehadiran" value="{{ $item['id_kehadiran'] }}">
                                        <div class="form-group">
                                            <input type="file" name="dokumentasi" class="form-control-file form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                            <small class="text-muted">Maksimal 5 MB (JPG, JPEG, PNG, PDF)</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Upload</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h3>Presensi Sidang TA</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="w-20">NIM</th>
                            <th class="w-20">MAHASISWA</th>
                            <th class="w-10">KOTA</th>
                            <th class="w-15">TANGGAL</th>
                            <th class="w-15">RUANGAN</th>
                            <th class="w-10">SESI</th>
                            <th class="w-10">STATUS</th>
                            <th class="w-20">DOKUMENTASI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($presensiSidangTA as $index => $item)
                            @php
                                $waktuSidang = new DateTime($item['waktu_sidang']);
                                $setengahJamSebelumSidang = (clone $waktuSidang)->modify('-30 minutes');
                                $empatJamSetelahSidang = (clone $waktuSidang)->modify('+4 hours');
                                $statusKehadiran = session("status_hadir_{$item['id_kehadiran']}", $item['status_hadir']);
                                $dokumentasi = session("dokumentasi_{$item['id_kehadiran']}", $item['dokumentasi']);
                            @endphp

                            <tr>
                                <td>{{ $item['nim'] }}</td>
                                <td>{{ $item['mahasiswa'] }}</td>
                                <td>{{ $item['id_kota'] }}</td>
                                <td>{{ $item['tanggal'] }}</td>
                                <td>{{ $item['ruangan'] }}</td>
                                <td>{{ $item['sesi'] }}</td>
                                <td>
                                    @if($statusKehadiran == 'hadir')
                                        <button class="btn btn-success btn-sm" disabled>Hadir</button>
                                    @elseif($statusKehadiran == 'absen')
                                        <button class="btn btn-danger btn-sm" disabled>Absen</button>
                                    @elseif($sekarang < $setengahJamSebelumSidang)
                                        <button class="btn btn-warning btn-sm" disabled>Absensi</button>
                                    @elseif($sekarang >= $setengahJamSebelumSidang && $sekarang <= $empatJamSetelahSidang)
                                        <form action="{{ route('presensi.hadir') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id_kehadiran" value="{{ $item['id_kehadiran'] }}">
                                            <button type="submit" class="btn btn-info btn-sm">Absensi</button>
                                        </form>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled>Absen</button>
                                    @endif
                                </td>
                                <td style="width: 250px;">
                                    @if($dokumentasi)
                                        <div class="mb-2">
                                            <strong>File Terupload:</strong>
                                            <a href="{{ asset('storage/' . $dokumentasi) }}" target="_blank">
                                                {{ basename($dokumentasi) }}
                                            </a>
                                        </div>
                                    @endif
                                    @if($errors->has('dokumentasi'))
                                        <div class="alert alert-danger">
                                        {{ $errors->first('dokumentasi') }}
                                         </div>
                                    @endif
                                    <form action="{{ route('presensi.dokumentasi') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id_kehadiran" value="{{ $item['id_kehadiran'] }}">
                                        <div class="form-group">
                                            <input type="file" name="dokumentasi" class="form-control-file form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                            <small class="text-muted">Maksimal 5 MB (JPG, JPEG, PNG, PDF)</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Upload</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h3>Status Kelulusan Sidang TA</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Status Kelulusan</th>
                            <th>Ceklis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Lulus tanpa perbaikan laporan</td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Lulus dengan perbaikan laporan</td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Mengulang Sidang Tugas Akhir</td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Tidak Lulus</td>
                            <td><input type="checkbox"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .form-control-sm {
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        .form-group {
            margin-bottom: 0.5rem;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop