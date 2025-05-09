@extends('adminlte::page')

@section('title', 'Berita Acara Pelaksanaan Sidang TA')

@section('content_header')
    <h1 class="mb-3">Berita Acara Pelaksanaan Sidang TA</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Berita Acara Pelaksanaan Sidang TA']
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 8%;">KoTA</th>
                            <th style="width: 8%;">NIM</th>
                            <th style="width: 13%;">Nama Mahasiswa</th>
                            <th style="width: 10%;">Tanggal</th>
                            <th style="width: 8%;">Ruangan</th>
                            <th style="width: 1%;">Sesi</th>
                            <th style="width: 13%;">Status Kehadiran</th>
                            <th style="width: 10%;">Dokumentasi</th>
                            <th style="width: 10%;">Batas Revisi</th>
                            <th style="width: 13%;">Status Kelulusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beritaAcaraSidangTA as $index => $item)
                            <tr>
                                <td>{{ $item->penjadwalan->kota->nama_kota ?? '-' }}</td>
                                <td>{{ $item->user->mahasiswa->nim ?? '-' }}</td>
                                <td>{{ $item->user->nama ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->translatedFormat ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->id_ruangan ?? '-' }}</td>
                                <td>{{ $item->penjadwalan->sesi ?? '-' }}</td>
                                <td>
                                    @if($item->status_hadir == 'hadir')
                                        <button class="btn btn-success btn-md my-1 w-100" disabled style="opacity: 1">Hadir</button>
                                    @elseif($item->status_hadir == 'tidak_hadir')
                                        <button class="btn btn-danger btn-md my-1 w-100" disabled style="opacity: 1">Absen</button>
                                    @elseif($item->status_hadir == 'belum_absen' && $sekarang->isSameDay($item->penjadwalan->tanggal))
                                        <form action="{{ route('presensi.hadir') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id_kehadiran" value="{{ $item->id_kehadiran }}">
                                            <input type="hidden" name="status_hadir" value="hadir">
                                            <button type="submit" class="btn btn-info btn-md my-1 w-100">Absensi</button>
                                        </form>
                                    @elseif($item->status_hadir == 'belum_absen' && $sekarang->gt(($item->penjadwalan->tanggal)))
                                        <button class="btn btn-danger btn-md my-1 w-100" disabled style="opacity: 1">Absen</button>
                                    @else
                                        <button class="btn btn-warning btn-md my-1 w-100" disabled style="opacity: 1">Belum Absen</button>
                                    @endif
                                </td>
                                <td style="width: 250px;">
                                    @if($item->foto_sidang || $item->foto_sidang == null)
                                        <div class="mb-2">
                                            <strong>File Terupload:</strong>
                                            <a href="{{ asset('storage/' . $item->foto_sidang) }}" target="_blank">
                                                {{ \Illuminate\Support\Str::limit(basename($item->foto_sidang),13) }}
                                            </a>
                                        </div>
                                    <button class="btn btn-primary btn-md my-1 w-100" 
                                        data-toggle="modal" 
                                        data-target="#modalDokumentasi{{ $item->id_kehadiran }}">
                                        <i class=""></i> Unggah File
                                    </button>
                                    @endif
                                </td>
                                <td>
                                    @if($item->batas_revisi)
                                      {{ $item->batas_revisi_formatted }}
                                    @else
                                    <button class="btn btn-primary btn-md my-1 w-100" 
                                        data-toggle="modal" 
                                        data-target="#modalBatasRevisi{{ $item->id_kehadiran }}">
                                    <i class=""></i> Isi Tanggal
                                    </button>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status_kelulusan == 'pending')
                                        <!-- Tombol untuk membuka modal -->
                                        <button type="button" class="btn btn-primary btn-md my-1 w-100" data-toggle="modal" data-target="#modalStatusKelulusan{{ $item->id_kehadiran }}">
                                            Isi Status
                                        </button>
                                    @else
                                        {{ $item->status_kelulusan_formatted }}  
                                    @endif
                                </td>
                            </tr>
                            <!-- Modal untuk mengisi status kelulusan -->
                            <x-adminlte-modal id="modalStatusKelulusan{{ $item->id_kehadiran }}" 
                                title="Isi Status Kelulusan" 
                                theme="blue" 
                                icon="fas fa-graduation-cap"
                                size="md">
                                <form action="{{ route('simpan.status.kelulusan') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_kehadiran" value="{{ $item->id_kehadiran }}">
                                    
                                    <div class="px-3"> <!-- Padding horizontal sesuai standar -->

                                        <!-- Alert Peringatan -->
                                        <div class="alert alert-info alert-dismissible">
                                            <h5><i class="icon fas fa-info-circle"></i> Informasi Penting!</h5>
                                            Pastikan status kelulusan sudah diverifikasi dengan benar sebelum disimpan.
                                            Status Kelulusan hanya dapat disimpan <strong>sekali saja</strong>
                                        </div>

                                        <div class="mb-2 form-group">
                                            <label for="status_kelulusan">Status Kelulusan</label>
                                            <x-adminlte-select name="status_kelulusan" required>
                                                <option value="" disabled selected>-- Pilih Status Kelulusan --</option>
                                                <option value="lulus_tanpa_perbaikan_laporan">Lulus Tanpa Perbaikan Laporan</option>
                                                <option value="lulus_dengan_perbaikan_laporan">Lulus Dengan Perbaikan Laporan</option>
                                                <option value="mengulang_sidang_tugas_akhir">Mengulang Sidang Tugas Akhir</option>
                                                <option value="tidak_lulus">Tidak Lulus</option>
                                            </x-adminlte-select>
                                        </div>
                                    </div>

                                    <div class="d-flex pt-3 justify-content-end">
                                        <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal"
                                        class="mx-1"/>
                                        <x-adminlte-button type="submit" theme="success" label="Simpan"
                                        class="mx-1"/>
                                    </div>
                                        <x-slot name="footerSlot"> </x-slot>
                                </form>
                            </x-adminlte-modal>
                            
                             <!-- Modal untuk upload dokumentasi -->
                             <x-adminlte-modal id="modalDokumentasi{{ $item->id_kehadiran }}" 
                                title="Unggah Dokumentasi Sidang" 
                                theme="blue"
                                icon="fas fa-camera"
                                size="md">
                                <form action="{{ route('presensi.dokumentasi') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id_kehadiran" value="{{ $item->id_kehadiran }}">
                                    <!-- Alert Peringatan -->
                                    <div class="alert alert-info alert-dismissible">
                                        <h5><i class="icon fas fa-info-circle"></i> Informasi Penting!</h5>
                                        <li>Hanya <strong>satu file</strong> yang diperbolehkan</li>
                                        <li>Format file: <strong>JPG, PNG, atau PDF</strong></li>
                                        <li>Ukuran maksimal: <strong>5MB</strong></li>
                                        <li>File sebelumnya akan diganti jika upload baru</li>
                                    </div>
                                    <div class="form-group">
                                        <label for="dokumentasi{{ $item->id_kehadiran }}" class="btn btn-default btn-block text-left" id="fileLabel{{ $item->id_kehadiran }}">
                                            <i class="fas fa-paperclip mr-2"></i>
                                            @if($item->foto_sidang)
                                                {{ basename($item->foto_sidang) }}
                                            @else
                                                Pilih file...
                                            @endif
                                        </label>
                                        <input type="file" 
                                               class="d-none" 
                                               name="dokumentasi" 
                                               id="dokumentasi{{ $item->id_kehadiran }}"
                                               accept=".jpg,.jpeg,.png,.pdf" 
                                               required>
                                        <small class="form-text text-muted">
                                            Klik area di atas untuk memilih file
                                        </small>
                                    </div>
                                    <div class="d-flex pt-3 justify-content-end">
                                        <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal"
                                        class="mx-1"/>
                                        <x-adminlte-button type="submit" theme="success" label="Simpan"
                                        class="mx-1"/>
                                    </div>
                                    <x-slot name="footerSlot"></x-slot>
                                </form>
                            </x-adminlte-modal>

                            <x-adminlte-modal id="modalBatasRevisi{{ $item->id_kehadiran }}" 
                                title="Set Batas Revisi" 
                                theme="blue"
                                icon="fas fa-calendar-day"
                                size="md">
                                <form action="{{ route('simpan.batas.revisi') }}" method="POST" id="formBatasRevisi{{ $item->id_kehadiran }}">
                                    @csrf
                                    <input type="hidden" name="id_kehadiran" value="{{ $item->id_kehadiran }}">
                                    <!-- Alert Peringatan -->
                                    <div class="alert alert-info alert-dismissible">
                                        <h5><i class="icon fas fa-info-circle"></i> Informasi Penting!</h5>
                                        Pastikan tanggal batas revisi sudah benar sebelum disimpan.
                                        Batas Revisi hanya dapat disimpan <strong>sekali saja</strong>
                                    </div>
                                    {{-- <div class="form-group">
                                        <label>Tanggal Batas Revisi:</label>
                                        <input type="date" name="batas_revisi" class="form-control" 
                                            value="{{ $item->batas_revisi }}" required>
                                    </div> --}}
                                    <div class="form-group">
                                        <label class="h6">Tanggal Batas Revisi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="date" name="batas_revisi" 
                                                   class="form-control form-control-lg"
                                                   placeholder="-- Pilih Tanggal --"
                                                   onfocus="this.showPicker()"
                                                   value="{{ $item->batas_revisi }}"
                                                   required>
                                        </div>
                                        <small class="form-text text-muted">
                                            Klik untuk memilih tanggal
                                        </small>
                                    </div>
                                </form>
                                <div class="d-flex pt-3 justify-content-end">
                                    <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal"
                                    class="mx-1"/>
                                    <x-adminlte-button type="submit" theme="success" label="Simpan" form="formBatasRevisi{{ $item->id_kehadiran }}"
                                    class="mx-1"/>
                                </div>
                                <x-slot name="footerSlot"> </x-slot>
                            </x-adminlte-modal>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script>
    // Fungsi untuk semua file input
    function handleFileInput() {
        document.querySelectorAll('input[type="file"].d-none').forEach(input => {
            input.addEventListener('change', function() {
                const label = this.previousElementSibling;
                const fileName = this.files[0]?.name || 'Pilih file...';
                label.innerHTML = `<i class="fas fa-paperclip mr-2"></i>${fileName}`;
            });
        });
    }

    // Jalankan saat modal terbuka
    $(document).on('shown.bs.modal', '.modal', handleFileInput);
    </script>
@stop